import Swiper from "swiper";
import { Navigation, Pagination } from "swiper/modules";
// import Swiper and modules styles
import "swiper/css";
import "swiper/css/navigation";
import "swiper/css/pagination";


var swiper = new Swiper(".swiper", {
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