<?php
/**
 * Single Product Image — galeria z thumbs po lewej (desktop) / pod main (mobile).
 * Figma: 390:1211 — strzalki gora/dol, thumbs 92px, main img radius 24, badges w rogu.
 *
 * @see https://woocommerce.com/document/template-structure/
 */

defined('ABSPATH') || exit;

if (!function_exists('wc_get_gallery_image_html')) {
    return;
}

global $product;

$post_thumbnail_id = $product->get_image_id();
$gallery_image_ids = $product->get_gallery_image_ids();

// Zbierz wszystkie ID (main + galeria) w jednej tablicy
$all_image_ids = [];
if ($post_thumbnail_id) {
    $all_image_ids[] = $post_thumbnail_id;
}
if ($gallery_image_ids && is_array($gallery_image_ids)) {
    $all_image_ids = array_merge($all_image_ids, $gallery_image_ids);
}

// Badges (Nowość / -25% / itp.) — wyrenderowane przez functions.php hookiem
// `shav_product_gallery_badges`. Tutaj tylko slot do wstawienia.
?>
<div class="product-gallery woocommerce-product-gallery" data-count="<?php echo count($all_image_ids); ?>">

    <?php if (count($all_image_ids) > 1): ?>
        <div class="product-gallery__thumbs-col">
            <button type="button" class="product-gallery__arrow product-gallery__arrow--up" aria-label="Poprzednie zdjęcie">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none">
                    <path d="M5 15l7-7 7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>

            <div class="product-gallery__thumbs">
                <?php foreach ($all_image_ids as $i => $img_id):
                    $thumb_url = wp_get_attachment_image_url($img_id, 'thumbnail');
                    if (!$thumb_url) continue;
                ?>
                    <button type="button"
                            class="product-gallery__thumb<?php echo $i === 0 ? ' product-gallery__thumb--active' : ''; ?>"
                            data-index="<?php echo $i; ?>"
                            aria-label="Zdjęcie <?php echo $i + 1; ?>">
                        <img src="<?php echo esc_url($thumb_url); ?>" alt="">
                    </button>
                <?php endforeach; ?>
            </div>

            <button type="button" class="product-gallery__arrow product-gallery__arrow--down" aria-label="Następne zdjęcie">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none">
                    <path d="M19 9l-7 7-7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
        </div>
    <?php endif; ?>

    <div class="product-gallery__main">
        <div class="product-gallery__badges">
            <?php do_action('shav_product_gallery_badges', $product); ?>
        </div>

        <div class="product-gallery__stage">
            <?php foreach ($all_image_ids as $i => $img_id):
                $full_url = wp_get_attachment_image_url($img_id, 'full');
                if (!$full_url) continue;
            ?>
                <img class="product-gallery__image<?php echo $i === 0 ? ' is-active' : ''; ?>"
                     src="<?php echo esc_url($full_url); ?>"
                     alt=""
                     data-index="<?php echo $i; ?>"
                     <?php echo $i === 0 ? '' : 'loading="lazy"'; ?>>
            <?php endforeach; ?>

            <?php if (empty($all_image_ids)): ?>
                <img class="product-gallery__image is-active"
                     src="<?php echo esc_url(wc_placeholder_img_src('woocommerce_single')); ?>"
                     alt="<?php esc_attr_e('Awaiting product image', 'woocommerce'); ?>">
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
(function() {
    const gallery = document.currentScript.previousElementSibling;
    if (!gallery || !gallery.classList.contains('product-gallery')) return;

    const thumbs = gallery.querySelectorAll('.product-gallery__thumb');
    const images = gallery.querySelectorAll('.product-gallery__image');
    const thumbsContainer = gallery.querySelector('.product-gallery__thumbs');
    const upBtn = gallery.querySelector('.product-gallery__arrow--up');
    const downBtn = gallery.querySelector('.product-gallery__arrow--down');
    if (!images.length) return;

    let currentIndex = 0;

    function setActive(i) {
        if (i < 0 || i >= images.length) return;
        currentIndex = i;
        thumbs.forEach((t, idx) => t.classList.toggle('product-gallery__thumb--active', idx === i));
        images.forEach((img, idx) => img.classList.toggle('is-active', idx === i));
        // przewin thumb container zeby aktywny byl widoczny
        const activeThumb = thumbs[i];
        if (activeThumb && thumbsContainer) {
            activeThumb.scrollIntoView({ block: 'nearest', inline: 'nearest', behavior: 'smooth' });
        }
    }

    thumbs.forEach((thumb) => {
        thumb.addEventListener('click', () => setActive(parseInt(thumb.dataset.index, 10)));
    });

    if (upBtn) upBtn.addEventListener('click', () => setActive(currentIndex - 1));
    if (downBtn) downBtn.addEventListener('click', () => setActive(currentIndex + 1));

    // Drag-to-scroll mysza w kontenerze thumbs (touch dziala natywnie przez touch-action)
    if (thumbsContainer) {
        const DRAG_THRESHOLD = 8; // pikseli — wieksza tolerancja na micro-ruch przy kliknieciu
        let isDown = false, startX = 0, startY = 0, startScrollL = 0, startScrollT = 0;
        let lastDx = 0, lastDy = 0, wasDrag = false;

        thumbsContainer.addEventListener('pointerdown', (e) => {
            if (e.pointerType === 'touch') return; // touch: zostaw natywny scroll
            isDown = true;
            wasDrag = false;
            lastDx = 0;
            lastDy = 0;
            startX = e.clientX;
            startY = e.clientY;
            startScrollL = thumbsContainer.scrollLeft;
            startScrollT = thumbsContainer.scrollTop;
            thumbsContainer.style.scrollBehavior = 'auto';
        });
        thumbsContainer.addEventListener('pointermove', (e) => {
            if (!isDown) return;
            lastDx = e.clientX - startX;
            lastDy = e.clientY - startY;
            const isDesktop = window.matchMedia('(min-width: 768px)').matches;
            // Przesuwaj scroll dopiero po przekroczeniu progu (zeby drobny ruch przy
            // klikaniu nie zmienial pozycji scrolla ani nie blokowal kliku)
            if (Math.abs(isDesktop ? lastDy : lastDx) > DRAG_THRESHOLD) {
                if (!wasDrag) {
                    wasDrag = true;
                    try { thumbsContainer.setPointerCapture(e.pointerId); } catch (_) {}
                    thumbsContainer.style.cursor = 'grabbing';
                }
                if (isDesktop) {
                    thumbsContainer.scrollTop = startScrollT - lastDy;
                } else {
                    thumbsContainer.scrollLeft = startScrollL - lastDx;
                }
            }
        });
        const endDrag = () => {
            isDown = false;
            thumbsContainer.style.scrollBehavior = '';
            thumbsContainer.style.cursor = '';
        };
        thumbsContainer.addEventListener('pointerup', endDrag);
        thumbsContainer.addEventListener('pointercancel', endDrag);
        // Anuluj klik tylko jesli faktycznie nastapil drag (nie tylko micro-ruch)
        thumbsContainer.addEventListener('click', (e) => {
            if (wasDrag) {
                e.preventDefault();
                e.stopPropagation();
                wasDrag = false;
            }
        }, true);
    }
})();
</script>
