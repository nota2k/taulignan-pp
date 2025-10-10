import Swiper from "swiper";
import { Navigation, Pagination } from "swiper/modules";
// import Swiper and modules styles
import "swiper/css";
import "swiper/css/navigation";
import "swiper/css/pagination";


var swiper = new Swiper(".swiper", {
  pagination: {
    el: ".swiper-pagination",
  },
});