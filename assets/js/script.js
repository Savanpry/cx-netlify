document.addEventListener("DOMContentLoaded", function () {

  /**  Remove H1 from Chat Widget */
  const observer = new MutationObserver(() => {
    const h1 = document.querySelector('#n8n-chat .chat-heading h1');
    if (h1) {
      const p = document.createElement('p');
      p.innerHTML = h1.innerHTML;
      p.className = h1.className;
      h1.replaceWith(p);
      observer.disconnect();
    }
  });

  observer.observe(document.body, { childList: true, subtree: true });



  /* footer address toggle js start */
  function initCustomAccordion() {
    const isMobile = window.innerWidth <= 767;
    const accordionItems = document.querySelectorAll("#mapAccordion .accordion-item");

    accordionItems.forEach(item => {
      const btn = item.querySelector(".accordion-button");
      const collapse = item.querySelector(".accordion-collapse");

      if (isMobile) {
        btn.removeAttribute("data-bs-toggle");
        btn.removeAttribute("data-bs-target");
        collapse.removeAttribute("data-bs-parent");


        btn.addEventListener("click", function (e) {
          e.preventDefault();


          accordionItems.forEach(i => {
            if (i !== item) {
              i.querySelector(".accordion-button").classList.add("collapsed");
              i.querySelector(".accordion-collapse").classList.remove("show");
            }
          });

          // toggle current item
          btn.classList.toggle("collapsed");
          collapse.classList.toggle("show");
        });
      } else {
        btn.setAttribute("data-bs-toggle", "collapse");
        btn.setAttribute("data-bs-target", "#" + collapse.id);
        collapse.setAttribute("data-bs-parent", "#mapAccordion");
      }
    });
  }

  // Run on load
  initCustomAccordion();

  // Run on resize
  window.addEventListener("resize", function () {
    initCustomAccordion();
  });

  /* footer address toggle js end */
})

// $(document).ready(function () {
//   if ($(".contact-btn-sec").length && $(".mobile-navbar").length) {
//     var contactBtn = $(".contact-btn-sec").prop("outerHTML");
//     $(".mobile-navbar").append(contactBtn);
//   }
// });

$(document).ready(function () {
  if ($(".contact-btn-sec").length && $(".mobile-navbar").length) {

    $(".contact-btn-sec").each(function () {
      var contactBtn = $(this).prop("outerHTML");
      $(".mobile-navbar").append(contactBtn);
    });

  }
});

// document.addEventListener("DOMContentLoaded", function () {
//     var input = document.querySelector("#tel"); // select the input element

//     if (input) { // check if element exists
//         var iti = window.intlTelInput(input, {
//             initialCountry: "auto",
//             geoIpLookup: function(success, failure) {
//                 fetch("https://ipapi.co/json/")
//                     .then(res => res.json())
//                     .then(data => success(data.country_code))
//                     .catch(() => success("us")); // fallback
//             },
//             utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"
//         });
//     }
// });



(function () {
  let doDomCheck;
  const domCheck = function () {
    let checker = document.querySelector("body");
    if (checker !== null) {
      clearInterval(doDomCheck);
      const newElement = `
          <div class="back-to-top_wrapeer" style="display:none;">
            <div class="back-to-top-inner">
              <a href="javascript:;">
                <svg width="18" height="14" viewBox="0 0 18 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <g clip-path="url(#clip0_463_4080)">
                  <path d="M0.627125 7.22609H12.6843C15.119 7.22609 17.0898 5.22152 17.0898 2.74528L17.0898 0.850586" stroke="white" stroke-linecap="round" stroke-linejoin="round"/>
                  <path d="M6.52344 1.21191L0.627273 7.22564L6.52344 13.2225" stroke="white" stroke-linecap="round" stroke-linejoin="round"/>
                  </g>
                  <defs>
                  <clipPath id="clip0_463_4080">
                  <rect width="18" height="14" fill="white" transform="matrix(-1 0 0 1 18 0)"/>
                  </clipPath>
                  </defs>
                </svg>
              </a>
            </div>
          </div>
      `;

      checker.insertAdjacentHTML('beforeend', newElement);

      const backToTop = document.querySelector(".back-to-top_wrapeer");
      const backToTopLink = document.querySelector(".back-to-top-inner a");

      window.addEventListener("scroll", function () {
        if (window.scrollY >= window.innerHeight) {
          backToTop.style.display = "block";
        } else {
          backToTop.style.display = "none";
        }
      });

      backToTopLink.addEventListener("click", function (e) {
        e.preventDefault();

        window.scrollTo({
          top: 0,
          behavior: "smooth"
        });

        let forceScroll = setInterval(() => {
          document.documentElement.scrollTop = 0;
          document.body.scrollTop = 0;
          if (window.scrollY === 0) {
            clearInterval(forceScroll);
          }
        }, 50);
      });
    }
  };

  doDomCheck = setInterval(domCheck, 200);
})();

jQuery(document).ready(function ($) {
  if ($(".hire-dev-popup").length) {
    // Open popup
    $("#hire-dev-cta").on("click", function () {
      $(".hire-dev-popup").addClass("active");
    });

    // Close popup
    $(".hire-dev-popup .career-close-toggle").on("click", function () {
      $(".hire-dev-popup").removeClass("active");
    });



  }
});



if (!/Chrome-Lighthouse/.test(navigator.userAgent)) {
  const lenis = new Lenis({
    duration: 0.5,
    easing: t => t,
    smooth: true,
    autoRaf: true,
    gestureOrientation: 'vertical',
  });

  $(document).ready(function () {

    // Toggle menu open/close
    $(".menuButton a.primary-btn").on("click", function (e) {
      e.preventDefault();
      $(this).toggleClass("active");
      $(".menu svg").toggleClass("expanded");
      $(".menu-date").toggleClass("open");
      $("body").toggleClass("open-menu");
      $("html").toggleClass("hidden-y");
    });

    // Generic function for forward navigation
    function openSubmenu($trigger, submenuSelector) {
      const $parentMenu = $trigger.closest("ul");
      const $submenu = $trigger.next(submenuSelector);

      $trigger.addClass("active");
      $parentMenu.css({ transform: "translateX(-100%)" });
      $submenu.css({
        transform: "translateX(0)",
        opacity: "1",
        background: "#000",
        zIndex: 9
      }).animate({ transform: "translateX(0)" }, 250);
    }

    // Generic function for back navigation
    function closeSubmenu($submenu, $previousMenu) {
      $submenu.closest("li").find(".arrow-icon").removeClass("active");
      $submenu.css({
        transform: "translateX(120%)",
        opacity: "0"
      });
      $previousMenu.css({
        transform: "translateX(0)",
        opacity: "1"
      }).animate({ transform: "translateX(0)" }, 250);
    }

    // Level 2 menu open
    $(".menu-date-bar > ul > li > .arrow-icon").on("click", function (e) {
      e.preventDefault();
      $(".arrow-icon").removeClass("active").next("ul.sub-menu").removeAttr("style");
      openSubmenu($(this), "ul.sub-menu");
    });

    // Level 2 menu back
    $(".menu-back-icon").on("click", function (e) {
      e.preventDefault();
      const $parentUl = $(this).closest("ul.sub-menu");
      const $previousMenu = $parentUl.closest("li").parents("ul").first();
      closeSubmenu($parentUl, $previousMenu);
    });

    // Level 3 menu open
    $(".menu-date-bar > ul > li .has-sub-menu-level-3 .arrow-icon").on("click", function (e) {
      e.preventDefault();
      $(".has-sub-menu-level-3 .arrow-icon").removeClass("active").next("ul.new-third-sub-menu").removeAttr("style");
      openSubmenu($(this), "ul.new-third-sub-menu");
    });

    // Level 3 menu back
    $(".new-third-sub-menu .menu-back-icon").on("click", function (e) {
      e.preventDefault();
      const $parentUl = $(this).closest("ul.new-third-sub-menu");
      closeSubmenu($parentUl, $parentUl.closest("li").find(".sub-menu"));
    });

    // Back to level 1
    $(".sub-menu .menu-back-icon").on("click", function (e) {
      e.preventDefault();
      const $parentUl = $(this).closest("ul.sub-menu");
      closeSubmenu($parentUl, $parentUl.closest("li").find(".menu-box-level-1"));
    });
  });

  /* menu js end */

  /* swiper-ourClient js start */
  $(document).ready(function () {
    var swiper = new Swiper(".swiper-ourclient", {
      spaceBetween: 20,
      slidesPerView: 5,
      centeredSlides: true,
      roundLengths: true,
      loop: true,
      loopAdditionalSlides: 30,
      autoplay: {
        delay: 2500,
        disableOnInteraction: false,
      },

      breakpoints: {
        1280: {
          slidesPerView: 4,
        },
        991: {
          slidesPerView: 4,
        },
        767: {
          slidesPerView: 2,
          spaceBetween: 10,
        }
      }

    });
  });
  /* swiper-ourClient js end */


  /* mission-video' js start */
  document.addEventListener('DOMContentLoaded', () => {
    const video = document.querySelector('.mission-video');
    const videoImg = document.querySelector('.video-img');
    const startVideo = document.querySelector('.start-video');
    const pauseVideos = document.querySelector('.pouse-videos');
    if (video && videoImg) {
      if (pauseVideos) pauseVideos.classList.add('hidden');
      if (startVideo) startVideo.classList.remove('hidden');
      videoImg.addEventListener('click', () => {
        if (video.paused) {
          video.play();
        } else {
          video.pause();
        }
        if (startVideo && pauseVideos) {
          startVideo.classList.toggle('hidden');
          pauseVideos.classList.toggle('hidden');
        }
      });
    }
  });
  /* mission-video' js end */

  /* Testimonial Section */
  if (document.querySelector(".testimonial-con-sec")) {
    function createContinuousMarquee(columnSelector, direction, speedMultiplier = 1) {
      const column = document.querySelector(columnSelector);
      const items = Array.from(column.children);
      if (!column || items.length === 0) {
        return;
      }

      const clonedItems = items.map(item => item.cloneNode(true));
      clonedItems.forEach(item => column.appendChild(item));
      const initializeMarquee = () => {
        const itemHeight = items[0].offsetHeight;
        const totalHeight = itemHeight * items.length;
        column.style.height = `${totalHeight * 2}px`;
        const marqueeAnimation = gsap.to(column, {
          y: direction * totalHeight,
          duration: (totalHeight / 50) * speedMultiplier,
          ease: "none",
          repeat: -1,
          paused: false,
          modifiers: {
            y: gsap.utils.unitize((y) => {
              const position = parseFloat(y) % totalHeight;
              return direction === 1 ? position - totalHeight : position;
            })
          }
        });
        column.addEventListener("mouseenter", () => marqueeAnimation.pause());
        column.addEventListener("mouseleave", () => marqueeAnimation.resume());
      };
      setTimeout(initializeMarquee, 50);
    }
    createContinuousMarquee(".column-1", -1, 0.7);
    createContinuousMarquee(".column-2", 1, 1);
    createContinuousMarquee(".column-3", -1, 0.7);
  }
  /* Testimonial Section Complete */

  /* Counter Slider Section */
  $(document).ready(function () {

    // if (document.querySelector('.counter-original')) {
      var swiper = new Swiper(".counter-slider", {
        scrollbar: '.swiper-scrollbar',
        effect: 'coverflow',
        direction: 'vertical',
        loop: true,
        slideToClickedSlide: true,
        grabCursor: true,
        centeredSlides: true,
        slidesPerView: "auto",
        autoplay: false,
        autoplaySpeed: 1000,
        coverflowEffect: {
          rotate: -5,
          stretch: 294.5,
          depth: 150,
          modifier: 1.2,
          slideShadows: false
        },
        freeMode: false,
        freeModeSticky: true,
        breakpoints: {
          767: {
            coverflowEffect: {
              rotate: -5,
              stretch: 120,
              depth: 130
            }
          },
        }
      });
    // }

    let swiperInstance;

    function initSwiper() {
      if ($('.awards-sec').length) {  // Check if .awards-sec exists
        if ($(window).width() <= 1260) {
          if (!swiperInstance) {
            swiperInstance = new Swiper(".awards-slider", {
              slidesPerView: 4,
              spaceBetween: 0,
              slidesPerGroup: 1,
              autoplay: {
                delay: 2500,
                disableOnInteraction: false
              },
              pagination: {
                el: '.swiper-pagination',
                clickable: true
              },
              loop: true,
              breakpoints: {
                1024: {
                  slidesPerView: 3,
                },
                768: {
                  slidesPerView: 2,
                },
                520: {
                  slidesPerView: 1,
                }
              }
            });
          }
        } else {
          if (swiperInstance) {
            swiperInstance.destroy(true, true);
            swiperInstance = null;
          }
        }
      }
    }

    // Initialize on load
    initSwiper();

    // Re-check on resize
    $(window).resize(function () {
      initSwiper();
    });
    let teamSwiperInstance;

    function initTeamSwiper() {
      if ($('.team-intro-slider').length) {  // Check if .team-intro-slider exists
        if ($(window).width() <= 1260) {
          if (!teamSwiperInstance) {
            teamSwiperInstance = new Swiper(".team-intro-slider", {
              slidesPerView: 4,
              spaceBetween: 0,
              slidesPerGroup: 1,
              loop: true,
              autoplay: {
                delay: 2500,
                disableOnInteraction: false
              },
              pagination: {
                el: '.swiper-pagination',
                clickable: true
              },
              breakpoints: {
                1199: {
                  slidesPerView: 3,
                },
                800: {
                  slidesPerView: 2,
                },
                520: {
                  slidesPerView: 1,
                }
              }
            });
          }
        } else {
          if (teamSwiperInstance) {
            teamSwiperInstance.destroy(true, true);
            teamSwiperInstance = null;
          }
        }
      }
    }

    // Initialize on load
    initTeamSwiper();

    // Re-check on resize
    $(window).resize(function () {
      initTeamSwiper();
    });


    let testimonailSliderMobile;

    function testimonailSwiper() {

      if ($('.testimonial-con-mobile-sec').length) {
        if ($(window).width() <= 1024) {
          if (!testimonailSliderMobile) {
            testimonailSliderMobile = new Swiper(".testimonial-con-mobile-sec", {
              slidesPerView: 3,
              spaceBetween: 0,
              slidesPerGroup: 1,
              loop: true,
              autoplay: {
                delay: 2500,
                disableOnInteraction: false
              },
              pagination: {
                el: '.swiper-pagination',
                clickable: true
              },
              breakpoints: {
                800: {
                  slidesPerView: 2,
                },
                520: {
                  slidesPerView: 1,
                }
              }
            });
          }
        } else {
          if (testimonailSliderMobile) {
            testimonailSliderMobile.destroy(true, true);
            testimonailSliderMobile = null;
          }
        }
      }
    }

    // Initialize on load
    testimonailSwiper();

    // Re-check on resize
    $(window).resize(function () {
      testimonailSwiper();
    });
  });
  /* Counter Slider Section Complete */

  /* Technologies Section Start */

  function toggleClassesInfinite() {
    const classNames = ['my_section_main', 'cutting-line-embed', 'cutting-tool-icon-wrap']; // Add all class names here

    classNames.forEach(className => {
      const elements = document.querySelectorAll(`.${className}`);
      elements.forEach(element => {
        // Every 5 seconds, add the 'animated' class
        setInterval(() => {
          element.classList.add('animated');

          // After 1 second, remove the 'animated' class
          setTimeout(() => {
            element.classList.remove('animated');
          }, 2000); // Remove class after 1 second
        }, 5000); // Add class every 5 seconds
      });
    });
  }

  // Call the function
  toggleClassesInfinite();


  /* Technologies Section End */

  /* Footer Section Start */

  const anchors = document.querySelectorAll('.mapnav ul li a[href^="#"]');
  const mapItems = document.querySelectorAll('.map-item');

  anchors.forEach(anchor => {
    anchor.addEventListener('click', (event) => {
      event.preventDefault();
      const hrefValue = anchor.getAttribute('href').substring(1);

      mapItems.forEach(item => {
        if (item.getAttribute('data-attr') === hrefValue) {
          item.classList.add('active');
        } else {
          item.classList.remove('active');
        }
      });
    });
  });

  /* Footer Section End */
  function animateDot() {
    let progress = 0;
    let totalDuration = 0.1;
    gsap.to({}, {
      duration: totalDuration,
      repeat: -1,
      ease: "power1.inOut",
      onUpdate: function () {
        const lengthAtProgress = pathLength * progress;
        const point = drarLogo.getPointAtLength(lengthAtProgress);
        movingDot.setAttribute('cx', point.x);
        movingDot.setAttribute('cy', point.y);
        progress += 0.001 * gsap.utils.random(0.005, 1);
        if (progress >= 1) {
          progress = 0;
        }
      },
      onRepeat: function () {
        totalDuration = gsap.utils.random(8, 12);
      }
    });
  }



  function neonFlicker(paths) {
    paths.forEach((path) => {
      function flicker() {
        // Randomly adjust drop shadow to create a flickering effect
        const flickerAmount = gsap.utils.random(5.1, 10.5); // Vary shadow intensity
        const flickerColor = gsap.utils.random(0.1, 2); // Random brightness
        const blurAmount = gsap.utils.random(0.5, 2.5); // Random blur amount for flicker

        // Apply new shadow intensity
        path.style.filter = `drop-shadow(0 0 ${blurAmount}px rgba(242, 110, 101, ${flickerColor})) drop-shadow(0 0 ${blurAmount * 2}px rgba(242, 110, 101, ${flickerColor}))`;
      }

      // Flicker randomly over time without stopping other animations
      gsap.to({}, {
        duration: gsap.utils.random(0.1, 0.5),
        repeat: 2,
        onRepeat: flicker,
        repeatRefresh: false // Get a new random value for each repeat
      });
    });
  }

  var rotate = document.querySelector(".rotateGlow");

  if (rotate) {
    gsap.to(".rotateGlow ellipse", {
      repeat: -1,
      yoyo: true,
      duration: 3,
      ease: "sine.inOut",
      attr: {
        rx: 500, // Increase or decrease the width dynamically
        ry: 200,  // Adjust the height dynamically
        transform: "rotate(-15 1528.03 831.902)" // Subtle rotation changes for liquid effect
      }
    });
  }

  document.addEventListener("DOMContentLoaded", function () {
    const siteWrapper = document.querySelector(".site_wrapper");

    if (siteWrapper) {
      const sections = siteWrapper.querySelectorAll("section");

      if (sections.length >= 2) {
        const triggerSection = sections[1];

        // Check if at least one of the target elements exists
        if (document.querySelector('.line-animation') || document.querySelector('.line-animation-right')) {
          gsap.fromTo(
            ['.line-animation', '.line-animation-right'],
            { opacity: 0 },
            {
              opacity: 1,
              scrollTrigger: {
                trigger: triggerSection,
                start: "top top",
                toggleActions: "play none none reverse",
              },
              duration: 0.1,
            }
          );
        }
      }
    }

    gsap.registerPlugin(ScrollTrigger);

    if (document.querySelector(".line-one")) {
      gsap.to(".line-one", {
        y: 800,
        scrollTrigger: {
          trigger: ".site_wrapper",
          start: "top top",
          end: "bottom top",
          scrub: 0.1,
        }
      });
    }

    if (document.querySelector(".line-two")) {
      gsap.to(".line-two", {
        y: -800,
        scrollTrigger: {
          trigger: ".site_wrapper",
          start: "top top",
          end: "bottom top",
          scrub: 0.1,
        }
      });
    }

    gsap.set('.cursor', { xPercent: 0, yPercent: 0, scale: 1, opacity: 1 });

    let cursor = document.querySelector('.cursor');
    let buttons = document.querySelectorAll('a');
    let body = document.querySelector('body');
    let mouseX, mouseY;

    window.addEventListener('mousemove', e => {
      mouseX = e.clientX;
      mouseY = e.clientY;
      gsap.to(cursor, { duration: 0.5, x: mouseX, y: mouseY });
    });

    buttons.forEach(button => {
      button.addEventListener('mouseenter', () => {
        gsap.to(cursor, { duration: 0.5, backgroundColor: '#F26E65', scale: 0 });
      });

      button.addEventListener('mouseleave', () => {
        gsap.to(cursor, { duration: 0.5, backgroundColor: '#F26E65', scale: 1 });
      });
    });

    body.addEventListener('mouseenter', () => {
      gsap.to(cursor, { duration: 0.5, backgroundColor: '#F26E65', scale: 1 });
    });

    body.addEventListener('mouseleave', () => {
      gsap.to(cursor, { duration: 0.5, backgroundColor: '#F26E65', scale: 0 });
    });
  });



  /* Service Section JS Start */

  gsap.registerPlugin(ScrollTrigger);
  if ($('.inner-service-wrapper').length > 0) {
    // Initial styles for elements
    gsap.set([".content-inside-x", ".content-inside-x h2", ".content-inside-x p", ".first-svg", ".second-svg"], { opacity: 0 });
    gsap.set("#x-path", { fill: "black" });

    const bannerTimeline = gsap.timeline({
      scrollTrigger: {
        trigger: ".main-service-sec",
        start: "top top",
        end: "+=300%",
        scrub: 1,
        pin: true,
        pinSpacing: true,
      },
    });

    // Animation sequence
    bannerTimeline
      .set([".letter-x > svg"], { width: "100vw" }) // Set initial width without animation
      .to([".letter-x > svg"], {
        opacity: 1,
        duration: 2,
        width: "1400vw",
        ease: "power1.inOut"
      })
      .to(".content-inside-x", {
        opacity: 1,
        duration: 0,
      }, 0)
      .to("#x-path", {
        fill: "#F26E65",
        duration: 0.5,
        ease: "power1.inOut",
      }, 0.5);

    // Responsive font size handling
    gsap.matchMedia()
      .add("(min-width: 1661px)", () => {
        bannerTimeline.to(".content-inside-x h2", { fontSize: "6rem", opacity: 1, duration: 0.5, ease: "power1.inOut" }, 0.5);
      })
      .add("(max-width: 1660px)", () => {
        bannerTimeline.to(".content-inside-x h2", { fontSize: "5rem", opacity: 1, duration: 0.5, ease: "power1.inOut" }, 0.5);
      })
      .add("(max-width: 991px)", () => {
        bannerTimeline.to(".content-inside-x h2", { fontSize: "4rem", opacity: 1, duration: 0.5, ease: "power1.inOut" }, 0.5);
      })
      .add("(max-width: 767px)", () => {
        bannerTimeline.to(".content-inside-x h2", { fontSize: "2.5rem", opacity: 1, duration: 0.5, ease: "power1.inOut" }, 0.5);
      })
      .add("(max-width: 575px)", () => {
        bannerTimeline.to(".content-inside-x h2", { fontSize: "2.5rem", opacity: 1, duration: 0.5, ease: "power1.inOut" }, 0.5);
      });

    // Continue animation
    bannerTimeline
      .to(".first-svg", {
        opacity: 1,
        x: 200,
        y: 200,
        duration: 1,
        ease: "power1.inOut",
      }, 0)
      .to(".second-svg", {
        opacity: 1,
        x: -200,
        y: 200,
        duration: 1,
        ease: "power1.inOut",
      }, 0)
      .to([".first-svg", ".second-svg"], {
        opacity: 0,
        duration: 0.5,
      }, 0.8)
      .to(".content-inside-x h2", {
        opacity: 0,
        duration: 0,
        ease: "power1.inOut",
      }, 0);
  }

  /* Service Section JS End */
  /* Industries Section Start *//* Industries Section Start */

  let swiperIndustries;
  if ($('.industries-section').length > 0) {
    function initIndustriesSection() {
      if ($(window).width() > 1260) {

        // Destroy Swiper if it exists
        if (swiperIndustries) {
          swiperIndustries.destroy(true, true);
          swiperIndustries = null;
        }

        // Remove Swiper-related classes
        $(".product-industries-slider").removeClass("swiper-wrapper");
        $(".industries-slider-small").removeClass("swiper-slide");

        // Remove any existing GSAP pin spacers
        if ($(".industries-section").parent().hasClass("pin-spacer")) {
          $(".industries-section").unwrap();
        }

        const slider = document.querySelector(".slider");
        const sliderItems = document.querySelectorAll(".slider-item");
        const totalItems = sliderItems.length;

        // Calculate total scroll distance as % (each item width in vw)
        const totalScroll = totalItems * 100 / totalItems; // or adjust multiplier for spacing

        // Set initial position
        gsap.set(slider, { xPercent: 0 });

        // Animate horizontally
        gsap.to(slider, {
          xPercent: -((100 / totalItems) * (totalItems - 1)), // move left for all cards
          ease: "none",
          scrollTrigger: {
            trigger: ".industries-section",
            pin: true,          // 🔹 pin section
            start: "top top",   // when section hits top
            end: "+=" + (totalItems * 100) + "vw", // adjust distance for all items
            scrub: 3,           // smooth scroll
          },
        });
      }

      else {
        // Kill GSAP ScrollTrigger and remove pin spacer
        ScrollTrigger.getAll().forEach(trigger => {
          if (trigger.trigger === document.querySelector(".industries-section")) {
            trigger.kill();
          }
        });

        if ($(".industries-section").parent().hasClass("pin-spacer")) {
          $(".industries-section").unwrap();
        }

        // Initialize Swiper
        if (!$(".product-industries-slider").hasClass("swiper-wrapper")) {
          $(".product-industries-slider").addClass("swiper-wrapper");
          $(".industries-slider-small").addClass("swiper-slide");
        }

        if (!swiperIndustries) {
          swiperIndustries = new Swiper(".product-industries-slider", {
            slidesPerView: 3,
            spaceBetween: 0,
            slidesPerGroup: 1,
            loop: true,
            autoplay: {
              delay: 2500,
              disableOnInteraction: false
            },
            pagination: {
              el: '.product-industries-slider .swiper-pagination',
              clickable: true
            },
            breakpoints: {
              991: {
                slidesPerView: 2,
              },
              520: {
                slidesPerView: 1,
              }
            }
          });
        }
      }
    }

    // Initialize on load
    initIndustriesSection();

    // Re-check on resize
    $(window).resize(function () {
      initIndustriesSection();
    });


  }
  /* Industries Section end */
  /* SVG Draw JS Start */
  gsap.registerPlugin(ScrollTrigger);
  const sections = document.querySelectorAll(".gloabl-banner-inner-page, .inner-service-sec, .work-culture");
  if (sections) {
    sections.forEach((section) => {
      const paths = section.querySelectorAll(".svg-draw-sec svg path");
      const svgElement = section.querySelector(".svg-draw-sec svg");
      const svgBanner = section.querySelector(".svg-about-banner");

      if (svgElement) {
        gsap.set(svgElement, { opacity: 0 }); // Set SVG opacity to 0 initially
      }

      ScrollTrigger.create({
        trigger: section,
        start: "top 75%",
        onEnter: () => {
          if (svgElement) {
            gsap.to(svgElement, { opacity: 1, duration: 0.3 }); // Fade in SVG when animation starts
          }

          paths.forEach((path) => {
            const length = path.getTotalLength();

            gsap.set(path, {
              strokeDasharray: length,
              strokeDashoffset: length,
            });

            gsap.to(path, {
              strokeDashoffset: 0,
              duration: 1,
              ease: "power1.inOut",
              delay: 0.1,
              onComplete: () => {
                if (svgBanner) {
                  gsap.to(svgBanner, { opacity: 1, duration: 0.5 });
                }
              },
            });
          });
        },
      });
    });
  }
  /* SVG Draw JS End */



  /* Typing Animation JS Start */

  // var textWrappers = document.querySelectorAll(
  //   "h3:not(.development-service .service-item h3), h1:not(.hero-wrapper h1), h2:not(.map-wrapper-address-mobile .accordion-item .accordion-header):not(.no-effect), h5, h6, a.ft-button span, h4:not(.development-service .service-item .development-service-title ), .seo-title-secondary,  .h2"
  // );
  //  // Exclude .development-service h3

  // textWrappers.forEach((textWrapper) => {
  //   textWrapper.innerHTML = textWrapper.textContent
  //     .split(/\s+/)
  //     .map(word => {
  //       let letters = word.split('').map(letter => `<span class='letter'>${letter}</span>`).join('');
  //       return `<span class='word'>${letters}</span>`;
  //     })
  //     .join(' ');
  // });

  // const observer = new IntersectionObserver((entries, observer) => {
  //   entries.forEach(entry => {
  //     if (!entry.isIntersecting) return;

  //     const target = entry.target;
  //     target.style.opacity = 1;

  //     // Animate each letter
  //     const letters = target.querySelectorAll('.word .letter');
  //     letters.forEach((letter, i) => {
  //       letter.style.opacity = 0;
  //       letter.style.transform = 'translateX(40px) scaleX(0.3)';
  //       letter.style.transition = 'opacity 800ms ease-out, transform 800ms ease-out';

  //       setTimeout(() => {
  //         letter.style.opacity = 1;
  //         letter.style.transform = 'translateX(0) scaleX(1)';
  //       }, 200 + 25 * i);
  //     });

  //     // Animate siblings after all letters
  //     const siblings = Array.from(target.parentElement.children).filter(el =>
  //       el !== target && (el.matches('p') || el.matches('a') || el.matches('ul') || el.classList.contains('cust-summary-wrapper'))
  //     );

  //     siblings.forEach((el, i) => {
  //       el.style.opacity = 0;
  //       el.style.transition = 'opacity 500ms ease-out';
  //       setTimeout(() => {
  //         el.style.opacity = 1;
  //       }, 200 * i + (200 + 25 * letters.length)); // start after letters animation
  //     });

  //     observer.unobserve(target);
  //   });
  // }, { threshold: 0.1 });


  // Element.prototype.nextElementSiblings = function (selector) {
  //   let siblings = [];
  //   let nextSibling = this.nextElementSibling;
  //   while (nextSibling) {
  //     if (nextSibling.matches(selector)) {
  //       siblings.push(nextSibling);
  //     } else if (nextSibling.matches('h3:not(.development-service .service-item h3), h1:not(.hero-wrapper h1), h2:not(.map-wrapper-address-mobile .accordion-item .accordion-header), h5, h6, a.ft-button span, h4:not(.development-service .service-item .development-service-title)')) {
  //       break;
  //     }
  //     nextSibling = nextSibling.nextElementSibling;
  //   }
  //   return siblings;
  // };

  // textWrappers.forEach(textWrapper => observer.observe(textWrapper));



  /* Typing Animation JS End */

  /* Blog slider JS Start */
  document.addEventListener("DOMContentLoaded", function () {
    let blogData = [
      {
        "link": "/blog/ada-health-clone-solutions",
        "image": "/assets/img/ada-health-clone-solutions.webp",
        "alt": "AI symptom checker app development guide  Ada Health clone architecture 2026",

        "category": "AI Development",
        "title": "How to Build a Character AI Clone: Blueprint of AI Development"
      },
      {
        "link": "/blog/multipass-ai-clone-solutions-guide",
        "image": "/assets/img/multipass-ai-clone-solutions-guide.webp",
        "alt": "Multipass AI Clone Solutions’s Complete Guide",

        "category": "AI Development",
        "title": "Build a Multi-Model AI Consensus Engine Like Multipass"
      },
      {
        "link": "/blog/polybuzz-ai-clone-solutions",
        "image": "/assets/img/polybuzz-ai-clone-solutions.webp",
        "alt": "Build a PolyBuzz AI Clone App: Features, Cost & Tech Stack (2026)",

        "category": "AI Development",
        "title": "Build a PolyBuzz AI Clone App: Features, Cost & Tech Stack (2026)"
      },

      {
        "link": "/blog/scalable-and-secure-online-ecommerce-store",
        "image": "/assets/img/scalable-and-secure-online-ecommerce-store.webp",
        "alt": "Scalable, Secure & High-Performance Online E-commerce Store",

        "category": "Web Development",
        "title": "Web Development for E-commerce – Building a Scalable & Secure Online Store"
      },
      {
        "link": "/blog/how-to-build-crushon-ai-clone-app",
        "image": "/assets/img/how-to-build-crushon-ai-clone-app.webp",
        "alt": "CrushOn AI Clone: AI Chat App Tutorial",

        "category": "Ai Development",
        "title": "How to Build a CrushOn AI Clone — Your Step-by-Step AI Chat App Guide"
      },
      {
        "link": "/blog/ai-in-digital-marketing-how-brands-stay-ahead",
        "image": "/assets/img/ai-in-digital-marketing-how-brands-stay-ahead.webp",
        "alt": "AI in Digital Marketing",

        "category": "Digital Marketing",
        "title": "Affordable Progressive Web App Development Services: Transparent Pricing for Modern Businesses"
      }
    ];

    const blogWrapper = document.getElementById("blogWrapper");

    if (!blogWrapper) return;





    blogWrapper.innerHTML = "";


    blogData.forEach(item => {
      const slide = `
      <div class="swiper-slide" aria-label="Go to blog page">
        <div class="blog-post-slide">
          <a href="${item.link}">
            <div class="blog-img">
              <img src="${item.image}" alt="${item.alt}">
            </div>
            <div class="bottom-text">
              <p>${item.category}</p>
              <p class="h3">${item.title}</p>
            </div>
          </a>
        </div>

        <a href="${item.link}">
          <div class="blog-swiper-bottom-arrow">
            <span>
              <img src="/assets/img/post-arrow.svg" alt="arrow-icon">
            </span>
          </div>
          <span class="btn-text"></span>
        </a>
      </div>
    `;
      blogWrapper.insertAdjacentHTML("beforeend", slide);
    });


    new Swiper(".myblog-post", {
      slidesPerView: 5,
      spaceBetween: 50,
      centeredSlides: true,
      loop: true,
      autoplay: { delay: 3000, disableOnInteraction: false },
      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },
      breakpoints: {
        1661: { spaceBetween: 40 },
        1440: { slidesPerView: 3, spaceBetween: 40 },
        1200: { slidesPerView: 2, spaceBetween: 30 },
        880: { slidesPerView: 2, spaceBetween: 20 },
        576: { slidesPerView: 1, spaceBetween: 20 },
      },
    });
  });

  /* Blog slider JS end */

  /* Technologies Section Start */

  function startAnimationLoop(element) {
    // Store interval reference on element so we can stop it later
    if (element._animationInterval) return; // prevent duplicates

    element._animationInterval = setInterval(() => {
      element.classList.add("animated");

      setTimeout(() => {
        element.classList.remove("animated");
      }, 2000);
    }, 5000);
  }

  function stopAnimationLoop(element) {
    if (element._animationInterval) {
      clearInterval(element._animationInterval);
      element._animationInterval = null;
    }
  }

  function observeAnimations() {
    const classNames = [
      "my_section_main",
      "cutting-line-embed",
      "cutting-tool-icon-wrap",
    ];

    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            startAnimationLoop(entry.target);
          } else {
            stopAnimationLoop(entry.target);
          }
        });
      },
      {
        threshold: 0.5, // 50% in viewport before triggering
      }
    );

    classNames.forEach((className) => {
      document.querySelectorAll(`.${className}`).forEach((el) => {
        observer.observe(el);
      });
    });
  }

  // Call once
  observeAnimations();


  /* Service SEction Shap Animation Start */

  if (document.querySelector('.inner-service-wrapper')) {

    gsap.to(".inner-shap-1", {
      rotation: 360,
      x: "100vw",
      y: "100vh",
      repeat: -1,
      yoyo: true,
      paused: true,
      scrollTrigger: {
        trigger: ".inner-service-wrapper",
        start: "top bottom",
        end: "bottom 80%",
        scrub: true,
        toggleActions: "play none none reverse",
      }
    });

    gsap.to(".inner-shap-2", {
      rotation: -360,
      x: "-100vw",
      y: "-100vh",
      repeat: -1,
      yoyo: true,
      paused: true,
      scrollTrigger: {
        trigger: ".inner-service-wrapper",
        start: "top bottom",
        end: "bottom 80%",
        scrub: true,
        toggleActions: "play none none reverse",
      }
    });

    const serviceWrapper = document.querySelector('.inner-service-wrapper');
    const serviceShap = document.querySelector('.inner-service-shap');

    const observerService = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          serviceShap.classList.add('fixed');
        }
      });
    }, { threshold: 0.1 });

    observerService.observe(serviceWrapper);

    const serviceList = document.querySelector('.inner-service-list');

    const observerServiceList = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (!entry.isIntersecting) {
          serviceShap.classList.remove('fixed');
        }
      });
    }, { threshold: 0 });

    observerServiceList.observe(serviceList);
  }


  /* Squad Section JS Start */

  if (document.querySelector('.hire-main-sec')) {

    gsap.registerPlugin(ScrollTrigger);

    const timeline = gsap.timeline({
      scrollTrigger: {
        trigger: ".hire-main-sec .hire-con-row",
        start: "top center",
        end: "bottom center",
        toggleActions: "play none none none",
      },
    });

    const items = gsap.utils.shuffle(gsap.utils.toArray(".hire-con-item"));

    items.forEach((item) => {
      timeline.to(item, {
        opacity: 1,
        filter: "blur(0px)",
        duration: 0.2,
        delay: Math.random() * 0.2,
      });
    });

  }

  /* Squad Section JS End */

  /* Animated Section JS Start */

  if (document.querySelector(".Process-main-sec")) {

    let swiperInstance;

    function initializeAnimationOrSlider() {
      const screenWidth = window.innerWidth;
      const boxes = gsap.utils.toArray(".animated-section .item");
      const containerWidth = boxes.reduce((acc, el) => acc + el.offsetWidth, 0);
      const offset = -containerWidth + window.innerWidth / 20;

      // Swiper for smaller screens (below 1260px)
      if (screenWidth <= 1260) {

        // Destroy GSAP animation if active
        ScrollTrigger.getAll().forEach(trigger => trigger.kill());
        gsap.globalTimeline.clear();

        // Destroy previous Swiper instance if it exists
        if (swiperInstance) {
          swiperInstance.destroy(true, true);
        }

        // Check if Swiper class is present
        let sliderContainer = document.querySelector(".animated-box-main");
        if (sliderContainer) {
          sliderContainer.classList.add("swiper-container");
          let slides = document.querySelectorAll(".animated-section .item");

          slides.forEach(slide => {
            slide.classList.add("swiper-slide");
          });

          // Create Swiper wrapper
          let wrapper = document.createElement("div");
          wrapper.classList.add("swiper-wrapper");

          slides.forEach(slide => {
            wrapper.appendChild(slide);
          });

          // Remove existing content & add Swiper wrapper
          sliderContainer.innerHTML = "";
          sliderContainer.appendChild(wrapper);

          // Add Swiper pagination & navigation
          let pagination = document.createElement("div");
          pagination.classList.add("swiper-pagination");

          sliderContainer.appendChild(pagination);

          // Initialize Swiper
          swiperInstance = new Swiper(".animated-box-main", {
            slidesPerView: 2.5,
            slidesPerGroup: 1,
            spaceBetween: 20,
            loop: true,
            autoplay: {
              delay: 8000,
              disableOnInteraction: false,
            },
            pagination: {
              el: ".swiper-pagination",
              clickable: true,
            },
            breakpoints: {
              1025: {
                slidesPerView: 2,
                spaceBetween: 20,
              },
              768: {
                slidesPerView: 1,
              },
            },
          });

        }

      } else {
        // Destroy Swiper if active
        if (swiperInstance) {
          swiperInstance.destroy(true, true);
          swiperInstance = null;
        }

        // Initialize GSAP animation
        gsap.to(boxes, {
          scrollTrigger: {
            trigger: ".Process-main-sec",
            start: "top top",
            end: () => `+=${containerWidth}`,
            scrub: true,
            pin: ".Process-main-sec",
            pinSpacing: true,
            // onEnter: () => console.log("Process section pinned and scrolling started"),
            // onLeave: () => console.log("Process section unpinned, next section can scroll"),
            // onLeaveBack: () => console.log("Scroll has passed back through the Process section"),
          },
          x: offset,
          ease: "linear"
        });

        let initialPos = boxes[0].getBoundingClientRect().left;

        function scaleItems() {
          const currentPos = boxes[0].getBoundingClientRect().left;
          const scaleAmount = Math.min(Math.abs((initialPos - currentPos) * 0.0175), 1);
          gsap.to(boxes, { scale: 1 - scaleAmount / 2 });

          initialPos = currentPos;
          requestAnimationFrame(scaleItems);
        }

        scaleItems();
      }
    }

    // Run on load
    initializeAnimationOrSlider();

    // Run on window resize
    window.addEventListener("resize", initializeAnimationOrSlider);
  }


  /* Animated Section JS End */


  /* FAQ accordion js Start */

  jQuery(document).ready(function ($) {
    $(".faq-area-block-wrap").click(function () {
      var $this = $(this);
      var $content = $this.find(".at-tab");
      var $title = $this.find(".at-title");

      if ($title.hasClass("active")) {

        $title.removeClass("active");
        $this.removeClass("active");
        $content.slideUp();
      } else {

        $(".faq-area-block-wrap .at-title.active")
          .removeClass("active")
          .closest(".faq-area-block-wrap")
          .removeClass("active")
          .find(".at-tab")
          .slideUp();

        $title.addClass("active");
        $this.addClass("active");
        $content.slideDown();
      }
    });
  });

  /* FAQ accordion js End */


  /* Dedicated Team js start */
  $(document).ready(function () {
    if ($(".dedicated-slider").length > 0) {
      new Swiper('.swiper-container', {
        slidesPerView: 1,
        spaceBetween: 0,
        loop: true,

        autoplay: {
          delay: 3000,
          disableOnInteraction: false,
        },


      });
    }
  });
  /* Dedicated Team js end */


  /* Specialize Section JS Start */

  const tabButtons = document.querySelectorAll('.tab-btn');
  const tabContents = document.querySelectorAll('.tab-contents');

  tabButtons.forEach((button) => {
    button.addEventListener('click', () => {
      const targetTab = button.getAttribute('data-tab');

      tabButtons.forEach((btn) => btn.classList.remove('active'));
      tabContents.forEach((content) => content.classList.remove('active'));

      button.classList.add('active');
      document.getElementById(targetTab).classList.add('active');
    });
  });

  /* Specialize Section JS End */


  /* Our Projects js start */

  if ($(".projects-main-sec").length > 0) {
    if (window.innerWidth > 767) {
      if ($(".projects-list-2").length > 0) {
        gsap.utils.toArray('.project-list-wrapper').forEach((wrapper) => {
          const items = wrapper.querySelectorAll('.projects-item');

          items.forEach((item, index) => {
            const isEven = (index + 1) % 2 === 0;

            gsap.from(item, {
              x: isEven ? 100 : -100,
              opacity: 0,
              duration: 1,
              ease: 'power2.out',
              scrollTrigger: {
                trigger: item,
                start: "top 80%",
                end: "top 50%",
                toggleActions: "play none none reverse",
              }
            });
          });
        });
      }
      else {
        gsap.utils.toArray('.projects-item').forEach((item, index) => {
          const isEven = (index + 1) % 2 === 0;

          gsap.from(item, {
            x: isEven ? 100 : -100,
            opacity: 0,
            duration: 1,
            ease: 'power2.out',
            scrollTrigger: {
              trigger: item,
              start: "top 80%",
              end: "top 50%",
              toggleActions: "play none none reverse",
            }
          });
        });
      }
    }
  }

  /* Our Projects js End */


  /* App Features Tabs js Start */
  document.addEventListener("DOMContentLoaded", function () {
    const tabButtons = document.querySelectorAll(".tab-buttons li");
    const tabContents = document.querySelectorAll(".tab-content");

    tabButtons.forEach((button) => {
      button.addEventListener("click", function () {
        const tabId = this.getAttribute("data-tab");

        tabButtons.forEach((btn) => btn.classList.remove("active"));
        tabContents.forEach((content) => content.classList.remove("active"));

        this.classList.add("active");
        document.getElementById(tabId).classList.add("active");
      });
    });
  });

  /* App Features Tabs js End */

  /* service - review from client js Start */

  // var swiper = new Swiper(".od-app-slider", {
  //   direction: "vertical",
  //   mousewheelControl: true,
  //   slidesPerView: 1,
  //   loop: true,
  //   pagination: {
  //     el: ".od-app-pagi",
  //     clickable: true,
  //   },
  // });


  var swiper = new Swiper(".od-app-slider", {
    direction: "vertical",
    // autoplay: {
    //   delay: 2500,
    //   disableOnInteraction: false
    // },
    pagination: {
      el: ".od-app-pagi",
      clickable: true,
    },
  });

  $(document).ready(function () {
    var swiper = new Swipern(".review-client-slider", {
      effect: "cards",
      grabCursor: true,
      initialSlide: 2,
      speed: 800,
      loop: false,
      centeredSlides: true,
      rotate: true,
      navigation: {
        nextEl: ".review-client-next",
        prevEl: ".review-client-prev",
      },

    });
  });

  /* service - review from client js End */

  /* Technology stack sec js start */

  if ($(".technology-stack-sec").length > 0) {
    const tabButtonsSec = document.querySelectorAll('.technology-stack-sec .tab-buttons');
    const tabContentsSec = document.querySelectorAll('.technology-stack-sec .tab-contents');

    tabButtonsSec.forEach((button) => {
      button.addEventListener('click', () => {
        const targetTabSec = button.getAttribute('data-tab');

        tabButtonsSec.forEach((btn) => btn.classList.remove('active'));
        tabContentsSec.forEach((content) => content.classList.remove('active'));

        button.classList.add('active');
        document.getElementById(targetTabSec).classList.add('active');
      });
    });

    gsap.registerPlugin(ScrollTrigger);

    gsap.fromTo(
      ".technology-stack-sec .animated-img-sec img",
      {
        scale: 0,
        y: 1000,
      },
      {
        scale: 1,
        y: 0,
        rotation: 0,
        duration: 1,
        ease: "power2.out",
        scrollTrigger: {
          trigger: ".technology-stack-sec",
          start: "top 0%",
          end: "bottom 0%",
          toggleActions: "play reverse play reverse",
        },
      }
    );
  }

  /* Technology stack sec js end */

  /* Product details page  js start  */
  /* summary js start  */


  const firstLi = document.querySelector('ul li');
  if (firstLi) {
    firstLi.classList.add('active');
  }

  document.querySelectorAll('.link-item').forEach((link) => {
    link.addEventListener('click', (event) => {
      const targetId = link.getAttribute('href');


      if (targetId.startsWith('#')) {
        event.preventDefault();

        document.querySelectorAll('ul li').forEach((li) => {
          li.classList.remove('active');
        });

        const parentLi = link.closest('li');
        if (parentLi) {
          parentLi.classList.add('active');
        }

        const targetElement = document.querySelector(targetId);
        if (targetElement) {
          targetElement.scrollIntoView({ behavior: 'smooth' });
        }
      }
    });
  });

  document.addEventListener('DOMContentLoaded', () => {

    window.scrollTo(0, 0);
  });



  document.addEventListener("DOMContentLoaded", function () {
    const sections = document.querySelectorAll(".cust-summary-wrapper");
    const navItems = document.querySelectorAll(".summary-list li");

    function updateActiveSection() {
      let currentActive = null;

      sections.forEach((section) => {
        const rect = section.getBoundingClientRect();
        if (rect.top <= 100 && rect.bottom >= 100) { // Checking if the section is near the top
          currentActive = section.id;
        }
      });

      if (currentActive) {
        navItems.forEach((li) => li.classList.remove("active"));
        document.querySelector(`.summary-list a[href="#${currentActive}"]`)?.parentElement.classList.add("active");
      }
    }

    window.addEventListener("scroll", updateActiveSection);
    updateActiveSection(); // Run on page load
  });


  $(document).ready(function () {
    if ($('.summary-sec').length) {
      $('.summary-sec > .container > .row:nth-of-type(2)').addClass('sticky-row');
    }
  });


  /* summary js end  */

  /* Product details page  js end  */

  /* Portfolio Sec JS Start */

  var swiper = new Swiper(".portfolio-thumb", {
    spaceBetween: 0,
    slidesPerView: 1,
    freeMode: true,

    watchSlidesProgress: true,
  });
  var swiper2 = new Swiper(".portfolio-slider", {
    spaceBetween: 0,
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },
    pagination: {
      el: ".swiper-pagination",
      type: "fraction",
    },
    thumbs: {
      swiper: swiper,
    },
  });

  $(document).ready(function () {
    // Set first li active initially
    if ($(".projects-list-2").length) {
      $(".project-list-inner .item").removeClass("active").first().addClass("active");

      // On scroll
      $(window).on("scroll", function () {
        $(".project-list-wrapper").each(function () {
          var sectionTop = $(this).offset().top - $(window).scrollTop();

          if (sectionTop <= 0) {
            var id = $(this).attr("id");

            $(".project-list-inner .item").removeClass("active");
            $('.project-list-inner .item a[href="#' + id + '"]')
              .parent()
              .addClass("active");
          }
        });
      });
    }
  });


  /* Portfolio Sec JS End */

  /* career accordion js Start */

  jQuery(document).ready(function ($) {
    $(".career-area-block-wrap").click(function () {
      var $this = $(this);
      var $content = $this.find(".at-tab");
      var $title = $this.find(".at-title");

      if ($title.hasClass("active")) {

        $title.removeClass("active");
        $this.removeClass("active");
        $content.slideUp();
      } else {

        $(".career-area-block-wrap .at-title.active")
          .removeClass("active")
          .closest(".career-area-block-wrap")
          .removeClass("active")
          .find(".at-tab")
          .slideUp();

        $title.addClass("active");
        $this.addClass("active");
        $content.slideDown();
      }
    });
  });

  /* career accordion js End */
  jQuery(document).ready(function ($) {
    const $gifWrapper = $('.imgaes-row.gif-wrapper');
    const $gifCols = $('.gif-col-wrapper');
    const $yourPassion = $('.cust-img-effect');


    $yourPassion.on('mouseenter', function () {
      $('body').addClass('overflow-hidden-x');
      $(document).on('mousemove.gifWrapper', function (e) {
        const mouseX = e.pageX;
        const mouseY = e.pageY;
        $gifWrapper.css({
          'left': (mouseX - 100) + 'px',
          'top': mouseY + 'px',
        });
        const rotation = (mouseX / $(window).width()) * 48 - 24;
        $gifWrapper.css('transform', 'rotate(' + rotation + 'deg)');
      });

      $gifCols.each(function (index) {
        $(this).on('mouseenter', function () {
          $gifWrapper.css({
            'visibility': 'visible',
            'opacity': '1'
          });
          $gifWrapper.find('img').each(function (i) {
            if (i === index) {
              $(this).show();
            } else {
              $(this).hide();
            }
          });
        });

        $(this).on('mouseleave', function () {
          $gifWrapper.css({
            'visibility': 'hidden',
            'opacity': '0'
          });
        });
      });
    });

    $yourPassion.on('mouseleave', function () {

      $('body').removeClass('overflow-hidden-x');

      $(document).off('mousemove.gifWrapper');
      $gifWrapper.css({
        'left': '0px',
        'top': '0px',
        'transform': 'rotate(0deg)',
        'visibility': 'hidden',
        'opacity': '0'
      });
    });
  });

  /* Event Sec JS Start */

  $(document).ready(function () {
    function setupVideoHover(swiperInstance) {
      const videos = document.querySelectorAll('.event-video-img video');

      videos.forEach(video => {
        video.onmouseover = null;
        video.onmouseout = null;

        video.addEventListener('mouseover', () => {
          video.play();
          swiperInstance.autoplay.stop();
        });

        video.addEventListener('mouseout', () => {
          video.pause();
          swiperInstance.autoplay.start();
        });
      });
    }

    function playActiveVideoOnSingleSlide(swiperInstance) {
      const currentBreakpoint = swiperInstance.currentBreakpoint;
      const slidesPerView = swiperInstance.params.breakpoints[currentBreakpoint]?.slidesPerView || swiperInstance.params.slidesPerView;

      if (slidesPerView === 1) {
        const allVideos = document.querySelectorAll('.event-video-img video');
        allVideos.forEach(video => video.pause());

        const activeSlide = swiperInstance.slides[swiperInstance.activeIndex];
        const video = activeSlide.querySelector('video');
        if (video && swiperIsVisible) {
          video.play();
        }
      }
    }

    let swiperIsVisible = false;

    const swiper = new Swiper('.event-video-swiper', {
      loop: true,
      slidesPerView: 6,
      spaceBetween: 25,
      autoplay: {
        delay: 0,
        disableOnInteraction: false
      },
      speed: 3000,
      breakpoints: {
        1920: { slidesPerView: 6, spaceBetween: 25 },
        1660: { slidesPerView: 5, spaceBetween: 25 },
        1028: { slidesPerView: 2, spaceBetween: 25 },
        480: { slidesPerView: 1, spaceBetween: 25, autoplay: false, speed: 1000, }
      },
      on: {
        init: function () {
          setupVideoHover(this);
          playActiveVideoOnSingleSlide(this);
        },
        slideChangeTransitionEnd: function () {
          setupVideoHover(this);
          playActiveVideoOnSingleSlide(this);
        }
      }
    });

    // 👁️ IntersectionObserver to check visibility
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        swiperIsVisible = entry.isIntersecting;

        if (swiperIsVisible) {
          playActiveVideoOnSingleSlide(swiper); // Trigger video play if visible and slidePerView == 1
        } else {
          // Pause all videos when swiper is not visible
          const allVideos = document.querySelectorAll('.event-video-img video');
          allVideos.forEach(video => video.pause());
        }
      });
    }, {
      threshold: 0.5 // Adjust as needed
    });

    const swiperElement = document.querySelector('.event-video-swiper');
    if (swiperElement) {
      observer.observe(swiperElement);
    }
  });


  /* Event Sec JS End */

  /* Review Section BG Animation JS Start */

  if (document.querySelector(".review-client-area")) {
    const screenWidth = window.innerWidth;
    const screenHeight = window.innerHeight;

    let x1 = 0, y1 = 0, dx1 = 6, dy1 = 6;
    let x2 = screenWidth - 50, y2 = screenHeight - 50, dx2 = -8, dy2 = -8;

    const leftTop = document.querySelector(".left-top");
    const bottomRight = document.querySelector(".bottom-right");

    function animateDivs() {
      x1 += dx1;
      y1 += dy1;

      if (x1 <= 0 || x1 >= screenWidth - 50) dx1 *= -1;
      if (y1 <= 0 || y1 >= screenHeight - 50) dy1 *= -1;

      leftTop.style.transform = `translate(${x1}px, ${y1}px)`;

      x2 += dx2;
      y2 += dy2;

      if (x2 <= 0 || x2 >= screenWidth - 50) dx2 *= -1;
      if (y2 <= 0 || y2 >= screenHeight - 50) dy2 *= -1;

      bottomRight.style.transform = `translate(${x2 - (screenWidth - 50)}px, ${y2 - (screenHeight - 50)}px)`;

      requestAnimationFrame(animateDivs);
    }

    animateDivs();
  }

  /* Review Section BG Animation JS End */


  /* Header Mega Menu JS Start */

  document.addEventListener("DOMContentLoaded", function () {
    const menuItems = document.querySelectorAll(".navbar .mega-menu > ul > li");

    menuItems.forEach(item => {
      let link = item.querySelector("a");
      let subMenu = item.querySelector(".second-sub-menu-inner");

      if (link && subMenu) {
        link.addEventListener("mouseenter", function () {
          subMenu.style.display = "block";
          subMenu.style.opacity = "0";
          setTimeout(() => {
            subMenu.style.opacity = "1";
          }, 10);

          item.classList.add("active-menu");
        });

        subMenu.addEventListener("mouseenter", function () {
          subMenu.style.display = "block";
          subMenu.style.opacity = "1";
        });

        item.addEventListener("mouseleave", function (event) {
          if (!item.contains(event.relatedTarget)) {
            subMenu.style.opacity = "0";
            setTimeout(() => {
              subMenu.style.display = "none";
            }, 300);
            item.classList.remove("active-menu");
          }
        });
      }
    });

    document.querySelectorAll(".second-sub-menu > li > a").forEach(item => {
      item.addEventListener("mouseenter", function () {
        let thirdSubMenu = this.nextElementSibling;

        if (!this.classList.contains("opend-sub-menu")) {
          document.querySelectorAll(".third-sub-menu.active").forEach(activeMenu => {
            activeMenu.style.opacity = "0";
            setTimeout(() => {
              activeMenu.style.display = "none";
            }, 300);
            activeMenu.classList.remove("active");
          });

          document.querySelectorAll(".second-sub-menu > li > a.opend-sub-menu").forEach(activeLink => {
            activeLink.classList.remove("opend-sub-menu");
          });
        }

        if (thirdSubMenu && thirdSubMenu.classList.contains("third-sub-menu")) {
          thirdSubMenu.style.display = "flex";
          thirdSubMenu.style.opacity = "0";
          setTimeout(() => {
            thirdSubMenu.style.opacity = "1";
            thirdSubMenu.classList.add("active");
            this.classList.add("opend-sub-menu");
          }, 10);
        }
      });
    });

    document.querySelectorAll(".second-sub-menu > li:first-child > a").forEach(link => {
      link.classList.add("opend-sub-menu");
      let thirdSubMenu = link.nextElementSibling;
      if (thirdSubMenu && thirdSubMenu.classList.contains("third-sub-menu")) {
        thirdSubMenu.style.display = "flex";
        thirdSubMenu.style.opacity = "1";
        thirdSubMenu.classList.add("active");
      }
    });

    document.querySelectorAll(".second-sub-menu > li").forEach(item => {
      item.addEventListener("mouseenter", function () {
        let parentSubMenu = this.closest(".second-sub-menu-inner");
        if (parentSubMenu) {
          parentSubMenu.style.display = "block";
          parentSubMenu.style.opacity = "1";
        }
      });

      item.addEventListener("mouseleave", function (event) {
        let parentSubMenu = this.closest(".second-sub-menu-inner");
        if (parentSubMenu && !parentSubMenu.contains(event.relatedTarget)) {
          parentSubMenu.style.opacity = "0";
          setTimeout(() => {
            parentSubMenu.style.display = "none";
          }, 300);
        }
      });
    });

    document.querySelectorAll(".navbar .mega-menu ul li a").forEach(link => {
      link.addEventListener("click", function (event) {
        event.stopPropagation();
      });
    });

    document.querySelectorAll(".second-sub-menu-inner").forEach(subMenu => {
      subMenu.addEventListener("click", function (event) {
        event.stopPropagation();
      });
    });
  });

  $('.v3-sub-menu-level-2').css({
    height: '0',
  });

  $('.mega-menu.menu-v3 > ul > li').hover(
    function () {
      var submenu = $(this).find('.v3-sub-menu-level-2');
      var fullHeight = submenu.prop('scrollHeight') + 'px';
      submenu.css('height', fullHeight);
      $(this).addClass('hovered');
    },
    function () {
      var submenu = $(this).find('.v3-sub-menu-level-2');
      submenu.css('height', '0');
      $(this).removeClass('hovered');
    }
  );
  /* Header Mega Menu JS End */

  /* Mobile Menu JS start */
  jQuery(document).ready(function ($) {

    // Toggle Mobile Menu
    $('.menu-toggle-link').click(function (event) {
      event.stopPropagation();
      $(this).toggleClass('active');
      $('.mobile-menu').toggleClass('open');
      $('body').toggleClass('overflow-hide');
    });

    // Toggle first-level menu
    $(".list-item-1 > .link-item-level-1 .submenu-toggle").click(function (e) {
      e.preventDefault();
      e.stopPropagation();

      let $parentLi = $(this).closest(".list-item-1");
      let $submenu = $parentLi.find(".second-sub-menu").first();

      if ($submenu.is(":visible")) {
        $submenu.slideUp(300);
        $parentLi.removeClass("active");
      } else {
        $(".second-sub-menu").not($submenu).slideUp(300);
        $(".list-item-1").removeClass("active");
        $submenu.stop(true, true).slideDown(300);
        $parentLi.addClass("active");
      }
    });

    // Toggle second-level menu
    $(".second-sub-menu-li > a .submenu-toggle, .second-sub-menu-li > span .submenu-toggle").click(function (e) {
      e.preventDefault();
      e.stopPropagation();

      let $parentLi = $(this).closest(".second-sub-menu-li");
      let $submenu = $parentLi.find(".third-sub-menu").first();

      if ($submenu.is(":visible")) {
        $submenu.slideUp(300);
        $parentLi.removeClass("active");
      } else {
        $(".third-sub-menu").not($submenu).slideUp(300);
        $(".second-sub-menu-li").removeClass("active");
        $submenu.stop(true, true).slideDown(300);
        $parentLi.addClass("active");
      }
    });

    // Click outside to close
    $(document).click(function (event) {
      if (!$('.menu-toggle-link').is(event.target) && !$('.mobile-menu').is(event.target) && $('.mobile-menu').has(event.target).length === 0) {
        $('.menu-toggle-link').removeClass('active');
        $('.mobile-menu').removeClass('open');
        $(".second-sub-menu, .third-sub-menu").slideUp(300);
        $(".list-item-1, .second-sub-menu-li").removeClass("active");
        $('body').removeClass('overflow-hide');
      }
    });

  });

  /* Mobile Menu JS end */

  document.addEventListener("DOMContentLoaded", function () {
    let activeBtn = document.querySelector(".btn-gal.on");
    let firstCategory = activeBtn ? [...activeBtn.classList].find(cls => cls !== "btn-gal" && cls !== "on") : null;
    let gridItems = document.querySelectorAll(".grid .grid-item");

    function filterItems(category) {
      gridItems.forEach(item => {
        let hasCategory = item.classList.contains(category);
        if (category === "all" || hasCategory) {
          item.style.display = "block";
        } else {
          item.style.display = "none";
        }
      });
    }

    if (firstCategory && firstCategory !== "all") {
      filterItems(firstCategory);
    } else {
      filterItems("all");
    }

    document.querySelectorAll(".btn-gal").forEach(button => {
      button.addEventListener("click", function () {
        let selectedCategory = [...this.classList].find(cls => cls !== "btn-gal" && cls !== "on");

        filterItems(selectedCategory);

        document.querySelectorAll(".btn-gal").forEach(btn => btn.classList.remove("on"));
        this.classList.add("on");
      });
    });
  });



  /* Event Name JS Start */
  if ($(".join-team-sec").length > 0) {
    const container = document.querySelector(".join-team-container");
    const sectionsjoin = gsap.utils.toArray(".join-team-image-wrapper");

    gsap.to(sectionsjoin, {
      xPercent: -100 * (sectionsjoin.length - 1),
      ease: "none",
      scrollTrigger: {
        trigger: container,
        pin: true,
        scrub: 1,
        snap: 1 / (sectionsjoin.length - 1),
        end: () => "+=" + container.offsetWidth
      }
    });
  }


  /* Event Name JS End */

  /* Blur Image JS Start */

  if ($(".hero-wrapper").length > 0) {

    document.addEventListener("DOMContentLoaded", function () {
      let heroWrapper = document.querySelector(".hero-wrapper");
      let flyingBlurImages = document.querySelectorAll(".hero-wrapper .flying.blur");

      heroWrapper.addEventListener("mouseenter", function () {
        flyingBlurImages.forEach(img => {
          img.style.filter = "blur(2px)";
        });
      });

      heroWrapper.addEventListener("mouseleave", function () {
        flyingBlurImages.forEach(img => {
          img.style.filter = "none";
        });
      });
    });

  };


  /* Blur Image JS End */

  /* Phases On Demands App Info Section JS Start */

  document.addEventListener("DOMContentLoaded", function () {
    const cardBodies = document.querySelectorAll(".card-body");

    cardBodies.forEach(cardBody => {
      const cardInfoBlocks = cardBody.querySelectorAll(".card-info-block");

      cardInfoBlocks.forEach((cardInfoBlock, index) => {
        const ul = cardInfoBlock.querySelector("ul");
        const heading = cardInfoBlock.querySelector("h4");

        // Always show the first item or those with empty heading
        if ((index === 0 || (heading && heading.textContent.trim() === "")) && ul) {
          ul.classList.add("show");
          // ul.style.height = `${ul.scrollHeight}px`;

          if (heading) {
            heading.classList.add("active"); // Add 'active' to h4
          }
        }
      });
    });

    const headers = document.querySelectorAll(".card-info-block h4");
    headers.forEach(header => {
      header.addEventListener("click", function () {
        const ul = this.nextElementSibling;
        const cardBody = this.closest(".card-body");

        if (ul && ul.tagName === "UL") {
          if (ul.classList.contains("show")) {
            return; // Already open
          }

          const columnUls = cardBody.querySelectorAll(".card-info-block ul");
          const columnH4s = cardBody.querySelectorAll(".card-info-block h4");

          columnUls.forEach(otherUl => {
            otherUl.classList.remove("show");
            // otherUl.style.height = "0";
            otherUl.style.opacity = "0";
          });

          columnH4s.forEach(otherH4 => {
            otherH4.classList.remove("active"); // Remove active class from all h4s
          });

          ul.classList.add("show");
          // ul.style.height = `${ul.scrollHeight}px`;
          ul.style.opacity = "1";
          this.classList.add("active"); // Add active class to clicked h4

          ul.addEventListener("transitionend", function () {
            // ul.style.height = "auto";  
            ul.style.opacity = "1";
          }, { once: true }); // Run only once
        }
      });
    });
  });



  /* Phases On Demands App Info Section JS Start */

  /* Specialize Section Click JS Start */

  let tabArrow = document.querySelector(".tab-arrow");

  if (tabArrow) {
    tabArrow.addEventListener("click", function () {
      let tabCol = document.querySelector(".tab-col");
      tabCol.scrollBy({ left: 200, behavior: "smooth" });
    });
  }


  /* Specialize Section Click JS End */

  let projectLists = document.querySelectorAll(".project-list-wrapper");

  if (projectLists.length > 0) {
    function initSwiperProjects() {
      let screenWidth = window.innerWidth;

      projectLists.forEach((projectList, index) => {
        if (screenWidth <= 767) {
          if (!projectList.classList.contains("swiper")) {
            projectList.classList.add("swiper");

            let projectsItems = Array.from(projectList.querySelectorAll(".projects-item"));

            let projectsWrapper = document.createElement("div");
            projectsWrapper.classList.add("swiper-wrapper");

            projectsItems.forEach(item => {
              let swiperSlide = document.createElement("div");
              swiperSlide.classList.add("swiper-slide");
              swiperSlide.appendChild(item);
              projectsWrapper.appendChild(swiperSlide);
            });

            projectList.innerHTML = "";
            projectList.appendChild(projectsWrapper);

            let pagination = document.createElement("div");
            pagination.classList.add("swiper-pagination");
            pagination.classList.add(`swiper-pagination-${index}`); // unique pagination
            projectList.appendChild(pagination);

            new Swiper(projectList, {
              slidesPerView: 1,
              spaceBetween: 16,
              pagination: {
                el: `.swiper-pagination-${index}`,
                clickable: true,
              },
            });
          }
        } else {
          if (projectList.classList.contains("swiper")) {
            projectList.classList.remove("swiper");

            let projectsItems = Array.from(projectList.querySelectorAll(".swiper-slide .projects-item"));

            projectList.innerHTML = "";

            projectsItems.forEach(item => {
              projectList.appendChild(item);
            });
          }
        }
      });
    }

    initSwiperProjects();
    window.addEventListener("resize", initSwiperProjects);
  }



  let projectList = document.querySelector(".projects-list");

  if (projectList) {
    function initSwiperProject() {
      let screenWidth = window.innerWidth;

      if (screenWidth <= 767) {
        if (!projectList.classList.contains("swiper")) {
          projectList.classList.add("swiper");

          let projectsItems = Array.from(projectList.querySelectorAll(".projects-item"));

          let projectsWrapper = document.createElement("div");
          projectsWrapper.classList.add("swiper-wrapper");

          projectsItems.forEach(item => {
            let swiperSlide = document.createElement("div");
            swiperSlide.classList.add("swiper-slide");
            swiperSlide.appendChild(item);
            projectsWrapper.appendChild(swiperSlide);
          });

          projectList.innerHTML = "";
          projectList.appendChild(projectsWrapper);

          let pagination = document.createElement("div");
          pagination.classList.add("swiper-pagination");
          projectList.appendChild(pagination);

          new Swiper(".projects-list", {
            slidesPerView: 1,
            spaceBetween: 16,
            pagination: {
              el: ".swiper-pagination",
              clickable: true,
            },
          });
        }
      }
      else {
        if (projectList.classList.contains("swiper")) {
          projectList.classList.remove("swiper");

          let projectsItems = Array.from(projectList.querySelectorAll(".swiper-slide .projects-item"));

          projectList.innerHTML = "";

          projectsItems.forEach(item => {
            projectList.appendChild(item);
          });
        }
      }
    }

    initSwiperProject();
    window.addEventListener("resize", initSwiperProject);
  }


  /* Related Blog Section Click JS End */

  /* Related News Section Click JS End */

  let relatedNews = document.querySelector(".related-news-row");
  let originalItems = Array.from(document.querySelectorAll(".news-item"));

  let swiperInstance;

  if (relatedNews) {
    function initSwiperNews() {
      let screenWidth = window.innerWidth;

      if (screenWidth <= 991) {
        if (!relatedNews.classList.contains("swiper-initialized")) {
          relatedNews.classList.add("swiper");

          let projectsWrapper = document.createElement("div");
          projectsWrapper.classList.add("swiper-wrapper");

          originalItems.forEach(item => {
            let swiperSlide = document.createElement("div");
            swiperSlide.classList.add("swiper-slide");
            swiperSlide.appendChild(item);
            projectsWrapper.appendChild(swiperSlide);
          });

          relatedNews.innerHTML = "";
          relatedNews.appendChild(projectsWrapper);

          let pagination = document.createElement("div");
          pagination.classList.add("swiper-pagination");
          relatedNews.appendChild(pagination);

          swiperInstance = new Swiper(".related-news-row", {
            slidesPerView: 2,
            spaceBetween: 20,
            pagination: {
              el: ".swiper-pagination",
              clickable: true,
            },
            breakpoints: {
              991: {
                slidesPerView: 2,
              },
              768: {
                slidesPerView: 1,
              },
            },
          });
        }
      } else {
        if (relatedNews.classList.contains("swiper")) {
          if (swiperInstance) {
            swiperInstance.destroy(true, true);
          }
          relatedNews.classList.remove("swiper", "swiper-initialized");

          relatedNews.innerHTML = "";

          originalItems.forEach(item => {
            relatedNews.appendChild(item);
          });
        }
      }
    }

    initSwiperNews();

    let resizeTimeout;
    window.addEventListener("resize", function () {
      clearTimeout(resizeTimeout);
      resizeTimeout = setTimeout(initSwiperNews, 100);
    });
  }



  /* Industries Page JS Start */

  let industries = document.querySelector(".industries-list-wrapper");

  if (industries) {
    let industriesItems = Array.from(document.querySelectorAll(".industries-list-box"));
    let industriesInstance = null;

    function initSwiperOrList() {
      let screenWidth = window.innerWidth;

      if (screenWidth < 1025 && !industriesInstance) {
        industries.classList.add("swiper");

        let wrapper = document.createElement("div");
        wrapper.classList.add("swiper-wrapper");

        industriesItems.forEach(item => {
          let slide = document.createElement("div");
          slide.classList.add("swiper-slide");
          slide.appendChild(item);
          wrapper.appendChild(slide);
        });

        industries.innerHTML = "";
        industries.appendChild(wrapper);

        let pagination = document.createElement("div");
        pagination.classList.add("swiper-pagination");
        industries.appendChild(pagination);

        industriesInstance = new Swiper(".industries-list-wrapper", {
          slidesPerView: 3,
          slidesPerGroup: 3,
          spaceBetween: 20,
          pagination: {
            el: ".swiper-pagination",
            clickable: true,
          },
          breakpoints: {
            1025: {
              slidesPerView: 3,
              slidesPerGroup: 3
            },
            992: {
              slidesPerView: 2,
              slidesPerGroup: 2
            },
            641: {
              slidesPerView: 1,
              slidesPerGroup: 1
            }
          }
        });

      } else if (screenWidth > 1025 && industriesInstance) {
        industriesInstance.destroy(true, true);
        industriesInstance = null;

        industries.classList.remove("swiper");

        industries.innerHTML = "";
        industriesItems.forEach(item => industries.appendChild(item));
      }
    }

    initSwiperOrList();

    let resizeTimeout;
    window.addEventListener("resize", function () {
      clearTimeout(resizeTimeout);
      resizeTimeout = setTimeout(initSwiperOrList, 100);
    });
  }



  let loaderHeight = document.querySelector(".loaderWrapper.home");
  if (loaderHeight) {
    function setLoaderHeight() {
      if ($(window).width() <= 767) {
        let heroHeight = $('.hero-wrapper').outerHeight();
        $('.loaderWrapper').css('height', heroHeight);
      } else {
        $('.loaderWrapper').css('height', '100vh');
      }
    }

    $(document).ready(function () {
      setLoaderHeight();
      const observer = new MutationObserver(function () {
        setLoaderHeight();
      });

      observer.observe(document.querySelector('.hero-wrapper'), { attributes: true, childList: true, subtree: true });
    });

    $(window).resize(function () {
      setLoaderHeight();
    });
  }


  /* Join Team Mobile JS Start */

  let teamMobile = document.querySelector(".join-team-mobile");

  if (teamMobile) {

    var swiper = new Swiper(".team-mobile-slider", {
      spaceBetween: 20,
      pagination: {
        el: ".swiper-pagination",
        clickable: true
      },
    });

  }

  /* Join Team Mobile JS End */


  /* Opportunities Section JS Start */

  let opportunities = document.querySelector(".career-area");

  if (opportunities) {

    document.addEventListener("DOMContentLoaded", function () {
      document.querySelectorAll('.career-area-block-wrap').forEach(wrapper => {
        const topBlock = wrapper.querySelector('.career-area-block-top');
        const otherInfo = wrapper.querySelector('.other-info');
        const buttonWrapper = wrapper.querySelector('.button-wraaper');
        const cardText = wrapper.querySelector('.card-text');

        const originalOtherInfoParent = otherInfo.parentElement;
        const originalButtonWrapperParent = buttonWrapper.parentElement;

        function moveElements() {
          if (window.innerWidth <= 767) {
            if (!cardText.contains(otherInfo)) {
              cardText.appendChild(otherInfo);
              cardText.appendChild(buttonWrapper);
            }
          } else {
            if (!originalOtherInfoParent.contains(otherInfo)) {
              originalOtherInfoParent.appendChild(otherInfo);
            }
            if (!originalButtonWrapperParent.contains(buttonWrapper)) {
              originalButtonWrapperParent.appendChild(buttonWrapper);
            }
          }
        }

        window.addEventListener('load', moveElements);
        window.addEventListener('resize', moveElements);
      });

      // Prevent toggle behavior when clicking the button link
      document.querySelectorAll('.button-wraaper a, .career-area-block-wrap .card-text').forEach(link => {
        link.addEventListener('click', function (e) {
          e.stopPropagation(); // Prevents the button click from triggering toggle logic
        });
      });

    });

  }

  /* Opportunities Section JS End */

  /* Dynamic Year Js Start */

  document.getElementById("footer-year").textContent = new Date().getFullYear();

  /* Dynamic Year Js End */


  /* testimoanil thumbnail js  */
  $(document).ready(function () {
    // Testimonial video thumbnail play
    $('.cust-video-play-icon').on('click', function () {
      var videoSrc = $(this).closest('.testimonial-thumbnail-img').data('video');
      if (videoSrc) {
        $('#testimonialVideo source').attr('src', videoSrc);
        $('#testimonialVideo')[0].load();
        $('#videoModal').fadeIn();
      }
    });

    // Close modal on background or close button click
    $('.video-modal-close, #videoModal').on('click', function (e) {
      if ($(e.target).is('#videoModal') || $(e.target).is('.video-modal-close')) {
        $('#videoModal').fadeOut(function () {
          var video = $('#testimonialVideo')[0];
          video.pause();
          video.currentTime = 0;
        });
      }
    });
  });


  /* testimoanil thumbnail js  */


  /* Browser specific class JS Start */

  (function () {
    const ua = navigator.userAgent.toLowerCase();
    const body = document.body;

    // OS Detection
    if (ua.indexOf('windows') !== -1) {
      body.classList.add('os-windows');
    } else if (ua.indexOf('mac') !== -1) {
      body.classList.add('os-mac');
    } else if (ua.indexOf('linux') !== -1) {
      body.classList.add('os-linux');
    } else if (/android/.test(ua)) {
      body.classList.add('os-android');
    } else if (/iphone|ipad|ipod/.test(ua)) {
      body.classList.add('os-ios');
    }

    // Browser Detection
    if (ua.indexOf('chrome') > -1 && ua.indexOf('edge') === -1 && ua.indexOf('opr') === -1) {
      body.classList.add('browser-chrome');
    } else if (ua.indexOf('firefox') > -1) {
      body.classList.add('browser-firefox');
    } else if (ua.indexOf('safari') > -1 && ua.indexOf('chrome') === -1) {
      body.classList.add('browser-safari');
    } else if (ua.indexOf('opr') > -1 || ua.indexOf('opera') > -1) {
      body.classList.add('browser-opera');
    } else if (ua.indexOf('edge') > -1) {
      body.classList.add('browser-edge');
    } else if (ua.indexOf('trident') > -1 || ua.indexOf('msie') > -1) {
      body.classList.add('browser-ie');
    }

  })();

  /* Browser specific class JS End */


  /* custom js for phases slider items start */

  let phasesRow = document.querySelector(".phases-row");

  document.addEventListener("DOMContentLoaded", function () {
    var slideCount = document.querySelectorAll('.phases-row .swiper-slide').length;
    const prevArrow = document.querySelector('.phases-row .swiper-button-prev');
    const nextArrow = document.querySelector('.phases-row .swiper-button-next');

    const swiper = new Swiper('.phases-row', {
      slidesPerView: 3,
      slidesPerGroup: 1, // 👈 Needed for scrolling 1 slide per click
      spaceBetween: 24,
      loop: false,
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
      breakpoints: {
        1024: {
          slidesPerView: 2,
          slidesPerGroup: 2
        },
        641: {
          slidesPerView: 1,
          slidesPerGroup: 1
        }
      }
    });
    if (phasesRow) {
      // Conditionally hide arrows only if screen is >1024px and slides ≤ 3
      function updateArrowVisibility() {
        const isDesktop = window.innerWidth > 1024;
        if (isDesktop && slideCount <= 3) {
          prevArrow.style.display = 'none';
          nextArrow.style.display = 'none';
        } else {
          prevArrow.style.display = 'flex';
          nextArrow.style.display = 'flex';
        }
      }

      updateArrowVisibility();
      window.addEventListener('resize', updateArrowVisibility);
    }
  });


  function setEqualTitleHeights() {
    var maxHeight = 0;
    var $titles = $('.phases-on-demands-app-info-box .card-title');

    // Reset height to auto before recalculating
    $titles.css('height', 'auto');

    // Find the tallest title
    $titles.each(function () {
      var thisHeight = $(this).outerHeight();
      if (thisHeight > maxHeight) {
        maxHeight = thisHeight;
      }
    });

    // Set all titles to the tallest height
    $titles.css('height', maxHeight + 'px');
  }

  $(document).ready(function () {
    setEqualTitleHeights();
  });

  $(window).on('resize', function () {
    setEqualTitleHeights();
  });

  /* custom js for phases slider items end */

  $(document).ready(function () {
    $('.gloabl-banner-inner-page .inner-page-wrapper .cust-row-breadcurmb.row').each(function () {
      if (!$(this).hasClass('for-mobile')) {
        $(this).remove();
      }
    });
  });

  $(document).ready(function () {
    if ($('.cursor').length) {
      $('.cursor').prependTo('body');
    }
  });

  var checkInterval = setInterval(function () {
    var bannerWrapper = document.querySelector('.gloabl-banner-inner-page'); // Make sure class name is correct

    if (bannerWrapper) {
      // Clear interval once banner is found
      clearInterval(checkInterval);

      // Now check if breadcrumb is missing (check both possible classes)
      var breadcrumb = document.querySelector(
        '.cust-row-breadcurmb.row.for-mobile, .cust-row-breadcrumb.row.for-mobile'
      );

      if (!breadcrumb) {
        bannerWrapper.classList.add('no-breadcrumb');
      }
    }
  }, 100); // Check every 100ms

  /* include form captcha.js */
  $.getScript('/assets/js/captcha.js?v=' + new Date().getTime())
    .done(function () {
      $(document).trigger('captchaLoaded');
    })
    .fail(function () {
      console.error('Failed to load captcha.js');
    });


  /* include form talkformload.js */

  $.getScript('/assets/js/forms/talkformload.js?v=' + new Date().getTime())
    .fail(function () {
      console.error('Failed to load talkformload.js');
    });



  /* include form hireformload.js */

  $.getScript('/assets/js/forms/hireformload.js?v=' + new Date().getTime())
    .fail(function () {
      console.error('Failed to load hireformload.js');
    });


  /* include form submit js */

  $.getScript('/assets/js/form-submit.js?v=' + new Date().getTime())
    .done(function () {
      // console.log('form-submit.js loaded and executed.');
    })
    .fail(function () {
      console.error('Failed to load form-submit.js');
    });

  /* custom-js for career page popup start */

  $(document).ready(function () {
    if ($('.career-form-btn').length) {
      $('.career-form-btn').on('click', function (e) {
        e.preventDefault();

        const jobTitle = $(this).closest('.career-area-block-wrap').find('.card-title').text().trim();
        const jobLocation = $(this).closest('.career-area-block-wrap').find('.career-location').text().trim();

        $('#caeer-selected-post').val(jobTitle).prop('readonly', true);
        $('#career-location-post').val(jobLocation).prop('readonly', true);

        $('.career-popup-overlay').fadeIn().addClass("active");
        $('html').addClass('career-page-body-hidden');
      });

      $('.career-close-toggle').on('click', function () {
        $('.career-popup-overlay').fadeOut().removeClass("active");
        $('html').removeClass('career-page-body-hidden');
      });

      $('.career-popup-overlay').on('click', function (e) {
        if (e.target !== this) return;
        $(this).fadeOut().removeClass("active");
        $('html').removeClass('career-page-body-hidden');
      });
    }
  });


  /* custom-js for career page popup end */

  if ($('.lets-talk-form').length) {
    $('.lets-talk-form').before(`
      <div id="toast" class="toast">Your Message submitted successfully!</div>
    `);
  }

  /* emp testimonial video js start */
  // if (document.querySelector('.emp-testimonial-sec')) {

  //   }
  if ($('.emp-tab-wrapper').length) {
    $('.emp-tab-wrapper .nav-link').on('click', function () {
      // Remove autoplay and pause all videos
      $('.emp-tab-wrapper .nav-link video').each(function () {
        this.pause();
        $(this).removeAttr('autoplay');
      });

      // Set autoplay and play the clicked video
      const video = $(this).find('video')[0];
      if (video) {
        $(video).attr('autoplay', true);
        video.play();
      }
    });
  }

  document.addEventListener("DOMContentLoaded", function () {
    if (document.querySelector('.emp-swiper')) {
      document.querySelectorAll(".emp-swiper").forEach((el) => {
        new Swiper(el, {
          slidesPerView: 1,          // always one slide
          spaceBetween: 25,
          loop: true,
          navigation: {
            nextEl: el.querySelector(".swiper-button-next"),
            prevEl: el.querySelector(".swiper-button-prev"),
          },
        });
      });
      // Handle play icon click
      const playButtons = document.querySelectorAll('.cust-video-play-icon');

      playButtons.forEach(btn => {
        btn.addEventListener('click', function () {
          const currentWrapper = this.closest('.emp-video-wrapper');
          const currentVideo = currentWrapper.querySelector('video');
          const currentImage = currentWrapper.querySelector('img');

          // Pause all other videos
          document.querySelectorAll('.emp-video-wrapper').forEach(wrapper => {
            const video = wrapper.querySelector('video');
            const image = wrapper.querySelector('img');
            const icon = wrapper.querySelector('.cust-video-play-icon');

            if (video !== currentVideo) {
              video.pause();
              video.currentTime = 0;
              if (image) {
                image.style.opacity = 1;
                image.style.zIndex = 1;
              }
              if (icon) {
                icon.style.opacity = 1;
                icon.style.zIndex = 2;
              }
            }
          });

          // Style updates for current
          this.style.zIndex = 0;
          this.style.opacity = 0;
          if (currentImage) {
            currentImage.style.zIndex = 0;
            currentImage.style.opacity = 0;
          }

          currentVideo.play();
        });
      });

      // Pause all videos on slide change
      swiper.on('slideChange', function () {
        document.querySelectorAll('.emp-video-wrapper').forEach(wrapper => {
          const video = wrapper.querySelector('video');
          const image = wrapper.querySelector('img');
          const icon = wrapper.querySelector('.cust-video-play-icon');

          video.pause();
          video.currentTime = 0;

          if (image) {
            image.style.opacity = 1;
            image.style.zIndex = 1;
          }
          if (icon) {
            icon.style.opacity = 1;
            icon.style.zIndex = 2;
          }
        });
      });
    }
  });

  /* emp testimonial video js start */

  jQuery(document).ready(function ($) {
    if ($('.career-filter').length) {   // ✅ only run if filter exists
      $('#locationFilter').on('change', function () {
        var selected = $(this).val().toLowerCase();

        if (selected === 'all') {
          $('.career-area-block-wrap').show();
        } else {
          $('.career-area-block-wrap').hide().filter(function () {
            return $(this).find('.career-location').text().toLowerCase().includes(selected);
          }).show();
        }
      });
    }
  });


  document.addEventListener("DOMContentLoaded", function () {
    let currentPath = window.location.pathname;

    // remove trailing slash & .html if present
    currentPath = currentPath.replace(/\/$/, "").replace(/\.html$/, "");

    let menuLinks = document.querySelectorAll(".mega-menu a");

    menuLinks.forEach(link => {
      let linkPath = link.getAttribute("href");

      if (!linkPath) return;

      // remove trailing slash & .html
      linkPath = linkPath.replace(/\/$/, "").replace(/\.html$/, "");

      // ✅ check: exact match OR currentPath starts with menu link (for sub-pages like /blog/xyz)
      if (currentPath === linkPath || currentPath.startsWith(linkPath + "/")) {
        // set current on matched link
        link.classList.add("current");

        // walk up and set current on parent <li> > a
        let parentLi = link.closest("li");
        while (parentLi) {
          let parentLink = parentLi.querySelector(":scope > a");
          if (parentLink) {
            parentLink.classList.add("current");
          }
          parentLi = parentLi.parentElement.closest("li");
        }
      }
    });
  });

  $(document).ready(function () {

    if ($('#hire-dev-cta').length) {


      $('#hire-dev-cta').on('click', function () {
        $('html').css('overflow', 'hidden');
      });


      $('.hire-dev-popup a.career-close-toggle').on('click', function () {
        if ($(this).length) {
          $('html').css('overflow', '');
        }
      });
    }
  });

  document.addEventListener("click", function () {
    // Wait a bit for the dropdown to render
    setTimeout(function () {
      const itiDropdown = document.querySelector(".iti__country-list");

      if (itiDropdown) {
        ["wheel", "touchmove"].forEach(evt => {
          itiDropdown.addEventListener(
            evt,
            function (e) {
              e.stopPropagation(); // keep scroll inside dropdown
            },
            { passive: false }
          );
        });
      }
    }, 50);
  });

  /* recent blogs list js start */
  document.addEventListener("DOMContentLoaded", function () {

    // === JSON-LIKE BLOG DATA ===
    const blogs = [

      {
        category: "ai-development",
        url: "/blog/glean-ai-clone-development",
        image: "/assets/img/glean-ai-clone-development.webp",
        alt: "What Is Glean AI Enterprise Work AI Platform Guide",
        tag: "AI Development",
        title: "What Is Glean AI? Features, Benefits & How It Works"
      },

      {
        category: "ai-development",
        url: "/blog/talkie-ai-clone-development",
        image: "/assets/img/talkie-ai-clone-development.webp",
        alt: "Talkie AI Clone App AI Character Chat Platform Architecture Guide 2026",
        tag: "AI Development",
        title: "Talkie AI Clone App Development: Features, Cost & Tech (2026)"
      },

      {
        category: "ai-development",
        url: "/blog/opus-clip-ai-clone-development-guide",
        image: "/assets/img/opus-clip-ai-clone-development.webp",
        alt: "Opus Clip AI Clone Development Guide 2026",
        tag: "AI Development",
        title: "Opus Clip AI Clone Development: The Complete 2026 Guide"
      },

    ];



    // === TAKE ONLY LATEST 3 BLOGS ===
    const latestBlogs = blogs.slice(0, 3);

    // === TARGET CONTAINER ===
    const container = document.querySelector(".cust-blog-row.related-blog-row");
    if (!container) return;

    container.innerHTML = "";

    // === LOOP & RENDER HTML ===
    latestBlogs.forEach(blog => {

      const card = `
      <div class="col-12 col-md-6 col-lg-4 col-xl-4 grid-item element-item ${blog.category}">
        <div class="Our-Blogs-gallery-outer-block-box">
          <a href="${blog.url}" class="blog-link">
            <div class="card h-100">
              <div class="card-img">
                <img src="${blog.image}" class="card-img-top" alt="${blog.alt}">
              </div>
              <div class="card-body">
                <p class="card-title">${blog.tag}</p>
                <p class="card-text">${blog.title}</p>
              </div>
            </div>
            <div class="card-readmore">
              <span class="card-arrow d-flex align-items-center justify-content-center">
                <svg width="21" height="22" viewBox="0 0 21 22">
                  <path d="M14.3876 7.11042L5.90234 15.5957" stroke="#F26E65" stroke-linecap="round" stroke-linejoin="round" />
                  <path d="M6.60938 6.40332L15.1006 6.40927L15.0947 14.8886" stroke="#F26E65" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
              </span>
            </div>
          </a>
        </div>
      </div>
    `;

      container.insertAdjacentHTML("beforeend", card);
    });

    let relatedBlogs = document.querySelector(".related-blog-row");
    let originalBlogItems = Array.from(document.querySelectorAll(".grid-item"));
    let swiperBlogInstance;

    if (relatedBlogs) {
      function initSwiperBlog() {
        let screenWidth = window.innerWidth;

        if (screenWidth <= 991) {
          if (!relatedBlogs.classList.contains("swiper-initialized")) {
            relatedBlogs.classList.add("swiper");

            let projectsWrapper = document.createElement("div");
            projectsWrapper.classList.add("swiper-wrapper");

            originalBlogItems.forEach(item => {
              let swiperSlide = document.createElement("div");
              swiperSlide.classList.add("swiper-slide");
              swiperSlide.appendChild(item);
              projectsWrapper.appendChild(swiperSlide);
            });

            relatedBlogs.innerHTML = "";
            relatedBlogs.appendChild(projectsWrapper);

            let pagination = document.createElement("div");
            pagination.classList.add("swiper-pagination");
            relatedBlogs.appendChild(pagination);

            swiperBlogInstance = new Swiper(".related-blog-row", {
              slidesPerView: 2,
              spaceBetween: 20,
              loop: true, // ✅ add loop
              simulateTouch: true,
              allowTouchMove: true,
              grabCursor: true,
              touchRatio: 1, // ✅ ensure swipe sensitivity
              touchReleaseOnEdges: true, // ✅ smoother on edges
              resistanceRatio: 0.65,
              autoplay: {
                delay: 3000,
                disableOnInteraction: false,
              },
              pagination: {
                el: ".swiper-pagination",
                clickable: true,
              },
              breakpoints: {
                991: {
                  slidesPerView: 2,
                },
                768: {
                  slidesPerView: 1,
                },
              },
            });

          }
        } else {
          if (relatedBlogs.classList.contains("swiper")) {
            if (swiperBlogInstance) {
              swiperBlogInstance.destroy(true, true);
            }
            relatedBlogs.classList.remove("swiper", "swiper-initialized");

            relatedBlogs.innerHTML = "";

            originalBlogItems.forEach(item => {
              relatedBlogs.appendChild(item);
            });
          }
        }
      }

      initSwiperBlog();

      let resizeTimeout;
      window.addEventListener("resize", function () {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(initSwiperBlog, 100);
      });
    }



  });
  /* recent blogs list js end */

  /* Disable Click JS Start */

  document.addEventListener("contextmenu", (event) => event.preventDefault());

  document.addEventListener("keydown", (event) => {
    if (
      event.key === "F12" ||
      (event.ctrlKey && event.shiftKey && (event.key === "I" || event.key === "J")) ||
      (event.ctrlKey && event.key === "U")
    ) {
      event.preventDefault();
    }
  });

  document.addEventListener("keyup", (event) => {
    if (event.ctrlKey && event.key === "u") {
      event.preventDefault();
      alert("Viewing source code is disabled!");
    }
  });

  /* Disable Click JS End */

}

/* Chatbot Widget */
const interval = setInterval(() => {
    const el = document.querySelector('#cypherox-chatbot-container .chat-area    ');
  if (el) {
    el.setAttribute('data-lenis-prevent', 'true');
    clearInterval(interval);
  }
}, 300);
(function (w, d) {
  var s = d.createElement("script");
  s.src = "https://chatbot.cypherox.com/assets/cx-chat-widget-v2.js?time=" + Date.now();
  s.defer = true;
  d.body.appendChild(s);
})(window, document);

/* On Load Auto Version Pass JS Start */

if (document.querySelector(".main-style")) {
  document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("main-style").href =
      "/assets/css/styles.css?v=" + Date.now();
  });
}

/* On Load Auto Version Pass JS End */


/* Screen Resize Page Load JS Start */

/* let resizeTimer;

window.addEventListener("resize", function () {
  clearTimeout(resizeTimer);
  resizeTimer = setTimeout(function () {
    location.reload();
  }, 500);
}); */

/* Screen Resize Page Load JS End */

/* New Header JS Start */

document.addEventListener("DOMContentLoaded", function () {
  const officialLink = document.querySelector("li.official-partners");
  const subMenu = document.querySelector("li.company-multi-dropdown .sub-menu-small");
  const dropdownMain = document.querySelector(".company-multi-dropdown-main");

  function openDropdown() {
    subMenu.style.overflow = "visible";
    dropdownMain.style.height = dropdownMain.scrollHeight + "px";
  }

  function closeDropdown() {
    dropdownMain.style.height = "0px";
    subMenu.style.overflow = "hidden";
  }

  officialLink.addEventListener("mouseenter", openDropdown);
  officialLink.addEventListener("mouseleave", function () {
    setTimeout(() => {
      if (!dropdownMain.matches(":hover")) {
        closeDropdown();
      }
    }, 100);
  });

  dropdownMain.addEventListener("mouseenter", openDropdown);
  dropdownMain.addEventListener("mouseleave", closeDropdown);
});

/* New Header JS End */