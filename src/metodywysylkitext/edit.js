import {
  useBlockProps, RichText, InspectorControls, MediaUpload, MediaUploadCheck,
} from "@wordpress/block-editor";
import { PanelBody, Button, TextareaControl, SelectControl, TextControl } from "@wordpress/components";
import { useState } from "@wordpress/element";

export default function Edit({ attributes, setAttributes }) {
  const a = attributes;
  const { alignment } = a;
  const [importJson, setImportJson] = useState("");
  const alignClass = alignment === "left" ? " metody-wysylki-textcontainer--left" : "";
  const blockProps = useBlockProps({ className: "blacktext-container-wysylka container" });

  return (
    <div {...blockProps}>
      <InspectorControls>
        <PanelBody title="Tłumaczenia AI (JSON)" initialOpen={false}>
          <TextareaControl
            label="Skopiuj ten JSON dla AI"
            value={(() => {
              const data = {
                header: a.header || "",
                description: a.description || "",
                subheader: a.subheader || "",
                option1Title: a.option1Title || "",
                option1Desc: a.option1Desc || "",
                option2Title: a.option2Title || "",
                option2Desc: a.option2Desc || "",
              };
              return JSON.stringify(data, null, 2);
            })()}
            readOnly
            rows={10}
            help="Skopiuj i wklej do AI z prośbą o przetłumaczenie samych wartości."
          />
          <TextareaControl
            label="Wklej przetłumaczony JSON"
            value={importJson}
            onChange={setImportJson}
            rows={10}
          />
          <Button variant="primary" onClick={() => {
            try {
              const parsed = JSON.parse(importJson);
              const updates = {};
              if (parsed.header !== undefined) updates.header = parsed.header;
              if (parsed.description !== undefined) updates.description = parsed.description;
              if (parsed.subheader !== undefined) updates.subheader = parsed.subheader;
              if (parsed.option1Title !== undefined) updates.option1Title = parsed.option1Title;
              if (parsed.option1Desc !== undefined) updates.option1Desc = parsed.option1Desc;
              if (parsed.option2Title !== undefined) updates.option2Title = parsed.option2Title;
              if (parsed.option2Desc !== undefined) updates.option2Desc = parsed.option2Desc;
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
        <PanelBody title="Opcje ikon SVG (Inline)" initialOpen={false}>
          {[1, 2].map((i) => (
            <div key={i} style={{ marginBottom: 16 }}>
              <TextareaControl
                label={`Opcja ${i} — Inline SVG`}
                help="Wklej cały tag <svg>...</svg> by nadpisać obrazek z bloku"
                value={a[`option${i}IconSvg`] || ""}
                onChange={(v) => setAttributes({ [`option${i}IconSvg`]: v })}
                rows={4}
              />
            </div>
          ))}
        </PanelBody>
        <PanelBody title="Ustawienia wyglądu" initialOpen={true}>
          <SelectControl
            label="Wyrównanie treści"
            value={alignment || "center"}
            options={[
              { label: "Do środka (Domyślnie)", value: "center" },
              { label: "Do lewej", value: "left" }
            ]}
            onChange={(v) => setAttributes({ alignment: v })}
          />
        </PanelBody>
      </InspectorControls>

      <div className={`metody-wysylki-textcontainer container--narrow2-important${alignClass}`}>
        <RichText tagName="h2" className="metody-wysylki-header" value={a.header} onChange={(v) => setAttributes({ header: v })} placeholder="Nagłówek" />
        <RichText tagName="p" className="metody-wysylki-p" value={a.description} onChange={(v) => setAttributes({ description: v })} placeholder="Opis" />
        <RichText tagName="h2" className="metody-wysylki-h2" value={a.subheader} onChange={(v) => setAttributes({ subheader: v })} placeholder="Podnagłówek" />
        <ul className="metody-wysylki-ul">
          {[1, 2].map((i) => {
            const svgInline = a[`option${i}IconSvg`];
            const iconUrl = a[`option${i}Icon`];
            return (
              <div key={i} className="metody-wysylki-list">
                {svgInline ? (
                  <span className="metody-wysylki-list-icon" dangerouslySetInnerHTML={{ __html: svgInline }} />
                ) : (
                  <MediaUploadCheck>
                    <MediaUpload
                      onSelect={(media) => setAttributes({ [`option${i}Icon`]: media.url })}
                      allowedTypes={["image"]}
                      value={iconUrl}
                      render={({ open }) => (
                        <div
                          onClick={open}
                          style={{
                            cursor: "pointer",
                            minWidth: "64px",
                            minHeight: "64px",
                            display: "flex",
                            alignItems: "center",
                            justifyContent: "center",
                            border: iconUrl ? "none" : "1px dashed #ccc",
                            backgroundColor: iconUrl ? "transparent" : "#f9f9f9",
                            marginBottom: "8px"
                          }}
                          title="Kliknij, aby wybrać obraz PNG/SVG z biblioteki"
                        >
                          {iconUrl ? (
                            <img className="metody-wysylki-list-icon" src={iconUrl} alt="" style={{ maxHeight: 64, maxWidth: 64, objectFit: "contain", margin: 0 }} />
                          ) : (
                            <span style={{ fontSize: "10px", color: "#999", textAlign: "center", lineHeight: "1.2" }}>Dodaj<br/>zdjęcie</span>
                          )}
                        </div>
                      )}
                    />
                  </MediaUploadCheck>
                )}
                <RichText tagName="li" value={a[`option${i}Title`]} onChange={(v) => setAttributes({ [`option${i}Title`]: v })} placeholder={`Opcja ${i} Tytuł`} />
                <RichText tagName="span" value={a[`option${i}Desc`]} onChange={(v) => setAttributes({ [`option${i}Desc`]: v })} placeholder={`Opis ${i}`} />
              </div>
            );
          })}
        </ul>
      </div>
    </div>
  );
}
