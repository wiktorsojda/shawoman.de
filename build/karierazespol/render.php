<?php
$image       = isset($attributes['image']) && $attributes['image']
    ? $attributes['image']
    : 'https://blendygo.pl/wp-content/uploads/zespol-section-zdjecie.jpeg';
$imageAlt    = isset($attributes['imageAlt'])    ? $attributes['imageAlt']    : 'zespol zdjecie';
$heading     = isset($attributes['heading'])     ? $attributes['heading']     : '';
$description = isset($attributes['description']) ? $attributes['description'] : '';
?>
<section class="container zespol-section zespol-section-kariera">
    <div class="container--narrow2-important">
        <div class="zespol-header zespol-header-kariera">
            <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($imageAlt); ?>" class="zespol-image">
            <div class="zespol-text">
                <h2><?php echo wp_kses_post($heading); ?></h2>
                <p><?php echo wp_kses_post($description); ?></p>
            </div>
        </div>
        <div class="stats-grid">
            <?php for ($i = 1; $i <= 4; $i++):
                $num = isset($attributes["stat{$i}Number"]) ? $attributes["stat{$i}Number"] : '';
                $lab = isset($attributes["stat{$i}Label"])  ? $attributes["stat{$i}Label"]  : '';
            ?>
                <div class="stat-item">
                    <div class="stat-number"><?php echo wp_kses_post($num); ?></div>
                    <div class="stat-label"><?php echo wp_kses_post($lab); ?></div>
                </div>
            <?php endfor; ?>
        </div>
    </div>
</section>

<style>
.stat-item {
    display: flex;
    border-radius: 200px;
    width: 160px;
    height: 160px;
    padding: 10px;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    aspect-ratio: 1 / 1;
   

    @media(max-width:800px) {
        width: 150px;
        height: 150px;
    }
    @media(max-width:4150px) {
        width: 138px;
        height: 138px;
    }
}


.stats-grid {
    display: flex;
    
    justify-content: space-between;
    text-align: center;

    @media(max-width:1000px) {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        justify-items: center;
        gap: 16px;
     }
}
.zespol-header  {
    display: flex;
    gap: 60px;
    align-items: center;
    margin-bottom: 60px;

    @media(max-width:1100px) {
        flex-direction: column;
     }

}

.zespol-text {
    display: flex;
    flex-direction: column;
    gap: 32px;

}

.zespol-image {
    border-radius: 24px;
    max-width: 720px;
    @media(max-width:1100px) {
        max-width: 100%;
     }
}

.zespol-header h2 {
    color: var(--Grey_900, #080808);
text-align: center;
font-size: 42px;
font-style: normal;
font-weight: 600;
line-height: 117.75%; 
margin: 0;
font-family: "bebas-neue-pro",sans-serif;

@media(max-width:1100px) {

font-size: 34px;
 }
}

.zespol-header p {
    color: var(--Grey_900, #080808);
text-align: center;
font-family: "roboto", sans-serif;
font-size: 16px;
font-style: normal;
font-weight: 400;
line-height: normal;


 
}


.zespol-section {
    padding: 80px 20px;
    background: #f7f7f7;

}

.blacktext-container-kariera {
    padding: 100px 0px !important;
}




@media(max-width: 900px) { 
    .zespol-header-kariera {
        justify-content: column-reverse !important;
    }

    .zespol-section-kariera {
        padding: 60px 20px !important;
    }

    .blacktext-container-kariera {
        padding: 45px 14px !important;
    }
    
    .features-flex-ikony-kariera {
        grid-template-columns: repeat(2, 1fr) !important;
        gap: 24px !important;
    }
}

</style>
