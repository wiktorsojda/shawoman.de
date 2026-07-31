import { useBlockProps, RichText, InspectorControls } from "@wordpress/block-editor";
import { PanelBody } from "@wordpress/components";

export default function Edit({ attributes, setAttributes }) {
  const a = attributes;
  const blockProps = useBlockProps();
  return (
    <div {...blockProps}>
      <InspectorControls>
        <PanelBody title="Etykiety" initialOpen={true}>
          <RichText tagName="div" value={a.continueLabel}  onChange={(v) => setAttributes({ continueLabel: v })}  placeholder={'„Continue reading"'} />
          <RichText tagName="div" value={a.postedByPrefix} onChange={(v) => setAttributes({ postedByPrefix: v })} placeholder={'„Posted by"'} />
          <RichText tagName="div" value={a.inText}         onChange={(v) => setAttributes({ inText: v })}         placeholder={'„in"'} />
        </PanelBody>
      </InspectorControls>
      <RichText tagName="h1" value={a.bannerTitle}    onChange={(v) => setAttributes({ bannerTitle: v })}    placeholder="Tytuł baneru" />
      <RichText tagName="p"  value={a.bannerSubtitle} onChange={(v) => setAttributes({ bannerSubtitle: v })} placeholder="Podtytuł baneru" />
      <div style={{ padding: 16, background: "#f7f7f7", color: "#666", fontSize: 13 }}>
        <strong>Blog Index (strona główna bloga)</strong><br/>
        Lista postów dynamiczna. Edytuj banner i etykiety.
      </div>
    </div>
  );
}
