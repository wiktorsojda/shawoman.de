import { useBlockProps, RichText, InspectorControls } from "@wordpress/block-editor";
import { PanelBody } from "@wordpress/components";

export default function Edit({ attributes, setAttributes }) {
  const a = attributes;
  const blockProps = useBlockProps({ className: "container ikony-kariera-container" });
  return (
    <div {...blockProps}>
      <InspectorControls>
        <PanelBody title="Pomoc" initialOpen={false}>
          <p style={{ fontSize: 12 }}>Ikony SVG (1-6) sa zdefiniowane na sztywno w szablonie. Edytuj poniższe teksty i nagłówki bezpośrednio w bloku.</p>
        </PanelBody>
      </InspectorControls>
      <div className="container--narrow2-important">
        <div className="blendygo-content">
          <RichText tagName="div" className="section-main-title" value={a.sectionMainTitle} onChange={(v) => setAttributes({ sectionMainTitle: v })} placeholder="Tytuł sekcji" />
          <RichText tagName="div" className="section-text" value={a.sectionText} onChange={(v) => setAttributes({ sectionText: v })} placeholder="Tekst pod tytułem" />
          <div className="features-flex-ikony features-flex-ikony-kariera">
            {[1, 2, 3, 4, 5, 6].map((i) => (
              <div key={i} className="feature-item">
                <div style={{ width: 72, height: 72, background: "#eee", display: "flex", alignItems: "center", justifyContent: "center", color: "#999", fontSize: 11 }}>SVG {i}</div>
                <div className="feature-text-group">
                  <RichText tagName="p" className="feature-text" value={a[`feature${i}`]} onChange={(v) => setAttributes({ [`feature${i}`]: v })} placeholder={`Tekst ${i}`} />
                </div>
              </div>
            ))}
          </div>
        </div>
      </div>
    </div>
  );
}
