import {
  useBlockProps, RichText, InspectorControls, MediaUpload, MediaUploadCheck,
} from "@wordpress/block-editor";
import { PanelBody, Button, TextControl, ColorPicker } from "@wordpress/components";

export default function Edit({ attributes, setAttributes }) {
  const { title, videoURL, backgroundImage, overlayColor } = attributes;
  const wrapperStyle = backgroundImage
    ? { backgroundImage: `url(${backgroundImage})`, backgroundSize: "cover", backgroundPosition: "center" }
    : {};
  const blockProps = useBlockProps({
    className: "video-background-container video-woman",
    style: wrapperStyle,
  });

  return (
    <div {...blockProps}>
      <InspectorControls>
        <PanelBody title="Tło wideo" initialOpen={true}>
          <MediaUploadCheck>
            <MediaUpload onSelect={(media) => setAttributes({ videoURL: media?.url || "" })} allowedTypes={["video"]} value={videoURL}
              render={({ open }) => (<Button variant="secondary" onClick={open}>{videoURL ? "Zmień wideo" : "Wybierz wideo"}</Button>)} />
          </MediaUploadCheck>
          {videoURL && (
            <Button variant="link" isDestructive onClick={() => setAttributes({ videoURL: "" })} style={{ marginTop: 8, display: "block" }}>
              Usuń wideo
            </Button>
          )}
          <p style={{ marginTop: 12, fontSize: 11, color: "#666" }}>Albo wklej URL ręcznie:</p>
          <TextControl label="Wideo URL" value={videoURL} onChange={(v) => setAttributes({ videoURL: v })} />
        </PanelBody>

        <PanelBody title="Tło — obraz (alternatywa wideo)" initialOpen={false}>
          <MediaUploadCheck>
            <MediaUpload onSelect={(media) => setAttributes({ backgroundImage: media?.url || "" })} allowedTypes={["image"]} value={backgroundImage}
              render={({ open }) => (<Button variant="secondary" onClick={open}>{backgroundImage ? "Zmień obraz" : "Wybierz obraz"}</Button>)} />
          </MediaUploadCheck>
          {backgroundImage && (
            <Button variant="link" isDestructive onClick={() => setAttributes({ backgroundImage: "" })} style={{ marginTop: 8, display: "block" }}>
              Usuń obraz
            </Button>
          )}
        </PanelBody>

        <PanelBody title="Overlay (przyciemnienie)" initialOpen={false}>
          <p style={{ marginTop: 0, fontSize: 12 }}>Kolor + przezroczystość warstwy nad wideo/obrazem (default: #00000057).</p>
          <ColorPicker color={overlayColor} onChange={(v) => setAttributes({ overlayColor: v })} enableAlpha />
        </PanelBody>
      </InspectorControls>

      {videoURL && <video className="video-background" src={videoURL} autoPlay loop muted playsInline />}

      <section className="about-us-second" style={{ backgroundColor: overlayColor }}>
        <div className="about-us-second-title">
          <RichText
            tagName="span"
            className="about-us-span first container--narrow2-important"
            value={title}
            onChange={(v) => setAttributes({ title: v })}
            placeholder="Tytuł"
          />
        </div>
        <div className="about-us-logos">
          <div style={{ padding: 16, color: "#999", fontSize: 12, textAlign: "center" }}>
            (Logo marek poniżej — zachowane statycznie w renderze)
          </div>
        </div>
      </section>
    </div>
  );
}
