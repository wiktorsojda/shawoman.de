<?php

/**
 * Globalne Ustawienia Sklepu (Snippet)
 * Gotowe do wklejenia do Code Snippets
 */

// 1. Rejestracja zakładki w menu
add_action('admin_menu', 'blendygode_register_store_settings_page');
function blendygode_register_store_settings_page() {
    add_menu_page(
        'Opcje Sklepu',
        'Opcje Sklepu',
        'manage_options',
        'blendy-store-settings',
        'blendygode_render_store_settings_page',
        'dashicons-admin-generic', // Ikona
        58 // Pozycja (pod produktami)
    );
}

// 2. Rejestracja zmiennych w bazie wp_options
add_action('admin_init', 'blendygode_register_store_settings');
function blendygode_register_store_settings() {
    $settings_group = 'blendy_store_settings_group';

    // Zakładka 1: Baner Sklepu
    register_setting($settings_group, 'blendy_shop_banner_desk');
    register_setting($settings_group, 'blendy_shop_banner_mob');

    // Zakładka 2: Pasek Magazynowy
    register_setting($settings_group, 'blendy_stock_strip_enabled');
    register_setting($settings_group, 'blendy_stock_strip_percent');
    register_setting($settings_group, 'blendy_stock_strip_text');
    register_setting($settings_group, 'blendy_stock_strips_json');

    // Zakładka 3: Akordeony (Rezerwujemy 5 slotów)
    for ($i = 1; $i <= 5; $i++) {
        register_setting($settings_group, 'blendy_acc_title_' . $i);
        register_setting($settings_group, 'blendy_acc_svg_' . $i);
        register_setting($settings_group, 'blendy_acc_content_' . $i);
    }

    // Zakładka 4: Odznaki Promocyjne (JSON Repeater)
    register_setting($settings_group, 'blendy_promo_badges_json');
    
    // Zakładka 5: Etykiety Tekstowe (JSON Repeater)
    register_setting($settings_group, 'blendy_text_badges_json');
    register_setting($settings_group, 'blendy_svg_badges_json');
}

// 3. Załadowanie skryptów (Media Uploader + WooCommerce Select2)
add_action('admin_enqueue_scripts', 'blendygode_store_settings_scripts');
function blendygode_store_settings_scripts($hook) {
    if ($hook !== 'toplevel_page_blendy-store-settings') {
        return;
    }
    wp_enqueue_media();
    
    // Załaduj style i skrypty WooCommerce dla select2 (wyszukiwarka produktów)
    if (class_exists('WooCommerce')) {
        wp_enqueue_style('woocommerce_admin_styles', WC()->plugin_url() . '/assets/css/admin.css', array(), WC_VERSION);
        wp_enqueue_script('wc-enhanced-select');
    }
}

// 4. Renderowanie interfejsu wizualnego (UI)
function blendygode_render_store_settings_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    if (isset($_GET['settings-updated'])) {
        add_settings_error('blendy_store_messages', 'blendy_store_message', __('Ustawienia zapisane.', 'text-domain'), 'updated');
    }
    settings_errors('blendy_store_messages');

    // Pobierz kategorie produktów do JS
    $product_cats = get_terms(array('taxonomy' => 'product_cat', 'hide_empty' => false));
    $cats_array = array();
    if (!is_wp_error($product_cats)) {
        foreach ($product_cats as $cat) {
            $cats_array[] = array('id' => $cat->term_id, 'name' => $cat->name);
        }
    }
    ?>

    <style>
        .blendy-wrap { max-width: 1000px; margin-top: 20px; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif; }
        .blendy-header { background: #fff; padding: 20px 30px; border-radius: 8px 8px 0 0; border: 1px solid #ccd0d4; border-bottom: none; display: flex; align-items: center; gap: 15px; }
        .blendy-header h1 { margin: 0; font-size: 22px; font-weight: 600; color: #1d2327; }
        
        .blendy-tabs { display: flex; background: #f0f0f1; border-left: 1px solid #ccd0d4; border-right: 1px solid #ccd0d4; padding: 0 15px; }
        .blendy-tab { padding: 15px 25px; cursor: pointer; border-bottom: 3px solid transparent; font-weight: 600; color: #50575e; transition: all 0.2s ease; }
        .blendy-tab:hover { color: #1d2327; }
        .blendy-tab.active { color: #2271b1; border-bottom-color: #2271b1; background: #fff; }

        .blendy-content { background: #fff; border: 1px solid #ccd0d4; border-top: none; padding: 30px; border-radius: 0 0 8px 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
        .blendy-tab-content { display: none; }
        .blendy-tab-content.active { display: block; animation: fadein 0.3s; }
        @keyframes fadein { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }

        .blendy-field-group { margin-bottom: 25px; padding-bottom: 25px; border-bottom: 1px solid #f0f0f1; }
        .blendy-field-group:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
        .blendy-label { display: block; font-weight: 600; margin-bottom: 8px; color: #1d2327; font-size: 14px; }
        .blendy-desc { display: block; color: #646970; font-size: 13px; margin-bottom: 10px; font-style: italic; }
        
        .promo-swatches { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 10px; }
        .promo-swatch { width: 30px; height: 30px; border-radius: 4px; border: 2px solid #ddd; cursor: pointer; position: relative; display: flex; align-items: center; justify-content: center; font-weight: bold; color: #555; background: #fff; transition: all 0.2s; }
        .promo-swatch:hover { border-color: #999; transform: scale(1.05); }
        .promo-swatch.is-selected { border-color: #2271b1; box-shadow: 0 0 0 2px rgba(34,113,177,0.3); }
        .custom-css-input { display: none; }
        .custom-css-input.is-active { display: block; margin-top: 10px; }
        
        .blendy-input-text { width: 100%; max-width: 600px; padding: 8px 12px; border-radius: 4px; border: 1px solid #8c8f94; }
        .blendy-input-textarea { width: 100%; max-width: 600px; height: 100px; padding: 8px 12px; border-radius: 4px; border: 1px solid #8c8f94; font-family: monospace; }
        
        .blendy-img-preview { max-width: 300px; margin-top: 10px; border-radius: 4px; display: block; border: 1px dashed #ccd0d4; padding: 5px; }
        .button-media-upload { margin-top: 5px !important; }

        .blendy-accordion-item { background: #f6f7f7; border: 1px solid #ccd0d4; border-radius: 6px; padding: 20px; margin-bottom: 20px; position: relative; }
        .blendy-accordion-item h3, .blendy-repeater-row h3 { margin-top: 0; padding-bottom: 10px; border-bottom: 1px solid #ccd0d4; font-size: 16px; margin-bottom: 20px; }
        
        .blendy-remove-btn { display: inline-block; margin-bottom: 15px; color: #d63638; text-decoration: none; font-weight: 600; font-size: 13px; padding: 4px 8px; border: 1px solid #d63638; border-radius: 3px; transition: all 0.2s; background: #fff; }
        .blendy-remove-btn:hover { background: #d63638 !important; color: #fff !important; text-decoration: none; }

        .blendy-submit-wrap { margin-top: 30px; padding-top: 20px; border-top: 1px solid #ccd0d4; }
        
        /* Repeater Styles */
        .blendy-repeater-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(420px, 1fr)); gap: 20px; align-items: start; }
        .blendy-repeater-row { background: #fff; border: 1px solid #e2e4e7; border-radius: 8px; padding: 20px; position: relative; box-shadow: 0 2px 5px rgba(0,0,0,0.04); transition: all 0.3s ease; }
        .blendy-repeater-row:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.08); border-color: #ccd0d4; }
        
        .blendy-repeater-row h3 { padding-right: 120px; cursor: pointer; user-select: none; display: flex; align-items: center; color: #1d2327; font-size: 15px; transition: color 0.2s; }
        .blendy-repeater-row h3:hover { color: #2271b1; }
        .blendy-repeater-row h3::before { content: '\f140'; font-family: dashicons; margin-right: 8px; color: #8c8f94; transition: transform 0.2s; font-size: 20px; width: 20px; height: 20px; display: inline-flex; align-items: center; justify-content: center; }
        
        .blendy-repeater-row.is-folded h3::before { transform: rotate(-90deg); }
        .blendy-repeater-row.is-folded .blendy-field-group { display: none; }
        .blendy-repeater-row.is-folded h3 { border-bottom: none; padding-bottom: 0; margin-bottom: 0; }
        
        .blendy-remove-btn.blendy-remove-row, .blendy-remove-btn.blendy-remove-row-stock, .blendy-remove-btn.blendy-remove-row-text-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            margin-bottom: 0;
            z-index: 2;
        }

        .blendy-add-row { display: inline-block; margin-bottom: 20px; }
        .wp-core-ui .blendy-add-row { margin-bottom: 25px; } /* Delikatny odstęp 5px */
    </style>

    <div class="blendy-wrap">
        <div class="blendy-header">
            <span class="dashicons dashicons-store" style="font-size: 28px; width: 28px; height: 28px; color: #2271b1;"></span>
            <h1>Opcje Globalne Sklepu</h1>
        </div>

        <div class="blendy-tabs">
            <div class="blendy-tab active" data-target="tab-banner">Banery Sklepu</div>
            <div class="blendy-tab" data-target="tab-stock">Paski Magazynowe</div>
            <div class="blendy-tab" data-target="tab-accordions">Akordeony</div>
            <div class="blendy-tab" data-target="tab-badges">Odznaki Procentowe</div>
            <div class="blendy-tab" data-target="tab-text-badges">Etykiety Tekstowe</div>
            <div class="blendy-tab" data-target="tab-svg-badges">Odznaki SVG</div>
        </div>

        <div class="blendy-content">
            <form action="options.php" method="post" id="blendy-store-settings-form">
                <?php settings_fields('blendy_store_settings_group'); ?>
                
                <!-- ZAKŁADKA 1: BANER SKLEPU -->
                <div id="tab-banner" class="blendy-tab-content active">
                    <h2>Główny baner wyświetlany na listach produktów</h2>
                    
                    <div class="blendy-field-group">
                        <label class="blendy-label">Zdjęcie (Desktop)</label>
                        <span class="blendy-desc">Grafika dla urządzeń z większym ekranem.</span>
                        <input type="hidden" name="blendy_shop_banner_desk" id="blendy_shop_banner_desk" value="<?php echo esc_attr(get_option('blendy_shop_banner_desk')); ?>">
                        <button type="button" class="button button-secondary button-media-upload" data-target="blendy_shop_banner_desk">Wybierz z biblioteki</button>
                        <img class="blendy-img-preview" src="<?php echo esc_url(get_option('blendy_shop_banner_desk')); ?>" style="<?php echo get_option('blendy_shop_banner_desk') ? '' : 'display:none;'; ?>">
                    </div>

                    <div class="blendy-field-group">
                        <label class="blendy-label">Zdjęcie (Mobile)</label>
                        <span class="blendy-desc">Grafika dedykowana dla telefonów.</span>
                        <input type="hidden" name="blendy_shop_banner_mob" id="blendy_shop_banner_mob" value="<?php echo esc_attr(get_option('blendy_shop_banner_mob')); ?>">
                        <button type="button" class="button button-secondary button-media-upload" data-target="blendy_shop_banner_mob">Wybierz z biblioteki</button>
                        <img class="blendy-img-preview" src="<?php echo esc_url(get_option('blendy_shop_banner_mob')); ?>" style="<?php echo get_option('blendy_shop_banner_mob') ? '' : 'display:none;'; ?>">
                    </div>
                </div>

                <!-- ZAKŁADKA 2: PASEK MAGAZYNOWY -->
                <div id="tab-stock" class="blendy-tab-content">
                    <h2>Globalne ustawienia paska</h2>
                    
                    <div class="blendy-field-group">
                        <label class="blendy-label">Status paska na sklepie</label>
                        <select name="blendy_stock_strip_enabled" class="blendy-input-text" style="max-width: 200px;">
                            <option value="yes" <?php selected(get_option('blendy_stock_strip_enabled', 'yes'), 'yes'); ?>>Włączony</option>
                            <option value="no" <?php selected(get_option('blendy_stock_strip_enabled'), 'no'); ?>>Wyłączony</option>
                        </select>
                        <span class="blendy-desc" style="margin-top:5px;">Jeśli wyłączysz tutaj, pasek zniknie ze wszystkich produktów (chyba że w produkcie nadpiszesz to ustawienie).</span>
                    </div>

                    <div class="blendy-field-group">
                        <label class="blendy-label">Domyślny Tekst Paska</label>
                        <input type="text" name="blendy_stock_strip_text" class="blendy-input-text" value="<?php echo esc_attr(get_option('blendy_stock_strip_text', 'Nur solange der Vorrat reicht! – Nur noch: {percent}%')); ?>">
                        <span class="blendy-desc">Zostanie użyty jeśli produkt nie łapie się na żadną regułę. Tag <code>{percent}</code> zostanie zamieniony na liczbę.</span>
                    </div>

                    <div class="blendy-field-group">
                        <label class="blendy-label">Domyślny procent wypełnienia (%)</label>
                        <input type="number" name="blendy_stock_strip_percent" class="blendy-input-text" min="0" max="100" style="max-width: 150px;" value="<?php echo esc_attr(get_option('blendy_stock_strip_percent', 80)); ?>">
                        <span class="blendy-desc">Np. 80. Użyty globalnie, jeśli nie ma reguły ani nadpisania.</span>
                    </div>

                    <hr style="margin:30px 0; border:1px solid #ddd;">
                    
                    <h2>Dynamiczne Reguły Paska Magazynowego</h2>
                    <p class="blendy-desc">Pozwala nadpisać tekst i procent dla konkretnych kategorii lub produktów.</p>
                    
                    <button type="button" class="button button-secondary blendy-add-row" id="add-stock-rule">+ Dodaj nową regułę paska</button>
                    
                    <div id="blendy-stock-container" class="blendy-repeater-grid">
                        <!-- Tu JS wrzuca rzędy pasków -->
                    </div>

                    <!-- Ukryte pole trzymające JSON ze wszystkimi regułami -->
                    <textarea name="blendy_stock_strips_json" id="blendy_stock_strips_json" style="display:none;"><?php echo esc_textarea(get_option('blendy_stock_strips_json', '[]')); ?></textarea>
                </div>

                <!-- ZAKŁADKA 3: AKORDEONY -->
                <div id="tab-accordions" class="blendy-tab-content">
                    <h2>Globalne Akordeony pod przyciskiem Koszyka</h2>
                    <p class="blendy-desc">Wypełnij poniższe sloty. Puste sekcje zostaną zignorowane.</p>
                    
                    <?php for ($i = 1; $i <= 5; $i++) : ?>
                    <div class="blendy-accordion-item">
                        <h3>Slot #<?php echo $i; ?></h3>
                        
                        <div class="blendy-field-group" style="border:none; padding:0; margin-bottom:15px;">
                            <label class="blendy-label">Tytuł</label>
                            <input type="text" name="blendy_acc_title_<?php echo $i; ?>" class="blendy-input-text" value="<?php echo esc_attr(get_option('blendy_acc_title_' . $i)); ?>">
                        </div>
                        
                        <div class="blendy-field-group" style="border:none; padding:0; margin-bottom:15px;">
                            <label class="blendy-label">Kod SVG Ikony</label>
                            <textarea name="blendy_acc_svg_<?php echo $i; ?>" class="blendy-input-textarea"><?php echo esc_textarea(get_option('blendy_acc_svg_' . $i)); ?></textarea>
                        </div>
                        
                        <div class="blendy-field-group" style="border:none; padding:0; margin-bottom:0;">
                            <label class="blendy-label">Treść (Rozwinięcie)</label>
                            <?php 
                                wp_editor(
                                    get_option('blendy_acc_content_' . $i), 
                                    'blendy_acc_content_' . $i, 
                                    array(
                                        'textarea_name' => 'blendy_acc_content_' . $i,
                                        'media_buttons' => false,
                                        'textarea_rows' => 5,
                                        'teeny'         => true,
                                        'quicktags'     => false
                                    )
                                ); 
                            ?>
                        </div>
                    </div>
                    <?php endfor; ?>
                </div>

                <!-- ZAKŁADKA 4: ODZNAKI PROMOCYJNE -->
                <div id="tab-badges" class="blendy-tab-content">
                    <h2>Odznaki (Badges) - Dynamiczne Reguły</h2>
                    <p class="blendy-desc">Twórz nieskończoną ilość reguł dla odznak. Możesz je przypisywać do całych kategorii lub wyszukiwać konkretne produkty.</p>
                    
                    <button type="button" class="button button-secondary blendy-add-row" id="add-badge-rule">+ Dodaj nową regułę odznaki</button>
                    
                    <div id="blendy-badges-container" class="blendy-repeater-grid">
                        <!-- Tu JS wrzuca rzędy -->
                    </div>

                    <!-- Ukryte pole trzymające JSON ze wszystkimi regułami -->
                    <textarea name="blendy_promo_badges_json" id="blendy_promo_badges_json" style="display:none;"><?php echo esc_textarea(get_option('blendy_promo_badges_json', '[]')); ?></textarea>
                </div>
                <!-- ZAKŁADKA 5: ETYKIETY TEKSTOWE -->
                <div id="tab-text-badges" class="blendy-tab-content">
                    <h2>Etykiety Tekstowe (Bestseller, Nowość)</h2>
                    <p class="blendy-desc">Etykiety wyświetlane nad odznaką procentową. Możesz dodawać własne teksty (np. "Bestseller") i ustawiać im kolory tła.</p>
                    
                    <button type="button" class="button button-secondary blendy-add-row" id="add-text-badge-rule">+ Dodaj nową etykietę tekstową</button>
                    
                    <div id="blendy-text-badges-container" class="blendy-repeater-grid">
                        <!-- Tu JS wrzuca rzędy -->
                    </div>

                    <!-- Ukryte pole trzymające JSON ze wszystkimi regułami -->
                    <textarea name="blendy_text_badges_json" id="blendy_text_badges_json" style="display:none;"><?php echo esc_textarea(get_option('blendy_text_badges_json', '[]')); ?></textarea>
                </div>
                <div id="tab-svg-badges" class="blendy-tab-content">
                    <h2>Odznaki SVG (Pod krótkim opisem)</h2>
                    <p class="blendy-desc">Etykiety z ikonami (SVG / Zdjęcie), tekstem, customizacją stylów, które wyświetlą się na stronach produktów pod opisem.</p>
                    
                    <button type="button" class="button button-secondary blendy-add-row" id="add-svg-badge-rule">+ Dodaj nową odznakę SVG</button>
                    
                    <div id="blendy-svg-badges-container" class="blendy-repeater-grid">
                        <!-- Tu JS wrzuca rzędy -->
                    </div>

                    <!-- Ukryte pole trzymające JSON ze wszystkimi regułami -->
                    <textarea name="blendy_svg_badges_json" id="blendy_svg_badges_json" style="display:none;"><?php echo esc_textarea(get_option('blendy_svg_badges_json', '[]')); ?></textarea>
                </div>
                <div class="blendy-submit-wrap">
                    <?php submit_button('Zapisz Globalne Ustawienia', 'primary', 'submit', false, array('style' => 'font-size: 16px; padding: 6px 24px;', 'id' => 'blendy-main-submit')); ?>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Zakładki
            const tabs = document.querySelectorAll('.blendy-tab');
            const contents = document.querySelectorAll('.blendy-tab-content');

            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    tabs.forEach(t => t.classList.remove('active'));
                    contents.forEach(c => c.classList.remove('active'));
                    this.classList.add('active');
                    document.getElementById(this.dataset.target).classList.add('active');
                });
            });

            // Media Uploader WordPressa
            let file_frame;
            let currentTargetId = '';
            
            document.querySelectorAll('.button-media-upload').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    currentTargetId = this.dataset.target;
                    
                    if (file_frame) {
                        file_frame.open();
                        return;
                    }

                    file_frame = wp.media.frames.file_frame = wp.media({
                        title: 'Wybierz baner',
                        button: { text: 'Użyj tego obrazka' },
                        multiple: false
                    });

                    file_frame.on('select', function() {
                        let attachment = file_frame.state().get('selection').first().toJSON();
                        let targetInput = document.getElementById(currentTargetId);
                        targetInput.value = attachment.url;
                        let previewImg = targetInput.nextElementSibling.nextElementSibling;
                        if(previewImg && previewImg.tagName === 'IMG') {
                            previewImg.src = attachment.url;
                            previewImg.style.display = 'block';
                        }
                    });

                    file_frame.open();
                });
            });

            // --- REPEATER ODZNAK PROMOCYJNYCH ---
            const container = document.getElementById('blendy-badges-container');
            const hiddenJsonInput = document.getElementById('blendy_promo_badges_json');
            const addBtn = document.getElementById('add-badge-rule');
            const form = document.getElementById('blendy-store-settings-form');

            // Media Uploader dla Repeaterów (SVG Badges)
            form.addEventListener('click', function(e) {
                if (e.target.classList.contains('sb-btn-img-upload') || e.target.classList.contains('sb-btn-bgimg-upload')) {
                    e.preventDefault();
                    let btn = e.target;
                    let isBg = btn.classList.contains('sb-btn-bgimg-upload');
                    let card = btn.closest('.promo-media-card');
                    let input = card.querySelector(isBg ? '.sb-bgimg-input' : '.sb-img-input');
                    let preview = card.querySelector('.promo-preview-box');
                    let actions = card.querySelector('.promo-actions');
                    
                    let frame = wp.media({
                        title: 'Wybierz obrazek',
                        button: { text: 'Użyj' },
                        multiple: false
                    });
                    
                    frame.on('select', function() {
                        let attachment = frame.state().get('selection').first().toJSON();
                        input.value = attachment.url;
                        preview.innerHTML = '<img src="' + attachment.url + '">';
                        
                        if (!actions.querySelector('.btn-remove')) {
                            let removeBtnClass = isBg ? 'sb-btn-bgimg-remove' : 'sb-btn-img-remove';
                            actions.insertAdjacentHTML('beforeend', '<a href="#" class="btn-remove ' + removeBtnClass + '">Usuń</a>');
                        }
                        
                        syncSvgBadgeDataFromDOM();
                    });
                    frame.open();
                }

                if (e.target.classList.contains('sb-btn-img-remove') || e.target.classList.contains('sb-btn-bgimg-remove')) {
                    e.preventDefault();
                    let btn = e.target;
                    let isBg = btn.classList.contains('sb-btn-bgimg-remove');
                    let card = btn.closest('.promo-media-card');
                    let input = card.querySelector(isBg ? '.sb-bgimg-input' : '.sb-img-input');
                    let preview = card.querySelector('.promo-preview-box');
                    
                    input.value = '';
                    preview.innerHTML = '<span class="dashicons dashicons-format-image"></span>';
                    btn.remove();
                    
                    syncSvgBadgeDataFromDOM();
                }
            });

            
            // Pobierz kategorie przekazane z PHP
            const wpCategories = <?php echo json_encode($cats_array); ?>;

            // Wczytaj początkowe dane
            let badgeData = [];
            try {
                badgeData = JSON.parse(hiddenJsonInput.value) || [];
            } catch(e) { badgeData = []; }

            const predefinedColors = [
                'linear-gradient(90deg, #630303 1.11%, #C90606 96.67%)',
                'linear-gradient(85deg, #630303 -7.37%, #F00 107.37%)',
                'linear-gradient(265deg, #E0AC84 0%, #A4664A 100%)',
                '#000000'
            ];

            function renderRows() {
                // Przed zniszczeniem DOM niszczymy select2 żeby uniknąć wycieków pamięci
                if (typeof jQuery !== 'undefined' && jQuery.fn.selectWoo) {
                    jQuery('.wc-product-search').each(function() {
                        if(jQuery(this).hasClass('select2-hidden-accessible')) {
                            jQuery(this).selectWoo('destroy');
                        }
                    });
                }
                
                container.innerHTML = '';
                
                badgeData.forEach((rule, index) => {
                    const row = document.createElement('div');
                    row.className = 'blendy-repeater-row' + (rule.isFolded ? ' is-folded' : '');
                    
                    let isCustomColor = rule.color && !predefinedColors.includes(rule.color);
                    let selectValue = isCustomColor ? 'custom' : (rule.color || predefinedColors[0]);
                    let headerTitle = rule.text ? 'Reguła: ' + rule.text : 'Reguła #' + (index + 1);

                    row.innerHTML = `
                        <h3 class="rule-header">${headerTitle}</h3>
                        <a href="#" class="blendy-remove-btn blendy-remove-row" data-index="${index}">🗑 Usuń Regułę</a>
                        
                        <div class="blendy-field-group" style="border:none; padding:0; margin-bottom:15px;">
                            <label class="blendy-label">Zastosuj do:</label>
                            <select class="blendy-input-text badge-type-select" data-index="${index}" style="max-width:300px;">
                                <option value="global" ${rule.type === 'global' ? 'selected' : ''}>Wszystkich Produktów (Globalnie)</option>
                                <option value="categories" ${rule.type === 'categories' ? 'selected' : ''}>Wybranych Kategorii</option>
                                <option value="products" ${rule.type === 'products' ? 'selected' : ''}>Konkretnych Produktów</option>
                            </select>
                        </div>
                        
                        <div class="blendy-field-group target-container-categories" style="border:none; padding:0; margin-bottom:15px; ${rule.type === 'categories' ? '' : 'display:none;'}">
                            <label class="blendy-label">Wybierz kategorie:</label>
                            <select class="blendy-input-text badge-cats-select" multiple="multiple" data-index="${index}" style="max-width:600px; height:80px;">
                                ${wpCategories.map(cat => `<option value="${cat.id}" ${(rule.categories || []).includes(cat.id.toString()) ? 'selected' : ''}>${cat.name}</option>`).join('')}
                            </select>
                            <span class="blendy-desc">Przytrzymaj CTRL/CMD aby wybrać wiele kategorii.</span>
                        </div>

                        <div class="blendy-field-group target-container-products" style="border:none; padding:0; margin-bottom:15px; ${rule.type === 'products' ? '' : 'display:none;'}">
                            <label class="blendy-label">Wyszukaj i wybierz produkty:</label>
                            <select class="wc-product-search badge-products-select" multiple="multiple" style="width: 100%; max-width:600px;" data-placeholder="Szukaj produktów..." data-action="woocommerce_json_search_products_and_variations" data-index="${index}">
                            </select>
                        </div>
                        
                        <div class="blendy-field-group" style="border:none; padding:0; margin-bottom:15px;">
                            <label class="blendy-label">Procent obniżki (sama liczba)</label>
                            <input type="number" class="blendy-input-text badge-text-input" data-index="${index}" value="${rule.text || ''}" placeholder="np. 20">
                        </div>

                        <div class="blendy-field-group" style="border:none; padding:0; margin-bottom:0;">
                            <label class="blendy-label">Kolor Liczby (Kod CSS - Gradient lub Kolor)</label>
                            <div class="promo-swatches" data-index="${index}">
                                ${predefinedColors.map(color => `
                                    <div class="promo-swatch ${selectValue === color ? 'is-selected' : ''}" style="background: ${color};" data-color="${color}" title="${color}"></div>
                                `).join('')}
                                <div class="promo-swatch custom-trigger ${selectValue === 'custom' ? 'is-selected' : ''}" title="Własny CSS">+</div>
                            </div>
                            <div class="custom-css-input ${selectValue === 'custom' ? 'is-active' : ''}">
                                <input type="text" class="blendy-input-text badge-color-input" data-index="${index}" value="${isCustomColor ? rule.color : (selectValue === 'custom' ? '' : selectValue)}" placeholder="Wpisz np. linear-gradient(to right, #ff0000, #00ff00)">
                            </div>
                        </div>
                    `;
                    container.appendChild(row);

                    // Jeśli są wybrane produkty, dodajemy optiony na sztywno żeby select2 je załadował prawidłowo
                    if (rule.type === 'products' && rule.products && rule.products.length > 0) {
                        const selectElement = row.querySelector('.badge-products-select');
                        rule.products.forEach(prod => {
                            const option = new Option(prod.text, prod.id, true, true);
                            selectElement.append(option);
                        });
                    }
                });

                // Podepnij usuwanie
                document.querySelectorAll('.blendy-remove-row').forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        if(confirm('Na pewno usunąć tę regułę?')) {
                            syncDataFromDOM();
                            badgeData.splice(parseInt(this.dataset.index), 1);
                            renderRows();
                        }
                    });
                });

                // Podepnij przełączanie selectów typu (Global/Kategorie/Produkty)
                document.querySelectorAll('.badge-type-select').forEach(select => {
                    select.addEventListener('change', function(e) {
                        syncDataFromDOM();
                        badgeData[parseInt(this.dataset.index)].type = this.value;
                        renderRows();
                    });
                });

                // Obsługa swatchy kolorów
                document.querySelectorAll('.promo-swatches').forEach(swatches => {
                    swatches.addEventListener('click', function(e) {
                        const swatch = e.target.closest('.promo-swatch');
                        if (!swatch) return;
                        
                        const container = this.parentElement;
                        const inputField = container.querySelector('.badge-color-input');
                        const customInputBox = container.querySelector('.custom-css-input');
                        
                        // Zmiana klasy is-selected
                        this.querySelectorAll('.promo-swatch').forEach(s => s.classList.remove('is-selected'));
                        swatch.classList.add('is-selected');

                        if (swatch.classList.contains('custom-trigger')) {
                            customInputBox.classList.add('is-active');
                            inputField.value = ''; // Czekamy na wpis usera
                        } else {
                            customInputBox.classList.remove('is-active');
                            inputField.value = swatch.dataset.color;
                        }
                    });
                });

                // Zainicjuj Select2 z WooCommerce na nowo wygenerowanych rzędach
                if (typeof jQuery !== 'undefined' && jQuery.fn.selectWoo) {
                    jQuery('.wc-product-search').each(function() {
                        var $select = jQuery(this);
                        $select.selectWoo({
                            minimumInputLength: 3,
                            allowClear: true,
                            ajax: {
                                url: ajaxurl,
                                dataType: 'json',
                                delay: 250,
                                data: function(params) {
                                    return {
                                        term: params.term,
                                        action: $select.data('action') || 'woocommerce_json_search_products_and_variations',
                                        security: typeof wc_enhanced_select_params !== 'undefined' ? wc_enhanced_select_params.search_products_nonce : ''
                                    };
                                },
                                processResults: function(data) {
                                    var terms = [];
                                    if (data) {
                                        jQuery.each(data, function(id, text) {
                                            terms.push({ id: id, text: text });
                                        });
                                    }
                                    return { results: terms };
                                },
                                cache: true
                            }
                        });
                    });
                }
            }

            // Funkcja ściągająca wpisane dane do pamięci przed np. dodaniem nowego wiersza
            function syncDataFromDOM() {
                const rows = document.querySelectorAll('.blendy-repeater-row:not(.stock-row):not(.text-badge-row)');
                badgeData = [];
                rows.forEach((row, idx) => {
                    const type = row.querySelector('.badge-type-select').value;
                    const text = row.querySelector('.badge-text-input').value;
                    const color = row.querySelector('.badge-color-input').value;
                    const isFolded = row.classList.contains('is-folded');
                    
                    let categories = [];
                    let products = [];
                    
                    if (type === 'categories') {
                        const catSelect = row.querySelector('.badge-cats-select');
                        Array.from(catSelect.selectedOptions).forEach(opt => categories.push(opt.value));
                    } else if (type === 'products') {
                        const prodSelect = row.querySelector('.badge-products-select');
                        if (typeof jQuery !== 'undefined') {
                            const selectedData = jQuery(prodSelect).selectWoo('data');
                            selectedData.forEach(item => {
                                products.push({ id: item.id, text: item.text });
                            });
                        }
                    }

                    badgeData.push({ type, categories, products, text, color });
                });
            }

            // Inicjalny render
            renderRows();

            // Dodawanie wiersza (Odznaki)
            addBtn.addEventListener('click', function(e) {
                e.preventDefault();
                syncDataFromDOM();
                badgeData.push({ type: 'categories', categories: [], products: [], text: '', color: '' });
                renderRows();
            });

            // --- REPEATER PASKÓW MAGAZYNOWYCH ---
            const containerStock = document.getElementById('blendy-stock-container');
            const hiddenJsonInputStock = document.getElementById('blendy_stock_strips_json');
            const addBtnStock = document.getElementById('add-stock-rule');
            
            let stockData = [];
            try {
                stockData = JSON.parse(hiddenJsonInputStock.value) || [];
            } catch(e) { stockData = []; }

            function renderStockRows() {
                if (typeof jQuery !== 'undefined' && jQuery.fn.selectWoo) {
                    jQuery(containerStock).find('.wc-product-search').each(function() {
                        if(jQuery(this).hasClass('select2-hidden-accessible')) {
                            jQuery(this).selectWoo('destroy');
                        }
                    });
                }
                
                containerStock.innerHTML = '';
                
                stockData.forEach((rule, index) => {
                    const row = document.createElement('div');
                    row.className = 'blendy-repeater-row stock-row' + (rule.isFolded ? ' is-folded' : '');
                    let headerTitle = rule.text ? 'Pasek: ' + rule.text : 'Reguła Paska #' + (index + 1);
                    
                    row.innerHTML = `
                        <h3 class="rule-header">${headerTitle}</h3>
                        <a href="#" class="blendy-remove-btn blendy-remove-row-stock" data-index="${index}">🗑 Usuń Regułę</a>
                        
                        <div class="blendy-field-group" style="border:none; padding:0; margin-bottom:15px;">
                            <label class="blendy-label">Zastosuj do:</label>
                            <select class="blendy-input-text stock-type-select" data-index="${index}" style="max-width:300px;">
                                <option value="global" ${rule.type === 'global' ? 'selected' : ''}>Wszystkich Produktów (Globalnie)</option>
                                <option value="categories" ${rule.type === 'categories' ? 'selected' : ''}>Wybranych Kategorii</option>
                                <option value="products" ${rule.type === 'products' ? 'selected' : ''}>Konkretnych Produktów</option>
                            </select>
                        </div>
                        
                        <div class="blendy-field-group target-container-categories" style="border:none; padding:0; margin-bottom:15px; ${rule.type === 'categories' ? '' : 'display:none;'}">
                            <label class="blendy-label">Wybierz kategorie:</label>
                            <select class="blendy-input-text stock-cats-select" multiple="multiple" data-index="${index}" style="max-width:600px; height:80px;">
                                ${wpCategories.map(cat => `<option value="${cat.id}" ${(rule.categories || []).includes(cat.id.toString()) ? 'selected' : ''}>${cat.name}</option>`).join('')}
                            </select>
                        </div>

                        <div class="blendy-field-group target-container-products" style="border:none; padding:0; margin-bottom:15px; ${rule.type === 'products' ? '' : 'display:none;'}">
                            <label class="blendy-label">Wyszukaj i wybierz produkty:</label>
                            <select class="wc-product-search stock-products-select" multiple="multiple" style="width: 100%; max-width:600px;" data-placeholder="Szukaj produktów..." data-action="woocommerce_json_search_products_and_variations" data-index="${index}">
                            </select>
                        </div>
                        
                        <div class="blendy-field-group" style="border:none; padding:0; margin-bottom:15px;">
                            <label class="blendy-label">Nadpisany Tekst Paska</label>
                            <input type="text" class="blendy-input-text stock-text-input" data-index="${index}" value="${rule.text || ''}" placeholder="Tylko {percent}% pozostało!">
                        </div>

                        <div class="blendy-field-group" style="border:none; padding:0; margin-bottom:0;">
                            <label class="blendy-label">Procent Wypełnienia (%)</label>
                            <input type="number" class="blendy-input-text stock-percent-input" data-index="${index}" min="0" max="100" style="max-width: 150px;" value="${rule.percent || ''}" placeholder="80">
                        </div>
                    `;
                    containerStock.appendChild(row);

                    if (rule.type === 'products' && rule.products && rule.products.length > 0) {
                        const selectElement = row.querySelector('.stock-products-select');
                        rule.products.forEach(prod => {
                            const option = new Option(prod.text, prod.id, true, true);
                            selectElement.append(option);
                        });
                    }
                });

                document.querySelectorAll('.blendy-remove-row-stock').forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        if(confirm('Na pewno usunąć tę regułę?')) {
                            syncStockDataFromDOM();
                            stockData.splice(parseInt(this.dataset.index), 1);
                            renderStockRows();
                        }
                    });
                });

                document.querySelectorAll('.stock-type-select').forEach(select => {
                    select.addEventListener('change', function(e) {
                        syncStockDataFromDOM();
                        stockData[parseInt(this.dataset.index)].type = this.value;
                        renderStockRows();
                    });
                });

                if (typeof jQuery !== 'undefined' && jQuery.fn.selectWoo) {
                    jQuery(containerStock).find('.wc-product-search').each(function() {
                        var $select = jQuery(this);
                        $select.selectWoo({
                            minimumInputLength: 3,
                            allowClear: true,
                            ajax: {
                                url: ajaxurl,
                                dataType: 'json',
                                delay: 250,
                                data: function(params) {
                                    return {
                                        term: params.term,
                                        action: $select.data('action') || 'woocommerce_json_search_products_and_variations',
                                        security: typeof wc_enhanced_select_params !== 'undefined' ? wc_enhanced_select_params.search_products_nonce : ''
                                    };
                                },
                                processResults: function(data) {
                                    var terms = [];
                                    if (data) { jQuery.each(data, function(id, text) { terms.push({ id: id, text: text }); }); }
                                    return { results: terms };
                                },
                                cache: true
                            }
                        });
                    });
                }
            }

            function syncStockDataFromDOM() {
                const rows = document.querySelectorAll('.stock-row');
                stockData = [];
                rows.forEach((row, idx) => {
                    const type = row.querySelector('.stock-type-select').value;
                    const text = row.querySelector('.stock-text-input').value;
                    const percent = row.querySelector('.stock-percent-input').value;
                    const isFolded = row.classList.contains('is-folded');
                    
                    let categories = [];
                    let products = [];
                    
                    if (type === 'categories') {
                        const catSelect = row.querySelector('.stock-cats-select');
                        Array.from(catSelect.selectedOptions).forEach(opt => categories.push(opt.value));
                    } else if (type === 'products') {
                        const prodSelect = row.querySelector('.stock-products-select');
                        if (typeof jQuery !== 'undefined') {
                            const selectedData = jQuery(prodSelect).selectWoo('data');
                            selectedData.forEach(item => {
                                products.push({ id: item.id, text: item.text });
                            });
                        }
                    }

                    stockData.push({
                        type: type,
                        categories: categories,
                        products: products,
                        text: text,
                        percent: percent,
                        isFolded: isFolded
                    });
                });
            }

            if(addBtnStock) {
                addBtnStock.addEventListener('click', function(e) {
                    e.preventDefault();
                    syncStockDataFromDOM();
                    stockData.push({
                        type: 'global',
                        categories: [],
                        products: [],
                        text: '',
                        percent: '',
                        isFolded: false
                    });
                    renderStockRows();
                });
            }

            renderStockRows();

            // --- REPEATER ETYKIET TEKSTOWYCH ---
            const containerTextBadges = document.getElementById('blendy-text-badges-container');
            const hiddenJsonInputTextBadges = document.getElementById('blendy_text_badges_json');
            const hiddenJsonInputSvgBadges = document.getElementById('blendy_svg_badges_json');
            const containerSvgBadges = document.getElementById('blendy-svg-badges-container');
            const btnAddSvgBadgeRule = document.getElementById('add-svg-badge-rule');

            const addBtnTextBadges = document.getElementById('add-text-badge-rule');
            
            let textBadgeData = [];
            try {
                textBadgeData = JSON.parse(hiddenJsonInputTextBadges.value) || [];
            } catch(e) { textBadgeData = []; }


            // ==========================================
            // LOGIKA SVG BADGES
            // ==========================================
            let svgBadgeData = [];
            try {
                let rawVal = hiddenJsonInputSvgBadges.value.trim();
                if (rawVal && rawVal !== '[]') {
                    if (rawVal.startsWith('[')) {
                        svgBadgeData = JSON.parse(rawVal) || [];
                    } else {
                        svgBadgeData = JSON.parse(decodeURIComponent(escape(atob(rawVal)))) || [];
                    }
                }
            } catch(e) { svgBadgeData = []; }

            function renderSvgBadgeRows() {
                if (typeof jQuery !== 'undefined' && jQuery.fn.selectWoo) {
                    jQuery(containerSvgBadges).find('.wc-product-search').each(function() {
                        if(jQuery(this).hasClass('select2-hidden-accessible')) {
                            jQuery(this).selectWoo('destroy');
                        }
                    });
                }
                
                containerSvgBadges.innerHTML = '';
                
                svgBadgeData.forEach((rule, index) => {
                    const row = document.createElement('div');
                    row.className = 'blendy-repeater-row svg-badge-row' + (rule.isFolded ? ' is-folded' : '');
                    
                    let headerTitle = rule.text ? 'Odznaka: ' + rule.text : 'Odznaka #' + (index + 1);

                    let iconType = rule.iconType || 'svg';
                    let align = rule.align || 'flex-start';
                    let iconHeightUnit = rule.iconHeightUnit || 'em';
                    let iconHeightVal = rule.iconHeightVal || '1.2';
                    let widthVal = rule.width || '100';
                    let isAutoWidth = rule.widthAuto || false;

                    row.innerHTML = `
                        <h3 class="rule-header">${headerTitle}</h3>
                        <a href="#" class="blendy-remove-btn blendy-remove-row-svg-badge" data-index="${index}">🗑 Usuń Odznakę</a>
                        
                        <div class="blendy-field-group" style="border:none; padding:0; margin-bottom:15px;">
                            <label class="blendy-label">Zastosuj do:</label>
                            <select class="blendy-input-text sb-type-select" data-index="${index}" style="max-width:300px;">
                                <option value="global" ${rule.type === 'global' ? 'selected' : ''}>Wszystkich Produktów (Globalnie)</option>
                                <option value="categories" ${rule.type === 'categories' ? 'selected' : ''}>Wybranych Kategorii</option>
                                <option value="products" ${rule.type === 'products' ? 'selected' : ''}>Konkretnych Produktów</option>
                            </select>
                        </div>
                        
                        <div class="blendy-field-group target-container-categories" style="border:none; padding:0; margin-bottom:15px; ${rule.type === 'categories' ? '' : 'display:none;'}">
                            <label class="blendy-label">Wybierz kategorie:</label>
                            <select class="blendy-input-text sb-cats-select" multiple="multiple" data-index="${index}" style="max-width:600px; height:80px;">
                                ${wpCategories.map(cat => `<option value="${cat.id}" ${(rule.categories || []).includes(cat.id.toString()) ? 'selected' : ''}>${cat.name}</option>`).join('')}
                            </select>
                        </div>

                        <div class="blendy-field-group target-container-products" style="border:none; padding:0; margin-bottom:15px; ${rule.type === 'products' ? '' : 'display:none;'}">
                            <label class="blendy-label">Wyszukaj i wybierz produkty:</label>
                            <select class="wc-product-search sb-products-select" multiple="multiple" style="width: 100%; max-width:600px;" data-placeholder="Szukaj produktów..." data-action="woocommerce_json_search_products_and_variations" data-index="${index}">
                            </select>
                        </div>

                        <div style="display:flex; gap:15px; margin-bottom:15px;">
                            <div class="blendy-field-group" style="flex:2; border:none; padding:0; margin:0;">
                                <label class="blendy-label">Tekst Badge'a</label>
                                <input type="text" class="blendy-input-text sb-text-input" data-index="${index}" value="${rule.text || ''}">
                            </div>
                            <div class="blendy-field-group" style="flex:1; border:none; padding:0; margin:0;">
                                <label class="blendy-label">Typ Ikony</label>
                                <div style="display:flex; gap:10px; flex-wrap:wrap; margin-top:5px;">
                                    <label style="margin:0; display:flex; align-items:center; gap:5px;"><input type="radio" name="sb_icon_type_${index}" value="svg" class="sb-icon-type" data-index="${index}" ${iconType === 'svg' ? 'checked' : ''}> Kod SVG</label>
                                    <label style="margin:0; display:flex; align-items:center; gap:5px;"><input type="radio" name="sb_icon_type_${index}" value="image" class="sb-icon-type" data-index="${index}" ${iconType === 'image' ? 'checked' : ''}> Obrazek</label>
                                </div>
                            </div>
                        </div>

                        <div style="display:flex; gap:15px; margin-bottom:15px;">
                            <div class="blendy-field-group sb-svg-wrapper" style="flex:1; border:none; padding:0; margin:0; ${iconType === 'svg' ? 'display:block;' : 'display:none;'}">
                                <label class="blendy-label">Kod SVG</label>
                                <textarea class="blendy-input-textarea sb-svg-input" data-index="${index}" placeholder="Wklej <svg>...">${rule.svgCode || ''}</textarea>
                            </div>
                            <div class="blendy-field-group sb-img-wrapper" style="flex:1; border:none; padding:0; margin:0; ${iconType === 'image' ? 'display:block;' : 'display:none;'}">
                                <label class="blendy-label">Obrazek Ikony</label>
                                <div class="promo-media-card" style="max-width:300px;">
                                    <div class="promo-preview-box">
                                        ${rule.image ? `<img src="${rule.image}">` : `<span class="dashicons dashicons-format-image"></span>`}
                                    </div>
                                    <input type="hidden" class="sb-img-input" value="${rule.image || ''}">
                                    <div class="promo-actions">
                                        <button type="button" class="button button-secondary button-media-upload sb-btn-img-upload">Wybierz Obrazek</button>
                                        ${rule.image ? `<a href="#" class="btn-remove sb-btn-img-remove">Usuń</a>` : ''}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div style="display:flex; gap:15px; margin-bottom:15px; align-items:flex-start;">
                            <div style="flex:1; display:flex; flex-direction:column; gap:15px;">
                                <div style="display:flex; gap:15px; flex-wrap:wrap;">
                                    <div class="blendy-field-group" style="flex:1; min-width:200px; border:none; padding:0; margin:0;">
                                        <label class="blendy-label">Kolor Tła Badge</label>
                                        <div class="promo-swatches sb-bg-swatches" data-index="${index}">
                                            ${predefinedColors.map(color => `
                                                <div class="promo-swatch ${(rule.bgColor || '#f0f0f1') === color ? 'is-selected' : ''}" style="background: ${color};" data-color="${color}" title="${color}"></div>
                                            `).join('')}
                                            <div class="promo-swatch custom-trigger ${!predefinedColors.includes(rule.bgColor || '#f0f0f1') ? 'is-selected' : ''}" title="Własny CSS">+</div>
                                        </div>
                                        <div class="custom-css-input ${!predefinedColors.includes(rule.bgColor || '#f0f0f1') ? 'is-active' : ''}">
                                            <input type="text" class="blendy-input-text sb-bgcolor-input" data-index="${index}" value="${rule.bgColor || '#f0f0f1'}" placeholder="np. #f0f0f1">
                                        </div>
                                    </div>
                                    <div class="blendy-field-group" style="flex:1; min-width:200px; border:none; padding:0; margin:0;">
                                        <label class="blendy-label">Kolor Tekstu Badge</label>
                                        <div class="promo-swatches sb-text-swatches" data-index="${index}">
                                            ${predefinedColors.map(color => `
                                                <div class="promo-swatch ${(rule.textColor || '#000000') === color ? 'is-selected' : ''}" style="background: ${color};" data-color="${color}" title="${color}"></div>
                                            `).join('')}
                                            <div class="promo-swatch custom-trigger ${!predefinedColors.includes(rule.textColor || '#000000') ? 'is-selected' : ''}" title="Własny CSS">+</div>
                                        </div>
                                        <div class="custom-css-input ${!predefinedColors.includes(rule.textColor || '#000000') ? 'is-active' : ''}">
                                            <input type="text" class="blendy-input-text sb-textcolor-input" data-index="${index}" value="${rule.textColor || '#000000'}" placeholder="np. #000000">
                                        </div>
                                    </div>
                                </div>
                                <div class="blendy-field-group" style="border:none; padding:0; margin:0;">
                                    <label class="blendy-label">Wysokość Ikony</label>
                                    <div style="display:flex; border:1px solid #8c8f94; border-radius:4px; overflow:hidden; height:34px; max-width:200px;">
                                        <input type="number" step="0.1" class="sb-iconheightval-input" data-index="${index}" value="${iconHeightVal}" style="border:none; border-radius:0; flex:1; outline:none; box-shadow:none; padding:0 8px;">
                                        <select class="sb-iconheightunit-select" data-index="${index}" style="border:none; border-left:1px solid #8c8f94; border-radius:0; background:#f6f7f7; padding:0 10px;">
                                            <option value="px" ${iconHeightUnit === 'px' ? 'selected' : ''}>px</option>
                                            <option value="em" ${iconHeightUnit === 'em' ? 'selected' : ''}>em</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div style="flex:1;">
                                <label class="blendy-label">Tło Obrazkowe Badge</label>
                                <div class="promo-media-card" style="max-width:300px;">
                                    <div class="promo-preview-box">
                                        ${rule.bgImage ? `<img src="${rule.bgImage}">` : `<span class="dashicons dashicons-format-image"></span>`}
                                    </div>
                                    <input type="hidden" class="sb-bgimg-input" value="${rule.bgImage || ''}">
                                    <div class="promo-actions">
                                        <button type="button" class="button button-secondary button-media-upload sb-btn-bgimg-upload">Wybierz Tło</button>
                                        ${rule.bgImage ? `<a href="#" class="btn-remove sb-btn-bgimg-remove">Usuń</a>` : ''}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div style="display:flex; gap:15px; margin-bottom:15px; background:#e4f0fa; padding:10px; border-radius:4px;">
                            <div class="blendy-field-group" style="flex:1; border:none; padding:0; margin:0;">
                                <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; margin-bottom:5px;">
                                    <label class="blendy-label" style="margin:0;">Szerokość (%)</label>
                                    <label style="font-weight:normal; font-size:12px; margin:0; display:flex; align-items:center; gap:5px;"><input type="checkbox" class="sb-widthauto-input" data-index="${index}" ${isAutoWidth ? 'checked' : ''}> Auto (fit-content)</label>
                                </div>
                                <div style="display:flex; align-items:center; gap:10px;">
                                    <input type="range" class="sb-width-input" data-index="${index}" min="10" max="100" value="${widthVal}" style="flex:1;">
                                    <span style="font-size:12px; font-weight:bold; width:40px; text-align:right;">${widthVal}%</span>
                                </div>
                            </div>
                            <div class="blendy-field-group" style="flex:1; border:none; padding:0; margin:0;">
                                <label class="blendy-label">Wyrównanie</label>
                                <select class="blendy-input-text sb-align-select" data-index="${index}">
                                    <option value="flex-start" ${align === 'flex-start' ? 'selected' : ''}>Do lewej</option>
                                    <option value="center" ${align === 'center' ? 'selected' : ''}>Do środka</option>
                                    <option value="flex-end" ${align === 'flex-end' ? 'selected' : ''}>Do prawej</option>
                                </select>
                            </div>
                        </div>

                        <div style="display:flex; gap:15px; background:#e4f0fa; padding:10px; border-radius:4px;">
                            <div class="blendy-field-group" style="flex:0.25; border:none; padding:0; margin:0;">
                                <label class="blendy-label">Margin Góra</label>
                                <input type="number" class="blendy-input-text sb-mt-input" data-index="${index}" value="${rule.mt !== undefined ? rule.mt : '12'}">
                            </div>
                            <div class="blendy-field-group" style="flex:0.25; border:none; padding:0; margin:0;">
                                <label class="blendy-label">Margin Dół</label>
                                <input type="number" class="blendy-input-text sb-mb-input" data-index="${index}" value="${rule.mb !== undefined ? rule.mb : '0'}">
                            </div>
                            <div class="blendy-field-group" style="flex:0.25; border:none; padding:0; margin:0;">
                                <label class="blendy-label">Padding Y</label>
                                <input type="number" class="blendy-input-text sb-py-input" data-index="${index}" value="${rule.py !== undefined ? rule.py : '5'}">
                            </div>
                            <div class="blendy-field-group" style="flex:0.25; border:none; padding:0; margin:0;">
                                <label class="blendy-label">Padding X</label>
                                <input type="number" class="blendy-input-text sb-px-input" data-index="${index}" value="${rule.px !== undefined ? rule.px : '10'}">
                            </div>
                        </div>
                    `;
                    containerSvgBadges.appendChild(row);

                    if (rule.type === 'products' && rule.products && rule.products.length > 0) {
                        const selectElement = row.querySelector('.sb-products-select');
                        rule.products.forEach(prod => {
                            const option = new Option(prod.text, prod.id, true, true);
                            selectElement.append(option);
                        });
                    }
                });

                document.querySelectorAll('.blendy-remove-row-svg-badge').forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        if(confirm('Na pewno usunąć tę odznakę?')) {
                            syncSvgBadgeDataFromDOM();
                            svgBadgeData.splice(parseInt(this.dataset.index), 1);
                            renderSvgBadgeRows();
                        }
                    });
                });

                document.querySelectorAll('.sb-type-select').forEach(select => {
                    select.addEventListener('change', function(e) {
                        syncSvgBadgeDataFromDOM();
                        svgBadgeData[parseInt(this.dataset.index)].type = this.value;
                        renderSvgBadgeRows();
                    });
                });
                
                document.querySelectorAll('.sb-icon-type').forEach(radio => {
                    radio.addEventListener('change', function(e) {
                        syncSvgBadgeDataFromDOM();
                        svgBadgeData[parseInt(this.dataset.index)].iconType = this.value;
                        renderSvgBadgeRows();
                    });
                });
                

                containerSvgBadges.querySelectorAll('.promo-swatches').forEach(swatches => {
                    swatches.addEventListener('click', function(e) {
                        const swatch = e.target.closest('.promo-swatch');
                        if (!swatch) return;
                        
                        const container = this.parentElement;
                        const inputField = container.querySelector(this.classList.contains('sb-bg-swatches') ? '.sb-bgcolor-input' : '.sb-textcolor-input');
                        const customInputBox = container.querySelector('.custom-css-input');
                        
                        this.querySelectorAll('.promo-swatch').forEach(s => s.classList.remove('is-selected'));
                        swatch.classList.add('is-selected');

                        if (swatch.classList.contains('custom-trigger')) {
                            customInputBox.classList.add('is-active');
                            inputField.value = '';
                        } else {
                            customInputBox.classList.remove('is-active');
                            inputField.value = swatch.dataset.color;
                        }
                    });
                });

                document.querySelectorAll('.sb-width-input').forEach(slider => {
                    slider.addEventListener('input', function(e) {
                        this.nextElementSibling.innerText = this.value + '%';
                    });
                });

                if (typeof jQuery !== 'undefined' && jQuery.fn.selectWoo) {
                    jQuery(containerSvgBadges).find('.sb-cats-select').selectWoo({ placeholder: 'Wybierz kategorie...' });
                    jQuery(containerSvgBadges).find('.wc-product-search').each(function() {
                        jQuery(this).selectWoo({
                            ajax: {
                                url: ajaxurl,
                                dataType: 'json',
                                delay: 250,
                                data: function(params) {
                                    return {
                                        term: params.term,
                                        action: 'woocommerce_json_search_products_and_variations',
                                        security: window.wc_enhanced_select_params ? window.wc_enhanced_select_params.search_products_nonce : ''
                                    };
                                },
                                processResults: function(data) {
                                    let terms = [];
                                    if (data) {
                                        jQuery.each(data, function(id, text) {
                                            terms.push({ id: id, text: text });
                                        });
                                    }
                                    return { results: terms };
                                },
                                cache: true
                            },
                            minimumInputLength: 3
                        });
                    });
                }
            }

            function syncSvgBadgeDataFromDOM() {
                const rows = document.querySelectorAll('.svg-badge-row');
                svgBadgeData = [];
                
                rows.forEach((row, idx) => {
                    let typeSelect = row.querySelector('.sb-type-select');
                    let ruleType = typeSelect ? typeSelect.value : 'global';
                    let cats = [];
                    let prods = [];
                    
                    if (ruleType === 'categories') {
                        let catSelect = row.querySelector('.sb-cats-select');
                        if (catSelect && typeof jQuery !== 'undefined') {
                            cats = jQuery(catSelect).val() || [];
                        }
                    } else if (ruleType === 'products') {
                        let prodSelect = row.querySelector('.tb-products-select') || row.querySelector('.sb-products-select');
                        if (prodSelect && typeof jQuery !== 'undefined') {
                            let selectedData = jQuery(prodSelect).selectWoo('data');
                            if (selectedData) {
                                prods = selectedData.map(item => ({ id: item.id, text: item.text }));
                            }
                        }
                    }

                    svgBadgeData.push({
                        isFolded: row.classList.contains('is-folded'),
                        type: ruleType,
                        categories: cats,
                        products: prods,
                        text: (row.querySelector('.sb-text-input') ? row.querySelector('.sb-text-input').value : ''),
                        iconType: (row.querySelector('.sb-icon-type:checked') ? row.querySelector('.sb-icon-type:checked').value : 'svg'),
                        svgCode: (row.querySelector('.sb-svg-input') ? row.querySelector('.sb-svg-input').value : ''),
                        image: (row.querySelector('.sb-img-input') ? row.querySelector('.sb-img-input').value : ''),
                        bgColor: (row.querySelector('.sb-bgcolor-input') ? row.querySelector('.sb-bgcolor-input').value : ''),
                        textColor: (row.querySelector('.sb-textcolor-input') ? row.querySelector('.sb-textcolor-input').value : ''),
                        iconHeightVal: (row.querySelector('.sb-iconheightval-input') ? row.querySelector('.sb-iconheightval-input').value : '1.2'),
                        iconHeightUnit: (row.querySelector('.sb-iconheightunit-select') ? row.querySelector('.sb-iconheightunit-select').value : 'em'),
                        bgImage: (row.querySelector('.sb-bgimg-input') ? row.querySelector('.sb-bgimg-input').value : ''),
                        width: (row.querySelector('.sb-width-input') ? row.querySelector('.sb-width-input').value : '100'),
                        widthAuto: (row.querySelector('.sb-widthauto-input') ? row.querySelector('.sb-widthauto-input').checked : false),
                        align: (row.querySelector('.sb-align-select') ? row.querySelector('.sb-align-select').value : 'flex-start'),
                        mt: (row.querySelector('.sb-mt-input') ? row.querySelector('.sb-mt-input').value : '12'),
                        mb: (row.querySelector('.sb-mb-input') ? row.querySelector('.sb-mb-input').value : '0'),
                        py: (row.querySelector('.sb-py-input') ? row.querySelector('.sb-py-input').value : '5'),
                        px: (row.querySelector('.sb-px-input') ? row.querySelector('.sb-px-input').value : '10')
                    });
                });
            }

            if (btnAddSvgBadgeRule) {
                btnAddSvgBadgeRule.addEventListener('click', function(e) {
                    e.preventDefault();
                    syncSvgBadgeDataFromDOM();
                    svgBadgeData.push({
                        isFolded: false, type: 'global', categories: [], products: [],
                        text: '', iconType: 'svg', svgCode: '', image: '',
                        bgColor: '#f0f0f1', textColor: '#000000', iconHeightVal: '1.2', iconHeightUnit: 'em',
                        bgImage: '', width: '100', widthAuto: false, align: 'flex-start',
                        mt: '12', mb: '0', py: '5', px: '10'
                    });
                    renderSvgBadgeRows();
                });
            }

            if (containerSvgBadges) {
                renderSvgBadgeRows();
            }

            function renderTextBadgeRows() {
                if (typeof jQuery !== 'undefined' && jQuery.fn.selectWoo) {
                    jQuery(containerTextBadges).find('.wc-product-search').each(function() {
                        if(jQuery(this).hasClass('select2-hidden-accessible')) {
                            jQuery(this).selectWoo('destroy');
                        }
                    });
                }
                
                containerTextBadges.innerHTML = '';
                
                textBadgeData.forEach((rule, index) => {
                    const row = document.createElement('div');
                    row.className = 'blendy-repeater-row text-badge-row' + (rule.isFolded ? ' is-folded' : '');
                    
                    let isCustomColor = rule.color && !predefinedColors.includes(rule.color);
                    let selectValue = isCustomColor ? 'custom' : (rule.color || predefinedColors[0]);
                    let headerTitle = rule.text ? 'Etykieta: ' + rule.text : 'Reguła #' + (index + 1);

                    row.innerHTML = `
                        <h3 class="rule-header">${headerTitle}</h3>
                        <a href="#" class="blendy-remove-btn blendy-remove-row-text-badge" data-index="${index}">🗑 Usuń Regułę</a>
                        
                        <div class="blendy-field-group" style="border:none; padding:0; margin-bottom:15px;">
                            <label class="blendy-label">Zastosuj do:</label>
                            <select class="blendy-input-text tb-type-select" data-index="${index}" style="max-width:300px;">
                                <option value="global" ${rule.type === 'global' ? 'selected' : ''}>Wszystkich Produktów (Globalnie)</option>
                                <option value="categories" ${rule.type === 'categories' ? 'selected' : ''}>Wybranych Kategorii</option>
                                <option value="products" ${rule.type === 'products' ? 'selected' : ''}>Konkretnych Produktów</option>
                            </select>
                        </div>
                        
                        <div class="blendy-field-group target-container-categories" style="border:none; padding:0; margin-bottom:15px; ${rule.type === 'categories' ? '' : 'display:none;'}">
                            <label class="blendy-label">Wybierz kategorie:</label>
                            <select class="blendy-input-text tb-cats-select" multiple="multiple" data-index="${index}" style="max-width:600px; height:80px;">
                                ${wpCategories.map(cat => `<option value="${cat.id}" ${(rule.categories || []).includes(cat.id.toString()) ? 'selected' : ''}>${cat.name}</option>`).join('')}
                            </select>
                            <span class="blendy-desc">Przytrzymaj CTRL/CMD aby wybrać wiele kategorii.</span>
                        </div>

                        <div class="blendy-field-group target-container-products" style="border:none; padding:0; margin-bottom:15px; ${rule.type === 'products' ? '' : 'display:none;'}">
                            <label class="blendy-label">Wyszukaj i wybierz produkty:</label>
                            <select class="wc-product-search tb-products-select" multiple="multiple" style="width: 100%; max-width:600px;" data-placeholder="Szukaj produktów..." data-action="woocommerce_json_search_products_and_variations" data-index="${index}">
                            </select>
                        </div>
                        
                        <div class="blendy-field-group" style="border:none; padding:0; margin-bottom:15px;">
                            <label class="blendy-label">Tekst Etykiety (np. Bestseller, Nowość)</label>
                            <input type="text" class="blendy-input-text tb-text-input" data-index="${index}" value="${rule.text || ''}" placeholder="Bestseller">
                        </div>

                        <div class="blendy-field-group" style="border:none; padding:0; margin-bottom:15px;">
                            <label class="blendy-label">Kolor Tekstu</label>
                            <select class="blendy-input-text tb-text-color-select" data-index="${index}" style="max-width:300px;">
                                <option value="#ffffff" ${rule.textColor === '#ffffff' ? 'selected' : ''}>Biały</option>
                                <option value="#000000" ${rule.textColor === '#000000' ? 'selected' : ''}>Czarny</option>
                            </select>
                        </div>

                        <div class="blendy-field-group" style="border:none; padding:0; margin-bottom:0;">
                            <label class="blendy-label">Kolor Tła (Kod CSS - Gradient lub Kolor)</label>
                            <div class="promo-swatches" data-index="${index}">
                                ${predefinedColors.map(color => `
                                    <div class="promo-swatch ${selectValue === color ? 'is-selected' : ''}" style="background: ${color};" data-color="${color}" title="${color}"></div>
                                `).join('')}
                                <div class="promo-swatch custom-trigger ${selectValue === 'custom' ? 'is-selected' : ''}" title="Własny CSS">+</div>
                            </div>
                            <div class="custom-css-input ${selectValue === 'custom' ? 'is-active' : ''}">
                                <input type="text" class="blendy-input-text tb-color-input" data-index="${index}" value="${isCustomColor ? rule.color : (selectValue === 'custom' ? '' : selectValue)}" placeholder="Wpisz np. linear-gradient(to right, #ff0000, #00ff00)">
                            </div>
                        </div>
                    `;
                    containerTextBadges.appendChild(row);

                    if (rule.type === 'products' && rule.products && rule.products.length > 0) {
                        const selectElement = row.querySelector('.tb-products-select');
                        rule.products.forEach(prod => {
                            const option = new Option(prod.text, prod.id, true, true);
                            selectElement.append(option);
                        });
                    }
                });

                document.querySelectorAll('.blendy-remove-row-text-badge').forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        if(confirm('Na pewno usunąć tę regułę?')) {
                            syncTextBadgeDataFromDOM();
                            textBadgeData.splice(parseInt(this.dataset.index), 1);
                            renderTextBadgeRows();
                        }
                    });
                });

                document.querySelectorAll('.tb-type-select').forEach(select => {
                    select.addEventListener('change', function(e) {
                        syncTextBadgeDataFromDOM();
                        textBadgeData[parseInt(this.dataset.index)].type = this.value;
                        renderTextBadgeRows();
                    });
                });

                // Obsługa swatchy kolorów dla etykiet tekstowych
                containerTextBadges.querySelectorAll('.promo-swatches').forEach(swatches => {
                    swatches.addEventListener('click', function(e) {
                        const swatch = e.target.closest('.promo-swatch');
                        if (!swatch) return;
                        
                        const container = this.parentElement;
                        const inputField = container.querySelector('.tb-color-input');
                        const customInputBox = container.querySelector('.custom-css-input');
                        
                        this.querySelectorAll('.promo-swatch').forEach(s => s.classList.remove('is-selected'));
                        swatch.classList.add('is-selected');

                        if (swatch.classList.contains('custom-trigger')) {
                            customInputBox.classList.add('is-active');
                            inputField.value = '';
                        } else {
                            customInputBox.classList.remove('is-active');
                            inputField.value = swatch.dataset.color;
                        }
                    });
                });

                if (typeof jQuery !== 'undefined' && jQuery.fn.selectWoo) {
                    jQuery(containerTextBadges).find('.wc-product-search').each(function() {
                        var $select = jQuery(this);
                        $select.selectWoo({
                            minimumInputLength: 3,
                            allowClear: true,
                            ajax: {
                                url: ajaxurl,
                                dataType: 'json',
                                delay: 250,
                                data: function(params) {
                                    return {
                                        term: params.term,
                                        action: $select.data('action') || 'woocommerce_json_search_products_and_variations',
                                        security: typeof wc_enhanced_select_params !== 'undefined' ? wc_enhanced_select_params.search_products_nonce : ''
                                    };
                                },
                                processResults: function(data) {
                                    var terms = [];
                                    if (data) { jQuery.each(data, function(id, text) { terms.push({ id: id, text: text }); }); }
                                    return { results: terms };
                                },
                                cache: true
                            }
                        });
                    });
                }
            }

            function syncTextBadgeDataFromDOM() {
                const rows = document.querySelectorAll('.text-badge-row');
                textBadgeData = [];
                rows.forEach((row, idx) => {
                    const type = row.querySelector('.tb-type-select').value;
                    const text = row.querySelector('.tb-text-input').value;
                    const color = row.querySelector('.tb-color-input').value;
                    const textColor = row.querySelector('.tb-text-color-select').value;
                    const isFolded = row.classList.contains('is-folded');
                    
                    let categories = [];
                    let products = [];
                    
                    if (type === 'categories') {
                        const catSelect = row.querySelector('.tb-cats-select');
                        Array.from(catSelect.selectedOptions).forEach(opt => categories.push(opt.value));
                    } else if (type === 'products') {
                        const prodSelect = row.querySelector('.tb-products-select');
                        if (typeof jQuery !== 'undefined') {
                            const selectedData = jQuery(prodSelect).selectWoo('data');
                            selectedData.forEach(item => {
                                products.push({ id: item.id, text: item.text });
                            });
                        }
                    }

                    textBadgeData.push({ type, categories, products, text, color, textColor, isFolded });
                });
            }

            if(addBtnTextBadges) {
                addBtnTextBadges.addEventListener('click', function(e) {
                    e.preventDefault();
                    syncTextBadgeDataFromDOM();
                    textBadgeData.push({ type: 'global', categories: [], products: [], text: '', color: '', textColor: '#ffffff', isFolded: false });
                    renderTextBadgeRows();
                });
            }

            renderTextBadgeRows();

            // Dynamiczna aktualizacja nagłówków w locie
            form.addEventListener('input', function(e) {
                if (e.target.classList.contains('badge-text-input') || e.target.classList.contains('tb-text-input')) {
                    const row = e.target.closest('.blendy-repeater-row');
                    if(row) {
                        const header = row.querySelector('.rule-header');
                        if(header) {
                            const val = e.target.value.trim();
                            const isTextBadge = row.classList.contains('text-badge-row');
                            const prefix = isTextBadge ? 'Etykieta: ' : 'Reguła: ';
                            header.textContent = val ? prefix + val : 'Reguła';
                        }
                    }
                }
                if (e.target.classList.contains('stock-text-input')) {
                    const row = e.target.closest('.blendy-repeater-row');
                    if(row) {
                        const header = row.querySelector('.rule-header');
                        if(header) {
                            const val = e.target.value.trim();
                            header.textContent = val ? 'Pasek: ' + val : 'Reguła Paska';
                        }
                    }
                }
            });

            // Obsługa zwijania/rozwijania kafelków (folding)
            form.addEventListener('click', function(e) {
                if (e.target.classList.contains('rule-header')) {
                    const row = e.target.closest('.blendy-repeater-row');
                    if (row) {
                        row.classList.toggle('is-folded');
                    }
                }
            });

            // Przed wysłaniem formularza serializuj oba JSON-y
            form.addEventListener('submit', function() {
                try { syncDataFromDOM(); } catch (e) { console.error("Error in syncDataFromDOM", e); }
                try { syncStockDataFromDOM(); } catch (e) { console.error("Error in syncStockDataFromDOM", e); }
                try { syncTextBadgeDataFromDOM(); } catch (e) { console.error("Error in syncTextBadgeDataFromDOM", e); }
                try { syncSvgBadgeDataFromDOM(); } catch (e) { console.error("Error in syncSvgBadgeDataFromDOM", e); }
                
                if (hiddenJsonInput) hiddenJsonInput.value = JSON.stringify(badgeData);
                if (hiddenJsonInputStock) hiddenJsonInputStock.value = JSON.stringify(stockData);
                if (hiddenJsonInputTextBadges) hiddenJsonInputTextBadges.value = JSON.stringify(textBadgeData);
                if (hiddenJsonInputSvgBadges) hiddenJsonInputSvgBadges.value = btoa(unescape(encodeURIComponent(JSON.stringify(svgBadgeData))));
            });
        });
    </script>
    <?php
}

