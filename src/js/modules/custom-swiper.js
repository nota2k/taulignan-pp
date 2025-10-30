import Swiper from "swiper";
import { Navigation, Pagination, Scrollbar } from "swiper/modules";
// import Swiper and modules styles
import "swiper/css";
import "swiper/css/navigation";
import "swiper/css/pagination";
import "swiper/css/scrollbar";

document.addEventListener("DOMContentLoaded", function () {
  var swiper = new Swiper(".swiper:not(.sejours-swiper)", {
    modules: [Navigation, Pagination, Scrollbar],
    // setWrapperSize: true,
    slidesPerView: "2.5",
    centeredSlides: true,

    breakpoints: {
      768: {
        slidesPerView: 1.2,
      },
    },
    pagination: {
      el: ".swiper-pagination",
    },
  });

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
});
