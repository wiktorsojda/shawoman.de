/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/glownaopinie/edit.js"
/*!**********************************!*\
  !*** ./src/glownaopinie/edit.js ***!
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
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__);




const REVIEW_NUMS = [1, 2, 3, 4, 5, 6, 7, 8];
function Edit({
  attributes,
  setAttributes
}) {
  const a = attributes;
  const blockProps = (0,_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.useBlockProps)({
    className: "glownaopinie"
  });
  const [importJson, setImportJson] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.useState)("");
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    ...blockProps
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.InspectorControls, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
    title: "T\u0142umaczenia AI (JSON)",
    initialOpen: false
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextareaControl, {
    label: "Skopiuj ten JSON dla AI",
    value: (() => {
      const data = {
        sectionTitle: a.sectionTitle || '',
        verifiedLabel: a.verifiedLabel || '',
        review1Name: a.review1Name || '',
        review1Text: a.review1Text || '',
        review2Name: a.review2Name || '',
        review2Text: a.review2Text || '',
        review3Name: a.review3Name || '',
        review3Text: a.review3Text || '',
        review4Name: a.review4Name || '',
        review4Text: a.review4Text || '',
        review5Name: a.review5Name || '',
        review5Text: a.review5Text || '',
        review6Name: a.review6Name || '',
        review6Text: a.review6Text || '',
        review7Name: a.review7Name || '',
        review7Text: a.review7Text || '',
        review8Name: a.review8Name || '',
        review8Text: a.review8Text || ''
      };
      return JSON.stringify(data, null, 2);
    })(),
    readOnly: true,
    rows: 10,
    help: "Skopiuj i wklej do AI z pro\u015Bb\u0105 o przet\u0142umaczenie samych warto\u015Bci."
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextareaControl, {
    label: "Wklej przet\u0142umaczony JSON",
    value: importJson,
    onChange: setImportJson,
    rows: 10
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Button, {
    variant: "primary",
    onClick: () => {
      try {
        const parsed = JSON.parse(importJson);
        const updates = {};
        if (parsed.sectionTitle !== undefined) updates.sectionTitle = parsed.sectionTitle;
        if (parsed.verifiedLabel !== undefined) updates.verifiedLabel = parsed.verifiedLabel;
        for (let i = 1; i <= 8; i++) {
          if (parsed[`review${i}Name`] !== undefined) updates[`review${i}Name`] = parsed[`review${i}Name`];
          if (parsed[`review${i}Text`] !== undefined) updates[`review${i}Text`] = parsed[`review${i}Text`];
        }
        setAttributes(updates);
        alert('Zaktualizowano pomyślnie!');
        setImportJson('');
      } catch (e) {
        alert('Błąd! Niepoprawny format JSON.');
      }
    },
    style: {
      width: '100%',
      justifyContent: 'center'
    }
  }, "Importuj t\u0142umaczenie")), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
    title: "Slider \u2014 ustawienia",
    initialOpen: true
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.RangeControl, {
    label: "Widocznych kart na desktopie",
    min: 1,
    max: 4,
    value: a.visibleCount,
    onChange: v => setAttributes({
      visibleCount: v
    })
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.ToggleControl, {
    label: "Auto-przewijanie",
    checked: a.autoScroll,
    onChange: v => setAttributes({
      autoScroll: v
    })
  }), a.autoScroll && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    label: "Czas mi\u0119dzy slajdami (ms)",
    type: "number",
    value: a.autoScrollMs,
    onChange: v => setAttributes({
      autoScrollMs: parseInt(v) || 6000
    })
  })), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
    title: "Etykiety",
    initialOpen: false
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", {
    style: {
      fontSize: 12,
      color: "#999"
    }
  }, "Etykiet\u0119 \u201EZaufana opinia...\" edytujesz bezpo\u015Brednio w bloku poni\u017Cej.")), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
    title: "Ikona \"Zaufana opinia\" - SVG",
    initialOpen: false
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextareaControl, {
    label: "Inline SVG",
    help: "Wklej ca\u0142y tag <svg>...</svg>. Puste = domy\u015Blna ikona piecz\u0105tki.",
    value: a.verifiedIconSvg || "",
    onChange: v => setAttributes({
      verifiedIconSvg: v
    }),
    rows: 8
  }))), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.RichText, {
    tagName: "h2",
    className: "glownaopinie__title",
    value: a.sectionTitle,
    onChange: v => setAttributes({
      sectionTitle: v
    }),
    placeholder: "Tytu\u0142 sekcji"
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "glownaopinie__slider"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "glownaopinie__cards"
  }, REVIEW_NUMS.map(n => {
    const name = a[`review${n}Name`];
    const text = a[`review${n}Text`];
    const isEmpty = !name && !text;
    return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      key: n,
      className: `glownaopinie__card${isEmpty ? " glownaopinie__card--empty" : ""}`
    }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "glownaopinie__card-slot-label",
      style: {
        fontSize: 11,
        color: "#999",
        marginBottom: 8,
        textTransform: "uppercase",
        letterSpacing: 0.5
      }
    }, "Opinia ", n, isEmpty ? " (pusta — puste pomijane na froncie)" : ""), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "glownaopinie__card-pill"
    }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.RichText, {
      tagName: "span",
      value: name,
      onChange: v => setAttributes({
        [`review${n}Name`]: v
      }),
      placeholder: "Imi\u0119"
    }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
      className: "glownaopinie__card-pill-sep",
      "aria-hidden": "true"
    }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.RichText, {
      tagName: "span",
      value: a[`review${n}Rating`],
      onChange: v => setAttributes({
        [`review${n}Rating`]: v
      }),
      placeholder: "5.0"
    }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
      className: "glownaopinie__card-pill-stars",
      "aria-hidden": "true"
    }, "\u2605\u2605\u2605\u2605\u2605")), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.RichText, {
      tagName: "p",
      className: "glownaopinie__card-text",
      value: text,
      onChange: v => setAttributes({
        [`review${n}Text`]: v
      }),
      placeholder: "Tre\u015B\u0107 opinii \u2014 wpisz aby doda\u0107"
    }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "glownaopinie__card-verified"
    }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.RichText, {
      tagName: "span",
      value: a.verifiedLabel,
      onChange: v => setAttributes({
        verifiedLabel: v
      }),
      placeholder: "Zaufana opinia..."
    }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
      className: "glownaopinie__card-verified-sep",
      "aria-hidden": "true"
    }), a[`review${n}IconSvg`] || a.verifiedIconSvg ? (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
      className: "glownaopinie__card-verified-icon",
      "aria-hidden": "true",
      dangerouslySetInnerHTML: {
        __html: a[`review${n}IconSvg`] || a.verifiedIconSvg
      }
    }) : (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
      className: "glownaopinie__card-verified-icon",
      "aria-hidden": "true"
    }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
      xmlns: "http://www.w3.org/2000/svg",
      width: "32",
      height: "32",
      viewBox: "0 0 32 32",
      fill: "none"
    }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
      d: "M20 18.6666C18 18.6666 16 20.6666 16 20.6666C16 20.6666 14 18.6666 12 18.6666C12 16.4573 13.7907 14.6666 16 14.6666C18.2093 14.6666 20 16.4573 20 18.6666ZM16 13.3333C17.4733 13.3333 18.6667 12.14 18.6667 10.6666C18.6667 9.19328 17.4733 7.99995 16 7.99995C14.5267 7.99995 13.3333 9.19328 13.3333 10.6666C13.3333 12.14 14.5267 13.3333 16 13.3333ZM31.8507 26.8213C31.5507 27.5426 30.8787 27.992 30.096 27.992H28.0133V30.0746C28.0133 31.2666 27.0413 31.9773 26.1013 31.9773C25.6 31.9773 25.1413 31.784 24.7747 31.42L18.9507 25.596C18.9507 25.596 18.9467 25.588 18.9427 25.584C18.72 25.7626 18.5013 25.9453 18.244 26.088C17.5467 26.4746 16.7653 26.668 15.984 26.668C15.2027 26.668 14.4227 26.4746 13.7267 26.0893C13.464 25.9426 13.24 25.7573 13.0147 25.5746C13.008 25.5813 13.0067 25.5893 13.0013 25.596L7.18001 31.4173C6.81067 31.784 6.35201 31.9773 5.85067 31.9773C4.91067 31.9773 3.93734 31.2666 3.93734 30.0746V27.992H1.85467C1.07334 27.992 0.401339 27.5426 0.101339 26.8213C-0.198661 26.096 -0.0399945 25.3026 0.514672 24.7493L5.23334 20.0293C5.15867 19.4666 5.17601 18.9053 5.30267 18.3546C5.38801 17.976 5.20667 17.5666 4.84934 17.3346C4.18401 16.9066 3.64534 16.3253 3.24667 15.6066C2.86667 14.9186 2.66667 14.1306 2.67067 13.3253C2.66667 12.5333 2.86534 11.7439 3.24534 11.0586C3.64534 10.3386 4.18401 9.75728 4.84934 9.32795C5.20667 9.09728 5.38934 8.68928 5.30267 8.30928C5.12267 7.51995 5.14934 6.71062 5.38267 5.90128C5.82001 4.37328 7.04401 3.14928 8.57467 2.70928C9.38401 2.47862 10.192 2.45462 10.98 2.62928C11.3653 2.71595 11.7693 2.53328 12 2.17595C12.4293 1.51195 13.0093 0.973284 13.7267 0.574617C15.12 -0.198716 16.852 -0.197383 18.244 0.574617C18.964 0.974617 19.5453 1.51328 19.9733 2.17728C20.204 2.53462 20.6107 2.70928 20.992 2.63195C21.78 2.45328 22.588 2.47995 23.396 2.70928C24.928 3.14928 26.1507 4.37195 26.5907 5.90395C26.8213 6.70928 26.848 7.51862 26.6693 8.30928C26.584 8.68928 26.7653 9.09862 27.1227 9.32928C27.7867 9.75728 28.3253 10.34 28.7253 11.0586C29.1053 11.7453 29.304 12.5333 29.3 13.3386C29.304 14.1306 29.1053 14.92 28.7253 15.6053C28.3253 16.3253 27.7867 16.908 27.1227 17.336C26.7653 17.5666 26.5827 17.9746 26.6693 18.3546C26.796 18.912 26.8133 19.4786 26.736 20.0466L31.44 24.7506C31.9933 25.3026 32.1507 26.096 31.8507 26.8213ZM10.7573 24.068C10.04 24.1946 9.30667 24.1626 8.57467 23.956C7.73734 23.716 7.01067 23.228 6.43467 22.5986L3.70801 25.3253H5.26934C6.00534 25.3253 6.60267 25.9213 6.60267 26.6586V28.2213L10.7573 24.068ZM21.5813 21.432C21.928 21.5133 22.2907 21.4973 22.6613 21.3906C23.304 21.2066 23.8413 20.6706 24.0253 20.028C24.132 19.6573 24.1453 19.2933 24.068 18.9453C23.7347 17.4786 24.38 15.9306 25.6773 15.0946C25.968 14.9066 26.2093 14.644 26.3933 14.3133C26.552 14.0266 26.636 13.6893 26.6347 13.34C26.636 12.9773 26.552 12.64 26.3933 12.352C26.2093 12.0213 25.9693 11.76 25.6787 11.572C24.3827 10.736 23.736 9.18795 24.0693 7.71995C24.148 7.37328 24.1333 7.01062 24.028 6.63995C23.8427 5.99595 23.3067 5.45862 22.6627 5.27462C22.2933 5.17062 21.9307 5.15328 21.5827 5.23462C20.12 5.56795 18.5693 4.92128 17.7333 3.62528C17.5453 3.33462 17.2827 3.09328 16.9507 2.90928C16.3667 2.58662 15.608 2.58528 15.0213 2.90928C14.692 3.09195 14.4293 3.33195 14.24 3.62395C13.404 4.92128 11.8547 5.56662 10.3893 5.23195C10.0453 5.15595 9.68001 5.16795 9.30801 5.27462C8.66534 5.45862 8.12934 5.99462 7.94534 6.63728C7.83867 7.00928 7.82401 7.37195 7.90267 7.71862C8.23601 9.18662 7.58934 10.7346 6.29334 11.5693C6.00134 11.7586 5.76134 12.0213 5.57734 12.352C5.41867 12.6386 5.33467 12.976 5.33601 13.3253C5.33467 13.688 5.41867 14.0253 5.57734 14.3133C5.76001 14.6426 6.00134 14.9053 6.29334 15.0933C7.58934 15.9293 8.23601 17.4773 7.90267 18.9453C7.82401 19.292 7.83867 19.6533 7.94401 20.0253C8.12934 20.6693 8.66534 21.2066 9.30934 21.3906C9.67867 21.4946 10.0427 21.5106 10.3907 21.4306C10.6467 21.372 10.9067 21.3453 11.1653 21.3453C12.3773 21.3453 13.5507 21.972 14.2413 23.04C14.4293 23.332 14.692 23.572 15.0227 23.756C15.608 24.0786 16.3667 24.08 16.952 23.756C17.2827 23.5733 17.5453 23.332 17.7333 23.0413C18.5693 21.7453 20.1173 21.1066 21.584 21.432H21.5813ZM28.2427 25.3253L25.5253 22.608C24.952 23.232 24.2267 23.716 23.396 23.9533C22.6547 24.164 21.9133 24.1946 21.1907 24.064L25.3467 28.22V26.6573C25.3467 25.92 25.944 25.324 26.68 25.324L28.2427 25.3253Z",
      fill: "#3F3F3F"
    })))), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      style: {
        marginTop: 12,
        paddingTop: 12,
        borderTop: "1px dashed #ddd"
      }
    }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextareaControl, {
      label: `Ikona SVG dla opinii ${n} (opcjonalnie)`,
      help: "Wklej tag <svg>...</svg>. Puste = ikona globalna lub domy\u015Blna.",
      value: a[`review${n}IconSvg`] || "",
      onChange: v => setAttributes({
        [`review${n}IconSvg`]: v
      }),
      rows: 4
    })));
  })), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", {
    style: {
      fontSize: 12,
      color: "#999",
      marginTop: 16
    }
  }, "W edytorze widzisz wszystkie 8 slot\xF3w na opinie. Na froncie aktywuje si\u0119 slider \u2014 pokazuje ", a.visibleCount, " kart na raz, slider przewija pozosta\u0142e. Puste sloty s\u0105 pomijane na froncie.")));
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

/***/ "./src/glownaopinie/block.json"
/*!*************************************!*\
  !*** ./src/glownaopinie/block.json ***!
  \*************************************/
(module) {

module.exports = /*#__PURE__*/JSON.parse('{"$schema":"https://schemas.wp.org/trunk/block.json","apiVersion":3,"name":"ourblocktheme/glownaopinie","title":"Główna Opinie (Slider)","attributes":{"sectionTitle":{"type":"string","default":"Opinie naszych klientów"},"verifiedLabel":{"type":"string","default":"Zaufana Opinia potwierdzona zakupem"},"verifiedIconSvg":{"type":"string","default":""},"visibleCount":{"type":"number","default":3},"autoScroll":{"type":"boolean","default":true},"autoScrollMs":{"type":"number","default":6000},"review1Name":{"type":"string","default":"Joanna"},"review1Rating":{"type":"string","default":"5.0"},"review1Text":{"type":"string","default":"Często wyjeżdżam służbowo i potrzebowałam czegoś kompaktowego. Shav Woman jest lekka, a bateria trzyma tak długo, że nawet nie biorę ładowarki na tygodniowe wyjazdy. Rozwiązała mój problem z pakowaniem zbędnych kabli i szukaniem gniazdek w hotelowej łazience."},"review2Name":{"type":"string","default":"Ewelina"},"review2Rating":{"type":"string","default":"5.0"},"review2Text":{"type":"string","default":"Rano zawsze brakuje mi czasu, a golenie na sucho często kończyło się podrażnieniem. Tą maszynką mogę golić się pod prysznicem dzięki wodoodporności, co oszczędza mi mnóstwo czasu. Działa błyskawicznie i nie zapycha się, więc poranna toaleta stała się o wiele sprawniejsza."},"review3Name":{"type":"string","default":"Agnieszka"},"review3Rating":{"type":"string","default":"5.0"},"review3Text":{"type":"string","default":"Bałam się używać elektrycznych golarek w miejscach intymnych, bojąc się zacięć. Ta maszynka jest tak zaprojektowana, że sunie gładko i bezpiecznie nawet w trudniej dostępnych miejscach. Problem stresu przy depilacji bikini zniknął całkowicie, a efekt jest bardzo dokładny."},"review4Name":{"type":"string","default":""},"review4Rating":{"type":"string","default":"5.0"},"review4Text":{"type":"string","default":""},"review5Name":{"type":"string","default":""},"review5Rating":{"type":"string","default":"5.0"},"review5Text":{"type":"string","default":""},"review6Name":{"type":"string","default":""},"review6Rating":{"type":"string","default":"5.0"},"review6Text":{"type":"string","default":""},"review7Name":{"type":"string","default":""},"review7Rating":{"type":"string","default":"5.0"},"review7Text":{"type":"string","default":""},"review8Name":{"type":"string","default":""},"review8Rating":{"type":"string","default":"5.0"},"review8Text":{"type":"string","default":""},"review1IconSvg":{"type":"string","default":""},"review2IconSvg":{"type":"string","default":""},"review3IconSvg":{"type":"string","default":""},"review4IconSvg":{"type":"string","default":""},"review5IconSvg":{"type":"string","default":""},"review6IconSvg":{"type":"string","default":""},"review7IconSvg":{"type":"string","default":""},"review8IconSvg":{"type":"string","default":""}},"editorScript":"file:./index.js","render":"file:./render.php","supports":{"html":false,"anchor":true,"align":["wide","full"],"color":{"background":true,"text":true,"link":true,"gradients":true},"typography":{"fontSize":true,"lineHeight":true,"__experimentalFontFamily":true,"__experimentalFontWeight":true,"__experimentalFontStyle":true,"__experimentalTextTransform":true,"__experimentalLetterSpacing":true,"__experimentalTextDecoration":true},"spacing":{"padding":true,"margin":true,"blockGap":true},"__experimentalBorder":{"color":true,"radius":true,"style":true,"width":true}}}');

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
  !*** ./src/glownaopinie/index.js ***!
  \***********************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/blocks */ "@wordpress/blocks");
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _block_json__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./block.json */ "./src/glownaopinie/block.json");
/* harmony import */ var _edit__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./edit */ "./src/glownaopinie/edit.js");





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