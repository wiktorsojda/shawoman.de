import {
  useBlockProps, RichText, InspectorControls, MediaUpload, MediaUploadCheck,
} from "@wordpress/block-editor";
import { PanelBody, Button, TextControl } from "@wordpress/components";

export default function Edit({ attributes, setAttributes }) {
  const { title, ctaLabel, ctaURL, videoURL, backgroundImage } = attributes;
  const wrapperStyle = backgroundImage
    ? { backgroundImage: `url(${backgroundImage})`, backgroundSize: "cover", backgroundPosition: "center" }
    : {};
  const blockProps = useBlockProps({ className: "video-background-container", style: wrapperStyle });
  return (
    <div {...blockProps}>
      <InspectorControls>
        <PanelBody title="Przycisk CTA" initialOpen={true}>
          <TextControl label="Link przycisku" value={ctaURL} onChange={(v) => setAttributes({ ctaURL: v })} />
        </PanelBody>
        <PanelBody title="Tło wideo" initialOpen={false}>
          <MediaUploadCheck>
            <MediaUpload onSelect={(media) => setAttributes({ videoURL: media.url })} allowedTypes={["video"]} value={videoURL}
              render={({ open }) => (<Button variant="secondary" onClick={open}>{videoURL ? "Zmień wideo" : "Wybierz wideo"}</Button>)} />
          </MediaUploadCheck>
        </PanelBody>
        <PanelBody title="Tło — obraz" initialOpen={false}>
          <MediaUploadCheck>
            <MediaUpload onSelect={(media) => setAttributes({ backgroundImage: media.url })} allowedTypes={["image"]} value={backgroundImage}
              render={({ open }) => (<Button variant="secondary" onClick={open}>{backgroundImage ? "Zmień obraz" : "Wybierz obraz"}</Button>)} />
          </MediaUploadCheck>
        </PanelBody>
      </InspectorControls>
      {videoURL && <video className="video-background" src={videoURL} autoPlay loop muted playsInline />}
      <section className="about-us-second">
        <div className="about-us-second-title">
          <RichText tagName="span" className="about-us-span first container--narrow2-important" value={title} onChange={(v) => setAttributes({ title: v })} placeholder="Tytuł" />
          <div className="cta-glowna">
            <a href={ctaURL} className="cta-button">
              <RichText tagName="span" value={ctaLabel} onChange={(v) => setAttributes({ ctaLabel: v })} placeholder="Etykieta CTA" />
            </a>
          </div>
        </div>
      </section>
    </div>
  );
}
