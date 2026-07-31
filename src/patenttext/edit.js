import {
  useBlockProps, RichText, InspectorControls, MediaUpload, MediaUploadCheck,
} from "@wordpress/block-editor";
import { PanelBody, Button, TextControl } from "@wordpress/components";

export default function Edit({ attributes, setAttributes }) {
  const a = attributes;
  const blockProps = useBlockProps({ className: "patent-father-container" });
  return (
    <div {...blockProps}>
      <InspectorControls>
        <PanelBody title="Obraz: Znak towarowy" initialOpen={false}>
          <MediaUploadCheck>
            <MediaUpload onSelect={(media) => setAttributes({ znakImage: media.url })} allowedTypes={["image"]} value={a.znakImage}
              render={({ open }) => (<Button variant="secondary" onClick={open}>{a.znakImage ? "Zmień obraz" : "Wybierz obraz"}</Button>)} />
          </MediaUploadCheck>
        </PanelBody>
        <PanelBody title="Obraz: Wzór przemysłowy" initialOpen={false}>
          <MediaUploadCheck>
            <MediaUpload onSelect={(media) => setAttributes({ wzorImage: media.url })} allowedTypes={["image"]} value={a.wzorImage}
              render={({ open }) => (<Button variant="secondary" onClick={open}>{a.wzorImage ? "Zmień obraz" : "Wybierz obraz"}</Button>)} />
          </MediaUploadCheck>
        </PanelBody>
        <PanelBody title="Link do PDF" initialOpen={false}>
          <TextControl label="URL pliku PDF" value={a.pdfLinkURL} onChange={(v) => setAttributes({ pdfLinkURL: v })} />
        </PanelBody>
      </InspectorControls>
      <div className="container--narrow2-important patent-container">
        <div className="zarejestrowano-container">
          <div className="zarejestrowano-title-subcontainer">
            <RichText tagName="div" value={a.topTitle} onChange={(v) => setAttributes({ topTitle: v })} placeholder="Tytuł" />
          </div>
          <RichText tagName="div" className="zarejestrowano-rest" value={a.topDescription} onChange={(v) => setAttributes({ topDescription: v })} placeholder="Opis" />
        </div>
        <div className="znak-container">
          <div className="znak-subcontainer">
            <div className="znak-image-container">
              {a.znakImage && <img src={a.znakImage} alt="" />}
            </div>
            <div className="znak-subcontainer-text">
              <RichText tagName="span" value={a.znakLabel} onChange={(v) => setAttributes({ znakLabel: v })} placeholder="Etykieta" />
            </div>
          </div>
          <div className="wzor-subcontainer">
            <div className="wzor-subcontainer-text">
              <RichText tagName="span" value={a.wzorLabel} onChange={(v) => setAttributes({ wzorLabel: v })} placeholder="Etykieta" />
            </div>
            <div className="wzor-image-container">
              {a.wzorImage && <img src={a.wzorImage} alt="" />}
            </div>
          </div>
        </div>
        <div className="zarejestrowano-container">
          <div className="zarejestrowano-title-subcontainer">
            <RichText tagName="span" value={a.pdfTitle} onChange={(v) => setAttributes({ pdfTitle: v })} placeholder="Tytuł sekcji PDF" />
          </div>
        </div>
        <div className="znak-dokumenty">
          <RichText tagName="a" value={a.pdfLinkText} onChange={(v) => setAttributes({ pdfLinkText: v })} placeholder="Tekst linku" />
        </div>
      </div>
    </div>
  );
}
