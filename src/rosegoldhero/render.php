<?php
// =============================================================================
// Drop — Hero z licznikiem (rosegoldhero)
// Reużywalny hero promocji: tło + header + badge + tytuł + podtytuł + countdown.
// =============================================================================
$themeUri = get_template_directory_uri();

$backgroundImage = isset($attributes['backgroundImage']) && $attributes['backgroundImage']
    ? $attributes['backgroundImage']
    : $themeUri . '/images/rosegold/hero-bg.jpg';
$overlayOpacity = isset($attributes['overlayOpacity']) ? (float) $attributes['overlayOpacity'] : 50;
$bgPosX = isset($attributes['bgPosX']) ? (float) $attributes['bgPosX'] : 50;
$bgPosY = isset($attributes['bgPosY']) ? (float) $attributes['bgPosY'] : 50;

$logoImage = isset($attributes['logoImage']) && $attributes['logoImage']
    ? $attributes['logoImage']
    : esc_url(home_url('/wp-content/uploads/2026/05/SW_logo_v1.png'));
$logoAlt = isset($attributes['logoAlt']) ? $attributes['logoAlt'] : 'Shav Woman';
$logoURL = isset($attributes['logoURL']) ? $attributes['logoURL'] : '/';

$showCart = isset($attributes['showCart']) ? $attributes['showCart'] : true;
$cartURL  = isset($attributes['cartURL']) ? $attributes['cartURL'] : '/koszyk';

$badge         = isset($attributes['badge']) ? $attributes['badge'] : 'URODZINY SHAV WOMAN';
$title         = isset($attributes['title']) ? $attributes['title'] : 'Drop Rose Gold';
$subtitle1     = isset($attributes['subtitle1']) ? $attributes['subtitle1'] : 'Najniższe ceny w historii!';
$subtitleEmoji = isset($attributes['subtitleEmoji']) ? $attributes['subtitleEmoji'] : '🎂';
$subtitle2     = isset($attributes['subtitle2']) ? $attributes['subtitle2'] : 'Pozostało jeszcze:';
$showCountdown = isset($attributes['showCountdown']) ? $attributes['showCountdown'] : true;
$countdownDate = isset($attributes['countdownDate']) ? $attributes['countdownDate'] : '';

$labelDays    = isset($attributes['labelDays']) ? $attributes['labelDays'] : 'dni';
$labelHours   = isset($attributes['labelHours']) ? $attributes['labelHours'] : 'godziny';
$labelMinutes = isset($attributes['labelMinutes']) ? $attributes['labelMinutes'] : 'minuty';
$labelSeconds = isset($attributes['labelSeconds']) ? $attributes['labelSeconds'] : 'sekundy';

$cart_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none"><path d="M27.3444 15.9388C25.8223 5.05214 24.5007 4.33334 16.0059 4.33334C7.49682 4.33334 6.17389 5.04947 4.65562 15.9245C3.99349 20.668 4.35615 23.4336 5.86895 25.1732C7.68402 27.2591 10.9555 27.6665 15.9939 27.6665C21.0402 27.6665 24.3168 27.2577 26.1326 25.1704C27.6439 23.4336 28.0066 20.6732 27.3444 15.9388ZM24.6244 23.8581C23.3146 25.3648 20.3516 25.6667 15.9948 25.6667C11.6459 25.6667 8.68815 25.3645 7.37895 23.8593C6.29895 22.6193 6.07029 20.2565 6.63682 16.2005C8.01442 6.33387 8.46615 6.33387 16.0066 6.33387C23.5326 6.33387 23.9831 6.33387 25.3646 16.2153C25.9298 20.2631 25.7019 22.6197 24.6244 23.8581Z" fill="#252525"/><path d="M19.3334 9C19.0682 9 18.8138 9.10536 18.6263 9.29289C18.4388 9.48043 18.3334 9.73478 18.3334 10C18.3334 10.6188 18.0876 11.2123 17.65 11.6499C17.2124 12.0875 16.6189 12.3333 16.0001 12.3333C15.3812 12.3333 14.7877 12.0875 14.3502 11.6499C13.9126 11.2123 13.6667 10.6188 13.6667 10C13.6667 9.73478 13.5614 9.48043 13.3739 9.29289C13.1863 9.10536 12.932 9 12.6667 9C12.4015 9 12.1472 9.10536 11.9596 9.29289C11.7721 9.48043 11.6667 9.73478 11.6667 10C11.6667 11.1493 12.1233 12.2515 12.936 13.0641C13.7486 13.8768 14.8508 14.3333 16.0001 14.3333C17.1494 14.3333 18.2516 13.8768 19.0642 13.0641C19.8769 12.2515 20.3334 11.1493 20.3334 10C20.3334 9.73478 20.2281 9.48043 20.0405 9.29289C19.853 9.10536 19.5986 9 19.3334 9Z" fill="#252525"/></svg>';
$hamburger_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5"/></svg>';

$cart_href = function_exists('wc_get_cart_url') ? wc_get_cart_url() : esc_url(home_url($cartURL));

$countdown_tiles = [
    ['days', $labelDays],
    ['hours', $labelHours],
    ['minutes', $labelMinutes],
    ['seconds', $labelSeconds],
];
?>
<section class="rosegoldhero">
    <div class="rosegoldhero__bg"
        style="background-image:url('<?php echo esc_url($backgroundImage); ?>'); background-position:<?php echo esc_attr($bgPosX); ?>% <?php echo esc_attr($bgPosY); ?>%;">
        <span class="rosegoldhero__overlay" style="background: rgba(0,0,0,<?php echo esc_attr($overlayOpacity / 100); ?>);"
            aria-hidden="true"></span>

        <header class="rosegoldhero__header header" id="rosegoldhero-menu">
            <div class="header__inner">
                <a class="header__logo" href="<?php echo esc_url(site_url($logoURL)); ?>">
                    <img src="<?php echo esc_url($logoImage); ?>" alt="<?php echo esc_attr($logoAlt); ?>">
                </a>

                <nav class="header__nav">
                    <button class="header__nav-close" aria-label="Zamknij menu" type="button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                        </svg>
                    </button>
                    <?php for ($i = 1; $i <= 4; $i++):
                        $label = isset($attributes["nav{$i}Label"]) ? $attributes["nav{$i}Label"] : '';
                        $url = isset($attributes["nav{$i}URL"]) ? $attributes["nav{$i}URL"] : '';
                        if (!$label) continue; ?>
                        <a href="<?php echo esc_url(home_url($url)); ?>"><?php echo wp_kses_post($label); ?></a>
                    <?php endfor; ?>
                    <?php if (function_exists('shav_render_menu_banners')) echo shav_render_menu_banners(); ?>
                </nav>

                <?php if ($showCart): ?>
                    <a class="header__cart" href="<?php echo esc_url($cart_href); ?>" aria-label="Koszyk">
                        <?php echo $cart_svg; ?>
                        <?php if (function_exists('WC') && WC()->cart): ?>
                            <span class="cart-count"><?php echo (int) WC()->cart->get_cart_contents_count(); ?></span>
                        <?php endif; ?>
                    </a>
                <?php endif; ?>

                <button class="header__hamburger" aria-label="Otwórz menu" aria-expanded="false">
                    <?php echo $hamburger_svg; ?>
                </button>
            </div>
        </header>

        <div class="rosegoldhero__content">
            <div class="rosegoldhero__inner">
                <?php if ($badge): ?>
                    <span class="rosegoldhero__badge"><?php echo wp_kses_post($badge); ?></span>
                <?php endif; ?>
                <h1 class="rosegoldhero__title"><?php echo wp_kses_post($title); ?></h1>
                <p class="rosegoldhero__subtitle">
                    <span><?php echo wp_kses_post($subtitle1); ?></span>
                    <?php if ($subtitleEmoji): ?><span><?php echo wp_kses_post($subtitleEmoji); ?></span><?php endif; ?>
                    <span class="rosegoldhero__subtitle-2"><?php echo wp_kses_post($subtitle2); ?></span>
                </p>
                <?php if ($showCountdown): ?>
                    <span class="rosegoldhero__divider" aria-hidden="true"></span>
                    <div class="rosegoldhero__countdown" data-countdown="<?php echo esc_attr($countdownDate); ?>">
                        <?php foreach ($countdown_tiles as $tile): ?>
                            <div class="rosegoldhero__tile">
                                <span class="rosegoldhero__tile-num" data-unit="<?php echo esc_attr($tile[0]); ?>">--</span>
                                <span class="rosegoldhero__tile-label"><?php echo wp_kses_post($tile[1]); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<script>
    (function () {
        // --- Mobile menu (jak globalny header) ---
        document.querySelectorAll('#rosegoldhero-menu').forEach((header) => {
            const hamburger = header.querySelector('.header__hamburger');
            const mobileMenu = header.querySelector('.header__nav');
            if (hamburger && mobileMenu) {
                hamburger.addEventListener('click', () => {
                    const expanded = hamburger.getAttribute('aria-expanded') === 'true';
                    hamburger.setAttribute('aria-expanded', !expanded);
                    mobileMenu.classList.toggle('header__nav--open');
                    document.body.classList.toggle('has-mobile-menu-open');
                });
                mobileMenu.querySelectorAll('a').forEach((link) => link.addEventListener('click', () => {
                    hamburger.setAttribute('aria-expanded', 'false');
                    mobileMenu.classList.remove('header__nav--open');
                    document.body.classList.remove('has-mobile-menu-open');
                }));
                const closeBtn = mobileMenu.querySelector('.header__nav-close');
                if (closeBtn) closeBtn.addEventListener('click', () => {
                    hamburger.setAttribute('aria-expanded', 'false');
                    mobileMenu.classList.remove('header__nav--open');
                    document.body.classList.remove('has-mobile-menu-open');
                });
            }
        });

        // --- Countdown ---
        document.querySelectorAll('.rosegoldhero__countdown').forEach((box) => {
            const raw = (box.dataset.countdown || '').trim();
            let target = raw ? new Date(raw.replace(' ', 'T')).getTime() : NaN;
            // brak/niepoprawna data => evergreen: +3 dni od teraz
            if (isNaN(target) || target <= Date.now()) {
                target = Date.now() + 3 * 24 * 60 * 60 * 1000;
            }
            const nums = {};
            box.querySelectorAll('.rosegoldhero__tile-num').forEach((el) => { nums[el.dataset.unit] = el; });
            const pad = (n) => String(n).padStart(2, '0');

            function tick() {
                let diff = Math.max(0, target - Date.now());
                const d = Math.floor(diff / 86400000); diff -= d * 86400000;
                const h = Math.floor(diff / 3600000); diff -= h * 3600000;
                const m = Math.floor(diff / 60000); diff -= m * 60000;
                const s = Math.floor(diff / 1000);
                if (nums.days) nums.days.textContent = pad(d);
                if (nums.hours) nums.hours.textContent = pad(h);
                if (nums.minutes) nums.minutes.textContent = pad(m);
                if (nums.seconds) nums.seconds.textContent = pad(s);
            }
            tick();
            setInterval(tick, 1000);
        });
    })();
</script>
