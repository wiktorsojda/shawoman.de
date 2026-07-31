/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./inc/mobile-menu.js"
/*!****************************!*\
  !*** ./inc/mobile-menu.js ***!
  \****************************/
() {

// document.addEventListener('DOMContentLoaded', e => {
//     const hamburger = document.querySelector(".mobile-hamburger button");
//     const mobileMenu = document.querySelector(".custom-menu");
//     const subMenuItem = document.querySelectorAll(".menu-item-has-children");
//     // const headerMenu = document.querySelector("header");
//     // const headerlinks = document.querySelectorAll("header ul .menu-item a");
//     const customCart = document.querySelector(".custom-cart");
//     const customCartMobile = document.querySelector(".custom-cart-mobile");
//     const miniCartCloseBtn = document.querySelector(".mini-cart-close");
//     const miniCartSidebar = document.querySelector(".widget_shopping_cart_content");

//     // function toggleMenu(event) {
//     //     event.preventDefault();
//     //     event.stopPropagation();

//     //     const expanded = hamburger.getAttribute('aria-expanded') === 'true';
//     //     hamburger.setAttribute('aria-expanded', !expanded);
//     //     mobileMenu.classList.toggle("menu-active");

//     //     if (!expanded) {
//     //         hamburger.setAttribute('aria-label', 'Zamknij menu');
//     //         console.log('zamykanie');
//     //     } else {
//     //         hamburger.setAttribute('aria-label', 'Otwórz menu');
//     //         console.log('otiweranie');
//     //     }
//     // }

//     // hamburger.addEventListener("click", toggleMenu);

//     hamburger.addEventListener("click", () => {
//         mobileMenu.classList.toggle("menu-active");
//     });

//     // function toggleSubMenu(e) {
//     //     const subMenu = this.querySelector(".sub-menu");
//     //     subMenu.classList.toggle("active");
//     //     e.stopPropagation();
//     // }

//     // function closeSubMenus() {
//     //     const activeSubMenus = document.querySelectorAll(".sub-menu.active");
//     //     activeSubMenus.forEach(subMenu => {
//     //         subMenu.classList.remove("active");
//     //     });
//     // }

//     // function toggleMiniCart() {
//     //     miniCartSidebar.classList.toggle("mini-cart-active");
//     // }

//     // const passiveEvent = { passive: true };

//     // hamburger.addEventListener("click", () => {
//     //     mobileMenu.classList.toggle("menu-active");
//     // });
//     // hamburger.addEventListener("click", toggleMenu);
//     // hamburger.addEventListener("touchstart", toggleMenu, passiveEvent);

//     // subMenuItem.forEach(sub => {
//     //     sub.addEventListener("click", toggleSubMenu);
//     //     // sub.addEventListener("touchstart", toggleSubMenu, passiveEvent);
//     // });

//     // document.addEventListener("click", closeSubMenus);
//     // document.addEventListener("touchstart", closeSubMenus, passiveEvent);

//     // if (customCart) {
//     //     customCart.addEventListener("click", toggleMiniCart);
//     //     // customCart.addEventListener("touchstart", toggleMiniCart, passiveEvent);
//     // }

//     // if (customCartMobile) {
//     //     customCartMobile.addEventListener("click", toggleMiniCart);
//     //     // customCartMobile.addEventListener("touchstart", toggleMiniCart, passiveEvent);
//     // }

//     // if (miniCartCloseBtn) {
//     //     miniCartCloseBtn.addEventListener("click", toggleMiniCart);
//     //     // miniCartCloseBtn.addEventListener("touchstart", toggleMiniCart, passiveEvent);
//     // }
// });

// pod add to cart
document.addEventListener('DOMContentLoaded', function () {
  const titles = document.querySelectorAll('.custom-title');
  titles.forEach(function (title) {
    title.addEventListener('click', function () {
      const description = this.nextElementSibling;
      if (description) {
        description.style.display = description.style.display === 'none' ? 'block' : 'none';
      }
    });
  });
});

/***/ },

/***/ "./src/header/edit.js"
/*!****************************!*\
  !*** ./src/header/edit.js ***!
  \****************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
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
    className: "header"
  });
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("header", {
    ...blockProps
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.InspectorControls, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
    title: "Logo",
    initialOpen: true
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.MediaUploadCheck, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.MediaUpload, {
    onSelect: media => setAttributes({
      logoImage: media.url,
      logoAlt: media.alt || a.logoAlt
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
    label: "Alt logo",
    value: a.logoAlt,
    onChange: v => setAttributes({
      logoAlt: v
    })
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    label: "Link logo (URL)",
    value: a.logoURL,
    onChange: v => setAttributes({
      logoURL: v
    })
  })), NAV_NUMS.map(n => (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
    key: n,
    title: `Link nawigacji ${n}`,
    initialOpen: n <= 2
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    label: "Etykieta",
    value: a[`nav${n}Label`],
    onChange: v => setAttributes({
      [`nav${n}Label`]: v
    })
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    label: "URL",
    value: a[`nav${n}URL`],
    onChange: v => setAttributes({
      [`nav${n}URL`]: v
    })
  }))), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
    title: "Koszyk",
    initialOpen: false
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.ToggleControl, {
    label: "Poka\u017C ikon\u0119 koszyka",
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
  }))), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "header__inner"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    className: "header__logo",
    href: a.logoURL
  }, a.logoImage ? (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("img", {
    src: a.logoImage,
    alt: a.logoAlt
  }) : (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    style: {
      padding: 8,
      background: "#f0f0f0",
      color: "#999",
      fontSize: 12
    }
  }, "Logo (wybierz w panelu)")), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("nav", {
    className: "header__nav"
  }, NAV_NUMS.map(n => {
    const lab = a[`nav${n}Label`];
    const url = a[`nav${n}URL`];
    if (!lab) return null;
    return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
      key: n,
      href: url
    }, lab);
  })), a.showCart && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    className: "header__cart",
    href: a.cartURL,
    "aria-label": "Koszyk"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    style: {
      display: "inline-block",
      width: 24,
      height: 24,
      border: "1px solid #999",
      borderRadius: 4,
      padding: 4,
      color: "#999",
      fontSize: 11,
      textAlign: "center"
    }
  }, "\uD83D\uDED2"))));
}

/***/ },

/***/ "react"
/*!************************!*\
  !*** external "React" ***!
  \************************/
(module) {

"use strict";
module.exports = window["React"];

/***/ },

/***/ "@wordpress/block-editor"
/*!*************************************!*\
  !*** external ["wp","blockEditor"] ***!
  \*************************************/
(module) {

"use strict";
module.exports = window["wp"]["blockEditor"];

/***/ },

/***/ "@wordpress/blocks"
/*!********************************!*\
  !*** external ["wp","blocks"] ***!
  \********************************/
(module) {

"use strict";
module.exports = window["wp"]["blocks"];

/***/ },

/***/ "@wordpress/components"
/*!************************************!*\
  !*** external ["wp","components"] ***!
  \************************************/
(module) {

"use strict";
module.exports = window["wp"]["components"];

/***/ },

/***/ "./src/header/block.json"
/*!*******************************!*\
  !*** ./src/header/block.json ***!
  \*******************************/
(module) {

"use strict";
module.exports = /*#__PURE__*/JSON.parse('{"$schema":"https://schemas.wp.org/trunk/block.json","apiVersion":3,"name":"ourblocktheme/header","title":"Header","attributes":{"logoImage":{"type":"string","default":""},"logoAlt":{"type":"string","default":"Shav Logo"},"logoURL":{"type":"string","default":"/"},"nav1Label":{"type":"string","default":"Produkty"},"nav1URL":{"type":"string","default":"/sklep"},"nav2Label":{"type":"string","default":"Kontakt"},"nav2URL":{"type":"string","default":"/kontakt"},"nav3Label":{"type":"string","default":""},"nav3URL":{"type":"string","default":""},"nav4Label":{"type":"string","default":""},"nav4URL":{"type":"string","default":""},"showCart":{"type":"boolean","default":true},"cartURL":{"type":"string","default":"/koszyk"},"hamburgerLabel":{"type":"string","default":"Otwórz menu"}},"editorScript":"file:./index.js","render":"file:./render.php","supports":{"html":false,"anchor":true,"align":["wide","full"],"color":{"background":true,"text":true,"link":true,"gradients":true},"typography":{"fontSize":true,"lineHeight":true,"__experimentalFontFamily":true,"__experimentalFontWeight":true,"__experimentalFontStyle":true,"__experimentalTextTransform":true,"__experimentalLetterSpacing":true,"__experimentalTextDecoration":true},"spacing":{"padding":true,"margin":true,"blockGap":true},"__experimentalBorder":{"color":true,"radius":true,"style":true,"width":true}}}');

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
// This entry needs to be wrapped in an IIFE because it needs to be in strict mode.
(() => {
"use strict";
/*!*****************************!*\
  !*** ./src/header/index.js ***!
  \*****************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/blocks */ "@wordpress/blocks");
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _block_json__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./block.json */ "./src/header/block.json");
/* harmony import */ var _edit__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./edit */ "./src/header/edit.js");
/* harmony import */ var _inc_mobile_menu__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../../inc/mobile-menu */ "./inc/mobile-menu.js");
/* harmony import */ var _inc_mobile_menu__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_inc_mobile_menu__WEBPACK_IMPORTED_MODULE_5__);





 // Adjusted path

(0,_wordpress_blocks__WEBPACK_IMPORTED_MODULE_1__.registerBlockType)(_block_json__WEBPACK_IMPORTED_MODULE_3__.name, {
  edit: _edit__WEBPACK_IMPORTED_MODULE_4__["default"],
  save: () => (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2__.InnerBlocks.Content, null)
});
})();

/******/ })()
;
//# sourceMappingURL=index.js.map