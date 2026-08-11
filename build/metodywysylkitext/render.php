<?php
$header       = isset($attributes['header'])       ? $attributes['header']       : '';
$description  = isset($attributes['description'])  ? $attributes['description']  : '';
$subheader    = isset($attributes['subheader'])    ? $attributes['subheader']    : '';
$option1Title = isset($attributes['option1Title']) ? $attributes['option1Title'] : '';
$option1Desc  = isset($attributes['option1Desc'])  ? $attributes['option1Desc']  : '';
$option1Icon    = isset($attributes['option1Icon'])    ? $attributes['option1Icon']    : '';
$option1IconSvg = isset($attributes['option1IconSvg']) ? $attributes['option1IconSvg'] : '';
$option2Title = isset($attributes['option2Title']) ? $attributes['option2Title'] : '';
$option2Desc  = isset($attributes['option2Desc'])  ? $attributes['option2Desc']  : '';
$option2Icon    = isset($attributes['option2Icon'])    ? $attributes['option2Icon']    : '';
$option2IconSvg = isset($attributes['option2IconSvg']) ? $attributes['option2IconSvg'] : '';
$alignment      = isset($attributes['alignment'])      ? $attributes['alignment']      : 'center';

// Whitelist SVG dla wp_kses (inline SVG z atrybutu)
$svg_allowed = [
    'svg'      => ['xmlns' => 1, 'viewbox' => 1, 'width' => 1, 'height' => 1, 'fill' => 1, 'stroke' => 1, 'class' => 1, 'aria-hidden' => 1],
    'path'     => ['d' => 1, 'fill' => 1, 'stroke' => 1, 'stroke-width' => 1, 'stroke-linecap' => 1, 'stroke-linejoin' => 1, 'clip-rule' => 1, 'fill-rule' => 1, 'opacity' => 1],
    'g'        => ['fill' => 1, 'stroke' => 1, 'transform' => 1, 'clip-path' => 1, 'opacity' => 1],
    'circle'   => ['cx' => 1, 'cy' => 1, 'r' => 1, 'fill' => 1, 'stroke' => 1, 'stroke-width' => 1],
    'rect'     => ['x' => 1, 'y' => 1, 'width' => 1, 'height' => 1, 'rx' => 1, 'ry' => 1, 'fill' => 1, 'stroke' => 1],
    'polygon'  => ['points' => 1, 'fill' => 1, 'stroke' => 1],
    'polyline' => ['points' => 1, 'fill' => 1, 'stroke' => 1],
    'line'     => ['x1' => 1, 'y1' => 1, 'x2' => 1, 'y2' => 1, 'stroke' => 1, 'stroke-width' => 1],
    'defs'     => [],
    'clippath' => ['id' => 1],
    'use'      => ['href' => 1, 'xlink:href' => 1],
];

$options = [
    ['title' => $option1Title, 'desc' => $option1Desc, 'icon' => $option1Icon, 'svg' => $option1IconSvg],
    ['title' => $option2Title, 'desc' => $option2Desc, 'icon' => $option2Icon, 'svg' => $option2IconSvg],
];

$align_class = $alignment === 'left' ? ' metody-wysylki-textcontainer--left' : '';
?>
<section class="blacktext-container-wysylka container">
    <div class="metody-wysylki-textcontainer container--narrow2-important<?php echo esc_attr($align_class); ?>">
        <h2 class="metody-wysylki-header"><?php echo wp_kses_post($header); ?></h2>
        <p class="metody-wysylki-p"><?php echo wp_kses_post($description); ?></p>
        <h2 class="metody-wysylki-h2"><?php echo wp_kses_post($subheader); ?></h2>
        <ul class="metody-wysylki-ul">
            <?php foreach ($options as $opt): ?>
                <div class="metody-wysylki-list">
                    <?php if (!empty($opt['svg'])): ?>
                        <span class="metody-wysylki-list-icon"><?php echo wp_kses($opt['svg'], $svg_allowed); ?></span>
                    <?php elseif (!empty($opt['icon'])): ?>
                        <img class="metody-wysylki-list-icon" src="<?php echo esc_url($opt['icon']); ?>" alt="" style="max-height: 50px;">
                    <?php endif; ?>
                    <li><?php echo wp_kses_post($opt['title']); ?></li>
                    <span><?php echo wp_kses_post($opt['desc']); ?></span>
                </div>
            <?php endforeach; ?>
        </ul>
    </div>
</section>
