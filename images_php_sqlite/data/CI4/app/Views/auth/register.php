<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RounDnPounD | Inscription</title>

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
            <input type="text" name="query" placeholder="Rechercher un produit..."
                value="<?= esc($searchQuery ?? '') ?>" />
            <button type="submit">
                <span class="material-symbols-outlined">search</span>
            </button>
        </form>

        <nav class="header-buttons">
            <a href="<?= site_url('favoris') ?>" name="favoris" id="favoris" class="icon-text"><span
                    class="material-icons">star</span> <span>Favoris</span></a>
            <a href="<?= site_url('panier') ?>" name="panier" id="panier" class="icon-text"><span
                    class="material-icons">shopping_cart</span> <span>Panier</span></a>
            <a href="<?= site_url('login') ?>" name="connexion" id="connexion" class="used-icon-text"><span
                    class="material-icons">person</span> <span>Connexion</span></a>
        </nav>
    </header>

    <main>
        <div class="connexion-window">
            <h2>Inscription</h2>
            <?php if (session('errors') !== null && !empty(session('errors'))): ?>
                <div class="errormessage">
                    <ul>
                        <?php foreach (session('errors') as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach ?>
                    </ul>
                </div>
            <?php endif ?>

            <form action="/CI4/public/auth/register" method="post">

                <label for="email">Adresse Email :</label>
                <input type="email" name="email" value="<?= old('email') ?>" required>

                <label for="username">Nom :</label>
                <input type="text" name="username" value="<?= old('username') ?>" required>

                <label for="password">Mot de passe :</label>
                <input type="password" name="password" required>

                <label for="password_confirm">Confirmer le Mot de passe :</label>
                <input type="password" name="password_confirm" required>
                <div class="terms-container">
                    <input type="checkbox" name="terms" id="terms" required>
                    <label for="terms" class="label-inline">
                        J'accepte les <a href="#" id="openTerms">conditions d'utilisation</a>
                    </label>
                </div>

                <div id="termsModal" class="modal">
                    <div class="modal-content">
                        <span class="close-modal">&times;</span>
                        <h2>Conditions d'Utilisation</h2>
                        <div class="terms-text">
                            <p><strong>1. Objet :</strong> Les présentes conditions régissent la vente et la livraison
                                des sandwichs RounDnPounD via notre boutique en ligne.</p>

                            <p><strong>2. Commandes :</strong> Toute commande passée sur notre site implique
                                l’acceptation des présentes conditions. Le paiement s’effectue en ligne, par carte
                                bancaire ou tout autre moyen proposé.</p>

                            <p><strong>3. Livraison :</strong> Les livraisons sont assurées dans les zones desservies
                                par RounDnPounD. Les délais sont indiqués lors de la commande et peuvent varier selon la
                                localisation.</p>

                            <p><strong>4. Cookies :</strong> Nous utilisons des cookies pour gérer votre panier, votre
                                session et améliorer votre expérience. Vous pouvez les désactiver via les paramètres de
                                votre navigateur.</p>

                            <p><strong>5. Données personnelles :</strong> Vos données sont collectées uniquement pour le
                                traitement et la livraison de vos commandes, conformément au RGPD. Elles ne sont ni
                                vendues ni partagées avec des tiers.</p>

                            <p><strong>6. Rétractation :</strong> Les produits alimentaires préparés sur commande ne
                                sont pas éligibles au retour ou au remboursement après livraison. Toute annulation doit
                                être notifiée par email au moins 2 heures avant la livraison.</p>

                            <p><strong>7. Responsabilité :</strong> RounDnPounD ne peut être tenu responsable en cas de
                                retard ou d’impossibilité de livraison due à des circonstances indépendantes de sa
                                volonté.</p>
                        </div>
                        <button type="button" id="closeTermsBtn" class="btn">J'ai lu et j'ai compris</button>
                    </div>
                </div>

                <button type="submit">S'inscrire</button>
            </form>
            <p>Déjà un compte ? <a href="<?= site_url('login') ?>">Connectez-vous ici</a></p>
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
        const modal = document.getElementById("termsModal");
        const openBtn = document.getElementById("openTerms");
        const checkbox = document.getElementById("terms");
        const closeX = document.querySelector(".close-modal");
        const closeBtn = document.getElementById("closeTermsBtn");

        openBtn.onclick = (e) => {
            e.preventDefault();
            modal.style.display = "block";
        }

        const closeModal = () => modal.style.display = "none";

        const closeAndCheck = () => {
            checkbox.checked = true;
            closeModal();
        }

        closeX.onclick = closeModal;
        closeBtn.onclick = closeAndCheck;

        window.onclick = (event) => {
            if (event.target == modal) closeModal();
        }
    </script>
</body>

</html>