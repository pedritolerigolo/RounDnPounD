<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RounDnPounD | Commande</title>

    <link rel="icon" type="image/png" href="<?= base_url('assets/images/favicon.png') ?>" />

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik+Scribble&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="<?= base_url('assets/css/addressegetter.css') ?>" />
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

        <form id="noconnexion" action="<?= site_url('produits/recherche') ?>" method="get" class="search-bar">
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
        <?php if (session()->getFlashdata('error')): ?>
            <div
                style="color: #fff; background: #d32f2f; padding: 10px; border-radius: 12px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>
        <div class="connexion-window">
            <h2>Commande</h2>
            <div class="info-recap">
                <p><strong>Récapitulatif :</strong> <?= $details ?></p>
                <p><strong>Total à payer :</strong> <?= $prixTotal ?></p>
            </div>
            <form action="<?= site_url('commande/traiter') ?>" method="post">
                <input type="hidden" name="idProduit" value="<?= $idProduit ?>">
                <input type="hidden" name="prixTotal" value="<?= $prixTotal ?>">
                <div class="form-group">
                    <?php if (auth()->loggedIn()): ?>
                        <label for="adresse_select">Choisir une adresse enregistrée :</label>
                        <?php if (!empty($adresses)): ?>
                            <select name="adresse" id="adresse_select" class="form-control" required>
                                <option value="">-- Sélectionnez une adresse --</option>
                                <?php foreach ($adresses as $adr): ?>
                                    <option
                                        value="<?= esc($adr->addresse_complete . ', ' . $adr->code_postal . ' ' . $adr->ville . ', ' . $adr->pays) ?>">
                                        <?= esc($adr->addresse_complete) ?> (<?= esc($adr->ville) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        <?php else: ?>
                            <p class="text-warning">Aucune adresse enregistrée.</p>
                        <?php endif; ?>
                    <?php else: ?>
                        <label for="adresse_input">Adresse de livraison :</label>
                        <input type="text" name="adresse" id="adresse_input" class="form-control"
                            placeholder="Entrez votre adresse complète" required>
                        <label for="adresse_input">Adresse mail :</label>
                        <input type="email" name="email" id="email_input" class="form-control"
                            placeholder="Entrez votre adresse email" required>
                    <?php endif; ?>
                </div>
                <div class="adresse-actions" style="margin-top: 15px; display: flex; gap: 10px;">
                    <button type="submit" class="btn-commande" <?php if (auth()->loggedIn() && empty($adresses))
                        echo 'disabled'; ?>>
                        Passer au paiement avec cette adresse
                    </button>
                    <?php if (auth()->loggedIn()): ?>
                        <a href="<?= site_url('adresse/ajouter') ?>" class="btn-ajouter-adresses">
                            <span class="material-icons">add_location</span>
                            Ajouter une nouvelle adresse
                        </a>
                    <?php endif; ?>
                </div>
            </form>

            <p style="margin-top: 20px;">
                <a href="<?= site_url('produits') ?>">Retour à l'acceuil</a>
            </p>
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