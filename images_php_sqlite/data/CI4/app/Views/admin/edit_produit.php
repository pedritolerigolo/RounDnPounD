<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RounDnPounD | Modif Produit</title>

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
            <h1>Modifier le Produit</h1>

            <form action="<?= site_url('admin/update-produit/' . $produit->id) ?>" method="post"
                enctype="multipart/form-data">
                <?= csrf_field() ?>

                <label>Nom du produit</label>
                <input type="text" name="nom" value="<?= esc($produit->nom) ?>" required>

                <label>Prix (€)</label>
                <input type="number" step="0.01" name="prix" value="<?= esc($produit->prix) ?>" required>

                <label>Description</label>
                <textarea name="descr" rows="4"><?= esc($produit->descr) ?></textarea>

                <button type="submit" class="btn-submit-admin"
                    style="user-select: none; display: center; align-items: center; gap: 10px; background: #003366; color: white; padding: 12px 25px; border-radius: 12px; text-decoration: none; font-weight: bold; transition: transform 0.2s;">Enregistrer
                    les modifications</button>
            </form>
            <?php if (!empty($avis)): ?>
                <div style="margin-top: 40px; padding-top: 30px; border-top: 2px solid #e0e0e0;">
                    <h2 style="color: #003366; margin-bottom: 10px;">
                        Avis clients (<?= count($avis) ?>)
                        <?php if ($moyenneNote > 0): ?>
                            <span style="color: #ffc107; font-size: 1.2rem; margin-left: 10px;">
                                ⭐
                                <?= number_format($moyenneNote, 1) ?>/5
                            </span>
                        <?php endif; ?>
                    </h2>

                    <div style="display: flex; flex-direction: column; gap: 15px; margin-top: 20px;">
                        <?php foreach ($avis as $commentaire): ?>
                            <div
                                style="background: #f8f9fa; border-left: 4px solid #003366; padding: 15px; border-radius: 8px; position: relative;">
                                <!-- En-tête de l'avis -->
                                <div
                                    style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                                    <div>
                                        <strong style="color: #003366; font-size: 1rem;">
                                            <?= esc($commentaire->client_nom ?? 'Client') ?>
                                        </strong>
                                        <span style="color: #ffc107; margin-left: 10px; font-size: 1.1rem;">
                                            <?= str_repeat('⭐', $commentaire->note) ?>
                                        </span>
                                    </div>

                                    <!-- Bouton de suppression -->
                                    <form action="<?= site_url('admin/delete-avis/' . $commentaire->id) ?>" method="post"
                                        onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet avis ?');"
                                        style="margin: 0;">
                                        <?= csrf_field() ?>
                                        <button type="submit"
                                            style="background: #dc3545; color: white; border: none; padding: 8px 15px; border-radius: 6px; cursor: pointer; font-size: 0.85rem; transition: background 0.2s;"
                                            onmouseover="this.style.background='#c82333'"
                                            onmouseout="this.style.background='#dc3545'">
                                            Supprimer
                                        </button>
                                    </form>
                                </div>

                                <!-- Texte de l'avis -->
                                <?php if (!empty($commentaire->texte)): ?>
                                    <p style="margin: 10px 0 0 0; color: #555; line-height: 1.6;">
                                        <?= nl2br(esc($commentaire->texte)) ?>
                                    </p>
                                <?php else: ?>
                                    <p style="margin: 10px 0 0 0; color: #999; font-style: italic;">
                                        (Aucun commentaire)
                                    </p>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php else: ?>
                <div
                    style="margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 8px; text-align: center; color: #666;">
                    <p style="margin: 0;">Aucun avis pour ce produit.</p>
                </div>
            <?php endif; ?>
            <div style="margin-top: 20px; text-align: center;">
                <a href="<?= site_url('admin/produits') ?>"
                    style="color: #fff; text-decoration: none; font-size: 0.9rem; opacity: 0.8;">
                    Annuler et retourner à la liste
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