<?php
$image = isset($attributes['image']) && $attributes['image']
    ? $attributes['image']
    : esc_url(home_url('/wp-content/uploads/shav-cechy-maszynki.png'));
?>
<div class="features-camera-spec__list">
    <div class="features-camera-spec__list-image #parent">
        <img height="650px" src="<?php echo esc_url($image); ?>" class="product-image" alt="Shav 2">
        <div class="testowydiv">
            <ul>
                <?php for ($i = 1; $i <= 3; $i++):
                    $l1 = isset($attributes["feature{$i}Line1"]) ? $attributes["feature{$i}Line1"] : '';
                    $l2 = isset($attributes["feature{$i}Line2"]) ? $attributes["feature{$i}Line2"] : '';
                ?>
                <li>
                    <p class="flex-functions">
                        <span><?php echo wp_kses_post($l1); ?></span>
                        <span><?php echo wp_kses_post($l2); ?></span>
                    </p>
                    <div class="line-functions line-functions-angle"></div>
                </li>
                <?php endfor; ?>
            </ul>
        </div>
        <div class="testowydiv2">
            <ul>
                <?php for ($i = 4; $i <= 6; $i++):
                    $l1 = isset($attributes["feature{$i}Line1"]) ? $attributes["feature{$i}Line1"] : '';
                    $l2 = isset($attributes["feature{$i}Line2"]) ? $attributes["feature{$i}Line2"] : '';
                ?>
                <li>
                    <div class="line-functions line-functions-angle"></div>
                    <p class="flex-functions">
                        <span><?php echo wp_kses_post($l1); ?></span>
                        <span><?php echo wp_kses_post($l2); ?></span>
                    </p>
                </li>
                <?php endfor; ?>
            </ul>
        </div>
    </div>
</div>
