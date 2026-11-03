// main.js
import $ from './jquery.min.js';
import Swiper from './swiper.min.js';
import gsap from './gsap.min.js';
import ScrollTrigger from './ScrollTrigger.min.js';
import anime from './anime.min.js';
import lottie from './lottie.min.js';


import './script.js?1.0.0'; // Custom scripts

// Expose libraries globally
window.$ = $; // jQuery
window.jQuery = $; // For plugins expecting jQuery
window.Swiper = Swiper; // Swiper
window.gsap = gsap; // GSAP
window.ScrollTrigger = ScrollTrigger; // GSAP ScrollTrigger
window.anime = anime; // Anime.js
window.lottie = lottie; // Lottie

