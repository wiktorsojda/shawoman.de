import {
  useBlockProps, RichText, InspectorControls, MediaUpload, MediaUploadCheck,
} from "@wordpress/block-editor";
import { PanelBody, Button, TextControl } from "@wordpress/components";

const ITEM_NUMS = [1, 2, 3, 4, 5, 6];

export default function Edit({ attributes, setAttributes }) {
  const a = attributes;
  const blockProps = useBlockProps({ className: "glownagrid" });

  function renderTile(n) {
    return (
      <div key={n} className={`glownagrid__tile glownagrid__tile--${n}`}>
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
      </div>
    );
  }

  return (
    <div {...blockProps}>
      <InspectorControls>
        {ITEM_NUMS.map((n) => (
          <PanelBody key={n} title={`Kafelek ${n}`} initialOpen={false}>
            <MediaUploadCheck>
              <MediaUpload onSelect={(media) => setAttributes({ [`item${n}Image`]: media?.url || "" })} allowedTypes={["image"]} value={a[`item${n}Image`]}
                render={({ open }) => (<Button variant="secondary" onClick={open}>{a[`item${n}Image`] ? "Zmień zdjęcie" : "Wybierz zdjęcie"}</Button>)} />
            </MediaUploadCheck>
            <TextControl label="URL produktu" value={a[`item${n}URL`]} onChange={(v) => setAttributes({ [`item${n}URL`]: v })} style={{ marginTop: 12 }} />
          </PanelBody>
        ))}
      </InspectorControls>

      <div className="glownagrid__grid">
        {ITEM_NUMS.map((n) => renderTile(n))}
      </div>
    </div>
  );
}
