import {
  useBlockProps,
  RichText,
  InspectorControls,
  MediaUpload,
  MediaUploadCheck,
} from "@wordpress/block-editor";
import { PanelBody, Button, TextareaControl } from "@wordpress/components";
import { useState } from "@wordpress/element";

export default function Edit({ attributes, setAttributes }) {
  const { backgroundImage, backgroundImageMobile, title, subtitle } = attributes;
  const [importJson, setImportJson] = useState("");

  const wrapperStyle = {
    ...(backgroundImage ? { '--bg-desktop': `url(${backgroundImage})` } : {}),
    ...(backgroundImageMobile || backgroundImage ? { '--bg-mobile': `url(${backgroundImageMobile || backgroundImage})` } : {}),
  };

  const blockProps = useBlockProps({
    className: "video-background-container-banner",
    style: wrapperStyle,
  });

  return (
    <div {...blockProps}>
      <InspectorControls>
        <PanelBody title="Tłumaczenia AI (JSON)" initialOpen={false}>
          <TextareaControl
            label="Skopiuj ten JSON dla AI"
            value={(() => {
              const data = {
                title: title || '',
                subtitle: subtitle || ''
              };
              return JSON.stringify(data, null, 2);
            })()}
            readOnly
            rows={5}
            help="Skopiuj i wklej do AI z prośbą o przetłumaczenie samych wartości."
          />
          <TextareaControl
            label="Wklej przetłumaczony JSON"
            value={importJson}
            onChange={setImportJson}
            rows={5}
          />
          <Button variant="primary" onClick={() => {
            try {
              const parsed = JSON.parse(importJson);
              const updates = {};
              if (parsed.title !== undefined) updates.title = parsed.title;
              if (parsed.subtitle !== undefined) updates.subtitle = parsed.subtitle;
              setAttributes(updates);
              alert('Zaktualizowano pomyślnie!');
              setImportJson('');
            } catch (e) {
              alert('Błąd! Niepoprawny format JSON.');
            }
          }} style={{ width: '100%', justifyContent: 'center' }}>
            Importuj tłumaczenie
          </Button>
        </PanelBody>
        <PanelBody title="Tło — desktop" initialOpen={true}>
          <MediaUploadCheck>
            <MediaUpload
              onSelect={(media) => setAttributes({ backgroundImage: media.url })}
              allowedTypes={["image"]}
              value={backgroundImage}
              render={({ open }) => (
                <Button variant="secondary" onClick={open}>
                  {backgroundImage ? "Zmień obraz tła (desktop)" : "Wybierz obraz tła (desktop)"}
                </Button>
              )}
            />
          </MediaUploadCheck>
          {backgroundImage && (
            <Button variant="link" isDestructive onClick={() => setAttributes({ backgroundImage: "" })} style={{ marginTop: 8 }}>
              Usuń obraz tła (desktop)
            </Button>
          )}
        </PanelBody>

        <PanelBody title="Tło — mobile" initialOpen={false}>
          <p style={{ fontSize: 12, color: "#666", marginTop: 0 }}>
            Opcjonalne. Jeśli puste, użyje obrazu desktopowego.
          </p>
          <MediaUploadCheck>
            <MediaUpload
              onSelect={(media) => setAttributes({ backgroundImageMobile: media.url })}
              allowedTypes={["image"]}
              value={backgroundImageMobile}
              render={({ open }) => (
                <Button variant="secondary" onClick={open}>
                  {backgroundImageMobile ? "Zmień obraz tła (mobile)" : "Wybierz obraz tła (mobile)"}
                </Button>
              )}
            />
          </MediaUploadCheck>
          {backgroundImageMobile && (
            <Button variant="link" isDestructive onClick={() => setAttributes({ backgroundImageMobile: "" })} style={{ marginTop: 8 }}>
              Usuń obraz tła (mobile)
            </Button>
          )}
        </PanelBody>
      </InspectorControls>

      <section className="about-us-second-banner">
        <RichText
          tagName="p"
          className="about-us-second-subtitle-banner"
          value={subtitle}
          onChange={(v) => setAttributes({ subtitle: v })}
          placeholder="Podtytuł"
          allowedFormats={["core/bold", "core/italic"]}
        />
        <RichText
          tagName="h1"
          className="about-us-second-title-banner"
          value={title}
          onChange={(v) => setAttributes({ title: v })}
          placeholder="Tytuł"
          allowedFormats={["core/bold", "core/italic"]}
        />
        <span className="about-us-second-scroll" aria-hidden="true">
          <svg width="71" height="71" viewBox="0 0 71 71" fill="none" xmlns="http://www.w3.org/2000/svg">
            <g filter="url(#filter0_d_wysylka_edit)">
              <path d="M6.2 30.2C6.2 14.736 18.736 2.2 34.2 2.2C49.6639 2.2 62.2 14.736 62.2 30.2C62.2 45.664 49.6639 58.2 34.2 58.2C18.736 58.2 6.2 45.664 6.2 30.2Z" fill="url(#paint0_wysylka_edit)" fillOpacity="0.1" />
              <path d="M34.2 2.7C49.3878 2.7 61.7 15.0122 61.7 30.2C61.7 45.3878 49.3878 57.7 34.2 57.7C19.0121 57.7 6.7 45.3878 6.7 30.2C6.7 15.0122 19.0121 2.7 34.2 2.7Z" stroke="url(#paint1_wysylka_edit)" />
              <path d="M34.2065 39.7C34.9084 39.6934 35.5796 39.4057 36.0745 38.8993L41.8119 33.0772C42.0605 32.8229 42.2 32.4789 42.2 32.1204C42.2 31.7618 42.0605 31.4179 41.8119 31.1636C41.6879 31.0364 41.5403 30.9354 41.3777 30.8665C41.2151 30.7976 41.0407 30.7621 40.8646 30.7621C40.6885 30.7621 40.5141 30.7976 40.3515 30.8665C40.1889 30.9354 40.0413 31.0364 39.9173 31.1636L35.5408 35.6286V22.0572C35.5408 21.6972 35.4002 21.352 35.15 21.0975C34.8997 20.843 34.5603 20.7 34.2065 20.7C33.8526 20.7 33.5132 20.843 33.263 21.0975C33.0128 21.352 32.8722 21.6972 32.8722 22.0572V35.6286L28.4823 31.1636C28.2329 30.908 27.8938 30.7637 27.5397 30.7625C27.1856 30.7612 26.8456 30.903 26.5943 31.1568C26.3431 31.4106 26.2012 31.7554 26.2 32.1156C26.1987 32.4757 26.3382 32.8216 26.5876 33.0772L32.3251 38.8993C32.8233 39.409 33.4999 39.697 34.2065 39.7Z" fill="white" />
            </g>
            <defs>
              <filter id="filter0_d_wysylka_edit" x="0" y="0" width="70.4" height="70.4">
                <feFlood floodOpacity="0" result="BackgroundImageFix" />
                <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha" />
                <feOffset dx="1" dy="5" />
                <feGaussianBlur stdDeviation="3.6" />
                <feComposite in2="hardAlpha" operator="out" />
                <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.12 0" />
                <feBlend mode="normal" in2="BackgroundImageFix" result="effect1" />
                <feBlend mode="normal" in="SourceGraphic" in2="effect1" result="shape" />
              </filter>
              <linearGradient id="paint0_wysylka_edit" x1="17.91" y1="7.71" x2="44.21" y2="58.19" gradientUnits="userSpaceOnUse">
                <stop offset="0.122" stopColor="#9E9E9E" />
                <stop offset="0.44" stopColor="white" />
              </linearGradient>
              <linearGradient id="paint1_wysylka_edit" x1="15.36" y1="8.56" x2="43.79" y2="59.89" gradientUnits="userSpaceOnUse">
                <stop stopColor="white" />
                <stop offset="1" stopColor="white" stopOpacity="0" />
              </linearGradient>
            </defs>
          </svg>
        </span>
      </section>
    </div>
  );
}
