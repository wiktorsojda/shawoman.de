import { useBlockProps, RichText } from "@wordpress/block-editor";

export default function Edit({ attributes, setAttributes }) {
  const a = attributes;
  const blockProps = useBlockProps({ className: "faq-container-glowna" });
  return (
    <div {...blockProps}>
      <div className="faq-wrapper container--narrow2-important">
        <RichText tagName="div" className="section-main-title" value={a.sectionTitle} onChange={(v) => setAttributes({ sectionTitle: v })} placeholder="Tytuł sekcji" />
        <div className="faq-wrapper-questions">
          {[1, 2, 3, 4, 5, 6].map((i) => {
            const t = a[`offer${i}Title`];
            const c = a[`offer${i}Content`];
            if (!t && !c) return null;
            return (
              <div key={i} className="faq" style={{ marginBottom: 16 }}>
                <button className="faq-accordion" type="button" style={{ background: "#f7f7f7", padding: 20, borderRadius: 8 }}>
                  <RichText tagName="h3" className="faq-header" value={t} onChange={(v) => setAttributes({ [`offer${i}Title`]: v })} placeholder={`Tytuł oferty ${i}`} />
                </button>
                <div className="faq-pannel" style={{ padding: 20 }}>
                  <RichText tagName="div" value={c} onChange={(v) => setAttributes({ [`offer${i}Content`]: v })} placeholder="Treść oferty (HTML)" />
                </div>
              </div>
            );
          })}
          <p style={{ fontSize: 12, color: "#999", marginTop: 16 }}>Aby dodać kolejne oferty, wpisz je w slotach 1-6 (puste są pomijane na froncie).</p>
        </div>
      </div>
    </div>
  );
}
