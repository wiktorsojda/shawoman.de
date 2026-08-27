<?php
$wrapper_attrs = get_block_wrapper_attributes([
    'class' => 'ekokarty'
]);

$title = isset($attributes['title']) ? $attributes['title'] : 'W Shav Woman stawiamy na rozwiązania, które mogą <br>ograniczać ilość niepotrzebnych odpadów. Dlatego oferujemy <br>wielorazowe maszynki do golenia i korzystamy z bardziej <br>odpowiedzialnych form dostawy.';

$card1Icon = isset($attributes['card1Icon']) ? $attributes['card1Icon'] : '';
$card1TopIcon = isset($attributes['card1TopIcon']) ? $attributes['card1TopIcon'] : '';
$card1Title = isset($attributes['card1Title']) ? $attributes['card1Title'] : 'Nawet 3 000 000 mniej jednorazowych maszynek';
$card1Text = isset($attributes['card1Text']) ? $attributes['card1Text'] : 'Jako marka Shav do tej pory sprzedaliśmy już około 300 000 wielorazowych maszynek. Jeśli każda z nich zastąpi w czasie użytkowania zaledwie 10 plastikowych jednorazówek, oznacza to nawet <strong>3 miliony jednorazowych maszynek mniej.</strong><br><br>To prosty wybór, który przy regularnym goleniu może realnie <strong>ograniczać zużycie plastiku</strong> i ilość generowanych odpadów.';

$card2Icon = isset($attributes['card2Icon']) ? $attributes['card2Icon'] : '';
$card2TopIcon = isset($attributes['card2TopIcon']) ? $attributes['card2TopIcon'] : '';
$card2Title = isset($attributes['card2Title']) ? $attributes['card2Title'] : 'Stworzona do dłuższego użytkowania';
$card2Text = isset($attributes['card2Text']) ? $attributes['card2Text'] : 'Shav Woman <strong>nie jest</strong> produktem na kilka goleń. To urządzenie <strong>wielokrotnego</strong> użytku, które <strong>może służyć przez długi czas.</strong><br><br>Gdy wymienne ostrze się zużyje, <strong>nie musisz wymieniać całego urządzenia</strong> – możesz dokupić nowe ostrza i dalej korzystać z tego samego <strong>urządzenia.</strong> Dzięki temu <strong>ograniczasz ilość odpadów</strong> i wydłużasz czas użytkowania produktu.';

$card3Icon = isset($attributes['card3Icon']) ? $attributes['card3Icon'] : '';
$card3TopIcon = isset($attributes['card3TopIcon']) ? $attributes['card3TopIcon'] : '';
$card3Title = isset($attributes['card3Title']) ? $attributes['card3Title'] : 'Dostawa z DHL GoGreen';
$card3Text = isset($attributes['card3Text']) ? $attributes['card3Text'] : 'Nasze przesyłki wysyłamy za pośrednictwem usługi <strong>DHL GoGreen</strong>. DHL realizuje w jej ramach działania mające na celu ograniczenie i kompensowanie emisji związanych z transportem przesyłek.<br><br><strong>Małe wybory mają znaczenie.</strong> Wierzymy, że komfort codziennej pielęgnacji może iść w parze z bardziej świadomym podejściem do środowiska.';

// Oczyszczanie kodu HTML, wliczając kolory span z edytora Gutenberga.
$allowed_html = array(
    'br' => array(),
    'strong' => array(),
    'b' => array(),
    'em' => array(),
    'i' => array(),
    'span' => array(
        'style' => array(),
        'class' => array()
    )
);

// Alternatywnie dla tekstow gdzie chcemy wspierac wiecej tagow
$allowed_text_html = array_merge(wp_kses_allowed_html('post'), [
    'span' => [
        'style' => true,
        'class' => true
    ]
]);
?>

<section <?php echo $wrapper_attrs; ?>>
    <div class="ekokarty__inner">
        <h2 class="ekokarty__title"><?php echo wp_kses($title, $allowed_html); ?></h2>
        
        <div class="ekokarty__grid">
            <div class="ekokarty__card">
                <?php if ($card1TopIcon): ?>
                    <img src="<?php echo esc_url($card1TopIcon); ?>" class="ekokarty__card-top-icon" alt="">
                <?php endif; ?>
                <div class="ekokarty__card-icon">
                    <?php if ($card1Icon): ?>
                        <img src="<?php echo esc_url($card1Icon); ?>" alt="">
                    <?php endif; ?>
                </div>
                <h3 class="ekokarty__card-title"><?php echo wp_kses($card1Title, $allowed_html); ?></h3>
                <div class="ekokarty__card-text"><?php echo wp_kses($card1Text, $allowed_text_html); ?></div>
            </div>

            <div class="ekokarty__card">
                <?php if ($card2TopIcon): ?>
                    <img src="<?php echo esc_url($card2TopIcon); ?>" class="ekokarty__card-top-icon" alt="">
                <?php endif; ?>
                <div class="ekokarty__card-icon">
                    <?php if ($card2Icon): ?>
                        <img src="<?php echo esc_url($card2Icon); ?>" alt="">
                    <?php endif; ?>
                </div>
                <h3 class="ekokarty__card-title"><?php echo wp_kses($card2Title, $allowed_html); ?></h3>
                <div class="ekokarty__card-text"><?php echo wp_kses($card2Text, $allowed_text_html); ?></div>
            </div>

            <div class="ekokarty__card ekokarty__card--green">
                <?php if ($card3TopIcon): ?>
                    <img src="<?php echo esc_url($card3TopIcon); ?>" class="ekokarty__card-top-icon" alt="">
                <?php endif; ?>
                <div class="ekokarty__card-icon">
                    <?php if ($card3Icon): ?>
                        <img src="<?php echo esc_url($card3Icon); ?>" alt="">
                    <?php endif; ?>
                </div>
                <h3 class="ekokarty__card-title ekokarty__card-title--green"><?php echo wp_kses($card3Title, $allowed_html); ?></h3>
                <div class="ekokarty__card-text ekokarty__card-text--green"><?php echo wp_kses($card3Text, $allowed_text_html); ?></div>
            </div>
        </div>
    </div>
</section>
