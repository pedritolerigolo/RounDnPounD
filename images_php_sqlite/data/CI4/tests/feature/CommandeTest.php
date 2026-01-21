<?php

namespace Tests\Feature;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;

class CommandeTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    public function testCommandePanierValide()
    {
        $result = $this->withSession(['user_id' => 1])
                       ->post('/commande/valider');

        $result->assertStatus(200);
    }

    public function testCommandePanierVide()
    {
        $result = $this->withSession(['user_id' => 1])
                       ->post('/commande/valider');

        $result->assertStatus(400);
    }
}
