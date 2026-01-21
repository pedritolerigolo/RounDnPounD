<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RounDnPounD | Merci</title>

    <link rel="icon" type="image/png" href="<?= base_url('assets/images/favicon.png') ?>" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/css/footer.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/css/merci.css') ?>" />
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

    <?php if (session('message')): ?>
        <div id="flash-message"
            style="position: fixed; top: 20px; right: 20px; background: #28a745; color: white; padding: 15px 25px; border-radius: 8px; z-index: 1000; box-shadow: 0 4px 12px rgba(0,0,0,0.2); display: flex; align-items: center; gap: 10px;">
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

    <main class="thanks-container">
        <div class="thanks-card">
            <div class="success-checkmark">
                <div class="check-icon">
                    <span class="icon-line line-tip"></span>
                    <span class="icon-line line-long"></span>
                    <div class="icon-circle"></div>
                    <div class="icon-fix"></div>
                </div>
            </div>

            <h1>Paiement Réussi !</h1>
            <p class="order-id">Commande <strong>#<?= esc($orderNumber ?? '12345') ?></strong></p>

            <div class="message">
                <p>Merci pour votre confiance. Votre commande est en cours de préparation par nos chefs.</p>
                <p>Vous allez recevoir un e-mail de confirmation d'ici quelques instants.</p>
            </div>

            <div class="actions">
                <a href="<?= site_url('produits') ?>" class="btn-primary">
                    <span class="material-icons">shopping_bag</span>
                    Continuer mes achats
                </a>
                <a href="<?= site_url('compte/commandes') ?>" class="btn-secondary">
                    <span class="material-icons">receipt_long</span>
                    Suivre ma commande
                </a>
            </div>
        </div>
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