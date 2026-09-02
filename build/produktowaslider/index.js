/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/produktowaslider/edit.js"
/*!**************************************!*\
  !*** ./src/produktowaslider/edit.js ***!
  \**************************************/
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
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__);




function Edit(props) {
  const {
    attributes,
    setAttributes
  } = props;
  const {
    slides
  } = attributes;
  const blockProps = (0,_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.useBlockProps)({
    className: "produktowaslider-editor-preview"
  });

  // --- Tłumaczenia AI (JSON) ---
  const [jsonInput, setJsonInput] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.useState)("");
  const [jsonError, setJsonError] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.useState)("");
  const generateJson = () => {
    // We want to translate only texts (like alt attributes)
    const data = {
      slides
    };
    setJsonInput(JSON.stringify(data, null, 2));
    setJsonError("");
  };
  const applyJson = () => {
    try {
      const parsed = JSON.parse(jsonInput);
      if (parsed.slides && Array.isArray(parsed.slides)) {
        setAttributes({
          slides: parsed.slides
        });
        setJsonError("Atrybuty zaktualizowane pomyślnie!");
      } else {
        setJsonError("Brak tablicy 'slides' w JSON.");
      }
      setTimeout(() => setJsonError(""), 3000);
    } catch (e) {
      setJsonError("Błąd parsowania JSON. Sprawdź format.");
    }
  };

  // --- Zarządzanie slajdami ---
  const addSlide = () => {
    const newSlides = [...slides, {
      desktopImage: "",
      mobileImage: "",
      altText: ""
    }];
    setAttributes({
      slides: newSlides
    });
  };
  const removeSlide = index => {
    const newSlides = [...slides];
    newSlides.splice(index, 1);
    setAttributes({
      slides: newSlides
    });
  };
  const updateSlide = (index, key, value) => {
    const newSlides = [...slides];
    newSlides[index][key] = value;
    setAttributes({
      slides: newSlides
    });
  };
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    ...blockProps
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.InspectorControls, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
    title: "Slajdy (Desktop & Mobile)",
    initialOpen: true
  }, slides.map((slide, index) => (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    key: index,
    style: {
      marginBottom: "20px",
      padding: "10px",
      border: "1px solid #ddd",
      borderRadius: "4px"
    }
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", {
    style: {
      fontWeight: "bold",
      margin: "0 0 10px 0"
    }
  }, "Slajd #", index + 1), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.MediaUploadCheck, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.MediaUpload, {
    onSelect: media => updateSlide(index, "desktopImage", media.url),
    allowedTypes: ["image"],
    render: ({
      open
    }) => (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Button, {
      variant: slide.desktopImage ? "secondary" : "primary",
      onClick: open,
      style: {
        width: "100%",
        marginBottom: "10px",
        justifyContent: "center"
      }
    }, slide.desktopImage ? "Zmień zdjęcie Desktop" : "Wybierz zdjęcie Desktop")
  })), slide.desktopImage && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("img", {
    src: slide.desktopImage,
    alt: "Desktop Preview",
    style: {
      width: "100%",
      height: "auto",
      marginBottom: "10px",
      borderRadius: "4px"
    }
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.MediaUploadCheck, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.MediaUpload, {
    onSelect: media => updateSlide(index, "mobileImage", media.url),
    allowedTypes: ["image"],
    render: ({
      open
    }) => (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Button, {
      variant: slide.mobileImage ? "secondary" : "primary",
      onClick: open,
      style: {
        width: "100%",
        marginBottom: "10px",
        justifyContent: "center"
      }
    }, slide.mobileImage ? "Zmień zdjęcie Mobile" : "Wybierz zdjęcie Mobile")
  })), slide.mobileImage && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("img", {
    src: slide.mobileImage,
    alt: "Mobile Preview",
    style: {
      width: "100%",
      height: "auto",
      marginBottom: "10px",
      borderRadius: "4px"
    }
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    label: "Tekst alternatywny (Alt) / Tytu\u0142 obrazka",
    value: slide.altText,
    onChange: value => updateSlide(index, "altText", value)
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Button, {
    isDestructive: true,
    onClick: () => removeSlide(index),
    style: {
      marginTop: "10px"
    }
  }, "Usu\u0144 ten slajd"))), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Button, {
    variant: "primary",
    onClick: addSlide,
    style: {
      width: "100%",
      justifyContent: "center"
    }
  }, "+ Dodaj kolejny slajd")), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
    title: "T\u0142umaczenia AI (JSON)",
    initialOpen: false
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", {
    style: {
      fontSize: "12px",
      marginBottom: "10px"
    }
  }, "Wygeneruj struktur\u0119 slajd\xF3w do przet\u0142umaczenia przez AI (alt texty). Wklej przet\u0142umaczony JSON z powrotem."), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Button, {
    variant: "secondary",
    onClick: generateJson,
    style: {
      marginBottom: "10px"
    }
  }, "Wygeneruj JSON"), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextareaControl, {
    value: jsonInput,
    onChange: value => setJsonInput(value),
    rows: 12,
    help: jsonError,
    style: {
      fontFamily: "monospace",
      fontSize: "11px"
    }
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Button, {
    variant: "primary",
    onClick: applyJson,
    disabled: !jsonInput
  }, "Zastosuj t\u0142umaczenie"))), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    style: {
      padding: "20px",
      border: "2px dashed #ccc",
      textAlign: "center",
      backgroundColor: "#f9f9f9"
    }
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("h3", {
    style: {
      margin: "0 0 10px 0",
      color: "#666"
    }
  }, "[Blok: Slider Produktowy]"), slides.length === 0 ? (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", {
    style: {
      color: "#999",
      fontSize: "13px",
      margin: 0
    }
  }, "Brak slajd\xF3w. Dodaj je w panelu bocznym.") : (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    style: {
      display: "flex",
      flexWrap: "wrap",
      gap: "10px",
      justifyContent: "center",
      marginTop: "15px"
    }
  }, slides.map((slide, index) => (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    key: index,
    style: {
      width: "120px",
      border: "1px solid #ddd",
      borderRadius: "8px",
      overflow: "hidden",
      backgroundColor: "#fff"
    }
  }, slide.desktopImage ? (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("img", {
    src: slide.desktopImage,
    alt: "Podgl\u0105d",
    style: {
      width: "100%",
      height: "80px",
      objectFit: "cover",
      display: "block"
    }
  }) : (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    style: {
      width: "100%",
      height: "80px",
      display: "flex",
      alignItems: "center",
      justifyContent: "center",
      backgroundColor: "#eee",
      color: "#999",
      fontSize: "11px"
    }
  }, "Brak zdj."), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    style: {
      padding: "5px",
      fontSize: "11px",
      color: "#666",
      background: "#f1f1f1",
      borderTop: "1px solid #ddd"
    }
  }, "Slajd ", index + 1))))));
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

/***/ "@wordpress/element"
/*!*********************************!*\
  !*** external ["wp","element"] ***!
  \*********************************/
(module) {

module.exports = window["wp"]["element"];

/***/ },

/***/ "./src/produktowaslider/block.json"
/*!*****************************************!*\
  !*** ./src/produktowaslider/block.json ***!
  \*****************************************/
(module) {

module.exports = /*#__PURE__*/JSON.parse('{"$schema":"https://schemas.wp.org/trunk/block.json","apiVersion":3,"name":"ourblocktheme/produktowaslider","title":"Slider Produktowy","category":"design","icon":"images-alt2","description":"Dynamiczny slider produktowy (styl peek) dla desktopu i mobile. Dodawaj i usuwaj slajdy wygodnie w edytorze.","supports":{"html":false,"align":["wide","full"]},"attributes":{"slides":{"type":"array","default":[]}},"textdomain":"shavwoman","editorScript":"file:./index.js","render":"file:./render.php"}');

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
/*!***************************************!*\
  !*** ./src/produktowaslider/index.js ***!
  \***************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/blocks */ "@wordpress/blocks");
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _edit__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./edit */ "./src/produktowaslider/edit.js");
/* harmony import */ var _block_json__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./block.json */ "./src/produktowaslider/block.json");



(0,_wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__.registerBlockType)(_block_json__WEBPACK_IMPORTED_MODULE_2__.name, {
  edit: _edit__WEBPACK_IMPORTED_MODULE_1__["default"],
  save: () => null // Dynamic block
});
})();

/******/ })()
;
//# sourceMappingURL=index.js.map