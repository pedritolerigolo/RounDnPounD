<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RounDnPounD | Modifier le mot de passe</title>

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

    <main class="profil-page">
        <div class="connexion-window">
            <header class="profil-header" style="text-align: center; margin-bottom: 20px;">
                <h2>Sécurité</h2>
                <p style="color: #666; font-size: 14px;">Choisissez un nouveau mot de passe robuste.</p>
            </header>

            <?php if (session('errors')): ?>
                <div
                    style="color: #d32f2f; background: #ffebee; padding: 10px; border-radius: 8px; margin-bottom: 15px; font-size: 13px; list-style: none;">
                    <?php foreach (session('errors') as $error): ?>
                        <li><span class="material-icons" style="font-size: 14px; vertical-align: middle;">error</span>
                            <?= $error ?></li>
                    <?php endforeach ?>
                </div>
            <?php endif ?>

            <form action="<?= site_url('compte/update-password') ?>" method="post">
                <?= csrf_field() ?>

                <label for="password">Nouveau mot de passe</label>
                <input type="password" name="password" id="password" required>

                <label for="password_confirm">Confirmer le mot de passe</label>
                <input type="password" name="password_confirm" id="password_confirm" required>


                <button type="submit"
                    style="width:100%; padding:12px; background:#003366; color:white; border:none; border-radius:8px; cursor:pointer; font-weight:bold; transition: background 0.3s;">
                    Mettre à jour
                </button>
            </form>

            <div style="margin-top: 20px; text-align: center;">
                <a href="<?= site_url('gestion') ?>"
                    style="color: #003366; font-size: 14px; text-decoration:none; border-bottom: 1px solid transparent; transition: border 0.3s;">
                    Annuler et retourner au profil
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