<?php
$title       = isset($attributes['title'])       ? $attributes['title']       : '';
$description = isset($attributes['description']) ? $attributes['description'] : '';
$images      = isset($attributes['images']) && is_array($attributes['images']) ? $attributes['images'] : [];
$buttonText  = isset($attributes['buttonText']) ? $attributes['buttonText']  : '';
$buttonUrl   = isset($attributes['buttonUrl'])  ? $attributes['buttonUrl']   : '#';
$titleSizeDesktop       = isset($attributes['titleSizeDesktop'])       ? (int) $attributes['titleSizeDesktop']       : 42;
$titleSizeMobile        = isset($attributes['titleSizeMobile'])        ? (int) $attributes['titleSizeMobile']        : 26;
$descriptionSizeDesktop = isset($attributes['descriptionSizeDesktop']) ? (int) $attributes['descriptionSizeDesktop'] : 18;
$descriptionSizeMobile  = isset($attributes['descriptionSizeMobile'])  ? (int) $attributes['descriptionSizeMobile']  : 16;
$buttonSize             = isset($attributes['buttonSize'])             ? (int) $attributes['buttonSize']             : 12;

$wrapper_style = sprintf(
    '--title-size-desktop:%dpx;--title-size-mobile:%dpx;--description-size-desktop:%dpx;--description-size-mobile:%dpx;--button-size:%dpx;',
    $titleSizeDesktop, $titleSizeMobile, $descriptionSizeDesktop, $descriptionSizeMobile, $buttonSize
);
$wrapper_attrs = get_block_wrapper_attributes([
    'class' => 'misjaslider',
    'style' => $wrapper_style,
]);
$total = count($images);
?>
<section <?php echo $wrapper_attrs; ?> data-total="<?php echo esc_attr($total); ?>">
    <header class="misjaslider__head">
        <?php if ($title) : ?>
            <h2 class="misjaslider__title"><?php echo wp_kses_post($title); ?></h2>
        <?php endif; ?>
        <?php if ($description) : ?>
            <p class="misjaslider__description"><?php echo wp_kses_post($description); ?></p>
        <?php endif; ?>
    </header>

    <?php if ($total > 0) : ?>
        <div class="misjaslider__slider">
            <div class="misjaslider__track" role="region" aria-roledescription="carousel">
                <?php
                $valid_images = [];
                foreach ($images as $img) {
                    $url = is_array($img) ? ($img['url'] ?? '') : $img;
                    if ($url) $valid_images[] = $url;
                }
                $cnt = count($valid_images);
                foreach ($valid_images as $i => $url) :
                    // Initial state: pierwszy=active, drugi=next, ostatni=prev (dla desktopa)
                    $cls = '';
                    if ($i === 0) $cls = ' is-active';
                    elseif ($i === 1 && $cnt > 1) $cls = ' is-next';
                    elseif ($i === $cnt - 1 && $cnt > 2) $cls = ' is-prev';
                    ?>
                    <div class="misjaslider__slide<?php echo $cls; ?>" data-index="<?php echo (int) $i; ?>" aria-hidden="<?php echo $i === 0 ? 'false' : 'true'; ?>">
                        <img src="<?php echo esc_url($url); ?>" alt="" loading="lazy" />
                    </div>
                <?php endforeach; ?>
            </div>
            <nav class="misjaslider__nav" aria-label="Nawigacja slidera">
                <button class="misjaslider__nav-btn misjaslider__nav-btn--prev" type="button" aria-label="Poprzedni">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <div class="misjaslider__dots" role="tablist">
                    <?php for ($i = 0; $i < $total; $i++) : ?>
                        <button type="button" class="misjaslider__dot<?php echo $i === 0 ? ' is-active' : ''; ?>" data-index="<?php echo $i; ?>" aria-label="Slajd <?php echo ($i + 1); ?>"></button>
                    <?php endfor; ?>
                </div>
                <button class="misjaslider__nav-btn misjaslider__nav-btn--next" type="button" aria-label="Następny">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M9 18l6-6-6-6" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </nav>
        </div>
    <?php endif; ?>

    <?php if ($buttonText) : ?>
        <a class="misjaslider__cta" href="<?php echo esc_url($buttonUrl); ?>" target="_blank" rel="noopener noreferrer">
            <span class="misjaslider__cta-text"><?php echo esc_html($buttonText); ?></span>
            <span class="misjaslider__cta-icon" aria-hidden="true">
                <svg width="10" height="9" viewBox="0 0 24 24" fill="none">
                    <path d="M5 12h14M13 5l7 7-7 7" stroke="white" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </span>
        </a>
    <?php endif; ?>
</section>
<script>
(function() {
  const root = document.currentScript.previousElementSibling;
  if (!root || !root.classList.contains('misjaslider')) return;
  const slides = root.querySelectorAll('.misjaslider__slide');
  const dots   = root.querySelectorAll('.misjaslider__dot');
  const prev   = root.querySelector('.misjaslider__nav-btn--prev');
  const next   = root.querySelector('.misjaslider__nav-btn--next');
  const total  = slides.length;
  if (!total) return;
  let idx = 0;

  function update() {
    slides.forEach((s, i) => {
      s.classList.remove('is-active', 'is-prev', 'is-next');
      const diff = ((i - idx) % total + total) % total;
      if (diff === 0) s.classList.add('is-active');
      else if (diff === 1) s.classList.add('is-next');
      else if (diff === total - 1) s.classList.add('is-prev');
      s.setAttribute('aria-hidden', diff === 0 ? 'false' : 'true');
    });
    dots.forEach((d, i) => d.classList.toggle('is-active', i === idx));
  }

  function go(newIdx) {
    idx = ((newIdx % total) + total) % total;
    update();
  }

  prev && prev.addEventListener('click', () => go(idx - 1));
  next && next.addEventListener('click', () => go(idx + 1));
  dots.forEach((d, i) => d.addEventListener('click', () => go(i)));

  update();
})();
</script>
