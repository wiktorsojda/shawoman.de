<?php
$sectionTitle = isset($attributes['sectionTitle']) ? $attributes['sectionTitle'] : '';
?>
<section class="faq-container-glowna" style="background-color: #fff;">
    <div class="faq-wrapper container--narrow2-important" style="background-color: #FCFCFC; padding: 0px;">
        <div class="section-main-title" style="text-align: center;"><?php echo wp_kses_post($sectionTitle); ?></div>
        <div class="faq-wrapper-questions">
            <?php for ($i = 1; $i <= 6; $i++):
                $t = isset($attributes["offer{$i}Title"])   ? $attributes["offer{$i}Title"]   : '';
                $c = isset($attributes["offer{$i}Content"]) ? $attributes["offer{$i}Content"] : '';
                if (!$t && !$c) continue;
            ?>
                <div class="faq">
                    <button class="faq-accordion" style="padding: 20px; border-radius: 8px; background-color: #f7f7f7; padding-right: 20px !important;">
                        <h3 class="faq-header" style="color: #0F8BE8; text-decoration: underline;"><?php echo wp_kses_post($t); ?></h3>
                        <i class="fas fa-chevron-down" style="color: #000"></i>
                    </button>
                    <div class="faq-pannel" style="color: #000; background-color: #f7f7f7">
                        <div style="padding: 20px;">
                            <?php echo wp_kses_post($c); ?>
                        </div>
                    </div>
                </div>
            <?php endfor; ?>
        </div>
    </div>
</section>
