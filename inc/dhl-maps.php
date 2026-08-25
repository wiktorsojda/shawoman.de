// Placeholder dla kodu DHL MAPS
// 1. Sekcja wyboru i modal mapy na frontendzie CheckoutWC
add_action('cfw_checkout_after_shipping_methods', function () {
    $dhl_logo = defined('PR_DHL_PLUGIN_DIR_URL') ? PR_DHL_PLUGIN_DIR_URL . '/assets/img/dhl-official.png' : '';
    ?>
    <div id="dhl-packstation-section"
        style="margin: 20px 0; padding: 16px; border: 1px solid #e5e7eb; border-radius: 8px; background-color: #f9fafb; font-family: inherit;">
        <div class="registration_info" style="font-size: 13px; color: #4b5563; line-height: 1.5; margin-bottom: 14px;">
            Für Lieferungen an DHL-Packstationen müssen Sie
            <a href="https://www.dhl.de/de/privatkunden/kundenkonto/registrierung.html" target="_blank"
                rel="noopener noreferrer" style="color: #d40511; text-decoration: underline; font-weight: 500;">
                ein DHL-Konto erstellen
            </a>
            und eine Postnummer erhalten.
        </div>

        <div style="display: flex; align-items: center; margin-bottom: 12px;">
            <a data-fancybox id="dhl_parcel_finder" class="button" data-src="#dhl_parcel_finder_form" href="javascript:;"
                style="display: inline-flex !important; align-items: center; justify-content: center; gap: 10px; background-color: #fc0; color: #d40511; border: 1px solid #eab308; padding: 10px 18px; border-radius: 6px; font-weight: 700; font-size: 14px; text-decoration: none; box-shadow: 0 1px 2px rgba(0,0,0,0.05); cursor: pointer; visibility: visible !important; opacity: 1 !important;">
                <span>Suchen Packstation</span>
                <?php if ($dhl_logo): ?>
                    <img src="<?php echo esc_url($dhl_logo); ?>" class="dhl-co-logo" alt="DHL"
                        style="height: 16px; width: auto; display: block;">
                <?php endif; ?>
            </a>
        </div>

        <div id="dhl-postnummer-wrap" style="margin-top: 10px; display: none;">
            <label id="dhl-postnummer-label" style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 4px;">
                DHL Postnummer (Ihre Kundennummer)
            </label>
            <input type="text" id="dhl_custom_postnummer_field" name="dhl_packstation_postnummer_temp"
                placeholder="z.B. 12345678"
                style="width: 100%; max-width: 300px; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;">
        </div>

        <div id="dhl-selected-info"
            style="display: none; padding: 10px 14px; background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 6px; font-size: 13px; color: #1e40af; margin-top: 12px; margin-bottom: 12px; align-items: center; justify-content: space-between;">
            <div><strong>Ausgewählt:</strong> <span id="dhl-selected-text"></span></div>
            <a href="javascript:;" onclick="window.clearDhlSelection()" style="color: #d40511; font-weight: 600; text-decoration: underline; font-size: 12px; cursor: pointer; margin-left: 10px; white-space: nowrap;">Löschen (Zmień)</a>
        </div>
        
        <!-- Ukryte pola do przekazania danych w formularzu CheckoutWC -->
        <input type="hidden" id="dhl_hidden_ps_name" name="dhl_hidden_ps_name" value="">
        <input type="hidden" id="dhl_hidden_ps_zip" name="dhl_hidden_ps_zip" value="">
        <input type="hidden" id="dhl_hidden_ps_city" name="dhl_hidden_ps_city" value="">
    </div>
    <?php
}, 10);

add_action('wp_footer', function () {
    if (!is_checkout()) return;
    ?>
    <style>
        /* --- MODAL (POPUP) --- */
        #dhl_parcel_finder_form { max-width: 1000px !important; width: 95vw !important; padding: 40px !important; border-radius: 16px !important; box-sizing: border-box !important; background-color: #ffffff !important; box-shadow: 0 24px 48px rgba(0,0,0,0.12) !important; font-family: inherit !important; position: relative !important; }
        #dhl_parcel_finder_form::after { content: ""; display: block !important; width: 100% !important; height: 40px !important; margin-top: 24px !important; background: url('http://shav.de/wp-content/uploads/automaty_poziomo_15042026.png') no-repeat center center !important; background-size: contain !important; }
        #dhl_parcel_finder_form h2, #dhl_parcel_finder_form h3 { font-size: 22px !important; font-weight: 600 !important; color: #111111 !important; margin-bottom: 24px !important; text-align: left !important; }
        /* --- FANCYBOX CLOSE BUTTON (NAPRAWA) --- */
        #dhl_parcel_finder_form .fancybox-close-small { position: absolute !important; background-color: #ffffff !important; color: #111111 !important; border-radius: 50% !important; border: 1px solid #EBEBEB !important; box-shadow: 0 2px 8px rgba(0,0,0,0.05) !important; width: 36px !important; height: 36px !important; padding: 0 !important; top: 16px !important; right: 16px !important; display: flex !important; align-items: center !important; justify-content: center !important; transition: background-color 0.2s ease, transform 0.2s ease !important; opacity: 1 !important; z-index: 9999 !important; }
        #dhl_parcel_finder_form .fancybox-close-small svg { fill: currentColor !important; width: 20px !important; height: 20px !important; opacity: 1 !important; }
        #dhl_parcel_finder_form .fancybox-close-small:hover { background-color: #f1f1f1 !important; transform: scale(1.05) !important; }
        /* --- FORMULARZ WYSZUKIWANIA W MODALU --- */
        #dhl_parcel_finder_form form#checkout_dhl_parcel_finder { display: flex !important; flex-wrap: wrap !important; gap: 16px !important; align-items: center !important; margin-bottom: 24px !important; background: #FAFAFA !important; padding: 16px !important; border-radius: 12px !important; border: 1px solid #EBEBEB !important; }
        #dhl_parcel_finder_form form#checkout_dhl_parcel_finder p.form-row { margin: 0 !important; padding: 0 !important; display: flex !important; align-items: center !important; flex: 1 1 auto !important; min-width: 140px !important; }
        #dhl_parcel_finder_form form#checkout_dhl_parcel_finder p.form-row.packstation { min-width: auto !important; flex: 0 0 auto !important; gap: 8px !important; }
        #dhl_parcel_finder_form form#checkout_dhl_parcel_finder p.form-row label { margin: 0 !important; font-size: 14px !important; font-weight: 500 !important; }
        #dhl_parcel_finder_form form#checkout_dhl_parcel_finder input[type="text"] { width: 100% !important; padding: 12px 16px !important; border: 1px solid #D1D1D1 !important; border-radius: 8px !important; font-size: 15px !important; color: #111111 !important; background-color: #ffffff !important; outline: none !important; transition: all 0.2s ease !important; }
        #dhl_parcel_finder_form form#checkout_dhl_parcel_finder input[type="text"]:focus { border-color: #111111 !important; box-shadow: 0 0 0 1px #111111 !important; }
        #dhl_parcel_finder_form input[type="submit"].button { background-color: #111111 !important; color: #ffffff !important; border-radius: 8px !important; padding: 12px 24px !important; border: none !important; font-weight: 600 !important; font-size: 15px !important; cursor: pointer !important; transition: all 0.2s ease !important; width: 100% !important; }
        #dhl_parcel_finder_form input[type="submit"].button:hover { background-color: #333333 !important; transform: translateY(-1px) !important; box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important; }
        /* --- MAPA --- */
        #dhl_parcel_finder_form #map, #dhl_parcel_finder_form .leaflet-container { width: 100% !important; height: 550px !important; border-radius: 12px !important; border: 1px solid #EBEBEB !important; overflow: hidden !important; box-shadow: inset 0 2px 8px rgba(0,0,0,0.03) !important; z-index: 1 !important; }
        .leaflet-bar { border: none !important; box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important; border-radius: 8px !important; overflow: hidden !important; }
        .leaflet-bar a { background-color: #ffffff !important; color: #111111 !important; border-bottom: 1px solid #EBEBEB !important; width: 32px !important; height: 32px !important; line-height: 32px !important; }
        .leaflet-bar a:hover { background-color: #FAFAFA !important; }
        .leaflet-popup-content-wrapper { border-radius: 12px !important; box-shadow: 0 12px 32px rgba(0,0,0,0.15) !important; padding: 8px 4px !important; }
        .leaflet-popup-content { font-family: inherit !important; color: #333333 !important; margin: 16px !important; line-height: 1.6 !important; font-size: 14px !important; min-width: 250px !important; width: auto !important; }
        @media (max-width: 480px) { .leaflet-popup-content { min-width: 200px !important; } }
        .leaflet-popup-content .parcel-title { font-size: 16px !important; font-weight: 700 !important; color: #111111 !important; margin: 0 0 8px 0 !important; }
        .leaflet-popup-content strong, .leaflet-popup-content b { color: #111111 !important; font-weight: 600 !important; }
        .leaflet-popup-content button, .leaflet-popup-content a.pr-dhl-set-location { display: block !important; width: 100% !important; text-align: center !important; margin-top: 20px !important; background-color: #ffcc00 !important; color: #111111 !important; border-radius: 6px !important; padding: 12px 16px !important; border: none !important; font-weight: 600 !important; font-size: 14px !important; text-decoration: none !important; cursor: pointer !important; transition: all 0.2s ease !important; box-shadow: 0 2px 4px rgba(255, 204, 0, 0.2) !important; }
        .leaflet-popup-content button:hover, .leaflet-popup-content a.pr-dhl-set-location:hover { background-color: #e6b800 !important; transform: translateY(-1px) !important; box-shadow: 0 4px 8px rgba(255, 204, 0, 0.3) !important; }
        .leaflet-popup-tip-container { margin-top: -2px !important; }
    </style>

    <script>
        (function () {
            function setDhlCookie(name, value) {
                document.cookie = name + "=" + encodeURIComponent(value || '') + "; path=/; max-age=3600";
            }

            // Odtwarzanie widoku po odświeżeniu AJAX
            window.restoreDhlState = function() {
                var psName = sessionStorage.getItem('dhl_ps_name');
                var psZip = sessionStorage.getItem('dhl_ps_zip');
                var psCity = sessionStorage.getItem('dhl_ps_city');
                var postNummer = sessionStorage.getItem('dhl_postnummer');

                if (psName) {
                    var infoBoxes = document.querySelectorAll('[id="dhl-selected-info"]');
                    var infoTexts = document.querySelectorAll('[id="dhl-selected-text"]');
                    if (infoTexts.length > 0) {
                        infoTexts.forEach(function(el) {
                            el.innerText = psName + ', ' + (psZip ? psZip : '') + ' ' + (psCity ? psCity : '');
                        });
                        infoBoxes.forEach(function(el) {
                            el.style.display = 'flex';
                        });
                    }

                    // Pokaż i dostosuj pole Postnummer w zależności od typu punktu
                    var isPackstation = /Packstation/i.test(psName);
                    var pnWrap = document.getElementById('dhl-postnummer-wrap');
                    var pnLabel = document.getElementById('dhl-postnummer-label');
                    if(pnWrap && pnLabel) {
                        pnWrap.style.display = 'block';
                        if (isPackstation) {
                            pnLabel.innerHTML = 'DHL Postnummer (Ihre Kundennummer) <span style="color:red;">*</span>';
                        } else {
                            pnLabel.innerHTML = 'DHL Postnummer (Optional)';
                            pnField.removeAttribute('required');
                            pnField.removeAttribute('pattern');
                        }
                    }

                    // Aktualizacja ukrytych pól formularza dla CheckoutWC
                    var hidName = document.getElementById('dhl_hidden_ps_name');
                    if(hidName) hidName.value = psName;
                    var hidZip = document.getElementById('dhl_hidden_ps_zip');
                    if(hidZip) hidZip.value = psZip;
                    var hidCity = document.getElementById('dhl_hidden_ps_city');
                    if(hidCity) hidCity.value = psCity;

                    // Próba wypełnienia natywnych pól WooCommerce
                    var wcAddr1 = document.getElementById('shipping_address_1');
                    if(wcAddr1) { wcAddr1.value = psName; wcAddr1.dispatchEvent(new Event('change', {bubbles: true})); }
                    var wcZip = document.getElementById('shipping_postcode');
                    if(wcZip) { wcZip.value = psZip; wcZip.dispatchEvent(new Event('change', {bubbles: true})); }
                    var wcCity = document.getElementById('shipping_city');
                    if(wcCity) { wcCity.value = psCity; wcCity.dispatchEvent(new Event('change', {bubbles: true})); }
                    var wcDiffAddr = document.getElementById('ship-to-different-address-checkbox');
                    if(wcDiffAddr && !wcDiffAddr.checked) { wcDiffAddr.click(); }

                    // KLUZCOWE: Zapisujemy do ciasteczek, by CheckoutWC nie usunął tego przy wysyłce
                    setDhlCookie('dhl_ps_name', psName);
                    setDhlCookie('dhl_ps_zip', psZip);
                    setDhlCookie('dhl_ps_city', psCity);
                }

                if (postNummer) {
                    var pnFields = document.querySelectorAll('[id="dhl_custom_postnummer_field"]');
                    pnFields.forEach(function(el) {
                        if (!el.value) el.value = postNummer;
                    });
                    
                    var wcAddr2 = document.getElementById('shipping_address_2');
                    if(wcAddr2) { wcAddr2.value = postNummer; wcAddr2.dispatchEvent(new Event('change', {bubbles: true})); }

                    setDhlCookie('dhl_postnummer', postNummer);
                }
            };

            // Czyszczenie wyboru paczkomatu
            window.clearDhlSelection = function() {
                sessionStorage.removeItem('dhl_ps_name');
                sessionStorage.removeItem('dhl_ps_zip');
                sessionStorage.removeItem('dhl_ps_city');
                sessionStorage.removeItem('dhl_postnummer');
                setDhlCookie('dhl_ps_name', '');
                setDhlCookie('dhl_ps_zip', '');
                setDhlCookie('dhl_ps_city', '');
                setDhlCookie('dhl_postnummer', '');
                
                var infoBoxes = document.querySelectorAll('[id="dhl-selected-info"]');
                infoBoxes.forEach(function(el) { el.style.display = 'none'; });
                
                var pnFields = document.querySelectorAll('[id="dhl_custom_postnummer_field"]');
                pnFields.forEach(function(el) { el.value = ''; });
                
                var pnWrap = document.getElementById('dhl-postnummer-wrap');
                if(pnWrap) pnWrap.style.display = 'none';

                var hidName = document.getElementById('dhl_hidden_ps_name');
                if(hidName) hidName.value = '';
                var hidZip = document.getElementById('dhl_hidden_ps_zip');
                if(hidZip) hidZip.value = '';
                var hidCity = document.getElementById('dhl_hidden_ps_city');
                if(hidCity) hidCity.value = '';
            };

            // Delegacja dla głównego przycisku otwierającego mapę (w razie gdy AJAX usunie eventy)
            document.addEventListener('click', function (e) {
                var btn = e.target.closest('#dhl_parcel_finder');
                if (btn) {
                    e.preventDefault();
                    if (typeof jQuery !== 'undefined' && jQuery.fancybox) {
                        jQuery.fancybox.open({
                            src: btn.getAttribute('data-src'),
                            type: 'inline'
                        });
                    }
                }
            });

            // Delegacja dla przycisku wyboru w Google Maps / Leaflet
            document.addEventListener('click', function (e) {
                var popupBtn = e.target.closest('.leaflet-popup button, .leaflet-popup a, .pr-dhl-set-location, [data-location-type], .gm-style-iw button, .gm-style-iw a');
                if (popupBtn) {
                    var popupContent = popupBtn.closest('.leaflet-popup-content, .gm-style-iw, .gm-style-iw-d, .gm-style-iw-c');
                    
                    if (!popupContent) {
                        popupContent = popupBtn.parentElement;
                        while (popupContent && popupContent !== document.body) {
                            var text = popupContent.innerText || popupContent.textContent;
                            if (/\b\d{5}\b/.test(text) && /(Packstation|Filiale|Paketshop)/i.test(text)) {
                                break;
                            }
                            popupContent = popupContent.parentElement;
                        }
                    }

                    if (popupContent && popupContent !== document.body) {
                        var fullText = popupContent.innerText || popupContent.textContent;

                        var packMatch = fullText.match(/(Packstation\s*\d+|Filiale\s*\d+|Paketshop\s*\d+)/i);
                        var postCodeMatch = fullText.match(/\b\d{5}\b/);

                        var cityClean = '';
                        var cityLineMatch = fullText.match(/\b\d{5}\s+([^\n\r]+)/);
                        if (cityLineMatch && cityLineMatch[1]) {
                            cityClean = cityLineMatch[1].replace(/(ÖFFNUNGSZEITEN|GODZINY|SERVICES|USŁUGI|Öffnungszeiten|Services).*/i, '').trim();
                        }

                        if (packMatch) {
                            var psName = packMatch[0];
                            var psZip = postCodeMatch ? postCodeMatch[0] : '';

                            sessionStorage.setItem('dhl_ps_name', psName);
                            sessionStorage.setItem('dhl_ps_zip', psZip);
                            sessionStorage.setItem('dhl_ps_city', cityClean);
                            
                            window.restoreDhlState();

                            if (typeof jQuery !== 'undefined' && jQuery.fancybox) {
                                jQuery.fancybox.close();
                            }
                        }
                    }
                }
            });

            // Delegacja na pole Postnummer (przetrwa AJAX)
            document.addEventListener('input', function (e) {
                if (e.target && e.target.id === 'dhl_custom_postnummer_field') {
                    var val = e.target.value;
                    sessionStorage.setItem('dhl_postnummer', val);
                    setDhlCookie('dhl_postnummer', val);
                }
            });

            // Reaktywacja po odświeżeniu CheckoutWC
            if (typeof jQuery !== 'undefined') {
                jQuery(document.body).on('updated_checkout cfw_updated_checkout', function() {
                    window.restoreDhlState();
                });
            }

            // Inicjalizacja przy pierwszym załadowaniu
            window.restoreDhlState();
        })();
    </script>
    <?php
}, 10);

// 2. Backend: Generowanie czystego adresu dostawy w zamówieniu na podstawie COOKIES i ukrytych pól
add_action('woocommerce_checkout_create_order', function ($order, $data) {
    // Odczyt z POST (ukryte pola), fallback do ciasteczek - priorytet 9999 by nadpisać CheckoutWC
    $ps_name = isset($_POST['dhl_hidden_ps_name']) && !empty($_POST['dhl_hidden_ps_name']) ? sanitize_text_field(wp_unslash($_POST['dhl_hidden_ps_name'])) : (isset($_COOKIE['dhl_ps_name']) ? sanitize_text_field(wp_unslash(urldecode($_COOKIE['dhl_ps_name']))) : '');
    
    // Postnummer może być z naszego customowego pola lub z ciastka
    $postnummer_post = isset($_POST['dhl_packstation_postnummer_temp']) ? sanitize_text_field(wp_unslash($_POST['dhl_packstation_postnummer_temp'])) : '';
    $postnummer = !empty($postnummer_post) ? $postnummer_post : (isset($_COOKIE['dhl_postnummer']) ? sanitize_text_field(wp_unslash(urldecode($_COOKIE['dhl_postnummer']))) : '');
    
    $ps_zip = isset($_POST['dhl_hidden_ps_zip']) && !empty($_POST['dhl_hidden_ps_zip']) ? sanitize_text_field(wp_unslash($_POST['dhl_hidden_ps_zip'])) : (isset($_COOKIE['dhl_ps_zip']) ? sanitize_text_field(wp_unslash(urldecode($_COOKIE['dhl_ps_zip']))) : '');
    $ps_city = isset($_POST['dhl_hidden_ps_city']) && !empty($_POST['dhl_hidden_ps_city']) ? sanitize_text_field(wp_unslash($_POST['dhl_hidden_ps_city'])) : (isset($_COOKIE['dhl_ps_city']) ? sanitize_text_field(wp_unslash(urldecode($_COOKIE['dhl_ps_city']))) : '');
    
    // Sprawdzamy czy to w ogóle jest Paczkomat (Packstation, Filiale, etc)
    if (preg_match('/(Packstation|Filiale|Paketshop)/i', $ps_name)) {
        $order->set_shipping_address_1($ps_name);
        $order->set_shipping_address_2($postnummer);
        
        if (!empty($ps_zip)) {
            $order->set_shipping_postcode($ps_zip);
        }
        if (!empty($ps_city)) {
            $order->set_shipping_city($ps_city);
        }
        $order->set_shipping_company('');
        
        $order->update_meta_data('_shipping_dhl_address_type', 'packstation');
        $order->update_meta_data('_shipping_dhl_postnum', $postnummer);
        $order->update_meta_data('shipping_dhl_postnum', $postnummer);
        $order->update_meta_data('_shipping_postnummer', $postnummer);
        $order->update_meta_data('shipping_postnummer', $postnummer);
        
        // Czyścimy ciasteczka, by nie nałożyły się na kolejne zamówienie
        setcookie('dhl_ps_name', '', time() - 3600, '/');
        setcookie('dhl_postnummer', '', time() - 3600, '/');
        setcookie('dhl_ps_zip', '', time() - 3600, '/');
        setcookie('dhl_ps_city', '', time() - 3600, '/');
    }
}, 9999, 2);

// 3. Walidacja w koszyku: Wymuszaj Postnummer tylko dla Packstation
add_action('woocommerce_checkout_process', function () {
    $ps_name = isset($_POST['dhl_hidden_ps_name']) ? sanitize_text_field(wp_unslash($_POST['dhl_hidden_ps_name'])) : '';
    $postnummer = isset($_POST['dhl_packstation_postnummer_temp']) ? sanitize_text_field(wp_unslash($_POST['dhl_packstation_postnummer_temp'])) : '';
    
    if (stripos($ps_name, 'Packstation') !== false) {
        if (empty($postnummer)) {
            wc_add_notice('Für die Lieferung an eine <strong>DHL Packstation</strong> ist eine DHL Postnummer erforderlich.', 'error');
        } elseif (!preg_match('/^\d{6,10}$/', preg_replace('/\s+/', '', $postnummer))) {
            wc_add_notice('Ihre <strong>DHL Postnummer</strong> ist ungültig. Bitte prüfen Sie Ihre Eingabe (z.B. 12345678).', 'error');
        }
    }
});