import {
  useBlockProps, RichText, InspectorControls, MediaUpload, MediaUploadCheck,
} from "@wordpress/block-editor";
import { PanelBody, Button, TextControl } from "@wordpress/components";

export default function Edit({ attributes, setAttributes }) {
  const a = attributes;
  const blockProps = useBlockProps({ className: "blacktext-container-wysylka glowna-kup-container container" });
  return (
    <div {...blockProps}>
      <InspectorControls>
        <PanelBody title="Wideo desktop" initialOpen={false}>
          <MediaUploadCheck>
            <MediaUpload onSelect={(media) => setAttributes({ videoDesktop: media.url })} allowedTypes={["video"]} value={a.videoDesktop}
              render={({ open }) => (<Button variant="secondary" onClick={open}>{a.videoDesktop ? "Zmień" : "Wybierz"}</Button>)} />
          </MediaUploadCheck>
        </PanelBody>
        <PanelBody title="Wideo mobile" initialOpen={false}>
          <MediaUploadCheck>
            <MediaUpload onSelect={(media) => setAttributes({ videoMobile: media.url })} allowedTypes={["video"]} value={a.videoMobile}
              render={({ open }) => (<Button variant="secondary" onClick={open}>{a.videoMobile ? "Zmień" : "Wybierz"}</Button>)} />
          </MediaUploadCheck>
        </PanelBody>
        <PanelBody title="Przycisk" initialOpen={false}>
          <TextControl label="Link przycisku" value={a.buttonURL} onChange={(v) => setAttributes({ buttonURL: v })} />
        </PanelBody>
      </InspectorControls>
      <div className="metody-wysylki-kup-container glowna-kup-subcontainer container--narrow2-important">
        <div className="metody-wysylki-left-container">
          <RichText tagName="h1" value={a.heading} onChange={(v) => setAttributes({ heading: v })} placeholder="Nagłówek" />
          <RichText tagName="p" value={a.description} onChange={(v) => setAttributes({ description: v })} placeholder="Opis" />
          <a className="button-link" href={a.buttonURL}>
            <RichText tagName="button" value={a.buttonLabel} onChange={(v) => setAttributes({ buttonLabel: v })} placeholder="Etykieta" />
          </a>
        </div>
      </div>
    </div>
  );
}
