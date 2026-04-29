// document.addEventListener('DOMContentLoaded', e => {
//     const hamburger = document.querySelector(".mobile-hamburger button");
//     const mobileMenu = document.querySelector(".custom-menu");
//     const subMenuItem = document.querySelectorAll(".menu-item-has-children");
//     // const headerMenu = document.querySelector("header");
//     // const headerlinks = document.querySelectorAll("header ul .menu-item a");
//     const customCart = document.querySelector(".custom-cart");
//     const customCartMobile = document.querySelector(".custom-cart-mobile");
//     const miniCartCloseBtn = document.querySelector(".mini-cart-close");
//     const miniCartSidebar = document.querySelector(".widget_shopping_cart_content");
    

//     // function toggleMenu(event) {
//     //     event.preventDefault();
//     //     event.stopPropagation();
    
//     //     const expanded = hamburger.getAttribute('aria-expanded') === 'true';
//     //     hamburger.setAttribute('aria-expanded', !expanded);
//     //     mobileMenu.classList.toggle("menu-active");
    
//     //     if (!expanded) {
//     //         hamburger.setAttribute('aria-label', 'Zamknij menu');
//     //         console.log('zamykanie');
//     //     } else {
//     //         hamburger.setAttribute('aria-label', 'Otwórz menu');
//     //         console.log('otiweranie');
//     //     }
//     // }
    
//     // hamburger.addEventListener("click", toggleMenu);


//     hamburger.addEventListener("click", () => {
//         mobileMenu.classList.toggle("menu-active");
//     });


//     // function toggleSubMenu(e) {
//     //     const subMenu = this.querySelector(".sub-menu");
//     //     subMenu.classList.toggle("active");
//     //     e.stopPropagation();
//     // }

//     // function closeSubMenus() {
//     //     const activeSubMenus = document.querySelectorAll(".sub-menu.active");
//     //     activeSubMenus.forEach(subMenu => {
//     //         subMenu.classList.remove("active");
//     //     });
//     // }

//     // function toggleMiniCart() {
//     //     miniCartSidebar.classList.toggle("mini-cart-active");
//     // }

//     // const passiveEvent = { passive: true };


//     // hamburger.addEventListener("click", () => {
//     //     mobileMenu.classList.toggle("menu-active");
//     // });
//     // hamburger.addEventListener("click", toggleMenu);
//     // hamburger.addEventListener("touchstart", toggleMenu, passiveEvent);

//     // subMenuItem.forEach(sub => {
//     //     sub.addEventListener("click", toggleSubMenu);
//     //     // sub.addEventListener("touchstart", toggleSubMenu, passiveEvent);
//     // });

//     // document.addEventListener("click", closeSubMenus);
//     // document.addEventListener("touchstart", closeSubMenus, passiveEvent);

//     // if (customCart) {
//     //     customCart.addEventListener("click", toggleMiniCart);
//     //     // customCart.addEventListener("touchstart", toggleMiniCart, passiveEvent);
//     // }

//     // if (customCartMobile) {
//     //     customCartMobile.addEventListener("click", toggleMiniCart);
//     //     // customCartMobile.addEventListener("touchstart", toggleMiniCart, passiveEvent);
//     // }

//     // if (miniCartCloseBtn) {
//     //     miniCartCloseBtn.addEventListener("click", toggleMiniCart);
//     //     // miniCartCloseBtn.addEventListener("touchstart", toggleMiniCart, passiveEvent);
//     // }
// });




// pod add to cart
document.addEventListener('DOMContentLoaded', function() {
    const titles = document.querySelectorAll('.custom-title');
    
    titles.forEach(function(title) {
        title.addEventListener('click', function() {
            const description = this.nextElementSibling;
            if (description) {
                description.style.display = description.style.display === 'none' ? 'block' : 'none';
            }
        });
    });
});

