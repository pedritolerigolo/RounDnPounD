const enterBtn = document.querySelector('.enter-btn');

if(enterBtn) {
    enterBtn.addEventListener('click', function(e) {
        e.preventDefault(); 
        const targetUrl = this.href;

        // 1. On ajoute la classe au body qui déclenche la fermeture des rideaux CSS
        document.body.classList.add('curtain-closed');
        
        // 2. On attend la fin de l'animation (800ms) avant de changer de page
        setTimeout(() => {
            window.location.href = targetUrl;
        }, 800); 
    });
}