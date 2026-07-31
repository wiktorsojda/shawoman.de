<?php
$sectionTitleBefore    = isset($attributes['sectionTitleBefore'])    ? $attributes['sectionTitleBefore']    : 'Zespół';
$sectionTitleHighlight = isset($attributes['sectionTitleHighlight']) ? $attributes['sectionTitleHighlight'] : 'Shava';
$sectionSubtitle       = isset($attributes['sectionSubtitle'])       ? $attributes['sectionSubtitle']       : '';
$highlightColor        = isset($attributes['highlightColor'])        ? $attributes['highlightColor']        : '#0983A0';
$showMoreLabel         = isset($attributes['showMoreLabel'])         ? $attributes['showMoreLabel']         : 'Zobacz wszystkich';
?>
<section class="team container">
    <div class="center">
        <h2><?php echo wp_kses_post($sectionTitleBefore); ?> <span style="color: <?php echo esc_attr($highlightColor); ?>"><?php echo wp_kses_post($sectionTitleHighlight); ?></span></h2>
        <span class="center-span"><?php echo wp_kses_post($sectionSubtitle); ?></span>
    </div>
    <div class="team-content">
        <?php for ($i = 1; $i <= 20; $i++):
            $name = isset($attributes["member{$i}Name"]) ? $attributes["member{$i}Name"] : '';
            $role = isset($attributes["member{$i}Role"]) ? $attributes["member{$i}Role"] : '';
            $box  = isset($attributes["member{$i}BoxClass"]) ? $attributes["member{$i}BoxClass"] : '';
            if (!$name && !$role) continue;
        ?>
            <div class="box <?php echo esc_attr($box); ?>">
                <h3 class="imie"><?php echo wp_kses_post($name); ?></h3>
                <h3 class="stanowisko"><?php echo wp_kses_post($role); ?></h3>
            </div>
        <?php endfor; ?>
    </div>
    <button id="toggleButton" class="show-more"><?php echo wp_kses_post($showMoreLabel); ?></button>
</section>
