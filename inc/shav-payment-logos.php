<?php
/**
 * Frontend display logic for checkout / buybox payment logos.
 */

// Wyświetlanie logotypów płatności
function shav_display_payment_logos()
{
    $payment_logos = get_option('shav_checkout_payment_logos', '');
    $grayscale = get_option('shav_checkout_payment_logos_grayscale', 'yes');

    // Ustawienie filtru CSS, jeśli użytkownik wybrał wyszarzenie
    $filter_css = ($grayscale === 'yes') ? 'filter: grayscale(100%); opacity: 0.7;' : '';

    if (!empty($payment_logos)) {
        $urls = explode(',', $payment_logos);
        if (empty($urls))
            return;

        echo '<style>
            .shav-payment-logos-wrapper {
                width: 100%;
                overflow: hidden;
                margin-top: 24px;
                margin-bottom: 15px;
                position: relative;
                clear: both;
            }
            .shav-payment-logos-gallery {
                display: flex;
                flex-wrap: nowrap;
                align-items: center;
                justify-content: center;
                gap: 0;
                width: 100%;
                overflow-x: auto;
                scrollbar-width: none;
                -ms-overflow-style: none;
            }
            .shav-payment-logos-gallery::-webkit-scrollbar {
                display: none;
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
                .shav-payment-logos-wrapper {
                    margin-top: 22.5px;
                }
                .shav-payment-logos-gallery img {
                    height: 18px;
                }
                .shav-payment-logo-wrap:not(:last-child)::after {
                    height: 18px;
                    margin: 0 10px;
                }
            }
        </style>';
        echo '<div class="shav-payment-logos-wrapper">';
        echo '<div class="shav-payment-logos-gallery">';

        foreach ($urls as $url) {
            echo '<div class="shav-payment-logo-wrap">';
            echo '<img src="' . esc_url(trim($url)) . '" alt="Metoda płatności" />';
            echo '</div>';
        }

        echo '</div>';
        echo '</div>';

        // Jeśli to strona koszyka, dodaj separator przed ikonami gwarancyjnymi
        if (doing_action('woocommerce_proceed_to_checkout') || doing_action('woocommerce_review_order_after_submit')) {
            echo '<div style="border-bottom: 1px solid #EAEAEA; width: 100%; margin: 0 0 15px 0;"></div>';
        }
    }
}

// 1. Pod przyciskiem add to cart na stronie produktu (buybox)
add_action('woocommerce_after_add_to_cart_form', 'shav_display_payment_logos', 20);

// 2. W koszyku pod przyciskiem "Przejdź do kasy" (priorytet 20, by weszło pod przycisk, ale nad 3 ikony z priorytetem 21)
add_action('woocommerce_proceed_to_checkout', 'shav_display_payment_logos', 20);

// 3. W checkoucie (pod przyciskiem place order)
add_action('woocommerce_review_order_after_submit', 'shav_display_payment_logos', 10);
