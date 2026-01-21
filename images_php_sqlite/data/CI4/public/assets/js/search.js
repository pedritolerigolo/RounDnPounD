document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('search-input');
    const suggestionsBox = document.getElementById('search-suggestions');
    let timeout = null;

    if (!input || !suggestionsBox) {
        console.error('Éléments introuvables');
        return;
    }

    input.addEventListener('input', () => {
        clearTimeout(timeout);
        const query = input.value.trim();

        if (query.length < 2) {
            suggestionsBox.style.display = 'none';
            return;
        }

        timeout = setTimeout(async () => {
            try {
                const response = await fetch(`${SUGGESTIONS_URL}?query=${encodeURIComponent(query)}`);
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const produits = await response.json();

                if (produits.length > 0) {
                    suggestionsBox.innerHTML = produits.map(p => `
                        <a href="${DETAILS_URL}${p.id}" class="suggestion-item">
                            <img src="${BASE_IMAGE_URL}${p.Image}" alt="${p.nom}" onerror="this.src='${BASE_IMAGE_URL}default.jpg'">
                            <div class="suggestion-info">
                                <span class="suggestion-name">${p.nom}</span>
                                <span class="suggestion-price">${parseFloat(p.prix).toFixed(2)} €</span>
                            </div>
                        </a>
                    `).join('');
                    suggestionsBox.style.display = 'block';
                } else {
                    suggestionsBox.innerHTML = '<div class="no-results">Aucun résultat</div>';
                    suggestionsBox.style.display = 'block';
                }
            } catch (error) {
                console.error('Erreur suggestions:', error);
                suggestionsBox.style.display = 'none';
            }
        }, 300);
    });

    document.addEventListener('click', (e) => {
        if (!input.contains(e.target) && !suggestionsBox.contains(e.target)) {
            suggestionsBox.style.display = 'none';
        }
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            suggestionsBox.style.display = 'none';
        }
    });
});