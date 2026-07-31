import {
  useBlockProps,
  RichText,
  InspectorControls,
  MediaUpload,
  MediaUploadCheck,
} from "@wordpress/block-editor";
import { PanelBody, Button, RangeControl } from "@wordpress/components";

export default function Edit({ attributes, setAttributes }) {
  const {
    logoImage, logoImage2, title, description,
    titleSizeDesktop, titleSizeMobile,
    descriptionSizeDesktop, descriptionSizeMobile,
  } = attributes;

  const blockProps = useBlockProps({
    className: "misjarakroll",
    style: {
      "--title-size-desktop": `${titleSizeDesktop}px`,
      "--title-size-mobile": `${titleSizeMobile}px`,
      "--description-size-desktop": `${descriptionSizeDesktop}px`,
      "--description-size-mobile": `${descriptionSizeMobile}px`,
    },
  });

  return (
    <section {...blockProps}>
      <InspectorControls>
        <PanelBody title="Logo 1 (Rak'n'Roll)" initialOpen={true}>
          <MediaUploadCheck>
            <MediaUpload
              onSelect={(m) => setAttributes({ logoImage: m.url })}
              allowedTypes={["image"]}
              value={logoImage}
              render={({ open }) => (
                <Button variant="secondary" onClick={open}>
                  {logoImage ? "Zmień logo" : "Wybierz logo"}
                </Button>
              )}
            />
          </MediaUploadCheck>
          {logoImage && (
            <Button variant="link" isDestructive onClick={() => setAttributes({ logoImage: "" })} style={{ marginTop: 8 }}>
              Usuń logo
            </Button>
          )}
        </PanelBody>
        <PanelBody title="Logo 2 (Fundacja Pokonać Endometriozę)" initialOpen={true}>
          <MediaUploadCheck>
            <MediaUpload
              onSelect={(m) => setAttributes({ logoImage2: m.url })}
              allowedTypes={["image"]}
              value={logoImage2}
              render={({ open }) => (
                <Button variant="secondary" onClick={open}>
                  {logoImage2 ? "Zmień logo" : "Wybierz logo"}
                </Button>
              )}
            />
          </MediaUploadCheck>
          {logoImage2 && (
            <Button variant="link" isDestructive onClick={() => setAttributes({ logoImage2: "" })} style={{ marginTop: 8 }}>
              Usuń logo
            </Button>
          )}
        </PanelBody>
        <PanelBody title="Rozmiary tekstu — desktop" initialOpen={false}>
          <RangeControl
            label="Tytuł (desktop)"
            value={titleSizeDesktop}
            onChange={(v) => setAttributes({ titleSizeDesktop: v })}
            min={16} max={96} step={1}
          />
          <RangeControl
            label="Opis (desktop)"
            value={descriptionSizeDesktop}
            onChange={(v) => setAttributes({ descriptionSizeDesktop: v })}
            min={10} max={32} step={1}
          />
        </PanelBody>
        <PanelBody title="Rozmiary tekstu — mobile" initialOpen={false}>
          <RangeControl
            label="Tytuł (mobile)"
            value={titleSizeMobile}
            onChange={(v) => setAttributes({ titleSizeMobile: v })}
            min={16} max={56} step={1}
          />
          <RangeControl
            label="Opis (mobile)"
            value={descriptionSizeMobile}
            onChange={(v) => setAttributes({ descriptionSizeMobile: v })}
            min={10} max={24} step={1}
          />
        </PanelBody>
      </InspectorControls>

      <div className="misjarakroll__card">
        {logoImage && (
          <div className="misjarakroll__logo">
            <img src={logoImage} alt="Fundacja Rak'n'Roll" />
          </div>
        )}
        {logoImage2 && (
          <div className="misjarakroll__logo2">
            <img src={logoImage2} alt="Fundacja Pokonać Endometriozę" />
          </div>
        )}
        {!logoImage && !logoImage2 && (
          <span style={{ opacity: 0.5, fontSize: 13 }}>Wybierz loga w panelu bocznym →</span>
        )}
      </div>
      <div className="misjarakroll__text">
        <RichText
          tagName="h2"
          className="misjarakroll__title"
          value={title}
          onChange={(v) => setAttributes({ title: v })}
          placeholder="Tytuł"
        />
        <RichText
          tagName="p"
          className="misjarakroll__description"
          value={description}
          onChange={(v) => setAttributes({ description: v })}
          placeholder="Opis"
        />
      </div>
    </section>
  );
}
