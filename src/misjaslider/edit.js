import {
  useBlockProps,
  RichText,
  InspectorControls,
  MediaUpload,
  MediaUploadCheck,
} from "@wordpress/block-editor";
import { PanelBody, Button, TextControl, RangeControl } from "@wordpress/components";

export default function Edit({ attributes, setAttributes }) {
  const {
    title, description, images, buttonText, buttonUrl,
    titleSizeDesktop, titleSizeMobile,
    descriptionSizeDesktop, descriptionSizeMobile,
    buttonSize,
  } = attributes;

  const blockProps = useBlockProps({
    className: "misjaslider",
    style: {
      "--title-size-desktop": `${titleSizeDesktop}px`,
      "--title-size-mobile": `${titleSizeMobile}px`,
      "--description-size-desktop": `${descriptionSizeDesktop}px`,
      "--description-size-mobile": `${descriptionSizeMobile}px`,
      "--button-size": `${buttonSize}px`,
    },
  });

  const onSelectImages = (media) => {
    const list = Array.isArray(media) ? media : [media];
    setAttributes({ images: list.map((m) => ({ id: m.id, url: m.url })) });
  };

  return (
    <section {...blockProps}>
      <InspectorControls>
        <PanelBody title="Zdjęcia slidera" initialOpen={true}>
          <MediaUploadCheck>
            <MediaUpload
              onSelect={onSelectImages}
              allowedTypes={["image"]}
              multiple
              gallery
              value={(images || []).map((i) => i.id).filter(Boolean)}
              render={({ open }) => (
                <Button variant="secondary" onClick={open}>
                  {images && images.length > 0
                    ? `Edytuj galerię (${images.length} zdjęć)`
                    : "Wybierz zdjęcia"}
                </Button>
              )}
            />
          </MediaUploadCheck>
          {images && images.length > 0 && (
            <Button variant="link" isDestructive onClick={() => setAttributes({ images: [] })} style={{ marginTop: 8 }}>
              Usuń wszystkie
            </Button>
          )}
        </PanelBody>

        <PanelBody title="Przycisk CTA" initialOpen={false}>
          <TextControl
            label="Tekst przycisku"
            value={buttonText}
            onChange={(v) => setAttributes({ buttonText: v })}
          />
          <TextControl
            label="URL"
            value={buttonUrl}
            onChange={(v) => setAttributes({ buttonUrl: v })}
            type="url"
          />
          <RangeControl
            label="Rozmiar tekstu przycisku"
            value={buttonSize}
            onChange={(v) => setAttributes({ buttonSize: v })}
            min={10} max={24} step={1}
          />
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

      <header className="misjaslider__head">
        <RichText
          tagName="h2"
          className="misjaslider__title"
          value={title}
          onChange={(v) => setAttributes({ title: v })}
          placeholder="Tytuł"
        />
        <RichText
          tagName="p"
          className="misjaslider__description"
          value={description}
          onChange={(v) => setAttributes({ description: v })}
          placeholder="Opis"
        />
      </header>

      {images && images.length > 0 && (
        <div className="misjaslider__slider">
          <div className="misjaslider__track">
            {images.slice(0, 3).map((img, i) => (
              <div key={i} className={`misjaslider__slide ${i === 1 ? "is-active" : i === 0 ? "is-prev" : "is-next"}`}>
                <img src={img.url} alt="" />
              </div>
            ))}
          </div>
          <nav className="misjaslider__nav">
            <button className="misjaslider__nav-btn misjaslider__nav-btn--prev" type="button">‹</button>
            <div className="misjaslider__dots">
              {images.map((_, i) => (
                <span key={i} className={`misjaslider__dot ${i === 0 ? "is-active" : ""}`}></span>
              ))}
            </div>
            <button className="misjaslider__nav-btn misjaslider__nav-btn--next" type="button">›</button>
          </nav>
        </div>
      )}

      {buttonText && (
        <span className="misjaslider__cta">
          <span className="misjaslider__cta-text">{buttonText}</span>
          <span className="misjaslider__cta-icon">→</span>
        </span>
      )}
    </section>
  );
}
