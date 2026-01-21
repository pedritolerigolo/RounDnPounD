<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RounDnPounD - Bienvenue</title>
    <link rel="icon" type="image/png" href="<?= base_url('assets/img/favicon.png') ?>" />

    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/css/basepage.css') ?>" />


    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
</head>

<body>
    <div class="curtain panel-left"></div>
    <div class="curtain panel-right"></div>

    <div class="background-layer" id="scene-background"></div>

    <main>
        <div class="welcome-container">
            <div class="gif">
                <img src="<?= base_url('assets/images/gifAcceuil.gif') ?>" alt="gif acceuil">
            </div>
            <h1>Bienvenue chez Round&Pound !</h1>
            <h2>"Des qu'ils cesseront de tourner.</h2>
            <h2>Vous n'en ferez qu'une bouchée."</h2>
        </div>

        <a href="<?= site_url('produits') ?>" class="enter-btn">Allons-y</a>
    </main>

    <script src="<?= base_url('assets/js/animation.js') ?>"></script>
    <script src="<?= base_url('assets/js/curtain.js') ?>"></script>
    <script type="module">
        import { emojiCursor } from 'https://unpkg.com/cursor-effects@latest/dist/esm.js';

        new emojiCursor({ emoji: ["🥪", "🌭", "🍔"] });
    </script>
</body>

</html>