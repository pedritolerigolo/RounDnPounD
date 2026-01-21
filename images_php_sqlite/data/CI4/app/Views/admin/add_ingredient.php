<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RounDnPounD | Ajout Ingredient</title>

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
    </header>²

    <main class="profil-page">
        <div class="connexion-window-admin">
            <h1 style="user-select: none;">Nouvel Ingrédient</h1>

            <?php if (session()->has('errors')): ?>
                <div
                    style="user-select: none; color: white; background: #d32f2f; padding: 10px; border-radius: 8px; margin-bottom: 20px;">
                    <ul>
                        <?php foreach (session('errors') as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach ?>
                    </ul>
                </div>
            <?php endif ?>

            <form action="<?= site_url('admin/create-ingredient') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <label>Nom de l'ingrédient</label>
                <input type="text" name="nom" required>

                <label>Quantité</label>
                <input type="number" step="1" name="quantite" required>

                <div style="margin-bottom: 15px; text-align: left;">
                    <label
                        style="display:block; margin-bottom:5px; font-size:14px; font-weight:bold;">Fournisseur</label>
                    <div id="ingredients-container">
                        <div class="ingredient-row" style="margin-bottom: 10px; display: flex; gap: 10px;">
                            <select name="fournisseur" required
                                style="width:100%; padding:10px; border:1px solid #ddd; border-radius:8px; background: white;">
                                <option value="">-- Choisir un fournisseur --</option>
                                <?php foreach ($allFournisseurs as $ing): ?>
                                    <?php $id = is_array($ing) ? $ing['id'] : $ing->id; ?>
                                    <?php $nom = is_array($ing) ? $ing['nom'] : $ing->nom; ?>
                                    <option value="<?= $id ?>"><?= esc($nom) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div style="margin-bottom: 20px;">
                    <label
                        style="display:block; margin-bottom:5px; font-size:14px; font-weight:bold;">Allergènes (facultatif)</label>
                    <div
                        style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 10px; background: #f9f9f9; padding: 15px; border-radius: 12px; border: 1px solid #eee;">
                        <?php
                        $liste_allergenes = [
                            'gluten' => 'Gluten',
                            'crustaces' => 'Crustacés',
                            'oeufs' => 'Œufs',
                            'poissons' => 'Poissons',
                            'arachides' => 'Arachides',
                            'soja' => 'Soja',
                            'lait' => 'Lait / Lactose',
                            'fruits_a_coque' => 'Fruits à coque',
                            'celeri' => 'Céleri',
                            'moutarde' => 'Moutarde',
                            'sesame' => 'Sésame',
                            'sulfites' => 'Sulfites',
                            'lupin' => 'Lupin',
                            'mollusques' => 'Mollusques'
                        ];

                        foreach ($liste_allergenes as $key => $label): ?>
                            <label
                                style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 13px; color: #444; padding: 5px; transition: background 0.2s; border-radius: 6px;"
                                onmouseover="this.style.background='#fff'" onmouseout="this.style.background='transparent'">
                                <input type="checkbox" name="allergenes[]" value="<?= $key ?>"
                                    style="width: 18px; height: 18px; accent-color: #003366; cursor: pointer;">
                                <?= $label ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                    <p style="font-size: 11px; color: #888; margin-top: 8px;">
                        <span class="material-icons" style="font-size: 12px; vertical-align: middle;">info</span>
                        Cochez uniquement les allergènes présents dans cet ingrédient.
                    </p>
                </div>
                <button type="submit"
                    style="user-select: none; width:100%; padding:12px; background:#003366; color:white; border:none; border-radius:8px; font-weight:bold; cursor:pointer;">
                    Enregistrer le produit
                </button>
            </form>

            <div style="user-select: none; margin-top: 20px; text-align: center;">
                <a href="<?= site_url('admin/ingredients') ?>"
                    style="color: #003366; font-size: 14px; text-decoration:none;">Annuler</a>
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