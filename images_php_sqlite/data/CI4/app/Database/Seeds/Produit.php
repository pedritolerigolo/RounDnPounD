<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Produit extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nom' => "le classico",
                'descr' => "il est tres delicieux, miam miam, sulrrrrrp",
                'prix' => "8.99",
                'Image' => "1.gif",
                'Ingredients' => 1,
            ],
            [
                'nom' => "Crispy Cheeken",
                'descr' => "il est tres delicieux, miam miam, sulrrrrrp",
                'prix' => "9.99",
                'Image' => "cheeken.gif",
                'Ingredients' => 2,
            ],
            [
                'nom' => "Spicy BBQ",
                'descr' => "il est tres piquant, miam miam, sulrrrrrp",
                'prix' => "5.99",
                'Image' => "barbak.gif",
                'Ingredients' => 3,
            ],
            [
                'nom' => "Gyro",
                'descr' => "il est tres grecque, miam miam, sulrrrrrp",
                'prix' => "8.99",
                'Image' => "gyros.gif",
                'Ingredients' => 4,
            ],
            [
                'nom' => "La feinte",
                'descr' => "Veggie ? Nahhh",
                'prix' => "5.99",
                'Image' => "fakeveggie.gif",
                'Ingredients' => 5,
            ],
            [
                'nom' => "Veggie eggplant",
                'descr' => "Le délice au naturel",
                'prix' => "8.99",
                'Image' => "veggie.gif",
                'Ingredients' => 6,
            ],
            [
                'nom' => "Jambon Beurre",
                'descr' => "Il meritait le titre de classique",
                'prix' => "6.99",
                'Image' => "jambon-buerre.gif",
                'Ingredients' => 7,
            ],
            [
                'nom' => "Swedish Hot-dog",
                'descr' => "Il est très bon, Promis",
                'prix' => "5.99",
                'Image' => "zeyzey.gif",
                'Ingredients' => 8,
            ],

        ];

        // Insère les données dans la table 'Produit'
        $this->db->table('Produit')->insertBatch($data);
    }
}
