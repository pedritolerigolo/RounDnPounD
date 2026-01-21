<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\ProduitModel;
use App\Models\CommandeModel;
use App\Models\PanierModel;
use App\Models\ProduitsCommandeModel;
use App\Models\StockModel;
use App\Services\FactureService;
use Mpdf\Mpdf;

class CommandeController extends BaseController
{
    protected $produitModel;
    protected $commandeModel;
    protected $panierModel;
    protected $produitsCommandeModel;
    protected $stockModel;
    protected $factureService;

    public function __construct()
    {
        $this->produitModel = new ProduitModel();
        $this->commandeModel = new CommandeModel();
        $this->panierModel = new PanierModel();
        $this->produitsCommandeModel = new ProduitsCommandeModel();
        $this->stockModel = new StockModel();
        $this->factureService = new FactureService();
    }

    public function checkout($idProduit = 'panier')
    {
        $prixTotal = 0;
        $details = "";

        if ($idProduit === 'panier' && auth()->loggedIn()) {
            $userId = auth()->id();
            $panier = $this->panierModel->getPanierUtilisateur($userId);

            if (!$panier) {
                return redirect()->to('/')->with('error', "Panier vide");
            }

            $prixTotal = $this->panierModel->calculerPrixTotal($userId);
            $details = "Commande Panier";
        } else {
            $produit = $this->produitModel->find($idProduit);
            
            if (!$produit) {
                return redirect()->to('/')->with('error', "Produit introuvable");
            }
            
            // Gérer si $produit est un objet ou un array
            $prixTotal = is_object($produit) ? $produit->prix : $produit['prix'];
            $details = "Achat direct : " . (is_object($produit) ? $produit->nom : $produit['nom']);
        }

        $viewData = [
            'prixTotal' => $prixTotal,
            'idProduit' => $idProduit,
            'details' => $details
        ];

        if (auth()->loggedIn()) {
            $db = \Config\Database::connect();
            $userId = auth()->id();
            $viewData['adresses'] = $db->table('Adresse')
                ->where('client', $userId)
                ->get()
                ->getResult();
        }

        return view('commande/addressegetter', $viewData);
    }

    public function traiterCommande()
    {
        $idProduit = $this->request->getPost('idProduit');
        $adresse = $this->request->getPost('adresse');
        $prixTotal = $this->request->getPost('prixTotal');
        
        $userId = auth()->loggedIn() 
            ? auth()->id() 
            : $this->request->getPost('email');

        // Vérifier les stocks
        $produitsAVerifier = $this->preparerProduitsVerification($idProduit, $userId);
        
        if (!$this->stockModel->verifierStocksProduits($produitsAVerifier)) {
            return redirect()->back()->with('error', 
                "Stocks d'ingrédients insuffisants pour honorer la commande !");
        }

        $idContenu = $this->commandeModel->genererIdContenuUnique();

        $db = \Config\Database::connect();
        $db->transStart();

        // Créer les produits de la commande
        if ($idProduit === 'panier') {
            $panier = $this->panierModel->getPanierUtilisateur($userId);
            $items = $this->panierModel->getProduitsAvecDetails($panier->produits);
            $this->produitsCommandeModel->copierDepuisPanier($idContenu, $items);
            $estPanier = true;
        } else {
            $this->produitsCommandeModel->ajouterProduit($idContenu, $idProduit, 1);
            $estPanier = false;
            
            // Normaliser le prix pour achat direct
            $prixTotal = (float) str_replace(
                [',', '€', ' '],
                ['.', '', ''],
                $prixTotal
            );
        }

        // Créer la commande
        $this->commandeModel->creerCommande([
            'commanditaire' => (string) $userId,
            'adresseLivraison' => $adresse,
            'prixTotal' => $prixTotal,
            'contenu' => $idContenu,
            'statut' => 'En attente du paiement',
            'panier' => $estPanier
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 
                "Erreur technique lors de la création de la commande.");
        }

        return redirect()->to("commande/paiement/$idContenu");
    }

    /**
     * Prépare la liste des produits à vérifier pour le stock
     */
    private function preparerProduitsVerification($idProduit, $userId)
    {
        if ($idProduit === 'panier') {
            $panier = $this->panierModel->getPanierUtilisateur($userId);
            if (!$panier) {
                return [];
            }
            // $panier est un objet, donc on utilise -> au lieu de []
            return $this->panierModel->getProduitsAvecDetails($panier->produits);
        } else {
            return [(object) ['id_produit' => $idProduit, 'quantite' => 1]];
        }
    }

    public function paiement($idContenu)
    {
        $commande = $this->commandeModel->getByContenu($idContenu);

        if (!$commande) {
            return redirect()->to('/')->with('error', "Commande introuvable.");
        }

        // Convertir en objet si nécessaire pour la vue
        $commande = is_array($commande) ? (object) $commande : $commande;

        return view('commande/page_paiement', [
            'commande' => $commande
        ]);
    }

    public function confirmerPaiement()
    {
        $idContenu = $this->request->getPost('idContenu');
        $userId = auth()->id();

        // Mettre à jour le statut
        $this->commandeModel->updateStatut($idContenu, 'En préparation');

        // Décrémenter les stocks
        $this->stockModel->decrementerStocksCommande($idContenu);

        // Vider le panier si la commande vient du panier
        if ($this->commandeModel->estCommandePanier($idContenu) && $userId) {
            $this->panierModel->viderPanier($userId);
        }

        // Générer la facture
        $this->factureService->generer($idContenu);
        // $this->envoyerFactureMail($idContenu); // Décommentez si pas proxy de l'IUT

        return view('merci', [
            'orderNumber' => $idContenu
        ]);
    }

    /**
     * Change le statut d'une commande (avancer ou reculer dans le workflow)
     */
    public function changeStatut($idCommande, $direction)
    {
        $etapes = ['En attente du paiement', 'En préparation', 'Expédiée', 'Livrée'];

        $commande = $this->commandeModel->find($idCommande);

        if (!$commande) {
            return redirect()->back()->with('error', "Commande introuvable.");
        }

        // Convertir en objet si nécessaire
        $commande = is_array($commande) ? (object) $commande : $commande;

        $indexActuel = array_search($commande->statut, $etapes);

        if ($indexActuel === false) {
            $indexActuel = 0;
        }

        if ($direction === 'next' && $indexActuel < count($etapes) - 1) {
            $nouvelIndex = $indexActuel + 1;
        } elseif ($direction === 'prev' && $indexActuel > 0) {
            $nouvelIndex = $indexActuel - 1;
        } else {
            return redirect()->back()->with('message', "Impossible de changer le statut dans cette direction.");
        }

        $this->commandeModel->update($idCommande, ['statut' => $etapes[$nouvelIndex]]);

        return redirect()->back()->with('message', "Statut mis à jour : " . $etapes[$nouvelIndex]);
    }

    /**
     * Génère une facture en JSON pour une commande
     * @deprecated Utilisez FactureService::generer() à la place
     */
    private function genereFacture($idContenu)
    {
        return $this->factureService->generer($idContenu);
    }

    /**
     * Envoie la facture par email
     */
    public function envoyerFactureMail($idCommande)
    {
        $email = \Config\Services::email();
        $currentUser = auth()->user();
        $currentUserId = auth()->id();

        $commande = $this->commandeModel->find($idCommande);

        if (!$commande) {
            return redirect()->back()->with('error', "Commande introuvable.");
        }

        // Convertir en objet si nécessaire
        $commande = is_array($commande) ? (object) $commande : $commande;

        // Vérification des permissions
        if (!$currentUser->inGroup('admin')) {
            if ((string) $commande->commanditaire !== (string) $currentUserId) {
                return redirect()->to(site_url('produits'))
                    ->with('error', "Accès non autorisé à cette facture.");
            }
        }

        // Récupérer l'email du destinataire
        $destinataire = $this->getEmailDestinataire($commande->commanditaire);

        if (!$destinataire) {
            return redirect()->back()->with('error', "Impossible de trouver l'adresse email.");
        }

        // Charger la facture avec le service
        $dataFacture = $this->factureService->charger($commande->contenu);

        if (!$dataFacture) {
            return redirect()->back()->with('error', "Le fichier de facture est introuvable.");
        }

        // Générer le message HTML avec le service
        $messageHtml = $this->factureService->genererEmailHtml($dataFacture);

        // Configurer et envoyer l'email
        $email->setTo($destinataire);
        $email->setSubject("Votre facture RounDnPounD - " . $dataFacture->numero_facture);
        $email->setMessage($messageHtml);
        $email->setMailType('html');

        if ($email->send()) {
            return redirect()->back()->with('message', "La facture a été envoyée avec succès.");
        } else {
            return redirect()->back()->with('error', "Erreur lors de l'envoi : " . $email->printDebugger());
        }
    }

    /**
     * Récupère l'email du destinataire selon son type (user_id ou email direct)
     */
    private function getEmailDestinataire($commanditaire)
    {
        if (ctype_digit((string) $commanditaire)) {
            $db = \Config\Database::connect();
            $user = $db->table('clients')
                ->select('adresse_mail')
                ->where('clients.user_id', $commanditaire)
                ->get()
                ->getRow();
            
            return $user ? $user->adresse_mail : null;
        } else {
            return $commanditaire; // Email direct
        }
    }

    /**
     * Consulte les détails d'une commande (facture)
     */
    public function consulterCommande($idCommande)
    {
        $currentUserId = auth()->id();
        $currentUser = auth()->user();

        $commande = $this->commandeModel->find($idCommande);

        if (!$commande) {
            return redirect()->back()->with('error', "Commande introuvable.");
        }

        // Convertir en objet si nécessaire
        $commande = is_array($commande) ? (object) $commande : $commande;

        // Vérification des permissions
        if (!$currentUser->inGroup('admin') && (string) $commande->commanditaire !== (string) $currentUserId) {
            return redirect()->to('/')->with('error', "Accès non autorisé.");
        }

        // Charger la facture avec le service
        $dataFacture = $this->factureService->charger($commande->contenu);

        if (!$dataFacture) {
            return redirect()->back()->with('error', "Les détails de cette commande ne sont plus disponibles.");
        }

        return view('gestion/detail_facture', [
            'facture' => $dataFacture,
            'statut' => $commande->statut,
            'commande_id' => $idCommande,
            'contenu' => $commande->contenu
        ]);
    }

        /**
     * Télécharge la facture d'une commande au format PDF
     * 
     * @param int $idCommande ID de la commande
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function telechargerFacture($idCommande)
    {
        $currentUserId = auth()->id();
        $currentUser = auth()->user();

        // Récupérer la commande
        $commande = $this->commandeModel->find($idCommande);

        if (!$commande) {
            return redirect()->back()->with('error', "Commande introuvable.");
        }

        // Convertir en objet si nécessaire
        $commande = is_array($commande) ? (object) $commande : $commande;

        // Vérification des permissions
        if (!$currentUser->inGroup('admin')) {
            if ((string) $commande->commanditaire !== (string) $currentUserId) {
                return redirect()->to(site_url('produits'))
                    ->with('error', "Accès non autorisé à cette facture.");
            }
        }

        // Charger les données de la facture avec le service
        $dataFacture = $this->factureService->charger($commande->contenu);

        if (!$dataFacture) {
            return redirect()->back()->with('error', "La facture n'existe pas ou n'a pas pu être chargée.");
        }

        // Générer le contenu HTML de la facture
        $html = $this->genererHtmlFacture($dataFacture, $commande);

        // Utiliser mPDF ou TCPDF pour générer le PDF
        // Ici, exemple avec mPDF (nécessite: composer require mpdf/mpdf)
        try {
            $mpdf = new Mpdf([
                'format' => 'A4',
                'margin_left' => 15,
                'margin_right' => 15,
                'margin_top' => 20,
                'margin_bottom' => 20,
            ]);

            $mpdf->WriteHTML($html);
            
            // Nom du fichier
            $nomFichier = 'Facture_' . $dataFacture->numero_facture . '.pdf';
            
            // Force le téléchargement
            return $this->response
                ->setHeader('Content-Type', 'application/pdf')
                ->setHeader('Content-Disposition', 'attachment; filename="' . $nomFichier . '"')
                ->setBody($mpdf->Output('', 'S'));

        } catch (\Exception $e) {
            log_message('error', 'Erreur génération PDF: ' . $e->getMessage());
            return redirect()->back()->with('error', "Erreur lors de la génération du PDF.");
        }
    }

    /**
     * Génère le HTML formaté pour la facture PDF
     * 
     * @param object $dataFacture Données de la facture
     * @param object $commande Objet commande
     * @return string HTML de la facture
     */
    private function genererHtmlFacture($dataFacture, $commande): string
    {
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <style>
                body { font-family: Arial, sans-serif; font-size: 11pt; }
                .header { text-align: center; margin-bottom: 30px; }
                .header h1 { color: #003366; margin: 0; }
                .info-section { margin-bottom: 20px; }
                .info-section strong { color: #003366; }
                table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                th { background-color: #003366; color: white; padding: 10px; text-align: left; }
                td { padding: 8px; border-bottom: 1px solid #ddd; }
                .text-right { text-align: right; }
                .text-center { text-align: center; }
                .total-section { margin-top: 20px; text-align: right; }
                .total-section .total { font-size: 14pt; font-weight: bold; color: #003366; }
                .footer { margin-top: 40px; text-align: center; font-size: 9pt; color: #666; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>RounDnPounD</h1>
                <p>Vos sandwichs préférés</p>
            </div>

            <div class="info-section">
                <strong>Facture N° :</strong> ' . esc($dataFacture->numero_facture) . '<br>
                <strong>Date :</strong> ' . esc($dataFacture->date) . '<br>
                <strong>Statut :</strong> ' . esc($commande->statut) . '
            </div>

            <div class="info-section">
                <strong>Adresse de livraison :</strong><br>
                ' . nl2br(esc($dataFacture->adresse)) . '
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th class="text-center">Quantité</th>
                        <th class="text-right">Prix unitaire</th>
                        <th class="text-right">Sous-total</th>
                    </tr>
                </thead>
                <tbody>';

        foreach ($dataFacture->produits as $produit) {
            $html .= '
                    <tr>
                        <td>' . esc($produit->nom) . '</td>
                        <td class="text-center">' . esc($produit->quantite) . '</td>
                        <td class="text-right">' . number_format($produit->prix_uni, 2, ',', ' ') . ' €</td>
                        <td class="text-right">' . number_format($produit->sous_total, 2, ',', ' ') . ' €</td>
                    </tr>';
        }

        $html .= '
                </tbody>
            </table>

            <div class="total-section">
                <p class="total">Total TTC : ' . number_format($dataFacture->total_ttc, 2, ',', ' ') . ' €</p>
            </div>

            <div class="footer">
                <p>Merci pour votre confiance !</p>
                <p>RounDnPounD - contact@roundnpound.fr</p>
            </div>
        </body>
        </html>';

        return $html;
    }
}

