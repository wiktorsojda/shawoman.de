<?php
/**
 * Dynamiczna darmowa dostawa zdefiniowana w ustawieniach płaskiej stawki (flat_rate).
 */

// 1. Dodanie pola do panelu admina (ustawienia danej metody flat_rate)
add_filter( 'woocommerce_shipping_instance_form_fields_flat_rate', 'shav_add_free_shipping_threshold_field' );
function shav_add_free_shipping_threshold_field( $settings ) {
    $settings['free_shipping_threshold'] = array(
        'title'       => __( 'Darmowa od kwoty (€)', 'woocommerce' ),
        'type'        => 'text',
        'description' => __( 'Wpisz kwotę, od której ta metoda ma automatycznie kosztować 0€. Zostaw puste, by wyłączyć.', 'woocommerce' ),
        'default'     => '',
        'desc_tip'    => true,
    );
    return $settings;
}

// 2. Logika zerująca cenę jeśli wartość koszyka przekracza próg
add_filter( 'woocommerce_package_rates', 'shav_dynamic_free_shipping_from_settings', 10, 2 );
function shav_dynamic_free_shipping_from_settings( $rates, $package ) {
    // Wartość koszyka (produkty po rabatach + podatek, bez wysyłki)
    $cart_total = WC()->cart->get_cart_contents_total() + WC()->cart->get_cart_contents_tax();

    foreach ( $rates as $rate_key => $rate ) {
        // Dotyczy tylko standardowych Płaskich stawek (flat_rate)
        if ( 'flat_rate' === $rate->method_id ) {
            
            // Pobieramy opcje dla TEJ konkretnej metody (np. DHL vs Paczkomat)
            $option_key = 'woocommerce_flat_rate_' . $rate->instance_id . '_settings';
            $instance_settings = get_option( $option_key );
            
            if ( ! empty( $instance_settings['free_shipping_threshold'] ) ) {
                $threshold = (float) $instance_settings['free_shipping_threshold'];
                
                // Jeśli wpisano próg i koszyk go przekroczył
                if ( $threshold > 0 && $cart_total >= $threshold ) {
                    $rates[$rate_key]->cost = 0;
                    
                    // Zerujemy podatek
                    $taxes = array();
                    foreach ( $rates[$rate_key]->taxes as $key => $tax ) {
                        $taxes[$key] = 0;
                    }
                    $rates[$rate_key]->taxes = $taxes;
                }
            }
        }
}
    return $rates;
}

// 3. Wymuszenie wyświetlania słowa "Kostenlos!" (zamiast pusto), gdy cena metody wynosi 0
add_filter( 'woocommerce_cart_shipping_method_full_label', 'shav_force_kostenlos_label', 10, 2 );
function shav_force_kostenlos_label( $label, $method ) {
    // Jeśli koszt metody to 0 (np. wyzerowany przez nasz kod wyżej)
    if ( $method->cost == 0 ) {
        // Doklejamy span, żeby nasz wcześniejszy, "lekki CSS" bez problemu go pokazał obok loga
        $label .= ' <span class="woocommerce-Price-amount amount">Kostenlos!</span>';
    }
    return $label;
}
