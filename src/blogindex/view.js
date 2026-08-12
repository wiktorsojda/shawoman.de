import Glide from '@glidejs/glide';
import '@glidejs/glide/dist/css/glide.core.min.css';

document.addEventListener('DOMContentLoaded', () => {
    const sliderElement = document.querySelector('.blog-glide');
    if (sliderElement) {
        new Glide(sliderElement, {
            type: 'carousel',
            startAt: 0,
            perView: 1,
            gap: 0,
            autoplay: 4000,
            hoverpause: true,
            animationDuration: 800
        }).mount();
    }

    // Rozwiązanie problemu ze sticky sidebar: 
    // WordPress często dodaje 'overflow: hidden' do kontenerów takich jak #page lub .site-content, co całkowicie psuje 'position: sticky'.
    // Poniższy kod przechodzi przez wszystkich rodziców sidebara i zmienia 'overflow: hidden' na 'overflow: clip', co naprawia sticky.
    const sidebar = document.querySelector('.blog-sidebar');
    if (sidebar) {
        let parent = sidebar.parentElement;
        while (parent && parent !== document.body && parent !== document.documentElement) {
            const style = window.getComputedStyle(parent);
            if (style.overflow === 'hidden' || style.overflowX === 'hidden' || style.overflowY === 'hidden') {
                parent.style.setProperty('overflow', 'clip', 'important');
                parent.style.setProperty('overflow-x', 'clip', 'important');
                if (style.overflowY === 'hidden') {
                    parent.style.setProperty('overflow-y', 'clip', 'important');
                }
            }
            parent = parent.parentElement;
        }
    }
});
