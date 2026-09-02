/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/rosegoldhero/edit.js"
/*!**********************************!*\
  !*** ./src/rosegoldhero/edit.js ***!
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



const NAV_NUMS = [1, 2, 3, 4];
function Edit({
  attributes,
  setAttributes
}) {
  const a = attributes;
  const blockProps = (0,_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.useBlockProps)({
    className: "rosegoldhero"
  });
  const bg = a.backgroundImage;
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    ...blockProps
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.InspectorControls, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
    title: "T\u0142o hero",
    initialOpen: true
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.MediaUploadCheck, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.MediaUpload, {
    onSelect: m => setAttributes({
      backgroundImage: m.url
    }),
    allowedTypes: ["image"],
    value: bg,
    render: ({
      open
    }) => (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Button, {
      variant: "secondary",
      onClick: open
    }, bg ? "Zmień tło" : "Wybierz tło")
  })), bg && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Button, {
    variant: "link",
    isDestructive: true,
    onClick: () => setAttributes({
      backgroundImage: ""
    })
  }, "Usu\u0144 t\u0142o (u\u017Cyj domy\u015Blnego)"), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.RangeControl, {
    label: "Przyciemnienie t\u0142a (%)",
    value: a.overlayOpacity,
    min: 0,
    max: 90,
    onChange: v => setAttributes({
      overlayOpacity: v
    })
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.RangeControl, {
    label: "Pozycja t\u0142a \u2014 poziom (lewo \u2192 prawo)",
    value: a.bgPosX,
    min: 0,
    max: 100,
    onChange: v => setAttributes({
      bgPosX: v
    })
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.RangeControl, {
    label: "Pozycja t\u0142a \u2014 pion (g\xF3ra \u2192 d\xF3\u0142)",
    value: a.bgPosY,
    min: 0,
    max: 100,
    onChange: v => setAttributes({
      bgPosY: v
    })
  })), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
    title: "Logo i nawigacja",
    initialOpen: false
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.MediaUploadCheck, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.MediaUpload, {
    onSelect: m => setAttributes({
      logoImage: m.url,
      logoAlt: m.alt || a.logoAlt
    }),
    allowedTypes: ["image"],
    value: a.logoImage,
    render: ({
      open
    }) => (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Button, {
      variant: "secondary",
      onClick: open
    }, a.logoImage ? "Zmień logo" : "Wybierz logo")
  })), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    label: "Link logo",
    value: a.logoURL,
    onChange: v => setAttributes({
      logoURL: v
    })
  }), NAV_NUMS.map(n => (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    key: n,
    style: {
      borderTop: "1px solid #eee",
      paddingTop: 8,
      marginTop: 8
    }
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    label: `Nawigacja ${n} — etykieta`,
    value: a[`nav${n}Label`],
    onChange: v => setAttributes({
      [`nav${n}Label`]: v
    })
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    label: `Nawigacja ${n} — URL`,
    value: a[`nav${n}URL`],
    onChange: v => setAttributes({
      [`nav${n}URL`]: v
    })
  }))), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.ToggleControl, {
    label: "Poka\u017C koszyk",
    checked: a.showCart,
    onChange: v => setAttributes({
      showCart: v
    })
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    label: "URL koszyka",
    value: a.cartURL,
    onChange: v => setAttributes({
      cartURL: v
    })
  })), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
    title: "Licznik odliczaj\u0105cy",
    initialOpen: false
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.ToggleControl, {
    label: "Poka\u017C licznik",
    checked: a.showCountdown,
    onChange: v => setAttributes({
      showCountdown: v
    })
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    type: "datetime-local",
    label: "Data zako\u0144czenia (puste = auto +3 dni)",
    value: a.countdownDate,
    onChange: v => setAttributes({
      countdownDate: v
    })
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    label: "Etykieta: dni",
    value: a.labelDays,
    onChange: v => setAttributes({
      labelDays: v
    })
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    label: "Etykieta: godziny",
    value: a.labelHours,
    onChange: v => setAttributes({
      labelHours: v
    })
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    label: "Etykieta: minuty",
    value: a.labelMinutes,
    onChange: v => setAttributes({
      labelMinutes: v
    })
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    label: "Etykieta: sekundy",
    value: a.labelSeconds,
    onChange: v => setAttributes({
      labelSeconds: v
    })
  }))), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "rosegoldhero__bg",
    style: {
      ...(bg ? {
        backgroundImage: `url(${bg})`
      } : {}),
      backgroundPosition: `${a.bgPosX}% ${a.bgPosY}%`
    }
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "rosegoldhero__overlay",
    style: {
      background: `rgba(0,0,0,${(a.overlayOpacity || 0) / 100})`
    },
    "aria-hidden": "true"
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "rosegoldhero__header header"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "header__inner"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "header__logo"
  }, a.logoImage ? (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("img", {
    src: a.logoImage,
    alt: a.logoAlt
  }) : (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    style: {
      color: "#252525",
      fontWeight: 700
    }
  }, "SHAV")), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("nav", {
    className: "header__nav"
  }, NAV_NUMS.map(n => a[`nav${n}Label`] ? (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    key: n,
    href: "#"
  }, a[`nav${n}Label`]) : null)), a.showCart && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "header__cart",
    "aria-hidden": "true"
  }, "\uD83D\uDED2"))), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "rosegoldhero__content"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "rosegoldhero__inner"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.RichText, {
    tagName: "span",
    className: "rosegoldhero__badge",
    value: a.badge,
    onChange: v => setAttributes({
      badge: v
    }),
    placeholder: "Badge"
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.RichText, {
    tagName: "h1",
    className: "rosegoldhero__title",
    value: a.title,
    onChange: v => setAttributes({
      title: v
    }),
    placeholder: "Tytu\u0142 dropu"
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", {
    className: "rosegoldhero__subtitle"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.RichText, {
    tagName: "span",
    value: a.subtitle1,
    onChange: v => setAttributes({
      subtitle1: v
    }),
    placeholder: "Podtytu\u0142"
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.RichText, {
    tagName: "span",
    value: a.subtitleEmoji,
    onChange: v => setAttributes({
      subtitleEmoji: v
    }),
    placeholder: "\uD83C\uDF82"
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.RichText, {
    tagName: "span",
    value: a.subtitle2,
    onChange: v => setAttributes({
      subtitle2: v
    }),
    placeholder: "Pozosta\u0142o jeszcze:"
  })), a.showCountdown && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(react__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "rosegoldhero__divider",
    "aria-hidden": "true"
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "rosegoldhero__countdown"
  }, [["--", a.labelDays], ["--", a.labelHours], ["--", a.labelMinutes], ["--", a.labelSeconds]].map(([num, lab], i) => (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "rosegoldhero__tile",
    key: i
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "rosegoldhero__tile-num"
  }, num), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "rosegoldhero__tile-label"
  }, lab)))))))));
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

/***/ "./src/rosegoldhero/block.json"
/*!*************************************!*\
  !*** ./src/rosegoldhero/block.json ***!
  \*************************************/
(module) {

module.exports = /*#__PURE__*/JSON.parse('{"$schema":"https://schemas.wp.org/trunk/block.json","apiVersion":3,"name":"ourblocktheme/rosegoldhero","title":"Drop — Hero z licznikiem","category":"theme","icon":"clock","description":"Hero dropu: tło + header (logo/nav/koszyk), badge, tytuł, podtytuł i licznik odliczający. W pełni edytowalny — reużywalny pod dowolną promocję.","attributes":{"backgroundImage":{"type":"string","default":""},"overlayOpacity":{"type":"number","default":50},"bgPosX":{"type":"number","default":50},"bgPosY":{"type":"number","default":50},"logoImage":{"type":"string","default":""},"logoAlt":{"type":"string","default":"Shav Woman"},"logoURL":{"type":"string","default":"/"},"nav1Label":{"type":"string","default":"Produkty"},"nav1URL":{"type":"string","default":"/sklep"},"nav2Label":{"type":"string","default":"O nas"},"nav2URL":{"type":"string","default":"/o-nas"},"nav3Label":{"type":"string","default":"Kontakt"},"nav3URL":{"type":"string","default":"/kontakt"},"nav4Label":{"type":"string","default":"Sprzedaż hurtowa"},"nav4URL":{"type":"string","default":"/wspolpraca"},"cartURL":{"type":"string","default":"/koszyk"},"showCart":{"type":"boolean","default":true},"badge":{"type":"string","default":"URODZINY SHAV WOMAN"},"title":{"type":"string","default":"Drop Rose Gold"},"subtitle1":{"type":"string","default":"Najniższe ceny w historii!"},"subtitleEmoji":{"type":"string","default":"🎂"},"subtitle2":{"type":"string","default":"Pozostało jeszcze:"},"showCountdown":{"type":"boolean","default":true},"countdownDate":{"type":"string","default":""},"labelDays":{"type":"string","default":"dni"},"labelHours":{"type":"string","default":"godziny"},"labelMinutes":{"type":"string","default":"minuty"},"labelSeconds":{"type":"string","default":"sekundy"}},"editorScript":"file:./index.js","render":"file:./render.php","supports":{"html":false,"anchor":true,"align":["full"]}}');

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
  !*** ./src/rosegoldhero/index.js ***!
  \***********************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/blocks */ "@wordpress/blocks");
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _block_json__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./block.json */ "./src/rosegoldhero/block.json");
/* harmony import */ var _edit__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./edit */ "./src/rosegoldhero/edit.js");





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