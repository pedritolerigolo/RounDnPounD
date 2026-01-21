<?php

namespace Tests\Unit;

use App\Models\ProduitModel;
use CodeIgniter\Test\CIUnitTestCase;

class ProduitModelTest extends CIUnitTestCase
{
    public function testProduitExiste()
    {
        $model = new ProduitModel();
        $produit = $model->find(1);

        $this->assertNotNull($produit);
    }

    public function testRechercheProduit()
    {
        $model = new ProduitModel();
        $resultats = $model->rechercher('Pizza');

        $this->assertIsArray($resultats);
        $this->assertNotEmpty($resultats);
    }
}
