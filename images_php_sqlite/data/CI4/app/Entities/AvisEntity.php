<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class AvisEntity extends Entity
{
    protected $datamap = [];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts = [
        'id' => 'int',
        'id_client' => 'int',
        'id_produit' => 'int',
        'note' => 'int',
        'texte' => 'string',
    ];
}
