<?php
$titleBefore = isset($attributes['titleBefore']) ? $attributes['titleBefore'] : 'Shav ';
$titleAccent = isset($attributes['titleAccent']) ? $attributes['titleAccent'] : 'woman.';
$titleAfter  = isset($attributes['titleAfter'])  ? $attributes['titleAfter']  : ' Dlaczego warto wybrać?';

$card1Icon       = isset($attributes['card1Icon'])       ? $attributes['card1Icon']       : '';
$card1TextStrong = isset($attributes['card1TextStrong']) ? $attributes['card1TextStrong'] : '';
$card1TextLight  = isset($attributes['card1TextLight'])  ? $attributes['card1TextLight']  : '';

$card2Title       = isset($attributes['card2Title'])       ? $attributes['card2Title']       : '';
$card2RatingCount = isset($attributes['card2RatingCount']) ? $attributes['card2RatingCount'] : '';
$card2RatingScore = isset($attributes['card2RatingScore']) ? $attributes['card2RatingScore'] : '';

$card3Icon       = isset($attributes['card3Icon'])       ? $attributes['card3Icon']       : '';
$card3TextStrong = isset($attributes['card3TextStrong']) ? $attributes['card3TextStrong'] : '';
$card3TextLight  = isset($attributes['card3TextLight'])  ? $attributes['card3TextLight']  : '';

$avatars = [];
for ($i = 1; $i <= 5; $i++) {
    $key = "card2Avatar{$i}";
    if (!empty($attributes[$key])) {
        $avatars[] = $attributes[$key];
    }
}

// Placeholder SVG icons jako default gdy user nie wgral wlasnych
$icon_handshake = '<svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="m11 17 2 2a1 1 0 1 0 3-3"/><path d="m14 14 2.5 2.5a1 1 0 1 0 3-3l-3.88-3.88a3 3 0 0 0-4.24 0l-.88.88a1 1 0 1 1-3-3l2.81-2.81a5.79 5.79 0 0 1 7.06-.87l.47.28a2 2 0 0 0 1.42.25L21 4"/><path d="m21 3 1 11h-2"/><path d="M3 3 2 14l6.5 6.5a1 1 0 1 0 3-3"/><path d="M3 4h8"/></svg>';
$icon_swimsuit = '<svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="5" r="3"/><path d="M8 9h8a2 2 0 0 1 2 2v3a4 4 0 0 1-4 4h-1l-1 4-1-4H10a4 4 0 0 1-4-4v-3a2 2 0 0 1 2-2Z"/></svg>';
?>
<section class="glownaoceny">
    <h2 class="glownaoceny__title">
        <span><?php echo wp_kses_post($titleBefore); ?></span><span class="glownaoceny__title-accent"><?php echo wp_kses_post($titleAccent); ?></span><span class="glownaoceny__title-after"><?php echo wp_kses_post($titleAfter); ?></span>
    </h2>

    <div class="glownaoceny__cards">
        <!-- Karta 1 -->
        <div class="glownaoceny__card">
            <div class="glownaoceny__card-icon">
                <?php if ($card1Icon): ?>
                    <img src="<?php echo esc_url($card1Icon); ?>" alt="">
                <?php else: ?>
                    <?php echo $icon_handshake; ?>
                <?php endif; ?>
            </div>
            <p class="glownaoceny__card-text">
                <span><?php echo wp_kses_post($card1TextStrong); ?></span>
                <span class="glownaoceny__card-text-light"><?php echo wp_kses_post($card1TextLight); ?></span>
            </p>
        </div>

        <!-- Karta 2 -->
        <div class="glownaoceny__card">
            <p class="glownaoceny__card-title"><?php echo wp_kses_post($card2Title); ?></p>
            <?php if (!empty($avatars)): ?>
                <div class="glownaoceny__card-avatars">
                    <?php foreach ($avatars as $av): ?>
                        <img src="<?php echo esc_url($av); ?>" alt="">
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <div class="glownaoceny__card-pill">
                <span><?php echo wp_kses_post($card2RatingCount); ?></span>
                <span class="glownaoceny__card-pill-sep" aria-hidden="true"></span>
                <span><?php echo wp_kses_post($card2RatingScore); ?></span>
                <span class="glownaoceny__card-pill-stars" aria-label="5 z 5 gwiazdek">
                    <?php for ($i = 0; $i < 5; $i++): ?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="#e9bd0b">
                            <path d="M12 17.27 18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                        </svg>
                    <?php endfor; ?>
                </span>
            </div>
        </div>

        <!-- Karta 3 -->
        <div class="glownaoceny__card">
            <div class="glownaoceny__card-icon">
                <?php if ($card3Icon): ?>
                    <img src="<?php echo esc_url($card3Icon); ?>" alt="">
                <?php else: ?>
                    <?php echo $icon_swimsuit; ?>
                <?php endif; ?>
            </div>
            <p class="glownaoceny__card-text">
                <span><?php echo wp_kses_post($card3TextStrong); ?></span>
                <span class="glownaoceny__card-text-light"><?php echo wp_kses_post($card3TextLight); ?></span>
            </p>
        </div>
    </div>
</section>
