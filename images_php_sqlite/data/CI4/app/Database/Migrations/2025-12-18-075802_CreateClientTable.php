<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateClientTable extends Migration
{
    public function up()
    {
    $this->forge->addField([
        'user_id' => [
            'type'       => 'INTEGER',
            'null'       => false,
        ],
        'adresse_mail' => [
            'type' => 'TEXT',
            'null' => true,
        ],
        'is_admin' => [
            'type' => 'BOOLEAN',
            'default' => false,
        ],'nb_commandes_en_cours' => [
            'type' => 'INTEGER',
            'default' => 0,
        ],
        'created_at' => ['type' => 'DATETIME', 'null' => true],
        'updated_at' => ['type' => 'DATETIME', 'null' => true],
    ]);

    $this->forge->addKey('user_id', true);
    $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
    $this->forge->createTable('clients');
    }

    public function down()
    {
        $this->forge->dropTable('clients');
    }
}