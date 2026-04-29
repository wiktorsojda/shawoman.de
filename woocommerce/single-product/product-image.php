<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.8.0
 */

defined( 'ABSPATH' ) || exit;

// Note: `wc_get_gallery_image_html` was added in WC 3.3.2 and did not exist prior. This check protects against theme overrides being used on older versions of WC.
if ( ! function_exists( 'wc_get_gallery_image_html' ) ) {
	return;
}

global $product;

$columns           = apply_filters( 'woocommerce_product_thumbnails_columns', 4 );
$post_thumbnail_id = $product->get_image_id();
$gallery_image_ids = $product->get_gallery_image_ids();
$wrapper_classes   = apply_filters(
	'woocommerce_single_product_image_gallery_classes',
	array(
		'woocommerce-product-gallery',
		'woocommerce-product-gallery--' . ( $post_thumbnail_id ? 'with-images' : 'without-images' ),
		'woocommerce-product-gallery--columns-' . absint( $columns ),
		'images',
	)
);
?>
<div class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $wrapper_classes ) ) ); ?>" data-columns="<?php echo esc_attr( $columns ); ?>" style="opacity: 0; transition: opacity .25s ease-in-out;">


<div class="woocommerce-product-gallery__wrapper">
		<?php
		if ( $post_thumbnail_id ) {
			$html = wc_get_gallery_image_html( $post_thumbnail_id, true );
		} else {
			$html  = '<div class="woocommerce-product-gallery__image--placeholder">';
			$html .= sprintf( '<img src="%s" alt="%s" class="wp-post-image" />', esc_url( wc_placeholder_img_src( 'woocommerce_single' ) ), esc_html__( 'Awaiting product image', 'woocommerce' ) );
			$html .= '</div>';
		}

		echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $post_thumbnail_id ); // phpcs:disable WordPress.XSS.EscapeOutput.OutputNotEscaped

		// Remove the carousel code here
		// Loop through and display product gallery images
		if ( $gallery_image_ids && is_array( $gallery_image_ids ) ) {
			foreach ( $gallery_image_ids as $gallery_image_id ) {
				$gallery_image_html = wc_get_gallery_image_html( $gallery_image_id );
				echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $gallery_image_html, $gallery_image_id ); // Display the gallery images
			}
		}
		// Add the custom icons and text below the product image
		?>


    </div>

</div>
<!-- 
//  defined('ABSPATH') || exit;

//  if (!function_exists('wc_get_gallery_image_html')) {
// 	 return;
 
//  }
 
//  global $product;
 
//  $columns           = apply_filters('woocommerce_product_thumbnails_columns', 4);
//  $post_thumbnail_id = $product->get_image_id();
//  $gallery_image_ids = $product->get_gallery_image_ids();
//  $wrapper_classes   = apply_filters(
// 	 'woocommerce_single_product_image_gallery_classes',
// 	 [
// 		 'woocommerce-product-gallery',
// 		 'woocommerce-product-gallery--' . ($post_thumbnail_id ? 'with-images' : 'without-images'),
// 		 'woocommerce-product-gallery--columns-' . absint($columns),
// 		 'images',
// 	 ]
//  );
 
//  $all_image_ids = [];
//  if ($post_thumbnail_id) {
// 	 $all_image_ids[] = $post_thumbnail_id;
//  }
//  if ($gallery_image_ids && is_array($gallery_image_ids)) {
// 	 $all_image_ids = array_merge($all_image_ids, $gallery_image_ids);
//  }
//  ?>
  -->
<!-- //  <div class="<#?php echo esc_attr(implode(' ', array_map('sanitize_html_class', $wrapper_classes))); ?>" data-columns="<#?php echo esc_attr($columns); ?>" style="opacity: 1; transition: opacity .25s ease-in-out;"> -->
<!-- //  <div class="swiper-button-prev"></div> -->
	
<!-- // 	 Główny slider Swiper (1 zdjęcie na raz, fade) -->
<!-- // 	 <div class="swiper main-slider"> -->
<!-- // 		 <div class="swiper-wrapper"> -->
<!-- // 			 <#?php 
// 		foreach ( $all_image_ids as $image_id ) {
// 			// każdy slajd to oryginalny HTML galerii, z danymi dla wariacji
// 			$slide = wc_get_gallery_image_html( $image_id );
// 			echo '<div class="swiper-slide">';
// 			echo $slide;
// 			echo '</div>';
// 		}
 
 
 
 
 
 
			 
// 			 ?>
// 		 </div>
// 	 </div>
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
// 		 <div class="swiper-button-next"></div>
// 	  Miniatury slider Swiper z nawigacją 
// 	 <div class="swiper thumb-slider">
// 		 <div class="swiper-wrapper">
// 			 <3?php
// 			 foreach($all_image_ids as $image_id) {
// 				 echo '<div class="swiper-slide">';
// 				 echo wp_get_attachment_image($image_id, 'thumbnail');
// 				 echo '</div>';
// 			 }
// 			 ?>
// 		 </div>
// 		  <div class="swiper-button-prev"></div>
// 		 <div class="swiper-button-next"></div> -->
<!-- // 	 </div> -->
 
<!-- //  </div> -->
 
 <!-- Swiper synchronizacja main<->thumbs -->
 <script>
//  document.addEventListener('DOMContentLoaded', function() {
// 	 // Swiper dla miniatur
// 	 var thumbSwiper = new Swiper('.thumb-slider', {
// 	 spaceBetween: 10,
// 	 slidesPerView: 3, // mobile domyślnie
// 	 watchSlidesProgress: true,
// 	 centerInsufficientSlides: true,
// 	 centeredSlides: false,
// 	 breakpoints: {
// 		 480: { slidesPerView: 3 },
// 		 768: { slidesPerView: 4 },
// 		 1024: { slidesPerView: 5 } // max 5 na desktopie
// 	 }
// 	 // navigation: nie używamy!
//  });
 
// 	 // Swiper dla głównego zdjęcia
// 	 var mainSwiper = new Swiper('.main-slider', {
// 		 spaceBetween: 10,
// 		 effect: 'fade',
// 		 fadeEffect: { crossFade: true },
// 		 thumbs: {
// 			 swiper: thumbSwiper,
// 		 }
// 	 });
 
// 	 // Własna obsługa strzałek
// 	 var prev = document.querySelector('.swiper-button-prev');
// 	 var next = document.querySelector('.swiper-button-next');
// 	 function slideToThumb(idx) {
// 	 // Nie przesuwaj thumbSwiper, jeśli aktywny slajd mieści się w oknie
// 	 let visibleSlides = thumbSwiper.slidesPerViewDynamic();
// 	 let firstVisible = thumbSwiper.activeIndex;
// 	 let lastVisible = firstVisible + visibleSlides - 1;
// 	 if (idx < firstVisible) {
// 		 thumbSwiper.slideTo(idx); // po lewej, przesuń slider
// 	 } else if (idx > lastVisible) {
// 		 // po prawej, przesuń tak, żeby wybrany slajd był ostatni na widoku
// 		 thumbSwiper.slideTo(idx - visibleSlides + 1);
// 	 }
// 	 mainSwiper.slideTo(idx); // zawsze zmieniaj główne zdjęcie
//  }
// 	 if (next) {
// 		 next.addEventListener('click', function() {
// 			 let current = mainSwiper.activeIndex;
// 			 if (current < mainSwiper.slides.length - 1) {
// 				 slideToThumb(current + 1);
// 			 }
// 		 });
// 	 }
// 	 if (prev) {
// 		 prev.addEventListener('click', function() {
// 			 let current = mainSwiper.activeIndex;
// 			 if (current > 0) {
// 				 slideToThumb(current - 1);
// 			 }
// 		 });
// 	 }
//  });
 </script>
 
 
 <style>
 /* Slider główny
 .swiper.thumb-slider {
	 max-width: 500px;
	 margin-left: auto;
	 margin-right: auto;
 }
 
 .main-slider {
	 width: 100%;
	 margin-bottom: 12px;
 }
 .main-slider .swiper-slide {
	 display: flex;
	 align-items: center;
	 justify-content: center;
 }
 .main-slider img {
	 width: 100%;
	 max-height: 550px;
	 object-fit: contain;
	 border-radius: 8px;
 }
 
 /* Slider miniatur */
 /* .swiper.thumb-slider {
	 width: 100%;
	 height: 90px;
	 margin-top: 0;
	 margin-bottom: 21px;
	 position: relative;
	 display: none;
 }
 
 .swiper.thumb-slider .swiper-wrapper {
	 justify-content: center;
 }
 .thumb-slider .swiper-slide {
	 opacity: 0.6;
	 cursor: pointer;
	 border: 2px solid transparent;
	 border-radius: 6px;
	 transition: border .2s, opacity .2s;
	 width: 80px !important;
	 height: 80px;
	 display: flex;
	 align-items: center;
	 justify-content: center;
 
 }
 
 .swiper-slide img {
	 border-radius: 8px;
 }
 .thumb-slider .swiper-slide-thumb-active {
	 opacity: 1;
	 border-radius: 8px;
	 border: 2px solid #0983A0;
	 
 }
 .swiper-button-next,
 .swiper-button-prev {
	 color: #fff;
	 top: 50%;
	 transform: translateY(-50%);
	 width: 47.5px;
	 height: 47.5px;
	 border-radius: 50%;
	 background: rgba(166, 166, 166, 0.90);
	 box-shadow: 0 2px 8px rgba(0,0,0,0.10);
 }
 .swiper-button-next:after,
 .swiper-button-prev:after {
	 font-size: 18px;
 }
 
 .swiper-button-prev,
 .swiper-button-next {
	 display: flex !important;
	 opacity: 1 !important;
	 pointer-events: auto !important;
 } */
  
 </style>