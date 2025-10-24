/**
 * JavaScript pour le bloc Query Loop Séjours avec Tri
 * 
 * @package Taulignan_U_Child
 * @since 1.0.0
 */

document.addEventListener('DOMContentLoaded', function() {
    console.log('Sejours Query Sort module loaded');
    
    // Initialiser les fonctionnalités du bloc
    initSejoursQuerySort();
});

/**
 * Initialiser les fonctionnalités du bloc Query Loop Séjours
 */
function initSejoursQuerySort() {
    const sejoursQueryBlocks = document.querySelectorAll('.sejours-query-sort');
    
    if (sejoursQueryBlocks.length === 0) {
        return;
    }
    
    sejoursQueryBlocks.forEach((block) => {
        initSortControls(block);
        initCardInteractions(block);
        initPagination(block);
    });
    
    console.log('Sejours Query Sort initialized');
}

/**
 * Initialiser les contrôles de tri
 */
function initSortControls(block) {
    const sortSelect = block.querySelector('.sejours-sort-select');
    
    if (!sortSelect) {
        return;
    }
    
    // Tri automatique au changement
    sortSelect.addEventListener('change', function() {
        const form = this.closest('form');
        if (form) {
            // Ajouter un indicateur de chargement
            showLoadingIndicator(block);
            
            // Soumettre le formulaire
            form.submit();
        }
    });
    
    // Animation lors du changement de tri
    sortSelect.addEventListener('change', function() {
        const results = block.querySelector('.sejours-query-results');
        if (results) {
            results.style.opacity = '0.7';
            results.style.transition = 'opacity 0.3s ease';
        }
    });
}

/**
 * Initialiser les interactions des cartes
 */
function initCardInteractions(block) {
    const cards = block.querySelectorAll('.sejour-card');
    
    cards.forEach((card) => {
        // Animation au survol
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-4px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
        
        // Animation au clic
        const cardButton = card.querySelector('.sejour-card-button');
        if (cardButton) {
            cardButton.addEventListener('click', function(e) {
                // Ajouter une animation de clic
                this.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    this.style.transform = 'scale(1)';
                }, 150);
            });
        }
    });
}

/**
 * Initialiser la pagination
 */
function initPagination(block) {
    const pagination = block.querySelector('.sejours-pagination');
    
    if (!pagination) {
        return;
    }
    
    const paginationLinks = pagination.querySelectorAll('.page-numbers a');
    
    paginationLinks.forEach((link) => {
        link.addEventListener('click', function(e) {
            // Ajouter un indicateur de chargement
            showLoadingIndicator(block);
            
            // Animation de transition
            const results = block.querySelector('.sejours-query-results');
            if (results) {
                results.style.opacity = '0.5';
                results.style.transition = 'opacity 0.3s ease';
            }
        });
    });
}

/**
 * Afficher un indicateur de chargement
 */
function showLoadingIndicator(block) {
    const results = block.querySelector('.sejours-query-results');
    
    if (!results) {
        return;
    }
    
    // Créer l'indicateur de chargement s'il n'existe pas
    let loadingIndicator = block.querySelector('.sejours-loading-indicator');
    
    if (!loadingIndicator) {
        loadingIndicator = document.createElement('div');
        loadingIndicator.className = 'sejours-loading-indicator';
        loadingIndicator.innerHTML = `
            <div class="sejours-loading-spinner">
                <div class="spinner"></div>
                <p>Chargement des séjours...</p>
            </div>
        `;
        
        // Styles pour l'indicateur
        loadingIndicator.style.cssText = `
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            border-radius: 8px;
        `;
        
        results.style.position = 'relative';
        results.appendChild(loadingIndicator);
    }
    
    // Afficher l'indicateur
    loadingIndicator.style.display = 'flex';
}

/**
 * Masquer l'indicateur de chargement
 */
function hideLoadingIndicator(block) {
    const loadingIndicator = block.querySelector('.sejours-loading-indicator');
    if (loadingIndicator) {
        loadingIndicator.style.display = 'none';
    }
}

/**
 * Fonction utilitaire pour animer l'apparition des cartes
 */
function animateCardsAppearance(block) {
    const cards = block.querySelectorAll('.sejour-card');
    
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
}

/**
 * Fonction pour filtrer les séjours côté client (optionnel)
 */
function filterSejoursByDate(block, filterType) {
    const cards = block.querySelectorAll('.sejour-card');
    const today = new Date();
    
    cards.forEach((card) => {
        const dateElement = card.querySelector('.sejour-card-date');
        if (!dateElement) {
            return;
        }
        
        const dateText = dateElement.textContent.trim();
        const cardDate = parseDateFromText(dateText);
        
        let shouldShow = true;
        
        switch (filterType) {
            case 'upcoming':
                shouldShow = cardDate >= today;
                break;
            case 'past':
                shouldShow = cardDate < today;
                break;
            case 'current_month':
                const currentMonth = today.getMonth();
                const currentYear = today.getFullYear();
                shouldShow = cardDate.getMonth() === currentMonth && 
                           cardDate.getFullYear() === currentYear;
                break;
        }
        
        card.style.display = shouldShow ? 'block' : 'none';
    });
}

/**
 * Parser une date depuis le texte affiché
 */
function parseDateFromText(dateText) {
    // Supprimer l'emoji et les espaces
    const cleanText = dateText.replace(/📅\s*/, '').trim();
    
    // Essayer différents formats de date
    const formats = [
        /(\d{1,2})\s+(\w{3})\s+(\d{4})/, // "15 Jan 2024"
        /(\d{1,2})\/(\d{1,2})\/(\d{4})/, // "15/01/2024"
        /(\d{4})-(\d{1,2})-(\d{1,2})/,  // "2024-01-15"
    ];
    
    for (const format of formats) {
        const match = cleanText.match(format);
        if (match) {
            if (format === formats[0]) {
                // Format "15 Jan 2024"
                const months = {
                    'Jan': 0, 'Feb': 1, 'Mar': 2, 'Apr': 3, 'May': 4, 'Jun': 5,
                    'Jul': 6, 'Aug': 7, 'Sep': 8, 'Oct': 9, 'Nov': 10, 'Dec': 11
                };
                return new Date(parseInt(match[3]), months[match[2]], parseInt(match[1]));
            } else {
                // Formats numériques
                const [, part1, part2, part3] = match;
                if (format === formats[1]) {
                    // Format "15/01/2024"
                    return new Date(parseInt(part3), parseInt(part2) - 1, parseInt(part1));
                } else {
                    // Format "2024-01-15"
                    return new Date(parseInt(part1), parseInt(part2) - 1, parseInt(part3));
                }
            }
        }
    }
    
    return new Date(); // Fallback
}

// Exposer les fonctions globalement si nécessaire
window.SejoursQuerySort = {
    initSortControls,
    initCardInteractions,
    initPagination,
    showLoadingIndicator,
    hideLoadingIndicator,
    animateCardsAppearance,
    filterSejoursByDate
};
