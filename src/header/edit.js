import {
  useBlockProps, RichText, InspectorControls, MediaUpload, MediaUploadCheck,
} from "@wordpress/block-editor";
import { PanelBody, Button, TextControl, ToggleControl } from "@wordpress/components";

const NAV_NUMS = [1, 2, 3, 4];

export default function Edit({ attributes, setAttributes }) {
  const a = attributes;
  const blockProps = useBlockProps({ className: "header" });
  return (
    <header {...blockProps}>
      <InspectorControls>
        <PanelBody title="Logo" initialOpen={true}>
          <MediaUploadCheck>
            <MediaUpload onSelect={(media) => setAttributes({ logoImage: media.url, logoAlt: media.alt || a.logoAlt })} allowedTypes={["image"]} value={a.logoImage}
              render={({ open }) => (<Button variant="secondary" onClick={open}>{a.logoImage ? "Zmień logo" : "Wybierz logo"}</Button>)} />
          </MediaUploadCheck>
          <TextControl label="Alt logo"     value={a.logoAlt} onChange={(v) => setAttributes({ logoAlt: v })} />
          <TextControl label="Link logo (URL)" value={a.logoURL} onChange={(v) => setAttributes({ logoURL: v })} />
        </PanelBody>
        {NAV_NUMS.map((n) => (
          <PanelBody key={n} title={`Link nawigacji ${n}`} initialOpen={n <= 2}>
            <TextControl label="Etykieta" value={a[`nav${n}Label`]} onChange={(v) => setAttributes({ [`nav${n}Label`]: v })} />
            <TextControl label="URL"      value={a[`nav${n}URL`]}   onChange={(v) => setAttributes({ [`nav${n}URL`]: v })} />
          </PanelBody>
        ))}
        <PanelBody title="Koszyk" initialOpen={false}>
          <ToggleControl label="Pokaż ikonę koszyka" checked={a.showCart} onChange={(v) => setAttributes({ showCart: v })} />
          <TextControl label="URL koszyka" value={a.cartURL} onChange={(v) => setAttributes({ cartURL: v })} />
        </PanelBody>
      </InspectorControls>
      <div className="header__inner">
        <a className="header__logo" href={a.logoURL}>
          {a.logoImage
            ? <img src={a.logoImage} alt={a.logoAlt} />
            : <span style={{ padding: 8, background: "#f0f0f0", color: "#999", fontSize: 12 }}>Logo (wybierz w panelu)</span>}
        </a>
        <nav className="header__nav">
          {NAV_NUMS.map((n) => {
            const lab = a[`nav${n}Label`];
            const url = a[`nav${n}URL`];
            if (!lab) return null;
            return <a key={n} href={url}>{lab}</a>;
          })}
        </nav>
        {a.showCart && (
          <a className="header__cart" href={a.cartURL} aria-label="Koszyk">
            <span style={{ display: "inline-block", width: 24, height: 24, border: "1px solid #999", borderRadius: 4, padding: 4, color: "#999", fontSize: 11, textAlign: "center" }}>🛒</span>
          </a>
        )}
      </div>
    </header>
  );
}
