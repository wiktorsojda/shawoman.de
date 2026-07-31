import { useBlockProps, RichText, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, TextareaControl } from "@wordpress/components";

export default function Edit({ attributes, setAttributes }) {
  const { title, subtitle, content } = attributes;
  const blockProps = useBlockProps({ className: "faq-container" });
  return (
    <div {...blockProps}>
      <InspectorControls>
        <PanelBody title="Treść regulaminu (HTML)" initialOpen={true}>
          <TextareaControl
            label="HTML"
            help="Pełny HTML akceptowany. Każda pozycja listy jako <li>. Sekcje tytułowe: <li class='regulamin-list-title'>...</li>"
            value={content || ""}
            onChange={(v) => setAttributes({ content: v })}
            rows={25}
          />
        </PanelBody>
      </InspectorControls>
      <div className="container--narrow2-important">
        <RichText tagName="div" className="regulamin-title" value={title} onChange={(v) => setAttributes({ title: v })} placeholder="Tytuł" />
        <RichText tagName="div" className="regulamin-subtitle" value={subtitle} onChange={(v) => setAttributes({ subtitle: v })} placeholder="Podtytuł" />
        <div
          style={{ marginTop: 16, padding: 16, border: "1px dashed #ccc", background: "#fafafa" }}
        >
          <strong style={{ display: "block", marginBottom: 8, fontSize: 12, color: "#666" }}>
            Podgląd treści (edytuj w panelu po prawej):
          </strong>
          <ul
            className="regulamin-list"
            dangerouslySetInnerHTML={{ __html: content || "<li><em>Brak treści — wpisz w panelu Inspector po prawej.</em></li>" }}
          />
        </div>
      </div>
    </div>
  );
}
