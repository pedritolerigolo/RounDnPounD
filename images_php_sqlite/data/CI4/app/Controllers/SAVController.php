<?php
namespace App\Controllers;
use App\Models\RemboursementModel;
use App\Models\ProduitModel;
use App\Models\CommandeModel;

class SAVController extends BaseController
{

    public function demandeRemboursement($numFacture)
    {
        // Récupère les données de la facture depuis le fichier JSON
        $cheminFacture = WRITEPATH . 'factures/facture_' . $numFacture . '.json';

        if (!file_exists($cheminFacture)) {
            return redirect()->back()->with('error', "Facture introuvable.");
        }

        $dataFacture = json_decode(file_get_contents($cheminFacture));

        return view('sav/formulaire_remboursement', [
            'facture' => $dataFacture,
            'numFacture' => $numFacture
        ]);
    }

    public function traiterRemboursement()
    {
        $remboursementModel = new RemboursementModel();
        
        // Récupère les données du formulaire
        $factureId = $this->request->getPost('facture_id');
        $nomsSelectionnes = $this->request->getPost('produits_selectionnes');
        $motif = $this->request->getPost('motif');
        $precision = $this->request->getPost('precision');
        $raisonComplete = $motif . " | Détails : " . $precision;

        // Vérifie qu'au moins un produit est sélectionné
        if (empty($nomsSelectionnes)) {
            return redirect()->back()->with('error', "Veuillez cocher au moins un sandwich.");
        }

        // Vérifie l'existence du fichier facture
        $cheminFacture = WRITEPATH . 'factures/facture_' . $factureId . '.json';
        if (!file_exists($cheminFacture)) {
            return redirect()->back()->with('error', "Fichier facture introuvable.");
        }

        $dataFacture = json_decode(file_get_contents($cheminFacture));
        $selectionPourDb = [];
        $montantTotal = 0;

        // Traite chaque produit sélectionné
        foreach ($nomsSelectionnes as $nomProduit) {
            $quantite = (int) $this->request->getPost('quantite_' . urlencode($nomProduit));
            $prixFacture = 0;

            // Recherche le prix unitaire dans la facture
            foreach ($dataFacture->produits as $pJson) {
                if ($pJson->nom === $nomProduit) {
                    $prixFacture = (float) $pJson->prix_uni;
                    break;
                }
            }

            // Prépare les données pour la base de données
            $selectionPourDb[] = [
                'nom_produit' => $nomProduit,
                'quantite' => $quantite
            ];
            $montantTotal += ($prixFacture * $quantite);
        }

        // Crée la demande de remboursement
        if ($remboursementModel->creerDemande($factureId, $selectionPourDb, $montantTotal, $raisonComplete)) {
            return redirect()->to('gestion')->with('message', "Demande enregistrée avec succès !");
        }

        return redirect()->back()->with('error', "Une erreur est survenue.");
    }

    public function listeRemboursements()
    {
        $remboursementModel = new RemboursementModel();

        // Récupère toutes les demandes de remboursement
        $data = [
            'demandes' => $remboursementModel->orderBy('id', 'DESC')->findAll(),
            'title' => 'Gestion des remboursements'
        ];

        return view('admin/list_remboursements', $data);
    }


    public function validerRemboursement($idDemande)
    {
        $rembourseModel = new RemboursementModel();
        $commandeModel = new CommandeModel();

        // Récupère la demande de remboursement
        $demande = $rembourseModel->find($idDemande);
        if (!$demande)
            return $this->response->setJSON(['status' => 'error']);

        $idContenu = $demande['facture'];
        $cheminJson = WRITEPATH . 'factures/facture_' . $idContenu . '.json';

        // Met à jour la facture si elle existe
        if (file_exists($cheminJson)) {
            $facture = json_decode(file_get_contents($cheminJson));
            $produitsRembourses = $rembourseModel->getProduitsAssocies($demande['produits']);

            // Traite chaque produit remboursé
            foreach ($produitsRembourses as $pr) {
                foreach ($facture->produits as $key => $pJson) {
                    if (trim((string) $pJson->nom) === trim((string) $pr->nom_produit)) {
                        // Réduit la quantité et recalcule le sous-total
                        $pJson->quantite -= (int) $pr->quantite;
                        $pJson->sous_total = round($pJson->quantite * $pJson->prix_uni, 2);

                        // Supprime le produit si la quantité est nulle ou négative
                        if ($pJson->quantite <= 0)
                            unset($facture->produits[$key]);
                        break;
                    }
                }
            }

            // Réindexe le tableau des produits
            $facture->produits = array_values($facture->produits);
            
            // Recalcule le total TTC
            $nouveauTotal = 0;
            foreach ($facture->produits as $prod)
                $nouveauTotal += (float) $prod->sous_total;
            $facture->total_ttc = round($nouveauTotal, 2);

            // Supprime la facture si tous les produits sont remboursés
            if (empty($facture->produits)) {
                unlink($cheminJson);
                $commandeModel->suppressionTotale($idContenu);
            } else {
                // Met à jour le fichier facture et le prix dans la base
                file_put_contents($cheminJson, json_encode($facture, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                $commandeModel->updatePrix($idContenu, $facture->total_ttc);
            }
        }

        // Nettoie la demande de remboursement
        $rembourseModel->nettoyerDemande($idDemande, $demande['produits']);
        return $this->response->setJSON(['status' => 'success']);
    }

    public function rejeterRemboursement($idDemande)
    {
        $rembourseModel = new RemboursementModel();

        // Récupère la demande de remboursement
        $demande = $rembourseModel->find($idDemande);
        if (!$demande)
            return $this->response->setJSON(['status' => 'error']);

        // Supprime la demande sans modifier la facture
        $rembourseModel->nettoyerDemande($idDemande, $demande['produits']);

        return $this->response->setJSON(['status' => 'success']);
    }
}