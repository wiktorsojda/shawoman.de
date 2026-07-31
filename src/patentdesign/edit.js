import {
  useBlockProps, RichText, InspectorControls, MediaUpload, MediaUploadCheck,
} from "@wordpress/block-editor";
import { PanelBody, Button } from "@wordpress/components";

export default function Edit({ attributes, setAttributes }) {
  const { tagline, title, description, image } = attributes;
  const blockProps = useBlockProps({ className: "blacktext-container-wysylka container" });
  return (
    <div {...blockProps}>
      <InspectorControls>
        <PanelBody title="Obraz po prawej" initialOpen={true}>
          <MediaUploadCheck>
            <MediaUpload onSelect={(media) => setAttributes({ image: media.url })} allowedTypes={["image"]} value={image}
              render={({ open }) => (<Button variant="secondary" onClick={open}>{image ? "Zmień obraz" : "Wybierz obraz"}</Button>)} />
          </MediaUploadCheck>
          {image && (<Button variant="link" isDestructive onClick={() => setAttributes({ image: "" })} style={{ marginTop: 8 }}>Usuń obraz</Button>)}
        </PanelBody>
      </InspectorControls>
      <div className="metody-wysylki-kup-container container--narrow2-important">
        <div className="metody-wysylki-left-container">
          <div className="test-flex" id="patnet-flex-test">
            <RichText tagName="p" id="patent-text" value={tagline} onChange={(v) => setAttributes({ tagline: v })} placeholder="Tagline" />
          </div>
          <RichText tagName="h2" value={title} onChange={(v) => setAttributes({ title: v })} placeholder="Tytuł" />
          <RichText tagName="p" value={description} onChange={(v) => setAttributes({ description: v })} placeholder="Opis" />
        </div>
        <div>
          {image && <img className="metody-wysylki-right-image" src={image} alt="" />}
        </div>
      </div>
    </div>
  );
}
