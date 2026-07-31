import { useBlockProps, RichText, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, ColorPicker } from "@wordpress/components";

export default function Edit({ attributes, setAttributes }) {
  const a = attributes;
  const blockProps = useBlockProps({ className: "team container" });
  const memberCount = 20;
  return (
    <div {...blockProps}>
      <InspectorControls>
        <PanelBody title="Kolor wyróżnienia" initialOpen={false}>
          <ColorPicker color={a.highlightColor} onChange={(c) => setAttributes({ highlightColor: c })} enableAlpha={false} />
        </PanelBody>
      </InspectorControls>
      <div className="center">
        <h2>
          <RichText tagName="span" value={a.sectionTitleBefore} onChange={(v) => setAttributes({ sectionTitleBefore: v })} placeholder="Tytuł" />{" "}
          <RichText tagName="span" value={a.sectionTitleHighlight} onChange={(v) => setAttributes({ sectionTitleHighlight: v })} placeholder="Wyróżnienie" style={{ color: a.highlightColor }} />
        </h2>
        <RichText tagName="span" className="center-span" value={a.sectionSubtitle} onChange={(v) => setAttributes({ sectionSubtitle: v })} placeholder="Podtytuł" />
      </div>
      <div className="team-content">
        {Array.from({ length: memberCount }, (_, idx) => idx + 1).map((i) => (
          <div key={i} className={`box ${a[`member${i}BoxClass`] || ""}`}>
            <RichText tagName="h3" className="imie" value={a[`member${i}Name`]} onChange={(v) => setAttributes({ [`member${i}Name`]: v })} placeholder="Imię" />
            <RichText tagName="h3" className="stanowisko" value={a[`member${i}Role`]} onChange={(v) => setAttributes({ [`member${i}Role`]: v })} placeholder="Stanowisko" />
          </div>
        ))}
      </div>
      <RichText tagName="button" id="toggleButton" className="show-more" value={a.showMoreLabel} onChange={(v) => setAttributes({ showMoreLabel: v })} placeholder="Etykieta przycisku" />
    </div>
  );
}
