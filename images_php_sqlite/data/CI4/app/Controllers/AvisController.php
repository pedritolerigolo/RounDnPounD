<?php

namespace App\Controllers;

use App\Models\AvisModel;
use App\Models\ProduitModel;
use App\Entities\AvisEntity;

class AvisController extends BaseController
{
    public function rediger($nomProduitSlug)
    {
        $userId = auth()->id();
        if (!$userId) {
            return redirect()->to('login')->with('error', "Connectez-vous pour noter ce produit.");
        }

        $produitModel = new ProduitModel();
        $avisModel = new AvisModel();

        // recherche du produit
        $nomProduit = str_replace('-', ' ', $nomProduitSlug);
        $produit = $produitModel->like('nom', $nomProduit)->first();

        if (!$produit) {
            return redirect()->back()->with('error', "Produit introuvable.");
        }

        // on verifie si l'utilisateur peut rédiger un avis
        if (!$avisModel->peutRedigerAvis($userId, $produit->id)) {
            return redirect()->to('produits/show/' . $produit->id)
                             ->with('error', "Vous ne pouvez pas noter ce produit (soit déjà fait, soit produit non reçu).");
        }

        return view('gestion/form_avis', ['produit' => $produit]);
    }

    public function sauvegarder()
    {
        $avisModel = new AvisModel();
        
        // création d'un nouvel avis
        $avis = new AvisEntity($this->request->getPost());
        $avis->id_client = auth()->id();

        if ($avisModel->save($avis)) {
            return redirect()->to('produits/show/' . $avis->id_produit)
                             ->with('message', "Merci pour votre avis !");
        }

        return redirect()->back()->with('error', "Une erreur est survenue lors de l'enregistrement.");
    }
}