<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RounDnPounD | Remboursements</title>

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
        <div class="connexion-window-admin" style="max-width: 1000px">
            <div style="border-bottom: 2px solid #eee; padding-bottom: 20px; mb-4">
                <h2 style="color: #003366; margin: 0;">Gestion des demandes de remboursement</h2>
                <p style="color: #666;">Liste des dossiers en attente de traitement</p>
            </div>

            <div style="margin-top: 20px; overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; text-align: left;">
                    <thead>
                        <tr style="color: #003366; border-bottom: 2px solid #eee;">
                            <th style="padding: 15px;">Facture</th>
                            <th style="padding: 15px;">Montant</th>
                            <th style="padding: 15px;">Raison détaillée</th>
                            <th style="padding: 15px; text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($demandes)): ?>
                            <?php foreach ($demandes as $d): ?>
                                <tr style="border-bottom: 1px solid #f0f0f0; transition: background 0.2s;"
                                    onmouseover="this.style.background='#742222'"
                                    onmouseout="this.style.background='transparent'">
                                    <td
                                        style="padding: 15px; font-weight: bold; vertical-align: top; white-space: nowrap; width: 100px;">
                                        <?= $d['facture'] ?>
                                    </td>

                                    <td
                                        style="padding: 15px; color: #d32f2f; font-weight: bold; vertical-align: top; white-space: nowrap; width: 100px;">
                                        <?= number_format($d['montant'], 2) ?> €
                                    </td>

                                    <td style="padding: 15px; text-align: center; vertical-align: top; width: 130px;">
                                        <div
                                            style="display: flex; gap: 10px; justify-content: center; position: sticky; top: 15px;">
                                            <?= nl2br(esc($d['raison'])) ?>
                                        </div>
                                    </td>
                                    <td style="padding: 15px; text-align: center;">
                                        <div style="display: flex; gap: 10px; justify-content: center;">
                                            <button onclick="traiterDemande(event, <?= $d['id'] ?>, 'valider')"
                                                style="background: #2e7d32; color: white; border: none; padding: 8px 15px; border-radius: 6px; cursor: pointer; display: flex; align-items: center; gap: 5px; font-weight: bold;">
                                                <span class="material-icons" style="font-size: 18px;">check</span>
                                            </button>

                                            <button onclick="traiterDemande(event, <?= $d['id'] ?>, 'rejeter')"
                                                style="background: #d32f2f; color: white; border: none; padding: 8px 15px; border-radius: 6px; cursor: pointer; display: flex; align-items: center; gap: 5px; font-weight: bold;">
                                                <span class="material-icons" style="font-size: 18px;">close</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" style="padding: 40px; text-align: center; color: #003366;">
                                    <span class="material-icons"
                                        style="font-size: 48px; display: block; margin-bottom: 10px;">inbox</span>
                                    Aucune demande de remboursement pour le moment.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div style="user-select: none; margin-top: 20px; text-align: center;">
                <a href="<?= site_url('gestion') ?>"
                    style="user-select: none; color: #666; font-size: 14px; text-decoration:none;">
                    <span class="material-icons"
                        style="user-select: none; font-size: 16px; vertical-align: middle;">arrow_back</span>
                    Retour au menu
                </a>
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
    <script>
        async function traiterDemande(e, id, action) {
            if (e) e.preventDefault();

            if (!confirm(`Voulez-vous vraiment ${action} cette demande ?`)) return;

            const btn = e.currentTarget;
            const row = btn.closest('tr');

            console.log(`Tentative de ${action} pour l'ID ${id}`);

            try {
                const response = await fetch(`<?= site_url('admin/remboursements/') ?>${action}/${id}`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) throw new Error('Erreur serveur : ' + response.status);

                const result = await response.json();
                console.log("Réponse reçue :", result);

                if (result.status === 'success') {
                    row.style.background = action === 'valider' ? '#e8f5e9' : '#ffebee';
                    row.style.transition = "all 0.5s ease";
                    row.style.opacity = "0";
                    row.style.transform = "translateX(20px)";
                    setTimeout(() => row.remove(), 500);
                } else {
                    alert("Erreur retournée : " + result.message);
                }
            } catch (error) {
                console.error("Erreur Fetch :", error);
                alert("La requête a échoué. Vérifiez la console (F12).");
            }
        }
    </script>

    <style>
        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }
    </style>
</body>

</html>