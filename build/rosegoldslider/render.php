<?php
// =============================================================================
// Drop — Slider banerów (rosegoldslider)
// Karuzela "peek": aktywny slajd wyśrodkowany, sąsiednie przygaszone.
// =============================================================================
$themeUri = get_template_directory_uri();

$autoScroll   = isset($attributes['autoScroll']) ? $attributes['autoScroll'] : true;
$autoScrollMs = isset($attributes['autoScrollMs']) ? (int) $attributes['autoScrollMs'] : 5000;

$slides = [];
for ($i = 1; $i <= 5; $i++) {
    $img = isset($attributes["slide{$i}Image"]) ? $attributes["slide{$i}Image"] : '';
    if (!$img) continue;
    $imgM = isset($attributes["slide{$i}ImageMobile"]) && $attributes["slide{$i}ImageMobile"] ? $attributes["slide{$i}ImageMobile"] : $img;
    $slides[] = [
        'image'       => $img,
        'imageMobile' => $imgM,
        'url'         => isset($attributes["slide{$i}Url"]) ? $attributes["slide{$i}Url"] : '',
        'alt'         => isset($attributes["slide{$i}Alt"]) ? $attributes["slide{$i}Alt"] : '',
    ];
}
// Brak slajdów → domyślne z motywu (osobne desktop / mobile)
if (empty($slides)) {
    $slides = [
        ['image' => $themeUri . '/images/rosegold/slide-1.jpg', 'imageMobile' => $themeUri . '/images/rosegold/slide-m-1.jpg', 'url' => '', 'alt' => ''],
        ['image' => $themeUri . '/images/rosegold/slide-2.jpg', 'imageMobile' => $themeUri . '/images/rosegold/slide-m-2.jpg', 'url' => '', 'alt' => ''],
        ['image' => $themeUri . '/images/rosegold/slide-3.jpg', 'imageMobile' => $themeUri . '/images/rosegold/slide-m-3.jpg', 'url' => '', 'alt' => ''],
    ];
}

$uid = 'rosegoldslider-' . wp_unique_id();
?>
<section class="rosegoldslider" id="<?php echo esc_attr($uid); ?>"
    data-autoscroll="<?php echo $autoScroll ? '1' : '0'; ?>"
    data-autoscroll-ms="<?php echo esc_attr($autoScrollMs); ?>">
    <div class="rosegoldslider__viewport">
        <div class="rosegoldslider__track">
            <?php foreach ($slides as $idx => $s):
                $tag = $s['url'] ? 'a' : 'div';
                $href = $s['url'] ? ' href="' . esc_url($s['url']) . '"' : '';
                ?>
                <<?php echo $tag; ?> class="rosegoldslider__slide<?php echo $idx === 0 ? ' is-active' : ''; ?>"<?php echo $href; ?>>
                    <img class="rosegoldslider__img rosegoldslider__img--desktop" src="<?php echo esc_url($s['image']); ?>" alt="<?php echo esc_attr($s['alt']); ?>" draggable="false">
                    <img class="rosegoldslider__img rosegoldslider__img--mobile" src="<?php echo esc_url($s['imageMobile']); ?>" alt="" draggable="false" aria-hidden="true">
                </<?php echo $tag; ?>>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="rosegoldslider__dots" role="tablist" aria-label="Wybierz slajd"></div>
</section>

<script>
    (function () {
        const root = document.getElementById('<?php echo esc_js($uid); ?>');
        if (!root) return;
        const track = root.querySelector('.rosegoldslider__track');
        const slides = Array.from(root.querySelectorAll('.rosegoldslider__slide'));
        const dotsBox = root.querySelector('.rosegoldslider__dots');
        if (!track || slides.length === 0) return;

        const autoScroll = root.dataset.autoscroll === '1';
        const autoScrollMs = parseInt(root.dataset.autoscrollMs || '5000', 10);
        const total = slides.length;
        let index = 0;
        let timer = null;
        let dots = [];

        function metrics() {
            const slideW = slides[0].getBoundingClientRect().width;
            const gap = parseFloat(getComputedStyle(track).columnGap || getComputedStyle(track).gap || '8') || 8;
            const viewW = root.querySelector('.rosegoldslider__viewport').getBoundingClientRect().width;
            return { slideW, gap, viewW };
        }

        function apply(withTransition) {
            const { slideW, gap, viewW } = metrics();
            const offset = index * (slideW + gap) - (viewW - slideW) / 2;
            track.style.transition = withTransition === false ? 'none' : '';
            track.style.transform = 'translateX(' + (-offset) + 'px)';
            slides.forEach((s, i) => s.classList.toggle('is-active', i === index));
            dots.forEach((d, i) => d.classList.toggle('is-active', i === index));
        }

        function go(i, withTransition) {
            index = (i + total) % total;
            apply(withTransition);
        }

        function buildDots() {
            if (!dotsBox) return;
            dotsBox.innerHTML = '';
            dots = [];
            if (total <= 1) return;
            for (let i = 0; i < total; i++) {
                const b = document.createElement('button');
                b.type = 'button';
                b.className = 'rosegoldslider__dot';
                b.setAttribute('aria-label', 'Slajd ' + (i + 1));
                b.addEventListener('click', () => { go(i); resetAuto(); });
                dotsBox.appendChild(b);
                dots.push(b);
            }
        }

        // ---- Drag / swipe ----
        let dragging = false, startX = 0, moved = 0, pid = null;
        track.addEventListener('pointerdown', (e) => {
            if (e.button !== undefined && e.button !== 0) return;
            dragging = true; pid = e.pointerId; startX = e.clientX; moved = 0;
            track.setPointerCapture(pid);
            track.style.transition = 'none';
            root.classList.add('is-dragging');
            if (timer) { clearInterval(timer); timer = null; }
        });
        track.addEventListener('pointermove', (e) => {
            if (!dragging) return;
            moved = e.clientX - startX;
            const { slideW, gap, viewW } = metrics();
            const base = index * (slideW + gap) - (viewW - slideW) / 2;
            track.style.transform = 'translateX(' + (-(base - moved)) + 'px)';
        });
        function endDrag() {
            if (!dragging) return;
            dragging = false;
            root.classList.remove('is-dragging');
            try { track.releasePointerCapture(pid); } catch (err) {}
            const { slideW } = metrics();
            if (Math.abs(moved) > slideW * 0.15) go(index + (moved < 0 ? 1 : -1)); else apply();
            resetAuto();
        }
        track.addEventListener('pointerup', endDrag);
        track.addEventListener('pointercancel', endDrag);
        track.addEventListener('click', (e) => {
            if (Math.abs(moved) > 6) { e.preventDefault(); e.stopPropagation(); }
        }, true);

        // ---- Auto ----
        function resetAuto() {
            if (timer) clearInterval(timer);
            if (autoScroll && total > 1) timer = setInterval(() => go(index + 1), autoScrollMs);
        }

        let rAF = null;
        window.addEventListener('resize', () => {
            if (rAF) cancelAnimationFrame(rAF);
            rAF = requestAnimationFrame(() => apply(false));
        });

        buildDots();
        apply(false);
        resetAuto();
    })();
</script>
