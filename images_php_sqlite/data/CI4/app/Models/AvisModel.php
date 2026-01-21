<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\AvisEntity;

class AvisModel extends Model
{
    protected $table = 'Avis';
    protected $primaryKey = 'id';
    protected $returnType = AvisEntity::class;
    protected $allowedFields = ['id_produit', 'id_client', 'note', 'texte'];

    /**
     * Récupère les avis d'un produit avec les noms des clients
     */
    public function getAvisByProduit(int $idProduit)
    {
        return $this->select('avis.*, users.username as client_nom')
            ->join('users', 'users.id = avis.id_client', 'left')
            ->where('id_produit', $idProduit)
            ->findAll();
    }

    /**
     * Calcule la moyenne des notes d'un produit
     */
    public function getMoyenneNote(int $idProduit): float
    {
        $avis = $this->select('note')
            ->where('id_produit', $idProduit)
            ->asArray()
            ->findAll();

        if (count($avis) === 0) {
            return 0.0;
        }

        $total = array_sum(array_column($avis, 'note'));
        return round($total / count($avis), 1);
    }

    public function peutRedigerAvis(int $userId, int $idProduit): bool
    {
        // on vérifie si l'utilisateur a déjà laissé un avis sur ce produit
        $dejaNote = $this->where('id_produit', $idProduit)
            ->where('id_client', $userId)
            ->countAllResults();

        if ($dejaNote > 0) {
            return false;
        }

        // on vérifie aussi si le produit a été livré à cet utilisateur
        $commandeLivree = $this->db->table('Commande')
            ->join('ProduitsCommande', 'ProduitsCommande.id = Commande.contenu')
            ->where('Commande.commanditaire', $userId)
            ->where('Commande.statut', 'Livrée')
            ->where('ProduitsCommande.id_produit', $idProduit)
            ->countAllResults();

        return ($commandeLivree > 0);
    }

    /**
     * Supprime un avis par son ID
     */
    public function deleteAvisById(int $idAvis): array
    {
        $avis = $this->find($idAvis);

        if (!$avis) {
            return [
                'success' => false,
                'message' => 'Avis introuvable.'
            ];
        }

        if ($this->delete($idAvis)) {
            return [
                'success' => true,
                'message' => 'Avis supprimé avec succès.'
            ];
        }

        return [
            'success' => false,
            'message' => 'Erreur lors de la suppression de l\'avis.'
        ];
    }
}