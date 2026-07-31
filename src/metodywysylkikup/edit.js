import {
  useBlockProps, RichText, InspectorControls, MediaUpload, MediaUploadCheck,
} from "@wordpress/block-editor";
import { PanelBody, Button, TextControl } from "@wordpress/components";

export default function Edit({ attributes, setAttributes }) {
  const a = attributes;
  const blockProps = useBlockProps({ className: "blacktext-container-wysylka container" });
  return (
    <div {...blockProps}>
      <InspectorControls>
        <PanelBody title="Obraz po prawej" initialOpen={true}>
          <MediaUploadCheck>
            <MediaUpload onSelect={(media) => setAttributes({ image: media.url })} allowedTypes={["image"]} value={a.image}
              render={({ open }) => (<Button variant="secondary" onClick={open}>{a.image ? "Zmień obraz" : "Wybierz obraz"}</Button>)} />
          </MediaUploadCheck>
        </PanelBody>
        <PanelBody title="Przycisk" initialOpen={false}>
          <TextControl label="Link przycisku" value={a.buttonURL} onChange={(v) => setAttributes({ buttonURL: v })} />
        </PanelBody>
        <PanelBody title="Cykliczne teksty (Gwarantujemy ...)" initialOpen={false}>
          <TextControl label="Tekst 1" value={a.changeText1} onChange={(v) => setAttributes({ changeText1: v })} />
          <TextControl label="Tekst 2" value={a.changeText2} onChange={(v) => setAttributes({ changeText2: v })} />
          <TextControl label="Tekst 3" value={a.changeText3} onChange={(v) => setAttributes({ changeText3: v })} />
          <TextControl label="Tekst 4" value={a.changeText4} onChange={(v) => setAttributes({ changeText4: v })} />
        </PanelBody>
      </InspectorControls>
      <div className="metody-wysylki-kup-container container--narrow2-important">
        <div className="metody-wysylki-left-container">
          <div className="test-flex">
            <p className="kup-text">
              <RichText tagName="span" value={a.tagline} onChange={(v) => setAttributes({ tagline: v })} placeholder="Tagline" />
              <span className="changebox">
                <span>{a.changeText1}</span><br/>
                <span>{a.changeText2}</span><br/>
                <span>{a.changeText3}</span><br/>
                <span>{a.changeText4}</span><br/>
              </span>
            </p>
          </div>
          <RichText tagName="h2" value={a.heading} onChange={(v) => setAttributes({ heading: v })} placeholder="Nagłówek" />
          <RichText tagName="p" value={a.description} onChange={(v) => setAttributes({ description: v })} placeholder="Opis" />
          <a className="button-link" href={a.buttonURL}>
            <RichText tagName="button" value={a.buttonLabel} onChange={(v) => setAttributes({ buttonLabel: v })} placeholder="Etykieta" />
          </a>
        </div>
        <div className="metody-wysylki-right-container">
          {a.image && <img className="metody-wysylki-right-image" src={a.image} alt="" />}
        </div>
      </div>
    </div>
  );
}
