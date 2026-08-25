<?php
if (!function_exists('get_onas_image')) {
    function get_onas_image($attributes, $i, $default_filename) {
        $key = "item{$i}Image";
        if (isset($attributes[$key]) && $attributes[$key]) {
            return $attributes[$key];
        }
        return esc_url(home_url('/wp-content/uploads/' . $default_filename));
    }
}

$defaults = [
    'shav-o-nas-1.jpg',
    'shav-o-nas-3.jpg',
    'shav-o-nas-2.webp',
    'shav-o-nas-4.jpg',
];
?>
<section class="container container-onas-images" page-content>
    <section class="banner-onas" aria-label="popular movies">
        <div class="banner-slider-onas">
            <?php for ($i = 1; $i <= 4; $i++):
                $img   = get_onas_image($attributes, $i, $defaults[$i - 1]);
                $alt   = isset($attributes["item{$i}Alt"])   ? $attributes["item{$i}Alt"]   : '';
                $title = isset($attributes["item{$i}Title"]) ? $attributes["item{$i}Title"] : '';
                $text  = isset($attributes["item{$i}Text"])  ? $attributes["item{$i}Text"]  : '';
                $active = $i === 1 ? 'active-onas' : '';
            ?>
                <div class="slider-item-onas <?php echo $active; ?>" slider-item>
                    <img src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr($alt); ?>" class="img-cover-onas bg-image-onas" loading="<?php echo $i === 1 ? 'eager' : 'lazy'; ?>">
                    <div class="banner-content-onas">
                        <h2 class="heading-onas"><?php echo wp_kses_post($title); ?></h2>
                        <p class="banner-text-onas"><?php echo wp_kses_post($text); ?></p>
                    </div>
                </div>
            <?php endfor; ?>
        </div>

        <button class="arrow-onas left-arrow-onas">
            <svg xmlns="http://www.w3.org/2000/svg" width="43" height="44" viewBox="0 0 43 44" fill="none">
                <circle cx="21.5" cy="22" r="21.5" transform="rotate(-90 21.5 22)" fill="white" fill-opacity="0.3"/>
                <path fill-rule="evenodd" clip-rule="evenodd" d="M23.4664 14.6428C23.9162 15.0712 23.9162 15.7657 23.4664 16.1941L17.3701 22.0001L23.4664 27.8061C23.9162 28.2345 23.9162 28.929 23.4664 29.3574C23.0166 29.7858 22.2873 29.7858 21.8375 29.3574L14.9268 22.7758C14.477 22.3474 14.477 21.6528 14.9268 21.2245L21.8375 14.6428C22.2873 14.2144 23.0166 14.2144 23.4664 14.6428Z" fill="white"/>
            </svg>
        </button>

        <div class="slider-control-onas">
            <div class="control-inner-onas">
                <?php for ($i = 1; $i <= 4; $i++):
                    $img = get_onas_image($attributes, $i, $defaults[$i - 1]);
                    $alt = isset($attributes["item{$i}Alt"]) ? $attributes["item{$i}Alt"] : '';
                    $active = $i === 1 ? 'active-onas' : '';
                ?>
                    <button class="poster-box-onas slider-item-onas <?php echo $active; ?>">
                        <img src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr($alt); ?>" loading="lazy" draggable="false" class="img-cover-onas">
                    </button>
                <?php endfor; ?>
            </div>
        </div>

        <button class="arrow-onas right-arrow-onas">
            <svg xmlns="http://www.w3.org/2000/svg" width="43" height="44" viewBox="0 0 43 44" fill="none">
                <circle cx="21.5" cy="22" r="21.5" transform="rotate(90 21.5 22)" fill="white" fill-opacity="0.3"/>
                <path fill-rule="evenodd" clip-rule="evenodd" d="M19.5336 29.3572C19.0838 28.9288 19.0838 28.2343 19.5336 27.8059L25.6299 21.9999L19.5336 16.1939C19.0838 15.7655 19.0838 15.071 19.5336 14.6426C19.9834 14.2142 20.7127 14.2142 21.1625 14.6426L28.0732 21.2242C28.523 21.6526 28.523 22.3472 28.0732 22.7755L21.1625 29.3572C20.7127 29.7856 19.9834 29.7856 19.5336 29.3572Z" fill="white"/>
            </svg>
        </button>
    </section>
</section>
