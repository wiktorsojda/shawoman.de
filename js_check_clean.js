    
        // Reusable function to fetch and fill stock - globally available
        window.fetchStockForAutoFill = function(selectElement, maxInput) {
            const productId = selectElement.val();
            if (productId && productId.length > 0) {
                const firstProductId = productId[0]; // Bierzemy pierwszy wybrany produkt
                
                if (typeof jQuery !== 'undefined') {
                    jQuery.post(ajaxurl, {
                        action: 'shav_get_product_stock',
                        product_id: firstProductId
                    }, function(response) {
                        if (response.success && response.data.stock) {
                            maxInput.val(response.data.stock);
                            // Highlight na zielono na chwile
                            maxInput.css('background-color', '#e6ffed');
                            setTimeout(function() { maxInput.css('background-color', ''); }, 1500);
                        }
                    });
                }
            }
        };

        window.addEventListener('error', function(e) {
            jQuery('.shav-settings-wrap').prepend('<div class="notice notice-error"><p><strong>JS Error:</strong> ' + e.message + ' in ' + e.filename + ' on line ' + e.lineno + '</p></div>');
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Zakładki
            const tabs = document.querySelectorAll('.shav-tab');
            const contents = document.querySelectorAll('.shav-tab-content');

            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    tabs.forEach(t => t.classList.remove('active'));
                    contents.forEach(c => c.classList.remove('active'));
                    this.classList.add('active');
                    document.getElementById(this.dataset.target).classList.add('active');
                });
            });

            // Media Uploader WordPressa
            let file_frame;
            let currentTargetId = '';
            
            document.querySelectorAll('.button-media-upload').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    currentTargetId = this.dataset.target;
                    
                    if (file_frame) {
                        file_frame.open();
                        return;
                    }

                    file_frame = wp.media.frames.file_frame = wp.media({
                        title: 'Wybierz baner',
                        button: { text: 'Użyj tego obrazka' },
                        multiple: false
                    });

                    file_frame.on('select', function() {
                        let attachment = file_frame.state().get('selection').first().toJSON();
                        let targetInput = document.getElementById(currentTargetId);
                        targetInput.value = attachment.url;
                        let previewImg = targetInput.nextElementSibling.nextElementSibling;
                        if(previewImg && previewImg.tagName === 'IMG') {
                            previewImg.src = attachment.url;
                            previewImg.style.display = 'block';
                        }
                    });

                    file_frame.open();
                });
            });

            // --- REPEATER ODZNAK PROMOCYJNYCH ---
            const container = document.getElementById('shav-badges-container');
            const hiddenJsonInput = document.getElementById('shav_promo_badges_json');
            const addBtn = document.getElementById('add-badge-rule');
            const form = document.getElementById('shav-store-settings-form');

            // Media Uploader dla Repeaterów (SVG Badges)
            form.addEventListener('click', function(e) {
                if (e.target.classList.contains('sb-btn-img-upload') || e.target.classList.contains('sb-btn-bgimg-upload')) {
                    e.preventDefault();
                    let btn = e.target;
                    let isBg = btn.classList.contains('sb-btn-bgimg-upload');
                    let card = btn.closest('.promo-media-card');
                    let input = card.querySelector(isBg ? '.sb-bgimg-input' : '.sb-img-input');
                    let preview = card.querySelector('.promo-preview-box');
                    let actions = card.querySelector('.promo-actions');
                    
                    let frame = wp.media({
                        title: 'Wybierz obrazek',
                        button: { text: 'Użyj' },
                        multiple: false
                    });
                    
                    frame.on('select', function() {
                        let attachment = frame.state().get('selection').first().toJSON();
                        input.value = attachment.url;
                        preview.innerHTML = '<img src="' + attachment.url + '">';
                        
                        if (!actions.querySelector('.btn-remove')) {
                            let removeBtnClass = isBg ? 'sb-btn-bgimg-remove' : 'sb-btn-img-remove';
                            actions.insertAdjacentHTML('beforeend', '<a href="#" class="btn-remove ' + removeBtnClass + '">Usuń</a>');
                        }
                        
                        syncSvgBadgeDataFromDOM();
                    });
                    frame.open();
                }

                if (e.target.classList.contains('sb-btn-img-remove') || e.target.classList.contains('sb-btn-bgimg-remove')) {
                    e.preventDefault();
                    let btn = e.target;
                    let isBg = btn.classList.contains('sb-btn-bgimg-remove');
                    let card = btn.closest('.promo-media-card');
                    let input = card.querySelector(isBg ? '.sb-bgimg-input' : '.sb-img-input');
                    let preview = card.querySelector('.promo-preview-box');
                    
                    input.value = '';
                    preview.innerHTML = '<span class="dashicons dashicons-format-image"></span>';
                    btn.remove();
                    
                    syncSvgBadgeDataFromDOM();
                }
            });

            
            // Pobierz kategorie przekazane z PHP
            const wpCategories = [];

            // Wczytaj początkowe dane
            let badgeData = [];
            try {
                badgeData = JSON.parse(hiddenJsonInput.value) || [];
            } catch(e) { badgeData = []; }

            const predefinedColors = [
                'linear-gradient(90deg, #630303 1.11%, #C90606 96.67%)',
                'linear-gradient(85deg, #630303 -7.37%, #F00 107.37%)',
                'linear-gradient(265deg, #E0AC84 0%, #A4664A 100%)',
                '#000000'
            ];

            function renderRows() {
                // Przed zniszczeniem DOM niszczymy select2 żeby uniknąć wycieków pamięci
                if (typeof jQuery !== 'undefined' && jQuery.fn.selectWoo) {
                    jQuery('.wc-product-search').each(function() {
                        if(jQuery(this).hasClass('select2-hidden-accessible')) {
                            try { jQuery(this).selectWoo('destroy'); } catch(e) {}
                        }
                    });
                }
                
                container.innerHTML = '';
                
                badgeData.forEach((rule, index) => {
                    const row = document.createElement('div');
                    row.className = 'shav-repeater-row' + (rule.isFolded ? ' is-folded' : '');
                    
                    let isCustomColor = rule.color && !predefinedColors.includes(rule.color);
                    let selectValue = isCustomColor ? 'custom' : (rule.color || predefinedColors[0]);
                    let headerTitle = rule.text ? 'Reguła: ' + rule.text : 'Reguła #' + (index + 1);

                    row.innerHTML = `
                        <h3 class="rule-header">${headerTitle}</h3>
                        <a href="#" class="shav-remove-btn shav-remove-row" data-index="${index}">🗑 Usuń Regułę</a>
                        
                        <div class="shav-field-group" style="border:none; padding:0; margin-bottom:15px;">
                            <label class="shav-label">Zastosuj do:</label>
                            <select class="shav-input-text badge-type-select" data-index="${index}" style="max-width:300px;">
                                <option value="global" ${rule.type === 'global' ? 'selected' : ''}>Wszystkich Produktów (Globalnie)</option>
                                <option value="categories" ${rule.type === 'categories' ? 'selected' : ''}>Wybranych Kategorii</option>
                                <option value="products" ${rule.type === 'products' ? 'selected' : ''}>Konkretnych Produktów</option>
                            </select>
                        </div>
                        
                        <div class="shav-field-group target-container-categories" style="border:none; padding:0; margin-bottom:15px; ${rule.type === 'categories' ? '' : 'display:none;'}">
                            <label class="shav-label">Wybierz kategorie:</label>
                            <select class="shav-input-text badge-cats-select" multiple="multiple" data-index="${index}" style="max-width:600px; height:80px;">
                                ${wpCategories.map(cat => `<option value="${cat.id}" ${(rule.categories || []).includes(cat.id.toString()) ? 'selected' : ''}>${cat.name}</option>`).join('')}
                            </select>
                            <span class="shav-desc">Przytrzymaj CTRL/CMD aby wybrać wiele kategorii.</span>
                        </div>

                        <div class="shav-field-group target-container-products" style="border:none; padding:0; margin-bottom:15px; ${rule.type === 'products' ? '' : 'display:none;'}">
                            <label class="shav-label">Wyszukaj i wybierz produkty:</label>
                            <select class="wc-product-search badge-products-select" multiple="multiple" style="width: 100%; max-width:600px;" data-placeholder="Szukaj produktów..." data-action="woocommerce_json_search_products_and_variations" data-index="${index}">
                            </select>
                        </div>
                        
                        <div class="shav-field-group" style="border:none; padding:0; margin-bottom:15px;">
                            <label class="shav-label">Procent obniżki (sama liczba)</label>
                            <input type="number" class="shav-input-text badge-text-input" data-index="${index}" value="${rule.text || ''}" placeholder="np. 20">
                        </div>

                        <div class="shav-field-group" style="border:none; padding:0; margin-bottom:0;">
                            <label class="shav-label">Kolor Liczby (Kod CSS - Gradient lub Kolor)</label>
                            <div class="promo-swatches" data-index="${index}">
                                ${predefinedColors.map(color => `
                                    <div class="promo-swatch ${selectValue === color ? 'is-selected' : ''}" style="background: ${color};" data-color="${color}" title="${color}"></div>
                                `).join('')}
                                <div class="promo-swatch custom-trigger ${selectValue === 'custom' ? 'is-selected' : ''}" title="Własny CSS">+</div>
                            </div>
                            <div class="custom-css-input ${selectValue === 'custom' ? 'is-active' : ''}">
                                <input type="text" class="shav-input-text badge-color-input" data-index="${index}" value="${isCustomColor ? rule.color : (selectValue === 'custom' ? '' : selectValue)}" placeholder="Wpisz np. linear-gradient(to right, #ff0000, #00ff00)">
                            </div>
                        </div>
                    `;
                    container.appendChild(row);

                    // Jeśli są wybrane produkty, dodajemy optiony na sztywno żeby select2 je załadował prawidłowo
                    if (rule.type === 'products' && Array.isArray(rule.products) && rule.products.length > 0) {
                        const selectElement = row.querySelector('.badge-products-select');
                        rule.products.forEach(prod => {
                            const option = new Option(prod.text, prod.id, true, true);
                            selectElement.append(option);
                        });
                    }
                });

                // Podepnij usuwanie
                document.querySelectorAll('.shav-remove-row').forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        if(confirm('Na pewno usunąć tę regułę?')) {
                            syncDataFromDOM();
                            let idx = parseInt(btn.getAttribute('data-index'));
                            if (!isNaN(idx)) {
                                badgeData.splice(idx, 1);
                                renderRows();
                            }
                        }
                    });
                });

                // Podepnij przełączanie selectów typu (Global/Kategorie/Produkty)
                document.querySelectorAll('.badge-type-select').forEach(select => {
                    select.addEventListener('change', function(e) {
                        syncDataFromDOM();
                        let idx = parseInt(select.getAttribute('data-index'));
                        if (!isNaN(idx) && badgeData[idx]) {
                            badgeData[idx].type = this.value;
                            renderRows();
                        }
                    });
                });

                // Obsługa swatchy kolorów
                document.querySelectorAll('.promo-swatches').forEach(swatches => {
                    swatches.addEventListener('click', function(e) {
                        const swatch = e.target.closest('.promo-swatch');
                        if (!swatch) return;
                        
                        const container = this.parentElement;
                        const inputField = container.querySelector('.badge-color-input');
                        const customInputBox = container.querySelector('.custom-css-input');
                        
                        // Zmiana klasy is-selected
                        this.querySelectorAll('.promo-swatch').forEach(s => s.classList.remove('is-selected'));
                        swatch.classList.add('is-selected');

                        if (swatch.classList.contains('custom-trigger')) {
                            customInputBox.classList.add('is-active');
                            inputField.value = ''; // Czekamy na wpis usera
                        } else {
                            customInputBox.classList.remove('is-active');
                            inputField.value = swatch.dataset.color;
                        }
                    });
                });

                // Zainicjuj Select2 z WooCommerce na nowo wygenerowanych rzędach
                if (typeof jQuery !== 'undefined' && jQuery.fn.selectWoo) {
                    jQuery(containerStock).find('.wc-product-search').each(function() {
                        var $select = jQuery(this);
                        $select.selectWoo({
                            minimumInputLength: 3,
                            allowClear: true,
                            ajax: {
                                url: ajaxurl,
                                dataType: 'json',
                                delay: 250,
                                data: function(params) {
                                    return {
                                        term: params.term,
                                        action: $select.data('action') || 'woocommerce_json_search_products_and_variations',
                                        security: typeof wc_enhanced_select_params !== 'undefined' ? wc_enhanced_select_params.search_products_nonce : ''
                                    };
                                },
                                processResults: function(data) {
                                    var terms = [];
                                    if (data) {
                                        jQuery.each(data, function(id, text) {
                                            terms.push({ id: id, text: text });
                                        });
                                    }
                                    return { results: terms };
                                },
                                cache: true
                            }
                        });
                    });
                }
            }

            // Funkcja ściągająca wpisane dane do pamięci przed np. dodaniem nowego wiersza
            function syncDataFromDOM() {
                const rows = document.querySelectorAll('.shav-repeater-row:not(.stock-row):not(.text-badge-row):not(.svg-badge-row)');
                badgeData = [];
                rows.forEach((row, idx) => {
                    const type = row.querySelector('.badge-type-select').value;
                    const text = row.querySelector('.badge-text-input').value;
                    const color = row.querySelector('.badge-color-input').value;
                    const isFolded = row.classList.contains('is-folded');
                    
                    let categories = [];
                    let products = [];
                    
                    if (type === 'categories') {
                        const catSelect = row.querySelector('.badge-cats-select');
                        if (catSelect) {
                            Array.from(catSelect.selectedOptions).forEach(opt => categories.push(opt.value));
                        }
                    } else if (type === 'products') {
                        const prodSelect = row.querySelector('.badge-products-select');
                        if (typeof jQuery !== 'undefined' && jQuery(prodSelect).hasClass('select2-hidden-accessible')) {
                            const selectedData = jQuery(prodSelect).selectWoo('data');
                            if (Array.isArray(selectedData)) {
                                selectedData.forEach(item => {
                                    products.push({ id: item.id, text: item.text });
                                });
                            }
                        }
                    }

                    badgeData.push({ type, categories, products, text, color });
                });
            }

            // Inicjalny render
            renderRows();

            // Dodawanie wiersza (Odznaki)
            addBtn.addEventListener('click', function(e) {
                e.preventDefault();
                syncDataFromDOM();
                badgeData.push({ type: 'categories', categories: [], products: [], text: '', color: '' });
                renderRows();
            });

            // --- REPEATER PASKÓW MAGAZYNOWYCH ---
            const containerStock = document.getElementById('shav-stock-container');
            const hiddenJsonInputStock = document.getElementById('shav_stock_strips_json');
            const addBtnStock = document.getElementById('add-stock-rule');
            
            let stockData = [];
            try {
                let rawVal = hiddenJsonInputStock.value.trim();
                if (rawVal && rawVal !== '[]') {
                    if (rawVal.startsWith('[')) {
                        stockData = JSON.parse(rawVal) || [];
                    } else {
                        stockData = JSON.parse(decodeURIComponent(escape(atob(rawVal)))) || [];
                    }
                }
            } catch(e) { stockData = []; }

            function renderStockRows() {
                if (typeof jQuery !== 'undefined' && jQuery.fn.selectWoo) {
                    jQuery(containerStock).find('.wc-product-search').each(function() {
                        if(jQuery(this).hasClass('select2-hidden-accessible')) {
                            try { jQuery(this).selectWoo('destroy'); } catch(e) {}
                        }
                    });
                }
                
                containerStock.innerHTML = '';
                
                stockData.forEach((rule, index) => {
                    const row = document.createElement('div');
                    row.className = 'shav-repeater-row stock-row' + (rule.isFolded ? ' is-folded' : '');
                    let headerTitle = rule.text ? 'Pasek: ' + rule.text : 'Reguła Paska #' + (index + 1);
                    
                    row.innerHTML = `
                        <h3 class="rule-header">${headerTitle}</h3>
                        <a href="#" class="shav-remove-btn shav-remove-row-stock" data-index="${index}">🗑 Usuń Regułę</a>
                        
                        <div class="shav-field-group" style="border:none; padding:0; margin-bottom:15px;">
                            <label class="shav-label">Zastosuj do:</label>
                            <select class="shav-input-text stock-type-select" data-index="${index}" style="max-width:300px;">
                                <option value="global" ${rule.type === 'global' ? 'selected' : ''}>Wszystkich Produktów (Globalnie)</option>
                                <option value="categories" ${rule.type === 'categories' ? 'selected' : ''}>Wybranych Kategorii</option>
                                <option value="products" ${rule.type === 'products' ? 'selected' : ''}>Konkretnych Produktów</option>
                            </select>
                        </div>
                        
                        <div class="shav-field-group target-container-categories" style="border:none; padding:0; margin-bottom:15px; ${rule.type === 'categories' ? '' : 'display:none;'}">
                            <label class="shav-label">Wybierz kategorie:</label>
                            <select class="shav-input-text stock-cats-select" multiple="multiple" data-index="${index}" style="max-width:600px; height:80px;">
                                ${wpCategories.map(cat => `<option value="${cat.id}" ${(rule.categories || []).includes(cat.id.toString()) ? 'selected' : ''}>${cat.name}</option>`).join('')}
                            </select>
                        </div>

                        <div class="shav-field-group target-container-products" style="border:none; padding:0; margin-bottom:15px; ${rule.type === 'products' ? '' : 'display:none;'}">
                            <label class="shav-label">Wyszukaj i wybierz produkty:</label>
                            <select class="wc-product-search stock-product-select stock-products-select" multiple="multiple" style="width: 100%; max-width:600px;" data-placeholder="Szukaj produktów..." data-action="woocommerce_json_search_products_and_variations" data-index="${index}">
                            </select>
                        </div>
                        
                        <div class="shav-field-group" style="border:none; padding:0; margin-bottom:15px;">
                            <label class="shav-label">Tryb obliczania paska</label>
                            <select class="shav-input-text stock-mode-select" data-index="${index}" style="max-width:300px;">
                                <option value="auto" ${rule.mode === 'auto' || !rule.mode ? 'selected' : ''}>Automatyczny (rzeczywisty ze stanu)</option>
                                <option value="manual" ${rule.mode === 'manual' ? 'selected' : ''}>Ręczny (stały procent)</option>
                            </select>
                        </div>

                        <div class="shav-field-group" style="border:none; padding:0; margin-bottom:15px;">
                            <label class="shav-label">Nadpisany Tekst Paska (tagi: {percent}, {stock})</label>
                            <input type="text" class="shav-input-text stock-text-input" data-index="${index}" value="${rule.text || ''}" placeholder="Tylko {percent}% pozostało!">
                        </div>

                        <div class="shav-field-group" style="border:none; padding:0; margin-bottom:15px; ${rule.mode === 'manual' ? '' : 'display:none;'}">
                            <label class="shav-label">Statyczny Procent Wypełnienia (%)</label>
                            <input type="number" class="shav-input-text stock-percent-input" data-index="${index}" min="0" max="100" style="max-width: 150px;" value="${rule.percent || ''}" placeholder="80">
                        </div>

                        <div class="shav-field-group" style="border:none; padding:0; margin-bottom:0; ${rule.mode === 'manual' ? 'display:none;' : ''}">
                            <label class="shav-label">Limit magazynowy (baza dla 100%)</label>
                            <input type="number" class="shav-input-text stock-max-input" data-index="${index}" min="1" style="max-width: 150px;" value="${rule.max_stock || ''}" placeholder="100">
                        </div>
                    `;
                    containerStock.appendChild(row);

                    if (rule.type === 'products' && Array.isArray(rule.products) && rule.products.length > 0) {
                        const selectElement = row.querySelector('.stock-products-select');
                        rule.products.forEach(prod => {
                            const option = new Option(prod.text, prod.id, true, true);
                            selectElement.append(option);
                        });
                    }
                });

                document.querySelectorAll('.shav-remove-row-stock').forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        if(confirm('Na pewno usunąć tę regułę?')) {
                            syncStockDataFromDOM();
                            let idx = parseInt(btn.getAttribute('data-index'));
                            if (!isNaN(idx)) {
                                stockData.splice(idx, 1);
                                renderStockRows();
                            }
                        }
                    });
                });

                document.querySelectorAll('.stock-type-select').forEach(select => {
                    select.addEventListener('change', function(e) {
                        syncStockDataFromDOM();
                        let idx = parseInt(select.getAttribute('data-index'));
                        if (!isNaN(idx) && stockData[idx]) {
                            stockData[idx].type = this.value;
                            renderStockRows();
                        }
                    });
                });

                document.querySelectorAll('.stock-mode-select').forEach(select => {
                    select.addEventListener('change', function(e) {
                        syncStockDataFromDOM();
                        let idx = parseInt(select.getAttribute('data-index'));
                        if (!isNaN(idx) && stockData[idx]) {
                            stockData[idx].mode = this.value;
                            renderStockRows();
                        }
                    });
                });

                if (typeof jQuery !== 'undefined' && jQuery.fn.selectWoo) {
                    jQuery(containerStock).find('.wc-product-search').each(function() {
                        var sel = $(this);
                        var row = sel.closest('.shav-repeater-row');
                        sel.selectWoo({
                            ajax: {
                                url: ajaxurl,
                                dataType: 'json',
                                delay: 250,
                                data: function(params) {
                                    return {
                                        term: params.term,
                                        action: 'woocommerce_json_search_products_and_variations',
                                        security: typeof wc_enhanced_select_params !== 'undefined' ? wc_enhanced_select_params.search_products_nonce : ''
                                    };
                                },
                                processResults: function(data) {
                                    var terms = [];
                                    if (data) {
                                        jQuery.each(data, function(id, text) {
                                            terms.push({ id: id, text: text });
                                        });
                                    }
                                    return { results: terms };
                                },
                                cache: true
                            },
                            minimumInputLength: 3
                        });

                        // Auto-fill trigger when product changes
                        sel.on('change', function() {
                            const modeSelect = $(row).find('.stock-mode-select').val();
                            if (modeSelect === 'auto') {
                                const maxInput = $(row).find('.stock-max-input');
                                window.fetchStockForAutoFill($(this), maxInput);
                            }
                        });
                        
                        // Auto-fill check during render: if mode is auto and max_stock is empty, fetch it
                        const currentMode = $(row).find('.stock-mode-select').val();
                        const maxInput = $(row).find('.stock-max-input');
                        if (currentMode === 'auto' && !maxInput.val()) {
                            window.fetchStockForAutoFill(sel, maxInput);
                        }
                    });
                }
            }

            function syncStockDataFromDOM() {
                const rows = document.querySelectorAll('.stock-row');
                stockData = [];
                rows.forEach((row, idx) => {
                    const typeSelect = row.querySelector('.stock-type-select');
                    const modeSelect = row.querySelector('.stock-mode-select');
                    const textInput = row.querySelector('.stock-text-input');
                    const percentInput = row.querySelector('.stock-percent-input');
                    const maxStockInput = row.querySelector('.stock-max-input');
                    
                    const type = typeSelect ? typeSelect.value : 'global';
                    const mode = modeSelect ? modeSelect.value : 'auto';
                    const text = textInput ? textInput.value : '';
                    const percent = percentInput ? percentInput.value : '';
                    const max_stock = maxStockInput ? maxStockInput.value : '';
                    const isFolded = row.classList.contains('is-folded');
                    
                    let categories = [];
                    let products = [];
                    
                    if (type === 'categories') {
                        const catSelect = row.querySelector('.stock-cats-select');
                        if (catSelect) {
                            Array.from(catSelect.selectedOptions).forEach(opt => categories.push(opt.value));
                        }
                    } else if (type === 'products') {
                        const prodSelect = row.querySelector('.stock-products-select');
                        if (typeof jQuery !== 'undefined' && jQuery(prodSelect).hasClass('select2-hidden-accessible')) {
                            const selectedData = jQuery(prodSelect).selectWoo('data');
                            if (Array.isArray(selectedData)) {
                                selectedData.forEach(item => {
                                    products.push({ id: item.id, text: item.text });
                                });
                            }
                        }
                    }

                    stockData.push({
                        type: type,
                        mode: mode,
                        categories: categories,
                        products: products,
                        text: text,
                        percent: percent,
                        max_stock: max_stock,
                        isFolded: isFolded
                    });
                });
            }

            if(addBtnStock) {
                addBtnStock.addEventListener('click', function(e) {
                    e.preventDefault();
                    syncStockDataFromDOM();
                    stockData.push({
                        type: 'global',
                        mode: 'auto',
                        categories: [],
                        products: [],
                        text: '',
                        percent: '',
                        max_stock: '',
                        isFolded: false
                    });
                    renderStockRows();
                });
            }

            renderStockRows();

            // --- REPEATER ETYKIET TEKSTOWYCH ---
            const containerTextBadges = document.getElementById('shav-text-badges-container');
            const hiddenJsonInputTextBadges = document.getElementById('shav_text_badges_json');
            const hiddenJsonInputSvgBadges = document.getElementById('shav_svg_badges_json');
            const containerSvgBadges = document.getElementById('shav-svg-badges-container');
            const btnAddSvgBadgeRule = document.getElementById('add-svg-badge-rule');

            const addBtnTextBadges = document.getElementById('add-text-badge-rule');
            
            let textBadgeData = [];
            try {
                textBadgeData = JSON.parse(hiddenJsonInputTextBadges.value) || [];
            } catch(e) { textBadgeData = []; }


            // ==========================================
            // LOGIKA SVG BADGES
            // ==========================================
            let svgBadgeData = [];
            try {
                let rawVal = hiddenJsonInputSvgBadges.value.trim();
                if (rawVal && rawVal !== '[]') {
                    if (rawVal.startsWith('[')) {
                        svgBadgeData = JSON.parse(rawVal) || [];
                    } else {
                        svgBadgeData = JSON.parse(decodeURIComponent(escape(atob(rawVal)))) || [];
                    }
                }
            } catch(e) { svgBadgeData = []; }

            function renderSvgBadgeRows() {
                if (typeof jQuery !== 'undefined' && jQuery.fn.selectWoo) {
                    jQuery(containerSvgBadges).find('.wc-product-search').each(function() {
                        if(jQuery(this).hasClass('select2-hidden-accessible')) {
                            try { jQuery(this).selectWoo('destroy'); } catch(e) {}
                        }
                    });
                }
                
                containerSvgBadges.innerHTML = '';
                
                svgBadgeData.forEach((rule, index) => {
                    const row = document.createElement('div');
                    row.className = 'shav-repeater-row svg-badge-row' + (rule.isFolded ? ' is-folded' : '');
                    
                    let headerTitle = rule.text ? 'Odznaka: ' + rule.text : 'Odznaka #' + (index + 1);

                    let iconType = rule.iconType || 'svg';
                    let align = rule.align || 'flex-start';
                    let iconHeightUnit = rule.iconHeightUnit || 'em';
                    let iconHeightVal = rule.iconHeightVal || '1.2';
                    let widthVal = rule.width || '100';
                    let isAutoWidth = rule.widthAuto || false;

                    row.innerHTML = `
                        <h3 class="rule-header">${headerTitle}</h3>
                        <a href="#" class="shav-remove-btn shav-remove-row-svg-badge" data-index="${index}">🗑 Usuń Odznakę</a>
                        
                        <div class="shav-field-group" style="border:none; padding:0; margin-bottom:15px;">
                            <label class="shav-label">Zastosuj do:</label>
                            <select class="shav-input-text sb-type-select" data-index="${index}" style="max-width:300px;">
                                <option value="global" ${rule.type === 'global' ? 'selected' : ''}>Wszystkich Produktów (Globalnie)</option>
                                <option value="categories" ${rule.type === 'categories' ? 'selected' : ''}>Wybranych Kategorii</option>
                                <option value="products" ${rule.type === 'products' ? 'selected' : ''}>Konkretnych Produktów</option>
                            </select>
                        </div>
                        
                        <div class="shav-field-group target-container-categories" style="border:none; padding:0; margin-bottom:15px; ${rule.type === 'categories' ? '' : 'display:none;'}">
                            <label class="shav-label">Wybierz kategorie:</label>
                            <select class="shav-input-text sb-cats-select" multiple="multiple" data-index="${index}" style="max-width:600px; height:80px;">
                                ${wpCategories.map(cat => `<option value="${cat.id}" ${(rule.categories || []).includes(cat.id.toString()) ? 'selected' : ''}>${cat.name}</option>`).join('')}
                            </select>
                        </div>

                        <div class="shav-field-group target-container-products" style="border:none; padding:0; margin-bottom:15px; ${rule.type === 'products' ? '' : 'display:none;'}">
                            <label class="shav-label">Wyszukaj i wybierz produkty:</label>
                            <select class="wc-product-search sb-products-select" multiple="multiple" style="width: 100%; max-width:600px;" data-placeholder="Szukaj produktów..." data-action="woocommerce_json_search_products_and_variations" data-index="${index}">
                            </select>
                        </div>

                        <div style="display:flex; gap:15px; margin-bottom:15px;">
                            <div class="shav-field-group" style="flex:2; border:none; padding:0; margin:0;">
                                <label class="shav-label">Tekst Badge'a</label>
                                <input type="text" class="shav-input-text sb-text-input" data-index="${index}" value="${rule.text || ''}">
                            </div>
                            <div class="shav-field-group" style="flex:1; border:none; padding:0; margin:0;">
                                <label class="shav-label">Typ Ikony</label>
                                <div style="display:flex; gap:10px; flex-wrap:wrap; margin-top:5px;">
                                    <label style="margin:0; display:flex; align-items:center; gap:5px;"><input type="radio" name="sb_icon_type_${index}" value="svg" class="sb-icon-type" data-index="${index}" ${iconType === 'svg' ? 'checked' : ''}> Kod SVG</label>
                                    <label style="margin:0; display:flex; align-items:center; gap:5px;"><input type="radio" name="sb_icon_type_${index}" value="image" class="sb-icon-type" data-index="${index}" ${iconType === 'image' ? 'checked' : ''}> Obrazek</label>
                                </div>
                            </div>
                        </div>

                        <div style="display:flex; gap:15px; margin-bottom:15px;">
                            <div class="shav-field-group sb-svg-wrapper" style="flex:1; border:none; padding:0; margin:0; ${iconType === 'svg' ? 'display:block;' : 'display:none;'}">
                                <label class="shav-label">Kod SVG</label>
                                <textarea class="shav-input-textarea sb-svg-input" data-index="${index}" placeholder="Wklej <svg>...">${rule.svgCode || ''}</textarea>
                            </div>
                            <div class="shav-field-group sb-img-wrapper" style="flex:1; border:none; padding:0; margin:0; ${iconType === 'image' ? 'display:block;' : 'display:none;'}">
                                <label class="shav-label">Obrazek Ikony</label>
                                <div class="promo-media-card" style="max-width:300px;">
                                    <div class="promo-preview-box">
                                        ${rule.image ? `<img src="${rule.image}">` : `<span class="dashicons dashicons-format-image"></span>`}
                                    </div>
                                    <input type="hidden" class="sb-img-input" value="${rule.image || ''}">
                                    <div class="promo-actions">
                                        <button type="button" class="button button-secondary button-media-upload sb-btn-img-upload">Wybierz Obrazek</button>
                                        ${rule.image ? `<a href="#" class="btn-remove sb-btn-img-remove">Usuń</a>` : ''}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div style="display:flex; gap:15px; margin-bottom:15px; align-items:flex-start;">
                            <div style="flex:1; display:flex; flex-direction:column; gap:15px;">
                                <div style="display:flex; gap:15px; flex-wrap:wrap;">
                                    <div class="shav-field-group" style="flex:1; min-width:200px; border:none; padding:0; margin:0;">
                                        <label class="shav-label">Kolor Tła Badge</label>
                                        <div class="promo-swatches sb-bg-swatches" data-index="${index}">
                                            ${predefinedColors.map(color => `
                                                <div class="promo-swatch ${(rule.bgColor || '#f0f0f1') === color ? 'is-selected' : ''}" style="background: ${color};" data-color="${color}" title="${color}"></div>
                                            `).join('')}
                                            <div class="promo-swatch custom-trigger ${!predefinedColors.includes(rule.bgColor || '#f0f0f1') ? 'is-selected' : ''}" title="Własny CSS">+</div>
                                        </div>
                                        <div class="custom-css-input ${!predefinedColors.includes(rule.bgColor || '#f0f0f1') ? 'is-active' : ''}">
                                            <input type="text" class="shav-input-text sb-bgcolor-input" data-index="${index}" value="${rule.bgColor || '#f0f0f1'}" placeholder="np. #f0f0f1">
                                        </div>
                                    </div>
                                    <div class="shav-field-group" style="flex:1; min-width:200px; border:none; padding:0; margin:0;">
                                        <label class="shav-label">Kolor Tekstu Badge</label>
                                        <div class="promo-swatches sb-text-swatches" data-index="${index}">
                                            ${predefinedColors.map(color => `
                                                <div class="promo-swatch ${(rule.textColor || '#000000') === color ? 'is-selected' : ''}" style="background: ${color};" data-color="${color}" title="${color}"></div>
                                            `).join('')}
                                            <div class="promo-swatch custom-trigger ${!predefinedColors.includes(rule.textColor || '#000000') ? 'is-selected' : ''}" title="Własny CSS">+</div>
                                        </div>
                                        <div class="custom-css-input ${!predefinedColors.includes(rule.textColor || '#000000') ? 'is-active' : ''}">
                                            <input type="text" class="shav-input-text sb-textcolor-input" data-index="${index}" value="${rule.textColor || '#000000'}" placeholder="np. #000000">
                                        </div>
                                    </div>
                                </div>
                                <div class="shav-field-group" style="border:none; padding:0; margin:0;">
                                    <label class="shav-label">Wysokość Ikony</label>
                                    <div style="display:flex; border:1px solid #8c8f94; border-radius:4px; overflow:hidden; height:34px; max-width:200px;">
                                        <input type="number" step="0.1" class="sb-iconheightval-input" data-index="${index}" value="${iconHeightVal}" style="border:none; border-radius:0; flex:1; outline:none; box-shadow:none; padding:0 8px;">
                                        <select class="sb-iconheightunit-select" data-index="${index}" style="border:none; border-left:1px solid #8c8f94; border-radius:0; background:#f6f7f7; padding:0 10px;">
                                            <option value="px" ${iconHeightUnit === 'px' ? 'selected' : ''}>px</option>
                                            <option value="em" ${iconHeightUnit === 'em' ? 'selected' : ''}>em</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div style="flex:1;">
                                <label class="shav-label">Tło Obrazkowe Badge</label>
                                <div class="promo-media-card" style="max-width:300px;">
                                    <div class="promo-preview-box">
                                        ${rule.bgImage ? `<img src="${rule.bgImage}">` : `<span class="dashicons dashicons-format-image"></span>`}
                                    </div>
                                    <input type="hidden" class="sb-bgimg-input" value="${rule.bgImage || ''}">
                                    <div class="promo-actions">
                                        <button type="button" class="button button-secondary button-media-upload sb-btn-bgimg-upload">Wybierz Tło</button>
                                        ${rule.bgImage ? `<a href="#" class="btn-remove sb-btn-bgimg-remove">Usuń</a>` : ''}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div style="display:flex; gap:15px; margin-bottom:15px; background:#e4f0fa; padding:10px; border-radius:4px;">
                            <div class="shav-field-group" style="flex:1; border:none; padding:0; margin:0;">
                                <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; margin-bottom:5px;">
                                    <label class="shav-label" style="margin:0;">Szerokość (%)</label>
                                    <label style="font-weight:normal; font-size:12px; margin:0; display:flex; align-items:center; gap:5px;"><input type="checkbox" class="sb-widthauto-input" data-index="${index}" ${isAutoWidth ? 'checked' : ''}> Auto (fit-content)</label>
                                </div>
                                <div style="display:flex; align-items:center; gap:10px;">
                                    <input type="range" class="sb-width-input" data-index="${index}" min="10" max="100" value="${widthVal}" style="flex:1;">
                                    <span style="font-size:12px; font-weight:bold; width:40px; text-align:right;">${widthVal}%</span>
                                </div>
                            </div>
                            <div class="shav-field-group" style="flex:1; border:none; padding:0; margin:0;">
                                <label class="shav-label">Wyrównanie</label>
                                <select class="shav-input-text sb-align-select" data-index="${index}">
                                    <option value="flex-start" ${align === 'flex-start' ? 'selected' : ''}>Do lewej</option>
                                    <option value="center" ${align === 'center' ? 'selected' : ''}>Do środka</option>
                                    <option value="flex-end" ${align === 'flex-end' ? 'selected' : ''}>Do prawej</option>
                                </select>
                            </div>
                        </div>

                        <div style="display:flex; gap:15px; background:#e4f0fa; padding:10px; border-radius:4px;">
                            <div class="shav-field-group" style="flex:0.25; border:none; padding:0; margin:0;">
                                <label class="shav-label">Margin Góra</label>
                                <input type="number" class="shav-input-text sb-mt-input" data-index="${index}" value="${rule.mt !== undefined ? rule.mt : '12'}">
                            </div>
                            <div class="shav-field-group" style="flex:0.25; border:none; padding:0; margin:0;">
                                <label class="shav-label">Margin Dół</label>
                                <input type="number" class="shav-input-text sb-mb-input" data-index="${index}" value="${rule.mb !== undefined ? rule.mb : '0'}">
                            </div>
                            <div class="shav-field-group" style="flex:0.25; border:none; padding:0; margin:0;">
                                <label class="shav-label">Padding Y</label>
                                <input type="number" class="shav-input-text sb-py-input" data-index="${index}" value="${rule.py !== undefined ? rule.py : '5'}">
                            </div>
                            <div class="shav-field-group" style="flex:0.25; border:none; padding:0; margin:0;">
                                <label class="shav-label">Padding X</label>
                                <input type="number" class="shav-input-text sb-px-input" data-index="${index}" value="${rule.px !== undefined ? rule.px : '10'}">
                            </div>
                        </div>
                    `;
                    containerSvgBadges.appendChild(row);

                    if (rule.type === 'products' && Array.isArray(rule.products) && rule.products.length > 0) {
                        const selectElement = row.querySelector('.sb-products-select');
                        rule.products.forEach(prod => {
                            const option = new Option(prod.text, prod.id, true, true);
                            selectElement.append(option);
                        });
                    }
                });

                document.querySelectorAll('.shav-remove-row-svg-badge').forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        if(confirm('Na pewno usunąć tę odznakę?')) {
                            syncSvgBadgeDataFromDOM();
                            svgBadgeData.splice(parseInt(this.dataset.index), 1);
                            renderSvgBadgeRows();
                        }
                    });
                });

                document.querySelectorAll('.sb-type-select').forEach(select => {
                    select.addEventListener('change', function(e) {
                        syncSvgBadgeDataFromDOM();
                        svgBadgeData[parseInt(this.dataset.index)].type = this.value;
                        renderSvgBadgeRows();
                    });
                });
                
                document.querySelectorAll('.sb-icon-type').forEach(radio => {
                    radio.addEventListener('change', function(e) {
                        syncSvgBadgeDataFromDOM();
                        svgBadgeData[parseInt(this.dataset.index)].iconType = this.value;
                        renderSvgBadgeRows();
                    });
                });
                

                containerSvgBadges.querySelectorAll('.promo-swatches').forEach(swatches => {
                    swatches.addEventListener('click', function(e) {
                        const swatch = e.target.closest('.promo-swatch');
                        if (!swatch) return;
                        
                        const container = this.parentElement;
                        const inputField = container.querySelector(this.classList.contains('sb-bg-swatches') ? '.sb-bgcolor-input' : '.sb-textcolor-input');
                        const customInputBox = container.querySelector('.custom-css-input');
                        
                        this.querySelectorAll('.promo-swatch').forEach(s => s.classList.remove('is-selected'));
                        swatch.classList.add('is-selected');

                        if (swatch.classList.contains('custom-trigger')) {
                            customInputBox.classList.add('is-active');
                            inputField.value = '';
                        } else {
                            customInputBox.classList.remove('is-active');
                            inputField.value = swatch.dataset.color;
                        }
                    });
                });

                document.querySelectorAll('.sb-width-input').forEach(slider => {
                    slider.addEventListener('input', function(e) {
                        this.nextElementSibling.innerText = this.value + '%';
                    });
                });

                if (typeof jQuery !== 'undefined' && jQuery.fn.selectWoo) {
                    jQuery(containerSvgBadges).find('.sb-cats-select').selectWoo({ placeholder: 'Wybierz kategorie...' });
                    jQuery(containerSvgBadges).find('.wc-product-search').each(function() {
                        jQuery(this).selectWoo({
                            ajax: {
                                url: ajaxurl,
                                dataType: 'json',
                                delay: 250,
                                data: function(params) {
                                    return {
                                        term: params.term,
                                        action: 'woocommerce_json_search_products_and_variations',
                                        security: window.wc_enhanced_select_params ? window.wc_enhanced_select_params.search_products_nonce : ''
                                    };
                                },
                                processResults: function(data) {
                                    let terms = [];
                                    if (data) {
                                        jQuery.each(data, function(id, text) {
                                            terms.push({ id: id, text: text });
                                        });
                                    }
                                    return { results: terms };
                                },
                                cache: true
                            },
                            minimumInputLength: 3
                        });
                    });
                }
            }

            function syncSvgBadgeDataFromDOM() {
                const rows = document.querySelectorAll('.svg-badge-row');
                svgBadgeData = [];
                
                rows.forEach((row, idx) => {
                    let typeSelect = row.querySelector('.sb-type-select');
                    let ruleType = typeSelect ? typeSelect.value : 'global';
                    let cats = [];
                    let prods = [];
                    
                    if (ruleType === 'categories') {
                        let catSelect = row.querySelector('.sb-cats-select');
                        if (catSelect && typeof jQuery !== 'undefined') {
                            cats = jQuery(catSelect).val() || [];
                        }
                    } else if (ruleType === 'products') {
                        let prodSelect = row.querySelector('.tb-products-select') || row.querySelector('.sb-products-select');
                        if (prodSelect && typeof jQuery !== 'undefined') {
                            let selectedData = jQuery(prodSelect).selectWoo('data');
                            if (selectedData) {
                                prods = selectedData.map(item => ({ id: item.id, text: item.text }));
                            }
                        }
                    }

                    svgBadgeData.push({
                        isFolded: row.classList.contains('is-folded'),
                        type: ruleType,
                        categories: cats,
                        products: prods,
                        text: (row.querySelector('.sb-text-input') ? row.querySelector('.sb-text-input').value : ''),
                        iconType: (row.querySelector('.sb-icon-type:checked') ? row.querySelector('.sb-icon-type:checked').value : 'svg'),
                        svgCode: (row.querySelector('.sb-svg-input') ? row.querySelector('.sb-svg-input').value : ''),
                        image: (row.querySelector('.sb-img-input') ? row.querySelector('.sb-img-input').value : ''),
                        bgColor: (row.querySelector('.sb-bgcolor-input') ? row.querySelector('.sb-bgcolor-input').value : ''),
                        textColor: (row.querySelector('.sb-textcolor-input') ? row.querySelector('.sb-textcolor-input').value : ''),
                        iconHeightVal: (row.querySelector('.sb-iconheightval-input') ? row.querySelector('.sb-iconheightval-input').value : '1.2'),
                        iconHeightUnit: (row.querySelector('.sb-iconheightunit-select') ? row.querySelector('.sb-iconheightunit-select').value : 'em'),
                        bgImage: (row.querySelector('.sb-bgimg-input') ? row.querySelector('.sb-bgimg-input').value : ''),
                        width: (row.querySelector('.sb-width-input') ? row.querySelector('.sb-width-input').value : '100'),
                        widthAuto: (row.querySelector('.sb-widthauto-input') ? row.querySelector('.sb-widthauto-input').checked : false),
                        align: (row.querySelector('.sb-align-select') ? row.querySelector('.sb-align-select').value : 'flex-start'),
                        mt: (row.querySelector('.sb-mt-input') ? row.querySelector('.sb-mt-input').value : '12'),
                        mb: (row.querySelector('.sb-mb-input') ? row.querySelector('.sb-mb-input').value : '0'),
                        py: (row.querySelector('.sb-py-input') ? row.querySelector('.sb-py-input').value : '5'),
                        px: (row.querySelector('.sb-px-input') ? row.querySelector('.sb-px-input').value : '10')
                    });
                });
            }

            if (btnAddSvgBadgeRule) {
                btnAddSvgBadgeRule.addEventListener('click', function(e) {
                    e.preventDefault();
                    syncSvgBadgeDataFromDOM();
                    svgBadgeData.push({
                        isFolded: false, type: 'global', categories: [], products: [],
                        text: '', iconType: 'svg', svgCode: '', image: '',
                        bgColor: '#f0f0f1', textColor: '#000000', iconHeightVal: '1.2', iconHeightUnit: 'em',
                        bgImage: '', width: '100', widthAuto: false, align: 'flex-start',
                        mt: '12', mb: '0', py: '5', px: '10'
                    });
                    renderSvgBadgeRows();
                });
            }

            if (containerSvgBadges) {
                renderSvgBadgeRows();
            }

            function renderTextBadgeRows() {
                if (typeof jQuery !== 'undefined' && jQuery.fn.selectWoo) {
                    jQuery(containerTextBadges).find('.wc-product-search').each(function() {
                        if(jQuery(this).hasClass('select2-hidden-accessible')) {
                            try { jQuery(this).selectWoo('destroy'); } catch(e) {}
                        }
                    });
                }
                
                containerTextBadges.innerHTML = '';
                
                textBadgeData.forEach((rule, index) => {
                    const row = document.createElement('div');
                    row.className = 'shav-repeater-row text-badge-row' + (rule.isFolded ? ' is-folded' : '');
                    
                    let isCustomColor = rule.color && !predefinedColors.includes(rule.color);
                    let selectValue = isCustomColor ? 'custom' : (rule.color || predefinedColors[0]);
                    let headerTitle = rule.text ? 'Etykieta: ' + rule.text : 'Reguła #' + (index + 1);

                    row.innerHTML = `
                        <h3 class="rule-header">${headerTitle}</h3>
                        <a href="#" class="shav-remove-btn shav-remove-row-text-badge" data-index="${index}">🗑 Usuń Regułę</a>
                        
                        <div class="shav-field-group" style="border:none; padding:0; margin-bottom:15px;">
                            <label class="shav-label">Zastosuj do:</label>
                            <select class="shav-input-text tb-type-select" data-index="${index}" style="max-width:300px;">
                                <option value="global" ${rule.type === 'global' ? 'selected' : ''}>Wszystkich Produktów (Globalnie)</option>
                                <option value="categories" ${rule.type === 'categories' ? 'selected' : ''}>Wybranych Kategorii</option>
                                <option value="products" ${rule.type === 'products' ? 'selected' : ''}>Konkretnych Produktów</option>
                            </select>
                        </div>
                        
                        <div class="shav-field-group target-container-categories" style="border:none; padding:0; margin-bottom:15px; ${rule.type === 'categories' ? '' : 'display:none;'}">
                            <label class="shav-label">Wybierz kategorie:</label>
                            <select class="shav-input-text tb-cats-select" multiple="multiple" data-index="${index}" style="max-width:600px; height:80px;">
                                ${wpCategories.map(cat => `<option value="${cat.id}" ${(rule.categories || []).includes(cat.id.toString()) ? 'selected' : ''}>${cat.name}</option>`).join('')}
                            </select>
                            <span class="shav-desc">Przytrzymaj CTRL/CMD aby wybrać wiele kategorii.</span>
                        </div>

                        <div class="shav-field-group target-container-products" style="border:none; padding:0; margin-bottom:15px; ${rule.type === 'products' ? '' : 'display:none;'}">
                            <label class="shav-label">Wyszukaj i wybierz produkty:</label>
                            <select class="wc-product-search tb-products-select" multiple="multiple" style="width: 100%; max-width:600px;" data-placeholder="Szukaj produktów..." data-action="woocommerce_json_search_products_and_variations" data-index="${index}">
                            </select>
                        </div>
                        
                        <div class="shav-field-group" style="border:none; padding:0; margin-bottom:15px;">
                            <label class="shav-label">Tekst Etykiety (np. Bestseller, Nowość)</label>
                            <input type="text" class="shav-input-text tb-text-input" data-index="${index}" value="${rule.text || ''}" placeholder="Bestseller">
                        </div>

                        <div class="shav-field-group" style="border:none; padding:0; margin-bottom:15px;">
                            <label class="shav-label">Kolor Tekstu</label>
                            <select class="shav-input-text tb-text-color-select" data-index="${index}" style="max-width:300px;">
                                <option value="#ffffff" ${rule.textColor === '#ffffff' ? 'selected' : ''}>Biały</option>
                                <option value="#000000" ${rule.textColor === '#000000' ? 'selected' : ''}>Czarny</option>
                            </select>
                        </div>

                        <div class="shav-field-group" style="border:none; padding:0; margin-bottom:0;">
                            <label class="shav-label">Kolor Tła (Kod CSS - Gradient lub Kolor)</label>
                            <div class="promo-swatches" data-index="${index}">
                                ${predefinedColors.map(color => `
                                    <div class="promo-swatch ${selectValue === color ? 'is-selected' : ''}" style="background: ${color};" data-color="${color}" title="${color}"></div>
                                `).join('')}
                                <div class="promo-swatch custom-trigger ${selectValue === 'custom' ? 'is-selected' : ''}" title="Własny CSS">+</div>
                            </div>
                            <div class="custom-css-input ${selectValue === 'custom' ? 'is-active' : ''}">
                                <input type="text" class="shav-input-text tb-color-input" data-index="${index}" value="${isCustomColor ? rule.color : (selectValue === 'custom' ? '' : selectValue)}" placeholder="Wpisz np. linear-gradient(to right, #ff0000, #00ff00)">
                            </div>
                        </div>
                    `;
                    containerTextBadges.appendChild(row);

                    if (rule.type === 'products' && Array.isArray(rule.products) && rule.products.length > 0) {
                        const selectElement = row.querySelector('.tb-products-select');
                        rule.products.forEach(prod => {
                            const option = new Option(prod.text, prod.id, true, true);
                            selectElement.append(option);
                        });
                    }
                });

                document.querySelectorAll('.shav-remove-row-text-badge').forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        if(confirm('Na pewno usunąć tę regułę?')) {
                            syncTextBadgeDataFromDOM();
                            let idx = parseInt(btn.getAttribute('data-index'));
                            if (!isNaN(idx)) {
                                textBadgeData.splice(idx, 1);
                                renderTextBadgeRows();
                            }
                        }
                    });
                });

                document.querySelectorAll('.tb-type-select').forEach(select => {
                    select.addEventListener('change', function(e) {
                        syncTextBadgeDataFromDOM();
                        let idx = parseInt(select.getAttribute('data-index'));
                        if (!isNaN(idx) && textBadgeData[idx]) {
                            textBadgeData[idx].type = this.value;
                            renderTextBadgeRows();
                        }
                    });
                });

                // Obsługa swatchy kolorów dla etykiet tekstowych
                containerTextBadges.querySelectorAll('.promo-swatches').forEach(swatches => {
                    swatches.addEventListener('click', function(e) {
                        const swatch = e.target.closest('.promo-swatch');
                        if (!swatch) return;
                        
                        const container = this.parentElement;
                        const inputField = container.querySelector('.tb-color-input');
                        const customInputBox = container.querySelector('.custom-css-input');
                        
                        this.querySelectorAll('.promo-swatch').forEach(s => s.classList.remove('is-selected'));
                        swatch.classList.add('is-selected');

                        if (swatch.classList.contains('custom-trigger')) {
                            customInputBox.classList.add('is-active');
                            inputField.value = '';
                        } else {
                            customInputBox.classList.remove('is-active');
                            inputField.value = swatch.dataset.color;
                        }
                    });
                });

                if (typeof jQuery !== 'undefined' && jQuery.fn.selectWoo) {
                    jQuery(containerTextBadges).find('.wc-product-search').each(function() {
                        var $select = jQuery(this);
                        $select.selectWoo({
                            minimumInputLength: 3,
                            allowClear: true,
                            ajax: {
                                url: ajaxurl,
                                dataType: 'json',
                                delay: 250,
                                data: function(params) {
                                    return {
                                        term: params.term,
                                        action: $select.data('action') || 'woocommerce_json_search_products_and_variations',
                                        security: typeof wc_enhanced_select_params !== 'undefined' ? wc_enhanced_select_params.search_products_nonce : ''
                                    };
                                },
                                processResults: function(data) {
                                    var terms = [];
                                    if (data) { jQuery.each(data, function(id, text) { terms.push({ id: id, text: text }); }); }
                                    return { results: terms };
                                },
                                cache: true
                            }
                        });
                    });
                }
            }

            function syncTextBadgeDataFromDOM() {
                const rows = document.querySelectorAll('.text-badge-row');
                textBadgeData = [];
                rows.forEach((row, idx) => {
                    const type = row.querySelector('.tb-type-select').value;
                    const text = row.querySelector('.tb-text-input').value;
                    const color = row.querySelector('.tb-color-input').value;
                    const textColor = row.querySelector('.tb-text-color-select').value;
                    const isFolded = row.classList.contains('is-folded');
                    
                    let categories = [];
                    let products = [];
                    
                    if (type === 'categories') {
                        const catSelect = row.querySelector('.tb-cats-select');
                        if (catSelect) {
                            Array.from(catSelect.selectedOptions).forEach(opt => categories.push(opt.value));
                        }
                    } else if (type === 'products') {
                        const prodSelect = row.querySelector('.tb-products-select');
                        if (typeof jQuery !== 'undefined' && jQuery(prodSelect).hasClass('select2-hidden-accessible')) {
                            const selectedData = jQuery(prodSelect).selectWoo('data');
                            if (Array.isArray(selectedData)) {
                                selectedData.forEach(item => {
                                    products.push({ id: item.id, text: item.text });
                                });
                            }
                        }
                    }

                    textBadgeData.push({ type, categories, products, text, color, textColor, isFolded });
                });
            }

            if(addBtnTextBadges) {
                addBtnTextBadges.addEventListener('click', function(e) {
                    e.preventDefault();
                    syncTextBadgeDataFromDOM();
                    textBadgeData.push({ type: 'global', categories: [], products: [], text: '', color: '', textColor: '#ffffff', isFolded: false });
                    renderTextBadgeRows();
                });
            }

            renderTextBadgeRows();

            // Dynamiczna aktualizacja nagłówków w locie
            form.addEventListener('input', function(e) {
                if (e.target.classList.contains('badge-text-input') || e.target.classList.contains('tb-text-input')) {
                    const row = e.target.closest('.shav-repeater-row');
                    if(row) {
                        const header = row.querySelector('.rule-header');
                        if(header) {
                            const val = e.target.value.trim();
                            const isTextBadge = row.classList.contains('text-badge-row');
                            const prefix = isTextBadge ? 'Etykieta: ' : 'Reguła: ';
                            header.textContent = val ? prefix + val : 'Reguła';
                        }
                    }
                }
                if (e.target.classList.contains('stock-text-input')) {
                    const row = e.target.closest('.shav-repeater-row');
                    if(row) {
                        const header = row.querySelector('.rule-header');
                        if(header) {
                            const val = e.target.value.trim();
                            header.textContent = val ? 'Pasek: ' + val : 'Reguła Paska';
                        }
                    }
                }
            });

            // Obsługa zwijania/rozwijania kafelków (folding)
            form.addEventListener('click', function(e) {
                if (e.target.classList.contains('rule-header')) {
                    const row = e.target.closest('.shav-repeater-row');
                    if (row) {
                        row.classList.toggle('is-folded');
                    }
                }
            });

            // Przed wysłaniem formularza serializuj oba JSON-y
            form.addEventListener('submit', function() {
                try { syncDataFromDOM(); } catch (e) { console.error("Error in syncDataFromDOM", e); }
                try { syncStockDataFromDOM(); } catch (e) { console.error("Error in syncStockDataFromDOM", e); }
                try { syncTextBadgeDataFromDOM(); } catch (e) { console.error("Error in syncTextBadgeDataFromDOM", e); }
                try { syncSvgBadgeDataFromDOM(); } catch (e) { console.error("Error in syncSvgBadgeDataFromDOM", e); }
                
                if (hiddenJsonInput) hiddenJsonInput.value = JSON.stringify(badgeData);
                if (hiddenJsonInputStock) hiddenJsonInputStock.value = btoa(unescape(encodeURIComponent(JSON.stringify(stockData))));
                if (hiddenJsonInputTextBadges) hiddenJsonInputTextBadges.value = JSON.stringify(textBadgeData);
                if (hiddenJsonInputSvgBadges) hiddenJsonInputSvgBadges.value = btoa(unescape(encodeURIComponent(JSON.stringify(svgBadgeData))));
            });
        });
    
