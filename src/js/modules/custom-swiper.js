import Swiper from "swiper";
import { Navigation, Pagination, Scrollbar } from "swiper/modules";
// import Swiper and modules styles
import "swiper/css";
import "swiper/css/navigation";
import "swiper/css/pagination";

document.addEventListener("DOMContentLoaded", function () {
  // var swiper = new Swiper(".swiper:not(.sejours-swiper)", {
  //   modules: [Navigation, Pagination, Scrollbar],
  //   // setWrapperSize: true,
  //   slidesPerView: "auto",
  //   spaceBetween: 0,
  //   pagination: {
  //     el: ".swiper-pagination",
  //   },
  // });

  var swiper = new Swiper(".sejours-swiper", {
    modules: [Navigation, Pagination, Scrollbar],
    slidesPerView: 1,
    centeredSlides: true,
    // If we need pagination
    pagination: {
      el: ".swiper-pagination",
    },

    // Navigation arrows
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },

    // And if we need scrollbar
    scrollbar: {
      el: ".swiper-scrollbar",
    },
  });

  document.querySelectorAll('.slider-gallery').forEach(function(el){
    var raw = el.getAttribute('data-swiper') || '{}';
    var opts = {};
    try { opts = JSON.parse(raw); } catch (e) { opts = {}; }

    // slidesPerView: convertir nombre si string numérique
    if (typeof opts.slidesPerView === 'string' && opts.slidesPerView !== 'auto') {
      var num = parseFloat(opts.slidesPerView);
      if (!isNaN(num)) opts.slidesPerView = num;
    }

    // Injecter modules
    opts.modules = [Navigation, Pagination, Scrollbar];

    // Pagination/navigation scoping au bloc
    var pagEl = el.querySelector('.swiper-pagination');
    if (pagEl) {
      opts.pagination = Object.assign({ clickable: true }, opts.pagination || {}, { el: pagEl });
    }
    var nextEl = el.querySelector('.swiper-button-next');
    var prevEl = el.querySelector('.swiper-button-prev');
    if (nextEl && prevEl) {
      opts.navigation = Object.assign({}, opts.navigation || {}, { nextEl: nextEl, prevEl: prevEl });
    }

    var instance = new Swiper(el, opts);
    instance.allowTouchMove = true;
  });
});
