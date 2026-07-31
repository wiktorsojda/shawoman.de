import { useBlockProps, RichText } from "@wordpress/block-editor";
export default function Edit({ attributes, setAttributes }) {
  const blockProps = useBlockProps({ className: "blacktext-container container" });
  return (
    <div {...blockProps}>
      <div id="text-container">
        <RichText tagName="div" className="line line-head" value={attributes.title} onChange={(v) => setAttributes({ title: v })} placeholder="Tytuł" />
        <RichText tagName="div" className="line line-rest" value={attributes.description} onChange={(v) => setAttributes({ description: v })} placeholder="Opis" />
      </div>
    </div>
  );
}
