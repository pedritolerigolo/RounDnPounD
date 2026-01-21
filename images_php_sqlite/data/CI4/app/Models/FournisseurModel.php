<?php

namespace App\Models;

use CodeIgniter\Model;

class FournisseurModel extends Model
{
    protected $table = 'fournisseur';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = \App\Entities\FournisseurEntity::class;
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'id',
        'nom',
        'contact',
        'adresse',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    /**
     * Récupère tous les fournisseurs triés par ID décroissant
     */
    public function getAllOrderedByIdDesc()
    {
        return $this->orderBy('id', 'DESC')->findAll();
    }

    /**
     * Supprime un fournisseur après vérification
     */
    public function deleteFournisseurWithCheck($id): array
    {
        $count = $this->db->table('Ingredient')
            ->where('fournisseur', $id)
            ->countAllResults();

        if ($count > 0) {
            return [
                'success' => false,
                'message' => "Impossible de supprimer : ce fournisseur est lié à $count ingrédient(s)."
            ];
        }

        $fournisseur = $this->find($id);

        if ($fournisseur) {
            $this->delete($id);
        }

        return [
            'success' => true,
            'message' => 'Fournisseur supprimé.'
        ];
    }

    /**
     * Duplique un fournisseur
     */
    public function duplicateFournisseur($idOriginal): array
    {
        $original = $this->find($idOriginal);

        if (!$original) {
            return [
                'success' => false,
                'message' => 'Fournisseur introuvable.'
            ];
        }

        $nouveauFournisseur = clone $original;
        $data = $nouveauFournisseur->toArray();
        unset($data['id']);

        $nouveauId = $this->insert($data);

        if ($nouveauId) {
            return [
                'success' => true,
                'message' => "Fournisseur dupliqué avec succès (Nouveau ID : $nouveauId)"
            ];
        }

        $erreurs = $this->errors();
        $messageErreur = !empty($erreurs) ? implode(', ', $erreurs) : "Erreur inconnue lors de l'insertion.";

        return [
            'success' => false,
            'message' => "Échec de duplication : " . $messageErreur
        ];
    }
}
