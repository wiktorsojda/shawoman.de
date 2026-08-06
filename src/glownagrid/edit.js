import {
  useBlockProps, RichText, InspectorControls, MediaUpload, MediaUploadCheck, URLInput
} from "@wordpress/block-editor";
import { PanelBody, Button, TextControl, TextareaControl, Popover } from "@wordpress/components";
import { useState } from "@wordpress/element";

const ITEM_NUMS = [1, 2, 3, 4, 5, 6];

export default function Edit({ attributes, setAttributes }) {
  const a = attributes;
  const blockProps = useBlockProps({ className: "glownagrid" });
  const [importJson, setImportJson] = useState("");
  const [activeTile, setActiveTile] = useState(null);

  function renderTile(n) {
    const isSelected = activeTile === n;
    return (
      <div 
        key={n} 
        className={`glownagrid__tile glownagrid__tile--${n} ${isSelected ? 'is-selected' : ''}`}
        onClick={(e) => setActiveTile(n)}
      >
        {a[`item${n}Image`]
          ? <img className="glownagrid__bg" src={a[`item${n}Image`]} alt="" />
          : <div className="glownagrid__bg glownagrid__bg--placeholder">Wybierz zdjęcie kafelka {n} w panelu po prawej</div>}
        <div className="glownagrid__label">
          <RichText tagName="span" value={a[`item${n}Label`]} onChange={(v) => setAttributes({ [`item${n}Label`]: v })} placeholder={`Etykieta ${n}`} />
          {n === 6 && (
            <RichText tagName="span" className="glownagrid__label-accent" value={a[`item${n}Accent`]} onChange={(v) => setAttributes({ [`item${n}Accent`]: v })} placeholder="(Dolce)" />
          )}
        </div>
        <div className="glownagrid__cta">
          <RichText tagName="span" value={a.buttonLabel} onChange={(v) => setAttributes({ buttonLabel: v })} placeholder="Zobacz" />
          <span className="glownagrid__cta-arrow" aria-hidden="true">→</span>
        </div>

        {isSelected && (
          <Popover position="bottom center" onClose={() => setActiveTile(null)}>
            <div style={{ padding: '16px', minWidth: '300px' }}>
              <p style={{ marginTop: 0, marginBottom: '8px', fontWeight: 'bold' }}>Wyszukaj produkt lub wklej link (Kafelek {n})</p>
              <URLInput
                value={a[`item${n}URL`]}
                onChange={(url) => setAttributes({ [`item${n}URL`]: url })}
                __nextHasNoMarginBottom
              />
            </div>
          </Popover>
        )}
      </div>
    );
  }

  return (
    <div {...blockProps}>
      <InspectorControls>
        <PanelBody title="Tłumaczenia AI (JSON)" initialOpen={false}>
          <TextareaControl
            label="Skopiuj ten JSON dla AI"
            value={(() => {
              const data = {
                buttonLabel: a.buttonLabel || '',
                item1Label: a.item1Label || '',
                item2Label: a.item2Label || '',
                item3Label: a.item3Label || '',
                item4Label: a.item4Label || '',
                item5Label: a.item5Label || '',
                item6Label: a.item6Label || '',
                item6Accent: a.item6Accent || ''
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
              if (parsed.buttonLabel !== undefined) updates.buttonLabel = parsed.buttonLabel;
              if (parsed.item1Label !== undefined) updates.item1Label = parsed.item1Label;
              if (parsed.item2Label !== undefined) updates.item2Label = parsed.item2Label;
              if (parsed.item3Label !== undefined) updates.item3Label = parsed.item3Label;
              if (parsed.item4Label !== undefined) updates.item4Label = parsed.item4Label;
              if (parsed.item5Label !== undefined) updates.item5Label = parsed.item5Label;
              if (parsed.item6Label !== undefined) updates.item6Label = parsed.item6Label;
              if (parsed.item6Accent !== undefined) updates.item6Accent = parsed.item6Accent;
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
        {ITEM_NUMS.map((n) => (
          <PanelBody key={n} title={`Kafelek ${n}`} initialOpen={false}>
            <MediaUploadCheck>
              <MediaUpload onSelect={(media) => setAttributes({ [`item${n}Image`]: media?.url || "" })} allowedTypes={["image"]} value={a[`item${n}Image`]}
                render={({ open }) => (<Button variant="secondary" onClick={open}>{a[`item${n}Image`] ? "Zmień zdjęcie" : "Wybierz zdjęcie"}</Button>)} />
            </MediaUploadCheck>
            <div style={{ marginTop: 12 }}>
              <p style={{ marginBottom: 8, fontSize: 12 }}>Wyszukaj produkt (URL)</p>
              <URLInput 
                value={a[`item${n}URL`]} 
                onChange={(v) => setAttributes({ [`item${n}URL`]: v })} 
              />
            </div>
          </PanelBody>
        ))}
      </InspectorControls>

      <div className="glownagrid__grid">
        {ITEM_NUMS.map((n) => renderTile(n))}
      </div>
    </div>
  );
}
