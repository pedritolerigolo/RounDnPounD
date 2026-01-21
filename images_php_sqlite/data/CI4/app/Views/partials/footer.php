<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

<footer class="footer">
  <div class="footer-inner">
    <div class="footer-brand">
      <div class="brand-mark">
        <img src="<?= base_url('assets/images/favicon.png') ?>" alt="Round & Pound" />
      </div>
      <div class="brand-text">
        <h3>Round & Pound</h3>
        <p class="brand-slogan">Dès qu'ils cesseront de tourner,<br>vous n'en ferez qu'une bouchée.</p>
      </div>
    </div>

    <div class="footer-columns">
      <div class="footer-col">
        <h4>Service</h4>
        <ul>
          <li><a href="<?= base_url('produits') ?>">Commander</a></li>
          <li><a href="<?= base_url('panier') ?>">Panier</a></li>
          <li><a href="<?= base_url('favoris') ?>">Liste de souhaits</a></li>
          <?php if (auth()->loggedIn()): ?>
            <?php if (auth()->user()->inGroup('admin')): ?>
              <li><a href="<?= base_url('gestion') ?>">Gestion de la boutique</a></li>
            <?php else: ?>
              <li><a href="<?= base_url('gestion') ?>">Mon compte</a></li>
            <?php endif; ?>
          <?php else: ?>
            <li><a href="<?= base_url('login') ?>">Connectez-vous</a></li>
          <?php endif; ?>
        </ul>
      </div>

      <div class="footer-col">
        <h4>About</h4>
        <ul>
          <li><a href="<?= site_url('about#histoire') ?>">Notre histoire</a></li>
          <li><a href="<?= site_url('about#equipe') ?>">Equipe</a></li>
          <li><a href="<?= site_url('about#mention-legale') ?>">Mention légale</a></li>
          <li><a href="<?= site_url('allergenes') ?>">Allergènes</a></li>
        </ul>
      </div>

      <div class="footer-col">
        <h4>Contact</h4>
        <ul>
          <li><a href="mailto:roundandpoundburgers@gmail.com">nous contacter par mail</a></li>
          <li><a href="tel:+33123456789">01 23 45 67 89</a></li>
          <li><a href="https://maps.app.goo.gl/Rjr8XBKiWFLXq9Et5" target="_blank">12 Rue de la Galette, Paris</a></li>
          <li><a href="<?= base_url('/') ?>">Revenir à l'accueil</a></li>
        </ul>
      </div>

      <div class="footer-col">
        <h4>Réseaux</h4>
        <div class="social-links">
          <a href="#" aria-label="Facebook"><i class="icon ion-social-facebook"></i></a>
          <a href="#" aria-label="Instagram"><i class="icon ion-social-instagram"></i></a>
          <a href="#" aria-label="Twitter"><i class="icon ion-social-twitter"></i></a>
          <a href="#" aria-label="Youtube"><i class="icon ion-social-youtube"></i></a>
        </div>
      </div>
    </div>
  </div>

  <div class="footer-bottom">
    <p>&copy; 2025 Rounds And Pounds. Tous droits réservés.</p>
  </div>
</footer>