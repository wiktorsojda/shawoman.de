import {
  useBlockProps, RichText, InspectorControls, MediaUpload, MediaUploadCheck,
} from "@wordpress/block-editor";
import { PanelBody, Button, TextControl, ToggleControl } from "@wordpress/components";

const COL1_NUMS = [1, 2, 3, 4, 5, 6];
const COL2_NUMS = [1, 2, 3, 4, 5, 6];
const SOCIAL_NUMS = [1, 2, 3, 4];

export default function Edit({ attributes, setAttributes }) {
  const a = attributes;
  const blockProps = useBlockProps({ className: "footer" });

  return (
    <footer {...blockProps}>
      <InspectorControls>
        <PanelBody title="Karta CTA — link przycisku" initialOpen={true}>
          <TextControl label="URL przycisku" value={a.ctaButtonURL} onChange={(v) => setAttributes({ ctaButtonURL: v })} />
        </PanelBody>
        <PanelBody title="Kolumna 1 — linki" initialOpen={false}>
          {COL1_NUMS.map((n) => (
            <TextControl key={n} label={`Link ${n} URL`} value={a[`col1Link${n}URL`]} onChange={(v) => setAttributes({ [`col1Link${n}URL`]: v })} />
          ))}
        </PanelBody>
        <PanelBody title="Kolumna 2 — linki" initialOpen={false}>
          {COL2_NUMS.map((n) => (
            <TextControl key={n} label={`Link ${n} URL`} value={a[`col2Link${n}URL`]} onChange={(v) => setAttributes({ [`col2Link${n}URL`]: v })} />
          ))}
        </PanelBody>
        {SOCIAL_NUMS.map((n) => (
          <PanelBody key={n} title={`Social ${n}`} initialOpen={false}>
            <MediaUploadCheck>
              <MediaUpload onSelect={(media) => setAttributes({ [`social${n}Icon`]: media.url })} allowedTypes={["image"]} value={a[`social${n}Icon`]}
                render={({ open }) => (<Button variant="secondary" onClick={open}>{a[`social${n}Icon`] ? "Zmień ikonę" : "Wybierz ikonę"}</Button>)} />
            </MediaUploadCheck>
            <TextControl label="Nazwa (aria-label)" value={a[`social${n}Label`]} onChange={(v) => setAttributes({ [`social${n}Label`]: v })} />
            <TextControl label="URL" value={a[`social${n}URL`]} onChange={(v) => setAttributes({ [`social${n}URL`]: v })} />
          </PanelBody>
        ))}
        <PanelBody title="Stopka — dolny pasek" initialOpen={false}>
          <ToggleControl label={'Pokaż przycisk „back to top"'} checked={a.showBackToTop} onChange={(v) => setAttributes({ showBackToTop: v })} />
          <TextControl label="Polityka URL" value={a.policyURL} onChange={(v) => setAttributes({ policyURL: v })} />
          <TextControl label="Regulamin URL" value={a.termsURL} onChange={(v) => setAttributes({ termsURL: v })} />
          <MediaUploadCheck>
            <MediaUpload onSelect={(media) => setAttributes({ bottomLogo: media.url })} allowedTypes={["image"]} value={a.bottomLogo}
              render={({ open }) => (<Button variant="secondary" onClick={open} style={{ marginTop: 8 }}>{a.bottomLogo ? "Zmień logo" : "Wybierz logo dolne"}</Button>)} />
          </MediaUploadCheck>
        </PanelBody>
      </InspectorControls>

      <div className="footer__inner">
        <div className="footer__top">
          {/* Karta CTA */}
          <div className="footer__cta">
            <h2 className="footer__cta-title">
              <RichText tagName="span" value={a.ctaTitleBefore} onChange={(v) => setAttributes({ ctaTitleBefore: v })} placeholder="Shav " />
              <RichText tagName="span" className="footer__cta-title-accent" value={a.ctaTitleAccent} onChange={(v) => setAttributes({ ctaTitleAccent: v })} placeholder="woman." />
            </h2>
            <RichText tagName="p" className="footer__cta-subtitle" value={a.ctaSubtitle} onChange={(v) => setAttributes({ ctaSubtitle: v })} placeholder="Subtitle" />
            <span className="footer__cta-button">
              <RichText tagName="span" value={a.ctaButtonLabel} onChange={(v) => setAttributes({ ctaButtonLabel: v })} placeholder="Etykieta" />
              <span className="footer__cta-button-arrow" aria-hidden="true">→</span>
            </span>
          </div>

          {/* Linki + social */}
          <div className="footer__links">
            <div className="footer__col">
              <RichText tagName="p" className="footer__col-title" value={a.col1Title} onChange={(v) => setAttributes({ col1Title: v })} placeholder="Tytuł" />
              <ul className="footer__col-list">
                {COL1_NUMS.map((n) => (
                  <li key={n} style={!a[`col1Link${n}Label`] ? { opacity: 0.5, fontStyle: "italic" } : undefined}>
                    <RichText
                      tagName="span"
                      value={a[`col1Link${n}Label`]}
                      onChange={(v) => setAttributes({ [`col1Link${n}Label`]: v })}
                      placeholder={`Link ${n} — wpisz nazwę`}
                    />
                  </li>
                ))}
              </ul>
            </div>

            <div className="footer__col">
              <RichText tagName="p" className="footer__col-title" value={a.col2Title} onChange={(v) => setAttributes({ col2Title: v })} placeholder="Tytuł" />
              <ul className="footer__col-list">
                {COL2_NUMS.map((n) => (
                  <li key={n} style={!a[`col2Link${n}Label`] ? { opacity: 0.5, fontStyle: "italic" } : undefined}>
                    <RichText
                      tagName="span"
                      value={a[`col2Link${n}Label`]}
                      onChange={(v) => setAttributes({ [`col2Link${n}Label`]: v })}
                      placeholder={`Link ${n} — wpisz nazwę`}
                    />
                  </li>
                ))}
              </ul>
            </div>

            <div className="footer__social">
              {SOCIAL_NUMS.map((n) => (
                <div key={n} className="footer__social-icon">
                  {a[`social${n}Icon`]
                    ? <img src={a[`social${n}Icon`]} alt={a[`social${n}Label`]} />
                    : <span style={{ fontSize: 9, color: "#999" }}>{a[`social${n}Label`].charAt(0)}</span>}
                </div>
              ))}
            </div>
          </div>
        </div>

        <hr className="footer__separator" />

        <div className="footer__bottom">
          <RichText tagName="p" className="footer__copyright" value={a.copyright} onChange={(v) => setAttributes({ copyright: v })} placeholder="Copyright" />
          <div className="footer__logo">
            {a.bottomLogo
              ? <img src={a.bottomLogo} alt="" />
              : <span style={{ fontSize: 11, color: "#999" }}>logo</span>}
          </div>
          <div className="footer__legal">
            <RichText tagName="span" value={a.policyLabel} onChange={(v) => setAttributes({ policyLabel: v })} placeholder="Polityka prywatności" />
            <span className="footer__legal-sep" aria-hidden="true"></span>
            <RichText tagName="span" value={a.termsLabel} onChange={(v) => setAttributes({ termsLabel: v })} placeholder="Regulamin" />
            {a.showBackToTop && (
              <>
                <span className="footer__legal-sep" aria-hidden="true"></span>
                <span className="footer__back-to-top" aria-hidden="true">↑</span>
              </>
            )}
          </div>
        </div>
      </div>
    </footer>
  );
}
