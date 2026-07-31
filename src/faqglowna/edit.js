import { useBlockProps, RichText, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, ToggleControl, SelectControl } from "@wordpress/components";

export default function Edit({ attributes, setAttributes }) {
  const a = attributes;
  const blockProps = useBlockProps({ className: a.containerClass || "glownafaq" });
  const Heading = a.headingTag || "h3";

  // W edytorze nadpisujemy max-height/opacity, zeby panel z odpowiedzia byl
  // zawsze widoczny i edytowalny (front zachowuje accordion).
  const panelEditorStyle = {
    maxHeight: "none",
    opacity: 1,
    paddingBottom: 16,
  };

  return (
    <div {...blockProps}>
      <InspectorControls>
        <PanelBody title="Ustawienia FAQ" initialOpen={true}>
          <ToggleControl label="Pokaż główny tytuł sekcji" checked={a.showTopTitle} onChange={(v) => setAttributes({ showTopTitle: v })} />
          <ToggleControl label="Pokaż dodatkowy tytuł (mniejszy)" checked={a.showTitle} onChange={(v) => setAttributes({ showTitle: v })} />
          <SelectControl
            label="Tag nagłówka pytania"
            value={a.headingTag}
            options={[
              { label: "h2", value: "h2" },
              { label: "h3", value: "h3" },
              { label: "h4", value: "h4" },
              { label: "div", value: "div" },
            ]}
            onChange={(v) => setAttributes({ headingTag: v })}
          />
        </PanelBody>
      </InspectorControls>
      <div className="glownafaq__inner">
        {a.showTopTitle && (
          <RichText tagName="h2" className="glownafaq__top-title" value={a.topTitle} onChange={(v) => setAttributes({ topTitle: v })} placeholder="Tytuł sekcji" />
        )}
        {a.showTitle && (
          <RichText tagName="div" className="glownafaq__title" value={a.title} onChange={(v) => setAttributes({ title: v })} placeholder="Dodatkowy tytuł" />
        )}
        <div className="glownafaq__list">
          {[1, 2, 3, 4, 5, 6, 7, 8, 9, 10].map((i) => {
            const q = a[`question${i}`];
            const ans = a[`answer${i}`];
            if (!q && !ans) return null;
            return (
              <div key={i} className="glownafaq__item">
                <div className="glownafaq__trigger faq-accordion" style={{ display: "flex", justifyContent: "space-between", alignItems: "center", gap: 16 }}>
                  <RichText tagName={Heading} className="glownafaq__question faq-header" value={q} onChange={(v) => setAttributes({ [`question${i}`]: v })} placeholder={`Pytanie ${i}`} />
                  <span className="glownafaq__chevron" aria-hidden="true">▾</span>
                </div>
                <div className="glownafaq__panel faq-pannel" style={panelEditorStyle}>
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
