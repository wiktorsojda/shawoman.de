import { useBlockProps, RichText, InspectorControls } from "@wordpress/block-editor";
import { PanelBody } from "@wordpress/components";

export default function Edit({ attributes, setAttributes }) {
  const blockProps = useBlockProps();
  return (
    <div {...blockProps}>
      <InspectorControls>
        <PanelBody title="Etykiety" initialOpen={true}>
          <RichText tagName="div" value={attributes.continueLabel} onChange={(v) => setAttributes({ continueLabel: v })} placeholder={'Etykieta „czytaj dalej"'} />
          <RichText tagName="div" value={attributes.postedByPrefix} onChange={(v) => setAttributes({ postedByPrefix: v })} placeholder={'„Posted by"'} />
          <RichText tagName="div" value={attributes.inText} onChange={(v) => setAttributes({ inText: v })} placeholder={'„in"'} />
        </PanelBody>
      </InspectorControls>
      <div style={{ padding: 16, background: "#f7f7f7", color: "#666", fontSize: 13 }}>
        <strong>Archive (lista postów / kategoria / tag)</strong><br/>
        Lista jest dynamiczna z WP. Tu możesz edytować tylko etykiety.
      </div>
    </div>
  );
}
