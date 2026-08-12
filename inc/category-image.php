<?php
/**
 * Adds a custom banner image field to WordPress Categories.
 */

// Add field to Add New Category form
add_action('category_add_form_fields', 'blendyblog_add_category_image_field');
function blendyblog_add_category_image_field() {
    wp_enqueue_media();
    ?>
    <div class="form-field term-group">
        <label for="category_banner_id"><?php _e('Banner Kategorii (Opcjonalny)', 'mojmotyw'); ?></label>
        <div class="category-banner-preview" style="margin-bottom: 10px;"></div>
        <input type="hidden" id="category_banner_id" name="category_banner_id" class="custom_media_url" value="">
        <button type="button" class="button category-banner-upload"><?php _e('Wybierz zdjęcie', 'mojmotyw'); ?></button>
        <button type="button" class="button category-banner-remove" style="display:none;"><?php _e('Usuń zdjęcie', 'mojmotyw'); ?></button>
        <p class="description"><?php _e('Wybierz własne zdjęcie w tle dla bannera tej kategorii. Jeśli nie wybierzesz, wyświetli się domyślny slider ze zdjęciami wpisów.', 'mojmotyw'); ?></p>
    </div>
    <?php
}

// Add field to Edit Category form
add_action('category_edit_form_fields', 'blendyblog_edit_category_image_field', 10, 2);
function blendyblog_edit_category_image_field($term, $taxonomy) {
    wp_enqueue_media();
    $image_id = get_term_meta($term->term_id, 'category_banner_id', true);
    ?>
    <tr class="form-field term-group-wrap">
        <th scope="row"><label for="category_banner_id"><?php _e('Banner Kategorii', 'mojmotyw'); ?></label></th>
        <td>
            <div class="category-banner-preview" style="margin-bottom: 10px;">
                <?php if ($image_id) : ?>
                    <?php echo wp_get_attachment_image($image_id, 'medium', false, array('style' => 'max-width: 300px; height: auto; border-radius: 8px;')); ?>
                <?php endif; ?>
            </div>
            <input type="hidden" id="category_banner_id" name="category_banner_id" value="<?php echo esc_attr($image_id); ?>">
            <button type="button" class="button category-banner-upload"><?php _e('Wybierz zdjęcie', 'mojmotyw'); ?></button>
            <button type="button" class="button category-banner-remove" style="<?php echo $image_id ? '' : 'display:none;'; ?>"><?php _e('Usuń zdjęcie', 'mojmotyw'); ?></button>
            <p class="description"><?php _e('Wybierz własne zdjęcie w tle dla bannera tej kategorii. Jeśli nie wybierzesz, wyświetli się domyślny slider ze zdjęciami wpisów.', 'mojmotyw'); ?></p>
        </td>
    </tr>
    <?php
}

// Save the custom field
add_action('created_category', 'blendyblog_save_category_image_field', 10, 2);
add_action('edited_category', 'blendyblog_save_category_image_field', 10, 2);
function blendyblog_save_category_image_field($term_id, $tt_id) {
    if (isset($_POST['category_banner_id'])) {
        update_term_meta($term_id, 'category_banner_id', sanitize_text_field($_POST['category_banner_id']));
    }
}

// Javascript to handle the media uploader
add_action('admin_footer', 'blendyblog_category_image_script');
function blendyblog_category_image_script() {
    $screen = get_current_screen();
    if ($screen && $screen->id === 'edit-category') :
    ?>
    <script>
    jQuery(document).ready(function($) {
        var file_frame;
        $(document).on('click', '.category-banner-upload', function(e) {
            e.preventDefault();
            var button = $(this);
            var wrapper = button.closest('.form-field, td');
            if (file_frame) {
                file_frame.open();
                return;
            }
            file_frame = wp.media.frames.file_frame = wp.media({
                title: 'Wybierz banner dla kategorii',
                button: { text: 'Użyj tego zdjęcia' },
                multiple: false
            });
            file_frame.on('select', function() {
                var attachment = file_frame.state().get('selection').first().toJSON();
                wrapper.find('#category_banner_id').val(attachment.id);
                wrapper.find('.category-banner-preview').html('<img src="' + attachment.url + '" style="max-width: 300px; height: auto; border-radius: 8px;">');
                wrapper.find('.category-banner-remove').show();
            });
            file_frame.open();
        });

        $(document).on('click', '.category-banner-remove', function(e) {
            e.preventDefault();
            var button = $(this);
            var wrapper = button.closest('.form-field, td');
            wrapper.find('#category_banner_id').val('');
            wrapper.find('.category-banner-preview').empty();
            button.hide();
        });
    });
    </script>
    <?php
    endif;
}
