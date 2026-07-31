import {
  useBlockProps, RichText, InspectorControls, MediaUpload, MediaUploadCheck,
} from "@wordpress/block-editor";
import { PanelBody, Button, TextControl, ToggleControl } from "@wordpress/components";

const AVATAR_NUMS = [1, 2, 3, 4, 5];

export default function Edit({ attributes, setAttributes }) {
  const a = attributes;
  const blockProps = useBlockProps({ className: "glownabaner" });

  return (
    <div {...blockProps}>
      <InspectorControls>
        <PanelBody title="Pasek opinii" initialOpen={false}>
          <ToggleControl label="Pokaż pasek opinii" checked={a.showRating} onChange={(v) => setAttributes({ showRating: v })} />
          {AVATAR_NUMS.map((n) => (
            <MediaUploadCheck key={n}>
              <MediaUpload onSelect={(media) => setAttributes({ [`avatar${n}`]: media.url })} allowedTypes={["image"]} value={a[`avatar${n}`]}
                render={({ open }) => (
                  <Button variant="secondary" onClick={open} style={{ marginBottom: 8 }}>
                    Avatar {n}{a[`avatar${n}`] ? " (zmień)" : ""}
                  </Button>
                )} />
            </MediaUploadCheck>
          ))}
        </PanelBody>

        <PanelBody title="Banner — zdjęcie tła" initialOpen={true}>
          <p style={{ marginTop: 0 }}><strong>Desktop</strong> (≥ 768px)</p>
          <MediaUploadCheck>
            <MediaUpload onSelect={(media) => { console.log("[glownabaner] desktop onSelect:", media); setAttributes({ bannerImage: media?.url || "" }); }} allowedTypes={["image"]} value={a.bannerImage}
              render={({ open }) => (
                <Button variant="secondary" onClick={open} style={{ marginBottom: 16 }}>
                  {a.bannerImage ? "Zmień zdjęcie desktop" : "Wybierz zdjęcie desktop"}
                </Button>
              )} />
          </MediaUploadCheck>

          <p style={{ marginBottom: 8 }}><strong>Mobile</strong> (&lt; 768px) — opcjonalne, pusto = używa desktop</p>
          <MediaUploadCheck>
            <MediaUpload onSelect={(media) => { console.log("[glownabaner] mobile onSelect:", media); setAttributes({ bannerImageMobile: media?.url || "" }); }} allowedTypes={["image"]} value={a.bannerImageMobile}
              render={({ open }) => (
                <Button variant="secondary" onClick={open} style={{ marginRight: 8 }}>
                  {a.bannerImageMobile ? "Zmień zdjęcie mobile" : "Wybierz zdjęcie mobile"}
                </Button>
              )} />
          </MediaUploadCheck>
          {a.bannerImageMobile && (
            <Button variant="link" isDestructive onClick={() => setAttributes({ bannerImageMobile: "" })}>
              Usuń zdjęcie mobile
            </Button>
          )}
        </PanelBody>

        <PanelBody title="Banner — link CTA" initialOpen={false}>
          <TextControl label="URL przycisku" value={a.bannerCtaURL} onChange={(v) => setAttributes({ bannerCtaURL: v })} />
        </PanelBody>
      </InspectorControls>

      {/* Rating */}
      {a.showRating && (
        <div className="glownabaner__rating">
          <span className="glownabaner__rating-line"></span>
          <div className="glownabaner__rating-pill">
            <div className="avatars">
              {AVATAR_NUMS.map((n) => a[`avatar${n}`] && (
                <img key={n} className="avatars__item" src={a[`avatar${n}`]} alt="" />
              ))}
            </div>
            <span className="glownabaner__rating-sep"></span>
            <RichText tagName="span" className="glownabaner__rating-count" value={a.ratingCount} onChange={(v) => setAttributes({ ratingCount: v })} placeholder="+xxx Opinii" />
            <span className="glownabaner__rating-sep"></span>
            <RichText tagName="span" className="glownabaner__rating-score" value={a.ratingScore} onChange={(v) => setAttributes({ ratingScore: v })} placeholder="4.9" />
            <span className="glownabaner__rating-stars" aria-hidden="true">★★★★★</span>
          </div>
          <span className="glownabaner__rating-line"></span>
        </div>
      )}

      {/* Hero banner */}
      <div className="glownabaner__hero">
        {a.bannerImage
          ? <img className="glownabaner__hero-image" src={a.bannerImage} alt="" />
          : <div className="glownabaner__hero-image glownabaner__hero-image--placeholder">Wybierz zdjęcie tła w panelu po prawej</div>}
        <div className="glownabaner__hero-content">
          <h1 className="glownabaner__hero-title">
            <RichText tagName="span" value={a.bannerTitle} onChange={(v) => setAttributes({ bannerTitle: v })} placeholder="Shav " />
            <RichText tagName="span" className="glownabaner__hero-title-accent" value={a.bannerTitleAccent} onChange={(v) => setAttributes({ bannerTitleAccent: v })} placeholder="Days" />
          </h1>
          <RichText tagName="p" className="glownabaner__hero-subtitle" value={a.bannerSubtitle} onChange={(v) => setAttributes({ bannerSubtitle: v })} placeholder="Subtitle" />
          <div className="glownabaner__hero-cta">
            <RichText tagName="span" value={a.bannerCtaLabel} onChange={(v) => setAttributes({ bannerCtaLabel: v })} placeholder="Etykieta CTA" />
            <span className="glownabaner__hero-cta-arrow" aria-hidden="true">→</span>
          </div>
        </div>
      </div>
    </div>
  );
}
