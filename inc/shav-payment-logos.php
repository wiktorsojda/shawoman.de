<?php
/**
 * Frontend display logic for checkout / buybox payment logos.
 */

// Wyświetlanie logotypów płatności w koszyku i checkout (z zakładki Checkout / buybox)
function shav_display_payment_logos() {
    $payment_logos = get_option('shav_checkout_payment_logos', '');
    $grayscale = get_option('shav_checkout_payment_logos_grayscale', 'yes');
    
    // Ustawienie filtru CSS, jeśli użytkownik wybrał wyszarzenie
    $filter_css = ($grayscale === 'yes') ? 'filter: grayscale(100%); opacity: 0.7;' : '';

    if (!empty($payment_logos)) {
        $urls = explode(',', $payment_logos);
        if (empty($urls)) return;

        echo '<style>
            .shav-payment-logos-wrapper {
                width: 100%;
                overflow: hidden;
                margin: 15px 0;
                position: relative;
            }
            .shav-payment-logos-gallery {
                display: flex;
                flex-wrap: nowrap;
                align-items: center;
                /* By default centered if fits */
                justify-content: center;
                gap: 0;
                width: max-content;
                min-width: 100%;
            }
            .shav-payment-logos-gallery.is-scrolling {
                justify-content: flex-start;
                animation: shav-payment-scroll 15s linear infinite;
            }
            .shav-payment-logos-gallery img {
                height: 20px;
                width: auto;
                object-fit: contain;
                ' . $filter_css . '
            }
            .shav-payment-logo-wrap {
                display: flex;
                align-items: center;
                flex-shrink: 0;
            }
            .shav-payment-logo-wrap:not(:last-child)::after {
                content: "";
                display: block;
                width: 1px;
                height: 20px;
                background-color: #A5A5A5;
                border-radius: 1px;
                margin: 0 12px;
            }
            @media (max-width: 768px) {
                .shav-payment-logos-gallery img {
                    height: 18px;
                }
                .shav-payment-logo-wrap:not(:last-child)::after {
                    height: 18px;
                    margin: 0 10px;
                }
            }
            @keyframes shav-payment-scroll {
                0% { transform: translateX(0); }
                100% { transform: translateX(-50%); }
            }
        </style>';
        echo '<div class="shav-payment-logos-wrapper">';
        echo '<div class="shav-payment-logos-gallery" id="shav-payment-gallery">';
        
        // Zduplikowana lista by zrobić nieskończone przewijanie (jeśli będzie potrzebne)
        $all_urls = array_merge($urls, $urls);
        foreach ($all_urls as $url) {
            echo '<div class="shav-payment-logo-wrap">';
            echo '<img src="' . esc_url(trim($url)) . '" alt="Metoda płatności" />';
            echo '</div>';
        }
        
        echo '</div>';
        echo '</div>';
        
        // Skrypt sprawdzający czy logotypy wykraczają poza kontener (na ekranach mobile)
        // Jeśli tak, odpala autoplay i usuwa wyśrodkowanie
        echo '<script>
        document.addEventListener("DOMContentLoaded", function() {
            var wrapper = document.querySelector(".shav-payment-logos-wrapper");
            var gallery = document.getElementById("shav-payment-gallery");
            if (wrapper && gallery) {
                // Polowa szerokosci scrollWidth to nasz wlasciwy set obrazkow
                if ((gallery.scrollWidth / 2) > wrapper.clientWidth) {
                    gallery.classList.add("is-scrolling");
                } else {
                    // Jeśli się mieszczą, usuwamy drugą (zduplikowaną) partię obrazków
                    var wraps = gallery.querySelectorAll(".shav-payment-logo-wrap");
                    var half = Math.floor(wraps.length / 2);
                    for(var i = half; i < wraps.length; i++) {
                        wraps[i].remove();
                    }
                    // Ostatni element pierwszej polowy nie powinien miec kreski
                    var newWraps = gallery.querySelectorAll(".shav-payment-logo-wrap");
                    if(newWraps.length > 0) {
                        newWraps[newWraps.length - 1].style.setProperty("::after", "display: none");
                        // Mały hack CSS dla ostatniego widocznego dziecka gdy usuwamy resztę
                        gallery.insertAdjacentHTML("beforeend", "<style>.shav-payment-logo-wrap:nth-child(" + newWraps.length + ")::after { display: none !important; }</style>");
                    }
                }
            }
        });
        </script>';
    }
}
// Wyświetlanie pod przyciskiem add to cart na stronie produktu (buybox)
add_action('woocommerce_after_add_to_cart_form', 'shav_display_payment_logos', 20);
// Wyświetlanie w checkoucie (pod przyciskiem place order)
add_action('woocommerce_review_order_after_submit', 'shav_display_payment_logos', 10);
