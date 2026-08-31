/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/shav-product-grid/edit.js"
/*!***************************************!*\
  !*** ./src/shav-product-grid/edit.js ***!
  \***************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ Edit)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__);





function Edit({
  attributes,
  setAttributes
}) {
  const {
    mainTitle,
    subTitle,
    selectionType,
    categoryId,
    productIds,
    customCategoryOrder,
    orderBy,
    limit,
    productGradients,
    globalSavingsPill,
    productSavingsPills
  } = attributes;
  const blockProps = (0,_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.useBlockProps)();
  const [selectedProductId, setSelectedProductId] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_4__.useState)(null);
  const predefinedGradients = {
    zloty: 'linear-gradient(177.46deg, #d28f33 23.78%, #c17627 9.03%, #f3ceaa 51.08%, #c17627 87.59%, #c99b70 132.32%, #b06b23 158.8%)',
    srebrny: 'linear-gradient(177.46deg, #999999 23.78%, #b2b2b2 35%, #f5f5f5 50%, #cccccc 75%, #999999 95%, #f5f5f5 110%)',
    platynowy: 'linear-gradient(177.46deg, #3a3a3a 23.78%, #606060 35%, #8f8f8f 50%, #cecece 75%, #888888 95%, #464646 110%)',
    rosegold: 'linear-gradient(105deg, #e9c1b9 0%, #bc8d80 26%, #8d6154 62%, #a6776a 84%, #cf9f8f 100%)'
  };
  const gradientOptions = [{
    label: 'Domyślny / Brak',
    value: 'none',
    background: '#e0e0e0'
  }, {
    label: 'Złoty',
    value: 'zloty',
    background: predefinedGradients.zloty
  }, {
    label: 'Srebrny',
    value: 'srebrny',
    background: predefinedGradients.srebrny
  }, {
    label: 'Platynowy',
    value: 'platynowy',
    background: predefinedGradients.platynowy
  }, {
    label: 'Rose Gold',
    value: 'rosegold',
    background: predefinedGradients.rosegold
  }, {
    label: 'Własny CSS',
    value: 'custom',
    background: 'repeating-linear-gradient(45deg, #eee, #eee 10px, #ddd 10px, #ddd 20px)'
  }];
  const categories = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_3__.useSelect)(select => {
    return select("core").getEntityRecords("taxonomy", "product_cat", {
      per_page: -1
    });
  }, []);
  const products = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_3__.useSelect)(select => {
    // Fetch all published products with _embed to get images
    return select("core").getEntityRecords("postType", "product", {
      per_page: 100,
      status: "publish",
      _embed: true
    });
  }, []);
  const categoryOptions = [{
    label: "Wszystkie kategorie",
    value: ""
  }, ...(categories || []).map(cat => ({
    label: cat.name,
    value: cat.slug
  }))];
  const productSuggestions = (products || []).map(p => p.title.rendered);
  const handleProductChange = selectedTitles => {
    const newIds = selectedTitles.map(title => {
      const product = products.find(p => p.title.rendered === title);
      return product ? product.id : null;
    }).filter(id => id !== null);
    setAttributes({
      productIds: newIds
    });
  };
  const selectedProductTitles = (productIds || []).map(id => {
    const product = (products || []).find(p => p.id === id);
    return product ? product.title.rendered : "";
  }).filter(title => title !== "");

  // Prepare preview products
  let previewProducts = [];
  if (selectionType === 'manual') {
    previewProducts = (productIds || []).map(id => {
      return (products || []).find(p => p.id === id);
    }).filter(Boolean);
  } else {
    // For category view
    if (orderBy === 'menu_order' && customCategoryOrder && customCategoryOrder.length > 0) {
      previewProducts = customCategoryOrder.map(id => {
        return (products || []).find(p => p.id === id);
      }).filter(Boolean);
    } else {
      const selectedCategory = (categories || []).find(cat => cat.slug === categoryId);
      const selectedCategoryId = selectedCategory ? selectedCategory.id : null;
      previewProducts = (products || []).filter(p => {
        if (!selectedCategoryId) return true;
        return p.categories && p.categories.includes(selectedCategoryId);
      }).slice(0, limit);

      // Fallback for WooCommerce products REST response if 'categories' isn't natively populated but 'product_cat' might be.
      if (previewProducts.length === 0 && selectedCategoryId) {
        previewProducts = (products || []).filter(p => {
          // Czasami REST dla produktów WC przetrzymuje kategorie w innej strukturze.
          // Spróbujmy znaleźć cokolwiek:
          if (p.product_cat && p.product_cat.includes(selectedCategoryId)) return true;
          return false; // W najgorszym razie podgląd nie pofiltruje dokładnie
        }).slice(0, limit);
      }

      // Jeżeli wciąż pusty (brak filtru), weź po prostu produkty.
      if (previewProducts.length === 0 && (products || []).length > 0) {
        previewProducts = (products || []).slice(0, limit);
      }
    }
  }
  const handleMoveButton = (e, index, direction) => {
    e.stopPropagation();
    e.preventDefault();
    let newIds = [];
    if (selectionType === 'manual') {
      newIds = [...productIds];
    } else {
      newIds = customCategoryOrder && customCategoryOrder.length > 0 ? [...customCategoryOrder] : previewProducts.map(p => p.id);
    }
    if (direction === 'left' && index > 0) {
      const temp = newIds[index - 1];
      newIds[index - 1] = newIds[index];
      newIds[index] = temp;
    } else if (direction === 'right' && index < newIds.length - 1) {
      const temp = newIds[index + 1];
      newIds[index + 1] = newIds[index];
      newIds[index] = temp;
    }
    if (selectionType === 'manual') {
      setAttributes({
        productIds: newIds
      });
    } else {
      setAttributes({
        customCategoryOrder: newIds
      });
    }
  };
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    ...blockProps
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.InspectorControls, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
    title: "Ustawienia Siatki Produkt\xF3w",
    initialOpen: true
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    label: "Tytu\u0142 g\u0142\xF3wny",
    value: mainTitle,
    onChange: val => setAttributes({
      mainTitle: val
    })
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    label: "Podtytu\u0142 (Szary, marka)",
    value: subTitle,
    onChange: val => setAttributes({
      subTitle: val
    })
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.RadioControl, {
    label: "Wybierz \u017Ar\xF3d\u0142o produkt\xF3w",
    selected: selectionType,
    options: [{
      label: 'Kategoria',
      value: 'category'
    }, {
      label: 'Pojedyncze produkty (Ręcznie)',
      value: 'manual'
    }],
    onChange: val => setAttributes({
      selectionType: val
    })
  }), selectionType === 'category' && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.SelectControl, {
    label: "Kategoria Produkt\xF3w",
    value: categoryId,
    options: categoryOptions,
    onChange: val => setAttributes({
      categoryId: val,
      customCategoryOrder: []
    })
  }), selectionType === 'manual' && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    style: {
      marginBottom: "20px"
    }
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.FormTokenField, {
    label: "Szukaj i dodaj produkty",
    value: selectedProductTitles,
    suggestions: productSuggestions,
    onChange: handleProductChange,
    __experimentalExpandOnFocus: true
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", {
    style: {
      fontSize: "12px",
      color: "#666"
    }
  }, "Mo\u017Cesz zmienia\u0107 kolejno\u015B\u0107 \u0142api\u0105c i przeci\u0105gaj\u0105c produkty w podgl\u0105dzie bloku!")), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.SelectControl, {
    label: "Sortuj wed\u0142ug",
    value: orderBy,
    options: [{
      label: 'Ręcznie / Kolejność w Menu',
      value: 'menu_order'
    }, {
      label: 'Po dacie (Najnowsze)',
      value: 'date'
    }, {
      label: 'Po nazwie (A-Z)',
      value: 'title'
    }, {
      label: 'Po popularności (Bestsellery)',
      value: 'popularity'
    }],
    onChange: val => setAttributes({
      orderBy: val
    })
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.RangeControl, {
    label: "Limit produkt\xF3w do pokazania",
    value: limit,
    onChange: val => setAttributes({
      limit: val
    }),
    min: 1,
    max: 24
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.ToggleControl, {
    label: "W\u0142\u0105cz pill 'Oszcz\u0119dzasz' dla ca\u0142ej siatki (je\u015Bli w promocji)",
    checked: globalSavingsPill,
    onChange: val => setAttributes({
      globalSavingsPill: val
    })
  })), selectedProductId && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
    title: "Opcje zaznaczonego produktu",
    initialOpen: true
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", {
    style: {
      fontSize: '13px',
      color: '#666',
      marginBottom: '15px'
    }
  }, "Wybrany produkt ID: ", (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("strong", null, selectedProductId)), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    style: {
      marginBottom: '20px'
    }
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("label", {
    style: {
      display: 'block',
      fontSize: '13px',
      fontWeight: 500,
      marginBottom: '10px'
    }
  }, "Wizualny wyb\xF3r gradientu u g\xF3ry karty"), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    style: {
      display: 'grid',
      gridTemplateColumns: 'repeat(3, 1fr)',
      gap: '10px'
    }
  }, gradientOptions.map(opt => {
    const currentVal = (productGradients || {})[selectedProductId]?.type || 'none';
    const isSelected = currentVal === opt.value || currentVal === '' && opt.value === 'none';
    return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      key: opt.value,
      onClick: () => {
        const newGradients = {
          ...(productGradients || {})
        };
        if (opt.value === 'none') {
          delete newGradients[selectedProductId];
        } else {
          newGradients[selectedProductId] = {
            type: opt.value,
            customValue: newGradients[selectedProductId]?.customValue || ''
          };
        }
        setAttributes({
          productGradients: newGradients
        });
      },
      style: {
        cursor: 'pointer',
        border: isSelected ? '2px solid #007cba' : '2px solid transparent',
        borderRadius: '8px',
        padding: '4px',
        textAlign: 'center',
        transition: 'all 0.2s',
        backgroundColor: isSelected ? '#f0f8ff' : '#fff'
      }
    }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      style: {
        height: '24px',
        borderRadius: '4px',
        background: opt.background,
        marginBottom: '6px',
        boxShadow: 'inset 0 0 0 1px rgba(0,0,0,0.1)'
      }
    }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
      style: {
        fontSize: '11px',
        display: 'block',
        lineHeight: 1.2
      }
    }, opt.label));
  }))), (productGradients || {})[selectedProductId]?.type === 'custom' && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    label: "W\u0142asny kod CSS gradientu",
    help: "Np. linear-gradient(90deg, red, blue)",
    value: (productGradients || {})[selectedProductId]?.customValue || '',
    onChange: val => {
      const newGradients = {
        ...(productGradients || {})
      };
      if (newGradients[selectedProductId]) {
        newGradients[selectedProductId].customValue = val;
        setAttributes({
          productGradients: newGradients
        });
      }
    }
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.ToggleControl, {
    label: "W\u0142\u0105cz pill 'Oszcz\u0119dzasz' dla tego produktu (je\u015Bli w promocji)",
    checked: !!(productSavingsPills || {})[selectedProductId],
    onChange: val => {
      const newPills = {
        ...(productSavingsPills || {})
      };
      newPills[selectedProductId] = val;
      setAttributes({
        productSavingsPills: newPills
      });
    }
  }))), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    style: {
      padding: "30px",
      border: "1px solid #e0e0e0",
      borderRadius: "10px",
      backgroundColor: "#fff",
      boxShadow: "inset 0 0 20px rgba(0,0,0,0.02)"
    }
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("h3", {
    style: {
      margin: "0 0 20px 0",
      fontSize: "24px"
    }
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    style: {
      color: "#111",
      fontWeight: "800"
    }
  }, mainTitle, " "), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    style: {
      color: "#888",
      fontWeight: "400"
    }
  }, subTitle)), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    style: {
      display: "grid",
      gridTemplateColumns: "repeat(auto-fill, minmax(200px, 1fr))",
      gap: "20px"
    }
  }, previewProducts.length === 0 ? (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", {
    style: {
      gridColumn: "1 / -1",
      color: "#999",
      textAlign: "center",
      padding: "40px"
    }
  }, products === null ? "Ładowanie produktów..." : "Brak produktów do wyświetlenia. Dodaj produkty w panelu bocznym.") : previewProducts.map((product, index) => {
    const isManual = orderBy === 'menu_order';
    const imageUrl = product._embedded?.['wp:featuredmedia']?.[0]?.source_url || "";
    const prodGrad = (productGradients || {})[product.id];
    let activeGradientCSS = null;
    if (prodGrad && prodGrad.type !== 'none') {
      if (prodGrad.type === 'custom' && prodGrad.customValue) {
        activeGradientCSS = prodGrad.customValue;
      } else if (predefinedGradients[prodGrad.type]) {
        activeGradientCSS = predefinedGradients[prodGrad.type];
      }
    }
    return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      key: product.id + '-' + index,
      onClick: () => setSelectedProductId(product.id),
      style: {
        backgroundColor: "#fff",
        borderRadius: "12px",
        overflow: "hidden",
        boxShadow: "0 4px 10px rgba(0,0,0,0.05)",
        border: selectedProductId === product.id ? "2px solid #007cba" : "2px solid transparent",
        transition: "all 0.2s ease",
        display: "flex",
        flexDirection: "column",
        height: "100%",
        cursor: "pointer",
        position: "relative"
      }
    }, activeGradientCSS && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      style: {
        position: "absolute",
        top: 0,
        left: 0,
        right: 0,
        height: "16px",
        background: activeGradientCSS,
        zIndex: 2
      }
    }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      style: {
        height: "200px",
        backgroundColor: "#f5f5f5",
        backgroundImage: imageUrl ? `url(${imageUrl})` : "none",
        backgroundSize: "cover",
        backgroundPosition: "center",
        display: "flex",
        alignItems: "center",
        justifyContent: "center",
        flexShrink: 0
      }
    }, !imageUrl && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
      style: {
        color: "#aaa"
      }
    }, "Brak zdj\u0119cia")), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      style: {
        padding: "15px",
        fontSize: "14px",
        fontWeight: "600",
        textAlign: "center",
        color: "#333",
        lineHeight: "1.3",
        flexGrow: 1
      },
      dangerouslySetInnerHTML: {
        __html: product.title.rendered
      }
    }), isManual && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      style: {
        display: "flex",
        justifyContent: "space-between",
        padding: "10px",
        backgroundColor: "#f9f9f9",
        borderTop: "1px solid #eee",
        marginTop: "auto"
      }
    }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
      onClick: e => handleMoveButton(e, index, 'left'),
      disabled: index === 0,
      style: {
        cursor: index === 0 ? "not-allowed" : "pointer",
        padding: "5px 10px",
        border: "1px solid #ccc",
        borderRadius: "4px",
        background: "#fff",
        opacity: index === 0 ? 0.3 : 1
      },
      title: "Przesu\u0144 w lewo"
    }, "\u25C0"), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
      onClick: e => handleMoveButton(e, index, 'right'),
      disabled: index === previewProducts.length - 1,
      style: {
        cursor: index === previewProducts.length - 1 ? "not-allowed" : "pointer",
        padding: "5px 10px",
        border: "1px solid #ccc",
        borderRadius: "4px",
        background: "#fff",
        opacity: index === previewProducts.length - 1 ? 0.3 : 1
      },
      title: "Przesu\u0144 w prawo"
    }, "\u25B6")));
  })), orderBy === 'menu_order' && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    style: {
      textAlign: "center",
      marginTop: "25px",
      padding: "10px",
      backgroundColor: "#f0f8ff",
      borderRadius: "8px",
      color: "#007cba",
      fontSize: "14px",
      fontWeight: "bold"
    }
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    style: {
      marginRight: "8px"
    }
  }, "\uD83D\uDC46"), "U\u017Cyj strza\u0142ek na kafelkach, aby zmieni\u0107 ich kolejno\u015B\u0107")));
}

/***/ },

/***/ "react"
/*!************************!*\
  !*** external "React" ***!
  \************************/
(module) {

module.exports = window["React"];

/***/ },

/***/ "@wordpress/block-editor"
/*!*************************************!*\
  !*** external ["wp","blockEditor"] ***!
  \*************************************/
(module) {

module.exports = window["wp"]["blockEditor"];

/***/ },

/***/ "@wordpress/blocks"
/*!********************************!*\
  !*** external ["wp","blocks"] ***!
  \********************************/
(module) {

module.exports = window["wp"]["blocks"];

/***/ },

/***/ "@wordpress/components"
/*!************************************!*\
  !*** external ["wp","components"] ***!
  \************************************/
(module) {

module.exports = window["wp"]["components"];

/***/ },

/***/ "@wordpress/data"
/*!******************************!*\
  !*** external ["wp","data"] ***!
  \******************************/
(module) {

module.exports = window["wp"]["data"];

/***/ },

/***/ "@wordpress/element"
/*!*********************************!*\
  !*** external ["wp","element"] ***!
  \*********************************/
(module) {

module.exports = window["wp"]["element"];

/***/ },

/***/ "./src/shav-product-grid/block.json"
/*!******************************************!*\
  !*** ./src/shav-product-grid/block.json ***!
  \******************************************/
(module) {

module.exports = /*#__PURE__*/JSON.parse('{"$schema":"https://schemas.wp.org/trunk/block.json","apiVersion":2,"name":"ourblocktheme/shav-product-grid","title":"Siatka Produktów Shav","category":"theme","icon":"grid-view","description":"Niestandardowa siatka produktów z zachowaniem stylów motywu.","attributes":{"mainTitle":{"type":"string","default":"Urządzenia."},"subTitle":{"type":"string","default":"Shav woman"},"selectionType":{"type":"string","default":"category"},"categoryId":{"type":"string","default":""},"productIds":{"type":"array","default":[]},"customCategoryOrder":{"type":"array","default":[]},"orderBy":{"type":"string","default":"menu_order"},"limit":{"type":"number","default":12},"productGradients":{"type":"object","default":{}},"globalSavingsPill":{"type":"boolean","default":false},"productSavingsPills":{"type":"object","default":{}}},"textdomain":"shav","editorScript":"file:./index.js","render":"file:./render.php"}');

/***/ }

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		if (!(moduleId in __webpack_modules__)) {
/******/ 			delete __webpack_module_cache__[moduleId];
/******/ 			var e = new Error("Cannot find module '" + moduleId + "'");
/******/ 			e.code = 'MODULE_NOT_FOUND';
/******/ 			throw e;
/******/ 		}
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry needs to be wrapped in an IIFE because it needs to be isolated against other modules in the chunk.
(() => {
/*!****************************************!*\
  !*** ./src/shav-product-grid/index.js ***!
  \****************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/blocks */ "@wordpress/blocks");
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _block_json__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./block.json */ "./src/shav-product-grid/block.json");
/* harmony import */ var _edit__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./edit */ "./src/shav-product-grid/edit.js");





(0,_wordpress_blocks__WEBPACK_IMPORTED_MODULE_1__.registerBlockType)(_block_json__WEBPACK_IMPORTED_MODULE_3__.name, {
  edit: _edit__WEBPACK_IMPORTED_MODULE_4__["default"],
  save: () => {
    const blockProps = _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2__.useBlockProps.save();
    return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      ...blockProps
    });
  }
});
})();

/******/ })()
;
//# sourceMappingURL=index.js.map