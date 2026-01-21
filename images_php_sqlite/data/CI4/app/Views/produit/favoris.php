<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RounDnPounD | Liste De Souhait</title>

    <link rel="icon" type="image/png" href="<?= base_url('assets/images/favicon.png') ?>" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/css/favoris.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/css/footer.css') ?>" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body>
    <header class="header">
        <div class="logo">
            <a href=<?= site_url('produits') ?>><img src="<?= base_url('assets/images/favicon.png') ?>" alt="logo" /></a>
        </div>

        <form action="<?= site_url('produits/recherche') ?>" method="get" class="search-bar">
            <input type="text" name="query" id="search-input" placeholder="Rechercher un produit..."
                value="<?= esc($searchQuery ?? '') ?>" autocomplete="off" />
            <button type="submit">
                <span class="material-symbols-outlined">search</span>
            </button>
            <div id="search-suggestions" class="suggestions-box"></div>
        </form>

        <nav class="header-buttons">
            <a href="<?= site_url('favoris') ?>" class="used-icon-text">
                <span class="material-icons">star</span>
                <span>Favoris</span>
            </a>

            <a href="<?= site_url('panier') ?>" class="icon-text">
                <span class="material-icons">shopping_cart</span>
                <span>Panier</span>
            </a>

            <?php if (auth()->loggedIn()): ?>
                <?php if (auth()->user()->inGroup('admin')): ?>
                    <a href="<?= site_url('gestion') ?>" class="icon-text">
                        <span class="material-icons">settings</span>
                        <span>Gestion</span>
                    </a>
                <?php else: ?>
                    <a href="<?= site_url('gestion') ?>" class="icon-text">
                        <span class="material-icons">account_circle</span>
                        <span>Compte</span>
                    </a>
                <?php endif; ?>

                <a href="<?= site_url('logout') ?>" class="icon-text" title="Déconnexion">
                    <span class="material-icons">logout</span>
                </a>

            <?php else: ?>
                <a href="<?= site_url('login') ?>" class="icon-text">
                    <span class="material-icons">person</span>
                    <span>Connexion</span>
                </a>
            <?php endif; ?>
        </nav>
    </header>


    <main class="favoris-container" style="user-select: none;">

        <?php if (empty($favoris)): ?>
            <div class="no-results">
                <p>Votre liste de souhaits est vide pour le moment.</p>
                <a href="<?= site_url('produits') ?>" class="back-link">Découvrir nos produits</a>
            </div>
        <?php else: ?>
            <div class="wishlist-grid">
                <?php foreach ($favoris as $produit): ?>
                    <div class="wishlist-item">
                        <a href="<?= site_url('produits/show/' . $produit->id) ?>">
                            <img src="<?= base_url('assets/images/produits/' . $produit->Image) ?>"
                                alt="<?= esc($produit->nom) ?>">
                        </a>

                        <div class="item-details">
                            <a href="<?= site_url('produits/show/' . $produit->id) ?>" class="product-name">
                                <?= esc($produit->nom) ?>
                            </a>

                            <div class="actions">
                                <?php
                                $qty = $quantitesPanier[$produit->id] ?? 0;
                                ?>

                                <?php if ($qty > 0): ?>
                                    <div class="qty-controls-mini"
                                        style="display: flex; align-items: center; background: #f0f0f0; border-radius: 20px; padding: 2px 8px;">
                                        <a href="<?= site_url('panier/diminuer/' . $produit->id) ?>"
                                            style="color: #003366; text-decoration: none; display: flex;">
                                            <span class="material-icons" style="font-size: 20px;">remove_circle_outline</span>
                                        </a>

                                        <span style="margin: 0 8px; font-weight: bold; font-size: 0.9rem; color: #333;">
                                            <?= $qty ?>
                                        </span>

                                        <a href="<?= site_url('panier/ajouter/' . $produit->id) ?>"
                                            style="color: #003366; text-decoration: none; display: flex;">
                                            <span class="material-icons" style="font-size: 20px;">add_circle_outline</span>
                                        </a>
                                    </div>
                                <?php else: ?>
                                    <a href="<?= site_url('panier/ajouter/' . $produit->id) ?>" class="btn-cart"
                                        title="Ajouter au panier">
                                        <span class="material-icons">shopping_cart</span>
                                    </a>
                                <?php endif; ?>

                                <a href="<?= site_url('produits/toggleWishlist/' . $produit->id) ?>" class="btn-remove"
                                    title="Retirer des favoris">
                                    <span class="material-icons">delete</span>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>


    <footer class="footer">
        <?= view('partials/footer') ?>
    </footer>

    <script src="<?= base_url('assets/js/animation.js') ?>"></script>
    <script>
        const SUGGESTIONS_URL = "<?= site_url('produits/suggestions') ?>";
        const DETAILS_URL = "<?= site_url('produits/show/') ?>";
        const BASE_IMAGE_URL = "<?= base_url('assets/images/produits/') ?>";
    </script>
    <script src="<?= base_url('assets/js/search.js') ?>"></script>

</body>

</html>