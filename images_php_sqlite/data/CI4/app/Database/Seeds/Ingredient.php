<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Ingredient extends Seeder
{
    public function run()
    {
        $data = [
[
                'nom' => 'jambon',
                'quantite' => '300',
                'fournisseur' => '1', //1
            ],
            [
                'nom' => 'salade',
                'quantite' => '300',
                'fournisseur' => '1', //2
            ],
            [
                'nom' => 'pain de mie',
                'quantite' => '300',
                'fournisseur' => '1', //3
            ],
            [
                'nom' => 'fromage',
                'quantite' => '300',
                'fournisseur' => '1', //4
            ],
            [
                'nom' => 'tomate',
                'quantite' => '300',
                'fournisseur' => '1', //5
            ],
            [
                'nom' => 'oeuf',
                'quantite' => '300',
                'fournisseur' => '1', //6
            ],
            [
                'nom' => 'mayonnaise',
                'quantite' => '300',
                'fournisseur' => '1', //7
            ],
            [
                'nom' => 'beurre',
                'quantite' => '300',
                'fournisseur' => '1', //8
            ],
            [
                'nom' => 'moutarde',
                'quantite' => '300',
                'fournisseur' => '1', //9
            ],
            [
                'nom' => 'cornichon',
                'quantite' => '300',
                'fournisseur' => '1', //10
            ],
            [
                'nom' => 'thon',
                'quantite' => '300',
                'fournisseur' => '1', //11
            ],
            [
                'nom' => 'oignon',
                'quantite' => '300',
                'fournisseur' => '1', //12
            ],
            [
                'nom' => 'poivron',
                'quantite' => '300',
                'fournisseur' => '1', //13
            ],
            [
                'nom' => 'courgette',
                'quantite' => '300',
                'fournisseur' => '1', //14
            ],
            [
                'nom' => 'carotte',
                'quantite' => '300',
                'fournisseur' => '1', //15
            ],
            [
                'nom' => 'pomme de terre',
                'quantite' => '300',
                'fournisseur' => '1', //16
            ],
            [
                'nom' => 'laitue',
                'quantite' => '300',
                'fournisseur' => '1', //17
            ],
            [
                'nom' => 'avocat',
                'quantite' => '300',
                'fournisseur' => '1', //18
            ],
            [
                'nom' => 'poulet',
                'quantite' => '300',
                'fournisseur' => '1', //19
            ],
            [
                'nom' => 'bacon',
                'quantite' => '300',
                'fournisseur' => '1', //20
            ],
            [
                'nom' => 'steak haché',
                'quantite' => '300',
                'fournisseur' => '1', //21
            ],
            [
                'nom' => 'champignon',
                'quantite' => '300',
                'fournisseur' => '1', //22
            ],
            [
                'nom' => 'épinard',
                'quantite' => '300',
                'fournisseur' => '1', //23
            ],
            [
                'nom' => 'feta',
                'quantite' => '300',
                'fournisseur' => '1', //24
            ],
            [
                'nom' => 'olive',
                'quantite' => '300',
                'fournisseur' => '1', //25
            ],
            [
                'nom' => 'câpre',
                'quantite' => '300',
                'fournisseur' => '1', //26
            ],
            [
                'nom' => 'sauce tomate',
                'quantite' => '300',
                'fournisseur' => '1', //27
            ],
            [
                'nom' => 'ketchup',
                'quantite' => '300',
                'fournisseur' => '1', //28
            ],
            [
                'nom' => 'sauce barbecue',
                'quantite' => '300',
                'fournisseur' => '1', //29
            ],
            [
                'nom' => 'jalapeño',
                'quantite' => '300',
                'fournisseur' => '1', //30
            ],
            [
                'nom' => 'ananas',
                'quantite' => '300',
                'fournisseur' => '1', //31
            ],
            [
                'nom' => 'poulet pané',
                'quantite' => '300',
                'fournisseur' => '1',
            ],
            [
                'nom' => 'cheddar',
                'quantite' => '300',
                'fournisseur' => '1',
            ],
            [
                'nom' => 'pain burger',
                'quantite' => '300',
                'fournisseur' => '1',
            ],
            [
                'nom' => 'sauce secrete',
                'quantite' => '300',
                'fournisseur' => '1', //35
            ],
            [
                'nom' => 'pain baguette',
                'quantite' => '300',
                'fournisseur' => '1',
            ],
            [
                'nom' => 'pain hot-dog',
                'quantite' => '300',
                'fournisseur' => '1',
            ],
            [
                'nom' => 'merguez',
                'quantite' => '300',
                'fournisseur' => '1', //38
            ],
            [
                'nom' => 'pain pita',
                'quantite' => '300',
                'fournisseur' => '1', //39
            ],
            [
                'nom' => 'boeuf',
                'quantite' => '300',
                'fournisseur' => '1', //40
            ],
            [
                'nom' => 'fromage vegan',
                'quantite' => '300',
                'fournisseur' => '1', //41
            ],
            [
                'nom' => 'Aubergine',
                'quantite' => '300',
                'fournisseur' => '1', //42
            ],


        ];

        // Insère les données dans la table 'Ingredient'
        $this->db->table('Ingredient')->insertBatch($data);
    }
}
