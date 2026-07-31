import {
  useBlockProps, RichText, InspectorControls, MediaUpload, MediaUploadCheck,
} from "@wordpress/block-editor";
import { PanelBody, Button, TextControl } from "@wordpress/components";

export default function Edit({ attributes, setAttributes }) {
  const { image, imageAlt, heading, phone, phoneHref, email, emailHref, hours } = attributes;
  const blockProps = useBlockProps({ className: "patent-father-container" });
  return (
    <div {...blockProps}>
      <InspectorControls>
        <PanelBody title="Zdjęcie kontaktowe" initialOpen={true}>
          <MediaUploadCheck>
            <MediaUpload onSelect={(media) => setAttributes({ image: media.url, imageAlt: media.alt || imageAlt })} allowedTypes={["image"]} value={image}
              render={({ open }) => (<Button variant="secondary" onClick={open}>{image ? "Zmień zdjęcie" : "Wybierz zdjęcie"}</Button>)} />
          </MediaUploadCheck>
          <TextControl label="Tekst alternatywny" value={imageAlt} onChange={(v) => setAttributes({ imageAlt: v })} />
        </PanelBody>
        <PanelBody title="Kontakt — linki" initialOpen={true}>
          <TextControl label="Telefon (link tel:)" value={phoneHref} onChange={(v) => setAttributes({ phoneHref: v })} />
          <TextControl label="Email (link mailto:)" value={emailHref} onChange={(v) => setAttributes({ emailHref: v })} />
        </PanelBody>
      </InspectorControls>
      <div className="container--narrow2-important patent-container hurt-kontakt-container" id="hurt-kontakt-container-id">
        {image && <img src={image} alt={imageAlt} className="hurt-kontakt-image" />}
        <div className="zwroty-kontakt">
          <RichText tagName="span" className="zwroty-kontakt-head" value={heading} onChange={(v) => setAttributes({ heading: v })} placeholder="Nagłówek" />
          <div className="zwroty-kontakt-numbers">
            <RichText tagName="span" value={phone} onChange={(v) => setAttributes({ phone: v })} placeholder="Numer telefonu" />
            <RichText tagName="span" value={email} onChange={(v) => setAttributes({ email: v })} placeholder="Email" />
            <RichText tagName="span" value={hours} onChange={(v) => setAttributes({ hours: v })} placeholder="Godziny" />
          </div>
        </div>
      </div>
    </div>
  );
}
