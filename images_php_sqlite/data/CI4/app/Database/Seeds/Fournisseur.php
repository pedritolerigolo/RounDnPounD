<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Fournisseur extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nom' => 'youenn simonneau',
                'contact' => '0612345678',
                'adresse' => '4 rue du skibidi maréchal Pétain 75000 Paris',
            ]];

        // Insère les données dans la table 'Fournisseur'
        $this->db->table('Fournisseur')->insertBatch($data);
    }
}
