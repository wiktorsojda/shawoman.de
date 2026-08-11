import {
  useBlockProps, RichText, InspectorControls,
  MediaUpload, MediaUploadCheck,
} from "@wordpress/block-editor";
import { PanelBody, Button, RangeControl, TextareaControl } from "@wordpress/components";
import { useState } from "@wordpress/element";

const IMG_NUMS = [1, 2, 3];

export default function Edit({ attributes, setAttributes }) {
  const a = attributes;
  const [importJson, setImportJson] = useState("");
  const blockProps = useBlockProps({
    className: "onasjak",
    style: {
      "--title-size-desktop": `${a.titleSizeDesktop}px`,
      "--title-size-mobile": `${a.titleSizeMobile}px`,
      "--description-size-desktop": `${a.descriptionSizeDesktop}px`,
      "--description-size-mobile": `${a.descriptionSizeMobile}px`,
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
                title: a.title || '',
                description: a.description || ''
              };
              return JSON.stringify(data, null, 2);
            })()}
            readOnly
            rows={6}
            help="Skopiuj i wklej do AI z prośbą o przetłumaczenie samych wartości."
          />
          <TextareaControl
            label="Wklej przetłumaczony JSON"
            value={importJson}
            onChange={setImportJson}
            rows={6}
          />
          <Button variant="primary" onClick={() => {
            try {
              const parsed = JSON.parse(importJson);
              const updates = {};
              if (parsed.title !== undefined) updates.title = parsed.title;
              if (parsed.description !== undefined) updates.description = parsed.description;
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
        {IMG_NUMS.map((n) => (
          <PanelBody key={n} title={`Zdjęcie ${n}${n === 1 ? " (szerokie)" : ""}`} initialOpen={n === 1}>
            <MediaUploadCheck>
              <MediaUpload onSelect={(m) => setAttributes({ [`image${n}`]: m.url })} allowedTypes={["image"]} value={a[`image${n}`]}
                render={({ open }) => (<Button variant="secondary" onClick={open}>{a[`image${n}`] ? "Zmień zdjęcie" : "Wybierz zdjęcie"}</Button>)} />
            </MediaUploadCheck>
            {a[`image${n}`] && (<Button variant="link" isDestructive onClick={() => setAttributes({ [`image${n}`]: "" })} style={{ marginTop: 8 }}>Usuń</Button>)}
          </PanelBody>
        ))}
        <PanelBody title="Rozmiary tekstu" initialOpen={false}>
          <RangeControl label="Tytuł (desktop)" value={a.titleSizeDesktop} onChange={(v) => setAttributes({ titleSizeDesktop: v })} min={16} max={64} />
          <RangeControl label="Tytuł (mobile)" value={a.titleSizeMobile} onChange={(v) => setAttributes({ titleSizeMobile: v })} min={16} max={48} />
          <RangeControl label="Opis (desktop)" value={a.descriptionSizeDesktop} onChange={(v) => setAttributes({ descriptionSizeDesktop: v })} min={12} max={28} />
          <RangeControl label="Opis (mobile)" value={a.descriptionSizeMobile} onChange={(v) => setAttributes({ descriptionSizeMobile: v })} min={12} max={24} />
        </PanelBody>
      </InspectorControls>

      <header className="onasjak__head">
        <RichText tagName="h2" className="onasjak__title" value={a.title} onChange={(v) => setAttributes({ title: v })} placeholder="Tytuł" />
        <RichText tagName="p" className="onasjak__description" value={a.description} onChange={(v) => setAttributes({ description: v })} placeholder="Opis" />
      </header>
      <div className="onasjak__gallery">
        {IMG_NUMS.map((n) => a[`image${n}`] && (
          <div key={n} className={`onasjak__slot${n === 1 ? " onasjak__slot--wide" : ""}`}>
            <img src={a[`image${n}`]} alt="" />
          </div>
        ))}
      </div>
    </section>
  );
}
