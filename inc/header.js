document.addEventListener('DOMContentLoaded', () => {
    const hamburger = document.querySelector(".header__hamburger");
    const mobileMenu = document.querySelector(".header__nav");
    
    if (hamburger && mobileMenu) {
        hamburger.addEventListener("click", () => {
            const expanded = hamburger.getAttribute('aria-expanded') === 'true';
            hamburger.setAttribute('aria-expanded', !expanded);
            mobileMenu.classList.toggle("header__nav--open");
            document.body.classList.toggle("has-mobile-menu-open");
        });

        // Zamykanie po kliknieciu linku
        mobileMenu.querySelectorAll("a").forEach(a => {
            a.addEventListener("click", () => {
                hamburger.setAttribute('aria-expanded', 'false');
                mobileMenu.classList.remove("header__nav--open");
                document.body.classList.remove("has-mobile-menu-open");
            });
        });

        // Zamykanie po kliknieciu krzyzyka (X)
        const closeBtn = mobileMenu.querySelector(".header__nav-close");
        if (closeBtn) {
            closeBtn.addEventListener("click", () => {
                hamburger.setAttribute('aria-expanded', 'false');
                mobileMenu.classList.remove("header__nav--open");
                document.body.classList.remove("has-mobile-menu-open");
            });
        }
    }
});
