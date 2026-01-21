<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('about', 'Home::about');
$routes->get('allergenes', 'ProduitController::listeAllergenesProduits');

//produits
$routes->get('/produits', 'ProduitController::index');

$routes->get('produits/recherche', 'ProduitController::rechercher');
$routes->get('produits/suggestions', 'ProduitController::suggestions');

$routes->get('/produits/show/(:num)', 'ProduitController::show/$1');
$routes->get('produits/toggleWishlist/(:num)', 'ProduitController::toggleWishlist/$1');

$routes->get('favoris', 'WishListController::index');

$routes->get('panier', 'PanierController::index');
$routes->get('panier/ajouter/(:num)', 'PanierController::ajouterAuPanier/$1');
$routes->get('panier/supprimer/(:num)', 'PanierController::supprimerDuPanier/$1');
$routes->get('panier/diminuer/(:num)', 'PanierController::retirerUnDuPanier/$1');
$routes->get('panier/vider', 'PanierController::viderPanier');

$routes->get('commande/checkout', 'CommandeController::checkout');
$routes->get('commande/addressegetter/(:any)', 'CommandeController::checkout/$1');
$routes->post('commande/traiter', 'CommandeController::traiterCommande');
$routes->get('commande/paiement/(:num)', 'CommandeController::paiement/$1');
$routes->post('commande/confirmerPaiement', 'CommandeController::confirmerPaiement');


//cookies
$routes->get('/cookies/accept', 'Cookies::accept');
$routes->get('/cookies/decline', 'Cookies::decline');

//comptes
$routes->get('/login', 'Login::loginView');
$routes->get('/register', 'Register::registerView');
$routes->get('/auth/logout', 'Login::logoutAction');

$routes->post('/auth/login', 'Login::loginAction');
$routes->post('/auth/register', 'Register::registerAction');

$routes->get('mot-de-passe-oublie', '\CodeIgniter\Shield\Controllers\MagicLinkController::loginView');
$routes->post('mot-de-passe-oublie', '\CodeIgniter\Shield\Controllers\MagicLinkController::loginAction');

$routes->get('compte/adresses', 'GestionController::listAdresses');
$routes->get('adresse/ajouter', 'GestionController::ajouteraddresseView');
$routes->post('adresse/sauvegarder', 'GestionController::sauvegarderaddresse');
$routes->get('delete-adresse/(:num)', 'GestionController::deleteAdresse/$1');

$routes->get('compte/commandes', 'GestionController::listCommandes');
$routes->get('envoie-facture/(:num)', 'CommandeController::envoyerFactureMail/$1');
$routes->get('consulte-commande/(:num)', 'CommandeController::consulterCommande/$1');
$routes->get('telecharge-facture/(:num)', 'CommandeController::telechargerFacture/$1');

$routes->get('avis/rediger/(:segment)', 'AvisController::rediger/$1');
$routes->post('avis/sauvegarder', 'AvisController::sauvegarder');

$routes->group('SAV', function ($routes) {
    $routes->get('demande-remboursement/(:any)', 'SAVController::demandeRemboursement/$1');
    $routes->post('traiterRemboursement', 'SAVController::traiterRemboursement');
});

service('auth')->routes($routes);

//gestion
$routes->get('/gestion', 'GestionController::index');

$routes->get('compte/modifier-password', 'GestionController::passwordView');
$routes->post('compte/update-password', 'GestionController::passwordUpdate');

$routes->get('compte/supprimer', 'GestionController::deleteAccount');

$routes->group('admin', ['filter' => 'group:admin'], static function ($routes) {
    $routes->get('clients', 'GestionController::listClients', ['filter' => 'group:admin']);
    $routes->get('make-admin/(:num)', 'GestionController::makeAdmin/$1', ['filter' => 'group:admin']);
    $routes->get('produits', 'ProduitController::listProduits', ['filter' => 'group:admin']);
    $routes->get('delete-produit/(:num)', 'ProduitController::deleteProduit/$1', ['filter' => 'group:admin']);
    $routes->get('add-produit', 'ProduitController::addProduitView', ['filter' => 'group:admin']);
    $routes->post('create-produit', 'ProduitController::createProduitAction', ['filter' => 'group:admin']);
    $routes->get('edit-produit/(:num)', 'ProduitController::editProduitView/$1', ['filter' => 'group:admin']);
    $routes->post('update-produit/(:num)', 'ProduitController::updateProduitAction/$1', ['filter' => 'group:admin']);
    $routes->post('delete-avis/(:num)', 'ProduitController::deleteAvis/$1', ['filter' => 'group:admin']);
    $routes->get('fournisseurs', 'GestionController::listFournisseurs', ['filter' => 'group:admin']);
    $routes->get('delete-fournisseur/(:num)', 'GestionController::deleteFournisseur/$1', ['filter' => 'group:admin']);
    $routes->get('add-fournisseur', 'GestionController::addFournisseurView', ['filter' => 'group:admin']);
    $routes->post('create-fournisseur', 'GestionController::createFournisseurAction', ['filter' => 'group:admin']);
    $routes->get('edit-fournisseur/(:num)', 'GestionController::editFournisseurView/$1', ['filter' => 'group:admin']);
    $routes->post('update-fournisseur/(:num)', 'GestionController::updateFournisseurAction/$1', ['filter' => 'group:admin']);
    $routes->get('dupliquer-fournisseur/(:num)', 'GestionController::dupliquerFournisseur/$1', ['filter' => 'group:admin']);
    $routes->get('ingredients', 'GestionController::listIngredients', ['filter' => 'group:admin']);
    $routes->get('delete-ingredient/(:num)', 'GestionController::deleteIngredient/$1', ['filter' => 'group:admin']);
    $routes->get('add-ingredient', 'GestionController::addIngredientView', ['filter' => 'group:admin']);
    $routes->post('create-ingredient', 'GestionController::createIngredientAction', ['filter' => 'group:admin']);
    $routes->get('edit-ingredient/(:num)', 'GestionController::editIngredientView/$1', ['filter' => 'group:admin']);
    $routes->post('update-ingredient/(:num)', 'GestionController::updateIngredientAction/$1', ['filter' => 'group:admin']);
    $routes->get('commandes', 'GestionController::listToutesCommandes', ['filter' => 'group:admin']);
    $routes->get('change-statut/(:num)/(:any)', 'CommandeController::changeStatut/$1/$2', ['filter' => 'group:admin']);
    $routes->get('remboursements', 'SAVController::listeRemboursements', ['filter' => 'group:admin']);
    $routes->post('remboursements/valider/(:num)', 'SAVController::validerRemboursement/$1', ['filter' => 'group:admin']);
    $routes->post('remboursements/rejeter/(:num)', 'SAVController::rejeterRemboursement/$1', ['filter' => 'group:admin']);
});