<?php

namespace Tests\Feature;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;

class AdminProduitTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    public function testAjoutProduitAdmin()
    {
        $result = $this->withSession([
            'user_id' => 1,
            'role' => 'admin'
        ])->post('/admin/produit/ajouter', [
            'nom' => 'Produit Test',
            'prix' => 10
        ]);

        $result->assertStatus(200);
    }

    public function testAjoutProduitUtilisateur()
    {
        $result = $this->withSession([
            'user_id' => 2,
            'role' => 'user'
        ])->post('/admin/produit/ajouter');

        $result->assertStatus(403);
    }
}
