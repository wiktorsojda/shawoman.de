import {
  useBlockProps, RichText, InspectorControls, MediaUpload, MediaUploadCheck,
} from "@wordpress/block-editor";
import { PanelBody, Button, TextControl } from "@wordpress/components";

const FEATURE_NUMS = [1, 2, 3, 4];

export default function Edit({ attributes, setAttributes }) {
  const a = attributes;
  const blockProps = useBlockProps({ className: "glownacechy" });
  return (
    <div {...blockProps}>
      <InspectorControls>
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
