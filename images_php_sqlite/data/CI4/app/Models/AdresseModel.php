<?php

namespace App\Models;

use CodeIgniter\Model;

class AdresseModel extends Model
{
    protected $table = 'adresse';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'client',
        'ville',
        'code_postal',
        'addresse_complete',
        'pays',
        'info_suppl'
    ];

    /**
     * Récupère les adresses d'un client
     */
    public function getAdressesByClient($clientId)
    {
        return $this->where('client', $clientId)->findAll();
    }

    /**
     * Supprime une adresse si l'utilisateur en est propriétaire
     */
    public function deleteAdresseIfOwner($id, $userId): array
    {
        // On vérifie l'existence et l'appartenance
        $adresse = $this->where(['id' => $id, 'client' => $userId])->first();

        if (!$adresse) {
            return [
                'success' => false,
                'message' => "Adresse introuvable ou accès refusé."
            ];
        }

        $this->delete($id);

        return [
            'success' => true,
            'message' => 'Adresse supprimée avec succès.'
        ];
    }
}
