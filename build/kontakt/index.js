/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/kontakt/edit.js"
/*!*****************************!*\
  !*** ./src/kontakt/edit.js ***!
  \*****************************/
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




function Edit({
  attributes,
  setAttributes
}) {
  const a = attributes;
  const [importJson, setImportJson] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.useState)("");
  const blockProps = (0,_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.useBlockProps)({
    className: "kontakt"
  });
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.useEffect)(() => {
    // Migracja FAQ (Legacy)
    if (!a.faqItems || a.faqItems.length === 0) {
      let migrated = [];
      for (let i = 1; i <= 10; i++) {
        if (a[`kontaktQuestion${i}`] || a[`kontaktAnswer${i}`]) {
          migrated.push({
            question: a[`kontaktQuestion${i}`] || "",
            answer: a[`kontaktAnswer${i}`] || ""
          });
        }
      }
      if (migrated.length > 0) {
        setAttributes({
          faqItems: migrated
        });
      }
    }

    // Migracja Info Kafelków (Legacy)
    if (!a.infoTiles || a.infoTiles.length === 0) {
      const migratedInfo = [];
      if (a.hurtTitle || a.hurtIntro || a.hurtButtonLabel) {
        migratedInfo.push({
          title: a.hurtTitle || "Sprzedaż hurtowa",
          intro: a.hurtIntro || "Jesteś zainteresowany współpracą z nami?",
          buttonLabel: a.hurtButtonLabel || "Dowiedz się więcej",
          buttonURL: a.hurtButtonURL || "/sprzedaz-hurtowa"
        });
      }
      if (a.zwrotyTitle || a.zwrotyIntro || a.zwrotyButtonLabel) {
        migratedInfo.push({
          title: a.zwrotyTitle || "Zwroty",
          intro: a.zwrotyIntro || "W ciągu 14 dni możesz zwrócić produkt kupiony przez internet.",
          buttonLabel: a.zwrotyButtonLabel || "Dowiedz się więcej",
          buttonURL: a.zwrotyButtonURL || "/zwrot"
        });
      }
      if (a.reklamacjeTitle || a.reklamacjeIntro || a.reklamacjeButtonLabel) {
        migratedInfo.push({
          title: a.reklamacjeTitle || "Reklamacje",
          intro: a.reklamacjeIntro || "Chcesz dowiedzieć się jak złożyć reklamację?",
          buttonLabel: a.reklamacjeButtonLabel || "Dowiedz się więcej",
          buttonURL: a.reklamacjeButtonURL || "/zwrot"
        });
      }
      if (migratedInfo.length > 0) {
        setAttributes({
          infoTiles: migratedInfo
        });
      }
    }
  }, []);
  const contactMethods = a.contactMethods || [];
  const faqItems = a.faqItems || [];
  const infoTiles = a.infoTiles || [];
  const updateContactMethod = (index, field, value) => {
    const newItems = [...contactMethods];
    newItems[index] = {
      ...newItems[index],
      [field]: value
    };
    setAttributes({
      contactMethods: newItems
    });
  };
  const addContactMethod = () => {
    setAttributes({
      contactMethods: [...contactMethods, {
        type: 'phone',
        label: '',
        link: ''
      }]
    });
  };
  const removeContactMethod = index => {
    const newItems = [...contactMethods];
    newItems.splice(index, 1);
    setAttributes({
      contactMethods: newItems
    });
  };
  const updateFaqItem = (index, field, value) => {
    const newItems = [...faqItems];
    newItems[index] = {
      ...newItems[index],
      [field]: value
    };
    setAttributes({
      faqItems: newItems
    });
  };
  const addFaqItem = () => {
    setAttributes({
      faqItems: [...faqItems, {
        question: "",
        answer: ""
      }]
    });
  };
  const removeFaqItem = index => {
    const newItems = [...faqItems];
    newItems.splice(index, 1);
    setAttributes({
      faqItems: newItems
    });
  };
  const updateInfoTile = (index, field, value) => {
    const newItems = [...infoTiles];
    newItems[index] = {
      ...newItems[index],
      [field]: value
    };
    setAttributes({
      infoTiles: newItems
    });
  };
  const addInfoTile = () => {
    setAttributes({
      infoTiles: [...infoTiles, {
        title: "",
        intro: "",
        buttonLabel: "",
        buttonURL: ""
      }]
    });
  };
  const removeInfoTile = index => {
    const newItems = [...infoTiles];
    newItems.splice(index, 1);
    setAttributes({
      infoTiles: newItems
    });
  };
  const iconOptions = [{
    label: 'Telefon',
    value: 'phone'
  }, {
    label: 'E-mail',
    value: 'email'
  }, {
    label: 'WhatsApp',
    value: 'whatsapp'
  }, {
    label: 'Własny SVG (kod)',
    value: 'custom'
  }, {
    label: 'Własny obraz/SVG (biblioteka)',
    value: 'image'
  }, {
    label: 'Inne (Brak)',
    value: 'other'
  }];
  const getIconSvg = item => {
    const type = item.type;
    if (type === 'custom' && item.customSvg) {
      return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
        dangerouslySetInnerHTML: {
          __html: item.customSvg
        },
        style: {
          display: 'flex'
        }
      });
    }
    if (type === 'image' && item.customImage) {
      return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("img", {
        src: item.customImage,
        alt: "icon",
        style: {
          width: 24,
          height: 24,
          objectFit: 'contain'
        }
      });
    }
    if (type === 'phone') {
      return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
        width: "24",
        height: "24",
        viewBox: "0 0 24 24",
        fill: "none",
        xmlns: "http://www.w3.org/2000/svg"
      }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
        d: "M22.17 1.81994L21.12 0.909942C19.91 -0.300059 17.95 -0.300059 16.74 0.909942C16.71 0.939942 14.86 3.34994 14.86 3.34994C13.72 4.54994 13.72 6.43994 14.86 7.62994L16.02 9.08994C14.56 12.3999 12.29 14.6799 9.09 16.0399L7.63 14.8699C6.44 13.7199 4.54 13.7199 3.35 14.8699C3.35 14.8699 0.940004 16.7199 0.910004 16.7499C-0.299996 17.9599 -0.299996 19.9199 0.860004 21.0799L1.86 22.2299C3.01 23.3799 4.56 24.0099 6.24 24.0099C13.88 24.0099 24 13.8799 24 6.24994C24 4.57994 23.37 3.01994 22.17 1.82994V1.81994ZM6.24 21.9999C5.1 21.9999 4.05 21.5799 3.33 20.8499L2.33 19.6999C1.92 19.2899 1.9 18.6199 2.29 18.1899C2.29 18.1899 4.68 16.3499 4.71 16.3199C5.12 15.9099 5.84 15.9099 6.26 16.3199C6.29 16.3499 8.3 17.9599 8.3 17.9599C8.58 18.1799 8.95 18.2399 9.28 18.1099C13.42 16.5299 16.39 13.5699 18.1 9.29994C18.23 8.96994 18.18 8.58994 17.95 8.29994C17.95 8.29994 16.34 6.27994 16.32 6.25994C15.89 5.82994 15.89 5.13994 16.32 4.70994C16.35 4.67994 18.19 2.28994 18.19 2.28994C18.62 1.89994 19.29 1.90994 19.75 2.36994L20.8 3.27994C21.57 4.04994 22 5.09994 22 6.23994C22 13.1999 12.23 21.9999 6.24 21.9999Z",
        fill: "#3F3F3F"
      }));
    }
    if (type === 'email') {
      return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
        width: "24",
        height: "24",
        viewBox: "0 0 24 24",
        fill: "none",
        xmlns: "http://www.w3.org/2000/svg"
      }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
        d: "M20 4H4C2.9 4 2.01 4.9 2.01 6L2 18C2 19.1 2.9 20 4 20H20C21.1 20 22 19.1 22 18V6C22 4.9 21.1 4 20 4ZM20 8L12 13L4 8V6L12 11L20 6V8Z",
        fill: "#3F3F3F"
      }));
    }
    if (type === 'whatsapp') {
      return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
        width: "24",
        height: "24",
        viewBox: "0 0 24 24",
        fill: "none",
        xmlns: "http://www.w3.org/2000/svg"
      }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
        d: "M12.04 2C6.58 2 2.13 6.45 2.13 11.91C2.13 13.66 2.59 15.36 3.45 16.86L2 22L7.3 20.62C8.75 21.41 10.38 21.83 12.04 21.83C17.5 21.83 21.95 17.38 21.95 11.92C21.95 9.27 20.92 6.78 19.05 4.91C17.18 3.03 14.69 2 12.04 2ZM12.05 20.16C10.6 20.16 9.18 19.78 7.95 19.05L7.66 18.88L4.47 19.71L5.32 16.59L5.13 16.28C4.33 15.01 3.91 13.5 3.91 11.91C3.91 7.42 7.56 3.77 12.05 3.77C14.23 3.77 16.27 4.62 17.81 6.16C19.35 7.7 20.2 9.74 20.2 11.92C20.2 16.41 16.54 20.16 12.05 20.16ZM16.52 14.07C16.27 13.95 15.07 13.35 14.85 13.27C14.63 13.19 14.45 13.15 14.28 13.39C14.11 13.64 13.62 14.23 13.47 14.41C13.32 14.59 13.17 14.61 12.92 14.49C12.67 14.37 11.89 14.11 10.96 13.28C10.23 12.63 9.73 11.82 9.58 11.57C9.43 11.32 9.56 11.19 9.68 11.07C9.8 10.95 9.94 10.78 10.07 10.63C10.2 10.48 10.25 10.37 10.35 10.21C10.45 10.05 10.4 9.9 10.35 9.78C10.3 9.66 9.8 8.44 9.59 7.94C9.39 7.46 9.18 7.52 9.03 7.52C8.88 7.52 8.71 7.52 8.53 7.52C8.36 7.52 8.08 7.58 7.85 7.82C7.63 8.07 6.98 8.68 6.98 9.92C6.98 11.16 7.88 12.35 8.01 12.52C8.13 12.69 9.77 15.34 12.37 16.35C14.55 17.2 15.01 17.03 15.52 16.96C16.03 16.89 17.15 16.27 17.37 15.6C17.59 14.93 17.59 14.35 17.52 14.23C17.44 14.09 17.27 14.07 17.02 13.95L16.52 14.07Z",
        fill: "#3F3F3F"
      }));
    }
    return null;
  };
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    ...blockProps
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.InspectorControls, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
    title: "Metody kontaktu",
    initialOpen: true
  }, contactMethods.map((item, i) => (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    key: i,
    style: {
      border: '1px solid #ddd',
      padding: '12px',
      marginBottom: '12px',
      borderRadius: '4px',
      backgroundColor: '#f9f9f9'
    }
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.SelectControl, {
    label: "Typ",
    value: item.type,
    options: iconOptions,
    onChange: v => updateContactMethod(i, "type", v)
  }), item.type === 'custom' && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextareaControl, {
    label: "Wklej kod SVG",
    value: item.customSvg || "",
    onChange: v => updateContactMethod(i, "customSvg", v),
    help: "Wklej pe\u0142ny kod <svg>...</svg>"
  }), item.type === 'image' && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    style: {
      marginBottom: 16
    }
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.MediaUploadCheck, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.MediaUpload, {
    onSelect: media => updateContactMethod(i, "customImage", media.url),
    allowedTypes: ["image"],
    value: item.customImage,
    render: ({
      open
    }) => (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Button, {
      variant: "secondary",
      onClick: open
    }, item.customImage ? "Zmień ikonę" : "Wybierz ikonę z biblioteki")
  })), item.customImage && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("img", {
    src: item.customImage,
    style: {
      width: 24,
      display: 'block',
      marginTop: 8
    }
  })), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    label: "Etykieta (tekst)",
    value: item.label,
    onChange: v => updateContactMethod(i, "label", v)
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    label: "Link (href)",
    value: item.link,
    onChange: v => updateContactMethod(i, "link", v),
    help: "np. tel:+48690801270 lub mailto:kontakt@shavwoman.pl"
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    style: {
      marginTop: 12,
      padding: 12,
      background: '#fff',
      border: '1px solid #ccc',
      borderRadius: 4
    }
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("strong", null, "Rozszerzone szczeg\xF3\u0142y (opcjonalnie)"), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", {
    style: {
      fontSize: 12,
      color: '#666'
    }
  }, "Zdj\u0119cie lub dodatkowy tekst pod metod\u0105 kontaktu."), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    label: "Tekst nad zdj\u0119ciem",
    value: item.qrText || '',
    onChange: v => updateContactMethod(i, "qrText", v)
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.MediaUploadCheck, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.MediaUpload, {
    onSelect: media => updateContactMethod(i, "qrImage", media.url),
    allowedTypes: ["image"],
    value: item.qrImage,
    render: ({
      open
    }) => (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Button, {
      variant: "secondary",
      onClick: open
    }, item.qrImage ? "Zmień zdjęcie" : "Wybierz zdjęcie")
  })), item.qrImage && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("img", {
    src: item.qrImage,
    style: {
      width: '100px',
      display: 'block',
      marginTop: 8
    }
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    label: "Tekst przycisku pod zdj\u0119ciem",
    value: item.qrButton || '',
    onChange: v => updateContactMethod(i, "qrButton", v),
    style: {
      marginTop: 12
    }
  })), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Button, {
    isDestructive: true,
    variant: "link",
    onClick: () => removeContactMethod(i),
    style: {
      padding: 0,
      marginTop: 12
    }
  }, "Usu\u0144"))), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Button, {
    variant: "secondary",
    onClick: addContactMethod,
    style: {
      width: "100%",
      justifyContent: "center"
    }
  }, "+ Dodaj metod\u0119 kontaktu")), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
    title: "Legacy FAQ (Stare dane z Fallbacku)",
    initialOpen: false
  }, faqItems.map((item, i) => (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    key: i,
    style: {
      border: '1px solid #ddd',
      padding: '12px',
      marginBottom: '12px',
      borderRadius: '4px',
      backgroundColor: '#f9f9f9'
    }
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    label: `Pytanie ${i + 1}`,
    value: item.question,
    onChange: v => updateFaqItem(i, "question", v)
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextareaControl, {
    label: `Odpowiedź ${i + 1}`,
    value: item.answer,
    onChange: v => updateFaqItem(i, "answer", v),
    rows: 3
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Button, {
    isDestructive: true,
    variant: "link",
    onClick: () => removeFaqItem(i),
    style: {
      padding: 0
    }
  }, "Usu\u0144 to pytanie"))), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Button, {
    variant: "secondary",
    onClick: addFaqItem,
    style: {
      width: "100%",
      justifyContent: "center"
    }
  }, "+ Dodaj nowe pytanie FAQ")), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
    title: "Kafelki (Dolne boxy informacyjne)",
    initialOpen: false
  }, infoTiles.map((item, i) => (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    key: i,
    style: {
      border: '1px solid #ddd',
      padding: '12px',
      marginBottom: '12px',
      borderRadius: '4px',
      backgroundColor: '#f9f9f9'
    }
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    label: `Tytuł ${i + 1}`,
    value: item.title,
    onChange: v => updateInfoTile(i, "title", v)
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    label: `Etykieta przycisku ${i + 1}`,
    value: item.buttonLabel,
    onChange: v => updateInfoTile(i, "buttonLabel", v)
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    label: `URL przycisku ${i + 1}`,
    value: item.buttonURL,
    onChange: v => updateInfoTile(i, "buttonURL", v)
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Button, {
    isDestructive: true,
    variant: "link",
    onClick: () => removeInfoTile(i),
    style: {
      padding: 0
    }
  }, "Usu\u0144 ten kafelek"))), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Button, {
    variant: "secondary",
    onClick: addInfoTile,
    style: {
      width: "100%",
      justifyContent: "center"
    }
  }, "+ Dodaj nowy kafelek")), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
    title: "T\u0142umaczenia AI (JSON)",
    initialOpen: false
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextareaControl, {
    label: "Skopiuj ten JSON dla AI",
    value: (() => {
      const data = {
        title: a.title || "",
        subtitle: a.subtitle || "",
        contactMethods: contactMethods,
        faqItems: faqItems,
        infoTiles: infoTiles
      };
      return JSON.stringify(data, null, 2);
    })(),
    readOnly: true,
    rows: 10,
    help: "Skopiuj i wklej do AI z pro\u015Bb\u0105 o przet\u0142umaczenie samych warto\u015Bci (etykiet)."
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
        const keys = ["title", "subtitle"];
        keys.forEach(k => {
          if (parsed[k] !== undefined) updates[k] = parsed[k];
        });
        if (parsed.contactMethods && Array.isArray(parsed.contactMethods)) updates.contactMethods = parsed.contactMethods;
        if (parsed.faqItems && Array.isArray(parsed.faqItems)) updates.faqItems = parsed.faqItems;
        if (parsed.infoTiles && Array.isArray(parsed.infoTiles)) updates.infoTiles = parsed.infoTiles;
        setAttributes(updates);
        alert("Zaktualizowano pomyślnie!");
        setImportJson("");
      } catch (e) {
        alert("Błąd! Niepoprawny format JSON.");
      }
    },
    style: {
      width: "100%",
      justifyContent: "center"
    }
  }, "Importuj t\u0142umaczenie"))), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "kontakt__inner"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.RichText, {
    tagName: "h1",
    className: "kontakt__title",
    value: a.title,
    onChange: v => setAttributes({
      title: v
    }),
    placeholder: "Jeste\u015Bmy tu \u017Ceby Ci pom\xF3c"
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.RichText, {
    tagName: "p",
    className: "kontakt__subtitle",
    value: a.subtitle,
    onChange: v => setAttributes({
      subtitle: v
    }),
    placeholder: "Masz pytanie? Skontaktuj si\u0119 z nami. Jeste\u015Bmy dost\u0119pni poniedzia\u0142ek - pi\u0105tek 8:00-16:00"
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "kontakt__methods"
  }, contactMethods.map((item, i) => (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    key: i,
    className: "kontakt__method-wrapper",
    style: {
      display: 'flex',
      flexDirection: 'column',
      alignItems: 'center',
      gap: 16
    }
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "kontakt__method"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "kontakt__method-icon"
  }, getIconSvg(item)), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.RichText, {
    tagName: "span",
    className: "kontakt__method-text",
    value: item.label,
    onChange: v => updateContactMethod(i, "label", v),
    placeholder: "Wpisz kontakt (np. numer telefonu)..."
  })), item.type === 'whatsapp' && (item.qrText || item.qrImage || item.qrButton) && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "kontakt__method-qr",
    style: {
      display: 'flex',
      flexDirection: 'column',
      alignItems: 'center',
      gap: 8,
      padding: 16,
      background: '#fff',
      border: '1px solid #eee',
      borderRadius: 8
    }
  }, item.qrText && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    style: {
      fontSize: 13,
      fontWeight: 600
    }
  }, item.qrText), item.qrImage && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("img", {
    src: item.qrImage,
    alt: "QR",
    style: {
      maxWidth: 150
    }
  }), item.qrButton && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    style: {
      background: '#F4E1D9',
      padding: '6px 12px',
      borderRadius: 8,
      fontSize: 12,
      fontWeight: 600
    }
  }, item.qrButton))))), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "kontakt__tiles"
  }, infoTiles.map((item, i) => (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    key: i,
    className: "kontakt__tile"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.RichText, {
    tagName: "p",
    className: "kontakt__tile-title",
    value: item.title,
    onChange: v => updateInfoTile(i, "title", v),
    placeholder: "Wpisz nazw\u0119 (np. Shav Woman)"
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.RichText, {
    tagName: "span",
    className: "kontakt__tile-link",
    value: item.buttonLabel,
    onChange: v => updateInfoTile(i, "buttonLabel", v),
    placeholder: "Wpisz nazw\u0119 linku..."
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

/***/ "@wordpress/element"
/*!*********************************!*\
  !*** external ["wp","element"] ***!
  \*********************************/
(module) {

module.exports = window["wp"]["element"];

/***/ },

/***/ "./src/kontakt/block.json"
/*!********************************!*\
  !*** ./src/kontakt/block.json ***!
  \********************************/
(module) {

module.exports = /*#__PURE__*/JSON.parse('{"$schema":"https://schemas.wp.org/trunk/block.json","apiVersion":3,"name":"ourblocktheme/kontakt","title":"Kontakt","attributes":{"faqItems":{"type":"array","default":[]},"infoTiles":{"type":"array","default":[{"title":"Dostępne metody płatności.","buttonLabel":"Dowiedz się więcej","buttonURL":"/metody-platnosci"},{"title":"Dostępne metody wysyłki.","buttonLabel":"Dowiedz się więcej","buttonURL":"/metody-wysylki"},{"title":"Chcesz zwrócić produkt?","buttonLabel":"Dowiedz się więcej","buttonURL":"/zwrot"},{"title":"Chcesz dowiedzieć się jak złożyć reklamację?","buttonLabel":"Dowiedz się więcej","buttonURL":"/reklamacje"}]},"contactMethods":{"type":"array","default":[{"type":"phone","label":"690 801 270","link":"tel:+48690801270"},{"type":"email","label":"kontakt@shavwoman.pl - na de what\'s up?","link":"mailto:kontakt@shavwoman.pl"}]},"title":{"type":"string","default":"Kontakt"},"subtitle":{"type":"string","default":"Biuro obsługi klienta"},"obsługaTitle":{"type":"string","default":"Obsługa klienta"},"obsługaIntro":{"type":"string","default":"Masz pytanie? Skontaktuj się z nami!"},"phone":{"type":"string","default":"690 801 270"},"phoneHref":{"type":"string","default":"tel:+48690801270"},"email":{"type":"string","default":"kontakt@shavwoman.pl"},"emailHref":{"type":"string","default":"mailto:kontakt@shavwoman.pl"},"hours":{"type":"string","default":"poniedziałek - piątek: 8:00 - 16:00"},"faqTitle":{"type":"string","default":"Najczęściej zadawane pytania"},"hurtTitle":{"type":"string","default":"Sprzedaż hurtowa"},"hurtIntro":{"type":"string","default":"Jesteś zainteresowany współpracą z nami?"},"hurtButtonLabel":{"type":"string","default":"Dowiedz się więcej"},"hurtButtonURL":{"type":"string","default":"/sprzedaz-hurtowa"},"zwrotyTitle":{"type":"string","default":"Zwroty"},"zwrotyIntro":{"type":"string","default":"W ciągu 14 dni możesz zwrócić produkt kupiony przez internet."},"zwrotyButtonLabel":{"type":"string","default":"Dowiedz się więcej"},"zwrotyButtonURL":{"type":"string","default":"/zwrot"},"reklamacjeTitle":{"type":"string","default":"Reklamacje"},"reklamacjeIntro":{"type":"string","default":"Chcesz dowiedzieć się jak złożyć reklamację?"},"reklamacjeButtonLabel":{"type":"string","default":"Dowiedz się więcej"},"reklamacjeButtonURL":{"type":"string","default":"/zwrot"},"kontaktQuestion1":{"type":"string","default":"Ile trwa realizacja zamówienia?"},"kontaktAnswer1":{"type":"string","default":"Realizacja i dostawa wszystkich zamówień ze strony Shav odbywa się w ciągu 1-2 dni roboczych."},"kontaktQuestion2":{"type":"string","default":"Jakie są dostępne formy dostawy?"},"kontaktAnswer2":{"type":"string","default":"Oferujemy trzy opcje dostawy: przesyłkę kurierską InPost (darmowa), Paczkomaty InPost (darmowa) oraz przesyłkę kurierską InPost pobraniową (+4,99 zł). Dla zamówień poniżej 100 zł koszt wysyłki zwiększa się o 9,99 zł."},"kontaktQuestion3":{"type":"string","default":"Jakie są dostępne formy płatności?"},"kontaktAnswer3":{"type":"string","default":"Akceptujemy płatności poprzez PayU, BLIK, płatności kartą oraz płatności za pobraniem."},"kontaktQuestion4":{"type":"string","default":"Jak mogę śledzić status mojego zamówienia?"},"kontaktAnswer4":{"type":"string","default":"Link do śledzenia paczki otrzymasz w momencie przekazania zamówienia do realizacji (maksymalnie kolejnego dnia roboczego) na adres email podany w zamówieniu."},"kontaktQuestion5":{"type":"string","default":"Czy jest możliwa wysyłka zagranicę?"},"kontaktAnswer5":{"type":"string","default":"Tak, w takim przypadku prosimy o kontakt mailowy: kontakt@shavwoman.pl"},"kontaktQuestion6":{"type":"string","default":"Gdzie znajdę dowód zakupu?"},"kontaktAnswer6":{"type":"string","default":"Paragon lub fakturę otrzymasz w momencie przekazania zamówienia do realizacji (maksymalnie kolejnego dnia roboczego) na adres email podany w zamówieniu."},"kontaktQuestion7":{"type":"string","default":""},"kontaktAnswer7":{"type":"string","default":""},"kontaktQuestion8":{"type":"string","default":""},"kontaktAnswer8":{"type":"string","default":""},"kontaktQuestion9":{"type":"string","default":""},"kontaktAnswer9":{"type":"string","default":""},"kontaktQuestion10":{"type":"string","default":""},"kontaktAnswer10":{"type":"string","default":""}},"editorScript":"file:./index.js","render":"file:./render.php","supports":{"html":false,"anchor":true,"align":["wide","full"],"color":{"background":true,"text":true,"link":true,"gradients":true},"typography":{"fontSize":true,"lineHeight":true,"__experimentalFontFamily":true,"__experimentalFontWeight":true,"__experimentalFontStyle":true,"__experimentalTextTransform":true,"__experimentalLetterSpacing":true,"__experimentalTextDecoration":true},"spacing":{"padding":true,"margin":true,"blockGap":true},"__experimentalBorder":{"color":true,"radius":true,"style":true,"width":true}}}');

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
/*!******************************!*\
  !*** ./src/kontakt/index.js ***!
  \******************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/blocks */ "@wordpress/blocks");
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _block_json__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./block.json */ "./src/kontakt/block.json");
/* harmony import */ var _edit__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./edit */ "./src/kontakt/edit.js");





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