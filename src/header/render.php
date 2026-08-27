<?php
$custom_logo_id = get_theme_mod('custom_logo');
$custom_logo_url = $custom_logo_id ? wp_get_attachment_image_url($custom_logo_id, 'full') : false;

$logoImage = (isset($attributes['logoImage']) && $attributes['logoImage'])
    ? $attributes['logoImage']
    : ($custom_logo_url ?: esc_url(home_url('/wp-content/uploads/shav-logo.png')));
$logoAlt = isset($attributes['logoAlt']) ? $attributes['logoAlt'] : 'Shav Logo';
$logoURL = isset($attributes['logoURL']) ? $attributes['logoURL'] : '/';
$showCart = isset($attributes['showCart']) ? $attributes['showCart'] : true;
$cartURL = isset($attributes['cartURL']) ? $attributes['cartURL'] : '/koszyk';
$hamburgerLabel = isset($attributes['hamburgerLabel']) ? $attributes['hamburgerLabel'] : 'Otwórz menu';
$menuId = isset($attributes['menuId']) ? (int) $attributes['menuId'] : 0;

if (!$menuId) {
    $locations = get_nav_menu_locations();
    if (isset($locations['primary_menu']) && $locations['primary_menu']) {
        $menuId = $locations['primary_menu'];
    } else {
        $menus = wp_get_nav_menus();
        if (!empty($menus)) {
            $menuId = $menus[0]->term_id;
        }
    }
}

$showWhatsapp = isset($attributes['showWhatsapp']) ? $attributes['showWhatsapp'] : false;
$whatsappUrl = isset($attributes['whatsappUrl']) ? $attributes['whatsappUrl'] : 'https://wa.me/';
$whatsappQrImage = isset($attributes['whatsappQrImage']) ? $attributes['whatsappQrImage'] : '';
$whatsappQrText = isset($attributes['whatsappQrText']) ? $attributes['whatsappQrText'] : '';
$whatsappButtonText = isset($attributes['whatsappButtonText']) ? $attributes['whatsappButtonText'] : 'Napisz do nas';

$whatsapp_svg = '<svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M25.3991 6.54413C24.1765 5.30961 22.7205 4.33076 21.1158 3.66462C19.5111 2.99848 17.7899 2.65838 16.0524 2.66413C8.77242 2.66413 2.83909 8.59747 2.83909 15.8775C2.83909 18.2108 3.45242 20.4775 4.59909 22.4775L2.73242 29.3308L9.73242 27.4908C11.6658 28.5441 13.8391 29.1041 16.0524 29.1041C23.3324 29.1041 29.2658 23.1708 29.2658 15.8908C29.2658 12.3575 27.8924 9.03747 25.3991 6.54413ZM16.0524 26.8641C14.0791 26.8641 12.1458 26.3308 10.4524 25.3308L10.0524 25.0908L5.89242 26.1841L6.99909 22.1308L6.73242 21.7175C5.63582 19.9669 5.05365 17.9432 5.05242 15.8775C5.05242 9.82414 9.98575 4.8908 16.0391 4.8908C18.9724 4.8908 21.7324 6.03747 23.7991 8.11747C24.8226 9.13595 25.6336 10.3475 26.1853 11.6819C26.7369 13.0163 27.018 14.4469 27.0124 15.8908C27.0391 21.9441 22.1058 26.8641 16.0524 26.8641ZM22.0791 18.6508C21.7458 18.4908 20.1191 17.6908 19.8258 17.5708C19.5191 17.4641 19.3058 17.4108 19.0791 17.7308C18.8524 18.0641 18.2258 18.8108 18.0391 19.0241C17.8524 19.2508 17.6524 19.2775 17.3191 19.1041C16.9858 18.9441 15.9191 18.5841 14.6658 17.4641C13.6791 16.5841 13.0258 15.5041 12.8258 15.1708C12.6391 14.8375 12.7991 14.6641 12.9724 14.4908C13.1191 14.3441 13.3058 14.1041 13.4658 13.9175C13.6258 13.7308 13.6924 13.5841 13.7991 13.3708C13.9058 13.1441 13.8524 12.9575 13.7724 12.7975C13.6924 12.6375 13.0258 11.0108 12.7591 10.3441C12.4924 9.70413 12.2124 9.78413 12.0124 9.7708H11.3724C11.1458 9.7708 10.7991 9.8508 10.4924 10.1841C10.1991 10.5175 9.34575 11.3175 9.34575 12.9441C9.34575 14.5708 10.5324 16.1441 10.6924 16.3575C10.8524 16.5841 13.0258 19.9175 16.3324 21.3441C17.1191 21.6908 17.7324 21.8908 18.2124 22.0375C18.9991 22.2908 19.7191 22.2508 20.2924 22.1708C20.9324 22.0775 22.2524 21.3708 22.5191 20.5975C22.7991 19.8241 22.7991 19.1708 22.7058 19.0241C22.6124 18.8775 22.4124 18.8108 22.0791 18.6508Z" fill="currentColor"/>
</svg>';

$cart_svg = '<svg aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none"><path d="M27.3444 15.9388C25.8223 5.05214 24.5007 4.33334 16.0059 4.33334C7.49682 4.33334 6.17389 5.04947 4.65562 15.9245C3.99349 20.668 4.35615 23.4336 5.86895 25.1732C7.68402 27.2591 10.9555 27.6665 15.9939 27.6665C21.0402 27.6665 24.3168 27.2577 26.1326 25.1704C27.6439 23.4336 28.0066 20.6732 27.3444 15.9388ZM24.6244 23.8581C23.3146 25.3648 20.3516 25.6667 15.9948 25.6667C11.6459 25.6667 8.68815 25.3645 7.37895 23.8593C6.29895 22.6193 6.07029 20.2565 6.63682 16.2005C8.01442 6.33387 8.46615 6.33387 16.0066 6.33387C23.5326 6.33387 23.9831 6.33387 25.3646 16.2153C25.9298 20.2631 25.7019 22.6197 24.6244 23.8581Z" fill="#252525"/><path d="M19.3334 9C19.0682 9 18.8138 9.10536 18.6263 9.29289C18.4388 9.48043 18.3334 9.73478 18.3334 10C18.3334 10.6188 18.0876 11.2123 17.65 11.6499C17.2124 12.0875 16.6189 12.3333 16.0001 12.3333C15.3812 12.3333 14.7877 12.0875 14.3502 11.6499C13.9126 11.2123 13.6667 10.6188 13.6667 10C13.6667 9.73478 13.5614 9.48043 13.3739 9.29289C13.1863 9.10536 12.932 9 12.6667 9C12.4015 9 12.1472 9.10536 11.9596 9.29289C11.7721 9.48043 11.6667 9.73478 11.6667 10C11.6667 11.1493 12.1233 12.2515 12.936 13.0641C13.7486 13.8768 14.8508 14.3333 16.0001 14.3333C17.1494 14.3333 18.2516 13.8768 19.0642 13.0641C19.8769 12.2515 20.3334 11.1493 20.3334 10C20.3334 9.73478 20.2281 9.48043 20.0405 9.29289C19.853 9.10536 19.5986 9 19.3334 9Z" fill="#252525"/></svg>';

$hamburger_svg = '<svg aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5"/></svg>';

$menu_items = $menuId ? wp_get_nav_menu_items($menuId) : false;
?>
<?php 
$topbar_data = function_exists('shav_get_topbar_data') ? shav_get_topbar_data() : false;
if ($topbar_data): 
?>
<div class="shav-topbar" style="background: <?php echo esc_attr($topbar_data['bg']); ?>; color: <?php echo esc_attr($topbar_data['color']); ?>;">
    <div class="shav-topbar__inner">
        <span class="shav-topbar__text"><?php echo wp_kses_post($topbar_data['text']); ?></span>
        
        <div class="shav-topbar__right">
            <?php if (!empty($topbar_data['coupon'])): ?>
                <div class="shav-topbar__coupon-wrapper">
                    <span class="shav-topbar__separator">|</span>
                    <button type="button" class="shav-topbar__coupon" data-coupon="<?php echo esc_attr($topbar_data['coupon']); ?>">
                        <span class="shav-topbar__coupon-label">
                            Code kopieren
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_1809_5814)">
                                <path d="M10 13.3333H3.33333C2.4496 13.3323 1.60237 12.9807 0.97748 12.3559C0.352588 11.731 0.00105857 10.8837 0 10L0 3.33333C0.00105857 2.4496 0.352588 1.60237 0.97748 0.97748C1.60237 0.352588 2.4496 0.00105857 3.33333 0L10 0C10.8837 0.00105857 11.731 0.352588 12.3559 0.97748C12.9807 1.60237 13.3323 2.4496 13.3333 3.33333V10C13.3323 10.8837 12.9807 11.731 12.3559 12.3559C11.731 12.9807 10.8837 13.3323 10 13.3333ZM3.33333 1.33333C2.8029 1.33333 2.29419 1.54405 1.91912 1.91912C1.54405 2.29419 1.33333 2.8029 1.33333 3.33333V10C1.33333 10.5304 1.54405 11.0391 1.91912 11.4142C2.29419 11.7893 2.8029 12 3.33333 12H10C10.5304 12 11.0391 11.7893 11.4142 11.4142C11.7893 11.0391 12 10.5304 12 10V3.33333C12 2.8029 11.7893 2.29419 11.4142 1.91912C11.0391 1.54405 10.5304 1.33333 10 1.33333H3.33333ZM16 12.6667V4C16 3.82319 15.9298 3.65362 15.8047 3.5286C15.6797 3.40357 15.5101 3.33333 15.3333 3.33333C15.1565 3.33333 14.987 3.40357 14.8619 3.5286C14.7369 3.65362 14.6667 3.82319 14.6667 4V12.6667C14.6667 13.1971 14.456 13.7058 14.0809 14.0809C13.7058 14.456 13.1971 14.6667 12.6667 14.6667H4C3.82319 14.6667 3.65362 14.7369 3.5286 14.8619C3.40357 14.987 3.33333 15.1565 3.33333 15.3333C3.33333 15.5101 3.40357 15.6797 3.5286 15.8047C3.65362 15.9298 3.82319 16 4 16H12.6667C13.5504 15.9989 14.3976 15.6474 15.0225 15.0225C15.6474 14.3976 15.9989 13.5504 16 12.6667Z" fill="currentColor"/>
                                </g>
                                <defs>
                                <clipPath id="clip0_1809_5814">
                                <rect width="16" height="16" fill="white"/>
                                </clipPath>
                                </defs>
                            </svg>
                        </span>
                    </button>
                </div>
            <?php endif; ?>

            <div class="shav-topbar__trustpilot">
                <?php 
                $tp_link = isset($topbar_data['trustpilot']) ? $topbar_data['trustpilot'] : '';
                if (!empty($tp_link)): ?>
                    <a href="<?php echo esc_url($tp_link); ?>" target="_blank" rel="noopener noreferrer" class="shav-topbar__trustpilot-link">
                <?php else: ?>
                    <span class="shav-topbar__trustpilot-link">
                <?php endif; ?>
                
                <span class="shav-topbar__trustpilot-rating">4.9</span>
                <div class="shav-topbar__trustpilot-stars">
                    <?php for($i=0; $i<5; $i++): ?>
                        <svg class="star-small" viewBox="0 0 22 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M21.5546 7.79727H13.3236L10.7812 0L8.23093 7.79727L0 7.78936L6.66578 12.6132L4.11547 20.4026L10.7812 15.5866L17.4391 20.4026L14.8967 12.6132L21.5546 7.79727Z" fill="currentColor"/>
                        </svg>
                    <?php endfor; ?>
                </div>
                <div class="shav-topbar__trustpilot-logo">
                    <svg class="star-big" viewBox="0 0 22 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M21.5546 7.79727H13.3236L10.7812 0L8.23093 7.79727L0 7.78936L6.66578 12.6132L4.11547 20.4026L10.7812 15.5866L17.4391 20.4026L14.8967 12.6132L21.5546 7.79727Z" fill="currentColor"/>
                    </svg>
                    <span class="shav-topbar__trustpilot-text">Trustpilot</span>
                </div>

                <?php if (!empty($tp_link)): ?>
                    </a>
                <?php else: ?>
                    </span>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const topbar = document.querySelector('.shav-topbar');
    const couponBtn = document.querySelector('.shav-topbar__coupon');
    if (couponBtn && topbar) {
        couponBtn.addEventListener('click', function() {
            const code = this.getAttribute('data-coupon');
            navigator.clipboard.writeText(code).then(() => {
                const textEl = this.querySelector('.shav-topbar__coupon-label');
                const originalHTML = textEl.innerHTML;
                textEl.innerText = 'Kopiert!';
                setTimeout(() => {
                    topbar.classList.add('is-copied-hidden');
                }, 1500);
            });
        });
    }
});
</script>
<?php endif; ?>
<header class="header" id="top-menu">
    <div class="header__inner">
        <a class="header__logo" href="<?php echo esc_url(site_url($logoURL)); ?>">
            <img src="<?php echo esc_url($logoImage); ?>" alt="<?php echo esc_attr($logoAlt); ?>">
        </a>

        <nav class="header__nav" aria-label="Główna nawigacja">
            <button class="header__nav-close" aria-label="Zamknij menu" type="button">
                <svg aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    viewBox="0 0 24 24" fill="none">
                    <path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                </svg>
            </button>

            <?php if ($menu_items && !is_wp_error($menu_items)): ?>
                <?php foreach ($menu_items as $item): ?>
                    <a href="<?php echo esc_url($item->url); ?>" target="<?php echo esc_attr($item->target ?: '_self'); ?>">
                        <?php echo wp_kses_post($item->title); ?>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if ($showWhatsapp): ?>
                <a class="header__nav-whatsapp-mobile" href="<?php echo esc_url($whatsappUrl); ?>" target="_blank"
                    aria-label="WhatsApp">
                    <span style="display: flex; align-items: center; gap: 8px;">
                        WhatsApp
                        <svg width="24" height="24" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M25.3991 6.54413C24.1765 5.30961 22.7205 4.33076 21.1158 3.66462C19.5111 2.99848 17.7899 2.65838 16.0524 2.66413C8.77242 2.66413 2.83909 8.59747 2.83909 15.8775C2.83909 18.2108 3.45242 20.4775 4.59909 22.4775L2.73242 29.3308L9.73242 27.4908C11.6658 28.5441 13.8391 29.1041 16.0524 29.1041C23.3324 29.1041 29.2658 23.1708 29.2658 15.8908C29.2658 12.3575 27.8924 9.03747 25.3991 6.54413ZM16.0524 26.8641C14.0791 26.8641 12.1458 26.3308 10.4524 25.3308L10.0524 25.0908L5.89242 26.1841L6.99909 22.1308L6.73242 21.7175C5.63582 19.9669 5.05365 17.9432 5.05242 15.8775C5.05242 9.82414 9.98575 4.8908 16.0391 4.8908C18.9724 4.8908 21.7324 6.03747 23.7991 8.11747C24.8226 9.13595 25.6336 10.3475 26.1853 11.6819C26.7369 13.0163 27.018 14.4469 27.0124 15.8908C27.0391 21.9441 22.1058 26.8641 16.0524 26.8641ZM22.0791 18.6508C21.7458 18.4908 20.1191 17.6908 19.8258 17.5708C19.5191 17.4641 19.3058 17.4108 19.0791 17.7308C18.8524 18.0641 18.2258 18.8108 18.0391 19.0241C17.8524 19.2508 17.6524 19.2775 17.3191 19.1041C16.9858 18.9441 15.9191 18.5841 14.6658 17.4641C13.6791 16.5841 13.0258 15.5041 12.8258 15.1708C12.6391 14.8375 12.7991 14.6641 12.9724 14.4908C13.1191 14.3441 13.3058 14.1041 13.4658 13.9175C13.6258 13.7308 13.6924 13.5841 13.7991 13.3708C13.9058 13.1441 13.8524 12.9575 13.7724 12.7975C13.6924 12.6375 13.0258 11.0108 12.7591 10.3441C12.4924 9.70413 12.2124 9.78413 12.0124 9.7708H11.3724C11.1458 9.7708 10.7991 9.8508 10.4924 10.1841C10.1991 10.5175 9.34575 11.3175 9.34575 12.9441C9.34575 14.5708 10.5324 16.1441 10.6924 16.3575C10.8524 16.5841 13.0258 19.9175 16.3324 21.3441C17.1191 21.6908 17.7324 21.8908 18.2124 22.0375C18.9991 22.2908 19.7191 22.2508 20.2924 22.1708C20.9324 22.0775 22.2524 21.3708 22.5191 20.5975C22.7991 19.8241 22.7991 19.1708 22.7058 19.0241C22.6124 18.8775 22.4124 18.8108 22.0791 18.6508Z"
                                fill="currentColor" />
                        </svg>
                    </span>
                </a>
            <?php endif; ?>

            <?php if (function_exists('shav_render_menu_banners'))
                echo shav_render_menu_banners(); ?>
        </nav>

        <?php if ($showWhatsapp): ?>
            <div class="header__whatsapp">
                <a class="header__whatsapp-link" href="<?php echo esc_url($whatsappUrl); ?>" target="_blank"
                    aria-label="WhatsApp">
                    <?php echo $whatsapp_svg; ?>
                </a>
                <?php if ($whatsappQrImage || $whatsappQrText || $whatsappButtonText): ?>
                    <div class="header__whatsapp-dropdown" style="display: none;">
                        <?php if ($whatsappQrText): ?>
                            <span class="header__whatsapp-text"><?php echo wp_kses_post($whatsappQrText); ?></span>
                        <?php endif; ?>
                        <?php if ($whatsappQrImage): ?>
                            <img src="<?php echo esc_url($whatsappQrImage); ?>" alt="WhatsApp QR Code"
                                style="max-width: 200px; width: 100%; height: auto;">
                        <?php endif; ?>
                        <?php if ($whatsappButtonText): ?>
                            <a class="header__whatsapp-btn" href="<?php echo esc_url($whatsappUrl); ?>" target="_blank">
                                <?php echo wp_kses_post($whatsappButtonText); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ($showCart): ?>
            <?php
            $cart_href = function_exists('wc_get_cart_url')
                ? wc_get_cart_url()
                : esc_url(home_url($cartURL));
            ?>
            <a class="header__cart" href="<?php echo esc_url($cart_href); ?>" aria-label="Koszyk">
                <?php echo $cart_svg; ?>
                <?php if (function_exists('WC') && WC()->cart): ?>
                    <span class="cart-count"><?php echo (int) WC()->cart->get_cart_contents_count(); ?></span>
                <?php endif; ?>
            </a>
        <?php endif; ?>

        <button class="header__hamburger" aria-label="<?php echo esc_attr($hamburgerLabel); ?>" aria-expanded="false">
            <?php echo $hamburger_svg; ?>
        </button>
    </div>
</header>