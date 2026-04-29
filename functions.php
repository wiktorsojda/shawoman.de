<?php


// adobe font babe neue pro
function add_resource_hints_and_fonts() {
  // DNS Prefetch for non-preconnected resources
  echo '<link rel="dns-prefetch" href="//cdnjs.cloudflare.com">';

  // Preconnect for prioritized resources
  echo '<link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>';
  echo '<link rel="preconnect" href="https://use.typekit.net" crossorigin>';

  // Adobe Fonts
  echo '<link rel="stylesheet" href="https://use.typekit.net/opc5tyt.css">';
}
add_action('wp_head', 'add_resource_hints_and_fonts');


// GT4M 
function add_google_tag_manager() {
  ?>
  <!-- Google Tag Manager -->
  <script>
  (function(w,d,s,l,i){
      w[l]=w[l]||[];
      w[l].push({'gtm.start': new Date().getTime(), event:'gtm.js'});
      var f=d.getElementsByTagName(s)[0],
          j=d.createElement(s),
          dl=l!='dataLayer'?'&l='+l:'';
      j.async=true;
      j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;
      f.parentNode.insertBefore(j,f);
  })(window,document,'script','dataLayer','GTM-NSBCTBMK');
  </script>
  <!-- End Google Tag Manager -->
  <?php
}
add_action('wp_head', 'add_google_tag_manager');

// tik tok podpiecie
function add_tiktok_analytics() {
  ?>
  <script>
  !function (w, d, t) {
    w.TiktokAnalyticsObject=t;var ttq=w[t]=w[t]||[];ttq.methods=["page","track","identify","instances","debug","on","off","once","ready","alias","group","enableCookie","disableCookie"],ttq.setAndDefer=function(t,e){t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}};for(var i=0;i<ttq.methods.length;i++)ttq.setAndDefer(ttq,ttq.methods[i]);ttq.instance=function(t){for(var e=ttq._i[t]||[],n=0;n<ttq.methods.length;n++)ttq.setAndDefer(e,ttq.methods[n]);return e},ttq.load=function(e,n){var i="https://analytics.tiktok.com/i18n/pixel/events.js";ttq._i=ttq._i||{},ttq._i[e]=[],ttq._i[e]._u=i,ttq._t=ttq._t||{},ttq._t[e]=+new Date,ttq._o=ttq._o||{},ttq._o[e]=n||{};var o=document.createElement("script");o.type="text/javascript",o.async=!0,o.src=i+"?sdkid="+e+"&lib="+t;var a=document.getElementsByTagName("script")[0];a.parentNode.insertBefore(o,a)};

    ttq.load('CGRA4PJC77U2N7ED4RL0');
    ttq.page();
  }(window, document, 'ttq');
  </script>
  <?php
}
add_action('wp_head', 'add_tiktok_analytics');


// omnibus product page
// Add a custom field to enter the lowest price in the product edit screen
function add_lowest_price_field() {
    echo '<div class="options_group">';

    // Input field for the lowest price
    woocommerce_wp_text_input( 
        array( 
            'id' => 'lowest_price_30_days', 
            'label' => __( 'Najniższa cena z 30 dni przed obniżką:', 'woocommerce' ), 
            'desc_tip' => 'true',
            'description' => __( 'Enter the lowest price from the last 30 days before the discount.', 'woocommerce' )
        )
    );

    echo '</div>';
}
add_action( 'woocommerce_product_options_pricing', 'add_lowest_price_field' );

// Save the custom field data
function save_lowest_price_field( $post_id ) {
    $product = wc_get_product( $post_id );

    // Save the lowest price from the last 30 days
    $lowest_price = isset( $_POST['lowest_price_30_days'] ) ? sanitize_text_field( $_POST['lowest_price_30_days'] ) : '';
    $product->update_meta_data( 'lowest_price_30_days', $lowest_price );

    $product->save();
}
add_action( 'woocommerce_process_product_meta', 'save_lowest_price_field' );


// Display the lowest price (Omnibus) below the product price
function display_lowest_price_30_days() {
    global $product;

    // Get the lowest price from the product meta data
    $lowest_price = $product->get_meta( 'lowest_price_30_days' );

    // Display it only if the lowest price is set
    if ( ! empty( $lowest_price ) ) {
        echo '<p class="lowest-price" style="font-size: 14px; color: #7A7A7A; margin-top: 10px;">';
        echo 'Najniższa cena z 30 dni przed obniżką: ' . esc_html( $lowest_price ) . ' zł';
        echo '</p>';
    }
}
// 


// cart etui za darmo
// add_action('woocommerce_cart_totals_before_shipping', 'add_free_case_to_cart_if_product_49_in_cart');

function add_free_case_to_cart_if_product_49_in_cart() {
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
function is_product_in_cart($product_id) {
    foreach (WC()->cart->get_cart() as $cart_item) {
        if ($cart_item['product_id'] == $product_id) {
            return true;
        }
    }
    return false;
}



// metka strona produktowa
function add_new_custom_promo_fields() {
    echo '<div class="options_group">';

    // New Promo Text field
    woocommerce_wp_text_input( 
        array( 
            'id' => 'new_promo_text', 
            'label' => __( 'New Promo Text', 'woocommerce' ), 
            'desc_tip' => 'true',
            'description' => __( 'Enter the text for the new promotional element.', 'woocommerce' )
        )
    );

    // New Background Image URL field
    woocommerce_wp_text_input( 
        array( 
            'id' => 'new_promo_bg_image', 
            'label' => __( 'New Promo Background Image', 'woocommerce' ), 
            'desc_tip' => 'true',
            'description' => __( 'Enter the URL of the background image for the new promotional element.', 'woocommerce' ) 
        )
    );

    echo '</div>';
}
add_action( 'woocommerce_product_options_general_product_data', 'add_new_custom_promo_fields' );

function save_new_custom_promo_fields( $post_id ) {
    $product = wc_get_product( $post_id );

    // Save new promo text
    $new_promo_text = isset( $_POST['new_promo_text'] ) ? sanitize_text_field( $_POST['new_promo_text'] ) : '';
    $product->update_meta_data( 'new_promo_text', $new_promo_text );

    // Save new background image URL
    $new_promo_bg_image = isset( $_POST['new_promo_bg_image'] ) ? sanitize_text_field( $_POST['new_promo_bg_image'] ) : '';
    $product->update_meta_data( 'new_promo_bg_image', $new_promo_bg_image );

    $product->save();
}
add_action( 'woocommerce_process_product_meta', 'save_new_custom_promo_fields' );

function display_new_promotional_element() {
    global $post;
    $product = wc_get_product( $post->ID );

    // Get new custom fields
    $new_promo_text = $product->get_meta( 'new_promo_text' );
    $new_promo_bg_image = $product->get_meta( 'new_promo_bg_image' );

    // Only display if the new promo text is set
    if ( ! empty( $new_promo_text ) ) {
        // Set default background image or use the provided one
        $new_bg_image = ! empty( $new_promo_bg_image ) ? 'background-image: url(' . esc_url( $new_promo_bg_image ) . ');' : '';

        // echo '<div class="custom-price-add-to-cart" style="position: relative;">'; // Parent div with relative positioning
        echo '<div class="new-promotional-element" style="';
        echo ' ';
        echo '' . $new_bg_image . '">';
        echo '<p style="">';
        echo esc_html( $new_promo_text );
        echo '</p>';
        echo '</div>';
        // echo '</div>';
    }
}
// add_action( 'woocommerce_after_single_product_summary', 'display_new_promotional_element', 15 );


// cart banner
// Set the specific product ID for which you want to add the banner
$product_id_with_banner = 68;

function add_cart_banner_fields() {
    global $post;
    global $product_id_with_banner;

    // Only display the fields for the specified product
    if ( $post->ID != $product_id_with_banner ) {
        return;
    }

    // Add desktop banner image field
    woocommerce_wp_text_input( 
        array( 
            'id' => 'cart_banner_desktop_image', 
            'label' => __( 'Desktop Cart Banner Image', 'woocommerce' ), 
            'desc_tip' => 'true',
            'description' => __( 'Enter the URL of the desktop banner image to display on the cart page.', 'woocommerce' ),
            'type' => 'text'
        )
    );

    // Add mobile banner image field
    woocommerce_wp_text_input( 
        array( 
            'id' => 'cart_banner_mobile_image', 
            'label' => __( 'Mobile Cart Banner Image', 'woocommerce' ), 
            'desc_tip' => 'true',
            'description' => __( 'Enter the URL of the mobile banner image to display on the cart page.', 'woocommerce' ),
            'type' => 'text'
        )
    );
}

add_action( 'woocommerce_product_options_general_product_data', 'add_cart_banner_fields' );

function save_cart_banner_fields( $post_id ) {
    global $product_id_with_banner;

    // Only save for the specified product
    if ( $post_id != $product_id_with_banner ) {
        return;
    }

    $product = wc_get_product( $post_id );

    // Save the desktop banner image URL
    if ( isset( $_POST['cart_banner_desktop_image'] ) ) {
        $desktop_banner_image_url = esc_url_raw( $_POST['cart_banner_desktop_image'] );
        $product->update_meta_data( 'cart_banner_desktop_image', $desktop_banner_image_url );
    }

    // Save the mobile banner image URL
    if ( isset( $_POST['cart_banner_mobile_image'] ) ) {
        $mobile_banner_image_url = esc_url_raw( $_POST['cart_banner_mobile_image'] );
        $product->update_meta_data( 'cart_banner_mobile_image', $mobile_banner_image_url );
    }

    $product->save();
}

add_action( 'woocommerce_process_product_meta', 'save_cart_banner_fields' );


function display_cart_banner_image() {
    global $product_id_with_banner;

        // Sprawdź, czy produkt o ID 49 znajduje się w koszyku
        // if (is_product_in_cart(49) || is_product_in_cart(185780) || is_product_in_cart(209201)) {
            // if (is_product_in_cart(49)) {
    $product = wc_get_product( $product_id_with_banner );
    $desktop_banner_image_url = $product->get_meta( 'cart_banner_desktop_image' );
    $mobile_banner_image_url = $product->get_meta( 'cart_banner_mobile_image' );

    echo '<div class="cart-banner-image">';

    // Display the desktop banner if it's set
    if ( $desktop_banner_image_url ) {
        echo '<img class="desktop-banner" src="' . esc_url( $desktop_banner_image_url ) . '" alt="' . esc_attr__( 'Cart Desktop Banner Image', 'woocommerce' ) . '" style="width: 100%; height: auto; display: none;">';
    }

    // Display the mobile banner if it's set
    if ( $mobile_banner_image_url ) {
        echo '<img class="mobile-banner" src="' . esc_url( $mobile_banner_image_url ) . '" alt="' . esc_attr__( 'Cart Mobile Banner Image', 'woocommerce' ) . '" style="width: 96%; margin: auto; height: auto; display: none;">';
    }

    echo '</div>';
// }
}

add_action( 'woocommerce_before_cart', 'display_cart_banner_image', 1 );

add_action( 'wp_footer', 'switch_banner_based_on_screen_size' );
function switch_banner_based_on_screen_size() {
    ?>
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function() {
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





function add_favicon() {
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


function custom_inline_quantity_update_js() {
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
  wp_add_inline_script( 'jquery', $script );
}
add_action( 'wp_enqueue_scripts', 'custom_inline_quantity_update_js' );

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
function add_cart_popup_html() {
    ?>
    <!-- Cart Popup Structure -->
    <div class="cart-popup-father">
    <div id="cart-popup" class="cart-popup">
        <div class="cart-popup-content">
            <span class="close-cart-popup">&times;</span>
            <div class="cart-popup-header-container">
            <div class="cart-popup-header" >
                <div class="header-promotion">Promocja</div>
                <div class="text-header">zestaw w obniżonej cenie!</div>
                <p>Przy zakupie 3 produktów oszczędzasz 27 zł!</p>
            </div>
            </div>
            <div class="cart-popup-items">
            <?php
              // Array of specific product/variation IDs and their corresponding image IDs
              $specific_variation_images = array(
                248426   => 65126, // Replace with actual variation ID and attachment ID
                248427 => 65125, // Replace with actual variation ID and attachment ID
                15513 => 65034  // Replace with actual variation ID and attachment ID
              );
  
              // Loop through each product/variation
              foreach ($specific_variation_images as $product_id => $custom_image_id) {
                  $product = wc_get_product($product_id);
                  
                  if ($product) {
                      $is_variation = $product->is_type('variation');
                      
                      if ($is_variation) {
                          $parent_id = $product->get_parent_id();
                          $product_url = get_permalink($parent_id);
                      } else {
                          $product_url = get_permalink($product_id);
                      }
                      
                      // Get the image URL from the image ID
                      if ($custom_image_id) {
                          $custom_image_url = wp_get_attachment_image_url($custom_image_id, 'woocommerce_thumbnail');
                      } else {
                          // Fallback to product thumbnail
                          $custom_image_url = wp_get_attachment_image_src(get_post_thumbnail_id($product_id), 'woocommerce_thumbnail')[0];
                      }
                      
                      $product_title = $product->get_name();
                      ?>
                        <div class="cart-popup-product">
                            <a href="<?php echo esc_url($product_url); ?>">
                              <div class="cart-popup-product-container">
                                <div class="cart-popup-product-img">
                                <img src="<?php echo esc_url($custom_image_url); ?>" alt="<?php echo esc_attr($product_title); ?>">
                                </div>
                                <div class="cart-popup-product-rest">
                                <div class="cart-popup-rest-title"><?php echo esc_html($product_title); ?></div>
                                <span class="price"><?php echo $product->get_price_html(); ?></span>
                                
                                <?php if ($is_variation) { ?>
                                    <!-- Form for variation product -->
                                    <form class="cart" method="post" enctype="multipart/form-data">
                                        <input type="hidden" name="add-to-cart" value="<?php echo esc_attr($parent_id); ?>">
                                        <input type="hidden" name="variation_id" value="<?php echo esc_attr($product_id); ?>">
                                        <input type="hidden" name="quantity" value="1">
                                        <?php
                                        // Add variation attributes as hidden fields
                                        $attributes = $product->get_variation_attributes();
                                        foreach ($attributes as $attr_name => $attr_value) {
                                            echo '<input type="hidden" name="' . esc_attr($attr_name) . '" value="' . esc_attr($attr_value) . '">';
                                        }
                                        ?>
                                        <button type="submit" class="single_add_to_cart_button button alt">Dodaj do koszyka</button>
                                    </form>
                                <?php } else { ?>
                                    <!-- Form for simple product -->
                                    <form class="cart" method="post" enctype="multipart/form-data">
                                        <input type="hidden" name="add-to-cart" value="<?php echo esc_attr($product_id); ?>">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="single_add_to_cart_button button alt">Dodaj do koszyka</button>
                                    </form>
                                <?php } ?>
                                
                                </div>
                                </div>
                            </a>
                        </div>
                    <?php
                  }
              }
                ?>
            </div>
            <div class="cart-popup-zestaw">
            <form action="" method="post" class="cart-popup-form">
                  <input type="hidden" name="add-to-cart" value="64967">
                  <button type="submit">Dodaj zestaw za 110 zł</button>
              </form>
            </div>
        </div>
    </div>
    </div>
    <?php
  }
  add_action('wp_footer', 'add_cart_popup_html');
  


// 2. JavaScript Logic (conditionally add this on the cart page only)
function add_cart_popup_js() {
  if (is_cart()) { // Check if it's the cart page
      ?>
      <script type="text/javascript">
          document.addEventListener('DOMContentLoaded', function () {
              const cartPopup = document.getElementById('cart-popup');
              const closePopup = document.querySelector('.close-cart-popup');

              // Function to show the cart popup
              function showCartPopup() {
                  cartPopup.style.display = 'block';

                  // Load the cart contents via AJAX
                  fetchCartItems();

                  // Set a cookie to prevent the popup from showing again
                  document.cookie = "cartPopupShown=true; path=/";
              }

              // Function to close the cart popup
              function closeCartPopup() {
                  cartPopup.style.display = 'none';
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
                      success: function(response) {
                          callback(response === 'true');
                      }
                  });
              }

             // IDs produktów do sprawdzenia
             const productIds = [49, 209201]; // Tutaj dodaj wszystkie potrzebne ID

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
                            setTimeout(showCartPopup, 2000);
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
function check_product_in_cart() {
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
function pageBanner($args = NULL) {
  
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

function university_files() {
  wp_enqueue_script('main-university-js', get_theme_file_uri('/build/index.js'), array('jquery'), '1.0', true);
  wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
  wp_enqueue_style('university_main_styles', get_theme_file_uri('/build/style-index.css'), array(), rand(1, 999));
  wp_enqueue_style('university_extra_styles', get_theme_file_uri('/build/index.css'), array(), rand(1, 999));
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

function add_async_attribute($tag, $handle) {
  if ('main-university-js' !== $handle) {
    return $tag;
  }
  return str_replace(' src', ' async="async" src', $tag);
}





// I don't think it's neccessery
// Redirect subscriber accounts out of admin and onto homepage
add_action('admin_init', 'redirectSubsToFrontend');

function redirectSubsToFrontend() {
  $ourCurrentUser = wp_get_current_user();

  if (count($ourCurrentUser->roles) == 1 AND $ourCurrentUser->roles[0] == 'subscriber') {
    wp_redirect(site_url('/'));
    exit;
  }
}

add_action('wp_loaded', 'noSubsAdminBar');

function noSubsAdminBar() {
  $ourCurrentUser = wp_get_current_user();

  if (count($ourCurrentUser->roles) == 1 AND $ourCurrentUser->roles[0] == 'subscriber') {
    show_admin_bar(false);
  }
}

// this also no necesssery
// Customize Login Screen
add_filter('login_headerurl', 'ourHeaderUrl');

function ourHeaderUrl() {
  return esc_url(site_url('/'));
}

add_action('login_enqueue_scripts', 'ourLoginCSS');

function ourLoginCSS() {
  wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
  wp_enqueue_style('university_main_styles', get_theme_file_uri('/build/style-index.css'), array(), rand(1, 999));
  wp_enqueue_style('university_extra_styles', get_theme_file_uri('/build/index.css'), array(), rand(1, 999));


}

add_filter('login_headertitle', 'ourLoginTitle');

function ourLoginTitle() {
  return get_bloginfo('name');
}




// Register our new blocks
function our_new_blocks() {
  wp_localize_script('wp-editor', 'ourThemeData', array('themePath' => get_stylesheet_directory_uri()));


  register_block_type_from_metadata(__DIR__ . '/build/whoweareonas');
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
  register_block_type_from_metadata(__DIR__ . '/build/glownainstagram');
  register_block_type_from_metadata(__DIR__ . '/build/faqglowna');
  register_block_type_from_metadata(__DIR__ . '/build/glownaikony');
  register_block_type_from_metadata(__DIR__ . '/build/whoweareglowna');
  register_block_type_from_metadata(__DIR__ . '/build/glownakup');
  register_block_type_from_metadata(__DIR__ . '/build/metodyplatnoscitext');
  register_block_type_from_metadata(__DIR__ . '/build/metodyplatnoscihead');
  register_block_type_from_metadata(__DIR__ . '/build/faq');
  register_block_type_from_metadata(__DIR__ . '/build/metodywysylkikup');
  register_block_type_from_metadata(__DIR__ . '/build/metodywysylkitext');
  register_block_type_from_metadata(__DIR__ . '/build/metodywysylkihead');
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
  register_block_type_from_metadata(__DIR__ . '/build/glownabaner');
  register_block_type_from_metadata(__DIR__ . '/build/glownagrid');
  register_block_type_from_metadata(__DIR__ . '/build/glownaopinie');
  register_block_type_from_metadata(__DIR__ . '/build/karieraheader');
  register_block_type_from_metadata(__DIR__ . '/build/karierazespol');
  register_block_type_from_metadata(__DIR__ . '/build/textkariera');
  register_block_type_from_metadata(__DIR__ . '/build/karieraikony');
  register_block_type_from_metadata(__DIR__ . '/build/karieraoferty');

}

add_action('init', 'our_new_blocks');


function myallowedblocks($allowed_block_types, $editor_context) {
  // If you are on a page/post editor screen
  if (!empty($editor_context->post)) {
    return $allowed_block_types;
  }

  // if you are on the FSE screen
  return array('ourblocktheme/header', 'ourblocktheme/footer');
}

// Uncomment the line below if you actually want to restrict which block types are allowed
// add_filter('allowed_block_types_all', 'myallowedblocks', 10, 2);

function enqueue_custom_gsap_scripts() {
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
  wp_enqueue_script('slider-platnosci', get_template_directory_uri() . '/inc/slider-platnosci.js', array('jquery', 'gsap-core', 'gsap-scrolltrigger', 'gsap-draggable', 'gsap-scrollto'), '1.0', true);
}
add_action('wp_enqueue_scripts', 'enqueue_custom_gsap_scripts');



// Function to list pages in footer
function my_custom_page_list_shortcode() {
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
function enqueue_mobile_menu_script() {
  // Enqueue React and ReactDOM from CDN
  // wp_enqueue_script('react', 'https://unpkg.com/react@18/umd/react.production.min.js', array(), null, true);
  // wp_enqueue_script('react-dom', 'https://unpkg.com/react-dom@18/umd/react-dom.production.min.js', array('react'), null, true);

  // Enqueue your bundled mobile menu script
  // wp_enqueue_script('mobile-menu', get_template_directory_uri() . '/inc/mobile-menu.js', array(), null, true);

  // Localize script to pass data from PHP to JavaScript
  wp_localize_script('mobile-menu', 'menuData', array(
      'categories' => array_map(function($term) {
          $products = wc_get_products(array(
              'category' => array($term->slug),
              'limit' => 4,
          ));
          return array(
              'id' => $term->term_id,
              'name' => $term->name,
              'products' => array_map(function($product) {
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
  add_theme_support('post-thumbnails');
  add_image_size('pageBanner', 1500, 350, true);
  add_theme_support('editor-styles');
  add_editor_style(array('https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i', 'build/style-index.css', 'build/index.css'));

}
add_action('after_setup_theme', 'mytheme_add_woocommerce_support');


// Disable WooCommerce Zoom, Lightbox, and Gallery Slider
add_action( 'wp', 'remove_woocommerce_zoom_lightbox_gallery', 100 );

function remove_woocommerce_zoom_lightbox_gallery() {
    remove_theme_support( 'wc-product-gallery-zoom' );
    remove_theme_support( 'wc-product-gallery-lightbox' );
    // remove_theme_support( 'wc-product-gallery-slider' );
}

// Ensure that the zoom class is removed
// add_filter( 'wp_get_attachment_image_attributes', 'custom_remove_zoom_class', 10, 3 );

function custom_remove_zoom_class( $attr, $attachment, $size ) {
    if( is_product() ) {
        unset( $attr['data-srcset'] );
        unset( $attr['data-large_image'] );
        unset( $attr['data-large_image_width'] );
        unset( $attr['data-large_image_height'] );
        if ( isset( $attr['class'] ) ) {
            $attr['class'] = str_replace( 'wp-post-image', '', $attr['class'] );
        }
    }
    return $attr;
}

// Remove the link around the product image
add_filter( 'woocommerce_single_product_image_thumbnail_html', 'remove_image_link', 10, 2 );

function remove_image_link( $html, $post_id ) {
    if ( is_product() ) {
        // Remove the anchor tag
        $html = preg_replace( '#<a.*?>(.*?)<\/a>#i', '$1', $html );
    }
    return $html;
}

// cos nie tak z ta funkcja
wp_enqueue_script('wc-add-to-cart-variation');

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
function sf_update_woo_flexslider_options($options) {
  $options['directionNav'] = true;
  return $options;
}


// SUBTITLE BELOW TITLE IN SINGLE PAGE
// Add subtitle field to product edit page
function add_subtitle_custom_field() {
  woocommerce_wp_text_input( 
      array( 
          'id' => 'product_subtitle', 
          'label' => __( 'Product Subtitle', 'woocommerce' ), 
          'desc_tip' => 'true',
          'description' => __( 'Enter the subtitle of the product.', 'woocommerce' ),
          'type' => 'text'
      )
  );
}
add_action( 'woocommerce_product_options_general_product_data', 'add_subtitle_custom_field' );

// Save the custom field value
function save_subtitle_custom_field( $post_id ) {
  $product = wc_get_product( $post_id );
  $subtitle = isset( $_POST['product_subtitle'] ) ? $_POST['product_subtitle'] : '';
  $product->update_meta_data( 'product_subtitle', sanitize_text_field( $subtitle ) );
  $product->save();
}
add_action( 'woocommerce_process_product_meta', 'save_subtitle_custom_field' );


// icon with text below price
// Add icon with text fields to product edit page
function add_multiple_icon_text_custom_fields() {
  echo '<div class="options_group">';

  for ($i = 1; $i <= 3; $i++) {
      woocommerce_wp_text_input( 
          array( 
              'id' => 'product_icon_url_' . $i, 
              'label' => __( 'Icon URL ' . $i, 'woocommerce' ), 
              'desc_tip' => 'true',
              'description' => __( 'Enter the URL for the icon ' . $i . '.', 'woocommerce' ) 
          )
      );
      
      woocommerce_wp_text_input( 
          array( 
              'id' => 'product_icon_text_' . $i, 
              'label' => __( 'Icon Text ' . $i, 'woocommerce' ), 
              'desc_tip' => 'true',
              'description' => __( 'Enter the text to display next to the icon ' . $i . '.', 'woocommerce' ) 
          )
      );
  }

  echo '</div>';
}
// add_action( 'woocommerce_product_options_general_product_data', 'add_multiple_icon_text_custom_fields' );

// Save the custom field values
function save_multiple_icon_text_custom_fields( $post_id ) {
  $product = wc_get_product( $post_id );
  
  for ($i = 1; $i <= 3; $i++) {
      $icon_url = isset( $_POST['product_icon_url_' . $i] ) ? $_POST['product_icon_url_' . $i] : '';
      $product->update_meta_data( 'product_icon_url_' . $i, sanitize_text_field( $icon_url ) );

      $icon_text = isset( $_POST['product_icon_text_' . $i] ) ? $_POST['product_icon_text_' . $i] : '';
      $product->update_meta_data( 'product_icon_text_' . $i, sanitize_text_field( $icon_text ) );
  }

  $product->save();
}
// add_action( 'woocommerce_process_product_meta', 'save_multiple_icon_text_custom_fields' );

// Display multiple icons with text below the product price
function display_multiple_icons_text_below_price() {
  global $post;
  $product = wc_get_product( $post->ID );

  echo '<div class="product-icons-container">'; // Open parent container

  for ($i = 1; $i <= 3; $i++) {
      $icon_url = $product->get_meta( 'product_icon_url_' . $i );
      $icon_text = $product->get_meta( 'product_icon_text_' . $i );

      if ( ! empty( $icon_url ) && ! empty( $icon_text ) ) {
          echo '<div class="product-icon-text">';
          echo '<img src="' . esc_url( $icon_url ) . '" alt="Product Icon ' . $i . '" style="width: 20px; height: auto; vertical-align: middle; margin-right: 10px;" />';
          echo '<span>' . esc_html( $icon_text ) . '</span>';
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
function display_product_subtitle() {
  global $post;
  $product = wc_get_product( $post->ID );
  $subtitle = $product->get_meta( 'product_subtitle' );

  if ( ! empty( $subtitle ) ) {
      echo '<h2 class="product-subtitle">' . esc_html( $subtitle ) . '</h2>';
  }
}
add_action( 'woocommerce_single_product_summary', 'display_product_subtitle', 6 );





// Display subtitle shop page
function display_product_title_and_subtitle_shop() {
  global $post;
  $product = wc_get_product( $post->ID );
  $subtitle = $product->get_meta( 'product_subtitle' );
     // Get the product permalink (URL to the single product page)
     $product_permalink = get_permalink( $product->get_id() );

  echo '<div class="product-title-subtitle-wrapper">';
        // Wrap the image in a link to the product page
        echo '<a href="' . esc_url( $product_permalink ). '" style="text-decoration: none;">';
  // Output the product title
  echo '<h2 class="woocommerce-loop-product__title">';
  echo get_the_title();
  echo '</h2>';
  
  // Output the product subtitle, if it exists
  if ( ! empty( $subtitle ) ) {
      echo '<h3 class="product-subtitle-shop">' . esc_html( $subtitle ) . '</h3>';
  }
  echo '</a>';
  echo '</div>';
}


// shop page product promotion tag


function add_product_promotion_tag() {
  echo '<div class="options_group">';

  // Promo Text field
  woocommerce_wp_text_input( 
    array( 
      'id' => 'product_promotion_tag', 
      'label' => __( 'Product Promotion Tag', 'woocommerce' ), 
      'desc_tip' => 'true',
      'description' => __( 'Enter the promotion tag of the product.', 'woocommerce' ),
  )
  );

    // Background Gradient field
    woocommerce_wp_textarea_input( 
      array( 
          'id' => 'product_promotion_tag_color', 
          'label' => __( 'Promotion Tag Text Color', 'woocommerce' ), 
          'desc_tip' => 'true',
          'description' => __( 'Enter the CSS gradient (e.g., linear-gradient(to right, #ff0000, #00ff00)) for the promotional element.', 'woocommerce' ) 
      )
  );

  echo '</div>';
}
add_action( 'woocommerce_product_options_general_product_data', 'add_product_promotion_tag' );



// Save the custom field value
function save_product_promotion_tag( $post_id ) {
  $product = wc_get_product( $post_id );

  // Save promo text
  $subtitle = isset( $_POST['product_promotion_tag'] ) ? $_POST['product_promotion_tag'] : '';
  $product->update_meta_data( 'product_promotion_tag', sanitize_text_field( $subtitle ) );

    // Save promo background gradient
    $color = isset( $_POST['product_promotion_tag_color'] ) ? $_POST['product_promotion_tag_color'] : '';
    $product->update_meta_data( 'product_promotion_tag_color', sanitize_textarea_field( $color ) );

  $product->save();
}
add_action( 'woocommerce_process_product_meta', 'save_product_promotion_tag' );


function display_product_promotion_tag() {
  global $post;
  $product = wc_get_product( $post->ID );

    // Get the promotion tag and color
    $subtitle = $product->get_meta( 'product_promotion_tag' );
    $color = $product->get_meta( 'product_promotion_tag_color' );

    // Only display if the promo text is set
    if ( ! empty( $subtitle ) ) {
      // Set default gradient if not provided
      $color = ! empty( $color ) ? $color : 'linear-gradient(to right, #ff0000, #00ff00)'; // Default gradient

      echo '<div class="product-promotion-tag" style="';
      echo 'background: ' . esc_attr( $color ) . ';';
      echo '">';
      echo esc_html( $subtitle );
      echo '</div>';
  }
}


// second promotion tag on product page
function add_custom_promo_fields_two_lines() {
    echo '<div class="options_group">';
  
    // Promo Percentage field (for bigger text, -17%)
    woocommerce_wp_text_input( 
        array( 
            'id' => 'promo_percentage_text', 
            'label' => __( 'Promo Percentage Text', 'woocommerce' ), 
            'desc_tip' => 'true',
            'description' => __( 'Enter the percentage text to display on the first line (e.g., "-17%").', 'woocommerce' )
        )
    );
  
    // Background Gradient for percentage text
    woocommerce_wp_textarea_input( 
        array( 
            'id' => 'promo_percentage_gradient', 
            'label' => __( 'Promo Percentage Gradient', 'woocommerce' ), 
            'desc_tip' => 'true',
            'description' => __( 'Enter the CSS gradient for the percentage text (e.g., linear-gradient(to right, #ff0000, #00ff00)).', 'woocommerce' ) 
        )
    );
  
    // Promo Text field (for "promocja")
    woocommerce_wp_text_input( 
        array( 
            'id' => 'promo_small_text', 
            'label' => __( 'Promo Small Text', 'woocommerce' ), 
            'desc_tip' => 'true',
            'description' => __( 'Enter the small text to display below the percentage (e.g., "promocja").', 'woocommerce' )
        )
    );
  
    echo '</div>';
  }
  add_action( 'woocommerce_product_options_general_product_data', 'add_custom_promo_fields_two_lines' );

  function save_custom_promo_fields_two_lines( $post_id ) {
    $product = wc_get_product( $post_id );
  
    // Save percentage text
    $promo_percentage_text = isset( $_POST['promo_percentage_text'] ) ? $_POST['promo_percentage_text'] : '';
    $product->update_meta_data( 'promo_percentage_text', sanitize_text_field( $promo_percentage_text ) );
  
    // Save percentage gradient
    $promo_percentage_gradient = isset( $_POST['promo_percentage_gradient'] ) ? $_POST['promo_percentage_gradient'] : '';
    $product->update_meta_data( 'promo_percentage_gradient', sanitize_textarea_field( $promo_percentage_gradient ) );
  
    // Save small promo text
    $promo_small_text = isset( $_POST['promo_small_text'] ) ? $_POST['promo_small_text'] : '';
    $product->update_meta_data( 'promo_small_text', sanitize_text_field( $promo_small_text ) );
  
    $product->save();
  }
  add_action( 'woocommerce_process_product_meta', 'save_custom_promo_fields_two_lines' );

  
  function display_promotional_element_two_lines() {
    global $post;
    $product = wc_get_product( $post->ID );
  
    // Get custom fields
    $promo_percentage_text = $product->get_meta( 'promo_percentage_text' );
    $promo_percentage_gradient = $product->get_meta( 'promo_percentage_gradient' );
    $promo_small_text = $product->get_meta( 'promo_small_text' );
  
    // Only display if the percentage text is set
    if ( ! empty( $promo_percentage_text ) ) {
        // Set default gradient for the percentage text if not provided
        $percentage_gradient = ! empty( $promo_percentage_gradient ) ? $promo_percentage_gradient : 'linear-gradient(to right, #ff0000, #00ff00)'; // Default gradient
  
        echo '<div class="promotional-element-two-lines">';
  
        // Display the percentage text with gradient
        echo '<span class="promo-percentage" style="background: ' . esc_attr( $percentage_gradient ) . '; -webkit-background-clip: text; color: transparent; display: inline-block;">';
        echo esc_html( $promo_percentage_text );
        echo '</span>';
  
        // Display the small promo text
        if ( ! empty( $promo_small_text ) ) {
            echo '<span class="promo-small-text">';
            echo esc_html( $promo_small_text );
            echo '</span>';
        }
  
        echo '</div>';
    }
  }
  add_action( 'woocommerce_before_single_product_summary', 'display_promotional_element_two_lines', 10 );

  // short description title
function add_custom_short_description_field() {
    echo '<div class="options_group">';

    // Short Description Tag field
    woocommerce_wp_text_input( 
        array( 
            'id' => 'short_description_tag', 
            'label' => __( 'Short Description Custom Title', 'woocommerce' ), 
            'desc_tip' => 'true',
            'description' => __( 'Enter custom text to display above the short description.', 'woocommerce' ),
        )
    );

    // SVG Content field
    woocommerce_wp_textarea_input( 
        array( 
            'id' => 'short_description_svg', 
            'label' => __( 'SVG Icon', 'woocommerce' ), 
            'desc_tip' => 'true',
            'description' => __( 'Enter SVG markup to display next to the short description text.', 'woocommerce' ),
        )
    );

    echo '</div>';
}
add_action( 'woocommerce_product_options_general_product_data', 'add_custom_short_description_field' );

function save_custom_short_description_field( $post_id ) {
    $product = wc_get_product( $post_id );
    
    // Save custom text
    $short_description_tag = isset( $_POST['short_description_tag'] ) ? $_POST['short_description_tag'] : '';
    $product->update_meta_data( 'short_description_tag', sanitize_text_field( $short_description_tag ) );

    // Save SVG content with custom sanitization
    $allowed_tags = array(
        'svg' => array(
            'xmlns' => true,
            'width' => true,
            'height' => true,
            'viewBox' => true,
            'fill' => true,
        ),
        'path' => array(
            'd' => true,
            'fill' => true,
            'stroke' => true,
            'stroke-width' => true,
        ),
    );
    
    $short_description_svg = isset( $_POST['short_description_svg'] ) ? $_POST['short_description_svg'] : '';
    $product->update_meta_data( 'short_description_svg', wp_kses( $short_description_svg, $allowed_tags ) );

    $product->save();
}
add_action( 'woocommerce_process_product_meta', 'save_custom_short_description_field' );

function display_custom_short_description_field() {
    global $post;
    $product = wc_get_product( $post->ID );

    // Get the custom text and SVG content
    $short_description_tag = $product->get_meta( 'short_description_tag' );
    $short_description_svg = $product->get_meta( 'short_description_svg' );

    // Only display if the custom text is set
    if ( ! empty( $short_description_tag ) ) {
        echo '<div class="short-description-custom-text product-tag">';

        // Display SVG if it's set
        if ( ! empty( $short_description_svg ) ) {
            echo '<span style="display:flex;">' . $short_description_svg . '</span>';
        }
        
        echo esc_html( $short_description_tag );
        echo '</div>';
    }
}
add_action( 'woocommerce_single_product_summary', 'display_custom_short_description_field', 19 );

// second one

function add_custom_short_description_field_second() {
    echo '<div class="options_group">';

    // Short Description Tag field
    woocommerce_wp_text_input( 
        array( 
            'id' => 'short_description_tag_second', 
            'label' => __( 'Short Description Custom Title Second', 'woocommerce' ), 
            'desc_tip' => 'true',
            'description' => __( 'Enter custom text to display above the short description.', 'woocommerce' ),
        )
    );

    // SVG Content field
    woocommerce_wp_textarea_input( 
        array( 
            'id' => 'short_description_svg_second', 
            'label' => __( 'SVG Icon second', 'woocommerce' ), 
            'desc_tip' => 'true',
            'description' => __( 'Enter SVG markup to display next to the short description text.', 'woocommerce' ),
        )
    );

    echo '</div>';
}
add_action( 'woocommerce_product_options_general_product_data', 'add_custom_short_description_field_second' );

function save_custom_short_description_field_second( $post_id ) {
    $product = wc_get_product( $post_id );
    
    // Save custom text
    $short_description_tag = isset( $_POST['short_description_tag_second'] ) ? $_POST['short_description_tag_second'] : '';
    $product->update_meta_data( 'short_description_tag_second', sanitize_text_field( $short_description_tag ) );

    // Save SVG content with custom sanitization
    $allowed_tags = array(
        'svg' => array(
            'xmlns' => true,
            'width' => true,
            'height' => true,
            'viewBox' => true,
            'fill' => true,
        ),
        'path' => array(
            'd' => true,
            'fill' => true,
            'stroke' => true,
            'stroke-width' => true,
        ),
    );
    
    $short_description_svg = isset( $_POST['short_description_svg_second'] ) ? $_POST['short_description_svg_second'] : '';
    $product->update_meta_data( 'short_description_svg_second', wp_kses( $short_description_svg, $allowed_tags ) );

    $product->save();
}
add_action( 'woocommerce_process_product_meta', 'save_custom_short_description_field_second' );

function display_custom_short_description_field_second() {
    global $post;
    $product = wc_get_product( $post->ID );

    // Get the custom text and SVG content
    $short_description_tag = $product->get_meta( 'short_description_tag_second' );
    $short_description_svg = $product->get_meta( 'short_description_svg_second' );

    // Only display if the custom text is set
    if ( ! empty( $short_description_tag ) ) {
        echo '<br/><div class="background-red product-tag">';

        // Display SVG if it's set
        if ( ! empty( $short_description_svg ) ) {
            echo '<span style="display:flex; margin-left: 5px;">' . $short_description_svg . '</span>';
        }
        
        echo esc_html( $short_description_tag );
        echo '</div>';
    }
}
add_action( 'woocommerce_single_product_summary', 'display_custom_short_description_field_second', 19 );





// pasek magazynowyAdd commentMore actions
// Add custom fields to product edit page
// 1. Dodaj pola w panelu admina
function add_custom_strip_fields() {
    global $post;

    echo '<div class="options_group">';

    // Checkbox aktywacji
    woocommerce_wp_checkbox(array(
        'id' => 'enable_stock_strip',
        'label' => __('Pokaż pasek magazynowy', 'woocommerce'),
        'description' => __('Zaznacz aby aktywować pasek magazynowy', 'woocommerce'),
        'value' => 'yes',
        'custom_attributes' => array('checked' => 'checked') // Domyślnie aktywny
    ));

    // Pole na procenty
    woocommerce_wp_text_input(array(
        'id' => 'custom_percentage',
        'label' => __('Procent wypełnienia', 'woocommerce'),
        'desc_tip' => true,
        'description' => __('Wpisz procent wypełnienia paska (0-100)', 'woocommerce'),
        'wrapper_class' => 'show_if_enabled'
    ));

    echo '</div>';
}
add_action('woocommerce_product_options_general_product_data', 'add_custom_strip_fields');

// 2. Zapisz wartości pól
function save_custom_strip_fields($post_id) {
    $product = wc_get_product($post_id);





    // Domyślna aktywacja dla nowych produktów
    if (!$product->meta_exists('enable_stock_strip')) {
        $product->update_meta_data('enable_stock_strip', 'yes');
    }

    // Aktualizacja checkboxa
    $enable_strip = isset($_POST['enable_stock_strip']) ? 'yes' : 'no';
    $product->update_meta_data('enable_stock_strip', $enable_strip);

    // Zapisz procent
    $percentage = isset($_POST['custom_percentage']) ? intval($_POST['custom_percentage']) : '';
    $product->update_meta_data('custom_percentage', $percentage);

    $product->save();
}
add_action('woocommerce_process_product_meta', 'save_custom_strip_fields');

// 3. Wyświetlanie paska z fallbackiem
function display_percentage_strip() {
    global $post;


    $product = wc_get_product($post->ID);
    $default_product = wc_get_product(68); // Produkt fallback

    // Sprawdź czy funkcja jest aktywna
    $is_enabled = $product->meta_exists('enable_stock_strip') ? 
                 $product->get_meta('enable_stock_strip') : 
                 'yes';

    if ($is_enabled !== 'yes') return;

    // Pobierz procent
    $percentage = $product->get_meta('custom_percentage');

    // Fallback do produktu 103
    if ((empty($percentage) || !is_numeric($percentage)) && $default_product) {
        $percentage = $default_product->get_meta('custom_percentage');
    }

    // Walidacja wartości
    if (!empty($percentage) && is_numeric($percentage)) {
        $percentage = min(max(intval($percentage), 0), 100);

        // Wyświetl pasek (zachowane oryginalne style)
        echo '<div class="custom-percentage-strip-container">';
        echo '<p style="margin: 0 0 10px 0;">Dostępność na magazynie: <strong>'.esc_html($percentage).'%</strong></p>';
        echo '<div class="custom-percentage-strip">';
        echo '<div style="width: '.esc_attr($percentage).'%; height: 12px; background: linear-gradient(90deg, #C00 0%, #940000 100%); border-radius: 32px; position: relative; top: 0; left: 0; display: flex; align-items: center; justify-content: center;"></div>';
        echo '</div>';
        echo '</div>';
    }
   

}
add_action( 'woocommerce_share', 'display_percentage_strip', 20 );

// Add custom banner field to product edit page
function add_konkurs_banner_field() {
    echo '<div class="options_group">';

    // Banner Image URL Field
    woocommerce_wp_text_input( 
        array( 
            'id' => 'custom_banner_image_konkurs', 
            'label' => __( 'Banner Image Konkurs', 'woocommerce' ), 
            'desc_tip' => 'true',
            'description' => __( 'Enter the URL of the banner image to display under Add to Cart.', 'woocommerce' ) 
        )
    );

    echo '</div>';
}
add_action( 'woocommerce_product_options_general_product_data', 'add_konkurs_banner_field' );

// Save the custom banner field value
function save_konkurs_banner_field( $post_id ) {
    $product = wc_get_product( $post_id );

    // Save banner image URL
    $banner_image_url = isset( $_POST['custom_banner_image_konkurs'] ) ? $_POST['custom_banner_image_konkurs'] : '';
    $product->update_meta_data( 'custom_banner_image_konkurs', esc_url_raw( $banner_image_url ) );
    
    $product->save();
}
add_action( 'woocommerce_process_product_meta', 'save_konkurs_banner_field' );

// Display the banner image under the Add to Cart button on the product page
function display_konkurs_banner_image() {
    global $post;
    $product = wc_get_product( $post->ID );

    // Get the banner image URL
    $banner_image_url = $product->get_meta( 'custom_banner_image_konkurs' );

    if ( ! empty( $banner_image_url ) ) {
        echo '<div class="custom-banner-image-container" style="margin-top: 20px; text-align: center;">';
        echo '<img src="' . esc_url( $banner_image_url ) . '" alt="' . esc_attr__( 'Product Banner', 'woocommerce' ) . '" style="width: 100%; max-width: 500px; height: auto; border-radius: 10px;">';

        // Display the fixed link below the banner
        // echo '<p style="font-size: 10px; margin-top: 5px; color: #000">Kup golarkę, podczas trwania promocji aby wziąć udział w konkursie. <a href="https://shav.pl/wp-content/uploads/Regulamin-konkursu-Shav-Swieta-2025.docx.pdf" target="_blank"> Regulamin konkursu</a></p>';

        echo '</div>';
    }
}
add_action( 'woocommerce_share', 'display_konkurs_banner_image', 23 );



// SHOP IMAGE - different product images
// SHOP TOP IMAGE WITH TEXT


// Set your specific product ID
$product_id_with_banner = 68; // Replace 123 with the actual product ID you want to use


function add_shop_page_banner_image_fields() {
    global $post, $product_id_with_banner;


    if ( $post->ID != $product_id_with_banner ) {
        return; // Pokazujemy pola tylko dla określonego produktu
    }

    // Pole dla baneru desktopowego
    woocommerce_wp_text_input( 
        array( 
            'id'          => 'shop_page_banner_image_desktop', 
            'label'       => __( 'Shop Page Banner Image Desktop', 'woocommerce' ), 
            'desc_tip'    => 'true',
            'description' => __( 'Wprowadź URL obrazka baneru do wyświetlania na desktopie.', 'woocommerce' ),
            'type'        => 'text'
        )
    );

    // Pole dla baneru mobilnego
    woocommerce_wp_text_input( 
        array( 
            'id'          => 'shop_page_banner_image_mobile', 
            'label'       => __( 'Shop Page Banner Image Mobile', 'woocommerce' ), 
            'desc_tip'    => 'true',
            'description' => __( 'Wprowadź URL obrazka baneru do wyświetlania na urządzeniach mobilnych.', 'woocommerce' ),
            'type'        => 'text'
        )
    );
}

add_action( 'woocommerce_product_options_general_product_data', 'add_shop_page_banner_image_fields' );

function save_shop_page_banner_image_fields( $post_id ) {
    global $product_id_with_banner;

    if ( $post_id != $product_id_with_banner ) {
        return; // Zapisujemy dane tylko dla określonego produktu
    }

    $product = wc_get_product( $post_id );

    if ( isset( $_POST['shop_page_banner_image_desktop'] ) ) {
        $desktop_banner_url = esc_url_raw( $_POST['shop_page_banner_image_desktop'] );
        $product->update_meta_data( 'shop_page_banner_image_desktop', $desktop_banner_url );
    }

    if ( isset( $_POST['shop_page_banner_image_mobile'] ) ) {
        $mobile_banner_url = esc_url_raw( $_POST['shop_page_banner_image_mobile'] );
        $product->update_meta_data( 'shop_page_banner_image_mobile', $mobile_banner_url );
    }

    $product->save();
}

add_action( 'woocommerce_process_product_meta', 'save_shop_page_banner_image_fields' );

function display_shop_banner_image_with_text() {
    global $product_id_with_banner;
    // Zmienna flagowa, aby uniknąć zdublowanych wywołań
    static $executed = false;
    if ( $executed ) return;
    $executed = true;

    if ( is_shop() ) {
        $product = wc_get_product( $product_id_with_banner );
        if ( $product ) {
            $desktop_banner_url = $product->get_meta( 'shop_page_banner_image_desktop' );
            $mobile_banner_url  = $product->get_meta( 'shop_page_banner_image_mobile' );
            $text_1             = $product->get_meta( 'shop_page_banner_text_1' );
            $text_2             = $product->get_meta( 'shop_page_banner_text_2' );

            if ( $desktop_banner_url || $mobile_banner_url ) {
                echo '<div class="shop-banner-image" style="position: relative;">';

                // Baner desktopowy – nadaj klasę, którą w CSS ukryjesz na mobile
                if ( $desktop_banner_url ) {
                    echo '<img class="shop-banner-image-desktop" src="' . esc_url( $desktop_banner_url ) . '" alt="' . esc_attr__( 'Shop Banner Image Desktop', 'woocommerce' ) . '" style="width: 100%; height: auto;">';
                }

                // Baner mobilny – nadaj klasę, którą w CSS ukryjesz na desktopie
                if ( $mobile_banner_url ) {
                    echo '<img class="shop-banner-image-mobile" src="' . esc_url( $mobile_banner_url ) . '" alt="' . esc_attr__( 'Shop Banner Image Mobile', 'woocommerce' ) . '" style="width: 100%; height: auto;">';
                }

                echo '<div class="shop-banner-textcontainer">';
                if ( ! empty( $text_1 ) ) {
                    echo '<div class="banner-text banner-text-1">' . wp_kses_post( $text_1 ) . '</div>';


                }
                if ( ! empty( $text_2 ) ) {
                    echo '<div class="banner-text banner-text-2">' . esc_html( $text_2 ) . '</div>';


                }
                echo '</div>'; // .shop-banner-textcontainer
                echo '</div>'; // .shop-banner-image
            }
        }
    }
}

add_action( 'woocommerce_before_main_content', 'display_shop_banner_image_with_text', 5 );

function add_shop_page_banner_text_fields() {
    global $post;
    global $product_id_with_banner;

    if ( $post->ID != $product_id_with_banner ) {
        return; // Only show for the specified product ID
    }

    woocommerce_wp_textarea_input( 
        array( 
            'id' => 'shop_page_banner_text_1', 
            'label' => __( 'Shop Page Banner Text 1', 'woocommerce' ), 
            'desc_tip' => 'true',
            'description' => __( 'Enter the first text fragment to display on the shop page banner image. You can use HTML to style specific words.', 'woocommerce' ),
            'type' => 'textarea',
            'class' => 'short'
        )
    );

    woocommerce_wp_text_input( 
        array( 
            'id' => 'shop_page_banner_text_2', 
            'label' => __( 'Shop Page Banner Text 2', 'woocommerce' ), 
            'desc_tip' => 'true',
            'description' => __( 'Enter the second text fragment to display on the shop page banner image.', 'woocommerce' ),
            'type' => 'text'
        )
    );
}

add_action( 'woocommerce_product_options_general_product_data', 'add_shop_page_banner_text_fields' );

function save_shop_page_banner_text_fields( $post_id ) {
    global $product_id_with_banner;

    if ( $post_id != $product_id_with_banner ) {
        return; // Only save for the specified product ID
    }

    $product = wc_get_product( $post_id );

    if ( isset( $_POST['shop_page_banner_text_1'] ) ) {
        $text_1 = wp_kses_post( $_POST['shop_page_banner_text_1'] );
        $product->update_meta_data( 'shop_page_banner_text_1', $text_1 );
    }

    if ( isset( $_POST['shop_page_banner_text_2'] ) ) {
        $text_2 = sanitize_text_field( $_POST['shop_page_banner_text_2'] );
        $product->update_meta_data( 'shop_page_banner_text_2', $text_2 );
    }

    $product->save();
}

add_action( 'woocommerce_process_product_meta', 'save_shop_page_banner_text_fields' );



////////
function add_product_shop_image() {
  woocommerce_wp_text_input(
      array(
          'id' => 'product_shop_image',
          'label' => __( 'Shop Image URL', 'woocommerce' ),
          'desc_tip' => 'true',
          'description' => __( 'Enter the URL of the image to be used on the shop page.', 'woocommerce' ),
          'type' => 'text'
      )
  );
}
add_action( 'woocommerce_product_options_general_product_data', 'add_product_shop_image' );

function save_product_shop_image( $post_id ) {
  $product = wc_get_product( $post_id );
  $shop_image_url = isset( $_POST['product_shop_image'] ) ? $_POST['product_shop_image'] : '';
  $product->update_meta_data( 'product_shop_image', esc_url_raw( $shop_image_url ) );
  $product->save();
}
add_action( 'woocommerce_process_product_meta', 'save_product_shop_image' );

function display_custom_shop_image() {
  global $product;

  $shop_image_url = $product->get_meta( 'product_shop_image' );

   // Get the product permalink (URL to the single product page)
   $product_permalink = get_permalink( $product->get_id() );

  if ( ! empty( $shop_image_url ) ) {
    
    echo '<div class="wc-block-components-product-image-new">';
            // Wrap the image in a link to the product page
            echo '<a href="' . esc_url( $product_permalink ) . '">';
    echo '<img src="' . esc_url( $shop_image_url ) . '" class="custom-shop-image" />';
    echo '</a>';
    echo '</div>';
} else {
  echo '<div class="custom-image-container">';
  // Wrap the default product thumbnail in a link to the product page
  echo '<a href="' . esc_url( $product_permalink ) . '">';
  woocommerce_template_loop_product_thumbnail();
  echo '</a>';
  echo '</div>';
}
}
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
add_action( 'woocommerce_before_shop_loop_item', 'display_custom_shop_image', 10 );


add_action( 'woocommerce_shop_loop_item_title', 'display_product_promotion_tag', 10);

remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
add_action( 'woocommerce_shop_loop_item_title', 'display_product_title_and_subtitle_shop', 10 );







// Add custom fields to product after add to cart
function add_custom_fields() {
  echo '<div class="options_group">';

  // Image URL field
  woocommerce_wp_text_input( 
      array( 
          'id' => 'custom_image_url', 
          'label' => __( 'Custom Image URL', 'woocommerce' ), 
          'desc_tip' => 'true',
          'description' => __( 'Enter the URL for the custom image.', 'woocommerce' ) 
      )
  );

  // Title field
  woocommerce_wp_text_input( 
      array( 
          'id' => 'custom_title', 
          'label' => __( 'Custom Title', 'woocommerce' ), 
          'desc_tip' => 'true',
          'description' => __( 'Enter the custom title.', 'woocommerce' )
      )
  );
  
  // Description field
  woocommerce_wp_textarea_input( 
      array( 
          'id' => 'custom_description', 
          'label' => __( 'Custom Description', 'woocommerce' ), 
          'desc_tip' => 'true',
          'description' => __( 'Enter the custom description.', 'woocommerce' ) 
      )
  );

  echo '</div>';
}
add_action( 'woocommerce_product_options_general_product_data', 'add_custom_fields' );

// Save the custom field values
function save_custom_fields( $post_id ) {
  $product = wc_get_product( $post_id );
  
  // Save image URL
  $image_url = isset( $_POST['custom_image_url'] ) ? $_POST['custom_image_url'] : '';
  $product->update_meta_data( 'custom_image_url', sanitize_text_field( $image_url ) );
  
  // Save title
  $title = isset( $_POST['custom_title'] ) ? $_POST['custom_title'] : '';
  $product->update_meta_data( 'custom_title', sanitize_text_field( $title ) );
  
  // Save description
  $description = isset( $_POST['custom_description'] ) ? $_POST['custom_description'] : '';
  $product->update_meta_data( 'custom_description', sanitize_textarea_field( $description ) );
  
  $product->save();
}
add_action( 'woocommerce_process_product_meta', 'save_custom_fields' );

// Display custom image under the Add to Cart button
function display_custom_image() {
  global $post;
  $product = wc_get_product( $post->ID );
  
  // Get image URL
  $image_url = $product->get_meta( 'custom_image_url' );

  if ( ! empty( $image_url ) ) {
      echo '<div class="custom-image-section" style="margin-top: 20px;">';
      echo '<img src="' . esc_url( $image_url ) . '" alt="Custom Image" style="max-width: 100%; height: auto;" />';
      echo '</div>';
  }
}
add_action( 'woocommerce_after_add_to_cart_form', 'display_custom_image', 10 );

// Display custom title and description under the Add to Cart button
function display_custom_title_description() {
  global $post;
  $product = wc_get_product( $post->ID );
  
  // Get title and description
  $title = $product->get_meta( 'custom_title' );
  $description = $product->get_meta( 'custom_description' );

  if ( ! empty( $title ) || ! empty( $description ) ) {
      echo '<div class="custom-title-description-section" style="margin-top: 20px;">';
      
      if ( ! empty( $title ) ) {
          echo '<div class="custom-title" style="cursor: pointer; color: #0073aa; text-decoration: underline; margin-bottom: 10px;">';
          echo esc_html( $title );
          echo '</div>';
      }

      if ( ! empty( $description ) ) {
          echo '<div class="custom-description" style="display: none; margin-bottom: 10px;">';
          echo wp_kses_post( wpautop( $description ) );
          echo '</div>';
      }

      echo '</div>';
  }
}
add_action( 'woocommerce_after_add_to_cart_form', 'display_custom_title_description', 15 );

// display items to add to cart under add to cart button 
//  ZESTAW NA STRONIE PRODUKTOWEJ 
function add_custom_product_fields() {
    global $post;



















































    echo '<div class="options_group">';









    // Checkbox aktywacji
    woocommerce_wp_checkbox(array(
        'id' => 'enable_custom_product_section',
        'label' => __('Pokaż sekcję produktu', 'woocommerce'),
        'value' => 'yes',
        'custom_attributes' => array('checked' => 'checked') // Domyślnie aktywny
    ));

    // Custom Title field
    woocommerce_wp_text_input(array(
        'id' => 'custom_product_title',
        'label' => __('Tytuł produktu', 'woocommerce'),
        'desc_tip' => 'true',
        'description' => __('Wpisz niestandardowy tytuł do wyświetlenia.', 'woocommerce'),
        'wrapper_class' => 'show_if_enabled'
    ));

    // Custom Image URL field
    woocommerce_wp_text_input(array(
        'id' => 'custom_product_image_url',
        'label' => __('URL obrazka produktu', 'woocommerce'),
        'desc_tip' => 'true',
        'description' => __('Wpisz URL obrazka do wyświetlenia.', 'woocommerce'),
        'wrapper_class' => 'show_if_enabled'
    ));

    // Custom Product ID field
    woocommerce_wp_text_input(array(
        'id' => 'custom_product_id',
        'label' => __('ID produktu', 'woocommerce'),
        'desc_tip' => 'true',
        'description' => __('Wpisz ID produktu do dodania do koszyka.', 'woocommerce'),
        'wrapper_class' => 'show_if_enabled'
    ));

    // Custom Price field
    woocommerce_wp_text_input(array(
        'id' => 'custom_product_price',
        'label' => __('Cena produktu', 'woocommerce'),
        'desc_tip' => 'true',
        'description' => __('Wpisz niestandardową cenę do wyświetlenia.', 'woocommerce'),
        'wrapper_class' => 'show_if_enabled'
    ));

    // Custom Unit Price field
    woocommerce_wp_text_input(array(
        'id' => 'custom_product_unit_price',
        'label' => __('Cena jednostkowa', 'woocommerce'),
        'desc_tip' => 'true',
        'description' => __('Wpisz cenę jednostkową do wyświetlenia.', 'woocommerce'),
        'wrapper_class' => 'show_if_enabled'
    ));

    // Custom Product List field
    woocommerce_wp_textarea_input(array(
        'id' => 'custom_product_list',
        'label' => __('Lista produktów', 'woocommerce'),
        'desc_tip' => 'true',
        'description' => __('Wpisz listę produktów, jeden na linię.', 'woocommerce'),
        'wrapper_class' => 'show_if_enabled'
    ));

    echo '</div>';
}
add_action('woocommerce_product_options_general_product_data', 'add_custom_product_fields');

// 2. Zapisz wartości pól
function save_custom_product_fields($post_id) {
    $product = wc_get_product($post_id);
    
    // Domyślna aktywacja dla nowych produktów
    if (!$product->meta_exists('enable_custom_product_section')) {
        $product->update_meta_data('enable_custom_product_section', 'yes');
    }

    // Aktualizacja checkboxa
    $enable_section = isset($_POST['enable_custom_product_section']) ? 'yes' : 'no';
    $product->update_meta_data('enable_custom_product_section', $enable_section);

    // Zapisz pozostałe pola
    $fields = [
        'custom_product_title',
        'custom_product_image_url',
        'custom_product_id',
        'custom_product_price',
        'custom_product_unit_price',
        'custom_product_list'
    ];

    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            $value = $field === 'custom_product_list' ? sanitize_textarea_field($_POST[$field]) : sanitize_text_field($_POST[$field]);
            $product->update_meta_data($field, $value);
        }
    }
    
    $product->save();
}
add_action('woocommerce_process_product_meta', 'save_custom_product_fields');

// 3. Wyświetlanie z fallbackiem
function display_custom_product_section() {
    global $post;
    
    $product = wc_get_product($post->ID);
    $default_product = wc_get_product(68); // Produkt fallback
    
    // Sprawdź czy funkcja jest aktywna
    $is_enabled = $product->meta_exists('enable_custom_product_section') ? 
                 $product->get_meta('enable_custom_product_section') : 
                 'yes';

    if ($is_enabled !== 'yes') return;

    // Pobierz wartości z fallbackiem
    $fields = [
        'custom_product_title',
        'custom_product_image_url',
        'custom_product_id',
        'custom_product_price',
        'custom_product_unit_price',
        'custom_product_list'
    ];

    $values = [];
    foreach ($fields as $field) {
        $values[$field] = $product->get_meta($field);
        if ((empty($values[$field]) || $values[$field] === '') && $default_product) {
            $values[$field] = $default_product->get_meta($field);

        }
    }

    // Jeśli brak ID produktu - wyjdź
    if (empty($values['custom_product_id'])) return;

    echo '<div class="zestaw-container">';
    echo '<div class="zestaw-title">Zestaw promocyjny:</div>';
    echo '<div class="custom-product-section" style="margin-top: 0px;">';
    
    echo '<div class="zestaw-section">';
    echo '<div class="zestaw-details">';

    // Obrazek
    if (!empty($values['custom_product_image_url'])) {
        echo '<div class="custom-product-image-father" style="margin-bottom: 10px;">';
        echo '<div class="custom-product-image">';
        echo '<img src="'.esc_url($values['custom_product_image_url']).'" alt="'.esc_attr($values['custom_product_title']).'" style="width: 210px; height: auto; border-radius: 8px;" />';
        echo '</div>';
        echo '</div>';
    }

    // Tytuł i lista
    echo '<div class="custom-title-list-container" style="margin-top: 10px;">';







    if (!empty($values['custom_product_title'])) {
        echo '<h3 class="custom-product-title">'.esc_html($values['custom_product_title']).'</h3>';
    }

    if (!empty($values['custom_product_list'])) {
        echo '<ul style="list-style: disc; font-weight: 400; padding: 0; padding-left: 13px; line-height: normal; margin-bottom: 0; color: #000; font-size: 14px; font-style: normal;">';
        foreach (explode("\n", $values['custom_product_list']) as $item) {
            echo '<li style="margin-bottom: 0px;">'.esc_html($item).'</li>';
        }
        echo '</ul>';
    }

    echo '</div>'; // End custom-title-list-container
    echo '</div>'; // End zestaw-details

 // Cena i przycisk
echo '<div class="zestaw-cena">';
echo '<div class="zestaw-cena-sub">';



if (!empty($values['custom_product_price'])) {
    echo '<div style="margin-bottom: 10px;">';
    echo '<span style="display: block; margin-bottom: 5px;">Cena za zestaw:</span>';
    echo '<span class="zestaw-cena-title" style="display: block; font-size: 32px; color: #AC0000;">'.esc_html($values['custom_product_price']).' zł</span>';
    echo '</div>';
}


if (!empty($values['custom_product_unit_price'])) {
    echo '<div style="margin-bottom: 15px;">';
    echo '<span class="zestaw-tekst-cena" style="display: block; margin-bottom: 5px;">Cena poza zestawem:</span>';
    echo '<span class="zestaw-cena-rest" style="display: block;">'.esc_html($values['custom_product_unit_price']).' zł</span>';
    echo '</div>';
}










echo '</div>';

// Przycisk
$product_url = get_permalink($values['custom_product_id']);
if ($product_url) {
    echo '<a href="'.esc_url($product_url).'" class="button alt single_add_to_cart_button" style="margin-top: 10px; text-decoration: none; margin-bottom: 1em; width: 100% !important; text-align: center; padding: 0px;">'.__('Zobacz zestaw', 'woocommerce').'</a>';
}

echo '</div>'; // End zestaw-cena
    echo '</div>'; // End zestaw-section
    echo '</div>';
    echo '</div>';
}
add_action( 'woocommerce_share', 'display_custom_product_section', 22 );


// promotional tag in product page
function add_custom_promo_fields() {
  echo '<div class="options_group">';

  // Promo Text field
  woocommerce_wp_text_input( 
      array( 
          'id' => 'promo_text', 
          'label' => __( 'Promo Text', 'woocommerce' ), 
          'desc_tip' => 'true',
          'description' => __( 'Enter the text to display inside the promotional element.', 'woocommerce' )
      )
  );

    // Background Gradient field
    woocommerce_wp_textarea_input( 
      array( 
          'id' => 'promo_bg_gradient', 
          'label' => __( 'Promo Background Gradient', 'woocommerce' ), 
          'desc_tip' => 'true',
          'description' => __( 'Enter the CSS gradient (e.g., linear-gradient(to right, #ff0000, #00ff00)) for the promotional element.', 'woocommerce' ) 
      )
  );

  echo '</div>';
}
add_action( 'woocommerce_product_options_general_product_data', 'add_custom_promo_fields' );


function save_custom_promo_fields( $post_id ) {
  $product = wc_get_product( $post_id );

  // Save promo text
  $promo_text = isset( $_POST['promo_text'] ) ? $_POST['promo_text'] : '';
  $product->update_meta_data( 'promo_text', sanitize_text_field( $promo_text ) );

    // Save promo background gradient
    $promo_bg_gradient = isset( $_POST['promo_bg_gradient'] ) ? $_POST['promo_bg_gradient'] : '';
    $product->update_meta_data( 'promo_bg_gradient', sanitize_textarea_field( $promo_bg_gradient ) );

  $product->save();
}
add_action( 'woocommerce_process_product_meta', 'save_custom_promo_fields' );

function display_promotional_element() {
  global $post;
  $product = wc_get_product( $post->ID );

  // Get custom fields
  $promo_text = $product->get_meta( 'promo_text' );
  $promo_bg_gradient = $product->get_meta( 'promo_bg_gradient' );

    // Only display if the promo text is set
    if ( ! empty( $promo_text ) ) {
      // Set default gradient if not provided
      $bg_gradient = ! empty( $promo_bg_gradient ) ? $promo_bg_gradient : 'linear-gradient(to right, #ff0000, #00ff00)'; // Default gradient

      echo '<div class="promotional-element" style="';
      echo 'background: ' . esc_attr( $bg_gradient ) . ';';
      echo '">';
      echo esc_html( $promo_text );
      echo '</div>';
  }
}
add_action( 'woocommerce_before_single_product_summary', 'display_promotional_element', 10 );




// banner top of single-product page
//desktop
function add_custom_banner_field() {
  woocommerce_wp_text_input( 
      array( 
          'id' => 'custom_banner_url', 
          'label' => __( 'Custom Banner Image URL', 'woocommerce' ), 
          'desc_tip' => 'true',
          'description' => __( 'Enter the URL for the custom banner image.', 'woocommerce' ) 
      )
  );
}
add_action( 'woocommerce_product_options_general_product_data', 'add_custom_banner_field' );

function save_custom_banner_field( $post_id ) {
  $product = wc_get_product( $post_id );

  // Save banner image URL
  $banner_url = isset( $_POST['custom_banner_url'] ) ? $_POST['custom_banner_url'] : '';
  $product->update_meta_data( 'custom_banner_url', sanitize_text_field( $banner_url ) );
  $product->save();
}
add_action( 'woocommerce_process_product_meta', 'save_custom_banner_field' );


// mobile

function add_custom_mobile_banner_field() {
  woocommerce_wp_text_input( 
      array( 
          'id' => 'custom_mobile_banner_url', 
          'label' => __( 'Custom Mobile Banner Image URL', 'woocommerce' ), 
          'desc_tip' => 'true',
          'description' => __( 'Enter the URL for the custom banner image for mobile view.', 'woocommerce' ) 
      )
  );
}
add_action( 'woocommerce_product_options_general_product_data', 'add_custom_mobile_banner_field' );

function save_custom_mobile_banner_field( $post_id ) {
  $product = wc_get_product( $post_id );

  // Save mobile banner image URL
  $mobile_banner_url = isset( $_POST['custom_mobile_banner_url'] ) ? $_POST['custom_mobile_banner_url'] : '';
  $product->update_meta_data( 'custom_mobile_banner_url', sanitize_text_field( $mobile_banner_url ) );
  $product->save();
}
add_action( 'woocommerce_process_product_meta', 'save_custom_mobile_banner_field' );

function display_custom_banner() {
  global $post;
  $product = wc_get_product( $post->ID );

  // Get banner URLs
  $banner_url = $product->get_meta( 'custom_banner_url' );
  $mobile_banner_url = $product->get_meta( 'custom_mobile_banner_url' );

  if ( ! empty( $banner_url ) ) {
      echo '<div class="custom-banner-section" style="width: 100%; text-align: center; margin-bottom: 20px;">';

      // Standard banner
      echo '<img class="custom-banner-desktop" src="' . esc_url( $banner_url ) . '" alt="Custom Banner" style="width: 100%; height: auto;" />';

      // Mobile banner
      if ( ! empty( $mobile_banner_url ) ) {
          echo '<img class="custom-banner-mobile" src="' . esc_url( $mobile_banner_url ) . '" alt="Custom Mobile Banner" style="width: 100%; height: auto;" />';
      }

      echo '</div>';
  }
}
// add_action( 'woocommerce_before_single_product', 'display_custom_banner', 5 );
// add_action( 'woocommerce_before_cart_table', 'display_custom_banner', 5 );

// product banner timer
// Add a new field for the countdown timer
function add_countdown_timer_field() {
    woocommerce_wp_text_input( 
        array( 
            'id' => 'countdown_timer_end_date', 
            'label' => __( 'Countdown Timer End Date', 'woocommerce' ), 
            'desc_tip' => 'true',
            'description' => __( 'Enter the end date and time for the countdown timer in YYYY-MM-DDTHH:MM format.', 'woocommerce' ),
            'type' => 'datetime-local'
        )
    );
}
add_action( 'woocommerce_product_options_general_product_data', 'add_countdown_timer_field' );

// Save the countdown timer end date field
function save_countdown_timer_field( $post_id ) {
    $product = wc_get_product( $post_id );
    $countdown_end_date = isset( $_POST['countdown_timer_end_date'] ) ? $_POST['countdown_timer_end_date'] : '';
    $product->update_meta_data( 'countdown_timer_end_date', sanitize_text_field( $countdown_end_date ) );
    $product->save();
}
add_action( 'woocommerce_process_product_meta', 'save_countdown_timer_field' );

// Display custom banner with countdown timer on top of it
function display_custom_banner_with_timer() {
    global $post;
    $product_id = $post->ID;
    $product = wc_get_product( $product_id );

    // Get banner URLs and countdown timer end date
    $banner_url = $product->get_meta( 'custom_banner_url' );
    $mobile_banner_url = $product->get_meta( 'custom_mobile_banner_url' );
    $countdown_end_date = $product->get_meta( 'countdown_timer_end_date' );

    // --- NOWY KOD: Nadpisywanie dla konkretnych ID ---
    $master_product_id = 233781;
    $target_product_ids = array( 243159, 243156, 279965, 279955, 244024, 243166 );

    if ( in_array( $product_id, $target_product_ids ) ) {
        $master_product = wc_get_product( $master_product_id );
        
        if ( $master_product ) {
            $master_banner_url = $master_product->get_meta( 'custom_banner_url' );
            
            // Jeśli produkt 233781 ma ustawiony baner, nadpisujemy obecne zmienne
            if ( ! empty( $master_banner_url ) ) {
                $banner_url = $master_banner_url;
                $mobile_banner_url = $master_product->get_meta( 'custom_mobile_banner_url' );
                $countdown_end_date = $master_product->get_meta( 'countdown_timer_end_date' );
            }
        }
    }
    // --- KONIEC NOWEGO KODU ---

    // Jeśli baner nie jest ustawiony, pobierz domyślny z produktu o ID 49
    if ( empty( $banner_url ) ) {
        $default_product = wc_get_product( 68 ); 
        $banner_url = $default_product->get_meta( 'custom_banner_url' );
    }

    if ( empty( $mobile_banner_url ) ) {
        $default_product = wc_get_product( 68 ); 
        $mobile_banner_url = $default_product->get_meta( 'custom_mobile_banner_url' );
    }

    // Jeśli licznik nie jest ustawiony, pobierz domyślny z produktu o ID 49
    if ( empty( $countdown_end_date ) ) {
        $default_product = wc_get_product( 68 );
        $countdown_end_date = $default_product->get_meta( 'countdown_timer_end_date' );
    }

    if ( ! empty( $banner_url ) ) {
        echo '<div class="custom-banner-section" style="position: relative; width: 100%; text-align: center; margin-bottom: 20px;">';

        // Standard banner with timer
        echo '<img class="custom-banner-desktop" src="' . esc_url( $banner_url ) . '" alt="Custom Banner" style="width: 100%; height: auto;" />';

        // Countdown timer on the banner
        if ( ! empty( $countdown_end_date ) ) {
            echo '<div class="countdown-timer" style="position: absolute; top: 60%; left: 49%; transform: translate(-50%, -10%); background-color: transparent; color: red; padding: 10px; border-radius: 5px;">';
            echo '<p id="countdown"></p>';
            echo '</div>';

            echo '<script>
            var countdownDate = new Date("' . esc_js( $countdown_end_date ) . '").getTime(); 
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
        if ( ! empty( $mobile_banner_url ) ) {
            echo '<img class="custom-banner-mobile" src="' . esc_url( $mobile_banner_url ) . '" alt="Custom Mobile Banner" style="width: 100%; height: auto;" />';

            if ( ! empty( $countdown_end_date ) ) {
                echo '<div class="countdown-timer-mobile" style="position: absolute; top: 56%; left: 50%; transform: translate(-50%, -10%); background-color: transparent; color: red; padding: 10px; border-radius: 5px;">';
                echo '<p id="countdown-mobile"></p>';
                echo '</div>';

                echo '<script>
                var countdownDateMobile = new Date("' . esc_js( $countdown_end_date ) . '").getTime(); 
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
add_action( 'woocommerce_before_single_product', 'display_custom_banner_with_timer', 5 );

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

function display_custom_block_on_specific_product() {
  $specific_product_id = 49; // The ID of the specific product
  $specific_product_ostrze_stalowe_id = 52;
  $specific_product_ostrze_foliowe_id = 64846;
  $specific_product_etui_id = 15513;
  $specific_product_ostrze_klasyczne = 190815;
  $specific_product_trymer = 209188;
  $specific_product_pelnyzestaw = 185780;
  $specific_product_zestawmaszynkatrymer = 209201;
  $specific_product_shav1_id = 210446;
  $specific_product_myjka = 213609;
  $specific_product_paznokcie = 213962;
  $specific_product_balsam = 228529;
  $specific_product_dezo = 228536;
  $specific_product_kosmetyki = 228537;
  $specific_product_ostrze_trymer = 229416;
  $specific_product_handler = 230511;
  $specific_product_bokserki = 232124;
  $specific_product_3bokserki = 232131;
  $specific_product_5bokserki = 232132;
  $specific_product_crowner = 233723;
  $specific_product_shavwomen = 68;

  if ( is_product() && get_the_ID() == $specific_product_id ) {
    //   include __DIR__ . '/build/logobackground/render.php';
    include __DIR__ . '/build/produktowaslider/render.php';
      include __DIR__ . '/build/whoweare/render.php';
      include __DIR__ . '/build/whowearenew/render.php';
    //   include __DIR__ . '/build/blackwithtext/render.php';
    include __DIR__ . '/build/cechyspolecznosc/render.php';
    include __DIR__ . '/build/cechypewnosc/render.php';
    include __DIR__ . '/build/cechyostrza/render.php';
    //   include __DIR__ . '/build/cechycutfree/render.php';
      include __DIR__ . '/build/cechywodo/render.php';
      include __DIR__ . '/build/cechyzero/render.php';
    //   include __DIR__ . '/build/cechypodswietlanie/render.php';
    //   include __DIR__ . '/build/maszynkafunkcje/render.php';
      include __DIR__ . '/build/pudelkoproduktowa/render.php';
      include __DIR__ . '/build/ogrodnikproduktowa/render.php';
      include __DIR__ . '/build/faq/render.php';


  
  }

  if (is_product() && get_the_ID() == $specific_product_ostrze_stalowe_id ) {
    include __DIR__ . '/build/ostrzestaloweproduktowa/render.php';
    include __DIR__ . '/build/metodywysylkikup/render.php';
    include __DIR__ . '/build/faqostrzestalowe/render.php';
  }

  if (is_product() && get_the_ID() == $specific_product_ostrze_foliowe_id ) {
    include __DIR__ . '/build/ostrzefolioweproduktowa2/render.php';
    include __DIR__ . '/build/metodywysylkikup/render.php';
    include __DIR__ . '/build/faqostrzefoliowe/render.php';
  }

  if (is_product() && get_the_ID() == $specific_product_etui_id ) {
    include __DIR__ . '/build/etuiproduktowa/render.php';
    include __DIR__ . '/build/metodywysylkikup/render.php';
    include __DIR__ . '/build/faqetui/render.php';
  }

  if (is_product() && get_the_ID() == $specific_product_ostrze_klasyczne ) {
    include __DIR__ . '/build/ostrzeklasyczne/render.php';
    include __DIR__ . '/build/metodywysylkikup/render.php';
  }

  if (is_product() && get_the_ID() == $specific_product_trymer ) {
    include __DIR__ . '/build/whowearetrymer/render.php';
    include __DIR__ . '/build/blackwithtexttrymer/render.php';
    include __DIR__ . '/build/wodotrymer/render.php';
    include __DIR__ . '/build/komforttrymer/render.php';
    include __DIR__ . '/build/cutfreetrymer/render.php';
    include __DIR__ . '/build/ostrzetrymer/render.php';
    include __DIR__ . '/build/pudelkoproduktowatrymer/render.php';
    include __DIR__ . '/build/modelproduktowatrymer/render.php';
    include __DIR__ . '/build/faqtrymer/render.php';
}

if (is_product() && get_the_ID() == $specific_product_pelnyzestaw ) {
    include __DIR__ . '/build/pelnyzestaw/render.php';
    include __DIR__ . '/build/metodywysylkikup/render.php';
  }

  if (is_product() && get_the_ID() == $specific_product_zestawmaszynkatrymer ) {
    include __DIR__ . '/build/maszynkatrymerzestaw/render.php';
    include __DIR__ . '/build/metodywysylkikup/render.php';
  }




  if (is_product() && get_the_ID() == $specific_product_shav1_id ) {
    echo '<div id="custom-blocks-container">'; // Kontener
    echo '<div id="block1">'; include __DIR__ . '/build/whoweareshav1/render.php'; echo '</div>';
    echo '<div id="block2">'; include __DIR__ . '/build/blackwithtextshav1/render.php'; echo '</div>';
    echo '<div id="block3">'; include __DIR__ . '/build/cechyshav1wodo/render.php'; echo '</div>';
    echo '<div id="block4">'; include __DIR__ . '/build/cechyshav1cutfree/render.php'; echo '</div>';
    echo '<div id="block5">'; include __DIR__ . '/build/cechyshav1ostrzestalowe/render.php'; echo '</div>';
    echo '<div id="block6">'; include __DIR__ . '/build/cechyshav1lampa/render.php'; echo '</div>';
    echo '<div id="block7">'; include __DIR__ . '/build/cechyshav1stacja/render.php'; echo '</div>';
    echo '</div>';
}

if (is_product() && get_the_ID() == $specific_product_myjka ) {
    include __DIR__ . '/build/blackwithtextmyjka/render.php'; 
    include __DIR__ . '/build/cechymyjkakapiel/render.php'; 
    include __DIR__ . '/build/cechymyjkawygoda/render.php'; 
    include __DIR__ . '/build/cechymyjkasilikon/render.php'; 
    include __DIR__ . '/build/cechymyjkaoczyszczenie/render.php'; 
    include __DIR__ . '/build/faqmyjka/render.php';
}

if (is_product() && get_the_ID() == $specific_product_paznokcie ) {
    include __DIR__ . '/build/blackwithtextpaznokcie/render.php'; 
    include __DIR__ . '/build/cechypaznokcieobcinaki/render.php'; 
    include __DIR__ . '/build/cechypaznokciepilnik/render.php'; 
    include __DIR__ . '/build/cechypaznokcienozyczki/render.php'; 
    include __DIR__ . '/build/cechypaznokciepeseta/render.php'; 
    include __DIR__ . '/build/cechypaznokciezestaw/render.php'; 
    include __DIR__ . '/build/faqpaznokcie/render.php';
}

if (is_product() && get_the_ID() == $specific_product_balsam ) {
    include __DIR__ . '/build/whowearebalsam/render.php';
    include __DIR__ . '/build/blackwithtextbalsam/render.php'; 
    include __DIR__ . '/build/szachbalsam1/render.php'; 
    include __DIR__ . '/build/szachbalsam4/render.php';  
    include __DIR__ . '/build/szachbalsam2/render.php'; 
    include __DIR__ . '/build/szachbalsam3/render.php'; 
    include __DIR__ . '/build/faqbalsam/render.php';
}

if (is_product() && get_the_ID() == $specific_product_dezo ) {
    include __DIR__ . '/build/whowearedezo/render.php';
    include __DIR__ . '/build/blackwithtextdezo/render.php'; 
    include __DIR__ . '/build/szachdezo1/render.php'; 
    include __DIR__ . '/build/szachdezo4/render.php';  
    include __DIR__ . '/build/szachdezo2/render.php'; 
    include __DIR__ . '/build/szachdezo3/render.php'; 
    include __DIR__ . '/build/faqdezo/render.php';
}

if (is_product() && get_the_ID() == $specific_product_kosmetyki ) {
    include __DIR__ . '/build/szachbalsam1/render.php'; 
    include __DIR__ . '/build/szachdezo4/render.php'; 
}

if (is_product() && get_the_ID() == $specific_product_ostrze_trymer ) {
    include __DIR__ . '/build/ostrzetrymerproduktowa/render.php';
    include __DIR__ . '/build/metodywysylkikup/render.php';
}


if (is_product() && get_the_ID() == $specific_product_handler ) {
    include __DIR__ . '/build/whowearehandler/render.php';
    include __DIR__ . '/build/blackwithtexthandler/render.php';
    include __DIR__ . '/build/szach1handler/render.php';
    include __DIR__ . '/build/szach2handler/render.php';
    include __DIR__ . '/build/szach3handler/render.php';
    include __DIR__ . '/build/szach4handler/render.php';
    include __DIR__ . '/build/pudelkoproduktowahandler/render.php';
    include __DIR__ . '/build/faqhandler/render.php';
}


if (is_product() && get_the_ID() == $specific_product_bokserki) {
    include __DIR__ . '/build/blackwithtextbokserki/render.php';
    include __DIR__ . '/build/szach1bokserki/render.php';
    include __DIR__ . '/build/szach3bokserki/render.php';
    include __DIR__ . '/build/szach2bokserki/render.php';
    include __DIR__ . '/build/szach4bokserki/render.php';
    include __DIR__ . '/build/faqbokserki/render.php';
}

if (is_product() && get_the_ID() == $specific_product_3bokserki) {
    include __DIR__ . '/build/blackwithtextbokserki/render.php';
    include __DIR__ . '/build/szach1bokserki/render.php';
    include __DIR__ . '/build/szach3bokserki/render.php';
    include __DIR__ . '/build/szach2bokserki/render.php';
    include __DIR__ . '/build/szach4bokserki/render.php';
    include __DIR__ . '/build/faqbokserki/render.php';
}

if (is_product() && get_the_ID() == $specific_product_5bokserki) {
    include __DIR__ . '/build/blackwithtextbokserki/render.php';
    include __DIR__ . '/build/szach1bokserki/render.php';
    include __DIR__ . '/build/szach3bokserki/render.php';
    include __DIR__ . '/build/szach2bokserki/render.php';
    include __DIR__ . '/build/szach4bokserki/render.php';
    include __DIR__ . '/build/faqbokserki/render.php';
}


if (is_product() && get_the_ID() == $specific_product_crowner) {
    include __DIR__ . '/build/whowearecrowner/render.php';
    include __DIR__ . '/build/blackwithtextcrowner/render.php';
    include __DIR__ . '/build/szachcrowner1/render.php';
    include __DIR__ . '/build/szachcrowner2/render.php';
    include __DIR__ . '/build/szachcrowner3/render.php';
    include __DIR__ . '/build/szachcrowner4/render.php';
    include __DIR__ . '/build/faqcrowner/render.php';
}

if (is_product() && get_the_ID() == $specific_product_shavwomen) {
    include __DIR__ . '/build/whoweareshavwomen/render.php';
    include __DIR__ . '/build/blackwithtextshavwomen/render.php';
    include __DIR__ . '/build/szachshavwomen1/render.php';
    include __DIR__ . '/build/szachshavwomen2/render.php';
    include __DIR__ . '/build/szachshavwomen3/render.php';
    include __DIR__ . '/build/szachshavwomen4/render.php';
    include __DIR__ . '/build/faqshavwomen/render.php';
}

if (is_product() && get_the_ID() == $specific_product_boxwojownika) {
    include __DIR__ . '/build/wojowniktext/render.php';
    include __DIR__ . '/build/wojownikshav/render.php';
    include __DIR__ . '/build/wojowniktrymer/render.php';
    include __DIR__ . '/build/wojownikdezo/render.php';
    include __DIR__ . '/build/wojownikmyjka/render.php';
}
}

function display_custom_block_under_guttenberg_elements() {
  $specific_product_id = 68; // The ID of the specific product
  if ( is_product() && get_the_ID() == $specific_product_id ) {
    // include __DIR__ . '/build/stacjadokujaca/render.php';


}
}

add_action('woocommerce_after_single_product_summary', 'display_custom_block_on_specific_product', 4);
add_action('woocommerce_after_single_product_summary', 'display_custom_block_under_guttenberg_elements', 5);


// // change position of price
// // Remove the price from its default position and add to cart
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
// for now
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);
add_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 3);


// remove_action('woocommerce_cart_coupon', 'woocommerce_template_coupon_code', 10);
// add_action('woocommerce_custom_location', 'woocommerce_template_coupon_code');

// Custom function to display the price and add to cart button within a parent container
function custom_price_add_to_cart() {
  global $product;
  
  echo '<div class="custom-price-add-to-cart">';
  woocommerce_template_single_price();
  display_lowest_price_30_days();
  display_new_promotional_element();
  woocommerce_template_single_rating();
  woocommerce_template_single_add_to_cart();
  echo '</div>';
}


// Add the price below the short description
add_action( 'woocommerce_single_product_summary', 'custom_price_add_to_cart', 25 );


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
function display_cart_custom_image() {
  // Assuming you want to display the custom image of the first product in the cart
  $cart_items = WC()->cart->get_cart();

  if ( empty( $cart_items ) ) {
      return;
  }

  // Get the first product in the cart
  $first_cart_item = reset( $cart_items );
  $product_id = $first_cart_item['product_id'];
  $product = wc_get_product( $product_id );

  // Get the custom image URL
  $image_url = $product->get_meta( 'custom_image_url' );

  if ( ! empty( $image_url ) ) {
      echo '<div class="custom-image-section-cart" style="margin-top: 20px;">';
      echo '<img src="' . esc_url( $image_url ) . '" alt="Custom Image" style="max-width: 100%; height: auto;" />';
      echo '</div>';
  }
}
add_action( 'woocommerce_proceed_to_checkout', 'display_cart_custom_image', 20 );



// CART

function custom_cart_breadcrumbs() {
  // Custom breadcrumb trail
  $breadcrumb = array(
      'home'      => array(
          'title' => __('Koszyk', 'your-text-domain'),
          'url'   => wc_get_cart_url(),
      ),
      'moje-dane' => array(
          'title' => __('Moje Dane', 'your-text-domain'),
          'url'   => '#', // Replace '#' with the actual URL if you have one, otherwise leave it as is for a non-clickable breadcrumb.
      ),
      'wysylka'   => array(
          'title' => __('Wysyłka', 'your-text-domain'),
          'url'   => '#', // Replace '#' with the actual URL if you have one.
      ),
      'platnosc'  => array(
          'title' => __('Płatność', 'your-text-domain'),
          'url'   => '#', // Replace '#' with the actual URL if you have one.
      ),
  );

  // Output the custom breadcrumb
  echo '<nav class="woocommerce-breadcrumb">';
  foreach ( $breadcrumb as $crumb ) {
      echo '<a href="' . esc_url( $crumb['url'] ) . '">' . esc_html( $crumb['title'] ) . '</a>';
      if ( $crumb !== end( $breadcrumb ) ) {
          echo ' &gt; '; // Separator between breadcrumbs
      }
  }
  echo '</nav>';
}

add_action('woocommerce_before_cart', 'custom_cart_breadcrumbs', 5);


function custom_woocommerce_breadcrumbs() {
  return array(
      'delimiter'   => ' &gt; ', // Changes the delimiter between breadcrumb items
      'wrap_before' => '<nav class="woocommerce-breadcrumb" itemprop="breadcrumb">', // Changes the opening wrapper tag
      'wrap_after'  => '</nav>', // Changes the closing wrapper tag
      'before'      => '', // Changes the content before each breadcrumb
      'after'       => '', // Changes the content after each breadcrumb
      'home'        => _x( 'Home', 'breadcrumb', 'woocommerce' ), // Changes the text for the "Home" breadcrumb
  );
}
add_filter('woocommerce_breadcrumb_defaults', 'custom_woocommerce_breadcrumbs');



////////////
function add_icons_below_checkout_button() {
  ?>
  <div class="cart-icons">
      <div class="cart-icon-item-to-checkout">
      <svg width="30" height="21" viewBox="0 0 30 21" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M15.0001 21.0001C14.9576 21.0001 14.9153 20.9939 14.8745 20.9817C12.5518 20.3477 10.4577 19.0654 8.83705 17.2848C4.36492 12.3218 5.40792 5.55371 5.66211 4.24733C5.67635 4.17451 5.70888 4.10651 5.75664 4.04972C5.8044 3.99293 5.86581 3.94923 5.93511 3.92271C7.28698 3.40514 8.62442 2.86133 9.91111 2.30614C11.5662 1.59302 13.2029 0.837019 14.7774 0.0582691C14.8423 0.02161 14.9153 0.00162167 14.9898 9.45989e-05C15.0643 -0.00143247 15.1381 0.0155495 15.2044 0.0495191C16.7842 0.831332 18.4279 1.59039 20.0891 2.30571C21.3758 2.86133 22.7141 3.40514 24.0651 3.92446C24.1344 3.95098 24.1958 3.99468 24.2436 4.05147C24.2913 4.10826 24.3239 4.17626 24.3381 4.24908C24.5923 5.55546 25.6353 12.3236 21.1619 17.2866C19.5457 19.0624 17.4583 20.3427 15.1427 20.9782C15.0967 20.9933 15.0485 21.0007 15.0001 21.0001ZM6.47805 4.65114C6.2383 6.08702 5.4963 12.2706 9.48761 16.6986C10.9707 18.3275 12.8803 19.5084 15.0001 20.1076C17.1199 19.5084 19.0296 18.3275 20.5126 16.6986C24.5035 12.2706 23.7615 6.08702 23.5217 4.65114C22.2372 4.15633 20.9676 3.63833 19.7435 3.10939C18.1317 2.41552 16.537 1.68096 15.0001 0.924082C13.4632 1.68096 11.8685 2.41552 10.2567 3.11158C9.03261 3.63833 7.76298 4.15633 6.47805 4.65114Z" fill="black"/>
<path d="M14.9387 15C13.618 15 12.3514 14.5259 11.4175 13.682C10.4836 12.8381 9.95898 11.6935 9.95898 10.5C9.95898 9.30653 10.4836 8.16193 11.4175 7.31802C12.3514 6.47411 13.618 6 14.9387 6C15.0708 6 15.1974 6.04741 15.2908 6.1318C15.3842 6.21619 15.4367 6.33065 15.4367 6.45C15.4367 6.56935 15.3842 6.68381 15.2908 6.7682C15.1974 6.85259 15.0708 6.9 14.9387 6.9C14.1508 6.9 13.3806 7.11114 12.7254 7.50671C12.0703 7.90228 11.5597 8.46453 11.2582 9.12234C10.9567 9.78015 10.8778 10.504 11.0315 11.2023C11.1852 11.9007 11.5646 12.5421 12.1217 13.0456C12.6789 13.5491 13.3887 13.8919 14.1615 14.0308C14.9343 14.1697 15.7353 14.0984 16.4632 13.826C17.1912 13.5535 17.8133 13.0921 18.2511 12.5001C18.6888 11.908 18.9225 11.212 18.9225 10.5C18.9225 10.3807 18.9749 10.2662 19.0683 10.1818C19.1617 10.0974 19.2884 10.05 19.4204 10.05C19.5525 10.05 19.6792 10.0974 19.7726 10.1818C19.8659 10.2662 19.9184 10.3807 19.9184 10.5C19.917 11.6931 19.3918 12.8369 18.4583 13.6805C17.5247 14.5242 16.259 14.9987 14.9387 15Z" fill="black"/>
<path d="M14.53 12C14.4037 12 14.2826 11.9561 14.1934 11.8779L12.2886 10.2113C12.2097 10.1318 12.1681 10.0292 12.1721 9.92393C12.1762 9.81871 12.2256 9.71873 12.3105 9.6441C12.3954 9.56947 12.5094 9.52572 12.6296 9.52164C12.7499 9.51757 12.8674 9.55348 12.9586 9.62214L14.4872 10.9596L17.9633 7.15637C18.0024 7.11363 18.0507 7.07806 18.1055 7.05166C18.1603 7.02527 18.2205 7.00858 18.2826 7.00254C18.3448 6.99651 18.4077 7.00124 18.4678 7.01649C18.5279 7.03173 18.5839 7.05717 18.6328 7.09137C18.6816 7.12557 18.7223 7.16785 18.7524 7.21579C18.7826 7.26374 18.8017 7.31642 18.8086 7.37082C18.8155 7.42522 18.8101 7.48027 18.7927 7.53284C18.7752 7.58541 18.7462 7.63446 18.7071 7.67719L14.8976 11.8438C14.8558 11.8894 14.8034 11.9269 14.7439 11.9536C14.6844 11.9804 14.6192 11.9959 14.5524 11.9992L14.53 12Z" fill="black"/>
</svg>

          <span>2 lata gwarancji</span>
      </div>
      <div class="cart-icon-item-to-checkout">
      <svg width="28" height="22" viewBox="0 0 28 22" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M27.0518 4.95304H27.0514L27.0522 4.95931C27.0566 4.99418 27.0566 5.02947 27.0522 5.06433L27.0518 5.06428V5.07061V16.8272C27.0509 16.9968 26.9481 17.1493 26.7912 17.2138L26.791 17.2139L16.5963 21.446C16.5962 21.4461 16.5961 21.4461 16.596 21.4461C16.551 21.464 16.5032 21.4738 16.4548 21.4754C16.4065 21.4738 16.3587 21.464 16.3138 21.4462C16.3136 21.4461 16.3135 21.4461 16.3134 21.446L6.1186 17.2139L6.11842 17.2138C5.96156 17.1494 5.85877 16.997 5.85786 16.8273V14.8617V14.8117H5.80786H1.47195C1.23986 14.8117 1.05168 14.6235 1.05168 14.3914C1.05168 14.1593 1.23986 13.9711 1.47195 13.9711H5.80786H5.85786V13.9211V12.3128V12.2628H5.80786H3.9785C3.74641 12.2628 3.55823 12.0746 3.55823 11.8425C3.55823 11.6104 3.74641 11.4223 3.9785 11.4223H5.80786H5.85786V11.3723V9.76392V9.71392H5.80786H0.520272V9.70279V9.65279H0.470272C0.238178 9.65279 0.05 9.46461 0.05 9.23251C0.05 9.00042 0.238178 8.81224 0.470272 8.81224H5.86429H5.91429V8.76224V5.00007H5.91469L5.91389 4.99379C5.90948 4.95893 5.90948 4.92364 5.91389 4.88877L5.91429 4.88882V4.8825V4.85463C5.92252 4.83497 5.93221 4.81597 5.94331 4.79777L5.9686 4.76405L6.02132 4.70694L6.04674 4.68515L6.0916 4.66444H6.10413H6.11382L6.12281 4.66082L16.3136 0.555349L16.3137 0.555325C16.4147 0.514459 16.5278 0.514452 16.6288 0.555304C16.6288 0.555311 16.6289 0.555318 16.6289 0.555325L26.843 4.73127L26.8521 4.73499H26.8619H26.8745L26.9193 4.75569L26.9447 4.77748L26.9975 4.83459L27.0227 4.86831C27.0338 4.88652 27.0435 4.90551 27.0518 4.92517V4.95304ZM16.4875 1.4172L16.4689 1.40975L16.4503 1.41722L7.50102 5.0101L7.38754 5.05566L7.50043 5.10266L11.5213 6.77683L11.5406 6.78489L11.5599 6.77672L20.354 3.05687L20.4659 3.00954L20.3531 2.9644L16.4875 1.4172ZM15.9653 20.4053L16.0345 20.4341V20.3591V9.59462V9.56129L16.0038 9.54847L10.3605 7.19711L10.3604 7.19706L6.76751 5.711L6.6984 5.68242V5.75721V8.82338V8.87338H6.7484H7.68894C7.92104 8.87338 8.10922 9.06156 8.10922 9.29365C8.10922 9.52574 7.92104 9.71392 7.68894 9.71392H6.7484H6.6984V9.76392V11.3675V11.4175H6.7484H11.216C11.4481 11.4175 11.6363 11.6057 11.6363 11.8378C11.6363 12.0699 11.4481 12.2581 11.216 12.2581H6.7484H6.6984V12.3081V13.9164V13.9664H6.7484H8.70003C8.93212 13.9664 9.1203 14.1546 9.1203 14.3867C9.1203 14.6188 8.93212 14.807 8.70003 14.807H6.7484H6.6984V14.857V16.5123V16.5457L6.72918 16.5585L15.9653 20.4053ZM16.4543 8.84598L16.4736 8.85405L16.4929 8.84602L19.8224 7.45872L19.8225 7.45867L25.4328 5.10732L25.545 5.06029L25.4322 5.01483L21.5806 3.46293L21.5615 3.4552L21.5424 3.46327L12.739 7.19723L12.6298 7.24355L12.7392 7.28938L16.4543 8.84598ZM16.8892 20.3591V20.4341L16.9584 20.4053L26.2133 16.5585L26.2442 16.5457L26.2441 16.5122L26.2206 5.74769L26.2205 5.67284L26.1514 5.70165L19.5676 8.44802L19.5676 8.44804L16.92 9.54845L16.8892 9.56126V9.59462V20.3591Z" fill="black" stroke="white" stroke-width="0.1"/>
</svg>

          <span>Darmowa dostawa w 48h</span>
      </div>
      <div class="cart-icon-item-to-checkout">
      <svg width="30" height="23" viewBox="0 0 30 23" fill="none" xmlns="http://www.w3.org/2000/svg">
      <g clip-path="url(#clip0_783_3913)">
      <path d="M14.5029 17.6165C14.4558 17.6164 14.4092 17.606 14.3664 17.5862L8.5861 14.8059C8.53331 14.7797 8.48882 14.7394 8.45758 14.6894C8.42634 14.6395 8.40959 14.5818 8.40918 14.5229V8.30023C8.40902 8.24707 8.42238 8.19475 8.44801 8.14818C8.47364 8.10161 8.51069 8.06232 8.55568 8.03401C8.60067 8.0057 8.65212 7.9893 8.70519 7.98635C8.75827 7.9834 8.81122 7.994 8.85907 8.01715L14.6394 10.7974C14.6922 10.8236 14.7367 10.8639 14.7679 10.9139C14.7992 10.9638 14.8159 11.0215 14.8163 11.0804V17.3031C14.8163 17.3561 14.8029 17.4083 14.7773 17.4547C14.7516 17.5011 14.7146 17.5402 14.6697 17.5685C14.6199 17.6003 14.562 17.6169 14.5029 17.6165ZM9.03599 14.3308L14.1895 16.8128V11.2801L9.03599 8.79814V14.3308Z" fill="black"/>
      <path d="M14.5029 11.3913C14.4592 11.3944 14.4153 11.3884 14.374 11.3736L8.59369 8.5934C8.54002 8.5684 8.49461 8.5286 8.46279 8.47868C8.43097 8.42876 8.41406 8.37078 8.41406 8.31158C8.41406 8.25238 8.43097 8.19441 8.46279 8.14449C8.49461 8.09456 8.54002 8.05476 8.59369 8.02977L14.374 5.23691C14.4163 5.2169 14.4625 5.20651 14.5092 5.20651C14.556 5.20651 14.6022 5.2169 14.6445 5.23691L20.4248 8.01713C20.478 8.04285 20.5228 8.08307 20.5541 8.13315C20.5854 8.18324 20.6019 8.24115 20.6017 8.30021C20.6017 8.3589 20.5851 8.4164 20.5538 8.46605C20.5225 8.5157 20.4778 8.55547 20.4248 8.58076L14.6268 11.3736C14.5871 11.3878 14.545 11.3938 14.5029 11.3913ZM9.44798 8.29768L14.5029 10.7316L19.5579 8.30274L14.5029 5.86625L9.44798 8.29768Z" fill="black"/>
      <path d="M14.5029 17.6165C14.4438 17.6169 14.3858 17.6003 14.336 17.5685C14.2912 17.5403 14.2542 17.5011 14.2285 17.4547C14.2029 17.4083 14.1894 17.3561 14.1895 17.3031V11.083C14.1893 11.0239 14.2058 10.966 14.2371 10.9159C14.2684 10.8658 14.3132 10.8256 14.3664 10.7999L20.1467 8.01968C20.1946 7.99653 20.2475 7.98594 20.3006 7.98889C20.3537 7.99184 20.4051 8.00824 20.4501 8.03654C20.4951 8.06485 20.5321 8.10414 20.5578 8.15071C20.5834 8.19728 20.5968 8.2496 20.5966 8.30276V14.5203C20.5968 14.5794 20.5803 14.6373 20.549 14.6874C20.5177 14.7375 20.4728 14.7777 20.4197 14.8034L14.6267 17.5862C14.5879 17.6047 14.5458 17.615 14.5029 17.6165ZM14.8163 11.2801V16.8052L19.9698 14.3232V8.79814L14.8163 11.2801Z" fill="black"/>
      <path d="M11.611 10.0012C11.5406 10.0005 11.4725 9.97613 11.4177 9.93201C11.3629 9.88789 11.3245 9.8266 11.3087 9.75802C11.293 9.68943 11.3008 9.61753 11.3308 9.55391C11.3609 9.49028 11.4115 9.43863 11.4745 9.40726L17.2574 6.62704C17.3322 6.59141 17.418 6.58682 17.4961 6.61429C17.5743 6.64176 17.6384 6.69905 17.6744 6.77364C17.6924 6.8106 17.7029 6.85077 17.7052 6.89182C17.7076 6.93286 17.7018 6.97396 17.6882 7.01275C17.6745 7.05153 17.6533 7.08723 17.6258 7.11777C17.5983 7.14831 17.565 7.17309 17.5278 7.19067L11.7475 9.97089C11.7046 9.99053 11.6581 10.0009 11.611 10.0012Z" fill="black"/>
      <path d="M12.3867 12.7309C12.3395 12.7311 12.2928 12.7207 12.2502 12.7005L10.5441 11.8791C10.507 11.8615 10.4737 11.8368 10.4461 11.8062C10.4186 11.7757 10.3974 11.74 10.3838 11.7012C10.3702 11.6624 10.3644 11.6213 10.3667 11.5803C10.3691 11.5392 10.3796 11.499 10.3975 11.4621C10.4336 11.3875 10.4977 11.3302 10.5758 11.3027C10.654 11.2753 10.7398 11.2799 10.8146 11.3155L12.5206 12.1319C12.5834 12.1631 12.634 12.2146 12.6641 12.2779C12.6942 12.3413 12.7022 12.413 12.6868 12.4814C12.6713 12.5499 12.6334 12.6112 12.5789 12.6555C12.5245 12.6998 12.4568 12.7246 12.3867 12.7258V12.7309Z" fill="black"/>
      <path d="M5.00707 15.6021C4.9459 15.6031 4.88579 15.5861 4.83414 15.5533C4.78249 15.5205 4.74157 15.4734 4.71641 15.4176C3.76775 13.0715 3.69423 10.4626 4.50927 8.06678C5.32431 5.67101 6.97358 3.64815 9.15612 2.36731C11.3387 1.08647 13.909 0.633057 16.398 1.0898C18.8871 1.54654 21.129 2.88299 22.7145 4.85528C22.7439 4.88668 22.7666 4.92379 22.781 4.9643C22.7955 5.00482 22.8014 5.04788 22.7985 5.09079C22.7956 5.1337 22.7838 5.17556 22.7641 5.21375C22.7443 5.25194 22.7168 5.28564 22.6834 5.31276C22.65 5.33989 22.6114 5.35985 22.57 5.37141C22.5286 5.38296 22.4852 5.38587 22.4426 5.37994C22.4 5.37401 22.3591 5.35938 22.3224 5.33695C22.2857 5.31452 22.254 5.28478 22.2293 5.24957C20.7377 3.40002 18.6319 2.14726 16.2947 1.71911C13.9576 1.29096 11.5444 1.71588 9.49404 2.91658C7.4437 4.11728 5.89241 6.014 5.12232 8.26178C4.35222 10.5096 4.41446 12.9591 5.29773 15.1648C5.31671 15.2124 5.32377 15.2638 5.31829 15.3147C5.31281 15.3656 5.29495 15.4143 5.26628 15.4567C5.2376 15.4991 5.19899 15.5338 5.15381 15.5578C5.10862 15.5819 5.05824 15.5944 5.00707 15.5945V15.6021Z" fill="black"/>
      <path d="M14.515 22.0118C12.9369 22.0155 11.3781 21.6648 9.95376 20.9854C8.52939 20.306 7.2758 19.3154 6.28557 18.0866C6.23328 18.0196 6.20977 17.9345 6.2202 17.8501C6.23062 17.7658 6.27414 17.689 6.34117 17.6367C6.40821 17.5844 6.49326 17.5609 6.57763 17.5713C6.662 17.5818 6.73878 17.6253 6.79106 17.6923C8.29174 19.5397 10.4066 20.7859 12.7497 21.2037C15.0928 21.6215 17.508 21.1829 19.5547 19.968C21.6014 18.7532 23.143 16.8429 23.8984 14.5859C24.6538 12.3289 24.5726 9.87552 23.6695 7.67341C23.6525 7.6352 23.6434 7.59398 23.6426 7.55218C23.6418 7.51037 23.6494 7.46883 23.6649 7.42999C23.6804 7.39116 23.7035 7.35582 23.7328 7.32605C23.7622 7.29629 23.7972 7.2727 23.8358 7.25668C23.8745 7.24066 23.9159 7.23253 23.9577 7.23276C23.9995 7.233 24.0409 7.2416 24.0793 7.25806C24.1177 7.27452 24.1525 7.2985 24.1815 7.3286C24.2105 7.3587 24.2333 7.3943 24.2483 7.4333C25.1094 9.54087 25.2701 11.8694 24.7067 14.0753C24.1433 16.2812 22.8856 18.2475 21.1193 19.684C19.2493 21.1932 16.918 22.0149 14.515 22.0118Z" fill="black"/>
      <path d="M22.4969 5.38857H22.4615L19.5423 5.07264C19.4639 5.05884 19.3937 5.01573 19.3459 4.95205C19.2981 4.88838 19.2764 4.80891 19.2851 4.72979C19.2938 4.65066 19.3322 4.57781 19.3927 4.52601C19.4531 4.47421 19.531 4.44735 19.6105 4.45088L22.2088 4.73143L22.4994 2.12308C22.5013 2.08025 22.512 2.03827 22.5308 1.99973C22.5495 1.96119 22.576 1.92691 22.6086 1.89901C22.6411 1.87112 22.6791 1.85019 22.72 1.83754C22.761 1.82489 22.8041 1.82077 22.8467 1.82545C22.8893 1.83013 22.9305 1.8435 22.9678 1.86473C23.005 1.88597 23.0375 1.91462 23.0632 1.94891C23.0889 1.98321 23.1074 2.02241 23.1173 2.06411C23.1273 2.1058 23.1286 2.1491 23.1212 2.19132L22.8078 5.11055C22.7984 5.19313 22.7565 5.26859 22.6915 5.32033C22.6365 5.36493 22.5677 5.38905 22.4969 5.38857Z" fill="black"/>
      <path d="M6.1969 21.0968H6.16151C6.07915 21.0879 6.00367 21.0467 5.95157 20.9823C5.89947 20.9179 5.87499 20.8355 5.88349 20.7531L6.19942 17.8338C6.20335 17.793 6.21545 17.7534 6.23499 17.7174C6.25453 17.6814 6.28111 17.6496 6.31316 17.6241C6.37787 17.5719 6.4605 17.5474 6.54316 17.5558L9.46239 17.8692C9.54042 17.8831 9.61027 17.9261 9.65788 17.9895C9.7055 18.0528 9.72734 18.1319 9.71901 18.2107C9.71068 18.2895 9.67279 18.3622 9.61299 18.4142C9.55318 18.4663 9.47589 18.4937 9.39668 18.491L6.79085 18.2104L6.50525 20.8188C6.49715 20.895 6.4612 20.9655 6.40429 21.0168C6.34738 21.0681 6.27352 21.0966 6.1969 21.0968Z" fill="black"/>
      </g>
      <defs>
      <clipPath id="clip0_783_3913">
      <rect width="23" height="23" fill="white" transform="translate(3)"/>
      </clipPath>
      </defs>
      </svg>


          <span>30 dni na zwrot</span>
      </div>
  </div>
  <?php
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

add_filter( 'woocommerce_product_tabs', 'woo_custom_reviews_tab', 98 );
function woo_custom_reviews_tab( $tabs ) {

    // Save the original callback function
    $original_callback = $tabs['reviews']['callback'];

    // Modify the callback to include your custom content along with the original reviews
    $tabs['reviews']['callback'] = function() use ( $original_callback ) {
        // Add the custom review summary at the top
        custom_review_summary();

        // Display the default reviews content
        call_user_func( $original_callback );
    };

    return $tabs;
}

function custom_review_summary() {
  global $product;

  // // Check if the product exists and reviews are enabled
  if ( ! $product || ! comments_open() ) {
      echo '<p>Produkt lub opinie niedostępne.</p>';
      return;
  }

  // // Get basic product review data
  $average = $product->get_average_rating();
  $rating_count = $product->get_rating_count();
  $review_count = $product->get_review_count();

  // // Check if data is available
  if ( !$rating_count || !$review_count ) {
      echo '<p>Nie znaleziono opinii.</p>';
      return;
  }

   // Avoid division by zero for rating percentages
   $rating_count = $rating_count > 0 ? $rating_count : 1;

   // Query to get the count of each rating
   $args = array(
       'post_id' => $product->get_id(),
       'type'    => 'review',
       'status'  => 'approve',
       'meta_query' => array(
           array(
               'key'     => 'rating',
               'value'   => array(1, 2, 3, 4, 5),
               'compare' => 'IN'
           )
       )
   );

   $comments = get_comments( $args );
   $ratings = array( 1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0 );

   foreach ( $comments as $comment ) {
       $rating = intval( get_comment_meta( $comment->comment_ID, 'rating', true ) );
       if ( $rating > 0 && $rating <= 5 ) {
           $ratings[$rating]++;
       }
   }

   // HTML output for the review summary
   ?>
   <div class="review-summary">
       <div class="average-rating">
            <div class="rating-title">Opinie klientów</div>
            <div class="average-rating-subcontainer">
           <div class="rating-number"><?php echo esc_html( number_format( $average, 2 ) ); ?></div>
           <div class="average-rating-subsubcontainer">
           <div class="rating-stars">
                <div class="star-rating" role="img" aria-label="<?php printf( __( 'Rated %s out of 5', 'woocommerce' ), $average ); ?>">
                    <span style="width:<?php echo esc_attr( $average * 20 ); ?>%"></span>
                </div>
            </div>
           <div class="rating-count"><?php printf( __( '%d opinii', 'woocommerce' ), esc_html( $review_count ) ); ?></div>
           </div> 
          </div>
          </div>
       <div class="rating-breakdown">
       <?php 
    // Reverse the order of the $ratings array
    krsort( $ratings ); 
    ?>

           <?php foreach ( $ratings as $stars => $count ) : ?>
               <div class="rating-bar">
               <div class="star-rating" role="img" aria-label="<?php printf( __( 'Rated %d out of 5', 'woocommerce' ), $stars ); ?>">
        <span style="width:<?php echo esc_attr( $stars * 20 ); ?>%"></span>
    </div>
                   <div class="bar">
                    
                       <span style="width: <?php echo esc_attr( ( $count / $rating_count ) * 100 ); ?>%"></span>
                   </div>
                   <span class="bar-count-rating"><?php echo esc_html( $count ); ?></span>

               </div>
           <?php endforeach; ?>

       </div>
   </div>
   <?php
}



// baner i timer (pasek u gory strony)
// Dodanie sekcji ustawień globalnych w WordPressie
function add_promo_settings_page() {
    add_options_page(
        'Promo Bar Settings',
        'Promo Bar',
        'manage_options',
        'promo-bar-settings',
        'render_promo_settings_page'
    );
}
add_action( 'admin_menu', 'add_promo_settings_page' );

function render_promo_settings_page() {
    if ( isset( $_POST['save_promo_settings'] ) ) {
        update_option( 'global_promo_text', sanitize_text_field( $_POST['global_promo_text'] ) );
        update_option( 'global_promo_timer_end_date', sanitize_text_field( $_POST['global_promo_timer_end_date'] ) );
        echo '<div class="updated"><p>Settings saved!</p></div>';
    }

    $promo_text = get_option( 'global_promo_text', '' );
    $promo_timer_end_date = get_option( 'global_promo_timer_end_date', '' );

    ?>
    <div class="wrap">
        <h1>Promo Bar Settings</h1>
        <form method="post">
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Promo Text</th>
                    <td>
                        <input type="text" name="global_promo_text" value="<?php echo esc_attr( $promo_text ); ?>" class="regular-text" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Promo Timer End Date</th>
                    <td>
                        <input type="datetime-local" name="global_promo_timer_end_date" value="<?php echo esc_attr( $promo_timer_end_date ); ?>" class="regular-text" />
                    </td>
                </tr>
            </table>
            <?php submit_button( 'Save Settings', 'primary', 'save_promo_settings' ); ?>
        </form>
    </div>
    <?php
}

// Wyświetlanie paska na każdej stronie
function display_global_promo_bar() {
    $promo_text = get_option( 'global_promo_text', '' );
    $promo_timer_end_date = get_option( 'global_promo_timer_end_date', '' );

    if ( ! empty( $promo_text ) ) {
        echo '<div class="container promo" style="z-index: 9999;">';
        echo '<p class="promo-text">' . esc_html( $promo_text );
        
                // Wyświetlenie linku
                echo ' <a class="promo-tetxt-link" href="' . esc_url( site_url('/golarka-damska-shav-woman/') ) . '" style=""> KUP TERAZ </a>';


        if ( ! empty( $promo_timer_end_date ) ) {
            echo ' <span style="font-weight: normal; margin-left: 5px; margin-right: 5px;">| Pozostało jeszcze:</span>';
            echo ' <span id="promo-countdown" style="font-weight: bold;"></span>';
            echo '<script>
                var promoCountdownDate = new Date("' . esc_js( $promo_timer_end_date ) . '").getTime();
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
add_action( 'wp_body_open', 'display_global_promo_bar' );
function add_promo_bar_styles() {
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
function custom_enqueue_swiper() {
    wp_enqueue_style('swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css');
    wp_enqueue_script('swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'custom_enqueue_swiper');




// strona produktowa rozsuwane pointy
// Dodaj pola dla rozsuwanych elementów
// 1. Dodaj pola w panelu admina

function add_accordion_fields() {
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
function save_accordion_fields($post_id) {
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

    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            $value = strpos($field, 'svg') !== false ? wp_kses_post($_POST[$field]) : sanitize_text_field($_POST[$field]);
            $product->update_meta_data($field, $value);
        }
    }
    
    $product->save();
}
add_action('woocommerce_process_product_meta', 'save_accordion_fields');

// 3. Wyświetlanie z fallbackiem
function display_product_accordion() {
    global $post;
    
    $product = wc_get_product($post->ID);
    $default_product = wc_get_product(68); // Produkt fallback
    
    // Sprawdź czy funkcja jest aktywna
    $is_enabled = $product->meta_exists('show_accordion') ? 
                 $product->get_meta('show_accordion') : 
                 'yes';

    if ($is_enabled !== 'yes') return;

    echo '<div class="custom-accordion" style="margin: 0px 0; font-family: Roboto, sans-serif; color: #000 !important;">';

    for ($i = 1; $i <= 3; $i++) {
        // Pobierz wartości z fallbackiem
        $title = $product->get_meta("accordion_title_$i");
        $svg = $product->get_meta("accordion_svg_$i");
        $content = $product->get_meta("accordion_content_$i");

        if ((empty($title) || empty($content)) && $default_product) {
            $title = $default_product->get_meta("accordion_title_$i");
            $svg = $default_product->get_meta("accordion_svg_$i");
            $content = $default_product->get_meta("accordion_content_$i");
        }

        if (!empty($title) && !empty($content)) {
            echo '<div class="accordion-item" style="margin-bottom: 10px; border-radius: 5px;">
                <div class="accordion-header" onclick="toggleAccordion(this)" style="padding: 10px; cursor: pointer; display: flex; justify-content: space-between; align-items: center;">
                <div style="display: flex; gap: 10px; align-items: center;">
                '.$svg.'
                <span>'.$title.'</span>
                    </div>
                    <div style="display: flex; align-items: center;">
                        <svg class="accordion-icon" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" style="transition: transform 0.3s; margin-left: 10px;">
                            <path d="M8 11L3 6L4.4 4.6L8 8.2L11.6 4.6L13 6L8 11Z" fill="currentColor"/>
                        </svg>
                    </div>
                </div>
                <div class="accordion-content" style="display: none; padding: 10px;">
                    '.wpautop($content).'
                </div>
            </div>';
        }
    }
    
    echo '</div>';
}
add_action( 'woocommerce_share', 'display_product_accordion', 21);

  
// skrypt do rozwijania  pol produktowa
// Dodaj styl i skrypt
function add_accordion_scripts() {
    ?>
    <script>
        function toggleAccordion(header) {
            const content = header.nextElementSibling;
            content.style.display = content.style.display === 'none' ? 'block' : 'none';
        }
    </script>
    <?php
  }
  add_action('wp_footer', 'add_accordion_scripts');

  // skrypt zeby dalo sie svg dodac z edycji produktu 
  function cc_mime_types($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
  }
  add_filter('upload_mimes', 'cc_mime_types');

  function fix_svg() {
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



function mytheme_add_woocommerce_support2() {
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-slider'); // <- TO KONIECZNIE MUSI BYĆ AKTYWNE
    // Pozostałe ustawienia zostaw bez zmian
}
add_action('after_setup_theme', 'mytheme_add_woocommerce_support2');

add_action('after_setup_theme', 'remove_wc_gallery_zoom_lightbox', 99);

function remove_wc_gallery_zoom_lightbox() {
    remove_theme_support('wc-product-gallery-zoom');
    remove_theme_support('wc-product-gallery-lightbox');
}





/**
 * wylaczenie aktualizacji liczby przy koszyku w headerze bo generuje duze obciazenie
 * Wyłączenie WooCommerce Cart Fragments (AJAX)
 * Naprawia wysokie obciążenie CPU przy dużym ruchu.
 */
add_action( 'wp_enqueue_scripts', 'disable_wc_cart_fragments', 11 ); 
function disable_wc_cart_fragments() { 
    wp_dequeue_script( 'wc-cart-fragments' ); 
}