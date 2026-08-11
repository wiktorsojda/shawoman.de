<?php
$header      = isset($attributes['header'])      ? $attributes['header']      : '';
$description = isset($attributes['description']) ? $attributes['description'] : '';
$methods     = isset($attributes['methods']) && is_array($attributes['methods']) ? $attributes['methods'] : [];
$alignment   = isset($attributes['alignment'])   ? $attributes['alignment']   : 'center';
$alignClass  = $alignment === 'left' ? ' metody-wysylki-textcontainer--left' : '';

// Fallback do legacy method1..4 jesli `methods` puste
if (empty($methods)) {
    for ($i = 1; $i <= 4; $i++) {
        $t = isset($attributes["method{$i}Title"]) ? $attributes["method{$i}Title"] : '';
        $d = isset($attributes["method{$i}Desc"])  ? $attributes["method{$i}Desc"]  : '';
        $iconUrl = isset($attributes["method{$i}Icon"])    ? $attributes["method{$i}Icon"]    : '';
        $iconSvg = isset($attributes["method{$i}IconSvg"]) ? $attributes["method{$i}IconSvg"] : '';
        if ($t || $d || $iconUrl || $iconSvg) {
            $methods[] = ['title' => $t, 'desc' => $d, 'icon' => $iconUrl, 'iconSvg' => $iconSvg];
        }
    }
}

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
?>
<section class="blacktext-container-wysylka container">
    <div class="metody-wysylki-textcontainer container--narrow2-important<?php echo $alignClass; ?>">
        <h2 class="metody-wysylki-header"><?php echo wp_kses_post($header); ?></h2>
        <p class="metody-wysylki-p"><?php echo wp_kses_post($description); ?></p>
        <ul class="metody-platnosci-ul">
            <?php foreach ($methods as $m):
                $t = isset($m['title'])   ? $m['title']   : '';
                $d = isset($m['desc'])    ? $m['desc']    : '';
                $iconUrl = isset($m['icon'])    ? $m['icon']    : '';
                $iconSvg = isset($m['iconSvg']) ? $m['iconSvg'] : '';
                if (!$t && !$d) continue;
            ?>
                <div class="metody-platnosci-list">
                    <div class="metody-platnosci-list-left">
                        <li><?php echo wp_kses_post($t); ?></li>
                        <span><?php echo wp_kses_post($d); ?></span>
                    </div>
                    <div class="metody-platnosci-list-right">
                        <?php if (!empty($iconSvg)): ?>
                            <span class="metody-platnosci-icon-svg"><?php echo wp_kses($iconSvg, $svg_allowed); ?></span>
                        <?php elseif (!empty($iconUrl)): ?>
                            <img src="<?php echo esc_url($iconUrl); ?>" alt="" style="max-height: 64px; max-width: 64px; object-fit: contain;">
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </ul>
    </div>
</section>
