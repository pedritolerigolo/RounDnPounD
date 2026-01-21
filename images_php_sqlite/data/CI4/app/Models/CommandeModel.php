<?php
namespace App\Models;

use CodeIgniter\Model;

class CommandeModel extends Model
{
    protected $table = 'Commande';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';  // Changé en 'object' pour cohérence
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'commanditaire',
        'adresseLivraison',
        'prixTotal',
        'contenu',
        'statut',
        'panier',
        'created_at'
    ];

    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'commanditaire' => 'required',
        'adresseLivraison' => 'required',
        'prixTotal' => 'required|decimal',
        'contenu' => 'required|integer',
        'statut' => 'required'
    ];

    protected $validationMessages = [
        'commanditaire' => [
            'required' => 'Le commanditaire est requis'
        ],
        'prixTotal' => [
            'required' => 'Le prix total est requis',
            'decimal' => 'Le prix doit être un nombre décimal'
        ]
    ];

    /**
     * Récupère une commande par son ID de contenu
     */
    public function getByContenu($idContenu)
    {
        return $this->where('contenu', $idContenu)->first();
    }

    /**
     * Crée une nouvelle commande
     */
    public function creerCommande($data)
    {
        $commandeData = [
            'commanditaire' => $data['commanditaire'],
            'adresseLivraison' => $data['adresseLivraison'],
            'prixTotal' => $data['prixTotal'],
            'contenu' => $data['contenu'],
            'statut' => $data['statut'] ?? 'En attente du paiement',
            'panier' => $data['panier'] ?? false,
            'created_at' => date('Y-m-d H:i:s')
        ];

        return $this->insert($commandeData);
    }

    /**
     * Met à jour le statut d'une commande
     */
    public function updateStatut($idContenu, $statut)
    {
        return $this->where('contenu', $idContenu)
            ->set('statut', $statut)
            ->update();
    }

    /**
     * Génère un ID de contenu unique
     */
    public function genererIdContenuUnique()
    {
        $exists = true;
        $randomId = 0;

        while ($exists) {
            $randomId = mt_rand(100000, 999999);
            $check = $this->where('contenu', $randomId)->first();

            if (!$check) {
                $exists = false;
            }
        }

        return $randomId;
    }

    /**
     * Vérifie si une commande est issue d'un panier
     */
    public function estCommandePanier($idContenu)
    {
        $commande = $this->where('contenu', $idContenu)
            ->where('panier', true)
            ->first();

        return !empty($commande);
    }

    /**
     * Récupère les commandes d'un utilisateur
     */
    public function getCommandesUtilisateur($userId)
    {
        return $this->where('commanditaire', $userId)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    public function suppressionTotale($idContenu)
    {
        $db = \Config\Database::connect();
        $db->table('ProduitsCommande')->where('id', $idContenu)->delete();
        return $this->where('contenu', $idContenu)->delete();
    }

    public function updatePrix($idContenu, $nouveauPrix)
    {
        return $this->where('contenu', $idContenu)
            ->set(['prix_total' => $nouveauPrix])
            ->update();
    }

    /**
     * Récupère toutes les commandes avec l'email du client
     */
    public function getAllCommandesWithClientEmail()
    {
        $commandesRaw = $this->orderBy('created_at', 'DESC')->findAll();
        $commandesFinales = [];

        foreach ($commandesRaw as $com) {
            $emailAffiche = "";

            if (ctype_digit((string) $com->commanditaire)) {
                $user = $this->db->table('clients')
                    ->select('adresse_mail')
                    ->where('clients.user_id', $com->commanditaire)
                    ->get()
                    ->getRow();
                $emailAffiche = $user ? $user->adresse_mail : "Compte supprimé";
            } else {
                $emailAffiche = $com->commanditaire;
            }

            $com->email_client = $emailAffiche;
            $commandesFinales[] = $com;
        }

        return $commandesFinales;
    }
}