// Utiliser des variables avec des noms uniques pour éviter les conflits
window.TaulignanSwiperModule = (function() {
  'use strict';
  
  // Vérifier si Swiper est déjà initialisé
  if (window.TaulignanSwiperInitialized) {
    console.log('Swiper déjà initialisé, arrêt');
    return;
  }
  
  window.TaulignanSwiperInitialized = true;
  
  // Importer Swiper de manière dynamique pour éviter les conflits
  let SwiperClass = null;
  
  // Fonction pour charger Swiper
  function loadSwiper() {
    if (typeof Swiper !== 'undefined') {
      SwiperClass = Swiper;
      initSwiper();
    } else {
      // Attendre que Swiper soit disponible
      setTimeout(loadSwiper, 100);
    }
  }
  
  // Fonction d'initialisation
  function initSwiper() {
    if (!SwiperClass) return;
    
    const swiperElements = document.querySelectorAll('.swiper');
    
    swiperElements.forEach(function(element) {
      // Vérifier si l'élément n'a pas déjà été initialisé
      if (!element.hasAttribute('data-swiper-initialized')) {
        try {
          new SwiperClass(element, {
            // Configuration de base
            direction: "horizontal",
            loop: true,
            
          });
          element.setAttribute('data-swiper-initialized', 'true');
          console.log('Swiper initialisé pour:', element);
        } catch (error) {
          console.error('Erreur lors de l\'initialisation de Swiper:', error);
        }
      }
    });
  }
  
  // Attendre que le DOM soit chargé
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', loadSwiper);
  } else {
    loadSwiper();
  }
  
  return {
    init: initSwiper
  };
})();
