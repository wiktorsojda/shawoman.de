import { useBlockProps, RichText, InspectorControls } from "@wordpress/block-editor";
import { PanelBody } from "@wordpress/components";

export default function Edit({ attributes, setAttributes }) {
  const blockProps = useBlockProps();
  return (
    <div {...blockProps}>
      <InspectorControls>
        <PanelBody title="Etykiety" initialOpen={true}>
          <RichText tagName="div" value={attributes.backLabel} onChange={(v) => setAttributes({ backLabel: v })} placeholder={'Tekst „wstecz do"'} />
        </PanelBody>
      </InspectorControls>
      <div style={{ padding: 16, background: "#f7f7f7", color: "#666", fontSize: 13 }}>
        <strong>Page (template strony)</strong><br/>
        Treść strony renderowana dynamicznie przez WP — edytuj ją w „Edytuj stronę". Tutaj możesz zmienić tylko etykiety nagłówków.
      </div>
    </div>
  );
}
