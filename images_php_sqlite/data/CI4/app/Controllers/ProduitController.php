<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\ProduitModel;
use App\Models\IngredientModel;
use App\Models\ProduitIngredientModel;
use App\Models\AvisModel;
use App\Models\WishListModel;
use App\Models\PanierModel;

class ProduitController extends BaseController
{
    protected $produitModel;
    protected $ingredientModel;
    protected $pivotModel;
    protected $avisModel;
    protected $wishListModel;
    protected $panierModel;

    public function __construct()
    {
        $this->produitModel = new ProduitModel();
        $this->ingredientModel = new IngredientModel();
        $this->pivotModel = new ProduitIngredientModel();
        $this->avisModel = new AvisModel();
        $this->wishListModel = new WishListModel();
        $this->panierModel = new PanierModel();
    }

    public function index()
    {
        // Récupère tous les produits
        $data['produits'] = $this->produitModel->getAllProduits();
        foreach ($data['produits'] as $p) {
            log_message('debug', 'Prix brut : ' . var_export($p->prix, true));
        }
        return view('produit/liste', $data);
    }

    public function show($id = null)
    {
        // Récupère le produit par son ID
        $produit = $this->produitModel->find($id);

        if (!$produit) {
            return redirect()->to(site_url('produits'))->with('error', 'Produit non trouvé.');
        }

        // Récupère les allergènes associés au produit
        $allergeneModel = new \App\Models\AllergeneModel();
        $allergenes = $allergeneModel->getAllergenesByProduit((int) $id);

        // Récupère les informations associées au produit
        $listeIngredients = $produit->getListeIngredients();
        $avis = $this->avisModel->getAvisByProduit($id);
        $moyenneNote = $this->avisModel->getMoyenneNote($id);

        $userId = auth()->id();
        $inWishlist = false;
        $quantiteDansPanier = 0;

        // Vérifie si l'utilisateur est connecté pour récupérer ses données
        if (auth()->loggedIn()) {
            $inWishlist = $this->wishListModel->isInWishlist($userId, $id);
            $quantiteDansPanier = $this->panierModel->getQuantiteProduit($userId, $id);
        }

        $data = [
            'produit' => $produit,
            'listeIngredients' => $listeIngredients,
            'allergenes' => $allergenes,
            'inWishlist' => $inWishlist,
            'quantiteDansPanier' => $quantiteDansPanier,
            'avis' => $avis,
            'moyenneNote' => $moyenneNote
        ];

        return view('produit/details', $data);
    }

    public function toggleWishlist($productId)
    {
        // Vérifie si l'utilisateur est connecté
        if (!auth()->loggedIn()) {
            return redirect()->to(site_url('login'));
        }

        // Ajoute ou retire le produit de la liste de souhaits
        $result = $this->wishListModel->toggleWishlist(auth()->id(), $productId);
        return redirect()->back()->with('message', $result['message']);
    }

    public function listProduits()
    {
        // Vérifie si l'utilisateur est administrateur
        if (!auth()->loggedIn() || !auth()->user()->inGroup('admin')) {
            return redirect()->to(site_url('produits'))->with('error', 'Vous devez être connecté et administrateur pour accéder à cette page.');
        }

        // Récupère tous les produits pour l'administration
        $data = [
            'produits' => $this->produitModel->getProduitsForAdmin(),
            'user' => auth()->user(),
            'isAdmin' => true
        ];

        return view('admin/list_produits', $data);
    }

    public function deleteProduit($id)
    {
        // Supprime le produit et ses dépendances
        $result = $this->produitModel->deleteProduitWithDependencies($id);

        if ($result) {
            return redirect()->to(site_url('admin/produits'))->with('message', 'Produit supprimé.');
        }

        return redirect()->to(site_url('admin/produits'))->with('error', 'Erreur lors de la suppression.');
    }

    public function addProduitView()
    {
        // Vérifie si l'utilisateur est administrateur
        if (!auth()->loggedIn() || !auth()->user()->inGroup('admin')) {
            return redirect()->to(site_url('produits'))->with('error', 'Vous devez être connecté et administrateur pour accéder à cette page.');
        }

        // Récupère tous les ingrédients pour le formulaire
        $data['allIngredients'] = $this->ingredientModel->findAll();
        return view('admin/add_produit', $data);
    }

    public function createProduitAction()
    {
        // Définit les règles de validation pour un nouveau produit
        $rules = [
            'nom' => 'required|min_length[3]',
            'prix' => 'required|decimal|greater_than[0]',
            'Image' => [
                'label' => 'Image du produit',
                'rules' => 'uploaded[Image]|is_image[Image]|mime_in[Image,image/jpg,image/jpeg,image/png,image/webp,image/gif]'
            ]
        ];

        // Vérifie la validité des données
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Upload de l'image du produit
        $img = $this->request->getFile('Image');
        $newName = $img->getRandomName();
        $img->move(FCPATH . 'assets/images/produits', $newName);

        // Prépare les données du produit
        $dataProduit = [
            'nom' => $this->request->getPost('nom'),
            'descr' => $this->request->getPost('descr'),
            'prix' => $this->request->getPost('prix'),
            'Image' => $newName
        ];

        // Crée le produit avec ses ingrédients
        $ingredientsSelected = $this->request->getPost('Ingredients') ?? [];
        $newProduitId = $this->produitModel->createProduitWithIngredients($dataProduit, $ingredientsSelected);

        if ($newProduitId) {
            return redirect()->to(site_url('admin/produits'))->with('message', 'Produit créé et ingrédients mis à jour !');
        }

        return redirect()->back()->withInput()->with('error', 'Erreur lors de la création du produit.');
    }

    public function editProduitView($id)
    {
        // Vérifie si l'utilisateur est administrateur
        if (!auth()->loggedIn() || !auth()->user()->inGroup('admin')) {
            return redirect()->to(site_url('produits'))->with('error', 'Vous devez être connecté et administrateur pour accéder à cette page.');
        }

        // Récupère le produit à modifier
        $produit = $this->produitModel->find($id);

        if (!$produit) {
            return redirect()->to(site_url('admin/produits'))->with('error', "Produit introuvable.");
        }

        // Récupère les avis et la note moyenne du produit
        $avis = $this->avisModel->getAvisByProduit($id);
        $moyenneNote = $this->avisModel->getMoyenneNote($id);

        return view('admin/edit_produit', [
            'produit' => $produit,
            'avis' => $avis,
            'moyenneNote' => $moyenneNote
        ]);
    }

    public function deleteAvis($idAvis)
    {
        // Vérifie si l'utilisateur est administrateur
        if (!auth()->loggedIn() || !auth()->user()->inGroup('admin')) {
            return redirect()->to(site_url('produits'))->with('error', 'Accès refusé.');
        }

        // Supprime l'avis sélectionné
        $result = $this->avisModel->deleteAvisById($idAvis);

        if ($result['success']) {
            return redirect()->back()->with('message', $result['message']);
        }

        return redirect()->back()->with('error', $result['message']);
    }

    public function updateProduitAction($id)
    {
        // Définit les règles de validation pour la mise à jour
        $rules = [
            'nom' => 'required|min_length[3]',
            'prix' => 'required|numeric',
        ];

        // Vérifie la validité des données
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Prépare les données mises à jour
        $data = [
            'nom' => $this->request->getPost('nom'),
            'descr' => $this->request->getPost('descr'),
            'prix' => $this->request->getPost('prix'),
        ];

        // Met à jour le produit
        if ($this->produitModel->update($id, $data)) {
            return redirect()->to(site_url('admin/produits'))->with('message', 'Produit mis à jour !');
        }

        return redirect()->back()->with('error', "Erreur lors de la mise à jour.");
    }

    public function rechercher()
    {
        // Récupère la requête de recherche et recherche les produits correspondants
        $query = $this->request->getGet('query');
        $resultats = $this->produitModel->rechercherProduitsAvecIngredients($query);
        $selectedFilters = $this->request->getGet('ingredients') ?? [];
        // On prends aussi les ingredients pour les filtres
        $allIngredients = $this->ingredientModel->findAll();

        return view('produit/recherche_resultats', [
            'produits' => $resultats,
            'searchQuery' => $query,
            'allIngredients' => $allIngredients,
            'selectedFilters' => $selectedFilters
        ]);
    }

    public function suggestions()
    {
        // Récupère les suggestions de produits pour l'autocomplétion
        $query = $this->request->getGet('query');
        $resultats = $this->produitModel->getSuggestions($query);

        return $this->response
            ->setContentType('application/json')
            ->setJSON($resultats);
    }

    public function listeAllergenesProduits()
    {
        $produitModel = new ProduitModel();
        $data = [
            'titre' => 'Tableau des Allergènes par Produit',
            'produits_allergenes' => $produitModel->getAllergenesParProduit(),
            'colonnes' => [
                'gluten',
                'crustaces',
                'oeufs',
                'poissons',
                'arachides',
                'soja',
                'lait',
                'fruits_a_coque',
                'celeri',
                'moutarde',
                'sesame',
                'sulfites',
                'lupin',
                'mollusques'
            ]
        ];

        return view('allergenes_produits', $data);
    }
}