<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RounDnPounD | Gestion</title>

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
        <div class="profil-card" style="user-select: none;">
            <header class="profil-header">
                <h1>Mon Profil</h1>
                <p class="user-email"><?= esc($user->email) ?></p>
                <?php if ($isAdmin): ?>
                    <span class="badge-admin">Mode Administrateur</span>
                <?php endif; ?>
            </header>

            <div class="profil-sections">
                <div class="profil-section">
                    <h3>Mes Activités</h3>
                    <ul class="action-list">
                        <li>
                            <a href="<?= site_url('panier') ?>">
                                <span class="material-icons">shopping_basket</span>
                                <div class="text">
                                    <strong>Mon Panier</strong>
                                    <span>Finaliser mes achats en cours</span>
                                </div>
                                <span class="material-icons arrow">chevron_right</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= site_url('compte/commandes') ?>">
                                <span class="material-icons">history</span>
                                <div class="text">
                                    <strong>Mes Commandes</strong>
                                    <span>Historique et suivi de vos colis</span>
                                </div>
                                <span class="material-icons arrow">chevron_right</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= site_url('compte/adresses') ?>">
                                <span class="material-icons">location_on</span>
                                <div class="text">
                                    <strong>Mes adresses</strong>
                                    <span>Ajout et suppression de mes adresses</span>
                                </div>
                                <span class="material-icons arrow">chevron_right</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="profil-section">
                    <h3>Sécurité</h3>
                    <ul class="action-list">
                        <li>
                            <a href="<?= site_url('compte/modifier-password') ?>">
                                <span class="material-icons">lock</span>
                                <div class="text">
                                    <strong>Modifier le mot de passe</strong>
                                </div>
                                <span class="material-icons arrow">chevron_right</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <?php if ($isAdmin): ?>
                    <div class="profil-section admin-zone">
                        <h3>Outils Gestion</h3>
                        <ul class="action-list">
                            <li>
                                <a href="<?= site_url('admin/commandes') ?>">
                                    <span class="material-icons">pallet</span>
                                    <div class="text"><strong>Gestion des commandes</strong></div>
                                    <span class="material-icons arrow">chevron_right</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?= site_url('admin/remboursements') ?>">
                                    <span class="material-icons">currency_exchange</span>
                                    <div class="text"><strong>Gestion des remboursements</strong></div>
                                    <span class="material-icons arrow">chevron_right</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?= site_url('admin/clients') ?>">
                                    <span class="material-icons">group</span>
                                    <div class="text"><strong>Gestion des comptes</strong></div>
                                    <span class="material-icons arrow">chevron_right</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?= site_url('admin/produits') ?>">
                                    <span class="material-icons">inventory_2</span>
                                    <div class="text"><strong>Gestion des produits</strong></div>
                                    <span class="material-icons arrow">chevron_right</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?= site_url('admin/fournisseurs') ?>">
                                    <span class="material-icons">local_shipping</span>
                                    <div class="text"><strong>Gestion des fournisseurs</strong></div>
                                    <span class="material-icons arrow">chevron_right</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?= site_url('admin/ingredients') ?>">
                                    <span class="material-icons">egg_alt</span>
                                    <div class="text"><strong>Gestion des ingredients</strong></div>
                                    <span class="material-icons arrow">chevron_right</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                <?php endif; ?>

                <div class="profil-section">
                    <ul class="action-list danger-zone">
                        <li>
                            <a href="<?= site_url('compte/supprimer') ?>"
                                onclick="return confirm('Supprimer définitivement ?')">
                                <span class="material-icons">delete_forever</span>
                                <div class="text"><strong>Supprimer mon compte</strong></div>
                                <span class="material-icons arrow">chevron_right</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </main>

    <style>
        .profil-page {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 80vh;
            padding: 40px 20px;
        }

        .profil-card {
            background: rgba(255, 255, 255, 0.95);
            width: 100%;
            max-width: 500px;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(5px);
        }

        .profil-header h1 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }

        .user-email {
            color: #666;
            margin-bottom: 20px;
        }

        .profil-section {
            margin-top: 25px;
        }

        .profil-section h3 {
            font-size: 12px;
            text-transform: uppercase;
            color: #999;
            margin-bottom: 8px;
            margin-left: 5px;
        }

        .action-list {
            list-style: none;
            padding: 0;
            margin: 0;
            background: #fff;
            border: 1px solid #eee;
            border-radius: 12px;
        }

        .action-list li {
            border-bottom: 1px solid #eee;
        }

        .action-list li:last-child {
            border-bottom: none;
        }

        .action-list a {
            display: flex;
            align-items: center;
            padding: 14px;
            text-decoration: none;
            color: #333;
        }

        .action-list a:hover {
            background: #f8f9fa;
        }

        .action-list .material-icons {
            margin-right: 15px;
            color: #555;
        }

        .action-list .text {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .action-list .text strong {
            font-size: 14px;
        }

        .action-list .text span {
            font-size: 12px;
            color: #888;
        }

        .action-list .arrow {
            margin-right: 0;
            color: #ccc;
            font-size: 20px;
        }

        .danger-zone a {
            color: #d32f2f;
        }

        .danger-zone .material-icons {
            color: #d32f2f;
        }

        .badge-admin {
            background: #ffd700;
            color: #000;
            padding: 2px 10px;
            border-radius: 10px;
            font-size: 11px;
            font-weight: bold;
        }
    </style>

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