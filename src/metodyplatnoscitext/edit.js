import {
  useBlockProps, RichText, InspectorControls, MediaUpload, MediaUploadCheck,
} from "@wordpress/block-editor";
import { PanelBody, Button, TextareaControl } from "@wordpress/components";
import { useEffect } from "@wordpress/element";

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
  const a = attributes;
  const methods = Array.isArray(a.methods) && a.methods.length > 0 ? a.methods : migrateLegacy(a);

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

  return (
    <div {...blockProps}>
      <InspectorControls>
        <PanelBody title="Metody płatności" initialOpen={true}>
          {methods.map((m, idx) => (
            <div key={idx} style={{ border: "1px solid #ddd", padding: 12, borderRadius: 4, marginBottom: 12 }}>
              <strong style={{ display: "block", marginBottom: 8 }}>Metoda {idx + 1}</strong>
              <p style={{ fontSize: 12, opacity: 0.7, margin: "0 0 8px" }}>
                Wybierz obraz z biblioteki <strong>lub</strong> wklej kod SVG (inline).
                Inline ma priorytet.
              </p>
              <MediaUploadCheck>
                <MediaUpload
                  onSelect={(media) => updateMethod(idx, { icon: media.url })}
                  allowedTypes={["image"]}
                  value={m.icon}
                  render={({ open }) => (
                    <Button variant="secondary" onClick={open}>
                      {m.icon ? "Zmień ikonę (URL)" : "Wybierz ikonę z biblioteki"}
                    </Button>
                  )}
                />
              </MediaUploadCheck>
              {m.icon && (
                <Button variant="link" isDestructive onClick={() => updateMethod(idx, { icon: "" })} style={{ marginTop: 8, marginBottom: 8 }}>
                  Usuń URL
                </Button>
              )}
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
      </InspectorControls>

      <div className="metody-wysylki-textcontainer container--narrow2-important">
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
                {m.iconSvg
                  ? <span className="metody-platnosci-icon-svg" dangerouslySetInnerHTML={{ __html: m.iconSvg }} />
                  : m.icon ? <img src={m.icon} alt="" style={{ maxHeight: 50 }} /> : null}
              </div>
            </div>
          ))}
        </ul>
      </div>
    </div>
  );
}
