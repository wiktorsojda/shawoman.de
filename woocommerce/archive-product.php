<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template has been customized to completely bypass standard WooCommerce archive loops
 * and instead render the Gutenberg blocks built directly on the "Sklep" page.
 */

defined('ABSPATH') || exit;

get_header('shop');

$shop_page_id = wc_get_page_id('shop');
if ($shop_page_id) {
    $shop_page = get_post($shop_page_id);
    if ($shop_page && !empty($shop_page->post_content)) {
        echo '<div class="shop-archive-blocks">';
        echo apply_filters('the_content', $shop_page->post_content);
        echo '</div>';
    } else {
        echo '<div style="text-align: center; padding: 50px;">Skonfiguruj stronę Sklepu w zakładce Strony -> Sklep, dodając tam blok "Siatka Produktów Shav".</div>';
    }
}

get_footer('shop');
