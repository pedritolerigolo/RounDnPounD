<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Avis extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id_client' => 1,
                'id_produit' => 8,
                'note' => 4,
                'texte' => "Très bon produit, je le recommande !",
            ],
            [
                'id_client' => 1,
                'id_produit' => 8,
                'note' => 3,
                'texte' => "Bon",
            ],
        ];

        $this->db->table('Avis')->insertBatch($data);
    }
}
