import {
  useBlockProps, RichText, InspectorControls, MediaUpload, MediaUploadCheck,
} from "@wordpress/block-editor";
import {
  PanelBody, Button, TextControl, ToggleControl,
} from "@wordpress/components";

const PRODUCTS = [1, 2, 3, 4];

export default function Edit({ attributes, setAttributes }) {
  const a = attributes;
  const blockProps = useBlockProps({ className: "rosegoldgrid" });

  return (
    <div {...blockProps}>
      <InspectorControls>
        <PanelBody title="Ustawienia sekcji" initialOpen={true}>
          <ToggleControl label="Pokaż przyciski koszyka" checked={a.showCart} onChange={(v) => setAttributes({ showCart: v })} />
        </PanelBody>
        {PRODUCTS.map((n) => (
          <PanelBody key={n} title={`Produkt ${n}`} initialOpen={false}>
            <MediaUploadCheck>
              <MediaUpload onSelect={(m) => setAttributes({ [`prod${n}Image`]: m.url })} allowedTypes={["image"]} value={a[`prod${n}Image`]}
                render={({ open }) => (
                  <Button variant="secondary" onClick={open}>{a[`prod${n}Image`] ? "Zmień obraz" : "Wybierz obraz"}</Button>
                )} />
            </MediaUploadCheck>
            {a[`prod${n}Image`] && <Button variant="link" isDestructive onClick={() => setAttributes({ [`prod${n}Image`]: "" })}>Usuń (użyj domyślnego)</Button>}
            <TextControl type="number" label="ID produktu WooCommerce" value={a[`prod${n}ProductId`] || ""} onChange={(v) => setAttributes({ [`prod${n}ProductId`]: parseInt(v || 0, 10) })} />
            <TextControl type="number" label="ID wariantu (produkt wielowariantowy)" help="Dla produktów z wariantami — ID konkretnego wariantu (edycja produktu → Warianty). Zostaw 0 dla prostych." value={a[`prod${n}VariationId`] || ""} onChange={(v) => setAttributes({ [`prod${n}VariationId`]: parseInt(v || 0, 10) })} />
            <TextControl label="Własny link obrazu (opcjonalnie)" value={a[`prod${n}LinkUrl`]} onChange={(v) => setAttributes({ [`prod${n}LinkUrl`]: v })} />
          </PanelBody>
        ))}
      </InspectorControls>

      <h2 className="rosegoldgrid__heading">
        <RichText tagName="span" value={a.heading1} onChange={(v) => setAttributes({ heading1: v })} placeholder="Zobacz produkty dropu " />
        <RichText tagName="span" className="rosegoldgrid__heading-accent" value={a.heading2Accent} onChange={(v) => setAttributes({ heading2Accent: v })} placeholder="Rose Gold" />
      </h2>

      <div className="rosegoldgrid__grid">
        {PRODUCTS.map((n) => (
          <div className="rosegoldgrid__card" key={n}>
            <RichText tagName="span" className="rosegoldgrid__badge" value={a[`prod${n}Badge`]} onChange={(v) => setAttributes({ [`prod${n}Badge`]: v })} placeholder="Badge" />
            <div className="rosegoldgrid__media">
              {a[`prod${n}Image`]
                ? <img src={a[`prod${n}Image`]} alt="" />
                : <div className="rosegoldgrid__media-placeholder">Obraz {n}</div>}
              {a.showCart && <span className="rosegoldgrid__cart" aria-hidden="true">🛒</span>}
            </div>
            <div className="rosegoldgrid__info">
              <div className="rosegoldgrid__text">
                <p className="rosegoldgrid__title">
                  <RichText tagName="span" value={a[`prod${n}Title`]} onChange={(v) => setAttributes({ [`prod${n}Title`]: v })} placeholder="Nazwa produktu " />
                  <RichText tagName="span" className="rosegoldgrid__brand" value={a[`prod${n}Brand`]} onChange={(v) => setAttributes({ [`prod${n}Brand`]: v })} placeholder="Marka" />
                </p>
                <RichText tagName="p" className="rosegoldgrid__sub" value={a[`prod${n}Sub`]} onChange={(v) => setAttributes({ [`prod${n}Sub`]: v })} placeholder="Podtytuł" />
              </div>
              <div className="rosegoldgrid__price">
                <RichText tagName="span" className="rosegoldgrid__price-old" value={a[`prod${n}OldPrice`]} onChange={(v) => setAttributes({ [`prod${n}OldPrice`]: v })} placeholder="Stara cena (opcj.)" />
                <RichText tagName="span" className="rosegoldgrid__price-new" value={a[`prod${n}NewPrice`]} onChange={(v) => setAttributes({ [`prod${n}NewPrice`]: v })} placeholder="Cena" />
              </div>
              <RichText tagName="span" className="rosegoldgrid__bottom-badge" value={a[`prod${n}BottomBadge`]} onChange={(v) => setAttributes({ [`prod${n}BottomBadge`]: v })} placeholder="Dolny badge (opcj.)" />
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}
