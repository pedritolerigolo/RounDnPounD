<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
Catalogue des Produits
<?= $this->endSection() ?>

<?= $this->section('content') ?>

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

<div class="product-grid">
    <?php foreach ($produits as $p): ?>
        <div class="product-card">
            <img src="<?= base_url('assets/images/produits/' . $p->Image) ?>" alt="<?= esc($p->nom) ?>">
            <h3><?= esc($p->nom) ?></h3>
            <p><?= number_format($p->prix, 2) ?> €</p>
            <a href="<?= site_url('produits/show/' . $p->id) ?>" class="btn-details">Voir le produit</a>
        </div>
    <?php endforeach; ?>
</div>

<?= $this->endSection() ?>