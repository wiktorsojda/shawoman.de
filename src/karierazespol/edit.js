import {
  useBlockProps, RichText, InspectorControls, MediaUpload, MediaUploadCheck,
} from "@wordpress/block-editor";
import { PanelBody, Button, TextControl } from "@wordpress/components";

export default function Edit({ attributes, setAttributes }) {
  const a = attributes;
  const blockProps = useBlockProps({ className: "container zespol-section zespol-section-kariera" });
  return (
    <div {...blockProps}>
      <InspectorControls>
        <PanelBody title="Zdjęcie zespołu" initialOpen={true}>
          <MediaUploadCheck>
            <MediaUpload onSelect={(media) => setAttributes({ image: media.url, imageAlt: media.alt || a.imageAlt })} allowedTypes={["image"]} value={a.image}
              render={({ open }) => (<Button variant="secondary" onClick={open}>{a.image ? "Zmień zdjęcie" : "Wybierz zdjęcie"}</Button>)} />
          </MediaUploadCheck>
          <TextControl label="Alt zdjęcia" value={a.imageAlt} onChange={(v) => setAttributes({ imageAlt: v })} />
        </PanelBody>
      </InspectorControls>
      <div className="container--narrow2-important">
        <div className="zespol-header zespol-header-kariera">
          {a.image && <img src={a.image} alt={a.imageAlt} className="zespol-image" />}
          <div className="zespol-text">
            <RichText tagName="h2" value={a.heading} onChange={(v) => setAttributes({ heading: v })} placeholder="Nagłówek" />
            <RichText tagName="p" value={a.description} onChange={(v) => setAttributes({ description: v })} placeholder="Opis" />
          </div>
        </div>
        <div className="stats-grid">
          {[1, 2, 3, 4].map((i) => (
            <div key={i} className="stat-item">
              <RichText tagName="div" className="stat-number" value={a[`stat${i}Number`]} onChange={(v) => setAttributes({ [`stat${i}Number`]: v })} placeholder="Liczba" />
              <RichText tagName="div" className="stat-label" value={a[`stat${i}Label`]} onChange={(v) => setAttributes({ [`stat${i}Label`]: v })} placeholder="Etykieta" />
            </div>
          ))}
        </div>
      </div>
    </div>
  );
}
