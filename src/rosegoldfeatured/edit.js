import {
  useBlockProps, RichText, InspectorControls, MediaUpload, MediaUploadCheck,
} from "@wordpress/block-editor";
import {
  PanelBody, Button, TextControl, ToggleControl,
} from "@wordpress/components";

export default function Edit({ attributes, setAttributes }) {
  const a = attributes;
  const blockProps = useBlockProps({ className: "rosegoldfeatured" });

  return (
    <div {...blockProps}>
      <InspectorControls>
        <PanelBody title="Obraz produktu" initialOpen={true}>
          <MediaUploadCheck>
            <MediaUpload onSelect={(m) => setAttributes({ image: m.url, imageAlt: m.alt || a.imageAlt })} allowedTypes={["image"]} value={a.image}
              render={({ open }) => (
                <Button variant="secondary" onClick={open}>{a.image ? "Zmień obraz" : "Wybierz obraz"}</Button>
              )} />
          </MediaUploadCheck>
          {a.image && <Button variant="link" isDestructive onClick={() => setAttributes({ image: "" })}>Usuń (użyj domyślnego)</Button>}
          <TextControl label="Alt obrazu" value={a.imageAlt} onChange={(v) => setAttributes({ imageAlt: v })} />
        </PanelBody>

        <PanelBody title="Produkt i koszyk" initialOpen={false}>
          <TextControl type="number" label="ID produktu WooCommerce" help="Klik w obraz → strona produktu. Klik w koszyk → dodanie do koszyka (produkt prosty)." value={a.productId || ""} onChange={(v) => setAttributes({ productId: parseInt(v || 0, 10) })} />
          <TextControl type="number" label="ID wariantu (produkt wielowariantowy)" help="Dla produktów z wariantami — ID konkretnego wariantu. Zostaw 0 dla prostych." value={a.variationId || ""} onChange={(v) => setAttributes({ variationId: parseInt(v || 0, 10) })} />
          <TextControl label="Własny link obrazu (opcjonalnie)" help="Zostaw puste, aby użyć strony produktu z ID." value={a.linkUrl} onChange={(v) => setAttributes({ linkUrl: v })} />
          <ToggleControl label="Pokaż przycisk koszyka" checked={a.showCart} onChange={(v) => setAttributes({ showCart: v })} />
        </PanelBody>
      </InspectorControls>

      <div className="rosegoldfeatured__card">
        <RichText tagName="span" className="rosegoldfeatured__badge" value={a.badge} onChange={(v) => setAttributes({ badge: v })} placeholder="Badge" />
        <div className="rosegoldfeatured__body">
          <div className="rosegoldfeatured__media">
            {a.image
              ? <img src={a.image} alt={a.imageAlt} />
              : <div className="rosegoldfeatured__media-placeholder">Wybierz obraz (lub zostaw domyślny)</div>}
            {a.showCart && <span className="rosegoldfeatured__cart" aria-hidden="true">🛒</span>}
          </div>
          <div className="rosegoldfeatured__content">
            <div className="rosegoldfeatured__head">
              <RichText tagName="h2" className="rosegoldfeatured__title" value={a.title} onChange={(v) => setAttributes({ title: v })} placeholder="Tytuł" />
              <RichText tagName="p" className="rosegoldfeatured__desc" value={a.description} onChange={(v) => setAttributes({ description: v })} placeholder="Opis" />
            </div>
            <div className="rosegoldfeatured__meta">
              <p className="rosegoldfeatured__name">
                <RichText tagName="span" value={a.namePart1} onChange={(v) => setAttributes({ namePart1: v })} placeholder="Zestaw " />
                <RichText tagName="span" className="rosegoldfeatured__name-brand" value={a.nameBrand} onChange={(v) => setAttributes({ nameBrand: v })} placeholder="Shav Woman " />
                <RichText tagName="span" value={a.namePart2} onChange={(v) => setAttributes({ namePart2: v })} placeholder="Rose Gold" />
              </p>
              <RichText tagName="p" className="rosegoldfeatured__sub" value={a.productSub} onChange={(v) => setAttributes({ productSub: v })} placeholder="Podtytuł produktu" />
              <div className="rosegoldfeatured__price">
                <RichText tagName="span" className="rosegoldfeatured__discount" value={a.discountBadge} onChange={(v) => setAttributes({ discountBadge: v })} placeholder="OSZCZĘDZASZ 30%" />
                <span className="rosegoldfeatured__price-row">
                  <RichText tagName="span" className="rosegoldfeatured__price-old" value={a.oldPrice} onChange={(v) => setAttributes({ oldPrice: v })} placeholder="Stara cena" />
                  <RichText tagName="span" className="rosegoldfeatured__price-new" value={a.newPrice} onChange={(v) => setAttributes({ newPrice: v })} placeholder="Nowa cena" />
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
