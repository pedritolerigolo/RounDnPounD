<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Ingredients extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id_liste' => '1',
                'id_prod' => '2',
                'quantite' => '1',
            ],
            [
                'id_liste' => '1',
                'id_prod' => '3',
                'quantite' => '1',
            ],
            [
                'id_liste' => '1',
                'id_prod' => '4',
                'quantite' => '1',
            ],
            [
                'id_liste' => '1',
                'id_prod' => '20',
                'quantite' => '1',
            ],

            //crispy cheeken
            [
                'id_liste' => '2',
                'id_prod' => '32',
                'quantite' => '1',
            ],
            [
                'id_liste' => '2',
                'id_prod' => '33',
                'quantite' => '2',
            ],
            [
                'id_liste' => '2',
                'id_prod' => '34',
                'quantite' => '1',
            ],
            [
                'id_liste' => '2',
                'id_prod' => '35',
                'quantite' => '1',
            ],


            //spicy BBQ
            [
                'id_liste' => '3',
                'id_prod' => '37',
                'quantite' => '1',
            ],
            [
                'id_liste' => '3',
                'id_prod' => '38',
                'quantite' => '1',
            ],
            [
                'id_liste' => '3',
                'id_prod' => '29',
                'quantite' => '1',
            ],
            [
                'id_liste' => '3',
                'id_prod' => '33',
                'quantite' => '1',
            ],
            [
                'id_liste' => '3',
                'id_prod' => '30',
                'quantite' => '1',
            ],



            //gyro
            [
                'id_liste' => '4',
                'id_prod' => '39',
                'quantite' => '1',
            ],
            [
                'id_liste' => '4',
                'id_prod' => '40',
                'quantite' => '1',
            ],
            [
                'id_liste' => '4',
                'id_prod' => '2',
                'quantite' => '1',
            ],
            [
                'id_liste' => '4',
                'id_prod' => '24',
                'quantite' => '1',
            ],
            [
                'id_liste' => '4',
                'id_prod' => '25',
                'quantite' => '1',
            ],
            [
                'id_liste' => '4',
                'id_prod' => '35',
                'quantite' => '1',
            ],



            //fakeveggie
            [
                'id_liste' => '5',
                'id_prod' => '39',
                'quantite' => '1',
            ],
            [
                'id_liste' => '5',
                'id_prod' => '17',
                'quantite' => '1',
            ],
            [
                'id_liste' => '5',
                'id_prod' => '21',
                'quantite' => '1',
            ],
            [
                'id_liste' => '5',
                'id_prod' => '19',
                'quantite' => '1',
            ],
            [
                'id_liste' => '5',
                'id_prod' => '38',
                'quantite' => '1',
            ],
            [
                'id_liste' => '5',
                'id_prod' => '35',
                'quantite' => '1',
            ],




            //veggie eggplant
            [
                'id_liste' => '6',
                'id_prod' => '36',
                'quantite' => '1',
            ],
            [
                'id_liste' => '6',
                'id_prod' => '12',
                'quantite' => '1',
            ],
            [
                'id_liste' => '6',
                'id_prod' => '42',
                'quantite' => '1',
            ],
            [
                'id_liste' => '6',
                'id_prod' => '13',
                'quantite' => '1',
            ],
            [
                'id_liste' => '6',
                'id_prod' => '5',
                'quantite' => '1',
            ],
            [
                'id_liste' => '6',
                'id_prod' => '35',
                'quantite' => '1',
            ],
            [
                'id_liste' => '6',
                'id_prod' => '41',
                'quantite' => '1',
            ],








            //jambon beurre
            [
                'id_liste' => '7',
                'id_prod' => '36',
                'quantite' => '1',
            ],
            [
                'id_liste' => '7',
                'id_prod' => '8',
                'quantite' => '1',
            ],
            [
                'id_liste' => '7',
                'id_prod' => '1',
                'quantite' => '8',
            ],
            [
                'id_liste' => '7',
                'id_prod' => '35',
                'quantite' => '1',
            ],






            //swedish hot dog zeyzey
            [
                'id_liste' => '8',
                'id_prod' => '37',
                'quantite' => '1',
            ],
            [
                'id_liste' => '8',
                'id_prod' => '14',
                'quantite' => '1',
            ],
            [
                'id_liste' => '8',
                'id_prod' => '7',
                'quantite' => '1',
            ],
            [
                'id_liste' => '8',
                'id_prod' => '2',
                'quantite' => '1',
            ],
            [
                'id_liste' => '8',
                'id_prod' => '35',
                'quantite' => '1',
            ],

            
        ];

        // Insère les données dans la table 'Ingredients'
        $this->db->table('Ingredients')->insertBatch($data);
    }
}
