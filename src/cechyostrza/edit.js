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
    className: "cechy-image-5 cechy-container cechy-image-container container",
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
            <Button variant="link" isDestructive onClick={() => setAttributes({ backgroundImage: "" })} style={ { marginTop: 8 } }>
              Usuń obraz tła
            </Button>
          )}
        </PanelBody>
        <PanelBody title="Funkcja 1" initialOpen={false}>
          <RichText
            tagName="div"
            value={attributes.feature1Title}
            onChange={(v) => setAttributes({ feature1Title: v })}
            placeholder="Tytuł funkcji 1"
          />
        </PanelBody>
        <PanelBody title="Funkcja 2" initialOpen={false}>
          <RichText
            tagName="div"
            value={attributes.feature2Title}
            onChange={(v) => setAttributes({ feature2Title: v })}
            placeholder="Tytuł funkcji 2"
          />
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
