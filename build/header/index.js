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
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_3__);




const NAV_NUMS = [1, 2, 3, 4];
function Edit({
  attributes,
  setAttributes
}) {
  const a = attributes;
  const blockProps = (0,_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.useBlockProps)({
    className: "header"
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
  const getMenuName = menuId => {
    if (!menus || !menuId) return "Brak wybranego menu";
    const menu = menus.find(m => m.id === parseInt(menuId));
    return menu ? menu.name : "Wybierz menu w panelu bocznym";
  };
  const aiJson = JSON.stringify({
    logoAlt: a.logoAlt,
    hamburgerLabel: a.hamburgerLabel,
    whatsappQrText: a.whatsappQrText,
    whatsappButtonText: a.whatsappButtonText
  }, null, 2);
  const handleAiJsonChange = val => {
    try {
      const parsed = JSON.parse(val);
      setAttributes(parsed);
    } catch (e) {
      // ignore invalid json during typing
    }
  };
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
  })), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
    title: "Nawigacja (Menu)",
    initialOpen: true
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.SelectControl, {
    label: "Wybierz Menu",
    value: a.menuId,
    options: menuOptions,
    onChange: v => setAttributes({
      menuId: parseInt(v)
    })
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", {
    style: {
      fontSize: 12,
      color: "#666",
      marginTop: 12
    }
  }, "Same linki dla wybranego menu edytujesz w klasycznej zak\u0142adce ", (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("strong", null, "Wygl\u0105d \u2192 Menu"), ".")), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
    title: "Koszyk, WhatsApp i Hamburger",
    initialOpen: false
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.ToggleControl, {
    label: "Poka\u017C WhatsApp",
    checked: a.showWhatsapp,
    onChange: v => setAttributes({
      showWhatsapp: v
    })
  }), a.showWhatsapp && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    style: {
      marginBottom: 16
    }
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    label: "URL WhatsApp",
    value: a.whatsappUrl,
    onChange: v => setAttributes({
      whatsappUrl: v
    })
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    label: "Tekst nad QR kodem",
    value: a.whatsappQrText,
    onChange: v => setAttributes({
      whatsappQrText: v
    })
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    label: "Tekst przycisku pod QR",
    value: a.whatsappButtonText,
    onChange: v => setAttributes({
      whatsappButtonText: v
    })
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.MediaUploadCheck, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.MediaUpload, {
    onSelect: media => setAttributes({
      whatsappQrImage: media.url
    }),
    allowedTypes: ["image"],
    render: ({
      open
    }) => (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Button, {
      variant: "secondary",
      onClick: open,
      style: {
        marginTop: 8
      }
    }, a.whatsappQrImage ? "Zmień QR code" : "Wybierz QR code")
  })), (a.whatsappQrText || a.whatsappQrImage || a.whatsappButtonText) && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    style: {
      marginTop: '10px',
      padding: '10px',
      border: '1px solid #ccc',
      borderRadius: '8px',
      width: 'fit-content'
    }
  }, a.whatsappQrText && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    style: {
      fontSize: '13px',
      fontWeight: '600',
      marginBottom: '8px',
      textAlign: 'center',
      color: '#1a1a1a'
    }
  }, a.whatsappQrText), a.whatsappQrImage && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("img", {
    src: a.whatsappQrImage,
    alt: "QR",
    style: {
      width: '100px',
      display: 'block',
      margin: '0 auto',
      borderRadius: 4
    }
  }), a.whatsappButtonText && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    style: {
      marginTop: '8px',
      padding: '6px 12px',
      background: '#F4E1D9',
      color: '#1a1a1a',
      borderRadius: '8px',
      fontSize: '12px',
      fontWeight: '600',
      textAlign: 'center'
    }
  }, a.whatsappButtonText))), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.ToggleControl, {
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
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    label: "Aria-label dla Hamburgera",
    value: a.hamburgerLabel,
    onChange: v => setAttributes({
      hamburgerLabel: v
    })
  })), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
    title: "T\u0142umaczenia AI (JSON)",
    initialOpen: false
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextareaControl, {
    label: "JSON z atrybutami (skopiuj i przet\u0142umacz)",
    value: aiJson,
    onChange: handleAiJsonChange,
    rows: 6
  }))), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "header__inner"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    className: "header__logo",
    href: a.logoURL
  }, a.logoImage || customLogoUrl ? (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("img", {
    src: a.logoImage || customLogoUrl,
    alt: a.logoAlt
  }) : (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    style: {
      padding: 8,
      background: "#f0f0f0",
      color: "#999",
      fontSize: 12
    }
  }, "Logo (wybierz w Dostosuj lub tutaj)")), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("nav", {
    className: "header__nav",
    "aria-label": "G\u0142\xF3wna nawigacja"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    style: {
      opacity: 0.5,
      fontStyle: "italic",
      alignSelf: "center",
      display: "inline-block"
    }
  }, "[Zarz\u0105dzaj w Wygl\u0105d \u2192 Menu: ", getMenuName(a.menuId), "]")), a.showWhatsapp && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "header__whatsapp",
    style: {
      marginLeft: "auto"
    }
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    style: {
      display: "inline-flex",
      alignItems: "center",
      justifyContent: "center",
      width: 32,
      height: 32,
      color: "#252525"
    }
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
    width: "32",
    height: "32",
    viewBox: "0 0 32 32",
    fill: "none",
    xmlns: "http://www.w3.org/2000/svg"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
    d: "M25.3991 6.54413C24.1765 5.30961 22.7205 4.33076 21.1158 3.66462C19.5111 2.99848 17.7899 2.65838 16.0524 2.66413C8.77242 2.66413 2.83909 8.59747 2.83909 15.8775C2.83909 18.2108 3.45242 20.4775 4.59909 22.4775L2.73242 29.3308L9.73242 27.4908C11.6658 28.5441 13.8391 29.1041 16.0524 29.1041C23.3324 29.1041 29.2658 23.1708 29.2658 15.8908C29.2658 12.3575 27.8924 9.03747 25.3991 6.54413ZM16.0524 26.8641C14.0791 26.8641 12.1458 26.3308 10.4524 25.3308L10.0524 25.0908L5.89242 26.1841L6.99909 22.1308L6.73242 21.7175C5.63582 19.9669 5.05365 17.9432 5.05242 15.8775C5.05242 9.82414 9.98575 4.8908 16.0391 4.8908C18.9724 4.8908 21.7324 6.03747 23.7991 8.11747C24.8226 9.13595 25.6336 10.3475 26.1853 11.6819C26.7369 13.0163 27.018 14.4469 27.0124 15.8908C27.0391 21.9441 22.1058 26.8641 16.0524 26.8641ZM22.0791 18.6508C21.7458 18.4908 20.1191 17.6908 19.8258 17.5708C19.5191 17.4641 19.3058 17.4108 19.0791 17.7308C18.8524 18.0641 18.2258 18.8108 18.0391 19.0241C17.8524 19.2508 17.6524 19.2775 17.3191 19.1041C16.9858 18.9441 15.9191 18.5841 14.6658 17.4641C13.6791 16.5841 13.0258 15.5041 12.8258 15.1708C12.6391 14.8375 12.7991 14.6641 12.9724 14.4908C13.1191 14.3441 13.3058 14.1041 13.4658 13.9175C13.6258 13.7308 13.6924 13.5841 13.7991 13.3708C13.9058 13.1441 13.8524 12.9575 13.7724 12.7975C13.6924 12.6375 13.0258 11.0108 12.7591 10.3441C12.4924 9.70413 12.2124 9.78413 12.0124 9.7708H11.3724C11.1458 9.7708 10.7991 9.8508 10.4924 10.1841C10.1991 10.5175 9.34575 11.3175 9.34575 12.9441C9.34575 14.5708 10.5324 16.1441 10.6924 16.3575C10.8524 16.5841 13.0258 19.9175 16.3324 21.3441C17.1191 21.6908 17.7324 21.8908 18.2124 22.0375C18.9991 22.2908 19.7191 22.2508 20.2924 22.1708C20.9324 22.0775 22.2524 21.3708 22.5191 20.5975C22.7991 19.8241 22.7991 19.1708 22.7058 19.0241C22.6124 18.8775 22.4124 18.8108 22.0791 18.6508Z",
    fill: "currentColor"
  })))), a.showCart && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    className: "header__cart",
    href: a.cartURL,
    "aria-label": "Koszyk",
    style: {
      marginLeft: a.showWhatsapp ? 12 : "auto"
    }
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

/***/ "@wordpress/data"
/*!******************************!*\
  !*** external ["wp","data"] ***!
  \******************************/
(module) {

"use strict";
module.exports = window["wp"]["data"];

/***/ },

/***/ "./src/header/block.json"
/*!*******************************!*\
  !*** ./src/header/block.json ***!
  \*******************************/
(module) {

"use strict";
module.exports = /*#__PURE__*/JSON.parse('{"$schema":"https://schemas.wp.org/trunk/block.json","apiVersion":3,"name":"ourblocktheme/header","title":"Header","attributes":{"logoImage":{"type":"string","default":""},"logoAlt":{"type":"string","default":"Shav Logo"},"logoURL":{"type":"string","default":"/"},"showCart":{"type":"boolean","default":true},"showWhatsapp":{"type":"boolean","default":false},"whatsappUrl":{"type":"string","default":"https://wa.me/"},"whatsappQrImage":{"type":"string","default":""},"whatsappQrText":{"type":"string","default":""},"whatsappButtonText":{"type":"string","default":"Napisz do nas"},"cartURL":{"type":"string","default":"/koszyk"},"hamburgerLabel":{"type":"string","default":"Otwórz menu"},"menuId":{"type":"number","default":0}},"editorScript":"file:./index.js","render":"file:./render.php","supports":{"html":false,"anchor":true,"align":["wide","full"],"color":{"background":true,"text":true,"link":true,"gradients":true},"typography":{"fontSize":true,"lineHeight":true,"__experimentalFontFamily":true,"__experimentalFontWeight":true,"__experimentalFontStyle":true,"__experimentalTextTransform":true,"__experimentalLetterSpacing":true,"__experimentalTextDecoration":true},"spacing":{"padding":true,"margin":true,"blockGap":true},"__experimentalBorder":{"color":true,"radius":true,"style":true,"width":true}}}');

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