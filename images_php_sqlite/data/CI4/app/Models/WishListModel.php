<?php

namespace App\Models;

use CodeIgniter\Model;

class WishListModel extends Model
{
    protected $table = 'ProduitsWishList';
    protected $allowedFields = ['id_produit', 'id_client'];
    protected $returnType = 'object';

    public function getFavorisByUser(int $userId)
    {
        // Récupère les produits favoris d'un utilisateur
        return $this->select('Produit.id, Produit.nom, Produit.Image, Produit.prix')
                    ->join('Produit', 'Produit.id = ProduitsWishList.id_produit')
                    ->where('id_client', $userId)
                    ->findAll();
    }

    /**
     * Vérifie si un produit est dans la wishlist d'un utilisateur
     */
    public function isInWishlist(int $userId, int $productId): bool
    {
        $result = $this->where('id_client', $userId)
                       ->where('id_produit', $productId)
                       ->first();
        
        return $result !== null;
    }

    /**
     * Ajoute un produit à la wishlist
     */
    public function addToWishlist(int $userId, int $productId): bool
    {
        return $this->insert([
            'id_client' => $userId,
            'id_produit' => $productId
        ]);
    }

    /**
     * Retire un produit de la wishlist
     */
    public function removeFromWishlist(int $userId, int $productId): bool
    {
        return $this->where('id_client', $userId)
                    ->where('id_produit', $productId)
                    ->delete();
    }

    /**
     * Toggle un produit dans la wishlist (ajoute ou retire)
     */
    public function toggleWishlist(int $userId, int $productId): array
    {
        $existing = $this->where('id_client', $userId)
                        ->where('id_produit', $productId)
                        ->first();

        if ($existing) {
            $this->removeFromWishlist($userId, $productId);
            return ['action' => 'removed', 'message' => 'Produit retiré de votre liste.'];
        } else {
            $this->addToWishlist($userId, $productId);
            return ['action' => 'added', 'message' => 'Produit ajouté à votre liste !'];
        }
    }
}