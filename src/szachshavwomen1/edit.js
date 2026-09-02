import {
  useBlockProps, RichText, InspectorControls, MediaUpload, MediaUploadCheck,
} from "@wordpress/block-editor";
import { PanelBody, Button, RangeControl, SelectControl, TextareaControl } from "@wordpress/components";
import { useState } from "@wordpress/element";

export default function Edit({ attributes, setAttributes }) {
  const a = attributes;
  const [jsonText, setJsonText] = useState("");

  const handleGenerateJson = () => {
    const data = {
      title: a.title,
      description: a.description
    };
    setJsonText(JSON.stringify(data, null, 2));
  };

  const handleApplyJson = () => {
    try {
      const parsed = JSON.parse(jsonText);
      const updates = {};
      if (parsed.title !== undefined) updates.title = parsed.title;
      if (parsed.description !== undefined) updates.description = parsed.description;
      setAttributes(updates);
      alert("Atrybuty zostały zaktualizowane!");
    } catch (e) {
      alert("Błąd: Nieprawidłowy format JSON.");
    }
  };
  const wrapperStyle = {};
  if (a.backgroundImage) wrapperStyle["--bg-desktop"] = `url(${a.backgroundImage})`;
  if (a.backgroundImageMobile) wrapperStyle["--bg-mobile"] = `url(${a.backgroundImageMobile})`;
  if (a.bgPositionDesktop) wrapperStyle["--bg-pos-desktop"] = a.bgPositionDesktop;
  if (a.bgPositionMobile) wrapperStyle["--bg-pos-mobile"] = a.bgPositionMobile;
  
  // fallback dla edytora (zawsze pokazuje desktop preview)
  if (a.backgroundImage) wrapperStyle.backgroundImage = `url(${a.backgroundImage})`;
  if (a.bgPositionDesktop) wrapperStyle.backgroundPosition = a.bgPositionDesktop;
  const blockProps = useBlockProps({
    className: `szachglass szachglass--x-${a.glassPositionX} szachglass--y-${a.glassPositionY}`,
    style: wrapperStyle,
  });
  const cardStyle = {
    width: a.glassWidth ? `${a.glassWidth}px` : undefined,
    textAlign: a.textAlign || "left",
  };
  return (
    <div {...blockProps}>
      <InspectorControls>
        <PanelBody title="Tłumaczenia AI (JSON)" initialOpen={false}>
          <p style={{ fontSize: "13px", marginBottom: "12px" }}>
            Wygeneruj JSON, przetłumacz teksty, a następnie wklej i zastosuj.
          </p>
          <Button variant="secondary" onClick={handleGenerateJson} style={{ marginBottom: "12px", width: "100%", justifyContent: "center" }}>
            Wygeneruj JSON
          </Button>
          <TextareaControl
            value={jsonText}
            onChange={(value) => setJsonText(value)}
            rows={8}
            help="Wklej tutaj przetłumaczony JSON"
          />
          <Button variant="primary" onClick={handleApplyJson} style={{ width: "100%", justifyContent: "center" }}>
            Zastosuj tłumaczenie
          </Button>
        </PanelBody>
        <PanelBody title="Tło — obraz" initialOpen={true}>
          <p style={{ marginTop: 0 }}><strong>Desktop</strong> (≥ 768px)</p>
          <MediaUploadCheck>
            <MediaUpload onSelect={(media) => setAttributes({ backgroundImage: media?.url || "" })} allowedTypes={["image"]} value={a.backgroundImage}
              render={({ open }) => (<Button variant="secondary" onClick={open}>{a.backgroundImage ? "Zmień zdjęcie desktop" : "Wybierz zdjęcie desktop"}</Button>)} />
          </MediaUploadCheck>
          {a.backgroundImage && (
            <Button variant="link" isDestructive onClick={() => setAttributes({ backgroundImage: "" })} style={{ marginTop: 4, marginBottom: 12, display: "block" }}>
              Usuń zdjęcie desktop
            </Button>
          )}
          <SelectControl
            label="Pozycja obrazka Desktop"
            value={a.bgPositionDesktop}
            options={[
              { label: "Środek (Center)", value: "center" },
              { label: "Góra (Top)", value: "top center" },
              { label: "Dół (Bottom)", value: "bottom center" },
              { label: "Lewo (Left)", value: "center left" },
              { label: "Prawo (Right)", value: "center right" },
            ]}
            onChange={(v) => setAttributes({ bgPositionDesktop: v })}
            style={{ marginBottom: 20 }}
          />
          <p style={{ marginBottom: 8 }}><strong>Mobile</strong> (&lt; 768px) — opcjonalne, pusto = używa desktop</p>
          <MediaUploadCheck>
            <MediaUpload onSelect={(media) => setAttributes({ backgroundImageMobile: media?.url || "" })} allowedTypes={["image"]} value={a.backgroundImageMobile}
              render={({ open }) => (<Button variant="secondary" onClick={open}>{a.backgroundImageMobile ? "Zmień zdjęcie mobile" : "Wybierz zdjęcie mobile"}</Button>)} />
          </MediaUploadCheck>
          {a.backgroundImageMobile && (
            <Button variant="link" isDestructive onClick={() => setAttributes({ backgroundImageMobile: "" })} style={{ marginTop: 4, display: "block" }}>
              Usuń zdjęcie mobile
            </Button>
          )}
          <SelectControl
            label="Pozycja obrazka Mobile"
            value={a.bgPositionMobile}
            options={[
              { label: "Środek (Center)", value: "center" },
              { label: "Góra (Top)", value: "top center" },
              { label: "Dół (Bottom)", value: "bottom center" },
              { label: "Lewo (Left)", value: "center left" },
              { label: "Prawo (Right)", value: "center right" },
            ]}
            onChange={(v) => setAttributes({ bgPositionMobile: v })}
          />
        </PanelBody>
        <PanelBody title="Pozycja karty" initialOpen={false}>
          <SelectControl
            label="Pozycja pozioma"
            value={a.glassPositionX}
            options={[
              { label: "Lewa", value: "left" },
              { label: "Środek", value: "center" },
              { label: "Prawa", value: "right" },
            ]}
            onChange={(v) => setAttributes({ glassPositionX: v })}
          />
          <SelectControl
            label="Pozycja pionowa"
            value={a.glassPositionY}
            options={[
              { label: "Góra", value: "top" },
              { label: "Środek", value: "middle" },
              { label: "Dół", value: "bottom" },
            ]}
            onChange={(v) => setAttributes({ glassPositionY: v })}
          />
          <SelectControl
            label="Wyrównanie tekstu"
            value={a.textAlign}
            options={[
              { label: "Do lewej", value: "left" },
              { label: "Do środka", value: "center" },
              { label: "Do prawej", value: "right" },
            ]}
            onChange={(v) => setAttributes({ textAlign: v })}
          />
          <RangeControl label="Szerokość karty (px)" min={320} max={1000} step={10} value={a.glassWidth} onChange={(v) => setAttributes({ glassWidth: v })} />
        </PanelBody>
        <PanelBody title="Rozmiary tekstu" initialOpen={false}>
          <RangeControl label="Tytuł (px)" min={20} max={80} step={1} value={a.titleSize} onChange={(v) => setAttributes({ titleSize: v })} />
          <RangeControl label="Opis (px)" min={12} max={32} step={1} value={a.descriptionSize} onChange={(v) => setAttributes({ descriptionSize: v })} />
        </PanelBody>
      </InspectorControls>
      <div className="szachglass__card" style={cardStyle}>
        <RichText
          tagName="h2"
          className="szachglass__title"
          style={{ fontSize: `${a.titleSize}px` }}
          value={a.title}
          onChange={(v) => setAttributes({ title: v })}
          placeholder="Tytuł"
        />
        <RichText
          tagName="p"
          className="szachglass__description"
          style={{ fontSize: `${a.descriptionSize}px` }}
          value={a.description}
          onChange={(v) => setAttributes({ description: v })}
          placeholder="Opis"
        />
      </div>
    </div>
  );
}
