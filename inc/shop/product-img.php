<?php
// 1. Dodanie metaboxa w bocznym pasku (sidebar)
function shav_add_shop_image_meta_box() {
    add_meta_box(
        'shav_shop_image_meta_box',
        __('Zdjęcie na archiwum (Zastępuje domyślne)', 'woocommerce'),
        'shav_shop_image_meta_box_html',
        'product',
        'side',
        'low'
    );
}
add_action('add_meta_boxes', 'shav_add_shop_image_meta_box');

// 2. HTML i JS dla biblioteki mediów w metaboxie
function shav_shop_image_meta_box_html($post) {
    // Pobierz zapisane ID zdjęcia
    $image_id = get_post_meta($post->ID, 'shav_shop_image_id', true);
    
    // Potrzebujemy wgrać skrypty media uploadera, jeśli jeszcze ich nie ma
    wp_enqueue_media();
    ?>
    <div class="shav-shop-image-wrapper" style="text-align: center;">
        <p class="hide-if-no-js">
            <a href="#" class="shav-upload-custom-image" style="display: block; margin-bottom: 10px;">
                <?php if ($image_id) : ?>
                    <?php echo wp_get_attachment_image($image_id, 'medium', false, array('style' => 'max-width: 100%; height: auto; display: block; margin: 0 auto;')); ?>
                <?php else : ?>
                    <?php _e('Ustaw zdjęcie dla archiwum', 'woocommerce'); ?>
                <?php endif; ?>
            </a>
        </p>
        <p class="hide-if-no-js">
            <a href="#" class="shav-remove-custom-image" style="<?php echo $image_id ? '' : 'display:none;'; ?>color: #a00; text-decoration: none;">
                <?php _e('Usuń zdjęcie', 'woocommerce'); ?>
            </a>
        </p>
        <input type="hidden" name="shav_shop_image_id" id="shav_shop_image_id" value="<?php echo esc_attr($image_id); ?>" />
    </div>

    <script type="text/javascript">
    jQuery(document).ready(function($){
        var frame;
        $('.shav-upload-custom-image').on('click', function(e) {
            e.preventDefault();
            var button = $(this);
            
            if (frame) {
                frame.open();
                return;
            }
            
            frame = wp.media({
                title: 'Wybierz zdjęcie dla archiwum',
                button: { text: 'Użyj tego zdjęcia' },
                multiple: false
            });
            
            frame.on('select', function() {
                var attachment = frame.state().get('selection').first().toJSON();
                $('#shav_shop_image_id').val(attachment.id);
                // Użyj miniatury do podglądu
                var imgUrl = attachment.sizes && attachment.sizes.medium ? attachment.sizes.medium.url : attachment.url;
                button.html('<img src="' + imgUrl + '" style="max-width:100%;height:auto;display:block;margin:0 auto;" />');
                $('.shav-remove-custom-image').show();
            });
            
            frame.open();
        });
        
        $('.shav-remove-custom-image').on('click', function(e){
            e.preventDefault();
            $('#shav_shop_image_id').val('');
            $('.shav-upload-custom-image').html('<?php _e('Ustaw zdjęcie dla archiwum', 'woocommerce'); ?>');
            $(this).hide();
        });
    });
    </script>
    <?php
}

// 3. Zapisywanie ID zdjęcia do bazy danych
function shav_save_shop_image_meta($post_id) {
    if (!function_exists('wc_get_product')) {
        return;
    }
    $product = wc_get_product($post_id);
    if (!$product) {
        return;
    }
    
    if (isset($_POST['shav_shop_image_id'])) {
        $image_id = absint($_POST['shav_shop_image_id']);
        if ($image_id > 0) {
            $product->update_meta_data('shav_shop_image_id', $image_id);
        } else {
            $product->delete_meta_data('shav_shop_image_id');
        }
        $product->save_meta_data();
    }
}
add_action('woocommerce_process_product_meta', 'shav_save_shop_image_meta');

// 4. Podmiana zdjęcia na froncie poza stroną pojedynczego produktu
function shav_replace_product_image_on_archive($image, $product, $size, $attr, $placeholder) {
    if (is_admin() && !wp_doing_ajax()) {
        return $image;
    }
    if (function_exists('is_product') && is_product()) {
        return $image;
    }
    if (!is_a($product, 'WC_Product')) {
        return $image;
    }
    
    $shop_image_id = $product->get_meta('shav_shop_image_id');
    
    // Fallback do starego pola URL (jeśli miałeś już zapisane produkty po staremu)
    $old_shop_image_url = $product->get_meta('product_shop_image');
    
    if (empty($shop_image_id) && empty($old_shop_image_url)) {
        return $image;
    }

    // Jeśli jest nowe pole z ID (rekomendowane rozwiązanie)
    if (!empty($shop_image_id)) {
        $custom_attr = wp_parse_args($attr, array(
            'class'    => 'custom-shop-image attachment-' . $size . ' size-' . $size,
            'loading'  => 'lazy',
            'decoding' => 'async'
        ));
        $custom_image = wp_get_attachment_image($shop_image_id, $size, false, $custom_attr);
        
        return $custom_image ? $custom_image : $image;
    }
    
    // Jeśli jest stare pole z URL (obsługa legacy)
    if (!empty($old_shop_image_url)) {
        $alt = esc_attr($product->get_name());
        $url = esc_url($old_shop_image_url);
        return '<img src="' . $url . '" alt="' . $alt . '" class="custom-shop-image attachment-' . $size . ' size-' . $size . '" decoding="async" loading="lazy" />';
    }

    return $image;
}
add_filter('woocommerce_product_get_image', 'shav_replace_product_image_on_archive', 10, 5);
