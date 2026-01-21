<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RounDnPounD | Connexion</title>

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
            <input type="text" name="query" placeholder="Rechercher un produit..."
                value="<?= esc($searchQuery ?? '') ?>" />
            <button type="submit">
                <span class="material-symbols-outlined">search</span>
            </button>
        </form>

        <nav class="header-buttons">
            <a href="<?= site_url('favoris') ?>" name="favoris" id="favoris" class="icon-text"><span
                    class="material-icons">star</span> <span>Favoris</span></a>
            <a href="<?= site_url('panier') ?>" name="panier" id="panier" class="icon-text"><span
                    class="material-icons">shopping_cart</span> <span>Panier</span></a>
            <a href="<?= site_url('register') ?>" name="inscription" id="inscription" class="used-icon-text"><span
                    class="material-icons">person</span> <span>Inscription</span></a>
        </nav>
    </header>

    <main>
        <div class="connexion-window">
            <h2>Connexion</h2>

            <?php if (session('error') !== null): ?>
                <div class="errormessage" role="alert"><?= esc(session('error')) ?></div>
            <?php elseif (session('errors') !== null): ?>
                <div class="errormessage" role="alert">
                    <?php if (is_array(session('errors'))): ?>
                        <?php foreach (session('errors') as $error): ?>
                            <?= esc($error) ?>
                            <br>
                        <?php endforeach ?>
                    <?php else: ?>
                        <?= esc(session('errors')) ?>
                    <?php endif ?>
                </div>
            <?php endif ?>

            <form action="/CI4/public/auth/login" method="post">
                <?= csrf_field() ?>
                <label for="username">Email :</label>
                <input type="email" id="username" name="email" value="<?= old('email') ?>" required>

                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" required>
                <style>
                    .forgot-password {
                        color: #003366 !important;
                        text-decoration: none !important;
                        font-weight: bold;
                    }

                    .forgot-password:hover {
                        text-decoration: underline !important;
                        color: #001a33 !important;
                    }
                </style>

                <a href="<?= site_url('mot-de-passe-oublie') ?>" class="forgot-password">
                    Mot de passe oublié?
                </a><br>

                <button type="submit">Se connecter</button>
            </form>
            <p>Pas encore de compte ? <a href="<?= site_url('register') ?>">Inscrivez-vous</a></p>
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