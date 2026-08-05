import {
  useBlockProps, RichText, InspectorControls, MediaUpload, MediaUploadCheck,
} from "@wordpress/block-editor";
import { PanelBody, Button, TextControl, TextareaControl } from "@wordpress/components";
import { useState } from "@wordpress/element";

const FEATURE_NUMS = [1, 2, 3, 4];

export default function Edit({ attributes, setAttributes }) {
  const a = attributes;
  const blockProps = useBlockProps({ className: "glownacechy" });
  const [importJson, setImportJson] = useState("");
  return (
    <div {...blockProps}>
      <InspectorControls>
        <PanelBody title="Tłumaczenia AI (JSON)" initialOpen={false}>
          <TextareaControl
            label="Skopiuj ten JSON dla AI"
            value={(() => {
              const data = {
                titleLine1: a.titleLine1 || '',
                titleLine2Before: a.titleLine2Before || '',
                titleLine2Accent: a.titleLine2Accent || '',
                feature1Title: a.feature1Title || '',
                feature1Sub: a.feature1Sub || '',
                feature2Title: a.feature2Title || '',
                feature2Sub: a.feature2Sub || '',
                feature3Title: a.feature3Title || '',
                feature3Sub: a.feature3Sub || '',
                feature4Title: a.feature4Title || '',
                feature4Sub: a.feature4Sub || ''
              };
              return JSON.stringify(data, null, 2);
            })()}
            readOnly
            rows={10}
            help="Skopiuj i wklej do AI z prośbą o przetłumaczenie samych wartości."
          />
          <TextareaControl
            label="Wklej przetłumaczony JSON"
            value={importJson}
            onChange={setImportJson}
            rows={10}
          />
          <Button variant="primary" onClick={() => {
            try {
              const parsed = JSON.parse(importJson);
              const updates = {};
              if (parsed.titleLine1 !== undefined) updates.titleLine1 = parsed.titleLine1;
              if (parsed.titleLine2Before !== undefined) updates.titleLine2Before = parsed.titleLine2Before;
              if (parsed.titleLine2Accent !== undefined) updates.titleLine2Accent = parsed.titleLine2Accent;
              if (parsed.feature1Title !== undefined) updates.feature1Title = parsed.feature1Title;
              if (parsed.feature1Sub !== undefined) updates.feature1Sub = parsed.feature1Sub;
              if (parsed.feature2Title !== undefined) updates.feature2Title = parsed.feature2Title;
              if (parsed.feature2Sub !== undefined) updates.feature2Sub = parsed.feature2Sub;
              if (parsed.feature3Title !== undefined) updates.feature3Title = parsed.feature3Title;
              if (parsed.feature3Sub !== undefined) updates.feature3Sub = parsed.feature3Sub;
              if (parsed.feature4Title !== undefined) updates.feature4Title = parsed.feature4Title;
              if (parsed.feature4Sub !== undefined) updates.feature4Sub = parsed.feature4Sub;
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
        <PanelBody title="Zdjęcie po prawej" initialOpen={true}>
          <MediaUploadCheck>
            <MediaUpload onSelect={(media) => setAttributes({ image: media.url, imageAlt: media.alt || a.imageAlt })} allowedTypes={["image"]} value={a.image}
              render={({ open }) => (
                <Button variant="secondary" onClick={open}>
                  {a.image ? "Zmień zdjęcie" : "Wybierz zdjęcie"}
                </Button>
              )} />
          </MediaUploadCheck>
          <TextControl label="Alt zdjęcia" value={a.imageAlt} onChange={(v) => setAttributes({ imageAlt: v })} />
        </PanelBody>
      </InspectorControls>

      <div className="glownacechy__inner">
        <div className="glownacechy__col">
          <h2 className="glownacechy__title">
            <RichText tagName="span" className="glownacechy__title-line" value={a.titleLine1} onChange={(v) => setAttributes({ titleLine1: v })} placeholder="Linia 1" />
            <span className="glownacechy__title-line">
              <RichText tagName="span" value={a.titleLine2Before} onChange={(v) => setAttributes({ titleLine2Before: v })} placeholder="Część zwykła" />
              <RichText tagName="span" className="glownacechy__title-accent" value={a.titleLine2Accent} onChange={(v) => setAttributes({ titleLine2Accent: v })} placeholder="Część akcentowana (Dolce)" />
            </span>
          </h2>

          <ul className="glownacechy__list">
            {FEATURE_NUMS.map((n, idx) => {
              const t = a[`feature${n}Title`];
              const s = a[`feature${n}Sub`];
              if (!t && !s) return null;
              return (
                <li key={n} className={`glownacechy__item${idx === 0 ? " is-active" : ""}`}>
                  <span className="glownacechy__bar" aria-hidden="true"></span>
                  <div className="glownacechy__text">
                    <RichText tagName="p" className="glownacechy__item-title" value={t} onChange={(v) => setAttributes({ [`feature${n}Title`]: v })} placeholder={`Tytuł ${n}`} />
                    <RichText tagName="p" className="glownacechy__item-sub"   value={s} onChange={(v) => setAttributes({ [`feature${n}Sub`]: v })}   placeholder={`Opis ${n}`} />
                  </div>
                </li>
              );
            })}
          </ul>
        </div>

        <div className="glownacechy__media">
          {a.image
            ? <img src={a.image} alt={a.imageAlt} />
            : <div className="glownacechy__media--placeholder">Wybierz zdjęcie w panelu po prawej</div>}
        </div>
      </div>
    </div>
  );
}
