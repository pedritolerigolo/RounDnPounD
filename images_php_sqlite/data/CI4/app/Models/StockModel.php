<?php
namespace App\Models;

use CodeIgniter\Model;

class StockModel extends Model
{
    protected $table            = 'Ingredient';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $allowedFields    = ['nom', 'quantite'];

    /**
     * Vérifie si les stocks sont suffisants pour une liste de produits
     */
    public function verifierStocksProduits($produits)
    {
        $db = \Config\Database::connect();

        foreach ($produits as $item) {
            $produit = $db->table('Produit')
                          ->where('id', $item->id_produit)
                          ->get()
                          ->getRow();

            if (!$produit) {
                continue;
            }

            $recette = $db->table('Ingredients')
                          ->where('id_liste', $produit->Ingredients)
                          ->get()
                          ->getResult();

            foreach ($recette as $ligneRecette) {
                $stockRow = $this->find($ligneRecette->id_prod);

                if (!$stockRow) {
                    continue;
                }

                $besoinTotal = $ligneRecette->quantite * $item->quantite;

                if ($stockRow['quantite'] < $besoinTotal) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Décrémente les stocks pour une commande
     */
    public function decrementerStocksCommande($idContenu)
    {
        $db = \Config\Database::connect();
        
        $produits = $db->table('ProduitsCommande')
                       ->where('id', $idContenu)
                       ->get()
                       ->getResult();

        foreach ($produits as $item) {
            $produit = $db->table('Produit')
                          ->where('id', $item->id_produit)
                          ->get()
                          ->getRow();

            if (!$produit) {
                continue;
            }

            $recette = $db->table('Ingredients')
                          ->where('id_liste', $produit->Ingredients)
                          ->get()
                          ->getResult();

            foreach ($recette as $ligne) {
                $this->where('id', $ligne->id_prod)
                     ->decrement('quantite', $ligne->quantite * $item->quantite);
            }
        }

        return true;
    }

    /**
     * Récupère le stock d'un ingrédient
     */
    public function getStock($idIngredient)
    {
        $ingredient = $this->find($idIngredient);
        return $ingredient ? $ingredient['quantite'] : 0;
    }
}