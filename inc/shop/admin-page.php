<?php
/**
 * Admin: Wyglad -> Strona sklepu
 * Repeater do konfiguracji sekcji /sklep/ (title, brand_label, kategoria, limit).
 * Zapis do wp_option 'shav_shop_sections' (czytane przez shav_get_shop_sections()).
 */

defined('ABSPATH') || exit;

// Rejestracja strony pod menu Wyglad
add_action('admin_menu', function () {
    add_theme_page(
        __('Strona sklepu', 'shav'),
        __('Strona sklepu', 'shav'),
        'manage_options',
        'shav-shop-page',
        'shav_shop_admin_render_page'
    );
});

// Rejestracja settingu z sanityzacja
add_action('admin_init', function () {
    register_setting('shav_shop_page_group', 'shav_shop_sections', [
        'type'              => 'array',
        'sanitize_callback' => 'shav_shop_sanitize_sections',
        'default'           => [],
    ]);
});

/**
 * Sanityzacja sekcji przed zapisem.
 */
function shav_shop_sanitize_sections($input): array
{
    if (!is_array($input)) {
        return [];
    }

    $clean = [];
    foreach ($input as $row) {
        if (!is_array($row)) {
            continue;
        }
        $category = isset($row['category']) ? sanitize_title($row['category']) : '';
        if ($category === '') {
            continue; // pusta sekcja = pomijamy
        }
        $clean[] = [
            'title'       => isset($row['title']) ? sanitize_text_field($row['title']) : '',
            'brand_label' => isset($row['brand_label']) ? sanitize_text_field($row['brand_label']) : '',
            'category'    => $category,
            'limit'       => isset($row['limit']) ? max(1, min(48, (int) $row['limit'])) : 12,
            'orderby'     => in_array($row['orderby'] ?? '', ['menu_order', 'date', 'title', 'rand'], true) ? $row['orderby'] : 'menu_order',
            'order'       => in_array($row['order'] ?? '', ['ASC', 'DESC'], true) ? $row['order'] : 'ASC',
        ];
    }
    return $clean;
}

/**
 * Render strony.
 */
function shav_shop_admin_render_page(): void
{
    if (!current_user_can('manage_options')) {
        return;
    }

    $sections = get_option('shav_shop_sections', []);
    if (!is_array($sections) || empty($sections)) {
        // Pierwsze wejscie — pokaz domyslny config
        $sections = [
            ['title' => 'Urządzenia.', 'brand_label' => 'Shav woman', 'category' => 'urzadzenia', 'limit' => 12, 'orderby' => 'menu_order', 'order' => 'ASC'],
            ['title' => 'Zestawy.',    'brand_label' => 'Kobiecy niezbędnik', 'category' => 'zestawy', 'limit' => 12, 'orderby' => 'menu_order', 'order' => 'ASC'],
            ['title' => 'Akcesoria.',  'brand_label' => 'Shav woman', 'category' => 'akcesoria', 'limit' => 12, 'orderby' => 'menu_order', 'order' => 'ASC'],
        ];
    }

    // Lista kategorii produktow dla dropdownow
    $categories = get_terms([
        'taxonomy'   => 'product_cat',
        'hide_empty' => false,
    ]);
    ?>
    <div class="wrap shav-shop-admin">
        <h1><?php esc_html_e('Strona sklepu', 'shav'); ?></h1>
        <p class="description">
            <?php esc_html_e('Konfiguracja sekcji wyswietlanych na /sklep/. Kazda sekcja to tytul + kategoria produktow.', 'shav'); ?>
        </p>

        <form method="post" action="options.php">
            <?php settings_fields('shav_shop_page_group'); ?>

            <table class="widefat shav-shop-sections">
                <thead>
                    <tr>
                        <th class="shav-shop-handle" style="width:30px;"></th>
                        <th><?php esc_html_e('Tytuł', 'shav'); ?></th>
                        <th><?php esc_html_e('Brand label', 'shav'); ?></th>
                        <th><?php esc_html_e('Kategoria', 'shav'); ?></th>
                        <th style="width:80px;"><?php esc_html_e('Limit', 'shav'); ?></th>
                        <th style="width:130px;"><?php esc_html_e('Sortowanie', 'shav'); ?></th>
                        <th style="width:80px;"><?php esc_html_e('Kolejność', 'shav'); ?></th>
                        <th style="width:80px;"></th>
                    </tr>
                </thead>
                <tbody id="shav-shop-sections-body">
                    <?php foreach ($sections as $i => $section) :
                        shav_shop_admin_row($i, $section, $categories);
                    endforeach; ?>
                </tbody>
            </table>

            <p style="margin-top:12px;">
                <button type="button" class="button button-secondary" id="shav-shop-add-row">
                    + <?php esc_html_e('Dodaj sekcję', 'shav'); ?>
                </button>
            </p>

            <?php submit_button(__('Zapisz konfigurację', 'shav')); ?>
        </form>

        <!-- Wzorzec wiersza dla JS (template) -->
        <template id="shav-shop-row-template">
            <?php shav_shop_admin_row('__INDEX__', [
                'title' => '', 'brand_label' => '', 'category' => '', 'limit' => 12, 'orderby' => 'menu_order', 'order' => 'ASC',
            ], $categories); ?>
        </template>
    </div>

    <style>
        .shav-shop-sections { margin-top: 16px; }
        .shav-shop-sections th, .shav-shop-sections td { padding: 10px 12px; vertical-align: middle; }
        .shav-shop-sections .shav-shop-handle { cursor: move; text-align: center; color: #999; user-select: none; font-size: 18px; }
        .shav-shop-sections input[type="text"], .shav-shop-sections input[type="number"], .shav-shop-sections select { width: 100%; }
        .shav-shop-sections tr.is-dragging { opacity: 0.4; }
        .shav-shop-sections tr.is-over { border-top: 2px solid #2271b1; }
        .shav-shop-remove { color: #b32d2e; cursor: pointer; }
    </style>

    <script>
    (function() {
        const tbody = document.getElementById('shav-shop-sections-body');
        const tpl   = document.getElementById('shav-shop-row-template');
        const addBtn = document.getElementById('shav-shop-add-row');

        function reindex() {
            [...tbody.querySelectorAll('tr')].forEach((tr, idx) => {
                tr.querySelectorAll('[name^="shav_shop_sections["]').forEach(el => {
                    el.name = el.name.replace(/shav_shop_sections\[\d+|__INDEX__\]/, `shav_shop_sections[${idx}`);
                });
            });
        }

        addBtn.addEventListener('click', () => {
            const html = tpl.innerHTML.replace(/__INDEX__/g, tbody.children.length);
            tbody.insertAdjacentHTML('beforeend', html);
            reindex();
        });

        tbody.addEventListener('click', (e) => {
            if (e.target.matches('.shav-shop-remove')) {
                e.target.closest('tr').remove();
                reindex();
            }
        });

        // Drag & drop wierszy
        let dragSrc = null;
        tbody.addEventListener('dragstart', (e) => {
            const tr = e.target.closest('tr');
            if (!tr) return;
            dragSrc = tr;
            tr.classList.add('is-dragging');
            e.dataTransfer.effectAllowed = 'move';
        });
        tbody.addEventListener('dragend', (e) => {
            const tr = e.target.closest('tr');
            if (tr) tr.classList.remove('is-dragging');
            tbody.querySelectorAll('.is-over').forEach(el => el.classList.remove('is-over'));
        });
        tbody.addEventListener('dragover', (e) => {
            e.preventDefault();
            const tr = e.target.closest('tr');
            if (!tr || tr === dragSrc) return;
            tbody.querySelectorAll('.is-over').forEach(el => el.classList.remove('is-over'));
            tr.classList.add('is-over');
        });
        tbody.addEventListener('drop', (e) => {
            e.preventDefault();
            const tr = e.target.closest('tr');
            if (!tr || !dragSrc || tr === dragSrc) return;
            const rect = tr.getBoundingClientRect();
            const after = (e.clientY - rect.top) > rect.height / 2;
            tr.parentNode.insertBefore(dragSrc, after ? tr.nextSibling : tr);
            reindex();
        });
    })();
    </script>
    <?php
}

/**
 * Render pojedynczego wiersza tabeli.
 */
function shav_shop_admin_row($index, array $section, array $categories): void
{
    $name = 'shav_shop_sections[' . esc_attr($index) . ']';
    ?>
    <tr draggable="true">
        <td class="shav-shop-handle" aria-label="<?php esc_attr_e('Przeciągnij aby zmienić kolejność', 'shav'); ?>">⋮⋮</td>
        <td>
            <input type="text" name="<?php echo $name; ?>[title]" value="<?php echo esc_attr($section['title'] ?? ''); ?>" placeholder="Urządzenia." />
        </td>
        <td>
            <input type="text" name="<?php echo $name; ?>[brand_label]" value="<?php echo esc_attr($section['brand_label'] ?? ''); ?>" placeholder="Shav woman" />
        </td>
        <td>
            <select name="<?php echo $name; ?>[category]">
                <option value=""><?php esc_html_e('— wybierz —', 'shav'); ?></option>
                <?php foreach ($categories as $cat) : ?>
                    <option value="<?php echo esc_attr($cat->slug); ?>" <?php selected($section['category'] ?? '', $cat->slug); ?>>
                        <?php echo esc_html($cat->name); ?> (<?php echo (int) $cat->count; ?>)
                    </option>
                <?php endforeach; ?>
                <?php
                // Jezeli zapisana kategoria nie istnieje (np. typo lub usunieta), pokaz ja jako placeholder
                $current = $section['category'] ?? '';
                $exists = false;
                foreach ($categories as $cat) { if ($cat->slug === $current) { $exists = true; break; } }
                if ($current && !$exists) : ?>
                    <option value="<?php echo esc_attr($current); ?>" selected>
                        <?php echo esc_html($current); ?> (nieistniejąca)
                    </option>
                <?php endif; ?>
            </select>
        </td>
        <td>
            <input type="number" name="<?php echo $name; ?>[limit]" value="<?php echo esc_attr($section['limit'] ?? 12); ?>" min="1" max="48" />
        </td>
        <td>
            <select name="<?php echo $name; ?>[orderby]">
                <?php
                $orderby_opts = [
                    'menu_order' => __('Wg kolejności', 'shav'),
                    'date'       => __('Data', 'shav'),
                    'title'      => __('Alfabetycznie', 'shav'),
                    'rand'       => __('Losowo', 'shav'),
                ];
                foreach ($orderby_opts as $val => $label) : ?>
                    <option value="<?php echo esc_attr($val); ?>" <?php selected($section['orderby'] ?? 'menu_order', $val); ?>>
                        <?php echo esc_html($label); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </td>
        <td>
            <select name="<?php echo $name; ?>[order]">
                <option value="ASC" <?php selected($section['order'] ?? 'ASC', 'ASC'); ?>>ASC</option>
                <option value="DESC" <?php selected($section['order'] ?? 'ASC', 'DESC'); ?>>DESC</option>
            </select>
        </td>
        <td>
            <a class="shav-shop-remove" role="button"><?php esc_html_e('Usuń', 'shav'); ?></a>
        </td>
    </tr>
    <?php
}
