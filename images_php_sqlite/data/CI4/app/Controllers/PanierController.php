<?php

namespace App\Controllers;

use App\Models\PanierModel;

class PanierController extends BaseController
{
    protected $panierModel;
    protected $db;

    public function __construct() {
        $this->panierModel = new PanierModel();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        //affichage du panier
        //redirection si non connecté
        if (!auth()->loggedIn()) return redirect()->to(site_url('login'));

        $userId = auth()->id();
        $panier = $this->panierModel->where('client', $userId)->first();

        if (!$panier) {
            return view('produit/panier', ['produits' => [], 'total' => 0]);
        }

        return view('produit/panier', [
            'produits' => $this->panierModel->getProduitsDetails($panier->produits),
            'total'    => $panier->prix_total
        ]);
    }

    public function ajouterAuPanier($idProduit)
    {
        //ajout au panier
        //redirection si non connecté
        if (!auth()->loggedIn()) return redirect()->to(site_url('login'));

        $userId = auth()->id();
        $produit = $this->db->table('Produit')->where('id', $idProduit)->get()->getRow();
        if (!$produit) return redirect()->back()->with('error', 'Produit introuvable.');

        $panier = $this->panierModel->where('client', $userId)->first();

        if (!$panier) {
            $idGroupe = $userId;
            $this->panierModel->insert([
                'client' => $userId, 
                'prix_total' => $produit->prix, 
                'produits' => $idGroupe
            ]);
        } else {
            $idGroupe = $panier->produits;
            $this->panierModel->update($userId, ['prix_total' => $panier->prix_total + $produit->prix]);
        }

        $this->panierModel->updatePivot($idGroupe, $idProduit, 'add');

        return redirect()->back()->with('message', 'Produit ajouté !');
    }

    public function retirerUnDuPanier($idProduit)
    {
        //retirer un du panier
        //redirection si non connecté
        if (!auth()->loggedIn()) return redirect()->to(site_url('login'));
        $userId = auth()->id();
        $panier = $this->panierModel->where('client', $userId)->first();
        $produit = $this->db->table('Produit')->where('id', $idProduit)->get()->getRow();

        if ($panier && $produit) {
            $this->panierModel->updatePivot($panier->produits, $idProduit, 'remove_one');
            $this->panierModel->update($userId, ['prix_total' => max(0, $panier->prix_total - $produit->prix)]);
        }

        return redirect()->back()->with('message', 'Quantité mise à jour.');
    }

    public function supprimerDuPanier($idProduit)
    {
        //supprimer du panier
        //redirection si non connecté
        if (!auth()->loggedIn()) return redirect()->to(site_url('login'));
        $userId = auth()->id();
        $panier = $this->panierModel->where('client', $userId)->first();
        $produit = $this->db->table('Produit')->where('id', $idProduit)->get()->getRow();

        if ($panier && $produit) {
            $pivot = $this->db->table('ProduitsPanier')->where(['id' => $panier->produits, 'id_produit' => $idProduit])->get()->getRow();
            if ($pivot) {
                $this->panierModel->updatePivot($panier->produits, $idProduit, 'delete_all');
                $this->panierModel->update($userId, ['prix_total' => max(0, $panier->prix_total - ($produit->prix * $pivot->quantite))]);
            }
        }
        return redirect()->back()->with('message', 'Produit retiré.');
    }

    public function viderPanier()
    {
        //vider le panier
        //redirection si non connecté
        if (!auth()->loggedIn()) return redirect()->to(site_url('login'));
        $userId = auth()->id();
        $panier = $this->panierModel->where('client', $userId)->first();

        if ($panier) {
            $this->db->table('ProduitsPanier')->where('id', $panier->produits)->delete();
            $this->panierModel->delete($userId);
        }

        return redirect()->to(site_url('panier'))->with('message', 'Panier vidé.');
    }
}