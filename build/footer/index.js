/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/footer/edit.js"
/*!****************************!*\
  !*** ./src/footer/edit.js ***!
  \****************************/
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




const SOCIAL_NUMS = [1, 2, 3, 4];
function Edit({
  attributes,
  setAttributes
}) {
  const a = attributes;
  const blockProps = (0,_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.useBlockProps)({
    className: "footer"
  });
  const menus = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_3__.useSelect)(select => {
    return select("core").getEntityRecords("taxonomy", "nav_menu", {
      per_page: -1
    });
  }, []);
  const siteInfo = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_3__.useSelect)(select => select("core").getEntityRecord("root", "site"), []);
  const customLogoId = siteInfo ? siteInfo.site_logo : null;
  const customLogoMedia = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_3__.useSelect)(select => {
    return customLogoId ? select("core").getMedia(customLogoId) : null;
  }, [customLogoId]);
  const customLogoUrl = customLogoMedia ? customLogoMedia.source_url : null;
  const menuOptions = [{
    label: "Wybierz menu...",
    value: 0
  }];
  if (menus) {
    menus.forEach(menu => {
      menuOptions.push({
        label: menu.name,
        value: menu.id
      });
    });
  }

  // Helper to get menu name for display in editor
  const getMenuName = menuId => {
    if (!menus || !menuId) return "Brak wybranego menu";
    const menu = menus.find(m => m.id === parseInt(menuId));
    return menu ? menu.name : "Wybierz menu w panelu bocznym";
  };
  const colsArray = Array.from({
    length: a.columnsCount
  }, (_, i) => i + 1);
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("footer", {
    ...blockProps
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.InspectorControls, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
    title: "Karta CTA \u2014 link przycisku",
    initialOpen: true
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    label: "URL przycisku",
    value: a.ctaButtonURL,
    onChange: v => setAttributes({
      ctaButtonURL: v
    })
  })), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
    title: "Kolumny nawigacji (Menu)",
    initialOpen: false
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.RangeControl, {
    label: "Liczba kolumn",
    value: a.columnsCount,
    onChange: v => setAttributes({
      columnsCount: v
    }),
    min: 1,
    max: 4
  }), colsArray.map(n => (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    key: n,
    style: {
      marginTop: 16,
      padding: 8,
      border: "1px solid #ddd"
    }
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", {
    style: {
      margin: "0 0 8px 0",
      fontWeight: "bold"
    }
  }, "Ustawienia Kolumny ", n), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.SelectControl, {
    label: `Wybierz Menu (Kolumna ${n})`,
    value: a[`col${n}MenuId`],
    options: menuOptions,
    onChange: v => setAttributes({
      [`col${n}MenuId`]: parseInt(v)
    })
  }))), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", {
    style: {
      fontSize: 12,
      color: "#666",
      marginTop: 12
    }
  }, "Uwaga: Same linki dla wybranego menu edytujesz w klasycznej zak\u0142adce ", (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("strong", null, "Wygl\u0105d \u2192 Menu"), ".")), SOCIAL_NUMS.map(n => (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
    key: n,
    title: `Social ${n}`,
    initialOpen: false
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.MediaUploadCheck, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.MediaUpload, {
    onSelect: media => setAttributes({
      [`social${n}Icon`]: media.url
    }),
    allowedTypes: ["image"],
    value: a[`social${n}Icon`],
    render: ({
      open
    }) => (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Button, {
      variant: "secondary",
      onClick: open
    }, a[`social${n}Icon`] ? "Zmień ikonę" : "Wybierz ikonę")
  })), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    label: "Nazwa (aria-label)",
    value: a[`social${n}Label`],
    onChange: v => setAttributes({
      [`social${n}Label`]: v
    })
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    label: "URL",
    value: a[`social${n}URL`],
    onChange: v => setAttributes({
      [`social${n}URL`]: v
    })
  }))), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
    title: "Stopka \u2014 dolny pasek",
    initialOpen: false
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.ToggleControl, {
    label: 'Pokaż przycisk „back to top"',
    checked: a.showBackToTop,
    onChange: v => setAttributes({
      showBackToTop: v
    })
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.SelectControl, {
    label: "Wybierz Menu (Zamiast sztywnych link\xF3w)",
    value: a.bottomMenuId,
    options: menuOptions,
    onChange: v => setAttributes({
      bottomMenuId: parseInt(v)
    }),
    help: "Je\u015Bli wybierzesz menu, poka\u017Ce si\u0119 ono zamiast sztywnych link\xF3w (max 3 elementy)."
  }), !a.bottomMenuId && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(react__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    label: "Polityka URL",
    value: a.policyURL,
    onChange: v => setAttributes({
      policyURL: v
    })
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    label: "Regulamin URL",
    value: a.termsURL,
    onChange: v => setAttributes({
      termsURL: v
    })
  })), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.MediaUploadCheck, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.MediaUpload, {
    onSelect: media => setAttributes({
      bottomLogo: media.url
    }),
    allowedTypes: ["image"],
    value: a.bottomLogo,
    render: ({
      open
    }) => (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Button, {
      variant: "secondary",
      onClick: open,
      style: {
        marginTop: 8
      }
    }, a.bottomLogo ? "Zmień logo" : "Wybierz logo dolne")
  })))), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "footer__inner"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "footer__top"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "footer__cta"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("h2", {
    className: "footer__cta-title"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.RichText, {
    tagName: "span",
    value: a.ctaTitleBefore,
    onChange: v => setAttributes({
      ctaTitleBefore: v
    }),
    placeholder: "Shav "
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.RichText, {
    tagName: "span",
    className: "footer__cta-title-accent",
    value: a.ctaTitleAccent,
    onChange: v => setAttributes({
      ctaTitleAccent: v
    }),
    placeholder: "woman."
  })), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.RichText, {
    tagName: "p",
    className: "footer__cta-subtitle",
    value: a.ctaSubtitle,
    onChange: v => setAttributes({
      ctaSubtitle: v
    }),
    placeholder: "Subtitle"
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "footer__cta-button"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.RichText, {
    tagName: "span",
    value: a.ctaButtonLabel,
    onChange: v => setAttributes({
      ctaButtonLabel: v
    }),
    placeholder: "Etykieta"
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "footer__cta-button-arrow",
    "aria-hidden": "true"
  }, "\u2192"))), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "footer__links",
    style: {
      display: "grid",
      gridTemplateColumns: "1fr 1fr",
      gap: 32,
      flex: 1
    }
  }, colsArray.map(n => (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    key: n,
    className: "footer__col"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.RichText, {
    tagName: "p",
    className: "footer__col-title",
    value: a[`col${n}Title`],
    onChange: v => setAttributes({
      [`col${n}Title`]: v
    }),
    placeholder: `Tytuł Kolumny ${n}`
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("ul", {
    className: "footer__col-list"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("li", {
    style: {
      opacity: 0.5,
      fontStyle: "italic"
    }
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null, "[Zarz\u0105dzaj w Wygl\u0105d \u2192 Menu: ", getMenuName(a[`col${n}MenuId`]), "]"))))), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "footer__social"
  }, SOCIAL_NUMS.map(n => (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    key: n,
    className: "footer__social-icon"
  }, a[`social${n}Icon`] ? (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("img", {
    src: a[`social${n}Icon`],
    alt: a[`social${n}Label`]
  }) : (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    style: {
      fontSize: 9,
      color: "#999"
    }
  }, a[`social${n}Label`].charAt(0))))))), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("hr", {
    className: "footer__separator"
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "footer__bottom"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.RichText, {
    tagName: "p",
    className: "footer__copyright",
    value: a.copyright,
    onChange: v => setAttributes({
      copyright: v
    }),
    placeholder: "Copyright"
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "footer__logo"
  }, a.bottomLogo || customLogoUrl ? (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("img", {
    src: a.bottomLogo || customLogoUrl,
    alt: ""
  }) : (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    style: {
      fontSize: 11,
      color: "#999"
    }
  }, "logo")), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "footer__legal"
  }, a.bottomMenuId ? (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    style: {
      fontStyle: "italic",
      opacity: 0.5
    }
  }, "[Menu: ", getMenuName(a.bottomMenuId), "]") : (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(react__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.RichText, {
    tagName: "span",
    value: a.policyLabel,
    onChange: v => setAttributes({
      policyLabel: v
    }),
    placeholder: "Polityka prywatno\u015Bci"
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "footer__legal-sep",
    "aria-hidden": "true"
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.RichText, {
    tagName: "span",
    value: a.termsLabel,
    onChange: v => setAttributes({
      termsLabel: v
    }),
    placeholder: "Regulamin"
  })), a.showBackToTop && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(react__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "footer__legal-sep",
    "aria-hidden": "true"
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "footer__back-to-top",
    "aria-hidden": "true"
  }, "\u2191"))))));
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

/***/ "./src/footer/block.json"
/*!*******************************!*\
  !*** ./src/footer/block.json ***!
  \*******************************/
(module) {

module.exports = /*#__PURE__*/JSON.parse('{"$schema":"https://schemas.wp.org/trunk/block.json","apiVersion":3,"name":"ourblocktheme/footer","title":"Footer","attributes":{"ctaTitleBefore":{"type":"string","default":"Shav "},"ctaTitleAccent":{"type":"string","default":"woman."},"ctaSubtitle":{"type":"string","default":"Nr. 1 maszynka do bikinii i ciała."},"ctaButtonLabel":{"type":"string","default":"Kup teraz"},"ctaButtonURL":{"type":"string","default":"/sklep"},"col1Title":{"type":"string","default":"Shav Woman"},"col2Title":{"type":"string","default":"Pomoc"},"columnsCount":{"type":"number","default":2},"col1MenuId":{"type":"number","default":0},"col2MenuId":{"type":"number","default":0},"col3Title":{"type":"string","default":"Kolumna 3"},"col3MenuId":{"type":"number","default":0},"col4Title":{"type":"string","default":"Kolumna 4"},"col4MenuId":{"type":"number","default":0},"social1Icon":{"type":"string","default":""},"social1Label":{"type":"string","default":"Facebook"},"social1URL":{"type":"string","default":"https://www.facebook.com/"},"social2Icon":{"type":"string","default":""},"social2Label":{"type":"string","default":"TikTok"},"social2URL":{"type":"string","default":"https://www.tiktok.com/"},"social3Icon":{"type":"string","default":""},"social3Label":{"type":"string","default":"Instagram"},"social3URL":{"type":"string","default":"https://www.instagram.com/"},"social4Icon":{"type":"string","default":""},"social4Label":{"type":"string","default":"YouTube"},"social4URL":{"type":"string","default":"https://www.youtube.com/"},"copyright":{"type":"string","default":"© Shav Woman 2026. Wszelkie prawa zastrzeżone."},"bottomLogo":{"type":"string","default":""},"policyLabel":{"type":"string","default":"Polityka prywatności"},"policyURL":{"type":"string","default":"/polityka-prywatnosci"},"termsLabel":{"type":"string","default":"Regulamin"},"termsURL":{"type":"string","default":"/regulamin"},"showBackToTop":{"type":"boolean","default":false},"bottomMenuId":{"type":"number","default":0}},"editorScript":"file:./index.js","render":"file:./render.php","supports":{"html":false,"anchor":true,"align":["wide","full"],"color":{"background":true,"text":true,"link":true,"gradients":true},"typography":{"fontSize":true,"lineHeight":true,"__experimentalFontFamily":true,"__experimentalFontWeight":true,"__experimentalFontStyle":true,"__experimentalTextTransform":true,"__experimentalLetterSpacing":true,"__experimentalTextDecoration":true},"spacing":{"padding":true,"margin":true,"blockGap":true},"__experimentalBorder":{"color":true,"radius":true,"style":true,"width":true}}}');

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
/*!*****************************!*\
  !*** ./src/footer/index.js ***!
  \*****************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/blocks */ "@wordpress/blocks");
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _block_json__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./block.json */ "./src/footer/block.json");
/* harmony import */ var _edit__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./edit */ "./src/footer/edit.js");



(0,_wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__.registerBlockType)(_block_json__WEBPACK_IMPORTED_MODULE_1__.name, {
  edit: _edit__WEBPACK_IMPORTED_MODULE_2__["default"]
});
})();

/******/ })()
;
//# sourceMappingURL=index.js.map