import {
  useBlockProps, RichText, InspectorControls, MediaUpload, MediaUploadCheck,
} from "@wordpress/block-editor";
import { PanelBody, Button, TextareaControl, SelectControl, TextControl, ToggleControl } from "@wordpress/components";
import { useEffect, useState } from "@wordpress/element";

// Migracja legacy
function migrateLegacy(a) {
  const list = [];
  for (let i = 1; i <= 2; i++) {
    const title = a[`option${i}Title`];
    const desc = a[`option${i}Desc`];
    const icon = a[`option${i}Icon`];
    const iconSvg = a[`option${i}IconSvg`];
    if (title || desc || icon || iconSvg) {
      list.push({ title: title || "", desc: desc || "", icon: icon || "", iconSvg: iconSvg || "" });
    }
  }
  return list;
}

export default function Edit({ attributes, setAttributes }) {
  const a = attributes;
  const { alignment, options: attrsOptions, grayscaleIcons } = a;
  
  const options = Array.isArray(attrsOptions) && attrsOptions.length > 0 ? attrsOptions : migrateLegacy(a);
  const [importJson, setImportJson] = useState("");
  const alignClass = alignment === "left" ? " metody-wysylki-textcontainer--left" : "";
  const grayscaleClass = grayscaleIcons ? " metody-wysylki-icon--grayscale" : "";
  const blockProps = useBlockProps({ className: "blacktext-container-wysylka container" });

  useEffect(() => {
    if (!Array.isArray(a.options) || a.options.length === 0) {
      const migrated = migrateLegacy(a);
      if (migrated.length > 0) setAttributes({ options: migrated });
    }
  }, []);

  const updateOption = (idx, patch) => {
    const next = options.map((o, i) => (i === idx ? { ...o, ...patch } : o));
    setAttributes({ options: next });
  };
  const addOption = () => {
    setAttributes({ options: [...options, { title: "", desc: "", icon: "", iconSvg: "" }] });
  };
  const removeOption = (idx) => {
    setAttributes({ options: options.filter((_, i) => i !== idx) });
  };
  const moveOption = (idx, dir) => {
    const next = [...options];
    const j = idx + dir;
    if (j < 0 || j >= next.length) return;
    [next[idx], next[j]] = [next[j], next[idx]];
    setAttributes({ options: next });
  };

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
                options: options.map(o => ({ title: o.title || "", desc: o.desc || "" }))
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
              if (parsed.options && Array.isArray(parsed.options)) {
                updates.options = options.map((o, i) => {
                  if (parsed.options[i]) {
                    return { ...o, title: parsed.options[i].title ?? o.title, desc: parsed.options[i].desc ?? o.desc };
                  }
                  return o;
                });
              }
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
        <PanelBody title="Metody wysyłki" initialOpen={true}>
          {options.map((opt, idx) => (
            <div key={idx} style={{ border: "1px solid #ddd", padding: 12, borderRadius: 4, marginBottom: 12 }}>
              <strong style={{ display: "block", marginBottom: 8 }}>Metoda {idx + 1}</strong>
              <TextControl
                label="Nagłówek metody"
                value={opt.title}
                onChange={(v) => updateOption(idx, { title: v })}
              />
              <TextareaControl
                label="Opis metody"
                value={opt.desc}
                onChange={(v) => updateOption(idx, { desc: v })}
                rows={3}
              />
              <p style={{ fontSize: 12, opacity: 0.7, margin: "0 0 8px" }}>
                Wybierz obraz z biblioteki <strong>lub</strong> wklej kod SVG (inline).
                Inline ma priorytet.
              </p>
              <div style={{ display: "flex", gap: "8px", alignItems: "center", marginBottom: "12px", marginTop: "8px", flexWrap: "wrap" }}>
                <MediaUploadCheck>
                  <MediaUpload
                    onSelect={(media) => updateOption(idx, { icon: media.url })}
                    allowedTypes={["image"]}
                    value={opt.icon}
                    render={({ open }) => (
                      <Button variant="secondary" onClick={open}>
                        {opt.icon ? "Zmień ikonę" : "Wybierz ikonę z biblioteki"}
                      </Button>
                    )}
                  />
                </MediaUploadCheck>
                {opt.icon && (
                  <Button variant="link" isDestructive onClick={() => updateOption(idx, { icon: "" })}>
                    Usuń ikonę
                  </Button>
                )}
              </div>
              <TextareaControl
                label="Inline SVG (kod)"
                help="Wklej cały tag <svg>...</svg>"
                value={opt.iconSvg || ""}
                onChange={(v) => updateOption(idx, { iconSvg: v })}
                rows={5}
              />
              <div style={{ display: "flex", gap: 4, marginTop: 8, flexWrap: "wrap" }}>
                <Button variant="tertiary" onClick={() => moveOption(idx, -1)} disabled={idx === 0}>↑</Button>
                <Button variant="tertiary" onClick={() => moveOption(idx, 1)} disabled={idx === options.length - 1}>↓</Button>
                <Button variant="link" isDestructive onClick={() => removeOption(idx)}>Usuń metodę</Button>
              </div>
            </div>
          ))}
          <Button variant="primary" onClick={addOption}>+ Dodaj metodę wysyłki</Button>
        </PanelBody>
        <PanelBody title="Ustawienia wyglądu" initialOpen={true}>
          <ToggleControl
            label="Szare ikony (bez koloru)"
            checked={!!grayscaleIcons}
            onChange={(v) => setAttributes({ grayscaleIcons: v })}
          />
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
          {options.map((opt, idx) => (
            <div key={idx} className="metody-wysylki-list">
              {opt.iconSvg ? (
                <span className={`metody-wysylki-list-icon${grayscaleClass}`} dangerouslySetInnerHTML={{ __html: opt.iconSvg }} />
              ) : (
                <MediaUploadCheck>
                  <MediaUpload
                    onSelect={(media) => updateOption(idx, { icon: media.url })}
                    allowedTypes={["image"]}
                    value={opt.icon}
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
                          border: opt.icon ? "none" : "1px dashed #ccc",
                          backgroundColor: opt.icon ? "transparent" : "#f9f9f9",
                          marginBottom: "8px"
                        }}
                        title="Kliknij, aby wybrać obraz PNG/SVG z biblioteki"
                      >
                        {opt.icon ? (
                          <img className={`metody-wysylki-list-icon${grayscaleClass}`} src={opt.icon} alt="" style={{ maxHeight: 35, width: "auto", objectFit: "contain", margin: 0 }} />
                        ) : (
                          <span style={{ fontSize: "10px", color: "#999", textAlign: "center", lineHeight: "1.2" }}>Dodaj<br/>zdjęcie</span>
                        )}
                      </div>
                    )}
                  />
                </MediaUploadCheck>
              )}
              <RichText tagName="li" value={opt.title} onChange={(v) => updateOption(idx, { title: v })} placeholder={`Opcja ${idx + 1} Tytuł`} />
              <RichText tagName="span" value={opt.desc} onChange={(v) => updateOption(idx, { desc: v })} placeholder={`Opis ${idx + 1}`} />
            </div>
          ))}
        </ul>
      </div>
    </div>
  );
}
