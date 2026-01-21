<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\UserModel;

class Admin extends Seeder
{
    public function run()
    {
        $users = auth()->getProvider();

        $user = new User([
            'username' => 'admin',
            'email'    => 'admin@example.com',
            'password' => './admin',
        ]);
        
        $users->save($user);

        $userId = $users->getInsertID();

        $user = $users->find($userId);
        $user->activate();
        $user->addGroup('admin');

        $adminData = [
            'user_id'      => $userId,
            'adresse_mail' => 'admin@example.com',
            'is_admin'     => 1,
        ];

        $this->db->table('Clients')->insert($adminData);
    }
}