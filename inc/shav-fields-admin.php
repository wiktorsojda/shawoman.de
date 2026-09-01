<?php
/**
 * Shav Fields Admin — centralny edytor pol produktow
 *
 * Macierz: produkty (wiersze) x grupy pol (kolumny). Klik w komorke -> modal z
 * edytorem wszystkich pol grupy + checkbox "Dziedzicz z master produktu".
 *
 * Master product ID = option `shav_master_product_id` (default 68).
 *
 * Per produkt + per grupa pol: meta `_shav_inherit_{group}` = 'yes'/'no'.
 * Helper `shav_get_field($product_id, $key, $group)` zwraca wartosc respektujac inherit.
 *
 * @package ShavWoman
 */

defined('ABSPATH') || exit;

// =============================================================================
// 1. REGISTRY pol — definicje wszystkich grup z polami
// =============================================================================
function shav_fields_registry()
{
    return [
        'subtitle' => [
            'label'  => 'Podtytuł produktu',
            'fields' => [
                ['key' => 'product_subtitle', 'label' => 'Podtytuł', 'type' => 'text'],
            ],
        ],
        'title_accent' => [
            'label'  => 'Akcent Dolce w tytule',
            'fields' => [
                ['key' => '_title_accent_last_word', 'label' => 'Wyróżnij wybrane słowo w tytule (zaznacz, by aktywować)', 'type' => 'checkbox'],
                ['key' => '_title_accent_custom_word', 'label' => 'Słowo do wyróżnienia (puste = domyślnie "Woman" lub ostatnie słowo)', 'type' => 'text'],
            ],
        ],
        'rating' => [
            'label'  => 'Rating pill (avatary + ocena)',
            'fields' => [
                ['key' => '_rating_avatar1', 'label' => 'Avatar 1 URL', 'type' => 'text'],
                ['key' => '_rating_avatar2', 'label' => 'Avatar 2 URL', 'type' => 'text'],
                ['key' => '_rating_avatar3', 'label' => 'Avatar 3 URL', 'type' => 'text'],
                ['key' => '_rating_avatar4', 'label' => 'Avatar 4 URL', 'type' => 'text'],
                ['key' => '_rating_avatar5', 'label' => 'Avatar 5 URL', 'type' => 'text'],
                ['key' => '_rating_count_label', 'label' => 'Liczba klientów', 'type' => 'text'],
                ['key' => '_rating_score_label', 'label' => 'Ocena (np. 4.9)', 'type' => 'text'],
            ],
        ],
        'info_pills' => [
            'label'  => 'Info pille (Dostawa / Rabat)',
            'fields' => [
                ['key' => '_info_pill_dark', 'label' => 'Pill ciemny', 'type' => 'text'],
                ['key' => '_info_pill_red',  'label' => 'Pill czerwony', 'type' => 'text'],
            ],
        ],
        'badges' => [
            'label'  => 'Badges (Nowość / -25%)',
            'fields' => [
                ['key' => 'new_promo_text',          'label' => 'Tekst "Nowość"', 'type' => 'text'],
                ['key' => 'promo_percentage_text',   'label' => 'Tekst "-25%"',    'type' => 'text'],
            ],
        ],
        'savings_pill' => [
            'label'  => 'Pill OSZCZĘDZASZ',
            'description' => 'Zielony pill obok ceny. Domyślnie auto-liczony z (regular - sale). Można nadpisać tekst.',
            'fields' => [
                ['key' => 'savings_pill_custom_text', 'label' => 'Tekst niestandardowy (puste = auto)', 'type' => 'text'],
            ],
        ],
        'kobiety' => [
            'label'  => 'Jesteśmy po stronie kobiet',
            'fields' => [
                ['key' => '_kobiety_title',           'label' => 'Tytuł sekcji', 'type' => 'text'],
                ['key' => '_kobiety_box1_text',       'label' => 'Box 1 — tekst', 'type' => 'textarea'],
                ['key' => '_kobiety_box1_link_text',  'label' => 'Box 1 — link tekst', 'type' => 'text'],
                ['key' => '_kobiety_box1_link_url',   'label' => 'Box 1 — link URL', 'type' => 'text'],
                ['key' => '_kobiety_box2_text',       'label' => 'Box 2 — tekst', 'type' => 'textarea'],
                ['key' => '_kobiety_box2_link_text',  'label' => 'Box 2 — link tekst', 'type' => 'text'],
                ['key' => '_kobiety_box2_link_url',   'label' => 'Box 2 — link URL', 'type' => 'text'],
            ],
        ],



        'lowest_price' => [
            'label'  => 'Najniższa cena z 30 dni',
            'fields' => [
                ['key' => 'lowest_price_30_days', 'label' => 'Najniższa cena (zł)', 'type' => 'text'],
            ],
        ],
        'shop_banner' => [
            'label'       => 'Banner sklepu (archiwum)',
            'master_only' => true,
            'description' => 'Ustaw na master produkcie — wyświetla się globalnie na archiwum sklepu.',
            'fields' => [
                ['key' => 'shop_page_banner_image_desktop', 'label' => 'URL Desktop', 'type' => 'text'],
                ['key' => 'shop_page_banner_image_mobile',  'label' => 'URL Mobile',  'type' => 'text'],
            ],
        ],
        'cart_banner' => [
            'label'  => 'Banner reklamowy (banner w koszyku)',
            'fields' => [
                ['key' => 'custom_banner_image_konkurs', 'label' => 'URL bannera konkursu', 'type' => 'text'],
                ['key' => 'cart_banner_desktop_image',   'label' => 'Cart banner Desktop URL', 'type' => 'text'],
                ['key' => 'cart_banner_mobile_image',    'label' => 'Cart banner Mobile URL', 'type' => 'text'],
                ['key' => 'custom_image_url',            'label' => 'Custom Image URL (sekcja w koszyku)', 'type' => 'text'],
            ],
        ],
        'product_top_banner' => [
            'label'  => 'Banner na górze strony produktu',
            'fields' => [
                ['key' => 'custom_banner_url',         'label' => 'URL Desktop', 'type' => 'text'],
                ['key' => 'custom_mobile_banner_url',  'label' => 'URL Mobile',  'type' => 'text'],
            ],
        ],
        'countdown_timer' => [
            'label'  => 'Licznik czasu (countdown)',
            'fields' => [
                ['key' => 'countdown_timer_end_date', 'label' => 'Data końcowa (YYYY-MM-DDTHH:MM)', 'type' => 'text'],
            ],
        ],
    ];
}

// =============================================================================
// 2. HELPER — pobierz wartosc pola respektujac inherit
// =============================================================================
function shav_get_field($product_id, $field_key, $group_key = '')
{
    $master_id = (int) get_option('shav_master_product_id', 68);

    // Auto-detekcja grupy jesli nie podana
    if (empty($group_key)) {
        foreach (shav_fields_registry() as $gk => $g) {
            foreach ($g['fields'] as $f) {
                if ($f['key'] === $field_key) { $group_key = $gk; break 2; }
            }
        }
    }

    $product = wc_get_product($product_id);
    if (!$product) return '';

    // Jesli to master produkt — zawsze wlasna wartosc
    if ((int) $product_id === $master_id) {
        return $product->get_meta($field_key);
    }

    $inherit = $product->get_meta("_shav_inherit_{$group_key}");
    if ($inherit === 'yes') {
        $master = wc_get_product($master_id);
        return $master ? $master->get_meta($field_key) : '';
    }

    return $product->get_meta($field_key);
}

// Czy grupa pol jest "ukryta" na danym produkcie (pole _shav_hidden_{group})
// Dane w bazie pozostaja — sluzy do tymczasowego ukrycia bez kasowania tresci.
function shav_is_hidden($product_id, $group_key)
{
    $product = wc_get_product($product_id);
    if (!$product) return false;
    return $product->get_meta("_shav_hidden_{$group_key}") === 'yes';
}

// =============================================================================
// 3. ADMIN MENU + Page
// =============================================================================
function shav_fields_register_menu()
{
    add_menu_page(
        'Shav: Pola produktów',
        'Shav: Pola',
        'manage_woocommerce',
        'shav-fields',
        'shav_fields_render_admin_page',
        'dashicons-grid-view',
        58
    );
    add_submenu_page('shav-fields', 'Ustawienia', 'Ustawienia', 'manage_woocommerce', 'shav-fields-settings', 'shav_fields_render_settings_page');
}
add_action('admin_menu', 'shav_fields_register_menu');

function shav_fields_register_settings()
{
    register_setting('shav_fields_settings_group', 'shav_master_product_id');
}
add_action('admin_init', 'shav_fields_register_settings');

function shav_fields_render_settings_page()
{
    ?>
    <div class="wrap">
        <h1>Shav: Pola — ustawienia</h1>
        <form method="post" action="options.php">
            <?php settings_fields('shav_fields_settings_group'); ?>
            <table class="form-table">
                <tr>
                    <th>ID produktu master (domyślnie 68 — Golarka Shav Woman)</th>
                    <td><input type="number" name="shav_master_product_id" value="<?php echo esc_attr(get_option('shav_master_product_id', 68)); ?>" class="regular-text"></td>
                </tr>
            </table>
            <?php submit_button('Zapisz'); ?>
        </form>
    </div>
    <?php
}

function shav_fields_render_admin_page()
{
    $registry  = shav_fields_registry();
    $master_id = (int) get_option('shav_master_product_id', 68);

    // Paginacja + search
    $per_page = 30;
    $paged    = max(1, isset($_GET['paged']) ? (int) $_GET['paged'] : 1);
    $search   = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';

    $args = [
        'status'   => ['publish', 'draft'],
        'limit'    => $per_page,
        'page'     => $paged,
        'paginate' => true,
    ];
    if (!empty($search)) {
        $args['s'] = $search;
    }
    $result = wc_get_products($args);
    $products    = $result->products;
    $total_pages = $result->max_num_pages;
    ?>
    <div class="wrap shav-fields-admin">
        <h1>Shav: Pola produktów</h1>
        <p>Master produkt: <strong>#<?php echo (int) $master_id; ?></strong>
        (<a href="<?php echo esc_url(admin_url('admin.php?page=shav-fields-settings')); ?>">zmień</a>).
        Klik w komórkę otwiera edytor pól dla danego produktu i grupy.</p>

        <form method="get" style="margin: 16px 0;">
            <input type="hidden" name="page" value="shav-fields">
            <input type="search" name="s" value="<?php echo esc_attr($search); ?>" placeholder="Szukaj produktu…" class="regular-text">
            <?php submit_button('Szukaj', 'secondary', '', false); ?>
        </form>

        <!-- Bulk toolbar -->
        <div id="shav-bulk-bar" style="display:none; position:sticky; top:32px; z-index:10; padding:10px 14px; background:#2271b1; color:#fff; border-radius:4px; margin-bottom:8px; align-items:center; gap:12px;">
            <strong>Zaznaczono: <span id="shav-bulk-count">0</span></strong>
            <button type="button" class="button button-primary" id="shav-bulk-inherit-on">↪ Dziedzicz z master</button>
            <button type="button" class="button" id="shav-bulk-inherit-off">✕ Wyłącz dziedziczenie</button>
            <button type="button" class="button" id="shav-bulk-clear">Odznacz wszystko</button>
        </div>

        <div class="shav-fields-table-wrap" style="overflow-x:auto; border:1px solid #ddd; background:#fff;">
            <table class="widefat shav-fields-table" style="border-collapse:separate; min-width:1200px;">
                <thead>
                    <tr>
                        <th style="position:sticky; left:0; background:#f9f9f9; z-index:2; min-width:240px;">
                            <label><input type="checkbox" id="shav-select-all"> Produkt</label>
                        </th>
                        <?php foreach ($registry as $gk => $g): ?>
                            <th style="min-width:160px; text-align:center;">
                                <?php echo esc_html($g['label']); ?><br>
                                <?php if (empty($g['master_only'])): ?>
                                    <label style="font-weight:normal; font-size:11px;">
                                        <input type="checkbox" class="shav-select-col" data-group="<?php echo esc_attr($gk); ?>"> kolumna
                                    </label>
                                <?php endif; ?>
                            </th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product):
                        $pid = $product->get_id();
                        $is_master = $pid === $master_id;
                        ?>
                        <tr>
                            <td style="position:sticky; left:0; background:#fff; z-index:1; border-right:1px solid #ddd;">
                                <label style="display:flex; gap:8px; align-items:flex-start;">
                                    <input type="checkbox" class="shav-select-row" data-product-id="<?php echo (int) $pid; ?>" <?php echo $is_master ? 'disabled title="Master nie może dziedziczyć"' : ''; ?>>
                                    <span>
                                        <strong>#<?php echo (int) $pid; ?>
                                        <?php if ($is_master): ?><span style="color:#c68e7a;">★ MASTER</span><?php endif; ?></strong><br>
                                        <a href="<?php echo esc_url(get_edit_post_link($pid)); ?>" target="_blank"><?php echo esc_html($product->get_name()); ?></a>
                                    </span>
                                </label>
                            </td>
                            <?php foreach ($registry as $gk => $g):
                                $is_master_only = !empty($g['master_only']);
                                $inherit = $product->get_meta("_shav_inherit_{$gk}") === 'yes';
                                $hidden  = $product->get_meta("_shav_hidden_{$gk}") === 'yes';
                                // Wskaznik stanu komorki
                                $has_data = false;
                                foreach ($g['fields'] as $f) {
                                    $v = $product->get_meta($f['key']);
                                    if (!empty($v)) { $has_data = true; break; }
                                }
                                $chip_bg = '#f1f1f1'; $chip_label = 'puste'; $disabled = false;
                                if ($is_master_only && !$is_master) {
                                    $chip_bg = '#f8f8f8';
                                    $chip_label = '★ tylko master';
                                    $disabled = true;
                                } elseif ($hidden) {
                                    $chip_bg = '#fde7e7';
                                    $chip_label = '⊘ ukryte';
                                } elseif ($is_master) {
                                    $chip_bg = $has_data ? '#fdf3ee' : '#f1f1f1';
                                    $chip_label = $has_data ? 'wypełnione' : 'puste';
                                } elseif ($inherit) {
                                    $chip_bg = '#e8f0fe';
                                    $chip_label = '↪ master';
                                } elseif ($has_data) {
                                    $chip_bg = '#e8f8e8';
                                    $chip_label = 'własne';
                                }
                                ?>
                                <td style="text-align:center; vertical-align:middle; border-right:1px solid #f0f0f0;">
                                    <?php if (!$disabled && !$is_master): ?>
                                        <input type="checkbox" class="shav-cell-cb"
                                            data-product-id="<?php echo (int) $pid; ?>"
                                            data-group="<?php echo esc_attr($gk); ?>"
                                            <?php checked($inherit); ?>
                                            style="margin-right:4px;">
                                    <?php endif; ?>
                                    <button type="button" class="button shav-edit-cell"
                                        data-product-id="<?php echo (int) $pid; ?>"
                                        data-group="<?php echo esc_attr($gk); ?>"
                                        <?php echo $disabled ? 'disabled' : ''; ?>
                                        style="background:<?php echo esc_attr($chip_bg); ?>; border-color:#ccc; <?php echo $disabled ? 'opacity:0.6;cursor:not-allowed;' : ''; ?>">
                                        <?php echo esc_html($chip_label); ?>
                                    </button>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php if ($total_pages > 1):
            $base_url = remove_query_arg('paged');
            ?>
            <div style="margin-top:16px;">
                <?php for ($p = 1; $p <= $total_pages; $p++): ?>
                    <a href="<?php echo esc_url(add_query_arg('paged', $p, $base_url)); ?>"
                        class="button <?php echo $p === $paged ? 'button-primary' : ''; ?>"
                        style="margin:2px;"><?php echo (int) $p; ?></a>
                <?php endfor; ?>
            </div>
        <?php endif; ?>

        <!-- Modal -->
        <div id="shav-edit-modal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:99999; align-items:center; justify-content:center;">
            <div class="shav-modal-box" style="background:#fff; width:90%; max-width:720px; max-height:90vh; overflow-y:auto; padding:24px; border-radius:8px;">
                <h2 id="shav-modal-title" style="margin-top:0;">Edytuj</h2>
                <div id="shav-modal-inherit-row" style="margin-bottom:16px; padding:12px; background:#f6f7f7; border-radius:4px;"></div>
                <div id="shav-modal-fields"></div>
                <div style="display:flex; gap:8px; margin-top:16px; justify-content:flex-end;">
                    <button type="button" class="button" id="shav-modal-cancel">Anuluj</button>
                    <button type="button" class="button button-primary" id="shav-modal-save">Zapisz</button>
                </div>
            </div>
        </div>

        <script>
        (function(){
            const REGISTRY = <?php echo wp_json_encode($registry); ?>;
            const AJAX_URL = <?php echo wp_json_encode(admin_url('admin-ajax.php')); ?>;
            const NONCE    = <?php echo wp_json_encode(wp_create_nonce('shav_fields_nonce')); ?>;
            const MASTER_ID = <?php echo (int) $master_id; ?>;

            const modal = document.getElementById('shav-edit-modal');
            const titleEl = document.getElementById('shav-modal-title');
            const inheritRow = document.getElementById('shav-modal-inherit-row');
            const fieldsEl = document.getElementById('shav-modal-fields');
            let currentPid = 0, currentGroup = '';

            function escAttr(s) { return String(s == null ? '' : s).replace(/&/g,'&amp;').replace(/"/g,'&quot;').replace(/</g,'&lt;'); }
            function escHtml(s) { return escAttr(s); }

            function closeModal() { modal.style.display = 'none'; }

            function openModal(pid, group) {
                currentPid = pid; currentGroup = group;
                const def = REGISTRY[group];
                if (!def) return;
                titleEl.textContent = 'Edytuj: ' + def.label + ' (produkt #' + pid + ')';

                // Pobierz wartosci AJAX
                const fd = new FormData();
                fd.append('action', 'shav_fields_get');
                fd.append('nonce', NONCE);
                fd.append('product_id', pid);
                fd.append('group', group);

                fetch(AJAX_URL, { method: 'POST', body: fd })
                    .then(r => r.json())
                    .then(json => {
                        if (!json.success) { alert('Błąd: ' + (json.data || '')); return; }
                        const data = json.data;
                        renderModal(def, data);
                        modal.style.display = 'flex';
                    });
            }

            function renderModal(def, data) {
                const isMaster = currentPid === MASTER_ID;
                const isMasterOnly = !!def.master_only;
                let row = '';
                if (def.description) {
                    row += '<div style="margin-bottom:8px; color:#555; font-style:italic;">' + escHtml(def.description) + '</div>';
                }
                // Toggle ukrycia — zawsze widoczny (oprocz master-only na nie-masterze)
                if (!(isMasterOnly && !isMaster)) {
                    row += '<label style="display:block; margin-bottom:6px;"><input type="checkbox" id="shav-hidden-cb" ' + (data.hidden ? 'checked' : '') + '> <strong>⊘ Ukryj tę sekcję na tym produkcie</strong> (dane w bazie pozostają)</label>';
                }
                if (isMasterOnly) {
                    row += '<em>Grupa <strong>tylko master</strong> — wartość globalna, edytowalna wyłącznie na produkcie #' + MASTER_ID + '.</em>';
                } else if (isMaster) {
                    row += '<em>To produkt master — nie może dziedziczyć.</em>';
                } else {
                    row += '<label><input type="checkbox" id="shav-inherit-cb" ' + (data.inherit ? 'checked' : '') + '> Dziedzicz z master (#' + MASTER_ID + ')</label>';
                }
                inheritRow.innerHTML = row;

                fieldsEl.innerHTML = '';
                def.fields.forEach(f => {
                    const v = data.values[f.key] != null ? data.values[f.key] : '';
                    let input = '';
                    if (f.type === 'textarea') {
                        input = '<textarea name="' + escAttr(f.key) + '" rows="3" style="width:100%;">' + escHtml(v) + '</textarea>';
                    } else if (f.type === 'checkbox') {
                        input = '<input type="checkbox" name="' + escAttr(f.key) + '" value="yes" ' + (v === 'yes' ? 'checked' : '') + '>';
                    } else if (f.type === 'number') {
                        input = '<input type="number" name="' + escAttr(f.key) + '" value="' + escAttr(v) + '" style="width:100%;">';
                    } else {
                        input = '<input type="text" name="' + escAttr(f.key) + '" value="' + escAttr(v) + '" style="width:100%;">';
                    }
                    fieldsEl.insertAdjacentHTML('beforeend',
                        '<div style="margin-bottom:12px;"><label style="display:block; font-weight:600; margin-bottom:4px;">' + escHtml(f.label) + '</label>' + input + '</div>'
                    );
                });
            }

            function save() {
                const def = REGISTRY[currentGroup];
                const fd = new FormData();
                fd.append('action', 'shav_fields_save');
                fd.append('nonce', NONCE);
                fd.append('product_id', currentPid);
                fd.append('group', currentGroup);
                const inheritCb = document.getElementById('shav-inherit-cb');
                fd.append('inherit', inheritCb && inheritCb.checked ? 'yes' : 'no');
                const hiddenCb = document.getElementById('shav-hidden-cb');
                fd.append('hidden', hiddenCb && hiddenCb.checked ? 'yes' : 'no');

                def.fields.forEach(f => {
                    const el = fieldsEl.querySelector('[name="' + f.key + '"]');
                    if (!el) return;
                    if (f.type === 'checkbox') {
                        fd.append('field_' + f.key, el.checked ? 'yes' : '');
                    } else {
                        fd.append('field_' + f.key, el.value);
                    }
                });

                fetch(AJAX_URL, { method: 'POST', body: fd })
                    .then(r => r.json())
                    .then(json => {
                        if (!json.success) { alert('Błąd zapisu: ' + (json.data || '')); return; }
                        location.reload();
                    });
            }

            document.querySelectorAll('.shav-edit-cell').forEach(btn => {
                btn.addEventListener('click', () => openModal(parseInt(btn.dataset.productId), btn.dataset.group));
            });
            document.getElementById('shav-modal-cancel').addEventListener('click', closeModal);
            document.getElementById('shav-modal-save').addEventListener('click', save);
            modal.addEventListener('click', e => { if (e.target === modal) closeModal(); });

            // ===== Bulk inherit =====
            const bulkBar = document.getElementById('shav-bulk-bar');
            const bulkCount = document.getElementById('shav-bulk-count');

            function getCheckedCells() {
                return Array.from(document.querySelectorAll('.shav-cell-cb:checked'));
            }

            function refreshBulkBar() {
                const checked = getCheckedCells();
                bulkCount.textContent = checked.length;
                bulkBar.style.display = checked.length > 0 ? 'flex' : 'none';
            }

            document.querySelectorAll('.shav-cell-cb').forEach(cb => {
                cb.addEventListener('change', refreshBulkBar);
            });

            // Select all w wierszu (toggle wszystkich komorek danego produktu)
            document.querySelectorAll('.shav-select-row').forEach(rowCb => {
                rowCb.addEventListener('change', () => {
                    const pid = rowCb.dataset.productId;
                    document.querySelectorAll('.shav-cell-cb[data-product-id="' + pid + '"]').forEach(c => {
                        c.checked = rowCb.checked;
                    });
                    refreshBulkBar();
                });
            });

            // Select all w kolumnie (toggle wszystkich komorek danej grupy)
            document.querySelectorAll('.shav-select-col').forEach(colCb => {
                colCb.addEventListener('change', () => {
                    const gk = colCb.dataset.group;
                    document.querySelectorAll('.shav-cell-cb[data-group="' + gk + '"]').forEach(c => {
                        c.checked = colCb.checked;
                    });
                    refreshBulkBar();
                });
            });

            // Master select-all
            const selectAll = document.getElementById('shav-select-all');
            if (selectAll) selectAll.addEventListener('change', () => {
                document.querySelectorAll('.shav-cell-cb').forEach(c => { c.checked = selectAll.checked; });
                refreshBulkBar();
            });

            document.getElementById('shav-bulk-clear').addEventListener('click', () => {
                document.querySelectorAll('.shav-cell-cb, .shav-select-row, .shav-select-col, #shav-select-all').forEach(c => { c.checked = false; });
                refreshBulkBar();
            });

            function bulkSetInherit(value) {
                const items = getCheckedCells().map(cb => ({ product_id: cb.dataset.productId, group: cb.dataset.group }));
                if (items.length === 0) return;
                const fd = new FormData();
                fd.append('action', 'shav_fields_bulk_inherit');
                fd.append('nonce', NONCE);
                fd.append('inherit', value);
                fd.append('items', JSON.stringify(items));
                fetch(AJAX_URL, { method: 'POST', body: fd })
                    .then(r => r.json())
                    .then(json => {
                        if (!json.success) { alert('Błąd: ' + (json.data || '')); return; }
                        location.reload();
                    });
            }

            document.getElementById('shav-bulk-inherit-on').addEventListener('click', () => bulkSetInherit('yes'));
            document.getElementById('shav-bulk-inherit-off').addEventListener('click', () => bulkSetInherit('no'));
        })();
        </script>
    </div>
    <?php
}

// =============================================================================
// 4. AJAX — get + save
// =============================================================================
function shav_fields_ajax_get()
{
    check_ajax_referer('shav_fields_nonce', 'nonce');
    if (!current_user_can('manage_woocommerce')) wp_send_json_error('Brak uprawnień');

    $product_id = (int) ($_POST['product_id'] ?? 0);
    $group      = sanitize_text_field($_POST['group'] ?? '');

    $registry = shav_fields_registry();
    if (!isset($registry[$group])) wp_send_json_error('Nieznana grupa');

    $product = wc_get_product($product_id);
    if (!$product) wp_send_json_error('Brak produktu');

    $values  = [];
    foreach ($registry[$group]['fields'] as $f) {
        $values[$f['key']] = $product->get_meta($f['key']);
    }
    $inherit = $product->get_meta("_shav_inherit_{$group}") === 'yes';
    $hidden  = $product->get_meta("_shav_hidden_{$group}") === 'yes';

    wp_send_json_success(['values' => $values, 'inherit' => $inherit, 'hidden' => $hidden]);
}
add_action('wp_ajax_shav_fields_get', 'shav_fields_ajax_get');

function shav_fields_ajax_save()
{
    check_ajax_referer('shav_fields_nonce', 'nonce');
    if (!current_user_can('manage_woocommerce')) wp_send_json_error('Brak uprawnień');

    $product_id = (int) ($_POST['product_id'] ?? 0);
    $group      = sanitize_text_field($_POST['group'] ?? '');
    $inherit    = ($_POST['inherit'] ?? 'no') === 'yes' ? 'yes' : 'no';

    $registry = shav_fields_registry();
    if (!isset($registry[$group])) wp_send_json_error('Nieznana grupa');

    $product = wc_get_product($product_id);
    if (!$product) wp_send_json_error('Brak produktu');

    $master_id = (int) get_option('shav_master_product_id', 68);

    // master_only — pozwol zapisac tylko na master produkcie
    if (!empty($registry[$group]['master_only']) && $product_id !== $master_id) {
        wp_send_json_error('Ta grupa jest edytowalna tylko na produkcie master.');
    }

    // Inherit flag (tylko jesli to nie master)
    if ($product_id !== $master_id) {
        $product->update_meta_data("_shav_inherit_{$group}", $inherit);
    }

    // Hidden flag — niezalezne od inherit, dziala na kazdym produkcie
    $hidden = ($_POST['hidden'] ?? 'no') === 'yes' ? 'yes' : 'no';
    $product->update_meta_data("_shav_hidden_{$group}", $hidden);

    // Zapisz wartosci pol
    foreach ($registry[$group]['fields'] as $f) {
        $raw = $_POST['field_' . $f['key']] ?? '';
        if ($f['type'] === 'textarea') {
            // Specjalna sanityzacja dla SVG (accordion_svg_*)
            if (strpos($f['key'], '_svg') !== false || $f['key'] === 'accordion_svg_1' || $f['key'] === 'accordion_svg_2' || $f['key'] === 'accordion_svg_3') {
                $allowed = [
                    'svg'      => ['xmlns' => 1, 'viewbox' => 1, 'width' => 1, 'height' => 1, 'fill' => 1, 'stroke' => 1, 'class' => 1],
                    'path'     => ['d' => 1, 'fill' => 1, 'stroke' => 1, 'stroke-width' => 1, 'stroke-linecap' => 1, 'stroke-linejoin' => 1, 'clip-rule' => 1, 'fill-rule' => 1],
                    'g'        => ['fill' => 1, 'stroke' => 1, 'transform' => 1],
                    'circle'   => ['cx' => 1, 'cy' => 1, 'r' => 1, 'fill' => 1, 'stroke' => 1],
                    'rect'     => ['x' => 1, 'y' => 1, 'width' => 1, 'height' => 1, 'rx' => 1, 'ry' => 1, 'fill' => 1, 'stroke' => 1],
                    'polygon'  => ['points' => 1, 'fill' => 1, 'stroke' => 1],
                    'line'     => ['x1' => 1, 'y1' => 1, 'x2' => 1, 'y2' => 1, 'stroke' => 1, 'stroke-width' => 1],
                    'defs'     => [],
                    'clippath' => ['id' => 1],
                ];
                $val = wp_kses($raw, $allowed);
            } else {
                $val = wp_kses_post($raw);
            }
        } elseif ($f['type'] === 'checkbox') {
            $val = $raw === 'yes' ? 'yes' : 'no';
        } else {
            $val = sanitize_text_field($raw);
        }
        $product->update_meta_data($f['key'], $val);
    }

    $product->save();
    wp_send_json_success(['ok' => true]);
}
add_action('wp_ajax_shav_fields_save', 'shav_fields_ajax_save');

// Bulk inherit toggle
function shav_fields_ajax_bulk_inherit()
{
    check_ajax_referer('shav_fields_nonce', 'nonce');
    if (!current_user_can('manage_woocommerce')) wp_send_json_error('Brak uprawnień');

    $inherit = ($_POST['inherit'] ?? 'no') === 'yes' ? 'yes' : 'no';
    $items   = json_decode(stripslashes($_POST['items'] ?? '[]'), true);
    if (!is_array($items)) wp_send_json_error('Brak danych');

    $registry  = shav_fields_registry();
    $master_id = (int) get_option('shav_master_product_id', 68);
    $updated   = 0;

    foreach ($items as $it) {
        $pid   = (int) ($it['product_id'] ?? 0);
        $group = sanitize_text_field($it['group'] ?? '');
        if (!isset($registry[$group])) continue;
        if (!empty($registry[$group]['master_only'])) continue;        // master-only — pomijaj
        if ($pid === $master_id) continue;                              // master nie dziedziczy

        $product = wc_get_product($pid);
        if (!$product) continue;
        $product->update_meta_data("_shav_inherit_{$group}", $inherit);
        $product->save();
        $updated++;
    }

    wp_send_json_success(['updated' => $updated]);
}
add_action('wp_ajax_shav_fields_bulk_inherit', 'shav_fields_ajax_bulk_inherit');
