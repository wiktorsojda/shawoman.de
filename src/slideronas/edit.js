import {
  useBlockProps, InspectorControls, MediaUpload, MediaUploadCheck,
} from "@wordpress/block-editor";
import { PanelBody, Button } from "@wordpress/components";

export default function Edit({ attributes, setAttributes }) {
  const { backgroundImage } = attributes;
  const wrapperStyle = backgroundImage
    ? { backgroundImage: `url(${backgroundImage})`, backgroundSize: "cover", backgroundPosition: "center", minHeight: 150 }
    : { minHeight: 150, background: "#f0f0f0" };
  const blockProps = useBlockProps({ className: "section-onas-gallery", style: wrapperStyle });
  return (
    <div {...blockProps}>
      <InspectorControls>
        <PanelBody title="Tło — obraz (opcjonalne)" initialOpen={true}>
          <MediaUploadCheck>
            <MediaUpload onSelect={(media) => setAttributes({ backgroundImage: media.url })} allowedTypes={["image"]} value={backgroundImage}
              render={({ open }) => (<Button variant="secondary" onClick={open}>{backgroundImage ? "Zmień obraz" : "Wybierz obraz"}</Button>)} />
          </MediaUploadCheck>
        </PanelBody>
      </InspectorControls>
      {!backgroundImage && <p style={{ padding: 16, textAlign: "center", color: "#999" }}>Slider O Nas — wrapper (treść z JS / CSS)</p>}
    </div>
  );
}
