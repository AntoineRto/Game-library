document.querySelectorAll('.game-card').forEach(card => {
        card.addEventListener('mouseenter', () => {
            card.classList.add('hovered'); // Ajoute la classe pour agrandir la carte
        });

        card.addEventListener('mouseleave', () => {
            card.classList.remove('hovered'); // Retire l'agrandissement de la carte
        });
    });
