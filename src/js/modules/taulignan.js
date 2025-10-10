console.log("🏰 Taulignan JS chargé avec succès !");

// Animation de la page d'accueil

// Titres révélés au scroll
document.addEventListener("DOMContentLoaded", () => {
  const blockTitle = document.querySelectorAll(".title-block-wrapper");
  console.log("🔍 Éléments .title-block-wrapper trouvés:", blockTitle.length);

  function openTitleAnimate(element) {
    let txt = element.querySelectorAll("h1"); // Sélectionner chaque h1 dans le titre
    txt.forEach((span,index) => {
      setTimeout(() => {
        span.classList.add('visible');
        span.animate(
          [{ transform: "translateY(300px)" }, { transform: "translateY(0px)" }],
          {
            duration: 2000,
            easing: "ease-in-out",
            fill: "forwards",
          }
        );
      }, index * 100);
    });
  }

  // Créer l'Intersection Observer avec la fonction de rappel et les options
  const observerCallback = (entries, observer) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting && !entry.target.classList.contains("visible")) {
        entry.target.classList.add("visible");
        openTitleAnimate(entry.target);
      }
    });
  };

  const observerOptions = {
    root: null, // utilise le viewport comme zone de référence
    rootMargin: "0px",
    threshold: 0.1, // déclenche lorsque 10% de l'élément est visible
  };

  const observer = new IntersectionObserver(observerCallback, observerOptions);

  // Commencer à observer chaque élément
  blockTitle.forEach((element) => {
    observer.observe(element);
  });
});

// Effet de parallax sur les images

let parallaxDiv = document.querySelectorAll(".parallax");
if (parallaxDiv.length > 0) {
  window.addEventListener("scroll", function () {
    let mediaQuery = window.matchMedia("(max-width: 600px)");
    if (mediaQuery.matches) {
      // Si la media query correspond (c'est-à-dire si la largeur de la fenêtre est inférieure à 600px), on désactive l'animation
      return;
    }
    else{
    parallaxDiv.forEach((element) => {
      let speed = element.getAttribute("data-speed");
      let elH = element.clientHeight / 2;
      let rectY = element.getBoundingClientRect().top - elH;
      // console.log(elH);
      // console.log(rectY);
      let posY = window.scrollY * speed * -1;
      if (speed <= 0.5) {
        posY = rectY * speed - 1;
        posY = posY / 5; // Déplacement plus lent pour les petites valeurs de speed
      } else if (speed > 0.5 && speed <= 1.5) {
        posY = rectY * speed * 1;
        posY = posY / 4; // Déplacement moyen pour les valeurs de speed entre 0.5 et 1
      } else {
        posY = rectY * speed * 1.5; // Déplacement plus rapide pour les grandes valeurs de speed
        posY = posY / 4;
      }
      // console.log(posY);
      let translation = `translateY(${posY}px)`;
      element.style.transform = translation;
    });
    }
  });
}

// Loading Page

  // let loader = document.querySelector(".loading-screen");
  // let progressBar = document.querySelector(".progress-bar");
  // let percent = document.querySelector(".percent");
  // let homeTemplate = document.querySelector(".home-template");


  // console.time("Execution Time");
  // let counter = 0;
  // let intervalId = setInterval(() => {
  //     counter += 10; // Augmente le compteur de 10 à chaque fois
  //     percent.textContent = counter + '%';
  //     progressBar.style.transition = '1s ease-in-out all';
  //     progressBar.style.transform = 'scaleX('+counter / 100+')';

  //     if (counter >= 100) {
  //         // Arrête l'intervalle une fois que le compteur atteint 100
  //         clearInterval(intervalId);
  //         homeTemplate.setAttribute('data-status', 'finished');

  //         setInterval(() => {
  //             loader.animate([
  //                 {opacity: '1'},
  //                 {opacity: '0'}
  //             ], {
  //                 duration: 1000,
  //                 fill: 'forwards'
  //             });

  //             setTimeout(() => {
  //                 homeTemplate.style.overflow = 'auto';
  //                 homeTemplate.style.height = 'auto';
  //                 loader.style.display = 'none';
  //             }, 1000);

  //         }, 1000);

  //         console.timeEnd("Execution Time");

  //     }
  // }, 200);

// Fullwidth forced
function fullwidth(el) {
  let parentDiv = el.parentElement;

  function resize() {
    let windowWidth = window.innerWidth;
    var containerWidth = parentDiv.offsetWidth;
    
    // Obtenir la position du conteneur parent par rapport au viewport
    var parentRect = parentDiv.getBoundingClientRect();
    var parentLeft = parentRect.left;
    
    // Calculer les marges pour étendre l'élément sur toute la largeur du viewport
    var marginLeft = parentLeft;
    var marginRight = windowWidth - (parentLeft + containerWidth);
    
    // Appliquer les marges négatives et forcer la largeur
    el.style.marginLeft = `-${marginLeft}px`;
    el.style.marginRight = `-${marginRight}px`;
    el.style.width = `${windowWidth}px`;
    el.style.maxWidth = 'none';
    el.style.minWidth = `${windowWidth}px`;
    
    // S'assurer que le contenu de la galerie utilise toute la largeur
    el.style.boxSizing = 'border-box';
  }

  // Call resize function initially
  resize();

  // Update on window resize
  window.addEventListener("resize", resize);
}

// Adapter la galerie selon le nombre d'éléments
function adaptGalleryLayout(galleryElement) {
  function adjustLayout() {
    const items = galleryElement.querySelectorAll('.gallery-item');
    const itemCount = items.length;
    
    if (itemCount === 0) return;
    
    // Calculer la largeur de la galerie (moins le padding)
    const galleryWidth = galleryElement.offsetWidth - 40; // 20px padding de chaque côté
    const gap = 10; // Gap entre les éléments
    
    let columns;
    let minItemWidth;
    
    // Définir le nombre de colonnes selon le nombre d'éléments et la largeur d'écran
    const isMobile = window.innerWidth < 768;
    
    if (itemCount === 1) {
      columns = 1;
    } else if (itemCount === 2) {
      columns = isMobile ? 1 : 2; // Sur mobile, une seule colonne
    } else if (itemCount === 3) {
      columns = isMobile ? 1 : 3; // Sur mobile, une seule colonne
    } else if (itemCount <= 6) {
      columns = isMobile ? 2 : Math.min(itemCount, 3);
    } else {
      // Pour plus de 6 éléments, utiliser la logique auto-fit normale
      columns = 'auto-fit';
      minItemWidth = 250;
    }
    
    // Appliquer les styles
    if (columns === 'auto-fit') {
      galleryElement.style.gridTemplateColumns = `repeat(auto-fit, minmax(${minItemWidth}px, 1fr))`;
    } else {
      galleryElement.style.gridTemplateColumns = `repeat(${columns}, 1fr)`;
    }
    
    // Forcer la largeur complète pour les éléments de la galerie
    if (itemCount <= 3 && !isMobile) {
      items.forEach(item => {
        item.style.width = '100%';
        item.style.maxWidth = 'none';
      });
    } else if (isMobile) {
      items.forEach(item => {
        item.style.width = '100%';
        item.style.maxWidth = 'none';
      });
    }
    
    // Ajouter un attribut data pour le CSS
    galleryElement.setAttribute('data-items', itemCount);
    
    console.log(`Galerie adaptée : ${itemCount} éléments, ${columns} colonnes`);
  }
  
  // Ajuster au chargement
  adjustLayout();
  
  // Réajuster lors du redimensionnement
  window.addEventListener("resize", adjustLayout);
  
  // Observer les changements dans la galerie (ajout/suppression d'éléments)
  const observer = new MutationObserver(adjustLayout);
  observer.observe(galleryElement, { 
    childList: true, 
    subtree: true 
  });
}

// Gallery
let gallery = document.querySelector(".gallery");
let galleryItem = document.querySelectorAll(".gallery-item");
let minigalleryItem = document.querySelectorAll(".minigallery-item");


if (gallery) {
  fullwidth(gallery);
  adaptGalleryLayout(gallery);
  
  // Réappliquer après le chargement des images
  window.addEventListener('load', () => {
    fullwidth(gallery);
    adaptGalleryLayout(gallery);
  });
}

function checkImageOrientation(items) {
  // Vérifier que items existe et n'est pas vide
  if (!items || items.length === 0) {
    // console.warn("Aucun élément trouvé pour checkImageOrientation");
    return;
  }

  // Convertir NodeList en Array si nécessaire
  const itemsArray = Array.from(items);
  
  itemsArray.forEach((element, index) => {
    let itemImg = element.querySelector("img");
    
    if (!itemImg) {
      // console.warn(`Pas d'image dans l'élément ${index}`);
      return;
    }
    
    function processImage() {
      const width = itemImg.naturalWidth || itemImg.width;
      const height = itemImg.naturalHeight || itemImg.height;
      
      // console.log(`Image ${index}: ${width}x${height}`);
      
      if (width > height) {
        element.classList.add("horizontal");
      } else if (width < height) {
        element.classList.add("vertical");
      } else {
        element.classList.add("square");
      }
    }
    
    if (itemImg.complete && itemImg.naturalWidth > 0) {
      processImage();
    } else {
      itemImg.addEventListener('load', processImage);
      itemImg.addEventListener('error', () => {
        // console.error('Erreur de chargement:', itemImg.src);
      });
    }
  });
}

// Vérifier avant d'appeler les fonctions
if (galleryItem.length > 0) {
  checkImageOrientation(galleryItem);
}

if (minigalleryItem.length > 0) {
  checkImageOrientation(minigalleryItem);
}


// let minigallery = document.querySelectorAll(".grid-gallery item");
// checkImageOrientation(minigallery)

// Appeler la fonction après un court délai pour s'assurer que les images sont chargées
setTimeout(checkImageOrientation, 1000);


//Animation chargement des images des galeries fullscreen

function isInViewport(element, threshold = 0.2) {
    const rect = element.getBoundingClientRect();
    const viewportHeight = window.innerHeight || document.documentElement.clientHeight;
    
    return (
        rect.top <= viewportHeight * (1 - threshold) && // Corrigé ici
        rect.bottom >= viewportHeight * threshold &&
        rect.left >= 0 &&
        rect.right <= window.innerWidth
    );
}

let animationTriggered = false; // Éviter les animations multiples

function checkAndAnimate() {
    if (animationTriggered) return;
    
    let easingElement = document.querySelector(".easing");
    let images = document.querySelectorAll(".gallery-item");
    
    if (!easingElement || images.length === 0) return;
    
    if (isInViewport(easingElement)) {
        animationTriggered = true;
        let delay = 0;
        
        images.forEach((image, index) => {
            setTimeout(() => {
                image.style.transition = "all 1s cubic-bezier(0.25, 0.46, 0.45, 0.94)";
                image.style.transform = "translateY(0)";
                image.style.opacity = "1";
            }, delay);
            delay += 250; // Réduit à 150ms pour plus de fluidité
        });
        
        console.log(`${images.length} animations déclenchées !`);
    }
}

// Optimisation avec throttling
let ticking = false;
let easingElement = document.querySelector(".easing");
let images = document.querySelectorAll(".gallery-item");

if (easingElement && images.length > 0) {
  document.addEventListener("scroll", () => {
      if (!ticking) {
          requestAnimationFrame(() => {
              checkAndAnimate();
              ticking = false;
          });
          ticking = true;
      }
  });

  // Vérification initiale au chargement
  document.addEventListener('DOMContentLoaded', checkAndAnimate);
}
//Menu toggle

var burger = document.querySelector(".burger-container"),
  navigation = document.querySelector(".main-navigation");

if (burger && navigation) {
  burger.onclick = function () {
    navigation.classList.toggle("menu-opened");
  };
}

// Modifier formulaire en fonction du sujet de contact

const selectSubject = document.querySelector(".wpcf7-select");
let fromDate = document.querySelector(".from-date");
let toDate = document.querySelector(".to-date");

let dateRange = document.querySelector(".date-range");

if(selectSubject && fromDate && toDate && dateRange) {
  selectSubject.addEventListener("change", function () {
    const fromInput = fromDate.querySelector("input");
    const toInput = toDate.querySelector("input");
    
    if (fromInput && toInput) {
      if (
        selectSubject.value == "Renseignements" ||
        selectSubject.value == "Autre"
      ) {
        fromInput.setAttribute("aria-required", "false");
        toInput.setAttribute("aria-required", "false");
        dateRange.style.display = "none";
        
      } else {
        fromInput.setAttribute("aria-required", "true");
        toInput.setAttribute("aria-required", "true");
        dateRange.style.display = "block";
      }
    }
  });
}

// Modifier formulaire en fonction du sujet de contact

document.addEventListener("DOMContentLoaded", function() {
  var start = document.querySelector('.from-date input');
  var end = document.querySelector('.to-date input');
  if(start && end) {
    start.addEventListener('change', function() {
        var startDate = new Date(start.value);
        startDate.setDate(startDate.getDate() + 1);
        
        var minDate = startDate.toISOString().split('T')[0]; // Convertir la date en format YYYY-MM-DD
        end.setAttribute('min', minDate);
    });
  }
});

//Sticky menu on scroll

let mastHead = document.querySelector('.wp-block-template-part')
// console.log(mastHead);

// S'assurer que le header a le bon état au chargement
function initHeader() {
  if(mastHead) {
    if(window.scrollY > 100) {
      mastHead.classList.add('sticky');
    } else {
      mastHead.classList.remove('sticky');
    }
  }
}

// Initialiser au chargement seulement si mastHead existe
if (mastHead) {
  initHeader();
}

// S'assurer que le header est correctement positionné après le chargement complet
window.addEventListener('load', function() {
  setTimeout(initHeader, 100); // Petit délai pour s'assurer que tout est chargé
});

// Réinitialiser aussi au DOMContentLoaded
document.addEventListener('DOMContentLoaded', initHeader);

window.addEventListener('scroll', function() {
  if(mastHead) {
    if(window.scrollY > 100) {
      mastHead.classList.add('sticky');
    } else {
      mastHead.classList.remove('sticky');
    }
  }
}
);

// Réinitialiser au redimensionnement de la fenêtre
window.addEventListener('resize', initHeader);

// Confirmation que le script est bien chargé
console.log("✅ Taulignan JS initialisé avec succès !");

