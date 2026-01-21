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
        <div class="connexion-window-admin" style="user-select: none;">
            <header class="profil-header">
                <h1>Gestion des Clients</h1>
                <p>Liste des utilisateurs inscrits (non-admins)</p>
            </header>

            <?php if (session('message')): ?>
                <div
                    style="user-select: none; background: #e8f5e9; color: #2e7d32; padding: 10px; border-radius: 8px; margin-bottom: 15px; font-size: 14px;">
                    <?= session('message') ?>
                </div>
            <?php endif; ?>

            <div class="profil-section">
                <ul class="action-list">
                    <?php if (empty($clients)): ?>
                        <li style="user-select: none; padding: 20px; text-align: center; color: #bb3e3eff;">Aucun client à
                            afficher.</li>
                    <?php endif; ?>

                    <?php foreach ($clients as $client): ?>
                        <li>
                            <div style="user-select: none; display: flex; align-items: center; padding: 16px; width: 100%;">
                                <span class="material-icons"
                                    style="user-select: none; margin-right: 15px; color: #003366;">person</span>
                                <div class="text" style="user-select: none; flex-grow: 1;">
                                    <strong><?= esc($client->username) ?></strong>
                                    <span
                                        style="user-select: none; font-size: 12px; color: #003366;"><?= esc($client->email) ?></span>
                                    <small style="user-select: none; color: #005791;">Commandes en cours :
                                        <?= $client->nb_commande_en_cours ?? 0 ?></small>
                                </div>
                                <?php if ($client->is_admin==0): ?>
                                <a href="<?= site_url('admin/make-admin/' . $client->id) ?>"
                                    onclick="return confirm('Promouvoir cet utilisateur en administrateur ?')"
                                    style="user-select: none; background: #007bff; color: white; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 12px; display: flex; align-items: center; gap: 5px;">
                                    <span class="material-icons"
                                        style="user-select: none; font-size: 16px; color: white; margin: 0;">verified_user</span>
                                    Rendre Admin
                                </a>
                                <?php endif; ?>
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
                    Retour
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