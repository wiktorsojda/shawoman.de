import {
  useBlockProps,
  RichText,
  InspectorControls,
  MediaUpload,
  MediaUploadCheck,
} from "@wordpress/block-editor";
import { PanelBody, Button, ColorPicker } from "@wordpress/components";

export default function Edit({ attributes, setAttributes }) {
  const { titleBefore, titleHighlight, titleAfter, subtitle, highlightColor, backgroundImage } = attributes;

  const wrapperStyle = backgroundImage
    ? { backgroundImage: `url(${backgroundImage})`, backgroundSize: "cover", backgroundPosition: "center" }
    : {};

  const blockProps = useBlockProps({ className: "ogrodnik-container container", style: wrapperStyle });

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
        </PanelBody>
        <PanelBody title="Kolor wyróżnienia" initialOpen={false}>
          <ColorPicker
            color={highlightColor}
            onChange={(c) => setAttributes({ highlightColor: c })}
            enableAlpha={false}
          />
        </PanelBody>
      </InspectorControls>

      <div className="text-ogrodnik-father">
        <div className="container--narrow2-important content-position-helper">
          <div id="text-container-ogrodnik">
            <p className="line line-head">
              <RichText
                tagName="span"
                value={titleBefore}
                onChange={(v) => setAttributes({ titleBefore: v })}
                placeholder="Początek"
              />{" "}
              <RichText
                tagName="span"
                value={titleHighlight}
                onChange={(v) => setAttributes({ titleHighlight: v })}
                placeholder="Wyróżnienie"
                style={{ color: highlightColor }}
              />{" "}
              <RichText
                tagName="span"
                value={titleAfter}
                onChange={(v) => setAttributes({ titleAfter: v })}
                placeholder="Koniec"
              />
            </p>
            <RichText
              tagName="p"
              className="line line-rest"
              value={subtitle}
              onChange={(v) => setAttributes({ subtitle: v })}
              placeholder="Podtytuł"
            />
          </div>
        </div>
      </div>
    </div>
  );
}
