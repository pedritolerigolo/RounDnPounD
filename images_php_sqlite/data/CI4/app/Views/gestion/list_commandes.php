<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RounDnPounD | Commandes</title>

    <link rel="icon" type="image/png" href="<?= base_url('assets/images/favicon.png') ?>" />

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik+Scribble&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/css/admin_form.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/css/footer.css') ?>" />
</head>

<body>
    <?php if (session('error')): ?>
        <div
            style="color: #fff; background: #d32f2f; padding: 15px; border-radius: 12px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
            <span class="material-icons">warning</span>
            <?= session('error') ?>
        </div>
    <?php endif; ?>
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
            <a href="<?= site_url('favoris') ?>" name="favoris" id="favoris" class="icon-text"><span
                    class="material-icons">star</span> <span>Favoris</span></a>
            <a href="<?= site_url('panier') ?>" name="panier" id="panier" class="icon-text"><span
                    class="material-icons">shopping_cart</span> <span>Panier</span></a>

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
        </nav>
    </header>

    <main class="profil-page">
        <div class="connexion-window-admin">
            <header class="profil-header"
                style="user-select: none; display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
                <div>
                    <h1>Liste De Mes Commandes</h1>
                </div>
                <span class="material-icons"
                    style="margin-bottom: 30px; margin-right: 20px; user-select: none; font-size: 40px; color: #003366;">history</span>
            </header>

            <div class="profil-section">
                <ul class="action-list">
                    <?php if (empty($commandes)): ?>
                        <li style="user-select: none; padding: 20px; text-align: center; color: #888;">Aucune commande
                            enregistrée</li>
                    <?php endif; ?>

                    <?php foreach ($commandes as $commande): ?>
                        <li style="user-select: none; display: flex; align-items: center; padding: 15px;">
                            <div class="text" style="user-select: none; flex-grow: 1;">
                                <strong><?= esc($commande->contenu) ?></strong>
                                <span
                                    style="user-select: none; color: #28a745; font-weight: bold;"><?= esc($commande->statut) ?></span>
                            </div>
                            <div style="user-select: none; display: flex; gap: 15px; align-items: center;">
                                <a href="<?= site_url('telecharge-facture/' . $commande->id) ?>" title="facture">
                                    <span class="material-icons" style="user-select: none; color: #003366;">receipt_long</span>
                                </a>

                                <a href="<?= site_url('consulte-commande/' . $commande->id) ?>" title="details"
                                    style="user-select: none; display: flex; align-items: center; text-decoration: none;">
                                    <span class="material-icons"
                                        style="user-select: none; color: #003366;">search</span>
                                </a>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div style="user-select: none; margin-top: 20px; text-align: center;">
                <a href="<?= site_url('gestion') ?>"
                    style="user-select: none; color: #666; font-size: 14px; text-decoration:none;">
                    <span class="material-icons"
                        style="user-select: none; font-size: 16px; vertical-align: middle;">arrow_back</span>
                    Retour au menu
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