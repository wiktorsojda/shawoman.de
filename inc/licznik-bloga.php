<?php
/* ==========================================================================
   BLOG AJAX VIEW COUNTER
   ========================================================================== */

add_action('wp_ajax_track_post_view', 'blendy_track_post_view');
add_action('wp_ajax_nopriv_track_post_view', 'blendy_track_post_view');

function blendy_track_post_view() {
    if (isset($_POST['post_id'])) {
        $post_id = intval($_POST['post_id']);
        if ($post_id > 0) {
            $views = get_post_meta($post_id, '_post_views_count', true);
            $views = $views ? intval($views) : 0;
            update_post_meta($post_id, '_post_views_count', $views + 1);
            echo $views + 1;
        }
    }
    wp_die();
}

add_action('wp_footer', 'blendy_post_view_ajax_script');
function blendy_post_view_ajax_script() {
    // Nie zliczamy odsłon administratorów/redaktorów
    if (is_single() && get_post_type() === 'post' && !current_user_can('edit_posts')) {
        $post_id = get_queried_object_id();
        ?>
        <script>
        document.addEventListener("DOMContentLoaded", function() {
            fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                },
                body: 'action=track_post_view&post_id=<?php echo $post_id; ?>'
            }).catch(function(e) { console.error(e); });
        });
        </script>
        <?php
    }
}
