// /**
//  * Gestion des couleurs des boutons WordPress
//  * Échange les valeurs de background et border au hover
//  * Gère le cas de currentColor en obtenant la couleur calculée réelle
//  */

// /**
//  * Obtient la couleur calculée réelle d'un élément (résout currentColor)
//  */
// function getComputedColor(element, property) {
//   const computedStyle = window.getComputedStyle(element);
//   let color = computedStyle.getPropertyValue(property);
  
//   // Si c'est currentColor, obtenir la couleur du texte
//   if (color === 'currentColor' || color === '' || !color || color === 'rgba(0, 0, 0, 0)') {
//     color = computedStyle.color;
//   }
  
//   // Si toujours vide ou transparent, utiliser une valeur par défaut
//   if (!color || color === 'transparent' || color === 'rgba(0, 0, 0, 0)') {
//     // Convertir currentColor en couleur réelle via un élément temporaire
//     const tempDiv = document.createElement('div');
//     tempDiv.style.color = computedStyle.color || '#000000';
//     tempDiv.style.position = 'absolute';
//     tempDiv.style.visibility = 'hidden';
//     document.body.appendChild(tempDiv);
    
//     const computed = window.getComputedStyle(tempDiv).color;
//     document.body.removeChild(tempDiv);
    
//     return computed;
//   }
  
//   // Normaliser le format de couleur
//   const tempDiv = document.createElement('div');
//   tempDiv.style.color = color;
//   tempDiv.style.position = 'absolute';
//   tempDiv.style.visibility = 'hidden';
//   document.body.appendChild(tempDiv);
  
//   const computed = window.getComputedStyle(tempDiv).color;
//   document.body.removeChild(tempDiv);
  
//   return computed;
// }

// /**
//  * Calcule une couleur de texte appropriée pour le hover (contraste)
//  */
// function calculateHoverTextColor(backgroundColor) {
//   if (!backgroundColor || backgroundColor === 'transparent') {
//     return '#000000';
//   }
  
//   const match = backgroundColor.match(/\d+/g);
//   if (!match || match.length < 3) {
//     return '#ffffff';
//   }
  
//   const r = parseInt(match[0], 10);
//   const g = parseInt(match[1], 10);
//   const b = parseInt(match[2], 10);
  
//   // Calculer la luminosité
//   const luminance = (0.299 * r + 0.587 * g + 0.114 * b) / 255;
  
//   // Retourner blanc si foncé, noir si clair
//   return luminance > 0.5 ? '#000000' : '#ffffff';
// }

// /**
//  * Configure les variables CSS pour un bouton
//  * Échange les valeurs de background et border au hover
//  */
// function setupButtonVariables(button) {
//   // Réinitialiser les variables
//   button.style.removeProperty('--original-bg-color');
//   button.style.removeProperty('--original-text-color');
//   button.style.removeProperty('--original-border-color');
//   button.style.removeProperty('--hover-bg-color');
//   button.style.removeProperty('--hover-text-color');
//   button.style.removeProperty('--hover-border-color');
  
//   const computedStyle = window.getComputedStyle(button);
//   const hasBackground = button.classList.contains('has-background');
//   const hasTextColor = button.classList.contains('has-text-color');
  
//   let originalBgColor = null;
//   let originalTextColor = null;
//   let originalBorderColor = null;
  
//   // Obtenir les couleurs originales
//   if (hasBackground) {
//     // Bouton avec fond : récupérer background-color, color et border-color
//     originalBgColor = getComputedColor(button, 'background-color');
//     originalTextColor = getComputedColor(button, 'color');
//     originalBorderColor = getComputedColor(button, 'border-color');
    
//     // Si border est transparent ou absent, utiliser le background
//     if (!originalBorderColor || originalBorderColor === 'rgba(0, 0, 0, 0)' || originalBorderColor === 'transparent') {
//       originalBorderColor = originalBgColor;
//     }
//   } else if (hasTextColor) {
//     // Bouton outline (sans fond) : récupérer color et border-color
//     originalTextColor = getComputedColor(button, 'color');
//     originalBorderColor = getComputedColor(button, 'border-color');
    
//     // Si border utilise currentColor, utiliser la couleur du texte
//     if (!originalBorderColor || originalBorderColor === 'rgba(0, 0, 0, 0)') {
//       originalBorderColor = originalTextColor;
//     }
    
//     originalBgColor = 'transparent';
//   }
  
//   // Si on n'a toujours pas de couleur de texte, utiliser une valeur par défaut
//   if (!originalTextColor || originalTextColor === 'rgba(0, 0, 0, 0)') {
//     originalTextColor = computedStyle.color || '#000000';
//   }
  
//   // Stocker les couleurs originales
//   if (originalBgColor) {
//     button.style.setProperty('--original-bg-color', originalBgColor);
//   }
//   if (originalTextColor) {
//     button.style.setProperty('--original-text-color', originalTextColor);
//   }
//   if (originalBorderColor) {
//     button.style.setProperty('--original-border-color', originalBorderColor);
//   }
  
//   // ÉCHANGER les valeurs au hover : background ↔ border
//   if (hasBackground) {
//     // Pour les boutons avec fond : 
//     // - hover background = border original
//     // - hover border = background original
//     // - hover text = couleur contrastée avec le nouveau background
//     const hoverBgColor = originalBorderColor || originalTextColor || '#ffffff';
//     const hoverTextColor = calculateHoverTextColor(hoverBgColor);
//     const hoverBorderColor = originalBgColor || '#000000';
    
//     button.style.setProperty('--hover-bg-color', hoverBgColor);
//     button.style.setProperty('--hover-text-color', hoverTextColor);
//     button.style.setProperty('--hover-border-color', hoverBorderColor);
//   } else if (hasTextColor) {
//     // Pour les boutons outline :
//     // - hover background = border original (ou text si border = currentColor)
//     // - hover border = text original
//     // - hover text = couleur contrastée
//     const hoverBgColor = originalBorderColor || originalTextColor;
//     const hoverTextColor = calculateHoverTextColor(hoverBgColor);
//     const hoverBorderColor = originalTextColor;
    
//     button.style.setProperty('--hover-bg-color', hoverBgColor);
//     button.style.setProperty('--hover-text-color', hoverTextColor);
//     button.style.setProperty('--hover-border-color', hoverBorderColor);
//   }
// }

// /**
//  * Initialise les variables CSS pour tous les boutons
//  */
// function initButtonColors() {
//   const buttons = document.querySelectorAll('.wp-block-button__link');
  
//   buttons.forEach((button) => {
//     setupButtonVariables(button);
//   });
  
//   if (buttons.length > 0) {
//     console.log(`Button colors initialized for ${buttons.length} button(s).`);
//   }
// }

// /**
//  * Observe les changements dans le DOM pour les nouveaux boutons
//  */
// function observeButtonChanges() {
//   const observer = new MutationObserver((mutations) => {
//     let shouldUpdate = false;
    
//     mutations.forEach((mutation) => {
//       mutation.addedNodes.forEach((node) => {
//         if (node.nodeType === Node.ELEMENT_NODE) {
//           // Vérifier si c'est un bouton ou contient des boutons
//           if (node.classList && node.classList.contains('wp-block-button__link')) {
//             shouldUpdate = true;
//           } else if (node.querySelectorAll) {
//             const buttons = node.querySelectorAll('.wp-block-button__link');
//             if (buttons.length > 0) {
//               shouldUpdate = true;
//             }
//           }
//         }
//       });
//     });
    
//     if (shouldUpdate) {
//       initButtonColors();
//     }
//   });
  
//   observer.observe(document.body, {
//     childList: true,
//     subtree: true,
//   });
// }

// // Initialiser quand le DOM est prêt
// document.addEventListener('DOMContentLoaded', () => {
//   // Initialiser une première fois
//   initButtonColors();
  
//   // Observer les changements dynamiques
//   observeButtonChanges();
  
//   // Réinitialiser après le chargement complet (images, etc.)
//   window.addEventListener('load', () => {
//     setTimeout(initButtonColors, 100);
//   });
// });

// // Exporter la fonction pour réutilisation
// export { initButtonColors, setupButtonVariables };

