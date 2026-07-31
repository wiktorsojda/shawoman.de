<?php
$title          = isset($attributes['title'])          ? $attributes['title']          : '';
$showTitle      = isset($attributes['showTitle'])      ? $attributes['showTitle']      : true;
$showTopTitle   = isset($attributes['showTopTitle'])   ? $attributes['showTopTitle']   : true;
$topTitle       = isset($attributes['topTitle'])       ? $attributes['topTitle']       : 'Najczęściej zadawane pytania';
$headingTag     = isset($attributes['headingTag'])     ? $attributes['headingTag']     : 'h3';
$containerClass = isset($attributes['containerClass']) ? $attributes['containerClass'] : 'glownafaq';

$allowed_tags = ['h2', 'h3', 'h4', 'div'];
if (!in_array($headingTag, $allowed_tags, true)) $headingTag = 'h3';
?>
<section class="<?php echo esc_attr($containerClass); ?>">
    <div class="glownafaq__inner">
        <?php if ($showTopTitle && $topTitle): ?>
            <h2 class="glownafaq__top-title"><?php echo wp_kses_post($topTitle); ?></h2>
        <?php endif; ?>

        <?php if ($showTitle && $title): ?>
            <div class="glownafaq__title"><?php echo wp_kses_post($title); ?></div>
        <?php endif; ?>

        <div class="glownafaq__list">
            <?php for ($i = 1; $i <= 10; $i++):
                $q   = isset($attributes["question{$i}"]) ? $attributes["question{$i}"] : '';
                $ans = isset($attributes["answer{$i}"])   ? $attributes["answer{$i}"]   : '';
                if (!$q && !$ans) continue;
            ?>
                <div class="glownafaq__item">
                    <button class="glownafaq__trigger faq-accordion" type="button" aria-expanded="false">
                        <<?php echo $headingTag; ?> class="glownafaq__question faq-header"><?php echo wp_kses_post($q); ?></<?php echo $headingTag; ?>>
                        <span class="glownafaq__chevron" aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none">
                                <path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                    </button>
                    <div class="glownafaq__panel faq-pannel">
                        <p><?php echo wp_kses_post($ans); ?></p>
                    </div>
                </div>
            <?php endfor; ?>
        </div>
    </div>
</section>

<script>
(function() {
    document.querySelectorAll('.glownafaq').forEach((root) => {
        root.querySelectorAll('.glownafaq__item').forEach((item) => {
            const trigger = item.querySelector('.glownafaq__trigger');
            if (!trigger) return;
            trigger.addEventListener('click', () => {
                const isActive = item.classList.toggle('is-active');
                trigger.setAttribute('aria-expanded', isActive ? 'true' : 'false');
            });
        });
    });
})();
</script>
