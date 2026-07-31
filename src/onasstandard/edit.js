import {
  useBlockProps, RichText, InspectorControls,
  MediaUpload, MediaUploadCheck,
} from "@wordpress/block-editor";
import { PanelBody, Button, RangeControl, TextareaControl } from "@wordpress/components";

const LOGO_NUMS = [1, 2, 3, 4];

export default function Edit({ attributes, setAttributes }) {
  const a = attributes;
  const blockProps = useBlockProps({
    className: "onasstandard",
    style: {
      ...(a.backgroundImage && !a.backgroundVideo ? { backgroundImage: `url(${a.backgroundImage})` } : {}),
      backgroundSize: "cover",
      backgroundPosition: "center",
      "--title-size-desktop": `${a.titleSizeDesktop}px`,
      "--title-size-mobile": `${a.titleSizeMobile}px`,
      "--logo-height-desktop": `${a.logoHeightDesktop}px`,
      "--logo-height-mobile": `${a.logoHeightMobile}px`,
      "--margin-top-desktop": `${a.marginTopDesktop}px`,
      "--margin-top-mobile": `${a.marginTopMobile}px`,
    },
  });

  return (
    <section {...blockProps}>
      <InspectorControls>
        <PanelBody title="Wideo — desktop" initialOpen={true}>
          <MediaUploadCheck>
            <MediaUpload onSelect={(m) => setAttributes({ backgroundVideo: m.url })} allowedTypes={["video"]} value={a.backgroundVideo}
              render={({ open }) => (<Button variant="secondary" onClick={open}>{a.backgroundVideo ? "Zmień wideo (desktop)" : "Wybierz wideo (desktop)"}</Button>)} />
          </MediaUploadCheck>
          {a.backgroundVideo && (<Button variant="link" isDestructive onClick={() => setAttributes({ backgroundVideo: "" })} style={{ marginTop: 8 }}>Usuń wideo (desktop)</Button>)}
        </PanelBody>
        <PanelBody title="Wideo — mobile" initialOpen={false}>
          <p style={{ fontSize: 12, color: "#666", marginTop: 0 }}>Opcjonalne. Jeśli puste, użyje wideo desktopowego.</p>
          <MediaUploadCheck>
            <MediaUpload onSelect={(m) => setAttributes({ backgroundVideoMobile: m.url })} allowedTypes={["video"]} value={a.backgroundVideoMobile}
              render={({ open }) => (<Button variant="secondary" onClick={open}>{a.backgroundVideoMobile ? "Zmień wideo (mobile)" : "Wybierz wideo (mobile)"}</Button>)} />
          </MediaUploadCheck>
          {a.backgroundVideoMobile && (<Button variant="link" isDestructive onClick={() => setAttributes({ backgroundVideoMobile: "" })} style={{ marginTop: 8 }}>Usuń wideo (mobile)</Button>)}
        </PanelBody>
        <PanelBody title="Obraz (fallback / poster) — desktop" initialOpen={false}>
          <p style={{ fontSize: 12, color: "#666", marginTop: 0 }}>Pokazywany podczas ładowania wideo lub jeśli wideo niedostępne.</p>
          <MediaUploadCheck>
            <MediaUpload onSelect={(m) => setAttributes({ backgroundImage: m.url })} allowedTypes={["image"]} value={a.backgroundImage}
              render={({ open }) => (<Button variant="secondary" onClick={open}>{a.backgroundImage ? "Zmień obraz (desktop)" : "Wybierz obraz (desktop)"}</Button>)} />
          </MediaUploadCheck>
        </PanelBody>
        <PanelBody title="Obraz (fallback / poster) — mobile" initialOpen={false}>
          <MediaUploadCheck>
            <MediaUpload onSelect={(m) => setAttributes({ backgroundImageMobile: m.url })} allowedTypes={["image"]} value={a.backgroundImageMobile}
              render={({ open }) => (<Button variant="secondary" onClick={open}>{a.backgroundImageMobile ? "Zmień obraz (mobile)" : "Wybierz obraz (mobile)"}</Button>)} />
          </MediaUploadCheck>
        </PanelBody>
        {LOGO_NUMS.map((n) => (
          <PanelBody key={n} title={`Logo ${n}`} initialOpen={false}>
            <p style={{ fontSize: 12, color: "#666", marginTop: 0 }}>Wgraj obraz (PNG/JPG/SVG) ALBO wklej kod SVG poniżej (SVG nadpisuje obraz).</p>
            <MediaUploadCheck>
              <MediaUpload onSelect={(m) => setAttributes({ [`logo${n}Image`]: m.url })} allowedTypes={["image"]} value={a[`logo${n}Image`]}
                render={({ open }) => (<Button variant="secondary" onClick={open}>{a[`logo${n}Image`] ? "Zmień logo" : "Wybierz logo"}</Button>)} />
            </MediaUploadCheck>
            {a[`logo${n}Image`] && (<Button variant="link" isDestructive onClick={() => setAttributes({ [`logo${n}Image`]: "" })} style={{ marginTop: 8 }}>Usuń obraz</Button>)}
            <TextareaControl
              label="…lub wklej kod SVG"
              value={a[`logo${n}Svg`]}
              onChange={(v) => setAttributes({ [`logo${n}Svg`]: v })}
              help="Zawartość pliku .svg. Renderowany inline (filter brightness/invert daje biały kolor)."
              rows={6}
            />
          </PanelBody>
        ))}
        <PanelBody title="Rozmiary tekstu" initialOpen={false}>
          <RangeControl label="Tytuł (desktop)" value={a.titleSizeDesktop} onChange={(v) => setAttributes({ titleSizeDesktop: v })} min={20} max={96} />
          <RangeControl label="Tytuł (mobile)" value={a.titleSizeMobile} onChange={(v) => setAttributes({ titleSizeMobile: v })} min={16} max={64} />
          <RangeControl label="Wysokość logo (desktop)" value={a.logoHeightDesktop} onChange={(v) => setAttributes({ logoHeightDesktop: v })} min={16} max={64} />
          <RangeControl label="Wysokość logo (mobile)" value={a.logoHeightMobile} onChange={(v) => setAttributes({ logoHeightMobile: v })} min={12} max={48} />
        </PanelBody>
        <PanelBody title="Margines od góry" initialOpen={false}>
          <RangeControl label="Desktop" value={a.marginTopDesktop} onChange={(v) => setAttributes({ marginTopDesktop: v })} min={0} max={200} />
          <RangeControl label="Mobile" value={a.marginTopMobile} onChange={(v) => setAttributes({ marginTopMobile: v })} min={0} max={120} />
        </PanelBody>
      </InspectorControls>

      <div className="onasstandard__content">
        <RichText tagName="h2" className="onasstandard__title" value={a.title} onChange={(v) => setAttributes({ title: v })} placeholder="Tytuł" />
        <div className="onasstandard__logos">
          {LOGO_NUMS.map((n) => {
            const svg = a[`logo${n}Svg`];
            const img = a[`logo${n}Image`];
            if (svg) {
              return <span key={n} className="onasstandard__logo onasstandard__logo--svg" dangerouslySetInnerHTML={{ __html: svg }} />;
            }
            if (img) {
              return <img key={n} className="onasstandard__logo" src={img} alt="" />;
            }
            return null;
          })}
        </div>
      </div>
    </section>
  );
}
