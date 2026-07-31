import {
  useBlockProps, RichText, InspectorControls, MediaUpload, MediaUploadCheck,
} from "@wordpress/block-editor";
import {
  PanelBody, Button, TextControl, SelectControl,
} from "@wordpress/components";

const ITEMS = [1, 2, 3, 4];

export default function Edit({ attributes, setAttributes }) {
  const a = attributes;
  const blockProps = useBlockProps({ className: `rosegoldzestaw rosegoldzestaw--img-${a.imageSide}` });

  return (
    <div {...blockProps}>
      <InspectorControls>
        <PanelBody title="Zdjęcie" initialOpen={true}>
          <MediaUploadCheck>
            <MediaUpload onSelect={(m) => setAttributes({ image: m.url, imageAlt: m.alt || a.imageAlt })} allowedTypes={["image"]} value={a.image}
              render={({ open }) => (
                <Button variant="secondary" onClick={open}>{a.image ? "Zmień zdjęcie" : "Wybierz zdjęcie"}</Button>
              )} />
          </MediaUploadCheck>
          {a.image && <Button variant="link" isDestructive onClick={() => setAttributes({ image: "" })}>Usuń (użyj domyślnego)</Button>}
          <TextControl label="Alt zdjęcia" value={a.imageAlt} onChange={(v) => setAttributes({ imageAlt: v })} />
          <SelectControl label="Strona zdjęcia" value={a.imageSide} options={[
            { label: "Lewa", value: "left" },
            { label: "Prawa", value: "right" },
          ]} onChange={(v) => setAttributes({ imageSide: v })} />
        </PanelBody>
        <PanelBody title="Przycisk / produkt" initialOpen={false}>
          <TextControl type="number" label="ID produktu WooCommerce" help="Jeśli link poniżej jest pusty, przycisk prowadzi do strony tego produktu." value={a.productId || ""} onChange={(v) => setAttributes({ productId: parseInt(v || 0, 10) })} />
          <TextControl label="Link przycisku (opcjonalnie)" value={a.buttonURL} onChange={(v) => setAttributes({ buttonURL: v })} />
        </PanelBody>
      </InspectorControls>

      <div className="rosegoldzestaw__inner">
        <div className="rosegoldzestaw__media">
          {a.image
            ? <img src={a.image} alt={a.imageAlt} />
            : <div className="rosegoldzestaw__media-placeholder">Wybierz zdjęcie (lub zostaw domyślne)</div>}
        </div>
        <div className="rosegoldzestaw__content">
          <RichText tagName="span" className="rosegoldzestaw__badge" value={a.badge} onChange={(v) => setAttributes({ badge: v })} placeholder="Badge" />
          <RichText tagName="h2" className="rosegoldzestaw__title" value={a.title} onChange={(v) => setAttributes({ title: v })} placeholder="Tytuł" />
          <ul className="rosegoldzestaw__list">
            {ITEMS.map((n) => (
              <li key={n}>
                <RichText tagName="span" value={a[`item${n}`]} onChange={(v) => setAttributes({ [`item${n}`]: v })} placeholder={`Element ${n}`} />
              </li>
            ))}
          </ul>
        </div>
        <div className="rosegoldzestaw__pricebox">
          <div className="rosegoldzestaw__price-row">
            <RichText tagName="span" className="rosegoldzestaw__price-label" value={a.priceInLabel} onChange={(v) => setAttributes({ priceInLabel: v })} placeholder="Cena za zestaw:" />
            <span className="rosegoldzestaw__price-right">
              <RichText tagName="span" className="rosegoldzestaw__discount" value={a.discountBadge} onChange={(v) => setAttributes({ discountBadge: v })} placeholder="OSZCZĘDZASZ 30%" />
              <RichText tagName="span" className="rosegoldzestaw__price-in" value={a.priceIn} onChange={(v) => setAttributes({ priceIn: v })} placeholder="179,00 zł" />
            </span>
          </div>
          <div className="rosegoldzestaw__price-row">
            <RichText tagName="span" className="rosegoldzestaw__price-label" value={a.priceOutLabel} onChange={(v) => setAttributes({ priceOutLabel: v })} placeholder="Cena poza zestawem:" />
            <RichText tagName="span" className="rosegoldzestaw__price-out" value={a.priceOut} onChange={(v) => setAttributes({ priceOut: v })} placeholder="230,00 zł" />
          </div>
        </div>
        <div className="rosegoldzestaw__button">
          <RichText tagName="span" value={a.buttonLabel} onChange={(v) => setAttributes({ buttonLabel: v })} placeholder="Zobacz zestaw" />
          <span className="rosegoldzestaw__button-arrow" aria-hidden="true">→</span>
        </div>
      </div>
    </div>
  );
}
