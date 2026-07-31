import { useBlockProps, RichText } from "@wordpress/block-editor";

export default function Edit({ attributes, setAttributes }) {
  const { mainTitle, description } = attributes;
  const blockProps = useBlockProps({ className: "container blacktext-container-kariera" });
  return (
    <div {...blockProps}>
      <div id="text-container">
        <RichText tagName="div" className="section-main-title" value={mainTitle} onChange={(v) => setAttributes({ mainTitle: v })} placeholder="Tytuł" />
        <RichText tagName="div" className="section-text" value={description} onChange={(v) => setAttributes({ description: v })} placeholder="Opis" />
      </div>
    </div>
  );
}
