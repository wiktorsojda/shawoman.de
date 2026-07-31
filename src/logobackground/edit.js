import {
  useBlockProps, RichText, InspectorControls, MediaUpload, MediaUploadCheck,
} from "@wordpress/block-editor";
import { PanelBody, Button } from "@wordpress/components";

export default function Edit({ attributes, setAttributes }) {
  const a = attributes;
  const blockProps = useBlockProps({ className: "video-container" });
  return (
    <div {...blockProps}>
      <InspectorControls>
        <PanelBody title="Wideo desktop" initialOpen={true}>
          <MediaUploadCheck>
            <MediaUpload onSelect={(media) => setAttributes({ videoDesktop: media.url })} allowedTypes={["video"]} value={a.videoDesktop}
              render={({ open }) => (<Button variant="secondary" onClick={open}>{a.videoDesktop ? "Zmień" : "Wybierz"}</Button>)} />
          </MediaUploadCheck>
        </PanelBody>
        <PanelBody title="Wideo mobile" initialOpen={false}>
          <MediaUploadCheck>
            <MediaUpload onSelect={(media) => setAttributes({ videoMobile: media.url })} allowedTypes={["video"]} value={a.videoMobile}
              render={({ open }) => (<Button variant="secondary" onClick={open}>{a.videoMobile ? "Zmień" : "Wybierz"}</Button>)} />
          </MediaUploadCheck>
        </PanelBody>
      </InspectorControls>
      <div style={{ minHeight: 200, background: "#f0f0f0", padding: 16 }}>
        <p style={{ color: "#999" }}>Wideo desktop/mobile + tekst pod nim</p>
        <div style={{ marginTop: 16 }}>
          <RichText tagName="div" className="line line-head-mobile" value={a.title} onChange={(v) => setAttributes({ title: v })} placeholder="Tytuł" />
          <RichText tagName="div" className="line line-rest-mobile" value={a.description} onChange={(v) => setAttributes({ description: v })} placeholder="Opis" />
        </div>
      </div>
    </div>
  );
}
