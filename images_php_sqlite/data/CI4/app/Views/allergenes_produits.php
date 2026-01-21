<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RounDnPounD | Allergènes et Produits</title>

    <link rel="icon" type="image/png" href="<?= base_url('assets/images/favicon.png') ?>" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/css/about_us.css') ?>" />
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


    <main>
        <main class="profil-page">
            <div class="consultation-container"
                style="max-width: 95%; margin: 10px auto 160px; padding: 30px; background: #fff; border-radius: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
                

                <div style="border-bottom: 2px solid #eee; padding-bottom: 20px; margin-bottom: 20px;">
                    <h2 style="color: #003366; margin: 0;">Tableau de Sécurité Alimentaire</h2>
                    <p style="color: #666;">Récapitulatif des allergènes par produit</p>
                </div>

                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; min-width: 1000px; border: 1px solid #ddd;">
                        <thead>
                            <tr style="background: #003366; color: white;">
                                <th
                                    style="padding: 15px; text-align: left; border: 1px solid #002244; position: sticky; left: 0; background: #003366; z-index: 10;">
                                    Produits
                                </th>
                                <?php foreach ($colonnes as $col): ?>
                                    <th
                                        style="padding: 10px; text-align: center; border: 1px solid #002244; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px;">
                                        <?= str_replace('_', ' ', $col) ?>
                                    </th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($produits_allergenes as $p): ?>
                                <tr style="border-bottom: 1px solid #ddd; transition: background 0.1s;"
                                    onmouseover="this.style.background='#f1f4f9'"
                                    onmouseout="this.style.background='transparent'">
                                    <td
                                        style="padding: 12px 15px; font-weight: bold; color: #333; border: 1px solid #ddd; position: sticky; left: 0; background: inherit; z-index: 5; box-shadow: 2px 0 5px rgba(0,0,0,0.05);">
                                        <?= esc($p['produit_nom']) ?>
                                    </td>

                                    <?php foreach ($colonnes as $col): ?>
                                        <td style="padding: 0; text-align: center; border: 1px solid #ddd; width: 50px;">
                                            <?php if ($p[$col] == 1): ?>
                                                <div
                                                    style="background: #ffeeee; height: 100%; padding: 12px 0; display: flex; align-items: center; justify-content: center;">
                                                    <span class="material-icons"
                                                        style="color: #d32f2f; font-size: 22px; font-weight: bold;">close</span>
                                                </div>
                                            <?php else: ?>
                                                <span style="color: #eee; font-size: 0.8rem;"></span>
                                            <?php endif; ?>
                                        </td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div
                    style="margin-top: 20px; display: flex; align-items: center; gap: 20px; font-size: 0.9rem; color: #666;">
                    <div style="display: flex; align-items: center; gap: 5px;">
                        <span class="material-icons" style="color: #d32f2f; font-size: 18px;">close</span> Présence de
                        l'allergène
                    </div>
                    <div style="display: flex; align-items: center; gap: 5px;">
                        <span style="display: inline-block; width: 15px; height: 15px; border: 1px solid #ddd;"></span>
                        Absence ou traces non significatives
                    </div>
                </div>
            </div>
        </main>
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