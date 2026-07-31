import { useBlockProps, RichText, InspectorControls } from "@wordpress/block-editor";
import { PanelBody } from "@wordpress/components";

export default function Edit({ attributes, setAttributes }) {
  const a = attributes;
  const blockProps = useBlockProps({ className: "faq-container faq-container-kontakt" });
  return (
    <div {...blockProps}>
      <InspectorControls>
        <PanelBody title="Etykiety zakładek" initialOpen={false}>
          <RichText tagName="div" value={a.tab1Label} onChange={(v) => setAttributes({ tab1Label: v })} placeholder="Etykieta zakładki 1" />
          <RichText tagName="div" value={a.tab2Label} onChange={(v) => setAttributes({ tab2Label: v })} placeholder="Etykieta zakładki 2" />
        </PanelBody>
      </InspectorControls>
      <div className="container--narrow2-important">
        <div className="regulamin-title-container">
          <RichText tagName="div" className="regulamin-title" value={a.title} onChange={(v) => setAttributes({ title: v })} placeholder="Tytuł" />
          <RichText tagName="div" className="regulamin-subtitle" value={a.subtitle} onChange={(v) => setAttributes({ subtitle: v })} placeholder="Podtytuł" />
        </div>
        <div style={{ marginTop: 24, padding: 16, border: "1px dashed #ccc" }}>
          <strong>Treść zakładki ZWROTY:</strong>
          <RichText tagName="div" value={a.zwrotyContent} onChange={(v) => setAttributes({ zwrotyContent: v })} placeholder="Treść zakładki Zwroty (HTML akceptowany)" />
        </div>
        <div style={{ marginTop: 24, padding: 16, border: "1px dashed #ccc" }}>
          <strong>Treść zakładki REKLAMACJE:</strong>
          <RichText tagName="div" value={a.reklamacjeContent} onChange={(v) => setAttributes({ reklamacjeContent: v })} placeholder="Treść zakładki Reklamacje (HTML akceptowany)" />
        </div>
      </div>
    </div>
  );
}
