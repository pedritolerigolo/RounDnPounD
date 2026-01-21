<?php
namespace App\Models;

use CodeIgniter\Model;

class ProduitsCommandeModel extends Model
{
    protected $table            = 'ProduitsCommande';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $allowedFields    = ['id', 'id_produit', 'quantite'];

    /**
     * Récupère les produits d'une commande
     */
    public function getProduitsCommande($idContenu)
    {
        return $this->where('id', $idContenu)->findAll();
    }

    /**
     * Ajoute un produit à une commande
     */
    public function ajouterProduit($idContenu, $idProduit, $quantite)
    {
        return $this->insert([
            'id' => $idContenu,
            'id_produit' => $idProduit,
            'quantite' => $quantite
        ]);
    }

    /**
     * Copie les produits d'un panier vers une commande
     */
    public function copierDepuisPanier($idContenu, $itemsPanier)
    {
        $batch = [];
        foreach ($itemsPanier as $item) {
            $batch[] = [
                'id' => $idContenu,
                'id_produit' => $item->id_produit,
                'quantite' => $item->quantite
            ];
        }
        
        return $this->insertBatch($batch);
    }
}