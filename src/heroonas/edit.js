import {
  useBlockProps,
  RichText,
  InspectorControls,
  MediaUpload,
  MediaUploadCheck,
} from "@wordpress/block-editor";
import { PanelBody, Button } from "@wordpress/components";

export default function Edit({ attributes, setAttributes }) {
  const { videoURL, backgroundImage, title, subtitle, description } = attributes;

  const wrapperStyle = backgroundImage
    ? { backgroundImage: `url(${backgroundImage})`, backgroundSize: "cover", backgroundPosition: "center" }
    : {};

  const blockProps = useBlockProps({
    className: "video-background-container",
    style: wrapperStyle,
  });

  return (
    <div {...blockProps}>
      <InspectorControls>
        <PanelBody title="Tło wideo" initialOpen={true}>
          <MediaUploadCheck>
            <MediaUpload
              onSelect={(media) => setAttributes({ videoURL: media.url })}
              allowedTypes={["video"]}
              value={videoURL}
              render={({ open }) => (
                <Button variant="secondary" onClick={open}>
                  {videoURL ? "Zmień wideo" : "Wybierz wideo"}
                </Button>
              )}
            />
          </MediaUploadCheck>
          {videoURL && (
            <Button variant="link" isDestructive onClick={() => setAttributes({ videoURL: "" })} style={{ marginTop: 8 }}>
              Usuń wideo
            </Button>
          )}
        </PanelBody>

        <PanelBody title="Tło — obraz (alternatywnie)" initialOpen={false}>
          <MediaUploadCheck>
            <MediaUpload
              onSelect={(media) => setAttributes({ backgroundImage: media.url })}
              allowedTypes={["image"]}
              value={backgroundImage}
              render={({ open }) => (
                <Button variant="secondary" onClick={open}>
                  {backgroundImage ? "Zmień obraz" : "Wybierz obraz"}
                </Button>
              )}
            />
          </MediaUploadCheck>
          {backgroundImage && (
            <Button variant="link" isDestructive onClick={() => setAttributes({ backgroundImage: "" })} style={{ marginTop: 8 }}>
              Usuń obraz tła
            </Button>
          )}
        </PanelBody>
      </InspectorControls>

      {videoURL && (
        <video className="video-background" src={videoURL} autoPlay loop muted playsInline />
      )}

      <div className="content-overlay">
        <RichText
          tagName="h1"
          className="title"
          value={title}
          onChange={(v) => setAttributes({ title: v })}
          placeholder="Tytuł"
        />
        <RichText
          tagName="h2"
          className="subtitle"
          value={subtitle}
          onChange={(v) => setAttributes({ subtitle: v })}
          placeholder="Podtytuł"
        />
        <RichText
          tagName="p"
          className="description"
          value={description}
          onChange={(v) => setAttributes({ description: v })}
          placeholder="Opis"
        />
      </div>
    </div>
  );
}
