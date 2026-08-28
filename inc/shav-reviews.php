<?php

/**
 * Native custom product reviews functionality for ShavWoman
 * Replaces woo-photo-reviews plugin by allowing photo uploads
 * and forcing name/email fields even for logged-in users.
 */

// 1. Modyfikacja formularza opinii (wymuszenie imienia/emaila, dodanie pola na zdjęcie, dodanie enctype)
add_filter('woocommerce_product_review_comment_form_args', 'shav_custom_product_review_form_args', 99);
function shav_custom_product_review_form_args($comment_form)
{
    // Pobieramy obecnego użytkownika, by wypełnić ew. domyślne dane, ale nie blokować edycji
    $commenter = wp_get_current_commenter();
    
    // Tworzymy HTML dla pól:
    $author_value = esc_attr($commenter['comment_author']);
    $email_value  = esc_attr($commenter['comment_author_email']);
    
    if (is_user_logged_in()) {
        $current_user = wp_get_current_user();
        if (empty($author_value)) $author_value = esc_attr($current_user->display_name);
        if (empty($email_value))  $email_value  = esc_attr($current_user->user_email);
    }

    $name_field = '<p class="comment-form-author"><label for="author">' . __('Name', 'woocommerce') . ' <span class="required">*</span></label> ' .
                  '<input id="author" name="author" type="text" value="' . $author_value . '" size="30" required /></p>';
                  
    $email_field = '<p class="comment-form-email"><label for="email">' . __('Email', 'woocommerce') . ' <span class="required">*</span></label> ' .
                   '<input id="email" name="email" type="email" value="' . $email_value . '" size="30" required /></p>';

    // Niestandardowy input pliku, by uniknąć polskich tekstów "Brak wybranego pliku" z przeglądarki
    $image_field = '<p class="comment-form-image">' . 
                   '<label style="display:block; margin-bottom:8px;">Bild hinzufügen (Optional)</label>' .
                   '<span class="shav-custom-file-upload" style="display:flex; align-items:center; gap:16px;">' .
                   '  <label for="review_image" class="shav-file-btn" style="cursor:pointer; background:#F1F2F3; padding:8px 16px; border-radius:48px; font-size:14px; font-weight:500; margin:0;">Datei auswählen</label>' .
                   '  <span class="shav-file-text" style="font-size:14px; color:#666;">Keine Datei ausgewählt</span>' .
                   '  <input id="review_image" name="review_image" type="file" accept="image/jpeg, image/png, image/webp" style="display:none;" />' .
                   '</span>' .
                   '</p>' . 
                   '<script>
                     document.addEventListener("DOMContentLoaded", function() {
                         const fileInput = document.getElementById("review_image");
                         const fileText = document.querySelector(".shav-file-text");
                         if (fileInput && fileText) {
                             fileInput.addEventListener("change", function() {
                                 if (fileInput.files.length > 0) {
                                     fileText.textContent = fileInput.files[0].name;
                                 } else {
                                     fileText.textContent = "Keine Datei ausgewählt";
                                 }
                             });
                         }
                     });
                   </script>';

    // Nadpisujemy comment_field dodając nasze własne pola przed polem z tekstem
    $original_comment_field = isset($comment_form['comment_field']) ? $comment_form['comment_field'] : '';
    
    // Zastępujemy całe comment_field, tak by miało też pola Imię/Email (dla zalogowanych nadpisze domyślne)
    $comment_form['comment_field'] = $name_field . $email_field . $image_field . $original_comment_field;
    
    // Skasujmy standardowe fields, żeby nie dublować dla NIEzalogowanych
    $comment_form['fields'] = array();
    
    // Ukrywamy napis "Zalogowany jako..."
    $comment_form['logged_in_as'] = '';
    
    return $comment_form;
}

// 2. Wymuszenie enctype multipart/form-data za pomocą JS (najbardziej niezawodne w WooCommerce bez grzebania w templatkach)
add_action('comment_form_top', 'shav_add_enctype_to_comment_form');
function shav_add_enctype_to_comment_form()
{
    echo '<script>document.getElementById("commentform").setAttribute("enctype", "multipart/form-data");</script>';
}

// 3. Przetwarzanie i zapis przesłanego zdjęcia oraz nadpisanego imienia i emaila
add_action('comment_post', 'shav_save_review_image_and_data', 10, 3);
function shav_save_review_image_and_data($comment_id, $comment_approved, $commentdata)
{
    // Obsługa zdjęcia
    if (!empty($_FILES['review_image']['name'])) {
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');

        $attachment_id = media_handle_upload('review_image', 0);

        if (!is_wp_error($attachment_id)) {
            add_comment_meta($comment_id, 'review_image_id', $attachment_id);
        }
    }
    
    // Zapisujemy niestandardowe imię i email (przydatne, gdy użytkownik jest zalogowany, a zmienił w formularzu)
    if (isset($_POST['author']) && !empty($_POST['author'])) {
        global $wpdb;
        $author = sanitize_text_field($_POST['author']);
        $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
        
        $wpdb->update(
            $wpdb->comments,
            array('comment_author' => $author, 'comment_author_email' => $email),
            array('comment_ID' => $comment_id)
        );
    }
}

// 4. Wyświetlanie zdjęcia na liście opinii
add_action('woocommerce_review_after_comment_text', 'shav_display_review_image', 10);
function shav_display_review_image($comment)
{
    $image_id = get_comment_meta($comment->comment_ID, 'review_image_id', true);
    if ($image_id) {
        $image_url = wp_get_attachment_image_url($image_id, 'medium');
        $image_full = wp_get_attachment_image_url($image_id, 'full');
        if ($image_url) {
            echo '<div class="shav-review-images" style="margin-top: 16px;">';
            echo '<a href="' . esc_url($image_full) . '" target="_blank">';
            echo '<img src="' . esc_url($image_url) . '" alt="Kundenbewertung" style="max-width: 150px; height: auto; border-radius: 8px; object-fit: cover; cursor: pointer;" />';
            echo '</a>';
            echo '</div>';
        }
    }
}

// 5. AJAX handler for loading more reviews
add_action('wp_ajax_shav_load_more_reviews', 'shav_load_more_reviews');
add_action('wp_ajax_nopriv_shav_load_more_reviews', 'shav_load_more_reviews');
function shav_load_more_reviews() {
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $offset = isset($_POST['offset']) ? intval($_POST['offset']) : 0;
    $limit = isset($_POST['limit']) ? intval($_POST['limit']) : 6;
    
    if (!$product_id) {
        wp_send_json_error();
    }
    
    $comments = get_comments(array(
        'post_id' => $product_id,
        'status' => 'approve',
        'type' => 'review',
    ));
    
    // Sort descending by comment_date logic standard in WC
    // Since get_comments without order args does standard sorting, we can just slice it to match our PHP slice
    
    if (empty($comments)) {
        wp_send_json_success(array('html' => '', 'count' => 0, 'is_last' => true));
    }
    
    $sliced_comments = array_slice($comments, $offset, $limit);
    
    ob_start();
    wp_list_comments(apply_filters('woocommerce_product_review_list_args', array('callback' => 'woocommerce_comments')), $sliced_comments);
    $html = ob_get_clean();
    
    $is_last = ($offset + $limit >= count($comments));
    
    wp_send_json_success(array(
        'html' => $html,
        'count' => count($sliced_comments),
        'is_last' => $is_last
    ));
}
