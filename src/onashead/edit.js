import {
  useBlockProps,
  RichText,
  InspectorControls,
  MediaUpload,
  MediaUploadCheck,
} from "@wordpress/block-editor";
import { PanelBody, Button } from "@wordpress/components";

export default function Edit({ attributes, setAttributes }) {
  const { brand, headline, description, backgroundImage } = attributes;

  const wrapperStyle = backgroundImage
    ? { backgroundImage: `url(${backgroundImage})`, backgroundSize: "cover", backgroundPosition: "center" }
    : {};

  const blockProps = useBlockProps({
    className: "onas-head-container container",
    style: wrapperStyle,
  });

  return (
    <div {...blockProps}>
      <InspectorControls>
        <PanelBody title="Tło — obraz" initialOpen={false}>
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

      <div id="text-container">
        <div className="text-container-head">
          <RichText
            tagName="h1"
            className="line line-head-1"
            value={brand}
            onChange={(v) => setAttributes({ brand: v })}
            placeholder="Brand"
          />
          <RichText
            tagName="h2"
            className="line line-head-2"
            value={headline}
            onChange={(v) => setAttributes({ headline: v })}
            placeholder="Nagłówek"
          />
        </div>
        <RichText
          tagName="div"
          className="line line-rest"
          value={description}
          onChange={(v) => setAttributes({ description: v })}
          placeholder="Opis"
        />
      </div>
    </div>
  );
}
