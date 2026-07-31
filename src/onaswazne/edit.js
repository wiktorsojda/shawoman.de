import {
  useBlockProps, RichText, InspectorControls,
  MediaUpload, MediaUploadCheck,
} from "@wordpress/block-editor";
import { PanelBody, Button, TextControl, RangeControl, TextareaControl } from "@wordpress/components";

export default function Edit({ attributes, setAttributes }) {
  const a = attributes;
  const blockProps = useBlockProps({
    className: "onaswazne",
    style: {
      "--title-size-desktop": `${a.titleSizeDesktop}px`,
      "--title-size-mobile": `${a.titleSizeMobile}px`,
      "--description-size-desktop": `${a.descriptionSizeDesktop}px`,
      "--description-size-mobile": `${a.descriptionSizeMobile}px`,
      "--button-size": `${a.buttonSize}px`,
    },
  });

  return (
    <section {...blockProps}>
      <InspectorControls>
        <PanelBody title="Lewa karta — logo (np. Rak'n Roll)" initialOpen={true}>
          <p style={{ fontSize: 12, color: "#666", marginTop: 0 }}>Wgraj obraz (PNG/JPG/SVG) ALBO wklej kod SVG poniżej (SVG nadpisuje obraz).</p>
          <MediaUploadCheck>
            <MediaUpload onSelect={(m) => setAttributes({ leftLogoImage: m.url })} allowedTypes={["image"]} value={a.leftLogoImage}
              render={({ open }) => (<Button variant="secondary" onClick={open}>{a.leftLogoImage ? "Zmień logo" : "Wybierz logo"}</Button>)} />
          </MediaUploadCheck>
          {a.leftLogoImage && (<Button variant="link" isDestructive onClick={() => setAttributes({ leftLogoImage: "" })} style={{ marginTop: 8 }}>Usuń obraz</Button>)}
          <TextareaControl
            label="…lub wklej kod SVG"
            value={a.leftLogoSvg}
            onChange={(v) => setAttributes({ leftLogoSvg: v })}
            help="Wklej zawartość pliku .svg (rozpoczynającą się od <svg>)."
            rows={6}
          />
        </PanelBody>
        <PanelBody title="Lewa karta — drugie logo (opcjonalne)" initialOpen={true}>
          <p style={{ fontSize: 12, color: "#666", marginTop: 0 }}>Z drugim logiem okrąg zamienia się w zaokrągloną kartę z dwoma logami obok siebie.</p>
          <MediaUploadCheck>
            <MediaUpload onSelect={(m) => setAttributes({ leftLogoImage2: m.url })} allowedTypes={["image"]} value={a.leftLogoImage2}
              render={({ open }) => (<Button variant="secondary" onClick={open}>{a.leftLogoImage2 ? "Zmień logo 2" : "Wybierz logo 2"}</Button>)} />
          </MediaUploadCheck>
          {a.leftLogoImage2 && (<Button variant="link" isDestructive onClick={() => setAttributes({ leftLogoImage2: "" })} style={{ marginTop: 8 }}>Usuń logo 2</Button>)}
        </PanelBody>
        <PanelBody title="Prawa karta — przycisk + dekoracja (sylwetka)" initialOpen={false}>
          <TextControl label="Tekst przycisku" value={a.rightButtonText} onChange={(v) => setAttributes({ rightButtonText: v })} />
          <TextControl label="URL przycisku" value={a.rightButtonUrl} onChange={(v) => setAttributes({ rightButtonUrl: v })} type="url" />
          <p style={{ fontSize: 12, color: "#666", marginTop: 16 }}>Dekoracja: wgraj obraz (PNG/SVG) lub wklej kod SVG poniżej.</p>
          <MediaUploadCheck>
            <MediaUpload onSelect={(m) => setAttributes({ rightDecoration: m.url })} allowedTypes={["image"]} value={a.rightDecoration}
              render={({ open }) => (<Button variant="secondary" onClick={open} style={{ marginTop: 8 }}>{a.rightDecoration ? "Zmień dekorację" : "Wybierz dekorację (sylwetka)"}</Button>)} />
          </MediaUploadCheck>
          {a.rightDecoration && (<Button variant="link" isDestructive onClick={() => setAttributes({ rightDecoration: "" })} style={{ marginTop: 8 }}>Usuń obraz</Button>)}
          <TextareaControl
            label="…lub wklej kod SVG dekoracji"
            value={a.rightDecorationSvg}
            onChange={(v) => setAttributes({ rightDecorationSvg: v })}
            help="Wklej zawartość pliku .svg. SVG nadpisuje obraz."
            rows={6}
          />
        </PanelBody>
        <PanelBody title="Rozmiary tekstu" initialOpen={false}>
          <RangeControl label="Tytuł (desktop)" value={a.titleSizeDesktop} onChange={(v) => setAttributes({ titleSizeDesktop: v })} min={16} max={64} />
          <RangeControl label="Tytuł (mobile)" value={a.titleSizeMobile} onChange={(v) => setAttributes({ titleSizeMobile: v })} min={16} max={48} />
          <RangeControl label="Opis (desktop)" value={a.descriptionSizeDesktop} onChange={(v) => setAttributes({ descriptionSizeDesktop: v })} min={12} max={28} />
          <RangeControl label="Opis (mobile)" value={a.descriptionSizeMobile} onChange={(v) => setAttributes({ descriptionSizeMobile: v })} min={12} max={24} />
          <RangeControl label="Tekst przycisku" value={a.buttonSize} onChange={(v) => setAttributes({ buttonSize: v })} min={10} max={24} />
        </PanelBody>
      </InspectorControls>

      <article className="onaswazne__card onaswazne__card--left">
        <div className="onaswazne__card-content">
          <div className="onaswazne__text-block">
            <RichText tagName="h2" className="onaswazne__title" value={a.leftTitle} onChange={(v) => setAttributes({ leftTitle: v })} placeholder="Tytuł lewy" />
            <RichText tagName="p" className="onaswazne__description" value={a.leftDescription} onChange={(v) => setAttributes({ leftDescription: v })} placeholder="Opis lewy" />
          </div>
          {(a.leftLogoSvg || a.leftLogoImage || a.leftLogoImage2) && (
            <div className={`onaswazne__logo${a.leftLogoImage2 ? " onaswazne__logo--duo" : ""}`}>
              {(a.leftLogoSvg || a.leftLogoImage) && (
                <div className={`onaswazne__logo-inner${a.leftLogoSvg ? " onaswazne__logo-inner--svg" : ""}`}>
                  {a.leftLogoSvg
                    ? <span dangerouslySetInnerHTML={{ __html: a.leftLogoSvg }} />
                    : <img src={a.leftLogoImage} alt="" />}
                </div>
              )}
              {a.leftLogoImage2 && (
                <div className="onaswazne__logo-second">
                  <img src={a.leftLogoImage2} alt="" />
                </div>
              )}
            </div>
          )}
        </div>
      </article>

      <article className="onaswazne__card onaswazne__card--right">
        <div className="onaswazne__card-content">
          <div className="onaswazne__text-block">
            <RichText tagName="h2" className="onaswazne__title onaswazne__title--light" value={a.rightTitle} onChange={(v) => setAttributes({ rightTitle: v })} placeholder="Tytuł prawy" />
            <RichText tagName="p" className="onaswazne__description onaswazne__description--light" value={a.rightDescription} onChange={(v) => setAttributes({ rightDescription: v })} placeholder="Opis prawy" />
          </div>
          {a.rightButtonText && (
            <span className="onaswazne__cta">
              <span className="onaswazne__cta-text">{a.rightButtonText}</span>
              <span className="onaswazne__cta-icon">→</span>
            </span>
          )}
        </div>
        {(a.rightDecorationSvg || a.rightDecoration) && (
          <div className={`onaswazne__decoration${a.rightDecorationSvg ? " onaswazne__decoration--svg" : ""}`}>
            {a.rightDecorationSvg
              ? <span dangerouslySetInnerHTML={{ __html: a.rightDecorationSvg }} />
              : <img src={a.rightDecoration} alt="" />}
          </div>
        )}
      </article>
    </section>
  );
}
