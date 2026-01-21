<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RounDnPounD | produit : <?= esc($produit->nom) ?></title>

    <link rel="icon" type="image/png" href="<?= base_url('assets/images/favicon.png') ?>" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/css/details.css') ?>" />
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
            style="position: fixed; top: 20px; right: 20px; background: #28a745; color: white; padding: 15px 25px; border-radius: 8px; z-index: 10000; box-shadow: 0 4px 12px rgba(0,0,0,0.2); display: flex; align-items: center; gap: 10px;">
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
    <?php if (session('error')): ?>
        <div id="flash-error"
            style="position: fixed; top: 20px; right: 20px; background: #dc3545; color: white; padding: 15px 25px; border-radius: 8px; z-index: 10000; box-shadow: 0 4px 12px rgba(0,0,0,0.2); display: flex; align-items: center; gap: 10px;">
            <span class="material-icons">error_outline</span>
            <?= session('error') ?>
        </div>

        <script>
            setTimeout(() => {
                const err = document.getElementById('flash-error');
                if (err) err.style.display = 'none';
            }, 3000);
        </script>
    <?php endif; ?>

    <div class="product-container" style="user-select: none;">
        <div class="product-header">
            <h1><?= esc($produit->nom) ?></h1>
            <div class="product-price"><?= esc($produit->getPrixFormate()) ?></div>
        </div>

        <div class="product-content">
            <div class="product-image-section">
                <?php if (!empty($produit->Image)): ?>
                    <img src="<?= base_url('assets/images/produits/' . $produit->Image) ?>" alt="<?= esc($produit->nom) ?>">
                <?php else: ?>
                    <div class="no-image">Pas d'image disponible</div>
                <?php endif; ?>
            </div>

            <div class="product-info-section">
                <div class="info-card">
                    <h2>Ingrédients</h2>
                    <?php if (!empty($listeIngredients)): ?>
                        <ul>
                            <?php foreach ($listeIngredients as $ingredient): ?>
                                <li>
                                    <strong><?= esc($ingredient['nom']) ?></strong>
                                    <?php if (isset($ingredient['quantite']) && $ingredient['quantite'] > 1): ?>
                                        <span style="color: #bb946f; font-weight: bold; margin-left: 5px;">
                                            (x<?= esc($ingredient['quantite']) ?>)
                                        </span>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p>Aucune liste d'ingrédients disponible.</p>
                    <?php endif; ?>
                </div>

                <div class="info-card">
                    <h2>Description</h2>
                    <p><?= esc($produit->descr) ?></p>
                </div>
                <div class="info-card">
                    <h2>Note :</h2>
                    <?php if ($moyenneNote == 0): ?>
                        <p>Aucun avis pour le moment.</p>
                    <?php else: ?>
                        <div class="rating">
                            <?php
                            $noteArrondie = floor($moyenneNote * 2) / 2;
                            $etoilesPleines = floor($noteArrondie);
                            $demiEtoile = ($noteArrondie - $etoilesPleines) >= 0.5 ? 1 : 0;
                            $etoilesVides = 5 - $etoilesPleines - $demiEtoile;
                            for ($i = 0; $i < $etoilesPleines; $i++) {
                                echo '<span class="star full">★</span>';
                            }
                            if ($demiEtoile) {
                                echo '<span class="star half">★</span>';
                            }
                            for ($i = 0; $i < $etoilesVides; $i++) {
                                echo '<span class="star empty">★</span>';
                            }
                            ?>
                            <span class="note-value"><?= number_format($moyenneNote, 1) ?> / 5</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="info-card">
                <h2>Allergènes</h2>
                <?php
                $allergenesPresents = [];
                if (!empty($allergenes)) {
                    foreach ($allergenes as $nom => $present) {
                        if ($present == 1) {
                            $allergenesPresents[] = str_replace('_', ' ', $nom);
                        }
                    }
                }
                ?>

                <?php if (!empty($allergenesPresents)): ?>
                    <div style="display: flex; flex-wrap: wrap; gap: 8px; margin-top: 10px;">
                        <?php foreach ($allergenesPresents as $nom): ?>
                            <span
                                style="background: #fdf2f2; color: #d32f2f; border: 1px solid #d32f2f; padding: 5px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: bold; text-transform: capitalize; display: flex; align-items: center; gap: 5px;">
                                <span class="material-icons" style="font-size: 16px;">warning</span>
                                <?= esc($nom) ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                    <p style="font-size: 0.8rem; color: #888; margin-top: 12px; font-style: italic;">
                        * Ce produit contient les allergènes listés ci-dessus.
                    </p>
                <?php else: ?>
                    <p style="color: #28a745; display: flex; align-items: center; gap: 5px;">
                        <span class="material-icons">check_circle</span>
                        Aucun allergène majeur détecté.
                    </p>
                <?php endif; ?>
            </div>
        </div>

        <div class="product-footer">
            <?php if (!empty($produit->avis)): ?>
                <div class="info-card avis-section">
                    <h2>Avis</h2>
                    <p><?= esc($produit->avis) ?></p>
                </div>
            <?php endif; ?>
            <div class="wishlist-section" style="margin-top: 20px;">
                <?php if (!auth()->loggedIn()): ?>
                    <a href="<?= site_url('login') ?>" class="btn-wishlist">
                        <span class="material-icons">favorite_border</span>
                        Ajouter à ma liste de souhaits
                    </a>
                    <a href="<?= site_url('login') ?>" class="btn-add-cart">
                        <span class="material-icons">shopping_cart</span>
                        Ajouter au panier
                    </a>
                    <a href="<?= site_url('commande/addressegetter/' . $produit->id) ?>" class="btn-buy-now">
                        <span class="material-icons">bolt</span>
                        Achat Immédiat
                    </a>
                <?php else: ?>
                    <?php if ($inWishlist): ?>
                        <a href="<?= site_url('produits/toggleWishlist/' . $produit->id) ?>" class="btn-wishlist active"
                            style="color: #d32f2f;">
                            <span class="material-icons">favorite</span>
                            Retirer de ma liste
                        </a>
                    <?php else: ?>
                        <a href="<?= site_url('produits/toggleWishlist/' . $produit->id) ?>" class="btn-wishlist">
                            <span class="material-icons">favorite_border</span>
                            Ajouter à ma liste de souhaits
                        </a>
                    <?php endif; ?>
                    <div class="cart-controls" style="margin-top: 20px;">
                        <?php if ($quantiteDansPanier > 0): ?>
                            <div class="quantity-selector" style="display: flex; align-items: center; gap: 15px;">
                                <a href="<?= site_url('panier/diminuer/' . $produit->id) ?>" class="btn-qty">
                                    <span class="material-icons">remove_circle_outline</span>
                                </a>

                                <span style="font-size: 1.5rem; font-weight: bold; min-width: 30px; text-align: center;">
                                    <?= $quantiteDansPanier ?>
                                </span>

                                <a href="<?= site_url('panier/ajouter/' . $produit->id) ?>" class="btn-qty">
                                    <span class="material-icons">add_circle_outline</span>
                                </a>
                            </div>
                        <?php else: ?>
                            <a href="<?= site_url('panier/ajouter/' . $produit->id) ?>" class="btn-add-cart">
                                <span class="material-icons">shopping_cart</span>
                                Ajouter au panier
                            </a>
                        <?php endif; ?>
                        <a href="<?= site_url('commande/addressegetter/' . $produit->id) ?>" class="btn-buy-now">
                            <span class="material-icons">bolt</span>
                            Achat Immédiat
                        </a>
                    </div>
                <?php endif; ?>
            </div>
            <div class="navigation-footer">
                <a href="<?= site_url('produits') ?>" class="back-link"><span class="material-symbols-outlined"
                        style="vertical-align: middle;">
                        arrow_back_2
                    </span> Retour à la liste des produits</a>
            </div>
        </div>
    </div>
    <div class="comment-container" style="user-select: none;">
        <h2 class="section-title">Avis des clients (<?= count($avis) ?>)</h2>
        <?php if (!empty($avis)): ?>
            <div class="reviews-list">
                <?php foreach ($avis as $unAvis): ?>
                    <div class="review-card">
                        <div class="review-header">
                            <span class="review-author"><?= esc($unAvis->client_nom) ?></span>
                            <div class="review-rating">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <span class="star <?= ($i <= $unAvis->note) ? 'full' : 'empty' ?>">★</span>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <div class="review-body">
                            <p><?= nl2br(esc($unAvis->texte)) ?></p>
                        </div>
                        <?php if (isset($unAvis->created_at)): ?>
                            <div class="review-date">
                                Publié le <?= date('d/m/Y', strtotime($unAvis->created_at)) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="no-reviews">Aucun avis pour le moment, peut-etre que vous serez le premier !</p>
        <?php endif; ?>
    </div>
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