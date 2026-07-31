import { useBlockProps, RichText, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, ToggleControl, SelectControl } from "@wordpress/components";

export default function Edit({ attributes, setAttributes }) {
  const a = attributes;
  const blockProps = useBlockProps({ className: a.containerClass });
  const Heading = a.headingTag || "h2";
  // W edytorze nadpisujemy max-height/opacity żeby panel z odpowiedzią
  // był zawsze widoczny i edytowalny (front zachowuje accordion).
  const panelEditorStyle = {
    maxHeight: "none",
    opacity: 1,
    paddingBottom: 16,
  };
  return (
    <div {...blockProps}>
      <InspectorControls>
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
      <div className="faq-wrapper container--narrow2-important">
        {a.showTitle && (
          <RichText tagName="div" className="faq-title" value={a.title} onChange={(v) => setAttributes({ title: v })} placeholder="Tytuł sekcji" />
        )}
        <div className="faq-wrapper-questions">
          {[1, 2, 3, 4, 5, 6, 7, 8, 9, 10].map((i) => {
            const q = a[`question${i}`];
            const ans = a[`answer${i}`];
            if (!q && !ans) return null;
            return (
              <div key={i} className="faq" style={{ borderBottom: "1px solid #e5e5e5", paddingBottom: 12, marginBottom: 12 }}>
                <div className="faq-accordion" style={{ display: "flex", justifyContent: "space-between", alignItems: "center", padding: "12px 0" }}>
                  <RichText tagName={Heading} className="faq-header" value={q} onChange={(v) => setAttributes({ [`question${i}`]: v })} placeholder={`Pytanie ${i}`} />
                </div>
                <div className="faq-pannel" style={panelEditorStyle}>
                  <RichText tagName="p" value={ans} onChange={(v) => setAttributes({ [`answer${i}`]: v })} placeholder={`Odpowiedź ${i}`} />
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
