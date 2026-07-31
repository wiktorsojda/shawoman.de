import { useBlockProps, RichText, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, TextControl } from "@wordpress/components";

export default function Edit({ attributes, setAttributes }) {
  const a = attributes;
  const blockProps = useBlockProps();
  return (
    <div {...blockProps}>
      <InspectorControls>
        <PanelBody title={'Link „Blog Home"'} initialOpen={true}>
          <TextControl label="URL" value={a.blogHomeURL} onChange={(v) => setAttributes({ blogHomeURL: v })} />
        </PanelBody>
        <PanelBody title="Etykiety" initialOpen={false}>
          <RichText tagName="div" value={a.blogHomeLabel}  onChange={(v) => setAttributes({ blogHomeLabel: v })}  placeholder={'„Blog Home"'} />
          <RichText tagName="div" value={a.postedByPrefix} onChange={(v) => setAttributes({ postedByPrefix: v })} placeholder={'„Posted by"'} />
          <RichText tagName="div" value={a.inText}         onChange={(v) => setAttributes({ inText: v })}         placeholder={'„in"'} />
        </PanelBody>
      </InspectorControls>
      <div style={{ padding: 16, background: "#f7f7f7", color: "#666", fontSize: 13 }}>
        <strong>Single Post (artykuł)</strong><br/>
        Treść artykułu z WP. Tu możesz edytować etykiety i URL „Blog Home".
      </div>
    </div>
  );
}
