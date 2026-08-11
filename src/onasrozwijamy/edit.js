import {
  useBlockProps, RichText, InspectorControls,
  MediaUpload, MediaUploadCheck,
} from "@wordpress/block-editor";
import { PanelBody, Button, RangeControl, TextareaControl } from "@wordpress/components";
import { useState } from "@wordpress/element";

export default function Edit({ attributes, setAttributes }) {
  const a = attributes;
  const [importJson, setImportJson] = useState("");
  const blockProps = useBlockProps({
    className: "onasrozwijamy",
    style: {
      "--text-size-desktop": `${a.textSizeDesktop}px`,
      "--text-size-mobile": `${a.textSizeMobile}px`,
      "--signature-size-desktop": `${a.signatureSizeDesktop}px`,
      "--signature-size-mobile": `${a.signatureSizeMobile}px`,
    },
  });

  return (
    <section {...blockProps}>
      <InspectorControls>
        <PanelBody title="Tłumaczenia AI (JSON)" initialOpen={false}>
          <TextareaControl
            label="Skopiuj ten JSON dla AI"
            value={(() => {
              const data = {
                paragraph1: a.paragraph1 || '',
                paragraph2: a.paragraph2 || '',
                paragraph3: a.paragraph3 || '',
                signatureText: a.signatureText || ''
              };
              return JSON.stringify(data, null, 2);
            })()}
            readOnly
            rows={8}
            help="Skopiuj i wklej do AI z prośbą o przetłumaczenie samych wartości."
          />
          <TextareaControl
            label="Wklej przetłumaczony JSON"
            value={importJson}
            onChange={setImportJson}
            rows={8}
          />
          <Button variant="primary" onClick={() => {
            try {
              const parsed = JSON.parse(importJson);
              const updates = {};
              if (parsed.paragraph1 !== undefined) updates.paragraph1 = parsed.paragraph1;
              if (parsed.paragraph2 !== undefined) updates.paragraph2 = parsed.paragraph2;
              if (parsed.paragraph3 !== undefined) updates.paragraph3 = parsed.paragraph3;
              if (parsed.signatureText !== undefined) updates.signatureText = parsed.signatureText;
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
        <PanelBody title="Zdjęcie (lewo)" initialOpen={true}>
          <MediaUploadCheck>
            <MediaUpload onSelect={(m) => setAttributes({ leftImage: m.url })} allowedTypes={["image"]} value={a.leftImage}
              render={({ open }) => (<Button variant="secondary" onClick={open}>{a.leftImage ? "Zmień zdjęcie" : "Wybierz zdjęcie"}</Button>)} />
          </MediaUploadCheck>
        </PanelBody>
        <PanelBody title="Logo (sygnatura) — obraz / SVG" initialOpen={false}>
          <p style={{ fontSize: 12, color: "#666", marginTop: 0 }}>Wgraj PNG/JPG/SVG przez Media, ALBO wklej kod SVG poniżej (preferowane dla pełnej kontroli koloru).</p>
          <MediaUploadCheck>
            <MediaUpload onSelect={(m) => setAttributes({ signatureImage: m.url })} allowedTypes={["image"]} value={a.signatureImage}
              render={({ open }) => (<Button variant="secondary" onClick={open}>{a.signatureImage ? "Zmień logo" : "Wybierz logo"}</Button>)} />
          </MediaUploadCheck>
          {a.signatureImage && (<Button variant="link" isDestructive onClick={() => setAttributes({ signatureImage: "" })} style={{ marginTop: 8 }}>Usuń logo</Button>)}
          <TextareaControl
            label="…lub wklej kod SVG (nadpisuje obraz)"
            value={a.signatureSvg}
            onChange={(v) => setAttributes({ signatureSvg: v })}
            help="Wklej zawartość pliku .svg. Musi zaczynać się od <svg>. Renderowany inline (można stylować CSS-em)."
            rows={6}
          />
        </PanelBody>
        <PanelBody title="Rozmiary tekstu" initialOpen={false}>
          <RangeControl label="Tekst (desktop)" value={a.textSizeDesktop} onChange={(v) => setAttributes({ textSizeDesktop: v })} min={12} max={28} />
          <RangeControl label="Tekst (mobile)" value={a.textSizeMobile} onChange={(v) => setAttributes({ textSizeMobile: v })} min={12} max={24} />
          <RangeControl label="Sygnatura (desktop)" value={a.signatureSizeDesktop} onChange={(v) => setAttributes({ signatureSizeDesktop: v })} min={16} max={64} />
          <RangeControl label="Sygnatura (mobile)" value={a.signatureSizeMobile} onChange={(v) => setAttributes({ signatureSizeMobile: v })} min={14} max={48} />
        </PanelBody>
      </InspectorControls>

      <div className="onasrozwijamy__media">{a.leftImage && <img src={a.leftImage} alt="" />}</div>
      <div className="onasrozwijamy__text">
        <RichText tagName="p" className="onasrozwijamy__paragraph" value={a.paragraph1} onChange={(v) => setAttributes({ paragraph1: v })} placeholder="Akapit 1" />
        <RichText tagName="p" className="onasrozwijamy__paragraph" value={a.paragraph2} onChange={(v) => setAttributes({ paragraph2: v })} placeholder="Akapit 2" />
        <RichText tagName="p" className="onasrozwijamy__paragraph" value={a.paragraph3} onChange={(v) => setAttributes({ paragraph3: v })} placeholder="Akapit 3" />
        <div className="onasrozwijamy__signature">
          <RichText tagName="span" className="onasrozwijamy__signature-text" value={a.signatureText} onChange={(v) => setAttributes({ signatureText: v })} placeholder="Zespół" />
          {a.signatureSvg
            ? <span className="onasrozwijamy__signature-logo onasrozwijamy__signature-logo--svg" dangerouslySetInnerHTML={{ __html: a.signatureSvg }} />
            : a.signatureImage && <img className="onasrozwijamy__signature-logo" src={a.signatureImage} alt="Logo" />
          }
        </div>
      </div>
    </section>
  );
}
