import {
  useBlockProps, RichText, InspectorControls, MediaUpload, MediaUploadCheck,
} from "@wordpress/block-editor";
import { PanelBody, Button, TextControl, ColorPicker, TextareaControl } from "@wordpress/components";
import { useState, useEffect } from "@wordpress/element";

export default function Edit({ attributes, setAttributes }) {
  const { title, videoURL, backgroundImage, overlayColor, logos } = attributes;
  const [jsonText, setJsonText] = useState(JSON.stringify({ title }, null, 2));

  useEffect(() => {
    setJsonText(JSON.stringify({ title }, null, 2));
  }, [title]);

  const handleApplyJson = () => {
    try {
      const parsed = JSON.parse(jsonText);
      const updates = {};
      if (parsed.title !== undefined) updates.title = parsed.title;
      setAttributes(updates);
      alert("Pomyślnie zaktualizowano z JSON!");
    } catch (e) {
      alert("Błąd: Nieprawidłowy format JSON.");
    }
  };
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
        <PanelBody title="Tłumaczenia AI (JSON)" initialOpen={false}>
          <p style={{ fontSize: "13px", marginBottom: "12px" }}>
            Skopiuj JSON, przetłumacz teksty, a następnie wklej go tutaj i zastosuj.
          </p>
          <TextareaControl
            value={jsonText}
            onChange={(value) => setJsonText(value)}
            rows={6}
            help="Wklej tutaj przetłumaczony JSON"
          />
          <Button variant="primary" onClick={handleApplyJson} style={{ width: "100%", justifyContent: "center" }}>
            Zastosuj tłumaczenie
          </Button>
        </PanelBody>

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

        <PanelBody title="Logotypy" initialOpen={true}>
          <MediaUploadCheck>
            <MediaUpload
              onSelect={(media) => {
                const newLogos = media.map(m => ({ id: m.id, url: m.url, alt: m.alt }));
                setAttributes({ logos: newLogos });
              }}
              allowedTypes={["image"]}
              multiple={true}
              gallery={true}
              value={logos ? logos.map(l => l.id) : []}
              render={({ open }) => (
                <Button variant="secondary" onClick={open}>
                  {logos && logos.length > 0 ? "Zarządzaj logotypami" : "Dodaj logotypy"}
                </Button>
              )}
            />
          </MediaUploadCheck>
          {logos && logos.length > 0 && (
            <Button variant="link" isDestructive onClick={() => setAttributes({ logos: [] })} style={{ marginTop: 8, display: "block" }}>
              Usuń wszystkie logotypy
            </Button>
          )}
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
          <div className="about-us-swiper">
            {logos && logos.length > 0 ? (
              logos.map((logo, idx) => (
                <img key={idx} src={logo.url} alt={logo.alt || "Logo"} />
              ))
            ) : (
              <div style={{ padding: 16, color: "#999", fontSize: 12, textAlign: "center" }}>
                Dodaj logotypy w panelu bocznym.
              </div>
            )}
          </div>
        </div>
      </section>
    </div>
  );
}
