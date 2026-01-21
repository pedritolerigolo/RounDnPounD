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
        <div class="consultation-container"
            style="max-width: 850px; margin: 40px auto; padding: 30px; background: #fff; border-radius: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">

            <div
                style="display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 2px solid #eee; padding-bottom: 20px;">
                <div>
                    <h2 style="color: #003366; margin-right: 10px;">Commande #<?= esc($facture->numero_facture) ?></h2>
                    <p style="color: #666; margin: 5px 0 0 0;">Passée le <?= esc($facture->date) ?></p>
                </div>
                <div style="text-align: right;">
                    <span
                        style="display: inline-block; background: #bb946f; color: white; padding: 6px 15px; border-radius: 30px; font-weight: bold; font-size: 0.9rem;">
                        Statut : <?= esc($statut) ?>
                    </span>
                </div>
            </div>

            <div style="margin-top: 30px;">
                <h3 style="color: #333; font-size: 1.1rem; margin-bottom: 20px;">Articles de votre commande</h3>

                <?php foreach ($facture->produits as $produit): ?>
                    <?php for ($i = 1; $i <= $produit->quantite; $i++): ?>
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px; border: 1px solid #f0f0f0; border-radius: 12px; margin-bottom: 12px; transition: background 0.2s;"
                            onmouseover="this.style.background='#fcfcfc'" onmouseout="this.style.background='transparent'">

                            <div style="display: flex; align-items: center; gap: 15px;">
                                <span
                                    style="background: #003366; color: white; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; font-weight: bold;">
                                    <?= $i ?>
                                </span>
                                <div>
                                    <h4 style="margin: 0; color: #333;"><?= esc($produit->nom) ?></h4>
                                    <span style="color: #bb946f; font-weight: bold;"><?= number_format($produit->prix_uni, 2) ?>
                                        €</span>
                                </div>
                            </div>
                            <?php if ($statut === 'Livrée'): ?>
                            <a href="<?= site_url('avis/rediger/' . url_title($produit->nom)) ?>"
                                style="display: flex; align-items: center; gap: 8px; text-decoration: none; color: #003366; font-weight: 600; font-size: 0.9rem; padding: 8px 15px; border: 1px dashed #003366; border-radius: 8px;">
                                <span class="material-icons" style="font-size: 18px;">star_outline</span>
                                Noter cet article
                            </a>
                            <?php endif; ?>
                        </div>
                    <?php endfor; ?>
                <?php endforeach; ?>
            </div>

            <div
                style="margin-top: 40px; padding: 25px; background: #f8f9fa; border-radius: 12px; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <p style="margin: 0; color: #666;">Montant Total réglé</p>
                    <h2 style="margin: 0; color: #003366; font-size: 1.8rem;">
                        <?= number_format($facture->total_ttc, 2) ?> €</h2>
                </div>

                <div style="display: flex; gap: 15px;">
                    <a href="<?= site_url('SAV/demande-remboursement/' . $contenu) ?>"
                        style="display: flex; align-items: center; gap: 8px; background: #d32f2f; color: white; padding: 12px 25px; border-radius: 30px; text-decoration: none; font-weight: bold; box-shadow: 0 4px 10px rgba(211, 47, 47, 0.3);">
                        <span class="material-icons">money_off</span>
                        Demander un remboursement
                    </a>
                </div>
            </div>
            <div style="user-select: none; margin-top: 20px; text-align: center;">
                <a href="<?= site_url('compte/commandes') ?>"
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