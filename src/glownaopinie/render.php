<?php
$sectionTitle    = isset($attributes['sectionTitle'])    ? $attributes['sectionTitle']    : 'Opinie naszych klientów';
$verifiedLabel   = isset($attributes['verifiedLabel'])   ? $attributes['verifiedLabel']   : 'Zaufana Opinia potwierdzona zakupem';
$verifiedIconSvg = isset($attributes['verifiedIconSvg']) ? $attributes['verifiedIconSvg'] : '';
$visibleCount    = isset($attributes['visibleCount'])    ? (int) $attributes['visibleCount']  : 3;
$autoScroll    = isset($attributes['autoScroll'])    ? $attributes['autoScroll']    : true;
$autoScrollMs  = isset($attributes['autoScrollMs'])  ? (int) $attributes['autoScrollMs']  : 6000;

// Zbierz opinie z attributes
$reviews = [];
for ($i = 1; $i <= 8; $i++) {
    $name = isset($attributes["review{$i}Name"])    ? $attributes["review{$i}Name"]    : '';
    $rate = isset($attributes["review{$i}Rating"])  ? $attributes["review{$i}Rating"]  : '5.0';
    $text = isset($attributes["review{$i}Text"])    ? $attributes["review{$i}Text"]    : '';
    $icon = isset($attributes["review{$i}IconSvg"]) ? $attributes["review{$i}IconSvg"] : '';
    if (!$name && !$text) continue;
    $reviews[] = ['name' => $name, 'rating' => $rate, 'text' => $text, 'iconSvg' => $icon];
}

// Whitelist SVG dla wp_kses (inline SVG z atrybutu)
$svg_allowed = [
    'svg'      => ['xmlns' => 1, 'viewbox' => 1, 'width' => 1, 'height' => 1, 'fill' => 1, 'stroke' => 1, 'class' => 1, 'aria-hidden' => 1, 'preserveaspectratio' => 1],
    'path'     => ['d' => 1, 'fill' => 1, 'stroke' => 1, 'stroke-width' => 1, 'stroke-linecap' => 1, 'stroke-linejoin' => 1, 'clip-rule' => 1, 'fill-rule' => 1, 'opacity' => 1, 'transform' => 1],
    'g'        => ['fill' => 1, 'stroke' => 1, 'transform' => 1, 'clip-path' => 1, 'opacity' => 1, 'mask' => 1, 'id' => 1],
    'circle'   => ['cx' => 1, 'cy' => 1, 'r' => 1, 'fill' => 1, 'stroke' => 1, 'stroke-width' => 1],
    'rect'     => ['x' => 1, 'y' => 1, 'width' => 1, 'height' => 1, 'rx' => 1, 'ry' => 1, 'fill' => 1, 'stroke' => 1],
    'polygon'  => ['points' => 1, 'fill' => 1, 'stroke' => 1],
    'polyline' => ['points' => 1, 'fill' => 1, 'stroke' => 1],
    'line'     => ['x1' => 1, 'y1' => 1, 'x2' => 1, 'y2' => 1, 'stroke' => 1, 'stroke-width' => 1],
    'defs'     => [],
    'clippath' => ['id' => 1],
    'mask'     => ['id' => 1, 'maskunits' => 1],
    'use'      => ['href' => 1, 'xlink:href' => 1],
];

$icon_membership_default = '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none"><g clip-path="url(#shav-membership-clip)"><path d="M20 18.6666C18 18.6666 16 20.6666 16 20.6666C16 20.6666 14 18.6666 12 18.6666C12 16.4573 13.7907 14.6666 16 14.6666C18.2093 14.6666 20 16.4573 20 18.6666ZM16 13.3333C17.4733 13.3333 18.6667 12.14 18.6667 10.6666C18.6667 9.19328 17.4733 7.99995 16 7.99995C14.5267 7.99995 13.3333 9.19328 13.3333 10.6666C13.3333 12.14 14.5267 13.3333 16 13.3333ZM31.8507 26.8213C31.5507 27.5426 30.8787 27.992 30.096 27.992H28.0133V30.0746C28.0133 31.2666 27.0413 31.9773 26.1013 31.9773C25.6 31.9773 25.1413 31.784 24.7747 31.42L18.9507 25.596C18.9507 25.596 18.9467 25.588 18.9427 25.584C18.72 25.7626 18.5013 25.9453 18.244 26.088C17.5467 26.4746 16.7653 26.668 15.984 26.668C15.2027 26.668 14.4227 26.4746 13.7267 26.0893C13.464 25.9426 13.24 25.7573 13.0147 25.5746C13.008 25.5813 13.0067 25.5893 13.0013 25.596L7.18001 31.4173C6.81067 31.784 6.35201 31.9773 5.85067 31.9773C4.91067 31.9773 3.93734 31.2666 3.93734 30.0746V27.992H1.85467C1.07334 27.992 0.401339 27.5426 0.101339 26.8213C-0.198661 26.096 -0.0399945 25.3026 0.514672 24.7493L5.23334 20.0293C5.15867 19.4666 5.17601 18.9053 5.30267 18.3546C5.38801 17.976 5.20667 17.5666 4.84934 17.3346C4.18401 16.9066 3.64534 16.3253 3.24667 15.6066C2.86667 14.9186 2.66667 14.1306 2.67067 13.3253C2.66667 12.5333 2.86534 11.7439 3.24534 11.0586C3.64534 10.3386 4.18401 9.75728 4.84934 9.32795C5.20667 9.09728 5.38934 8.68928 5.30267 8.30928C5.12267 7.51995 5.14934 6.71062 5.38267 5.90128C5.82001 4.37328 7.04401 3.14928 8.57467 2.70928C9.38401 2.47862 10.192 2.45462 10.98 2.62928C11.3653 2.71595 11.7693 2.53328 12 2.17595C12.4293 1.51195 13.0093 0.973284 13.7267 0.574617C15.12 -0.198716 16.852 -0.197383 18.244 0.574617C18.964 0.974617 19.5453 1.51328 19.9733 2.17728C20.204 2.53462 20.6107 2.70928 20.992 2.63195C21.78 2.45328 22.588 2.47995 23.396 2.70928C24.928 3.14928 26.1507 4.37195 26.5907 5.90395C26.8213 6.70928 26.848 7.51862 26.6693 8.30928C26.584 8.68928 26.7653 9.09862 27.1227 9.32928C27.7867 9.75728 28.3253 10.34 28.7253 11.0586C29.1053 11.7453 29.304 12.5333 29.3 13.3386C29.304 14.1306 29.1053 14.92 28.7253 15.6053C28.3253 16.3253 27.7867 16.908 27.1227 17.336C26.7653 17.5666 26.5827 17.9746 26.6693 18.3546C26.796 18.912 26.8133 19.4786 26.736 20.0466L31.44 24.7506C31.9933 25.3026 32.1507 26.096 31.8507 26.8213ZM10.7573 24.068C10.04 24.1946 9.30667 24.1626 8.57467 23.956C7.73734 23.716 7.01067 23.228 6.43467 22.5986L3.70801 25.3253H5.26934C6.00534 25.3253 6.60267 25.9213 6.60267 26.6586V28.2213L10.7573 24.068ZM21.5813 21.432C21.928 21.5133 22.2907 21.4973 22.6613 21.3906C23.304 21.2066 23.8413 20.6706 24.0253 20.028C24.132 19.6573 24.1453 19.2933 24.068 18.9453C23.7347 17.4786 24.38 15.9306 25.6773 15.0946C25.968 14.9066 26.2093 14.644 26.3933 14.3133C26.552 14.0266 26.636 13.6893 26.6347 13.34C26.636 12.9773 26.552 12.64 26.3933 12.352C26.2093 12.0213 25.9693 11.76 25.6787 11.572C24.3827 10.736 23.736 9.18795 24.0693 7.71995C24.148 7.37328 24.1333 7.01062 24.028 6.63995C23.8427 5.99595 23.3067 5.45862 22.6627 5.27462C22.2933 5.17062 21.9307 5.15328 21.5827 5.23462C20.12 5.56795 18.5693 4.92128 17.7333 3.62528C17.5453 3.33462 17.2827 3.09328 16.9507 2.90928C16.3667 2.58662 15.608 2.58528 15.0213 2.90928C14.692 3.09195 14.4293 3.33195 14.24 3.62395C13.404 4.92128 11.8547 5.56662 10.3893 5.23195C10.0453 5.15595 9.68001 5.16795 9.30801 5.27462C8.66534 5.45862 8.12934 5.99462 7.94534 6.63728C7.83867 7.00928 7.82401 7.37195 7.90267 7.71862C8.23601 9.18662 7.58934 10.7346 6.29334 11.5693C6.00134 11.7586 5.76134 12.0213 5.57734 12.352C5.41867 12.6386 5.33467 12.976 5.33601 13.3253C5.33467 13.688 5.41867 14.0253 5.57734 14.3133C5.76001 14.6426 6.00134 14.9053 6.29334 15.0933C7.58934 15.9293 8.23601 17.4773 7.90267 18.9453C7.82401 19.292 7.83867 19.6533 7.94401 20.0253C8.12934 20.6693 8.66534 21.2066 9.30934 21.3906C9.67867 21.4946 10.0427 21.5106 10.3907 21.4306C10.6467 21.372 10.9067 21.3453 11.1653 21.3453C12.3773 21.3453 13.5507 21.972 14.2413 23.04C14.4293 23.332 14.692 23.572 15.0227 23.756C15.608 24.0786 16.3667 24.08 16.952 23.756C17.2827 23.5733 17.5453 23.332 17.7333 23.0413C18.5693 21.7453 20.1173 21.1066 21.584 21.432H21.5813ZM28.2427 25.3253L25.5253 22.608C24.952 23.232 24.2267 23.716 23.396 23.9533C22.6547 24.164 21.9133 24.1946 21.1907 24.064L25.3467 28.22V26.6573C25.3467 25.92 25.944 25.324 26.68 25.324L28.2427 25.3253Z" fill="#3F3F3F"/></g><defs><clipPath id="shav-membership-clip"><rect width="32" height="32" fill="white"/></clipPath></defs></svg>';
?>
<section class="glownaopinie"
    data-visible="<?php echo esc_attr($visibleCount); ?>"
    data-autoscroll="<?php echo $autoScroll ? '1' : '0'; ?>"
    data-autoscroll-ms="<?php echo esc_attr($autoScrollMs); ?>">
    <h2 class="glownaopinie__title"><?php echo wp_kses_post($sectionTitle); ?></h2>

    <div class="glownaopinie__viewport">
        <div class="glownaopinie__track">
            <?php foreach ($reviews as $r): ?>
                <article class="glownaopinie__card">
                    <div class="glownaopinie__card-pill">
                        <span><?php echo wp_kses_post($r['name']); ?></span>
                        <span class="glownaopinie__card-pill-sep" aria-hidden="true"></span>
                        <span><?php echo wp_kses_post($r['rating']); ?></span>
                        <span class="glownaopinie__card-pill-stars" aria-label="<?php echo esc_attr($r['rating']); ?> z 5">
                            <?php for ($j = 0; $j < 5; $j++): ?>
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="#e9bd0b"><path d="M12 17.27 18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <?php endfor; ?>
                        </span>
                    </div>
                    <p class="glownaopinie__card-text"><?php echo wp_kses_post($r['text']); ?></p>
                    <div class="glownaopinie__card-verified">
                        <span><?php echo wp_kses_post($verifiedLabel); ?></span>
                        <span class="glownaopinie__card-verified-sep" aria-hidden="true"></span>
                        <span class="glownaopinie__card-verified-icon"><?php
                            // Priority: per-review > global > default
                            if (!empty($r['iconSvg'])) {
                                echo wp_kses($r['iconSvg'], $svg_allowed);
                            } elseif (!empty($verifiedIconSvg)) {
                                echo wp_kses($verifiedIconSvg, $svg_allowed);
                            } else {
                                echo $icon_membership_default;
                            }
                        ?></span>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="glownaopinie__dots" role="tablist" aria-label="Wybierz opinie"></div>
</section>

<script>
(function() {
    document.querySelectorAll('.glownaopinie').forEach((root) => {
        const visible = parseInt(root.dataset.visible || '3', 10);
        const autoScroll = root.dataset.autoscroll === '1';
        const autoScrollMs = parseInt(root.dataset.autoscrollMs || '6000', 10);
        const track = root.querySelector('.glownaopinie__track');
        const cards = track ? track.querySelectorAll('.glownaopinie__card') : [];
        const dotsContainer = root.querySelector('.glownaopinie__dots');
        if (!track || cards.length === 0) return;

        const total = cards.length;
        let index = 0;
        let intervalId = null;
        let dotEls = [];

        function visibleCount() {
            return window.matchMedia('(max-width: 767px)').matches ? 1 : visible;
        }

        function maxIndex() {
            return Math.max(0, total - visibleCount());
        }

        function stepSize() {
            const card = cards[0];
            const cardWidth = card.getBoundingClientRect().width;
            const gap = parseInt(getComputedStyle(track).gap || '16', 10) || 16;
            return cardWidth + gap;
        }

        function applyTransform(offsetPx, withTransition) {
            track.style.transition = withTransition ? '' : 'none';
            track.style.transform = 'translateX(' + (-offsetPx) + 'px)';
        }

        function update(withTransition) {
            const max = maxIndex();
            if (index > max) index = max;
            if (index < 0) index = 0;
            applyTransform(stepSize() * index, withTransition !== false);
            updateDots();
        }

        function step(dir) {
            const max = maxIndex();
            index = (index + dir + (max + 1)) % (max + 1);
            update();
        }

        // ---- Dots ----
        function buildDots() {
            if (!dotsContainer) return;
            const count = maxIndex() + 1;
            dotsContainer.innerHTML = '';
            dotEls = [];
            if (count <= 1) return;
            for (let i = 0; i < count; i++) {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'glownaopinie__dot';
                btn.setAttribute('role', 'tab');
                btn.setAttribute('aria-label', 'Przejdź do opinii ' + (i + 1));
                btn.addEventListener('click', () => { index = i; update(); resetAuto(); });
                dotsContainer.appendChild(btn);
                dotEls.push(btn);
            }
            updateDots();
        }

        function updateDots() {
            dotEls.forEach((d, i) => {
                d.classList.toggle('glownaopinie__dot--active', i === index);
                d.setAttribute('aria-selected', i === index ? 'true' : 'false');
            });
        }

        // ---- Drag / swipe (pointer events) ----
        let dragging = false;
        let startX = 0;
        let startOffset = 0;
        let currentOffset = 0;
        let pointerId = null;

        track.addEventListener('pointerdown', (e) => {
            // ignoruj prawy klik
            if (e.button !== undefined && e.button !== 0) return;
            dragging = true;
            pointerId = e.pointerId;
            startX = e.clientX;
            startOffset = stepSize() * index;
            currentOffset = startOffset;
            track.setPointerCapture(pointerId);
            track.style.transition = 'none';
            root.classList.add('is-dragging');
            if (intervalId) { clearInterval(intervalId); intervalId = null; }
        });

        track.addEventListener('pointermove', (e) => {
            if (!dragging) return;
            const dx = e.clientX - startX;
            currentOffset = startOffset - dx;
            const max = maxIndex();
            const totalWidth = stepSize() * max;
            // resistance na krancach
            if (currentOffset < 0) currentOffset = currentOffset / 3;
            if (currentOffset > totalWidth) currentOffset = totalWidth + (currentOffset - totalWidth) / 3;
            applyTransform(currentOffset, false);
        });

        function endDrag(e) {
            if (!dragging) return;
            dragging = false;
            root.classList.remove('is-dragging');
            try { track.releasePointerCapture(pointerId); } catch (err) {}
            const dx = (e.clientX || 0) - startX;
            const threshold = stepSize() * 0.2;
            if (Math.abs(dx) > threshold) {
                index += dx < 0 ? 1 : -1;
            }
            update();
            resetAuto();
        }

        track.addEventListener('pointerup', endDrag);
        track.addEventListener('pointercancel', endDrag);
        // anuluj klik bezposrednio po dragu (zeby linki/inputy w karcie nie strzelaly)
        track.addEventListener('click', (e) => {
            if (Math.abs((e.clientX || 0) - startX) > 5 && startX !== 0) {
                e.preventDefault();
                e.stopPropagation();
            }
        }, true);

        // ---- Auto-scroll ----
        function resetAuto() {
            if (intervalId) clearInterval(intervalId);
            if (autoScroll && maxIndex() > 0) {
                intervalId = setInterval(() => step(1), autoScrollMs);
            }
        }

        // ---- Init + resize ----
        window.addEventListener('resize', () => { buildDots(); update(false); });
        buildDots();
        update(false);
        resetAuto();
    });
})();
</script>
