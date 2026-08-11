import {
  useBlockProps,
  RichText,
  InspectorControls,
  MediaUpload,
  MediaUploadCheck,
} from "@wordpress/block-editor";
import { PanelBody, Button, RangeControl, TextareaControl } from "@wordpress/components";
import { useState } from "@wordpress/element";

export default function Edit({ attributes, setAttributes }) {
  const {
    subtitle, title,
    backgroundImage, backgroundImageMobile,
    titleSizeDesktop, titleSizeMobile,
    subtitleSizeDesktop, subtitleSizeMobile,
  } = attributes;

  const [importJson, setImportJson] = useState("");

  const blockProps = useBlockProps({
    className: "misjaheader",
    style: {
      ...(backgroundImage ? { backgroundImage: `url(${backgroundImage})` } : {}),
      backgroundSize: "cover",
      backgroundPosition: "center",
      "--title-size-desktop": `${titleSizeDesktop}px`,
      "--title-size-mobile": `${titleSizeMobile}px`,
      "--subtitle-size-desktop": `${subtitleSizeDesktop}px`,
      "--subtitle-size-mobile": `${subtitleSizeMobile}px`,
    },
  });

  return (
    <section {...blockProps}>
      <InspectorControls>
        <PanelBody title="Tłumaczenia AI (JSON)" initialOpen={false}>
          <TextareaControl
            label="Skopiuj ten JSON dla AI"
            value={(() => {
              const data = {
                title: attributes.title || '',
                subtitle: attributes.subtitle || ''
              };
              return JSON.stringify(data, null, 2);
            })()}
            readOnly
            rows={6}
            help="Skopiuj i wklej do AI z prośbą o przetłumaczenie samych wartości."
          />
          <TextareaControl
            label="Wklej przetłumaczony JSON"
            value={importJson}
            onChange={setImportJson}
            rows={6}
          />
          <Button variant="primary" onClick={() => {
            try {
              const parsed = JSON.parse(importJson);
              const updates = {};
              if (parsed.title !== undefined) updates.title = parsed.title;
              if (parsed.subtitle !== undefined) updates.subtitle = parsed.subtitle;
              setAttributes(updates);
              alert('Zaktualizowano pomyślnie!');
              setImportJson('');
            } catch (e) {
              alert('Błąd! Niepoprawny format JSON.');
            }
          }} style={{ width: '100%', justifyContent: 'center' }}>
            Importuj tłumaczenie
          </Button>
        </PanelBody>
        <PanelBody title="Tło — desktop" initialOpen={true}>
          <MediaUploadCheck>
            <MediaUpload
              onSelect={(m) => setAttributes({ backgroundImage: m.url })}
              allowedTypes={["image"]}
              value={backgroundImage}
              render={({ open }) => (
                <Button variant="secondary" onClick={open}>
                  {backgroundImage ? "Zmień tło (desktop)" : "Wybierz tło (desktop)"}
                </Button>
              )}
            />
          </MediaUploadCheck>
          {backgroundImage && (
            <Button variant="link" isDestructive onClick={() => setAttributes({ backgroundImage: "" })} style={{ marginTop: 8 }}>
              Usuń tło (desktop)
            </Button>
          )}
        </PanelBody>
        <PanelBody title="Tło — mobile" initialOpen={false}>
          <MediaUploadCheck>
            <MediaUpload
              onSelect={(m) => setAttributes({ backgroundImageMobile: m.url })}
              allowedTypes={["image"]}
              value={backgroundImageMobile}
              render={({ open }) => (
                <Button variant="secondary" onClick={open}>
                  {backgroundImageMobile ? "Zmień tło (mobile)" : "Wybierz tło (mobile)"}
                </Button>
              )}
            />
          </MediaUploadCheck>
          {backgroundImageMobile && (
            <Button variant="link" isDestructive onClick={() => setAttributes({ backgroundImageMobile: "" })} style={{ marginTop: 8 }}>
              Usuń tło (mobile)
            </Button>
          )}
        </PanelBody>
        <PanelBody title="Rozmiary tekstu — desktop" initialOpen={false}>
          <RangeControl
            label="Tytuł (desktop)"
            value={titleSizeDesktop}
            onChange={(v) => setAttributes({ titleSizeDesktop: v })}
            min={16} max={128} step={1}
          />
          <RangeControl
            label="Podtytuł (desktop)"
            value={subtitleSizeDesktop}
            onChange={(v) => setAttributes({ subtitleSizeDesktop: v })}
            min={10} max={48} step={1}
          />
        </PanelBody>
        <PanelBody title="Rozmiary tekstu — mobile" initialOpen={false}>
          <RangeControl
            label="Tytuł (mobile)"
            value={titleSizeMobile}
            onChange={(v) => setAttributes({ titleSizeMobile: v })}
            min={16} max={80} step={1}
          />
          <RangeControl
            label="Podtytuł (mobile)"
            value={subtitleSizeMobile}
            onChange={(v) => setAttributes({ subtitleSizeMobile: v })}
            min={10} max={32} step={1}
          />
        </PanelBody>
      </InspectorControls>

      <div className="misjaheader__content">
        <RichText
          tagName="p"
          className="misjaheader__subtitle"
          value={subtitle}
          onChange={(v) => setAttributes({ subtitle: v })}
          placeholder="Podtytuł"
        />
        <RichText
          tagName="h1"
          className="misjaheader__title"
          value={title}
          onChange={(v) => setAttributes({ title: v })}
          placeholder="Tytuł"
        />
      </div>
    </section>
  );
}
