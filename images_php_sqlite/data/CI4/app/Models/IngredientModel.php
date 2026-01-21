<?php
// Fichier : app/Models/IngredientModel.php

namespace App\Models;

use CodeIgniter\Model;

class IngredientModel extends Model
{
    protected $table = 'Ingredient';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'id',
        'nom',
        'quantite',
        'fournisseur'
    ];
    public function verifierDisponibilite($items)
    {
        $db = \Config\Database::connect();
        foreach ($items as $item) {
            $produit = $db->table('Produit')->where('id', $item->id_produit)->get()->getRow();
            $recette = $db->table('Ingredients')->where('id_liste', $produit->Ingredients)->get()->getResult();

            foreach ($recette as $ligne) {
                $stock = $this->find($ligne->id_prod);
                if (!$stock || $stock['quantite'] < ($ligne->quantite * $item->quantite))
                    return false;
            }
        }
        return true;
    }

    public function soustraireStocksPourProduit($idProduit, $quantiteCommande)
    {
        $db = \Config\Database::connect();
        $produit = $db->table('Produit')->where('id', $idProduit)->get()->getRow();
        $recette = $db->table('Ingredients')->where('id_liste', $produit->Ingredients)->get()->getResult();

        foreach ($recette as $ligne) {
            $this->where('id', $ligne->id_prod)
                ->decrement('quantite', $ligne->quantite * $quantiteCommande);
        }
    }

    /**
     * Récupère tous les ingrédients avec le nom du fournisseur
     */
    public function getAllWithFournisseurName()
    {
        return $this->db->table('ingredient')
            ->select('ingredient.id, ingredient.nom, ingredient.quantite, ingredient.fournisseur, fournisseur.nom as fournisseur_nom')
            ->join('fournisseur', 'fournisseur.id = ingredient.fournisseur', 'left')
            ->orderBy('ingredient.id', 'ASC')
            ->get()
            ->getResult();
    }

    /**
     * Supprime un ingrédient après vérification
     */
    public function deleteIngredientWithCheck($id): array
    {
        $count = $this->db->table('Ingredients')
            ->where('id_prod', $id)
            ->countAllResults();

        if ($count > 0) {
            return [
                'success' => false,
                'message' => "Impossible de supprimer : cet ingrédient est lié à $count produit(s)."
            ];
        }

        $ingredient = $this->find($id);

        if ($ingredient) {
            $this->db->table('Allergenes')->where('id_ingredient', $id)->delete();
            $this->delete($id);
        }

        return [
            'success' => true,
            'message' => 'Ingrédient supprimé.'
        ];
    }
}