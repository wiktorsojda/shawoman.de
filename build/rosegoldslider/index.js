/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/rosegoldslider/edit.js"
/*!************************************!*\
  !*** ./src/rosegoldslider/edit.js ***!
  \************************************/
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



const SLIDES = [1, 2, 3, 4, 5];
function Edit({
  attributes,
  setAttributes
}) {
  const a = attributes;
  const blockProps = (0,_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.useBlockProps)({
    className: "rosegoldslider"
  });
  const filled = SLIDES.filter(n => a[`slide${n}Image`]);
  const preview = filled.length ? filled : [1, 2, 3];
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    ...blockProps
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.InspectorControls, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
    title: "Ustawienia karuzeli",
    initialOpen: true
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.ToggleControl, {
    label: "Auto-przewijanie",
    checked: a.autoScroll,
    onChange: v => setAttributes({
      autoScroll: v
    })
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.RangeControl, {
    label: "Czas przewijania (ms)",
    min: 2000,
    max: 12000,
    step: 500,
    value: a.autoScrollMs,
    onChange: v => setAttributes({
      autoScrollMs: v
    })
  })), SLIDES.map(n => (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
    key: n,
    title: `Slajd ${n}`,
    initialOpen: false
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.MediaUploadCheck, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.MediaUpload, {
    onSelect: m => setAttributes({
      [`slide${n}Image`]: m.url,
      [`slide${n}Alt`]: m.alt || a[`slide${n}Alt`]
    }),
    allowedTypes: ["image"],
    value: a[`slide${n}Image`],
    render: ({
      open
    }) => (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Button, {
      variant: "secondary",
      onClick: open
    }, a[`slide${n}Image`] ? "Zmień obraz" : "Wybierz obraz")
  })), a[`slide${n}Image`] && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Button, {
    variant: "link",
    isDestructive: true,
    onClick: () => setAttributes({
      [`slide${n}Image`]: ""
    })
  }, "Usu\u0144 slajd"), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    style: {
      borderTop: "1px solid #eee",
      paddingTop: 8,
      marginTop: 8
    }
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", {
    style: {
      margin: "0 0 4px",
      fontSize: 11,
      fontWeight: 600
    }
  }, "Obraz mobilny (opcjonalnie)"), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.MediaUploadCheck, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.MediaUpload, {
    onSelect: m => setAttributes({
      [`slide${n}ImageMobile`]: m.url
    }),
    allowedTypes: ["image"],
    value: a[`slide${n}ImageMobile`],
    render: ({
      open
    }) => (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Button, {
      variant: "secondary",
      onClick: open
    }, a[`slide${n}ImageMobile`] ? "Zmień mobilny" : "Wybierz mobilny")
  })), a[`slide${n}ImageMobile`] && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Button, {
    variant: "link",
    isDestructive: true,
    onClick: () => setAttributes({
      [`slide${n}ImageMobile`]: ""
    })
  }, "Usu\u0144 mobilny")), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    label: "Link (opcjonalnie)",
    value: a[`slide${n}Url`],
    onChange: v => setAttributes({
      [`slide${n}Url`]: v
    })
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    label: "Alt",
    value: a[`slide${n}Alt`],
    onChange: v => setAttributes({
      [`slide${n}Alt`]: v
    })
  })))), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "rosegoldslider__viewport"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "rosegoldslider__track"
  }, preview.map((n, i) => (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: `rosegoldslider__slide${i === 0 ? " is-active" : ""}`,
    key: n
  }, a[`slide${n}Image`] ? (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("img", {
    src: a[`slide${n}Image`],
    alt: a[`slide${n}Alt`] || ""
  }) : (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "rosegoldslider__placeholder"
  }, "Slajd ", n, " \u2014 wybierz obraz w panelu"))))), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "rosegoldslider__dots"
  }, preview.map((n, i) => (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    key: n,
    className: `rosegoldslider__dot${i === 0 ? " is-active" : ""}`
  }))));
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

/***/ "./src/rosegoldslider/block.json"
/*!***************************************!*\
  !*** ./src/rosegoldslider/block.json ***!
  \***************************************/
(module) {

module.exports = /*#__PURE__*/JSON.parse('{"$schema":"https://schemas.wp.org/trunk/block.json","apiVersion":3,"name":"ourblocktheme/rosegoldslider","title":"Drop — Slider banerów","category":"theme","icon":"images-alt2","description":"Karuzela banerów w stylu peek (aktywny slajd wyśrodkowany, sąsiednie przygaszone). Kropki, przeciąganie i auto-przewijanie. Do 5 slajdów, każdy z opcjonalnym linkiem.","attributes":{"autoScroll":{"type":"boolean","default":true},"autoScrollMs":{"type":"number","default":5000},"slide1Image":{"type":"string","default":""},"slide1Url":{"type":"string","default":""},"slide1Alt":{"type":"string","default":""},"slide2Image":{"type":"string","default":""},"slide2Url":{"type":"string","default":""},"slide2Alt":{"type":"string","default":""},"slide3Image":{"type":"string","default":""},"slide3Url":{"type":"string","default":""},"slide3Alt":{"type":"string","default":""},"slide4Image":{"type":"string","default":""},"slide4Url":{"type":"string","default":""},"slide4Alt":{"type":"string","default":""},"slide5Image":{"type":"string","default":""},"slide5Url":{"type":"string","default":""},"slide5Alt":{"type":"string","default":""},"slide1ImageMobile":{"type":"string","default":""},"slide2ImageMobile":{"type":"string","default":""},"slide3ImageMobile":{"type":"string","default":""},"slide4ImageMobile":{"type":"string","default":""},"slide5ImageMobile":{"type":"string","default":""}},"editorScript":"file:./index.js","render":"file:./render.php","supports":{"html":false,"anchor":true,"align":["full"],"spacing":{"margin":true}}}');

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
/*!*************************************!*\
  !*** ./src/rosegoldslider/index.js ***!
  \*************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/blocks */ "@wordpress/blocks");
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _block_json__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./block.json */ "./src/rosegoldslider/block.json");
/* harmony import */ var _edit__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./edit */ "./src/rosegoldslider/edit.js");





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