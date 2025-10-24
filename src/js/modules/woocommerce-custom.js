/**
 * Masque les variations WooCommerce en rupture de stock
 */

console.log("woocommerce-blocks-variations.js loaded");

// (function () {
//   function markOutOfStock() {
//     // Trouver tous les inputs avec data-stock="0" ou data-out-of-stock
//     document
//       .querySelectorAll(
//         'input[data-stock="0"], input[data-out-of-stock="true"]'
//       )
//       .forEach((input) => {
//         // Trouver le div parent .wc-block-add-to-cart-with-options-variation-selector-attribute-options__pill
//         const pill = input.closest(
//           ".wc-block-add-to-cart-with-options-variation-selector-attribute-options__pill"
//         );
//         if (pill) {
//           pill.classList.add("out-of-order");
//         }
//       });
//   }

//   // Lancer + observer
//   markOutOfStock();
//   new MutationObserver(markOutOfStock).observe(document.body, {
//     childList: true,
//     subtree: true,
//   });
// })();
