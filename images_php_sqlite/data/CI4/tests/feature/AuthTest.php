<?php

namespace Tests\Feature;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;

class AuthTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    public function testConnexionValide()
    {
        $result = $this->post('/login', [
            'email' => 'test@test.com',
            'password' => 'password'
        ]);

        $result->assertStatus(200);
    }

    public function testConnexionInvalide()
    {
        $result = $this->post('/login', [
            'email' => 'test@test.com',
            'password' => 'faux'
        ]);

        $result->assertStatus(401);
    }
}
