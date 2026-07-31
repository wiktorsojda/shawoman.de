<?php
/**
 * Konfiguracja sekcji strony sklepu (/sklep/).
 *
 * Filtr `shav_shop_sections` zwraca tablicę sekcji wyświetlanych na archiwum
 * produktów. Każda sekcja:
 *   - title:       pierwszy człon nagłówka (np. "Urządzenia.")
 *   - brand_label: drugi człon w jaśniejszym kolorze (np. "Shav woman")
 *   - category:    slug taksonomii product_cat
 *   - limit:       maksymalna liczba produktów (default 12)
 *   - orderby:     menu_order|date|title (default menu_order)
 *   - order:       ASC|DESC (default ASC)
 *
 * Domyślny config (poniżej) służy jako MVP zanim powstanie UI w adminie
 * (Faza 2b). UI nadpisze tę listę przez wp_options.
 */

defined('ABSPATH') || exit;

if (!function_exists('shav_get_shop_sections')) {
    function shav_get_shop_sections(): array
    {
        $defaults = [
            [
                'title'       => 'Urządzenia.',
                'brand_label' => 'Shav woman',
                'category'    => 'urzadzenia',
                'limit'       => 12,
                'orderby'     => 'menu_order',
                'order'       => 'ASC',
            ],
            [
                'title'       => 'Zestawy.',
                'brand_label' => 'Kobiecy niezbędnik',
                'category'    => 'zestawy',
                'limit'       => 12,
                'orderby'     => 'menu_order',
                'order'       => 'ASC',
            ],
            [
                'title'       => 'Akcesoria.',
                'brand_label' => 'Shav woman',
                'category'    => 'akcesoria',
                'limit'       => 12,
                'orderby'     => 'menu_order',
                'order'       => 'ASC',
            ],
        ];

        // Faza 2b nadpisze to przez wp_options.
        $stored = get_option('shav_shop_sections', null);
        $sections = is_array($stored) && !empty($stored) ? $stored : $defaults;

        return apply_filters('shav_shop_sections', $sections);
    }
}

/**
 * Pobiera produkty dla jednej sekcji.
 *
 * @return WP_Query
 */
if (!function_exists('shav_get_shop_section_query')) {
    function shav_get_shop_section_query(array $section): WP_Query
    {
        // WC 3+ filtruje visibility przez taksonomie `product_visibility`,
        // NIE meta `_visibility`. Domyslnie WP_Query z post_type=product juz
        // dziala, a my dodajemy jedynie filtr kategorii.
        $args = [
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'posts_per_page' => isset($section['limit']) ? (int) $section['limit'] : 12,
            'orderby'        => $section['orderby'] ?? 'menu_order',
            'order'          => $section['order']   ?? 'ASC',
            'tax_query'      => [
                'relation' => 'AND',
                [
                    'taxonomy' => 'product_cat',
                    'field'    => 'slug',
                    'terms'    => [$section['category'] ?? ''],
                ],
                [
                    'taxonomy' => 'product_visibility',
                    'field'    => 'name',
                    'terms'    => ['exclude-from-catalog', 'exclude-from-search'],
                    'operator' => 'NOT IN',
                ],
            ],
        ];

        return new WP_Query(apply_filters('shav_shop_section_query_args', $args, $section));
    }
}
