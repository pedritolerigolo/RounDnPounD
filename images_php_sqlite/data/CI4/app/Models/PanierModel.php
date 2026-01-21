<?php
namespace App\Models;

use CodeIgniter\Model;

class PanierModel extends Model
{
    protected $table = 'Panier';
    protected $primaryKey = 'client';
    protected $useAutoIncrement = false;
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['client', 'produits', 'prix_total'];

    protected $useTimestamps = false;
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;

    /**
     * Récupère le panier d'un utilisateur
     * Retourne toujours un objet (stdClass) ou null
     */
    public function getPanierUtilisateur($userId)
    {
        return $this->where('client', $userId)->first();
    }

    /**
     * Récupère les quantités des produits dans le panier de l'utilisateur
     */
    public function getQuantitesProduits(int $userId): array
    {
        $db = \Config\Database::connect();
        $quantites = [];
        $panier = $this->where('client', $userId)->asObject()->first();

        if ($panier) {
            $items = $db->table('ProduitsPanier')
                ->where('id', $panier->produits)
                ->get()
                ->getResult();

            foreach ($items as $item) {
                $quantites[$item->id_produit] = $item->quantite;
            }
        }

        return $quantites;
    }

    /**
     * Récupère les produits du panier avec leurs détails (version basique)
     */
    public function getProduitsAvecDetails($idGroupe)
    {
        return $this->db->table('ProduitsPanier')
            ->where('id', $idGroupe)
            ->get()
            ->getResult();
    }

    /**
     * Renvoie les détails complets des produits dans le panier (avec JOIN)
     */
    public function getProduitsDetails($idGroupe)
    {
        return $this->db->table('ProduitsPanier')
            ->select('Produit.id, Produit.nom, Produit.prix, Produit.Image, ProduitsPanier.quantite')
            ->join('Produit', 'Produit.id = ProduitsPanier.id_produit')
            ->where('ProduitsPanier.id', $idGroupe)
            ->get()
            ->getResult();
    }

    /**
     * Calcule le prix total du panier
     */
    public function calculerPrixTotal($userId)
    {
        $db = \Config\Database::connect();
        $panier = $this->getPanierUtilisateur($userId);

        if (!$panier) {
            return 0;
        }

        // $panier est un objet, donc on utilise ->
        $items = $db->table('ProduitsPanier')
            ->where('id', $panier->produits)
            ->get()
            ->getResult();

        $prixTotal = 0;
        foreach ($items as $item) {
            $produit = $db->table('Produit')
                ->where('id', $item->id_produit)
                ->get()
                ->getRow();

            if ($produit) {
                $prixTotal += ((float) $produit->prix * (int) $item->quantite);
            }
        }

        return $prixTotal;
    }

    /**
     * Met à jour la table pivot ProduitsPanier en fonction de l'action
     * 
     * @param int $idGroupe ID du groupe de produits (panier)
     * @param int $idProduit ID du produit
     * @param string $action 'add', 'remove_one' ou 'delete_all'
     * @return object|null Ligne existante avant modification
     */
    public function updatePivot($idGroupe, $idProduit, $action = 'add')
    {
        $pivotTable = $this->db->table('ProduitsPanier');
        $existing = $pivotTable->where(['id' => $idGroupe, 'id_produit' => $idProduit])
            ->get()
            ->getRow();

        if ($action === 'add') {
            if ($existing) {
                $pivotTable->where(['id' => $idGroupe, 'id_produit' => $idProduit])
                    ->update(['quantite' => $existing->quantite + 1]);
            } else {
                $pivotTable->insert([
                    'id' => $idGroupe,
                    'id_produit' => $idProduit,
                    'quantite' => 1
                ]);
            }
        } elseif ($action === 'remove_one') {
            if ($existing && $existing->quantite > 1) {
                $pivotTable->where(['id' => $idGroupe, 'id_produit' => $idProduit])
                    ->update(['quantite' => $existing->quantite - 1]);
            } else {
                $pivotTable->where(['id' => $idGroupe, 'id_produit' => $idProduit])
                    ->delete();
            }
        } elseif ($action === 'delete_all') {
            $pivotTable->where(['id' => $idGroupe, 'id_produit' => $idProduit])
                ->delete();
        }

        return $existing;
    }

    /**
     * Vide complètement le panier d'un utilisateur
     */
    public function viderPanier($userId)
    {
        $panier = $this->getPanierUtilisateur($userId);

        if ($panier) {
            // $panier est un objet, donc on utilise ->
            $this->db->table('ProduitsPanier')
                ->where('id', $panier->produits)
                ->delete();

            $this->where('client', $userId)
                ->set('prix_total', 0)
                ->update();
        }

        return true;
    }

    /**
     * Compte le nombre total d'articles dans le panier
     */
    public function compterArticles($userId)
    {
        $panier = $this->getPanierUtilisateur($userId);

        if (!$panier) {
            return 0;
        }

        // $panier est un objet, donc on utilise ->
        $items = $this->db->table('ProduitsPanier')
            ->where('id', $panier->produits)
            ->get()
            ->getResult();

        $total = 0;
        foreach ($items as $item) {
            $total += (int) $item->quantite;
        }

        return $total;
    }

    /**
     * Vérifie si le panier est vide
     */
    public function estVide($userId)
    {
        $panier = $this->getPanierUtilisateur($userId);

        if (!$panier) {
            return true;
        }

        // $panier est un objet, donc on utilise ->
        $count = $this->db->table('ProduitsPanier')
            ->where('id', $panier->produits)
            ->countAllResults();

        return $count === 0;
    }

    /**
     * Met à jour le prix total du panier en base de données
     */
    public function mettreAJourPrixTotal($userId)
    {
        $prixTotal = $this->calculerPrixTotal($userId);

        return $this->where('client', $userId)
            ->set('prix_total', $prixTotal)
            ->update();
    }

    /**
     * Récupère la quantité d'un produit spécifique dans le panier
     */
    public function getQuantiteProduit(int $userId, int $idProduit): int
    {
        $panier = $this->getPanierUtilisateur($userId);

        if (!$panier) {
            return 0;
        }

        $produitPanier = $this->db->table('ProduitsPanier')
            ->where('id', $panier->produits)
            ->where('id_produit', $idProduit)
            ->get()
            ->getRow();

        return $produitPanier ? (int) $produitPanier->quantite : 0;
    }
}