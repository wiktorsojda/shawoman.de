import {
  useBlockProps, RichText, InspectorControls, MediaUpload, MediaUploadCheck,
} from "@wordpress/block-editor";
import {
  PanelBody, Button, TextControl, SelectControl, ToggleControl,
} from "@wordpress/components";

export default function Edit({ attributes, setAttributes }) {
  const a = attributes;
  const blockProps = useBlockProps({ className: `rosegoldbanner rosegoldbanner--${a.contentAlign}` });

  return (
    <div {...blockProps}>
      <InspectorControls>
        <PanelBody title="Tło banera" initialOpen={true}>
          <MediaUploadCheck>
            <MediaUpload onSelect={(m) => setAttributes({ image: m.url, imageAlt: m.alt || a.imageAlt })} allowedTypes={["image"]} value={a.image}
              render={({ open }) => (
                <Button variant="secondary" onClick={open}>{a.image ? "Zmień zdjęcie" : "Wybierz zdjęcie"}</Button>
              )} />
          </MediaUploadCheck>
          {a.image && <Button variant="link" isDestructive onClick={() => setAttributes({ image: "" })}>Usuń (użyj domyślnego)</Button>}
          <div style={{ borderTop: "1px solid #eee", paddingTop: 8, marginTop: 8 }}>
            <p style={{ margin: "0 0 4px", fontSize: 11, fontWeight: 600 }}>Zdjęcie mobilne (opcjonalnie)</p>
            <MediaUploadCheck>
              <MediaUpload onSelect={(m) => setAttributes({ imageMobile: m.url })} allowedTypes={["image"]} value={a.imageMobile}
                render={({ open }) => (
                  <Button variant="secondary" onClick={open}>{a.imageMobile ? "Zmień mobilne" : "Wybierz mobilne"}</Button>
                )} />
            </MediaUploadCheck>
            {a.imageMobile && <Button variant="link" isDestructive onClick={() => setAttributes({ imageMobile: "" })}>Usuń mobilne</Button>}
          </div>
          <TextControl label="Alt zdjęcia" value={a.imageAlt} onChange={(v) => setAttributes({ imageAlt: v })} />
        </PanelBody>
        <PanelBody title="Układ i link" initialOpen={false}>
          <SelectControl label="Pozycja treści" value={a.contentAlign} options={[
            { label: "Do lewej", value: "left" },
            { label: "Wyśrodkowana", value: "center" },
            { label: "Do prawej", value: "right" },
          ]} onChange={(v) => setAttributes({ contentAlign: v })} />
          <TextControl label="Link banera (opcjonalnie)" value={a.linkUrl} onChange={(v) => setAttributes({ linkUrl: v })} />
        </PanelBody>
        <PanelBody title="Przycisk (strzałka, prawy dół)" initialOpen={false}>
          <ToggleControl label="Pokaż przycisk" checked={a.showButton} onChange={(v) => setAttributes({ showButton: v })} />
          <TextControl label="Link przycisku (przekierowanie)" value={a.buttonURL} onChange={(v) => setAttributes({ buttonURL: v })} />
          <TextControl label="Opis przycisku (dla dostępności)" help="Tekst czytany przez czytniki ekranu / tytuł po najechaniu." value={a.buttonLabel} onChange={(v) => setAttributes({ buttonLabel: v })} />
        </PanelBody>
      </InspectorControls>

      <div className="rosegoldbanner__bg" style={a.image ? { backgroundImage: `url(${a.image})` } : undefined}>
        <div className="rosegoldbanner__inner">
          <div className="rosegoldbanner__content">
            <RichText tagName="span" className="rosegoldbanner__badge" value={a.badge} onChange={(v) => setAttributes({ badge: v })} placeholder="Badge" />
            <RichText tagName="h2" className="rosegoldbanner__title" value={a.title} onChange={(v) => setAttributes({ title: v })} placeholder="Tytuł" />
            <RichText tagName="p" className="rosegoldbanner__desc" value={a.description} onChange={(v) => setAttributes({ description: v })} placeholder="Opis" />
          </div>
        </div>
      </div>
      {a.showButton && (
        <span className="rosegoldbanner__button" aria-hidden="true">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" /></svg>
        </span>
      )}
    </div>
  );
}
