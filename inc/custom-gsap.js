

const lenis = new Lenis();
window.lenis = lenis; // ekspozycja dla floating back-to-top i innych handlerow

lenis.on("scroll", (e) => {
  // console.log(e);
});

function raf(time) {
  lenis.raf(time);
  requestAnimationFrame(raf);
}

requestAnimationFrame(raf);


// Marquee logos (.about-us-swiper) — duplikuje zawartosc dla plynnego infinity loop
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.about-us-swiper').forEach((track) => {
    if (track.dataset.marqueeReady) return;
    const clone = track.cloneNode(true);
    clone.setAttribute('aria-hidden', 'true');
    // Wstaw klonowane dzieci do tego samego trackera (zamiast osobnego elementu)
    Array.from(clone.children).forEach((child) => {
      track.appendChild(child);
    });
    track.dataset.marqueeReady = '1';
  });
});


// top bar menu when scrolling down
document.addEventListener('DOMContentLoaded', function() {
  const header = document.getElementById('top-menu');
  if (!header) return;

  window.addEventListener('scroll', function() {
    if (window.scrollY > 150) {
      header.classList.add('scrolled');
    } else {
      header.classList.remove('scrolled');
    }
  });
});


document.addEventListener('DOMContentLoaded', () => {
  const pathname = window.location.pathname;

  if (pathname.includes("/o-nas") || pathname === "/" || pathname.includes("/golarka-shav-maszynka-do-miejsc-intymnych")) {
    const video = document.querySelector('.video-background');
    const testvideo = `<video class="video-background" src="https://shav.pl/wp-content/uploads/animacja-shav-2.mp4" autoplay loop muted playsinline loading="lazy"></video>`;
    
    
    const dHomeBanner = document.querySelector(".placeholder-video-new-film")

    if (dHomeBanner) {
        dHomeBanner.innerHTML = testvideo;
    }

  }
});

document.addEventListener('DOMContentLoaded', () => {
  const pathname = window.location.pathname;

  if (pathname.includes("/o-nas") || pathname === "/" || pathname.includes("/golarka-shav-maszynka-do-miejsc-intymnych")) {

    const testvideo = `<video class="video-background" src="https://shav.pl/wp-content/uploads/film-nowy.mp4" autoplay loop muted playsinline loading="lazy"></video>`;
    
    
    const dHomeBanner = document.querySelector(".placeholder-video")

    if (dHomeBanner) {
        dHomeBanner.innerHTML = testvideo;
    }

  }
});

document.addEventListener('DOMContentLoaded', () => {
  const pathname = window.location.pathname;

  if (pathname.includes("/golarka-do-glowy-crowner")) {

    const testvideo = `<video class="video-background" src="https://shav.pl/wp-content/uploads/lowG.mp4" autoplay loop muted playsinline loading="lazy"></video>`;


    const dHomeBanner = document.querySelector(".placeholder-video-crowner")

    if (dHomeBanner) {
        dHomeBanner.innerHTML = testvideo;
    }

  }
});

document.addEventListener('DOMContentLoaded', () => {
  const pathname = window.location.pathname;

  if (pathname.includes("/golarka-do-twarzy-handler")) {

    const testvideo = `<video class="video-background" src="https://shav.pl/wp-content/uploads/handler.mp4" autoplay loop muted playsinline loading="lazy"></video>`;


    const dHomeBanner = document.querySelector(".placeholder-video-handler")

    if (dHomeBanner) {
        dHomeBanner.innerHTML = testvideo;
    }

  }
});

document.addEventListener('DOMContentLoaded', () => {
  const pathname = window.location.pathname;

  if (pathname.includes("/trymer-do-nosa")) {

    const testvideo = `<video class="video-background" src="https://shav.pl/wp-content/uploads/trymer_hero.mp4" autoplay loop muted playsinline loading="lazy"></video>`;
    
    
    const dHomeBanner = document.querySelector(".placeholder-video")

    if (dHomeBanner) {
        dHomeBanner.innerHTML = testvideo;
    }

  }
});

document.addEventListener('DOMContentLoaded', () => {
  const pathname = window.location.pathname;

  if (pathname.includes("/golarka-shav-basic-maszynka-do-miejsc-intymnych")) {

    const testvideo = `<video class="video-background" src="https://shav.pl/wp-content/uploads/whoweareshav1.mp4" autoplay loop muted playsinline loading="lazy"></video>`;
    
    
    const dHomeBanner = document.querySelector(".placeholder-video-shav1")

    if (dHomeBanner) {
        dHomeBanner.innerHTML = testvideo;
    }

  }
});

document.addEventListener('DOMContentLoaded', () => {
  const pathname = window.location.pathname;

  if (pathname.includes("/balsam-shav")) {

    const testvideo = `<video class="video-background" src="https://shav.pl/wp-content/uploads/C0016.mp4" autoplay loop muted playsinline loading="lazy"></video>`;
    
    
    const dHomeBanner = document.querySelector(".placeholder-video-balsam")

    if (dHomeBanner) {
        dHomeBanner.innerHTML = testvideo;
    }

  }
});

document.addEventListener('DOMContentLoaded', () => {
  const pathname = window.location.pathname;

  if (pathname.includes("/dezodorant-shav")) {

    const testvideo = `<video class="video-background" src="https://shav.pl/wp-content/uploads/C0012.mp4" autoplay loop muted playsinline loading="lazy"></video>`;
    
    
    const dHomeBanner = document.querySelector(".placeholder-video-dezo")

    if (dHomeBanner) {
        dHomeBanner.innerHTML = testvideo;
    }

  }
});


document.addEventListener('DOMContentLoaded', () => {
  const pathname = window.location.pathname;

  if (pathname.includes("/golarka-shav-basic-maszynka-do-miejsc-intymnych")) {

    const testvideo = `<video class="cutfree-video" autoplay muted loop playsinline>
    <source src="https://shav.pl/wp-content/uploads/wodoodpornosc.mp4" type="video/mp4">
    Twoja przeglądarka nie obsługuje tagu wideo.
</video>`;
    
    
    const dHomeBanner = document.querySelector(".cechy-videoshav1-placeholder-1")

    if (dHomeBanner) {
        dHomeBanner.innerHTML = testvideo;
    }

  }
});

document.addEventListener('DOMContentLoaded', () => {
  const pathname = window.location.pathname;

  if (pathname.includes("/golarka-shav-basic-maszynka-do-miejsc-intymnych")) {

    const testvideo = `<video class="cutfree-video" autoplay muted loop playsinline>
    <source src="https://shav.pl/wp-content/uploads/bezpieczenstwo.mp4" type="video/mp4">
    Twoja przeglądarka nie obsługuje tagu wideo.
</video>`;
    
    
    const dHomeBanner = document.querySelector(".cechy-videoshav1-placeholder-2")

    if (dHomeBanner) {
        dHomeBanner.innerHTML = testvideo;
    }

  }
});


document.addEventListener('DOMContentLoaded', () => {
  const pathname = window.location.pathname;

  if (pathname.includes("/golarka-damska-shav-woman")) {

    const testvideo = `<video class="video-background" src="https://shavwoman.pl/wp-content/uploads/shav-woman-hero.mp4" autoplay loop muted playsinline loading="lazy"></video>`;
    
    
    const dHomeBanner = document.querySelector(".placeholder-video-woman")

    if (dHomeBanner) {
        dHomeBanner.innerHTML = testvideo;
    }

  }
});

document.addEventListener('DOMContentLoaded', () => {
  const pathname = window.location.pathname;

  if (pathname.includes("/golarka-shav-basic-maszynka-do-miejsc-intymnych")) {

    const testvideo = `<video class="cutfree-video" autoplay muted loop playsinline>
    <source src="https://shav.pl/wp-content/uploads/ostrza.mp4" type="video/mp4">
    Twoja przeglądarka nie obsługuje tagu wideo.
</video>`;
    
    
    const dHomeBanner = document.querySelector(".cechy-videoshav1-placeholder-3")

    if (dHomeBanner) {
        dHomeBanner.innerHTML = testvideo;
    }

  }
});

document.addEventListener('DOMContentLoaded', () => {
  const pathname = window.location.pathname;

  if (pathname.includes("/golarka-shav-basic-maszynka-do-miejsc-intymnych")) {

    const testvideo = `<video class="cutfree-video" autoplay muted loop playsinline>
    <source src="https://shav.pl/wp-content/uploads/podswietlenie.mp4" type="video/mp4">
    Twoja przeglądarka nie obsługuje tagu wideo.
</video>`;
    
    
    const dHomeBanner = document.querySelector(".cechy-videoshav1-placeholder-4")

    if (dHomeBanner) {
        dHomeBanner.innerHTML = testvideo;
    }

  }
});

document.addEventListener('DOMContentLoaded', () => {
  const pathname = window.location.pathname;

  if (pathname.includes("/golarka-shav-basic-maszynka-do-miejsc-intymnych")) {

    const testvideo = `<video class="cutfree-video" autoplay muted loop playsinline>
    <source src="https://shav.pl/wp-content/uploads/stacja_ladujaca.mp4" type="video/mp4">
    Twoja przeglądarka nie obsługuje tagu wideo.
</video>`;
    
    
    const dHomeBanner = document.querySelector(".cechy-videoshav1-placeholder-5")

    if (dHomeBanner) {
        dHomeBanner.innerHTML = testvideo;
    }

  }
});


// kolejnosc blokow shav1 
document.addEventListener("DOMContentLoaded", function() {
  if (window.innerWidth <= 786) {
      const container = document.getElementById('custom-blocks-container');
      const block1 = document.getElementById('block1');
      const block2 = document.getElementById('block2');

      // Zamiana kolejności
      container.insertBefore(block2, block1);
  }
});

// CART POPUP



// INSTAGRAM FEED CAROUSEL
document.addEventListener('DOMContentLoaded', () => {
  if (document.querySelector(".instagram-carousel-container")) {
    instagramFeed();
  }});
  

  function instagramFeed() {
    const carousel = document.querySelector('#sbi_images'); // Container for the carousel
    if (!carousel) return;

    const items = carousel.querySelectorAll('.sbi_item'); // Individual carousel items
    const itemsPerView = 4; // Number of items to display at once
    const itemCount = items.length;
    let currentIndex = 0;

    function updateCarousel() {
        const newTransform = -(currentIndex * 100 / itemsPerView) + '%';
        carousel.style.transform = 'translateX(' + newTransform + ')';
    }

    const nextBtn = document.querySelector('.next-instagram');
    const prevBtn = document.querySelector('.prev-instagram');

    if (nextBtn) {
        nextBtn.addEventListener('click', function() {
            currentIndex = Math.min(currentIndex + 1, itemCount - itemsPerView);
            updateCarousel();
        });
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', function() {
            currentIndex = Math.max(currentIndex - 1, 0);
            updateCarousel();
        });
    }

    updateCarousel();
  }





// ZWROTY REKLAMACJA FUNCIONALITY
document.addEventListener("DOMContentLoaded", () => {
  const zwrotyButton = document.getElementById('zwrotyBtn');
  const reklamacjeButton = document.getElementById('reklamacjeBtn');

  if (zwrotyButton && reklamacjeButton) {
      zwrotyButton.addEventListener('click', toggleZwroty);
      reklamacjeButton.addEventListener('click', toggleReklamacje);
  }
});

function toggleZwroty() {
  document.querySelector(".zwroty-container").classList.add("active");
  document.querySelector(".reklamacja-container").classList.remove("active");

  this.classList.add("active");
  document.getElementById('reklamacjeBtn').classList.remove("active");
}

function toggleReklamacje() {
  document.querySelector(".zwroty-container").classList.remove("active");
  document.querySelector(".reklamacja-container").classList.add("active");

  this.classList.add("active");
  document.getElementById('zwrotyBtn').classList.remove("active");
}


// O NAS TEAM
document.addEventListener("DOMContentLoaded", () => {
  const toggleButton = document.getElementById('toggleButton');
  
  if (toggleButton && document.querySelector(".team")) {
      toggleButton.addEventListener('click', rozwijanyTeam);
  }
});

const rozwijanyTeam = function() {
  var boxes = document.querySelectorAll('.team-content .box');
  var isExpanded = this.getAttribute('data-expanded') === 'true';

  if (isExpanded) {
      // Collapse: Hide extra boxes
      for (var i = 8; i < boxes.length; i++) {
          boxes[i].style.display = 'none';
      }
      this.textContent = 'Zobacz wszystkich'; // Change button text
  } else {
      // Expand: Show all boxes
      for (var i = 8; i < boxes.length; i++) {
          boxes[i].style.display = 'flex';
      }
      this.textContent = 'Zwiń'; // Change button text
  }

  this.setAttribute('data-expanded', !isExpanded); // Toggle the expanded state
};



  // O NAS IMAGES

  /**
 * Hero slider functionality
 */

  document.addEventListener("DOMContentLoaded", () => {
    if (document.querySelector(".container-onas-images")) {
        addHeroSlide();
    }
});

const addHeroSlide = function () {
  const sliderItems = document.querySelectorAll(".slider-item-onas");
  const sliderControls = document.querySelectorAll(".poster-box-onas");
  const controlInner = document.querySelector(".control-inner-onas");
  const leftArrow = document.querySelector(".left-arrow-onas");
  const rightArrow = document.querySelector(".right-arrow-onas");

  let lastSliderItem = sliderItems[0];
  let lastSliderControl = sliderControls[0];
  let currentIndex = 0;
  const totalItems = sliderItems.length;

  const itemWidth = sliderControls[0].offsetWidth;
  const itemGap = parseInt(window.getComputedStyle(controlInner).gap) || 0; // Get the gap between items
  // const isMobile = () => window.innerWidth <= 768;




  lastSliderItem.classList.add("active-onas");
  lastSliderControl.classList.add("active-onas");

  const updateArrows = () => {
    // Disable left arrow if first item is active
    if (currentIndex === 0) {
      leftArrow.disabled = true;
      leftArrow.style.opacity = "0.5"; // Dim the arrow visually
    } else {
      leftArrow.disabled = false;
      leftArrow.style.opacity = "1";
    }

    // Disable right arrow if last item is active
    if (currentIndex === 3) {
      rightArrow.disabled = true;
      rightArrow.style.opacity = "0.5"; // Dim the arrow visually
    } else {
      rightArrow.disabled = false;
      rightArrow.style.opacity = "1";
    }
  };

  const sliderStart = function (index) {
    if (index >= 0 && index < totalItems) {
      lastSliderItem.classList.remove("active-onas");
      lastSliderControl.classList.remove("active-onas");

      currentIndex = index;

      const nextSliderItem = sliderItems[currentIndex];
      const nextSliderControl = sliderControls[currentIndex];

      if (nextSliderItem && nextSliderControl) {
        nextSliderItem.classList.add("active-onas");
        nextSliderControl.classList.add("active-onas");

        lastSliderItem = nextSliderItem;
        lastSliderControl = nextSliderControl;

        // Calculate the shift to keep the focused item in place
        // const shift = -currentIndex * sliderControls[0].offsetWidth;
        // controlInner.style.transform = `translateX(${shift}px)`;
        const shift = Math.round(-(currentIndex * (itemWidth + itemGap)));
        controlInner.style.transform = `translateX(${shift}px)`;


        // Update arrow states
        updateArrows();
      }
    }
  };

  // Attach the click event listener to each control button
  sliderControls.forEach((control, index) => {
    control.addEventListener("click", function () {
      sliderStart(index);
    });
  });

  // Attach click event listeners to the arrows
  leftArrow.addEventListener("click", function () {
    if (currentIndex > 0) {
      const newIndex = currentIndex - 1;
      sliderStart(newIndex);
    }
  });

  rightArrow.addEventListener("click", function () {
    if (currentIndex < totalItems - 1) {
      const newIndex = currentIndex + 1;
      sliderStart(newIndex);
    }
  });

  updateArrows();
};
  // O NAS GALLERY

  

// FOOTER BACK TO TOP BUTTON — usuniete (zastapione floating .back-to-top w functions.php).
// Wczesniej tworzylo TRZECIA instancje Lenis co powodowalo konflikt scrollowania
// (pierwszy klik sie "gubil"). Globalna instancja Lenis siedzi na window.lenis (gora pliku).


    // Toggle mobile menu
    // const menuToggle = document.querySelector('.menu-toggle');
    // const nav = document.querySelector('.main-nav ul');
    
    // if (menuToggle) {
    //     menuToggle.addEventListener('click', function() {
    //         nav.classList.toggle('active');
    //     });
    // };



    // background logo 



gsap.registerPlugin(ScrollTrigger);

// Function to check if the device is mobile
function isMobile() {
  return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
}


// BACKGROUND VIDEO ON SCROLL LOGO AND STACJA DOKUJACA
function setupFirstVideoScroll() {
  let videoScroll = document.querySelector(".video-scroll"),
      frameNumber = 0,
      src = videoScroll.currentSrc || videoScroll.src;


       // Add autoplay attribute for mobile but control playback via JS
 if (isMobile()) {
  videoScroll.setAttribute("autoplay", "autoplay");
  videoScroll.load(); // Reload the video to apply the attribute change
}


  let videoScrollTL = gsap.timeline({
    defaults: { duration: 1 },
    scrollTrigger: {
      trigger: ".video-container",
      pin: true,
      start: "top top",
      end: "+=100%",
      scrub: true,
      markers: false,
      onUpdate: self => {
        let duration = videoScroll.duration;
        if (duration) {
          frameNumber = self.progress * duration;
          videoScroll.currentTime = frameNumber;
        }
      }
    }
  });

  function once(el, event, fn, opts) {
    var onceFn = function (e) {
      el.removeEventListener(event, onceFn);
      fn.apply(this, arguments);
    };
    el.addEventListener(event, onceFn, opts);
    return onceFn;
  }

  once(document.documentElement, "touchstart", function (e) {
    videoScroll.play();
    videoScroll.pause();
  });

  once(videoScroll, "loadedmetadata", function () {
    videoScrollTL.fromTo(videoScroll, { currentTime: 0 }, { currentTime: videoScroll.duration - 0.1 });
  });

  setTimeout(function () {
    if (window.fetch) {
      fetch(src).then(function (response) {
        return response.blob();
      }).then(function (response) {
        var blobURL = URL.createObjectURL(response);
        var t = videoScroll.currentTime;
        once(document.documentElement, "touchstart", function (e) {
          videoScroll.setAttribute("src", blobURL);
          videoScroll.currentTime = t + 0.01;
        });
      });
    }
  }, 0);
}

function setupSecondVideoScroll() {
  let videoScroll2 = document.querySelector(".video-scroll2"),
      frameNumber2 = 0,
      src2 = videoScroll2.currentSrc || videoScroll2.src;

  let videoScrollTL2 = gsap.timeline({
    defaults: { duration: 1 },
    scrollTrigger: {
      trigger: ".video-container2",
      pin: true,
      start: "top top",
      end: "+=300%",
      scrub: true,
      markers: false,
      onUpdate: self => {
        let duration = videoScroll2.duration;
        if (duration) {
          frameNumber2 = self.progress * duration;
          videoScroll2.currentTime = frameNumber2;
        }
      }
    }
  });

  function once(el, event, fn, opts) {
    var onceFn = function (e) {
      el.removeEventListener(event, onceFn);
      fn.apply(this, arguments);
    };
    el.addEventListener(event, onceFn, opts);
    return onceFn;
  }

  once(document.documentElement, "touchstart", function (e) {
    videoScroll2.play();
    videoScroll2.pause();
  });

  once(videoScroll2, "loadedmetadata", function () {
    videoScrollTL2.fromTo(videoScroll2, { currentTime: 0 }, { currentTime: videoScroll2.duration - 0.1 });
  });

  setTimeout(function () {
    if (window.fetch) {
      fetch(src2).then(function (response) {
        return response.blob();
      }).then(function (response) {
        var blobURL = URL.createObjectURL(response);
        var t = videoScroll2.currentTime;
        once(document.documentElement, "touchstart", function (e) {
          videoScroll2.setAttribute("src", blobURL);
          videoScroll2.currentTime = t + 0.01;
        });
      });
    }
  }, 0);
}

document.addEventListener("DOMContentLoaded", function () {
  // setupFirstVideoScroll();

});



document.addEventListener("DOMContentLoaded", () => {
  if ((window.location.pathname.includes("/o-nas")) || (window.location.pathname.includes("/golarka-shav-maszynka-do-miejsc-intymnych")) || (window.location.pathname.includes("/sprzedaz-hurtowa")) || (window.location.pathname.includes("/trymer-do-nosa"))|| (window.location.pathname.includes("/sprzedaz-hurtowa")) || (window.location.pathname.includes("/trymer-do-nosa")) || (window.location.pathname.includes("/golarka-shav-basic-maszynka-do-miejsc-intymnych")) || (window.location.pathname.includes("/balsam-shav")) || (window.location.pathname.includes("/dezodorant-shav")) || (window.location.pathname.includes("/golarka-do-twarzy-handler"))) {
    const wrapper = document.querySelector('.about-us-swiper');
    const logos = Array.from(wrapper.children);

    // Ensure each SVG has a defined width if not already set
    logos.forEach(logo => {
      if (logo.tagName.toLowerCase() === 'svg') {
        const width = logo.getBoundingClientRect().width;
        if (!width) {
          logo.style.width = '50px'; // Default width
        }
      }
    });

    // Calculate the total width of all logos combined
    const totalWidth = logos.reduce((acc, logo) => acc + logo.getBoundingClientRect().width, 0);

    // Clone logos to ensure continuous scrolling
    let cloneCount = Math.ceil(window.innerWidth / totalWidth) + 2; // Add extra clones for smooth looping
    for (let i = 0; i < cloneCount; i++) {
      logos.forEach(logo => {
        const clone = logo.cloneNode(true);
        wrapper.appendChild(clone);
      });
    }

    // Calculate the new total width after cloning
    const newTotalWidth = totalWidth * cloneCount;

    // Set the animation duration dynamically
    const scrollSpeed = 50; // You can adjust this for faster/slower scrolling
    wrapper.style.animationDuration = `130s`;

    // Ensure animation is applied
    wrapper.style.animation = `slide ${wrapper.style.animationDuration} linear infinite`;
  }
});

function animate(selector) {
    const element = document.querySelector(selector);
    if (!element) {
        console.error(`Element not found: ${selector}`);
        return;
    }

    return gsap.fromTo(element, 
        { y: 100, opacity: 0, filter: "blur(4px)" }, 
        { 
            y: 0, 
            opacity: 1, 
            filter: "blur(0)",
            duration: 2.8, 
            ease: "power1.out",
        }
    );
}

// FUNKCJE MASZYNKI CZARNE TLO
function initFunctionText() {
  gsap.registerPlugin(ScrollTrigger);

  ScrollTrigger.create({
      trigger: ".features-camera-spec__list-image",
      start: "top 80%",
      onEnter: () => {
          console.log('ScrollTrigger onEnter');
          animateEmergingFromDarkness('.testowydiv');
          animateEmergingFromDarkness('.testowydiv2');
      }
  });
}



  // BLACK BACKGROUND TEXT APPEAR
function initBlackText() {
    gsap.registerPlugin(ScrollTrigger);

    const lines = gsap.utils.toArray("#text-container .line");
  const tl = gsap.timeline({
    scrollTrigger: {
      trigger: ".blacktext-container",
      start: "top top",
      end: "+=200%",
      scrub: true,
      pin: true,
      pinSpacing: true
    }
  });

  // Animate each line from the end to the beginning
     // Animate each line to appear individually
      lines.reverse().forEach(line => {
        tl.fromTo(line, {
          opacity: 0,
          y: 50
        }, {
          opacity: 1,
          y: 0,
          duration: 2,
          ease: "power3.inOut"
        }, "-=1"); // Adjust overlap if needed
      });

      // Animate the color change for the entire text container
      tl.to("#text-container", {
        color: "#fff", // Light blue
        duration: 1,
        ease: "power3.inOut"
      }, 0)
      .to("#text-container", {
        color: "#fff", // Blue
        duration: 1,
        ease: "power3.inOut"
      }, 1.5)
      .to("#text-container", {
        color: "#fff",
        duration: 1,
        ease: "power3.inOut"
      }, 3); // Small delay before final color change-=1"); // Overlap the final color change
      
}


// CECHY TEXT



// BLACK FUNCTIONS MASZYNKA TEXT APPEAR
document.addEventListener("DOMContentLoaded", () => {
    if (document.querySelector(".features-camera-spec__list-image")) {
        initFunctionText();
    }
});

function animateEmergingFromDarkness(selector) {
  const element = document.querySelector(selector);
  if (!element) {
      console.error(`Element not found: ${selector}`);
      return;
  }

  return gsap.fromTo(element, 
      { 
          opacity: 0, 
          filter: 'blur(20px)', // Start with a high blur
          visibility: 'hidden'  // Initially hidden
      },  
      { 
          opacity: 1, 
          filter: 'blur(0px)', // End with no blur
          duration: 1.5, 
          ease: "sine.inOut",
          onStart: () => {
              element.style.visibility = 'visible'; // Make visible when animation starts
          }
      }
  );
}

function faqKontaktHelper() {
  var acc = document.getElementsByClassName("faq-accordion-kontakt");

  for (var i = 0; i < acc.length; i++) {
    acc[i].addEventListener("click", function () {
      // Close all other accordions
      for (var j = 0; j < acc.length; j++) {
        if (acc[j] !== this) {
          acc[j].classList.remove("active");
          acc[j].parentElement.classList.remove("active");
          var panel = acc[j].nextElementSibling;
          var icon = acc[j].querySelector('i');
          panel.style.maxHeight = null;
          panel.style.opacity = 0;
          icon.classList.remove('fa-chevron-up');
          icon.classList.add('fa-chevron-down');
        }
      }

      // Toggle the clicked accordion
      this.classList.toggle("active");
      this.parentElement.classList.toggle("active");

      var panel = this.nextElementSibling;
      var icon = this.querySelector('i');

      if (panel.style.maxHeight) {
          panel.style.maxHeight = null;
          panel.style.opacity = 0;
          icon.classList.remove('fa-chevron-up');
          icon.classList.add('fa-chevron-down');
      } else {
          panel.style.maxHeight = panel.scrollHeight + "px";
          panel.style.opacity = 1;
          icon.classList.remove('fa-chevron-down');
          icon.classList.add('fa-chevron-up');
      }
    });
  }
}

document.addEventListener("DOMContentLoaded", () => {
  if (document.querySelector(".faq-container")) {
      faqKontaktHelper();
  }
});


// FAQ ROZWIJANIE PRODUKTOWA
function faqHelper() {
  var acc = document.getElementsByClassName("faq-accordion");

  for (var i = 0; i < acc.length; i++) {
    acc[i].addEventListener("click", function () {
      // Close all other accordions
      for (var j = 0; j < acc.length; j++) {
        if (acc[j] !== this) {
          acc[j].classList.remove("active");
          acc[j].parentElement.classList.remove("active");
          var panel = acc[j].nextElementSibling;
          var icon = acc[j].querySelector('i');
          panel.style.maxHeight = null;
          panel.style.opacity = 0;
          icon.classList.remove('fa-chevron-up');
          icon.classList.add('fa-chevron-down');
        }
      }

      // Toggle the clicked accordion
      this.classList.toggle("active");
      this.parentElement.classList.toggle("active");

      var panel = this.nextElementSibling;
      var icon = this.querySelector('i');

      if (panel.style.maxHeight) {
          panel.style.maxHeight = null;
          panel.style.opacity = 0;
          icon.classList.remove('fa-chevron-up');
          icon.classList.add('fa-chevron-down');
      } else {
          panel.style.maxHeight = panel.scrollHeight + "px";
          panel.style.opacity = 1;
          icon.classList.remove('fa-chevron-down');
          icon.classList.add('fa-chevron-up');
      }
    });
  }
}



document.addEventListener("DOMContentLoaded", () => {
  if ((document.querySelector(".faq-container")) || (document.querySelector(".faq-container-glowna"))) {
      faqHelper();
  }
});


// stacja dokujaca
document.addEventListener("DOMContentLoaded", () => {
    if (document.querySelector(".video-container2")) {
        setupSecondVideoScroll();

    }
});



// METODY WYSYLKI
// scroll behaviour

document.addEventListener('DOMContentLoaded', (event) => {
  if ((window.location.pathname.includes('/metody-wysylki')) || (window.location.pathname.includes('/metody-platnosci')) || (window.location.pathname.includes('/patent')) || (window.location.pathname.includes('/kariera'))) {
    const wysylkaContainer = document.querySelector('.about-us-span-wysylka-container');
    const blacktextContainer = document.querySelector('.blacktext-container-wysylka');

    if (wysylkaContainer && blacktextContainer) {
      wysylkaContainer.addEventListener('click', () => {
        blacktextContainer.scrollIntoView({
          behavior: 'smooth'
        });
      });
    }
  }
});

document.addEventListener('DOMContentLoaded', (event) => {
  if ((window.location.pathname.includes('/kariera'))) {
    const wysylkaContainer = document.querySelector('.about-us-second-wysylka');
    const blacktextContainer = document.querySelector('.faq-container-glowna');

    if (wysylkaContainer && blacktextContainer) {
      wysylkaContainer.addEventListener('click', () => {
        blacktextContainer.scrollIntoView({
          behavior: 'smooth'
        });
      });
    }
  }
});


document.addEventListener('DOMContentLoaded', (event) => {
  if  ((window.location.pathname.includes('/sprzedaz-hurtowa')) || (window.location.pathname.includes('/kariera'))) {
    const wysylkaContainer = document.querySelector('.about-us-span-wysylka-container');
    const blacktextContainer = document.querySelector('.content-position-helper-mapa');

    if (wysylkaContainer && blacktextContainer) {
      wysylkaContainer.addEventListener('click', () => {
        blacktextContainer.scrollIntoView({
          behavior: 'smooth'
        });
      });
    }
  }
});



// words changing in metody wysylki
document.addEventListener("DOMContentLoaded", () => {
  wordsChanging();
});

function wordsChanging() {
  const changeboxes = document.querySelectorAll('.changebox');
  changeboxes.forEach(changebox => {
      const spans = changebox.querySelectorAll('span');
      if (!spans.length) return;
      let index = 0;

      function changeText() {
          const currentIndex = index;
          const nextIndex = (index + 1) % spans.length;

          spans[currentIndex].classList.remove('active');
          spans[currentIndex].style.transform = 'translateY(-100%)';
          spans[currentIndex].style.opacity = '0';
          
          spans[nextIndex].classList.add('active');
          spans[nextIndex].style.transform = 'translateY(0)';
          spans[nextIndex].style.opacity = '1';

          index = nextIndex;
      }

      // Initialize
      spans.forEach((span, i) => {
          if (i !== 0) {
              span.style.transform = 'translateY(-100%)';
              span.style.opacity = '0';
          } else {
              span.classList.add('active');
              span.style.transform = 'translateY(0)';
              span.style.opacity = '1';
          }
      });

      setInterval(changeText, 2000);
  });
}


/* przewijalna galeriaproduktu w dol */

document.addEventListener('DOMContentLoaded', function() {
  const gallery = document.querySelector('.woocommerce-product-gallery');
  const summary = document.querySelector('.summary.entry-summary');
  const wrapper = document.querySelector('#custom-background-wrapper');
  const headerHeight = 80; // podaj wysokość headera
  const desktopBreakpoint = 1024; // breakpoint dla desktopu (możesz dostosować)

  if (!gallery || !summary || !wrapper) return;

  function updateGalleryPosition() {
    if (window.innerWidth < desktopBreakpoint) {
      gallery.style.transform = 'translateY(0)';
      return;
    }

    const wrapperTop = wrapper.getBoundingClientRect().top + window.scrollY - headerHeight;
    const wrapperHeight = wrapper.offsetHeight;
    const galleryHeight = gallery.offsetHeight;
    const maxScroll = wrapperHeight - galleryHeight;

    const currentScroll = window.scrollY;
    const relativeScroll = currentScroll - wrapperTop;

    if (relativeScroll >= 0 && relativeScroll <= maxScroll) {
      gallery.style.transform = `translateY(${relativeScroll}px)`;
    } else if (relativeScroll < 0) {
      gallery.style.transform = 'translateY(0)';
    } else {
      gallery.style.transform = `translateY(${maxScroll}px)`;
    }
  }

  let ticking = false;

  window.addEventListener('scroll', function() {
    if (!ticking) {
      requestAnimationFrame(() => {
        updateGalleryPosition();
        ticking = false;
      });
      ticking = true;
    }
  });

  window.addEventListener('resize', updateGalleryPosition);
  updateGalleryPosition();
});



/* galeria produktu */
// document.addEventListener('DOMContentLoaded', function() {
//   var thumbSwiper = new Swiper('.thumb-slider', {
//       spaceBetween: 10,
//       slidesPerView: 4,
//       navigation: {
//           nextEl: '.swiper-button-next',
//           prevEl: '.swiper-button-prev',
//       },
//       watchSlidesProgress: true,
//       breakpoints: {
//           480: { slidesPerView: 5 },
//           768: { slidesPerView: 6 }
//       }
//   });

//   // Kliknięcie miniatury zmienia główne zdjęcie
//   document.querySelectorAll('.thumb-slider .thumbnail').forEach(function(thumb, idx) {
//       thumb.addEventListener('click', function() {
//           // Usuń klasę active z innych miniatur
//           document.querySelectorAll('.thumb-slider .thumbnail').forEach(t => t.classList.remove('active'));
//           this.classList.add('active');
//           // Zmień widoczne zdjęcie główne
//           var allImages = document.querySelectorAll('.woocommerce-product-gallery__wrapper > .woocommerce-product-gallery__image');
//           allImages.forEach(function(imgDiv, i) {
//               imgDiv.style.display = (i === idx) ? 'block' : 'none';
//           });
//       });
//   });
//   // Pokaż tylko pierwsze zdjęcie na start
//   var allImages = document.querySelectorAll('.woocommerce-product-gallery__wrapper > .woocommerce-product-gallery__image');
//   allImages.forEach(function(imgDiv, i) {
//       imgDiv.style.display = (i === 0) ? 'block' : 'none';
//   });
// });


// jQuery(document).ready(function($) {
//   $('.custom-thumbnails-slider .thumbnail').click(function() {
//     var img_id = $(this).data('image-id');
//     var mainImageContainer = $('.woocommerce-product-gallery__wrapper');

//     $('.custom-thumbnails-slider .thumbnail').removeClass('active');
//     $(this).addClass('active');

//     $.ajax({
//       url: woocommerce_params.ajax_url,
//       type: 'POST',
//       data: {
//         action: 'get_large_image',
//         attachment_id: img_id
//       },
//       success: function(response) {
//         if (response.success && response.data.html) {
//           mainImageContainer.fadeOut(150, function() {
//             $(this).html(response.data.html).fadeIn(150);
//           });
//         }
//       }
//     });
//   });
// });Add commentMore actions



// OPINIE KARUZELA
document.addEventListener('DOMContentLoaded', () => {
  const carousel = document.querySelector('.reviews-list');
  const dotsContainer = document.querySelector('.carousel-dots');
  const reviews = document.querySelectorAll('.review-item');
  let currentIndex = 0;
  let itemsPerView;

  function calculateItemsPerView() {
    return window.innerWidth >= 768 ? 3 : 1;
  }

  function createDots() {
    dotsContainer.innerHTML = '';
    itemsPerView = calculateItemsPerView();
    const dotCount = Math.ceil(reviews.length / itemsPerView);
    
    for(let i = 0; i < dotCount; i++) {
      const dot = document.createElement('div');
      dot.className = `dot${i === 0 ? ' active' : ''}`;
      dot.addEventListener('click', () => goToSlide(i));
      dotsContainer.appendChild(dot);
    }
  }

  function goToSlide(index) {
    itemsPerView = calculateItemsPerView();
    currentIndex = index;
    const slideWidth = carousel.offsetWidth / itemsPerView;
    const offset = -currentIndex * slideWidth * itemsPerView;
    
    carousel.style.transform = `translateX(${offset}px)`;
    
    document.querySelectorAll('.dot').forEach((dot, i) => {
      dot.classList.toggle('active', i === currentIndex);
    });
  }

  function handleResize() {
    createDots();
    goToSlide(0);
  }

  // Inicjalizacja
  createDots();
  
  

  window.addEventListener('resize', handleResize);
});



