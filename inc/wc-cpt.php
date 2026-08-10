<?php
/**
 * 1. REJESTRACJA CPT PROMOCJE
 */
function register_promocje_cpt() {
    $labels = [
        'name'               => 'Promocje',
        'singular_name'      => 'Promocja',
        'menu_name'          => 'Promocje',
        'add_new'            => 'Dodaj nową',
        'add_new_item'       => 'Dodaj nową promocję',
        'edit_item'          => 'Edytuj promocję',
        'all_items'          => 'Wszystkie promocje',
    ];

    $args = [
        'labels'             => $labels,
        'public'             => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'show_in_rest'       => true,
        'menu_position'      => 20,
        'menu_icon'          => 'dashicons-tag',
        'supports'           => ['title'],
        'has_archive'        => false,
    ];

    register_post_type('promocje', $args);
}
add_action('init', 'register_promocje_cpt');

/**
 * 2. DOSTOSOWANIE LISTY POSTÓW (KOLUMNY, MINIATURA, STATUS, KILL SWITCH)
 */
add_filter('manage_promocje_posts_columns', 'set_custom_edit_promocje_columns');
function set_custom_edit_promocje_columns($columns) {
    $new_columns = array();
    $new_columns['cb'] = $columns['cb'];
    $new_columns['title'] = $columns['title']; 
    $new_columns['promo_status'] = 'Status Kampanii';
    $new_columns['promo_priority'] = 'Priorytet';
    $new_columns['date'] = $columns['date'];
    return $new_columns;
}

// DOŁĄCZENIE MINIATURY OBOK TYTUŁU ZA POMOCĄ POST STATES
add_filter('display_post_states', 'blendygo_add_thumb_to_promo_title_state', 10, 2);
function blendygo_add_thumb_to_promo_title_state($post_states, $post) {
    if (is_admin() && $post->post_type === 'promocje') {
        $img = get_post_meta($post->ID, 'promo_banner_mob_timer', true) ?: get_post_meta($post->ID, 'promo_banner_mob', true);
        if ($img) {
            $post_states['promo_thumb'] = '<img src="' . esc_url($img) . '" style="width:40px; height:auto; border-radius:3px; margin-left:10px; vertical-align:middle; border:1px solid #ddd;">';
        } else {
            $post_states['promo_thumb'] = '<span class="dashicons dashicons-smartphone" style="font-size:30px; width:30px; height:30px; color:#ccc; margin-left:10px; vertical-align:middle;"></span>';
        }
    }
    return $post_states;
}

add_action('manage_promocje_posts_custom_column', 'custom_promocje_column', 10, 2);
function custom_promocje_column($column, $post_id) {
    switch ($column) {
        case 'promo_status':
            $is_active = get_post_meta($post_id, 'promo_is_active', true) ?: 'yes';
            $nonce = wp_create_nonce('promo_toggle_nonce');
            
            echo '<div id="promo-status-container-'.$post_id.'" style="display:flex; flex-direction:column; align-items:flex-start; gap:5px;">';
            
            // 1. STATUS
            if ($is_active === 'no') {
                echo '<span class="status-badge" style="background:#d63638; color:#fff; padding:3px 8px; border-radius:3px; font-weight:bold; font-size:11px;">WYŁĄCZONA MANUALNIE</span>';
            } else {
                $now = current_time('timestamp');
                $start = strtotime(get_post_meta($post_id, 'promo_date_start', true) ?: 0);
                $ext_start = strtotime(get_post_meta($post_id, 'promo_date_ext_start', true) ?: 0);
                $final = strtotime(get_post_meta($post_id, 'promo_date_final', true) ?: 0);
                $final_fixed = strtotime(get_post_meta($post_id, 'promo_date_final_fixed', true) ?: 0);
                $remove_ui = strtotime(get_post_meta($post_id, 'promo_date_remove_ui', true) ?: 0);

                $actual_final = $final_fixed ?: $final;
                $absolute_death = $remove_ui ?: $actual_final;

                if ($absolute_death && $now > $absolute_death) {
                    echo '<span class="status-badge" style="background:#50575e; color:#fff; padding:3px 8px; border-radius:3px; font-weight:bold; font-size:11px;">ZAKOŃCZONA</span>';
                } elseif ($actual_final && $absolute_death && $now > $actual_final && $now <= $absolute_death) {
                    echo '<span class="status-badge" style="background:#8224e3; color:#fff; padding:3px 8px; border-radius:3px; font-weight:bold; font-size:11px;">FAZA 4 (GRACE PERIOD)</span>';
                } elseif ($final_fixed && $final && $now >= $final && $now <= $final_fixed) {
                    echo '<span class="status-badge" style="background:#d63638; color:#fff; padding:3px 8px; border-radius:3px; font-weight:bold; font-size:11px;">FAZA 3 (PRZEDŁUŻENIE)</span>';
                } elseif ($ext_start && $actual_final && $now >= $ext_start && $now <= ($final ?: $final_fixed)) {
                    echo '<span class="status-badge" style="background:#d63638; color:#fff; padding:3px 8px; border-radius:3px; font-weight:bold; font-size:11px;">FAZA 2 (LICZNIK)</span>';
                } elseif ($start && $now >= $start) {
                    $phase1_end = $ext_start ?: ($final ?: $final_fixed);
                    if ($now < $phase1_end) {
                        echo '<span class="status-badge" style="background:#46b450; color:#fff; padding:3px 8px; border-radius:3px; font-weight:bold; font-size:11px;">FAZA 1 (AKTYWNA)</span>';
                    } else {
                        echo '<span class="status-badge" style="color:#888; font-size:11px;">Oczekuje / Błąd Dat</span>';
                    }
                } elseif ($start && $now < $start) {
                    echo '<span class="status-badge" style="background:#ffb900; color:#fff; padding:3px 8px; border-radius:3px; font-weight:bold; font-size:11px;">ZAPLANOWANA</span>';
                } else {
                    echo '<span class="status-badge" style="color:#888; font-size:11px;">Brak pełnych dat</span>';
                }
            }

            // 2. KILL SWITCH
            if ($is_active === 'no') {
                echo '<button class="button promo-quick-toggle" data-id="'.$post_id.'" data-nonce="'.$nonce.'" style="color:#d63638; border-color:#d63638;">Włącz</button>';
            } else {
                echo '<button class="button promo-quick-toggle" data-id="'.$post_id.'" data-nonce="'.$nonce.'" style="color:#46b450; border-color:#46b450;">Wyłącz</button>';
            }

            echo '</div>';
            break;

        case 'promo_priority':
            echo '<strong>' . esc_html(get_post_meta($post_id, 'promo_priority', true) ?: '0') . '</strong>';
            break;
    }
}

// AJAX HANDLER DLA SZYBKIEGO WYŁĄCZNIKA (KILL SWITCH)
add_action('wp_ajax_blendygo_toggle_promo_status', 'blendygo_toggle_promo_status_ajax');
function blendygo_toggle_promo_status_ajax() {
    check_ajax_referer('promo_toggle_nonce', 'nonce');
    if (!current_user_can('edit_posts')) wp_die();
    
    $post_id = intval($_POST['post_id']);
    $current_status = get_post_meta($post_id, 'promo_is_active', true) ?: 'yes';
    $new_status = ($current_status === 'no') ? 'yes' : 'no';
    
    update_post_meta($post_id, 'promo_is_active', $new_status);
    
    ob_start();
    if ($new_status === 'no') {
        echo '<span class="status-badge" style="background:#d63638; color:#fff; padding:3px 8px; border-radius:3px; font-weight:bold; font-size:11px;">WYŁĄCZONA MANUALNIE</span>';
    } else {
        $now = current_time('timestamp');
        $start = strtotime(get_post_meta($post_id, 'promo_date_start', true) ?: 0);
        $ext_start = strtotime(get_post_meta($post_id, 'promo_date_ext_start', true) ?: 0);
        $final = strtotime(get_post_meta($post_id, 'promo_date_final', true) ?: 0);
        $final_fixed = strtotime(get_post_meta($post_id, 'promo_date_final_fixed', true) ?: 0);
        $remove_ui = strtotime(get_post_meta($post_id, 'promo_date_remove_ui', true) ?: 0);

        $actual_final = $final_fixed ?: $final;
        $absolute_death = $remove_ui ?: $actual_final;

        if ($absolute_death && $now > $absolute_death) {
            echo '<span class="status-badge" style="background:#50575e; color:#fff; padding:3px 8px; border-radius:3px; font-weight:bold; font-size:11px;">ZAKOŃCZONA</span>';
        } elseif ($actual_final && $absolute_death && $now > $actual_final && $now <= $absolute_death) {
            echo '<span class="status-badge" style="background:#8224e3; color:#fff; padding:3px 8px; border-radius:3px; font-weight:bold; font-size:11px;">FAZA 4 (GRACE PERIOD)</span>';
        } elseif ($final_fixed && $final && $now >= $final && $now <= $final_fixed) {
            echo '<span class="status-badge" style="background:#d63638; color:#fff; padding:3px 8px; border-radius:3px; font-weight:bold; font-size:11px;">FAZA 3 (PRZEDŁUŻENIE)</span>';
        } elseif ($ext_start && $actual_final && $now >= $ext_start && $now <= ($final ?: $final_fixed)) {
            echo '<span class="status-badge" style="background:#d63638; color:#fff; padding:3px 8px; border-radius:3px; font-weight:bold; font-size:11px;">FAZA 2 (LICZNIK)</span>';
        } elseif ($start && $now >= $start) {
            $phase1_end = $ext_start ?: ($final ?: $final_fixed);
            if ($now < $phase1_end) {
                echo '<span class="status-badge" style="background:#46b450; color:#fff; padding:3px 8px; border-radius:3px; font-weight:bold; font-size:11px;">FAZA 1 (AKTYWNA)</span>';
            } else {
                echo '<span class="status-badge" style="color:#888; font-size:11px;">Oczekuje / Błąd Dat</span>';
            }
        } elseif ($start && $now < $start) {
            echo '<span class="status-badge" style="background:#ffb900; color:#fff; padding:3px 8px; border-radius:3px; font-weight:bold; font-size:11px;">ZAPLANOWANA</span>';
        } else {
            echo '<span class="status-badge" style="color:#888; font-size:11px;">Brak pełnych dat</span>';
        }
    }
    $status_html = ob_get_clean();

    wp_send_json_success(['new_status' => $new_status, 'status_html' => $status_html]);
}

// DODAJE SKRYPT DO LISTY POSTÓW
add_action('admin_footer-edit.php', 'blendygo_promo_list_scripts');
function blendygo_promo_list_scripts() {
    global $post_type;
    if ($post_type === 'promocje') {
        ?>
        <script>
        jQuery(document).ready(function($){
            $(document).on('click', '.promo-quick-toggle', function(e) {
                e.preventDefault();
                var btn = $(this);
                var post_id = btn.data('id');
                var nonce = btn.data('nonce');
                
                btn.text('Zapisywanie...');
                $.post(ajaxurl, {
                    action: 'blendygo_toggle_promo_status',
                    post_id: post_id,
                    nonce: nonce
                }, function(response) {
                    if(response.success) {
                        var container = $('#promo-status-container-' + post_id);
                        container.find('.status-badge').replaceWith(response.data.status_html);
                        
                        if (response.data.new_status === 'no') {
                            btn.html('Włącz');
                            btn.css({'color':'#d63638', 'border-color':'#d63638'});
                        } else {
                            btn.html('Wyłącz');
                            btn.css({'color':'#46b450', 'border-color':'#46b450'});
                        }
                    } else {
                        alert('Błąd podczas zapisywania.');
                        btn.text('Błąd');
                    }
                });
            });
        });
        </script>
        <?php
    }
}

/**
 * 3. ŁADOWANIE ASSETÓW ADMINA
 */
function promo_admin_assets($hook) {
    global $post_type;
    if ('promocje' !== $post_type) return;
    wp_enqueue_media();
}
add_action('admin_enqueue_scripts', 'promo_admin_assets');

/**
 * 4. AJAX DLA WYSZUKIWARKI PRODUKTÓW
 */
add_action('wp_ajax_promo_search_products', 'promo_search_products_ajax');
function promo_search_products_ajax() {
    check_ajax_referer('promo_search_nonce', 'nonce');
    $term = isset($_POST['term']) ? sanitize_text_field($_POST['term']) : '';
    
    $args = [
        'post_type'      => 'product',
        'post_status'    => 'publish',
        'posts_per_page' => 15,
        's'              => $term
    ];
    $query = new WP_Query($args);
    $results = [];
    if($query->have_posts()) {
        foreach($query->posts as $p) {
            $results[] = [
                'id' => $p->ID, 
                'title' => html_entity_decode(get_the_title($p->ID))
            ];
        }
    }
    wp_send_json($results);
}

/**
 * 5. REJESTRACJA META BOXA
 */
function add_promocje_meta_boxes() {
    add_meta_box('promo_details', 'Konfiguracja Kampanii', 'render_promocje_meta_box', 'promocje', 'normal', 'high');
}
add_action('add_meta_boxes', 'add_promocje_meta_boxes');

/**
 * 6. RENDEROWANIE INTERFEJSU
 */
function render_promocje_meta_box($post) {
    wp_nonce_field('promo_save_meta', 'promo_nonce');
    $meta = get_post_meta($post->ID);

    // DEFAULTY KOLORÓW
    $default_badge_bg = 'rgba(224, 224, 224, 0.8)';
    $default_badge_color = 'linear-gradient(90deg, #630303 1.11%, #C90606 96.67%)';
    $firm_colors = [
        'linear-gradient(90deg, #630303 1.11%, #C90606 96.67%)',
        'linear-gradient(85deg, #630303 -7.37%, #F00 107.37%)',
        'linear-gradient(265deg, #E0AC84 0%, #A4664A 100%)',
        '#FFFFFF',
        '#000000',
        'rgba(224, 224, 224, 0.8)'
    ];

    // --- STYLE ADMINA ---
    echo '<style>
        @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800;900&family=Work+Sans:wght@400;600;700;800;900&display=swap");

        .promo-admin-container { padding: 10px 0; font-family: sans-serif; }
        
        /* ZAKŁADKI UX */
        .promo-tabs { display: flex; border-bottom: 1px solid #ccc; margin-bottom: 20px; }
        .promo-tab { padding: 10px 20px; cursor: pointer; border: 1px solid transparent; border-bottom: none; background: #f1f1f1; margin-right: 5px; border-radius: 4px 4px 0 0; font-weight: 600; color: #555; }
        .promo-tab.active { background: #fff; border-color: #ccc; margin-bottom: -1px; color: #2271b1; }
        .promo-tab-content { display: none; padding-top: 10px; }
        .promo-tab-content.active { display: block; }

        .promo-section-title { font-size: 13px; font-weight: bold; border-bottom: 2px solid #2271b1; padding-bottom: 5px; margin: 30px 0 15px 0; text-transform: uppercase; color: #2271b1; }
        .promo-section-title:first-child { margin-top: 0; }
        
        /* TOOLTIP CSS */
        .promo-tooltip { position: relative; display: inline-block; margin-left: 8px; cursor: help; color: #2271b1; vertical-align: baseline; }
        .promo-tooltip .dashicons { font-size: 16px; width: 16px; height: 16px; }
        .promo-tooltip-text { visibility: hidden; width: 250px; background-color: #333; color: #fff; text-align: center; border-radius: 4px; padding: 8px; position: absolute; z-index: 1; bottom: 125%; left: 50%; transform: translateX(-50%); opacity: 0; transition: opacity 0.3s; font-size: 12px; font-weight: normal; text-transform: none; line-height: 1.4; box-shadow: 0 2px 5px rgba(0,0,0,0.2); }
        .promo-tooltip-text::after { content: ""; position: absolute; top: 100%; left: 50%; margin-left: -5px; border-width: 5px; border-style: solid; border-color: #333 transparent transparent transparent; }
        .promo-tooltip:hover .promo-tooltip-text { visibility: visible; opacity: 1; }

        .promo-flex-row { display: flex; flex-wrap: wrap; gap: 20px; margin-bottom: 10px; }
        .promo-field-group { flex: 1; min-width: 250px; margin-bottom: 5px; }
        .promo-field-group label.main-label { display: block; font-weight: 600; margin-bottom: 8px; color: #50575e; }
        
        
        /* KOLORY I SWATCHE */
        .promo-swatches { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 10px; }
        .promo-swatch { width: 32px; height: 32px; border-radius: 50%; border: 2px solid #fff; box-shadow: 0 0 0 1px #ccd0d4; cursor: pointer; position: relative; }
        .promo-swatch.is-selected { box-shadow: 0 0 0 2px #2271b1; transform: scale(1.1); }
        .promo-swatch.custom-trigger { background: #eee; display: flex; align-items: center; justify-content: center; font-size: 18px; font-weight: bold; }
        .custom-css-input { display: none; margin-top: 10px; }
        .custom-css-input.is-active { display: block; }
        
        /* UPLOADERY */
        .promo-media-card { background: #fdfdfd; border: 1px solid #ccd0d4; border-radius: 4px; padding: 12px; text-align: center; }
        .promo-preview-box { border: 1px dashed #b5bfc9; border-radius: 4px; background: #fff; min-height: 100px; display: flex; flex-direction: column; justify-content: center; align-items: center; margin-bottom: 10px; overflow: hidden; }
        .promo-preview-box img { max-width: 100%; height: auto; max-height: 120px; display: block; object-fit: contain; }
        .promo-preview-box span.dashicons { font-size: 30px; width: 30px; height: 30px; color: #dcdcde; }
        .promo-actions { display: flex; gap: 10px; justify-content: center; }
        .btn-remove { color: #d63638 !important; text-decoration: none !important; font-size: 12px; margin-top: 5px; display: inline-block; }
        .promo-input-text { width: 100%; padding: 8px; border: 1px solid #8c8f94; border-radius: 4px; }
        
        /* CHIPSY */
        .promo-multi-select-container { border: 1px solid #8c8f94; border-radius: 4px; background: #fff; position: relative; padding: 4px; min-height: 40px; cursor: text; }
        .promo-chips-area { display: flex; flex-wrap: wrap; gap: 5px; align-items: center; }
        .promo-chip { background: #f0f6fc; color: #2271b1; border: 1px solid #c2dbf1; padding: 4px 8px; border-radius: 16px; font-size: 12px; display: flex; align-items: center; gap: 5px; }
        .promo-chip-remove { cursor: pointer; color: #d63638; font-weight: bold; font-size: 14px; line-height: 1; padding-left: 3px; }
        .promo-search-input { border: none !important; outline: none !important; box-shadow: none !important; flex: 1; min-width: 120px; padding: 4px !important; background: transparent; }
        .promo-dropdown-list { position: absolute; top: 100%; left: 0; right: 0; background: #fff; border: 1px solid #ccd0d4; border-top: none; box-shadow: 0 4px 6px rgba(0,0,0,.1); max-height: 200px; overflow-y: auto; z-index: 100; margin: 0; padding: 0; list-style: none; display: none; border-radius: 0 0 4px 4px; }
        .promo-dropdown-list li { padding: 8px 10px; cursor: pointer; border-bottom: 1px solid #f0f0f1; font-size: 13px; }
        .promo-dropdown-list li:hover { background: #f0f6fc; color: #2271b1; }
        
        /* WIZUALNY EDYTOR LICZNIKA (MODAL) */
        .promo-live-modal { display: none; position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(0,0,0,0.85); z-index: 999999; justify-content: center; align-items: center; backdrop-filter: blur(5px); }
        .promo-live-modal-content { background: #fff; border-radius: 8px; width: 95%; max-width: 1400px; height: 90vh; display: flex; overflow: hidden; box-shadow: 0 15px 50px rgba(0,0,0,0.5); }
        
        /* PANEL BOCZNY (SIDEBAR) W MODALU */
        .promo-live-sidebar { width: 480px; background: #f1f1f1; border-right: 1px solid #ddd; display: flex; flex-direction: column; }
        .promo-live-header { padding: 15px 20px; border-bottom: 1px solid #ddd; display: flex; justify-content: space-between; align-items: center; background: #fff; }
        .promo-live-header h2 { margin: 0; font-size: 16px; color: #2271b1; }
        .promo-live-close { font-size: 24px; font-weight: bold; cursor: pointer; color: #555; line-height: 1; padding: 0 5px; }
        .promo-live-close:hover { color: #d63638; }
        .promo-live-sidebar-content { padding: 20px; overflow-y: auto; flex: 1; }
        
        /* KONTROLKI W SIDEBARZE */
        .editor-control-group { background: #fff; border: 1px solid #ddd; padding: 15px; margin-bottom: 15px; border-radius: 4px; box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
        .editor-control-group h4 { margin: 0 0 10px 0; font-size: 13px; color: #333; text-transform: uppercase; }
        .editor-label { font-size: 12px; color: #666; margin-bottom: 5px; display: block; font-weight: bold; }
        .editor-slider-row { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; }
        .editor-slider { flex: 1; }
        .editor-number-row { display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px; }
        .editor-number-input { width: 60px; text-align: center; border: 1px solid #ccc; border-radius: 4px; padding: 4px; }
        .btn-number { background: #f0f0f1; border: 1px solid #ccc; border-radius: 4px; cursor: pointer; width: 28px; height: 28px; font-weight: bold; display: flex; align-items: center; justify-content: center; }
        .btn-number:hover { background: #e0e0e1; }
        
        .view-switch { display: flex; gap: 5px; margin-bottom: 20px; background: #e0e0e0; padding: 4px; border-radius: 6px; }
        .view-btn { flex: 1; padding: 8px; text-align: center; cursor: pointer; border-radius: 4px; font-size: 13px; font-weight: bold; color: #555; }
        .view-btn.active { background: #fff; color: #2271b1; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }

        /* OBSZAR PODGLĄDU */
        .promo-live-preview-area { flex: 1; background: #e5e5e5; display: flex; justify-content: center; align-items: center; padding: 40px; overflow-y: auto; }
        .live-preview-canvas { position: relative; width: 100%; border-radius: 4px; box-shadow: 0 5px 25px rgba(0,0,0,0.15); overflow: hidden; transition: max-width 0.3s; background: #fff; }
        .live-preview-canvas img { width: 100%; height: auto; display: block; }
        .live-preview-timer-box { position: absolute; color: #f00; transform: translate(-50%, -10%); z-index: 99; }
        
        /* ODWZOROWANIE CSS ZE SCREENA DLA LICZNIKA */
        .lp-timer-flex { display: flex; column-gap: 20px; row-gap: 20px; font-family: "Poppins", sans-serif; height: 36px; line-height: normal; text-align: center; width: auto; justify-content: center; }
        .lp-wrapper { display: flex; flex-direction: column; align-items: center; }
        .lp-num { font-weight: bold; }
        .lp-lbl { text-transform: uppercase; margin-top: -2px; }
        .modal-swatch { border: 1px solid #ddd; width: 22px; height: 22px; border-radius: 50%; cursor: pointer; transition: transform 0.1s; }
        .modal-swatch:hover { transform: scale(1.15); }
        
        .validation-error { border-color: #d63638 !important; background: #fcf0f1 !important; }
        .validation-msg { color: #d63638; font-size: 12px; font-weight: bold; margin-top: 5px; display: none; }
    </style>';

    echo '<div class="promo-admin-container">';

    // --- ZAKŁADKI ---
    echo '<div class="promo-tabs">';
        echo '<div class="promo-tab active" data-tab="tab-main">Ustawienia Główne</div>';
        echo '<div class="promo-tab" data-tab="tab-banners">Bannery & Licznik</div>';
        echo '<div class="promo-tab" data-tab="tab-boosters">Funkcje Dodatkowe (Slider, Badge)</div>';
        echo '<div class="promo-tab" data-tab="tab-sets">Zestawy Promocyjne</div>';
        echo '<div class="promo-tab" data-tab="tab-drops">Produkty i Dropy</div>';
    echo '</div>';

    // --- WKLEJ NA GÓRZE ---
    echo '<div style="display:flex; justify-content:flex-end; margin-bottom:15px;">';
    echo '<button type="button" class="button" id="btn-paste-config"><span class="dashicons dashicons-clipboard" style="margin-top:3px;"></span> Wklej ustawienia</button>';
    echo '</div>';

    // ==========================================
    // ZAKŁADKA 1: USTAWIENIA GŁÓWNE
    // ==========================================
    echo '<div id="tab-main" class="promo-tab-content active">';

    // ==========================================
    // DODANO: TRYB TESTOWY (WIDOCZNE TYLKO DLA ADMINA)
    // ==========================================
    $admin_only = isset($meta['promo_admin_only']) && $meta['promo_admin_only'][0] === 'yes' ? 'checked' : '';
    echo '<div class="promo-flex-row" style="background:#fff3cd; padding:10px; border:1px solid #ffe69c; border-radius:4px; margin-bottom:20px;">';
        echo '<label style="font-weight:bold; color:#664d03; cursor:pointer;">';
        echo '<input type="checkbox" name="promo_admin_only" value="yes" '.$admin_only.'> TRYB TESTOWY: Aktywuj tę kampanię tylko dla zalogowanych administratorów (nie widoczna dla klientów)';
        echo '</label>';
    echo '</div>';

    // --- SEKCJA 1: ZASIĘG ---
    echo '<div class="promo-section-title">1. Produkty i kategorie objęte promocją <span class="promo-tooltip"><span class="dashicons dashicons-editor-help"></span><span class="promo-tooltip-text">Określa, na jakich stronach produktów wyświetli się kampania promocyjna.</span></span></div>';
    
    $is_global = isset($meta['promo_global']) && $meta['promo_global'][0] === 'yes' ? 'checked' : '';
    echo '<div style="margin-bottom:15px; padding:10px; background:#f0f6fc; border-left:4px solid #72aee6;">';
        echo '<label style="font-weight:bold; cursor:pointer; color:#2271b1;">';
        echo '<input type="checkbox" name="promo_global" value="yes" '.$is_global.'> AKTYWUJ GLOBALNIE (Promocja będzie działać na wszystkich produktach w sklepie bez względu na wybrane poniżej kategorie i produkty)';
        echo '</label>';
    echo '</div>';
    
    echo '<div class="promo-flex-row">';
        echo '<div class="promo-field-group"><label class="main-label">Kategorie:</label><div style="max-height:150px; overflow-y:auto; border:1px solid #ddd; padding:10px; background:#fff;">';
        $saved_cats = explode(',', (isset($meta['promo_categories']) ? $meta['promo_categories'][0] : ''));
        $categories = get_terms(['taxonomy' => 'product_cat', 'hide_empty' => false]);
        foreach ($categories as $cat) {
            $checked = in_array($cat->term_id, $saved_cats) ? 'checked' : '';
            echo '<label style="display:block;"><input type="checkbox" name="promo_categories[]" value="'.$cat->term_id.'" '.$checked.'> '.$cat->name.'</label>';
        }
        echo '</div></div>';
        
        echo '<div class="promo-field-group"><label class="main-label">Produkty:</label>';
        $saved_prods_str = isset($meta['promo_product_ids']) ? $meta['promo_product_ids'][0] : '';
        $saved_prods = !empty($saved_prods_str) ? explode(',', $saved_prods_str) : [];
        $saved_prod_data = [];
        if(!empty($saved_prods)) { foreach($saved_prods as $pid) { $saved_prod_data[] = ['id' => $pid, 'title' => get_the_title($pid)]; } }
        $saved_prods_json = json_encode($saved_prod_data);

        echo '<div class="promo-multi-select-container" id="promo-select-container"><div class="promo-chips-area" id="promo-chips-area"><input type="text" id="promo-product-search" class="promo-search-input" placeholder="Szukaj..." autocomplete="off"></div><ul class="promo-dropdown-list" id="promo-dropdown-list"></ul></div>';
        echo '<input type="hidden" name="promo_product_ids_hidden" id="promo-product-ids-hidden" value="'.esc_attr($saved_prods_str).'">';
        echo '</div>';
    echo '</div>';

    // --- SEKCJA 10: HARMONOGRAM I WALIDACJA DAT ---
    echo '<div class="promo-section-title">Harmonogram Kampanii <span class="promo-tooltip"><span class="dashicons dashicons-editor-help"></span><span class="promo-tooltip-text">Ustawia ramy czasowe dla Fazy 1, 2, 3 i 4 (Grace Period).</span></span></div>';
    echo '<p style="color: #666; font-style: italic; margin-top:-10px;">Zostaw pole "Pierwszy Koniec" puste, jeśli kampania odlicza tylko do Ostatecznego Końca. Puste pole "Zniknięcie Bannerów" ukryje promocję od razu po odliczeniu.</p>';
    
    echo '<div class="promo-flex-row">';
        echo '<div class="promo-field-group"><label class="main-label">Start</label><input type="datetime-local" id="date_start" name="promo_date_start" class="promo-input-text date-validate" value="'.(isset($meta['promo_date_start']) ? esc_attr($meta['promo_date_start'][0]) : '').'"><div class="validation-msg">Błąd: Ta data musi być chronologiczna!</div></div>';
        echo '<div class="promo-field-group"><label class="main-label">Start Odliczania</label><input type="datetime-local" id="date_ext" name="promo_date_ext_start" class="promo-input-text date-validate" value="'.(isset($meta['promo_date_ext_start']) ? esc_attr($meta['promo_date_ext_start'][0]) : '').'"><div class="validation-msg">Błąd: Ta data musi być chronologiczna!</div></div>';
        echo '<div class="promo-field-group"><label class="main-label">Pierwszy Koniec (Opcjonalnie)</label><input type="datetime-local" id="date_end" name="promo_date_final" class="promo-input-text date-validate" value="'.(isset($meta['promo_date_final']) ? esc_attr($meta['promo_date_final'][0]) : '').'"><div class="validation-msg">Błąd: Ta data musi być chronologiczna!</div></div>';
    echo '</div>';
    
    echo '<div class="promo-flex-row">';
        echo '<div class="promo-field-group"><label class="main-label" style="color:#d63638;">Ostateczny Koniec (Koniec odliczania)</label><input type="datetime-local" id="date_final_fixed" name="promo_date_final_fixed" class="promo-input-text date-validate" value="'.(isset($meta['promo_date_final_fixed']) ? esc_attr($meta['promo_date_final_fixed'][0]) : '').'"><div class="validation-msg">Błąd: Ta data musi być chronologiczna!</div></div>';
        echo '<div class="promo-field-group"><label class="main-label" style="color:#8224e3;">Zniknięcie Bannerów (Koniec Fazy 4)</label><input type="datetime-local" id="date_remove_ui" name="promo_date_remove_ui" class="promo-input-text date-validate" value="'.(isset($meta['promo_date_remove_ui']) ? esc_attr($meta['promo_date_remove_ui'][0]) : '').'"><div class="validation-msg">Błąd: Ta data musi być najpóźniejsza!</div></div>';
        echo '<div class="promo-field-group" style="visibility:hidden;"></div>';
    echo '</div>';

    // --- SEKCJA 2: BADGE I KOLORY ---
    echo '<div class="promo-section-title">2. Procent obniżki i główny wyróżnik (Badge) <span class="promo-tooltip"><span class="dashicons dashicons-editor-help"></span><span class="promo-tooltip-text">Konfiguruje główną etykietę promocyjną pojawiającą się na miniaturach i stronach produktów.</span></span></div>';
    echo '<div class="promo-flex-row">';
        echo '<div class="promo-field-group"><label class="main-label">Priorytet (wyższa liczba = ważniejsza)</label><input type="number" name="promo_priority" class="promo-input-text" value="'.(isset($meta['promo_priority']) ? esc_attr($meta['promo_priority'][0]) : '0').'"></div>';
        echo '<div class="promo-field-group"><label class="main-label">Procent obniżki (sama liczba)</label><input type="number" name="promo_percentage_text" class="promo-input-text" placeholder="np. 17" value="'.(isset($meta['promo_percentage_text']) ? esc_attr($meta['promo_percentage_text'][0]) : '').'"></div>';
        echo '<div class="promo-field-group"><label class="main-label">Tekst pod liczbą</label><input type="text" name="promo_small_text" class="promo-input-text" value="'.(isset($meta['promo_small_text']) ? esc_attr($meta['promo_small_text'][0]) : 'PROMOCJA').'"></div>';
        echo '<div class="promo-field-group"><label class="main-label">Kupon rabatowy</label><input type="text" name="promo_coupon_code" class="promo-input-text" placeholder="np. BLENDYGO10" value="'.(isset($meta['promo_coupon_code']) ? esc_attr($meta['promo_coupon_code'][0]) : '').'"></div>';
    echo '</div>';

    // SWITCHER: Ukryj w blokach FSE
    $hide_fse = isset($meta['promo_hide_fse_btn']) ? esc_attr($meta['promo_hide_fse_btn'][0]) : 'no';
    echo '<div class="promo-flex-row" style="margin-top: 10px;">';
        echo '<div class="promo-field-group"><label class="main-label">Ukryj przycisk promocji w blokach FSE (na Głównej)</label><select name="promo_hide_fse_btn" class="promo-input-text"><option value="no" '.selected($hide_fse, 'no', false).'>Nie (Domyślnie - Przycisk widoczny)</option><option value="yes" '.selected($hide_fse, 'yes', false).'>Tak (Ukryj przycisk)</option></select></div>';
        echo '<div class="promo-field-group" style="visibility:hidden;"></div>';
        echo '<div class="promo-field-group" style="visibility:hidden;"></div>';
        echo '<div class="promo-field-group" style="visibility:hidden;"></div>';
    echo '</div>';

    echo '<div class="promo-flex-row">';
        render_color_selector('Kolor Tła Badge (CSS lub z palety)', 'promo_badge_bg', $meta, $firm_colors, $default_badge_bg);
        render_color_selector('Kolor Liczby i Tekstu (CSS lub z palety)', 'promo_badge_color', $meta, $firm_colors, $default_badge_color);
    echo '</div>';

    echo '</div>'; // Koniec Zakładki 1


    // ==========================================
    // ZAKŁADKA 2: BANNERY I LICZNIK
    // ==========================================
    echo '<div id="tab-banners" class="promo-tab-content">';

    // --- UKRYTE POLA DLA DANYCH Z EDYTORA WIZUALNEGO ---
    $t_top = isset($meta['timer_pos_top']) ? esc_attr($meta['timer_pos_top'][0]) : '57';
    $t_left = isset($meta['timer_pos_left']) ? esc_attr($meta['timer_pos_left'][0]) : '50';
    $t_top_m = isset($meta['timer_pos_top_mob']) ? esc_attr($meta['timer_pos_top_mob'][0]) : '59';
    $t_left_m = isset($meta['timer_pos_left_mob']) ? esc_attr($meta['timer_pos_left_mob'][0]) : '38';
    
    $f_num = isset($meta['timer_font_size_num']) ? esc_attr($meta['timer_font_size_num'][0]) : '16';
    $f_lbl = isset($meta['timer_font_size_label']) ? esc_attr($meta['timer_font_size_label'][0]) : '14';
    $f_fam = isset($meta['timer_font_family']) ? esc_attr($meta['timer_font_family'][0]) : 'Work Sans';
    $f_wei_n = isset($meta['timer_font_weight_num']) ? esc_attr($meta['timer_font_weight_num'][0]) : '700';
    $f_wei_l = isset($meta['timer_font_weight_lbl']) ? esc_attr($meta['timer_font_weight_lbl'][0]) : '700';
    $gap_col = isset($meta['timer_gap_col']) ? esc_attr($meta['timer_gap_col'][0]) : '20';
    $gap_row = isset($meta['timer_gap_row']) ? esc_attr($meta['timer_gap_row'][0]) : '0';
    $c_num = isset($meta['timer_color_num']) ? esc_attr($meta['timer_color_num'][0]) : '#FF0000';
    $c_lbl = isset($meta['timer_color_lbl']) ? esc_attr($meta['timer_color_lbl'][0]) : '#000000';

    echo '<input type="hidden" name="timer_pos_top" id="val_top" value="'.$t_top.'">';
    echo '<input type="hidden" name="timer_pos_left" id="val_left" value="'.$t_left.'">';
    echo '<input type="hidden" name="timer_pos_top_mob" id="val_top_mob" value="'.$t_top_m.'">';
    echo '<input type="hidden" name="timer_pos_left_mob" id="val_left_mob" value="'.$t_left_m.'">';
    echo '<input type="hidden" name="timer_font_size_num" id="val_f_num" value="'.$f_num.'">';
    echo '<input type="hidden" name="timer_font_size_label" id="val_f_lbl" value="'.$f_lbl.'">';
    echo '<input type="hidden" name="timer_font_family" id="val_f_fam" value="'.$f_fam.'">';
    echo '<input type="hidden" name="timer_font_weight_num" id="val_f_wei_n" value="'.$f_wei_n.'">';
    echo '<input type="hidden" name="timer_font_weight_lbl" id="val_f_wei_l" value="'.$f_wei_l.'">';
    echo '<input type="hidden" name="timer_gap_col" id="val_gap_col" value="'.$gap_col.'">';
    echo '<input type="hidden" name="timer_gap_row" id="val_gap_row" value="'.$gap_row.'">';
    echo '<input type="hidden" name="timer_color_num" id="val_c_num" value="'.$c_num.'">';
    echo '<input type="hidden" name="timer_color_lbl" id="val_c_lbl" value="'.$c_lbl.'">';

    // --- PRZYCISK DO EDYTORA WIZUALNEGO ---
    echo '<div style="margin: 0 0 20px 0; text-align: left;">';
    echo '<button type="button" class="button button-primary button-hero" id="btn-live-preview">Otwórz Edytor Wizualny Licznika</button>';
    echo '</div>';

    // --- SEKCJA 3: BANNERY ---
    echo '<div class="promo-section-title">Bannery Produktu (Standard) <span class="promo-tooltip"><span class="dashicons dashicons-editor-help"></span><span class="promo-tooltip-text">Grafiki wyświetlane u samej góry w pojedynczym produkcie w standardowej fazie promocji.</span></span></div>';
    echo '<div class="promo-flex-row">';
        render_media_slot('Desktop Standard', 'promo_banner_desk', $meta);
        render_media_slot('Mobile Standard', 'promo_banner_mob', $meta);
    echo '</div>';

    echo '<div class="promo-section-title">Bannery Produktu (Wersja z Licznikiem) <span class="promo-tooltip"><span class="dashicons dashicons-editor-help"></span><span class="promo-tooltip-text">Grafiki wyświetlane u samej góry w pojedynczym produkcie, używane w trakcie odliczania.</span></span></div>';
    echo '<p style="color: #666; font-style: italic; margin-top:-10px;">Wgrywane, gdy kampania posiada aktywne odliczanie.</p>';
    echo '<div class="promo-flex-row">';
        render_media_slot('Desktop pod licznik', 'promo_banner_desk_timer', $meta);
        render_media_slot('Mobile pod licznik', 'promo_banner_mob_timer', $meta);
    echo '</div>';

    echo '<div class="promo-section-title">Bannery Listingu (Shop Page / Kategorie) <span class="promo-tooltip"><span class="dashicons dashicons-editor-help"></span><span class="promo-tooltip-text">Grafiki wyświetlane nad listą produktów w kategoriach.</span></span></div>';
    echo '<div class="promo-flex-row">';
        render_media_slot('Desktop Shop Page', 'promo_banner_shop_desk', $meta);
        render_media_slot('Mobile Shop Page', 'promo_banner_shop_mob', $meta);
    echo '</div>';

    echo '<div class="promo-section-title">Bannery Koszyka (Cart Page) <span class="promo-tooltip"><span class="dashicons dashicons-editor-help"></span><span class="promo-tooltip-text">Grafiki wyświetlane w koszyku, jeśli znajduje się w nim produkt objęty promocją.</span></span></div>';
    echo '<p style="color: #666; font-style: italic; margin-top:-10px;">Wyświetla się, gdy produkt objęty promocją znajdzie się w koszyku klienta.</p>';
    echo '<div class="promo-flex-row">';
        render_media_slot('Desktop Cart Banner', 'promo_banner_cart_desk', $meta);
        render_media_slot('Mobile Cart Banner', 'promo_banner_cart_mob', $meta);
    echo '</div>';

    echo '<div class="promo-section-title">Bannery Główna <span class="promo-tooltip"><span class="dashicons dashicons-editor-help"></span><span class="promo-tooltip-text">Główne bannery używane np. na stronie głównej.</span></span></div>';
    echo '<div class="promo-flex-row">';
        render_media_slot('Desktop Główna', 'promo_banner_main_desk', $meta);
        render_media_slot('Mobile Główna', 'promo_banner_main_mob', $meta);
    echo '</div>';

    echo '</div>'; // Koniec Zakładki 2


    // ==========================================
    // ZAKŁADKA 3: FUNKCJE DODATKOWE
    // ==========================================
    echo '<div id="tab-boosters" class="promo-tab-content">';

    // --- SEKCJA 8: ZDJĘCIA DLA SLIDERA ---
    echo '<div class="promo-section-title">8. Slider produktowy (Zdjęcia promocyjne) <span class="promo-tooltip"><span class="dashicons dashicons-editor-help"></span><span class="promo-tooltip-text">Zdjęcia do sekcji w opisie produktu (Slider) podczas trwania kampanii.</span></span></div>';
    echo '<div id="photos-wrapper">';
    
    // GENEROWANIE DO 5 ZDJĘĆ
    for ($i = 1; $i <= 5; $i++) {
        $has_photo = !empty($meta['promo_photo_'.$i][0]) || !empty($meta['promo_photo_mob_'.$i][0]);
        $display_photo = $has_photo ? 'block' : 'none';
        
        echo '<div class="photo-group" id="photo-group-'.$i.'" style="display:'.$display_photo.'; background:#f0f6fc; padding:15px; border:1px solid #c2dbf1; margin-bottom:15px; border-radius:4px;">';
        echo '<h4 style="margin-top:0;">Zdjęcie '.$i.' <a href="#" class="remove-photo" data-target="'.$i.'" style="color:#d63638; font-size:12px; font-weight:normal; text-decoration:none; float:right;">Usuń z zestawu</a></h4>';
        
        echo '<div class="promo-flex-row">';
            render_media_slot('Wersja Desktop (Opcjonalnie)', 'promo_photo_'.$i, $meta);
            render_media_slot('Wersja Mobile (Opcjonalnie)', 'promo_photo_mob_'.$i, $meta);
        echo '</div>';
        
        echo '</div>';
    }
    echo '</div>';
    
    // PRZYCISK DODAWANIA ZDJĘCIA
    echo '<div style="margin-bottom: 30px;">';
    echo '<button type="button" class="button" id="btn-add-photo"><span class="dashicons dashicons-plus-alt2" style="margin-top:3px;"></span> Dodaj kolejne zdjęcie</button>';
    echo '</div>';

    // --- SEKCJA 9: DODATKOWE BADGE ---
    echo '<div class="promo-section-title">9. Dodatkowe Badge (Krótki opis na produkcie) <span class="promo-tooltip"><span class="dashicons dashicons-editor-help"></span><span class="promo-tooltip-text">Dodaje małe etykiety z ikonami pod krótkim opisem produktu.</span></span></div>';
    
    echo '<div id="badges-wrapper">';
    
    // GENEROWANIE DO 3 BADGE'Y
    for ($i = 1; $i <= 3; $i++) {
        $has_data = !empty($meta['promo_badge_text_'.$i][0]) || !empty($meta['promo_badge_svg_'.$i][0]) || !empty($meta['promo_badge_image_'.$i][0]);
        $display = $has_data ? 'block' : 'none';
        $icon_type = isset($meta['promo_badge_icon_type_'.$i]) && $meta['promo_badge_icon_type_'.$i][0] === 'image' ? 'image' : 'svg';
        
        $b_width_ind = isset($meta['promo_badge_width_'.$i]) ? esc_attr($meta['promo_badge_width_'.$i][0]) : '100';
        $b_wauto = isset($meta['promo_badge_width_auto_'.$i]) ? esc_attr($meta['promo_badge_width_auto_'.$i][0]) : 'no';
        $b_align = isset($meta['promo_badge_align_'.$i]) ? esc_attr($meta['promo_badge_align_'.$i][0]) : 'flex-start';
        $b_mt = isset($meta['promo_badge_mt_'.$i]) ? esc_attr($meta['promo_badge_mt_'.$i][0]) : '12';
        $b_mb = isset($meta['promo_badge_mb_'.$i]) ? esc_attr($meta['promo_badge_mb_'.$i][0]) : '0';
        
        $b_py = isset($meta['promo_badge_py_'.$i]) ? esc_attr($meta['promo_badge_py_'.$i][0]) : '5';
        $b_px = isset($meta['promo_badge_px_'.$i]) ? esc_attr($meta['promo_badge_px_'.$i][0]) : '10';
        
        $b_isize_old = isset($meta['promo_badge_icon_size_'.$i]) ? esc_attr($meta['promo_badge_icon_size_'.$i][0]) : '';
        if ( !empty($b_isize_old) && !isset($meta['promo_badge_icon_size_val_'.$i]) ) {
            $b_isize_val = floatval($b_isize_old);
            $b_isize_unit = strpos($b_isize_old, 'px') !== false ? 'px' : 'em';
        } else {
            $b_isize_val = isset($meta['promo_badge_icon_size_val_'.$i]) ? esc_attr($meta['promo_badge_icon_size_val_'.$i][0]) : '1.2';
            $b_isize_unit = isset($meta['promo_badge_icon_size_unit_'.$i]) ? esc_attr($meta['promo_badge_icon_size_unit_'.$i][0]) : 'em';
        }

        echo '<div class="badge-group" id="badge-group-'.$i.'" style="display:'.$display.'; background:#f0f6fc; padding:15px; border:1px solid #c2dbf1; margin-bottom:15px; border-radius:4px;">';
        echo '<h4 style="margin-top:0;">Badge '.$i.' <a href="#" class="remove-badge" data-target="'.$i.'" style="color:#d63638; font-size:12px; font-weight:normal; text-decoration:none; float:right;">Usuń</a></h4>';
        
        echo '<div class="promo-flex-row">';
            echo '<div class="promo-field-group" style="flex:2;"><label class="main-label">Tekst Badge\'a</label><input type="text" name="promo_badge_text_'.$i.'" class="promo-input-text" value="'.(isset($meta['promo_badge_text_'.$i]) ? esc_attr($meta['promo_badge_text_'.$i][0]) : '').'"></div>';
            
            echo '<div class="promo-field-group" style="flex:1;"><label class="main-label">Typ Ikony</label>';
            echo '<label><input type="radio" name="promo_badge_icon_type_'.$i.'" value="svg" '.($icon_type === 'svg' ? 'checked' : '').' class="icon-type-toggle" data-target="'.$i.'"> Kod SVG</label> &nbsp; ';
            echo '<label><input type="radio" name="promo_badge_icon_type_'.$i.'" value="image" '.($icon_type === 'image' ? 'checked' : '').' class="icon-type-toggle" data-target="'.$i.'"> Wgraj obrazek</label>';
            echo '</div>';
        echo '</div>';
        
        echo '<div class="promo-flex-row">';
            // POLE SVG
            echo '<div class="promo-field-group icon-svg-wrapper-'.$i.'" style="'.($icon_type === 'svg' ? 'display:block;' : 'display:none;').'"><label class="main-label">Kod SVG</label><textarea name="promo_badge_svg_'.$i.'" class="promo-input-text" rows="3" placeholder="Wklej kod <svg>...">'.(isset($meta['promo_badge_svg_'.$i]) ? esc_textarea($meta['promo_badge_svg_'.$i][0]) : '').'</textarea></div>';
            
            // POLE OBRAZEK
            echo '<div class="promo-field-group icon-img-wrapper-'.$i.'" style="'.($icon_type === 'image' ? 'display:block;' : 'display:none;').'">';
            render_media_slot('Obrazek', 'promo_badge_image_'.$i, $meta);
            echo '</div>';
        echo '</div>';
        
        echo '<div class="promo-flex-row" style="align-items:flex-start;">';
            echo '<div style="flex:1; display:flex; flex-direction:column; gap:15px;">';
                echo '<div class="promo-flex-row" style="margin:0;">';
                    render_color_selector('Kolor Tła Badge', 'promo_badge_bg_color_'.$i, $meta, $firm_colors, '#f0f0f1');
                    render_color_selector('Kolor Tekstu Badge', 'promo_badge_text_color_'.$i, $meta, $firm_colors, '#000000');
                echo '</div>';
                echo '<div class="promo-field-group" style="margin:0;"><label class="main-label">Wysokość Ikony</label>';
                echo '<div style="display:flex; border: 1px solid #8c8f94; border-radius: 4px; overflow: hidden; height:34px; align-items:center;">';
                echo '<input type="number" step="0.1" name="promo_badge_icon_size_val_'.$i.'" value="'.$b_isize_val.'" style="border:none; border-radius:0; flex:1; outline:none; box-shadow:none; height:100%; padding:0 8px;">';
                echo '<select name="promo_badge_icon_size_unit_'.$i.'" style="border:none; border-left:1px solid #8c8f94; border-radius:0; background:#f6f7f7; padding: 0 10px; outline:none; height:100%; cursor:pointer;">';
                echo '<option value="px" '.selected($b_isize_unit, 'px', false).'>px</option>';
                echo '<option value="em" '.selected($b_isize_unit, 'em', false).'>em</option>';
                echo '</select>';
                echo '</div></div>';
            echo '</div>';
            echo '<div style="flex:1;">';
                render_media_slot('Tło Obrazkowe Badge', 'promo_badge_bg_image_'.$i, $meta);
            echo '</div>';
        echo '</div>';
        
        // POZYCJONOWANIE
        echo '<div class="promo-flex-row" style="background:#e4f0fa; padding:10px; border-radius:4px; margin-top:5px;">';
            echo '<div class="promo-field-group" style="flex:1;">';
            echo '<label class="main-label">Szerokość (%) <label style="font-weight:normal; font-size:12px; float:right;"><input type="checkbox" name="promo_badge_width_auto_'.$i.'" value="yes" '.checked($b_wauto, 'yes', false).'> Auto (fit-content)</label></label>';
            echo '<div class="editor-slider-row">';
                echo '<input type="range" class="editor-slider" name="promo_badge_width_'.$i.'" min="10" max="100" value="'.$b_width_ind.'" oninput="document.getElementById(\'bw_lbl_'.$i.'\').innerText = this.value + \'%\'">';
                echo '<span id="bw_lbl_'.$i.'" style="font-size:12px; font-weight:bold; width:40px; text-align:right;">'.$b_width_ind.'%</span>';
            echo '</div>';
            echo '</div>';

            echo '<div class="promo-field-group" style="flex:1;">';
            echo '<label class="main-label">Wyrównanie</label>';
            echo '<select name="promo_badge_align_'.$i.'" class="promo-input-text">';
            echo '<option value="flex-start" '.selected($b_align, 'flex-start', false).'>Do lewej</option>';
            echo '<option value="center" '.selected($b_align, 'center', false).'>Do środka</option>';
            echo '<option value="flex-end" '.selected($b_align, 'flex-end', false).'>Do prawej</option>';
            echo '</select>';
            echo '</div>';
        echo '</div>';

        // MARGINESY I PADDINGI
        echo '<div class="promo-flex-row" style="background:#e4f0fa; padding:10px; border-radius:4px; margin-top:5px;">';
            echo '<div class="promo-field-group" style="flex:0.25;">';
            echo '<label class="main-label">Margines Góra</label>';
            echo '<input type="number" name="promo_badge_mt_'.$i.'" class="promo-input-text" value="'.$b_mt.'">';
            echo '</div>';

            echo '<div class="promo-field-group" style="flex:0.25;">';
            echo '<label class="main-label">Margines Dół</label>';
            echo '<input type="number" name="promo_badge_mb_'.$i.'" class="promo-input-text" value="'.$b_mb.'">';
            echo '</div>';

            echo '<div class="promo-field-group" style="flex:0.25;"><label class="main-label">Padding Góra/Dół</label><input type="number" name="promo_badge_py_'.$i.'" class="promo-input-text" value="'.$b_py.'"></div>';
            echo '<div class="promo-field-group" style="flex:0.25;"><label class="main-label">Padding Lewo/Prawo</label><input type="number" name="promo_badge_px_'.$i.'" class="promo-input-text" value="'.$b_px.'"></div>';
        echo '</div>';
        
        echo '</div>';
    }
    echo '</div>';
    
    // PRZYCISK DODAWANIA BADGE'A
    echo '<div style="margin-bottom: 30px;">';
    echo '<button type="button" class="button" id="btn-add-badge"><span class="dashicons dashicons-plus-alt2" style="margin-top:3px;"></span> Dodaj badge</button>';
    echo '</div>';

    // --- SEKCJA 10: BANNER ATC (KONKURSOWY) ---
    echo '<div class="promo-section-title">10. Banner nad przyciskiem koszyka (Banner ATC / Konkursowy) <span class="promo-tooltip"><span class="dashicons dashicons-editor-help"></span><span class="promo-tooltip-text">Konfiguracja dodatkowego bannera i tekstu z regulaminem, wyświetlanego obok przycisku Dodaj do koszyka.</span></span></div>';
    
    $atc_reg_url = isset($meta['promo_banner_atc_reg_url']) ? esc_attr($meta['promo_banner_atc_reg_url'][0]) : '';
    $atc_text = isset($meta['promo_banner_atc_text']) ? wp_kses_post($meta['promo_banner_atc_text'][0]) : '';

    echo '<div class="promo-flex-row">';
        render_media_slot('Banner ATC', 'promo_banner_atc_desk', $meta);
    echo '</div>';
    
    echo '<div class="promo-flex-row" style="margin-bottom: 5px;">';
        echo '<div class="promo-field-group"><label class="main-label">Tekst pod bannerem z linkiem</label>';
        echo '<input type="text" name="promo_banner_atc_text" class="promo-input-text" value="'.$atc_text.'" placeholder="Kup blender i sprawdź [regulamin]"></div>';
    echo '</div>';
    echo '<p style="color: #666; font-size: 12px; margin-top:0; margin-bottom:15px;">Wpisz tekst pod bannerem. Fragment ujęty w nawiasy kwadratowe, np. [regulamin konkursu], zostanie automatycznie podlinkowany do URL regulaminu podanego poniżej.</p>';
    
    echo '<div class="promo-flex-row" style="margin-bottom: 20px;">';
        echo '<div class="promo-field-group"><label class="main-label">URL Regulaminu Konkursu (link do pliku PDF/Strony)</label>';
        echo '<input type="text" name="promo_banner_atc_reg_url" class="promo-input-text" value="'.$atc_reg_url.'" placeholder="https://..." style="max-width: 500px;"></div>';
    echo '</div>';

    // DODANO: ZWINIĘTA SEKCJA EDYCJI BLOKU FSE NA STRONIE GŁÓWNEJ
    echo '<details style="margin-top:30px; border:1px solid #c2dbf1; padding:15px; background:#f0f6fc; border-radius:4px;">';
    echo '<summary style="font-weight:bold; cursor:pointer; font-size:14px; color:#2271b1; outline:none;"><span class="dashicons dashicons-edit" style="vertical-align:text-bottom;"></span> NADPISANIE BLOKU FSE (STRONA GŁÓWNA)</summary>';
    echo '<div style="margin-top:20px; background:#fff; padding:15px; border:1px solid #ddd; border-radius:4px;">';

    echo '<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">';
    echo '<div class="promo-section-title" style="margin:0;">ZDJĘCIA BANERA</div>';
    echo '<button type="button" class="button button-primary" id="btn-fse-live-preview"><span class="dashicons dashicons-visibility" style="margin-top:3px;"></span> Otwórz Podgląd FSE</button>';
    echo '</div>';
    echo '<div class="promo-flex-row">';

    render_media_slot('Zdjęcie banera (desktop)', 'promo_fse_banner_image', $meta);
    render_media_slot('Zdjęcie banera (mobile)', 'promo_fse_banner_image_mobile', $meta);
    echo '</div>';

    // SUBTITLE
    echo '<div class="promo-section-title">PODTYTUŁ (BADGE)</div>';
    echo '<div class="promo-flex-row">';
    echo '<div class="promo-field-group"><label class="main-label">Tekst podtytułu</label><input type="text" name="promo_fse_subtitle" class="promo-input-text" value="'.(isset($meta['promo_fse_subtitle']) ? esc_attr($meta['promo_fse_subtitle'][0]) : '').'"></div>';
    echo '<div class="promo-field-group"><label class="main-label">Font Family</label><input type="text" name="promo_fse_subtitle_ff" class="promo-input-text" value="'.(isset($meta['promo_fse_subtitle_ff']) ? esc_attr($meta['promo_fse_subtitle_ff'][0]) : 'Poppins').'"></div>';
    echo '<div class="promo-field-group"><label class="main-label">Rozmiar fontu (px)</label><input type="number" name="promo_fse_subtitle_fs" class="promo-input-text" value="'.(isset($meta['promo_fse_subtitle_fs']) ? esc_attr($meta['promo_fse_subtitle_fs'][0]) : '16').'"></div>';
    echo '</div>';
    echo '<div class="promo-flex-row">';
    render_color_selector('Kolor tekstu', 'promo_fse_subtitle_color', $meta, $firm_colors, '#FCFCFC');
    render_color_selector('Tło (kolor/rgba)', 'promo_fse_subtitle_bg', $meta, $firm_colors, 'rgba(235, 194, 153, 0.60)');
    echo '</div>';

    // TITLE
    $use_title_svg = isset($meta['promo_fse_use_title_svg']) && $meta['promo_fse_use_title_svg'][0] === 'yes' ? 'checked' : '';
    echo '<div class="promo-section-title">TYTUŁ</div>';
    echo '<div class="promo-flex-row" style="margin-bottom:15px;">';
    echo '<label style="font-weight:bold;"><input type="checkbox" name="promo_fse_use_title_svg" value="yes" '.$use_title_svg.' class="fse-svg-toggle"> Używaj SVG zamiast tekstu</label>';
    echo '</div>';

    $display_fse_svg = ($use_title_svg !== '') ? 'block' : 'none';
    $fse_icon_type = isset($meta['promo_fse_title_icon_type']) && $meta['promo_fse_title_icon_type'][0] === 'image' ? 'image' : 'svg';
    echo '<div class="fse-svg-wrapper" style="display:'.$display_fse_svg.'; background:#f9f9f9; padding:15px; border:1px solid #ddd; margin-bottom:15px; border-radius:4px;">';
    
    echo '<div class="promo-flex-row" style="margin-bottom:15px;">';
    echo '<div class="promo-field-group" style="flex:1;"><label class="main-label">Typ Grafiki</label>';
    echo '<label><input type="radio" name="promo_fse_title_icon_type" value="svg" '.($fse_icon_type === 'svg' ? 'checked' : '').' class="icon-type-toggle" data-target="fse"> Kod SVG</label> &nbsp; ';
    echo '<label><input type="radio" name="promo_fse_title_icon_type" value="image" '.($fse_icon_type === 'image' ? 'checked' : '').' class="icon-type-toggle" data-target="fse"> Wgraj obrazek</label>';
    echo '</div></div>';

    echo '<div class="promo-flex-row">';
    echo '<div class="promo-field-group icon-svg-wrapper-fse" style="'.($fse_icon_type === 'svg' ? 'display:block;' : 'display:none;').'"><label class="main-label">Kod SVG (jeśli zaznaczono opcję wyżej)</label><textarea name="promo_fse_title_svg" class="promo-input-text" rows="4">'.(isset($meta['promo_fse_title_svg']) ? esc_textarea($meta['promo_fse_title_svg'][0]) : '').'</textarea></div>';
    
    echo '<div class="promo-field-group icon-img-wrapper-fse" style="'.($fse_icon_type === 'image' ? 'display:block;' : 'display:none;').'">';
    render_media_slot('Obrazek', 'promo_fse_title_image', $meta);
    echo '</div>';
    echo '</div>';

    echo '</div>';

    $display_fse_text = ($use_title_svg === '') ? 'block' : 'none';
    echo '<div class="fse-text-wrapper" style="display:'.$display_fse_text.';">';
    echo '<div class="promo-flex-row">';
    echo '<div class="promo-field-group"><label class="main-label">Tytuł (jeśli nie używamy SVG)</label><input type="text" name="promo_fse_title" class="promo-input-text" value="'.(isset($meta['promo_fse_title']) ? esc_attr($meta['promo_fse_title'][0]) : '').'"></div>';
    echo '<div class="promo-field-group"><label class="main-label">Font Family</label><input type="text" name="promo_fse_title_ff" class="promo-input-text" value="'.(isset($meta['promo_fse_title_ff']) ? esc_attr($meta['promo_fse_title_ff'][0]) : 'TAN - AEGEAN').'"></div>';
    echo '</div>';
    echo '<div class="promo-flex-row">';
    echo '<div class="promo-field-group"><label class="main-label">Rozmiar fontu (desktop, px)</label><input type="number" name="promo_fse_title_fs" class="promo-input-text" value="'.(isset($meta['promo_fse_title_fs']) ? esc_attr($meta['promo_fse_title_fs'][0]) : '56').'"></div>';
    echo '<div class="promo-field-group"><label class="main-label">Rozmiar fontu (mobile, px)</label><input type="number" name="promo_fse_title_fs_mobile" class="promo-input-text" value="'.(isset($meta['promo_fse_title_fs_mobile']) ? esc_attr($meta['promo_fse_title_fs_mobile'][0]) : '40').'"></div>';
    render_color_selector('Kolor tytułu', 'promo_fse_title_color', $meta, $firm_colors, '#FFFFFF');
    echo '</div>';
    echo '</div>';

    // BODY TEXT
    echo '<div class="promo-section-title">TEKST OPISU</div>';
    echo '<div class="promo-flex-row">';
    echo '<div class="promo-field-group"><label class="main-label">Tekst</label><textarea name="promo_fse_body_text" class="promo-input-text" rows="3">'.(isset($meta['promo_fse_body_text']) ? esc_textarea($meta['promo_fse_body_text'][0]) : '').'</textarea></div>';
    echo '</div>';
    echo '<div class="promo-flex-row">';
    echo '<div class="promo-field-group"><label class="main-label">Font Family</label><input type="text" name="promo_fse_body_ff" class="promo-input-text" value="'.(isset($meta['promo_fse_body_ff']) ? esc_attr($meta['promo_fse_body_ff'][0]) : 'Poppins').'"></div>';
    echo '<div class="promo-field-group"><label class="main-label">Rozmiar fontu (px)</label><input type="number" name="promo_fse_body_fs" class="promo-input-text" value="'.(isset($meta['promo_fse_body_fs']) ? esc_attr($meta['promo_fse_body_fs'][0]) : '16').'"></div>';
    render_color_selector('Kolor tekstu', 'promo_fse_body_color', $meta, $firm_colors, '#FCFCFC');
    echo '</div>';

    // BUTTON
    echo '<div class="promo-section-title">PRZYCISK</div>';
    echo '<div class="promo-flex-row">';
    echo '<div class="promo-field-group"><label class="main-label">Tekst przycisku</label><input type="text" name="promo_fse_button_text" class="promo-input-text" value="'.(isset($meta['promo_fse_button_text']) ? esc_attr($meta['promo_fse_button_text'][0]) : 'Poznaj kolekcję').'"></div>';
    echo '<div class="promo-field-group"><label class="main-label">Link</label><input type="text" name="promo_fse_button_link" class="promo-input-text" value="'.(isset($meta['promo_fse_button_link']) ? esc_attr($meta['promo_fse_button_link'][0]) : '#').'"></div>';
    echo '<div class="promo-field-group"><label class="main-label">Font Family</label><input type="text" name="promo_fse_button_ff" class="promo-input-text" value="'.(isset($meta['promo_fse_button_ff']) ? esc_attr($meta['promo_fse_button_ff'][0]) : 'Poppins').'"></div>';
    echo '<div class="promo-field-group"><label class="main-label">Rozmiar fontu (px)</label><input type="number" name="promo_fse_button_fs" class="promo-input-text" value="'.(isset($meta['promo_fse_button_fs']) ? esc_attr($meta['promo_fse_button_fs'][0]) : '16').'"></div>';
    echo '</div>';
    echo '<div class="promo-flex-row">';
    render_color_selector('Kolor tła przycisku', 'promo_fse_button_bg', $meta, $firm_colors, '#FCFCFC');
    render_color_selector('Kolor tekstu przycisku', 'promo_fse_button_text_color', $meta, $firm_colors, '#080808');
    render_color_selector('Kolor tła strzałki', 'promo_fse_arrow_bg', $meta, $firm_colors, '#080808');
    render_color_selector('Kolor strzałki', 'promo_fse_arrow_color', $meta, $firm_colors, '#FCFCFC');
    echo '</div>';

    echo '</div></details>';

    // SEKCJA: PRZYCISK I BANNER W MENU
    echo '<details style="margin-top:15px; border:1px solid #c2dbf1; padding:15px; background:#f0f6fc; border-radius:4px;">';
    echo '<summary style="font-weight:bold; cursor:pointer; font-size:14px; color:#2271b1; outline:none;"><span class="dashicons dashicons-menu" style="vertical-align:text-bottom;"></span> PRZYCISK I BANNER W MENU</summary>';
    echo '<div style="margin-top:20px; background:#fff; padding:15px; border:1px solid #ddd; border-radius:4px;">';

    echo '<div class="promo-section-title">PRZYCISK W HEADERZE (DESKTOP)</div>';
    $h_btn_en = isset($meta['promo_header_btn_enabled']) ? esc_attr($meta['promo_header_btn_enabled'][0]) : 'no';
    echo '<div class="promo-flex-row">';
    echo '<div class="promo-field-group"><label class="main-label">Nadpisz przycisk w headerze na czas trwania promo</label><select name="promo_header_btn_enabled" class="promo-input-text"><option value="yes" '.selected($h_btn_en, 'yes', false).'>Tak (Zmień przycisk na poniższy)</option><option value="no" '.selected($h_btn_en, 'no', false).'>Nie (Zostaw domyślne ustawienia bloku)</option></select></div>';
    echo '<div class="promo-field-group"><label class="main-label">Tekst przycisku</label><input type="text" name="promo_header_btn_text" class="promo-input-text" value="'.(isset($meta['promo_header_btn_text']) ? esc_attr($meta['promo_header_btn_text'][0]) : '').'"></div>';
    echo '<div class="promo-field-group"><label class="main-label">Link</label><input type="text" name="promo_header_btn_link" class="promo-input-text" value="'.(isset($meta['promo_header_btn_link']) ? esc_attr($meta['promo_header_btn_link'][0]) : '').'"></div>';
    echo '</div>';

    echo '<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">';
    echo '<div class="promo-section-title" style="margin:0;">BANNER W MENU (MOBILE)</div>';
    echo '<button type="button" class="button button-primary" id="btn-mm-live-preview"><span class="dashicons dashicons-visibility" style="margin-top:3px;"></span> Otwórz Podgląd Menu Bannera</button>';
    echo '</div>';
    
    $mm_ban_en = isset($meta['promo_mm_banner_enabled']) ? esc_attr($meta['promo_mm_banner_enabled'][0]) : 'no';
    echo '<div class="promo-flex-row">';
    echo '<div class="promo-field-group"><label class="main-label">Nadpisz banner w menu na czas trwania promo</label><select name="promo_mm_banner_enabled" class="promo-input-text"><option value="yes" '.selected($mm_ban_en, 'yes', false).'>Tak (Zmień banner na poniższy)</option><option value="no" '.selected($mm_ban_en, 'no', false).'>Nie (Zostaw domyślne ustawienia bloku)</option></select></div>';
    render_media_slot('Zdjęcie bannera', 'promo_mm_banner_image', $meta);
    echo '<div class="promo-field-group" style="visibility:hidden;"></div>';
    echo '</div>';
    
    echo '<p style="padding:10px; background:#e8f4fc; color:#2271b1; border-radius:4px; margin-top:15px;">Aby edytować teksty, SVG i wygląd elementów tego bannera, kliknij przycisk <strong>"Otwórz Podgląd Menu Bannera"</strong> powyżej.</p>';

    echo '</div></details>';

    echo '</div>'; // Koniec Zakładki 3


    // ==========================================
    // ZAKŁADKA 4: ZESTAWY PROMOCYJNE
    // ==========================================
    echo '<div id="tab-sets" class="promo-tab-content">';
    echo '<div class="promo-section-title">Konfiguracja Zestawów Promocyjnych <span class="promo-tooltip"><span class="dashicons dashicons-editor-help"></span><span class="promo-tooltip-text">Tworzy dedykowane sekcje "Kup Zestaw" na kartach wybranych produktów.</span></span></div>';
    echo '<p style="color: #666; font-style: italic; margin-top:-10px;">Zestawy nadpiszą obecne z functions. Używamy natywnej wyszukiwarki WooCommerce do wyboru produktów i docelowego linku.</p>';
    
    echo '<div id="sets-wrapper">';
    
    // GENEROWANIE DO 3 ZESTAWÓW W REPEATERZE
    for ($i = 1; $i <= 3; $i++) {
        // ODCZYT DANYCH Z BAZY
        $set_title = isset($meta['promo_set_title_'.$i]) ? esc_attr($meta['promo_set_title_'.$i][0]) : '';
        $set_btn_label = isset($meta['promo_set_btn_label_'.$i]) ? esc_attr($meta['promo_set_btn_label_'.$i][0]) : '';
        $set_header_label = isset($meta['promo_set_header_label_'.$i]) ? esc_attr($meta['promo_set_header_label_'.$i][0]) : ''; 
        $set_badge_type = isset($meta['promo_set_badge_type_'.$i]) ? esc_attr($meta['promo_set_badge_type_'.$i][0]) : '';
        $set_badge_custom = isset($meta['promo_set_badge_custom_'.$i]) ? esc_attr($meta['promo_set_badge_custom_'.$i][0]) : '';
        $set_target = isset($meta['promo_set_target_'.$i]) ? esc_attr($meta['promo_set_target_'.$i][0]) : '';
        $set_price_reg = isset($meta['promo_set_price_regular_'.$i]) ? esc_attr($meta['promo_set_price_regular_'.$i][0]) : '';
        $set_price_pro = isset($meta['promo_set_price_promo_'.$i]) ? esc_attr($meta['promo_set_price_promo_'.$i][0]) : '';
        $set_items = isset($meta['promo_set_items_'.$i]) ? esc_textarea($meta['promo_set_items_'.$i][0]) : '';
        $set_where_prods = isset($meta['promo_set_where_prods_'.$i]) ? $meta['promo_set_where_prods_'.$i][0] : '';
        $set_where_cats = isset($meta['promo_set_where_cats_'.$i]) ? $meta['promo_set_where_cats_'.$i][0] : '';
        $set_all_products = isset($meta['promo_set_all_products_'.$i]) && $meta['promo_set_all_products_'.$i][0] === 'yes' ? 'checked' : '';
        
        $where_prods_arr = !empty($set_where_prods) ? explode(',', $set_where_prods) : [];
        $where_cats_arr = !empty($set_where_cats) ? explode(',', $set_where_cats) : [];

        // UKRYWANIE PUSTYCH (ALE 1SZY ZAWSZE WIDOCZNY)
        $has_data = !empty($set_title) || !empty($set_target);
        $display_set = ($i === 1 || $has_data) ? 'block' : 'none';

        echo '<div class="set-group" id="set-group-'.$i.'" style="display:'.$display_set.'; background:#f0f6fc; padding:15px; border:1px solid #c2dbf1; margin-bottom:15px; border-radius:4px;">';
        echo '<h4 style="margin-top:0;">Zestaw '.$i.' <a href="#" class="remove-set" data-target="'.$i.'" style="color:#d63638; font-size:12px; font-weight:normal; text-decoration:none; float:right;">Usuń zestaw</a></h4>';
        
        // WIERSZ 1: Tytuł i Obrazek
        echo '<div class="promo-flex-row">';
            echo '<div class="promo-field-group"><label class="main-label">Tytuł zestawu (np.Zestaw zawiera:)</label>';
            echo '<input type="text" name="promo_set_title_'.$i.'" class="promo-input-text" value="'.$set_title.'"></div>';
            render_media_slot('Grafika Zestawu', 'promo_set_image_'.$i, $meta);
        echo '</div>';

        // WIERSZ 2: Link docelowy (Select2 - 1 produkt) ORAZ NAZWA PRZYCISKU
        echo '<div class="promo-flex-row">';
            echo '<div class="promo-field-group"><label class="main-label">Przycisk „Kup zestaw” prowadzi do:</label>';
            echo '<select class="wc-product-search" style="width:100%;" name="promo_set_target_'.$i.'" data-placeholder="Szukaj produktu docelowego..." data-action="woocommerce_json_search_products_and_variations">';
            if ($set_target) echo '<option value="'.esc_attr($set_target).'" selected="selected">'.esc_html(get_the_title($set_target)).'</option>';
            echo '</select></div>';
            
            echo '<div class="promo-field-group"><label class="main-label">Nazwa przycisku (Opcjonalnie, domyślnie ze słownika)</label>';
            echo '<input type="text" name="promo_set_btn_label_'.$i.'" class="promo-input-text" value="'.$set_btn_label.'" placeholder="np. Zobacz zestaw"></div>';
            
            echo '<div class="promo-field-group"><label class="main-label">Nagłówek zestawu (Opcjonalnie, domyślnie: Zestaw Promocyjny:)</label>';
            echo '<input type="text" name="promo_set_header_label_'.$i.'" class="promo-input-text" value="'.$set_header_label.'" placeholder="np. Zestaw Urodzinowy:"></div>';
        echo '</div>';

        // WIERSZ 2.5: Odznaka zestawu
        echo '<div class="promo-flex-row">';
            echo '<div class="promo-field-group"><label class="main-label">Typ odznaki (Pill na zestawie)</label>';
            echo '<select name="promo_set_badge_type_'.$i.'" class="promo-input-text">';
            echo '<option value="bestseller" '.selected($set_badge_type, 'bestseller', false).'>Najczęściej wybierane (ze słownika)</option>';
            echo '<option value="new" '.selected($set_badge_type, 'new', false).'>Nowość (ze słownika)</option>';
            echo '<option value="none" '.selected($set_badge_type, 'none', false).'>Brak odznaki</option>';
            echo '<option value="custom" '.selected($set_badge_type, 'custom', false).'>Własny tekst</option>';
            echo '</select></div>';
            
            echo '<div class="promo-field-group"><label class="main-label">Własny tekst odznaki</label>';
            echo '<input type="text" name="promo_set_badge_custom_'.$i.'" class="promo-input-text" value="'.$set_badge_custom.'" placeholder="Wpisz własny tekst"></div>';
        echo '</div>';

        // WIERSZ 3: Ceny
        echo '<div class="promo-flex-row">';
            echo '<div class="promo-field-group"><label class="main-label">Cena stara (przekreślona)</label>';
            echo '<input type="text" name="promo_set_price_regular_'.$i.'" class="promo-input-text" value="'.$set_price_reg.'" placeholder="np. 299,00 zł"></div>';
            echo '<div class="promo-field-group"><label class="main-label">Cena w zestawie (nowa)</label>';
            echo '<input type="text" name="promo_set_price_promo_'.$i.'" class="promo-input-text" value="'.$set_price_pro.'" placeholder="np. 249,00 zł"></div>';
        echo '</div>';

        // WIERSZ 4: Skład zestawu (Textarea)
        echo '<div class="promo-flex-row">';
            echo '<div class="promo-field-group"><label class="main-label">Skład zestawu (Wpisz każdy element w nowej linii)</label>';
            echo '<textarea name="promo_set_items_'.$i.'" class="promo-input-text" rows="4" placeholder="1x Blender BlendyGo 3&#10;1x Książka z przepisami">'.$set_items.'</textarea></div>';
        echo '</div>';

        // WIERSZ 5: Targetowanie
        echo '<hr style="border:0; border-top:1px dashed #c2dbf1; margin: 15px 0;">';
        echo '<h4 style="margin:0 0 10px 0; color:#50575e;">Targetowanie: Gdzie wyświetlać ten zestaw?</h4>';
        
        // CHECKBOX ZASTOSUJ WSZĘDZIE
        echo '<div class="promo-flex-row" style="margin-bottom: 15px; background: #e0ecf8; padding: 10px; border-radius: 4px;">';
        echo '<div class="promo-field-group" style="flex-basis: 100%;"><label style="font-weight:bold; cursor:pointer;"><input type="checkbox" name="promo_set_all_products_'.$i.'" value="yes" '.$set_all_products.'> <strong style="color:#2271b1;">Zastosuj ten zestaw globalnie do WSZYSTKICH produktów w sklepie</strong></label></div>';
        echo '</div>';
        
        echo '<div class="promo-flex-row">';
            // Produkty bazowe
            echo '<div class="promo-field-group"><label class="main-label">W wybranych produktach:</label>';
            echo '<select class="wc-product-search" multiple="multiple" style="width:100%;" name="promo_set_where_prods_'.$i.'[]" data-placeholder="Szukaj produktów bazowych..." data-action="woocommerce_json_search_products_and_variations">';
            foreach ($where_prods_arr as $pid) {
                if ($pid) echo '<option value="'.esc_attr($pid).'" selected="selected">'.esc_html(get_the_title($pid)).'</option>';
            }
            echo '</select></div>';
            
            // Kategorie bazowe
            echo '<div class="promo-field-group"><label class="main-label">Lub w całych kategoriach:</label>';
            echo '<div style="max-height:150px; overflow-y:auto; border:1px solid #8c8f94; padding:10px; background:#fff; border-radius:4px;">';
            $categories = get_terms(['taxonomy' => 'product_cat', 'hide_empty' => false]);
            foreach ($categories as $cat) {
                $checked = in_array($cat->term_id, $where_cats_arr) ? 'checked' : '';
                echo '<label style="display:block; margin-bottom:5px; cursor:pointer;"><input type="checkbox" name="promo_set_where_cats_'.$i.'[]" value="'.$cat->term_id.'" '.$checked.'> '.$cat->name.'</label>';
            }
            echo '</div></div>';
        echo '</div>';

        echo '</div>'; // Koniec .set-group
    }
    echo '</div>'; // Koniec #sets-wrapper

    // PRZYCISK DODAWANIA ZESTAWU
    echo '<div style="margin-bottom: 30px;">';
    echo '<button type="button" class="button" id="btn-add-set"><span class="dashicons dashicons-plus-alt2" style="margin-top:3px;"></span> Dodaj kolejny zestaw</button>';
    echo '</div>';
    
    echo '</div>'; // Koniec Zakładki Zestawów

    // ==========================================
    // ZAKŁADKA 5: PRODUKTY I DROPY
    // ==========================================
    echo '<div id="tab-drops" class="promo-tab-content">';
    
    echo '<div class="promo-section-title">Konfiguracja Bloku Aurora</div>';
    echo '<p style="color: #666; font-style: italic; margin-top:-10px;">Podaj produkty lub warianty, które mają podmienić standardową ofertę w bloku Aurora (czwarta karta zawsze będzie głównym produktem).</p>';
    echo '<div class="promo-flex-row">';
    for ($i = 1; $i <= 3; $i++) {
        $val = isset($meta['promo_aurora_product_'.$i]) ? esc_attr($meta['promo_aurora_product_'.$i][0]) : '';
        echo '<div class="promo-field-group"><label class="main-label">Wybierz Produkt/Wariant '.$i.'</label>';
        echo '<select class="wc-product-search" style="width:100%;" name="promo_aurora_product_'.$i.'" data-placeholder="Szukaj produktu lub wariantu..." data-action="woocommerce_json_search_products_and_variations">';
        if ($val && function_exists('wc_get_product')) {
            $p = wc_get_product($val);
            if ($p) echo '<option value="'.esc_attr($val).'" selected="selected">'.esc_html(wp_strip_all_tags($p->get_formatted_name())).'</option>';
        }
        echo '</select></div>';
    }
    echo '</div>';

    echo '<div class="promo-section-title">Limitowane Dropy Wariantów</div>';
    echo '<p style="color: #666; font-style: italic; margin-top:-10px;">Wybierz warianty, które będą ukryte w sklepie i włączone automatycznie tylko na czas trwania kampanii.</p>';
    $drop_val = isset($meta['promo_drop_variations']) ? $meta['promo_drop_variations'][0] : '';
    $drop_arr = !empty($drop_val) ? explode(',', $drop_val) : [];
    echo '<div class="promo-flex-row">';
    echo '<div class="promo-field-group" style="flex:1;"><label class="main-label">Wybierz Warianty do dropu</label>';
    echo '<select class="wc-product-search" multiple="multiple" style="width:100%;" name="promo_drop_variations[]" data-placeholder="Szukaj wariantów..." data-action="woocommerce_json_search_products_and_variations">';
    foreach ($drop_arr as $dpid) {
        if ($dpid && function_exists('wc_get_product')) {
            $p = wc_get_product(trim($dpid));
            if ($p) echo '<option value="'.esc_attr(trim($dpid)).'" selected="selected">'.esc_html(wp_strip_all_tags($p->get_formatted_name())).'</option>';
        }
    }
    echo '</select></div>';
    echo '</div>';

    echo '<div class="promo-section-title">Dynamic Pricing (Zniżki w locie)</div>';
    echo '<p style="color: #666; font-style: italic; margin-top:-10px;">Możesz dodać maksymalnie 5 różnych reguł zniżkowych. Odliczą się w koszyku w locie w trakcie trwania kampanii.</p>';
    
    echo '<div id="discounts-wrapper">';
    for ($d = 1; $d <= 5; $d++) {
        $disc_type = isset($meta['promo_discount_type_'.$d]) ? esc_attr($meta['promo_discount_type_'.$d][0]) : 'none';
        $disc_val = isset($meta['promo_discount_value_'.$d]) ? esc_attr($meta['promo_discount_value_'.$d][0]) : '';
        $disc_scope = isset($meta['promo_discount_scope_'.$d]) ? esc_attr($meta['promo_discount_scope_'.$d][0]) : 'selected';
        
        $disc_targets_val = isset($meta['promo_discount_targets_'.$d]) ? $meta['promo_discount_targets_'.$d][0] : '';
        $disc_targets_arr = !empty($disc_targets_val) ? explode(',', $disc_targets_val) : [];

        $d_display = ($d == 1 || $disc_type !== 'none') ? 'block' : 'none';

        echo '<div class="discount-group" id="discount-group-'.$d.'" style="display:'.$d_display.'; background:#f9f9f9; padding:15px; margin-bottom:15px; border:1px solid #ddd; border-radius:4px;">';
        echo '<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;"><h4 style="margin:0;">Reguła Zniżkowa '.$d.'</h4>';
        if ($d > 1) {
            echo '<button type="button" class="button remove-discount" data-target="'.$d.'" style="color:#d63638; border-color:#d63638;">Wyczyść i ukryj</button>';
        }
        echo '</div>';
        
        echo '<div class="promo-flex-row">';
        echo '<div class="promo-field-group"><label class="main-label">Typ zniżki w locie</label><select name="promo_discount_type_'.$d.'" class="promo-input-text"><option value="none" '.selected($disc_type, 'none', false).'>Brak zniżki w locie</option><option value="percentage" '.selected($disc_type, 'percentage', false).'>Zniżka Procentowa (%)</option><option value="fixed" '.selected($disc_type, 'fixed', false).'>Zniżka Kwotowa (Stała - np. 20 zł)</option></select></div>';
        echo '<div class="promo-field-group"><label class="main-label">Wartość zniżki</label><input type="text" name="promo_discount_value_'.$d.'" class="promo-input-text" value="'.$disc_val.'" placeholder="np. 15"></div>';
        echo '<div class="promo-field-group"><label class="main-label">Zasięg zniżki</label><select name="promo_discount_scope_'.$d.'" class="promo-input-text"><option value="selected" '.selected($disc_scope, 'selected', false).'>Wybrane niżej produkty/kategorie</option><option value="all" '.selected($disc_scope, 'all', false).'>Na cały asortyment sklepu</option></select></div>';
        echo '</div>';

        $disc_cats_val = isset($meta['promo_discount_categories_'.$d]) ? $meta['promo_discount_categories_'.$d][0] : '';
        $disc_cats_arr = !empty($disc_cats_val) ? explode(',', $disc_cats_val) : [];

        echo '<div class="promo-flex-row">';
        echo '<div class="promo-field-group" style="flex:1;"><label class="main-label">Produkty objęte zniżką (jeśli nie wybrano "cały sklep")</label>';
        echo '<select class="wc-product-search" multiple="multiple" style="width:100%;" name="promo_discount_targets_'.$d.'[]" data-placeholder="Szukaj produktów/wariantów..." data-action="woocommerce_json_search_products_and_variations">';
        foreach ($disc_targets_arr as $dtid) {
            if ($dtid && function_exists('wc_get_product')) {
                $p = wc_get_product(trim($dtid));
                if ($p) echo '<option value="'.esc_attr(trim($dtid)).'" selected="selected">'.esc_html(wp_strip_all_tags($p->get_formatted_name())).'</option>';
            }
        }
        echo '</select></div>';
        
        echo '<div class="promo-field-group" style="flex:1; margin-left:15px;"><label class="main-label">Kategorie objęte zniżką</label>';
        echo '<select class="wc-category-search" multiple="multiple" style="width:100%;" name="promo_discount_categories_'.$d.'[]" data-placeholder="Szukaj kategorii..." data-action="woocommerce_json_search_categories">';
        foreach ($disc_cats_arr as $dcid) {
            if ($dcid) {
                $term = get_term(trim($dcid), 'product_cat');
                if ($term && !is_wp_error($term)) {
                    echo '<option value="'.esc_attr(trim($dcid)).'" selected="selected">'.esc_html($term->name).'</option>';
                }
            }
        }
        echo '</select></div>';
        echo '</div>';

        echo '</div>'; // Koniec discount-group
    }
    echo '</div>'; // Koniec discounts-wrapper
    
    echo '<div style="margin-bottom: 30px;">';
    echo '<button type="button" class="button" id="btn-add-discount"><span class="dashicons dashicons-plus-alt2" style="margin-top:3px;"></span> Dodaj kolejną regułę</button>';
    echo '</div>';

    echo '</div>'; // Koniec Zakładki 5

    // SEKCJA TŁUMACZEŃ AI
    echo '<div style="margin-top:40px; padding:20px; background:#f0f6fc; border:1px solid #c2dbf1; border-radius:5px;">';
    echo '<h3 style="margin-top:0; font-size:16px;"><span class="dashicons dashicons-translation" style="margin-top:3px;"></span> Tłumaczenia AI (Szybki Import/Eksport)</h3>';
    echo '<p style="color:#555;">Krok 1: Skopiuj zawartość poniższego pola i wklej do ChatGPT, dodając np. słowo "czeski" lub "niemiecki".<br>Krok 2: Otrzymany od AI kod JSON wklej do drugiego okienka i kliknij "Importuj Tłumaczenie".</p>';
    echo '<div style="display:flex; gap:20px;">';
        echo '<div style="flex:1;">';
        echo '<label style="font-weight:bold; display:block; margin-bottom:5px;">JSON do skopiowania (z Promptem)</label>';
        echo '<textarea id="ai_export_json" readonly style="width:100%; height:200px; font-family:monospace; font-size:12px; background:#fff; cursor:text;" onclick="this.select();"></textarea>';
        echo '<button type="button" class="button" onclick="document.getElementById(\'ai_export_json\').select(); document.execCommand(\'copy\'); alert(\'Skopiowano!\');" style="margin-top:10px;"><span class="dashicons dashicons-admin-page" style="margin-top:4px;"></span> Skopiuj do schowka</button>';
        echo '</div>';
        echo '<div style="flex:1;">';
        echo '<label style="font-weight:bold; display:block; margin-bottom:5px;">JSON po przetłumaczeniu (Wklej tutaj)</label>';
        echo '<textarea id="ai_import_json" placeholder="Wklej odpowiedź z AI w formacie JSON..." style="width:100%; height:200px; font-family:monospace; font-size:12px; background:#fff;"></textarea>';
        echo '<button type="button" class="button button-primary" id="btn-import-ai" style="width:100%; margin-top:10px; text-align:center; display:flex; justify-content:center; align-items:center;">Zastosuj przetłumaczone teksty</button>';
        echo '</div>';
    echo '</div>';
    echo '</div>';

    // KOPIUJ NA DOLE
    echo '<div style="display:flex; justify-content:flex-end; margin-top:20px; border-top:1px solid #ccc; padding-top:15px;">';
    echo '<button type="button" class="button button-primary" id="btn-copy-config"><span class="dashicons dashicons-admin-page" style="margin-top:3px;"></span> Kopiuj styl (JSON) między domenami</button>';
    echo '</div>';

    echo '</div>'; // Koniec admin container

    // --- EDYTOR WIZUALNY A'LA ELEMENTOR (MODAL) ---
    echo '<div id="promo-live-modal" class="promo-live-modal">';
    echo '  <div class="promo-live-modal-content" id="promo-modal-box">';
    
    // SIDEBAR
    echo '      <div class="promo-live-sidebar">';
    echo '          <div class="promo-live-header">';
    echo '              <h2>Edytor Licznika</h2>';
    echo '              <span id="close-live-modal" class="promo-live-close">&times;</span>';
    echo '          </div>';
    echo '          <div class="promo-live-sidebar-content">';
    
    echo '              <div class="view-switch">';
    echo '                  <div class="view-btn active" data-view="desk"><span class="dashicons dashicons-desktop"></span> Desktop</div>';
    echo '                  <div class="view-btn" data-view="mob"><span class="dashicons dashicons-smartphone"></span> Mobile</div>';
    echo '              </div>';

    echo '              <div class="editor-control-group">';
    echo '                  <h4>Pozycja na banerze</h4>';
    echo '                  <span class="editor-label">Oś X (Poziom %)</span>';
    echo '                  <div class="editor-slider-row">';
    echo '                      <input type="range" class="editor-slider ctrl-font" id="ctrl_left" min="0" max="100" value="50">';
    echo '                      <span id="lbl_left" style="font-size:12px; font-weight:bold; width:30px;">50%</span>';
    echo '                  </div>';
    echo '                  <span class="editor-label">Oś Y (Pion %)</span>';
    echo '                  <div class="editor-slider-row">';
    echo '                      <input type="range" class="editor-slider ctrl-font" id="ctrl_top" min="0" max="100" value="57">';
    echo '                      <span id="lbl_top" style="font-size:12px; font-weight:bold; width:30px;">57%</span>';
    echo '                  </div>';
    echo '              </div>';

    echo '              <div class="editor-control-group">';
    echo '                  <h4>Odstępy (Gaps)</h4>';
    echo '                  <div class="editor-number-row">';
    echo '                      <span class="editor-label" style="margin:0;">Kolumny (px)</span>';
    echo '                      <div style="display:flex; gap:5px;">';
    echo '                          <button type="button" class="btn-number btn-minus" data-target="ctrl_gap_col">-</button>';
    echo '                          <input type="number" class="editor-number-input ctrl-font" id="ctrl_gap_col" value="20">';
    echo '                          <button type="button" class="btn-number btn-plus" data-target="ctrl_gap_col">+</button>';
    echo '                      </div>';
    echo '                  </div>';
    echo '                  <div class="editor-number-row">';
    echo '                      <span class="editor-label" style="margin:0;">Wiersze (px)</span>';
    echo '                      <div style="display:flex; gap:5px;">';
    echo '                          <button type="button" class="btn-number btn-minus" data-target="ctrl_gap_row">-</button>';
    echo '                          <input type="number" class="editor-number-input ctrl-font" id="ctrl_gap_row" value="0">';
    echo '                          <button type="button" class="btn-number btn-plus" data-target="ctrl_gap_row">+</button>';
    echo '                      </div>';
    echo '                  </div>';
    echo '              </div>';

    echo '              <div class="editor-control-group">';
    echo '                  <h4>Typografia</h4>';
    echo '                  <span class="editor-label">Font Family</span>';
    echo '                  <select id="ctrl_f_fam" class="promo-input-text ctrl-font" style="margin-bottom:10px;"><option value="Poppins">Poppins</option><option value="\'Work Sans\'">Work Sans</option><option value="Bebas Neue">Bebas Neue</option></select>';
    echo '                  <span class="editor-label">Waga Cyfr (Font Weight)</span>';
    echo '                  <select id="ctrl_f_wei_n" class="promo-input-text ctrl-font" style="margin-bottom:10px;"><option value="400">400</option><option value="600">600</option><option value="700">700</option><option value="800">800</option><option value="900">900</option></select>';
    echo '                  <span class="editor-label">Waga Etykiet (Font Weight)</span>';
    echo '                  <select id="ctrl_f_wei_l" class="promo-input-text ctrl-font" style="margin-bottom:10px;"><option value="400">400</option><option value="600">600</option><option value="700">700</option><option value="800">800</option><option value="900">900</option></select>';
    echo '                  <div class="editor-number-row">';
    echo '                      <span class="editor-label" style="margin:0;">Rozmiar Cyfr (px)</span>';
    echo '                      <div style="display:flex; gap:5px;">';
    echo '                          <button type="button" class="btn-number btn-minus" data-target="ctrl_f_num">-</button>';
    echo '                          <input type="number" class="editor-number-input ctrl-font" id="ctrl_f_num" value="16">';
    echo '                          <button type="button" class="btn-number btn-plus" data-target="ctrl_f_num">+</button>';
    echo '                      </div>';
    echo '                  </div>';
    echo '                  <div class="editor-number-row">';
    echo '                      <span class="editor-label" style="margin:0;">Rozmiar Etykiet (px)</span>';
    echo '                      <div style="display:flex; gap:5px;">';
    echo '                          <button type="button" class="btn-number btn-minus" data-target="ctrl_f_lbl">-</button>';
    echo '                          <input type="number" class="editor-number-input ctrl-font" id="ctrl_f_lbl" value="10">';
    echo '                          <button type="button" class="btn-number btn-plus" data-target="ctrl_f_lbl">+</button>';
    echo '                      </div>';
    echo '                  </div>';
    echo '              </div>';

    echo '              <div class="editor-control-group">';
    echo '                  <h4>Kolory</h4>';
    echo '                  <span class="editor-label">Kolor Cyfr</span>';
    echo '                  <input type="text" id="ctrl_c_num" class="promo-input-text ctrl-font" style="margin-bottom:5px;">';
    echo '                  <div class="promo-swatches" style="gap:4px; margin-bottom:15px; margin-top:0;">';
    foreach($firm_colors as $fc) { echo '<div class="promo-swatch modal-swatch" style="width:20px;height:20px;background:'.$fc.';" data-target="ctrl_c_num" data-color="'.$fc.'"></div>'; }
    echo '                  </div>';
    echo '                  <span class="editor-label">Kolor Etykiet</span>';
    echo '                  <input type="text" id="ctrl_c_lbl" class="promo-input-text ctrl-font" style="margin-bottom:5px;">';
    echo '                  <div class="promo-swatches" style="gap:4px; margin-top:0;">';
    foreach($firm_colors as $fc) { echo '<div class="promo-swatch modal-swatch" style="width:20px;height:20px;background:'.$fc.';" data-target="ctrl_c_lbl" data-color="'.$fc.'"></div>'; }
    echo '                  </div>';
    echo '              </div>';

    echo '              <div style="text-align:center; margin-top:20px;">';
    echo '                  <button type="button" class="button button-primary" id="btn-save-close-modal">Zakończ Edycję</button>';
    echo '              </div>';

    echo '          </div>'; 
    echo '      </div>'; 

    // MAIN PREVIEW AREA
    echo '      <div class="promo-live-preview-area">';
    echo '          <div id="lp-canvas" class="live-preview-canvas">';
    echo '              <img id="lp-img" src="" alt="Wgraj obraz pod licznik w panelu głównym, aby zobaczyć podgląd">';
    echo '              <div id="lp-timer-box" class="live-preview-timer-box">';
    echo '                  <div class="lp-timer-flex">';
    echo '                      <div class="lp-wrapper"><span class="lp-num">12</span><span class="lp-lbl">dni</span></div>';
    echo '                      <div class="lp-wrapper"><span class="lp-num">05</span><span class="lp-lbl">godziny</span></div>';
    echo '                      <div class="lp-wrapper"><span class="lp-num">45</span><span class="lp-lbl">minuty</span></div>';
    echo '                      <div class="lp-wrapper"><span class="lp-num">30</span><span class="lp-lbl">sekundy</span></div>';
    echo '                  </div>';
    echo '              </div>';
    echo '          </div>';
    echo '      </div>'; 
    
    echo '  </div>'; 
    echo '</div>'; 

    // --- EDYTOR WIZUALNY FSE / MENU (MODAL WSPÓLNY) ---
    echo '<div id="promo-common-live-modal" class="promo-live-modal">';
    echo '  <div class="promo-live-modal-content" id="promo-common-modal-box">';
    
    echo '      <div class="promo-live-sidebar">';
    echo '          <div class="promo-live-header">';
    echo '              <h2 id="common-modal-title">Podgląd Na Żywo</h2>';
    echo '              <span id="close-common-live-modal" class="promo-live-close">&times;</span>';
    echo '          </div>';
    echo '          <div class="promo-live-sidebar-content">';
    echo '              <div class="view-switch">';
    echo '                  <div class="common-view-btn active" data-view="desk" id="cvb-desk"><span class="dashicons dashicons-desktop"></span> Desktop</div>';
    echo '                  <div class="common-view-btn" data-view="mob" id="cvb-mob"><span class="dashicons dashicons-smartphone"></span> Mobile</div>';
    echo '              </div>';
    echo '              <p style="padding:15px; color:#666; font-size:13px; line-height:1.4;">Wielkość i pozycję grafiki ustawiaj poniżej. Pozostałe wartości zmieniaj w panelu głównym za tym oknem.</p>';
    
    // --- USTAWIENIA SVG DLA FSE (WIDOCZNE TYLKO GDY TRYB FSE) ---
    echo '              <div id="live-sidebar-settings-fse" class="editor-control-group" style="display:none;">';
    echo '                  <h4>Ustawienia Tytułu (Grafiki)</h4>';
    $fse_h_val = isset($meta['promo_fse_title_svg_h_val']) ? esc_attr($meta['promo_fse_title_svg_h_val'][0]) : '50';
    $fse_h_unit = isset($meta['promo_fse_title_svg_h_unit']) ? esc_attr($meta['promo_fse_title_svg_h_unit'][0]) : 'px';
    echo '                  <span class="editor-label">Wysokość Grafiki (Desktop)</span>';
    echo '                  <div style="display:flex; border: 1px solid #8c8f94; border-radius: 4px; overflow: hidden; height:34px; align-items:center; margin-bottom:10px;">';
    echo '                  <input type="number" step="0.1" name="promo_fse_title_svg_h_val" value="'.$fse_h_val.'" style="border:none; border-radius:0; flex:1; outline:none; box-shadow:none; height:100%; padding:0 8px; min-width:0;">';
    echo '                  <select name="promo_fse_title_svg_h_unit" style="border:none; border-left:1px solid #8c8f94; border-radius:0; background:#f6f7f7; padding: 0 10px; outline:none; height:100%; cursor:pointer;">';
    echo '                  <option value="px" '.selected($fse_h_unit, 'px', false).'>px</option><option value="em" '.selected($fse_h_unit, 'em', false).'>em</option><option value="%" '.selected($fse_h_unit, '%', false).'>%</option></select></div>';

    $fse_hm_val = isset($meta['promo_fse_title_svg_hm_val']) ? esc_attr($meta['promo_fse_title_svg_hm_val'][0]) : '40';
    $fse_hm_unit = isset($meta['promo_fse_title_svg_hm_unit']) ? esc_attr($meta['promo_fse_title_svg_hm_unit'][0]) : 'px';
    echo '                  <span class="editor-label">Wysokość Grafiki (Mobile)</span>';
    echo '                  <div style="display:flex; border: 1px solid #8c8f94; border-radius: 4px; overflow: hidden; height:34px; align-items:center; margin-bottom:10px;">';
    echo '                  <input type="number" step="0.1" name="promo_fse_title_svg_hm_val" value="'.$fse_hm_val.'" style="border:none; border-radius:0; flex:1; outline:none; box-shadow:none; height:100%; padding:0 8px; min-width:0;">';
    echo '                  <select name="promo_fse_title_svg_hm_unit" style="border:none; border-left:1px solid #8c8f94; border-radius:0; background:#f6f7f7; padding: 0 10px; outline:none; height:100%; cursor:pointer;">';
    echo '                  <option value="px" '.selected($fse_hm_unit, 'px', false).'>px</option><option value="em" '.selected($fse_hm_unit, 'em', false).'>em</option><option value="%" '.selected($fse_hm_unit, '%', false).'>%</option></select></div>';

    $fse_align = isset($meta['promo_fse_title_svg_align']) ? esc_attr($meta['promo_fse_title_svg_align'][0]) : 'center';
    echo '                  <span class="editor-label">Wyrównanie Grafiki</span><select name="promo_fse_title_svg_align" class="promo-input-text" style="width:100%; margin-bottom:10px;"><option value="left" '.selected($fse_align, 'left', false).'>Do lewej</option><option value="center" '.selected($fse_align, 'center', false).'>Wyśrodkuj</option><option value="right" '.selected($fse_align, 'right', false).'>Do prawej</option></select>';
    echo '              </div>';

    // --- USTAWIENIA SVG DLA MENU (WIDOCZNE TYLKO GDY TRYB MENU) ---
    echo '              <div id="live-sidebar-settings-mm" class="editor-control-group" style="display:none; padding: 0 15px;">';
    
    // --- TYTUŁ ---
    $use_mm_title_svg = isset($meta['promo_mm_banner_use_title_svg']) && $meta['promo_mm_banner_use_title_svg'][0] === 'yes' ? 'checked' : '';
    echo '<div class="promo-flex-row" style="margin-bottom:5px; margin-top:10px;">';
    echo '<label style="font-weight:bold; font-size:13px;"><input type="checkbox" name="promo_mm_banner_use_title_svg" value="yes" '.$use_mm_title_svg.' class="mm-svg-toggle" data-target="title"> Używaj SVG w Tytule</label>';
    echo '</div>';

    $display_mm_svg = ($use_mm_title_svg !== '') ? 'block' : 'none';
    $mm_icon_type = isset($meta['promo_mm_banner_title_icon_type']) && $meta['promo_mm_banner_title_icon_type'][0] === 'image' ? 'image' : 'svg';
    echo '<div class="mm-svg-wrapper-title" style="display:'.$display_mm_svg.'; background:#f1f1f1; padding:10px; border-radius:4px; margin-bottom:10px;">';
    echo '<div class="promo-flex-row" style="margin-bottom:10px;">';
    echo '<div class="promo-field-group" style="flex:1;"><label class="editor-label">Typ Grafiki</label>';
    echo '<label style="font-size:12px;"><input type="radio" name="promo_mm_banner_title_icon_type" value="svg" '.($mm_icon_type === 'svg' ? 'checked' : '').' class="icon-type-toggle" data-target="mm-title"> Kod SVG</label> <br>';
    echo '<label style="font-size:12px;"><input type="radio" name="promo_mm_banner_title_icon_type" value="image" '.($mm_icon_type === 'image' ? 'checked' : '').' class="icon-type-toggle" data-target="mm-title"> Obrazek</label>';
    echo '</div></div>';

    echo '<div class="promo-field-group icon-svg-wrapper-mm-title" style="'.($mm_icon_type === 'svg' ? 'display:block;' : 'display:none;').'"><label class="editor-label">Kod SVG (Tytuł)</label><textarea name="promo_mm_banner_title_svg" class="promo-input-text" rows="3" style="width:100%;">'.(isset($meta['promo_mm_banner_title_svg']) ? esc_textarea($meta['promo_mm_banner_title_svg'][0]) : '').'</textarea></div>';
    
    echo '<div class="promo-field-group icon-img-wrapper-mm-title" style="'.($mm_icon_type === 'image' ? 'display:block;' : 'display:none;').'">';
    render_media_slot('Obrazek', 'promo_mm_banner_title_image', $meta);
    echo '</div>';

    $mm_t_h_val = isset($meta['promo_mm_banner_title_svg_h_val']) ? esc_attr($meta['promo_mm_banner_title_svg_h_val'][0]) : '30';
    $mm_t_h_unit = isset($meta['promo_mm_banner_title_svg_h_unit']) ? esc_attr($meta['promo_mm_banner_title_svg_h_unit'][0]) : 'px';
    $mm_t_align = isset($meta['promo_mm_banner_title_svg_align']) ? esc_attr($meta['promo_mm_banner_title_svg_align'][0]) : 'left';
    echo '<div class="promo-field-group" style="margin-top:10px;"><label class="editor-label">Wysokość Grafiki</label><div style="display:flex; height:28px; border:1px solid #8c8f94; border-radius:4px; overflow:hidden;"><input type="number" step="0.1" name="promo_mm_banner_title_svg_h_val" value="'.$mm_t_h_val.'" style="border:none; border-radius:0; flex:1; outline:none; box-shadow:none; height:100%; padding:0 8px; min-width:0;"><select name="promo_mm_banner_title_svg_h_unit" style="border:none; border-left:1px solid #8c8f94; border-radius:0; background:#f6f7f7; padding: 0 5px; outline:none; height:100%; cursor:pointer;"><option value="px" '.selected($mm_t_h_unit, 'px', false).'>px</option><option value="em" '.selected($mm_t_h_unit, 'em', false).'>em</option><option value="rem" '.selected($mm_t_h_unit, 'rem', false).'>rem</option><option value="%" '.selected($mm_t_h_unit, '%', false).'>%</option></select></div></div>';
    echo '<div class="promo-field-group" style="margin-top:10px;"><label class="editor-label">Wyrównanie Grafiki</label><select name="promo_mm_banner_title_svg_align" class="promo-input-text" style="width:100%;"><option value="left" '.selected($mm_t_align, 'left', false).'>Do lewej</option><option value="center" '.selected($mm_t_align, 'center', false).'>Wyśrodkuj</option><option value="right" '.selected($mm_t_align, 'right', false).'>Do prawej</option></select></div>';
    echo '</div>';
    
    $display_mm_text = ($use_mm_title_svg === '') ? 'block' : 'none';
    echo '<div class="mm-text-wrapper-title" style="display:'.$display_mm_text.';">';
    echo '<div class="promo-field-group"><label class="editor-label">Tekst Tytułu</label><input type="text" name="promo_mm_banner_title" class="promo-input-text" style="width:100%;" value="'.(isset($meta['promo_mm_banner_title']) ? esc_attr($meta['promo_mm_banner_title'][0]) : '').'"></div>';
    echo '</div>';
    
    echo '<hr style="margin:15px 0;">';

    // --- PODTYTUŁ (SUBTITLE) ---
    $use_mm_sub_svg = isset($meta['promo_mm_banner_use_subtitle_svg']) && $meta['promo_mm_banner_use_subtitle_svg'][0] === 'yes' ? 'checked' : '';
    echo '<div class="promo-flex-row" style="margin-bottom:5px;">';
    echo '<label style="font-weight:bold; font-size:13px;"><input type="checkbox" name="promo_mm_banner_use_subtitle_svg" value="yes" '.$use_mm_sub_svg.' class="mm-svg-toggle" data-target="subtitle"> Używaj SVG w Podtytule</label>';
    echo '</div>';

    $display_mm_sub_svg = ($use_mm_sub_svg !== '') ? 'block' : 'none';
    $mm_sub_icon_type = isset($meta['promo_mm_banner_subtitle_icon_type']) && $meta['promo_mm_banner_subtitle_icon_type'][0] === 'image' ? 'image' : 'svg';
    echo '<div class="mm-svg-wrapper-subtitle" style="display:'.$display_mm_sub_svg.'; background:#f1f1f1; padding:10px; border-radius:4px; margin-bottom:10px;">';
    echo '<div class="promo-flex-row" style="margin-bottom:10px;">';
    echo '<div class="promo-field-group" style="flex:1;"><label class="editor-label">Typ Grafiki</label>';
    echo '<label style="font-size:12px;"><input type="radio" name="promo_mm_banner_subtitle_icon_type" value="svg" '.($mm_sub_icon_type === 'svg' ? 'checked' : '').' class="icon-type-toggle" data-target="mm-subtitle"> Kod SVG</label> <br>';
    echo '<label style="font-size:12px;"><input type="radio" name="promo_mm_banner_subtitle_icon_type" value="image" '.($mm_sub_icon_type === 'image' ? 'checked' : '').' class="icon-type-toggle" data-target="mm-subtitle"> Obrazek</label>';
    echo '</div></div>';
    echo '<div class="promo-field-group icon-svg-wrapper-mm-subtitle" style="'.($mm_sub_icon_type === 'svg' ? 'display:block;' : 'display:none;').'"><label class="editor-label">Kod SVG (Podtytuł)</label><textarea name="promo_mm_banner_subtitle_svg" class="promo-input-text" rows="3" style="width:100%;">'.(isset($meta['promo_mm_banner_subtitle_svg']) ? esc_textarea($meta['promo_mm_banner_subtitle_svg'][0]) : '').'</textarea></div>';
    echo '<div class="promo-field-group icon-img-wrapper-mm-subtitle" style="'.($mm_sub_icon_type === 'image' ? 'display:block;' : 'display:none;').'">';
    render_media_slot('Obrazek', 'promo_mm_banner_subtitle_image', $meta);
    echo '</div>';

    $mm_s_h_val = isset($meta['promo_mm_banner_subtitle_svg_h_val']) ? esc_attr($meta['promo_mm_banner_subtitle_svg_h_val'][0]) : '30';
    $mm_s_h_unit = isset($meta['promo_mm_banner_subtitle_svg_h_unit']) ? esc_attr($meta['promo_mm_banner_subtitle_svg_h_unit'][0]) : 'px';
    $mm_s_align = isset($meta['promo_mm_banner_subtitle_svg_align']) ? esc_attr($meta['promo_mm_banner_subtitle_svg_align'][0]) : 'left';
    echo '<div class="promo-field-group" style="margin-top:10px;"><label class="editor-label">Wysokość Grafiki</label><div style="display:flex; height:28px; border:1px solid #8c8f94; border-radius:4px; overflow:hidden;"><input type="number" step="0.1" name="promo_mm_banner_subtitle_svg_h_val" value="'.$mm_s_h_val.'" style="border:none; border-radius:0; flex:1; outline:none; box-shadow:none; height:100%; padding:0 8px; min-width:0;"><select name="promo_mm_banner_subtitle_svg_h_unit" style="border:none; border-left:1px solid #8c8f94; border-radius:0; background:#f6f7f7; padding: 0 5px; outline:none; height:100%; cursor:pointer;"><option value="px" '.selected($mm_s_h_unit, 'px', false).'>px</option><option value="em" '.selected($mm_s_h_unit, 'em', false).'>em</option><option value="rem" '.selected($mm_s_h_unit, 'rem', false).'>rem</option><option value="%" '.selected($mm_s_h_unit, '%', false).'>%</option></select></div></div>';
    echo '<div class="promo-field-group" style="margin-top:10px;"><label class="editor-label">Wyrównanie Grafiki</label><select name="promo_mm_banner_subtitle_svg_align" class="promo-input-text" style="width:100%;"><option value="left" '.selected($mm_s_align, 'left', false).'>Do lewej</option><option value="center" '.selected($mm_s_align, 'center', false).'>Wyśrodkuj</option><option value="right" '.selected($mm_s_align, 'right', false).'>Do prawej</option></select></div>';
    echo '</div>';
    
    $display_mm_sub_text = ($use_mm_sub_svg === '') ? 'block' : 'none';
    echo '<div class="mm-text-wrapper-subtitle" style="display:'.$display_mm_sub_text.';">';
    echo '<div class="promo-field-group"><label class="editor-label">Tekst Podtytułu</label><input type="text" name="promo_mm_banner_subtitle" class="promo-input-text" style="width:100%;" value="'.(isset($meta['promo_mm_banner_subtitle']) ? esc_attr($meta['promo_mm_banner_subtitle'][0]) : '').'"></div>';
    echo '</div>';
    echo '<div class="promo-field-group" style="margin-top:10px;"><label class="editor-label">Kolor Tła Podtytułu (np. #ff0000, zostaw puste by wyłączyć)</label><input type="text" name="promo_mm_banner_subtitle_bg" class="promo-input-text" style="width:100%;" value="'.(isset($meta['promo_mm_banner_subtitle_bg']) ? esc_attr($meta['promo_mm_banner_subtitle_bg'][0]) : '').'" placeholder="rgba(235, 194, 153, 0.60)"></div>';

    echo '<hr style="margin:15px 0;">';

    // --- TEKST PRZYCISKU (BTN TEXT) ---
    $use_mm_btn_svg = isset($meta['promo_mm_banner_use_btn_svg']) && $meta['promo_mm_banner_use_btn_svg'][0] === 'yes' ? 'checked' : '';
    echo '<div class="promo-flex-row" style="margin-bottom:5px;">';
    echo '<label style="font-weight:bold; font-size:13px;"><input type="checkbox" name="promo_mm_banner_use_btn_svg" value="yes" '.$use_mm_btn_svg.' class="mm-svg-toggle" data-target="btn"> Używaj SVG w Przycisku</label>';
    echo '</div>';

    $display_mm_btn_svg = ($use_mm_btn_svg !== '') ? 'block' : 'none';
    $mm_btn_icon_type = isset($meta['promo_mm_banner_btn_icon_type']) && $meta['promo_mm_banner_btn_icon_type'][0] === 'image' ? 'image' : 'svg';
    echo '<div class="mm-svg-wrapper-btn" style="display:'.$display_mm_btn_svg.'; background:#f1f1f1; padding:10px; border-radius:4px; margin-bottom:10px;">';
    echo '<div class="promo-flex-row" style="margin-bottom:10px;">';
    echo '<div class="promo-field-group" style="flex:1;"><label class="editor-label">Typ Grafiki</label>';
    echo '<label style="font-size:12px;"><input type="radio" name="promo_mm_banner_btn_icon_type" value="svg" '.($mm_btn_icon_type === 'svg' ? 'checked' : '').' class="icon-type-toggle" data-target="mm-btn"> Kod SVG</label> <br>';
    echo '<label style="font-size:12px;"><input type="radio" name="promo_mm_banner_btn_icon_type" value="image" '.($mm_btn_icon_type === 'image' ? 'checked' : '').' class="icon-type-toggle" data-target="mm-btn"> Obrazek</label>';
    echo '</div></div>';
    echo '<div class="promo-field-group icon-svg-wrapper-mm-btn" style="'.($mm_btn_icon_type === 'svg' ? 'display:block;' : 'display:none;').'"><label class="editor-label">Kod SVG (Przycisk)</label><textarea name="promo_mm_banner_btn_svg" class="promo-input-text" rows="3" style="width:100%;">'.(isset($meta['promo_mm_banner_btn_svg']) ? esc_textarea($meta['promo_mm_banner_btn_svg'][0]) : '').'</textarea></div>';
    echo '<div class="promo-field-group icon-img-wrapper-mm-btn" style="'.($mm_btn_icon_type === 'image' ? 'display:block;' : 'display:none;').'">';
    render_media_slot('Obrazek', 'promo_mm_banner_btn_image', $meta);
    echo '</div>';

    $mm_b_h_val = isset($meta['promo_mm_banner_btn_svg_h_val']) ? esc_attr($meta['promo_mm_banner_btn_svg_h_val'][0]) : '30';
    $mm_b_h_unit = isset($meta['promo_mm_banner_btn_svg_h_unit']) ? esc_attr($meta['promo_mm_banner_btn_svg_h_unit'][0]) : 'px';
    $mm_b_align = isset($meta['promo_mm_banner_btn_svg_align']) ? esc_attr($meta['promo_mm_banner_btn_svg_align'][0]) : 'left';
    echo '<div class="promo-field-group" style="margin-top:10px;"><label class="editor-label">Wysokość Grafiki</label><div style="display:flex; height:28px; border:1px solid #8c8f94; border-radius:4px; overflow:hidden;"><input type="number" step="0.1" name="promo_mm_banner_btn_svg_h_val" value="'.$mm_b_h_val.'" style="border:none; border-radius:0; flex:1; outline:none; box-shadow:none; height:100%; padding:0 8px; min-width:0;"><select name="promo_mm_banner_btn_svg_h_unit" style="border:none; border-left:1px solid #8c8f94; border-radius:0; background:#f6f7f7; padding: 0 5px; outline:none; height:100%; cursor:pointer;"><option value="px" '.selected($mm_b_h_unit, 'px', false).'>px</option><option value="em" '.selected($mm_b_h_unit, 'em', false).'>em</option><option value="rem" '.selected($mm_b_h_unit, 'rem', false).'>rem</option><option value="%" '.selected($mm_b_h_unit, '%', false).'>%</option></select></div></div>';
    echo '<div class="promo-field-group" style="margin-top:10px;"><label class="editor-label">Wyrównanie Grafiki</label><select name="promo_mm_banner_btn_svg_align" class="promo-input-text" style="width:100%;"><option value="left" '.selected($mm_b_align, 'left', false).'>Do lewej</option><option value="center" '.selected($mm_b_align, 'center', false).'>Wyśrodkuj</option><option value="right" '.selected($mm_b_align, 'right', false).'>Do prawej</option></select></div>';
    echo '</div>';
    
    $display_mm_btn_text = ($use_mm_btn_svg === '') ? 'block' : 'none';
    echo '<div class="mm-text-wrapper-btn" style="display:'.$display_mm_btn_text.';">';
    echo '<div class="promo-field-group"><label class="editor-label">Tekst przycisku</label><input type="text" name="promo_mm_banner_btn_text" class="promo-input-text" style="width:100%;" value="'.(isset($meta['promo_mm_banner_btn_text']) ? esc_attr($meta['promo_mm_banner_btn_text'][0]) : '').'"></div>';
    echo '<div class="promo-field-group" style="margin-top:10px;"><label class="editor-label">Link przycisku</label><input type="text" name="promo_mm_banner_btn_link" class="promo-input-text" style="width:100%;" value="'.(isset($meta['promo_mm_banner_btn_link']) ? esc_attr($meta['promo_mm_banner_btn_link'][0]) : '').'"></div>';
    echo '</div>';
    echo '              </div>';
    echo '                  <button type="button" class="button button-primary" id="btn-save-close-common-modal">Zakończ Edycję</button>';
    echo '              </div>';
    echo '          </div>'; 

    echo '      <div class="promo-live-preview-area" style="background:#ddd;">';
    echo '          <div id="lp-common-canvas" class="live-preview-canvas" style="background:#fff; transition: max-width 0.3s;">';
    
    // Wewnątrz canvasu dodamy dwa oddzielne kontenery: jeden dla FSE, drugi dla MENU
    echo '              <div id="lp-fse-wrapper" style="display:none; position:relative; overflow:hidden; border-radius:20px; margin:20px; min-height:400px; background:#fff;">';
    echo '                  <img id="lp-fse-bg" src="" style="width:100%; height:100%; display:block; object-fit:cover; position:absolute; inset:0;">';
    echo '                  <div id="lp-fse-content" style="position:absolute; top:0; right:0; bottom:0; width:50%; padding:40px 60px 96px; display:flex; flex-direction:column; justify-content:center; align-items:center; text-align:center; gap:16px; box-sizing:border-box;">';
    echo '                      <span id="lp-fse-subtitle" style="display:inline-flex; padding:8px 16px; justify-content:center; align-items:center; gap:10px; border-radius:24px; text-transform:uppercase;"></span>';
    echo '                      <div id="lp-fse-title-container" style="display:flex; justify-content:center; align-items:center; width:100%;">';
    echo '                          <div id="lp-fse-title-text" style="margin:0; font-family:\'TAN - AEGEAN\', serif; font-weight:400; line-height:normal; letter-spacing:-1.12px;"></div>';
    echo '                          <div id="lp-fse-title-svg" style="display:none; width:100%; justify-content:center; align-items:center;"></div>';
    echo '                          <img id="lp-fse-title-img" src="" style="display:none; width:100%; object-fit:contain;">';
    echo '                      </div>';
    echo '                      <p id="lp-fse-body" style="margin:0; max-width:460px; font-weight:400; line-height:normal;"></p>';
    echo '                  </div>';
    echo '                  <div id="lp-fse-btn-container" style="position:absolute; right:24px; bottom:24px; display:flex; justify-content:flex-end; z-index:2;">';
    echo '                      <div id="lp-fse-btn" style="display:inline-flex; padding:4px 4px 4px 24px; justify-content:center; align-items:center; gap:12px; border-radius:46px;">';
    echo '                          <span id="lp-fse-btn-text" style="font-weight:500; line-height:24px;"></span>';
    echo '                          <span id="lp-fse-btn-arrow" style="display:inline-flex; align-items:center; justify-content:center; flex-shrink:0;">';
    echo '                              <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48" fill="none"><rect id="lp-fse-arr-bg" x="0" y="48" width="48" height="48" rx="24" transform="rotate(-90 0 48)" fill="#080808" /><path id="lp-fse-arr-color" d="M30.7234 25.4985L12.4971 25.4985L12.4971 22.5042L30.7224 22.5031L22.6913 14.4721L24.8089 12.3545L36.4558 24.0014L24.8089 35.6482L22.6913 33.5306L30.7234 25.4985Z" fill="#FCFCFC" /></svg>';
    echo '                          </span>';
    echo '                      </div>';
    echo '                  </div>';
    echo '              </div>';
    
    echo '              <div id="lp-mm-wrapper" style="display:none; position:relative; overflow:hidden; border-radius:20px; min-height:230px; margin: 20px;">';
    echo '                  <img id="lp-mm-bg" src="" style="position:absolute; inset:0; width:100%; height:100%; object-fit:cover; display:block; z-index:0; background: linear-gradient(135deg, #f0c89a 0%, #c89a72 100%);">';
    echo '                  <div id="lp-mm-content" style="position:relative; z-index:1; padding:20px 20px 0 20px; display:flex; flex-direction:column; align-items:flex-start; gap:10px; text-align:left; width:100%; box-sizing:border-box;">';
    echo '                      <div id="lp-mm-subtitle-container" style="display:flex; justify-content:flex-start; align-items:center; width:100%;">';
    echo '                          <span id="lp-mm-subtitle" style="display:inline-flex; padding:6px 14px; border-radius:24px; background:rgba(235, 194, 153, 0.60); color:#FCFCFC; font-family:\'Poppins\', sans-serif; font-size:12px; text-transform:uppercase; font-weight:400; letter-spacing:0.5px;"></span>';
    echo '                          <div id="lp-mm-subtitle-svg" style="display:none; width:100%; justify-content:flex-start; align-items:center;"></div>';
    echo '                          <img id="lp-mm-subtitle-img" src="" style="display:none; width:100%; height:auto; object-fit:contain;">';
    echo '                      </div>';
    echo '                      <div id="lp-mm-title-container" style="display:flex; justify-content:flex-start; align-items:center; width:100%;">';
    echo '                          <div id="lp-mm-title-text" style="margin:0; color:#FFFFFF; font-family:\'TAN - AEGEAN\', serif; font-size:40px; font-weight:400; line-height:1.05; letter-spacing:-0.8px;"></div>';
    echo '                          <div id="lp-mm-title-svg" style="display:none; width:100%; justify-content:flex-start; align-items:center;"></div>';
    echo '                          <img id="lp-mm-title-img" src="" style="display:none; width:100%; height:auto; object-fit:contain;">';
    echo '                      </div>';
    echo '                  </div>';
    echo '                  <div id="lp-mm-btn" style="position:absolute; left:16px; bottom:16px; z-index:2; display:inline-flex; align-items:center; gap:8px; padding:4px 4px 4px 20px; border-radius:46px; background:#FCFCFC; max-width:calc(100% - 32px);">';
    echo '                      <div id="lp-mm-btn-container" style="display:flex; justify-content:flex-start; align-items:center; flex:1; min-width:0;">';
    echo '                          <span id="lp-mm-btn-text" style="color:#080808; font-family:\'Poppins\', sans-serif; font-size:14px; font-weight:500; line-height:24px;"></span>';
    echo '                          <div id="lp-mm-btn-svg" style="display:none; width:100%; justify-content:flex-start; align-items:center;"></div>';
    echo '                          <img id="lp-mm-btn-img" src="" style="display:none; width:100%; height:auto; object-fit:contain;">';
    echo '                      </div>';
    echo '                      <span id="lp-mm-btn-arrow" style="display:block; width:40px; height:40px; flex-shrink:0;">';
    echo '                          <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 48 48" fill="none"><rect x="0" y="48" width="48" height="48" rx="24" transform="rotate(-90 0 48)" fill="#080808" /><path d="M30.7234 25.4985L12.4971 25.4985L12.4971 22.5042L30.7224 22.5031L22.6913 14.4721L24.8089 12.3545L36.4558 24.0014L24.8089 35.6482L22.6913 33.5306L30.7234 25.4985Z" fill="#FCFCFC" /></svg>';
    echo '                      </span>';
    echo '                  </div>';
    echo '              </div>';
    
    echo '          </div>';
    echo '      </div>'; 
    echo '  </div>'; 
    echo '</div>'; 

    // --- SKRYPTY INTERFEJSU ---
    ?>
    <script>
    jQuery(document).ready(function($){

        // --- SKRYPT DLA ZAKŁADEK ---
        $('.promo-tab').on('click', function() {
            var target = $(this).data('tab');
            $('.promo-tab').removeClass('active');
            $(this).addClass('active');
            $('.promo-tab-content').removeClass('active');
            $('#' + target).addClass('active');
        });

        // --- WALIDACJA DAT ---
        function validateDates() {
            var isValid = true;
            $('.date-validate').removeClass('input-error');
            $('.validation-msg').hide();

            // Zbieramy daty do tablicy (tylko te, które zostały podane) i filtrujemy puste wartości
            var datesToCheck = [
                { id: '#date_start', val: $('#date_start').val() },
                { id: '#date_ext', val: $('#date_ext').val() },
                { id: '#date_end', val: $('#date_end').val() },
                { id: '#date_final_fixed', val: $('#date_final_fixed').val() },
                { id: '#date_remove_ui', val: $('#date_remove_ui').val() }
            ].filter(function(d) { return d.val !== ''; });

            // Walidacja chronologii (każda następna musi być większa)
            for(var i=0; i < datesToCheck.length - 1; i++) {
                if (datesToCheck[i].val >= datesToCheck[i+1].val) {
                    $(datesToCheck[i].id + ', ' + datesToCheck[i+1].id).addClass('input-error');
                    $(datesToCheck[i+1].id).next('.validation-msg').show();
                    isValid = false;
                }
            }
            
            if(!isValid) {
                $('#publish').prop('disabled', true);
            } else {
                $('#publish').prop('disabled', false);
            }
            return isValid;
        }
        
        $('.date-validate').on('change', validateDates);
        
        $('#post').submit(function(e){
            if(!validateDates()){
                e.preventDefault();
                alert("Błąd walidacji! Popraw daty kampanii przed zapisaniem.");
                $('html, body').animate({ scrollTop: $('#date_start').offset().top - 100 }, 500);
            }
        });

        // WYBÓR KOLORU GŁÓWNY 
        $('.promo-swatch:not(.modal-swatch)').click(function(){
            var color = $(this).data('color');
            var parent = $(this).closest('.promo-field-group');
            parent.find('.promo-color-target').val(color);
            parent.find('.promo-swatch').removeClass('is-selected');
            $(this).addClass('is-selected');
            
            if($(this).hasClass('custom-trigger')){
                parent.find('.custom-css-input').addClass('is-active').focus();
            } else {
                parent.find('.custom-css-input').removeClass('is-active');
            }
        });

        // WYBÓR KOLORU W MODALU
        $('.modal-swatch').click(function(){
            $('#' + $(this).data('target')).val($(this).data('color')).trigger('input');
        });

        // UPLOAD MEDIÓW
        $('.btn-select').on('click', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var frame = wp.media({ title: 'Wybierz obraz', button: { text: 'Użyj tego obrazu' }, multiple: false });
            frame.on('select', function() {
                var attachment = frame.state().get('selection').first().toJSON();
                $('#input-' + id).val(attachment.url);
                $('#box-' + id).html('<img src="' + attachment.url + '">');
                $('.btn-remove[data-id="' + id + '"]').show();
            });
            frame.open();
        });

        $('.btn-remove').on('click', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            $('#input-' + id).val('');
            $('#box-' + id).html('<span class="dashicons dashicons-format-image"></span>');
            $(this).hide();
        });

        // --- EDYTOR WIZUALNY (MODAL) LOGIKA ---
        var currentView = 'desk';
        
        function applyColorSettings($el, val) {
            if (val && val.includes('gradient')) {
                $el.css({ 'background': val, '-webkit-background-clip': 'text', 'color': 'transparent' });
            } else {
                $el.css({ 'background': 'none', '-webkit-background-clip': 'border-box', 'color': val });
            }
        }

        function updateCanvas() {
            var top = $('#ctrl_top').val();
            var left = $('#ctrl_left').val();
            var fNum = $('#ctrl_f_num').val();
            var fLbl = $('#ctrl_f_lbl').val();
            var fFam = $('#ctrl_f_fam').val();
            var fWeiN = $('#ctrl_f_wei_n').val();
            var fWeiL = $('#ctrl_f_wei_l').val();
            var gCol = $('#ctrl_gap_col').val();
            var gRow = $('#ctrl_gap_row').val();
            var cNum = $('#ctrl_c_num').val();
            var cLbl = $('#ctrl_c_lbl').val();

            $('#lbl_top').text(top + '%');
            $('#lbl_left').text(left + '%');

            $('#lp-timer-box').css({ 'top': top + '%', 'left': left + '%' });
            $('.lp-timer-flex').css({ 'font-family': fFam, 'column-gap': gCol + 'px' });
            $('.lp-wrapper').css({ 'row-gap': gRow + 'px' });
            
            $('.lp-num').css({ 'font-size': fNum + 'px', 'font-weight': fWeiN });
            if(cNum.includes('gradient')) {
                $('.lp-num').css({'background': cNum, '-webkit-background-clip': 'text', 'color': 'transparent'});
            } else {
                $('.lp-num').css({'background': 'none', '-webkit-background-clip': 'border-box', 'color': cNum});
            }

            $('.lp-lbl').css({ 'font-size': fLbl + 'px', 'font-weight': fWeiL });
            if(cLbl.includes('gradient')) {
                $('.lp-lbl').css({'background': cLbl, '-webkit-background-clip': 'text', 'color': 'transparent'});
            } else {
                $('.lp-lbl').css({'background': 'none', '-webkit-background-clip': 'border-box', 'color': cLbl});
            }

            if(currentView === 'desk') {
                $('#val_top').val(top);
                $('#val_left').val(left);
            } else {
                $('#val_top_mob').val(top);
                $('#val_left_mob').val(left);
            }
            $('#val_f_num').val(fNum);
            $('#val_f_lbl').val(fLbl);
            $('#val_f_fam').val(fFam);
            $('#val_f_wei_n').val(fWeiN);
            $('#val_f_wei_l').val(fWeiL);
            $('#val_gap_col').val(gCol);
            $('#val_gap_row').val(gRow);
            $('#val_c_num').val(cNum);
            $('#val_c_lbl').val(cLbl);
        }

        function loadViewData() {
            var bgUrl = currentView === 'desk' ? $('#input-promo_banner_desk_timer').val() : $('#input-promo_banner_mob_timer').val();
            if (!bgUrl) bgUrl = currentView === 'desk' ? $('#input-promo_banner_desk').val() : $('#input-promo_banner_mob').val();
            $('#lp-img').attr('src', bgUrl);

            var top = currentView === 'desk' ? $('#val_top').val() : $('#val_top_mob').val();
            var left = currentView === 'desk' ? $('#val_left').val() : $('#val_left_mob').val();
            
            $('#ctrl_top').val(top || (currentView==='desk'?'57':'59'));
            $('#ctrl_left').val(left || (currentView==='desk'?'50':'38'));
            
            $('#ctrl_f_num').val($('#val_f_num').val() || '16');
            $('#ctrl_f_lbl').val($('#val_f_lbl').val() || '14');
            $('#ctrl_f_fam').val($('#val_f_fam').val() || 'Work Sans'); //defaultowy font w edytorze 
            $('#ctrl_f_wei_n').val($('#val_f_wei_n').val() || '700');
            $('#ctrl_f_wei_l').val($('#val_f_wei_l').val() || '700');
            $('#ctrl_gap_col').val($('#val_gap_col').val() || '20');
            $('#ctrl_gap_row').val($('#val_gap_row').val() || '0');
            $('#ctrl_c_num').val($('#val_c_num').val() || '#FF0000');
            $('#ctrl_c_lbl').val($('#val_c_lbl').val() || '#000000');

            if (currentView === 'desk') {
                $('#lp-canvas').css('max-width', '100%');
            } else {
                $('#lp-canvas').css('max-width', '400px'); 
            }
            updateCanvas();
        }

        $('#btn-live-preview').click(function(e) {
            e.preventDefault();
            loadViewData();
            $('#promo-live-modal').css('display', 'flex');
        });

        $('#close-live-modal, #btn-save-close-modal').click(function() {
            $('#promo-live-modal').hide();
        });

        $('#promo-live-modal').click(function(e) {
            if (!$(e.target).closest('#promo-modal-box').length) {
                $(this).hide();
            }
        });

        $('.view-btn').click(function() {
            $('.view-btn').removeClass('active');
            $(this).addClass('active');
            currentView = $(this).data('view');
            loadViewData();
        });

        $('.ctrl-font').on('input change', updateCanvas);

        $('.btn-plus').click(function(){
            var target = $('#' + $(this).data('target'));
            target.val(parseInt(target.val()) + 1).trigger('input');
        });
        $('.btn-minus').click(function(){
            var target = $('#' + $(this).data('target'));
            target.val(parseInt(target.val()) - 1).trigger('input');
        });

        // --- LOGIKA PODGLĄDU NA ŻYWO (FSE / MENU) ---
        var currentCommonView = 'desk';
        var currentCommonMode = 'fse'; // 'fse' lub 'mm'

        function updateCommonCanvas() {
            if (currentCommonMode === 'fse') {
                $('#lp-fse-wrapper').show();
                $('#lp-mm-wrapper').hide();
                $('#live-sidebar-settings-fse').show();
                $('#live-sidebar-settings-mm').hide();
                $('#common-modal-title').text('Podgląd Na Żywo FSE');

                var bgUrl = currentCommonView === 'desk' ? $('#input-promo_fse_banner_image').val() : $('#input-promo_fse_banner_image_mobile').val();
                if (!bgUrl) bgUrl = currentCommonView === 'desk' ? $('#input-promo_fse_banner_image_mobile').val() : $('#input-promo_fse_banner_image').val();
                $('#lp-fse-bg').attr('src', bgUrl);

                // Subtitle
                var subText = $('input[name="promo_fse_subtitle"]').val();
                var subColor = $('input[name="promo_fse_subtitle_color"]').val() || '#ffffff';
                var subBg = $('input[name="promo_fse_subtitle_bg"]').val() || 'transparent';
                var subFf = $('input[name="promo_fse_subtitle_ff"]').val() || 'Poppins';
                var subFs = $('input[name="promo_fse_subtitle_fs"]').val() || '14';
                
                if (subText) {
                    $('#lp-fse-subtitle').text(subText).show();
                    applyColorSettings($('#lp-fse-subtitle'), subColor);
                    $('#lp-fse-subtitle').css({ 'background-color': subBg, 'font-family': subFf, 'font-size': subFs + 'px' });
                } else {
                    $('#lp-fse-subtitle').hide();
                }

                // Title
                var useSvg = $('input[name="promo_fse_use_title_svg"]').is(':checked');
                var iconType = $('input[name="promo_fse_title_icon_type"]:checked').val() || 'svg';
                var align = $('select[name="promo_fse_title_svg_align"]').val() || 'center';
                
                var titleContainerJustify = 'center';
                if (align === 'left') titleContainerJustify = 'flex-start';
                if (align === 'right') titleContainerJustify = 'flex-end';
                $('#lp-fse-title-container').css('justify-content', titleContainerJustify);

                if (useSvg) {
                    $('#lp-fse-title-text').hide();
                    var hVal = currentCommonView === 'desk' ? $('input[name="promo_fse_title_svg_h_val"]').val() : $('input[name="promo_fse_title_svg_hm_val"]').val();
                    var hUnit = currentCommonView === 'desk' ? $('select[name="promo_fse_title_svg_h_unit"]').val() : $('select[name="promo_fse_title_svg_hm_unit"]').val();
                    var heightStr = (hVal || '50') + (hUnit || 'px');

                    if (iconType === 'svg') {
                        var svgCode = $('textarea[name="promo_fse_title_svg"]').val();
                        $('#lp-fse-title-svg').html(svgCode).css('display', 'flex');
                        $('#lp-fse-title-img').hide();
                        $('#lp-fse-title-svg svg').css({ 'height': heightStr, 'width': 'auto', 'max-width': '100%' });
                    } else {
                        var imgUrl = $('#input-promo_fse_title_image').val();
                        if (imgUrl) {
                            $('#lp-fse-title-img').attr('src', imgUrl).css({ 'height': heightStr, 'max-width': '100%' }).show();
                        } else {
                            $('#lp-fse-title-img').hide();
                        }
                        $('#lp-fse-title-svg').hide();
                    }
                } else {
                    $('#lp-fse-title-svg').hide();
                    $('#lp-fse-title-img').hide();
                    var tText = $('input[name="promo_fse_title"]').val();
                    var tColor = $('input[name="promo_fse_title_color"]').val() || '#ffffff';
                    var tFf = $('input[name="promo_fse_title_ff"]').val() || 'TAN - AEGEAN';
                    var tFs = currentCommonView === 'desk' ? $('input[name="promo_fse_title_fs"]').val() : $('input[name="promo_fse_title_fs_mobile"]').val();
                    
                    if (tText) {
                        $('#lp-fse-title-text').text(tText).show();
                        applyColorSettings($('#lp-fse-title-text'), tColor);
                        $('#lp-fse-title-text').css({ 'font-family': tFf, 'font-size': (tFs||'56') + 'px' });
                    } else {
                        $('#lp-fse-title-text').hide();
                    }
                }

                // Body
                var bodyText = $('textarea[name="promo_fse_body_text"]').val();
                var bodyColor = $('input[name="promo_fse_body_color"]').val() || '#ffffff';
                var bodyFf = $('input[name="promo_fse_body_ff"]').val() || 'Poppins';
                var bodyFs = $('input[name="promo_fse_body_fs"]').val() || '16';
                if (bodyText) {
                    $('#lp-fse-body').text(bodyText).show();
                    applyColorSettings($('#lp-fse-body'), bodyColor);
                    $('#lp-fse-body').css({ 'font-family': bodyFf, 'font-size': bodyFs + 'px' });
                } else {
                    $('#lp-fse-body').hide();
                }

                // Button
                var btnText = $('input[name="promo_fse_button_text"]').val() || 'Poznaj kolekcję';
                var btnBg = $('input[name="promo_fse_button_bg"]').val() || '#ffffff';
                var btnTc = $('input[name="promo_fse_button_text_color"]').val() || '#000000';
                var btnFf = $('input[name="promo_fse_button_ff"]').val() || 'Poppins';
                var btnFs = $('input[name="promo_fse_button_fs"]').val() || '16';
                var arrBg = $('input[name="promo_fse_arrow_bg"]').val() || '#000000';
                var arrTc = $('input[name="promo_fse_arrow_color"]').val() || '#ffffff';

                $('#lp-fse-btn-text').text(btnText).css({ 'color': btnTc, 'font-family': btnFf, 'font-size': btnFs + 'px' });
                $('#lp-fse-btn').css({ 'background-color': btnBg });
                $('#lp-fse-arr-bg').attr('fill', arrBg);
                $('#lp-fse-arr-color').attr('fill', arrTc);

                // Dostosowanie do mobile vs desktop dla FSE
                if (currentCommonView === 'mob') {
                    $('#lp-fse-content').css({
                        'top': 'auto', 'right': '0', 'bottom': '0', 'left': '0',
                        'width': '100%', 'padding': '20px 24px 100px 24px', 'justify-content': 'flex-end', 'gap': '12px'
                    });
                    $('#lp-fse-subtitle').css({ 'font-size': '12px', 'padding': '6px 14px' });
                    $('#lp-fse-btn-container').css({ 'right': '24px', 'bottom': '24px' });
                    $('#lp-fse-btn').css({ 'padding': '4px 4px 4px 20px', 'gap': '8px' });
                    $('#lp-fse-btn-arrow').css({ 'width': '40px', 'height': '40px' });
                    $('#lp-fse-btn-arrow svg').attr('width', '40').attr('height', '40');
                } else {
                    $('#lp-fse-content').css({
                        'top': '0', 'right': '0', 'bottom': '0', 'left': 'auto',
                        'width': '50%', 'padding': '40px 60px 96px', 'justify-content': 'center', 'gap': '16px'
                    });
                    $('#lp-fse-btn-container').css({ 'right': '24px', 'bottom': '24px' });
                    $('#lp-fse-btn').css({ 'padding': '4px 4px 4px 24px', 'gap': '12px' });
                    $('#lp-fse-btn-arrow').css({ 'width': '48px', 'height': '48px' });
                    $('#lp-fse-btn-arrow svg').attr('width', '48').attr('height', '48');
                }

            } else if (currentCommonMode === 'mm') {
                $('#lp-mm-wrapper').show();
                $('#lp-fse-wrapper').hide();
                $('#live-sidebar-settings-mm').show();
                $('#live-sidebar-settings-fse').hide();
                $('#common-modal-title').text('Podgląd Na Żywo Menu Bannera');

                var bgUrlMM = $('#input-promo_mm_banner_image').val();
                if(bgUrlMM) {
                    $('#lp-mm-bg').attr('src', bgUrlMM).show();
                } else {
                    $('#lp-mm-bg').hide();
                }

                // Subtitle
                var useSvgMMSub = $('input[name="promo_mm_banner_use_subtitle_svg"]').is(':checked');
                var iconTypeMMSub = $('input[name="promo_mm_banner_subtitle_icon_type"]:checked').val() || 'svg';
                
                var alignMMSub = $('select[name="promo_mm_banner_subtitle_svg_align"]').val() || 'left';
                var subContainerJustifyMM = 'center';
                if (alignMMSub === 'left') subContainerJustifyMM = 'flex-start';
                if (alignMMSub === 'right') subContainerJustifyMM = 'flex-end';
                $('#lp-mm-subtitle-container').css('justify-content', subContainerJustifyMM);
                $('#lp-mm-subtitle-svg').css('justify-content', subContainerJustifyMM);

                if (useSvgMMSub) {
                    $('#lp-mm-subtitle').hide();
                    var hValMMSub = $('input[name="promo_mm_banner_subtitle_svg_h_val"]').val() || '30';
                    var hUnitMMSub = $('select[name="promo_mm_banner_subtitle_svg_h_unit"]').val() || 'px';
                    var heightStrMMSub = hValMMSub + hUnitMMSub;

                    if (iconTypeMMSub === 'svg') {
                        var svgCodeMMSub = $('textarea[name="promo_mm_banner_subtitle_svg"]').val();
                        $('#lp-mm-subtitle-svg').html(svgCodeMMSub).css('display', 'flex');
                        $('#lp-mm-subtitle-img').hide();
                        $('#lp-mm-subtitle-svg svg').css({ 'height': heightStrMMSub, 'width': 'auto', 'max-width': '100%', 'display': 'block' });
                    } else {
                        var imgUrlMMSub = $('#input-promo_mm_banner_subtitle_image').val();
                        if (imgUrlMMSub) {
                            $('#lp-mm-subtitle-img').attr('src', imgUrlMMSub).css({ 'height': heightStrMMSub, 'max-width': '100%', 'display': 'block' }).show();
                        } else {
                            $('#lp-mm-subtitle-img').hide();
                        }
                        $('#lp-mm-subtitle-svg').hide();
                    }
                } else {
                    $('#lp-mm-subtitle-svg').hide();
                    $('#lp-mm-subtitle-img').hide();
                    var mmSub = $('input[name="promo_mm_banner_subtitle"]').val();
                    var mmSubBg = $('input[name="promo_mm_banner_subtitle_bg"]').val() || '';
                    if (mmSubBg === '') {
                        $('#lp-mm-subtitle').css('background', 'transparent');
                    } else {
                        $('#lp-mm-subtitle').css('background', mmSubBg);
                    }
                    if (mmSub) {
                        $('#lp-mm-subtitle').text(mmSub).show();
                    } else {
                        $('#lp-mm-subtitle').hide();
                    }
                }

                // Title
                var useSvgMM = $('input[name="promo_mm_banner_use_title_svg"]').is(':checked');
                var iconTypeMM = $('input[name="promo_mm_banner_title_icon_type"]:checked').val() || 'svg';
                
                var alignMM = $('select[name="promo_mm_banner_title_svg_align"]').val() || 'left';
                var titleContainerJustifyMM = 'center';
                if (alignMM === 'left') titleContainerJustifyMM = 'flex-start';
                if (alignMM === 'right') titleContainerJustifyMM = 'flex-end';
                $('#lp-mm-title-container').css('justify-content', titleContainerJustifyMM);
                $('#lp-mm-title-svg').css('justify-content', titleContainerJustifyMM);

                if (useSvgMM) {
                    $('#lp-mm-title-text').hide();
                    var hValMM = $('input[name="promo_mm_banner_title_svg_h_val"]').val() || '30';
                    var hUnitMM = $('select[name="promo_mm_banner_title_svg_h_unit"]').val() || 'px';
                    var heightStrMM = hValMM + hUnitMM;

                    if (iconTypeMM === 'svg') {
                        var svgCodeMM = $('textarea[name="promo_mm_banner_title_svg"]').val();
                        $('#lp-mm-title-svg').html(svgCodeMM).css('display', 'flex');
                        $('#lp-mm-title-img').hide();
                        $('#lp-mm-title-svg svg').css({ 'height': heightStrMM, 'width': 'auto', 'max-width': '100%', 'display': 'block' });
                    } else {
                        var imgUrlMM = $('#input-promo_mm_banner_title_image').val();
                        if (imgUrlMM) {
                            $('#lp-mm-title-img').attr('src', imgUrlMM).css({ 'height': heightStrMM, 'max-width': '100%', 'display': 'block' }).show();
                        } else {
                            $('#lp-mm-title-img').hide();
                        }
                        $('#lp-mm-title-svg').hide();
                    }
                } else {
                    $('#lp-mm-title-svg').hide();
                    $('#lp-mm-title-img').hide();
                    var tTextMM = $('input[name="promo_mm_banner_title"]').val();
                    if (tTextMM) {
                        $('#lp-mm-title-text').text(tTextMM).show();
                    } else {
                        $('#lp-mm-title-text').hide();
                    }
                }

                // Button
                var useSvgMMBtn = $('input[name="promo_mm_banner_use_btn_svg"]').is(':checked');
                var iconTypeMMBtn = $('input[name="promo_mm_banner_btn_icon_type"]:checked').val() || 'svg';
                
                var alignMMBtn = $('select[name="promo_mm_banner_btn_svg_align"]').val() || 'left';
                var btnContainerJustifyMM = 'center';
                if (alignMMBtn === 'left') btnContainerJustifyMM = 'flex-start';
                if (alignMMBtn === 'right') btnContainerJustifyMM = 'flex-end';
                $('#lp-mm-btn-container').css('justify-content', btnContainerJustifyMM);
                $('#lp-mm-btn-svg').css('justify-content', btnContainerJustifyMM);

                if (useSvgMMBtn) {
                    $('#lp-mm-btn-text').hide();
                    var hValMMBtn = $('input[name="promo_mm_banner_btn_svg_h_val"]').val() || '30';
                    var hUnitMMBtn = $('select[name="promo_mm_banner_btn_svg_h_unit"]').val() || 'px';
                    var heightStrMMBtn = hValMMBtn + hUnitMMBtn;

                    if (iconTypeMMBtn === 'svg') {
                        var svgCodeMMBtn = $('textarea[name="promo_mm_banner_btn_svg"]').val();
                        $('#lp-mm-btn-svg').html(svgCodeMMBtn).css('display', 'flex');
                        $('#lp-mm-btn-img').hide();
                        $('#lp-mm-btn-svg svg').css({ 'height': heightStrMMBtn, 'width': 'auto', 'max-width': '100%', 'display': 'block' });
                    } else {
                        var imgUrlMMBtn = $('#input-promo_mm_banner_btn_image').val();
                        if (imgUrlMMBtn) {
                            $('#lp-mm-btn-img').attr('src', imgUrlMMBtn).css({ 'height': heightStrMMBtn, 'max-width': '100%', 'display': 'block' }).show();
                        } else {
                            $('#lp-mm-btn-img').hide();
                        }
                        $('#lp-mm-btn-svg').hide();
                    }
                } else {
                    $('#lp-mm-btn-svg').hide();
                    $('#lp-mm-btn-img').hide();
                    var btnTextMM = $('input[name="promo_mm_banner_btn_text"]').val() || 'Zobacz więcej';
                    $('#lp-mm-btn-text').text(btnTextMM).show();
                }
            }
        }

        $('#btn-fse-live-preview').click(function(e) {
            e.preventDefault();
            currentCommonMode = 'fse';
            
            $('.view-switch').show();
            $('.common-view-btn').removeClass('active');
            $('#cvb-desk').addClass('active');
            currentCommonView = 'desk';
            
            $('#lp-common-canvas').css('max-width', '100%');
            
            updateCommonCanvas();
            $('#promo-common-live-modal').css('display', 'flex');
        });

        $('#btn-mm-live-preview').click(function(e) {
            e.preventDefault();
            currentCommonMode = 'mm';
            
            $('.view-switch').hide();
            $('.common-view-btn').removeClass('active');
            $('#cvb-mob').addClass('active');
            currentCommonView = 'mob';
            
            $('#lp-common-canvas').css('max-width', '400px');
            
            updateCommonCanvas();
            $('#promo-common-live-modal').css('display', 'flex');
        });

        $('#close-common-live-modal, #btn-save-close-common-modal').click(function() {
            $('#promo-common-live-modal').hide();
        });

        $('#promo-common-live-modal').click(function(e) {
            if (!$(e.target).closest('#promo-common-modal-box').length) {
                $(this).hide();
            }
        });

        $('.common-view-btn').click(function() {
            $('.common-view-btn').removeClass('active');
            $(this).addClass('active');
            currentCommonView = $(this).data('view');
            if (currentCommonView === 'desk') {
                $('#lp-common-canvas').css('max-width', '100%');
            } else {
                $('#lp-common-canvas').css('max-width', '400px'); 
            }
            updateCommonCanvas();
        });

        // Nasłuchiwanie zmian dla live preview FSE i Menu
        $('input[name^="promo_fse_"], select[name^="promo_fse_"], textarea[name^="promo_fse_"], input[name^="promo_mm_"], select[name^="promo_mm_"], textarea[name^="promo_mm_"]').on('input change', function() {
            if($('#promo-common-live-modal').is(':visible')) {
                updateCommonCanvas();
            }
        });

        // --- BEZPIECZNE KOPIOWANIE USTAWIEŃ ---
        $('#btn-copy-config').click(function(e){
            e.preventDefault();
            var config = {
                gl:  $('input[name="promo_global"]').is(':checked') ? 'yes' : 'no',
                pri: $('input[name="promo_priority"]').val() || '',
                pct: $('input[name="promo_percentage_text"]').val() || '',
                txt: $('input[name="promo_small_text"]').val() || '',
                cup: $('input[name="promo_coupon_code"]').val() || '',
                bbg: $('input[name="promo_badge_bg"]').val() || '',
                bc:  $('input[name="promo_badge_color"]').val() || '',
                hmn: $('select[name="promo_hide_fse_btn"]').val() || 'no',
                tt:  $('#val_top').val() || '',
                tl:  $('#val_left').val() || '',
                ttm: $('#val_top_mob').val() || '',
                tlm: $('#val_left_mob').val() || '',
                fn:  $('#val_f_num').val() || '',
                fl:  $('#val_f_lbl').val() || '',
                ff:  $('#val_f_fam').val() || 'Poppins',
                wn:  $('#val_f_wei_n').val() || '700',
                wl:  $('#val_f_wei_l').val() || '700',
                gc:  $('#val_gap_col').val() || '20',
                gr:  $('#val_gap_row').val() || '0',
                cn:  $('#val_c_num').val() || '#FF0000',
                cl:  $('#val_c_lbl').val() || '#000000',
                dst: $('#date_start').val() || '',
                dex: $('#date_ext').val() || '',
                den: $('#date_end').val() || '',
                fnx: $('#date_final_fixed').val() || '',
                rui: $('#date_remove_ui').val() || '',

                // DYNAMIC PRICING
                dt1: $('select[name="promo_discount_type_1"]').val() || '',
                dv1: $('input[name="promo_discount_value_1"]').val() || '',
                ds1: $('select[name="promo_discount_scope_1"]').val() || '',
                dt2: $('select[name="promo_discount_type_2"]').val() || '',
                dv2: $('input[name="promo_discount_value_2"]').val() || '',
                ds2: $('select[name="promo_discount_scope_2"]').val() || '',
                dt3: $('select[name="promo_discount_type_3"]').val() || '',
                dv3: $('input[name="promo_discount_value_3"]').val() || '',
                ds3: $('select[name="promo_discount_scope_3"]').val() || '',
                dt4: $('select[name="promo_discount_type_4"]').val() || '',
                dv4: $('input[name="promo_discount_value_4"]').val() || '',
                ds4: $('select[name="promo_discount_scope_4"]').val() || '',
                dt5: $('select[name="promo_discount_type_5"]').val() || '',
                dv5: $('input[name="promo_discount_value_5"]').val() || '',
                ds5: $('select[name="promo_discount_scope_5"]').val() || '',
                
                // KOPIOWANIE DANYCH ZDJĘĆ Z PODZIAŁEM DESK/MOB
                p1: $('input[name="promo_photo_1"]').val() || '',
                p1m: $('input[name="promo_photo_mob_1"]').val() || '',
                p2: $('input[name="promo_photo_2"]').val() || '',
                p2m: $('input[name="promo_photo_mob_2"]').val() || '',
                p3: $('input[name="promo_photo_3"]').val() || '',
                p3m: $('input[name="promo_photo_mob_3"]').val() || '',
                p4: $('input[name="promo_photo_4"]').val() || '',
                p4m: $('input[name="promo_photo_mob_4"]').val() || '',
                p5: $('input[name="promo_photo_5"]').val() || '',
                p5m: $('input[name="promo_photo_mob_5"]').val() || '',

                // KOPIOWANIE DANYCH BADGE'Y
                b1t: $('input[name="promo_badge_text_1"]').val() || '',
                b1type: $('input[name="promo_badge_icon_type_1"]:checked').val() || 'svg',
                b1s: $('textarea[name="promo_badge_svg_1"]').val() || '',
                b1img: $('input[name="promo_badge_image_1"]').val() || '',
                b1bg: $('input[name="promo_badge_bg_color_1"]').val() || '',
                b1bgi: $('input[name="promo_badge_bg_image_1"]').val() || '',
                b1tc: $('input[name="promo_badge_text_color_1"]').val() || '',
                b1w: $('input[name="promo_badge_width_1"]').val() || '100',
                b1wa: $('input[name="promo_badge_width_auto_1"]').is(':checked') ? 'yes' : 'no',
                b1al: $('select[name="promo_badge_align_1"]').val() || 'flex-start',
                b1mt: $('input[name="promo_badge_mt_1"]').val() || '12',
                b1mb: $('input[name="promo_badge_mb_1"]').val() || '0',
                b1py: $('input[name="promo_badge_py_1"]').val() || '5',
                b1px: $('input[name="promo_badge_px_1"]').val() || '10',
                b1isv: $('input[name="promo_badge_icon_size_val_1"]').val() || '1.2',
                b1isu: $('select[name="promo_badge_icon_size_unit_1"]').val() || 'em',

                b2t: $('input[name="promo_badge_text_2"]').val() || '',
                b2type: $('input[name="promo_badge_icon_type_2"]:checked').val() || 'svg',
                b2s: $('textarea[name="promo_badge_svg_2"]').val() || '',
                b2img: $('input[name="promo_badge_image_2"]').val() || '',
                b2bg: $('input[name="promo_badge_bg_color_2"]').val() || '',
                b2bgi: $('input[name="promo_badge_bg_image_2"]').val() || '',
                b2tc: $('input[name="promo_badge_text_color_2"]').val() || '',
                b2w: $('input[name="promo_badge_width_2"]').val() || '100',
                b2wa: $('input[name="promo_badge_width_auto_2"]').is(':checked') ? 'yes' : 'no',
                b2al: $('select[name="promo_badge_align_2"]').val() || 'flex-start',
                b2mt: $('input[name="promo_badge_mt_2"]').val() || '12',
                b2mb: $('input[name="promo_badge_mb_2"]').val() || '0',
                b2py: $('input[name="promo_badge_py_2"]').val() || '5',
                b2px: $('input[name="promo_badge_px_2"]').val() || '10',
                b2isv: $('input[name="promo_badge_icon_size_val_2"]').val() || '1.2',
                b2isu: $('select[name="promo_badge_icon_size_unit_2"]').val() || 'em',

                b3t: $('input[name="promo_badge_text_3"]').val() || '',
                b3type: $('input[name="promo_badge_icon_type_3"]:checked').val() || 'svg',
                b3s: $('textarea[name="promo_badge_svg_3"]').val() || '',
                b3img: $('input[name="promo_badge_image_3"]').val() || '',
                b3bg: $('input[name="promo_badge_bg_color_3"]').val() || '',
                b3bgi: $('input[name="promo_badge_bg_image_3"]').val() || '',
                b3tc: $('input[name="promo_badge_text_color_3"]').val() || '',
                b3w: $('input[name="promo_badge_width_3"]').val() || '100',
                b3wa: $('input[name="promo_badge_width_auto_3"]').is(':checked') ? 'yes' : 'no',
                b3al: $('select[name="promo_badge_align_3"]').val() || 'flex-start',
                b3mt: $('input[name="promo_badge_mt_3"]').val() || '12',
                b3mb: $('input[name="promo_badge_mb_3"]').val() || '0',
                b3py: $('input[name="promo_badge_py_3"]').val() || '5',
                b3px: $('input[name="promo_badge_px_3"]').val() || '10',
                b3isv: $('input[name="promo_badge_icon_size_val_3"]').val() || '1.2',
                b3isu: $('select[name="promo_badge_icon_size_unit_3"]').val() || 'em',

                // ZESTAWY GLOBALNE I BANNER ATC URL ORAZ TEKST I PRZYCISKI
                s1t: $('input[name="promo_set_title_1"]').val() || '',
                s2t: $('input[name="promo_set_title_2"]').val() || '',
                s3t: $('input[name="promo_set_title_3"]').val() || '',
                s1all: $('input[name="promo_set_all_products_1"]').is(':checked') ? 'yes' : 'no',
                s2all: $('input[name="promo_set_all_products_2"]').is(':checked') ? 'yes' : 'no',
                s3all: $('input[name="promo_set_all_products_3"]').is(':checked') ? 'yes' : 'no',
                s1btn: $('input[name="promo_set_btn_label_1"]').val() || '',
                s2btn: $('input[name="promo_set_btn_label_2"]').val() || '',
                s3btn: $('input[name="promo_set_btn_label_3"]').val() || '',
                s1hl: $('input[name="promo_set_header_label_1"]').val() || '',
                s2hl: $('input[name="promo_set_header_label_2"]').val() || '',
                s3hl: $('input[name="promo_set_header_label_3"]').val() || '',
                batcd: $('input[name="promo_banner_atc_desk"]').val() || '',
                batcurl: $('input[name="promo_banner_atc_reg_url"]').val() || '',
                batctxt: $('input[name="promo_banner_atc_text"]').val() || '',
                
                // KOPIOWANIE NOWYCH PÓL HEADERA I MENU
                hbe: $('select[name="promo_header_btn_enabled"]').val() || 'no',
                hbt: $('input[name="promo_header_btn_text"]').val() || '',
                hbl: $('input[name="promo_header_btn_link"]').val() || '',
                mbe: $('select[name="promo_mm_banner_enabled"]').val() || 'no',
                mbi: $('input[name="promo_mm_banner_image"]').val() || '',
                mbs: $('input[name="promo_mm_banner_subtitle"]').val() || '',
                mbt: $('input[name="promo_mm_banner_title"]').val() || '',
                mbbt: $('input[name="promo_mm_banner_btn_text"]').val() || '',
                mbbl: $('input[name="promo_mm_banner_btn_link"]').val() || '',
                mmusvg: $('input[name="promo_mm_banner_use_title_svg"]').is(':checked') ? 'yes' : 'no',
                mmitype: $('input[name="promo_mm_banner_title_icon_type"]:checked').val() || 'svg',
                mmsvg: $('textarea[name="promo_mm_banner_title_svg"]').val() || '',
                mmimg: $('input[name="promo_mm_banner_title_image"]').val() || '',
                mmhval: $('input[name="promo_mm_banner_title_svg_h_val"]').val() || '30',
                mmhunit: $('select[name="promo_mm_banner_title_svg_h_unit"]').val() || 'px',
                mmsvgal: $('select[name="promo_mm_banner_title_svg_align"]').val() || 'center',
                mmsubbg: $('input[name="promo_mm_banner_subtitle_bg"]').val() || '',
                mmsubsvg: $('input[name="promo_mm_banner_use_subtitle_svg"]').is(':checked') ? 'yes' : 'no',
                mmsubtype: $('input[name="promo_mm_banner_subtitle_icon_type"]:checked').val() || 'svg',
                mmsubcode: $('textarea[name="promo_mm_banner_subtitle_svg"]').val() || '',
                mmsubimg: $('input[name="promo_mm_banner_subtitle_image"]').val() || '',
                mmsubhval: $('input[name="promo_mm_banner_subtitle_svg_h_val"]').val() || '30',
                mmsubhunit: $('select[name="promo_mm_banner_subtitle_svg_h_unit"]').val() || 'px',
                mmsubal: $('select[name="promo_mm_banner_subtitle_svg_align"]').val() || 'center',
                mmbtnsvg: $('input[name="promo_mm_banner_use_btn_svg"]').is(':checked') ? 'yes' : 'no',
                mmbtntype: $('input[name="promo_mm_banner_btn_icon_type"]:checked').val() || 'svg',
                mmbtncode: $('textarea[name="promo_mm_banner_btn_svg"]').val() || '',
                mmbtnimg: $('input[name="promo_mm_banner_btn_image"]').val() || '',
                mmbtnhval: $('input[name="promo_mm_banner_btn_svg_h_val"]').val() || '30',
                mmbtnhunit: $('select[name="promo_mm_banner_btn_svg_h_unit"]').val() || 'px',
                mmbtnal: $('select[name="promo_mm_banner_btn_svg_align"]').val() || 'center',
                
                // KOPIOWANIE FSE
                fsesub: $('input[name="promo_fse_subtitle"]').val() || '',
                fsei: $('input[name="promo_fse_banner_image"]').val() || '',
                fseim: $('input[name="promo_fse_banner_image_mobile"]').val() || '',
                fsesubff: $('input[name="promo_fse_subtitle_ff"]').val() || '',
                fsesubfs: $('input[name="promo_fse_subtitle_fs"]').val() || '',
                fsesubc: $('input[name="promo_fse_subtitle_color"]').val() || '',
                fsesubbg: $('input[name="promo_fse_subtitle_bg"]').val() || '',
                fseusvg: $('input[name="promo_fse_use_title_svg"]').is(':checked') ? 'yes' : 'no',
                fseitype: $('input[name="promo_fse_title_icon_type"]:checked').val() || 'svg',
                fsesvg: $('textarea[name="promo_fse_title_svg"]').val() || '',
                fseimg: $('input[name="promo_fse_title_image"]').val() || '',
                fsehval: $('input[name="promo_fse_title_svg_h_val"]').val() || '50',
                fsehunit: $('select[name="promo_fse_title_svg_h_unit"]').val() || 'px',
                fsehmval: $('input[name="promo_fse_title_svg_hm_val"]').val() || '40',
                fsehmunit: $('select[name="promo_fse_title_svg_hm_unit"]').val() || 'px',
                fsesvgal: $('select[name="promo_fse_title_svg_align"]').val() || 'center',
                fsetitle: $('input[name="promo_fse_title"]').val() || '',
                fsetitleff: $('input[name="promo_fse_title_ff"]').val() || '',
                fsetitlefs: $('input[name="promo_fse_title_fs"]').val() || '',
                fsetitlefsm: $('input[name="promo_fse_title_fs_mobile"]').val() || '',
                fsetitlec: $('input[name="promo_fse_title_color"]').val() || '',
                fsebody: $('textarea[name="promo_fse_body_text"]').val() || '',
                fsebodyff: $('input[name="promo_fse_body_ff"]').val() || '',
                fsebodyfs: $('input[name="promo_fse_body_fs"]').val() || '',
                fsebodyc: $('input[name="promo_fse_body_color"]').val() || '',
                fsebtn: $('input[name="promo_fse_button_text"]').val() || '',
                fsebtnl: $('input[name="promo_fse_button_link"]').val() || '',
                fsebtnff: $('input[name="promo_fse_button_ff"]').val() || '',
                fsebtnfs: $('input[name="promo_fse_button_fs"]').val() || '',
                fsebtnbg: $('input[name="promo_fse_button_bg"]').val() || '',
                fsebtntc: $('input[name="promo_fse_button_text_color"]').val() || '',
                fsearbg: $('input[name="promo_fse_arrow_bg"]').val() || '',
                fsearc: $('input[name="promo_fse_arrow_color"]').val() || ''
            };
            var str = btoa(unescape(encodeURIComponent(JSON.stringify(config))));
            
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val(str).select();
            try {
                document.execCommand("copy");
                alert('Skopiowano kod ustawień!');
            } catch(err) {
                alert('Nie udało się skopiować automatycznie. Zaznacz i skopiuj ten kod:\n\n' + str);
            }
            $temp.remove();
        });

        // --- WKLEJANIE USTAWIEŃ ---
        $('#btn-paste-config').click(function(e){
            e.preventDefault();
            var str = prompt('Wklej wygenerowany kod ustawień z innej domeny:');
            if(str) {
                try {
                    var decoded = atob(str);
                    var c;
                    try {
                        c = JSON.parse(decodeURIComponent(escape(decoded)));
                    } catch(err2) {
                        c = JSON.parse(decoded); // Fallback do starych kodów bez Unicode
                    }

                    if(c.gl === 'yes') $('input[name="promo_global"]').prop('checked', true); else $('input[name="promo_global"]').prop('checked', false);
                    $('input[name="promo_priority"]').val(c.pri);
                    $('input[name="promo_percentage_text"]').val(c.pct);
                    $('input[name="promo_small_text"]').val(c.txt);
                    if(c.cup !== undefined) $('input[name="promo_coupon_code"]').val(c.cup);
                    $('input[name="promo_badge_bg"]').val(c.bbg);
                    $('input[name="promo_badge_color"]').val(c.bc);
                    if(c.hmn !== undefined) $('select[name="promo_hide_fse_btn"]').val(c.hmn);
                    
                    $('#val_top').val(c.tt);
                    $('#val_left').val(c.tl);
                    $('#val_top_mob').val(c.ttm);
                    $('#val_left_mob').val(c.tlm);
                    $('#val_f_num').val(c.fn);
                    $('#val_f_lbl').val(c.fl);
                    $('#val_f_fam').val(c.ff || 'Poppins');
                    $('#val_f_wei_n').val(c.wn || '700');
                    $('#val_f_wei_l').val(c.wl || '700');
                    $('#val_gap_col').val(c.gc || '20');
                    $('#val_gap_row').val(c.gr || '0');
                    $('#val_c_num').val(c.cn || '#FF0000');
                    $('#val_c_lbl').val(c.cl || '#000000');
                    
                    // DATY
                    if(c.dst !== undefined) $('#date_start').val(c.dst);
                    if(c.dex !== undefined) $('#date_ext').val(c.dex);
                    if(c.den !== undefined) $('#date_end').val(c.den);
                    if(c.fnx !== undefined) $('#date_final_fixed').val(c.fnx);
                    if(c.rui !== undefined) $('#date_remove_ui').val(c.rui);

                    // DYNAMIC PRICING
                    if(c.dt1 !== undefined) $('select[name="promo_discount_type_1"]').val(c.dt1);
                    if(c.dv1 !== undefined) $('input[name="promo_discount_value_1"]').val(c.dv1);
                    if(c.ds1 !== undefined) $('select[name="promo_discount_scope_1"]').val(c.ds1);
                    if(c.dt2 !== undefined) $('select[name="promo_discount_type_2"]').val(c.dt2);
                    if(c.dv2 !== undefined) $('input[name="promo_discount_value_2"]').val(c.dv2);
                    if(c.ds2 !== undefined) $('select[name="promo_discount_scope_2"]').val(c.ds2);
                    if(c.dt3 !== undefined) $('select[name="promo_discount_type_3"]').val(c.dt3);
                    if(c.dv3 !== undefined) $('input[name="promo_discount_value_3"]').val(c.dv3);
                    if(c.ds3 !== undefined) $('select[name="promo_discount_scope_3"]').val(c.ds3);
                    if(c.dt4 !== undefined) $('select[name="promo_discount_type_4"]').val(c.dt4);
                    if(c.dv4 !== undefined) $('input[name="promo_discount_value_4"]').val(c.dv4);
                    if(c.ds4 !== undefined) $('select[name="promo_discount_scope_4"]').val(c.ds4);
                    if(c.dt5 !== undefined) $('select[name="promo_discount_type_5"]').val(c.dt5);
                    if(c.dv5 !== undefined) $('input[name="promo_discount_value_5"]').val(c.dv5);
                    if(c.ds5 !== undefined) $('select[name="promo_discount_scope_5"]').val(c.ds5);

                    if(c.fnx !== undefined) $('#date_final_fixed').val(c.fnx);
                    if(c.rui !== undefined) $('#date_remove_ui').val(c.rui);
                    
                    // IMPORT ZDJĘĆ DESKTOP I MOBILE
                    if(c.p1 !== undefined) { $('input[name="promo_photo_1"]').val(c.p1); if(c.p1 !== '') $('#box-promo_photo_1').html('<img src="'+c.p1+'">'); else $('#box-promo_photo_1').html('<span class="dashicons dashicons-format-image"></span>'); }
                    if(c.p1m !== undefined) { $('input[name="promo_photo_mob_1"]').val(c.p1m); if(c.p1m !== '') $('#box-promo_photo_mob_1').html('<img src="'+c.p1m+'">'); else $('#box-promo_photo_mob_1').html('<span class="dashicons dashicons-format-image"></span>'); }
                    
                    if(c.p2 !== undefined) { $('input[name="promo_photo_2"]').val(c.p2); if(c.p2 !== '') $('#box-promo_photo_2').html('<img src="'+c.p2+'">'); else $('#box-promo_photo_2').html('<span class="dashicons dashicons-format-image"></span>'); }
                    if(c.p2m !== undefined) { $('input[name="promo_photo_mob_2"]').val(c.p2m); if(c.p2m !== '') $('#box-promo_photo_mob_2').html('<img src="'+c.p2m+'">'); else $('#box-promo_photo_mob_2').html('<span class="dashicons dashicons-format-image"></span>'); }
                    
                    if(c.p3 !== undefined) { $('input[name="promo_photo_3"]').val(c.p3); if(c.p3 !== '') $('#box-promo_photo_3').html('<img src="'+c.p3+'">'); else $('#box-promo_photo_3').html('<span class="dashicons dashicons-format-image"></span>'); }
                    if(c.p3m !== undefined) { $('input[name="promo_photo_mob_3"]').val(c.p3m); if(c.p3m !== '') $('#box-promo_photo_mob_3').html('<img src="'+c.p3m+'">'); else $('#box-promo_photo_mob_3').html('<span class="dashicons dashicons-format-image"></span>'); }
                    
                    if(c.p4 !== undefined) { $('input[name="promo_photo_4"]').val(c.p4); if(c.p4 !== '') $('#box-promo_photo_4').html('<img src="'+c.p4+'">'); else $('#box-promo_photo_4').html('<span class="dashicons dashicons-format-image"></span>'); }
                    if(c.p4m !== undefined) { $('input[name="promo_photo_mob_4"]').val(c.p4m); if(c.p4m !== '') $('#box-promo_photo_mob_4').html('<img src="'+c.p4m+'">'); else $('#box-promo_photo_mob_4').html('<span class="dashicons dashicons-format-image"></span>'); }
                    
                    if(c.p5 !== undefined) { $('input[name="promo_photo_5"]').val(c.p5); if(c.p5 !== '') $('#box-promo_photo_5').html('<img src="'+c.p5+'">'); else $('#box-promo_photo_5').html('<span class="dashicons dashicons-format-image"></span>'); }
                    if(c.p5m !== undefined) { $('input[name="promo_photo_mob_5"]').val(c.p5m); if(c.p5m !== '') $('#box-promo_photo_mob_5').html('<img src="'+c.p5m+'">'); else $('#box-promo_photo_mob_5').html('<span class="dashicons dashicons-format-image"></span>'); }
                    
                    // IMPORT BADGE'Y
                    if(c.b1t !== undefined) $('input[name="promo_badge_text_1"]').val(c.b1t);
                    if(c.b1type !== undefined) $('input[name="promo_badge_icon_type_1"][value="'+c.b1type+'"]').prop('checked', true).trigger('change');
                    if(c.b1s !== undefined) $('textarea[name="promo_badge_svg_1"]').val(c.b1s);
                    if(c.b1img !== undefined) {
                        $('input[name="promo_badge_image_1"]').val(c.b1img);
                        if(c.b1img !== '') $('#box-promo_badge_image_1').html('<img src="'+c.b1img+'">');
                    }
                    if(c.b1bg !== undefined) $('input[name="promo_badge_bg_color_1"]').val(c.b1bg);
                    if(c.b1bgi !== undefined) {
                        $('input[name="promo_badge_bg_image_1"]').val(c.b1bgi);
                        if(c.b1bgi !== '') $('#box-promo_badge_bg_image_1').html('<img src="'+c.b1bgi+'">');
                    }
                    if(c.b1tc !== undefined) $('input[name="promo_badge_text_color_1"]').val(c.b1tc);
                    if(c.b1w !== undefined) { $('input[name="promo_badge_width_1"]').val(c.b1w).trigger('input'); }
                    if(c.b1wa !== undefined) { $('input[name="promo_badge_width_auto_1"]').prop('checked', c.b1wa === 'yes'); }
                    if(c.b1al !== undefined) $('select[name="promo_badge_align_1"]').val(c.b1al);
                    if(c.b1mt !== undefined) $('input[name="promo_badge_mt_1"]').val(c.b1mt);
                    if(c.b1mb !== undefined) $('input[name="promo_badge_mb_1"]').val(c.b1mb);
                    if(c.b1py !== undefined) $('input[name="promo_badge_py_1"]').val(c.b1py);
                    if(c.b1px !== undefined) $('input[name="promo_badge_px_1"]').val(c.b1px);
                    if(c.b1isv !== undefined) $('input[name="promo_badge_icon_size_val_1"]').val(c.b1isv);
                    if(c.b1isu !== undefined) $('select[name="promo_badge_icon_size_unit_1"]').val(c.b1isu);
                    if(c.b1is !== undefined && c.b1isv === undefined) { 
                        var num = parseFloat(c.b1is); var unit = c.b1is.indexOf('px') > -1 ? 'px' : 'em';
                        $('input[name="promo_badge_icon_size_val_1"]').val(isNaN(num) ? 1.2 : num);
                        $('select[name="promo_badge_icon_size_unit_1"]').val(unit);
                    }

                    if(c.b2t !== undefined) $('input[name="promo_badge_text_2"]').val(c.b2t);
                    if(c.b2type !== undefined) $('input[name="promo_badge_icon_type_2"][value="'+c.b2type+'"]').prop('checked', true).trigger('change');
                    if(c.b2s !== undefined) $('textarea[name="promo_badge_svg_2"]').val(c.b2s);
                    if(c.b2img !== undefined) {
                        $('input[name="promo_badge_image_2"]').val(c.b2img);
                        if(c.b2img !== '') $('#box-promo_badge_image_2').html('<img src="'+c.b2img+'">');
                    }
                    if(c.b2bg !== undefined) $('input[name="promo_badge_bg_color_2"]').val(c.b2bg);
                    if(c.b2bgi !== undefined) {
                        $('input[name="promo_badge_bg_image_2"]').val(c.b2bgi);
                        if(c.b2bgi !== '') $('#box-promo_badge_bg_image_2').html('<img src="'+c.b2bgi+'">');
                    }
                    if(c.b2tc !== undefined) $('input[name="promo_badge_text_color_2"]').val(c.b2tc);
                    if(c.b2w !== undefined) { $('input[name="promo_badge_width_2"]').val(c.b2w).trigger('input'); }
                    if(c.b2wa !== undefined) { $('input[name="promo_badge_width_auto_2"]').prop('checked', c.b2wa === 'yes'); }
                    if(c.b2al !== undefined) $('select[name="promo_badge_align_2"]').val(c.b2al);
                    if(c.b2mt !== undefined) $('input[name="promo_badge_mt_2"]').val(c.b2mt);
                    if(c.b2mb !== undefined) $('input[name="promo_badge_mb_2"]').val(c.b2mb);
                    if(c.b2py !== undefined) $('input[name="promo_badge_py_2"]').val(c.b2py);
                    if(c.b2px !== undefined) $('input[name="promo_badge_px_2"]').val(c.b2px);
                    if(c.b2isv !== undefined) $('input[name="promo_badge_icon_size_val_2"]').val(c.b2isv);
                    if(c.b2isu !== undefined) $('select[name="promo_badge_icon_size_unit_2"]').val(c.b2isu);
                    if(c.b2is !== undefined && c.b2isv === undefined) { 
                        var num = parseFloat(c.b2is); var unit = c.b2is.indexOf('px') > -1 ? 'px' : 'em';
                        $('input[name="promo_badge_icon_size_val_2"]').val(isNaN(num) ? 1.2 : num);
                        $('select[name="promo_badge_icon_size_unit_2"]').val(unit);
                    }

                    if(c.b3t !== undefined) $('input[name="promo_badge_text_3"]').val(c.b3t);
                    if(c.b3type !== undefined) $('input[name="promo_badge_icon_type_3"][value="'+c.b3type+'"]').prop('checked', true).trigger('change');
                    if(c.b3s !== undefined) $('textarea[name="promo_badge_svg_3"]').val(c.b3s);
                    if(c.b3img !== undefined) {
                        $('input[name="promo_badge_image_3"]').val(c.b3img);
                        if(c.b3img !== '') $('#box-promo_badge_image_3').html('<img src="'+c.b3img+'">');
                    }
                    if(c.b3bg !== undefined) $('input[name="promo_badge_bg_color_3"]').val(c.b3bg);
                    if(c.b3bgi !== undefined) {
                        $('input[name="promo_badge_bg_image_3"]').val(c.b3bgi);
                        if(c.b3bgi !== '') $('#box-promo_badge_bg_image_3').html('<img src="'+c.b3bgi+'">');
                    }
                    if(c.b3tc !== undefined) $('input[name="promo_badge_text_color_3"]').val(c.b3tc);
                    if(c.b3w !== undefined) { $('input[name="promo_badge_width_3"]').val(c.b3w).trigger('input'); }
                    if(c.b3wa !== undefined) { $('input[name="promo_badge_width_auto_3"]').prop('checked', c.b3wa === 'yes'); }
                    if(c.b3al !== undefined) $('select[name="promo_badge_align_3"]').val(c.b3al);
                    if(c.b3mt !== undefined) $('input[name="promo_badge_mt_3"]').val(c.b3mt);
                    if(c.b3mb !== undefined) $('input[name="promo_badge_mb_3"]').val(c.b3mb);
                    if(c.b3py !== undefined) $('input[name="promo_badge_py_3"]').val(c.b3py);
                    if(c.b3px !== undefined) $('input[name="promo_badge_px_3"]').val(c.b3px);
                    if(c.b3isv !== undefined) $('input[name="promo_badge_icon_size_val_3"]').val(c.b3isv);
                    if(c.b3isu !== undefined) $('select[name="promo_badge_icon_size_unit_3"]').val(c.b3isu);
                    if(c.b3is !== undefined && c.b3isv === undefined) { 
                        var num = parseFloat(c.b3is); var unit = c.b3is.indexOf('px') > -1 ? 'px' : 'em';
                        $('input[name="promo_badge_icon_size_val_3"]').val(isNaN(num) ? 1.2 : num);
                        $('select[name="promo_badge_icon_size_unit_3"]').val(unit);
                    }

                    // ZESTAWY GLOBALNE I BANNER ATC URL ORAZ TEKST I PRZYCISKI
                    if(c.s1t !== undefined) $('input[name="promo_set_title_1"]').val(c.s1t);
                    if(c.s2t !== undefined) $('input[name="promo_set_title_2"]').val(c.s2t);
                    if(c.s3t !== undefined) $('input[name="promo_set_title_3"]').val(c.s3t);
                    if(c.s1all === 'yes') $('input[name="promo_set_all_products_1"]').prop('checked', true); else $('input[name="promo_set_all_products_1"]').prop('checked', false);
                    if(c.s2all === 'yes') $('input[name="promo_set_all_products_2"]').prop('checked', true); else $('input[name="promo_set_all_products_2"]').prop('checked', false);
                    if(c.s3all === 'yes') $('input[name="promo_set_all_products_3"]').prop('checked', true); else $('input[name="promo_set_all_products_3"]').prop('checked', false);
                    if(c.s1btn !== undefined) $('input[name="promo_set_btn_label_1"]').val(c.s1btn);
                    if(c.s2btn !== undefined) $('input[name="promo_set_btn_label_2"]').val(c.s2btn);
                    if(c.s3btn !== undefined) $('input[name="promo_set_btn_label_3"]').val(c.s3btn);
                    if(c.s1hl !== undefined) $('input[name="promo_set_header_label_1"]').val(c.s1hl);
                    if(c.s2hl !== undefined) $('input[name="promo_set_header_label_2"]').val(c.s2hl);
                    if(c.s3hl !== undefined) $('input[name="promo_set_header_label_3"]').val(c.s3hl);
                    if(c.batcd !== undefined) $('input[name="promo_banner_atc_desk"]').val(c.batcd);
                    if(c.batcurl !== undefined) $('input[name="promo_banner_atc_reg_url"]').val(c.batcurl);
                    if(c.batctxt !== undefined) $('input[name="promo_banner_atc_text"]').val(c.batctxt);
                    
                    // IMPORT NOWYCH PÓL HEADERA I MENU
                    if(c.hbe !== undefined) $('select[name="promo_header_btn_enabled"]').val(c.hbe);
                    if(c.hbt !== undefined) $('input[name="promo_header_btn_text"]').val(c.hbt);
                    if(c.hbl !== undefined) $('input[name="promo_header_btn_link"]').val(c.hbl);
                    if(c.mbe !== undefined) $('select[name="promo_mm_banner_enabled"]').val(c.mbe);
                    if(c.mbs !== undefined) $('input[name="promo_mm_banner_subtitle"]').val(c.mbs);
                    if(c.mbt !== undefined) $('input[name="promo_mm_banner_title"]').val(c.mbt);
                    if(c.mbbt !== undefined) $('input[name="promo_mm_banner_btn_text"]').val(c.mbbt);
                    if(c.mbbl !== undefined) $('input[name="promo_mm_banner_btn_link"]').val(c.mbbl);
                    if(c.mbi !== undefined) {
                        $('input[name="promo_mm_banner_image"]').val(c.mbi);
                        if(c.mbi !== '') $('#box-promo_mm_banner_image').html('<img src="'+c.mbi+'">');
                        else $('#box-promo_mm_banner_image').html('<span class="dashicons dashicons-format-image"></span>');
                    }
                    if(c.mmusvg === 'yes') $('input[name="promo_mm_banner_use_title_svg"]').prop('checked', true); else $('input[name="promo_mm_banner_use_title_svg"]').prop('checked', false);
                    if(c.mmitype !== undefined) { $('input[name="promo_mm_banner_title_icon_type"][value="'+c.mmitype+'"]').prop('checked', true).trigger('change'); }
                    if(c.mmsvg !== undefined) $('textarea[name="promo_mm_banner_title_svg"]').val(c.mmsvg).trigger('input');
                    if(c.mmimg !== undefined) { $('input[name="promo_mm_banner_title_image"]').val(c.mmimg); if(c.mmimg !== '') $('#box-promo_mm_banner_title_image').html('<img src="'+c.mmimg+'">'); else $('#box-promo_mm_banner_title_image').html('<span class="dashicons dashicons-format-image"></span>'); }
                    if(c.mmhval !== undefined) $('input[name="promo_mm_banner_title_svg_h_val"]').val(c.mmhval).trigger('input');
                    if(c.mmhunit !== undefined) $('select[name="promo_mm_banner_title_svg_h_unit"]').val(c.mmhunit).trigger('change');
                    if(c.mmsvgal !== undefined) $('select[name="promo_mm_banner_title_svg_align"]').val(c.mmsvgal).trigger('change');
                    $('input[name="promo_mm_banner_use_title_svg"]').trigger('change');

                    if(c.mmsubbg !== undefined) $('input[name="promo_mm_banner_subtitle_bg"]').val(c.mmsubbg).trigger('input');

                    if(c.mmsubsvg === 'yes') $('input[name="promo_mm_banner_use_subtitle_svg"]').prop('checked', true); else $('input[name="promo_mm_banner_use_subtitle_svg"]').prop('checked', false);
                    if(c.mmsubtype !== undefined) { $('input[name="promo_mm_banner_subtitle_icon_type"][value="'+c.mmsubtype+'"]').prop('checked', true).trigger('change'); }
                    if(c.mmsubcode !== undefined) $('textarea[name="promo_mm_banner_subtitle_svg"]').val(c.mmsubcode).trigger('input');
                    if(c.mmsubimg !== undefined) { $('input[name="promo_mm_banner_subtitle_image"]').val(c.mmsubimg); if(c.mmsubimg !== '') $('#box-promo_mm_banner_subtitle_image').html('<img src="'+c.mmsubimg+'">'); else $('#box-promo_mm_banner_subtitle_image').html('<span class="dashicons dashicons-format-image"></span>'); }
                    if(c.mmsubhval !== undefined) $('input[name="promo_mm_banner_subtitle_svg_h_val"]').val(c.mmsubhval).trigger('input');
                    if(c.mmsubhunit !== undefined) $('select[name="promo_mm_banner_subtitle_svg_h_unit"]').val(c.mmsubhunit).trigger('change');
                    if(c.mmsubal !== undefined) $('select[name="promo_mm_banner_subtitle_svg_align"]').val(c.mmsubal).trigger('change');
                    $('input[name="promo_mm_banner_use_subtitle_svg"]').trigger('change');

                    if(c.mmbtnsvg === 'yes') $('input[name="promo_mm_banner_use_btn_svg"]').prop('checked', true); else $('input[name="promo_mm_banner_use_btn_svg"]').prop('checked', false);
                    if(c.mmbtntype !== undefined) { $('input[name="promo_mm_banner_btn_icon_type"][value="'+c.mmbtntype+'"]').prop('checked', true).trigger('change'); }
                    if(c.mmbtncode !== undefined) $('textarea[name="promo_mm_banner_btn_svg"]').val(c.mmbtncode).trigger('input');
                    if(c.mmbtnimg !== undefined) { $('input[name="promo_mm_banner_btn_image"]').val(c.mmbtnimg); if(c.mmbtnimg !== '') $('#box-promo_mm_banner_btn_image').html('<img src="'+c.mmbtnimg+'">'); else $('#box-promo_mm_banner_btn_image').html('<span class="dashicons dashicons-format-image"></span>'); }
                    if(c.mmbtnhval !== undefined) $('input[name="promo_mm_banner_btn_svg_h_val"]').val(c.mmbtnhval).trigger('input');
                    if(c.mmbtnhunit !== undefined) $('select[name="promo_mm_banner_btn_svg_h_unit"]').val(c.mmbtnhunit).trigger('change');
                    if(c.mmbtnal !== undefined) $('select[name="promo_mm_banner_btn_svg_align"]').val(c.mmbtnal);
                    $('input[name="promo_mm_banner_use_btn_svg"]').trigger('change');

                    // IMPORT FSE
                    if(c.fsesub !== undefined) $('input[name="promo_fse_subtitle"]').val(c.fsesub);
                    if(c.fsei !== undefined) $('input[name="promo_fse_banner_image"]').val(c.fsei);
                    if(c.fseim !== undefined) $('input[name="promo_fse_banner_image_mobile"]').val(c.fseim);
                    if(c.fsesubff !== undefined) $('input[name="promo_fse_subtitle_ff"]').val(c.fsesubff);
                    if(c.fsesubfs !== undefined) $('input[name="promo_fse_subtitle_fs"]').val(c.fsesubfs);
                    if(c.fsesubc !== undefined) $('input[name="promo_fse_subtitle_color"]').val(c.fsesubc);
                    if(c.fsesubbg !== undefined) $('input[name="promo_fse_subtitle_bg"]').val(c.fsesubbg);
                    if(c.fseusvg === 'yes') $('input[name="promo_fse_use_title_svg"]').prop('checked', true); else $('input[name="promo_fse_use_title_svg"]').prop('checked', false);
                    if(c.fseitype !== undefined) { $('input[name="promo_fse_title_icon_type"][value="'+c.fseitype+'"]').prop('checked', true).trigger('change'); }
                    if(c.fsesvg !== undefined) $('textarea[name="promo_fse_title_svg"]').val(c.fsesvg);
                    if(c.fseimg !== undefined) { $('input[name="promo_fse_title_image"]').val(c.fseimg); if(c.fseimg !== '') $('#box-promo_fse_title_image').html('<img src="'+c.fseimg+'">'); else $('#box-promo_fse_title_image').html('<span class="dashicons dashicons-format-image"></span>'); }
                    if(c.fsehval !== undefined) $('input[name="promo_fse_title_svg_h_val"]').val(c.fsehval);
                    if(c.fsehunit !== undefined) $('select[name="promo_fse_title_svg_h_unit"]').val(c.fsehunit);
                    if(c.fsehmval !== undefined) $('input[name="promo_fse_title_svg_hm_val"]').val(c.fsehmval);
                    if(c.fsehmunit !== undefined) $('select[name="promo_fse_title_svg_hm_unit"]').val(c.fsehmunit);
                    if(c.fsesvgal !== undefined) $('select[name="promo_fse_title_svg_align"]').val(c.fsesvgal);
                    $('input[name="promo_fse_use_title_svg"]').trigger('change');
                    if(c.fsetitle !== undefined) $('input[name="promo_fse_title"]').val(c.fsetitle);
                    if(c.fsetitleff !== undefined) $('input[name="promo_fse_title_ff"]').val(c.fsetitleff);
                    if(c.fsetitlefs !== undefined) $('input[name="promo_fse_title_fs"]').val(c.fsetitlefs);
                    if(c.fsetitlefsm !== undefined) $('input[name="promo_fse_title_fs_mobile"]').val(c.fsetitlefsm);
                    if(c.fsetitlec !== undefined) $('input[name="promo_fse_title_color"]').val(c.fsetitlec);
                    if(c.fsebody !== undefined) $('textarea[name="promo_fse_body_text"]').val(c.fsebody);
                    if(c.fsebodyff !== undefined) $('input[name="promo_fse_body_ff"]').val(c.fsebodyff);
                    if(c.fsebodyfs !== undefined) $('input[name="promo_fse_body_fs"]').val(c.fsebodyfs);
                    if(c.fsebodyc !== undefined) $('input[name="promo_fse_body_color"]').val(c.fsebodyc);
                    if(c.fsebtn !== undefined) $('input[name="promo_fse_button_text"]').val(c.fsebtn);
                    if(c.fsebtnl !== undefined) $('input[name="promo_fse_button_link"]').val(c.fsebtnl);
                    if(c.fsebtnff !== undefined) $('input[name="promo_fse_button_ff"]').val(c.fsebtnff);
                    if(c.fsebtnfs !== undefined) $('input[name="promo_fse_button_fs"]').val(c.fsebtnfs);
                    if(c.fsebtnbg !== undefined) $('input[name="promo_fse_button_bg"]').val(c.fsebtnbg);
                    if(c.fsebtntc !== undefined) $('input[name="promo_fse_button_text_color"]').val(c.fsebtntc);
                    if(c.fsearbg !== undefined) $('input[name="promo_fse_arrow_bg"]').val(c.fsearbg);
                    if(c.fsearc !== undefined) $('input[name="promo_fse_arrow_color"]').val(c.fsearc);

                    if(c.fsei !== undefined && c.fsei !== '') $('#box-promo_fse_banner_image').html('<img src="'+c.fsei+'">');
                    else $('#box-promo_fse_banner_image').html('<span class="dashicons dashicons-format-image"></span>');
                    
                    if(c.fseim !== undefined && c.fseim !== '') $('#box-promo_fse_banner_image_mobile').html('<img src="'+c.fseim+'">');
                    else $('#box-promo_fse_banner_image_mobile').html('<span class="dashicons dashicons-format-image"></span>');

                    // Pokaż zdjęcia, jeśli mają dane (wystarczy sprawdzić desktop lub mobile)
                    for(var j=1; j<=5; j++) {
                        if ($('input[name="promo_photo_'+j+'"]').val() !== '' || $('input[name="promo_photo_mob_'+j+'"]').val() !== '') {
                            $('#photo-group-'+j).show();
                        }
                    }
                    updatePhotoButton();

                    // Pokaż badge, jeśli mają dane
                    for(var i=1; i<=3; i++) {
                        if ($('input[name="promo_badge_text_'+i+'"]').val() !== '' || $('textarea[name="promo_badge_svg_'+i+'"]').val() !== '' || $('input[name="promo_badge_image_'+i+'"]').val() !== '') {
                            $('#badge-group-'+i).show();
                        }
                    }
                    updateBadgeButton();

                    alert('Ustawienia pomyślnie zaimportowane! Kliknij "Zapisz" po prawej stronie, aby zatwierdzić zmiany.');
                    
                    // Uruchomienie asynchronicznego pobierania po wklejeniu configu
                    setTimeout(processAsyncSideload, 1000);
                } catch(e) {
                    alert('Wklejony kod jest nieprawidłowy!');
                }
            }
        });


        // CHIPSY AJAX
        var selectedProducts = <?php echo $saved_prods_json; ?>;
        var $chipsArea = $('#promo-chips-area');
        var $searchInput = $('#promo-product-search');
        var $dropdown = $('#promo-dropdown-list');
        var $hiddenInput = $('#promo-product-ids-hidden');
        var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
        var nonce = '<?php echo wp_create_nonce("promo_search_nonce"); ?>';

        function renderChips() {
            $chipsArea.find('.promo-chip').remove();
            var ids = [];
            $.each(selectedProducts, function(index, prod) {
                var chip = $('<div class="promo-chip"><span>' + prod.title + '</span><span class="promo-chip-remove" data-id="' + prod.id + '">&times;</span></div>');
                $searchInput.before(chip);
                ids.push(prod.id);
            });
            $hiddenInput.val(ids.join(','));
        }
        renderChips();

        $chipsArea.on('click', '.promo-chip-remove', function(e){
            e.stopPropagation(); 
            var idToRemove = $(this).data('id');
            selectedProducts = selectedProducts.filter(function(prod) { return prod.id != idToRemove; });
            renderChips();
        });

        $('#promo-select-container').on('click', function(){ $searchInput.focus(); });

        var searchTimer;
        $searchInput.on('keyup focus', function() {
            var term = $(this).val();
            clearTimeout(searchTimer);
            searchTimer = setTimeout(function() {
                $.ajax({
                    url: ajaxurl, type: 'POST', data: { action: 'promo_search_products', nonce: nonce, term: term },
                    success: function(response) {
                        $dropdown.empty();
                        if(response.length > 0) {
                            $.each(response, function(i, item) {
                                if(selectedProducts.findIndex(p => p.id == item.id) === -1) {
                                    $dropdown.append('<li data-id="'+item.id+'" data-title="'+item.title+'">'+item.title+'</li>');
                                }
                            });
                        } else {
                            $dropdown.append('<li style="pointer-events:none; color:#aaa;">Brak wyników</li>');
                        }
                        $dropdown.show();
                    }
                });
            }, 300);
        });

        $dropdown.on('click', 'li', function() {
            var id = $(this).data('id');
            var title = $(this).data('title');
            if(id) {
                selectedProducts.push({id: id, title: title});
                renderChips();
                $searchInput.val('');
                $dropdown.hide();
            }
        });

        $(document).on('click', function(e) {
            if (!$(e.target).closest('#promo-select-container').length) { $dropdown.hide(); }
        });

        // LOGIKA DODAWANIA I USUWANIA ZDJĘĆ
        function updatePhotoButton() {
            var visibleCount = $('.photo-group:visible').length;
            if (visibleCount >= 5) {
                $('#btn-add-photo').hide();
            } else {
                $('#btn-add-photo').show();
            }
        }
        updatePhotoButton();

        $('#btn-add-photo').on('click', function(e) {
            e.preventDefault();
            var hiddenPhotos = $('.photo-group:hidden');
            if (hiddenPhotos.length > 0) {
                $(hiddenPhotos[0]).slideDown();
            }
            updatePhotoButton();
        });

        $('.remove-photo').on('click', function(e) {
            e.preventDefault();
            var target = $(this).data('target');
            $('#photo-group-' + target).slideUp(function() {
                // Czyszczenie obu pól w danym rzędzie
                $(this).find('input[type="hidden"]').val('');
                $(this).find('.promo-preview-box').html('<span class="dashicons dashicons-format-image"></span>');
                $(this).find('.btn-remove').hide(); 
                updatePhotoButton();
            });
        });

        // LOGIKA DODAWANIA I USUWANIA BADGE'ÓW
        function updateBadgeButton() {
            var visibleCount = $('.badge-group:visible').length;
            if (visibleCount >= 3) {
                $('#btn-add-badge').hide();
            } else {
                $('#btn-add-badge').show();
            }
        }
        updateBadgeButton();

        $('#btn-add-badge').on('click', function(e) {
            e.preventDefault();
            var hiddenBadges = $('.badge-group:hidden');
            if (hiddenBadges.length > 0) {
                $(hiddenBadges[0]).slideDown();
            }
            updateBadgeButton();
        });

        $('.remove-badge').on('click', function(e) {
            e.preventDefault();
            var target = $(this).data('target');
            $('#badge-group-' + target).slideUp(function() {
                $(this).find('input[type="text"]').val('');
                $(this).find('textarea').val('');
                $(this).find('input[type="radio"][value="svg"]').prop('checked', true).trigger('change');
                $(this).find('.btn-remove').trigger('click'); 
                updateBadgeButton();
            });
        });

        // PRZEŁĄCZANIE SVG / OBRAZEK W BADGE'U
        $('.icon-type-toggle').on('change', function() {
            var target = $(this).data('target');
            var val = $(this).val();
            if (val === 'svg') {
                $('.icon-svg-wrapper-' + target).show();
                $('.icon-img-wrapper-' + target).hide();
            } else {
                $('.icon-svg-wrapper-' + target).hide();
                $('.icon-img-wrapper-' + target).show();
            }
        });

        $('.fse-svg-toggle').on('change', function() {
            if($(this).is(':checked')) {
                $('.fse-svg-wrapper').slideDown();
                $('.fse-text-wrapper').slideUp();
            } else {
                $('.fse-svg-wrapper').slideUp();
                $('.fse-text-wrapper').slideDown();
            }
        });
        
        $('.mm-svg-toggle').on('change', function() {
            var target = $(this).data('target');
            if($(this).is(':checked')) {
                $('.mm-svg-wrapper-' + target).slideDown();
                $('.mm-text-wrapper-' + target).slideUp();
            } else {
                $('.mm-svg-wrapper-' + target).slideUp();
                $('.mm-text-wrapper-' + target).slideDown();
            }
        });

        // --- DODANO: LOGIKA DODAWANIA I USUWANIA ZESTAWÓW ---
        function updateSetButton() {
            var visibleCount = $('.set-group:visible').length;
            if (visibleCount >= 3) {
                $('#btn-add-set').hide();
            } else {
                $('#btn-add-set').show();
            }
        }
        updateSetButton();

        $('#btn-add-set').on('click', function(e) {
            e.preventDefault();
            var hiddenSets = $('.set-group:hidden');
            if (hiddenSets.length > 0) {
                $(hiddenSets[0]).slideDown();
            }
            updateSetButton();
        });

        // --- DODANO: LOGIKA DODAWANIA REGUŁ ZNIŻKOWYCH ---
        function updateDiscountButton() {
            var visibleCount = $('.discount-group:visible').length;
            if (visibleCount >= 5) {
                $('#btn-add-discount').hide();
            } else {
                $('#btn-add-discount').show();
            }
        }
        updateDiscountButton();

        $('#btn-add-discount').on('click', function(e) {
            e.preventDefault();
            var hiddenDiscounts = $('.discount-group:hidden');
            if (hiddenDiscounts.length > 0) {
                $(hiddenDiscounts[0]).slideDown();
            }
            updateDiscountButton();
        });

        $('.remove-discount').on('click', function(e) {
            e.preventDefault();
            var target = $(this).data('target');
            if(confirm('Na pewno wyczyścić i ukryć tę regułę zniżkową?')) {
                $('#discount-group-' + target).slideUp(function() {
                    $(this).find('input[type="text"]').val('');
                    $(this).find('select').val(null).trigger('change');
                    updateDiscountButton();
                });
            }
        });

        $('.remove-set').on('click', function(e) {
            e.preventDefault();
            var target = $(this).data('target');
            if(confirm('Na pewno wyczyścić i ukryć ten zestaw?')) {
                $('#set-group-' + target).slideUp(function() {
                    $(this).find('input[type="text"]').val('');
                    $(this).find('input[type="hidden"]').val('');
                    $(this).find('textarea').val('');
                    $(this).find('select').val(null).trigger('change');
                    $(this).find('input[type="checkbox"]').prop('checked', false);
                    $(this).find('.promo-preview-box').html('<span class="dashicons dashicons-format-image"></span>');
                    $(this).find('.btn-remove').hide(); 
                updateSetButton();
                });
            }
        });

        // --- TŁUMACZENIA AI ---
        function updateAiExport() {
            var trans = {
                _ai_prompt: "Jesteś ekspertem e-commerce. Przetłumacz poniższe teksty marketingowe sklepu na język podany w mojej wiadomości (lub zgadnij, jeśli jest to oczywiste). Mają brzmieć naturalnie, sprzedażowo i zachęcająco dla klienta w danym języku. Zwróć WYŁĄCZNIE czysty kod JSON, gotowy do skopiowania. Nie zmieniaj nazw kluczy. Zachowaj ewentualne formatowanie HTML (jak <br> lub <strong>) i znaki nowej linii (\\n).",
                pct: $('input[name="promo_percentage_text"]').val() || '',
                txt: $('input[name="promo_small_text"]').val() || '',
                cup: $('input[name="promo_coupon_code"]').val() || '',
                fl:  $('#val_f_lbl').val() || '',
                b1t: $('input[name="promo_badge_text_1"]').val() || '',
                b2t: $('input[name="promo_badge_text_2"]').val() || '',
                b3t: $('input[name="promo_badge_text_3"]').val() || '',
                s1t: $('input[name="promo_set_title_1"]').val() || '',
                s2t: $('input[name="promo_set_title_2"]').val() || '',
                s3t: $('input[name="promo_set_title_3"]').val() || '',
                s1btn: $('input[name="promo_set_btn_label_1"]').val() || '',
                s2btn: $('input[name="promo_set_btn_label_2"]').val() || '',
                s3btn: $('input[name="promo_set_btn_label_3"]').val() || '',
                s1hl: $('input[name="promo_set_header_label_1"]').val() || '',
                s2hl: $('input[name="promo_set_header_label_2"]').val() || '',
                s3hl: $('input[name="promo_set_header_label_3"]').val() || '',
                s1it: $('textarea[name="promo_set_items_1"]').val() || '',
                s2it: $('textarea[name="promo_set_items_2"]').val() || '',
                s3it: $('textarea[name="promo_set_items_3"]').val() || '',
                batctxt: $('input[name="promo_banner_atc_text"]').val() || '',
                hbt: $('input[name="promo_header_btn_text"]').val() || '',
                mbs: $('input[name="promo_mm_banner_subtitle"]').val() || '',
                mbt: $('input[name="promo_mm_banner_title"]').val() || '',
                mbbt: $('input[name="promo_mm_banner_btn_text"]').val() || '',
                fsesub: $('input[name="promo_fse_subtitle"]').val() || '',
                fsetitle: $('input[name="promo_fse_title"]').val() || '',
                fsebody: $('textarea[name="promo_fse_body_text"]').val() || '',
                fsebtn: $('input[name="promo_fse_button_text"]').val() || ''
            };
            for (var key in trans) {
                if (trans[key] === '' && key !== '_ai_prompt') delete trans[key];
            }
            $('#ai_export_json').val(JSON.stringify(trans, null, 2));
        }

        $('#ai_export_json').on('mouseenter focus', function(){
            updateAiExport();
        });
        
        $('#btn-import-ai').click(function(e) {
            e.preventDefault();
            var val = $('#ai_import_json').val();
            if (!val) return alert('Pole importu jest puste!');
            
            try {
                val = val.replace(/```json/gi, '').replace(/```/gi, '').trim();
                var c = JSON.parse(val);
                
                if(c.pct !== undefined) $('input[name="promo_percentage_text"]').val(c.pct).trigger('input');
                if(c.txt !== undefined) $('input[name="promo_small_text"]').val(c.txt).trigger('input');
                if(c.cup !== undefined) $('input[name="promo_coupon_code"]').val(c.cup).trigger('input');
                if(c.fl !== undefined) $('#val_f_lbl').val(c.fl).trigger('input');
                if(c.b1t !== undefined) $('input[name="promo_badge_text_1"]').val(c.b1t).trigger('input');
                if(c.b2t !== undefined) $('input[name="promo_badge_text_2"]').val(c.b2t).trigger('input');
                if(c.b3t !== undefined) $('input[name="promo_badge_text_3"]').val(c.b3t).trigger('input');
                if(c.s1t !== undefined) $('input[name="promo_set_title_1"]').val(c.s1t).trigger('input');
                if(c.s2t !== undefined) $('input[name="promo_set_title_2"]').val(c.s2t).trigger('input');
                if(c.s3t !== undefined) $('input[name="promo_set_title_3"]').val(c.s3t).trigger('input');
                if(c.s1btn !== undefined) $('input[name="promo_set_btn_label_1"]').val(c.s1btn).trigger('input');
                if(c.s2btn !== undefined) $('input[name="promo_set_btn_label_2"]').val(c.s2btn).trigger('input');
                if(c.s3btn !== undefined) $('input[name="promo_set_btn_label_3"]').val(c.s3btn).trigger('input');
                if(c.s1hl !== undefined) $('input[name="promo_set_header_label_1"]').val(c.s1hl).trigger('input');
                if(c.s2hl !== undefined) $('input[name="promo_set_header_label_2"]').val(c.s2hl).trigger('input');
                if(c.s3hl !== undefined) $('input[name="promo_set_header_label_3"]').val(c.s3hl).trigger('input');
                if(c.s1it !== undefined) $('textarea[name="promo_set_items_1"]').val(c.s1it).trigger('input');
                if(c.s2it !== undefined) $('textarea[name="promo_set_items_2"]').val(c.s2it).trigger('input');
                if(c.s3it !== undefined) $('textarea[name="promo_set_items_3"]').val(c.s3it).trigger('input');
                if(c.batctxt !== undefined) $('input[name="promo_banner_atc_text"]').val(c.batctxt).trigger('input');
                if(c.hbt !== undefined) $('input[name="promo_header_btn_text"]').val(c.hbt).trigger('input');
                if(c.mbs !== undefined) $('input[name="promo_mm_banner_subtitle"]').val(c.mbs).trigger('input');
                if(c.mbt !== undefined) $('input[name="promo_mm_banner_title"]').val(c.mbt).trigger('input');
                if(c.mbbt !== undefined) $('input[name="promo_mm_banner_btn_text"]').val(c.mbbt).trigger('input');
                if(c.fsesub !== undefined) $('input[name="promo_fse_subtitle"]').val(c.fsesub).trigger('input');
                if(c.fsetitle !== undefined) $('input[name="promo_fse_title"]').val(c.fsetitle).trigger('input');
                if(c.fsebody !== undefined) $('textarea[name="promo_fse_body_text"]').val(c.fsebody).trigger('input');
                if(c.fsebtn !== undefined) $('input[name="promo_fse_button_text"]').val(c.fsebtn).trigger('input');
                
                alert('Tłumaczenie zaimportowane! Możesz teraz zapisać promocję.');
                $('#ai_import_json').val('');
                updateAiExport();

                // Uruchomienie asynchronicznego pobierania po wklejeniu configu
                setTimeout(processAsyncSideload, 1000);
            } catch(err) {
                alert('Błąd importu! Upewnij się, że wklejasz tylko poprawny kod JSON.');
            }
        });
        
        updateAiExport();

        // --- ASYNCHRONICZNY SIDELOADING W TLE ---
        function processAsyncSideload() {
            var postId = $('#post_ID').val();
            if (!postId) return;

            var urlFields = [
                'promo_fse_banner_image', 'promo_fse_banner_image_mobile', 'promo_mm_banner_image',
                'promo_photo_1', 'promo_photo_mob_1', 'promo_photo_2', 'promo_photo_mob_2',
                'promo_photo_3', 'promo_photo_mob_3', 'promo_photo_4', 'promo_photo_mob_4',
                'promo_photo_5', 'promo_photo_mob_5'
            ];

            var queue = [];
            var homeUrl = window.location.origin;

            for (var i = 0; i < urlFields.length; i++) {
                var field = urlFields[i];
                var val = $('input[name="' + field + '"]').val();
                if (val && val.indexOf(homeUrl) === -1 && val.indexOf('http') === 0) {
                    queue.push({ field: field, url: val });
                }
            }

            if (queue.length === 0) return;

            if ($('#sideload-indicator').length === 0) {
                $('body').append('<div id="sideload-indicator" style="position:fixed; bottom:20px; right:20px; background:#2271b1; color:#fff; padding:10px 20px; border-radius:4px; z-index:99999; font-weight:bold; box-shadow:0 2px 10px rgba(0,0,0,0.2);"></div>');
            }

            var total = queue.length;
            var current = 0;

            function processNext() {
                if (queue.length === 0) {
                    $('#sideload-indicator').html('<span class="dashicons dashicons-yes-alt"></span> Pobieranie obrazów zakończone!').delay(3000).fadeOut(function(){ $(this).remove(); });
                    return;
                }

                current++;
                var item = queue.shift();
                $('#sideload-indicator').show().text('Pobieranie mediów... ' + current + ' z ' + total);

                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'blendygo_async_sideload',
                        image_url: item.url,
                        post_id: postId,
                        field_name: item.field,
                        _ajax_nonce: '<?php echo wp_create_nonce("blendygo_sideload_nonce"); ?>'
                    },
                    success: function(response) {
                        if (response.success && response.data.url) {
                            $('input[name="' + item.field + '"]').val(response.data.url);
                            var boxId = '#box-' + item.field;
                            if ($(boxId).length) {
                                $(boxId).html('<img src="' + response.data.url + '">');
                            }
                        }
                        processNext();
                    },
                    error: function() {
                        processNext();
                    }
                });
            }

            processNext();
        }

    });
    </script>
    <?php
}

function render_color_selector($label, $id, $meta, $palette, $default) {
    $current = isset($meta[$id]) && !empty($meta[$id][0]) ? $meta[$id][0] : $default;
    echo '<div class="promo-field-group">';
    echo '<label class="main-label">'.$label.'</label>';
    echo '<div class="promo-swatches">';
    foreach($palette as $color) {
        $selected = ($current === $color) ? 'is-selected' : '';
        echo '<div class="promo-swatch '.$selected.'" style="background: '.$color.';" data-color="'.$color.'" title="'.$color.'"></div>';
    }
    $custom_selected = (!in_array($current, $palette)) ? 'is-selected' : '';
    echo '<div class="promo-swatch custom-trigger '.$custom_selected.'" title="Własny CSS">+</div>';
    echo '</div>';
    echo '<div class="custom-css-input '.( !in_array($current, $palette) ? 'is-active' : '' ).'">';
    echo '<input type="text" name="'.$id.'" class="promo-input-text promo-color-target" value="'.esc_attr($current).'" placeholder="Wpisz np. #FF0000">';
    echo '</div>';
    echo '</div>';
}

function render_media_slot($label, $id, $meta) {
    $url = isset($meta[$id]) ? $meta[$id][0] : '';
    echo '<div class="promo-field-group">
            <label class="main-label">'.$label.'</label>
            <div class="promo-media-card">
                <input type="hidden" name="'.$id.'" id="input-'.$id.'" value="'.esc_url($url).'">
                <div class="promo-preview-box" id="box-'.$id.'">';
                if ($url) { echo '<img src="'.esc_url($url).'">'; } 
                else { echo '<span class="dashicons dashicons-format-image"></span>'; }
    echo '      </div>
                <div class="promo-actions">
                    <button type="button" class="button btn-select" data-id="'.$id.'">Wybierz</button>
                    <a href="#" class="btn-remove" data-id="'.$id.'" style="'.($url ? '' : 'display:none;').'">Usuń</a>
                </div>
            </div>
          </div>';
}

/**
 * 7. ZAPISYWANIE DANYCH
 */
function save_promocje_meta_data($post_id) {
    if (!isset($_POST['promo_nonce']) || !wp_verify_nonce($_POST['promo_nonce'], 'promo_save_meta')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    if (isset($_POST['promo_categories']) && is_array($_POST['promo_categories'])) {
        $sanitized_cats = array_map('sanitize_text_field', $_POST['promo_categories']);
        update_post_meta($post_id, 'promo_categories', implode(',', $sanitized_cats));
    } else {
        delete_post_meta($post_id, 'promo_categories');
    }

    if (isset($_POST['promo_global'])) {
        update_post_meta($post_id, 'promo_global', 'yes');
    } else {
        update_post_meta($post_id, 'promo_global', 'no');
    }

    if (isset($_POST['promo_product_ids_hidden'])) {
        $ids_raw = sanitize_text_field($_POST['promo_product_ids_hidden']);
        update_post_meta($post_id, 'promo_product_ids', $ids_raw);
    }

    // ZAPISYWANIE TRYBU TESTOWEGO (CHECKBOX)
    if (isset($_POST['promo_admin_only'])) {
        update_post_meta($post_id, 'promo_admin_only', 'yes');
    } else {
        update_post_meta($post_id, 'promo_admin_only', 'no');
    }

    // ZAPISYWANIE CHECKBOXA DLA SVG W BLOKU FSE
    if (isset($_POST['promo_fse_use_title_svg'])) {
        update_post_meta($post_id, 'promo_fse_use_title_svg', 'yes');
    } else {
        update_post_meta($post_id, 'promo_fse_use_title_svg', 'no');
    }

    // ZAPISYWANIE CHECKBOXA DLA SVG W MENU MOBILE
    if (isset($_POST['promo_mm_banner_use_title_svg'])) {
        update_post_meta($post_id, 'promo_mm_banner_use_title_svg', 'yes');
    } else {
        update_post_meta($post_id, 'promo_mm_banner_use_title_svg', 'no');
    }
    if (isset($_POST['promo_mm_banner_use_subtitle_svg'])) {
        update_post_meta($post_id, 'promo_mm_banner_use_subtitle_svg', 'yes');
    } else {
        update_post_meta($post_id, 'promo_mm_banner_use_subtitle_svg', 'no');
    }
    if (isset($_POST['promo_mm_banner_use_btn_svg'])) {
        update_post_meta($post_id, 'promo_mm_banner_use_btn_svg', 'yes');
    } else {
        update_post_meta($post_id, 'promo_mm_banner_use_btn_svg', 'no');
    }

    $text_fields = [
        'promo_is_active', 'promo_coupon_code', 'promo_priority', 
        'promo_small_text', 'promo_percentage_text', 'promo_badge_bg', 
        'promo_badge_color', 'promo_hide_fse_btn',
        'promo_banner_desk', 'promo_banner_mob',
        'promo_banner_desk_timer', 'promo_banner_mob_timer',
        'promo_banner_shop_desk', 'promo_banner_shop_mob',
        'promo_banner_cart_desk', 'promo_banner_cart_mob',
        'promo_banner_main_desk', 'promo_banner_main_mob',
        'timer_pos_top', 'timer_pos_left', 'timer_pos_top_mob', 'timer_pos_left_mob',
        'timer_font_size_num', 'timer_font_size_label',
        'timer_font_family', 'timer_font_weight_num', 'timer_font_weight_lbl',
        'timer_gap_col', 'timer_gap_row', 'timer_color_num', 'timer_color_lbl',
        'promo_date_start', 'promo_date_end', 'promo_date_ext_start', 'promo_date_final', 
        'promo_date_final_fixed', 'promo_date_remove_ui',
        'promo_photo_1', 'promo_photo_mob_1', 'promo_photo_2', 'promo_photo_mob_2', 
        'promo_photo_3', 'promo_photo_mob_3', 'promo_photo_4', 'promo_photo_mob_4', 
        'promo_photo_5', 'promo_photo_mob_5',
        'promo_badge_text_1', 'promo_badge_icon_type_1', 'promo_badge_image_1', 'promo_badge_bg_color_1', 'promo_badge_bg_image_1', 'promo_badge_text_color_1', 'promo_badge_width_1', 'promo_badge_width_auto_1', 'promo_badge_align_1', 'promo_badge_mt_1', 'promo_badge_mb_1', 'promo_badge_py_1', 'promo_badge_px_1', 'promo_badge_icon_size_val_1', 'promo_badge_icon_size_unit_1',
        'promo_badge_text_2', 'promo_badge_icon_type_2', 'promo_badge_image_2', 'promo_badge_bg_color_2', 'promo_badge_bg_image_2', 'promo_badge_text_color_2', 'promo_badge_width_2', 'promo_badge_width_auto_2', 'promo_badge_align_2', 'promo_badge_mt_2', 'promo_badge_mb_2', 'promo_badge_py_2', 'promo_badge_px_2', 'promo_badge_icon_size_val_2', 'promo_badge_icon_size_unit_2',
        'promo_badge_text_3', 'promo_badge_icon_type_3', 'promo_badge_image_3', 'promo_badge_bg_color_3', 'promo_badge_bg_image_3', 'promo_badge_text_color_3', 'promo_badge_width_3', 'promo_badge_width_auto_3', 'promo_badge_align_3', 'promo_badge_mt_3', 'promo_badge_mb_3', 'promo_badge_py_3', 'promo_badge_px_3', 'promo_badge_icon_size_val_3', 'promo_badge_icon_size_unit_3',
        'promo_fse_banner_image', 'promo_fse_banner_image_mobile',
        'promo_fse_subtitle', 'promo_fse_subtitle_color', 'promo_fse_subtitle_bg', 'promo_fse_subtitle_ff', 'promo_fse_subtitle_fs',
        'promo_fse_title', 'promo_fse_title_color', 'promo_fse_title_ff', 'promo_fse_title_fs', 'promo_fse_title_fs_mobile',
        'promo_fse_title_icon_type', 'promo_fse_title_svg_h_val', 'promo_fse_title_svg_h_unit', 'promo_fse_title_svg_hm_val', 'promo_fse_title_svg_hm_unit', 'promo_fse_title_svg_align',
        'promo_fse_body_text', 'promo_fse_body_color', 'promo_fse_body_ff', 'promo_fse_body_fs',
        'promo_fse_button_text', 'promo_fse_button_link', 'promo_fse_button_bg', 'promo_fse_button_text_color', 'promo_fse_button_ff', 'promo_fse_button_fs',
        'promo_fse_arrow_bg', 'promo_fse_arrow_color',
        'promo_header_btn_enabled', 'promo_header_btn_text', 'promo_header_btn_link',
        'promo_mm_banner_enabled', 'promo_mm_banner_image', 'promo_mm_banner_subtitle', 'promo_mm_banner_subtitle_bg', 'promo_mm_banner_title', 'promo_mm_banner_btn_text', 'promo_mm_banner_btn_link', 
        'promo_mm_banner_title_icon_type', 'promo_mm_banner_title_svg_h_val', 'promo_mm_banner_title_svg_h_unit', 'promo_mm_banner_title_svg_align', 'promo_mm_banner_title_image',
        'promo_mm_banner_subtitle_icon_type', 'promo_mm_banner_subtitle_svg_h_val', 'promo_mm_banner_subtitle_svg_h_unit', 'promo_mm_banner_subtitle_svg_align', 'promo_mm_banner_subtitle_image',
        'promo_mm_banner_btn_icon_type', 'promo_mm_banner_btn_svg_h_val', 'promo_mm_banner_btn_svg_h_unit', 'promo_mm_banner_btn_svg_align', 'promo_mm_banner_btn_image',
        'promo_aurora_product_1', 'promo_aurora_product_2', 'promo_aurora_product_3'
    ];

    $url_fields = [
        'promo_banner_desk', 'promo_banner_mob', 'promo_banner_desk_timer', 'promo_banner_mob_timer',
        'promo_banner_shop_desk', 'promo_banner_shop_mob', 'promo_banner_cart_desk', 'promo_banner_cart_mob',
        'promo_banner_main_desk', 'promo_banner_main_mob', 'promo_photo_1', 'promo_photo_mob_1', 
        'promo_photo_2', 'promo_photo_mob_2', 'promo_photo_3', 'promo_photo_mob_3', 
        'promo_photo_4', 'promo_photo_mob_4', 'promo_photo_5', 'promo_photo_mob_5',
        'promo_badge_image_1', 'promo_badge_image_2', 'promo_badge_image_3',
        'promo_fse_banner_image', 'promo_fse_banner_image_mobile', 'promo_fse_title_image', 'promo_mm_banner_image', 'promo_mm_banner_title_image', 'promo_mm_banner_subtitle_image', 'promo_mm_banner_btn_image',
        'promo_banner_atc_desk', 'promo_banner_atc_reg_url', 'promo_fse_button_link',
        'promo_header_btn_link', 'promo_mm_banner_btn_link'
    ];

    foreach ($text_fields as $field) {
        if (isset($_POST[$field])) {
            if (in_array($field, $url_fields)) {
                $value = esc_url_raw($_POST[$field]);
            } elseif (strpos($field, 'color') !== false || strpos($field, 'bg') !== false) {
                $value = sanitize_text_field(stripslashes($_POST[$field]));
            } else {
                $value = sanitize_text_field($_POST[$field]);
            }
            update_post_meta($post_id, $field, $value);
        }
    }

    $svg_fields = ['promo_badge_svg_1', 'promo_badge_svg_2', 'promo_badge_svg_3', 'promo_fse_title_svg', 'promo_mm_banner_title_svg', 'promo_mm_banner_subtitle_svg', 'promo_mm_banner_btn_svg'];
    $allowed_tags = array(
        'svg' => array('xmlns' => true, 'width' => true, 'height' => true, 'viewBox' => true, 'viewbox' => true, 'fill' => true, 'class' => true, 'style' => true, 'preserveaspectratio' => true),
        'path' => array('d' => true, 'fill' => true, 'stroke' => true, 'stroke-width' => true, 'class' => true, 'fill-rule' => true, 'clip-rule' => true, 'opacity' => true, 'transform' => true, 'stroke-linecap' => true, 'stroke-linejoin' => true),
        'g' => array('fill' => true, 'class' => true, 'filter' => true, 'transform' => true, 'opacity' => true, 'clip-path' => true, 'mask' => true, 'style' => true),
        'circle' => array('cx' => true, 'cy' => true, 'r' => true, 'fill' => true, 'class' => true, 'stroke' => true, 'opacity' => true),
        'rect' => array('x' => true, 'y' => true, 'width' => true, 'height' => true, 'rx' => true, 'ry' => true, 'fill' => true, 'stroke' => true, 'transform' => true, 'opacity' => true),
        'ellipse' => array('cx' => true, 'cy' => true, 'rx' => true, 'ry' => true, 'fill' => true, 'stroke' => true),
        'line' => array('x1' => true, 'y1' => true, 'x2' => true, 'y2' => true, 'stroke' => true, 'stroke-width' => true),
        'polygon' => array('points' => true, 'fill' => true, 'stroke' => true),
        'polyline' => array('points' => true, 'fill' => true, 'stroke' => true),
        'defs' => array(),
        'lineargradient' => array('id' => true, 'x1' => true, 'y1' => true, 'x2' => true, 'y2' => true, 'gradientunits' => true, 'gradienttransform' => true),
        'radialgradient' => array('id' => true, 'cx' => true, 'cy' => true, 'r' => true, 'fx' => true, 'fy' => true, 'gradientunits' => true),
        'stop' => array('offset' => true, 'stop-color' => true, 'stop-opacity' => true),
        'filter' => array('id' => true, 'x' => true, 'y' => true, 'width' => true, 'height' => true, 'filterunits' => true, 'color-interpolation-filters' => true),
        'feflood' => array('flood-opacity' => true, 'flood-color' => true, 'result' => true),
        'fecolormatrix' => array('in' => true, 'type' => true, 'values' => true, 'result' => true),
        'feoffset' => array('dx' => true, 'dy' => true, 'in' => true, 'result' => true),
        'fegaussianblur' => array('stddeviation' => true, 'in' => true, 'result' => true),
        'fecomposite' => array('in' => true, 'in2' => true, 'operator' => true, 'result' => true, 'k1' => true, 'k2' => true, 'k3' => true, 'k4' => true),
        'feblend' => array('mode' => true, 'in' => true, 'in2' => true, 'result' => true),
        'femerge' => array('result' => true),
        'femergenode' => array('in' => true),
        'clippath' => array('id' => true, 'clippathunits' => true),
        'mask' => array('id' => true, 'maskunits' => true, 'maskcontentunits' => true, 'x' => true, 'y' => true, 'width' => true, 'height' => true),
        'use' => array('href' => true, 'xlink:href' => true, 'x' => true, 'y' => true, 'width' => true, 'height' => true, 'transform' => true)
    );

    foreach ($svg_fields as $svg_field) {
        if (isset($_POST[$svg_field])) {
            $svg_clean = wp_kses(stripslashes($_POST[$svg_field]), $allowed_tags);
            update_post_meta($post_id, $svg_field, $svg_clean);
        }
    }

    for ($i = 1; $i <= 3; $i++) {
        $set_fields = ['promo_set_title_'.$i, 'promo_set_btn_label_'.$i, 'promo_set_header_label_'.$i, 'promo_set_badge_type_'.$i, 'promo_set_badge_custom_'.$i, 'promo_set_price_regular_'.$i, 'promo_set_price_promo_'.$i, 'promo_set_items_'.$i];
        foreach($set_fields as $sf) {
            if (isset($_POST[$sf])) {
                update_post_meta($post_id, $sf, sanitize_textarea_field($_POST[$sf]));
            }
        }
        
        // Zabezpieczenie checkboxów
        if (isset($_POST['promo_set_all_products_'.$i]) && $_POST['promo_set_all_products_'.$i] === 'yes') {
            update_post_meta($post_id, 'promo_set_all_products_'.$i, 'yes');
        } else {
            update_post_meta($post_id, 'promo_set_all_products_'.$i, 'no');
        }

        if (isset($_POST['promo_set_target_'.$i])) {
            update_post_meta($post_id, 'promo_set_target_'.$i, intval($_POST['promo_set_target_'.$i]));
        }
        if (isset($_POST['promo_set_image_'.$i])) {
            update_post_meta($post_id, 'promo_set_image_'.$i, esc_url_raw($_POST['promo_set_image_'.$i]));
        }
        if (isset($_POST['promo_set_where_prods_'.$i]) && is_array($_POST['promo_set_where_prods_'.$i])) {
            $prods_clean = array_map('intval', $_POST['promo_set_where_prods_'.$i]);
            update_post_meta($post_id, 'promo_set_where_prods_'.$i, implode(',', $prods_clean));
        } else {
            update_post_meta($post_id, 'promo_set_where_prods_'.$i, '');
        }
        if (isset($_POST['promo_set_where_cats_'.$i]) && is_array($_POST['promo_set_where_cats_'.$i])) {
            $cats_clean = array_map('intval', $_POST['promo_set_where_cats_'.$i]);
            update_post_meta($post_id, 'promo_set_where_cats_'.$i, implode(',', $cats_clean));
        } else {
            update_post_meta($post_id, 'promo_set_where_cats_'.$i, '');
        }
    }

    if (isset($_POST['promo_banner_atc_desk'])) {
        update_post_meta($post_id, 'promo_banner_atc_desk', esc_url_raw($_POST['promo_banner_atc_desk']));
    }
    if (isset($_POST['promo_banner_atc_reg_url'])) {
        update_post_meta($post_id, 'promo_banner_atc_reg_url', esc_url_raw($_POST['promo_banner_atc_reg_url']));
    }
    if (isset($_POST['promo_banner_atc_text'])) {
        update_post_meta($post_id, 'promo_banner_atc_text', sanitize_text_field($_POST['promo_banner_atc_text']));
    }
    
    // ZAPIS DROPOW WARIANTOW
    if (isset($_POST['promo_drop_variations']) && is_array($_POST['promo_drop_variations'])) {
        $drop_clean = array_map('intval', $_POST['promo_drop_variations']);
        update_post_meta($post_id, 'promo_drop_variations', implode(',', $drop_clean));
    } else {
        update_post_meta($post_id, 'promo_drop_variations', '');
    }

    // ZAPIS REGUL ZNIZKOWYCH (1-5)
    for ($d = 1; $d <= 5; $d++) {
        if (isset($_POST['promo_discount_type_'.$d])) {
            update_post_meta($post_id, 'promo_discount_type_'.$d, sanitize_text_field($_POST['promo_discount_type_'.$d]));
        }
        if (isset($_POST['promo_discount_value_'.$d])) {
            update_post_meta($post_id, 'promo_discount_value_'.$d, sanitize_text_field($_POST['promo_discount_value_'.$d]));
        }
        if (isset($_POST['promo_discount_scope_'.$d])) {
            update_post_meta($post_id, 'promo_discount_scope_'.$d, sanitize_text_field($_POST['promo_discount_scope_'.$d]));
        }
        if (isset($_POST['promo_discount_targets_'.$d]) && is_array($_POST['promo_discount_targets_'.$d])) {
            $tgt_clean = array_map('intval', $_POST['promo_discount_targets_'.$d]);
            update_post_meta($post_id, 'promo_discount_targets_'.$d, implode(',', $tgt_clean));
        } else {
            update_post_meta($post_id, 'promo_discount_targets_'.$d, '');
        }
        if (isset($_POST['promo_discount_categories_'.$d]) && is_array($_POST['promo_discount_categories_'.$d])) {
            $cat_clean = array_map('intval', $_POST['promo_discount_categories_'.$d]);
            update_post_meta($post_id, 'promo_discount_categories_'.$d, implode(',', $cat_clean));
        } else {
            update_post_meta($post_id, 'promo_discount_categories_'.$d, '');
        }
    }
}
add_action('save_post', 'save_promocje_meta_data');
/**
 * 1. ODBLOKOWANIE WYGRYWANIA PLIKÓW SVG DO BIBLIOTEKI MEDIÓW
 */
function blendygo_allow_svg_upload( $mimes ) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter( 'upload_mimes', 'blendygo_allow_svg_upload' );

/**
 * 2. NAPRAWA BŁĘDU "PLIK NIE MOŻE BYĆ PRZETWORZONY PRZEZ SERWER"
 * WYŁĄCZA SPRAWDZANIE ROZMIARÓW DLA SVG, KTÓREGO WP NIE POTRAFI SAMODZIELNIE "PRZECZYTAĆ"
 */
add_filter( 'wp_check_filetype_and_ext', function($data, $file, $filename, $mimes) {
    $ext = pathinfo( $filename, PATHINFO_EXTENSION );
    if ( $ext === 'svg' ) {
        $data['type'] = 'image/svg+xml';
        $data['ext'] = 'svg';
    }
    return $data;
}, 10, 4 );

/**
 * 3. WYŁĄCZENIE GENEROWANIA MINIATUR DLA SVG (ZAPOBIEGA BŁĘDOM GD/IMAGICK)
 */
add_filter( 'wp_prepare_attachment_for_js', function( $response, $attachment, $meta ) {
    if ( $response['mime'] === 'image/svg+xml' && empty( $response['sizes'] ) ) {
        $response['sizes'] = [
            'full' => [
                'url' => $response['url'],
                'width' => 100,
                'height' => 100,
                'orientation' => 'landscape',
            ],
        ];
    }
    return $response;
}, 10, 3 );

/**
 * =========================================================================
 * MODUŁ: ZARZĄDZANIE REGULAMINAMI (PODMENU W CPT PROMOCJE)
 * =========================================================================
 */

// 1. REJESTRACJA ZAKŁADKI W MENU
function blendygo_promo_add_regulaminy_submenu() {
    add_submenu_page(
        'edit.php?post_type=promocje',     // Rodzic (zakładka Promocje)
        'Zarządzanie Regulaminami',        // Tytuł strony
        'Regulaminy',                      // Tytuł w menu
        'manage_options',                  // Uprawnienia
        'blendygo-regulaminy',             // Slug
        'blendygo_regulaminy_page_render'  // Funkcja widoku
    );
}
add_action('admin_menu', 'blendygo_promo_add_regulaminy_submenu');

// 2. RENDEROWANIE WIDOKU I LOGIKA ZAPISU
function blendygo_regulaminy_page_render() {
    // A. ZAPIS DANYCH (I OBSŁUGA DODAWANIA NOWEGO SZABLONU)
    if (isset($_POST['blendygo_save_regulaminy']) && check_admin_referer('blendygo_reg_nonce_action', 'blendygo_reg_nonce_field')) {
        $reg_data = [
            'active_promo_id'     => isset($_POST['active_promo_id']) ? intval($_POST['active_promo_id']) : 0,
            'active_template_idx' => isset($_POST['active_template_idx']) ? intval($_POST['active_template_idx']) : 0,
            'templates'           => []
        ];

        // Zbieranie przesłanych szablonów (usunięte w JS nie zostaną przesłane)
        if (isset($_POST['tpl_name']) && is_array($_POST['tpl_name'])) {
            foreach ($_POST['tpl_name'] as $i => $name) {
                $content = isset($_POST['tpl_content'][$i]) ? wp_kses_post(wp_unslash($_POST['tpl_content'][$i])) : '';
                $reg_data['templates'][] = [
                    'name'    => sanitize_text_field($name),
                    'content' => $content
                ];
            }
        }

        // Jeśli kliknięto "Dodaj nowy szablon" (przeładowuje stronę by bezpiecznie wyrenderować nowy wp_editor)
        if (isset($_POST['blendygo_add_new_template']) && $_POST['blendygo_add_new_template'] === '1') {
            $reg_data['templates'][] = [
                'name'    => 'Nowy Szablon',
                'content' => ''
            ];
        }

        update_option('blendygo_regulaminy_global_data', $reg_data);
        echo '<div class="notice notice-success is-dismissible"><p>Ustawienia regulaminów zostały zapisane.</p></div>';
    }

    // B. POBRANIE DANYCH Z BAZY
    $data = get_option('blendygo_regulaminy_global_data', [
        'active_promo_id'     => 0,
        'active_template_idx' => 0,
        'templates'           => [['name' => 'Domyślny Regulamin', 'content' => '']]
    ]);

    // Pobranie listy TYLKO aktywnych promocji
    $promos = get_posts([
        'post_type'      => 'promocje',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'meta_query'     => [
            'relation' => 'OR',
            [
                'key'     => 'promo_is_active',
                'value'   => 'yes',
                'compare' => '='
            ],
            [
                'key'     => 'promo_is_active',
                'compare' => 'NOT EXISTS' // Fallback dla starszych wpisów bez tego klucza
            ]
        ]
    ]);
    ?>
    <style>
        .blendygo-reg-container { max-width: 950px; margin-top: 15px; }
        .blendygo-reg-container .postbox { box-sizing: border-box; width: 100%; margin-bottom: 20px; }
        .reg-editor-wrap { width: 100%; box-sizing: border-box; padding: 20px; }
        .reg-editor-wrap .wp-editor-wrap { width: 100%; }
    </style>

    <div class="wrap">
        <h1 style="margin-bottom: 20px;">Centrum Regulaminów Promocyjnych</h1>
        
        <form method="post" action="" id="regulaminy-form" class="blendygo-reg-container">
            <?php wp_nonce_field('blendygo_reg_nonce_action', 'blendygo_reg_nonce_field'); ?>
            <input type="hidden" name="blendygo_add_new_template" id="add_new_flag" value="0">

            <div class="postbox" style="padding: 20px; border-left: 4px solid #2271b1;">
                <h2 style="margin-top:0; padding:0;">1. Źródło danych (Aktywna Promocja)</h2>
                <p style="color: #666; font-size: 13px;">Wybierz promocję, z której system pobierze kod rabatowy, daty startu/zakończenia i wartość zniżki do aktywnego regulaminu. Na liście widoczne są tylko promocje o statusie "Aktywna".</p>
                <select name="active_promo_id" style="min-width: 350px; font-size: 14px; padding: 5px;">
                    <option value="0">-- Brak podpiętej promocji --</option>
                    <?php foreach ($promos as $p) : ?>
                        <option value="<?php echo $p->ID; ?>" <?php selected($data['active_promo_id'], $p->ID); ?>>
                            <?php echo esc_html($p->post_title); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="postbox" style="padding: 20px; display: flex; justify-content: space-between;">
                <div style="flex: 1;">
                    <h3 style="margin:0 0 10px 0;">Legenda Tagów</h3>
                    <p style="margin-bottom: 15px; color: #555; font-size: 13px;">Kliknij w tag, aby skopiować go do schowka, a następnie wklej w edytorze poniżej.</p>
                    <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                        <code style="cursor:pointer; padding:5px 10px; background:#f0f6fc; color:#2271b1; border-radius:3px;" onclick="navigator.clipboard.writeText('{NAZWA}')" title="Kopiuj">{NAZWA}</code>
                        <code style="cursor:pointer; padding:5px 10px; background:#f0f6fc; color:#2271b1; border-radius:3px;" onclick="navigator.clipboard.writeText('{START}')" title="Kopiuj">{START}</code>
                        <code style="cursor:pointer; padding:5px 10px; background:#f0f6fc; color:#2271b1; border-radius:3px;" onclick="navigator.clipboard.writeText('{KONIEC}')" title="Kopiuj">{KONIEC}</code>
                        <code style="cursor:pointer; padding:5px 10px; background:#f0f6fc; color:#2271b1; border-radius:3px;" onclick="navigator.clipboard.writeText('{KOD}')" title="Kopiuj">{KOD}</code>
                        <code style="cursor:pointer; padding:5px 10px; background:#f0f6fc; color:#2271b1; border-radius:3px;" onclick="navigator.clipboard.writeText('{WARTOSC}')" title="Kopiuj">{WARTOSC}</code>
                    </div>
                </div>
                <div style="border-left: 1px solid #ddd; padding-left: 20px; width: 250px;">
                    <h3 style="margin:0 0 10px 0;">Shortcode</h3>
                    <p style="color: #555; font-size: 13px;">Wklej na stronę, by wyświetlić wskazany poniżej szablon.</p>
                    <code style="display:block; text-align:center; padding:8px; background:#fcf0f1; color:#d63638; font-weight:bold; border-radius:3px;">[blendygo_regulamin]</code>
                </div>
            </div>

            <h2 style="margin-bottom: 15px;">2. Biblioteka Szablonów</h2>
            <div id="regulaminy-lista">
                <?php foreach ($data['templates'] as $idx => $tpl) : ?>
                    <div class="postbox reg-item" style="border-radius: 4px; overflow: hidden;">
                        <div style="background: #f6f7f7; padding: 15px 20px; border-bottom: 1px solid #ccd0d4; display: flex; justify-content: space-between; align-items: center;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <label style="cursor: pointer; display: flex; align-items: center; gap: 5px;">
                                    <input type="radio" name="active_template_idx" value="<?php echo $idx; ?>" <?php checked($data['active_template_idx'], $idx); ?> class="reg-radio">
                                    <strong class="reg-radio-label" style="color: <?php echo ($data['active_template_idx'] == $idx) ? '#2271b1' : '#50575e'; ?>;">AKTYWNY SZABLON</strong>
                                </label>
                            </div>
                            <div style="display: flex; align-items: center; gap: 15px;">
                                <input type="text" name="tpl_name[]" value="<?php echo esc_attr($tpl['name']); ?>" placeholder="Nazwa robocza..." style="width: 250px; font-weight: bold; padding: 4px 8px;">
                                <button type="button" class="button toggle-reg-editor">Pokaż/Ukryj Edytor</button>
                                <button type="button" class="button remove-reg-item" style="color: #d63638; border-color: #d63638;">Usuń szablon</button>
                            </div>
                        </div>
                        <div class="reg-editor-wrap" style="display: <?php echo ($data['active_template_idx'] == $idx) ? 'block' : 'none'; ?>;">
                            <?php 
                                wp_editor($tpl['content'], 'reg_content_' . $idx, array(
                                    'textarea_name' => 'tpl_content[]',
                                    'textarea_rows' => 15,
                                    'media_buttons' => false
                                )); 
                            ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px; margin-bottom: 40px;">
                <button type="button" class="button button-secondary" id="btn-add-template">
                    <span class="dashicons dashicons-plus" style="margin-top: 4px;"></span> Dodaj nowy szablon
                </button>
                <input type="submit" name="blendygo_save_regulaminy" class="button button-primary button-hero" value="Zapisz zmiany w regulaminach">
            </div>
        </form>
    </div>

    <script>
    jQuery(document).ready(function($){
        // Dynamiczna wizualizacja wybranego radio buttona
        $('.reg-radio').on('change', function() {
            $('.reg-radio-label').css('color', '#50575e');
            $(this).siblings('.reg-radio-label').css('color', '#2271b1');
        });

        // Pokaż / Ukryj edytor wp_editor
        $('.toggle-reg-editor').on('click', function(e){
            e.preventDefault();
            $(this).closest('.reg-item').find('.reg-editor-wrap').slideToggle(200);
        });

        // Usuwanie szablonu (usunięcie DOM przed wysłaniem formularza)
        $('.remove-reg-item').on('click', function(e){
            e.preventDefault();
            if(confirm('Na pewno usunąć ten szablon z biblioteki? Zmiana zajdzie po kliknięciu "Zapisz zmiany".')){
                $(this).closest('.reg-item').slideUp(300, function() {
                    $(this).remove();
                    // Zabezpieczenie indeksów radio po usunięciu
                    $('.reg-item').each(function(index) {
                        $(this).find('.reg-radio').val(index);
                    });
                });
            }
        });

        // Dodawanie nowego szablonu - wymusza bezpieczny POST, aby WP wyrenderował nowy TinyMCE
        $('#btn-add-template').on('click', function(e){
            e.preventDefault();
            $('#add_new_flag').val('1');
            $('#regulaminy-form').append('<input type="hidden" name="blendygo_save_regulaminy" value="1">');
            $('#regulaminy-form').submit();
        });
    });
    </script>
    <?php
}

/**
 * Dodaj pola obrazków do odpowiedzi REST API dla edytora bloków
 */
add_action('rest_api_init', function() {
    register_rest_field('promocje', 'promo_meta', array(
        'get_callback' => function($object) {
            $post_id = $object['id'];
            return array(
                'desk' => get_post_meta($post_id, 'promo_banner_main_desk', true),
                'mob'  => get_post_meta($post_id, 'promo_banner_main_mob', true),
            );
        }
    ));
});
// AJAX SIDELOAD W TLE
add_action('wp_ajax_blendygo_async_sideload', 'blendygo_async_sideload_handler');
function blendygo_async_sideload_handler() {
    check_ajax_referer('blendygo_sideload_nonce', '_ajax_nonce');
    if (!current_user_can('edit_posts')) wp_send_json_error('Brak uprawnień');

    $url = isset($_POST['image_url']) ? esc_url_raw($_POST['image_url']) : '';
    $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
    $field_name = isset($_POST['field_name']) ? sanitize_text_field($_POST['field_name']) : '';

    if (empty($url) || empty($post_id) || empty($field_name)) wp_send_json_error('Brak danych');
    
    $home = home_url();
    if (strpos($url, $home) !== false) wp_send_json_success(['url' => $url]);

    require_once(ABSPATH . 'wp-admin/includes/media.php');
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/image.php');

    $sideloaded = media_sideload_image($url, $post_id, null, 'src');
    if (!is_wp_error($sideloaded)) {
        update_post_meta($post_id, $field_name, $sideloaded);
        wp_send_json_success(['url' => $sideloaded]);
    } else {
        wp_send_json_error($sideloaded->get_error_message());
    }
}
