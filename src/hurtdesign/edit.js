import {
  useBlockProps, RichText, InspectorControls, MediaUpload, MediaUploadCheck,
} from "@wordpress/block-editor";
import { PanelBody, Button } from "@wordpress/components";

export default function Edit({ attributes, setAttributes }) {
  const a = attributes;
  const blockProps = useBlockProps({ className: "blacktext-container-wysylka container hurtdesign-container" });
  return (
    <div {...blockProps}>
      <InspectorControls>
        <PanelBody title="Obraz po prawej" initialOpen={true}>
          <MediaUploadCheck>
            <MediaUpload onSelect={(media) => setAttributes({ image: media.url })} allowedTypes={["image"]} value={a.image}
              render={({ open }) => (<Button variant="secondary" onClick={open}>{a.image ? "Zmień obraz" : "Wybierz obraz"}</Button>)} />
          </MediaUploadCheck>
        </PanelBody>
      </InspectorControls>
      <div className="metody-wysylki-kup-container container--narrow2-important">
        <div className="metody-wysylki-left-container">
          <RichText tagName="p" id="patent-text" value={a.tagline} onChange={(v) => setAttributes({ tagline: v })} placeholder="Tagline" />
          <RichText tagName="h2" value={a.heading} onChange={(v) => setAttributes({ heading: v })} placeholder="Nagłówek" />
          <RichText tagName="p" value={a.description} onChange={(v) => setAttributes({ description: v })} placeholder="Opis" />
        </div>
        <div>
          {a.image && <img className="metody-wysylki-right-image" src={a.image} alt="" />}
          <div style={{ marginTop: 16, color: "#999", fontSize: 12 }}>(Logo marek poniżej — zachowane statycznie)</div>
        </div>
      </div>
    </div>
  );
}
