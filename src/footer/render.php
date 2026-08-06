<?php
$ctaTitleBefore = isset($attributes['ctaTitleBefore']) ? $attributes['ctaTitleBefore'] : 'Shav ';
$ctaTitleAccent = isset($attributes['ctaTitleAccent']) ? $attributes['ctaTitleAccent'] : 'woman.';
$ctaSubtitle    = isset($attributes['ctaSubtitle'])    ? $attributes['ctaSubtitle']    : '';
$ctaButtonLabel = isset($attributes['ctaButtonLabel']) ? $attributes['ctaButtonLabel'] : 'Kup teraz';
$ctaButtonURL   = isset($attributes['ctaButtonURL'])   ? $attributes['ctaButtonURL']   : '/sklep';

$columnsCount = isset($attributes['columnsCount']) ? (int) $attributes['columnsCount'] : 2;

$copyright    = isset($attributes['copyright'])    ? $attributes['copyright']    : '';
$custom_logo_id = get_theme_mod('custom_logo');
$custom_logo_url = $custom_logo_id ? wp_get_attachment_image_url($custom_logo_id, 'full') : false;

$bottomLogo = (isset($attributes['bottomLogo']) && $attributes['bottomLogo'])
    ? $attributes['bottomLogo']
    : ($custom_logo_url ?: esc_url(home_url('/wp-content/uploads/shav-logo.png')));
$policyLabel  = isset($attributes['policyLabel'])  ? $attributes['policyLabel']  : 'Polityka prywatności';
$policyURL    = isset($attributes['policyURL'])    ? $attributes['policyURL']    : '/polityka-prywatnosci';
$termsLabel   = isset($attributes['termsLabel'])   ? $attributes['termsLabel']   : 'Regulamin';
$termsURL     = isset($attributes['termsURL'])     ? $attributes['termsURL']     : '/regulamin';
$showBackToTop= isset($attributes['showBackToTop']) ? $attributes['showBackToTop'] : false;
$bottomMenuId = isset($attributes['bottomMenuId']) ? (int) $attributes['bottomMenuId'] : 0;

// Default fallback SVG icons (Facebook, TikTok, Instagram, YouTube)
$default_social_svgs = [
    1 => '<svg aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M22 12.06C22 6.5 17.52 2 12 2S2 6.5 2 12.06c0 5.02 3.66 9.18 8.44 9.94v-7.03H7.9v-2.91h2.54V9.85c0-2.52 1.49-3.91 3.78-3.91 1.09 0 2.24.2 2.24.2v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56v1.87h2.78l-.44 2.91h-2.34V22c4.78-.76 8.43-4.92 8.43-9.94"/></svg>',
    2 => '<svg aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5.8 20.1a6.34 6.34 0 0 0 10.86-4.43V8.69a8.16 8.16 0 0 0 4.77 1.52V6.84a4.85 4.85 0 0 1-1.84-.15"/></svg>',
    3 => '<svg aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>',
    4 => '<svg aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M21.58 7.19c-.23-.86-.91-1.54-1.77-1.77C18.25 5 12 5 12 5s-6.25 0-7.81.42c-.86.23-1.54.91-1.77 1.77C2 8.75 2 12 2 12s0 3.25.42 4.81c.23.86.91 1.54 1.77 1.77C5.75 19 12 19 12 19s6.25 0 7.81-.42c.86-.23 1.54-.91 1.77-1.77C22 15.25 22 12 22 12s0-3.25-.42-4.81M10 15V9l5.2 3z"/></svg>',
];

$socials = [];
for ($i = 1; $i <= 4; $i++) {
    $url = isset($attributes["social{$i}URL"]) ? $attributes["social{$i}URL"] : '';
    if (!$url) continue;
    $socials[] = [
        'icon'  => isset($attributes["social{$i}Icon"]) ? $attributes["social{$i}Icon"] : '',
        'label' => isset($attributes["social{$i}Label"]) ? $attributes["social{$i}Label"] : '',
        'url'   => $url,
        'default_svg' => $default_social_svgs[$i] ?? '',
    ];
}
?>
<footer class="footer">
    <div class="footer__inner">
        <div class="footer__top">

            <!-- CTA card -->
            <div class="footer__cta">
                <h2 class="footer__cta-title">
                    <span><?php echo wp_kses_post($ctaTitleBefore); ?></span><span class="footer__cta-title-accent"><?php echo wp_kses_post($ctaTitleAccent); ?></span>
                </h2>
                <p class="footer__cta-subtitle"><?php echo wp_kses_post($ctaSubtitle); ?></p>
                <a class="footer__cta-button" href="<?php echo esc_url(home_url($ctaButtonURL)); ?>">
                    <span><?php echo wp_kses_post($ctaButtonLabel); ?></span>
                    <span class="footer__cta-button-arrow" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none">
                            <path d="M5 12h14m-6-7 7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                </a>
            </div>

            <!-- Linki + social -->
            <div class="footer__links">

                <?php 
                for ($col = 1; $col <= $columnsCount; $col++):
                    $colTitle = isset($attributes["col{$col}Title"]) ? $attributes["col{$col}Title"] : '';
                    $menuId = isset($attributes["col{$col}MenuId"]) ? (int) $attributes["col{$col}MenuId"] : 0;
                    
                    // Fetch menu items if menu ID is provided
                    $menu_items = $menuId ? wp_get_nav_menu_items($menuId) : false;
                ?>
                <div class="footer__col">
                    <p class="footer__col-title"><?php echo wp_kses_post($colTitle); ?></p>
                    <ul class="footer__col-list">
                        <?php if ($menu_items && !is_wp_error($menu_items)): ?>
                            <?php foreach ($menu_items as $item): ?>
                                <li>
                                    <a href="<?php echo esc_url($item->url); ?>" target="<?php echo esc_attr($item->target ?: '_self'); ?>">
                                        <?php echo wp_kses_post($item->title); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>
                <?php endfor; ?>

                <div class="footer__social">
                    <?php foreach ($socials as $s): ?>
                        <a class="footer__social-icon" href="<?php echo esc_url($s['url']); ?>" aria-label="<?php echo esc_attr($s['label']); ?>" target="_blank" rel="noopener noreferrer">
                            <?php if ($s['icon']): ?>
                                <img src="<?php echo esc_url($s['icon']); ?>" alt="">
                            <?php else: ?>
                                <?php echo $s['default_svg']; ?>
                            <?php endif; ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <hr class="footer__separator">

        <div class="footer__bottom">
            <p class="footer__copyright"><?php echo wp_kses_post($copyright); ?></p>
            <div class="footer__logo">
                <img src="<?php echo esc_url($bottomLogo); ?>" alt="">
            </div>
            <div class="footer__legal">
                <?php 
                if ($bottomMenuId) {
                    $menu_items = wp_get_nav_menu_items($bottomMenuId);
                    if ($menu_items && !is_wp_error($menu_items)) {
                        $items = array_slice($menu_items, 0, 3);
                        $count = count($items);
                        for ($i = 0; $i < $count; $i++) {
                            echo '<a href="' . esc_url($items[$i]->url) . '" target="' . esc_attr($items[$i]->target ?: '_self') . '">' . wp_kses_post($items[$i]->title) . '</a>';
                            if ($i < $count - 1 || $showBackToTop) {
                                echo '<span class="footer__legal-sep" aria-hidden="true"></span>';
                            }
                        }
                    }
                } else {
                ?>
                    <a href="<?php echo esc_url(home_url($policyURL)); ?>"><?php echo wp_kses_post($policyLabel); ?></a>
                    <span class="footer__legal-sep" aria-hidden="true"></span>
                    <a href="<?php echo esc_url(home_url($termsURL)); ?>"><?php echo wp_kses_post($termsLabel); ?></a>
                    <?php if ($showBackToTop): ?>
                        <span class="footer__legal-sep" aria-hidden="true"></span>
                    <?php endif; ?>
                <?php } ?>

                <?php if ($showBackToTop): ?>
                    <button class="footer__back-to-top" type="button" aria-label="Wróć do góry">
                        <svg aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none">
                            <path d="M12 19V5m-7 7 7-7 7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</footer>

<script>
(function() {
    const btn = document.querySelector('.footer__back-to-top');
    if (btn) {
        btn.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }
})();
</script>
