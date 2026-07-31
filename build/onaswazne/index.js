/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/onaswazne/edit.js"
/*!*******************************!*\
  !*** ./src/onaswazne/edit.js ***!
  \*******************************/
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



function Edit({
  attributes,
  setAttributes
}) {
  const a = attributes;
  const blockProps = (0,_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.useBlockProps)({
    className: "onaswazne",
    style: {
      "--title-size-desktop": `${a.titleSizeDesktop}px`,
      "--title-size-mobile": `${a.titleSizeMobile}px`,
      "--description-size-desktop": `${a.descriptionSizeDesktop}px`,
      "--description-size-mobile": `${a.descriptionSizeMobile}px`,
      "--button-size": `${a.buttonSize}px`
    }
  });
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("section", {
    ...blockProps
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.InspectorControls, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
    title: "Lewa karta \u2014 logo (np. Rak'n Roll)",
    initialOpen: true
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", {
    style: {
      fontSize: 12,
      color: "#666",
      marginTop: 0
    }
  }, "Wgraj obraz (PNG/JPG/SVG) ALBO wklej kod SVG poni\u017Cej (SVG nadpisuje obraz)."), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.MediaUploadCheck, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.MediaUpload, {
    onSelect: m => setAttributes({
      leftLogoImage: m.url
    }),
    allowedTypes: ["image"],
    value: a.leftLogoImage,
    render: ({
      open
    }) => (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Button, {
      variant: "secondary",
      onClick: open
    }, a.leftLogoImage ? "Zmień logo" : "Wybierz logo")
  })), a.leftLogoImage && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Button, {
    variant: "link",
    isDestructive: true,
    onClick: () => setAttributes({
      leftLogoImage: ""
    }),
    style: {
      marginTop: 8
    }
  }, "Usu\u0144 obraz"), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextareaControl, {
    label: "\u2026lub wklej kod SVG",
    value: a.leftLogoSvg,
    onChange: v => setAttributes({
      leftLogoSvg: v
    }),
    help: "Wklej zawarto\u015B\u0107 pliku .svg (rozpoczynaj\u0105c\u0105 si\u0119 od <svg>).",
    rows: 6
  })), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
    title: "Lewa karta \u2014 drugie logo (opcjonalne)",
    initialOpen: true
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", {
    style: {
      fontSize: 12,
      color: "#666",
      marginTop: 0
    }
  }, "Z drugim logiem okr\u0105g zamienia si\u0119 w zaokr\u0105glon\u0105 kart\u0119 z dwoma logami obok siebie."), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.MediaUploadCheck, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.MediaUpload, {
    onSelect: m => setAttributes({
      leftLogoImage2: m.url
    }),
    allowedTypes: ["image"],
    value: a.leftLogoImage2,
    render: ({
      open
    }) => (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Button, {
      variant: "secondary",
      onClick: open
    }, a.leftLogoImage2 ? "Zmień logo 2" : "Wybierz logo 2")
  })), a.leftLogoImage2 && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Button, {
    variant: "link",
    isDestructive: true,
    onClick: () => setAttributes({
      leftLogoImage2: ""
    }),
    style: {
      marginTop: 8
    }
  }, "Usu\u0144 logo 2")), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
    title: "Prawa karta \u2014 przycisk + dekoracja (sylwetka)",
    initialOpen: false
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    label: "Tekst przycisku",
    value: a.rightButtonText,
    onChange: v => setAttributes({
      rightButtonText: v
    })
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    label: "URL przycisku",
    value: a.rightButtonUrl,
    onChange: v => setAttributes({
      rightButtonUrl: v
    }),
    type: "url"
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", {
    style: {
      fontSize: 12,
      color: "#666",
      marginTop: 16
    }
  }, "Dekoracja: wgraj obraz (PNG/SVG) lub wklej kod SVG poni\u017Cej."), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.MediaUploadCheck, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.MediaUpload, {
    onSelect: m => setAttributes({
      rightDecoration: m.url
    }),
    allowedTypes: ["image"],
    value: a.rightDecoration,
    render: ({
      open
    }) => (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Button, {
      variant: "secondary",
      onClick: open,
      style: {
        marginTop: 8
      }
    }, a.rightDecoration ? "Zmień dekorację" : "Wybierz dekorację (sylwetka)")
  })), a.rightDecoration && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Button, {
    variant: "link",
    isDestructive: true,
    onClick: () => setAttributes({
      rightDecoration: ""
    }),
    style: {
      marginTop: 8
    }
  }, "Usu\u0144 obraz"), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextareaControl, {
    label: "\u2026lub wklej kod SVG dekoracji",
    value: a.rightDecorationSvg,
    onChange: v => setAttributes({
      rightDecorationSvg: v
    }),
    help: "Wklej zawarto\u015B\u0107 pliku .svg. SVG nadpisuje obraz.",
    rows: 6
  })), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
    title: "Rozmiary tekstu",
    initialOpen: false
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.RangeControl, {
    label: "Tytu\u0142 (desktop)",
    value: a.titleSizeDesktop,
    onChange: v => setAttributes({
      titleSizeDesktop: v
    }),
    min: 16,
    max: 64
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.RangeControl, {
    label: "Tytu\u0142 (mobile)",
    value: a.titleSizeMobile,
    onChange: v => setAttributes({
      titleSizeMobile: v
    }),
    min: 16,
    max: 48
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.RangeControl, {
    label: "Opis (desktop)",
    value: a.descriptionSizeDesktop,
    onChange: v => setAttributes({
      descriptionSizeDesktop: v
    }),
    min: 12,
    max: 28
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.RangeControl, {
    label: "Opis (mobile)",
    value: a.descriptionSizeMobile,
    onChange: v => setAttributes({
      descriptionSizeMobile: v
    }),
    min: 12,
    max: 24
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.RangeControl, {
    label: "Tekst przycisku",
    value: a.buttonSize,
    onChange: v => setAttributes({
      buttonSize: v
    }),
    min: 10,
    max: 24
  }))), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("article", {
    className: "onaswazne__card onaswazne__card--left"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "onaswazne__card-content"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "onaswazne__text-block"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.RichText, {
    tagName: "h2",
    className: "onaswazne__title",
    value: a.leftTitle,
    onChange: v => setAttributes({
      leftTitle: v
    }),
    placeholder: "Tytu\u0142 lewy"
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.RichText, {
    tagName: "p",
    className: "onaswazne__description",
    value: a.leftDescription,
    onChange: v => setAttributes({
      leftDescription: v
    }),
    placeholder: "Opis lewy"
  })), (a.leftLogoSvg || a.leftLogoImage || a.leftLogoImage2) && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: `onaswazne__logo${a.leftLogoImage2 ? " onaswazne__logo--duo" : ""}`
  }, (a.leftLogoSvg || a.leftLogoImage) && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: `onaswazne__logo-inner${a.leftLogoSvg ? " onaswazne__logo-inner--svg" : ""}`
  }, a.leftLogoSvg ? (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    dangerouslySetInnerHTML: {
      __html: a.leftLogoSvg
    }
  }) : (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("img", {
    src: a.leftLogoImage,
    alt: ""
  })), a.leftLogoImage2 && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "onaswazne__logo-second"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("img", {
    src: a.leftLogoImage2,
    alt: ""
  }))))), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("article", {
    className: "onaswazne__card onaswazne__card--right"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "onaswazne__card-content"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "onaswazne__text-block"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.RichText, {
    tagName: "h2",
    className: "onaswazne__title onaswazne__title--light",
    value: a.rightTitle,
    onChange: v => setAttributes({
      rightTitle: v
    }),
    placeholder: "Tytu\u0142 prawy"
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.RichText, {
    tagName: "p",
    className: "onaswazne__description onaswazne__description--light",
    value: a.rightDescription,
    onChange: v => setAttributes({
      rightDescription: v
    }),
    placeholder: "Opis prawy"
  })), a.rightButtonText && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "onaswazne__cta"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "onaswazne__cta-text"
  }, a.rightButtonText), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "onaswazne__cta-icon"
  }, "\u2192"))), (a.rightDecorationSvg || a.rightDecoration) && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: `onaswazne__decoration${a.rightDecorationSvg ? " onaswazne__decoration--svg" : ""}`
  }, a.rightDecorationSvg ? (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    dangerouslySetInnerHTML: {
      __html: a.rightDecorationSvg
    }
  }) : (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("img", {
    src: a.rightDecoration,
    alt: ""
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

/***/ "./src/onaswazne/block.json"
/*!**********************************!*\
  !*** ./src/onaswazne/block.json ***!
  \**********************************/
(module) {

module.exports = /*#__PURE__*/JSON.parse('{"$schema":"https://schemas.wp.org/trunk/block.json","apiVersion":3,"name":"ourblocktheme/onaswazne","title":"O nas — To dla nas ważne","category":"design","icon":"heart","attributes":{"leftTitle":{"type":"string","default":"To dla nas ważne"},"leftDescription":{"type":"string","default":"Angażujemy się w inicjatywy charytatywne wspierające kobiety. Do tej pory wsparliśmy Fundację Rak\'n\'Roll, przekazując darowizny na działania, które realnie pomagają kobietom."},"leftLogoImage":{"type":"string","default":""},"leftLogoImage2":{"type":"string","default":""},"rightTitle":{"type":"string","default":"Oswajamy tematy tabu"},"rightDescription":{"type":"string","default":"Chcemy też oswajać tematy związane z kobiecą pielęgnacją, ciałem i codziennym komfortem, które zbyt długo były traktowane jak tabu. Wychodzimy do ludzi, rozmawiamy otwarcie i pokazujemy, że o tych rzeczach można mówić naturalnie - bez skrępowania i bez niepotrzebnego dystansu."},"rightButtonText":{"type":"string","default":"Dowiedz się więcej"},"rightButtonUrl":{"type":"string","default":"/misja-spoleczna"},"rightDecoration":{"type":"string","default":""},"rightDecorationSvg":{"type":"string","default":""},"leftLogoSvg":{"type":"string","default":""},"titleSizeDesktop":{"type":"number","default":32},"titleSizeMobile":{"type":"number","default":26},"descriptionSizeDesktop":{"type":"number","default":16},"descriptionSizeMobile":{"type":"number","default":16},"buttonSize":{"type":"number","default":12}},"editorScript":"file:./index.js","render":"file:./render.php","supports":{"html":false,"anchor":true,"align":["wide","full"]}}');

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
/*!********************************!*\
  !*** ./src/onaswazne/index.js ***!
  \********************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/blocks */ "@wordpress/blocks");
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _block_json__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./block.json */ "./src/onaswazne/block.json");
/* harmony import */ var _edit__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./edit */ "./src/onaswazne/edit.js");





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