<?php
$title    = isset($attributes['title'])    ? $attributes['title']    : 'Kontakt';
$subtitle = isset($attributes['subtitle']) ? $attributes['subtitle'] : '';

$obsługaTitle = isset($attributes['obsługaTitle']) ? $attributes['obsługaTitle'] : '';
$obsługaIntro = isset($attributes['obsługaIntro']) ? $attributes['obsługaIntro'] : '';
$phone        = isset($attributes['phone'])        ? $attributes['phone']        : '';
$phoneHref    = isset($attributes['phoneHref'])    ? $attributes['phoneHref']    : '';
$email        = isset($attributes['email'])        ? $attributes['email']        : '';
$emailHref    = isset($attributes['emailHref'])    ? $attributes['emailHref']    : '';
$hours        = isset($attributes['hours'])        ? $attributes['hours']        : '';

$faqTitle = isset($attributes['faqTitle']) ? $attributes['faqTitle'] : '';

$hurtTitle       = isset($attributes['hurtTitle'])       ? $attributes['hurtTitle']       : '';
$hurtIntro       = isset($attributes['hurtIntro'])       ? $attributes['hurtIntro']       : '';
$hurtButtonLabel = isset($attributes['hurtButtonLabel']) ? $attributes['hurtButtonLabel'] : '';
$hurtButtonURL   = isset($attributes['hurtButtonURL'])   ? $attributes['hurtButtonURL']   : '';

$zwrotyTitle       = isset($attributes['zwrotyTitle'])       ? $attributes['zwrotyTitle']       : '';
$zwrotyIntro       = isset($attributes['zwrotyIntro'])       ? $attributes['zwrotyIntro']       : '';
$zwrotyButtonLabel = isset($attributes['zwrotyButtonLabel']) ? $attributes['zwrotyButtonLabel'] : '';
$zwrotyButtonURL   = isset($attributes['zwrotyButtonURL'])   ? $attributes['zwrotyButtonURL']   : '';

$reklamacjeTitle       = isset($attributes['reklamacjeTitle'])       ? $attributes['reklamacjeTitle']       : '';
$reklamacjeIntro       = isset($attributes['reklamacjeIntro'])       ? $attributes['reklamacjeIntro']       : '';
$reklamacjeButtonLabel = isset($attributes['reklamacjeButtonLabel']) ? $attributes['reklamacjeButtonLabel'] : '';
$reklamacjeButtonURL   = isset($attributes['reklamacjeButtonURL'])   ? $attributes['reklamacjeButtonURL']   : '';

$phone_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="14" viewBox="0 0 24 19" fill="none"><path d="M17.3022 19C13.2702 19 8.9645 17.7761 5.59797 14.9951C2.23705 12.2187 0.75 8.66415 0.75 5.32642C0.75 2.3848 3.63093 0 7.19777 0C7.47332 0 7.72107 0.138566 7.8234 0.349904L10.7104 6.31216C10.8486 6.5976 10.6806 6.92153 10.3351 7.03571L7.16372 8.08361C7.38806 11.0976 10.3167 13.5167 13.9646 13.7018L15.2331 11.082C15.371 10.797 15.763 10.6577 16.109 10.7719L23.3264 13.1568C23.5822 13.2413 23.75 13.446 23.75 13.6736C23.75 16.6152 20.8691 19 17.3022 19Z" fill="#000"/></svg>';
$email_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="14" viewBox="0 0 24 17" fill="none"><path d="M21.7285 0.5H2.77148C1.65913 0.5 0.75 1.39551 0.75 2.5V14.5C0.75 15.6049 1.65971 16.5 2.77148 16.5H21.7285C22.8409 16.5 23.75 15.6045 23.75 14.5V2.5C23.75 1.39524 22.8404 0.5 21.7285 0.5Z" fill="#000"/></svg>';
?>
<section class="faq-container faq-container-kontakt">
    <div class="container--narrow2-important">
        <div class="regulamin-title"><?php echo wp_kses_post($title); ?></div>
        <div class="regulamin-subtitle"><?php echo wp_kses_post($subtitle); ?></div>
        <div class="grid-container-kontakt">

            <div class="item1-kontakt">
                <div class="item-kontakt-title"><?php echo wp_kses_post($obsługaTitle); ?></div>
                <div style="border-top: 0.3px solid #e5e5e5; width: 100%;"></div>
                <span><?php echo wp_kses_post($obsługaIntro); ?></span>
                <div class="footer-contact-container">
                    <div class="footer-icon-container"><?php echo $phone_svg; ?></div>
                    <a href="<?php echo esc_url($phoneHref); ?>"><?php echo wp_kses_post($phone); ?></a>
                </div>
                <div class="footer-contact-container">
                    <div class="footer-icon-container"><?php echo $email_svg; ?></div>
                    <a href="<?php echo esc_url($emailHref); ?>"><?php echo wp_kses_post($email); ?></a>
                </div>
                <span><?php echo wp_kses_post($hours); ?></span>
            </div>

            <div class="item2-kontakt">
                <div class="faq-wrapper-kontakt kontakt-wrapper">
                    <div class="item-kontakt-title"><?php echo wp_kses_post($faqTitle); ?></div>
                    <div class="faq-wrapper-questions-kontakt">
                        <?php
                        $faqItems = isset($attributes['faqItems']) && is_array($attributes['faqItems']) ? $attributes['faqItems'] : [];
                        
                        // Fallback do starych atrybutów jeśli faqItems puste
                        if (empty($faqItems)) {
                            for ($i = 1; $i <= 10; $i++) {
                                $q = isset($attributes["kontaktQuestion{$i}"]) ? $attributes["kontaktQuestion{$i}"] : '';
                                $ans = isset($attributes["kontaktAnswer{$i}"]) ? $attributes["kontaktAnswer{$i}"] : '';
                                if ($q || $ans) {
                                    $faqItems[] = array('question' => $q, 'answer' => $ans);
                                }
                            }
                        }

                        foreach ($faqItems as $item):
                            $q = isset($item['question']) ? $item['question'] : '';
                            $ans = isset($item['answer']) ? $item['answer'] : '';
                            if (!$q && !$ans) continue;
                        ?>
                            <div class="faq-kontakt">
                                <button class="faq-accordion-kontakt" type="button">
                                    <?php echo wp_kses_post($q); ?>
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                                <div class="faq-pannel-kontakt">
                                    <p><?php echo wp_kses_post($ans); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="item3-kontakt background-white">
                <div class="item-kontakt-title"><?php echo wp_kses_post($hurtTitle); ?></div>
                <div style="border-top: 0.3px solid #0983a0; width: 100%; opacity: 0.3"></div>
                <span><?php echo wp_kses_post($hurtIntro); ?></span>
                <a href="<?php echo esc_url(home_url($hurtButtonURL)); ?>"><button class="background-white"><?php echo wp_kses_post($hurtButtonLabel); ?></button></a>
            </div>

            <div class="item4-kontakt background-white">
                <div class="item-kontakt-title"><?php echo wp_kses_post($zwrotyTitle); ?></div>
                <div style="border-top: 0.3px solid #0983a0; width: 100%; opacity: 0.3"></div>
                <span><?php echo wp_kses_post($zwrotyIntro); ?></span>
                <a href="<?php echo esc_url(home_url($zwrotyButtonURL)); ?>"><button class="background-white"><?php echo wp_kses_post($zwrotyButtonLabel); ?></button></a>
            </div>

            <div class="item5-kontakt background-white">
                <div class="item-kontakt-title"><?php echo wp_kses_post($reklamacjeTitle); ?></div>
                <div style="border-top: 0.3px solid #0983a0; width: 100%; opacity: 0.3"></div>
                <span><?php echo wp_kses_post($reklamacjeIntro); ?></span>
                <a href="<?php echo esc_url(home_url($reklamacjeButtonURL)); ?>"><button class="background-white"><?php echo wp_kses_post($reklamacjeButtonLabel); ?></button></a>
            </div>
        </div>
    </div>
</section>
