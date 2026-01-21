<?php
namespace App\Services;

use App\Models\CommandeModel;
use App\Models\ProduitsCommandeModel;
use App\Models\ProduitModel;

/**
 * Service pour gérer la génération et la manipulation des factures
 */
class FactureService
{
    protected $commandeModel;
    protected $produitsCommandeModel;
    protected $produitModel;

    public function __construct()
    {
        $this->commandeModel = new CommandeModel();
        $this->produitsCommandeModel = new ProduitsCommandeModel();
        $this->produitModel = new ProduitModel();
    }

    /**
     * Génère une facture en JSON pour une commande
     * 
     * @param int|string $idContenu ID du contenu de la commande
     * @return bool True si la facture a été générée avec succès
     */
    public function generer($idContenu): bool
    {
        $commande = $this->commandeModel->getByContenu($idContenu);

        if (!$commande) {
            return false;
        }

        // Convertir en objet si nécessaire
        $commande = is_array($commande) ? (object) $commande : $commande;

        $detailsProduits = $this->getDetailsProduits($idContenu);

        $factureData = [
            'numero_facture' => 'FAC-' . $commande->id . '-' . time(),
            'date' => date('d/m/Y H:i'),
            'client_id' => $commande->commanditaire,
            'adresse' => $commande->adresseLivraison,
            'produits' => $detailsProduits,
            'total_ttc' => $commande->prixTotal,
        ];

        return $this->sauvegarderFacture($idContenu, $factureData);
    }

    /**
     * Récupère les détails des produits d'une commande
     * 
     * @param int|string $idContenu
     * @return array
     */
    private function getDetailsProduits($idContenu): array
    {
        $produitsCommande = $this->produitsCommandeModel->getProduitsCommande($idContenu);
        $detailsProduits = [];

        foreach ($produitsCommande as $pc) {
            $idProduit = is_array($pc) ? $pc['id_produit'] : $pc->id_produit;
            $quantite = is_array($pc) ? $pc['quantite'] : $pc->quantite;

            $produit = $this->produitModel->find($idProduit);

            if ($produit) {
                $nom = is_object($produit) ? $produit->nom : $produit['nom'];

                $prix = $produit -> prix;

                $detailsProduits[] = [
                    'nom' => $nom,
                    'quantite' => $quantite,
                    'prix_uni' => $prix,
                    'sous_total' => $prix * $quantite
                ];
            }
        }

        return $detailsProduits;
    }

    /**
     * Sauvegarde la facture dans un fichier JSON
     * 
     * @param int|string $idContenu
     * @param array $factureData
     * @return bool
     */
    private function sauvegarderFacture($idContenu, array $factureData): bool
    {
        $cheminDossier = WRITEPATH . 'factures/';

        if (!is_dir($cheminDossier)) {
            if (!mkdir($cheminDossier, 0777, true)) {
                return false;
            }
        }

        $nomFichier = 'facture_' . $idContenu . '.json';
        $cheminComplet = $cheminDossier . $nomFichier;

        $json = json_encode($factureData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        return file_put_contents($cheminComplet, $json) !== false;
    }

    /**
     * Charge une facture depuis le fichier JSON
     * 
     * @param int|string $idContenu
     * @return object|null
     */
    public function charger($idContenu)
    {
        $cheminFacture = WRITEPATH . 'factures/facture_' . $idContenu . '.json';

        if (!file_exists($cheminFacture)) {
            return null;
        }

        $contenu = file_get_contents($cheminFacture);
        return json_decode($contenu);
    }

    /**
     * Vérifie si une facture existe
     * 
     * @param int|string $idContenu
     * @return bool
     */
    public function existe($idContenu): bool
    {
        $cheminFacture = WRITEPATH . 'factures/facture_' . $idContenu . '.json';
        return file_exists($cheminFacture);
    }

    /**
     * Supprime une facture
     * 
     * @param int|string $idContenu
     * @return bool
     */
    public function supprimer($idContenu): bool
    {
        $cheminFacture = WRITEPATH . 'factures/facture_' . $idContenu . '.json';

        if (!file_exists($cheminFacture)) {
            return false;
        }

        return unlink($cheminFacture);
    }

    /**
     * Génère le HTML de l'email de facture
     * 
     * @param object $dataFacture
     * @return string
     */
    public function genererEmailHtml($dataFacture): string
    {
        $html = "<h1>Merci pour votre commande chez RounDnPounD !</h1>";
        $html .= "<p>Voici le récapitulatif de votre facture <strong>" . esc($dataFacture->numero_facture) . "</strong> :</p>";
        $html .= "<table border='1' cellpadding='10' style='border-collapse: collapse; width: 100%;'>";
        $html .= "<thead>";
        $html .= "<tr style='background-color: #f2f2f2;'>";
        $html .= "<th>Produit</th><th>Quantité</th><th>Prix Unitaire</th><th>Sous-total</th>";
        $html .= "</tr>";
        $html .= "</thead>";
        $html .= "<tbody>";

        foreach ($dataFacture->produits as $p) {
            $html .= "<tr>";
            $html .= "<td>" . esc($p->nom) . "</td>";
            $html .= "<td style='text-align: center;'>" . esc($p->quantite) . "</td>";
            $html .= "<td style='text-align: right;'>" . number_format($p->prix_uni, 2) . " €</td>";
            $html .= "<td style='text-align: right;'>" . number_format($p->sous_total, 2) . " €</td>";
            $html .= "</tr>";
        }

        $html .= "</tbody>";
        $html .= "</table>";
        $html .= "<h3 style='text-align: right;'>Total TTC : " . number_format($dataFacture->total_ttc, 2) . " €</h3>";
        $html .= "<hr>";
        $html .= "<p><strong>Adresse de livraison :</strong><br>" . nl2br(esc($dataFacture->adresse)) . "</p>";
        $html .= "<hr>";
        $html .= "<p style='color: #666; font-size: 12px; text-align: center;'>Merci de votre confiance !</p>";

        return $html;
    }
}