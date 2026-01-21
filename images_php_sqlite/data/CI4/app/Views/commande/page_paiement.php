<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RounDnPounD | Paiement</title>

    <link rel="icon" type="image/png" href="<?= base_url('assets/images/favicon.png') ?>" />

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik+Scribble&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/css/connexion.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/css/footer.css') ?>" />
</head>

<body>
    <header class="header">
        <div class="logo">
            <a href="<?= base_url('produits') ?>"><img src="<?= base_url('assets/images/favicon.png') ?>"
                    alt="logo" /></a>
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
            <a href="<?= site_url('favoris') ?>" class="icon-text"><span class="material-icons">star</span>
                <span>Favoris</span></a>
            <a href="<?= site_url('panier') ?>" class="icon-text"><span class="material-icons">shopping_cart</span>
                <span>Panier</span></a>

            <?php if (auth()->loggedIn()): ?>
                <?php if (auth()->user()->inGroup('admin')): ?>
                    <a href="<?= site_url('gestion') ?>" class="used-icon-text">
                        <span class="material-icons">settings</span>
                        <span>Gestion</span>
                    </a>
                <?php else: ?>
                    <a href="<?= site_url('gestion') ?>" class="used-icon-text">
                        <span class="material-icons">account_circle</span>
                        <span>Compte</span>
                    </a>
                <?php endif; ?>
                <a href="<?= site_url('logout') ?>" class="icon-text" title="Déconnexion">
                    <span class="material-icons">logout</span>
                </a>
            <?php else: ?>
                <a href="<?= site_url('login') ?>" class="icon-text"><span class="material-icons">person</span>
                    <span>Connexion</span></a>
            <?php endif; ?>
        </nav>
    </header>

    <main>
        <?php if (session()->getFlashdata('error')): ?>
            <div
                style="color: #fff; background: #d32f2f; padding: 15px; border-radius: 12px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>
        <div class="payment-box">
            <h2>💳 Paiement Sécurisé</h2>
            <p>Montant à régler : <strong><?= number_format($commande->prixTotal, 2) ?> €</strong></p>

            <div class="card-simulation">
                <p>**** **** **** 4242</p>
                <p>Détenteur : jsp</p>
            </div>

            <form action="<?= site_url('commande/confirmerPaiement') ?>" method="post">
                <input type="hidden" name="idContenu" value="<?= $commande->contenu ?>">
                <button type="submit" class="btn-pay" onclick="this.innerHTML='Traitement...';">
                    Confirmer le paiement
                </button>
            </form>
        </div>

        <style>
            .payment-box {
                max-width: 400px;
                margin: 50px auto;
                text-align: center;
                padding: 30px;
                border: 2px solid #eee;
                border-radius: 15px;
            }

            .card-simulation {
                background: #222;
                color: #fff;
                padding: 20px;
                border-radius: 10px;
                margin: 20px 0;
                font-family: monospace;
            }

            .btn-pay {
                background: #bb946f;
                color: white;
                border: none;
                padding: 15px 30px;
                border-radius: 8px;
                font-size: 1.2rem;
                cursor: pointer;
                width: 100%;
            }
        </style>    
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