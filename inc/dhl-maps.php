<?php
// Placeholder dla kodu DHL MAPS
// 1. Sekcja wyboru i modal mapy na frontendzie CheckoutWC
add_action('cfw_checkout_after_shipping_methods', function () {
    $dhl_logo = 'http://shav.de/wp-content/uploads/automaty_poziomo_15042026.png';
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
                style="display: inline-flex; align-items: center; justify-content: center; gap: 10px; background-color: #fc0; color: #d40511; border: 1px solid #eab308; padding: 10px 18px; border-radius: 6px; font-weight: 700; font-size: 14px; text-decoration: none; box-shadow: 0 1px 2px rgba(0,0,0,0.05); cursor: pointer;">
                <span>Suchen Packstation</span>
                <?php if ($dhl_logo): ?>
                    <img src="<?php echo esc_url($dhl_logo); ?>" class="dhl-co-logo" alt="DHL"
                        style="height: 16px; width: auto; display: block;">
                <?php endif; ?>
            </a>
        </div>

        <div id="dhl-selected-info"
            style="display: none; padding: 10px 14px; background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 6px; font-size: 13px; color: #1e40af; margin-bottom: 12px;">
            <strong>Ausgewählt:</strong> <span id="dhl-selected-text"></span>
        </div>

        <div id="dhl-postnummer-wrap" style="margin-top: 10px;">
            <label style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 4px;">
                DHL Postnummer (Ihre Kundennummer) *
            </label>
            <input type="text" id="dhl_custom_postnummer_field" name="dhl_packstation_postnummer"
                placeholder="z.B. 12345678"
                style="width: 100%; max-width: 300px; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;">
        </div>

        <!-- Ukryte pola przesyłające dane wybranego punktu do serwera -->
        <input type="hidden" id="dhl_ps_name" name="dhl_ps_name" value="">
        <input type="hidden" id="dhl_ps_zip" name="dhl_ps_zip" value="">
        <input type="hidden" id="dhl_ps_city" name="dhl_ps_city" value="">
    </div>

    <style>
        /* --- MODAL (POPUP) --- */
        #dhl_parcel_finder_form {
            max-width: 1000px !important;
            width: 95vw !important;
            padding: 24px !important;
            border-radius: 16px !important;
            box-sizing: border-box !important;
            background-color: #ffffff !important;
            box-shadow: 0 24px 48px rgba(0,0,0,0.12) !important;
            font-family: inherit !important;
            position: relative !important;
        }
        
        /* LOGO POD MAPĄ (CSS injection) */
        #dhl_parcel_finder_form::after {
            content: "";
            display: block !important;
            width: 100% !important;
            height: 32px !important;
            margin-top: 16px !important;
            background: url('http://shav.de/wp-content/uploads/automaty_poziomo_15042026.png') no-repeat center center !important;
            background-size: contain !important;
        }
        
        /* Tytuł modala jeśli generuje go DHL */
        #dhl_parcel_finder_form h2, 
        #dhl_parcel_finder_form h3 {
            font-size: 22px !important;
            font-weight: 600 !important;
            color: #111111 !important;
            margin-bottom: 24px !important;
            text-align: left !important;
        }

        /* --- FANCYBOX CLOSE BUTTON (NAPRAWA) --- */
        #dhl_parcel_finder_form .fancybox-close-small {
            position: absolute !important;
            background-color: #ffffff !important;
            color: #111111 !important;
            border-radius: 50% !important;
            border: 1px solid #EBEBEB !important;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05) !important;
            width: 36px !important;
            height: 36px !important;
            padding: 0 !important;
            top: 16px !important;
            right: 16px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            transition: background-color 0.2s ease, transform 0.2s ease !important;
            opacity: 1 !important;
            z-index: 9999 !important;
        }
        #dhl_parcel_finder_form .fancybox-close-small svg {
            fill: currentColor !important;
            width: 20px !important;
            height: 20px !important;
            opacity: 1 !important;
        }
        #dhl_parcel_finder_form .fancybox-close-small:hover {
            background-color: #f1f1f1 !important;
            transform: scale(1.05) !important;
        }

        /* --- FORMULARZ WYSZUKIWANIA W MODALU --- */
        #dhl_parcel_finder_form form#checkout_dhl_parcel_finder {
            display: flex !important;
            flex-wrap: wrap !important;
            gap: 16px !important;
            align-items: center !important;
            margin-bottom: 24px !important;
            background: #FAFAFA !important;
            padding: 16px !important;
            border-radius: 12px !important;
            border: 1px solid #EBEBEB !important;
        }

        #dhl_parcel_finder_form form#checkout_dhl_parcel_finder p.form-row {
            margin: 0 !important;
            padding: 0 !important;
            display: flex !important;
            align-items: center !important;
            flex: 1 1 auto !important;
            min-width: 140px !important;
        }

        #dhl_parcel_finder_form form#checkout_dhl_parcel_finder p.form-row.packstation {
            min-width: auto !important;
            flex: 0 0 auto !important;
            gap: 8px !important;
        }
        
        #dhl_parcel_finder_form form#checkout_dhl_parcel_finder p.form-row label {
            margin: 0 !important;
            font-size: 14px !important;
            font-weight: 500 !important;
        }

        #dhl_parcel_finder_form form#checkout_dhl_parcel_finder input[type="text"] {
            width: 100% !important;
            padding: 12px 16px !important;
            border: 1px solid #D1D1D1 !important;
            border-radius: 8px !important;
            font-size: 15px !important;
            color: #111111 !important;
            background-color: #ffffff !important;
            outline: none !important;
            transition: all 0.2s ease !important;
        }

        #dhl_parcel_finder_form form#checkout_dhl_parcel_finder input[type="text"]:focus {
            border-color: #111111 !important;
            box-shadow: 0 0 0 1px #111111 !important;
        }

        /* Przycisk Szukaj (tylko input type=submit) */
        #dhl_parcel_finder_form input[type="submit"].button {
            background-color: #111111 !important;
            color: #ffffff !important;
            border-radius: 8px !important;
            padding: 12px 24px !important;
            border: none !important;
            font-weight: 600 !important;
            font-size: 15px !important;
            cursor: pointer !important;
            transition: all 0.2s ease !important;
            width: 100% !important;
        }

        #dhl_parcel_finder_form input[type="submit"].button:hover {
            background-color: #333333 !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;
        }

        /* --- MAPA LEAFLET --- */
        #dhl_parcel_finder_form #map,
        #dhl_parcel_finder_form .leaflet-container {
            width: 100% !important;
            height: 50vh !important;
            min-height: 350px !important;
            max-height: 500px !important;
            border-radius: 12px !important;
            border: 1px solid #EBEBEB !important;
            overflow: hidden !important;
            box-shadow: inset 0 2px 8px rgba(0,0,0,0.03) !important;
            z-index: 1 !important;
        }

        /* --- KONTROLKI MAPY (Zoom itp.) --- */
        .leaflet-bar {
            border: none !important;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;
            border-radius: 8px !important;
            overflow: hidden !important;
        }
        .leaflet-bar a {
            background-color: #ffffff !important;
            color: #111111 !important;
            border-bottom: 1px solid #EBEBEB !important;
            width: 32px !important;
            height: 32px !important;
            line-height: 32px !important;
        }
        .leaflet-bar a:hover {
            background-color: #FAFAFA !important;
        }
        
        /* --- POPUP WEWNĄTRZ MAPY (Leaflet Popup) --- */
        .leaflet-popup-content-wrapper {
            border-radius: 12px !important;
            box-shadow: 0 12px 32px rgba(0,0,0,0.15) !important;
            padding: 8px 4px !important;
        }
        .leaflet-popup-content {
            font-family: inherit !important;
            color: #333333 !important;
            margin: 16px !important;
            line-height: 1.6 !important;
            font-size: 14px !important;
            min-width: 250px !important; /* Nadpisuje inline style z Leaflet (ok. 172px) dając oddech */
            width: auto !important;
        }
        @media (max-width: 480px) {
            .leaflet-popup-content {
                min-width: 200px !important;
            }
        }
        .leaflet-popup-content .parcel-title {
            font-size: 16px !important;
            font-weight: 700 !important;
            color: #111111 !important;
            margin: 0 0 8px 0 !important;
        }
        .leaflet-popup-content strong, 
        .leaflet-popup-content b {
            color: #111111 !important;
            font-weight: 600 !important;
        }
        /* Przycisk wyboru wewnątrz popupu mapy */
        .leaflet-popup-content button,
        .leaflet-popup-content a.pr-dhl-set-location {
            display: block !important;
            width: 100% !important;
            text-align: center !important;
            margin-top: 20px !important;
            background-color: #ffcc00 !important; /* DHL Yellow dla akcji na mapie */
            color: #111111 !important;
            border-radius: 6px !important;
            padding: 12px 16px !important;
            border: none !important;
            font-weight: 600 !important;
            font-size: 14px !important;
            text-decoration: none !important;
            cursor: pointer !important;
            transition: all 0.2s ease !important;
            box-shadow: 0 2px 4px rgba(255, 204, 0, 0.2) !important;
        }
        .leaflet-popup-content button:hover,
        .leaflet-popup-content a.pr-dhl-set-location:hover {
            background-color: #e6b800 !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 4px 8px rgba(255, 204, 0, 0.3) !important;
        }
        .leaflet-popup-tip-container {
            margin-top: -2px !important;
        }
    </style>

    <script>
        (function () {
            document.addEventListener('click', function (e) {
                var popupBtn = e.target.closest('.leaflet-popup button, .leaflet-popup a, .pr-dhl-set-location, [data-location-type]');
                if (popupBtn) {
                    var popupContent = popupBtn.closest('.leaflet-popup-content');
                    if (popupContent) {
                        var fullText = popupContent.innerText || popupContent.textContent;

                        var packMatch = fullText.match(/(Packstation\s*\d+|Filiale\s*\d+)/i);
                        var postCodeMatch = fullText.match(/\b\d{5}\b/);

                        // Precyzyjne wyciągnięcie nazwy miasta (zatrzymanie przed słowami kluczowymi godzin otwarcia)
                        var cityClean = '';
                        var cityLineMatch = fullText.match(/\b\d{5}\s+([^\n\r]+)/);
                        if (cityLineMatch && cityLineMatch[1]) {
                            cityClean = cityLineMatch[1].replace(/(ÖFFNUNGSZEITEN|GODZINY|SERVICES|USŁUGI|Öffnungszeiten|Services).*/i, '').trim();
                        }

                        if (packMatch) document.getElementById('dhl_ps_name').value = packMatch[0];
                        if (postCodeMatch) document.getElementById('dhl_ps_zip').value = postCodeMatch[0];
                        if (cityClean) document.getElementById('dhl_ps_city').value = cityClean;

                        var infoBox = document.getElementById('dhl-selected-info');
                        var infoText = document.getElementById('dhl-selected-text');
                        if (infoBox && infoText && packMatch) {
                            infoText.innerText = packMatch[0] + ', ' + (postCodeMatch ? postCodeMatch[0] : '') + ' ' + cityClean;
                            infoBox.style.display = 'block';
                        }
                    }
                }
            });

            // Synchronizacja wpisanego Postnummer do natywnego pola WooCommerce (shipping_address_2)
            // Dzięki temu CheckoutWC i inne niestandardowe koszyki na 100% wyślą to pole do serwera.
            var postNummerField = document.getElementById('dhl_custom_postnummer_field');
            if (postNummerField) {
                postNummerField.addEventListener('input', function () {
                    var addr2 = document.getElementById('shipping_address_2');
                    var company = document.getElementById('shipping_company');
                    
                    if (addr2) {
                        addr2.value = this.value;
                    }
                    if (company) {
                        company.value = this.value; // Zapasowy fallback dla wtyczek, które czytają to z Company
                    }
                });
            }
        })();
    </script>
    <?php
}, 10);

// 2. Backend: Generowanie czystego adresu dostawy w zamówieniu
add_action('woocommerce_checkout_create_order', function ($order, $data) {
    $ps_name = !empty($_POST['dhl_ps_name']) ? sanitize_text_field(wp_unslash($_POST['dhl_ps_name'])) : '';
    $postnummer = !empty($_POST['dhl_packstation_postnummer']) ? sanitize_text_field(wp_unslash($_POST['dhl_packstation_postnummer'])) : '';

    // Fallback: jeśli CheckoutWC usunął nasze pole z $_POST, próbujemy złapać je z natywnego shipping_address_2
    if (empty($postnummer) && !empty($_POST['shipping_address_2'])) {
        $postnummer = sanitize_text_field(wp_unslash($_POST['shipping_address_2']));
    }

    $ps_zip = !empty($_POST['dhl_ps_zip']) ? sanitize_text_field(wp_unslash($_POST['dhl_ps_zip'])) : '';
    $ps_city = !empty($_POST['dhl_ps_city']) ? sanitize_text_field(wp_unslash($_POST['dhl_ps_city'])) : '';

    if (!empty($ps_name)) {
        $order->set_shipping_address_1($ps_name);
        $order->set_shipping_address_2($postnummer);
        if (!empty($ps_zip))
            $order->set_shipping_postcode($ps_zip);
        if (!empty($ps_city))
            $order->set_shipping_city($ps_city);
        
        $order->set_shipping_company('');

        // Zapisujemy pod wszystkie możliwe klucze, z których korzystają wtyczki DHL i DPD w systemie
        $order->update_meta_data('_shipping_dhl_address_type', 'packstation');
        $order->update_meta_data('_shipping_dhl_postnum', $postnummer);
        $order->update_meta_data('shipping_dhl_postnum', $postnummer);
        $order->update_meta_data('_shipping_postnummer', $postnummer);
        $order->update_meta_data('shipping_postnummer', $postnummer);
    }
}, 20, 2);