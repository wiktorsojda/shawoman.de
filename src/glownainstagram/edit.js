import {
  useBlockProps, RichText, InspectorControls, MediaUpload, MediaUploadCheck,
} from "@wordpress/block-editor";
import { PanelBody, Button, TextControl } from "@wordpress/components";

export default function Edit({ attributes, setAttributes }) {
  const a = attributes;
  const blockProps = useBlockProps({ className: "glownainstagram" });
  return (
    <div {...blockProps}>
      <InspectorControls>
        <PanelBody title="Logo profilu" initialOpen={true}>
          <MediaUploadCheck>
            <MediaUpload onSelect={(media) => setAttributes({ profileLogo: media.url })} allowedTypes={["image"]} value={a.profileLogo}
              render={({ open }) => (<Button variant="secondary" onClick={open}>{a.profileLogo ? "Zmień logo" : "Wybierz logo"}</Button>)} />
          </MediaUploadCheck>
        </PanelBody>
        <PanelBody title="Profil — link" initialOpen={false}>
          <TextControl label="URL profilu Instagram" value={a.profileURL} onChange={(v) => setAttributes({ profileURL: v })} />
        </PanelBody>
        <PanelBody title="Shortcode pluginu" initialOpen={false}>
          <TextControl label="Shortcode" help="Domyślnie: [instagram-feed feed=1]" value={a.shortcode} onChange={(v) => setAttributes({ shortcode: v })} />
        </PanelBody>
      </InspectorControls>

      <RichText tagName="h2" className="glownainstagram__title" value={a.sectionTitle} onChange={(v) => setAttributes({ sectionTitle: v })} placeholder="Tytuł sekcji" />

      <div className="glownainstagram__profile">
        <div className="glownainstagram__profile-avatar">
          {a.profileLogo
            ? <img src={a.profileLogo} alt="" />
            : <span style={{ fontSize: 11, color: "#999" }}>logo</span>}
        </div>
        <div className="glownainstagram__profile-text">
          <RichText tagName="p" className="glownainstagram__profile-name" value={a.profileName} onChange={(v) => setAttributes({ profileName: v })} placeholder="@nazwa" />
          <RichText tagName="p" className="glownainstagram__profile-followers" value={a.profileFollowers} onChange={(v) => setAttributes({ profileFollowers: v })} placeholder="X obserwujących" />
        </div>
      </div>

      <div className="glownainstagram__feed">
        <div style={{ padding: 32, background: "#f0f0f0", textAlign: "center", color: "#666" }}>
          <strong>Instagram Feed</strong><br />
          <small style={{ fontSize: 12 }}>Renderowane na froncie przez shortcode: <code>{a.shortcode}</code></small>
        </div>
      </div>
    </div>
  );
}
