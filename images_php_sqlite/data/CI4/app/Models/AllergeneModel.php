<?php

namespace App\Models;

use CodeIgniter\Model;

class AllergeneModel extends Model
{
    protected $table = 'Allergenes';
    protected $primaryKey = 'id_ingredient';
    protected $useAutoIncrement = false;
    protected $returnType = 'array';
    protected $protectFields = true;

    protected $allowedFields = [
        'id_ingredient',
        'gluten',
        'crustaces',
        'oeufs',
        'poissons',
        'arachides',
        'soja',
        'lait',
        'fruits_a_coque',
        'celeri',
        'moutarde',
        'sesame',
        'sulfites',
        'lupin',
        'mollusques'
    ];
    protected $useTimestamps = false;
    public function getAllergenesByProduit(int $produitId): array
    {
        return $this->select('
                MAX(gluten) as gluten, 
                MAX(crustaces) as crustaces, 
                MAX(oeufs) as oeufs, 
                MAX(poissons) as poissons, 
                MAX(arachides) as arachides, 
                MAX(soja) as soja, 
                MAX(lait) as lait, 
                MAX(fruits_a_coque) as fruits_a_coque, 
                MAX(celeri) as celeri, 
                MAX(moutarde) as moutarde, 
                MAX(sesame) as sesame, 
                MAX(sulfites) as sulfites, 
                MAX(lupin) as lupin, 
                MAX(mollusques) as mollusques
            ')
            ->join('Ingredients li', 'li.id_prod = Allergenes.id_ingredient')
            ->join('Produit p', 'p.Ingredients = li.id_liste')
            ->where('p.id', $produitId)
            ->get()
            ->getRowArray() ?? [];
    }
}