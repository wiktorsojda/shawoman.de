import { useBlockProps, RichText, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, ToggleControl, SelectControl, TextareaControl, Button } from "@wordpress/components";
import { useState } from "@wordpress/element";

export default function Edit({ attributes, setAttributes }) {
  const a = attributes;
  const blockProps = useBlockProps({ className: a.containerClass || "faq-kontakt-container" });
  const Heading = a.headingTag || "h2";
  const [importJson, setImportJson] = useState("");

  const panelEditorStyle = {
    maxHeight: "none",
    opacity: 1,
    paddingBottom: 16,
  };

  return (
    <div {...blockProps}>
      <InspectorControls>
        <PanelBody title="Tłumaczenia AI (JSON)" initialOpen={false}>
          <TextareaControl
            label="Skopiuj ten JSON dla AI"
            value={(() => {
              const data = { title: a.title || '' };
              for (let i = 1; i <= 10; i++) {
                if (a[`question${i}`] || a[`answer${i}`]) {
                  data[`question${i}`] = a[`question${i}`] || '';
                  data[`answer${i}`] = a[`answer${i}`] || '';
                }
              }
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
              if (parsed.title !== undefined) updates.title = parsed.title;
              for (let i = 1; i <= 10; i++) {
                if (parsed[`question${i}`] !== undefined) updates[`question${i}`] = parsed[`question${i}`];
                if (parsed[`answer${i}`] !== undefined) updates[`answer${i}`] = parsed[`answer${i}`];
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
        <PanelBody title="Ustawienia FAQ" initialOpen={true}>
          <ToggleControl label="Pokaż tytuł sekcji" checked={a.showTitle} onChange={(v) => setAttributes({ showTitle: v })} />
          <SelectControl
            label="Tag nagłówka pytania"
            value={a.headingTag}
            options={[
              { label: "h2", value: "h2" },
              { label: "h3", value: "h3" },
              { label: "div", value: "div" },
            ]}
            onChange={(v) => setAttributes({ headingTag: v })}
          />
        </PanelBody>
      </InspectorControls>
      <div className="faq-wrapper container--narrow2-important" style={{ maxWidth: 992, margin: "0 auto", width: "100%" }}>
        {a.showTitle && (
          <RichText tagName="div" className="faq-title" value={a.title} onChange={(v) => setAttributes({ title: v })} placeholder="Tytuł sekcji" style={{ textAlign: "center", marginBottom: 48, fontWeight: 500, fontFamily: "var(--wp--preset--font-family--base, 'Be Vietnam Pro', sans-serif)", fontSize: "clamp(44px, 4vw, 64px)", lineHeight: "120%", color: "#252525" }} />
        )}
        <div className="shav-product-faq">
          {[1, 2, 3, 4, 5, 6, 7, 8, 9, 10].map((i) => {
            const q = a[`question${i}`];
            const ans = a[`answer${i}`];
            if (!q && !ans) return null;
            return (
              <div key={i} className="shav-faq-item" style={{ background: "#F2F2F2", borderRadius: 8, marginBottom: 10, overflow: "hidden" }}>
                <div className="shav-faq-header" style={{ display: "flex", justifyContent: "space-between", alignItems: "center", padding: "32px 24px" }}>
                  <RichText tagName={Heading} className="shav-faq-question" style={{ fontSize: 15, fontWeight: 500, color: "#3F3F3F", margin: 0, width: "100%" }} value={q} onChange={(v) => setAttributes({ [`question${i}`]: v })} placeholder={`Pytanie ${i}`} />
                  <svg className="shav-faq-icon shav-faq-plus" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" style={{ flexShrink: 0, marginLeft: 10 }}>
                      <path className="h-line" d="M4 10h12" stroke="#3F3F3F" strokeWidth="2" strokeLinecap="round"/>
                      <path className="v-line" d="M10 4v12" stroke="#3F3F3F" strokeWidth="2" strokeLinecap="round"/>
                  </svg>
                </div>
                <div className="shav-faq-content" style={panelEditorStyle}>
                  <RichText tagName="div" value={ans} onChange={(v) => setAttributes({ [`answer${i}`]: v })} placeholder={`Odpowiedź ${i}`} style={{ padding: "0 24px 32px 24px", fontSize: 14, color: "#555", margin: 0 }} />
                </div>
              </div>
            );
          })}
          <p style={{ fontSize: 12, color: "#999", marginTop: 16 }}>Aby dodać kolejne pytania, wpisz je w slotach 1-10 (puste są pomijane na froncie).</p>
        </div>
      </div>
    </div>
  );
}
