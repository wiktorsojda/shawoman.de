import {
  useBlockProps, RichText, InspectorControls, MediaUpload, MediaUploadCheck,
} from "@wordpress/block-editor";
import { PanelBody, Button, TextControl } from "@wordpress/components";

export default function Edit({ attributes, setAttributes }) {
  const a = attributes;
  const blockProps = useBlockProps({ className: "shop-nav" });
  return (
    <div {...blockProps}>
      <InspectorControls>
        <PanelBody title="Obraz po prawej (kolumna)" initialOpen={true}>
          <MediaUploadCheck>
            <MediaUpload onSelect={(media) => setAttributes({ categoryImage: media.url })} allowedTypes={["image"]} value={a.categoryImage}
              render={({ open }) => (<Button variant="secondary" onClick={open}>{a.categoryImage ? "Zmień obraz" : "Wybierz obraz"}</Button>)} />
          </MediaUploadCheck>
        </PanelBody>
        <PanelBody title="Przycisk" initialOpen={false}>
          <TextControl label="URL przycisku" value={a.buttonURL} onChange={(v) => setAttributes({ buttonURL: v })} />
        </PanelBody>
        <PanelBody title="Lista produktów" initialOpen={false}>
          <TextControl label="Liczba produktów na kategorię" value={a.productsPerCategory} onChange={(v) => setAttributes({ productsPerCategory: parseInt(v) || 4 })} type="number" />
          <TextControl label="Wyklucz kategorię (nazwa)" value={a.excludeCategoryName} onChange={(v) => setAttributes({ excludeCategoryName: v })} />
        </PanelBody>
      </InspectorControls>
      <div className="dropdown-content">
        <div className="dropdown-inner">
          <div className="categories-column">
            <div style={{ padding: 16, color: "#999", fontSize: 12 }}>Lista kategorii produktów (dynamiczna z WooCommerce)</div>
          </div>
          <div className="image-column" style={{ height: 200, background: a.categoryImage ? `url(${a.categoryImage}) center/cover` : "#f0f0f0", display: "flex", alignItems: "center", justifyContent: "center" }}>
            <button className="menu-slider-button">
              <RichText tagName="span" value={a.buttonLabel} onChange={(v) => setAttributes({ buttonLabel: v })} placeholder="Etykieta" />
            </button>
          </div>
        </div>
      </div>
    </div>
  );
}
