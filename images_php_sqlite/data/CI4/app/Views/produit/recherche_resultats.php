<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RounDnPounD | Panier</title>

    <link rel="icon" type="image/png" href="<?= base_url('assets/images/favicon.png') ?>" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/css/recherche.css') ?>" />
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
            <span id="clear-search" class="material-icons"
                style="position: absolute; right: 90px; cursor: pointer; color: #333; display: <?= !empty($searchQuery) ? 'block' : 'none' ?>;">
                close
            </span>
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


    <button id="open-filter-btn" class="filter-toggle-btn">Filtres</button>

    <div class="filter-sidebar closed">
        <button id="close-filter-btn" class="filter-toggle-btn">&times;</button>
        <h3>Filtrer par Ingrédients</h3>

        <div class="ingredients-list">
            <?php foreach ($allIngredients as $ing): ?>
                <label class="filter-item">
                    <input type="checkbox" class="ingredient-checkbox" name="ingredients[]" value="<?= $ing['id'] ?>"
                        <?= in_array($ing['id'], $selectedFilters) ? 'checked' : '' ?>>
                    <span class="checkmark"></span>
                    <?= esc($ing['nom']) ?>
                </label>
            <?php endforeach; ?>
        </div>

        <button id="reset-filters" class="btn-reset">Réinitialiser</button>
    </div>

    <main class="recherche-page">
        <div id="product-container">
            <?php if (empty($produits)): ?>
                <div class="no-results">
                    <p>Désolé, aucun produit ne correspond à votre recherche.</p>
                    <a href="<?= site_url('produits') ?>" class="back-link">Voir tout le catalogue</a>
                </div>
            <?php else: ?>
                <div class="product-grid">
                    <?php foreach ($produits as $p): ?>
                        <div class="product-card" data-ingredients='<?= json_encode($p->ingredient_ids ?? []) ?>'>
                            <img src="<?= base_url('assets/images/produits/' . $p->Image) ?>" alt="<?= esc($p->nom) ?>">
                            <h3><?= esc($p->nom) ?></h3>
                            <p><?= number_format($p->prix, 2) ?> €</p>
                            <a href="<?= site_url('produits/show/' . $p->id) ?>" class="btn-details">Voir le produit</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
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
        document.addEventListener("DOMContentLoaded", () => {
            const sidebar = document.querySelector(".filter-sidebar");
            const openBtn = document.querySelector("#open-filter-btn");
            const closeBtn = document.querySelector("#close-filter-btn");
            const checkboxes = document.querySelectorAll(".ingredient-checkbox");
            const products = document.querySelectorAll(".product-card");
            const searchBar = document.querySelector('.search-bar');
            const resetBtn = document.querySelector("#reset-filters");
            const searchInput = document.getElementById('search-input');
            const clearSearch = document.getElementById('clear-search');
            const searchForm = document.getElementById('search-form');

            searchInput.addEventListener('input', () => {
                clearSearch.style.display = searchInput.value.length > 0 ? 'block' : 'none';
            });

            clearSearch.addEventListener('click', () => {
                searchInput.value = ''; 
                clearSearch.style.display = 'none';

                window.location.href = "<?= site_url('produits/recherche?query=') ?>";
            });

            const toggleFilterSidebar = (isOpen) => {
                if (sidebar) {
                    if (isOpen) {
                        sidebar.classList.remove('closed');
                        openBtn.style.display = 'none';
                        localStorage.setItem('filterSidebarOpen', 'true');
                    } else {
                        sidebar.classList.add('closed');
                        openBtn.style.display = 'block';
                        localStorage.setItem('filterSidebarOpen', 'false');
                    }
                }
            };

            const savedState = localStorage.getItem('filterSidebarOpen');
            toggleFilterSidebar(savedState === 'true');

            const appliquerFiltres = () => {
                const activeFilters = Array.from(checkboxes)
                    .filter(i => i.checked)
                    .map(i => parseInt(i.value));

                products.forEach(product => {
                    const productIngs = JSON.parse(product.dataset.ingredients || "[]");
                    if (activeFilters.length === 0) {
                        product.style.display = "block";
                    } else {
                        const isVisible = activeFilters.some(id => productIngs.includes(id));
                        product.style.display = isVisible ? "block" : "none";
                    }
                });
            };

            appliquerFiltres();

            checkboxes.forEach(box => {
                box.addEventListener('change', appliquerFiltres);
            });

            openBtn.addEventListener('click', () => toggleFilterSidebar(true));
            closeBtn.addEventListener('click', () => toggleFilterSidebar(false));

            if (resetBtn) {
                resetBtn.addEventListener('click', () => {
                    const url = new URL(window.location.href);
                    url.searchParams.delete('ingredients[]');
                    window.location.href = url.pathname + (url.searchParams.get('query') ? '?query=' + url.searchParams.get('query') : '?query=');
                });
            }

            if (searchBar) {
                searchBar.addEventListener('submit', function (e) {
                    const activeFilters = Array.from(document.querySelectorAll(".ingredient-checkbox:checked"))
                        .map(i => i.value);

                    activeFilters.forEach(id => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'ingredients[]';
                        input.value = id;
                        this.appendChild(input);
                    });
                });
            }
        });
    </script>

</body>

</html>