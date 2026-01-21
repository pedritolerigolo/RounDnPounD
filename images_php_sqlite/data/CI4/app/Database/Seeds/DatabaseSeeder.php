<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call('Fournisseur');
        $this->call('Ingredient');
        $this->call('Ingredients');
        $this->call('Produit');
        $this->call('Admin');
        $this->call('Avis');
        $this->call('AllergeneSeeder');
    }
}
