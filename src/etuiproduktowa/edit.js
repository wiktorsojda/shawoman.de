import {
  useBlockProps,
  RichText,
  InspectorControls,
  MediaUpload,
  MediaUploadCheck,
} from "@wordpress/block-editor";
import { PanelBody, Button } from "@wordpress/components";

export default function Edit({ attributes, setAttributes }) {
  const { title, description, backgroundImage } = attributes;

  const wrapperStyle = backgroundImage
    ? { backgroundImage: `url(${backgroundImage})`, backgroundSize: "cover", backgroundPosition: "center" }
    : {};

  const blockProps = useBlockProps({
    className: "cechy-image-etui cechy-container cechy-image-container container",
    style: wrapperStyle,
  });

  return (
    <div {...blockProps}>
      <InspectorControls>
        <PanelBody title="Tło — obraz" initialOpen={true}>
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

      <RichText
        tagName="div"
        className="line line-head"
        value={title}
        onChange={(v) => setAttributes({ title: v })}
        placeholder="Tytuł"
      />
      <RichText
        tagName="div"
        className="line line-rest"
        value={description}
        onChange={(v) => setAttributes({ description: v })}
        placeholder="Opis"
      />
    </div>
  );
}
