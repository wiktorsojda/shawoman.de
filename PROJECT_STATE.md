# PROJECT_STATE — Shav Woman Theme

> Punkt wejścia do nowej sesji. Jeśli to czytasz po starcie czystego kontekstu — to jest najważniejszy plik. Po nim zerknij do `css/base/_tokens.scss` (design tokens) i `memory/MEMORY.md` (osobiste notki).

## 1. Główny cel i założenia

- **Motyw WordPress + WooCommerce** dla marki Shav Woman, oparty o **design system z Figmy**.
- **Hybryda block-theme + classic** — nie czysty FSE. Mamy `templates/*.html` (block templates) podpięte w `theme.json`, ale klasyczne `functions.php` (3721 linii) zarządza WooCommerce, custom bloki, hooki itp.
- **85 customowych bloków Gutenberg** w `src/`, **wszystkie zarejestrowane** w `functions.php` jako `register_block_type_from_metadata(__DIR__ . '/build/<slug>')`. Audyt 2026-05-07: dodano 10 nowych bloków (`masazerwoman1-4`, `myjkaszach1-4`, `blackwithtextshavwomen`, `faqshavwomen`, plus `glownainstagram`).
- **Design system w SCSS** (`css/base/_tokens.scss`) jest **źródłem prawdy** dla kolorów, typografii, spacing, radius, effects. Wszystkie nowe moduły używają wyłącznie tokenów.
- **Wszystkie 75 bloków zostało doprowadzonych do edytowalności** z poziomu Gutenberga (RichText, MediaUpload, Inspector controls, block supports). Defaulty zachowują oryginalną treść.
- **Dla produktów WooCommerce włączyliśmy Gutenberga** (filtr `use_block_editor_for_post_type` w `functions.php`) — bloki są edytowalne także w długim opisie produktu.

## 2. Struktura plików (Mapa projektu)

```
ShavWoman/
├── PROJECT_STATE.md            ← ten plik
├── theme.json                  ← settings (paleta, fontSizes, layout, custom templates registry)
├── style.css                   ← stub `Theme Name: Custom Theme` (real style w build/)
├── functions.php               ← 3721 lin: rejestracja bloków, hooks WC, GTM, fonty
├── package.json                ← scripts: start | build | buildIndex | buildBlocks
├── src/                        ← 75 bloków Gutenberg (każdy = block.json + edit.js + index.js + render.php)
│   ├── index.js                ← entry webpack (importuje css/style.scss)
│   ├── header/, footer/        ← layout
│   ├── glownabaner/, glownacechy/, glownagrid/, glownaopinie/, glownaoceny/,
│   │   glownainstagram/, glownaikony/, glownakup/, faqglowna/  ← strona główna
│   ├── faq/, faqetui/, faqostrzefoliowe/, faqostrzestalowe/   ← FAQ produktowe
│   ├── cechy*/, *produktowa/, szachshavwomen[1-4]/             ← sekcje produktów
│   ├── hurtdesign/, hurtmapa/, hurtkontakt/, hurthead/         ← strona hurtowa
│   ├── kariera*/, textkariera/                                ← strona kariery
│   ├── patent*/, regulamin/, politykaprywatnosci/, zwroty/    ← treść statyczna
│   ├── metody*/, sliderplatnosci/, sliderwysylka/             ← płatności / wysyłka
│   ├── onashead/, onasimages/, onasteam/                      ← strona "o nas"
│   └── whoweare/, whoweareonas/, whoweareglowna/              ← warianty CTA
├── build/                      ← output webpack: <block>/, index.css, style-index.css
├── css/
│   ├── style.scss              ← @import wszystkiego (kompiluje się do build/style-index.css)
│   ├── base/
│   │   ├── _tokens.scss        ← ⭐ DESIGN SYSTEM: kolory, typo, spacing, radius, effects
│   │   ├── variables.scss      ← legacy (5 starych $beige itd.) — używane tylko przez btn.scss
│   │   ├── baseline.scss
│   │   └── utility-classes.scss
│   ├── modules/                ← BEM moduły (avatar, button, card, header, glowna*…)
│   └── woocommerce/            ← cart, shop, single-product (zrefaktoryzowane do tokens)
├── templates/                  ← block-templates HTML (FSE-style)
│   ├── front-page.html         ← <!-- wp:ourblocktheme/header --> ... <!-- wp:ourblocktheme/glownabaner --> ...
│   ├── archive.html, page.html, faq.html, contact.html, about-us.html,
│   │   delivery-methods.html, kariera.html, emptycanvas.html, index.html
├── parts/                      ← BRAK (FSE template parts nie używane)
├── patterns/                   ← BRAK
├── inc/
│   ├── custom-gsap.js          ← GSAP scrolltrigger animacje
│   ├── mobile-menu.js
│   ├── ourColors.js            ← legacy paleta dla starych bloków (genericbutton)
│   └── slider-platnosci.js
├── images/library-hero.jpg
├── woocommerce/                ← override pluginu WC (templates: cart, checkout, single-product, archive-product.php)
├── memory/                     ← MEMORY.md + project_inline_css_in_blocks.md (notki długoterminowe)
└── node_modules/, .git/, build/, screenshot.png
```

**Najważniejsze pliki:**

| Plik | Rola |
|---|---|
| `css/base/_tokens.scss` | Źródło prawdy DS — kolory, fonty, spacing, radius, button/header/card tokens |
| `css/style.scss` | Główny SCSS, import wszystkich modułów |
| `functions.php` | Rejestracja 75 bloków + hooki WC + filtr Gutenberg dla produktów |
| `theme.json` | Globalne ustawienia WP (paleta, layout, custom templates) |
| `templates/front-page.html` | Strona główna — kompozycja bloków `ourblocktheme/*` |
| `src/<block>/render.php` | Server-side render każdego bloku (używa `$attributes`) |

## 3. Stan theme.json

Kluczowe ustawienia w `theme.json`:

- **Paleta kolorów (legacy)**: 4 sloty — `pizza` (#0d3b66), `secondary` (#ee964b), `background` (#FFFFFF), `foreground` (#FFFFFF). **Te NIE są aktualnym design systemem** — design system żyje w `css/base/_tokens.scss` (`$color-peach-*`, `$color-neutral-*`, `$color-info-*`, etc.). theme.json nie został jeszcze zsynchronizowany.
- **Typografia**: `fontSizes: []` (puste — DS żyje w SCSS).
- **Layout**: `contentSize: 1840px`.
- **`appearanceTools: true`** — daje user UI dla margin/padding/border w blokach.
- **Custom templates registry** (sekcja `settings.settings.general.custom.templates`): 10 templates do wyboru w admin (Front Page, Faq, Patents, About us, Wholesale, Returns, Contact, Payment methods, Delivery methods, Carrier).
- **Override `core/button`**: tekst biały + bg `--wp--preset--color--pizza`, border kontrolki wyłączone.

**Do zrobienia w przyszłości:** zsynchronizować paletę i fontSizes z `_tokens.scss` żeby UI bloków pokazywał spójne kolory — patrz To-Do.

## 4. Ukończone szablony i części

`templates/*.html` (block-template HTML, używane przez WP block-template engine):

| Plik | Zawartość |
|---|---|
| `front-page.html` | Strona główna: header → glownabaner → glownacechy → glownagrid → glownaoceny → glownaopinie → glownainstagram → faqglowna → footer |
| `archive.html` | Lista postów (block `ourblocktheme/archive`) |
| `page.html` | Pojedyncza strona (block `ourblocktheme/page`) |
| `faq.html` | Strona FAQ |
| `contact.html`, `about-us.html`, `delivery-methods.html`, `kariera.html` | Podstrony |
| `index.html` | Fallback default |
| `emptycanvas.html` | Pusty kanwy do customowych stron |

Każdy template to kompozycja `<!-- wp:ourblocktheme/<block> -->`. Brak `parts/` ani `patterns/` — jeden monolityczny HTML per template.

## 5. Customowe rozwiązania i PHP

`functions.php` (3721 lin) zawiera:

- **Adobe Typekit + Google Fonts** preconnect/dns-prefetch (`add_resource_hints_and_fonts`)
- **Google Tag Manager + TikTok Pixel** (head injection)
- **Filtr Gutenberg dla `product`** post type:
  ```php
  add_filter('use_block_editor_for_post_type', fn($u, $p) => $p === 'product' ? true : $u, 10, 2);
  ```
- **Rejestracja 75 bloków** w funkcji `our_new_blocks()` (linie 762-852+):
  ```php
  register_block_type_from_metadata(__DIR__ . '/build/<block>');
  ```
- **WooCommerce custom fields** (linie 1071+): `woocommerce_wp_text_input` dla niestandardowych pól produktów
- **Custom WC hooki**: order tracking, mini-cart sidebar, product tabs override, related products customization
- **Polskie etykiety/tłumaczenia** (POlish-specific kropy, godziny pracy, etc.)
- **Enqueue** GSAP, Lenis smooth-scroll, mobile-menu, slider-platnosci JS

## 6. Integracja z WooCommerce

**Override w `woocommerce/`:**
- `archive-product.php` — strona sklepu
- `single-product.php` + `single-product/` — strona produktu
- `content-product.php`, `content-single-product.php` — markup product cards
- `cart/` + `checkout/` + `emails/` — pełne template overrides

**SCSS `css/woocommerce/`:**
- `cart.scss`, `single-product.scss` — zrefaktoryzowane do tokens
- `shop.scss` — **rozbity na moduły** (2026-05-29): `_shop-layout`, `_shop-banner`, `_shop-section-title`, `_shop-product-card`, `_shop-badges`, `_shop-set-frames`, `_shop-legacy` (transitional)

**Kluczowe rozwiązania:**
- ✅ **Picker Wzorców (`wp_block`) per produkt** (2026-05-07, functions.php @ ~785-850) → metabox "Bloki Shav (wzorce)" w edycji produktu z checkbox-listą; user wybiera które Wzorce wyświetlić pod opisem. Wzorce edytowalne w pełnym Gutenbergu na osobnym ekranie (Narzędzia → Wzorce). Render: `woocommerce_after_single_product_summary` priorytet 6 → `do_blocks($pattern->post_content)` w `<div class="shav-product-pattern">`. **Filtr `use_block_editor_for_post_type` dla produktu jest WYŁĄCZONY** (rozbijał WC UI: cenę/magazyn/warianty).
- ✅ Custom mini-cart sidebar
- ✅ Custom tabs (`woo_custom_reviews_tab`)
- ✅ Custom WC fields w admin (subtitle produktu, alternative image, related products by slug)
- ✅ **Produkty wielowariantowe = 1:1 box produktu prostego** (2026-07-17, Figma 1292:300) → override `woocommerce/single-product/add-to-cart/variable.php`: swatche kolorów (`.shav-variations`, okrągłe 38px, obrazek z wariacji, wybrany z obwódką neutral-900) nad rzędem ilość+"Dodaj do koszyka". Natywna `table.variations` i wstrzykiwana cena wariantu ukryte w CSS — obsługuje je `wc-add-to-cart-variation`. JS: `inc/shav-variations.js` (klik w swatch → set select → trigger change; sync labela "Kolor – X" i głównej ceny przez `found_variation`/`reset_data`; podmiana pierwszego zdjęcia customowej galerii `.product-gallery` na `variation.image.full_src`). Obrazek swatcha priorytetem z term meta `product_attribute_image` (plugin woo-variation-swatches na produkcji), fallback: miniatura wariacji → tekstowy pill. **Zestawy (woosg)**: swatche wariantu składnika w boxie ceny (sekcja "Zestawy" w product-summary.scss; uwaga — form.variations_form siedzi WEWNĄTRZ .woosg-title) + zdjęcie zestawu per wariant: pola URL w Dane produktu → Zaawansowane (dynamiczne z `woosg_ids`), meta `_shav_woosg_variant_image_*`, mapa JSON w wp_footer, obsługa w shav-variations.js v1.1. **Aktywny theme na produkcji: `customtheme`** (nie ShavWoman).
- ✅ **Tab Opinie — restyle do tokens** (2026-07-17): wywalony niebieski (`$color-info-*`) — badge oceny `.rating-number` → neutral-900, submit → accent-medium radius 24, bordery inputów → neutral-300, `.woocommerce-review-link` → neutral-600. Karty/filtry pluginu **woo-photo-reviews** (`wcpr-*`, sekcja na końcu single-product.scss): filtry jako pille (aktywny neutral-900), karty neutral-100 radius 8, ukryte `::before` z dymkiem (font pluginu) i tabelka `.wcpr-overall-rating`. WAŻNE: `.commentlist` NIE może być grid 3-col — w środku siedzi masonry pluginu (`.wcpr-grid`), grid dusił karty do 146px; jest `display:block`.
- ✅ **Blok onaswazne — drugie logo** (2026-07-17): atrybut `leftLogoImage2`; z drugim logiem `.onaswazne__logo` dostaje modyfikator `--duo` (okrąg → karta radius 24/32, logo1 w białym kole 110/140, `.onaswazne__logo-second` 160×85/220×117 contain — spójnie z misjarakroll). Bez logo2 wygląd bez zmian.
- ✅ **Blok misjarakroll — drugie logo** (2026-07-17, Figma desktop 1258:162 / mobile 845:1123): nowy atrybut `logoImage2`, markup: `.misjarakroll__card` (radius 24/32, padding 32/64, gradient+shadow) z `.misjarakroll__logo` (koło 110/140, białe tło, Rak'n'Roll) + `.misjarakroll__logo2` (160×85 / 220×117, contain, Fundacja Pokonać Endometriozę). Blok użyty na prod: `/misja-spoleczna/`. Logo endometriozy ściągnięte z Figmy: `logo-fundacja-pokonac-endometrioze.png` w katalogu theme (do wgrania w media na prod). SCSS: sekcja w `css/modules/product-summary.scss` (border swatcha z `!important` — legacy `.summary button` zeruje border). `shav_render_savings_pill` naprawiony dla variable (min z wariacji zamiast `get_regular_price()`). Guardy ACF/`wc_get_product(68)` w `title.php`, `tabs/additional-information.php`, `display_custom_banner_with_timer` (na Localu wybuchały fatalem).

## 6b. Podstrona kolekcji / Drop (Rose Gold) — 6 reużywalnych bloków

**Data:** 2026-07-24. Figma desktop `1330:1860`, mobile `1281:3025`.

Zbudowano **6 generycznych bloków promocji/dropu** (reużywalne pod dowolną kolekcję — nie tylko Rose Gold), w pełni edytowalnych z Gutenberga:

| Blok (slug) | Tytuł w edytorze | Co robi |
|---|---|---|
| `rosegoldhero` | Drop — Hero z licznikiem | Tło + **wbudowany header** (reużywa klasy `.header`: logo+nav 4 sloty+koszyk) + badge + H1 + podtytuł + **live countdown** (JS, data w atrybucie `countdownDate`, puste = evergreen +3 dni) |
| `rosegoldfeatured` | Drop — Produkt wyróżniony | Karta `neutral-100` radius 82, obraz z **add-to-cart** + ceny (stara/nowa), nazwa 3-kolor |
| `rosegoldgrid` | Drop — Siatka produktów | Nagłówek + 4 karty (2×2 desktop / 1-col poziomo mobile). Każda: badge, obraz→produkt, add-to-cart, ceny, opcjonalny dolny badge |
| `rosegoldbanner` | Drop — Baner | Full-bleed obraz + nakładka (badge+H1+opis), pozycja treści L/C/R, opcjonalny link |
| `rosegoldzestaw` | Drop — Zestaw | Obraz + treść: badge, tytuł, lista, box cenowy (green badge + ceny w/poza zestawem), button |
| `rosegoldslider` | Drop — Slider banerów | Karuzela "peek" (aktywny wyśrodkowany, sąsiedzi opacity 0.4), dots + drag + autoscroll, 5 slotów |

**Automatyzacja WooCommerce** (helpery w `functions.php`): każda karta produktu ma pole **ID produktu**. Z niego:
- **klik w obraz** → `get_permalink()` (strona produktu),
- **klik w koszyk** → `shav_rosegold_add_to_cart()`: produkt prosty+kupowalny → AJAX `ajax_add_to_cart` (integruje się z mini-cartem, enqueue `wc-add-to-cart`); inny typ → link do produktu; brak ID → nieaktywny placeholder (układ 1:1 zachowany).
- Helpery: `shav_rosegold_link()`, `shav_rosegold_add_to_cart()`, `shav_rosegold_cart_icon_svg()`.

**SCSS:** jeden moduł `css/modules/rosegold.scss` (wszystkie 6 bloków, BEM, tokeny). Kilka ról typografii wzięto z Figmy dosłownie (Body M 16, H4 22, Subtitle M 16) bo różnią się od globalnej skali — opisane lokalnie. Import w `style.scss` w sekcji DS modules. Badge = peachowy gradient (`$rg-badge-gradient`), radiusy 82/64/34 lokalne.

**Obrazy-defaulty:** `images/rosegold/*` (hero-bg.jpg, featured.png, prod-1..4.png, banner.jpg, zestaw.png, slide-1..3.jpg) — ściągnięte z Figmy, zresize'owane. Referencja przez `get_template_directory_uri()`; puste pole obrazu w bloku → default z motywu.

**Szablon:** `templates/rosegold.html` (= `post-content` + footer part; **bez** globalnego headera, bo hero ma własny). Zarejestrowany w `theme.json` jako `customTemplates` "rosegold" + w liście `settings.general.custom.templates` ("Rose Gold / Drop"). User tworzy Stronę → wybiera szablon "Rose Gold / Drop" → wstawia bloki.

**Rejestracja:** 6× `register_block_type_from_metadata(__DIR__.'/build/rosegold*')` w `our_new_blocks()`.

---

## 7. Dokładny punkt zatrzymania

**Pracowaliśmy nad strukturą strony głównej**, sekcja po sekcji refactor 1:1 do designu z Figmy. Każda sekcja:
1. Pobranie designu przez MCP Figmy
2. Aktualizacja `block.json` z atrybutami
3. Przepisanie `edit.js` (RichText + MediaUpload + Inspector)
4. Przepisanie `render.php` zgodnie z markupem Figmy
5. Stworzenie modułu SCSS w `css/modules/<block>.scss` używającego wyłącznie tokenów z `_tokens.scss`
6. Podpięcie modułu w `css/style.scss`

**Ostatnio zakończone (w tej kolejności):**
1. `header` (Figma 104:62694) — logo + 4 sloty nawigacji + cart + hamburger; nowy moduł `.header` zastąpił legacy `site-header.scss`
2. `glownabaner` (135:63212) — banner z 3 slajdami slidera (zachowany inline `<style>` + JS)
3. `glownacechy` (170:63458) — 2-kolumnowy: tytuł "Maszynka Shav woman." + 4 features + zdjęcie
4. `glownagrid` (135:63111) — siatka 6 produktów (CSS Grid, układ 481px + flex right)
5. `glownaoceny` (152:63343) — 3 karty "Dlaczego warto wybrać" (rename z poprzedniego `glownaopinie`)
6. `glownaopinie` (182:63510) — **NOWY** slider opinii klientów z JS (max 8 slotów, prev/next buttons, auto-scroll)
7. `glownainstagram` (211:55) — restyling, core (Smash Balloon plugin shortcode) zachowany, dodany tytuł + sekcja profilu + peach buttons
8. **`faqglowna`** — przeprojektowane na klasę `.glownafaq` (jasny wariant, design tokens). Default `containerClass` zmieniony z `faq-container-glowna` na `glownafaq`. Inline JS dla accordion `.is-active`. Moduł `css/modules/faqglowna.scss` używający tokenów.

10. **Wzorzec `szachglass`** (2026-05-07, Figma 334:151) — 12 bloków (`szachshavwomen1-4`, `masazerwoman1-4`, `myjkaszach1-4`) zunifikowane wg jednego schematu: zdjęcie tła + szklana karta z tytułem i opisem. Każdy ma 8 atrybutów: `backgroundImage`, `title`, `description`, `titleSize` (default 42), `descriptionSize` (default 18), `glassPositionX` (left/center/right), `glassPositionY` (top/middle/bottom), `glassWidth` (default 726). Inspector ma 3 panele: Tło, Pozycja karty, Rozmiary tekstu. Markup: `<section class="szachglass szachglass--x-X szachglass--y-Y">` z inline-style `background-image` + `<div class="szachglass__card">` (`@include glass-effect`). Mobile: karta zawsze centrowana (czytelność). Moduł: `css/modules/szachglass.scss`. Defaultowe teksty zachowane z poprzednich wersji bloków.
\
9. **`footer`** ⬅️ **OSTATNIA SEKCJA** (Figma 193:63671) — pełny restyling 1:1 z designem. `block.json` ma 50 atrybutów (CTA card, 6+6 link slotów per kolumna, 4 social slots, copyright, logo, legal links, back-to-top toggle). Markup: `.footer > .footer__inner > .footer__top (CTA + 2×col + social) + .footer__separator + .footer__bottom (copyright + logo + legal + back-to-top)`. Zachowane domyślne SVG ikon social (Facebook/TikTok/Instagram/YouTube). Inline JS dla `.footer__back-to-top` smooth scroll. Legacy `css/modules/site-footer.scss` **wyłączony** w `style.scss` (// import). Nowy moduł `css/modules/footer.scss` używa wyłącznie tokenów (neutral-50/100/200/700/800/900, peach-600/700 dla back-to-top, $space-32/40/48/64/80/120, $radius-xs/s, $font-size-h1/h2/h3/h4/body-l/body-m).

**Stan kompilacji:** SCSS kompiluje się czysto (`npx sass` exit=0). Nowe moduły są w `build/style-index.css` po ręcznym `npm run buildIndex`. Webpack watcher (`npm start`) trzeba **zrestartować** po dodaniu nowego pliku SCSS — inaczej nie wykrywa nowych @import.

**Floating back-to-top (2026-05-11):** globalny przycisk w prawym dolnym rogu (`.back-to-top`), pojawia się po przescrollowaniu o ~40% viewportu (min 400px). Klik → `window.scrollTo({top:0, behavior:'smooth'})` (fallback do Lenis jeśli `window.lenis` dostępny). **Wstawiany przez hook `wp_footer`** w `functions.php` (funkcja `shav_render_back_to_top_button`) — bo theme to block-theme (FSE) i `footer.php` NIE jest renderowany na większości stron (templates/*.html ładują parts/footer.html). Moduł `css/modules/back-to-top.scss` używa tokenów ($color-primary-medium/dark, $shadow-card, $color-white). Inline `.footer__back-to-top` w bloku footer **wyłączony domyślnie** (`showBackToTop` default=false w block.json + render.php — src i build).

**`glownaopinie` — dots + drag (2026-05-11):** usunięte przyciski nawigacji (`.glownaopinie__nav--prev/--next`), zastąpione dotami pod sliderem (`.glownaopinie__dots > .glownaopinie__dot`) generowanymi w JS (liczba = `total - visible + 1`, active dot rozciąga się do 28px szerokości). Drag/swipe na track przez pointer events (`pointerdown/move/up/cancel`) z resistance na krańcach, threshold 20% szerokości karty, click-cancel po dragu. `touch-action: pan-y` żeby na mobile pionowy scroll strony nadal działał. SCSS: cursor:grab/grabbing przez `.is-dragging` na root.

**Header — szerokość 1:1 z sekcjami (2026-05-11):** `.header` w `utility-classes.scss` dostała `margin: 0 20px !important` w przedziale 768-1340px (żeby zrównać z `clamp(20px, 2vw, 32px)` na contencie sekcji) i `margin: 0 auto` powyżej 1340px. Dodane `box-sizing: border-box` żeby border+padding nie wypychał szerokości. Wcześniej header leciał od krawędzi do krawędzi viewportu między 768-1300px → wyglądał szerzej niż sekcje.

**Fix `custom-gsap.js` (2026-05-11):** dodane null-guards do wszystkich `document.querySelector(...).innerHTML/classList/...` — 14× bloki `.placeholder-video-*` (uproszczone do `if (el) el.innerHTML = ...` zamiast wewnętrznej funkcji DAppendVideo), `#top-menu` scroll-listener, `instagramFeed()` (`#sbi_images`, `.next/prev-instagram`), oraz blok `#backToTop` (relikt — owinięty `if (backToTopButton && footer)`). Console przy scrollu już nie zalewa errorów.

**Refactor `/sklep/` (2026-05-29):** archive-product przepisany 1:1 z Figmy (Sklep - desktop - v2 898:1585 + mobile 898:1813).
- `woocommerce/archive-product.php` — pętla po sekcjach z configu (Urządzenia/Zestawy/Akcesoria), każda renderuje grid kart.
- `woocommerce/content-product.php` — pełny BEM markup karty (bez do_action hooków), badge'y (NOWOŚĆ/BESTSELLER/RABAT), tytuł z brand-split (`Shav Woman` jasny), subtitle, cena old/new, OSZCZĘDZASZ pill.
- `inc/shop/sections-config.php` — filtr `shav_shop_sections` + WP option (Faza 2b doda UI). Default: 3 sekcje × kategorie produktu.
- `inc/shop/frame-variant.php` — meta-field `_shav_frame_variant` na produkcie WC (select w zakładce Ogólne): brak/zloty/srebrny/platynowy/duo/wojownik/handler. Klasa CSS `.product-card--frame-{slug}` na karcie.
- `_shop-set-frames.scss` — gradient top stripe 16px desktop / 8px mobile (NIE border dookoła jak było wcześniej).
- Stare hooki `display_custom_shop_image` i `display_product_title_and_subtitle_shop` w functions.php zakomentowane (markup renderowany jawnie w content-product.php).
- Usunięte z legacy: 6 bloków ramek per `.post-{ID}` + `.has-text-align-center { text-align: left !important }`.
- Otwarte: **Faza 2b — UI w adminie** (Wygląd → Strona sklepu, repeater dla sekcji).

**Otwarte issue (visual):**
- User zgłosił że `glownagrid` "wygląda średnio" — wrócimy do tego przy końcowych poprawkach
- Layout strony głównej w real preview może nie pasować 1:1 (cache przeglądarki, niezsynchronizowany webpack-watch)

## 8. Kolejne kroki (To-Do)

1. **Sprawdź czy webpack-watcher chodzi** (`npm start` w terminalu) — jeśli zatrzymany na błędzie, zrestartuj. Następnie **twardy reload** strony głównej (Cmd+Shift+R) i porównaj z Figmą sekcję po sekcji. Wszystkie sekcje main-page powinny być teraz spójnie ostylowane.

2. **Sprawdź `glownagrid` wizualnie** — user zgłosił że layout się rozjeżdża mimo CSS Grid (`css/modules/glownagrid.scss`). Możliwe przyczyny: cache, `useBlockProps()` dodający paddingi, kafelki bez `aspect-ratio` na mobile. Zdiagnozuj przez DevTools (`grid-template-columns` na `.glownagrid__grid`).

3. **Następna sekcja strony głównej** — po faqglowna zostały: `glownaikony` i `glownakup`. Pobierz Figma node IDs i przerob tym samym wzorcem (Figma → block.json → edit.js → render.php → SCSS module → import w style.scss).

4. **Zsynchronizować `theme.json` z `_tokens.scss`** — palette w theme.json ma starodawne 4 kolory (pizza/secondary/background/foreground). Przepisać na semantic palette z DS (`primary-medium`, `accent-medium`, `info-medium`, `neutral-*` itd.) plus `fontSizes` z `$font-size-*`. Po tym block-editor UI pokazuje user spójne kolory zamiast losowych.

5. **(Niski priorytet)** Inline `<style>` w 3 pozostałych blokach (`karieraikony`, `textkariera`, `karierazespol`) do przeniesienia do SCSS — patrz `memory/project_inline_css_in_blocks.md`.

---

## Quick reference

**Komendy:**
- `npm start` — webpack watch (dev). Restart po dodaniu nowego `@import` w SCSS.
- `npm run buildIndex` — jednorazowy build CSS (gdy watcher zwariuje).
- `npx sass --quiet css/style.scss /tmp/x.css` — szybki test kompilacji SCSS bez webpack.

**Wzorzec refactoru bloku** (do designu z Figmy):
1. `mcp__plugin_figma_figma__get_design_context` z node ID
2. Edit `src/<block>/block.json` (attributes z defaults z Figmy)
3. Edit `src/<block>/edit.js` (RichText + MediaUpload + Inspector panels)
4. Edit `src/<block>/render.php` (markup zgodny z Figmą, używa `$attributes`)
5. Create/edit `css/modules/<block>.scss` (tylko tokens z `_tokens.scss`)
6. Add `@import "modules/<block>";` do `css/style.scss`
7. Register `register_block_type_from_metadata(__DIR__ . '/build/<block>');` w `functions.php` (jeśli nowy blok)
8. Restart `npm start` jeśli to nowy plik SCSS

**Tokens kluczowe:**
- Kolory: `$color-peach-{50..900}`, `$color-neutral-{50..900}`, `$color-info-{light,medium,dark}`, `$color-accent-*`, `$color-success-*`, `$color-warning-*`
- Fonty: `$font-family-base` (Be Vietnam Pro), `$font-family-accent` (Dolce — kursywa)
- Wagi: `$font-weight-{light,regular,medium,semibold,bold,extrabold}`, `$font-weight-accent-{thin,regular,medium,bold,black}`
- Rozmiary: `$font-size-{h1,h2,h3,h4,subtitle-l,subtitle-s,body-l,body-m,body-s}` + `-mob` warianty
- Spacing: `$space-{0,4,8,12,16,24,32,40,48,56,64,80,120}`
- Radius: `$radius-{xs,s,m,l,xl}` (8/16/24/36/42)
- Mixins typografii: `@include type-h1`, `type-h2`, ..., `type-body-l`, `type-accent`
- Mixin glass: `@include glass-effect`, `@include glass-overlay`
- Breakpoint: `$breakpoint-tablet: 768px`

## 9. Adaptacja na rynek niemiecki (DE) - NOWY ETAP

**Główne założenia (od sierpnia 2026):**
1. **Osobna instalacja i domena**: Wersja niemiecka znajduje się na osobnej domenie. Nie używamy wtyczek typu Polylang/WPML. 
2. **Polskie domyślne teksty zostają**: Fallbacki tekstowe w plikach PHP (`render.php`) zostawiamy po polsku, ponieważ pełnią rolę instrukcji / placeholdera dla polskiego zespołu (wiedzą, jaki tekst tam wpisać w edytorze). Niemieckie treści będą wprowadzane bezpośrednio przez edytor Gutenberga i nadpiszą fallbacki z atrybutów.
3. **Czystość i optymalizacja**: Unikamy spaghetti code, redukujemy ciężar. W razie potrzeby czyścimy `functions.php` ze specyficznych dla Polski zaszłości.
4. **Dostępność (a11y) i SEO**: Małymi krokami audytujemy i optymalizujemy szablony oraz bloki (nagłówki semantyczne, tagi alt, role ARIA, poprawne znaczniki HTML5).
5. **Metodologia pracy**: Działamy systematycznie, małymi krokami na komendy użytkownika.
6. **Kompilacja (Build)**: Po wprowadzaniu zmian w plikach SCSS lub blokach w `src/`, ZAWSZE musimy pamiętać o uruchomieniu komendy `npm run build` (lub `npm start` podczas developmentu), aby zmiany trafiły do folderu `build/` i były widoczne na froncie.
7. **Zachowanie frontu wizualnego**: Pod żadnym pozorem nie zmieniamy obecnego wyglądu front-endu (layoutu, stylów, wielkości) bez wyraźnego polecenia. Nawet jeśli wprowadzamy nowe funkcjonalności (np. dodatkowe kolumny menu), muszą one idealnie wpasowywać się w istniejący design system i układy, zachowując responsywność 1:1 względem oryginału.
8. **Czystość `functions.php`**: Plik `functions.php` utrzymujemy w czystości i zgodnie z dobrymi praktykami. Większe logiki i skrypty ładujemy poprzez `require_once` z folderu `inc/` (i ewentualnych podfolderów). Unikamy dodawania setek linii bezpośrednio do głównego pliku.
