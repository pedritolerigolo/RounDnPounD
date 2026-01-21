<?php
// Fichier : app/Models/ProduitIngredientModel.php

namespace App\Models;

use CodeIgniter\Model;

class ProduitIngredientModel extends Model
{
    protected $table = 'Ingredients';
    protected $primaryKey = ['id_liste', 'id_prod'];
    protected $returnType = 'array';
    protected $allowedFields = ['id_liste', 'id_prod'];
}