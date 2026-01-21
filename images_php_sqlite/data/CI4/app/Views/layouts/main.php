<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>RounDnPounD | <?= $this->renderSection('title') ?></title>

  <link rel="icon" type="image/png" href="<?= base_url('assets/images/favicon.png') ?>" />

  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>" />
  <link rel="stylesheet" href="<?= base_url('assets/css/home.css') ?>" />
  <link rel="stylesheet" href="<?= base_url('assets/css/footer.css') ?>" />
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />

  <?= $this->renderSection('extra-css') ?>
</head>
<?= view('cookies'); ?>

<body>
  <div class="curtain-opener opener-left"></div>
  <div class="curtain-opener opener-right"></div>
  <header class="header">
    <div class="logo">
      <a href="<?= base_url('produits') ?>"><img src="<?= base_url('assets/images/favicon.png') ?>" alt="logo" /></a>
    </div>

    <form action="<?= site_url('produits/recherche') ?>" method="get" class="search-bar">
      <input type="text" name="query" id="search-input" placeholder="Rechercher un produit..." value="<?= esc($searchQuery ?? '') ?>" autocomplete="off" />
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

  <main class="affiche-page">
    <?= $this->renderSection('content') ?>
  </main>

  <button id="open-sidebar-btn" class="sidebar-toggle-btn">(っ ᐛ )っ🍔</button>

  <div class="tiktok-sidebar">
    <button id="close-sidebar-btn" class="sidebar-toggle-btn">&times;</button>
    <h3>⬇ Experience Consommateur ⬇</h3>

    <div class="video-wrapper">
      <video id="mukbang-video" autoplay muted playsinline preload="metadata"></video>
    </div>

    <div class="video-controls">
      <button id="toggle-play-pause">
        <span class="material-symbols-outlined">pause</span>
        ❚❚
      </button>

      <button id="next-video">⏭ Suivant</button>
    </div>
  </div>

  <footer class="footer">
    <?= view('partials/footer') ?>
  </footer>

  <script src="<?= base_url('assets/js/animation.js') ?>"></script>
  <script type="module" crossorigin="anonymous" src="<?= base_url('assets/js/videoMukbang.js') ?>"></script>
  <script>
    const SUGGESTIONS_URL = "<?= site_url('produits/suggestions') ?>";
    const DETAILS_URL = "<?= site_url('produits/show/') ?>";
    const BASE_IMAGE_URL = "<?= base_url('assets/images/produits/') ?>";
  </script>
  <script src="<?= base_url('assets/js/search.js') ?>"></script>
</body>

</html>