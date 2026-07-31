<?php
$titleBefore     = isset($attributes['titleBefore'])     ? $attributes['titleBefore']     : 'Twoje kule';
$titleHighlight  = isset($attributes['titleHighlight'])  ? $attributes['titleHighlight']  : 'gładkie jak nigdy';
$titleAfter      = isset($attributes['titleAfter'])      ? $attributes['titleAfter']      : 'wcześniej';
$subtitle        = isset($attributes['subtitle'])        ? $attributes['subtitle']        : 'zadbaj o nie z shav 2!';
$highlightColor  = isset($attributes['highlightColor'])  ? $attributes['highlightColor']  : '#0983A0';
$backgroundImage = isset($attributes['backgroundImage']) ? $attributes['backgroundImage'] : '';

$inline_style = $backgroundImage
    ? 'background-image:url(' . esc_url($backgroundImage) . ');background-size:cover;background-position:center;'
    : '';
?>
<section class="ogrodnik-container container" <?php echo $inline_style ? 'style="' . $inline_style . '"' : ''; ?>>
    <div class="text-ogrodnik-father">
        <div class="container--narrow2-important content-position-helper">
            <div id="text-container-ogrodnik">
                <p class="line line-head">
                    <?php echo wp_kses_post($titleBefore); ?>
                    <span style="color: <?php echo esc_attr($highlightColor); ?>"><?php echo wp_kses_post($titleHighlight); ?></span>
                    <?php echo wp_kses_post($titleAfter); ?>
                </p>
                <p class="line line-rest"><?php echo wp_kses_post($subtitle); ?></p>
            </div>
        </div>
    </div>
</section>
