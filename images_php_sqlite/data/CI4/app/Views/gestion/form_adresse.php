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
            <input type="text" name="query" id="search-input" placeholder="Rechercher un produit..."
                value="<?= esc($searchQuery ?? '') ?>" autocomplete="off" />
            <button type="submit">
                <span class="material-symbols-outlined">search</span>
            </button>
            <div id="search-suggestions" class="suggestions-box"></div>
        </form>

        <nav class="header-buttons">
            <a href="<?= site_url('favoris') ?>" class="icon-text"><span class="material-icons">star</span>
                <span>Favoris</span></a>
            <a href="<?= site_url('panier') ?>" class="icon-text"><span class="material-icons">shopping_cart</span>
                <span>Panier</span></a>

            <?php if (auth()->loggedIn()): ?>
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
            <?php else: ?>
                <a href="<?= site_url('login') ?>" class="icon-text"><span class="material-icons">person</span>
                    <span>Connexion</span></a>
            <?php endif; ?>
        </nav>
    </header>

    <main>
        <?php if (session('error')): ?>
            <div
                style="color: #fff; background: #d32f2f; padding: 15px; border-radius: 12px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                <span class="material-icons">warning</span>
                <?= session('error') ?>
            </div>
        <?php endif; ?>
        <?php if (session('message')): ?>
            <div id="flash-message"
                style="position: fixed; top: 20px; right: 20px; background: #28a745; color: white; padding: 15px 25px; border-radius: 8px; z-index: 2000; box-shadow: 0 4px 12px rgba(0,0,0,0.2); display: flex; align-items: center; gap: 10px;">
                <span class="material-icons">check_circle</span>
                <?= session('message') ?>
            </div>

            <script>
                setTimeout(() => {
                    const msg = document.getElementById('flash-message');
                    if (msg) msg.style.display = 'none';
                }, 5000);
            </script>
        <?php endif; ?>
        <div class="connexion-window">
            <h2>Ajouter une nouvelle adresse</h2>

            <form action="<?= site_url('adresse/sauvegarder') ?>" method="post" class="form-style">
                <?= csrf_field() ?>

                <div class="form-group">
                    <label>Adresse complète (Rue, numéro, etc.)</label>
                    <input type="text" name="addresse_complete" class="form-control" placeholder="123 rue de la Paix"
                        required>
                </div>

                <div class="row" style="display:flex; gap:10px;">
                    <div class="form-group" style="flex:1;">
                        <label>Code Postal</label>
                        <input type="text" name="code_postal" class="form-control" placeholder="75000" required>
                    </div>
                    <div class="form-group" style="flex:2;">
                        <label>Ville</label>
                        <input type="text" name="ville" class="form-control" placeholder="Paris" required>
                    </div>
                </div>

                <div class="row" style="display:flex; gap:10px;">
                    <div class="form-group">
                        <label>Pays</label>
                        <input type="text" name="pays" class="form-control" value="France" required>
                    </div>
                    <div class="form-group">
                        <label>Informations supplémentaires</label>
                        <input type="text" name="info_suppl" class="form-control"
                            placeholder="Appartement, étage, etc.">
                    </div>
                </div>
                <input type="hidden" name="url_retour" value="<?= $urlRetour ?>">

                <div class="form-actions" style="margin-top:20px;">
                    <button type="submit" class="btn-save">Enregistrer cette adresse</button>
                    <a href="<?= $urlRetour ?>" class="btn-cancel">Annuler</a>
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