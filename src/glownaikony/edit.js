import { useBlockProps, RichText } from "@wordpress/block-editor";

export default function Edit({ attributes, setAttributes }) {
  const a = attributes;
  const blockProps = useBlockProps({ className: "blacktext-container-glowna container" });
  return (
    <div {...blockProps}>
      <div className="glowna-ikony-container container--narrow2-important">
        <div className="title-container">
          <RichText tagName="h2" value={a.title} onChange={(v) => setAttributes({ title: v })} placeholder="Tytuł" />
        </div>
        <div className="ikony-subcontainer">
          <div className="ikony-container">
            {[1, 2, 3, 4, 5].map((i) => (
              <div key={i} className="ikona-container">
                <div style={{ width: 60, height: 60, background: "#f5f5f5", display: "flex", alignItems: "center", justifyContent: "center", color: "#999", fontSize: 11 }}>SVG {i}</div>
                <RichText tagName="span" value={a[`label${i}`]} onChange={(v) => setAttributes({ [`label${i}`]: v })} placeholder={`Etykieta ${i}`} />
              </div>
            ))}
          </div>
        </div>
      </div>
    </div>
  );
}
