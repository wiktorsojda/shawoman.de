import {
  useBlockProps, RichText, InspectorControls, MediaUpload, MediaUploadCheck,
} from "@wordpress/block-editor";
import { PanelBody, Button, TextareaControl, TextControl, SelectControl } from "@wordpress/components";
import { useEffect, useState } from "@wordpress/element";

// Migracja legacy method1..4 -> methods[]
function migrateLegacy(a) {
  const list = [];
  for (let i = 1; i <= 4; i++) {
    const title = a[`method${i}Title`];
    const desc = a[`method${i}Desc`];
    const icon = a[`method${i}Icon`];
    const iconSvg = a[`method${i}IconSvg`];
    if (title || desc || icon || iconSvg) {
      list.push({ title: title || "", desc: desc || "", icon: icon || "", iconSvg: iconSvg || "" });
    }
  }
  return list;
}

export default function Edit({ attributes, setAttributes }) {
  const { header, description, methods: attrsMethods, alignment, ...legacyAttrs } = attributes;
  const a = attributes;
  const methods = Array.isArray(attrsMethods) && attrsMethods.length > 0 ? attrsMethods : migrateLegacy(a);
  const [importJson, setImportJson] = useState("");

  // Jednorazowa migracja przy pierwszym otwarciu
  useEffect(() => {
    if (!Array.isArray(a.methods) || a.methods.length === 0) {
      const migrated = migrateLegacy(a);
      if (migrated.length > 0) setAttributes({ methods: migrated });
    }
  }, []);

  const updateMethod = (idx, patch) => {
    const next = methods.map((m, i) => (i === idx ? { ...m, ...patch } : m));
    setAttributes({ methods: next });
  };
  const addMethod = () => {
    setAttributes({ methods: [...methods, { title: "", desc: "", icon: "", iconSvg: "" }] });
  };
  const removeMethod = (idx) => {
    setAttributes({ methods: methods.filter((_, i) => i !== idx) });
  };
  const moveMethod = (idx, dir) => {
    const next = [...methods];
    const j = idx + dir;
    if (j < 0 || j >= next.length) return;
    [next[idx], next[j]] = [next[j], next[idx]];
    setAttributes({ methods: next });
  };

  const blockProps = useBlockProps({ className: "blacktext-container-wysylka container" });
  const alignClass = alignment === "left" ? " metody-wysylki-textcontainer--left" : "";

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
                methods: methods.map(m => ({ title: m.title || "", desc: m.desc || "" }))
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
              if (parsed.methods && Array.isArray(parsed.methods)) {
                updates.methods = methods.map((m, i) => {
                  if (parsed.methods[i]) {
                    return { ...m, title: parsed.methods[i].title ?? m.title, desc: parsed.methods[i].desc ?? m.desc };
                  }
                  return m;
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
        <PanelBody title="Metody płatności" initialOpen={true}>
          {methods.map((m, idx) => (
            <div key={idx} style={{ border: "1px solid #ddd", padding: 12, borderRadius: 4, marginBottom: 12 }}>
              <strong style={{ display: "block", marginBottom: 8 }}>Metoda {idx + 1}</strong>
              <TextControl
                label="Nagłówek metody"
                value={m.title}
                onChange={(v) => updateMethod(idx, { title: v })}
              />
              <TextareaControl
                label="Opis metody"
                value={m.desc}
                onChange={(v) => updateMethod(idx, { desc: v })}
                rows={3}
              />
              <p style={{ fontSize: 12, opacity: 0.7, margin: "0 0 8px" }}>
                Wybierz obraz z biblioteki <strong>lub</strong> wklej kod SVG (inline).
                Inline ma priorytet.
              </p>
              <div style={{ display: "flex", gap: "8px", alignItems: "center", marginBottom: "12px", marginTop: "8px", flexWrap: "wrap" }}>
                <MediaUploadCheck>
                  <MediaUpload
                    onSelect={(media) => updateMethod(idx, { icon: media.url })}
                    allowedTypes={["image"]}
                    value={m.icon}
                    render={({ open }) => (
                      <Button variant="secondary" onClick={open}>
                        {m.icon ? "Zmień ikonę" : "Wybierz ikonę z biblioteki"}
                      </Button>
                    )}
                  />
                </MediaUploadCheck>
                {m.icon && (
                  <Button variant="link" isDestructive onClick={() => updateMethod(idx, { icon: "" })}>
                    Usuń ikonę
                  </Button>
                )}
              </div>
              <TextareaControl
                label="Inline SVG (kod)"
                help="Wklej cały tag <svg>...</svg>"
                value={m.iconSvg || ""}
                onChange={(v) => updateMethod(idx, { iconSvg: v })}
                rows={5}
              />
              <div style={{ display: "flex", gap: 4, marginTop: 8, flexWrap: "wrap" }}>
                <Button variant="tertiary" onClick={() => moveMethod(idx, -1)} disabled={idx === 0}>↑</Button>
                <Button variant="tertiary" onClick={() => moveMethod(idx, 1)} disabled={idx === methods.length - 1}>↓</Button>
                <Button variant="link" isDestructive onClick={() => removeMethod(idx)}>Usuń metodę</Button>
              </div>
            </div>
          ))}
          <Button variant="primary" onClick={addMethod}>+ Dodaj metodę</Button>
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
        <ul className="metody-platnosci-ul">
          {methods.map((m, idx) => (
            <div key={idx} className="metody-platnosci-list">
              <div className="metody-platnosci-list-left">
                <RichText tagName="li" value={m.title} onChange={(v) => updateMethod(idx, { title: v })} placeholder={`Tytuł ${idx + 1}`} />
                <RichText tagName="span" value={m.desc} onChange={(v) => updateMethod(idx, { desc: v })} placeholder={`Opis ${idx + 1}`} />
              </div>
              <div className="metody-platnosci-list-right">
                {m.iconSvg ? (
                  <span className="metody-platnosci-icon-svg" dangerouslySetInnerHTML={{ __html: m.iconSvg }} />
                ) : (
                  <MediaUploadCheck>
                    <MediaUpload
                      onSelect={(media) => updateMethod(idx, { icon: media.url })}
                      allowedTypes={["image"]}
                      value={m.icon}
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
                            border: m.icon ? "none" : "1px dashed #ccc",
                            backgroundColor: m.icon ? "transparent" : "#f9f9f9",
                          }}
                          title="Kliknij, aby wybrać obraz PNG/SVG z biblioteki"
                        >
                          {m.icon ? (
                            <img src={m.icon} alt="" style={{ maxHeight: 64, maxWidth: 64, objectFit: "contain" }} />
                          ) : (
                            <span style={{ fontSize: "10px", color: "#999", textAlign: "center", lineHeight: "1.2" }}>Dodaj<br/>zdjęcie</span>
                          )}
                        </div>
                      )}
                    />
                  </MediaUploadCheck>
                )}
              </div>
            </div>
          ))}
        </ul>
      </div>
    </div>
  );
}
