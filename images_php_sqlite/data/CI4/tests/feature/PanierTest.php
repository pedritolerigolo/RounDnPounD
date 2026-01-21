<?php

namespace Tests\Feature;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;

class PanierTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    public function testAjoutPanierUtilisateurConnecte()
    {
        $result = $this->withSession(['user_id' => 1])
                       ->post('/panier/ajouter/1');

        $result->assertStatus(200);
    }

    public function testAjoutPanierUtilisateurNonConnecte()
    {
        $result = $this->post('/panier/ajouter/1');

        $result->assertStatus(401);
    }
}
