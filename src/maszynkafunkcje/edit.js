import {
  useBlockProps, RichText, InspectorControls, MediaUpload, MediaUploadCheck,
} from "@wordpress/block-editor";
import { PanelBody, Button } from "@wordpress/components";

export default function Edit({ attributes, setAttributes }) {
  const a = attributes;
  const blockProps = useBlockProps({ className: "features-camera-spec__list" });
  return (
    <div {...blockProps}>
      <InspectorControls>
        <PanelBody title="Obraz produktu" initialOpen={true}>
          <MediaUploadCheck>
            <MediaUpload onSelect={(media) => setAttributes({ image: media.url })} allowedTypes={["image"]} value={a.image}
              render={({ open }) => (<Button variant="secondary" onClick={open}>{a.image ? "Zmień obraz" : "Wybierz obraz"}</Button>)} />
          </MediaUploadCheck>
        </PanelBody>
      </InspectorControls>
      <div className="features-camera-spec__list-image">
        {a.image && <img src={a.image} className="product-image" alt="" />}
        <div style={{ display: "grid", gridTemplateColumns: "1fr 1fr", gap: 16, marginTop: 16 }}>
          {[1, 2, 3, 4, 5, 6].map((i) => (
            <div key={i} style={{ padding: 8, border: "1px dashed #ccc" }}>
              <small style={{ color: "#999" }}>Funkcja {i}</small>
              <RichText tagName="div" value={a[`feature${i}Line1`]} onChange={(v) => setAttributes({ [`feature${i}Line1`]: v })} placeholder="Linia 1" />
              <RichText tagName="div" value={a[`feature${i}Line2`]} onChange={(v) => setAttributes({ [`feature${i}Line2`]: v })} placeholder="Linia 2" />
            </div>
          ))}
        </div>
      </div>
    </div>
  );
}
