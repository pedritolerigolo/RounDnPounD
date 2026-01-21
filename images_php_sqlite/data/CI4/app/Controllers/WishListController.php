<?php

namespace App\Controllers;

use App\Models\WishListModel;
use App\Models\PanierModel;

class WishListController extends BaseController
{
    public function index()
    {
        if (!auth()->loggedIn()) {
            return redirect()->to(site_url('login'));
        }

        $userId = auth()->id();
        
        //instanciation des modèles
        $wishModel = new WishListModel();
        $panierModel = new PanierModel();

        //récupération des données via les modèles
        $data = [
            'favoris'         => $wishModel->getFavorisByUser($userId),
            'quantitesPanier' => $panierModel->getQuantitesProduits($userId)
        ];

        return view('produit/favoris', $data);
    }
}