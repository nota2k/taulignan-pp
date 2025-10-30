/**
 * Initialisation automatique d'AOS (Animate On Scroll) sur toutes les sections
 * Ajoute automatiquement l'effet fade-up à tous les éléments .wp-block-group
 */

import AOS from 'aos';
import 'aos/dist/aos.css';

// Fonction pour ajouter les attributs AOS aux éléments
function setupAOSAttributes() {
  // Sélectionner toutes les sections
  const sections = document.querySelectorAll("section,.entry-content > .wp-block-group");

  // Si aucune section n'est trouvée, arrêter
  if (sections.length === 0) {
    console.log('AOS: No .wp-block-group elements found.');
    return;
  }

  // Ajouter les attributs AOS à chaque section qui n'en a pas déjà
  sections.forEach((section, index) => {
    // Ne pas écraser un attribut data-aos existant
    if (!section.hasAttribute('data-aos')) {
      section.setAttribute('data-aos', 'fade-up');
    }
    
    // Ajouter un délai progressif pour créer un effet de cascade
    section.setAttribute('data-aos-delay', '0');
    
    // Ajuster la durée de l'animation
    section.setAttribute('data-aos-duration', '800');
    
    // Définir l'offset (déclenchement avant que l'élément soit visible)
    section.setAttribute('data-aos-offset', '100');
  });

  console.log(`AOS attributes added to ${sections.length} elements.`);
}

// Fonction d'initialisation complète
function initAOS() {
  // Vérifier si AOS est disponible
  if (!AOS || typeof AOS.init !== 'function') {
    console.warn('AOS library is not loaded properly.');
    return;
  }

  // Configurer les attributs d'abord
  setupAOSAttributes();

  // Initialiser AOS avec des options personnalisées
  // Note: On n'utilise pas startEvent car on gère le timing nous-mêmes
  AOS.init({
    // Durée de l'animation en millisecondes
    duration: 300,
    
    // Offset en pixels pour déclencher l'animation avant que l'élément soit visible
    offset: 200,
    
    // Délai en millisecondes (sera surchargé par data-aos-delay sur chaque élément)
    delay: 0,
    
    // Déclencher l'animation une seule fois (true) ou à chaque fois (false)
    once: false,
    
    // Direction de l'animation (de bas en haut pour fade-up)
    easing: 'ease-in-out',
    
    // Désactiver AOS sur mobile si nécessaire
    disable: false, // Mettre à 'mobile' pour désactiver sur mobile
    
    // Commencer l'animation seulement si l'élément est partiellement visible
    anchorPlacement: 'top-bottom',
  });

  console.log('AOS initialized successfully.');
}

// Initialiser quand le DOM est prêt
function runAOSInit() {
  // Attendre un peu pour s'assurer que tous les éléments sont bien présents
  requestAnimationFrame(() => {
    initAOS();
    // Rafraîchir après un court délai pour capturer les éléments qui se chargent lentement
    setTimeout(() => {
      if (AOS && typeof AOS.refresh === 'function') {
        AOS.refresh();
      }
    }, 200);
  });
}

// Initialiser quand le DOM est prêt ou immédiatement s'il est déjà chargé
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', runAOSInit);
} else {
  // Le DOM est déjà chargé
  runAOSInit();
}

/**
 * Réinitialiser AOS après le chargement de contenu dynamique (AJAX, etc.)
 * À appeler manuellement si vous ajoutez du contenu dynamiquement
 */
export function refreshAOS() {
  if (AOS && typeof AOS.refresh === 'function') {
    AOS.refresh();
  }
}

/**
 * Réinitialiser AOS après le chargement d'images
 * Utile pour éviter que les animations se déclenchent avant que les images ne soient chargées
 */
window.addEventListener('load', () => {
  if (AOS && typeof AOS.refresh === 'function') {
    AOS.refresh();
  }
});

