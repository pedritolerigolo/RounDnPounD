<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RounDnPounD | MDP Oublié</title>

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
            <a href="<?= site_url('login') ?>" name="connexion" id="connexion" class="used-icon-text"><span
                    class="material-icons">person</span> <span>Connexion</span></a>
        </nav>
    </header>

    <main style="display: flex; justify-content: center; align-items: center; min-height: 70vh;">
        <div class="connexion-window" style="text-align: center; max-width: 450px;">

            <div style="margin-bottom: 20px;">
                <span class="material-icons" style="font-size: 64px; color: #28a745;">mark_email_read</span>
            </div>

            <h2 style="margin-bottom: 15px;">Consultez votre boîte mail !</h2>

            <p style="color: #555; line-height: 1.6; margin-bottom: 25px;">
                Nous venons de vous envoyer un <strong>Lien de connexion</strong> par e-mail.
                Il vous permet de vous connecter instantanément sans mot de passe.
            </p>

            <div
                style="background-color: #f8f9fa; padding: 15px; border-radius: 8px; border-left: 4px solid #007bff; margin-bottom: 25px; text-align: left;">
                <small style="color: #666; display: block; margin-bottom: 5px;">
                    <span class="material-icons" style="font-size: 14px; vertical-align: middle;">timer</span>
                    <strong>Validité :</strong> Ce lien expirera dans 60 minutes.
                </small>
                <small style="color: #666; display: block;">
                    <span class="material-icons" style="font-size: 14px; vertical-align: middle;">info</span>
                    <strong>Note :</strong> Vérifiez vos courriers indésirables (spams) si vous ne voyez rien.
                </small>
            </div>

            <a href="<?= site_url('login') ?>" class="btn-secondary"
                style="text-decoration: none; color: #007bff; font-weight: 500;">
                <span class="material-icons" style="font-size: 18px; vertical-align: middle;">arrow_back</span>
                Retour à la page de connexion
            </a>
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