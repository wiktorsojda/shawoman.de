import {
  useBlockProps, RichText, InspectorControls, MediaUpload, MediaUploadCheck,
} from "@wordpress/block-editor";
import { PanelBody, Button } from "@wordpress/components";

const AVATAR_NUMS = [1, 2, 3, 4, 5];

export default function Edit({ attributes, setAttributes }) {
  const a = attributes;
  const blockProps = useBlockProps({ className: "glownaoceny" });

  return (
    <div {...blockProps}>
      <InspectorControls>
        <PanelBody title="Karta 1 — ikona" initialOpen={false}>
          <MediaUploadCheck>
            <MediaUpload onSelect={(media) => setAttributes({ card1Icon: media.url })} allowedTypes={["image"]} value={a.card1Icon}
              render={({ open }) => (<Button variant="secondary" onClick={open}>{a.card1Icon ? "Zmień ikonę" : "Wybierz ikonę"}</Button>)} />
          </MediaUploadCheck>
        </PanelBody>
        <PanelBody title="Karta 2 — avatary" initialOpen={false}>
          {AVATAR_NUMS.map((n) => (
            <MediaUploadCheck key={n}>
              <MediaUpload onSelect={(media) => setAttributes({ [`card2Avatar${n}`]: media.url })} allowedTypes={["image"]} value={a[`card2Avatar${n}`]}
                render={({ open }) => (
                  <Button variant="secondary" onClick={open} style={{ marginBottom: 8 }}>
                    Avatar {n}{a[`card2Avatar${n}`] ? " (zmień)" : ""}
                  </Button>
                )} />
            </MediaUploadCheck>
          ))}
        </PanelBody>
        <PanelBody title="Karta 3 — ikona" initialOpen={false}>
          <MediaUploadCheck>
            <MediaUpload onSelect={(media) => setAttributes({ card3Icon: media.url })} allowedTypes={["image"]} value={a.card3Icon}
              render={({ open }) => (<Button variant="secondary" onClick={open}>{a.card3Icon ? "Zmień ikonę" : "Wybierz ikonę"}</Button>)} />
          </MediaUploadCheck>
        </PanelBody>
      </InspectorControls>

      <h2 className="glownaoceny__title">
        <RichText tagName="span" value={a.titleBefore} onChange={(v) => setAttributes({ titleBefore: v })} placeholder="Shav " />
        <RichText tagName="span" className="glownaoceny__title-accent" value={a.titleAccent} onChange={(v) => setAttributes({ titleAccent: v })} placeholder="woman." />
        <RichText tagName="span" className="glownaoceny__title-after" value={a.titleAfter} onChange={(v) => setAttributes({ titleAfter: v })} placeholder=" Dlaczego warto wybrać?" />
      </h2>

      <div className="glownaoceny__cards">
        {/* Karta 1 */}
        <div className="glownaoceny__card">
          <div className="glownaoceny__card-icon">
            {a.card1Icon
              ? <img src={a.card1Icon} alt="" />
              : <span className="glownaoceny__card-icon-placeholder">ikona</span>}
          </div>
          <p className="glownaoceny__card-text">
            <RichText tagName="span" value={a.card1TextStrong} onChange={(v) => setAttributes({ card1TextStrong: v })} placeholder="Tekst pierwszy" />
            {" "}
            <RichText tagName="span" className="glownaoceny__card-text-light" value={a.card1TextLight} onChange={(v) => setAttributes({ card1TextLight: v })} placeholder="Tekst drugi" />
          </p>
        </div>

        {/* Karta 2 */}
        <div className="glownaoceny__card">
          <RichText tagName="p" className="glownaoceny__card-title" value={a.card2Title} onChange={(v) => setAttributes({ card2Title: v })} placeholder="Tytuł" />
          <div className="glownaoceny__card-avatars">
            {AVATAR_NUMS.map((n) => a[`card2Avatar${n}`] && (
              <img key={n} src={a[`card2Avatar${n}`]} alt="" />
            ))}
          </div>
          <div className="glownaoceny__card-pill">
            <RichText tagName="span" value={a.card2RatingCount} onChange={(v) => setAttributes({ card2RatingCount: v })} placeholder="+300K Opinii" />
            <span className="glownaoceny__card-pill-sep" aria-hidden="true"></span>
            <RichText tagName="span" value={a.card2RatingScore} onChange={(v) => setAttributes({ card2RatingScore: v })} placeholder="4.9" />
            <span className="glownaoceny__card-pill-stars" aria-hidden="true">★★★★★</span>
          </div>
        </div>

        {/* Karta 3 */}
        <div className="glownaoceny__card">
          <div className="glownaoceny__card-icon">
            {a.card3Icon
              ? <img src={a.card3Icon} alt="" />
              : <span className="glownaoceny__card-icon-placeholder">ikona</span>}
          </div>
          <p className="glownaoceny__card-text">
            <RichText tagName="span" value={a.card3TextStrong} onChange={(v) => setAttributes({ card3TextStrong: v })} placeholder="Tekst pierwszy" />
            {" "}
            <RichText tagName="span" className="glownaoceny__card-text-light" value={a.card3TextLight} onChange={(v) => setAttributes({ card3TextLight: v })} placeholder="Tekst drugi" />
          </p>
        </div>
      </div>
    </div>
  );
}
