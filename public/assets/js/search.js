document.getElementById('searchInput').addEventListener('input', function() {
    const query = this.value.trim();
    const resultsContainer = document.getElementById('searchResults');

    if (query.length < 2) {
        resultsContainer.innerHTML = '';
        return;
    }

    fetch(`/game-library/public/search?query=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
            resultsContainer.innerHTML = data.map(result => `
                <div class="card mb-2">
                    <img src="${result.url ?? '../public/assets/img/default.jpg'}" class="card-img-top" alt="${result.title}">
                    <div class="card-body">
                        <h5 class="card-title">${result.title}</h5>
                        <p class="card-text">${result.description}</p>
                        <a href="/game-library/public/game-details?id=${result.Id_jeu}" class="btn btn-primary">Voir plus</a>
                    </div>
                </div>
            `).join('');
        })
        .catch(error => {
            console.error('Erreur lors de la recherche:', error);
        });
});
