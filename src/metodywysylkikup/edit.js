import {
  useBlockProps, RichText, InspectorControls, MediaUpload, MediaUploadCheck,
} from "@wordpress/block-editor";
import { PanelBody, Button, TextControl, TextareaControl } from "@wordpress/components";
import { useState } from "@wordpress/element";

export default function Edit({ attributes, setAttributes }) {
  const a = attributes;
  const [importJson, setImportJson] = useState("");
  const blockProps = useBlockProps({ className: "blacktext-container-wysylka container" });
  return (
    <div {...blockProps}>
      <InspectorControls>
        <PanelBody title="Tłumaczenia AI (JSON)" initialOpen={false}>
          <TextareaControl
            label="Skopiuj ten JSON dla AI"
            value={(() => {
              const data = {
                tagline: a.tagline || '',
                heading: a.heading || '',
                description: a.description || '',
                buttonLabel: a.buttonLabel || '',
                changeText1: a.changeText1 || '',
                changeText2: a.changeText2 || '',
                changeText3: a.changeText3 || '',
                changeText4: a.changeText4 || ''
              };
              return JSON.stringify(data, null, 2);
            })()}
            readOnly
            rows={12}
            help="Skopiuj i wklej do AI z prośbą o przetłumaczenie samych wartości."
          />
          <TextareaControl
            label="Wklej przetłumaczony JSON"
            value={importJson}
            onChange={setImportJson}
            rows={12}
          />
          <Button variant="primary" onClick={() => {
            try {
              const parsed = JSON.parse(importJson);
              const updates = {};
              if (parsed.tagline !== undefined) updates.tagline = parsed.tagline;
              if (parsed.heading !== undefined) updates.heading = parsed.heading;
              if (parsed.description !== undefined) updates.description = parsed.description;
              if (parsed.buttonLabel !== undefined) updates.buttonLabel = parsed.buttonLabel;
              if (parsed.changeText1 !== undefined) updates.changeText1 = parsed.changeText1;
              if (parsed.changeText2 !== undefined) updates.changeText2 = parsed.changeText2;
              if (parsed.changeText3 !== undefined) updates.changeText3 = parsed.changeText3;
              if (parsed.changeText4 !== undefined) updates.changeText4 = parsed.changeText4;
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
        <PanelBody title="Obraz po prawej" initialOpen={true}>
          <MediaUploadCheck>
            <MediaUpload onSelect={(media) => setAttributes({ image: media.url })} allowedTypes={["image"]} value={a.image}
              render={({ open }) => (<Button variant="secondary" onClick={open}>{a.image ? "Zmień obraz" : "Wybierz obraz"}</Button>)} />
          </MediaUploadCheck>
        </PanelBody>
        <PanelBody title="Przycisk" initialOpen={false}>
          <TextControl label="Link przycisku" value={a.buttonURL} onChange={(v) => setAttributes({ buttonURL: v })} />
        </PanelBody>
        <PanelBody title="Cykliczne teksty (Gwarantujemy ...)" initialOpen={false}>
          <TextControl label="Tekst 1" value={a.changeText1} onChange={(v) => setAttributes({ changeText1: v })} />
          <TextControl label="Tekst 2" value={a.changeText2} onChange={(v) => setAttributes({ changeText2: v })} />
          <TextControl label="Tekst 3" value={a.changeText3} onChange={(v) => setAttributes({ changeText3: v })} />
          <TextControl label="Tekst 4" value={a.changeText4} onChange={(v) => setAttributes({ changeText4: v })} />
        </PanelBody>
      </InspectorControls>
      <div className="metody-wysylki-kup-container container--narrow2-important">
        <div className="metody-wysylki-left-container">
          <div className="test-flex">
            <p className="kup-text">
              <RichText tagName="span" value={a.tagline} onChange={(v) => setAttributes({ tagline: v })} placeholder="Tagline" />
              <span className="changebox" style={{ overflow: "hidden", display: "inline-flex", flexDirection: "column" }}>
                <span style={{ position: "relative", transform: "none", opacity: 1, paddingBottom: 4 }}>{a.changeText1}</span>
                <span style={{ position: "relative", transform: "none", opacity: 1, paddingBottom: 4 }}>{a.changeText2}</span>
                <span style={{ position: "relative", transform: "none", opacity: 1, paddingBottom: 4 }}>{a.changeText3}</span>
                <span style={{ position: "relative", transform: "none", opacity: 1, paddingBottom: 4 }}>{a.changeText4}</span>
              </span>
            </p>
          </div>
          <RichText tagName="h2" value={a.heading} onChange={(v) => setAttributes({ heading: v })} placeholder="Nagłówek" />
          <RichText tagName="p" value={a.description} onChange={(v) => setAttributes({ description: v })} placeholder="Opis" />
          <a className="button-link" href={a.buttonURL}>
            <RichText tagName="button" value={a.buttonLabel} onChange={(v) => setAttributes({ buttonLabel: v })} placeholder="Etykieta" />
          </a>
        </div>
        <div className="metody-wysylki-right-container">
          <MediaUploadCheck>
            <MediaUpload
              onSelect={(m) => setAttributes({ image: m.url })}
              allowedTypes={["image"]}
              value={a.image}
              render={({ open }) => (
                <div
                  onClick={open}
                  style={{
                    cursor: "pointer",
                    minHeight: a.image ? "auto" : "400px",
                    display: "flex",
                    alignItems: "center",
                    justifyContent: "center",
                    backgroundColor: a.image ? "transparent" : "#f0f0f0",
                    border: a.image ? "none" : "2px dashed #ccc",
                    width: "100%",
                    height: "100%",
                  }}
                >
                  {a.image ? (
                    <img className="metody-wysylki-right-image" src={a.image} alt="" style={{ width: "100%", height: "100%", objectFit: "cover" }} />
                  ) : (
                    <span style={{ color: "#777", fontWeight: "bold" }}>Wybierz zdjęcie</span>
                  )}
                </div>
              )}
            />
          </MediaUploadCheck>
        </div>
      </div>
    </div>
  );
}
