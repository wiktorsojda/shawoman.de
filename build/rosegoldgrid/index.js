/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/rosegoldgrid/edit.js"
/*!**********************************!*\
  !*** ./src/rosegoldgrid/edit.js ***!
  \**********************************/
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



const PRODUCTS = [1, 2, 3, 4];
function Edit({
  attributes,
  setAttributes
}) {
  const a = attributes;
  const blockProps = (0,_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.useBlockProps)({
    className: "rosegoldgrid"
  });
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    ...blockProps
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.InspectorControls, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
    title: "Ustawienia sekcji",
    initialOpen: true
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.ToggleControl, {
    label: "Poka\u017C przyciski koszyka",
    checked: a.showCart,
    onChange: v => setAttributes({
      showCart: v
    })
  })), PRODUCTS.map(n => (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
    key: n,
    title: `Produkt ${n}`,
    initialOpen: false
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.MediaUploadCheck, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.MediaUpload, {
    onSelect: m => setAttributes({
      [`prod${n}Image`]: m.url
    }),
    allowedTypes: ["image"],
    value: a[`prod${n}Image`],
    render: ({
      open
    }) => (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Button, {
      variant: "secondary",
      onClick: open
    }, a[`prod${n}Image`] ? "Zmień obraz" : "Wybierz obraz")
  })), a[`prod${n}Image`] && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Button, {
    variant: "link",
    isDestructive: true,
    onClick: () => setAttributes({
      [`prod${n}Image`]: ""
    })
  }, "Usu\u0144 (u\u017Cyj domy\u015Blnego)"), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    type: "number",
    label: "ID produktu WooCommerce",
    value: a[`prod${n}ProductId`] || "",
    onChange: v => setAttributes({
      [`prod${n}ProductId`]: parseInt(v || 0, 10)
    })
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    type: "number",
    label: "ID wariantu (produkt wielowariantowy)",
    help: "Dla produkt\xF3w z wariantami \u2014 ID konkretnego wariantu (edycja produktu \u2192 Warianty). Zostaw 0 dla prostych.",
    value: a[`prod${n}VariationId`] || "",
    onChange: v => setAttributes({
      [`prod${n}VariationId`]: parseInt(v || 0, 10)
    })
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    label: "W\u0142asny link obrazu (opcjonalnie)",
    value: a[`prod${n}LinkUrl`],
    onChange: v => setAttributes({
      [`prod${n}LinkUrl`]: v
    })
  })))), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("h2", {
    className: "rosegoldgrid__heading"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.RichText, {
    tagName: "span",
    value: a.heading1,
    onChange: v => setAttributes({
      heading1: v
    }),
    placeholder: "Zobacz produkty dropu "
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.RichText, {
    tagName: "span",
    className: "rosegoldgrid__heading-accent",
    value: a.heading2Accent,
    onChange: v => setAttributes({
      heading2Accent: v
    }),
    placeholder: "Rose Gold"
  })), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "rosegoldgrid__grid"
  }, PRODUCTS.map(n => (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "rosegoldgrid__card",
    key: n
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.RichText, {
    tagName: "span",
    className: "rosegoldgrid__badge",
    value: a[`prod${n}Badge`],
    onChange: v => setAttributes({
      [`prod${n}Badge`]: v
    }),
    placeholder: "Badge"
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "rosegoldgrid__media"
  }, a[`prod${n}Image`] ? (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("img", {
    src: a[`prod${n}Image`],
    alt: ""
  }) : (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "rosegoldgrid__media-placeholder"
  }, "Obraz ", n), a.showCart && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "rosegoldgrid__cart",
    "aria-hidden": "true"
  }, "\uD83D\uDED2")), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "rosegoldgrid__info"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "rosegoldgrid__text"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", {
    className: "rosegoldgrid__title"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.RichText, {
    tagName: "span",
    value: a[`prod${n}Title`],
    onChange: v => setAttributes({
      [`prod${n}Title`]: v
    }),
    placeholder: "Nazwa produktu "
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.RichText, {
    tagName: "span",
    className: "rosegoldgrid__brand",
    value: a[`prod${n}Brand`],
    onChange: v => setAttributes({
      [`prod${n}Brand`]: v
    }),
    placeholder: "Marka"
  })), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.RichText, {
    tagName: "p",
    className: "rosegoldgrid__sub",
    value: a[`prod${n}Sub`],
    onChange: v => setAttributes({
      [`prod${n}Sub`]: v
    }),
    placeholder: "Podtytu\u0142"
  })), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "rosegoldgrid__price"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.RichText, {
    tagName: "span",
    className: "rosegoldgrid__price-old",
    value: a[`prod${n}OldPrice`],
    onChange: v => setAttributes({
      [`prod${n}OldPrice`]: v
    }),
    placeholder: "Stara cena (opcj.)"
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.RichText, {
    tagName: "span",
    className: "rosegoldgrid__price-new",
    value: a[`prod${n}NewPrice`],
    onChange: v => setAttributes({
      [`prod${n}NewPrice`]: v
    }),
    placeholder: "Cena"
  })), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.RichText, {
    tagName: "span",
    className: "rosegoldgrid__bottom-badge",
    value: a[`prod${n}BottomBadge`],
    onChange: v => setAttributes({
      [`prod${n}BottomBadge`]: v
    }),
    placeholder: "Dolny badge (opcj.)"
  }))))));
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

/***/ "./src/rosegoldgrid/block.json"
/*!*************************************!*\
  !*** ./src/rosegoldgrid/block.json ***!
  \*************************************/
(module) {

module.exports = /*#__PURE__*/JSON.parse('{"$schema":"https://schemas.wp.org/trunk/block.json","apiVersion":3,"name":"ourblocktheme/rosegoldgrid","title":"Drop — Siatka produktów","category":"theme","icon":"grid-view","description":"Nagłówek + siatka 4 kart produktów. Każda karta: badge, obraz (link do produktu), przycisk koszyka (dodaje do koszyka) i ceny. Pola puste są pomijane.","attributes":{"heading1":{"type":"string","default":"Zobacz produkty dropu "},"heading2Accent":{"type":"string","default":"Rose Gold"},"showCart":{"type":"boolean","default":true},"prod1Image":{"type":"string","default":""},"prod1Badge":{"type":"string","default":"EDYCJA LIMITOWANA"},"prod1Title":{"type":"string","default":"Golarka damska "},"prod1Brand":{"type":"string","default":"Shav Woman"},"prod1Sub":{"type":"string","default":"Maszynka do golenia bikinii i całego ciała"},"prod1OldPrice":{"type":"string","default":""},"prod1NewPrice":{"type":"string","default":"229,00 zł"},"prod1BottomBadge":{"type":"string","default":""},"prod1ProductId":{"type":"number","default":0},"prod1VariationId":{"type":"number","default":0},"prod1LinkUrl":{"type":"string","default":""},"prod2Image":{"type":"string","default":""},"prod2Badge":{"type":"string","default":"EDYCJA LIMITOWANA"},"prod2Title":{"type":"string","default":"Golarka damska "},"prod2Brand":{"type":"string","default":"Shav Woman"},"prod2Sub":{"type":"string","default":"Maszynka do golenia bikinii i całego ciała"},"prod2OldPrice":{"type":"string","default":""},"prod2NewPrice":{"type":"string","default":"229,00 zł"},"prod2BottomBadge":{"type":"string","default":""},"prod2ProductId":{"type":"number","default":0},"prod2VariationId":{"type":"number","default":0},"prod2LinkUrl":{"type":"string","default":""},"prod3Image":{"type":"string","default":""},"prod3Badge":{"type":"string","default":"EDYCJA LIMITOWANA"},"prod3Title":{"type":"string","default":"Zestaw ostrzy foliowych bazowych"},"prod3Brand":{"type":"string","default":""},"prod3Sub":{"type":"string","default":"Zestaw trzech ostrzy w obniżonej cenie"},"prod3OldPrice":{"type":"string","default":""},"prod3NewPrice":{"type":"string","default":"99,00 zł"},"prod3BottomBadge":{"type":"string","default":""},"prod3ProductId":{"type":"number","default":0},"prod3VariationId":{"type":"number","default":0},"prod3LinkUrl":{"type":"string","default":""},"prod4Image":{"type":"string","default":""},"prod4Badge":{"type":"string","default":"EDYCJA LIMITOWANA"},"prod4Title":{"type":"string","default":"Zestaw ostrzy foliowych okrągłych"},"prod4Brand":{"type":"string","default":""},"prod4Sub":{"type":"string","default":"Zestaw trzech ostrzy w obniżonej cenie"},"prod4OldPrice":{"type":"string","default":""},"prod4NewPrice":{"type":"string","default":"99,00 zł"},"prod4BottomBadge":{"type":"string","default":""},"prod4ProductId":{"type":"number","default":0},"prod4VariationId":{"type":"number","default":0},"prod4LinkUrl":{"type":"string","default":""}},"editorScript":"file:./index.js","render":"file:./render.php","supports":{"html":false,"anchor":true,"align":["wide","full"],"spacing":{"margin":true,"padding":true}}}');

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
/*!***********************************!*\
  !*** ./src/rosegoldgrid/index.js ***!
  \***********************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/blocks */ "@wordpress/blocks");
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _block_json__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./block.json */ "./src/rosegoldgrid/block.json");
/* harmony import */ var _edit__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./edit */ "./src/rosegoldgrid/edit.js");





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