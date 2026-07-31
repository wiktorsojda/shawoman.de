<?php
$title             = isset($attributes['title'])             ? $attributes['title']             : '';
$subtitle          = isset($attributes['subtitle'])          ? $attributes['subtitle']          : '';
$tab1Label         = isset($attributes['tab1Label'])         ? $attributes['tab1Label']         : 'Zwroty';
$tab2Label         = isset($attributes['tab2Label'])         ? $attributes['tab2Label']         : 'Reklamacje';
$zwrotyContent     = isset($attributes['zwrotyContent'])     ? $attributes['zwrotyContent']     : '';
$reklamacjeContent = isset($attributes['reklamacjeContent']) ? $attributes['reklamacjeContent'] : '';
?>
<section class="faq-container faq-container-kontakt">
    <div class="container--narrow2-important">
        <div class="regulamin-title-container">
            <div class="regulamin-title"><?php echo wp_kses_post($title); ?></div>
            <div class="regulamin-subtitle"><?php echo wp_kses_post($subtitle); ?></div>
        </div>
        <div class="zwroty-heading">
            <div id="zwrotyBtn" class="active"><?php echo wp_kses_post($tab1Label); ?></div>
            <div id="reklamacjeBtn"><?php echo wp_kses_post($tab2Label); ?></div>
        </div>
        <div class="zwroty-container active">
            <?php echo wp_kses_post($zwrotyContent); ?>
        </div>
        <div class="reklamacje-container" style="display:none;">
            <?php echo wp_kses_post($reklamacjeContent); ?>
        </div>
    </div>
</section>
