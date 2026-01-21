<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RounDnPounD | Panier</title>

    <link rel="icon" type="image/png" href="<?= base_url('assets/images/favicon.png') ?>" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/css/panier.css') ?>" />
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
            <a href="<?= site_url('favoris') ?>" class="icon-text">
                <span class="material-icons">star</span>
                <span>Favoris</span>
            </a>

            <a href="<?= site_url('panier') ?>" class="used-icon-text">
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

    <?php if (session('message')): ?>
        <div id="flash-message"
            style="position: fixed; top: 20px; right: 20px; background: #28a745; color: white; padding: 15px 25px; border-radius: 8px; z-index: 10000; box-shadow: 0 4px 12px rgba(0,0,0,0.2); display: flex; align-items: center; gap: 10px;">
            <span class="material-icons">check_circle</span>
            <?= session('message') ?>
        </div>

        <script>
            setTimeout(() => {
                const msg = document.getElementById('flash-message');
                if (msg) msg.style.display = 'none';
            }, 3000);
        </script>
    <?php endif; ?>

    <main class="panier-container" style="user-select: none;">

        <?php if (empty($produits)): ?>
            <div class="no-results">
                <p>Votre panier est vide.</p>
                <a href="<?= site_url('produits') ?>" class="back-link">Continuer mes achats</a>
            </div>
        <?php else: ?>
            <div class="panier-layout">
                <div class="panier-list">
                    <?php foreach ($produits as $p): ?>
                        <div class="panier-item">
                            <a href="<?= site_url('produits/show/' . $p->id) ?>">
                                <img src="<?= base_url('assets/images/produits/' . $p->Image) ?>" alt="<?= esc($p->nom) ?>">
                            </a>

                            <div class="item-info">
                                <a href="<?= site_url('produits/show/' . $p->id) ?>"
                                    style="text-decoration: none; color: inherit;">
                                    <h3><?= esc($p->nom) ?></h3>
                                </a>
                                <p class="unit-price">Prix unitaire : <?= number_format($p->prix, 2) ?> €</p>

                                <div class="quantity-controls"
                                    style="display: flex; align-items: center; gap: 10px; margin: 10px 0;">
                                    <a href="<?= site_url('panier/diminuer/' . $p->id) ?>" class="btn-qty"
                                        style="color: #003366; text-decoration: none;">
                                        <span class="material-icons"
                                            style="vertical-align: middle;">remove_circle_outline</span>
                                    </a>

                                    <span style="font-weight: bold; font-size: 1.1rem; min-width: 20px; text-align: center;">
                                        <?= $p->quantite ?>
                                    </span>

                                    <a href="<?= site_url('panier/ajouter/' . $p->id) ?>" class="btn-qty"
                                        style="color: #003366; text-decoration: none;">
                                        <span class="material-icons" style="vertical-align: middle;">add_circle_outline</span>
                                    </a>
                                </div>

                                <div class="item-total-price" style="font-weight: bold; color: #003366;">
                                    Sous-total : <?= number_format($p->prix * $p->quantite, 2) ?> €
                                </div>
                            </div>

                            <a href="<?= site_url('panier/supprimer/' . $p->id) ?>" class="delete-btn" title="Supprimer tout">
                                <span class="material-icons" style="color: #d32f2f;">delete</span>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="panier-summary">
                    <h3>Résumé</h3>
                    <div class="total-line">
                        <span>Total :</span>
                        <strong><?= number_format($total, 2) ?> €</strong>
                    </div>
                    <button class="btn-checkout" onclick="window.location.href='<?= site_url('commande/addressegetter/panier') ?>'">Commander</button>
                </div>
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