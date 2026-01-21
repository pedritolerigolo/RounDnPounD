<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RounDnPounD | About us</title>

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
        <div class="hero-about">
            <h1>RounDnPounD</h1>
            <p>Dès qu'ils cesseront de tourner,<br>vous n'en ferez qu'une bouchée.</p>
        </div>

        <div class="about-container">
            <section id="histoire">
                <h2>Notre histoire</h2>
                <p>
                    Fondée en 2025, RounDnPounD est née d'une idée simple : révolutionner le monde du sandwich.
                    Chaque recette est élaborée avec des ingrédients locaux pour vous offrir une expérience unique.
                </p>
            </section>

            <section id="equipe">
                <h2>L'Équipe</h2>
                <div class="team-grid">
                    <div class="team-member">
                        <strong>Martin Cozic</strong>
                        <p>Fondateur</p>
                    </div>
                    <div class="team-member">
                        <strong>Dylan Kaneb</strong>
                        <p>Cuisinier</p>
                    </div>
                    <div class="team-member">
                        <strong>Vincent Farge</strong>
                        <p>Congé maternité</p>
                    </div>
                    <div class="team-member">
                        <strong>Alexis Joly</strong>
                        <p>Taff ailleurs</p>
                    </div>
                    <div class="team-member">
                        <strong>Evan Guiheneuf</strong>
                        <p>Congé maternité</p>
                    </div>
                </div>
            </section>

            <section id="carriere">
                <h2>Carrière</h2>
                <p>Vous êtes passionné par la rotisserie ? Nous cherchons toujours des profils créatifs pour rejoindre
                    nos ateliers.</p>
            </section>

            <section id="mention-legale">
                <h2>Mentions légales</h2>
                <h3>1 – Édition du site</h3>
                <p>En vertu de l’article 6 de la loi n° 2004-575 du 21 juin 2004 pour la confiance dans l’économie
                    numérique, il est précisé aux utilisateurs du site internet https://www.roundnpound.com l’identité
                    des différents intervenants dans le cadre de sa réalisation et de son suivi:<br>
                    <br>
                    Propriétaire du site : COZIC MARTIN<br>
                    Identification de l’entreprise : COZIC MARTIN<br>
                    Directeur de la publication : COZIC MARTIN<br>
                    Hébergeur : local (pour l'instant)<br>
                    Délégué à la protection des données : COZIC MARTIN<br>
                </p><br>
                <h3>2 – Propriété intellectuelle et contrefaçons.</h3>
                <p>COZIC MARTIN est propriétaire des droits de propriété intellectuelle et détient les droits
                    d’usage sur tous les éléments accessibles sur le site internet, notamment les textes, images,
                    graphismes, logos, vidéos, architecture, icônes et sons.<br>
                    <br>
                    Toute reproduction, représentation, modification, publication, adaptation de tout ou partie des
                    éléments du site, quel que soit le moyen ou le procédé utilisé, est interdite, sauf autorisation
                    écrite préalable de COZIC MARTIN.<br>
                    <br>
                    Toute exploitation non autorisée du site ou de l’un quelconque des éléments qu’il contient sera
                    considérée comme constitutive d’une contrefaçon et poursuivie conformément aux dispositions des
                    articles L.335-2 et suivants du Code de Propriété Intellectuelle.<br>
                </p><br>
                <h3>3 – Limitations de responsabilité.</h3>
                <p>COZIC MARTIN ne pourra être tenu pour responsable des dommages directs et indirects
                    causés au matériel de l’utilisateur, lors de l’accès au site https://www.roundnpound.com.<br>
                    <br>
                    COZIC MARTIN décline toute responsabilité quant à l’utilisation qui pourrait être faite
                    des informations et contenus présents sur https://www.roundnpound.com.<br>
                    <br>
                    COZIC MARTIN s’engage à sécuriser au mieux le site https://www.roundnpound.com ,
                    cependant sa responsabilité ne pourra être mise en cause si des données indésirables sont importées
                    et installées sur son site à son insu.<br>
                    <br>
                    Des espaces interactifs (espace contact ou commentaires) sont à la disposition des utilisateurs.
                    COZIC MARTIN se réserve le droit de supprimer, sans mise en demeure préalable, tout
                    contenu déposé dans cet espace qui contreviendrait à la législation applicable en France, en
                    particulier aux dispositions relatives à la protection des données.<br>
                    <br>
                    Le cas échéant, COZIC MARTIN se réserve également la possibilité de mettre en cause la
                    responsabilité civile et/ou pénale de l’utilisateur, notamment en cas de message à caractère
                    raciste, injurieux, diffamant, ou pornographique, quel que soit le support utilisé (texte,
                    photographie …).<br>
                </p><br>
                <h3>4 – CNIL et gestion des données personnelles.</h3>
                <p>Conformément aux dispositions de la loi 78-17 du 6 janvier 1978 modifiée, l’utilisateur du site
                    https://www.roundnpound.com dispose d’un droit d’accès, de modification et de suppression des
                    informations collectées. Pour exercer ce droit, envoyez nous un message.
                </p><br>
                <h3>5 – Liens hypertextes et cookies</h3>
                <p>Le site https://www.roundnpound.com contient des liens hypertextes vers d’autres sites et dégage
                    toute responsabilité à propos de ces liens externes ou des liens créés par d’autres sites vers
                    https://www.roundnpound.com.<br>
                    <br>
                    La navigation sur le site https://www.roundnpound.com est susceptible de provoquer l’installation de
                    cookie(s) sur l’ordinateur de l’utilisateur.<br>
                    <br>
                    Un « cookie » est un fichier de petite taille qui enregistre des informations relatives à la
                    navigation d’un utilisateur sur un site. Les données ainsi obtenues permettent d’obtenir des mesures
                    de fréquentation, par exemple.<br>
                    <br>
                    Vous avez la possibilité d’accepter ou de refuser les cookies en modifiant les paramètres de votre
                    navigateur. Aucun cookie ne sera déposé sans votre consentement.<br>
                    <br>
                    Les cookies sont enregistrés pour une durée maximale de mois.<br>
                </p><br>
                <h3>6 – Droit applicable et attribution de juridiction.</h3>
                <p>Tout litige en relation avec l’utilisation du site https://www.roundnpound.com est soumis au droit
                    français. En dehors des cas où la loi ne le permet pas, il est fait attribution exclusive de
                    juridiction aux tribunaux compétents de Paris.
                </p><br>
            </section>
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
    <script type="module">
        import { fairyDustCursor } from 'https://unpkg.com/cursor-effects@latest/dist/esm.js';

        new fairyDustCursor({
            colors: ["#00ff00"],
            fairySymbol: "★",
        });
    </script>

</body>

</html>