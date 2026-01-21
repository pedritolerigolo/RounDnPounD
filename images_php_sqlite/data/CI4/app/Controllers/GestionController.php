<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class GestionController extends BaseController
{
    protected $produitModel;
    protected $fournisseurModel;
    protected $ingredientModel;
    protected $clientModel;
    protected $adresseModel;
    protected $commandeModel;

    public function __construct()
    {
        $this->produitModel = new \App\Models\ProduitModel();
        $this->fournisseurModel = new \App\Models\FournisseurModel();
        $this->ingredientModel = new \App\Models\IngredientModel();
        $this->clientModel = new \App\Models\ClientModel();
        $this->adresseModel = new \App\Models\AdresseModel();
        $this->commandeModel = new \App\Models\CommandeModel();
    }

    public function index()
    {
        // Vérifie si l'utilisateur est connecté
        if (!auth()->loggedIn()) {
            return redirect()->to(site_url('produits'))->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }

        // Récupère les informations de l'utilisateur connecté
        $user = auth()->user();
        $data = [
            'user' => $user,
            'isAdmin' => $user->inGroup('admin'),
        ];

        return view('gestion/index', $data);
    }

    public function passwordView()
    {
        // Vérifie si l'utilisateur est connecté
        if (!auth()->loggedIn()) {
            return redirect()->to(site_url('produits'))->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }
        return view('gestion/change_password');
    }

    public function passwordUpdate()
    {
        // Définit les règles de validation pour le mot de passe
        $rules = [
            'password' => 'required|strong_password',
            'password_confirm' => 'required|matches[password]',
        ];

        // Vérifie la validité des données
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Met à jour le mot de passe de l'utilisateur
        $user = auth()->user();
        $user->fill(['password' => $this->request->getPost('password')]);

        $users = auth()->getProvider();
        $users->save($user);

        return redirect()->to(site_url('gestion'))->with('message', 'Votre mot de passe a été mis à jour avec succès.');
    }

    public function deleteAccount()
    {
        // Vérifie si l'utilisateur est connecté
        if (!auth()->loggedIn()) {
            return redirect()->to(site_url('login'));
        }

        $user = auth()->user();
        $userId = auth()->id();

        // Vérifie si l'utilisateur a des commandes en cours
        $client = $this->clientModel->getClientWithCommandesEnCours($userId);

        if ($client && $client['nb_commandes_en_cours'] > 0) {
            return redirect()->to(site_url('gestion'))->with(
                'error',
                "Suppression impossible : vous avez encore {$client['nb_commandes_en_cours']} commande(s) en cours de traitement."
            );
        }

        // Empêche la suppression du dernier administrateur
        if ($user->inGroup('admin')) {
            if (!$this->clientModel->canDeleteAdmin()) {
                return redirect()->to(site_url('gestion'))->with('error', 'Impossible de supprimer le dernier compte administrateur.');
            }
        }

        // Supprime le compte utilisateur
        $this->clientModel->deleteUserAccount($userId);
        auth()->logout();

        return redirect()->to(site_url('produits'))->with('message', 'Votre compte a bien été supprimé.');
    }

    public function listClients()
    {
        // Vérifie si l'utilisateur est administrateur
        if (!auth()->loggedIn() || !auth()->user()->inGroup('admin')) {
            return redirect()->to(site_url('produits'))->with('error', 'Vous devez être connecté et administrateur pour accéder à cette page.');
        }

        // Récupère tous les clients non-administrateurs
        $data = [
            'clients' => $this->clientModel->getAllNonAdminClients(),
            'user' => auth()->user(),
            'isAdmin' => true
        ];

        return view('admin/list_clients', $data);
    }

    public function makeAdmin($userId)
    {
        // Promeut un utilisateur au rang d'administrateur
        $result = $this->clientModel->promoteToAdmin($userId);

        if ($result['success']) {
            return redirect()->back()->with('message', $result['message']);
        }

        return redirect()->back()->with('error', $result['message']);
    }

    public function listFournisseurs()
    {
        // Vérifie si l'utilisateur est administrateur
        if (!auth()->loggedIn() || !auth()->user()->inGroup('admin')) {
            return redirect()->to(site_url('produits'))->with('error', 'Vous devez être connecté et administrateur pour accéder à cette page.');
        }

        // Récupère tous les fournisseurs triés par ID décroissant
        $data = [
            'fournisseurs' => $this->fournisseurModel->getAllOrderedByIdDesc(),
            'user' => auth()->user(),
            'isAdmin' => true
        ];

        return view('admin/list_fournisseurs', $data);
    }

    public function deleteFournisseur($id)
    {
        // Supprime un fournisseur après vérification
        $result = $this->fournisseurModel->deleteFournisseurWithCheck($id);

        if (!$result['success']) {
            return redirect()->back()->with('error', $result['message']);
        }

        return redirect()->to(site_url('admin/fournisseurs'))->with('message', $result['message']);
    }

    public function addFournisseurView()
    {
        // Vérifie si l'utilisateur est administrateur
        if (!auth()->loggedIn() || !auth()->user()->inGroup('admin')) {
            return redirect()->to(site_url('produits'))->with('error', 'Vous devez être connecté et administrateur pour accéder à cette page.');
        }
        return view('admin/add_fournisseur');
    }

    public function createFournisseurAction()
    {
        // Définit les règles de validation pour un nouveau fournisseur
        $rules = [
            'nom' => 'required|min_length[2]|max_length[255]',
            'contact' => 'required|min_length[5]|max_length[255]',
            'adresse' => 'required|min_length[5]|max_length[255]',
        ];

        // Vérifie la validité des données
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Prépare les données du fournisseur
        $data = [
            'nom' => $this->request->getPost('nom'),
            'contact' => $this->request->getPost('contact'),
            'adresse' => $this->request->getPost('adresse'),
        ];

        // Enregistre le nouveau fournisseur
        if ($this->fournisseurModel->insert($data)) {
            return redirect()->to(site_url('admin/fournisseurs'))->with('message', 'Fournisseur ajouté avec succès !');
        }

        return redirect()->back()->withInput()->with('error', "Une erreur est survenue lors de l'enregistrement.");
    }

    public function editFournisseurView($id)
    {
        // Vérifie si l'utilisateur est administrateur
        if (!auth()->loggedIn() || !auth()->user()->inGroup('admin')) {
            return redirect()->to(site_url('produits'))->with('error', 'Vous devez être connecté et administrateur pour accéder à cette page.');
        }

        // Récupère le fournisseur à modifier
        $fournisseur = $this->fournisseurModel->find($id);

        if (!$fournisseur) {
            return redirect()->to(site_url('admin/fournisseurs'))->with('error', "Fournisseur introuvable.");
        }

        return view('admin/edit_fournisseur', ['fournisseur' => $fournisseur]);
    }

    public function updateFournisseurAction($id)
    {
        // Définit les règles de validation pour la mise à jour
        $rules = [
            'nom' => 'required|min_length[2]|max_length[255]',
            'contact' => 'required|min_length[5]|max_length[255]',
            'adresse' => 'required|min_length[5]|max_length[255]',
        ];

        // Vérifie la validité des données
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Prépare les données mises à jour
        $data = [
            'nom' => $this->request->getPost('nom'),
            'contact' => $this->request->getPost('contact'),
            'adresse' => $this->request->getPost('adresse'),
        ];

        // Met à jour le fournisseur
        if ($this->fournisseurModel->update($id, $data)) {
            return redirect()->to(site_url('admin/fournisseurs'))->with('message', 'Fournisseur mis à jour !');
        }

        return redirect()->back()->with('error', "Erreur lors de la mise à jour.");
    }

    public function dupliquerFournisseur($idOriginal)
    {
        // Duplique un fournisseur existant
        $result = $this->fournisseurModel->duplicateFournisseur($idOriginal);

        if ($result['success']) {
            return redirect()->back()->with('message', $result['message']);
        }

        return redirect()->back()->with('error', $result['message']);
    }

    public function listIngredients()
    {
        // Vérifie si l'utilisateur est administrateur
        if (!auth()->loggedIn() || !auth()->user()->inGroup('admin')) {
            return redirect()->to(site_url('produits'))->with('error', 'Vous devez être connecté et administrateur pour accéder à cette page.');
        }

        // Récupère tous les ingrédients avec le nom du fournisseur
        $data = [
            'ingredients' => $this->ingredientModel->getAllWithFournisseurName(),
            'user' => auth()->user(),
            'isAdmin' => true
        ];

        return view('admin/list_ingredients', $data);
    }

    public function deleteIngredient($id)
    {
        // Supprime un ingrédient après vérification
        $result = $this->ingredientModel->deleteIngredientWithCheck($id);

        if (!$result['success']) {
            return redirect()->back()->with('error', $result['message']);
        }

        return redirect()->to(site_url('admin/ingredients'))->with('message', $result['message']);
    }

    public function addIngredientView()
    {
        // Vérifie si l'utilisateur est administrateur
        if (!auth()->loggedIn() || !auth()->user()->inGroup('admin')) {
            return redirect()->to(site_url('produits'))->with('error', 'Vous devez être connecté et administrateur pour accéder à cette page.');
        }

        // Récupère tous les fournisseurs pour le formulaire
        $data['allFournisseurs'] = $this->fournisseurModel->findAll();
        return view('admin/add_ingredient', $data);
    }

    public function createIngredientAction()
    {
        // Définit les règles de validation pour un nouvel ingrédient
        $rules = [
            'nom' => 'required|min_length[2]|max_length[255]',
            'quantite' => 'required|integer|greater_than[0]',
            'fournisseur' => 'required'
        ];

        // Vérifie la validité des données
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Prépare les données de l'ingrédient
        $data = [
            'nom' => $this->request->getPost('nom'),
            'quantite' => $this->request->getPost('quantite'),
            'fournisseur' => $this->request->getPost('fournisseur'),
        ];

        // Enregistre le nouvel ingrédient
        $idIngredient = $this->ingredientModel->insert($data);

        if ($idIngredient) {

            $allergeneModel = new \App\Models\AllergeneModel();
            // On récupère le tableau des cases cochées de la vue
            $selectedAllergenes = $this->request->getPost('allergenes') ?? [];

            $colonnes = $allergeneModel->allowedFields;
            unset($colonnes[0]); // Retire 'id_ingredient' pour la boucle

            $dataAllergenes = ['id_ingredient' => $idIngredient];

            foreach ($colonnes as $colonne) {
                $dataAllergenes[$colonne] = in_array($colonne, $selectedAllergenes) ? 1 : 0;
            }

            // Insertion dans la table Allergenes
            $allergeneModel->insert($dataAllergenes);
        }

        return redirect()->to(site_url('admin/ingredients'))->with('message', 'Ingrédient et allergènes ajoutés avec succès !');
    }

    public function editIngredientView($id)
    {
        // Vérifie si l'utilisateur est administrateur
        if (!auth()->loggedIn() || !auth()->user()->inGroup('admin')) {
            return redirect()->to(site_url('produits'))->with('error', 'Vous devez être connecté et administrateur pour accéder à cette page.');
        }

        // Récupère l'ingrédient à modifier
        $ingredient = $this->ingredientModel->find($id);

        if (!$ingredient) {
            return redirect()->to(site_url('admin/ingredients'))->with('error', "Ingrédient introuvable.");
        }

        // Récupère tous les fournisseurs pour le formulaire
        $data['allFournisseurs'] = $this->fournisseurModel->findAll();
        $data['ingredient'] = $ingredient;
        return view('admin/edit_ingredient', $data);
    }

    public function updateIngredientAction($id)
    {
        // Définit les règles de validation pour la mise à jour
        $rules = [
            'nom' => 'required|min_length[2]|max_length[255]',
            'quantite' => 'required|integer|greater_than[0]',
        ];

        // Vérifie la validité des données
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Prépare les données mises à jour
        $data = [
            'nom' => $this->request->getPost('nom'),
            'quantite' => $this->request->getPost('quantite'),
            'fournisseur' => $this->request->getPost('fournisseur'),
        ];

        // Met à jour l'ingrédient
        if ($this->ingredientModel->update($id, $data)) {
            return redirect()->to(site_url('admin/ingredients'))->with('message', 'Ingrédient mis à jour !');
        }

        return redirect()->back()->with('error', "Erreur lors de la mise à jour.");
    }

    public function ajouteraddresseView()
    {
        // Vérifie si l'utilisateur est connecté
        if (!auth()->loggedIn()) {
            return redirect()->to(site_url('produits'))->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }

        // Récupère l'URL de la page précédente pour le retour
        $urlPrecedente = previous_url() ?? site_url('commande/checkout');

        return view('gestion/form_adresse', [
            'urlRetour' => $urlPrecedente
        ]);
    }

    public function sauvegarderaddresse()
    {
        // Définit les règles de validation pour une nouvelle adresse
        $rules = [
            'ville' => 'required',
            'code_postal' => 'required|integer',
            'addresse_complete' => 'required',
            'pays' => 'required',
        ];

        // Vérifie la validité des données
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Récupère l'URL de retour
        $urlprecedente = $this->request->getPost('url_retour') ?? site_url('commande/checkout');

        // Prépare les données de l'adresse
        $data = [
            'client' => auth()->id(),
            'ville' => $this->request->getPost('ville'),
            'code_postal' => $this->request->getPost('code_postal'),
            'addresse_complete' => $this->request->getPost('addresse_complete'),
            'pays' => $this->request->getPost('pays'),
            'info_suppl' => $this->request->getPost('info_suppl'),
        ];

        // Enregistre la nouvelle adresse
        $this->adresseModel->insert($data);

        return redirect()->to($urlprecedente)->with('message', 'Nouvelle adresse ajoutée !');
    }

    public function listAdresses()
    {
        // Vérifie si l'utilisateur est connecté
        if (!auth()->loggedIn()) {
            return redirect()->to(site_url('produits'))->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }

        // Récupère toutes les adresses du client connecté
        $data['adresses'] = $this->adresseModel->getAdressesByClient(auth()->id());

        return view('gestion/list_adresses', $data);
    }

    public function deleteAdresse($id)
    {
        // Supprime l'adresse si elle appartient à l'utilisateur
        $result = $this->adresseModel->deleteAdresseIfOwner($id, auth()->id());

        if ($result['success']) {
            return redirect()->to(site_url('compte/adresses'))->with('message', $result['message']);
        }

        return redirect()->to(site_url('compte/adresses'))->with('error', $result['message']);
    }

    public function listCommandes()
    {
        // Vérifie si l'utilisateur est connecté
        if (!auth()->loggedIn()) {
            return redirect()->to(site_url('produits'))->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }

        // Récupère toutes les commandes de l'utilisateur
        $data['commandes'] = $this->commandeModel->getCommandesUtilisateur(auth()->id());

        return view('gestion/list_commandes', $data);
    }

    public function listToutesCommandes()
    {
        // Vérifie si l'utilisateur est administrateur
        if (!auth()->loggedIn() || !auth()->user()->inGroup('admin')) {
            return redirect()->to(site_url('produits'))->with('error', 'Accès restreint.');
        }

        // Récupère toutes les commandes avec l'email du client
        $commandes = $this->commandeModel->getAllCommandesWithClientEmail();

        return view('admin/list_commandes', ['commandes' => $commandes]);
    }
}