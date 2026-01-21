<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RounDnPounD | Adresses</title>

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
                    <h2 style="color: #003366; margin: 0;">Demande de remboursement</h2>
                    <p style="color: #666; margin: 5px 0 0 0;">Facture #<?= esc($facture->numero_facture) ?></p>
                </div>
                <div style="text-align: right;">
                    <span
                        style="display: inline-block; background: #d32f2f; color: white; padding: 6px 15px; border-radius: 30px; font-weight: bold; font-size: 0.9rem;">
                        Espace SAV
                    </span>
                </div>
            </div>

            <form action="<?= site_url('SAV/traiterRemboursement') ?>" method="post">
                <input type="hidden" name="facture_id" value="<?= $numFacture ?>">

                <div style="margin-top: 30px;">
                    <h3 style="color: #333; font-size: 1.1rem; margin-bottom: 20px;">Sélectionnez les articles à
                        rembourser</h3>

                    <?php foreach ($facture->produits as $produit): ?>
                        <div
                            style="display: flex; justify-content: space-between; align-items: center; padding: 15px; border: 1px solid #f0f0f0; border-radius: 12px; margin-bottom: 12px;">

                            <div style="display: flex; align-items: center; gap: 15px;">
                                <input type="checkbox" name="produits_selectionnes[]" value="<?= esc($produit->nom) ?>"
                                    style="width: 22px; height: 22px; cursor: pointer; accent-color: #003366;">

                                <div>
                                    <h4 style="margin: 0; color: #333;"><?= esc($produit->nom) ?></h4>
                                    <span
                                        style="color: #bb946f; font-weight: bold;"><?= number_format($produit->prix_uni, 2) ?>
                                        €</span>
                                </div>
                            </div>

                            <div style="display: flex; align-items: center; gap: 10px;">
                                <label style="font-size: 0.9rem; color: #666;">Quantité :</label>
                                <select name="quantite_<?= urlencode($produit->nom) ?>"
                                    style="padding: 5px 10px; border-radius: 8px; border: 1px solid #ddd; background: #f9f9f9; color: #333; outline: none;">
                                    <?php for ($i = 1; $i <= $produit->quantite; $i++): ?>
                                        <option value="<?= $i ?>"><?= $i ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div
                    style="margin-top: 30px; padding: 25px; background: #fcfcfc; border: 1px solid #eee; border-radius: 12px;">
                    <h3
                        style="color: #003366; font-size: 1.1rem; margin-bottom: 15px; display: flex; align-items: center; gap: 8px;">
                        <span class="material-icons">info</span> Raison de la demande
                    </h3>

                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 600;">Motif
                            principal :</label>
                        <select name="motif" required
                            style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #ddd; outline: none;">
                            <option value="" disabled selected>Pourquoi demandez-vous un remboursement ?</option>
                            <option value="Produit non reçu">Produit non reçu</option>
                            <option value="Erreur de commande">Erreur de commande (mauvais burger)</option>
                            <option value="Problème de qualité">Problème de qualité / Hygiène</option>
                            <option value="Retard important">Retard de livraison important</option>
                            <option value="Autre">Autre (précisez ci-dessous)</option>
                        </select>
                    </div>

                    <div>
                        <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 600;">Précisions
                            (optionnel) :</label>
                        <textarea name="precision" rows="3" placeholder="Dites-nous en plus..."
                            style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #ddd; outline: none; font-family: inherit; resize: vertical;"></textarea>
                    </div>
                </div>

                <div style="margin-top: 30px; display: flex; justify-content: space-between; align-items: center;">
                    <a href="<?= site_url('consulte-commande/' . explode('-', $facture->numero_facture)[1]) ?>"
                        style="text-decoration: none; color: #666; font-weight: 600; display: flex; align-items: center; gap: 5px;">
                        <span class="material-icons">arrow_back</span> Retour
                    </a>

                    <button type="submit"
                        style="display: flex; align-items: center; gap: 8px; background: #003366; color: white; padding: 12px 30px; border-radius: 30px; border: none; text-decoration: none; font-weight: bold; cursor: pointer; box-shadow: 0 4px 10px rgba(0, 51, 102, 0.2); transition: transform 0.2s;"
                        onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
                        <span class="material-icons">send</span>
                        Envoyer la demande
                    </button>
                </div>
            </form>
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