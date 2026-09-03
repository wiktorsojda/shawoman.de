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
                <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g clip-path="url(#clip0_1664_830)">
                <rect x="36" y="1.57361e-06" width="36" height="36" rx="18" transform="rotate(90 36 1.57361e-06)" fill="#BCBCBC"/>
                <path d="M17.9982 28.125C18.7873 28.1177 19.5418 27.7997 20.0982 27.24L26.5482 20.805C26.8276 20.524 26.9844 20.1438 26.9844 19.7475C26.9844 19.3512 26.8276 18.971 26.5482 18.69C26.4087 18.5494 26.2428 18.4378 26.0601 18.3617C25.8773 18.2855 25.6812 18.2463 25.4832 18.2463C25.2852 18.2463 25.0891 18.2855 24.9063 18.3617C24.7235 18.4378 24.5576 18.5494 24.4182 18.69L19.4982 23.625L19.4982 8.625C19.4982 8.22718 19.3402 7.84565 19.0588 7.56434C18.7775 7.28304 18.396 7.125 17.9982 7.125C17.6004 7.125 17.2188 7.28304 16.9375 7.56434C16.6562 7.84565 16.4982 8.22718 16.4982 8.625L16.4982 23.625L11.5632 18.69C11.2827 18.4075 10.9015 18.2481 10.5035 18.2467C10.1054 18.2453 9.72314 18.402 9.44069 18.6825C9.15823 18.963 8.99876 19.3442 8.99735 19.7422C8.99594 20.1402 9.15272 20.5225 9.43319 20.805L15.8832 27.24C16.4432 27.8034 17.2038 28.1217 17.9982 28.125Z" fill="#FAFAFA"/>
                </g>
                <defs>
                <clipPath id="clip0_1664_830">
                <rect x="36" y="1.57361e-06" width="36" height="36" rx="18" transform="rotate(90 36 1.57361e-06)" fill="white"/>
                </clipPath>
                </defs>
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
                <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g clip-path="url(#clip0_1664_830)">
                <rect x="36" y="1.57361e-06" width="36" height="36" rx="18" transform="rotate(90 36 1.57361e-06)" fill="#BCBCBC"/>
                <path d="M17.9982 28.125C18.7873 28.1177 19.5418 27.7997 20.0982 27.24L26.5482 20.805C26.8276 20.524 26.9844 20.1438 26.9844 19.7475C26.9844 19.3512 26.8276 18.971 26.5482 18.69C26.4087 18.5494 26.2428 18.4378 26.0601 18.3617C25.8773 18.2855 25.6812 18.2463 25.4832 18.2463C25.2852 18.2463 25.0891 18.2855 24.9063 18.3617C24.7235 18.4378 24.5576 18.5494 24.4182 18.69L19.4982 23.625L19.4982 8.625C19.4982 8.22718 19.3402 7.84565 19.0588 7.56434C18.7775 7.28304 18.396 7.125 17.9982 7.125C17.6004 7.125 17.2188 7.28304 16.9375 7.56434C16.6562 7.84565 16.4982 8.22718 16.4982 8.625L16.4982 23.625L11.5632 18.69C11.2827 18.4075 10.9015 18.2481 10.5035 18.2467C10.1054 18.2453 9.72314 18.402 9.44069 18.6825C9.15823 18.963 8.99876 19.3442 8.99735 19.7422C8.99594 20.1402 9.15272 20.5225 9.43319 20.805L15.8832 27.24C16.4432 27.8034 17.2038 28.1217 17.9982 28.125Z" fill="#FAFAFA"/>
                </g>
                <defs>
                <clipPath id="clip0_1664_830">
                <rect x="36" y="1.57361e-06" width="36" height="36" rx="18" transform="rotate(90 36 1.57361e-06)" fill="white"/>
                </clipPath>
                </defs>
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
