<?php
/**
 * Shav Woman - Product FAQ Repeater
 * Dodaje dynamiczną zakładkę z FAQ w edycji produktu WooCommerce
 * oraz renderuje klasyczny akordeon na stronie produktu.
 */

defined('ABSPATH') || exit;

// =============================================================================
// 1. ZAKŁADKA W EDYCJI PRODUKTU WOOCOMMERCE
// =============================================================================

// Dodanie tabu w menu "Dane produktu"
add_filter('woocommerce_product_data_tabs', 'shav_faq_product_data_tab');
function shav_faq_product_data_tab($tabs) {
    $tabs['shav_faq'] = [
        'label'  => 'Shav FAQ',
        'target' => 'shav_faq_product_data',
        'class'  => ['show_if_simple', 'show_if_variable'],
    ];
    return $tabs;
}

// Renderowanie zawartości panelu
add_action('woocommerce_product_data_panels', 'shav_faq_product_data_panel');
function shav_faq_product_data_panel() {
    global $post;
    
    // Pobranie zapisanych reguł (zdekodowanie, jeśli to możliwe)
    $faq_json = get_post_meta($post->ID, '_shav_faq_json', true);
    if (empty($faq_json)) {
        $faq_json = '[]';
    }
    
    // Bezpieczne wstrzyknięcie JSON-a do JS
    ?>
    <div id="shav_faq_product_data" class="panel woocommerce_options_panel hidden">
        <div class="options_group">
            <p class="form-field">
                <strong>Dynamiczne FAQ dla produktu</strong><br>
                Dodawaj pytania i odpowiedzi. Wyświetlą się na karcie produktu w formie zwijanego akordeonu.
            </p>
            
            <div id="shav-faq-repeater-container" style="padding: 0 12px 10px;">
                <div id="shav-faq-rows" style="display: flex; flex-direction: column; gap: 15px; margin-bottom: 15px;">
                    <!-- Rows will be injected here -->
                </div>
                <button type="button" class="button button-primary" id="add-shav-faq-row">+ Dodaj nowe pytanie</button>
            </div>
            
            <!-- Ukryte pole przetrzymujące dane w Base64 dla bezpieczeństwa zapisu -->
            <input type="hidden" name="shav_faq_json" id="shav_faq_json" value="<?php echo esc_attr($faq_json); ?>">
        </div>

        <script>
            jQuery(document).ready(function($) {
                const container = document.getElementById('shav-faq-rows');
                const addBtn = document.getElementById('add-shav-faq-row');
                const hiddenInput = document.getElementById('shav_faq_json');
                
                let faqData = [];
                
                // Próba odczytu zapisanych danych
                try {
                    const rawVal = hiddenInput.value.trim();
                    if (rawVal) {
                        if (rawVal.startsWith('[')) {
                            faqData = JSON.parse(rawVal);
                        } else {
                            // base64 decode
                            faqData = JSON.parse(decodeURIComponent(escape(atob(rawVal)))) || [];
                        }
                    }
                } catch(e) {
                    console.error("Błąd parsowania FAQ JSON: ", e);
                    faqData = [];
                }
                
                function renderRows() {
                    container.innerHTML = '';
                    faqData.forEach((row, index) => {
                        const rowHtml = `
                            <div class="shav-faq-row" style="background: #f8f8f8; border: 1px solid #ccc; padding: 15px; border-radius: 4px; position: relative; margin-bottom: 15px; clear: both; overflow: hidden;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; clear: both; float: none;">
                                    <strong style="float: none; width: auto; margin: 0; padding: 0;">Pytanie #${index+1}</strong>
                                    <a href="#" class="shav-faq-remove" data-index="${index}" style="color: red; text-decoration: none; font-weight: bold; float: none; margin: 0; padding: 0;">Usuń &times;</a>
                                </div>
                                
                                <div style="margin-bottom: 15px; clear: both; float: none;">
                                    <label style="display:block; margin-bottom: 5px; font-weight: 600; float: none; width: auto; text-align: left; padding: 0;">Pytanie:</label>
                                    <input type="text" class="faq-question" data-index="${index}" value="${row.question ? row.question.replace(/"/g, '&quot;') : ''}" style="width: 100%; float: none; display: block; margin: 0; padding: 8px;">
                                </div>
                                
                                <div style="margin-bottom: 15px; clear: both; float: none;">
                                    <label style="display:block; margin-bottom: 5px; font-weight: 600; float: none; width: auto; text-align: left; padding: 0;">Odpowiedź:</label>
                                    <textarea class="faq-answer" data-index="${index}" style="width: 100%; height: 80px; float: none; display: block; margin: 0; padding: 8px;">${row.answer || ''}</textarea>
                                </div>
                                
                                <div style="margin-bottom: 0; clear: both; float: none;">
                                    <label style="display:block; margin-bottom: 5px; font-weight: 600; float: none; width: auto; text-align: left; padding: 0;">Zdjęcie (opcjonalne):</label>
                                    <div style="display:flex; gap: 10px; align-items: center; float: none; clear: both;">
                                        <input type="text" class="faq-image" data-index="${index}" value="${row.image ? row.image.replace(/"/g, '&quot;') : ''}" style="width: 80%; float: none; display: block; margin: 0; padding: 8px;" placeholder="URL obrazka (np. https://...)">
                                        <button type="button" class="button shav-upload-img" data-index="${index}" style="float: none; margin: 0;">Wybierz</button>
                                    </div>
                                </div>
                            </div>
                        `;
                        container.insertAdjacentHTML('beforeend', rowHtml);
                    });
                    
                    // Podpięcie zdarzeń usuwania
                    container.querySelectorAll('.shav-faq-remove').forEach(btn => {
                        btn.addEventListener('click', function(e) {
                            e.preventDefault();
                            const idx = parseInt(this.getAttribute('data-index'), 10);
                            syncData();
                            faqData.splice(idx, 1);
                            renderRows();
                        });
                    });
                    
                    // Podpięcie biblioteki mediów
                    container.querySelectorAll('.shav-upload-img').forEach(btn => {
                        btn.addEventListener('click', function(e) {
                            e.preventDefault();
                            const inputField = this.previousElementSibling;
                            
                            const frame = wp.media({
                                title: 'Wybierz zdjęcie do FAQ',
                                button: { text: 'Wybierz' },
                                multiple: false
                            });
                            
                            frame.on('select', function() {
                                const attachment = frame.state().get('selection').first().toJSON();
                                inputField.value = attachment.url;
                                syncData();
                            });
                            
                            frame.open();
                        });
                    });
                }
                
                function syncData() {
                    const rows = container.querySelectorAll('.shav-faq-row');
                    const newData = [];
                    rows.forEach(row => {
                        const q = row.querySelector('.faq-question').value;
                        const a = row.querySelector('.faq-answer').value;
                        const img = row.querySelector('.faq-image').value;
                        if (q.trim() !== '' || a.trim() !== '' || img.trim() !== '') {
                            newData.push({ question: q, answer: a, image: img });
                        }
                    });
                    faqData = newData;
                    // Zapis jako base64, żeby ominąć problemy z backslashami w WP
                    hiddenInput.value = btoa(unescape(encodeURIComponent(JSON.stringify(faqData))));
                }
                
                // Dodawanie nowego pytania
                addBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    syncData();
                    faqData.push({ question: '', answer: '' });
                    renderRows();
                });
                
                // Zapisz dane przy wysyłaniu formularza
                $('#post').on('submit', function() {
                    syncData();
                });
                
                // Initial render
                renderRows();
            });
        </script>
    </div>
    <?php
}

// Zapisywanie meta danych (JSON base64)
add_action('woocommerce_process_product_meta', 'shav_save_faq_product_data');
function shav_save_faq_product_data($post_id) {
    if (isset($_POST['shav_faq_json'])) {
        update_post_meta($post_id, '_shav_faq_json', sanitize_text_field($_POST['shav_faq_json']));
    }
}


// =============================================================================
// 2. WYŚWIETLANIE NA FRONCIE (Karta Produktu)
// =============================================================================

function display_product_faq() {
    global $post;
    if (!$post) return;
    
    $product = wc_get_product($post->ID);
    if (!$product) return;
    
    $faq_json = get_post_meta($post->ID, '_shav_faq_json', true);
    if (empty($faq_json) || $faq_json === '[]') return;
    
    $faq_data = [];
    if (strpos($faq_json, '[') === 0) {
        $faq_data = json_decode($faq_json, true);
    } else {
        $decoded = base64_decode($faq_json);
        if ($decoded) {
            $faq_data = json_decode($decoded, true);
        }
    }
    
    if (empty($faq_data) || !is_array($faq_data)) return;
    
    echo '<div class="shav-product-faq" style="margin: 20px 0; border: 1px solid #EAEAEA; border-radius: 8px; padding: 15px;">';
    echo '<h3 class="shav-faq-heading" style="font-size: 16px; font-weight: 600; margin-bottom: 15px; color: #3F3F3F;">Alles, was du wissen musst</h3>';
    
    foreach ($faq_data as $item) {
        if (empty($item['question'])) continue;
        
        $question = wp_kses_post($item['question']);
        $answer = $item['answer'];
        
        // Zabezpieczamy HTML, ale pozwalamy na złożone tagi
        $answer = wp_kses_post($answer);
        
        // Jeśli tekst nie zawiera ewidentnego tagu blokowego (np. table, div), to dodajemy auto-akapity (wpautop).
        // W przeciwnym razie wypluwamy czysty HTML, żeby wpautop nie rozwalił nam tabel.
        if (stripos($answer, '<table') === false && stripos($answer, '<div') === false) {
            $answer = wpautop($answer);
        }
        
        // Dodatkowo uruchamiamy shortcode'y (gdyby użyto wtyczki do tabel lub czegoś innego)
        $answer = do_shortcode($answer);
        
        echo '<div class="shav-faq-item" style="background: #F2F2F2; border-radius: 8px; margin-bottom: 10px; overflow: hidden;">';
            echo '<div class="shav-faq-header" onclick="shavToggleFaq(this)" style="display: flex; justify-content: space-between; align-items: center; cursor: pointer; padding: 15px 20px;">';
                echo '<span class="shav-faq-question" style="font-size: 15px; font-weight: 500; color: #3F3F3F;">' . $question . '</span>';
                // Ikona SVG plusa
                echo '<svg class="shav-faq-icon shav-faq-plus" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" style="transition: transform 0.3s ease; flex-shrink: 0; margin-left: 10px;">';
                    echo '<path class="h-line" d="M4 10h12" stroke="#3F3F3F" stroke-width="2" stroke-linecap="round"/>';
                    echo '<path class="v-line" d="M10 4v12" stroke="#3F3F3F" stroke-width="2" stroke-linecap="round"/>';
                echo '</svg>';
            echo '</div>';
            echo '<div class="shav-faq-content" style="display: none; padding: 0 20px 20px 20px; font-size: 14px; color: #555; line-height: 1.5;">';
                echo $answer;
                if (!empty($item['image'])) {
                    echo '<img src="' . esc_url($item['image']) . '" alt="FAQ Image" style="max-width: 100%; height: auto; border-radius: 8px; margin-top: 15px; display: block;">';
                }
            echo '</div>';
        echo '</div>';
    }
    echo '</div>';
}
// Podpięcie pod sekcją koszyka/akordeonów z boku
add_action('woocommerce_share', 'display_product_faq', 19);

// Skrypt obsługujący rozwijanie
add_action('wp_footer', 'shav_faq_scripts');
function shav_faq_scripts() {
    if (!is_product()) return;
    ?>
    <script>
        function shavToggleFaq(header) {
            const content = header.nextElementSibling;
            const icon = header.querySelector('.shav-faq-icon');
            const vLine = icon.querySelector('.v-line');
            
            const isOpen = content.style.display !== 'none' && content.style.display !== '';
            
            if (isOpen) {
                content.style.display = 'none';
                vLine.style.opacity = '1';
                icon.style.transform = 'rotate(0deg)';
            } else {
                content.style.display = 'block';
                vLine.style.opacity = '0';
                icon.style.transform = 'rotate(180deg)';
            }
        }
    </script>
    <?php
}
