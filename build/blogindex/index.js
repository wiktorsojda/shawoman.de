/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/blogindex/edit.js"
/*!*******************************!*\
  !*** ./src/blogindex/edit.js ***!
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
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__);





function Edit(props) {
  var _attributes$title, _attributes$subtitle, _attributes$buttonTex, _attributes$heroButto, _attributes$gridTitle, _attributes$noPostsTe, _attributes$sidebarCa, _attributes$featuredL, _attributes$videoUrl, _attributes$categoryP, _attributes$backToBlo, _attributes$heroBgIma, _attributes$aboutImag, _attributes$aboutTitl, _attributes$aboutText, _attributes$findUsTit, _attributes$igLink, _attributes$tiktokLin, _attributes$fbLink, _attributes$showIg, _attributes$showTikto, _attributes$showFb, _attributes$showViews, _attributes$showDates;
  const blockProps = (0,_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.useBlockProps)();
  const {
    attributes
  } = props;
  const title = (_attributes$title = attributes?.title) !== null && _attributes$title !== void 0 ? _attributes$title : 'BlendyBlog';
  const subtitle = (_attributes$subtitle = attributes?.subtitle) !== null && _attributes$subtitle !== void 0 ? _attributes$subtitle : 'Twój codzienny miks przepisów i lifestylowych inspiracji.';
  const buttonText = (_attributes$buttonTex = attributes?.buttonText) !== null && _attributes$buttonTex !== void 0 ? _attributes$buttonTex : 'Przejdź do wpisu';
  const heroButtonText = (_attributes$heroButto = attributes?.heroButtonText) !== null && _attributes$heroButto !== void 0 ? _attributes$heroButto : 'Czytaj wpisy';
  const gridTitle = (_attributes$gridTitle = attributes?.gridTitle) !== null && _attributes$gridTitle !== void 0 ? _attributes$gridTitle : 'Wszystkie wpisy';
  const noPostsText = (_attributes$noPostsTe = attributes?.noPostsText) !== null && _attributes$noPostsTe !== void 0 ? _attributes$noPostsTe : 'Brak wpisów do wyświetlenia.';
  const sidebarCategoriesTitle = (_attributes$sidebarCa = attributes?.sidebarCategoriesTitle) !== null && _attributes$sidebarCa !== void 0 ? _attributes$sidebarCa : 'KATEGORIE';
  const featuredLabel = (_attributes$featuredL = attributes?.featuredLabel) !== null && _attributes$featuredL !== void 0 ? _attributes$featuredL : 'Najnowszy wpis';
  const videoUrl = (_attributes$videoUrl = attributes?.videoUrl) !== null && _attributes$videoUrl !== void 0 ? _attributes$videoUrl : 'https://blendygo.pl/wp-content/uploads/2023/08/lifestylowy-2.mp4';
  const categoryPrefixText = (_attributes$categoryP = attributes?.categoryPrefixText) !== null && _attributes$categoryP !== void 0 ? _attributes$categoryP : 'Przeglądasz wpisy z kategorii: ';
  const backToBlogUrl = (_attributes$backToBlo = attributes?.backToBlogUrl) !== null && _attributes$backToBlo !== void 0 ? _attributes$backToBlo : '/blog/';
  const heroBgImage = (_attributes$heroBgIma = attributes?.heroBgImage) !== null && _attributes$heroBgIma !== void 0 ? _attributes$heroBgIma : '';
  const aboutImage = (_attributes$aboutImag = attributes?.aboutImage) !== null && _attributes$aboutImag !== void 0 ? _attributes$aboutImag : '';
  const aboutTitle = (_attributes$aboutTitl = attributes?.aboutTitle) !== null && _attributes$aboutTitl !== void 0 ? _attributes$aboutTitl : 'O NAS';
  const aboutText = (_attributes$aboutText = attributes?.aboutText) !== null && _attributes$aboutText !== void 0 ? _attributes$aboutText : 'Cześć, tu ekipa BlendyGo! 🥤\n\nWierzymy, że zdrowe nawyki mogą być proste i przyjemne. Na tym blogu miksujemy dla Ciebie codzienne porcje pysznych przepisów, porad i czystej motywacji. Złap z nami swój rytm!\n\nWpadnij też na nasze social media po codzienną dawkę miksu inspiracji! 👇';
  const findUsTitle = (_attributes$findUsTit = attributes?.findUsTitle) !== null && _attributes$findUsTit !== void 0 ? _attributes$findUsTit : 'Znajdziesz nas na:';
  const igLink = (_attributes$igLink = attributes?.igLink) !== null && _attributes$igLink !== void 0 ? _attributes$igLink : 'https://instagram.com/blendygo';
  const tiktokLink = (_attributes$tiktokLin = attributes?.tiktokLink) !== null && _attributes$tiktokLin !== void 0 ? _attributes$tiktokLin : 'https://tiktok.com/@blendygo';
  const fbLink = (_attributes$fbLink = attributes?.fbLink) !== null && _attributes$fbLink !== void 0 ? _attributes$fbLink : 'https://facebook.com/blendygo';
  const showIg = (_attributes$showIg = attributes?.showIg) !== null && _attributes$showIg !== void 0 ? _attributes$showIg : true;
  const showTiktok = (_attributes$showTikto = attributes?.showTiktok) !== null && _attributes$showTikto !== void 0 ? _attributes$showTikto : true;
  const showFb = (_attributes$showFb = attributes?.showFb) !== null && _attributes$showFb !== void 0 ? _attributes$showFb : true;
  const showViewsCounter = (_attributes$showViews = attributes?.showViewsCounter) !== null && _attributes$showViews !== void 0 ? _attributes$showViews : false;
  const showDates = (_attributes$showDates = attributes?.showDates) !== null && _attributes$showDates !== void 0 ? _attributes$showDates : true;
  const [importJson, setImportJson] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_4__.useState)("");
  const getExportJson = () => {
    const data = {
      title: title || '',
      subtitle: subtitle || '',
      buttonText: buttonText || '',
      heroButtonText: heroButtonText || '',
      gridTitle: gridTitle || '',
      noPostsText: noPostsText || '',
      sidebarCategoriesTitle: sidebarCategoriesTitle || '',
      featuredLabel: featuredLabel || '',
      categoryPrefixText: categoryPrefixText || '',
      backToBlogUrl: backToBlogUrl || '',
      aboutTitle: aboutTitle || '',
      aboutText: aboutText || '',
      findUsTitle: findUsTitle || ''
    };
    return JSON.stringify(data, null, 2);
  };
  const handleImport = () => {
    try {
      const parsed = JSON.parse(importJson);
      const updates = {};
      if (parsed.title !== undefined) updates.title = parsed.title;
      if (parsed.subtitle !== undefined) updates.subtitle = parsed.subtitle;
      if (parsed.buttonText !== undefined) updates.buttonText = parsed.buttonText;
      if (parsed.heroButtonText !== undefined) updates.heroButtonText = parsed.heroButtonText;
      if (parsed.gridTitle !== undefined) updates.gridTitle = parsed.gridTitle;
      if (parsed.noPostsText !== undefined) updates.noPostsText = parsed.noPostsText;
      if (parsed.sidebarCategoriesTitle !== undefined) updates.sidebarCategoriesTitle = parsed.sidebarCategoriesTitle;
      if (parsed.featuredLabel !== undefined) updates.featuredLabel = parsed.featuredLabel;
      if (parsed.categoryPrefixText !== undefined) updates.categoryPrefixText = parsed.categoryPrefixText;
      if (parsed.backToBlogUrl !== undefined) updates.backToBlogUrl = parsed.backToBlogUrl;
      if (parsed.aboutTitle !== undefined) updates.aboutTitle = parsed.aboutTitle;
      if (parsed.aboutText !== undefined) updates.aboutText = parsed.aboutText;
      if (parsed.findUsTitle !== undefined) updates.findUsTitle = parsed.findUsTitle;
      props.setAttributes(updates);
      alert('Zaktualizowano pomyślnie!');
      setImportJson('');
    } catch (e) {
      alert('Błąd! Niepoprawny format JSON.');
    }
  };

  // Fetch latest posts for the slider preview
  const sliderPosts = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_2__.useSelect)(select => {
    return select("core").getEntityRecords("postType", "post", {
      per_page: 3,
      _embed: true
    });
  }, []);
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    ...blockProps
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.InspectorControls, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.PanelBody, {
    title: "T\u0142umaczenia AI (JSON)",
    initialOpen: false
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.TextareaControl, {
    label: "Skopiuj ten JSON dla AI",
    value: getExportJson(),
    readOnly: true,
    rows: 10,
    help: "Skopiuj i wklej do Gemini z pro\u015Bb\u0105 o przet\u0142umaczenie samych warto\u015Bci."
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.TextareaControl, {
    label: "Wklej przet\u0142umaczony JSON",
    value: importJson,
    onChange: setImportJson,
    rows: 10
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.Button, {
    variant: "primary",
    onClick: handleImport,
    style: {
      width: '100%',
      justifyContent: 'center'
    }
  }, "Importuj t\u0142umaczenie")), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.PanelBody, {
    title: "Teksty",
    initialOpen: false
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.TextControl, {
    label: "Prefix kategorii",
    value: categoryPrefixText,
    onChange: val => props.setAttributes({
      categoryPrefixText: val
    })
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.TextControl, {
    label: "Link URL powrotu do bloga (u\u017Cywany w archiwum)",
    value: backToBlogUrl,
    onChange: val => props.setAttributes({
      backToBlogUrl: val
    })
  })), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.PanelBody, {
    title: "O nas - Ustawienia",
    initialOpen: true
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.ToggleControl, {
    label: "Poka\u017C licznik wy\u015Bwietle\u0144 na kafelkach wpis\xF3w",
    checked: showViewsCounter,
    onChange: val => props.setAttributes({
      showViewsCounter: val
    }),
    help: "Je\u015Bli w\u0142\u0105czone, u\u017Cytkownicy b\u0119d\u0105 widzie\u0107 ikon\u0119 oka z liczb\u0105 wy\u015Bwietle\u0144 posta."
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("hr", {
    style: {
      margin: '1rem 0'
    }
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.TextControl, {
    label: "Tytu\u0142 sekcji (O NAS)",
    value: aboutTitle,
    onChange: val => props.setAttributes({
      aboutTitle: val
    })
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.TextControl, {
    label: "Tytu\u0142 sekcji (Znajdziesz nas na:)",
    value: findUsTitle,
    onChange: val => props.setAttributes({
      findUsTitle: val
    })
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.MediaUploadCheck, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.MediaUpload, {
    onSelect: media => props.setAttributes({
      aboutImage: media.url
    }),
    allowedTypes: ['image'],
    render: ({
      open
    }) => (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      style: {
        marginBottom: '1rem'
      }
    }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.Button, {
      variant: "secondary",
      onClick: open
    }, aboutImage ? 'Zmień zdjęcie' : 'Wybierz zdjęcie'), aboutImage && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.Button, {
      variant: "link",
      isDestructive: true,
      onClick: () => props.setAttributes({
        aboutImage: ''
      })
    }, "Usu\u0144 zdj\u0119cie"))
  })), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.TextareaControl, {
    label: "Tekst O nas",
    value: aboutText,
    onChange: val => props.setAttributes({
      aboutText: val
    }),
    rows: 6
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("hr", {
    style: {
      margin: '1rem 0'
    }
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("h3", null, "T\u0142o Banera Hero"), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.MediaUploadCheck, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.MediaUpload, {
    onSelect: media => props.setAttributes({
      heroBgImage: media.url,
      videoUrl: media.url
    }),
    allowedTypes: ['image'],
    value: heroBgImage,
    render: ({
      open
    }) => (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      style: {
        marginBottom: '1rem'
      }
    }, heroBgImage && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("img", {
      src: heroBgImage,
      style: {
        width: '100%',
        marginBottom: '10px'
      }
    }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.Button, {
      isPrimary: true,
      onClick: open
    }, heroBgImage ? 'Zmień tło banera' : 'Wybierz tło banera'), heroBgImage && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.Button, {
      isLink: true,
      isDestructive: true,
      onClick: () => props.setAttributes({
        heroBgImage: '',
        videoUrl: ''
      }),
      style: {
        display: 'block',
        marginTop: '10px'
      }
    }, "Usu\u0144 zdj\u0119cie"))
  })), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("hr", {
    style: {
      margin: '1rem 0'
    }
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.ToggleControl, {
    label: "Poka\u017C Instagram",
    checked: showIg,
    onChange: val => props.setAttributes({
      showIg: val
    })
  }), showIg && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.TextControl, {
    label: "Link Instagram",
    value: igLink,
    onChange: val => props.setAttributes({
      igLink: val
    })
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.ToggleControl, {
    label: "Poka\u017C TikTok",
    checked: showTiktok,
    onChange: val => props.setAttributes({
      showTiktok: val
    })
  }), showTiktok && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.TextControl, {
    label: "Link TikTok",
    value: tiktokLink,
    onChange: val => props.setAttributes({
      tiktokLink: val
    })
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.ToggleControl, {
    label: "Poka\u017C Facebook",
    checked: showFb,
    onChange: val => props.setAttributes({
      showFb: val
    })
  }), showFb && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.TextControl, {
    label: "Link Facebook",
    value: fbLink,
    onChange: val => props.setAttributes({
      fbLink: val
    })
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.ToggleControl, {
    label: "Poka\u017C daty wpis\xF3w",
    checked: showDates,
    onChange: val => props.setAttributes({
      showDates: val
    })
  }))), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "blog-index-wrapper"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("header", {
    className: "blog-hero-simple",
    style: {
      position: 'relative',
      width: '100%',
      height: '450px',
      display: 'flex',
      alignItems: 'center',
      justifyContent: 'center',
      overflow: 'hidden'
    }
  }, heroBgImage ? (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("img", {
    src: heroBgImage,
    alt: "",
    style: {
      position: 'absolute',
      top: 0,
      left: 0,
      width: '100%',
      height: '100%',
      objectFit: 'cover',
      zIndex: 1
    }
  }) : (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    style: {
      position: 'absolute',
      top: 0,
      left: 0,
      width: '100%',
      height: '100%',
      backgroundColor: '#333',
      zIndex: 1
    }
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    style: {
      position: 'absolute',
      top: 0,
      left: 0,
      width: '100%',
      height: '100%',
      backgroundColor: 'rgba(0,0,0,0.5)',
      zIndex: 2
    }
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.RichText, {
    tagName: "h1",
    className: "section-main-title",
    style: {
      color: '#FFF',
      position: 'relative',
      zIndex: 3,
      textAlign: 'center',
      margin: 0,
      fontSize: '3rem',
      fontWeight: 'bold',
      textTransform: 'uppercase'
    },
    value: title,
    onChange: val => props.setAttributes({
      title: val
    }),
    placeholder: "BlendyBlog",
    allowedFormats: ['core/bold', 'core/italic']
  })), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "blog-container"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "blog-layout"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("main", {
    className: "blog-main-content"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "blog-section-header"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("h2", {
    className: "blog-section-header__title"
  }, gridTitle, " (Podgl\u0105d)")), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "blog-archive-grid"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", {
    style: {
      padding: '2rem',
      background: '#fff',
      border: '1px dashed #ccc'
    }
  }, noPostsText)), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "blog-widget blog-widget--about blog-widget--about-wide",
    style: {
      marginTop: '0',
      background: '#FFFAF6',
      boxShadow: '0px 8px 25px rgba(0, 0, 0, 0.06)'
    }
  }, aboutImage && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "blog-widget-about-image-col"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("img", {
    src: aboutImage,
    alt: "O blogu",
    className: "blog-widget-about-image",
    style: {
      width: '100%',
      borderRadius: '15px',
      aspectRatio: '1/1',
      objectFit: 'cover'
    }
  })), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "blog-widget-about-content-col"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "blog-widget__header"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("h3", {
    className: "blog-widget__title"
  }, aboutTitle)), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "blog-widget-about-text",
    style: {
      whiteSpace: 'pre-wrap',
      marginBottom: '1.5rem'
    }
  }, aboutText), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "blog-widget-socials",
    style: {
      display: 'flex',
      gap: '1rem',
      marginTop: '1rem'
    }
  }, showIg && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    style: {
      width: '40px',
      height: '40px',
      background: '#E07800',
      borderRadius: '50%'
    }
  }), showTiktok && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    style: {
      width: '40px',
      height: '40px',
      background: '#E07800',
      borderRadius: '50%'
    }
  }), showFb && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    style: {
      width: '40px',
      height: '40px',
      background: '#E07800',
      borderRadius: '50%'
    }
  }))))), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("aside", {
    className: "blog-sidebar"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "blog-widget blog-widget--categories"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("input", {
    type: "checkbox",
    id: "mobile-cat-toggle-edit",
    className: "mobile-cat-toggle-checkbox",
    hidden: true
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("label", {
    htmlFor: "mobile-cat-toggle-edit",
    className: "blog-widget__header"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("h3", {
    className: "blog-widget__title"
  }, sidebarCategoriesTitle)), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "blog-widget__content"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("ul", {
    className: "blog-widget__list"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("li", null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    href: "#"
  }, "PRZYK\u0141ADOWA KATEGORIA"))))), (showIg || showTiktok || showFb) && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "blog-widget blog-widget--sidebar-socials",
    style: {
      textAlign: 'center',
      padding: '2rem',
      background: '#FFFAF6',
      borderRadius: '20px',
      boxShadow: '0px 8px 25px rgba(0, 0, 0, 0.06)'
    }
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("h3", {
    className: "blog-widget__title",
    style: {
      marginBottom: '1rem',
      fontSize: '1.1rem',
      color: '#111'
    }
  }, findUsTitle), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "blog-widget-socials",
    style: {
      display: 'flex',
      gap: '0.8rem',
      justifyContent: 'center'
    }
  }, showIg && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    style: {
      width: '40px',
      height: '40px',
      background: '#E07800',
      borderRadius: '50%'
    }
  }), showTiktok && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    style: {
      width: '40px',
      height: '40px',
      background: '#E07800',
      borderRadius: '50%'
    }
  }), showFb && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    style: {
      width: '40px',
      height: '40px',
      background: '#E07800',
      borderRadius: '50%'
    }
  }))))))));
}

/***/ },

/***/ "./src/blogindex/index.js"
/*!********************************!*\
  !*** ./src/blogindex/index.js ***!
  \********************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/blocks */ "@wordpress/blocks");
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _block_json__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./block.json */ "./src/blogindex/block.json");
/* harmony import */ var _edit__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./edit */ "./src/blogindex/edit.js");
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./style.scss */ "./src/blogindex/style.scss");




(0,_wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__.registerBlockType)(_block_json__WEBPACK_IMPORTED_MODULE_1__.name, {
  edit: _edit__WEBPACK_IMPORTED_MODULE_2__["default"]
});

/***/ },

/***/ "./src/blogindex/style.scss"
/*!**********************************!*\
  !*** ./src/blogindex/style.scss ***!
  \**********************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


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

/***/ "@wordpress/element"
/*!*********************************!*\
  !*** external ["wp","element"] ***!
  \*********************************/
(module) {

module.exports = window["wp"]["element"];

/***/ },

/***/ "./src/blogindex/block.json"
/*!**********************************!*\
  !*** ./src/blogindex/block.json ***!
  \**********************************/
(module) {

module.exports = /*#__PURE__*/JSON.parse('{"$schema":"https://schemas.wp.org/trunk/block.json","apiVersion":3,"name":"ourblocktheme/blogindex","textdomain":"mojmotyw","title":"Fictional University Blog Index","editorScript":"file:./index.js","render":"file:./render.php","style":"file:./style-index.css","viewScript":"file:./view.js","attributes":{"title":{"type":"string","default":"BlendyBlog"},"subtitle":{"type":"string","default":"Ihr täglicher Mix aus Rezepten und Lifestyle-Inspirationen."},"heroBgImage":{"type":"string","default":""},"backToBlogUrl":{"type":"string","default":"/blog/"},"buttonText":{"type":"string","default":"Zum Beitrag"},"heroButtonText":{"type":"string","default":"Beiträge lesen"},"gridTitle":{"type":"string","default":"Alle Beiträge"},"noPostsText":{"type":"string","default":"Keine Beiträge zum Anzeigen."},"sidebarCategoriesTitle":{"type":"string","default":"KATEGORIEN"},"featuredLabel":{"type":"string","default":"Neuester Beitrag"},"videoUrl":{"type":"string","default":"https://blendygo.pl/wp-content/uploads/2023/08/lifestylowy-2.mp4"},"categoryPrefixText":{"type":"string","default":"Sie sehen Beiträge aus der Kategorie: "},"aboutImage":{"type":"string","default":"https://blendygo.pl/wp-content/uploads/zespol-section-zdjecie.jpeg"},"aboutTitle":{"type":"string","default":"ÜBER UNS"},"aboutText":{"type":"string","default":"Hallo, hier ist das BlendyGo-Team! 🥤\\n\\nWir glauben, dass gesunde Gewohnheiten einfach und unterhaltsam sein können. Auf diesem Blog mixen wir für Sie täglich leckere Rezepte, Tipps und pure Motivation. Finden Sie mit uns Ihren Rhythmus!\\n\\nBesuchen Sie auch unsere Social Media für Ihre tägliche Portion Inspiration! 👇"},"findUsTitle":{"type":"string","default":"Finden Sie uns auf:"},"igLink":{"type":"string","default":"https://instagram.com/blendygo"},"tiktokLink":{"type":"string","default":"https://tiktok.com/@blendygo"},"fbLink":{"type":"string","default":"https://facebook.com/blendygo"},"showIg":{"type":"boolean","default":true},"showTiktok":{"type":"boolean","default":true},"showFb":{"type":"boolean","default":true},"showViewsCounter":{"type":"boolean","default":false},"showDates":{"type":"boolean","default":true}}}');

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
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = __webpack_modules__;
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/chunk loaded */
/******/ 	(() => {
/******/ 		var deferred = [];
/******/ 		__webpack_require__.O = (result, chunkIds, fn, priority) => {
/******/ 			if(chunkIds) {
/******/ 				priority = priority || 0;
/******/ 				for(var i = deferred.length; i > 0 && deferred[i - 1][2] > priority; i--) deferred[i] = deferred[i - 1];
/******/ 				deferred[i] = [chunkIds, fn, priority];
/******/ 				return;
/******/ 			}
/******/ 			var notFulfilled = Infinity;
/******/ 			for (var i = 0; i < deferred.length; i++) {
/******/ 				var [chunkIds, fn, priority] = deferred[i];
/******/ 				var fulfilled = true;
/******/ 				for (var j = 0; j < chunkIds.length; j++) {
/******/ 					if ((priority & 1 === 0 || notFulfilled >= priority) && Object.keys(__webpack_require__.O).every((key) => (__webpack_require__.O[key](chunkIds[j])))) {
/******/ 						chunkIds.splice(j--, 1);
/******/ 					} else {
/******/ 						fulfilled = false;
/******/ 						if(priority < notFulfilled) notFulfilled = priority;
/******/ 					}
/******/ 				}
/******/ 				if(fulfilled) {
/******/ 					deferred.splice(i--, 1)
/******/ 					var r = fn();
/******/ 					if (r !== undefined) result = r;
/******/ 				}
/******/ 			}
/******/ 			return result;
/******/ 		};
/******/ 	})();
/******/ 	
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
/******/ 	/* webpack/runtime/jsonp chunk loading */
/******/ 	(() => {
/******/ 		// no baseURI
/******/ 		
/******/ 		// object to store loaded and loading chunks
/******/ 		// undefined = chunk not loaded, null = chunk preloaded/prefetched
/******/ 		// [resolve, reject, Promise] = chunk loading, 0 = chunk loaded
/******/ 		var installedChunks = {
/******/ 			"blogindex/index": 0,
/******/ 			"blogindex/style-index": 0
/******/ 		};
/******/ 		
/******/ 		// no chunk on demand loading
/******/ 		
/******/ 		// no prefetching
/******/ 		
/******/ 		// no preloaded
/******/ 		
/******/ 		// no HMR
/******/ 		
/******/ 		// no HMR manifest
/******/ 		
/******/ 		__webpack_require__.O.j = (chunkId) => (installedChunks[chunkId] === 0);
/******/ 		
/******/ 		// install a JSONP callback for chunk loading
/******/ 		var webpackJsonpCallback = (parentChunkLoadingFunction, data) => {
/******/ 			var [chunkIds, moreModules, runtime] = data;
/******/ 			// add "moreModules" to the modules object,
/******/ 			// then flag all "chunkIds" as loaded and fire callback
/******/ 			var moduleId, chunkId, i = 0;
/******/ 			if(chunkIds.some((id) => (installedChunks[id] !== 0))) {
/******/ 				for(moduleId in moreModules) {
/******/ 					if(__webpack_require__.o(moreModules, moduleId)) {
/******/ 						__webpack_require__.m[moduleId] = moreModules[moduleId];
/******/ 					}
/******/ 				}
/******/ 				if(runtime) var result = runtime(__webpack_require__);
/******/ 			}
/******/ 			if(parentChunkLoadingFunction) parentChunkLoadingFunction(data);
/******/ 			for(;i < chunkIds.length; i++) {
/******/ 				chunkId = chunkIds[i];
/******/ 				if(__webpack_require__.o(installedChunks, chunkId) && installedChunks[chunkId]) {
/******/ 					installedChunks[chunkId][0]();
/******/ 				}
/******/ 				installedChunks[chunkId] = 0;
/******/ 			}
/******/ 			return __webpack_require__.O(result);
/******/ 		}
/******/ 		
/******/ 		var chunkLoadingGlobal = globalThis["webpackChunkfictional_university_theme"] = globalThis["webpackChunkfictional_university_theme"] || [];
/******/ 		chunkLoadingGlobal.forEach(webpackJsonpCallback.bind(null, 0));
/******/ 		chunkLoadingGlobal.push = webpackJsonpCallback.bind(null, chunkLoadingGlobal.push.bind(chunkLoadingGlobal));
/******/ 	})();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module depends on other loaded chunks and execution need to be delayed
/******/ 	var __webpack_exports__ = __webpack_require__.O(undefined, ["blogindex/style-index"], () => (__webpack_require__("./src/blogindex/index.js")))
/******/ 	__webpack_exports__ = __webpack_require__.O(__webpack_exports__);
/******/ 	
/******/ })()
;
//# sourceMappingURL=index.js.map