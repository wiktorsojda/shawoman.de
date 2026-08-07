<?php
if ( ! function_exists( 'blendygo_get_label' ) ) {
    // SŁOWNIK JĘZYKOWY DLA 10 DOMEN
    function blendygo_get_label( $key, $promo_id = 0 ) {
        // ZABEZPIECZENIE RĘCZNE ZOSTAŁO USUNIĘTE. FUNKCJA DZIAŁA TERAZ TYLKO JAKO CZYSTY SŁOWNIK.
        $locale = get_locale();
        $lang_code = substr( $locale, 0, 2 );

        $dictionary = [
            'pl' => [
                'days' => 'dni', 'hours' => 'godziny', 'mins' => 'minuty', 'secs' => 'sekundy',
                'set_price' => 'Cena za zestaw:', 'set_price_rest' => 'Cena poza zestawem:', 'set_btn' => 'Zobacz zestaw',
                'set_header' => 'Zestaw Urodzinowy:',
                'atc_reg' => 'Regulamin konkursu',
                'currency' => ' zł', 'dec' => ',', 'tho' => ' ',
                'promo_badge_default' => 'PROMOCJA',
                'ending_soon' => 'Promocja wkrótce się zakończy'
            ],
            'de' => [
                'days' => 'Tage', 'hours' => 'Stunden', 'mins' => 'Minuten', 'secs' => 'Sekunden',
                'set_price' => 'Set-Preis:', 'set_price_rest' => 'Normalpreis:', 'set_btn' => 'Set ansehen',
                'set_header' => 'Aktionsset:',
                'atc_reg' => 'Wettbewerbsregeln',
                'currency' => ' €', 'dec' => ',', 'tho' => '.',
                'promo_badge_default' => 'AKTION',
                'ending_soon' => 'Aktion endet in Kürze'
            ],
            'hu' => [
                'days' => 'nap', 'hours' => 'óra', 'mins' => 'perc', 'secs' => 'másodperc',
                'set_price' => 'Szett ára:', 'set_price_rest' => 'Szett nélküli ár:', 'set_btn' => 'Szett megtekintése',
                'set_header' => 'Promóciós szett:',
                'atc_reg' => 'Verseny szabályzata',
                'currency' => ' Ft', 'dec' => ',', 'tho' => ' ',
                'promo_badge_default' => 'AKCIÓ',
                'ending_soon' => 'A promóció hamarosan véget ér'
            ],
            'cs' => [
                'days' => 'dní', 'hours' => 'hodin', 'mins' => 'minut', 'secs' => 'sekund',
                'set_price' => 'Cena sady:', 'set_price_rest' => 'Běžná cena:', 'set_btn' => 'Zobrazit sadu',
                'set_header' => 'Akční sada:',
                'atc_reg' => 'Pravidla soutěże',
                'currency' => ' Kč', 'dec' => ',', 'tho' => ' ',
                'promo_badge_default' => 'AKCE',
                'ending_soon' => 'Akce brzy skončí'
            ],
            'sk' => [
                'days' => 'dní', 'hours' => 'hodín', 'mins' => 'minút', 'secs' => 'sekúnd',
                'set_price' => 'Cena setu:', 'set_price_rest' => 'Bežná cena:', 'set_btn' => 'Zobraziť set',
                'set_header' => 'Akčný set:',
                'atc_reg' => 'Pravidlá súťaže',
                'currency' => ' €', 'dec' => ',', 'tho' => ' ',
                'promo_badge_default' => 'AKCIA',
                'ending_soon' => 'Akcia čoskoro skončí'
            ],
            'ro' => [
                'days' => 'zile', 'hours' => 'ore', 'mins' => 'minute', 'secs' => 'secunde',
                'set_price' => 'Prețul setului:', 'set_price_rest' => 'Preț normal:', 'set_btn' => 'Vezi setul',
                'set_header' => 'Set promoțional:',
                'atc_reg' => 'Regulamentul concursului',
                'currency' => ' lei', 'dec' => ',', 'tho' => '.',
                'promo_badge_default' => 'PROMOȚIE',
                'ending_soon' => 'Promoția se va încheia în curând'
            ],
            'it' => [
                'days' => 'giorni', 'hours' => 'ore', 'mins' => 'minuti', 'secs' => 'secondi',
                'set_price' => 'Prezzo del set:', 'set_price_rest' => 'Prezzo normale:', 'set_btn' => 'Vedi il set',
                'set_header' => 'Set promozionale:',
                'atc_reg' => 'Regolamento del concorso',
                'currency' => ' €', 'dec' => ',', 'tho' => '.',
                'promo_badge_default' => 'PROMOZIONE',
                'ending_soon' => 'La promozione terminerà presto'
            ],
            'es' => [
                'days' => 'días', 'hours' => 'horas', 'mins' => 'minutos', 'secs' => 'segundos',
                'set_price' => 'Precio del set:', 'set_price_rest' => 'Precio normal:', 'set_btn' => 'Ver el set',
                'set_header' => 'Set promocional:',
                'atc_reg' => 'Reglas del concurso',
                'currency' => ' €', 'dec' => ',', 'tho' => '.',
                'promo_badge_default' => 'PROMOCIÓN',
                'ending_soon' => 'La promoción terminará pronto'
            ],
            'nl' => [
                'days' => 'dagen', 'hours' => 'uur', 'mins' => 'minuten', 'secs' => 'seconden',
                'set_price' => 'Set prijs:', 'set_price_rest' => 'Normale prijs:', 'set_btn' => 'Bekijk de set',
                'set_header' => 'Promotieset:',
                'atc_reg' => 'Wedstrijdreglement',
                'currency' => ' €', 'dec' => ',', 'tho' => '.',
                'promo_badge_default' => 'PROMOTIE',
                'ending_soon' => 'De promotie eindigt binnenkort'
            ],
            'en' => [
                'days' => 'days', 'hours' => 'hours', 'mins' => 'minutes', 'secs' => 'seconds',
                'set_price' => 'Set price:', 'set_price_rest' => 'Regular price:', 'set_btn' => 'View set',
                'set_header' => 'Promotional set:',
                'atc_reg' => 'Contest rules',
                'currency' => ' £', 'dec' => '.', 'tho' => ',',
                'promo_badge_default' => 'PROMOTION',
                'ending_soon' => 'The promotion will end soon'
            ]
        ];

        // ZWRÓĆ TŁUMACZENIE, JEŚLI JĘZYK NIE JEST OBSŁUGIWANY, DAJ ANGIELSKI JAKO FALLBACK, A JEŚLI BRAKUJE TO POLSKI.
        if ( isset( $dictionary[$lang_code][$key] ) ) {
            return $dictionary[$lang_code][$key];
        } elseif ( isset( $dictionary['en'][$key] ) ) {
            return $dictionary['en'][$key];
        } else {
            return $dictionary['pl'][$key]; 
        }
    }
}

if ( ! function_exists( 'blendygo_get_promo_phase' ) ) {
    // FUNKCJA POMOCNICZA DO OKREŚLANIA FAZY OPARTA O TIMESTAMP WP Z UWZGLĘDNIENIEM FAZY 4 (GRACE PERIOD)
    function blendygo_get_promo_phase( $promo_id ) {
        $is_active = get_post_meta( $promo_id, 'promo_is_active', true );
        if ( $is_active === 'no' ) return 0;

        // TRYB TESTOWY ADMINA
        $admin_only = get_post_meta( $promo_id, 'promo_admin_only', true );
        if ( $admin_only === 'yes' && ! current_user_can( 'manage_options' ) ) {
            return 0;
        }

        $now = current_time( 'timestamp' );
        
        $start = strtotime( get_post_meta( $promo_id, 'promo_date_start', true ) ?: 0 );
        $ext_start = strtotime( get_post_meta( $promo_id, 'promo_date_ext_start', true ) ?: 0 );
        $final = strtotime( get_post_meta( $promo_id, 'promo_date_final', true ) ?: 0 );
        $final_fixed = strtotime( get_post_meta( $promo_id, 'promo_date_final_fixed', true ) ?: 0 );
        $remove_ui = strtotime( get_post_meta( $promo_id, 'promo_date_remove_ui', true ) ?: 0 );

        $actual_final = $final_fixed ?: $final;
        $absolute_death = $remove_ui ?: $actual_final;

        // JEŚLI JESTEŚMY PO ABSOLUTNYM ZAKOŃCZENIU
        if ( $absolute_death && $now > $absolute_death ) return 0;

        // FAZA 4 (GRACE PERIOD) - MIĘDZY ZABLOKOWANIEM LICZNIKA A CAŁKOWITYM ZNIKNIĘCIEM UI
        if ( $actual_final && $absolute_death && $now > $actual_final && $now <= $absolute_death ) return 4;

        // FAZA 3 (PRZEDŁUŻENIE)
        if ( $final_fixed && $final && $now >= $final && $now <= $final_fixed ) return 3;

        // FAZA 2 (LICZNIK STANDARDOWY)
        if ( $ext_start && $actual_final && $now >= $ext_start && $now <= ($final ?: $final_fixed) ) return 2;
        
        // FAZA 1 (BEZ LICZNIKA)
        $phase1_end = $ext_start ?: ($final ?: $final_fixed);
        if ( $start && $phase1_end && $now >= $start && $now < $phase1_end ) return 1;

        return 0; 
    }
}

if ( ! function_exists( 'blendygo_get_active_cpt_promo' ) ) {
    // 1. SILNIK WYSZUKIWANIA AKTYWNEJ PROMOCJI DLA PRODUKTU
    function blendygo_get_active_cpt_promo( $product_id = null ) {
        if ( ! $product_id ) $product_id = get_the_ID();
        if ( ! $product_id ) return null;

        $product_cats = wp_get_post_terms( $product_id, 'product_cat', [ 'fields' => 'ids' ] );

        $args = [
            'post_type'      => 'promocje',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'meta_key'       => 'promo_priority',
            'orderby'        => 'meta_value_num',
            'order'          => 'DESC',
        ];

        $promos = get_posts( $args );

        foreach ( $promos as $promo ) {
            $p_id = $promo->ID;
            if ( blendygo_get_promo_phase( $p_id ) === 0 ) continue; 

            // 1. ZASIĘG GLOBALNY
            if ( get_post_meta( $p_id, 'promo_global', true ) === 'yes' ) return $p_id;

            // 2. PRODUKTY
            $allowed_prods = explode( ',', get_post_meta( $p_id, 'promo_product_ids', true ) ?: '' );
            if ( in_array( $product_id, $allowed_prods ) ) return $p_id; 

            // 3. KATEGORIE
            $allowed_cats = explode( ',', get_post_meta( $p_id, 'promo_categories', true ) ?: '' );
            if ( ! empty( array_intersect( $product_cats, $allowed_cats ) ) ) return $p_id; 
        }
        return null;
    }
}

if ( ! function_exists( 'blendygo_get_global_active_cpt_promo' ) ) {
    function blendygo_get_global_active_cpt_promo() {
        $args = [
            'post_type'      => 'promocje',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'meta_key'       => 'promo_priority',
            'orderby'        => 'meta_value_num',
            'order'          => 'DESC',
        ];
        $promos = get_posts( $args );
        foreach ( $promos as $promo ) {
            if ( blendygo_get_promo_phase( $promo->ID ) !== 0 ) return $promo->ID;
        }
        return null;
    }
}

if ( ! function_exists( 'blendygo_get_active_cpt_set_index' ) ) {
    // WYSZUKIWANIE PASUJĄCEGO ZESTAWU DLA PRODUKTU Z HIERARCHIĄ
    function blendygo_get_active_cpt_set_index( $promo_id, $product_id = null ) {
        if ( ! $product_id ) $product_id = get_the_ID();
        if ( ! $product_id ) return 0;
        
        $product_cats = wp_get_post_terms( $product_id, 'product_cat', [ 'fields' => 'ids' ] );
        $global_fallback = 0;

        // KROK 1: SZUKAMY DOKŁADNEGO DOPASOWANIA (ID LUB KATEGORIA)
        for ( $i = 1; $i <= 3; $i++ ) {
            $target = get_post_meta( $promo_id, 'promo_set_target_' . $i, true );
            if ( empty( $target ) ) continue; 

            $allowed_prods = explode( ',', get_post_meta( $promo_id, 'promo_set_where_prods_' . $i, true ) ?: '' );
            if ( in_array( $product_id, $allowed_prods ) ) return $i; // DOKŁADNE DOPASOWANIE PRODUKTU - WYGRYWA

            $allowed_cats = explode( ',', get_post_meta( $promo_id, 'promo_set_where_cats_' . $i, true ) ?: '' );
            if ( ! empty( array_intersect( $product_cats, $allowed_cats ) ) ) return $i; // DOKŁADNE DOPASOWANIE KATEGORII - WYGRYWA

            // ZBIERAMY DANE O ZESTAWACH GLOBALNYCH NA PÓŹNIEJ
            $is_global = get_post_meta( $promo_id, 'promo_set_all_products_' . $i, true );
            if ( $is_global === 'yes' && $global_fallback === 0 ) {
                $global_fallback = $i;
            }
        }

        // KROK 2: JEŚLI NIE ZNALEZIONO DOKŁADNEGO DOPASOWANIA, ZWRÓĆ GLOBALNY FALLBACK
        return $global_fallback;
    }
}

if ( ! function_exists( 'blendygo_render_cpt_shop_banner' ) ) {
    function blendygo_render_cpt_shop_banner() {
        static $executed = false;
        if ( $executed || ( ! is_shop() && ! is_product_category() ) ) return;
        $executed = true;

        $promo_id = blendygo_get_global_active_cpt_promo();
        if ( ! $promo_id ) return;

        $img_dsktp = get_post_meta( $promo_id, 'promo_banner_shop_desk', true );
        $img_mobi  = get_post_meta( $promo_id, 'promo_banner_shop_mob', true );
        $text_1 = get_post_meta( $promo_id, 'promo_banner_shop_text_1', true );
        $text_2 = get_post_meta( $promo_id, 'promo_banner_shop_text_2', true );

        if ( empty( $img_dsktp ) && empty( $img_mobi ) ) return;

        echo '<div class="shop-banner-image cpt-shop-banner" style="position: relative; margin-bottom: 20px;">';
        if ( $img_dsktp ) echo '<img class="shop-banner-image-desktop" src="' . esc_url( $img_dsktp ) . '" style="width: 100%; height: auto;">';
        if ( $img_mobi ) echo '<img class="shop-banner-image-mobile" src="' . esc_url( $img_mobi ) . '" style="width: 100%; height: auto; display:none;">';
        
        if ( ! empty( $text_1 ) || ! empty( $text_2 ) ) {
            echo '<div class="shop-banner-textcontainer" style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); text-align:center; z-index: 100;">';
            if ( $text_1 ) echo '<div class="banner-text banner-text-1">' . wp_kses_post( $text_1 ) . '</div>';
            if ( $text_2 ) echo '<div class="banner-text banner-text-2">' . esc_html( $text_2 ) . '</div>';
            echo '</div>'; 
        }
        echo '</div>';
        ?>
        <style>@media (max-width: 768px) { .cpt-shop-banner .shop-banner-image-desktop { display: none !important; } .cpt-shop-banner .shop-banner-image-mobile { display: block !important; } }</style>
        <?php
    }
}

if ( ! function_exists( 'blendygo_render_cpt_banner' ) ) {
    function blendygo_render_cpt_banner() {
        if ( ! is_product() ) return;
        $promo_id = blendygo_get_active_cpt_promo();
        if ( ! $promo_id ) return;
        $phase = blendygo_get_promo_phase( $promo_id );
        if ( $phase === 0 ) return;

        $use_timer = false;
        $is_grace_period = false;
        $remaining_seconds = 0;
        $target_timestamp_utc = 0;

        if ( $phase === 1 ) {
            $img_dsktp = get_post_meta( $promo_id, 'promo_banner_desk', true );
            $img_mobi  = get_post_meta( $promo_id, 'promo_banner_mob', true );
        } else {
            $img_dsktp = get_post_meta( $promo_id, 'promo_banner_desk_timer', true ) ?: get_post_meta( $promo_id, 'promo_banner_desk', true );
            $img_mobi  = get_post_meta( $promo_id, 'promo_banner_mob_timer', true ) ?: get_post_meta( $promo_id, 'promo_banner_mob', true );
            
            if ($phase === 4) {
                // FAZA 4 - WYŚWIETLAMY TEKST ZAMIAST LICZNIKA
                $is_grace_period = true;
            } else {
                // FAZA 2 LUB 3 - ODLICZANIE
                $use_timer = true;
                $target_date = get_post_meta( $promo_id, ( $phase === 3 ? 'promo_date_final_fixed' : 'promo_date_final' ), true ) ?: get_post_meta( $promo_id, 'promo_date_final_fixed', true );
                
                if ( $target_date ) {
                    $remaining_seconds = strtotime( $target_date ) - current_time( 'timestamp' );
                    
                    // KONWERSJA CZASU WORDPRESSA NA ABSOLUTNY CZAS UTC DLA JAVASCRIPTU
                    $target_date_gmt = get_gmt_from_date( date( 'Y-m-d H:i:s', strtotime( $target_date ) ) );
                    $target_timestamp_utc = strtotime( $target_date_gmt );
                }
            }
        }

        if ( empty( $img_dsktp ) ) return;

        // USTAWIENIE DOMYŚLNYCH WARTOŚCI JAK W FUNCTIONS.PHP
        $t_top = get_post_meta( $promo_id, 'timer_pos_top', true ) ?: '57';
        $t_left = get_post_meta( $promo_id, 'timer_pos_left', true ) ?: '50';
        $t_top_m = get_post_meta( $promo_id, 'timer_pos_top_mob', true ) ?: '68';
        $t_left_m = get_post_meta( $promo_id, 'timer_pos_left_mob', true ) ?: '36';
        $f_num = get_post_meta( $promo_id, 'timer_font_size_num', true ) ?: '26';
        $f_lbl = get_post_meta( $promo_id, 'timer_font_size_label', true ) ?: '14';
        $f_fam = get_post_meta( $promo_id, 'timer_font_family', true ) ?: 'Work Sans';
        $f_wei_n = get_post_meta( $promo_id, 'timer_font_weight_num', true ) ?: '700';
        $f_wei_l = get_post_meta( $promo_id, 'timer_font_weight_lbl', true ) ?: '700';
        $g_col = get_post_meta( $promo_id, 'timer_gap_col', true ) ?: '20';
        $g_row = get_post_meta( $promo_id, 'timer_gap_row', true ) ?: '0';
        $c_num = get_post_meta( $promo_id, 'timer_color_num', true ) ?: '#FF0000';
        $c_lbl = get_post_meta( $promo_id, 'timer_color_lbl', true ) ?: '#FF0000';

        $c_num_style = ( strpos( $c_num, 'gradient' ) !== false ) ? 'background: ' . esc_attr( $c_num ) . ' !important; -webkit-background-clip: text !important; color: transparent !important; display: inline-block !important;' : 'color: ' . esc_attr( $c_num ) . ' !important; display: inline-block !important;';
        $c_lbl_style = ( strpos( $c_lbl, 'gradient' ) !== false ) ? 'background: ' . esc_attr( $c_lbl ) . ' !important; -webkit-background-clip: text !important; color: transparent !important; display: inline-block !important;' : 'color: ' . esc_attr( $c_lbl ) . ' !important; display: inline-block !important;';

        // POBRANIE TŁUMACZEŃ
        $l_days  = blendygo_get_label('days', $promo_id);
        $l_hours = blendygo_get_label('hours', $promo_id);
        $l_mins  = blendygo_get_label('mins', $promo_id);
        $l_secs  = blendygo_get_label('secs', $promo_id);
        $l_ending_soon = blendygo_get_label('ending_soon', $promo_id);

        ?>
        <style>
            @import url("https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Poppins:wght@400;600;700;800;900&family=Work+Sans:wght@400;600;700;800;900&display=swap");
            #blendygo-promo-banner-<?php echo $promo_id; ?> .countdown-timer-flex { display: flex; column-gap: <?php echo esc_attr($g_col); ?>px; font-family: <?php echo esc_attr($f_fam); ?>, sans-serif; height: 36px; line-height: normal; text-align: center; justify-content: center; width: 100%; white-space: nowrap; }
            #blendygo-promo-banner-<?php echo $promo_id; ?> .countdown-wrapper { display: flex; flex-direction: column; align-items: center; row-gap: <?php echo esc_attr($g_row); ?>px; }
            #blendygo-promo-banner-<?php echo $promo_id; ?> .countdown-number { font-weight: <?php echo esc_attr($f_wei_n); ?>; font-size: <?php echo esc_attr($f_num); ?>px; <?php echo $c_num_style; ?> }
            #blendygo-promo-banner-<?php echo $promo_id; ?> .countdown-label { font-weight: <?php echo esc_attr($f_wei_l); ?>; font-size: <?php echo esc_attr($f_lbl); ?>px; text-transform: uppercase; line-height: 1; margin-top: 0px; <?php echo $c_lbl_style; ?> }
            @media (min-width: 769px) { .cpt-banner-mobile, .cpt-timer-mob-container { display: none !important; } .cpt-timer-desk-container { position: absolute; top: <?php echo esc_attr($t_top); ?>%; left: <?php echo esc_attr($t_left); ?>%; transform: translate(-50%, -10%); width: 100%; text-align: center; pointer-events: none;} }
            @media (max-width: 768px) { .cpt-banner-desktop, .cpt-timer-desk-container { display: none !important; } .cpt-banner-mobile { display: block !important; height: auto !important; width: 100%; border-radius: 8px !important; } .cpt-timer-mob-container { position: absolute; top: <?php echo esc_attr($t_top_m); ?>%; left: <?php echo esc_attr($t_left_m); ?>%; transform: translate(-50%, -10%); display: block !important; width: 100%; text-align: center; pointer-events: none;} }
        </style>
        <div id="blendygo-promo-banner-<?php echo $promo_id; ?>" class="custom-banner-section" style="position: relative !important; display: block !important; width: 100%; text-align: center; margin-bottom: 20px;">
            <img src="<?php echo esc_url( $img_dsktp ); ?>" class="cpt-banner-desktop" style="width: 100%; height: auto; display: block;">
            
            <?php if ( $is_grace_period ) : ?>
                <div class="cpt-timer-desk-container"><div class="countdown-timer-flex"><span class="countdown-number"><?php echo esc_html($l_ending_soon); ?></span></div></div>
                <div class="cpt-timer-mob-container"><div class="countdown-timer-flex"><span class="countdown-number"><?php echo esc_html($l_ending_soon); ?></span></div></div>
            <?php elseif ( $use_timer && $remaining_seconds > 0 ) : ?>
                <div class="cpt-timer-desk-container"><div id="cpt-timer-desk-<?php echo $promo_id; ?>" class="countdown-timer-flex"></div></div>
                <div class="cpt-timer-mob-container"><div id="cpt-timer-mob-<?php echo $promo_id; ?>" class="countdown-timer-flex"></div></div>
                <script>
                    (function() {
                        // OBLICZANIE CZASU NA PODSTAWIE BEZWZGLĘDNEJ DATY
                        var targetTimeMs = <?php echo $target_timestamp_utc * 1000; ?>;
                        
                        function getRemainingSeconds() {
                            return Math.floor((targetTimeMs - Date.now()) / 1000);
                        }

                        var elDsktp = document.getElementById("cpt-timer-desk-<?php echo $promo_id; ?>"), elMobi = document.getElementById("cpt-timer-mob-<?php echo $promo_id; ?>");
                        var lblDays = "<?php echo esc_js($l_days); ?>";
                        var lblHours = "<?php echo esc_js($l_hours); ?>";
                        var lblMins = "<?php echo esc_js($l_mins); ?>";
                        var lblSecs = "<?php echo esc_js($l_secs); ?>";
                        var endingSoonTxt = "<?php echo esc_js($l_ending_soon); ?>";
                        
                        function renderTimer() {
                            var remainingSeconds = getRemainingSeconds();
                            if (remainingSeconds < 0) return false;

                            var d = Math.floor(remainingSeconds / 86400), 
                                h = Math.floor((remainingSeconds % 86400) / 3600), 
                                m = Math.floor((remainingSeconds % 3600) / 60), 
                                s = Math.floor(remainingSeconds % 60);
                                
                            h = (h < 10) ? "0" + h : h; 
                            m = (m < 10) ? "0" + m : m; 
                            s = (s < 10) ? "0" + s : s;
                            
                            var html = "<div class='countdown-wrapper'><span class='countdown-number'>" + d + "</span><span class='countdown-label'>" + lblDays + "</span></div>" + 
                                       "<div class='countdown-wrapper'><span class='countdown-number'>" + h + "</span><span class='countdown-label'>" + lblHours + "</span></div>" + 
                                       "<div class='countdown-wrapper'><span class='countdown-number'>" + m + "</span><span class='countdown-label'>" + lblMins + "</span></div>" + 
                                       "<div class='countdown-wrapper'><span class='countdown-number'>" + s + "</span><span class='countdown-label'>" + lblSecs + "</span></div>";
                                       
                            if(elDsktp) elDsktp.innerHTML = html; 
                            if(elMobi) elMobi.innerHTML = html;
                            return true;
                        }
                        
                        // INICJALIZACJA
                        if (renderTimer()) {
                            var x = setInterval(function() { 
                                if (!renderTimer()) { 
                                    clearInterval(x); 
                                    var graceHtml = "<span class='countdown-number'>" + endingSoonTxt + "</span>";
                                    if(elDsktp) elDsktp.innerHTML = graceHtml; 
                                    if(elMobi) elMobi.innerHTML = graceHtml;
                                } 
                            }, 1000);
                        } else {
                            var graceHtml = "<span class='countdown-number'>" + endingSoonTxt + "</span>";
                            if(elDsktp) elDsktp.innerHTML = graceHtml; 
                            if(elMobi) elMobi.innerHTML = graceHtml;
                        }
                    })();
                </script>
            <?php endif; ?>

            <?php if ( $img_mobi ) : ?><img src="<?php echo esc_url( $img_mobi ); ?>" class="cpt-banner-mobile" style="width: 100%; height: auto;"><?php endif; ?>
        </div>
        <?php
    }
}

if ( ! function_exists( 'blendygo_get_badge_colors' ) ) {
    function blendygo_get_badge_colors( $promo_id ) {
        $bg = get_post_meta( $promo_id, 'promo_badge_bg', true );
        $color = get_post_meta( $promo_id, 'promo_badge_color', true );
        
        $bg_style = $bg ? 'background: ' . esc_attr( $bg ) . ';' : '';
        
        if ( empty($color) ) {
            $color = 'linear-gradient(to right, #ff0000, #00ff00)'; 
        }
        
        $color_style = ( strpos( $color, 'gradient' ) !== false ) 
            ? 'background: ' . esc_attr( $color ) . '; -webkit-background-clip: text; color: transparent; display: inline-block;'
            : 'color: ' . esc_attr( $color ) . '; display: inline-block;';

        return [ 'bg' => $bg_style, 'color' => $color_style ];
    }
}

if ( ! function_exists( 'blendygo_render_cpt_badge' ) ) {
    // 4. RENDERER BADGE (STRONA PRODUKTU)
    function blendygo_render_cpt_badge() {
        $promo_id = blendygo_get_active_cpt_promo();
        if ( ! $promo_id ) return;

        $pct = get_post_meta( $promo_id, 'promo_percentage_text', true ); 
        $txt = get_post_meta( $promo_id, 'promo_small_text', true ) ?: blendygo_get_label('promo_badge_default', $promo_id);
        $styles = blendygo_get_badge_colors( $promo_id );

        if ( ! empty( $pct ) ) {
            $bg_attr = ! empty($styles['bg']) ? ' style="' . $styles['bg'] . '"' : '';
            
            echo '<div class="promotional-element-two-lines"' . $bg_attr . '>';
                echo '<span class="promo-percentage" style="' . $styles['color'] . '">-' . esc_html( $pct ) . '%</span>';
                echo '<span class="promo-small-text">' . esc_html( $txt ) . '</span>';
            echo '</div>';
        }
    }
}

if ( ! function_exists( 'blendygo_render_cpt_additional_badges' ) ) {
    function blendygo_render_cpt_additional_badges() {
        if ( ! is_product() ) return;
        $promo_id = blendygo_get_active_cpt_promo();
        if ( ! $promo_id ) return;

        // UWAGA: globalne $b_width zostało usunięte
        for ( $i = 1; $i <= 3; $i++ ) {
            $text = get_post_meta( $promo_id, 'promo_badge_text_' . $i, true );
            $icon_type = get_post_meta( $promo_id, 'promo_badge_icon_type_' . $i, true );
            $svg = get_post_meta( $promo_id, 'promo_badge_svg_' . $i, true );
            $img = get_post_meta( $promo_id, 'promo_badge_image_' . $i, true );
            
            $bg_color = get_post_meta( $promo_id, 'promo_badge_bg_color_' . $i, true );
            $text_color = get_post_meta( $promo_id, 'promo_badge_text_color_' . $i, true );
            
            $b_width_ind = get_post_meta( $promo_id, 'promo_badge_width_' . $i, true ) ?: '100';
            $b_wauto = get_post_meta( $promo_id, 'promo_badge_width_auto_' . $i, true );
            $b_align = get_post_meta( $promo_id, 'promo_badge_align_' . $i, true ) ?: 'flex-start';
            $b_mt = get_post_meta( $promo_id, 'promo_badge_mt_' . $i, true );
            if ( $b_mt === '' || $b_mt === false ) $b_mt = '12';
            $b_mb = get_post_meta( $promo_id, 'promo_badge_mb_' . $i, true );
            if ( $b_mb === '' || $b_mb === false ) $b_mb = '0';
            
            $b_py = get_post_meta( $promo_id, 'promo_badge_py_' . $i, true );
            if ( $b_py === '' || $b_py === false ) $b_py = '5';
            $b_px = get_post_meta( $promo_id, 'promo_badge_px_' . $i, true );
            if ( $b_px === '' || $b_px === false ) $b_px = '10';
            
            $isize_val = get_post_meta( $promo_id, 'promo_badge_icon_size_val_' . $i, true );
            $isize_unit = get_post_meta( $promo_id, 'promo_badge_icon_size_unit_' . $i, true );
            
            if ( $isize_val === '' || $isize_val === false ) {
                $old_isize = get_post_meta( $promo_id, 'promo_badge_icon_size_' . $i, true );
                if ( !empty($old_isize) ) {
                    $icon_size = $old_isize;
                } else {
                    $icon_size = '1.2em';
                }
            } else {
                $icon_size = $isize_val . $isize_unit;
            }
            
            $bg_img = get_post_meta( $promo_id, 'promo_badge_bg_image_' . $i, true );
            $width_css = ( $b_wauto === 'yes' ) ? 'fit-content' : esc_attr( $b_width_ind ) . '%';

            if ( ! empty( $text ) || ( $icon_type === 'svg' && ! empty( $svg ) ) || ( $icon_type === 'image' && ! empty( $img ) ) ) {
                
                $style = 'display: flex; align-items: center; margin-bottom: ' . esc_attr( $b_mb ) . 'px !important; margin-top: ' . esc_attr( $b_mt ) . 'px !important; width: ' . $width_css . ' !important; padding: ' . esc_attr( $b_py ) . 'px ' . esc_attr( $b_px ) . 'px ' . esc_attr( $b_py ) . 'px ' . esc_attr( $b_px ) . 'px !important; box-sizing: border-box; justify-content: ' . esc_attr( $b_align ) . ';';
                if ( ! empty( $bg_img ) ) {
                    $style .= ' background-image: url(' . esc_url( $bg_img ) . ') !important; background-size: cover !important; background-position: center !important;';
                } elseif ( ! empty( $bg_color ) ) {
                    $style .= ' background: ' . esc_attr( $bg_color ) . ' !important;';
                }
                if ( ! empty( $text_color ) ) {
                    $style .= ' color: ' . esc_attr( $text_color ) . ' !important;';
                }

                echo '<div class="short-description-custom-text product-tag" style="' . $style . '">';
                
                if ( $icon_type === 'svg' && ! empty( $svg ) ) {
                    $svg_scaled = preg_replace('/<svg /i', '<svg style="height: 100%; width: auto; max-width: 100%;" ', $svg, 1);
                    echo '<span style="display:flex; flex-shrink:0; height: ' . esc_attr( $icon_size ) . '; align-items: center; justify-content: center;">' . $svg_scaled . '</span>';
                } elseif ( $icon_type === 'image' && ! empty( $img ) ) {
                    if ( strtolower( substr( $img, -4 ) ) === '.svg' ) {
                        // Pobierz absolutną ścieżkę do obrazka SVG by wstrzyknąć inline
                        $upload_dir = wp_upload_dir();
                        $base_url = $upload_dir['baseurl'];
                        if ( strpos( $img, $base_url ) !== false ) {
                            $local_path = str_replace( $base_url, $upload_dir['basedir'], $img );
                            if ( file_exists( $local_path ) ) {
                                $inline_svg = file_get_contents( $local_path );
                                $inline_svg = preg_replace('/<svg /i', '<svg style="height: 100%; width: auto; max-width: 100%;" ', $inline_svg, 1);
                                echo '<span style="display:flex; flex-shrink:0; height: ' . esc_attr( $icon_size ) . '; align-items: center; justify-content: center;">' . $inline_svg . '</span>';
                            } else {
                                echo '<span style="display:flex; flex-shrink:0; height: ' . esc_attr( $icon_size ) . ';"><img src="' . esc_url( $img ) . '" alt="Ikona" style="height: 100%; width: auto; max-width: 100%; object-fit: contain;"></span>';
                            }
                        } else {
                            echo '<span style="display:flex; flex-shrink:0; height: ' . esc_attr( $icon_size ) . ';"><img src="' . esc_url( $img ) . '" alt="Ikona" style="height: 100%; width: auto; max-width: 100%; object-fit: contain;"></span>';
                        }
                    } else {
                        echo '<span style="display:flex; flex-shrink:0; height: ' . esc_attr( $icon_size ) . ';"><img src="' . esc_url( $img ) . '" alt="Ikona" style="height: 100%; width: auto; max-width: 100%; object-fit: contain;"></span>';
                    }
                }

                if ( ! empty( $text ) ) {
                    echo esc_html( $text );
                }

                echo '</div>';
            }
        }
    }
}

if ( ! function_exists( 'blendygo_render_cpt_product_set' ) ) {
    // RENDERER ZESTAWU PROMOCYJNEGO 1:1
    function blendygo_render_cpt_product_set() {
        $cpt_found = false;
        $title = ''; $img = ''; $target_id = ''; $price_reg = ''; $price_pro = ''; $items = ''; $lbl_btn = ''; $lbl_header = '';
        
        $promo_id = blendygo_get_active_cpt_promo();
        if ( $promo_id ) {
            $set_idx = blendygo_get_active_cpt_set_index( $promo_id );
            if ( $set_idx ) {
                $cpt_found = true;
                $title = get_post_meta( $promo_id, 'promo_set_title_' . $set_idx, true );
                $img = get_post_meta( $promo_id, 'promo_set_image_' . $set_idx, true );
                $target_id = get_post_meta( $promo_id, 'promo_set_target_' . $set_idx, true );
                $price_reg = get_post_meta( $promo_id, 'promo_set_price_regular_' . $set_idx, true );
                $price_pro = get_post_meta( $promo_id, 'promo_set_price_promo_' . $set_idx, true );
                $items = get_post_meta( $promo_id, 'promo_set_items_' . $set_idx, true );

                $custom_btn_label = get_post_meta( $promo_id, 'promo_set_btn_label_' . $set_idx, true );
                $custom_header_label = get_post_meta( $promo_id, 'promo_set_header_label_' . $set_idx, true );
                $lbl_btn = ! empty( $custom_btn_label ) ? $custom_btn_label : blendygo_get_label('set_btn', $promo_id);
                $lbl_header = ! empty( $custom_header_label ) ? $custom_header_label : blendygo_get_label('set_header', $promo_id);
            }
        }

        if ( ! $cpt_found ) {
            global $product;
            $current_product_id = $product ? $product->get_id() : 0;
            $saved_promo_sets = get_option('wc_cs_promo_sets', array());
            $matched_set = false;
            
            foreach ($saved_promo_sets as $p_set) {
                if (isset($p_set['active']) && $p_set['active'] === 'no') continue;
                
                $is_global = isset($p_set['is_global']) ? $p_set['is_global'] : 'no';
                $target_ids = isset($p_set['target_ids']) ? (array)$p_set['target_ids'] : array();
                
                if ($is_global === 'yes' || in_array($current_product_id, $target_ids)) {
                    $matched_set = $p_set;
                    break;
                }
            }
            
            if ($matched_set) {
                $cpt_found = true;
                $title = isset($matched_set['title']) ? $matched_set['title'] : '';
                $img = isset($matched_set['image']) ? $matched_set['image'] : '';
                $target_id = isset($matched_set['target_id']) ? $matched_set['target_id'] : '';
                $price_reg = isset($matched_set['price_reg']) ? $matched_set['price_reg'] : '';
                $price_pro = isset($matched_set['price_promo']) ? $matched_set['price_promo'] : '';
                $items = isset($matched_set['items']) ? $matched_set['items'] : '';
                $lbl_btn = !empty($matched_set['btn_label']) ? $matched_set['btn_label'] : 'Dodaj do koszyka';
                $lbl_header = !empty($matched_set['header_label']) ? $matched_set['header_label'] : 'Zestaw';
                $promo_id = false;
            }
        }

        if ( ! $cpt_found || empty( $target_id ) ) return;
        $product_url = get_permalink( $target_id );

        // POBRANIE SEPARATORÓW I WALUTY
        $cur = blendygo_get_label('currency', $promo_id);
        $dec = blendygo_get_label('dec', $promo_id);
        $tho = blendygo_get_label('tho', $promo_id);

        // FORMATOWANIE CEN (DYNAMICZNE SEPARATORY I WALUTA)
        $format_price = function($p) use ($cur, $dec, $tho) {
            if ( empty( trim($p) ) ) return '';
            $clean_val = preg_replace('/[^-0-9.,]/', '', $p);
            $clean_val = str_replace(',', '.', $clean_val);
            $pos = strrpos($clean_val, '.');
            if ($pos !== false && (strlen($clean_val) - $pos <= 3)) {
                $clean_val = str_replace('.', '', substr($clean_val, 0, $pos)) . '.' . substr($clean_val, $pos + 1);
            } else {
                $clean_val = str_replace('.', '', $clean_val);
            }
            if ( is_numeric( $clean_val ) ) {
                $decimals = (trim($cur) === 'Ft') ? 0 : 2;
                return number_format( (float) $clean_val, $decimals, $dec, $tho ) . $cur;
            }
            return $p . $cur;
        };

        $price_reg = $format_price($price_reg);
        $price_pro = $format_price($price_pro);

        echo '<div class="zestaw-container">';
        echo '<div class="zestaw-title">' . esc_html( $lbl_header ) . '</div>'; 
        echo '<div class="custom-product-section" style="margin-top: 0px;">';
        
        echo '<div class="zestaw-section">';
        echo '<div class="zestaw-details">';

        // OBRAZEK
        if ( ! empty( $img ) ) {
            echo '<div class="custom-product-image-father" style="margin-bottom: 10px;">';
            echo '<div class="custom-product-image">';
            echo '<img src="' . esc_url( $img ) . '" alt="' . esc_attr( $title ) . '" style="width: 210px; height: auto; border-radius: 8px;" />';
            echo '</div>';
            echo '</div>';
        }

        // TYTUŁ I SKŁAD
        echo '<div class="custom-title-list-container" style="margin-top: 10px;">';
        if ( ! empty( $title ) ) {
            echo '<h3 class="custom-product-title">' . esc_html( $title ) . '</h3>';
        }

        if ( ! empty( $items ) ) {
            echo '<ul style="list-style: disc; font-weight: 400; padding: 0; padding-left: 13px; line-height: normal; margin-bottom: 0; color: #000; font-size: 14px; font-style: normal;">';
            $items_array = explode( "\n", $items );
            foreach ( $items_array as $item ) {
                if ( trim( $item ) !== '' ) {
                    echo '<li style="margin-bottom: 0px;">' . esc_html( trim( $item ) ) . '</li>';
                }
            }
            echo '</ul>';
        }
        echo '</div>'; 
        echo '</div>'; 

        // CENY I PRZYCISK
        echo '<div class="zestaw-cena">';
        echo '<div class="zestaw-cena-sub">';

        if ( ! empty( $price_pro ) ) {
            echo '<div style="margin-bottom: 10px;">';
            echo '<span style="display: block; margin-bottom: 5px; font-weight: 500;">' . esc_html( blendygo_get_label('set_price', $promo_id) ) . '</span>';
            echo '<span class="zestaw-cena-title" style="display: block; font-size: 32px; color: #AC0000;">' . esc_html( $price_pro ) . '</span>';
            echo '</div>';
        }

        if ( ! empty( $price_reg ) && strip_tags($price_reg) !== strip_tags($price_pro) ) {
            echo '<div style="margin-bottom: 15px;">';
            echo '<span class="zestaw-tekst-cena" style="display: block; margin-bottom: 5px; font-weight: 500;">' . esc_html( blendygo_get_label('set_price_rest', $promo_id) ) . '</span>';
            echo '<span class="zestaw-cena-rest" style="display: block;">' . esc_html( $price_reg ) . '</span>';
            echo '</div>';
        }

        echo '</div>'; 

        if ( $product_url ) {
            echo '<a href="' . esc_url( $product_url ) . '" class="button alt single_add_to_cart_button" style="margin-top: 10px; text-decoration: none; margin-bottom: 1em; width: 100% !important; text-align: center; padding: 0px; font-size: 16px !important;">' . esc_html( $lbl_btn ) . '</a>';
        }

        echo '</div>'; 
        echo '</div>'; 
        echo '</div>'; 
        echo '</div>'; 
    }
}

if ( ! function_exists( 'blendygo_render_cpt_atc_banner' ) ) {
    function blendygo_render_cpt_atc_banner() {
        $promo_id = blendygo_get_active_cpt_promo();
        if ( ! $promo_id ) return;

        $img_desk = get_post_meta( $promo_id, 'promo_banner_atc_desk', true );
        $img_mob  = get_post_meta( $promo_id, 'promo_banner_atc_mob', true );
        $reg_url  = get_post_meta( $promo_id, 'promo_banner_atc_reg_url', true );
        $text     = get_post_meta( $promo_id, 'promo_banner_atc_text', true );

        // FALLBACK DLA WERSJI MOBILNEJ
        if ( empty( $img_mob ) && ! empty( $img_desk ) ) {
            $img_mob = $img_desk;
        }

        if ( empty( $img_desk ) && empty( $img_mob ) ) return;

        // WYMUSZENIE BLOKU I CLEAR BOTH ABY ODKLEIĆ OD PRZYCISKU ZACHOWUJĄC MARGINES 20PX
        echo '<div class="custom-banner-atc-container cpt-atc-banner" style="display: block !important; clear: both !important; margin: 20px 0 0 0 !important; width: 100%;">';
        
        echo '<div class="cpt-atc-image-wrapper">';
        if ( $img_desk ) {
            echo '<img class="atc-img-desk" src="' . esc_url( $img_desk ) . '" style="width: 100%; height: auto; display: block; border-radius: 8px;">';
        }
        if ( $img_mob ) {
            echo '<img class="atc-img-mob" src="' . esc_url( $img_mob ) . '" style="width: 100%; height: auto; display: none; border-radius: 8px;">';
        }
        echo '</div>';

        // PARSER REGULAMINU 
        if ( ! empty( $text ) ) {
            $safe_text = wp_kses_post( $text );
            if ( ! empty( $reg_url ) ) {
                $safe_text = preg_replace( '/\[(.*?)\]/', '<a href="' . esc_url( $reg_url ) . '" target="_blank" style="color: #000; text-decoration: none;">$1</a>', $safe_text );
            } else {
                $safe_text = preg_replace( '/\[(.*?)\]/', '$1', $safe_text );
            }
            echo '<p style="font-size: 10px; margin-top: 5px; margin-bottom: 0; text-align: left; color: #555; padding: 0 20px;">' . $safe_text . '</p>';
        }

        echo '</div>';
        ?>
        <style>
            .cpt-atc-image-wrapper {
                display: block;
                transition: transform 0.3s ease, box-shadow 0.3s ease;
                border-radius: 8px;
                overflow: hidden;
                @media (max-width: 768px) {
                    padding: 0 20px;
                }
            }
            .cpt-atc-image-wrapper:hover {
                transform: scale(1.008); 
                box-shadow: 0 4px 12px rgba(0,0,0,0.05); 
            }
            @media (max-width: 768px) {
                .cpt-atc-banner .atc-img-desk { display: none !important; }
                .cpt-atc-banner .atc-img-mob { display: block !important; }
                padding: 0 20px;
            }
        </style>
        <?php
    }
}

if ( ! function_exists( 'blendygo_render_cpt_cart_banner' ) ) {
    function blendygo_render_cpt_cart_banner() {
        if ( ! is_cart() || WC()->cart->is_empty() ) return;
        $best_promo_id = null; $best_priority = -1;
        foreach ( WC()->cart->get_cart() as $item ) {
            $p_id = blendygo_get_active_cpt_promo( $item['product_id'] );
            if ( $p_id ) {
                $priority = (int) get_post_meta( $p_id, 'promo_priority', true );
                if ( $priority > $best_priority ) { $best_priority = $priority; $best_promo_id = $p_id; }
            }
        }
        if ( ! $best_promo_id ) return;
        $img_dsktp = get_post_meta( $best_promo_id, 'promo_banner_cart_desk', true );
        $img_mobi  = get_post_meta( $best_promo_id, 'promo_banner_cart_mob', true );
        if ( empty( $img_dsktp ) && empty( $img_mobi ) ) return;
        echo '<div class="cart-banner-image cpt-cart-banner" style="position: relative; margin-bottom: 0px; text-align: center;">';
        if ( $img_dsktp ) echo '<img class="cart-banner-image-desktop" src="' . esc_url( $img_dsktp ) . '" style="width: 100%; height: auto;">';
        if ( $img_mobi ) echo '<img class="cart-banner-image-mobile" src="' . esc_url( $img_mobi ) . '" style="width: 96%; margin: auto; height: auto; display:none;">';
        echo '</div>';
        ?>
        <style>@media (max-width: 768px) { .cpt-cart-banner .cart-banner-image-desktop { display: none !important; } .cpt-cart-banner .cart-banner-image-mobile { display: block !important; } }</style>
        <?php
    }
}

if ( ! function_exists( 'blendygo_cpt_debugger' ) ) {
    function blendygo_cpt_debugger() {
        if ( ! current_user_can( 'manage_options' ) ) return;
        $now_str = current_time( 'Y-m-d H:i:s' );
        $promo_id = null; $phase = 0;
        
        if ( is_shop() || is_product_category() ) {
            $promo_id = blendygo_get_global_active_cpt_promo();
            $phase = $promo_id ? blendygo_get_promo_phase( $promo_id ) : 0;
        } elseif( function_exists('is_product') && is_product() ){
            $promo_id = blendygo_get_active_cpt_promo();
            $phase = $promo_id ? blendygo_get_promo_phase( $promo_id ) : 0;
        } elseif( function_exists('is_cart') && is_cart() && WC()->cart ) {
            $best_p = null; $best_pri = -1;
            foreach ( WC()->cart->get_cart() as $ci ) {
                $pid = blendygo_get_active_cpt_promo( $ci['product_id'] );
                if ( $pid ) {
                    $pri = (int) get_post_meta( $pid, 'promo_priority', true );
                    if ( $pri > $best_pri ) { $best_pri = $pri; $best_p = $pid; }
                }
            }
            $promo_id = $best_p;
            $phase = $promo_id ? blendygo_get_promo_phase( $promo_id ) : 0;
        }
        
        echo '<div id="blendygo-promo-debugger" style="position: fixed; bottom: 20px; right: 20px; background: #000; color: #0f0; padding: 15px; font-family: monospace; font-size: 14px; z-index: 999999; border: 1px solid #0f0; border-radius: 4px; box-shadow: 0 0 15px rgba(0,255,0,0.3); opacity: 0.9;">';
        echo '<strong style="color:#fff;">BLENDYGO CPT DEBUGGER</strong><br><br>';
        echo 'Czas WP: <span style="color:#fff;">' . esc_html( $now_str ) . '</span><br>';
        
        if( is_shop() || is_product_category() || is_product() || is_cart() ) {
            if ( $promo_id ) {
                echo 'Aktywne Promo ID: <span style="color:#fff;">' . esc_html( $promo_id ) . '</span><br>';
                echo 'Obecna Faza: <span style="color:#fff;">' . ( $phase > 0 ? 'Faza ' . esc_html( $phase ) : 'Brak Aktywnych Faz' ) . '</span><br>';
            } else { echo 'Aktywne Promo: <span style="color:#f00;">BRAK</span><br>'; }
        }
        echo '</div>';
    }
    add_action( 'wp_footer', 'blendygo_cpt_debugger', 999 );
}

if ( ! function_exists( 'blendygo_apply_cpt_overrides' ) ) {
    function blendygo_apply_cpt_overrides() {
        if ( is_cart() ) {
            $best_promo_id = null; $best_priority = -1;
            foreach ( WC()->cart->get_cart() as $item ) {
                $p_id = blendygo_get_active_cpt_promo( $item['product_id'] );
                if ( $p_id ) {
                    $priority = (int) get_post_meta( $p_id, 'promo_priority', true );
                    if ( $priority > $best_priority ) { $best_priority = $priority; $best_promo_id = $p_id; }
                }
            }
            if ( $best_promo_id ) {
                remove_action( 'woocommerce_before_cart', 'display_cart_banner_image', 1 );
            }
        }

        if ( is_product() ) {
            remove_action( 'woocommerce_share', 'display_custom_product_section', 20 );
            add_action( 'woocommerce_share', 'blendygo_render_cpt_product_set', 20 );

            $promo_id = blendygo_get_active_cpt_promo();
            if ( $promo_id ) {
                remove_action( 'woocommerce_before_single_product_summary', 'display_promotional_element_two_lines', 10 );
                add_action( 'woocommerce_before_single_product_summary', 'blendygo_render_cpt_badge', 9 );
                add_action( 'woocommerce_single_product_summary', 'blendygo_render_cpt_additional_badges', 18 );

                $atc_desk = get_post_meta( $promo_id, 'promo_banner_atc_desk', true );
                $atc_mob  = get_post_meta( $promo_id, 'promo_banner_atc_mob', true );
                if ( ! empty( $atc_desk ) || ! empty( $atc_mob ) ) {
                    remove_shortcode( 'custom_banner_atc' );
                    add_shortcode( 'custom_banner_atc', '__return_false' );
                    add_action( 'woocommerce_share', 'blendygo_render_cpt_atc_banner', 21 );
                }
            }
        }
        if ( is_shop() || is_product_category() ) {
            if ( blendygo_get_global_active_cpt_promo() ) {
                remove_action( 'woocommerce_before_main_content', 'display_shop_banner_image_with_text', 5 );
            }
        }
    }
    add_action( 'template_redirect', 'blendygo_apply_cpt_overrides' );
}

if ( ! function_exists( 'blendygo_override_fse_block_data' ) ) {
    // FILTR NADPISUJĄCY ATRYBUTY BLOKU FSE NA STRONIE GŁÓWNEJ PRZED JEGO WYRENDEROWANIEM
    function blendygo_override_fse_block_data( $parsed_block ) {
        if ( isset( $parsed_block['blockName'] ) && $parsed_block['blockName'] === 'ourblocktheme/urodzinybaner' ) {
            $promo_id = blendygo_get_global_active_cpt_promo();
            if ( $promo_id ) {
                $meta = get_post_meta( $promo_id );
                
                // ZDJĘCIA BANERA
                if ( ! empty( $meta['promo_fse_banner_image'][0] ) ) $parsed_block['attrs']['bannerImage'] = $meta['promo_fse_banner_image'][0];
                if ( ! empty( $meta['promo_fse_banner_image_mobile'][0] ) ) $parsed_block['attrs']['bannerImageMobile'] = $meta['promo_fse_banner_image_mobile'][0];
                
                // PODTYTUŁ
                if ( ! empty( $meta['promo_fse_subtitle'][0] ) ) $parsed_block['attrs']['subtitle'] = $meta['promo_fse_subtitle'][0];
                if ( ! empty( $meta['promo_fse_subtitle_color'][0] ) ) $parsed_block['attrs']['subtitleColor'] = $meta['promo_fse_subtitle_color'][0];
                if ( ! empty( $meta['promo_fse_subtitle_bg'][0] ) ) $parsed_block['attrs']['subtitleBgColor'] = $meta['promo_fse_subtitle_bg'][0];
                if ( ! empty( $meta['promo_fse_subtitle_ff'][0] ) ) $parsed_block['attrs']['subtitleFontFamily'] = $meta['promo_fse_subtitle_ff'][0];
                if ( isset( $meta['promo_fse_subtitle_fs'][0] ) && $meta['promo_fse_subtitle_fs'][0] !== '' ) $parsed_block['attrs']['subtitleFontSize'] = (int) $meta['promo_fse_subtitle_fs'][0];
                
                // TYTUŁ
                if ( isset( $meta['promo_fse_use_title_svg'][0] ) ) $parsed_block['attrs']['useTitleSvg'] = ( $meta['promo_fse_use_title_svg'][0] === 'yes' );
                if ( ! empty( $meta['promo_fse_title_svg'][0] ) ) $parsed_block['attrs']['titleSvg'] = $meta['promo_fse_title_svg'][0];
                if ( isset( $meta['promo_fse_title_svg_maxw'][0] ) && $meta['promo_fse_title_svg_maxw'][0] !== '' ) $parsed_block['attrs']['titleSvgMaxWidth'] = (int) $meta['promo_fse_title_svg_maxw'][0];
                if ( isset( $meta['promo_fse_title_svg_maxw_mobile'][0] ) && $meta['promo_fse_title_svg_maxw_mobile'][0] !== '' ) $parsed_block['attrs']['titleSvgMaxWidthMobile'] = (int) $meta['promo_fse_title_svg_maxw_mobile'][0];
                
                if ( ! empty( $meta['promo_fse_title'][0] ) ) $parsed_block['attrs']['title'] = $meta['promo_fse_title'][0];
                if ( ! empty( $meta['promo_fse_title_color'][0] ) ) $parsed_block['attrs']['titleColor'] = $meta['promo_fse_title_color'][0];
                if ( ! empty( $meta['promo_fse_title_ff'][0] ) ) $parsed_block['attrs']['titleFontFamily'] = $meta['promo_fse_title_ff'][0];
                if ( isset( $meta['promo_fse_title_fs'][0] ) && $meta['promo_fse_title_fs'][0] !== '' ) $parsed_block['attrs']['titleFontSize'] = (int) $meta['promo_fse_title_fs'][0];
                if ( isset( $meta['promo_fse_title_fs_mobile'][0] ) && $meta['promo_fse_title_fs_mobile'][0] !== '' ) $parsed_block['attrs']['titleFontSizeMobile'] = (int) $meta['promo_fse_title_fs_mobile'][0];
                
                // TEKST OPISU
                if ( ! empty( $meta['promo_fse_body_text'][0] ) ) $parsed_block['attrs']['bodyText'] = $meta['promo_fse_body_text'][0];
                if ( ! empty( $meta['promo_fse_body_color'][0] ) ) $parsed_block['attrs']['bodyTextColor'] = $meta['promo_fse_body_color'][0];
                if ( ! empty( $meta['promo_fse_body_ff'][0] ) ) $parsed_block['attrs']['bodyTextFontFamily'] = $meta['promo_fse_body_ff'][0];
                if ( isset( $meta['promo_fse_body_fs'][0] ) && $meta['promo_fse_body_fs'][0] !== '' ) $parsed_block['attrs']['bodyTextFontSize'] = (int) $meta['promo_fse_body_fs'][0];
                
                // PRZYCISK
                if ( isset( $meta['promo_hide_fse_btn'][0] ) && $meta['promo_hide_fse_btn'][0] === 'yes' ) {
                    // UKRYWAMY PRZYCISK ZGODNIE Z OPCJĄ "UKRYJ PRZYCISK PROMOCJI W BLOKACH FSE" Z KOKPITU
                    $parsed_block['attrs']['buttonText'] = '';
                } else {
                    if ( ! empty( $meta['promo_fse_button_text'][0] ) ) $parsed_block['attrs']['buttonText'] = $meta['promo_fse_button_text'][0];
                    if ( ! empty( $meta['promo_fse_button_link'][0] ) ) $parsed_block['attrs']['buttonLink'] = $meta['promo_fse_button_link'][0];
                    if ( ! empty( $meta['promo_fse_button_bg'][0] ) ) $parsed_block['attrs']['buttonBgColor'] = $meta['promo_fse_button_bg'][0];
                    if ( ! empty( $meta['promo_fse_button_text_color'][0] ) ) $parsed_block['attrs']['buttonTextColor'] = $meta['promo_fse_button_text_color'][0];
                    if ( ! empty( $meta['promo_fse_button_ff'][0] ) ) $parsed_block['attrs']['buttonFontFamily'] = $meta['promo_fse_button_ff'][0];
                    if ( isset( $meta['promo_fse_button_fs'][0] ) && $meta['promo_fse_button_fs'][0] !== '' ) $parsed_block['attrs']['buttonFontSize'] = (int) $meta['promo_fse_button_fs'][0];
                    if ( ! empty( $meta['promo_fse_arrow_bg'][0] ) ) $parsed_block['attrs']['arrowBgColor'] = $meta['promo_fse_arrow_bg'][0];
                    if ( ! empty( $meta['promo_fse_arrow_color'][0] ) ) $parsed_block['attrs']['arrowColor'] = $meta['promo_fse_arrow_color'][0];
                }
            }
        }
        return $parsed_block;
    }
    add_filter( 'render_block_data', 'blendygo_override_fse_block_data', 10, 1 );
}

if ( ! function_exists( 'blendygo_override_header_theme_mods' ) ) {
    // FUNKCJA NADPISUJĄCA THEME MODS DLA HEADERA I MENU MOBILNEGO
    function blendygo_override_header_theme_mods( $default_value, $mod_name ) {
        static $promo_id = null;
        static $meta = null;

        // ZAPOBIEGAMY WIELOKROTNEMU ZAPYTANIU DO BAZY DANYCH POPRZEZ ZACHOWANIE ID PROMOCJI W ZMIENNEJ STATYCZNEJ
        if ( $promo_id === null ) {
            $promo_id = blendygo_get_global_active_cpt_promo();
            if ( $promo_id ) {
                $meta = get_post_meta( $promo_id );
            } else {
                $meta = false; // BRAK AKTYWNEJ PROMOCJI
            }
        }

        if ( ! $promo_id || ! $meta ) {
            return $default_value;
        }

        // MAPOWANIE NAZW THEME MOD NA KLUCZE META Z CPT
        $map = [
            'blendy_header_birthday_btn_enabled' => 'promo_header_btn_enabled',
            'blendy_header_birthday_btn_text'    => 'promo_header_btn_text',
            'blendy_header_birthday_btn_link'    => 'promo_header_btn_link',
            'blendy_header_mm_banner_enabled'    => 'promo_mm_banner_enabled',
            'blendy_header_mm_banner_image'      => 'promo_mm_banner_image',
            'blendy_header_mm_banner_subtitle'   => 'promo_mm_banner_subtitle',
            'blendy_header_mm_banner_title'      => 'promo_mm_banner_title',
            'blendy_header_mm_banner_btn_text'   => 'promo_mm_banner_btn_text',
            'blendy_header_mm_banner_btn_link'   => 'promo_mm_banner_btn_link',
            'blendy_header_mm_banner_use_title_svg'  => 'promo_mm_banner_use_title_svg',
            'blendy_header_mm_banner_title_svg'        => 'promo_mm_banner_title_svg',
            'blendy_header_mm_banner_title_svg_h_val'  => 'promo_mm_banner_title_svg_h_val',
            'blendy_header_mm_banner_title_svg_h_unit' => 'promo_mm_banner_title_svg_h_unit',
            'blendy_header_mm_banner_title_svg_align'  => 'promo_mm_banner_title_svg_align',
            
            'blendy_header_mm_banner_use_subtitle_svg'  => 'promo_mm_banner_use_subtitle_svg',
            'blendy_header_mm_banner_subtitle_svg'      => 'promo_mm_banner_subtitle_svg',
            'blendy_header_mm_banner_subtitle_svg_h_val'=> 'promo_mm_banner_subtitle_svg_h_val',
            'blendy_header_mm_banner_subtitle_svg_h_unit'=> 'promo_mm_banner_subtitle_svg_h_unit',
            'blendy_header_mm_banner_subtitle_svg_align'=> 'promo_mm_banner_subtitle_svg_align',
            
            'blendy_header_mm_banner_use_btn_svg'  => 'promo_mm_banner_use_btn_svg',
            'blendy_header_mm_banner_btn_svg'      => 'promo_mm_banner_btn_svg',
            'blendy_header_mm_banner_btn_svg_h_val'=> 'promo_mm_banner_btn_svg_h_val',
            'blendy_header_mm_banner_btn_svg_h_unit'=> 'promo_mm_banner_btn_svg_h_unit',
            'blendy_header_mm_banner_btn_svg_align'=> 'promo_mm_banner_btn_svg_align',
            
            'blendy_header_mm_banner_title_icon_type'    => 'promo_mm_banner_title_icon_type',
            'blendy_header_mm_banner_title_image'        => 'promo_mm_banner_title_image',
            'blendy_header_mm_banner_subtitle_icon_type' => 'promo_mm_banner_subtitle_icon_type',
            'blendy_header_mm_banner_subtitle_image'     => 'promo_mm_banner_subtitle_image',
            'blendy_header_mm_banner_subtitle_bg'        => 'promo_mm_banner_subtitle_bg',
            'blendy_header_mm_banner_btn_icon_type'      => 'promo_mm_banner_btn_icon_type',
            'blendy_header_mm_banner_btn_image'          => 'promo_mm_banner_btn_image',
        ];

        if ( array_key_exists( $mod_name, $map ) ) {
            $meta_key = $map[ $mod_name ];

            // JEŚLI JEST TO OPCJA HEADER/MENU, SPRAWDZAMY CZY ZAZNACZONO "NADPIŚ" (YES)
            if ( strpos($meta_key, 'promo_header_btn_') === 0 ) {
                if ( !isset($meta['promo_header_btn_enabled'][0]) || $meta['promo_header_btn_enabled'][0] !== 'yes' ) {
                    return $default_value;
                }
            }
            if ( strpos($meta_key, 'promo_mm_banner_') === 0 ) {
                if ( !isset($meta['promo_mm_banner_enabled'][0]) || $meta['promo_mm_banner_enabled'][0] !== 'yes' ) {
                    return $default_value;
                }
            }

            // ZWRACAMY NADPISANĄ WARTOŚĆ, JEŚLI ISTNIEJE W CPT I NIE JEST PUSTA
            if ( isset( $meta[ $meta_key ][0] ) && ( $meta[ $meta_key ][0] !== '' || $meta_key === 'promo_mm_banner_subtitle_bg' ) ) {
                $val = $meta[ $meta_key ][0];
                // DLA PÓŁ WŁĄCZ/WYŁĄCZ (CHECKBOX/SELECT) ZWRACAMY BOOLEAN
                if ( $meta_key === 'promo_header_btn_enabled' || $meta_key === 'promo_mm_banner_enabled' || $meta_key === 'promo_mm_banner_use_title_svg' || $meta_key === 'promo_mm_banner_use_subtitle_svg' || $meta_key === 'promo_mm_banner_use_btn_svg' ) {
                    return ( $val === 'yes' ) ? true : false;
                }
                return $val;
            }
        }

        return $default_value;
    }

    // REJESTRACJA FILTRÓW DLA KAŻDEGO MODYFIKOWANEGO POLA
    $theme_mods_to_override = [
        'blendy_header_birthday_btn_enabled',
        'blendy_header_birthday_btn_text',
        'blendy_header_birthday_btn_link',
        'blendy_header_mm_banner_enabled',
        'blendy_header_mm_banner_image',
        'blendy_header_mm_banner_subtitle',
        'blendy_header_mm_banner_title',
        'blendy_header_mm_banner_btn_text',
        'blendy_header_mm_banner_btn_link',
        'blendy_header_mm_banner_use_title_svg',
        'blendy_header_mm_banner_title_svg',
        'blendy_header_mm_banner_title_svg_h_val',
        'blendy_header_mm_banner_title_svg_h_unit',
        'blendy_header_mm_banner_title_svg_align',
        'blendy_header_mm_banner_use_subtitle_svg',
        'blendy_header_mm_banner_subtitle_svg',
        'blendy_header_mm_banner_subtitle_svg_h_val',
        'blendy_header_mm_banner_subtitle_svg_h_unit',
        'blendy_header_mm_banner_subtitle_svg_align',
        'blendy_header_mm_banner_use_btn_svg',
        'blendy_header_mm_banner_btn_svg',
        'blendy_header_mm_banner_btn_svg_h_val',
        'blendy_header_mm_banner_btn_svg_h_unit',
        'blendy_header_mm_banner_btn_svg_align',
        'blendy_header_mm_banner_title_icon_type',
        'blendy_header_mm_banner_title_image',
        'blendy_header_mm_banner_subtitle_icon_type',
        'blendy_header_mm_banner_subtitle_image',
        'blendy_header_mm_banner_subtitle_bg',
        'blendy_header_mm_banner_btn_icon_type',
        'blendy_header_mm_banner_btn_image'
    ];

    foreach ( $theme_mods_to_override as $mod ) {
        add_filter( "theme_mod_{$mod}", function( $default_value ) use ( $mod ) {
            return blendygo_override_header_theme_mods( $default_value, $mod );
        }, 10, 1 );
    }
}

// CZYSZCZENIE CACHE LITESPEED
add_action('blendygo_cron_purge_cache', function() { if (class_exists('LiteSpeed_Cache_API')) LiteSpeed_Cache_API::purge_all(); });
add_action('save_post_promocje', function($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    wp_clear_scheduled_hook('blendygo_cron_purge_cache');
    $dates = [get_post_meta($post_id, 'promo_date_start', true), get_post_meta($post_id, 'promo_date_ext_start', true), get_post_meta($post_id, 'promo_date_final', true), get_post_meta($post_id, 'promo_date_final_fixed', true), get_post_meta($post_id, 'promo_date_remove_ui', true)];
    foreach ($dates as $d) { if ($d && strtotime($d) > current_time('timestamp')) wp_schedule_single_event(strtotime($d) + 30, 'blendygo_cron_purge_cache'); }
    if (class_exists('LiteSpeed_Cache_API')) LiteSpeed_Cache_API::purge_all();
});

add_action( 'wp', function() {
    add_action( 'woocommerce_before_single_product', 'blendygo_render_cpt_banner', 5 );
    add_action( 'woocommerce_before_main_content', 'blendygo_render_cpt_shop_banner', 5 );
    add_action( 'woocommerce_before_cart', 'blendygo_render_cpt_cart_banner', 1 );
}, 100 );

/**
 * =========================================================================
 * MODUŁ: DROPY WARIANTÓW (LIMITOWANA WIDOCZNOŚĆ)
 * =========================================================================
 */
if ( ! function_exists( 'blendygo_cpt_variation_visibility' ) ) {
    function blendygo_cpt_variation_visibility( $visible, $arg2 = null, $arg3 = null, $arg4 = null ) {
        if ( ! function_exists( 'get_posts' ) ) return $visible;

        $variation_id = 0;
        if ( is_object($arg4) && method_exists($arg4, 'get_id') ) {
            $variation_id = $arg4->get_id();
        } elseif ( is_object($arg3) && method_exists($arg3, 'get_id') ) {
            $variation_id = $arg3->get_id();
        } elseif ( is_object($arg2) && method_exists($arg2, 'get_id') ) {
            $variation_id = $arg2->get_id();
        } elseif ( is_numeric($arg2) ) {
            $variation_id = intval($arg2);
        }

        if ( ! $variation_id ) return $visible;

        static $promos = null;
        if ( $promos === null ) {
            $args = [
                'post_type'      => 'promocje',
                'post_status'    => 'publish',
                'posts_per_page' => -1,
            ];
            $promos = get_posts( $args );
        }
        
        $is_drop_in_any_promo = false;
        $is_drop_active = false;
        
        foreach ( $promos as $promo ) {
            $drop_str = get_post_meta( $promo->ID, 'promo_drop_variations', true );
            if ( ! empty( $drop_str ) ) {
                $drop_ids = array_map( 'intval', array_map( 'trim', explode( ',', $drop_str ) ) );
                if ( in_array( $variation_id, $drop_ids ) ) {
                    $is_drop_in_any_promo = true;
                    $phase = blendygo_get_promo_phase( $promo->ID );
                    if ( $phase >= 1 && $phase <= 3 ) {
                        $is_drop_active = true;
                        break; 
                    }
                }
            }
        }
        
        if ( $is_drop_in_any_promo ) {
            // Jeśli to hook od ukrywania (true = ukryj), odwracamy logikę
            if ( current_filter() === 'woocommerce_hide_invisible_variations' ) {
                return ! $is_drop_active; 
            }
            return $is_drop_active; 
        }
        
        // Jeśli wariant nie jest zablokowany jako drop, widoczność zostaje bez zmian
        return $visible; 
    }
    // Zostawiamy stare hooki na wszelki wypadek
    add_filter( 'woocommerce_variation_is_visible', 'blendygo_cpt_variation_visibility', 9999, 4 );
    add_filter( 'woocommerce_variation_is_active', 'blendygo_cpt_variation_visibility', 9999, 2 );
    add_filter( 'woocommerce_variation_is_purchasable', 'blendygo_cpt_variation_visibility', 9999, 2 );
    add_filter( 'woocommerce_hide_invisible_variations', 'blendygo_cpt_variation_visibility', 9999, 4 );
}

if ( ! function_exists( 'blendygo_cpt_filter_product_children' ) ) {
    function blendygo_cpt_filter_product_children( $children, $product, $visible_only ) {
        if ( ! function_exists( 'get_posts' ) || empty( $children ) || is_admin() && ! wp_doing_ajax() ) {
            return $children;
        }

        static $promos = null;
        if ( $promos === null ) {
            $args = [
                'post_type'      => 'promocje',
                'post_status'    => 'publish',
                'posts_per_page' => -1,
            ];
            $promos = get_posts( $args );
        }

        $filtered_children = [];

        foreach ( $children as $variation_id ) {
            $is_drop_in_any_promo = false;
            $is_drop_active = false;
            
            foreach ( $promos as $promo ) {
                $drop_str = get_post_meta( $promo->ID, 'promo_drop_variations', true );
                if ( ! empty( $drop_str ) ) {
                    $drop_ids = array_map( 'intval', array_map( 'trim', explode( ',', $drop_str ) ) );
                    if ( in_array( $variation_id, $drop_ids ) ) {
                        $is_drop_in_any_promo = true;
                        $phase = blendygo_get_promo_phase( $promo->ID );
                        if ( $phase >= 1 && $phase <= 3 ) {
                            $is_drop_active = true;
                            break; 
                        }
                    }
                }
            }
            
            // Wariant zostaje na liście jeśli: NIE jest dropem LUB JEST aktywnym dropem
            if ( ! $is_drop_in_any_promo || $is_drop_active ) {
                $filtered_children[] = $variation_id;
            }
        }
        
        return $filtered_children;
    }
    // Przefiltrowanie wszystkich dzieci, całkowicie ukrywa wariant przed istnieniem w JS
    add_filter( 'woocommerce_get_children', 'blendygo_cpt_filter_product_children', 9999, 3 );
}


/**
 * =========================================================================
 * MODUŁ: DYNAMIC PRICING W LOCIE
 * =========================================================================
 */
if ( ! function_exists( 'blendygo_cpt_dynamic_pricing' ) ) {
    function blendygo_cpt_dynamic_pricing( $price, $product ) {
        if ( is_admin() && ! wp_doing_ajax() ) return $price;
        if ( ! $product ) return $price;
        
        static $processing = [];
        $prod_id = $product->get_id();
        $parent_id = $product->is_type('variation') ? $product->get_parent_id() : $prod_id;
        
        if ( isset($processing[$prod_id]) ) return $price;
        $processing[$prod_id] = true;

        $promo_id = null;
        $specific_promo = blendygo_get_active_cpt_promo( $parent_id );
        $global_promo = blendygo_get_global_active_cpt_promo();
        
        $candidate_promos = [];
        if ($specific_promo) $candidate_promos[] = $specific_promo;
        if ($global_promo) $candidate_promos[] = $global_promo;
        
        foreach ($candidate_promos as $pid) {
            $phase = blendygo_get_promo_phase( $pid );
            if ( $phase < 1 || $phase > 3 ) continue; 
            
            // Przechodzimy przez 5 możliwych reguł zniżkowych w ramach danej promocji
            for ($d = 1; $d <= 5; $d++) {
                $type = get_post_meta( $pid, 'promo_discount_type_'.$d, true );
                if ( empty($type) || $type === 'none' ) continue;
                
                $scope = get_post_meta( $pid, 'promo_discount_scope_'.$d, true );
                $val = (float) str_replace(',', '.', get_post_meta( $pid, 'promo_discount_value_'.$d, true ));
                
                if ( $val <= 0 ) continue;
                
                $is_applicable = false;
                if ( $scope === 'all' ) {
                    $is_applicable = true;
                } elseif ( $scope === 'selected' ) {
                    $targets_str = get_post_meta( $pid, 'promo_discount_targets_'.$d, true );
                    $cats_str = get_post_meta( $pid, 'promo_discount_categories_'.$d, true );
                    
                    if ( !empty($targets_str) ) {
                        $targets = array_map('intval', explode(',', $targets_str));
                        // Sprawdzamy, czy ten produkt znajduje się w targetach
                        if ( in_array($prod_id, $targets) || ($product->is_type('variation') && in_array($product->get_parent_id(), $targets)) ) {
                            $is_applicable = true;
                        }
                    }
                    
                    if ( ! $is_applicable && !empty($cats_str) ) {
                        $cats = array_map('intval', explode(',', $cats_str));
                        $check_id = $product->is_type('variation') ? $product->get_parent_id() : $prod_id;
                        if ( has_term($cats, 'product_cat', $check_id) ) {
                            $is_applicable = true;
                        }
                    }
                    
                    if ( ! $is_applicable && empty($targets_str) && empty($cats_str) && $pid === $specific_promo ) {
                        // Fallback jeśli ktoś nic nie wybrał ale to specyficzne promo
                        $is_applicable = true;
                    }
                }
                
                if ( $is_applicable ) {
                    $promo_id = $pid;
                    $active_type = $type;
                    $active_val = $val;
                    break 2;
                }
            }
        }
        
        if ( $promo_id ) {
            $regular_price = (float) $product->get_regular_price();
            if ( ! $regular_price ) $regular_price = (float) $price; 
            
            $new_price = $price;
            
            if ( $active_type === 'percentage' ) {
                $new_price = $regular_price - ($regular_price * ($active_val / 100));
            } elseif ( $active_type === 'fixed' ) {
                $new_price = $regular_price - $active_val;
            }
            
            if ( $new_price < 0 ) $new_price = 0;
            
            unset($processing[$prod_id]);
            return $new_price;
        }
        
        unset($processing[$prod_id]);
        return $price;
    }
    
    add_filter( 'woocommerce_product_get_price', 'blendygo_cpt_dynamic_pricing', 99, 2 );
    add_filter( 'woocommerce_product_variation_get_price', 'blendygo_cpt_dynamic_pricing', 99, 2 );
    add_filter( 'woocommerce_product_get_sale_price', 'blendygo_cpt_dynamic_pricing', 99, 2 );
    add_filter( 'woocommerce_product_variation_get_sale_price', 'blendygo_cpt_dynamic_pricing', 99, 2 );

    // Filtry dla wewnętrznej tablicy cen wariantów (rozwiązuje problem na karcie produktu)
    function blendygo_cpt_dynamic_variation_prices( $price, $variation, $product ) {
        return blendygo_cpt_dynamic_pricing( $price, $variation );
    }
    add_filter( 'woocommerce_variation_prices_price', 'blendygo_cpt_dynamic_variation_prices', 99, 3 );
    add_filter( 'woocommerce_variation_prices_sale_price', 'blendygo_cpt_dynamic_variation_prices', 99, 3 );

    // Unikanie cachowania złych cen wariantów (zależność od fazy i ID promocji)
    function blendygo_cpt_prices_hash( $price_hash, $product, $for_display ) {
        $promo_id = blendygo_get_active_cpt_promo( $product->get_id() );
        if ( $promo_id ) {
            $price_hash[] = 'promo_' . $promo_id . '_phase_' . blendygo_get_promo_phase( $promo_id );
        }
        return $price_hash;
    }
    add_filter( 'woocommerce_get_variation_prices_hash', 'blendygo_cpt_prices_hash', 99, 3 );
}