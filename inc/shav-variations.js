// Warianty na stronach produktowych:
// 1) produkt wielowariantowy — swatche .shav-variations (klik ustawia natywny
//    select WooCommerce; cala logika wariacji zostaje w wc-add-to-cart-variation),
// 2) zestaw (WPC Grouped Product) — zdjecie zestawu per wariant skladnika
//    (mapa z PHP w #shav-woosg-variant-images).
// Galeria produktu jest customowa (.product-gallery), wiec podmiane zdjecia
// robimy sami: jak natywny WC, podmieniamy PIERWSZE zdjecie (stage + thumb).
jQuery(function ($) {
	// --- Galeria: wspolne helpery -------------------------------------------
	var $gallery = $('.product-gallery').first();
	var $stageFirst = $gallery.find('.product-gallery__image[data-index="0"]').first();
	var $thumbFirst = $gallery.find('.product-gallery__thumb[data-index="0"]').first();
	var origStageSrc = $stageFirst.attr('src');
	var origThumbSrc = $thumbFirst.find('img').attr('src');

	function showFirstImage() {
		if ($thumbFirst.length) {
			$thumbFirst[0].click(); // klik utrzymuje wewnetrzny stan galerii (currentIndex)
		} else if ($stageFirst.length) {
			$gallery.find('.product-gallery__image').removeClass('is-active');
			$stageFirst.addClass('is-active');
		}
	}

	function setGalleryImage(fullSrc, thumbSrc) {
		if (!$stageFirst.length || !fullSrc) {
			return;
		}
		$stageFirst.attr('src', fullSrc);
		$thumbFirst.find('img').attr('src', thumbSrc || fullSrc);
		showFirstImage();
	}

	function resetGalleryImage() {
		if (!$stageFirst.length || $stageFirst.attr('src') === origStageSrc) {
			return;
		}
		$stageFirst.attr('src', origStageSrc);
		$thumbFirst.find('img').attr('src', origThumbSrc);
	}

	// --- 1) Produkt wielowariantowy: swatche .shav-variations ---------------
	var $form = $('form.variations_form').has('.shav-variations').first();

	if ($form.length) {
		var $price = $('.custom-price-add-to-cart > .price').first();
		var priceHtmlOrig = $price.html();

		var syncRow = function ($row) {
			var attr = $row.data('attribute');
			var val = $form.find('select[name="' + attr + '"]').val() || '';
			var $selected = null;

			$row.find('.shav-variations__swatch').each(function () {
				var isOn = String($(this).data('value')) === String(val);
				$(this).toggleClass('is-selected', isOn);
				if (isOn) {
					$selected = $(this);
				}
			});

			$row.find('.shav-variations__label-value').text($selected ? $selected.data('label') : '');
			$row.find('.shav-variations__label-sep').toggle(!!$selected);
		};

		var syncAll = function () {
			$form.find('.shav-variations__row').each(function () {
				syncRow($(this));
			});
		};

		$form.on('click', '.shav-variations__swatch', function () {
			var $row = $(this).closest('.shav-variations__row');
			$form
				.find('select[name="' + $row.data('attribute') + '"]')
				.val($(this).data('value'))
				.trigger('change');
			syncRow($row);
		});

		// Zmiany z zewnatrz (reset "wyczysc", niedostepne kombinacje)
		$form.on('change', '.variations select', syncAll);

		// Cena wybranego wariantu w miejscu glownej ceny — 1:1 jak przy produkcie prostym
		$form.on('found_variation', function (event, variation) {
			if (!variation) {
				return;
			}
			if (variation.price_html) {
				var inner = $('<div>').html(variation.price_html).find('.price').html();
				$price.html(inner || variation.price_html);
			}
			var img = variation.image || {};
			if (img.full_src && img.full_src !== $stageFirst.attr('src')) {
				setGalleryImage(img.full_src, img.gallery_thumbnail_src || img.thumb_src);
			} else if (!img.full_src) {
				resetGalleryImage();
			}
		});
		$form.on('reset_data', function () {
			$price.html(priceHtmlOrig);
			resetGalleryImage();
			syncAll();
		});

		syncAll();
	}

	// --- 2) Zestaw (woosg): zdjecie zestawu per wariant skladnika -----------
	var dataEl = document.getElementById('shav-woosg-variant-images');
	var $woosgForms = $('.woosg-wrap form.variations_form');

	if (dataEl && $woosgForms.length && $stageFirst.length) {
		var variantImages = {};
		try {
			variantImages = JSON.parse(dataEl.textContent) || {};
		} catch (e) {}

		// attrs: { attribute_pa_kolor: "bialy" } -> URL z mapy albo null
		var findVariantImage = function (attrs) {
			var url = null;
			$.each(attrs || {}, function (attr, val) {
				if (val && variantImages[attr] && variantImages[attr][val]) {
					url = variantImages[attr][val];
					return false;
				}
			});
			return url;
		};

		var applyFromSelects = function () {
			var attrs = {};
			$woosgForms.find('.variations select').each(function () {
				attrs[$(this).attr('name')] = $(this).val();
			});
			var url = findVariantImage(attrs);
			if (url) {
				setGalleryImage(url, url);
			} else {
				resetGalleryImage();
			}
		};

		$woosgForms.on('found_variation', function (event, variation) {
			var url = variation ? findVariantImage(variation.attributes) : null;
			if (url) {
				setGalleryImage(url, url);
			}
		});
		$woosgForms.on('reset_data', resetGalleryImage);
		$woosgForms.on('change', '.variations select', function () {
			// fallback gdy found_variation nie odpali (np. wariant niedostepny)
			setTimeout(applyFromSelects, 50);
		});

		// stan poczatkowy (domyslnie wybrany wariant)
		applyFromSelects();
	}
});
