<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class FournisseurEntity extends Entity
{
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [];

    public function __clone()
    {
        if (isset($this->attributes['id'])) {
            unset($this->attributes['id']);
        }

        if (isset($this->attributes['nom'])) {
            $this->attributes['nom'] = "[CLONE] " . $this->attributes['nom'];
        }
        $this->attributes['created_at'] = null;
    }
}