<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RounDnPounD | Avis</title>

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
    <link rel="stylesheet" href="<?= base_url('assets/css/rate.css') ?>" />
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
        <div class="avis-container"
            style="max-width: 500px; margin: 50px auto; padding: 30px; border-radius: 15px; background: white; box-shadow: 0 10px 30px rgba(0,0,0,0.1); background-color: #daae85ff;">
            <h2 style="color: #003366;">Votre avis sur le <?= esc($produit->nom) ?></h2>

            <form action="<?= site_url('avis/sauvegarder') ?>" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="id_produit" value="<?= $produit->id ?>">

                <div class="form-group" style="margin-bottom: 20px;">
                    <label>Note (sur 5) :</label>
                    <div class="star-rating">
                        <input type="hidden" name="note" id="note-value" value="1">

                        <span class="star" data-value="1">&#9733;</span>
                        <span class="star" data-value="2">&#9733;</span>
                        <span class="star" data-value="3">&#9733;</span>
                        <span class="star" data-value="4">&#9733;</span>
                        <span class="star" data-value="5">&#9733;</span>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label>Votre commentaire :</label>
                    <textarea name="texte" required class="form-control" rows="5"
                        style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #ccc;"
                        placeholder="Qu'avez-vous pensé de ce donut ?"></textarea>
                </div>

                <button type="submit"
                    style="background: #003366; color: white; border: none; padding: 12px 25px; border-radius: 30px; width: 100%; font-weight: bold; cursor: pointer;">
                    Publier mon avis
                </button>
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

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const stars = document.querySelectorAll('.star');
            const noteInput = document.getElementById('note-value');

            updateStars(noteInput.value);

            stars.forEach(star => {
                star.addEventListener('click', () => {
                    const val = star.getAttribute('data-value');
                    noteInput.value = val;
                    updateStars(val);
                });
            });

            function updateStars(val) {
                stars.forEach(star => {
                    if (star.getAttribute('data-value') <= val) {
                        star.classList.add('active');
                    } else {
                        star.classList.remove('active');
                    }
                });
            }
        });
    </script>
</body>

</html>