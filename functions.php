<?php

// Centralny edytor pol produktow (admin > Shav: Pola)
require_once get_template_directory() . '/inc/shav-fields-admin.php';

// Modul strony sklepu (/sklep/) — sekcje, ramki, banner, hooki listy produktow
require_once get_template_directory() . '/inc/shop/_loader.php';

// Zezwolenie na upload SVG (np. logo/sygnatury w blokach onasrozwijamy/onaswazne)
require_once get_template_directory() . '/inc/svg-support.php';

// Skrypty migracyjne ze snippetów
require_once get_template_directory() . '/inc/wc-cpt.php';
require_once get_template_directory() . '/inc/cart-cross-sell.php';
require_once get_template_directory() . '/inc/wc-automatyzacja.php';
require_once get_template_directory() . '/inc/wc-faq.php';
require_once get_template_directory() . '/inc/theme-wyglad.php';
require_once get_template_directory() . '/inc/top-bar.php';
require_once get_template_directory() . '/inc/wc-badges.php';
require_once get_template_directory() . '/inc/ceny-w-zestawie.php';
require_once get_template_directory() . '/inc/ceny-front.php';

// adobe font babe neue pro
function add_resource_hints_and_fonts()
{
    // DNS Prefetch for non-preconnected resources~
    echo '<link rel="dns-prefetch" href="//cdnjs.cloudflare.com">';

    // Preconnect for prioritized resources
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">';
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';
    echo '<link rel="preconnect" href="https://use.typekit.net" crossorigin>';

    // Adobe Fonts (Dolce — kit dsg8nwe)
    echo '<link rel="stylesheet" href="https://use.typekit.net/dsg8nwe.css">';

    // Google Fonts (Be Vietnam Pro — wszystkie wagi 100-900 + italic)
    echo '<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap">';
}
add_action('wp_head', 'add_resource_hints_and_fonts');






// omnibus product page
// Add a custom field to enter the lowest price in the product edit screen
function add_lowest_price_field()
{
    echo '<div class="options_group">';

    // Input field for the lowest price
    woocommerce_wp_text_input(
        array(
            'id' => 'lowest_price_30_days',
            'label' => __('Niedrigster Preis der letzten 30 Tage:', 'woocommerce'),
            'desc_tip' => 'true',
            'description' => __('Geben Sie den niedrigsten Preis der letzten 30 Tage vor der Rabattierung ein.', 'woocommerce'),
            'wrapper_class' => 'show_if_simple show_if_variable show_if_woosg show_if_external show_if_grouped'
        )
    );

    // Checkbox to completely hide the Omnibus price
    woocommerce_wp_checkbox(
        array(
            'id' => 'hide_omnibus_price',
            'label' => __('Nie pokazuj Omnibus', 'woocommerce'),
            'description' => __('Zaznacz, aby całkowicie ukryć cenę z ostatnich 30 dni (Omnibus) dla tego produktu.', 'woocommerce'),
            'wrapper_class' => 'show_if_simple show_if_variable show_if_woosg show_if_external show_if_grouped'
        )
    );

    echo '</div>';
}
add_action('woocommerce_product_options_pricing', 'add_lowest_price_field');

// Save the custom field data
function save_lowest_price_field($post_id)
{
    $product = wc_get_product($post_id);

    // Save the lowest price from the last 30 days
    $lowest_price = isset($_POST['lowest_price_30_days']) ? sanitize_text_field($_POST['lowest_price_30_days']) : '';
    $product->update_meta_data('lowest_price_30_days', $lowest_price);

    // Save the hide omnibus checkbox
    $hide_omnibus = isset($_POST['hide_omnibus_price']) ? 'yes' : 'no';
    $product->update_meta_data('hide_omnibus_price', $hide_omnibus);

    $product->save();
}
add_action('woocommerce_process_product_meta', 'save_lowest_price_field');


// Display the lowest price (Omnibus) below the product price
function display_lowest_price_30_days()
{
    global $product;
    if (!$product)
        return;
    if (function_exists('shav_is_hidden') && shav_is_hidden($product->get_id(), 'lowest_price'))
        return;

    // Check if the product has the hide Omnibus checkbox checked
    if ($product->get_meta('hide_omnibus_price') === 'yes') {
        return;
    }

    $lowest_price = shav_get_field($product->get_id(), 'lowest_price_30_days', 'lowest_price');
    $is_manual = !empty($lowest_price);

    // Tryb automatyczny: jeśli puste, pobierz cenę regularną (tylko na promocji)
    if (!$is_manual) {
        if ($product->is_on_sale()) {
            if ($product->is_type('variable')) {
                $lowest_price = $product->get_variation_regular_price('min', true);
            } else {
                $lowest_price = $product->get_regular_price();
            }
        }

        // Jeśli produkt nie jest na promocji i brak ręcznej ceny, nie pokazuj dyrektywy
        if (empty($lowest_price)) {
            return;
        }
    }

    echo '<p class="lowest-price" style="font-size: 14px; color: #7A7A7A; margin-top: 10px;">';

    // Fraza objęta systemem tłumaczeń (np. do pliku .po na język DE)
    echo esc_html__('Niedrigster Preis der letzten 30 Tage:', 'woocommerce') . ' ';

    // Wyświetlamy cenę natywnym formatowaniem (automatycznie dobra waluta np. €)
    if (!$is_manual || is_numeric(str_replace(array(',', '.'), '', $lowest_price))) {
        echo wc_price((float) str_replace(',', '.', $lowest_price));
    } else {
        // Jeśli admin celowo dopisał w polu ręcznym np. "30 euro"
        echo esc_html($lowest_price);
    }

    echo '</p>';
}
// 


// cart etui za darmo
// add_action('woocommerce_cart_totals_before_shipping', 'add_free_case_to_cart_if_product_49_in_cart');

function add_free_case_to_cart_if_product_49_in_cart()
{
    // Sprawdź, czy produkt o ID 49 znajduje się w koszyku
    if (is_product_in_cart(49) || is_product_in_cart(185780)) {
        ?>
        <tr class="cart-free-case">
            <th>+ETUI</th>
            <td data-title="ETUI"><span class="woocommerce-Price-amount amount"><bdi><strong>GRATIS!</strong></bdi></span></td>
        </tr>
        <?php
    }
}

// Funkcja sprawdzająca, czy dany produkt znajduje się w koszyku
function is_product_in_cart($product_id)
{
    foreach (WC()->cart->get_cart() as $cart_item) {
        if ($cart_item['product_id'] == $product_id) {
            return true;
        }
    }
    return false;
}



// metka strona produktowa


// cart banner
// Set the specific product ID for which you want to add the banner
$product_id_with_banner = 68;

function add_cart_banner_fields()
{
    global $post;
    global $product_id_with_banner;

    // Only display the fields for the specified product
    if ($post->ID != $product_id_with_banner) {
        return;
    }

    // Add desktop banner image field
    woocommerce_wp_text_input(
        array(
            'id' => 'cart_banner_desktop_image',
            'label' => __('Desktop Cart Banner Image', 'woocommerce'),
            'desc_tip' => 'true',
            'description' => __('Enter the URL of the desktop banner image to display on the cart page.', 'woocommerce'),
            'type' => 'text'
        )
    );

    // Add mobile banner image field
    woocommerce_wp_text_input(
        array(
            'id' => 'cart_banner_mobile_image',
            'label' => __('Mobile Cart Banner Image', 'woocommerce'),
            'desc_tip' => 'true',
            'description' => __('Enter the URL of the mobile banner image to display on the cart page.', 'woocommerce'),
            'type' => 'text'
        )
    );
}

add_action('woocommerce_product_options_general_product_data', 'add_cart_banner_fields');

function save_cart_banner_fields($post_id)
{
    global $product_id_with_banner;

    // Only save for the specified product
    if ($post_id != $product_id_with_banner) {
        return;
    }

    $product = wc_get_product($post_id);

    // Save the desktop banner image URL
    if (isset($_POST['cart_banner_desktop_image'])) {
        $desktop_banner_image_url = esc_url_raw($_POST['cart_banner_desktop_image']);
        $product->update_meta_data('cart_banner_desktop_image', $desktop_banner_image_url);
    }

    // Save the mobile banner image URL
    if (isset($_POST['cart_banner_mobile_image'])) {
        $mobile_banner_image_url = esc_url_raw($_POST['cart_banner_mobile_image']);
        $product->update_meta_data('cart_banner_mobile_image', $mobile_banner_image_url);
    }

    $product->save();
}

add_action('woocommerce_process_product_meta', 'save_cart_banner_fields');


function display_cart_banner_image()
{
    global $product_id_with_banner;

    // Sprawdź, czy produkt o ID 49 znajduje się w koszyku
    // if (is_product_in_cart(49) || is_product_in_cart(185780) || is_product_in_cart(209201)) {
    // if (is_product_in_cart(49)) {
    $product = wc_get_product($product_id_with_banner);
    if (!$product)
        return;
    $desktop_banner_image_url = shav_get_field($product_id_with_banner, 'cart_banner_desktop_image', 'cart_banner');
    $mobile_banner_image_url = shav_get_field($product_id_with_banner, 'cart_banner_mobile_image', 'cart_banner');

    echo '<div class="cart-banner-image">';

    // Display the desktop banner if it's set
    if ($desktop_banner_image_url) {
        echo '<img class="desktop-banner" src="' . esc_url($desktop_banner_image_url) . '" alt="' . esc_attr__('Cart Desktop Banner Image', 'woocommerce') . '" style="width: 100%; height: auto; display: none;">';
    }

    // Display the mobile banner if it's set
    if ($mobile_banner_image_url) {
        echo '<img class="mobile-banner" src="' . esc_url($mobile_banner_image_url) . '" alt="' . esc_attr__('Cart Mobile Banner Image', 'woocommerce') . '" style="width: 96%; margin: auto; height: auto; display: none;">';
    }

    echo '</div>';
    // }
}

add_action('woocommerce_before_cart', 'display_cart_banner_image', 1);

add_action('wp_footer', 'switch_banner_based_on_screen_size');
function switch_banner_based_on_screen_size()
{
    ?>
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function () {
            function updateBannerVisibility() {
                var desktopBanner = document.querySelector('.desktop-banner');
                var mobileBanner = document.querySelector('.mobile-banner');

                if (window.innerWidth < 768) {
                    // Show mobile banner on smaller screens
                    if (mobileBanner) {
                        mobileBanner.style.display = 'block';
                    }
                    if (desktopBanner) {
                        desktopBanner.style.display = 'none';
                    }
                } else {
                    // Show desktop banner on larger screens
                    if (desktopBanner) {
                        desktopBanner.style.display = 'block';
                    }
                    if (mobileBanner) {
                        mobileBanner.style.display = 'none';
                    }
                }
            }

            // Call the function on load and when the window is resized
            updateBannerVisibility();
            window.addEventListener('resize', updateBannerVisibility);
        });
    </script>
    <?php
}





function add_favicon()
{
    $favicon_url = esc_url(home_url('/wp-content/uploads/cropped-ikona_witryny_shav.png'));
    echo '<link rel="icon" type="image/png" href="' . $favicon_url . '">';
}
add_action('wp_head', 'add_favicon');


// bledy kiedy uzytkownik jest niezalogowany
// function disable_rest_endpoints_for_guests($endpoints) {
//     if (!is_user_logged_in()) {
//         // Usuń endpoint, który powoduje problem
//         unset($endpoints['/wp/v2/users/me']);
//     }
//     return $endpoints;
// }
// add_filter('rest_endpoints', 'disable_rest_endpoints_for_guests');


function custom_inline_quantity_update_js()
{
    $script = "
  jQuery(document).ready(function($) {
      $('body').on('input', 'input.qty', function() {
          var form = $(this).closest('form');
          form.find('[name=\"update_cart\"]').prop('disabled', false); // Enable the update button
          
          // Delay the form submission to ensure the quantity is registered
          setTimeout(function() {
              form.trigger('submit'); // Submit the form to trigger WooCommerce update
          }, 800); // 300ms delay
      });
  });
  ";
    wp_add_inline_script('jquery', $script);
}
add_action('wp_enqueue_scripts', 'custom_inline_quantity_update_js');

// function custom_inline_quantity_update_js() {
//   $script = "
//   jQuery(document).ready(function($) {
//       $('body').on('change', 'input.qty', function() {
//           var form = $(this).closest('form');
//           form.find('[name=\"update_cart\"]').prop('disabled', false); // Enable the update button

//           // Delay the form submission to ensure the quantity is registered
//           setTimeout(function() {
//               form.trigger('submit'); // Submit the form to trigger WooCommerce update
//           }, 300); // 300ms delay
//       });
//   });
//   ";
//   wp_add_inline_script( 'jquery', $script );
// }
// add_action( 'wp_enqueue_scripts', 'custom_inline_quantity_update_js' );
// add_filter( 'woocommerce_cart_hash', '__return_true' );
// delete h2 - opis
add_filter('woocommerce_product_description_heading', '__return_null');




// 
// popup in cart
// 1. HTML Structure
// -----------------------------------------------------------------------------
// CART POPUP — Settings page (Ustawienia → Cart Popup)
// Wszystkie pola popupa: nagłówek, opis, lista produktów, button zestawu, triggery
// -----------------------------------------------------------------------------
function shav_cartpopup_register_settings_page()
{
    add_options_page('Cart Popup', 'Cart Popup', 'manage_options', 'shav-cartpopup', 'shav_cartpopup_render_settings_page');
}
add_action('admin_menu', 'shav_cartpopup_register_settings_page');

function shav_cartpopup_register_settings()
{
    register_setting('shav_cartpopup_group', 'shav_cartpopup_badge');
    register_setting('shav_cartpopup_group', 'shav_cartpopup_heading');
    register_setting('shav_cartpopup_group', 'shav_cartpopup_description');
    register_setting('shav_cartpopup_group', 'shav_cartpopup_triggers');     // CSV product IDs
    register_setting('shav_cartpopup_group', 'shav_cartpopup_bundle_id');
    register_setting('shav_cartpopup_group', 'shav_cartpopup_bundle_label');
    // 4 sloty: produkt + opcjonalny obraz
    for ($i = 1; $i <= 4; $i++) {
        register_setting('shav_cartpopup_group', "shav_cartpopup_product_{$i}_id");
        register_setting('shav_cartpopup_group', "shav_cartpopup_product_{$i}_image_id");
    }
}
add_action('admin_init', 'shav_cartpopup_register_settings');

function shav_cartpopup_render_settings_page()
{
    ?>
    <div class="wrap">
        <h1>Cart Popup — ustawienia</h1>
        <p>Popup wyświetla się na stronie koszyka, gdy w koszyku jest jeden z produktów wymienionych w "Trigger IDs".
            Pokazuje 4 polecane produkty i przycisk "Dodaj zestaw".</p>
        <form method="post" action="options.php">
            <?php settings_fields('shav_cartpopup_group'); ?>
            <h2>Nagłówek</h2>
            <table class="form-table">
                <tr>
                    <th>Badge (pigułka)</th>
                    <td><input type="text" name="shav_cartpopup_badge"
                            value="<?php echo esc_attr(get_option('shav_cartpopup_badge', 'Promocja')); ?>"
                            class="regular-text"></td>
                </tr>
                <tr>
                    <th>Tytuł</th>
                    <td><input type="text" name="shav_cartpopup_heading"
                            value="<?php echo esc_attr(get_option('shav_cartpopup_heading', 'Zestaw w obniżonej cenie!')); ?>"
                            class="regular-text"></td>
                </tr>
                <tr>
                    <th>Opis</th>
                    <td><input type="text" name="shav_cartpopup_description"
                            value="<?php echo esc_attr(get_option('shav_cartpopup_description', 'Przy zakupie 3 produktów oszczędzasz 27 zł!')); ?>"
                            class="regular-text"></td>
                </tr>
            </table>

            <h2>Triggery (kiedy popup się pokazuje)</h2>
            <table class="form-table">
                <tr>
                    <th>ID produktów (po przecinku)</th>
                    <td>
                        <input type="text" name="shav_cartpopup_triggers"
                            value="<?php echo esc_attr(get_option('shav_cartpopup_triggers', '49,209201')); ?>"
                            class="regular-text">
                        <p class="description">Popup pojawi się gdy któryś z tych produktów (ID) jest w koszyku.</p>
                    </td>
                </tr>
            </table>

            <h2>Lista 4 produktów</h2>
            <table class="form-table">
                <?php for ($i = 1; $i <= 4; $i++): ?>
                    <tr>
                        <th>Produkt <?php echo $i; ?> — ID</th>
                        <td>
                            <input type="text" name="shav_cartpopup_product_<?php echo $i; ?>_id"
                                value="<?php echo esc_attr(get_option("shav_cartpopup_product_{$i}_id", '')); ?>"
                                class="regular-text">
                            <p class="description">ID produktu lub wariacji. Tytuł i cena pobierane z WooCommerce.</p>
                        </td>
                    </tr>
                    <tr>
                        <th>Produkt <?php echo $i; ?> — ID obrazka (opcjonalnie)</th>
                        <td>
                            <input type="text" name="shav_cartpopup_product_<?php echo $i; ?>_image_id"
                                value="<?php echo esc_attr(get_option("shav_cartpopup_product_{$i}_image_id", '')); ?>"
                                class="regular-text">
                            <p class="description">ID załącznika <strong>lub pełny URL</strong> obrazka. Puste = użyje
                                miniaturki produktu.</p>
                        </td>
                    </tr>
                <?php endfor; ?>
            </table>

            <h2>Button "Dodaj zestaw"</h2>
            <table class="form-table">
                <tr>
                    <th>ID produktu zestawu</th>
                    <td><input type="text" name="shav_cartpopup_bundle_id"
                            value="<?php echo esc_attr(get_option('shav_cartpopup_bundle_id', '64967')); ?>"
                            class="regular-text"></td>
                </tr>
                <tr>
                    <th>Etykieta przycisku</th>
                    <td><input type="text" name="shav_cartpopup_bundle_label"
                            value="<?php echo esc_attr(get_option('shav_cartpopup_bundle_label', 'Dodaj zestaw za 110 zł')); ?>"
                            class="regular-text"></td>
                </tr>
            </table>

            <?php submit_button('Zapisz ustawienia'); ?>
        </form>
    </div>
    <?php
}

function add_cart_popup_html()
{
    // Pobierz opcje z Settings page (Cart Popup)
    $badge = get_option('shav_cartpopup_badge', 'Promocja');
    $heading = get_option('shav_cartpopup_heading', 'Zestaw w obniżonej cenie!');
    $description = get_option('shav_cartpopup_description', 'Przy zakupie 3 produktów oszczędzasz 27 zł!');
    $bundle_id = (int) get_option('shav_cartpopup_bundle_id', 64967);
    $bundle_label = get_option('shav_cartpopup_bundle_label', 'Dodaj zestaw za 110 zł');

    // Lista produktow z opcji (4 sloty)
    // Wartosc image moze byc: attachment ID (liczba) LUB URL — obsluguje oba
    $products = [];
    for ($i = 1; $i <= 4; $i++) {
        $pid = (int) get_option("shav_cartpopup_product_{$i}_id", 0);
        if ($pid > 0) {
            $products[$pid] = trim((string) get_option("shav_cartpopup_product_{$i}_image_id", ''));
        }
    }
    ?>
    <div class="cart-popup-father">
        <div id="cart-popup" class="cart-popup shav-cartpopup">
            <div class="cart-popup-content shav-cartpopup__content">
                <button type="button" class="close-cart-popup shav-cartpopup__close" aria-label="Zamknij">×</button>
                <div class="cart-popup-header-container shav-cartpopup__header">
                    <?php if (!empty($badge)): ?>
                        <span class="shav-cartpopup__badge"><?php echo esc_html($badge); ?></span>
                    <?php endif; ?>
                    <?php if (!empty($heading)): ?>
                        <h3 class="shav-cartpopup__title"><?php echo esc_html($heading); ?></h3>
                    <?php endif; ?>
                    <?php if (!empty($description)): ?>
                        <p class="shav-cartpopup__desc"><?php echo esc_html($description); ?></p>
                    <?php endif; ?>
                </div>
                <div class="cart-popup-items shav-cartpopup__grid">
                    <?php
                    foreach ($products as $product_id => $custom_image_raw) {
                        $product = wc_get_product($product_id);
                        if (!$product)
                            continue;

                        $is_variation = $product->is_type('variation');
                        if ($is_variation) {
                            $parent_id = $product->get_parent_id();
                            $product_url = get_permalink($parent_id);
                        } else {
                            $product_url = get_permalink($product_id);
                        }

                        // Rozpoznaj: URL czy attachment ID
                        $img_url = '';
                        if (!empty($custom_image_raw)) {
                            if (filter_var($custom_image_raw, FILTER_VALIDATE_URL)) {
                                $img_url = $custom_image_raw;
                            } elseif (is_numeric($custom_image_raw)) {
                                $aid = (int) $custom_image_raw;
                                $img_url = wp_get_attachment_image_url($aid, 'woocommerce_thumbnail');
                                if (empty($img_url)) {
                                    // Fallback: rozmiar 'thumbnail' lub original
                                    $img_url = wp_get_attachment_image_url($aid, 'thumbnail');
                                }
                                if (empty($img_url)) {
                                    $img_url = wp_get_attachment_url($aid);
                                }
                            }
                        }

                        // Ostateczny fallback — domyslna miniaturka produktu
                        if (empty($img_url)) {
                            $thumb_id = $is_variation ? $product->get_image_id() : get_post_thumbnail_id($product_id);
                            $img_url = $thumb_id ? wp_get_attachment_image_url($thumb_id, 'woocommerce_thumbnail') : '';
                        }

                        $product_title = $product->get_name();
                        ?>
                        <div class="cart-popup-product shav-cartpopup__card">
                            <a class="shav-cartpopup__card-link" href="<?php echo esc_url($product_url); ?>">
                                <div class="cart-popup-product-img shav-cartpopup__card-thumb">
                                    <?php if ($img_url): ?>
                                        <img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($product_title); ?>">
                                    <?php endif; ?>
                                </div>
                                <div class="shav-cartpopup__card-body">
                                    <div class="cart-popup-rest-title shav-cartpopup__card-title">
                                        <?php echo esc_html($product_title); ?>
                                    </div>
                                    <span
                                        class="price shav-cartpopup__card-price"><?php echo $product->get_price_html(); ?></span>
                                </div>
                            </a>
                            <form class="cart shav-cartpopup__card-form" method="post" enctype="multipart/form-data">
                                <?php if ($is_variation): ?>
                                    <input type="hidden" name="add-to-cart" value="<?php echo esc_attr($parent_id); ?>">
                                    <input type="hidden" name="variation_id" value="<?php echo esc_attr($product_id); ?>">
                                    <?php
                                    $attributes = $product->get_variation_attributes();
                                    foreach ($attributes as $attr_name => $attr_value) {
                                        echo '<input type="hidden" name="' . esc_attr($attr_name) . '" value="' . esc_attr($attr_value) . '">';
                                    }
                                    ?>
                                <?php else: ?>
                                    <input type="hidden" name="add-to-cart" value="<?php echo esc_attr($product_id); ?>">
                                <?php endif; ?>
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="shav-cartpopup__card-btn">Dodaj do koszyka</button>
                            </form>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <?php if ($bundle_id > 0 && !empty($bundle_label)): ?>
                    <div class="cart-popup-zestaw shav-cartpopup__bundle">
                        <form action="" method="post" class="cart-popup-form">
                            <input type="hidden" name="add-to-cart" value="<?php echo esc_attr($bundle_id); ?>">
                            <button type="submit"
                                class="shav-cartpopup__bundle-btn"><?php echo esc_html($bundle_label); ?></button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php
}
add_action('wp_footer', 'add_cart_popup_html');



// 2. JavaScript Logic (conditionally add this on the cart page only)
function add_cart_popup_js()
{
    if (is_cart()) { // Check if it's the cart page
        ?>
        <script type="text/javascript">
            document.addEventListener('DOMContentLoaded', function () {
                const cartPopup = document.getElementById('cart-popup');
                const closePopup = document.querySelector('.close-cart-popup');

                // Function to show the cart popup
                function showCartPopup() {
                    cartPopup.style.display = 'block';
                    document.body.classList.add('shav-cartpopup-open');

                    // Set a cookie to prevent the popup from showing again
                    document.cookie = "cartPopupShown=true; path=/";
                }

                // Function to close the cart popup
                function closeCartPopup() {
                    cartPopup.style.display = 'none';
                    document.body.classList.remove('shav-cartpopup-open');
                }

                // Function to fetch cart items via AJAX and load them into the popup
                function fetchCartItems() {
                    jQuery.ajax({
                        url: wc_add_to_cart_params.ajax_url, // WooCommerce AJAX URL
                        type: 'POST',
                        data: {
                            action: 'load_cart_items',
                        },
                        success: function (response) {
                            document.querySelector('.cart-popup-items').innerHTML = response;
                        }
                    });
                }

                // Initially hide the popup
                cartPopup.style.display = 'none';

                // Function to check if a specific product is in the cart
                function isProductInCart(productId, callback) {
                    jQuery.ajax({
                        url: wc_add_to_cart_params.ajax_url, // WooCommerce AJAX URL
                        type: 'POST',
                        data: {
                            action: 'check_product_in_cart',
                            product_id: productId
                        },
                        success: function (response) {
                            callback(response === 'true');
                        }
                    });
                }

                // IDs produktów do sprawdzenia — z Settings page (Cart Popup)
                const productIds = <?php
                $triggers_csv = get_option('shav_cartpopup_triggers', '49,209201');
                $ids = array_filter(array_map('intval', explode(',', $triggers_csv)));
                echo wp_json_encode($ids ?: [49, 209201]);
                ?>;

                // Sprawdź czy popup był już pokazany
                const popupShown = document.cookie.includes('cartPopupShown=');

                if (!popupShown) {
                    // Utwórz tablicę obietnic
                    const checks = productIds.map(id => {
                        return new Promise(resolve => {
                            isProductInCart(id, isInCart => resolve(isInCart));
                        });
                    });

                    // Sprawdź wszystkie równolegle
                    Promise.all(checks).then(results => {
                        if (results.some(inCart => inCart)) {
                            setTimeout(showCartPopup, 1000);
                        }
                    });
                }
                // Close popup when pressing the Esc key
                document.addEventListener('keydown', function (e) {
                    if (e.key === "Escape") {
                        closeCartPopup();
                    }
                });

                // Close popup when clicking the close button
                closePopup.addEventListener('click', closeCartPopup);
            });
        </script>
        <?php
    }
}
add_action('wp_footer', 'add_cart_popup_js');

// PHP function to handle AJAX request for checking product in cart
function check_product_in_cart()
{
    if (isset($_POST['product_id'])) {
        $product_id = intval($_POST['product_id']);
        $in_cart = false;

        foreach (WC()->cart->get_cart() as $cart_item) {
            if ($cart_item['product_id'] == $product_id) {
                $in_cart = true;
                break;
            }
        }

        echo $in_cart ? 'true' : 'false';
    }
    wp_die();
}
add_action('wp_ajax_check_product_in_cart', 'check_product_in_cart');
add_action('wp_ajax_nopriv_check_product_in_cart', 'check_product_in_cart');






// not sure if it's still needed
function pageBanner($args = NULL)
{

    if (!isset($args['title'])) {
        $args['title'] = get_the_title();
    }

    if (!isset($args['subtitle'])) {
        $args['subtitle'] = get_field('page_banner_subtitle');
    }

    // if (!isset($args['photo'])) {
    //   if (get_field('page_banner_background_image') AND !is_archive() AND !is_home() ) {
    //     $args['photo'] = get_field('page_banner_background_image')['sizes']['pageBanner'];
    //   } else {
    //     $args['photo'] = get_theme_file_uri('/images/ocean.jpg');
    //   }
    // }

    ?>
    <div class="page-banner">
        <div class="page-banner__bg-image" style="background-image: url(<?php echo $args['photo']; ?>);"></div>
        <div class="page-banner__content container container--narrow">
            <h1 class="page-banner__title"><?php echo $args['title'] ?></h1>
            <div class="page-banner__intro">
                <p><?php echo $args['subtitle']; ?></p>
            </div>
        </div>
    </div>
<?php }

function university_files()
{
    wp_enqueue_script('main-university-js', get_theme_file_uri('/build/index.js'), array('jquery'), '1.0', true);
    wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    wp_enqueue_style('university_main_styles', get_theme_file_uri('/build/style-index.css'), array(), filemtime(get_theme_file_path('/build/style-index.css')));
    wp_enqueue_style('university_extra_styles', get_theme_file_uri('/build/index.css'), array(), filemtime(get_theme_file_path('/build/index.css')));
    // Enqueue Font Awesome
    wp_enqueue_style('font-awesome', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');


    wp_localize_script('main-university-js', 'universityData', array(
        'root_url' => get_site_url(),
        'nonce' => wp_create_nonce('wp_rest')
    ));
    // Async attribute for JS
    add_filter('script_loader_tag', 'add_async_attribute', 10, 2);
}

add_action('wp_enqueue_scripts', 'university_files');

function add_async_attribute($tag, $handle)
{
    if ('main-university-js' !== $handle) {
        return $tag;
    }
    return str_replace(' src', ' async="async" src', $tag);
}





// I don't think it's neccessery
// Redirect subscriber accounts out of admin and onto homepage
add_action('admin_init', 'redirectSubsToFrontend');

function redirectSubsToFrontend()
{
    $ourCurrentUser = wp_get_current_user();

    if (count($ourCurrentUser->roles) == 1 AND $ourCurrentUser->roles[0] == 'subscriber') {
        wp_redirect(site_url('/'));
        exit;
    }
}

add_action('wp_loaded', 'noSubsAdminBar');

function noSubsAdminBar()
{
    $ourCurrentUser = wp_get_current_user();

    if (count($ourCurrentUser->roles) == 1 AND $ourCurrentUser->roles[0] == 'subscriber') {
        show_admin_bar(false);
    }
}

// this also no necesssery
// Customize Login Screen
add_filter('login_headerurl', 'ourHeaderUrl');

function ourHeaderUrl()
{
    return esc_url(site_url('/'));
}

add_action('login_enqueue_scripts', 'ourLoginCSS');

function ourLoginCSS()
{
    wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    wp_enqueue_style('university_main_styles', get_theme_file_uri('/build/style-index.css'), array(), filemtime(get_theme_file_path('/build/style-index.css')));
    wp_enqueue_style('university_extra_styles', get_theme_file_uri('/build/index.css'), array(), filemtime(get_theme_file_path('/build/index.css')));


}

add_filter('login_headertitle', 'ourLoginTitle');

function ourLoginTitle()
{
    return get_bloginfo('name');
}




// Wlacz Gutenberg (block editor) dla produktow WooCommerce.
// (Wylaczone — rozbija UI "Dane produktu" WC: cena, magazyn, warianty.)
// Zamiast tego ponizej: metabox z pickerem Wzorcow (wp_block), edytowalnych
// w pelnym Gutenbergu na osobnym ekranie, renderowanych pod opisem produktu.
// add_filter('use_block_editor_for_post_type', function ($use, $post_type) {
//     if ($post_type === 'product') { return true; }
//     return $use;
// }, 10, 2);

// =============================================================================
// SHAV: Picker wzorcow (wp_block) dla produktow
// =============================================================================
// Idea: zachowujemy klasyczny edytor produktu (WC UI: cena, magazyn, warianty),
// ale dodajemy metabox w ktorym user wybiera ktore "Wzorce" (post_type wp_block,
// admin: Narzedzia/Wzorce → wp-admin/edit.php?post_type=wp_block) maja sie
// wyswietlic pod opisem produktu. Wzorzec jest edytowalny w pelnym Gutenbergu
// (wszystkie nasze 85 blokow dostepne) — czyli user dodaje raz w "Wzorcach"
// kompozycje blokow, a tu tylko zaznacza ktore z nich pokazac na danym produkcie.
// Render: woocommerce_after_single_product_summary @ priorytet 6.
// =============================================================================

add_action('add_meta_boxes', function () {
    add_meta_box(
        'shav_product_block_patterns',
        'Bloki Shav (wzorce)',
        'shav_render_product_blocks_metabox',
        'product',
        'normal',
        'high'
    );
});

function shav_render_product_blocks_metabox($post)
{
    wp_nonce_field('shav_product_blocks_save', 'shav_product_blocks_nonce');
    $selected = (array) get_post_meta($post->ID, '_shav_product_block_ids', true);
    $patterns = get_posts([
        'post_type' => 'wp_block',
        'posts_per_page' => -1,
        'post_status' => ['publish', 'private'],
        'orderby' => 'title',
        'order' => 'ASC',
    ]);
    $new_url = admin_url('post-new.php?post_type=wp_block');
    $list_url = admin_url('edit.php?post_type=wp_block');
    ?>
    <p style="margin:0 0 12px;">
        Zaznacz ktore wzorce maja sie wyswietlic pod opisem tego produktu.
        Wzorce edytujesz w pelnym Gutenbergu (klik "edytuj" obok lub przycisk ponizej) — masz tam dostep do wszystkich
        blokow Shav.
        <br><br>
        <a href="<?php echo esc_url($new_url); ?>" class="button button-primary" target="_blank">+ Nowy wzorzec</a>
        <a href="<?php echo esc_url($list_url); ?>" class="button" target="_blank">Wszystkie wzorce</a>
    </p>
    <?php if (empty($patterns)): ?>
        <p><em>Brak wzorcow. Stworz pierwszy klikajac "+ Nowy wzorzec".</em></p>
    <?php else: ?>
        <div style="max-height:280px; overflow:auto; border:1px solid #ddd; padding:8px 12px; background:#fff;">
            <?php foreach ($patterns as $p): ?>
                <label style="display:block; padding:4px 0;">
                    <input type="checkbox" name="shav_product_block_ids[]" value="<?php echo esc_attr($p->ID); ?>" <?php checked(in_array($p->ID, $selected)); ?>>
                    <?php echo esc_html($p->post_title ?: '(bez tytulu) #' . $p->ID); ?>
                    <a href="<?php echo esc_url(get_edit_post_link($p->ID)); ?>" target="_blank"
                        style="margin-left:8px; font-size:11px;">[edytuj]</a>
                </label>
            <?php endforeach; ?>
        </div>
        <p style="margin-top:8px; color:#666; font-size:12px;">Kolejnosc renderowania = kolejnosc zaznaczania (od gory listy w
            dol).</p>
    <?php endif; ?>
<?php
}

add_action('save_post_product', function ($post_id) {
    if (
        !isset($_POST['shav_product_blocks_nonce']) ||
        !wp_verify_nonce($_POST['shav_product_blocks_nonce'], 'shav_product_blocks_save')
    ) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;
    if (!current_user_can('edit_post', $post_id))
        return;

    $ids = isset($_POST['shav_product_block_ids'])
        ? array_map('intval', (array) $_POST['shav_product_block_ids'])
        : [];
    update_post_meta($post_id, '_shav_product_block_ids', $ids);
});

add_action('woocommerce_after_single_product_summary', function () {
    if (!is_product()) {
        echo '<!-- SHAV-DEBUG: not a product -->';
        return;
    }
    $ids = (array) get_post_meta(get_the_ID(), '_shav_product_block_ids', true);
    if (empty($ids)) {
        echo '<!-- SHAV-DEBUG: empty _shav_product_block_ids -->';
        return;
    }

    $has_opinie = false;
    foreach ($ids as $id) {
        $pattern = get_post($id);
        if ($pattern && $pattern->post_type === 'wp_block') {
            if (has_block('ourblocktheme/opinieproduktowe', $pattern->post_content) || strpos($pattern->post_content, 'wp:ourblocktheme/opinieproduktowe') !== false) {
                $has_opinie = true;
                break;
            }
        }
    }

    $first = true;
    foreach ($ids as $id) {
        $pattern = get_post($id);
        if (!$pattern || $pattern->post_type !== 'wp_block')
            continue;
        echo '<div class="shav-product-pattern shav-product-pattern--' . esc_attr($id) . '">';
        
        if ($first && $has_opinie) {
            echo '<div class="shav-product-fake-tabs-wrapper" style="display: flex; justify-content: center; width: 100%; margin-bottom: 20px;">';
            echo '  <button class="shav-fake-tab-opinie" id="tab-opinie">Bewertungen</button>';
            echo '</div>';
            
            echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                var tabOpinie = document.getElementById("tab-opinie");
                if (tabOpinie) {
                    tabOpinie.addEventListener("click", function(e) {
                        e.preventDefault();
                        var opinieBlock = document.querySelector(".opinieproduktowe");
                        if (opinieBlock) {
                            var y = opinieBlock.getBoundingClientRect().top + window.scrollY - 100;
                            window.scrollTo({top: y, behavior: "smooth"});
                        }
                    });
                }
            });
            </script>';
            $first = false;
        }

        echo do_blocks($pattern->post_content);
        echo '</div>';
    }
}, 6);

// =============================================================================
// SHAV: Przycisk w Admin Barze do szybkiej edycji opisu (wzorca wp_block)
// =============================================================================
add_action('admin_bar_menu', function ($wp_admin_bar) {
    if (!is_admin() && is_product()) {
        $ids = (array) get_post_meta(get_the_ID(), '_shav_product_block_ids', true);
        $ids = array_filter($ids);

        if (!empty($ids)) {
            $first_id = reset($ids);
            $edit_url = get_edit_post_link($first_id, 'raw');
            $title = '✏️ Edytuj opis blokowy';
        } else {
            $edit_url = admin_url('edit.php?post_type=wp_block');
            $title = '✏️ Dodaj opis blokowy';
        }

        $wp_admin_bar->add_node([
            'id' => 'shav_edit_product_description',
            'title' => $title,
            'href' => $edit_url,
            'meta' => [
                'class' => 'shav-edit-desc-btn'
            ]
        ]);
    }
}, 100);

// =============================================================================
// Helpery bloków dropu / Rose Gold (add-to-cart + link do produktu)
// =============================================================================

/**
 * Ikona "dodaj do koszyka" (fi-rr-shopping-cart-add) jako inline SVG.
 * Kolor dziedziczony (currentColor) — sterowany z CSS.
 */
function shav_rosegold_cart_icon_svg()
{
    return '<svg class="rosegold-cart-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">'
        . '<path d="M7 24C8.10457 24 9 23.1046 9 22C9 20.8954 8.10457 20 7 20C5.89543 20 5 20.8954 5 22C5 23.1046 5.89543 24 7 24Z" fill="currentColor"/>'
        . '<path d="M17 24C18.1046 24 19 23.1046 19 22C19 20.8954 18.1046 20 17 20C15.8954 20 15 20.8954 15 22C15 23.1046 15.8954 24 17 24Z" fill="currentColor"/>'
        . '<path d="M23 3H21V1C21 0.734784 20.8946 0.48043 20.7071 0.292893C20.5196 0.105357 20.2652 0 20 0C19.7348 0 19.4804 0.105357 19.2929 0.292893C19.1054 0.48043 19 0.734784 19 1V3H17C16.7348 3 16.4804 3.10536 16.2929 3.29289C16.1054 3.48043 16 3.73478 16 4C16 4.26522 16.1054 4.51957 16.2929 4.70711C16.4804 4.89464 16.7348 5 17 5H19V7C19 7.26522 19.1054 7.51957 19.2929 7.70711C19.4804 7.89464 19.7348 8 20 8C20.2652 8 20.5196 7.89464 20.7071 7.70711C20.8946 7.51957 21 7.26522 21 7V5H23C23.2652 5 23.5196 4.89464 23.7071 4.70711C23.8946 4.51957 24 4.26522 24 4C24 3.73478 23.8946 3.48043 23.7071 3.29289C23.5196 3.10536 23.2652 3 23 3Z" fill="currentColor"/>'
        . '<path d="M21.771 9.726C21.6417 9.70181 21.5089 9.70352 21.3803 9.73103C21.2516 9.75854 21.1297 9.81132 21.0216 9.88629C20.9136 9.96126 20.8214 10.0569 20.7506 10.1678C20.6798 10.2786 20.6316 10.4024 20.609 10.532C20.4843 11.2242 20.1203 11.8505 19.5808 12.3016C19.0412 12.7527 18.3603 12.9999 17.657 13H5.418L4.478 5H13C13.2652 5 13.5196 4.89464 13.7071 4.70711C13.8946 4.51957 14 4.26522 14 4C14 3.73478 13.8946 3.48043 13.7071 3.29289C13.5196 3.10536 13.2652 3 13 3H4.242L4.2 2.648C4.11382 1.9186 3.76306 1.24615 3.21419 0.758104C2.66532 0.270054 1.95647 0.000312836 1.222 0L1 0C0.734784 0 0.48043 0.105357 0.292893 0.292893C0.105357 0.48043 0 0.734784 0 1C0 1.26522 0.105357 1.51957 0.292893 1.70711C0.48043 1.89464 0.734784 2 1 2H1.222C1.46693 2.00003 1.70334 2.08996 1.88637 2.25272C2.06941 2.41547 2.18634 2.63975 2.215 2.883L3.591 14.583C3.73385 15.7998 4.31848 16.9218 5.23391 17.736C6.14934 18.5502 7.33185 19 8.557 19H19C19.2652 19 19.5196 18.8946 19.7071 18.7071C19.8946 18.5196 20 18.2652 20 18C20 17.7348 19.8946 17.4804 19.7071 17.2929C19.5196 17.1054 19.2652 17 19 17H8.557C7.93652 17.0001 7.33127 16.8078 6.82461 16.4497C6.31796 16.0915 5.93483 15.585 5.728 15H17.657C18.8291 15.0001 19.9641 14.5884 20.8635 13.8368C21.763 13.0852 22.3698 12.0415 22.578 10.888C22.6014 10.7587 22.599 10.6261 22.5711 10.4977C22.5432 10.3693 22.4902 10.2477 22.4153 10.1398C22.3403 10.0319 22.2449 9.93978 22.1343 9.86878C22.0238 9.79778 21.9003 9.74926 21.771 9.726Z" fill="currentColor"/>'
        . '</svg>';
}

/**
 * Link do produktu na podstawie ID (albo ręczny override).
 * Zwraca '' jeśli nic sensownego nie ma.
 */
function shav_rosegold_link($product_id, $override = '')
{
    if ($override) {
        return $override;
    }
    $product_id = (int) $product_id;
    if ($product_id > 0 && function_exists('get_permalink')) {
        $url = get_permalink($product_id);
        if ($url) {
            return $url;
        }
    }
    return '';
}

/**
 * Przycisk "dodaj do koszyka" (białe kółko z ikoną).
 * - produkt prosty + kupowalny + na stanie → AJAX add-to-cart (integruje się z mini-cartem)
 * - inny typ (np. wariantowy) → link do strony produktu
 * - brak/niepoprawny ID → nieaktywny placeholder (zachowany wygląd 1:1)
 */
function shav_rosegold_add_to_cart($product_id, $extra_class = '', $variation_id = 0)
{
    $product_id = (int) $product_id;
    $variation_id = (int) $variation_id;
    $icon = shav_rosegold_cart_icon_svg();

    // Wybrany wariant produktu wielowariantowego → dodaj dokładnie ten wariant.
    // (Warianty nie działają przez AJAX add-to-cart, więc zwykły link — przeładowanie dodaje do koszyka.)
    if ($variation_id > 0 && function_exists('wc_get_product')) {
        $variation = wc_get_product($variation_id);
        if ($variation && $variation->is_type('variation') && $variation->is_purchasable() && $variation->is_in_stock()) {
            $parent_id = $variation->get_parent_id();
            $args = array('add-to-cart' => $parent_id, 'variation_id' => $variation_id);
            foreach ($variation->get_variation_attributes() as $ak => $av) {
                $args[$ak] = $av;
            }
            return sprintf(
                '<a href="%1$s" class="rosegold-cart-btn %2$s" data-quantity="1" rel="nofollow" aria-label="%3$s">%4$s</a>',
                esc_url(add_query_arg($args, get_permalink($parent_id))),
                esc_attr($extra_class),
                esc_attr('Dodaj do koszyka: ' . $variation->get_name()),
                $icon
            );
        }
    }

    if ($product_id > 0 && function_exists('wc_get_product')) {
        $product = wc_get_product($product_id);
        if ($product) {
            if ($product->is_type('simple') && $product->is_purchasable() && $product->is_in_stock()) {
                // Skrypty WooCommerce potrzebne do AJAX add-to-cart
                wp_enqueue_script('wc-add-to-cart');
                if (wp_script_is('wc-cart-fragments', 'registered')) {
                    wp_enqueue_script('wc-cart-fragments');
                }
                return sprintf(
                    '<a href="%1$s" class="rosegold-cart-btn %2$s add_to_cart_button ajax_add_to_cart" data-product_id="%3$d" data-quantity="1" rel="nofollow" aria-label="%4$s">%5$s</a>',
                    esc_url($product->add_to_cart_url()),
                    esc_attr($extra_class),
                    $product_id,
                    esc_attr('Dodaj do koszyka: ' . $product->get_name()),
                    $icon
                );
            }
            // Produkt złożony/wariantowy → prowadzimy na stronę produktu
            return sprintf(
                '<a href="%1$s" class="rosegold-cart-btn %2$s" aria-label="%3$s">%4$s</a>',
                esc_url(get_permalink($product_id)),
                esc_attr($extra_class),
                esc_attr('Zobacz produkt: ' . $product->get_name()),
                $icon
            );
        }
    }

    // Brak produktu — pokaż nieaktywny przycisk (żeby układ się zgadzał)
    return sprintf(
        '<span class="rosegold-cart-btn %1$s is-disabled" aria-hidden="true">%2$s</span>',
        esc_attr($extra_class),
        $icon
    );
}


// =============================================================================
// Banery w menu mobilnym (konfigurowalne z WP; wspólne dla globalnego menu
// i menu w hero dropu). Wygląd → "Banery menu mobilnego".
// =============================================================================

function shav_menu_banners_get()
{
    $opt = get_option('shav_menu_banners', array());
    if (!is_array($opt)) {
        $opt = array();
    }
    return wp_parse_args($opt, array('enabled' => 0, 'items' => array()));
}

// Strona ustawień pod Wygląd
add_action('admin_menu', function () {
    add_theme_page(
        'Banery menu mobilnego',
        'Banery menu mobilnego',
        'edit_theme_options',
        'shav-menu-banners',
        'shav_menu_banners_page'
    );
});

add_action('admin_init', function () {
    register_setting('shav_menu_banners_group', 'shav_menu_banners', array(
        'type' => 'array',
        'sanitize_callback' => 'shav_menu_banners_sanitize',
        'default' => array('enabled' => 0, 'items' => array()),
    ));
});

function shav_menu_banners_sanitize($input)
{
    $out = array('enabled' => empty($input['enabled']) ? 0 : 1, 'items' => array());
    if (!empty($input['items']) && is_array($input['items'])) {
        foreach ($input['items'] as $it) {
            $img = isset($it['image']) ? esc_url_raw(trim($it['image'])) : '';
            $link = isset($it['link']) ? esc_url_raw(trim($it['link'])) : '';
            if ($img) {
                $out['items'][] = array('image' => $img, 'link' => $link);
            }
        }
    }
    return $out;
}

// Media uploader tylko na tej stronie
add_action('admin_enqueue_scripts', function ($hook) {
    if ($hook === 'appearance_page_shav-menu-banners') {
        wp_enqueue_media();
    }
});

function shav_menu_banners_page()
{
    $data = shav_menu_banners_get();
    $items = $data['items'];
    // dopełnij do 3 slotów edycyjnych
    for ($i = count($items); $i < 3; $i++) {
        $items[] = array('image' => '', 'link' => '');
    }
    ?>
    <div class="wrap">
        <h1>Banery menu mobilnego</h1>
        <p>Banery pokazują się na dole rozwiniętego menu mobilnego — globalnie i na podstronach dropu.
            Każdy baner to obrazek + link (przekierowanie po kliknięciu). Puste sloty są pomijane.
            Wyłączenie „Pokaż banery" chowa je wszędzie.</p>
        <form method="post" action="options.php">
            <?php settings_fields('shav_menu_banners_group'); ?>
            <table class="form-table">
                <tr>
                    <th scope="row">Pokaż banery</th>
                    <td><label><input type="checkbox" name="shav_menu_banners[enabled]" value="1" <?php checked($data['enabled'], 1); ?>> Włącz banery w menu mobilnym</label></td>
                </tr>
            </table>
            <h2>Banery</h2>
            <?php foreach ($items as $idx => $it): ?>
                <div class="shav-mb-row"
                    style="margin:0 0 20px;padding:16px;border:1px solid #dcdcde;background:#fff;max-width:520px;border-radius:6px;">
                    <p><strong>Baner <?php echo (int) ($idx + 1); ?></strong></p>
                    <p>
                        <input type="text" class="shav-mb-image regular-text"
                            name="shav_menu_banners[items][<?php echo (int) $idx; ?>][image]"
                            value="<?php echo esc_attr($it['image']); ?>" placeholder="URL obrazka">
                        <button type="button" class="button shav-mb-upload">Wybierz obrazek</button>
                    </p>
                    <p class="shav-mb-preview"><?php if ($it['image']): ?><img src="<?php echo esc_url($it['image']); ?>"
                                style="max-width:280px;height:auto;border-radius:16px;"><?php endif; ?></p>
                    <p><label>Link (przekierowanie):<br><input type="url" class="regular-text"
                                name="shav_menu_banners[items][<?php echo (int) $idx; ?>][link]"
                                value="<?php echo esc_attr($it['link']); ?>" placeholder="https://..."></label></p>
                </div>
            <?php endforeach; ?>
            <?php submit_button(); ?>
        </form>
    </div>
    <script>
        jQuery(function ($) {
            $('.shav-mb-upload').on('click', function (e) {
                e.preventDefault();
                var row = $(this).closest('.shav-mb-row');
                var frame = wp.media({ title: 'Wybierz obrazek banera', button: { text: 'Użyj' }, multiple: false, library: { type: 'image' } });
                frame.on('select', function () {
                    var att = frame.state().get('selection').first().toJSON();
                    row.find('.shav-mb-image').val(att.url);
                    row.find('.shav-mb-preview').html('<img src="' + att.url + '" style="max-width:280px;height:auto;border-radius:16px;">');
                });
                frame.open();
            });
        });
    </script>
    <?php
}

/**
 * Markup banerów do menu mobilnego (wywoływany w globalnym headerze i w hero dropu).
 */
function shav_render_menu_banners()
{
    $data = shav_menu_banners_get();
    if (empty($data['enabled']) || empty($data['items'])) {
        return '';
    }
    $arrow = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
    $html = '<div class="menu-banners">';
    foreach ($data['items'] as $it) {
        if (empty($it['image'])) {
            continue;
        }
        $link = !empty($it['link']) ? $it['link'] : '';
        $tag = $link ? 'a' : 'div';
        $href = $link ? ' href="' . esc_url($link) . '"' : '';
        $html .= '<' . $tag . ' class="menu-banner"' . $href . ' style="background-image:url(\'' . esc_url($it['image']) . '\');" aria-label="Baner promocyjny">'
            . '<span class="menu-banner__arrow">' . $arrow . '</span>'
            . '</' . $tag . '>';
    }
    $html .= '</div>';
    return $html;
}


// Register our new blocks
function our_new_blocks()
{
    wp_localize_script('wp-editor', 'ourThemeData', array('themePath' => get_stylesheet_directory_uri()));

    // --- Bloki dropu / Rose Gold (reużywalne pod promocje) ---
    register_block_type_from_metadata(__DIR__ . '/build/eksperci');
    register_block_type_from_metadata(__DIR__ . '/build/jakuzywac');
    register_block_type_from_metadata(__DIR__ . '/build/opinieproduktowe');
    register_block_type_from_metadata(__DIR__ . '/build/produktowaslider');
    register_block_type_from_metadata(__DIR__ . '/build/rosegoldhero');
    register_block_type_from_metadata(__DIR__ . '/build/rosegoldfeatured');
    register_block_type_from_metadata(__DIR__ . '/build/rosegoldgrid');
    register_block_type_from_metadata(__DIR__ . '/build/rosegoldbanner');
    register_block_type_from_metadata(__DIR__ . '/build/rosegoldzestaw');
    register_block_type_from_metadata(__DIR__ . '/build/rosegoldslider');

    register_block_type_from_metadata(__DIR__ . '/build/whoweareonas');
    register_block_type_from_metadata(__DIR__ . '/build/whoweareshavwomen');
    register_block_type_from_metadata(__DIR__ . '/build/hurtdesign');
    register_block_type_from_metadata(__DIR__ . '/build/hurtmapa');
    register_block_type_from_metadata(__DIR__ . '/build/hurtkontakt');
    register_block_type_from_metadata(__DIR__ . '/build/patenttext');
    register_block_type_from_metadata(__DIR__ . '/build/hurthead');
    register_block_type_from_metadata(__DIR__ . '/build/patenthead');
    register_block_type_from_metadata(__DIR__ . '/build/patentdesign');
    register_block_type_from_metadata(__DIR__ . '/build/faqostrzefoliowe');
    register_block_type_from_metadata(__DIR__ . '/build/faqetui');
    register_block_type_from_metadata(__DIR__ . '/build/faqostrzestalowe');
    register_block_type_from_metadata(__DIR__ . '/build/etuiproduktowa');
    register_block_type_from_metadata(__DIR__ . '/build/ostrzefolioweproduktowa');
    register_block_type_from_metadata(__DIR__ . '/build/ostrzefolioweproduktowa2');
    register_block_type_from_metadata(__DIR__ . '/build/ostrzestaloweproduktowa');
    register_block_type_from_metadata(__DIR__ . '/build/pudelkoproduktowa');
    register_block_type_from_metadata(__DIR__ . '/build/zwroty');
    register_block_type_from_metadata(__DIR__ . '/build/ogrodnikproduktowa');
    register_block_type_from_metadata(__DIR__ . '/build/kontakt');
    register_block_type_from_metadata(__DIR__ . '/build/politykaprywatnosci');
    register_block_type_from_metadata(__DIR__ . '/build/regulamin');
    register_block_type_from_metadata(__DIR__ . '/build/onasimages');
    register_block_type_from_metadata(__DIR__ . '/build/onasteam');
    register_block_type_from_metadata(__DIR__ . '/build/onashead');
    register_block_type_from_metadata(__DIR__ . '/build/heroonas');
    register_block_type_from_metadata(__DIR__ . '/build/szachshavwomen1');
    register_block_type_from_metadata(__DIR__ . '/build/masazerwoman1');
    register_block_type_from_metadata(__DIR__ . '/build/masazerwoman2');
    register_block_type_from_metadata(__DIR__ . '/build/masazerwoman3');
    register_block_type_from_metadata(__DIR__ . '/build/masazerwoman4');
    register_block_type_from_metadata(__DIR__ . '/build/myjkaszach1');
    register_block_type_from_metadata(__DIR__ . '/build/myjkaszach2');
    register_block_type_from_metadata(__DIR__ . '/build/myjkaszach3');
    register_block_type_from_metadata(__DIR__ . '/build/myjkaszach4');
    register_block_type_from_metadata(__DIR__ . '/build/blackwithtextshavwomen');
    register_block_type_from_metadata(__DIR__ . '/build/faqshavwomen');
    register_block_type_from_metadata(__DIR__ . '/build/glownainstagram');
    register_block_type_from_metadata(__DIR__ . '/build/faqglowna');
    register_block_type_from_metadata(__DIR__ . '/build/glownaikony');
    register_block_type_from_metadata(__DIR__ . '/build/whoweareglowna');
    register_block_type_from_metadata(__DIR__ . '/build/glownakup');
    register_block_type_from_metadata(__DIR__ . '/build/ekokarty');
    register_block_type_from_metadata(__DIR__ . '/build/metodyplatnoscitext');
    register_block_type_from_metadata(__DIR__ . '/build/metodyplatnoscihead');
    register_block_type_from_metadata(__DIR__ . '/build/faq');
    register_block_type_from_metadata(__DIR__ . '/build/faqkontakt');
    register_block_type_from_metadata(__DIR__ . '/build/metodywysylkikup');
    register_block_type_from_metadata(__DIR__ . '/build/metodywysylkitext');
    register_block_type_from_metadata(__DIR__ . '/build/metodywysylkihead');
    register_block_type_from_metadata(__DIR__ . '/build/misjaheader');
    register_block_type_from_metadata(__DIR__ . '/build/misjarakroll');
    register_block_type_from_metadata(__DIR__ . '/build/misjaslider');
    register_block_type_from_metadata(__DIR__ . '/build/onasstandard');
    register_block_type_from_metadata(__DIR__ . '/build/onasrozwijamy');
    register_block_type_from_metadata(__DIR__ . '/build/onasjak');
    register_block_type_from_metadata(__DIR__ . '/build/onaswazne');
    register_block_type_from_metadata(__DIR__ . '/build/stacjadokujaca');
    register_block_type_from_metadata(__DIR__ . '/build/cechyzero');
    register_block_type_from_metadata(__DIR__ . '/build/cechypodswietlanie');
    register_block_type_from_metadata(__DIR__ . '/build/cechyostrza');
    register_block_type_from_metadata(__DIR__ . '/build/cechywodo');
    register_block_type_from_metadata(__DIR__ . '/build/cechycutfree');
    register_block_type_from_metadata(__DIR__ . '/build/blackwithtext');
    register_block_type_from_metadata(__DIR__ . '/build/maszynkafunkcje');
    register_block_type_from_metadata(__DIR__ . '/build/logobackground');
    register_block_type_from_metadata(__DIR__ . '/build/shopdropdown');
    register_block_type_from_metadata(__DIR__ . '/build/sliderwysylka');
    register_block_type_from_metadata(__DIR__ . '/build/sliderplatnosci');
    register_block_type_from_metadata(__DIR__ . '/build/slideronas');
    register_block_type_from_metadata(__DIR__ . '/build/timeline');
    register_block_type_from_metadata(__DIR__ . '/build/whoweare');
    register_block_type_from_metadata(__DIR__ . '/build/genericbutton');
    register_block_type_from_metadata(__DIR__ . '/build/genericheading');
    register_block_type_from_metadata(__DIR__ . '/build/slideshow');
    register_block_type_from_metadata(__DIR__ . '/build/banner');
    register_block_type_from_metadata(__DIR__ . '/build/footer');
    register_block_type_from_metadata(__DIR__ . '/build/header');
    register_block_type_from_metadata(__DIR__ . '/build/singlepost');
    register_block_type_from_metadata(__DIR__ . '/build/page');
    register_block_type_from_metadata(__DIR__ . '/build/blogindex');
    register_block_type_from_metadata(__DIR__ . '/build/archive');
    register_block_type_from_metadata(get_template_directory() . '/build/shav-product-grid');
    register_block_type_from_metadata(get_template_directory() . '/build/heroarchiwum');
    register_block_type_from_metadata(__DIR__ . '/build/glownabaner');
    register_block_type_from_metadata(__DIR__ . '/build/glownacechy');
    register_block_type_from_metadata(__DIR__ . '/build/glownagrid');
    register_block_type_from_metadata(__DIR__ . '/build/glownaoceny');
    register_block_type_from_metadata(__DIR__ . '/build/glownaopinie');
    register_block_type_from_metadata(__DIR__ . '/build/karieraheader');
    register_block_type_from_metadata(__DIR__ . '/build/karierazespol');
    register_block_type_from_metadata(__DIR__ . '/build/textkariera');
    register_block_type_from_metadata(__DIR__ . '/build/karieraikony');
    register_block_type_from_metadata(__DIR__ . '/build/karieraoferty');
    register_block_type_from_metadata(__DIR__ . '/build/porownanieprodukty');
}

add_action('init', 'our_new_blocks');


function myallowedblocks($allowed_block_types, $editor_context)
{
    // If you are on a page/post editor screen
    if (!empty($editor_context->post)) {
        return $allowed_block_types;
    }

    // if you are on the FSE screen
    return array('ourblocktheme/header', 'ourblocktheme/footer');
}

// Uncomment the line below if you actually want to restrict which block types are allowed
// add_filter('allowed_block_types_all', 'myallowedblocks', 10, 2);

function enqueue_custom_gsap_scripts()
{
    // Enqueue the custom GSAP script for the front-end.
    wp_enqueue_script(
        'custom-gsap',
        get_template_directory_uri() . '/inc/custom-gsap.js', // Adjust the path to your js directory
        array('wp-blocks', 'wp-element', 'wp-editor', 'gsap-core', 'gsap-scrolltrigger', 'gsap-scrollto', 'gsap-customease', 'lenis'), // Dependencies
        filemtime(get_template_directory() . '/inc/custom-gsap.js'), // Version based on file modification time
        true // Load in footer
    );

    // Enqueue GSAP and Lenis from CDN
    wp_enqueue_script('gsap-core', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.4/gsap.min.js', array(), '3.11.4', true);
    wp_enqueue_script('gsap-scrolltrigger', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.4/ScrollTrigger.min.js', array('gsap-core'), '3.11.4', true);
    wp_enqueue_script('gsap-scrollto', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.4/ScrollToPlugin.min.js', array('gsap-core'), '3.11.4', true);
    wp_enqueue_script('gsap-customease', 'https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/CustomEase.min.js', array('gsap-core'), '3.12.5', true);
    wp_enqueue_script('lenis', 'https://unpkg.com/@studio-freight/lenis@1.0.42/dist/lenis.min.js', array(), '1.0.42', true);

    // Enqueue slider script
    wp_enqueue_script('slider-platnosci', get_template_directory_uri() . '/inc/slider-platnosci.js', array('jquery', 'gsap-core', 'gsap-scrolltrigger', 'gsap-scrollto'), '1.0', true);
}
add_action('wp_enqueue_scripts', 'enqueue_custom_gsap_scripts');



// Function to list pages in footer
function my_custom_page_list_shortcode()
{
    // Arguments for wp_list_pages
    $args = array(
        'title_li' => '', // Remove default title
    );

    // Retrieve the list of pages
    $pages = wp_list_pages($args);

    // Wrap in a <ul> tag
    $output = '<ul>' . $pages . '</ul>';

    return $output;
}

// Register the shortcode
add_shortcode('list_pages_in_footer', 'my_custom_page_list_shortcode');


// mobile menu
function enqueue_mobile_menu_script()
{
    // Enqueue React and ReactDOM from CDN
    // wp_enqueue_script('react', 'https://unpkg.com/react@18/umd/react.production.min.js', array(), null, true);
    // wp_enqueue_script('react-dom', 'https://unpkg.com/react-dom@18/umd/react-dom.production.min.js', array('react'), null, true);

    // Enqueue your bundled mobile menu script
    // wp_enqueue_script('mobile-menu', get_template_directory_uri() . '/inc/mobile-menu.js', array(), null, true);

    // Localize script to pass data from PHP to JavaScript
    wp_localize_script('mobile-menu', 'menuData', array(
        'categories' => array_map(function ($term) {
            $products = wc_get_products(array(
                'category' => array($term->slug),
                'limit' => 4,
            ));
            return array(
                'id' => $term->term_id,
                'name' => $term->name,
                'products' => array_map(function ($product) {
                    return array(
                        'id' => $product->get_id(),
                        'name' => $product->get_name(),
                        'permalink' => get_permalink($product->get_id()),
                    );
                }, $products),
            );
        }, get_terms(array(
                'taxonomy' => 'product_cat',
                'orderby' => 'name',
                'hide_empty' => false,
            ))),
    ));
}
add_action('wp_enqueue_scripts', 'enqueue_mobile_menu_script');

function shav_enqueue_header_script()
{
    wp_enqueue_script('shav-header', get_template_directory_uri() . '/inc/header.js', array(), '1.0', true);
}
add_action('wp_enqueue_scripts', 'shav_enqueue_header_script');

if (!function_exists('shavwoman_support')):
    function shavwoman_support()
    {
        add_theme_support('wp-block-styles');
        add_editor_style('style.css');
    }
endif;
add_action('after_setup_theme', 'shavwoman_support');

// Force translations and link overrides for Single Post
add_filter('previous_post_link', function ($output) {
    return str_replace(['Poprzedni wpis:', 'Poprzedni:', 'Previous:', 'Poprzedni wpis', 'Poprzedni', 'Previous'], 'Vorheriger Beitrag:', $output);
});
add_filter('next_post_link', function ($output) {
    return str_replace(['Następny wpis:', 'Następny:', 'Next:', 'Następny wpis', 'Następny', 'Next'], 'Nächster Beitrag:', $output);
});
add_filter('render_block', function ($block_content, $block) {
    // Override back button link
    if (strpos($block_content, 'blog-hero-back-btn') !== false) {
        $block_content = str_replace('href="/unser-blog/"', 'href="/blog"', $block_content);
    }
    return $block_content;
}, 10, 2);

//////
function mytheme_register_nav_menu()
{
    register_nav_menus(array(
        'primary_menu' => __('Primary', 'nav-menu'),

    ));
}
add_action('after_setup_theme', 'mytheme_register_nav_menu', 0);


// WOCOMMERCE SETUP
// shop setup
function mytheme_add_woocommerce_support()
{
    add_theme_support('woocommerce');
    add_theme_support('align-wide');
    add_theme_support('wp-block-styles');
    add_theme_support('editor-styles');
    add_theme_support('dark-editor-style');

    //support
    add_theme_support('title-tag');
    add_theme_support('custom-logo');
    add_theme_support('post-thumbnails');
    add_image_size('pageBanner', 1500, 350, true);
    add_theme_support('editor-styles');
    add_editor_style(array('https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i', 'build/style-index.css', 'build/index.css'));

}
add_action('after_setup_theme', 'mytheme_add_woocommerce_support');


// Disable WooCommerce Zoom, Lightbox, and Gallery Slider
add_action('wp', 'remove_woocommerce_zoom_lightbox_gallery', 100);

function remove_woocommerce_zoom_lightbox_gallery()
{
    remove_theme_support('wc-product-gallery-zoom');
    remove_theme_support('wc-product-gallery-lightbox');
    // remove_theme_support( 'wc-product-gallery-slider' );
}

// Ensure that the zoom class is removed
// add_filter( 'wp_get_attachment_image_attributes', 'custom_remove_zoom_class', 10, 3 );

function custom_remove_zoom_class($attr, $attachment, $size)
{
    if (is_product()) {
        unset($attr['data-srcset']);
        unset($attr['data-large_image']);
        unset($attr['data-large_image_width']);
        unset($attr['data-large_image_height']);
        if (isset($attr['class'])) {
            $attr['class'] = str_replace('wp-post-image', '', $attr['class']);
        }
    }
    return $attr;
}

// Remove the link around the product image
add_filter('woocommerce_single_product_image_thumbnail_html', 'remove_image_link', 10, 2);

function remove_image_link($html, $post_id)
{
    if (is_product()) {
        // Remove the anchor tag
        $html = preg_replace('#<a.*?>(.*?)<\/a>#i', '$1', $html);
    }
    return $html;
}

// Swatche wariantow w boxie ceny (produkty wielowariantowe) —
// markup: woocommerce/single-product/add-to-cart/variable.php
add_action('wp_enqueue_scripts', function () {
    if (is_product()) {
        wp_enqueue_script('wc-add-to-cart-variation');
        wp_enqueue_script('shav-variations', get_template_directory_uri() . '/inc/shav-variations.js', array('jquery', 'wc-add-to-cart-variation'), '1.1', true);
    }
});

// =============================================================================
// Zestawy (WPC Grouped Product): zdjecie zestawu per wariant skladnika.
// Golarka w zestawie jest wielowariantowa — po wyborze koloru galeria zestawu
// ma pokazac dedykowane zdjecie CALEGO zestawu w tym kolorze (nie zdjecie
// samej golarki). Pola (URL) generuja sie w Dane produktu -> Zaawansowane,
// po jednym na kazda opcje wariantu kazdego wielowariantowego skladnika.
// Meta: _shav_woosg_variant_image_{atrybut}_{opcja}. Front: inc/shav-variations.js.
// =============================================================================

// Wielowariantowe skladniki zestawu (z meta `woosg_ids` pluginu, format "68/1,338/1")
function shav_woosg_get_variable_children($product_id)
{
    $ids = get_post_meta($product_id, 'woosg_ids', true);
    if (empty($ids)) {
        return array();
    }
    $pairs = is_array($ids) ? $ids : explode(',', (string) $ids);
    $children = array();
    foreach ($pairs as $pair) {
        if (is_array($pair)) {
            $pid = isset($pair['id']) ? (int) $pair['id'] : 0;
        } else {
            $pid = (int) strtok((string) $pair, '/');
        }
        if (!$pid) {
            continue;
        }
        $child = wc_get_product($pid);
        if ($child && $child->is_type('variable')) {
            $children[] = $child;
        }
    }
    return $children;
}

// Wszystkie klucze pol dla zestawu: [meta_key => [attr_field, option, label]]
function shav_woosg_variant_image_keys($product_id)
{
    $keys = array();
    foreach (shav_woosg_get_variable_children($product_id) as $child) {
        foreach ($child->get_variation_attributes() as $attribute_name => $options) {
            $attr_slug = sanitize_title($attribute_name);
            $attr_field = 'attribute_' . $attr_slug;
            foreach ($options as $option) {
                $label = $option;
                if (taxonomy_exists($attribute_name)) {
                    $term = get_term_by('slug', $option, $attribute_name);
                    if ($term) {
                        $label = $term->name;
                    }
                }
                $meta_key = '_shav_woosg_variant_image_' . $attr_slug . '_' . sanitize_title($option);
                $keys[$meta_key] = array(
                    'attr_field' => $attr_field,
                    'option' => $option,
                    'label' => wc_attribute_label($attribute_name, $child) . ': ' . $label,
                );
            }
        }
    }
    return $keys;
}

// Pola w adminie (Dane produktu -> Zaawansowane)
add_action('woocommerce_product_options_advanced', function () {
    global $post;
    if (!$post) {
        return;
    }
    $keys = shav_woosg_variant_image_keys($post->ID);
    if (empty($keys)) {
        return;
    }
    echo '<div class="options_group shav-woosg-images-group">';
    echo '<p style="padding: 10px 20px 0 20px; margin: 0;"><strong>Zdjęcie zestawu per wariant</strong><br>';
    echo '<span class="description" style="display:inline-block; margin-top:4px;">Wybierz zdjęcie całego zestawu pokazywanego w galerii po wyborze wariantu.</span></p>';

    $placeholder = wc_placeholder_img_src();
    foreach ($keys as $meta_key => $info) {
        $image_id = get_post_meta($post->ID, $meta_key, true);
        $image_url = '';
        if ($image_id && is_numeric($image_id)) {
            $image_url = wp_get_attachment_image_url($image_id, 'thumbnail');
        } elseif ($image_id) {
            $image_url = $image_id;
        }
        ?>
        <p class="form-field">
            <label for="<?php echo esc_attr($meta_key); ?>"><?php echo esc_html($info['label']); ?></label>
            <span class="shav-image-upload-wrapper" style="display:inline-block;">
                <a href="#" class="shav-upload-image-button" data-target="<?php echo esc_attr($meta_key); ?>">
                    <img src="<?php echo esc_url($image_url ? $image_url : $placeholder); ?>"
                        style="width:60px;height:60px;object-fit:cover;border:1px solid #ddd;border-radius:4px;display:block;" />
                </a>
                <input type="hidden" name="<?php echo esc_attr($meta_key); ?>" id="<?php echo esc_attr($meta_key); ?>"
                    value="<?php echo esc_attr($image_id); ?>" />
                <a href="#" class="shav-remove-image-button"
                    style="display:block;text-align:center;color:#a00;font-size:12px;margin-top:4px;">Usuń</a>
            </span>
        </p>
        <?php
    }
    echo '</div>';

    ?>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            var mediaFrame;
            $('.shav-woosg-images-group').on('click', '.shav-upload-image-button', function (e) {
                e.preventDefault();
                var $btn = $(this);
                var targetId = $btn.data('target');

                if (mediaFrame) {
                    mediaFrame.targetBtn = $btn;
                    mediaFrame.targetId = targetId;
                    mediaFrame.open();
                    return;
                }

                mediaFrame = wp.media({
                    title: 'Wybierz zdjęcie zestawu',
                    button: { text: 'Użyj tego zdjęcia' },
                    multiple: false
                });

                mediaFrame.targetBtn = $btn;
                mediaFrame.targetId = targetId;

                mediaFrame.on('select', function () {
                    var attachment = mediaFrame.state().get('selection').first().toJSON();
                    $('#' + mediaFrame.targetId).val(attachment.id);
                    var url = attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;
                    mediaFrame.targetBtn.find('img').attr('src', url);
                });

                mediaFrame.open();
            });

            $('.shav-woosg-images-group').on('click', '.shav-remove-image-button', function (e) {
                e.preventDefault();
                var $wrapper = $(this).closest('.shav-image-upload-wrapper');
                $wrapper.find('input[type="hidden"]').val('');
                $wrapper.find('img').attr('src', '<?php echo esc_url($placeholder); ?>');
            });
        });
    </script>
    <?php
});

// Zapis pol
add_action('woocommerce_process_product_meta', function ($post_id) {
    foreach (array_keys(shav_woosg_variant_image_keys($post_id)) as $meta_key) {
        if (isset($_POST[$meta_key])) {
            $val = sanitize_text_field($_POST[$meta_key]);
            update_post_meta($post_id, $meta_key, $val);
        }
    }
});

// Mapa dla JS na stronie zestawu: { attribute_pa_kolor: { bialy: url } }
add_action('wp_footer', function () {
    if (!is_product()) {
        return;
    }
    $product = wc_get_product(get_the_ID());
    if (!$product || !$product->is_type('woosg')) {
        return;
    }
    $map = array();
    foreach (shav_woosg_variant_image_keys($product->get_id()) as $meta_key => $info) {
        $val = get_post_meta($product->get_id(), $meta_key, true);
        if ($val) {
            $url = is_numeric($val) ? wp_get_attachment_image_url($val, 'full') : $val;
            if ($url) {
                $map[$info['attr_field']][$info['option']] = esc_url_raw($url);
            }
        }
    }
    if (!empty($map)) {
        echo '<script type="application/json" id="shav-woosg-variant-images">' . wp_json_encode($map) . '</script>';
    }
});

//remove breadcrumbs
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
// remove category from single product
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);

// Remove archive prefix
add_filter('get_the_archive_title', function ($title) {
    if (is_category()) {
        $title = single_cat_title('', false);
    } elseif (is_tag()) {
        $title = single_tag_title('', false);
    } elseif (is_author()) {
        $title = '<span class="vcard">' . get_the_author() . '</span>';
    } elseif (is_tax()) { //for custom post types
        $title = sprintf(__('%1$s'), single_term_title('', false));
    } elseif (is_post_type_archive()) {
        $title = post_type_archive_title('', false);
    }
    return $title;
});



// arrows in single product
// Add navigation arrows

add_filter('woocommerce_single_product_carousel_options', 'sf_update_woo_flexslider_options');
/**
 * Addd Navigations arrows
 */
function sf_update_woo_flexslider_options($options)
{
    $options['directionNav'] = true;
    return $options;
}


// SUBTITLE BELOW TITLE IN SINGLE PAGE
// Add subtitle field to product edit page
function add_subtitle_custom_field()
{
    woocommerce_wp_text_input(
        array(
            'id' => 'product_subtitle',
            'label' => __('Product Subtitle', 'woocommerce'),
            'desc_tip' => 'true',
            'description' => __('Enter the subtitle of the product.', 'woocommerce'),
            'type' => 'text'
        )
    );
}
add_action('woocommerce_product_options_general_product_data', 'add_subtitle_custom_field');

// Save the custom field value
function save_subtitle_custom_field($post_id)
{
    $product = wc_get_product($post_id);
    $subtitle = isset($_POST['product_subtitle']) ? $_POST['product_subtitle'] : '';
    $product->update_meta_data('product_subtitle', sanitize_text_field($subtitle));
    $product->save();
}
add_action('woocommerce_process_product_meta', 'save_subtitle_custom_field');

// Checkbox: wyroznij ostatnie slowo tytulu czcionka Dolce (kursywa, ~80px)
function shav_add_title_accent_field()
{
    woocommerce_wp_checkbox(array(
        'id' => '_title_accent_last_word',
        'label' => __('Wyróżnij ostatnie słowo tytułu (Dolce)', 'shavwoman'),
        'description' => __('Ostatnie słowo z tytułu produktu zostanie wyświetlone czcionką akcentową Dolce w większym rozmiarze.', 'shavwoman'),
        'desc_tip' => true,
    ));
}
add_action('woocommerce_product_options_general_product_data', 'shav_add_title_accent_field');

function shav_save_title_accent_field($post_id)
{
    $product = wc_get_product($post_id);
    $val = isset($_POST['_title_accent_last_word']) ? 'yes' : 'no';
    $product->update_meta_data('_title_accent_last_word', $val);
    $product->save();
}
add_action('woocommerce_process_product_meta', 'shav_save_title_accent_field');

// -----------------------------------------------------------------------------
// Rating pill (avatary + liczba klientow + ocena + gwiazdki) — Figma 390:1478
// -----------------------------------------------------------------------------
function shav_add_rating_pill_fields()
{
    echo '<div class="options_group"><p class="form-field"><strong>Rating pill (na stronie produktu):</strong></p>';

    for ($i = 1; $i <= 5; $i++) {
        woocommerce_wp_text_input(array(
            'id' => "_rating_avatar{$i}",
            'label' => "Avatar {$i} — URL",
            'description' => 'Wklej URL obrazka z biblioteki mediów (kwadratowy, ~60×60).',
            'desc_tip' => true,
        ));
    }
    woocommerce_wp_text_input(array(
        'id' => '_rating_count_label',
        'label' => 'Liczba klientów (etykieta)',
        'placeholder' => '+300K Klientów',
        'desc_tip' => true,
        'description' => 'Tekst wyświetlany w pasku (np. "+300K Klientów").',
    ));
    woocommerce_wp_text_input(array(
        'id' => '_rating_score_label',
        'label' => 'Ocena (etykieta)',
        'placeholder' => '4.9',
        'desc_tip' => true,
        'description' => 'Liczba wyświetlana obok gwiazdek (np. "4.9").',
    ));
    echo '</div>';
}
add_action('woocommerce_product_options_general_product_data', 'shav_add_rating_pill_fields');

function shav_save_rating_pill_fields($post_id)
{
    $product = wc_get_product($post_id);
    for ($i = 1; $i <= 5; $i++) {
        $key = "_rating_avatar{$i}";
        $product->update_meta_data($key, isset($_POST[$key]) ? esc_url_raw($_POST[$key]) : '');
    }
    $product->update_meta_data('_rating_count_label', isset($_POST['_rating_count_label']) ? sanitize_text_field($_POST['_rating_count_label']) : '');
    $product->update_meta_data('_rating_score_label', isset($_POST['_rating_score_label']) ? sanitize_text_field($_POST['_rating_score_label']) : '');
    $product->save();
}
add_action('woocommerce_process_product_meta', 'shav_save_rating_pill_fields');

function shav_render_rating_pill()
{
    global $product;
    if (!$product)
        return;

    $pid = $product->get_id();
    if (function_exists('shav_is_hidden') && shav_is_hidden($pid, 'rating'))
        return;
    $avatars = [];
    for ($i = 1; $i <= 5; $i++) {
        $url = shav_get_field($pid, "_rating_avatar{$i}", 'rating');
        if (!empty($url))
            $avatars[] = $url;
    }
    $count_label = shav_get_field($pid, '_rating_count_label', 'rating');
    $score_label = shav_get_field($pid, '_rating_score_label', 'rating');

    // Nic do pokazania — nie renderuj pustego pilla
    if (empty($avatars) && empty($count_label) && empty($score_label))
        return;

    echo '<div class="product-summary__rating-pill">';

    if (!empty($avatars)) {
        echo '<span class="product-summary__rating-avatars">';
        foreach ($avatars as $url) {
            echo '<img class="product-summary__rating-avatar" src="' . esc_url($url) . '" alt="">';
        }
        echo '</span>';
    }

    if (!empty($count_label)) {
        echo '<span class="product-summary__rating-sep" aria-hidden="true"></span>';
        echo '<span class="product-summary__rating-count">' . esc_html($count_label) . '</span>';
    }

    if (!empty($score_label)) {
        echo '<span class="product-summary__rating-sep" aria-hidden="true"></span>';
        echo '<span class="product-summary__rating-score">' . esc_html($score_label) . '</span>';
        echo '<span class="product-summary__rating-stars" aria-label="' . esc_attr($score_label) . ' z 5">';
        for ($s = 0; $s < 5; $s++) {
            echo '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="#e9bd0b"><path d="M12 17.27 18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>';
        }
        echo '</span>';
    }

    echo '</div>';
}
add_action('woocommerce_single_product_summary', 'shav_render_rating_pill', 7);

// -----------------------------------------------------------------------------
// Sekcja "Jesteśmy po stronie kobiet" (.shav-kobiety) — Figma 390:1601
// -----------------------------------------------------------------------------
function shav_add_kobiety_fields()
{
    echo '<div class="options_group"><p class="form-field"><strong>Sekcja "Jesteśmy po stronie kobiet":</strong></p>';

    woocommerce_wp_text_input(array(
        'id' => '_kobiety_title',
        'label' => 'Tytuł sekcji',
        'placeholder' => 'Jesteśmy po stronie kobiet',
        'desc_tip' => true,
        'description' => 'Zostaw puste, by ukryć całą sekcję.',
    ));

    for ($i = 1; $i <= 2; $i++) {
        woocommerce_wp_textarea_input(array(
            'id' => "_kobiety_box{$i}_text",
            'label' => "Box {$i} — tekst",
            'desc_tip' => true,
            'description' => 'Treść boxa.',
        ));
        woocommerce_wp_text_input(array(
            'id' => "_kobiety_box{$i}_link_text",
            'label' => "Box {$i} — tekst linku",
            'desc_tip' => true,
            'description' => 'Niebieski link na końcu boxa.',
        ));
        woocommerce_wp_text_input(array(
            'id' => "_kobiety_box{$i}_link_url",
            'label' => "Box {$i} — URL linku",
            'desc_tip' => true,
            'description' => 'Pełny URL lub względny (/strona).',
        ));
    }
    echo '</div>';
}
add_action('woocommerce_product_options_general_product_data', 'shav_add_kobiety_fields');

function shav_save_kobiety_fields($post_id)
{
    $product = wc_get_product($post_id);
    $product->update_meta_data('_kobiety_title', isset($_POST['_kobiety_title']) ? sanitize_text_field($_POST['_kobiety_title']) : '');
    for ($i = 1; $i <= 2; $i++) {
        $product->update_meta_data("_kobiety_box{$i}_text", isset($_POST["_kobiety_box{$i}_text"]) ? sanitize_textarea_field($_POST["_kobiety_box{$i}_text"]) : '');
        $product->update_meta_data("_kobiety_box{$i}_link_text", isset($_POST["_kobiety_box{$i}_link_text"]) ? sanitize_text_field($_POST["_kobiety_box{$i}_link_text"]) : '');
        $product->update_meta_data("_kobiety_box{$i}_link_url", isset($_POST["_kobiety_box{$i}_link_url"]) ? esc_url_raw($_POST["_kobiety_box{$i}_link_url"]) : '');
    }
    $product->save();
}
add_action('woocommerce_process_product_meta', 'shav_save_kobiety_fields');

function shav_render_kobiety()
{
    global $product;
    if (!$product)
        return;
    $pid = $product->get_id();
    if (function_exists('shav_is_hidden') && shav_is_hidden($pid, 'kobiety'))
        return;
    $title = shav_get_field($pid, '_kobiety_title', 'kobiety');
    if (empty($title))
        return;

    echo '<div class="shav-kobiety">';
    echo '<h3 class="shav-kobiety__title">' . esc_html($title) . '</h3>';
    echo '<div class="shav-kobiety__boxes">';

    for ($i = 1; $i <= 2; $i++) {
        $text = shav_get_field($pid, "_kobiety_box{$i}_text", 'kobiety');
        $link_text = shav_get_field($pid, "_kobiety_box{$i}_link_text", 'kobiety');
        $link_url = shav_get_field($pid, "_kobiety_box{$i}_link_url", 'kobiety');
        if (empty($text) && empty($link_text))
            continue;

        echo '<div class="shav-kobiety__box">';
        echo '<p>';
        if (!empty($text)) {
            echo nl2br(esc_html($text));
            if (!empty($link_text))
                echo ' ';
        }
        if (!empty($link_text)) {
            if (!empty($link_url)) {
                echo '<a class="shav-kobiety__link" href="' . esc_url($link_url) . '">' . esc_html($link_text) . '</a>';
            } else {
                echo '<span class="shav-kobiety__link">' . esc_html($link_text) . '</span>';
            }
        }
        echo '</p>';
        echo '</div>';
    }
    echo '</div>';
    echo '</div>';
}
add_action('woocommerce_share', 'shav_render_kobiety', 23);


// icon with text below price
// Add icon with text fields to product edit page
function add_multiple_icon_text_custom_fields()
{
    echo '<div class="options_group">';

    for ($i = 1; $i <= 3; $i++) {
        woocommerce_wp_text_input(
            array(
                'id' => 'product_icon_url_' . $i,
                'label' => __('Icon URL ' . $i, 'woocommerce'),
                'desc_tip' => 'true',
                'description' => __('Enter the URL for the icon ' . $i . '.', 'woocommerce')
            )
        );

        woocommerce_wp_text_input(
            array(
                'id' => 'product_icon_text_' . $i,
                'label' => __('Icon Text ' . $i, 'woocommerce'),
                'desc_tip' => 'true',
                'description' => __('Enter the text to display next to the icon ' . $i . '.', 'woocommerce')
            )
        );
    }

    echo '</div>';
}
// add_action( 'woocommerce_product_options_general_product_data', 'add_multiple_icon_text_custom_fields' );

// Save the custom field values
function save_multiple_icon_text_custom_fields($post_id)
{
    $product = wc_get_product($post_id);

    for ($i = 1; $i <= 3; $i++) {
        $icon_url = isset($_POST['product_icon_url_' . $i]) ? $_POST['product_icon_url_' . $i] : '';
        $product->update_meta_data('product_icon_url_' . $i, sanitize_text_field($icon_url));

        $icon_text = isset($_POST['product_icon_text_' . $i]) ? $_POST['product_icon_text_' . $i] : '';
        $product->update_meta_data('product_icon_text_' . $i, sanitize_text_field($icon_text));
    }

    $product->save();
}
// add_action( 'woocommerce_process_product_meta', 'save_multiple_icon_text_custom_fields' );

// Display multiple icons with text below the product price
function display_multiple_icons_text_below_price()
{
    global $post;
    $product = wc_get_product($post->ID);

    echo '<div class="product-icons-container">'; // Open parent container

    for ($i = 1; $i <= 3; $i++) {
        $icon_url = $product->get_meta('product_icon_url_' . $i);
        $icon_text = $product->get_meta('product_icon_text_' . $i);

        if (!empty($icon_url) && !empty($icon_text)) {
            echo '<div class="product-icon-text">';
            echo '<img src="' . esc_url($icon_url) . '" alt="Product Icon ' . $i . '" style="width: 20px; height: auto; vertical-align: middle; margin-right: 10px;" />';
            echo '<span>' . esc_html($icon_text) . '</span>';
            echo '</div>';
        }
    }

    echo '</div>'; // Close parent container
}
// add_action( 'woocommerce_single_product_summary', 'display_multiple_icons_text_below_price', 25 );


// Display multiple icons with text below product image on the left side
// function display_multiple_icons_text_below_image() {
//   global $post;
//   $product = wc_get_product( $post->ID );

//   echo '<div class="product-icon-text-wrapper" style="margin-top: 20px;">';

//   for ($i = 1; $i <= 3; $i++) {
//       $icon_url = $product->get_meta( 'product_icon_url_' . $i );
//       $icon_text = $product->get_meta( 'product_icon_text_' . $i );

//       if ( ! empty( $icon_url ) && ! empty( $icon_text ) ) {
//           echo '<div class="product-icon-text" style="margin-bottom: 10px; text-align: center;">';
//           echo '<img src="' . esc_url( $icon_url ) . '" alt="Product Icon ' . $i . '" style="width: 20px; height: auto; vertical-align: middle; margin-right: 10px;" />';
//           echo '<span>' . esc_html( $icon_text ) . '</span>';
//           echo '</div>';
//       }
//   }

//   echo '</div>';
// }
// add_action( 'woocommerce_product_thumbnails', 'display_multiple_icons_text_below_image', 20 );





// Display subtitle on the single product page
function display_product_subtitle()
{
    global $post;
    $product = wc_get_product($post->ID);
    if (!$product)
        return;
    if (function_exists('shav_is_hidden') && shav_is_hidden($product->get_id(), 'subtitle'))
        return;
    $subtitle = shav_get_field($product->get_id(), 'product_subtitle', 'subtitle');

    if (!empty($subtitle)) {
        echo '<h2 class="product-subtitle">' . esc_html($subtitle) . '</h2>';
    }
}
add_action('woocommerce_single_product_summary', 'display_product_subtitle', 6);

// Display rating pill below the subtitle
function display_shav_product_rating_pill() {
    global $post;
    $product = wc_get_product($post->ID);
    if (!$product)
        return;

    $avatars_val = get_option('shav_rating_avatars', '');
    $avatars = [];
    if (!empty($avatars_val)) {
        $urls = explode(',', $avatars_val);
        foreach ($urls as $url) {
            if (!empty(trim($url))) {
                $avatars[] = trim($url);
            }
        }
    }
    ?>
    <div class="shav-product-rating-pill">
        <?php if (!empty($avatars)) : ?>
            <div class="avatars">
                <?php foreach ($avatars as $av) : ?>
                    <img class="avatars__item" src="<?php echo esc_url($av); ?>" alt="Avatar">
                <?php endforeach; ?>
            </div>
            <span class="rating-sep" aria-hidden="true"></span>
        <?php endif; ?>
        <span class="rating-count">+300K Bewertungen</span>
        <span class="rating-sep" aria-hidden="true"></span>
        <span class="rating-score">4.9</span>
        <span class="rating-stars" aria-label="5 z 5 gwiazdek">
            <?php for ($i = 0; $i < 5; $i++) : ?>
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="#e9bd0b">
                    <path d="M12 17.27 18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                </svg>
            <?php endfor; ?>
        </span>
    </div>
    <?php
}
add_action('woocommerce_single_product_summary', 'display_shav_product_rating_pill', 7);





// Display subtitle shop page
function display_product_title_and_subtitle_shop()
{
    global $post;
    $product = wc_get_product($post->ID);
    $subtitle = $product->get_meta('product_subtitle');
    // Get the product permalink (URL to the single product page)
    $product_permalink = get_permalink($product->get_id());

    echo '<div class="product-title-subtitle-wrapper">';
    // Wrap the image in a link to the product page
    echo '<a href="' . esc_url($product_permalink) . '" style="text-decoration: none;">';
    // Output the product title
    echo '<h2 class="woocommerce-loop-product__title">';
    echo get_the_title();
    echo '</h2>';

    // Output the product subtitle, if it exists
    if (!empty($subtitle)) {
        echo '<h3 class="product-subtitle-shop">' . esc_html($subtitle) . '</h3>';
    }
    echo '</a>';
    echo '</div>';
}



// short description title

// pasek magazynowyAdd commentMore actions
// Add custom fields to product edit page
// Wyświetlanie paska magazynowego z globalnych ustawień (Kokpit)
function display_percentage_strip()
{
    global $post;
    if (!$post)
        return;

    $product = wc_get_product($post->ID);
    if (!$product)
        return;
    $pid = $product->get_id();

    // 1. Sprawdź globalny toggle (domyślnie wyłączony)
    $is_enabled = (get_option('shav_stock_strip_enabled', 'no') === 'yes');

    // 2. Pobierz globalne defaults
    $mode = get_option('shav_stock_strip_mode', 'auto');
    $percentage = get_option('shav_stock_strip_percent', 80);
    $max_stock = get_option('shav_stock_strip_max_stock', 50);
    $text_template = get_option('shav_stock_strip_text', 'Nur noch {stock} Stück auf Lager! – Schon {percent}% verkauft!');

    // 3. Ewaluacja reguł
    $rules_json = trim(get_option('shav_stock_strips_json', '[]'));
    if (!empty($rules_json) && $rules_json !== '[]') {
        if (strpos($rules_json, '[') === 0) {
            $rules = json_decode($rules_json, true);
        } else {
            $rules = json_decode(base64_decode($rules_json), true);
        }
    } else {
        $rules = [];
    }

    if (is_array($rules) && !empty($rules)) {
        $product_cats = wp_get_post_terms($pid, 'product_cat', ['fields' => 'ids']);
        if (!is_array($product_cats))
            $product_cats = [];

        foreach ($rules as $rule) {
            $match = false;
            if (isset($rule['type'])) {
                if ($rule['type'] === 'global') {
                    $match = true;
                } elseif ($rule['type'] === 'categories') {
                    $cats = isset($rule['categories']) ? (array) $rule['categories'] : [];
                    if (!empty(array_intersect($product_cats, $cats))) {
                        $match = true;
                    }
                } elseif ($rule['type'] === 'products') {
                    $prods = isset($rule['products']) ? (array) $rule['products'] : [];
                    foreach ($prods as $p) {
                        if (isset($p['id']) && (int) $p['id'] === $pid) {
                            $match = true;
                            break;
                        }
                    }
                }
            }

            if ($match) {
                $is_enabled = true; // Reguła wymusza włączenie paska, nawet jeśli globalnie jest wyłączony
                if (!empty($rule['mode']))
                    $mode = $rule['mode'];
                if (isset($rule['percent']) && $rule['percent'] !== '')
                    $percentage = $rule['percent'];
                if (isset($rule['max_stock']) && $rule['max_stock'] !== '')
                    $max_stock = $rule['max_stock'];
                if (!empty($rule['text']))
                    $text_template = $rule['text'];
                break; // Stop at first matched rule
            }
        }
    }

    // Jeśli globalnie wyłączony i żadna reguła go nie włącza, przerywamy
    if (!$is_enabled) {
        return;
    }

    // 4. Wyliczenia rzeczywistego stanu magazynowego
    $stock_qty = 0;
    if ($product->managing_stock()) {
        $current_stock = $product->get_stock_quantity();
        if ($current_stock !== null) {
            $stock_qty = $current_stock;
        }
    }

    if ($mode === 'auto' && $product->managing_stock() && $current_stock !== null) {
        if ($max_stock > 0) {
            // Wyliczamy prawdziwy procent względem ustawionego limitu bazowego (pozostały stan)
            $percentage = floor(($current_stock / $max_stock) * 100);
            if ($percentage > 100)
                $percentage = 100;
        } else {
            // Brak ustawionego limitu, przełączamy na bezpieczny fallback żeby pasek się wyświetlił
            $percentage = 100;
        }
    }

    // Walidacja i renderowanie
    if (is_numeric($percentage)) {
        $percentage_clamped = min(max(intval($percentage), 0), 100);
        $final_text = str_replace(['{percent}', '{stock}'], [$percentage_clamped, $stock_qty], $text_template);

        echo '<div class="shav-stock">';
        echo '<div class="shav-stock__icon">';
        echo '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none">';
        echo '<path d="M3 9V20a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1V9M3 9l2-6h14l2 6M3 9h18M8 13h8" stroke="#3F3F3F" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>';
        echo '</svg>';
        echo '</div>';
        echo '<div class="shav-stock__content">';
        echo '<p class="shav-stock__label">' . esc_html($final_text) . '</p>';
        echo '<div class="shav-stock__bar"><div class="shav-stock__bar-fill" style="width: ' . esc_attr($percentage_clamped) . '%"></div></div>';
        echo '</div>';
        echo '</div>';

    }
}
add_action('woocommerce_share', 'display_percentage_strip', 20);

// AJAX: Pobieranie obecnego stanu magazynowego produktu dla auto-uzupełniania w kokpicie
function shav_get_product_stock_ajax()
{
    if (!current_user_can('manage_options') || !isset($_POST['product_id'])) {
        wp_send_json_error();
    }

    $product_id = intval($_POST['product_id']);
    $product = wc_get_product($product_id);

    if ($product && $product->managing_stock()) {
        $stock = $product->get_stock_quantity();
        if ($stock !== null) {
            wp_send_json_success(['stock' => $stock]);
        }
    }
    wp_send_json_error(['message' => 'Brak stanu magazynowego']);
}
add_action('wp_ajax_shav_get_product_stock', 'shav_get_product_stock_ajax');

// Add custom banner field to product edit page
function add_konkurs_banner_field()
{
    echo '<div class="options_group">';

    // Banner Image URL Field
    woocommerce_wp_text_input(
        array(
            'id' => 'custom_banner_image_konkurs',
            'label' => __('Banner Image Konkurs', 'woocommerce'),
            'desc_tip' => 'true',
            'description' => __('Enter the URL of the banner image to display under Add to Cart.', 'woocommerce')
        )
    );

    echo '</div>';
}
add_action('woocommerce_product_options_general_product_data', 'add_konkurs_banner_field');

// Save the custom banner field value
function save_konkurs_banner_field($post_id)
{
    $product = wc_get_product($post_id);

    // Save banner image URL
    $banner_image_url = isset($_POST['custom_banner_image_konkurs']) ? $_POST['custom_banner_image_konkurs'] : '';
    $product->update_meta_data('custom_banner_image_konkurs', esc_url_raw($banner_image_url));

    $product->save();
}
add_action('woocommerce_process_product_meta', 'save_konkurs_banner_field');

// Display the banner image under the Add to Cart button on the product page
function display_konkurs_banner_image()
{
    global $post;
    $product = wc_get_product($post->ID);
    if (!$product)
        return;

    $banner_image_url = shav_get_field($product->get_id(), 'custom_banner_image_konkurs', 'cart_banner');

    if (!empty($banner_image_url)) {
        echo '<div class="custom-banner-image-container" style="margin-top: 20px; text-align: center;">';
        echo '<img src="' . esc_url($banner_image_url) . '" alt="' . esc_attr__('Product Banner', 'woocommerce') . '" style="width: 100%; max-width: 500px; height: auto; border-radius: 10px;">';

        // Display the fixed link below the banner
        // echo '<p style="font-size: 10px; margin-top: 5px; color: #000">Kup golarkę, podczas trwania promocji aby wziąć udział w konkursie. <a href="https://shav.pl/wp-content/uploads/Regulamin-konkursu-Shav-Swieta-2025.docx.pdf" target="_blank"> Regulamin konkursu</a></p>';

        echo '</div>';
    }
}
add_action('woocommerce_share', 'display_konkurs_banner_image', 23);



// SHOP IMAGE - different product images
// SHOP TOP IMAGE WITH TEXT


// Set your specific product ID
$product_id_with_banner = 68; // Replace 123 with the actual product ID you want to use


function add_shop_page_banner_image_fields()
{
    global $post, $product_id_with_banner;


    if ($post->ID != $product_id_with_banner) {
        return; // Pokazujemy pola tylko dla określonego produktu
    }

    // Pole dla baneru desktopowego
    woocommerce_wp_text_input(
        array(
            'id' => 'shop_page_banner_image_desktop',
            'label' => __('Shop Page Banner Image Desktop', 'woocommerce'),
            'desc_tip' => 'true',
            'description' => __('Wprowadź URL obrazka baneru do wyświetlania na desktopie.', 'woocommerce'),
            'type' => 'text'
        )
    );

    // Pole dla baneru mobilnego
    woocommerce_wp_text_input(
        array(
            'id' => 'shop_page_banner_image_mobile',
            'label' => __('Shop Page Banner Image Mobile', 'woocommerce'),
            'desc_tip' => 'true',
            'description' => __('Wprowadź URL obrazka baneru do wyświetlania na urządzeniach mobilnych.', 'woocommerce'),
            'type' => 'text'
        )
    );
}

add_action('woocommerce_product_options_general_product_data', 'add_shop_page_banner_image_fields');

function save_shop_page_banner_image_fields($post_id)
{
    global $product_id_with_banner;

    if ($post_id != $product_id_with_banner) {
        return; // Zapisujemy dane tylko dla określonego produktu
    }

    $product = wc_get_product($post_id);

    if (isset($_POST['shop_page_banner_image_desktop'])) {
        $desktop_banner_url = esc_url_raw($_POST['shop_page_banner_image_desktop']);
        $product->update_meta_data('shop_page_banner_image_desktop', $desktop_banner_url);
    }

    if (isset($_POST['shop_page_banner_image_mobile'])) {
        $mobile_banner_url = esc_url_raw($_POST['shop_page_banner_image_mobile']);
        $product->update_meta_data('shop_page_banner_image_mobile', $mobile_banner_url);
    }

    $product->save();
}

add_action('woocommerce_process_product_meta', 'save_shop_page_banner_image_fields');



////////
function add_product_shop_image()
{
    woocommerce_wp_text_input(
        array(
            'id' => 'product_shop_image',
            'label' => __('Shop Image URL', 'woocommerce'),
            'desc_tip' => 'true',
            'description' => __('Enter the URL of the image to be used on the shop page.', 'woocommerce'),
            'type' => 'text'
        )
    );
}
add_action('woocommerce_product_options_general_product_data', 'add_product_shop_image');

function save_product_shop_image($post_id)
{
    $product = wc_get_product($post_id);
    $shop_image_url = isset($_POST['product_shop_image']) ? $_POST['product_shop_image'] : '';
    $product->update_meta_data('product_shop_image', esc_url_raw($shop_image_url));
    $product->save();
}
add_action('woocommerce_process_product_meta', 'save_product_shop_image');

function display_custom_shop_image()
{
    global $product;

    $shop_image_url = $product->get_meta('product_shop_image');

    // Get the product permalink (URL to the single product page)
    $product_permalink = get_permalink($product->get_id());

    if (!empty($shop_image_url)) {

        echo '<div class="wc-block-components-product-image-new">';
        // Wrap the image in a link to the product page
        echo '<a href="' . esc_url($product_permalink) . '">';
        echo '<img src="' . esc_url($shop_image_url) . '" class="custom-shop-image" />';
        echo '</a>';
        echo '</div>';
    } else {
        echo '<div class="custom-image-container">';
        // Wrap the default product thumbnail in a link to the product page
        echo '<a href="' . esc_url($product_permalink) . '">';
        woocommerce_template_loop_product_thumbnail();
        echo '</a>';
        echo '</div>';
    }
}
// UWAGA: stare hooki shop loop wylaczone — nowy szablon woocommerce/content-product.php
// renderuje karte produktu samodzielnie (BEM), nie wywoluje do_action('woocommerce_*shop_loop_item*').
// Funkcje display_custom_shop_image i display_product_title_and_subtitle_shop pozostaja w pliku
// na wypadek innego uzycia, ale nie sa juz podpinane.
//
// remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
// add_action('woocommerce_before_shop_loop_item', 'display_custom_shop_image', 10);
// remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10);
// add_action('woocommerce_shop_loop_item_title', 'display_product_title_and_subtitle_shop', 10);







// Add custom fields to product after add to cart
function add_custom_fields()
{
    echo '<div class="options_group">';

    // Image URL field
    woocommerce_wp_text_input(
        array(
            'id' => 'custom_image_url',
            'label' => __('Custom Image URL', 'woocommerce'),
            'desc_tip' => 'true',
            'description' => __('Enter the URL for the custom image.', 'woocommerce')
        )
    );

    // Title field
    woocommerce_wp_text_input(
        array(
            'id' => 'custom_title',
            'label' => __('Custom Title', 'woocommerce'),
            'desc_tip' => 'true',
            'description' => __('Enter the custom title.', 'woocommerce')
        )
    );

    // Description field
    woocommerce_wp_textarea_input(
        array(
            'id' => 'custom_description',
            'label' => __('Custom Description', 'woocommerce'),
            'desc_tip' => 'true',
            'description' => __('Enter the custom description.', 'woocommerce')
        )
    );

    echo '</div>';
}
add_action('woocommerce_product_options_general_product_data', 'add_custom_fields');

// Save the custom field values
function save_custom_fields($post_id)
{
    $product = wc_get_product($post_id);

    // Save image URL
    $image_url = isset($_POST['custom_image_url']) ? $_POST['custom_image_url'] : '';
    $product->update_meta_data('custom_image_url', sanitize_text_field($image_url));

    // Save title
    $title = isset($_POST['custom_title']) ? $_POST['custom_title'] : '';
    $product->update_meta_data('custom_title', sanitize_text_field($title));

    // Save description
    $description = isset($_POST['custom_description']) ? $_POST['custom_description'] : '';
    $product->update_meta_data('custom_description', sanitize_textarea_field($description));

    $product->save();
}
add_action('woocommerce_process_product_meta', 'save_custom_fields');

// Display custom image under the Add to Cart button
function display_custom_image()
{
    global $post;
    $product = wc_get_product($post->ID);

    // Get image URL
    $image_url = $product->get_meta('custom_image_url');

    if (!empty($image_url)) {
        echo '<div class="custom-image-section" style="margin-top: 20px;">';
        echo '<img src="' . esc_url($image_url) . '" alt="Custom Image" style="max-width: 100%; height: auto;" />';
        echo '</div>';
    }
}
add_action('woocommerce_after_add_to_cart_form', 'display_custom_image', 10);

// Display custom title and description under the Add to Cart button
function display_custom_title_description()
{
    global $post;
    $product = wc_get_product($post->ID);

    // Get title and description
    $title = $product->get_meta('custom_title');
    $description = $product->get_meta('custom_description');

    if (!empty($title) || !empty($description)) {
        echo '<div class="custom-title-description-section" style="margin-top: 20px;">';

        if (!empty($title)) {
            echo '<div class="custom-title" style="cursor: pointer; color: #0073aa; text-decoration: underline; margin-bottom: 10px;">';
            echo esc_html($title);
            echo '</div>';
        }

        if (!empty($description)) {
            echo '<div class="custom-description" style="display: none; margin-bottom: 10px;">';
            echo wp_kses_post(wpautop($description));
            echo '</div>';
        }

        echo '</div>';
    }
}
add_action('woocommerce_after_add_to_cart_form', 'display_custom_title_description', 15);





// banner top of single-product page
//desktop
function add_custom_banner_field()
{
    woocommerce_wp_text_input(
        array(
            'id' => 'custom_banner_url',
            'label' => __('Custom Banner Image URL', 'woocommerce'),
            'desc_tip' => 'true',
            'description' => __('Enter the URL for the custom banner image.', 'woocommerce')
        )
    );
}
add_action('woocommerce_product_options_general_product_data', 'add_custom_banner_field');

function save_custom_banner_field($post_id)
{
    $product = wc_get_product($post_id);

    // Save banner image URL
    $banner_url = isset($_POST['custom_banner_url']) ? $_POST['custom_banner_url'] : '';
    $product->update_meta_data('custom_banner_url', sanitize_text_field($banner_url));
    $product->save();
}
add_action('woocommerce_process_product_meta', 'save_custom_banner_field');


// mobile

function add_custom_mobile_banner_field()
{
    woocommerce_wp_text_input(
        array(
            'id' => 'custom_mobile_banner_url',
            'label' => __('Custom Mobile Banner Image URL', 'woocommerce'),
            'desc_tip' => 'true',
            'description' => __('Enter the URL for the custom banner image for mobile view.', 'woocommerce')
        )
    );
}
add_action('woocommerce_product_options_general_product_data', 'add_custom_mobile_banner_field');

function save_custom_mobile_banner_field($post_id)
{
    $product = wc_get_product($post_id);

    // Save mobile banner image URL
    $mobile_banner_url = isset($_POST['custom_mobile_banner_url']) ? $_POST['custom_mobile_banner_url'] : '';
    $product->update_meta_data('custom_mobile_banner_url', sanitize_text_field($mobile_banner_url));
    $product->save();
}
add_action('woocommerce_process_product_meta', 'save_custom_mobile_banner_field');

function display_custom_banner()
{
    global $post;
    $product = wc_get_product($post->ID);
    if (!$product)
        return;
    $pid = $product->get_id();

    $banner_url = shav_get_field($pid, 'custom_banner_url', 'product_top_banner');
    $mobile_banner_url = shav_get_field($pid, 'custom_mobile_banner_url', 'product_top_banner');

    if (!empty($banner_url)) {
        echo '<div class="custom-banner-section" style="width: 100%; text-align: center; margin-bottom: 20px;">';

        // Standard banner
        echo '<img class="custom-banner-desktop" src="' . esc_url($banner_url) . '" alt="Custom Banner" style="width: 100%; height: auto;" />';

        // Mobile banner
        if (!empty($mobile_banner_url)) {
            echo '<img class="custom-banner-mobile" src="' . esc_url($mobile_banner_url) . '" alt="Custom Mobile Banner" style="width: 100%; height: auto;" />';
        }

        echo '</div>';
    }
}
// add_action( 'woocommerce_before_single_product', 'display_custom_banner', 5 );
// add_action( 'woocommerce_before_cart_table', 'display_custom_banner', 5 );

// product banner timer
// Add a new field for the countdown timer
function add_countdown_timer_field()
{
    woocommerce_wp_text_input(
        array(
            'id' => 'countdown_timer_end_date',
            'label' => __('Countdown Timer End Date', 'woocommerce'),
            'desc_tip' => 'true',
            'description' => __('Enter the end date and time for the countdown timer in YYYY-MM-DDTHH:MM format.', 'woocommerce'),
            'type' => 'datetime-local'
        )
    );
}
add_action('woocommerce_product_options_general_product_data', 'add_countdown_timer_field');

// Save the countdown timer end date field
function save_countdown_timer_field($post_id)
{
    $product = wc_get_product($post_id);
    $countdown_end_date = isset($_POST['countdown_timer_end_date']) ? $_POST['countdown_timer_end_date'] : '';
    $product->update_meta_data('countdown_timer_end_date', sanitize_text_field($countdown_end_date));
    $product->save();
}
add_action('woocommerce_process_product_meta', 'save_countdown_timer_field');

// Display custom banner with countdown timer on top of it
function display_custom_banner_with_timer()
{
    global $post;
    $product_id = $post->ID;
    $product = wc_get_product($product_id);

    $banner_url = shav_get_field($product_id, 'custom_banner_url', 'product_top_banner');
    $mobile_banner_url = shav_get_field($product_id, 'custom_mobile_banner_url', 'product_top_banner');
    $countdown_end_date = shav_get_field($product_id, 'countdown_timer_end_date', 'countdown_timer');

    // --- NOWY KOD: Nadpisywanie dla konkretnych ID ---
    $master_product_id = 233781;
    $target_product_ids = array(243159, 243156, 279965, 279955, 244024, 243166);

    if (in_array($product_id, $target_product_ids)) {
        $master_product = wc_get_product($master_product_id);

        if ($master_product) {
            $master_banner_url = $master_product->get_meta('custom_banner_url');

            // Jeśli produkt 233781 ma ustawiony baner, nadpisujemy obecne zmienne
            if (!empty($master_banner_url)) {
                $banner_url = $master_banner_url;
                $mobile_banner_url = $master_product->get_meta('custom_mobile_banner_url');
                $countdown_end_date = $master_product->get_meta('countdown_timer_end_date');
            }
        }
    }
    // --- KONIEC NOWEGO KODU ---

    // Jeśli baner nie jest ustawiony, pobierz domyślny z produktu o ID 68
    // (guard: produkt 68 może nie istnieć, np. na lokalnym devie)
    $default_product = wc_get_product(68);

    if (empty($banner_url) && $default_product) {
        $banner_url = $default_product->get_meta('custom_banner_url');
    }

    if (empty($mobile_banner_url) && $default_product) {
        $mobile_banner_url = $default_product->get_meta('custom_mobile_banner_url');
    }

    // Jeśli licznik nie jest ustawiony, pobierz domyślny z produktu o ID 68
    if (empty($countdown_end_date) && $default_product) {
        $countdown_end_date = $default_product->get_meta('countdown_timer_end_date');
    }

    if (!empty($banner_url)) {
        echo '<div class="custom-banner-section" style="position: relative; width: 100%; text-align: center; margin-bottom: 20px;">';

        // Standard banner with timer
        echo '<img class="custom-banner-desktop" src="' . esc_url($banner_url) . '" alt="Custom Banner" style="width: 100%; height: auto;" />';

        // Countdown timer on the banner
        if (!empty($countdown_end_date)) {
            echo '<div class="countdown-timer" style="position: absolute; top: 60%; left: 49%; transform: translate(-50%, -10%); background-color: transparent; color: red; padding: 10px; border-radius: 5px;">';
            echo '<p id="countdown"></p>';
            echo '</div>';

            echo '<script>
            var countdownDate = new Date("' . esc_js($countdown_end_date) . '").getTime(); 
            var countdownElement = document.getElementById("countdown");

                var x = setInterval(function() {
                    var now = new Date().getTime();
                    var distance = countdownDate - now;

                    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    countdownElement.innerHTML = 
                        "<span class=\'countdown-wrapper\'><span class=\'countdown-number\'>" + days + "</span><span class=\'countdown-label\'>dni</span></span> " + 
                        "<span class=\'countdown-wrapper\'><span class=\'countdown-number\'>" + hours + "</span><span class=\'countdown-label\'>godziny</span></span> " + 
                        "<span class=\'countdown-wrapper\'><span class=\'countdown-number\'>" + minutes + "</span><span class=\'countdown-label\'>minuty</span></span> " + 
                        "<span class=\'countdown-wrapper\'><span class=\'countdown-number\'>" + seconds + "</span><span class=\'countdown-label\'>sekundy</span></span>";

                    if (distance < 0) {
                        clearInterval(x);
                        countdownElement.innerHTML = "Promocja wkrótce się kończy";
                    }
                }, 1000);
            </script>';
        }

        // Mobile banner with timer
        if (!empty($mobile_banner_url)) {
            echo '<img class="custom-banner-mobile" src="' . esc_url($mobile_banner_url) . '" alt="Custom Mobile Banner" style="width: 100%; height: auto;" />';

            if (!empty($countdown_end_date)) {
                echo '<div class="countdown-timer-mobile" style="position: absolute; top: 56%; left: 50%; transform: translate(-50%, -10%); background-color: transparent; color: red; padding: 10px; border-radius: 5px;">';
                echo '<p id="countdown-mobile"></p>';
                echo '</div>';

                echo '<script>
                var countdownDateMobile = new Date("' . esc_js($countdown_end_date) . '").getTime(); 
                    var countdownMobileElement = document.getElementById("countdown-mobile");

                    var y = setInterval(function() {
                        var now = new Date().getTime();
                        var distance = countdownDateMobile - now;

                        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                        countdownMobileElement.innerHTML = 
                            "<span class=\'countdown-wrapper\'><span class=\'countdown-number\'>" + days + "</span><span class=\'countdown-label\'>dni</span></span> " + 
                            "<span class=\'countdown-wrapper\'><span class=\'countdown-number\'>" + hours + "</span><span class=\'countdown-label\'>godziny</span></span> " + 
                            "<span class=\'countdown-wrapper\'><span class=\'countdown-number\'>" + minutes + "</span><span class=\'countdown-label\'>minuty</span></span> " + 
                            "<span class=\'countdown-wrapper\'><span class=\'countdown-number\'>" + seconds + "</span><span class=\'countdown-label\'>sekundy</span></span>";

                        if (distance < 0) {
                            clearInterval(y);
                            countdownMobileElement.innerHTML = "Promocja wkrótce się kończy";
                        }
                    }, 1000);
                </script>';
            }
        }

        echo '</div>';
    }
}
add_action('woocommerce_before_single_product', 'display_custom_banner_with_timer', 5);

// // test customizing single product description
// function display_reusable_block_after_summary() {
//   // Replace 123 with your reusable block ID
//   // cechy
//   $specific_product_id = 49;
//   $block_id = 64587;
//   $block_id2 = 64590;
//   $block_id3 = 64597;
//   $block_id4 = 64599;
//   $block_id5 = 64602;

//   // inne
//   $block_maszynka_funkcje = 64612;
//   $block_stacja_dokujaca = 64618;

// Check if we are on the specific product page
// if ( is_product() && get_the_ID() == $specific_product_id ) {
// Get the reusable block content
// $block = get_post($block_id);
// $block2 = get_post($block_id2);
// $block3 = get_post($block_id3);
// $block4 = get_post($block_id4);
// $block5 = get_post($block_id5);
// $blockmaszynka = get_post($block_maszynka_funkcje);
// $blockstacjadokujaca = get_post($block_stacja_dokujaca);
// if ($block && 'wp_block' === $block->post_type) {
//     echo apply_filters('the_content', $block->post_content);
// }
// if ($block2 && 'wp_block' === $block2 -> post_type) {
//   echo apply_filters('the_content', $block2 -> post_content);
// }
// if ($block5 && 'wp_block' === $block5 -> post_type) {
//   echo apply_filters('the_content', $block5 -> post_content);
// }
// if ($block3 && 'wp_block' === $block3 -> post_type) {
//   echo apply_filters('the_content', $block3 -> post_content);
// }
// if ($block4 && 'wp_block' === $block4 -> post_type) {
//   echo apply_filters('the_content', $block4 -> post_content);
// }
// if ($blockmaszynka && 'wp_block' === $blockmaszynka -> post_type) {
//   echo apply_filters('the_content', $blockmaszynka -> post_content);
// }

//   }
// }
// add_action('woocommerce_after_single_product_summary', 'display_reusable_block_after_summary', 5);



function display_custom_block_under_guttenberg_elements()
{
    $specific_product_id = 68; // The ID of the specific product
    if (is_product() && get_the_ID() == $specific_product_id) {
        // include __DIR__ . '/build/stacjadokujaca/render.php';


    }
}

add_action('woocommerce_after_single_product_summary', 'display_custom_block_under_guttenberg_elements', 5);


// // change position of price
// // Remove the price from its default position and add to cart
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
// for now
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);
add_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 3);


// remove_action('woocommerce_cart_coupon', 'woocommerce_template_coupon_code', 10);
// add_action('woocommerce_custom_location', 'woocommerce_template_coupon_code');

// Custom function to display the price and add to cart button within a parent container
// Pill "OSZCZĘDZASZ X ZŁ" — zielony, liczony z (regular - sale)
function shav_render_savings_pill()
{
    global $product;
    if (!$product)
        return '';
    $pid = $product->get_id();

    if (function_exists('shav_is_hidden') && shav_is_hidden($pid, 'savings_pill'))
        return '';

    // 1. Niestandardowy tekst (jeśli wpisany) — ma priorytet
    $custom = function_exists('shav_get_field')
        ? shav_get_field($pid, 'savings_pill_custom_text', 'savings_pill')
        : '';
    if (!empty($custom)) {
        return '<span class="product-summary__savings-pill">' . esc_html($custom) . '</span>';
    }

    // 2. Auto z (regular - sale)
    if (!$product->is_on_sale())
        return '';
    if ($product->is_type('variable')) {
        // produkt wielowariantowy nie ma wlasnych cen — bierzemy min z wariacji
        $regular = (float) $product->get_variation_regular_price('min');
        $sale = (float) $product->get_variation_sale_price('min');
    } else {
        $regular = (float) $product->get_regular_price();
        $sale = (float) $product->get_sale_price();
    }
    if ($regular <= 0 || $sale <= 0 || $sale >= $regular)
        return '';
    $diff = $regular - $sale;
    $formatted = number_format($diff, 2, ',', ' ');
    $savings_prefix = function_exists('blendygo_get_label') ? blendygo_get_label('savings_prefix') : 'OSZCZĘDZASZ ';
    $cur = function_exists('get_woocommerce_currency_symbol') ? ' ' . get_woocommerce_currency_symbol() : ' ZŁ';
    return '<span class="product-summary__savings-pill">' . esc_html($savings_prefix) . esc_html($formatted) . esc_html($cur) . '</span>';
}

function custom_price_add_to_cart()
{
    global $product;

    echo '<div class="custom-price-add-to-cart">';
    woocommerce_template_single_price();
    echo shav_render_savings_pill();
    display_lowest_price_30_days();
    woocommerce_template_single_rating();
    woocommerce_template_single_add_to_cart();
    echo '</div>';
}


// Add the price below the short description
add_action('woocommerce_single_product_summary', 'custom_price_add_to_cart', 25);


// quantity button is missing. Added it by this line of code:


/// cart 
// function custom_cart_collaterals_content() {

//   ?#>

//   	<div class="cart-actions">
// 			<form class="woocommerce-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?#>" method="post">
// 				<?#php if (wc_coupons_enabled()) { ?#>
// 					<div class="coupon">
// 						<label for="coupon_code" class="screen-reader-text">Kupon:</label> 
// 						<input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="Kod kuponu"> 
// 						<button type="submit" class="button wp-element-button" name="apply_coupon" value="Wykorzystaj kupon">Wykorzystaj kupon</button>
// 					</div>
// 				<?php } ?#>

// 				<button type="submit" class="button wp-element-button" name="update_cart" value="Zaktualizuj koszyk">Zaktualizuj koszyk</button>

// 				<?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?#>
// 			</form>
// 		</div>
//   <?php
// }
// add_action('woocommerce_cart_totals_before_order_total', 'custom_cart_collaterals_content', 20);


// cart image under go to checkout button
function display_cart_custom_image()
{
    // Assuming you want to display the custom image of the first product in the cart
    $cart_items = WC()->cart->get_cart();

    if (empty($cart_items)) {
        return;
    }

    // Get the first product in the cart
    $first_cart_item = reset($cart_items);
    $product_id = $first_cart_item['product_id'];
    $product = wc_get_product($product_id);
    if (!$product)
        return;

    $image_url = shav_get_field($product_id, 'custom_image_url', 'cart_banner');

    if (!empty($image_url)) {
        echo '<div class="custom-image-section-cart" style="margin-top: 20px;">';
        echo '<img src="' . esc_url($image_url) . '" alt="Custom Image" style="max-width: 100%; height: auto;" />';
        echo '</div>';
    }
}
add_action('woocommerce_proceed_to_checkout', 'display_cart_custom_image', 20);



// CART

function custom_cart_breadcrumbs()
{
    // Custom breadcrumb trail
    $breadcrumb = array(
        'home' => array(
            'title' => __('Warenkorb', 'your-text-domain'),
            'url' => wc_get_cart_url(),
        ),
        'moje-dane' => array(
            'title' => __('Meine Daten', 'your-text-domain'),
            'url' => '#', // Replace '#' with the actual URL if you have one, otherwise leave it as is for a non-clickable breadcrumb.
        ),
        'wysylka' => array(
            'title' => __('Versand', 'your-text-domain'),
            'url' => '#', // Replace '#' with the actual URL if you have one.
        ),
        'platnosc' => array(
            'title' => __('Zahlung', 'your-text-domain'),
            'url' => '#', // Replace '#' with the actual URL if you have one.
        ),
    );

    // Output the custom breadcrumb
    echo '<nav class="woocommerce-breadcrumb">';
    foreach ($breadcrumb as $crumb) {
        echo '<a href="' . esc_url($crumb['url']) . '">' . esc_html($crumb['title']) . '</a>';
        if ($crumb !== end($breadcrumb)) {
            echo ' &gt; '; // Separator between breadcrumbs
        }
    }
    echo '</nav>';
}

add_action('woocommerce_before_cart', 'custom_cart_breadcrumbs', 5);


function custom_woocommerce_breadcrumbs()
{
    return array(
        'delimiter' => ' &gt; ', // Changes the delimiter between breadcrumb items
        'wrap_before' => '<nav class="woocommerce-breadcrumb" itemprop="breadcrumb">', // Changes the opening wrapper tag
        'wrap_after' => '</nav>', // Changes the closing wrapper tag
        'before' => '', // Changes the content before each breadcrumb
        'after' => '', // Changes the content after each breadcrumb
        'home' => _x('Home', 'breadcrumb', 'woocommerce'), // Changes the text for the "Home" breadcrumb
    );
}
add_filter('woocommerce_breadcrumb_defaults', 'custom_woocommerce_breadcrumbs');



////////////
function add_icons_below_checkout_button()
{
    $features_json = get_option('shav_svg_features_json', '[]');
    $features = json_decode($features_json, true);

    if (empty($features) || !is_array($features)) {
        return;
    }

    echo '<div class="cart-icons">';
    foreach ($features as $feature) {
        $title = $feature['title'];
        $icon_type = isset($feature['icon_type']) ? $feature['icon_type'] : 'svg';
        $svg = $feature['svg'];
        $image = isset($feature['image']) ? $feature['image'] : '';
        $link = isset($feature['link']) ? $feature['link'] : '';

        if (!empty($title)) {
            echo '<div class="cart-icon-item-to-checkout">';
            if (!empty($link)) {
                echo '<a href="' . esc_url($link) . '" style="text-decoration: none; display: flex; flex-direction: column; align-items: center; color: inherit;">';
            }

            if ($icon_type === 'image' && !empty($image)) {
                echo '<img src="' . esc_url($image) . '" alt="' . esc_attr($title) . '" style="max-width: 30px; max-height: 23px; object-fit: contain;">';
            } else {
                echo $svg;
            }

            echo '<span>' . esc_html($title) . '</span>';

            if (!empty($link)) {
                echo '</a>';
            }
            echo '</div>';
        }
    }
    echo '</div>';
}
add_action('woocommerce_proceed_to_checkout', 'add_icons_below_checkout_button', 21);



// rating calculator in single page: 

/**
 * Customize product data tabs
 */
// add_filter( 'woocommerce_product_tabs', 'woo_custom_reviews_tab', 98 );
// function woo_custom_reviews_tab( $tabs ) {

//     // Save the original callback function
//     $original_callback = $tabs['reviews']['callback'];

//     // Modify the callback to include your custom content
//     $tabs['reviews']['callback'] = function() use ( $original_callback ) {
//         // Display the default reviews content
//         call_user_func( $original_callback );

//         // Now add your custom content below
//         woo_custom_description_tab_content();
//     };

//     return $tabs;
// }

// function woo_custom_description_tab_content() {
//     echo '<h2>Custom Description</h2>';
//     echo '<p>Here\'s a custom description</p>';
// }

add_filter('woocommerce_product_tabs', 'woo_custom_remove_product_tabs', 98);
function woo_custom_remove_product_tabs($tabs)
{
    if (isset($tabs['reviews'])) {
        unset($tabs['reviews']);
    }

    if (isset($tabs['additional_information'])) {
        unset($tabs['additional_information']);
    }

    if (isset($tabs['description'])) {
        unset($tabs['description']);
    }

    return $tabs;
}

function custom_review_summary()
{
    global $product;

    // // Check if the product exists and reviews are enabled
    if (!$product || !comments_open()) {
        echo '<p>Produkt lub opinie niedostępne.</p>';
        return;
    }

    // // Get basic product review data
    $average = $product->get_average_rating();
    $rating_count = $product->get_rating_count();
    $review_count = $product->get_review_count();

    // // Check if data is available
    if (!$rating_count || !$review_count) {
        echo '<p>Nie znaleziono opinii.</p>';
        return;
    }

    // Avoid division by zero for rating percentages
    $rating_count = $rating_count > 0 ? $rating_count : 1;

    // Query to get the count of each rating
    $args = array(
        'post_id' => $product->get_id(),
        'type' => 'review',
        'status' => 'approve',
        'meta_query' => array(
            array(
                'key' => 'rating',
                'value' => array(1, 2, 3, 4, 5),
                'compare' => 'IN'
            )
        )
    );

    $comments = get_comments($args);
    $ratings = array(1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0);

    foreach ($comments as $comment) {
        $rating = intval(get_comment_meta($comment->comment_ID, 'rating', true));
        if ($rating > 0 && $rating <= 5) {
            $ratings[$rating]++;
        }
    }

    // HTML output for the review summary
    ?>
    <div class="review-summary">
        <div class="average-rating">
            <div class="rating-number-container">
                <span class="rating-number"><?php echo esc_html(number_format($average, 1, ',', '')); ?></span><span
                    class="rating-total">/5,0</span>
            </div>
            <div class="rating-count">
                <?php printf(__('%d Kundenbewertungen', 'woocommerce'), esc_html($review_count)); ?>
            </div>
            <div class="rating-verified">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                Verifizierte Käufe
            </div>
        </div>

        <div class="rating-breakdown">
            <?php
            // Reverse the order of the $ratings array
            krsort($ratings);
            ?>

            <?php foreach ($ratings as $stars => $count): ?>
                <div class="rating-bar">
                    <span class="bar-star-num"><?php echo esc_html($stars); ?></span>
                    <div class="star-rating" role="img"
                        aria-label="<?php printf(__('Rated %d out of 5', 'woocommerce'), $stars); ?>">
                        <span style="width:<?php echo esc_attr(($stars / 5) * 100); ?>%"></span>
                    </div>
                    <div class="bar">
                        <span style="width: <?php echo esc_attr(($count / $rating_count) * 100); ?>%"></span>
                    </div>
                    <span class="bar-count-rating"><?php echo esc_html($count); ?></span>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="review-action">
            <a href="#review_form_wrapper" class="woocommerce-review-link btn-add-review">Bewertung schreiben +</a>
        </div>
    </div>
    <?php
}



// baner i timer (pasek u gory strony)
// Dodanie sekcji ustawień globalnych w WordPressie
function add_promo_settings_page()
{
    add_options_page(
        'Promo Bar Settings',
        'Promo Bar',
        'manage_options',
        'promo-bar-settings',
        'render_promo_settings_page'
    );
}
add_action('admin_menu', 'add_promo_settings_page');

function render_promo_settings_page()
{
    if (isset($_POST['save_promo_settings'])) {
        update_option('global_promo_text', sanitize_text_field($_POST['global_promo_text']));
        update_option('global_promo_timer_end_date', sanitize_text_field($_POST['global_promo_timer_end_date']));
        echo '<div class="updated"><p>Settings saved!</p></div>';
    }

    $promo_text = get_option('global_promo_text', '');
    $promo_timer_end_date = get_option('global_promo_timer_end_date', '');

    ?>
    <div class="wrap">
        <h1>Promo Bar Settings</h1>
        <form method="post">
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Promo Text</th>
                    <td>
                        <input type="text" name="global_promo_text" value="<?php echo esc_attr($promo_text); ?>"
                            class="regular-text" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Promo Timer End Date</th>
                    <td>
                        <input type="datetime-local" name="global_promo_timer_end_date"
                            value="<?php echo esc_attr($promo_timer_end_date); ?>" class="regular-text" />
                    </td>
                </tr>
            </table>
            <?php submit_button('Save Settings', 'primary', 'save_promo_settings'); ?>
        </form>
    </div>
    <?php
}

// Wyświetlanie paska na każdej stronie
function display_global_promo_bar()
{
    $promo_text = get_option('global_promo_text', '');
    $promo_timer_end_date = get_option('global_promo_timer_end_date', '');

    if (!empty($promo_text)) {
        echo '<div class="container promo" style="z-index: 9999;">';
        echo '<p class="promo-text">' . esc_html($promo_text);

        // Wyświetlenie linku
        echo ' <a class="promo-tetxt-link" href="' . esc_url(site_url('/golarka-damska-shav-woman/')) . '" style=""> KUP TERAZ </a>';


        if (!empty($promo_timer_end_date)) {
            echo ' <span style="font-weight: normal; margin-left: 5px; margin-right: 5px;">| Pozostało jeszcze:</span>';
            echo ' <span id="promo-countdown" style="font-weight: bold;"></span>';
            echo '<script>
                var promoCountdownDate = new Date("' . esc_js($promo_timer_end_date) . '").getTime();
                var promoCountdownElement = document.getElementById("promo-countdown");

                var interval = setInterval(function() {
                    var now = new Date().getTime();
                    var distance = promoCountdownDate - now;

                    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    promoCountdownElement.innerHTML = days + "d " + hours + "g " + minutes + "m " + seconds + "s";

                    if (distance < 0) {
                        clearInterval(interval);
                        promoCountdownElement.innerHTML = "Promocja wkrótce się kończy";
                    }
                }, 1000);
            </script>';
        }

        echo '</p></div>';
    }
}
add_action('wp_body_open', 'display_global_promo_bar');
function add_promo_bar_styles()
{
    echo '
     <style>
        /* Specyficzne dla stron produktowych */
        body.single-product .site-header {
            margin-top: 41px; /* Dopasuj wysokość, jeśli header nachodzi na pasek */
        }

        .site-main {
            margin-top: 48px;
        }

        /* to tylko w przzypadku kiedy uzywamy paska do promocji u gory strony */
        .site-header {
        margin-top: 41px;
        }
    </style>
    ';
}
// add_action( 'wp_head', 'add_promo_bar_styles' );



/* slider produktowa */
function custom_enqueue_swiper()
{
    wp_enqueue_style('swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css');
    wp_enqueue_script('swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'custom_enqueue_swiper');




// strona produktowa rozsuwane pointy
// Dodaj pola dla rozsuwanych elementów
// 1. Dodaj pola w panelu admina

function add_accordion_fields()
{
    global $post;

    echo '<div class="options_group">';

    // Checkbox aktywacji
    woocommerce_wp_checkbox(array(
        'id' => 'show_accordion',
        'label' => __('Pokaż rozsuwane elementy', 'woocommerce'),
        'value' => 'yes',
        'custom_attributes' => array('checked' => 'checked') // Domyślnie aktywny
    ));

    // Element 1 - Dostawa
    woocommerce_wp_text_input(array(
        'id' => 'accordion_title_1',
        'label' => __('Nagłówek 1', 'woocommerce'),
        'value' => 'Darmowa dostawa w 48h',
        'wrapper_class' => 'show_if_enabled'
    ));

    woocommerce_wp_textarea_input(array(
        'id' => 'accordion_svg_1',
        'label' => __('SVG dla Nagłówka 1', 'woocommerce'),
        'description' => __('Wklej kod SVG dla pierwszego nagłówka', 'woocommerce'),
        'wrapper_class' => 'show_if_enabled'
    ));

    woocommerce_wp_textarea_input(array(
        'id' => 'accordion_content_1',
        'label' => __('Treść 1', 'woocommerce'),
        'description' => __('Tekst który się rozwinie', 'woocommerce'),
        'wrapper_class' => 'show_if_enabled'
    ));

    // Element 2 - Zwrot
    woocommerce_wp_text_input(array(
        'id' => 'accordion_title_2',
        'label' => __('Nagłówek 2', 'woocommerce'),
        'value' => '30 dni na zwrot',
        'wrapper_class' => 'show_if_enabled'
    ));

    woocommerce_wp_textarea_input(array(
        'id' => 'accordion_svg_2',
        'label' => __('SVG dla Nagłówka 2', 'woocommerce'),
        'description' => __('Wklej kod SVG dla drugiego nagłówka', 'woocommerce'),
        'wrapper_class' => 'show_if_enabled'
    ));

    woocommerce_wp_textarea_input(array(
        'id' => 'accordion_content_2',
        'label' => __('Treść 2', 'woocommerce'),
        'wrapper_class' => 'show_if_enabled'
    ));

    // Element 3 - Gwarancja
    woocommerce_wp_text_input(array(
        'id' => 'accordion_title_3',
        'label' => __('Nagłówek 3', 'woocommerce'),
        'value' => '2 lata gwarancji',
        'wrapper_class' => 'show_if_enabled'
    ));

    woocommerce_wp_textarea_input(array(
        'id' => 'accordion_svg_3',
        'label' => __('SVG dla Nagłówka 3', 'woocommerce'),
        'description' => __('Wklej kod SVG dla trzeciego nagłówka', 'woocommerce'),
        'wrapper_class' => 'show_if_enabled'
    ));

    woocommerce_wp_textarea_input(array(
        'id' => 'accordion_content_3',
        'label' => __('Treść 3', 'woocommerce'),
        'wrapper_class' => 'show_if_enabled'
    ));

    echo '</div>';
}
add_action('woocommerce_product_options_general_product_data', 'add_accordion_fields');

// 2. Zapisz wartości pól
function save_accordion_fields($post_id)
{
    $product = wc_get_product($post_id);

    // Domyślna aktywacja dla nowych produktów
    if (!$product->meta_exists('show_accordion')) {
        $product->update_meta_data('show_accordion', 'yes');
    }

    // Aktualizacja checkboxa
    $show_accordion = isset($_POST['show_accordion']) ? 'yes' : 'no';
    $product->update_meta_data('show_accordion', $show_accordion);

    // Zapisz pozostałe pola
    $fields = [
        'accordion_title_1',
        'accordion_svg_1',
        'accordion_content_1',
        'accordion_title_2',
        'accordion_svg_2',
        'accordion_content_2',
        'accordion_title_3',
        'accordion_svg_3',
        'accordion_content_3'
    ];

    // Whitelist tagow SVG (wp_kses_post wycina <svg> domyslnie)
    $svg_allowed = [
        'svg' => ['xmlns' => 1, 'viewbox' => 1, 'width' => 1, 'height' => 1, 'fill' => 1, 'stroke' => 1, 'class' => 1, 'aria-hidden' => 1],
        'path' => ['d' => 1, 'fill' => 1, 'stroke' => 1, 'stroke-width' => 1, 'stroke-linecap' => 1, 'stroke-linejoin' => 1, 'clip-rule' => 1, 'fill-rule' => 1, 'opacity' => 1],
        'g' => ['fill' => 1, 'stroke' => 1, 'transform' => 1, 'clip-path' => 1, 'opacity' => 1],
        'circle' => ['cx' => 1, 'cy' => 1, 'r' => 1, 'fill' => 1, 'stroke' => 1, 'stroke-width' => 1],
        'rect' => ['x' => 1, 'y' => 1, 'width' => 1, 'height' => 1, 'rx' => 1, 'ry' => 1, 'fill' => 1, 'stroke' => 1],
        'polygon' => ['points' => 1, 'fill' => 1, 'stroke' => 1],
        'polyline' => ['points' => 1, 'fill' => 1, 'stroke' => 1],
        'line' => ['x1' => 1, 'y1' => 1, 'x2' => 1, 'y2' => 1, 'stroke' => 1, 'stroke-width' => 1],
        'defs' => [],
        'clippath' => ['id' => 1],
        'use' => ['href' => 1, 'xlink:href' => 1],
    ];

    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            if (strpos($field, 'svg') !== false) {
                $value = wp_kses($_POST[$field], $svg_allowed);
            } else {
                $value = sanitize_text_field($_POST[$field]);
            }
            $product->update_meta_data($field, $value);
        }
    }

    $product->save();
}
add_action('woocommerce_process_product_meta', 'save_accordion_fields');

// 3. Wyświetlanie jako poziomy rząd atutów (Features SVG)
function display_product_accordion()
{
    global $post;

    $product = wc_get_product($post->ID);
    if (!$product)
        return;
    $pid = $product->get_id();

    // Pobieramy atuty jako tablicę JSON
    $features_json = get_option('shav_svg_features_json', '[]');
    $features = json_decode($features_json, true);

    if (empty($features) || !is_array($features)) {
        return;
    }

    echo '<div class="shav-svg-features" style="display: flex; justify-content: space-between; align-items: flex-start; gap: 10px; margin: 20px 0;">';

    foreach ($features as $feature) {
        $title = $feature['title'];
        $icon_type = isset($feature['icon_type']) ? $feature['icon_type'] : 'svg';
        $svg = $feature['svg'];
        $image = isset($feature['image']) ? $feature['image'] : '';
        $link = isset($feature['link']) ? $feature['link'] : '';

        if (!empty($title)) {
            $is_green = stripos($title, 'darmowa') !== false;
            $color_style = $is_green ? 'color: #3b8227;' : 'color: #3F3F3F;';

            echo '<div class="shav-svg-feature-item" style="display: flex; flex-direction: column; align-items: center; text-align: center; flex: 1;">';
            if (!empty($link)) {
                echo '<a href="' . esc_url($link) . '" style="display: flex; flex-direction: column; align-items: center; text-decoration: none;">';
            }

            echo '<span class="shav-svg-feature-icon" style="margin-bottom: 8px; display: flex; align-items: center; justify-content: center; width: 80px; height: 80px; color: #3F3F3F;">';
            if ($icon_type === 'image' && !empty($image)) {
                echo '<img src="' . esc_url($image) . '" alt="' . esc_attr($title) . '" style="max-width: 40px; max-height: 40px; object-fit: contain;">';
            } else {
                echo $svg;
            }
            echo '</span>';

            echo '<span class="shav-svg-feature-title" style="font-size: 12px; line-height: 1.3; font-weight: 600; ' . $color_style . '">' . $title . '</span>';

            if (!empty($link)) {
                echo '</a>';
            }
            echo '</div>';
        }
    }

    echo '</div>';
}
add_action('woocommerce_share', 'display_product_accordion', 18);




// Floating back-to-top — globalny przycisk w prawym dolnym rogu (FSE-safe, dziala na block theme)
function shav_render_back_to_top_button()
{
    ?>
    <button type="button" class="back-to-top" aria-label="Wróć do góry" hidden>
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M12 19V5m-7 7 7-7 7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round" />
        </svg>
    </button>
    <script>
        (function () {
            const btn = document.querySelector('.back-to-top');
            if (!btn) return;
            btn.hidden = false;

            const threshold = Math.max(400, window.innerHeight * 0.4);

            function onScroll() {
                if (window.scrollY > threshold) {
                    btn.classList.add('back-to-top--visible');
                } else {
                    btn.classList.remove('back-to-top--visible');
                }
            }

            window.addEventListener('scroll', onScroll, { passive: true });
            onScroll();

            btn.addEventListener('click', () => {
                if (window.lenis && typeof window.lenis.scrollTo === 'function') {
                    window.lenis.scrollTo(0, { duration: 1.5 });
                } else {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            });
        })();
    </script>
    <?php
}
add_action('wp_footer', 'shav_render_back_to_top_button');

// skrypt zeby dalo sie svg dodac z edycji produktu
function cc_mime_types($mimes)
{
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'cc_mime_types');

function fix_svg()
{
    echo '<style type="text/css">
          .attachment-266x266, .thumbnail img {
               width: 100% !important;
               height: auto !important;
          }
          </style>';
}
add_action('admin_head', 'fix_svg');

// galeria setup
// Disable WooCommerce Zoom, Lightbox, and Gallery Slider



function mytheme_add_woocommerce_support2()
{
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-slider'); // <- TO KONIECZNIE MUSI BYĆ AKTYWNE
    // Pozostałe ustawienia zostaw bez zmian
}
add_action('after_setup_theme', 'mytheme_add_woocommerce_support2');

add_action('after_setup_theme', 'remove_wc_gallery_zoom_lightbox', 99);

function remove_wc_gallery_zoom_lightbox()
{
    remove_theme_support('wc-product-gallery-zoom');
    remove_theme_support('wc-product-gallery-lightbox');
}





/**
 * wylaczenie aktualizacji liczby przy koszyku w headerze bo generuje duze obciazenie
 * Wyłączenie WooCommerce Cart Fragments (AJAX)
 * Naprawia wysokie obciążenie CPU przy dużym ruchu.
 */
add_action('wp_enqueue_scripts', 'disable_wc_cart_fragments', 11);
function disable_wc_cart_fragments()
{
    wp_dequeue_script('wc-cart-fragments');
}
require get_template_directory() . '/inc/licznik-bloga.php';
require get_template_directory() . '/inc/category-image.php';

// Wymuszenie wyświetlania adresu dostawy w mailach WooCommerce (dla paczkomatów na rynku DE)
add_filter('woocommerce_order_needs_shipping_address', '__return_true', 999);
require_once get_theme_file_path('/inc/shav-reviews.php');

// Przepięcie notatek do zamówienia pod dane adresowe
add_filter( 'woocommerce_checkout_fields', 'shav_move_checkout_order_notes', 9999 );
function shav_move_checkout_order_notes( $fields ) {
    if ( isset( $fields['order']['order_comments'] ) ) {
        $fields['billing']['order_comments'] = $fields['order']['order_comments'];
        unset( $fields['order']['order_comments'] );
    }
    return $fields;
}


// Zmiana tekstu "(optional)" w polach formularza na rynku DE
add_filter( 'woocommerce_form_field_optional', 'shav_custom_optional_text', 10, 2 );
function shav_custom_optional_text( $optional_text, $args ) {
    return '&nbsp;<span class="optional">(optional)</span>';
}

// Loga płatności (checkout / buybox)
require_once get_theme_file_path('/inc/shav-payment-logos.php');

// Dynamiczna darmowa wysyłka z poziomu ustawień
require_once get_theme_file_path('/inc/shav-free-shipping.php');

// Helper for FSE blocks to highlight an accent word
if (!function_exists('shav_highlight_accent_word')) {
    function shav_highlight_accent_word($full_text, $accent_word, $accent_class) {
        if (empty($accent_word) || empty($full_text)) {
            return $full_text; // nothing to do
        }
        $pattern = '/\b' . preg_quote(trim($accent_word), '/') . '\b/i';
        
        // Wrap with a temporary marker, then replace to avoid breaking HTML if any
        $replaced = preg_replace_callback($pattern, function($matches) use ($accent_class) {
            return '<span class="' . esc_attr($accent_class) . '">' . $matches[0] . '</span>';
        }, $full_text, 1);
        
        // If not found as a full word, try normal replace (might be punctuation)
        if ($replaced === $full_text) {
             $pattern = '/' . preg_quote(trim($accent_word), '/') . '/i';
             $replaced = preg_replace_callback($pattern, function($matches) use ($accent_class) {
                 return '<span class="' . esc_attr($accent_class) . '">' . $matches[0] . '</span>';
             }, $full_text, 1);
        }
        
        return $replaced;
    }
}
