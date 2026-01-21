<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class ProduitEntity extends Entity
{
    protected $attributes = [
        'id' => null,
        'nom' => null,
        'descr' => null,
        'prix' => null,
        'Ingredients' => null,
        'Image' => null,
    ];

    protected $datamap = [];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'id' => 'int',
        'Ingredients' => 'int',
        'prix' => 'double',
        'nom' => 'string',
        'descr' => 'string',
        'Image' => 'string',
    ];

    public function getPrixFormate(): string
    {
        return number_format($this->attributes['prix'] ?? 0, 2, ',', ' ') . ' €';
    }

    public function getListeIngredients(): array
    {
        $pivotModel = model('ProduitIngredientModel');
        $ingredientModel = model('IngredientModel');

        $id_liste = $this->attributes['Ingredients'];

        $pivotData = $pivotModel
            ->where('id_liste', $id_liste)
            ->findAll();

        if (empty($pivotData)) {
            return [];
        }

        $resultatComplet = [];

        foreach ($pivotData as $row) {
            $detailsIngredient = $ingredientModel->find($row['id_prod']);

            if ($detailsIngredient) {
                $resultatComplet[] = [
                    'nom' => $detailsIngredient['nom'],
                    'quantite' => $row['quantite']
                ];
            }
        }

        return $resultatComplet;
    }
}