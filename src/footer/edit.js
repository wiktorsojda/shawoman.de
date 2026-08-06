import {
  useBlockProps, RichText, InspectorControls, MediaUpload, MediaUploadCheck,
} from "@wordpress/block-editor";
import { PanelBody, Button, TextControl, ToggleControl, SelectControl, RangeControl } from "@wordpress/components";
import { useSelect } from "@wordpress/data";

const SOCIAL_NUMS = [1, 2, 3, 4];

export default function Edit({ attributes, setAttributes }) {
  const a = attributes;
  const blockProps = useBlockProps({ className: "footer" });

  const menus = useSelect((select) => {
    return select("core").getEntityRecords("taxonomy", "nav_menu", { per_page: -1 });
  }, []);

  const siteInfo = useSelect((select) => select("core").getEntityRecord("root", "site"), []);
  const customLogoId = siteInfo ? siteInfo.site_logo : null;
  const customLogoMedia = useSelect((select) => {
    return customLogoId ? select("core").getMedia(customLogoId) : null;
  }, [customLogoId]);
  const customLogoUrl = customLogoMedia ? customLogoMedia.source_url : null;

  const menuOptions = [{ label: "Wybierz menu...", value: 0 }];
  if (menus) {
    menus.forEach((menu) => {
      menuOptions.push({ label: menu.name, value: menu.id });
    });
  }

  // Helper to get menu name for display in editor
  const getMenuName = (menuId) => {
    if (!menus || !menuId) return "Brak wybranego menu";
    const menu = menus.find((m) => m.id === parseInt(menuId));
    return menu ? menu.name : "Wybierz menu w panelu bocznym";
  };

  const colsArray = Array.from({ length: a.columnsCount }, (_, i) => i + 1);

  return (
    <footer {...blockProps}>
      <InspectorControls>
        <PanelBody title="Karta CTA — link przycisku" initialOpen={true}>
          <TextControl label="URL przycisku" value={a.ctaButtonURL} onChange={(v) => setAttributes({ ctaButtonURL: v })} />
        </PanelBody>

        <PanelBody title="Kolumny nawigacji (Menu)" initialOpen={false}>
          <RangeControl
            label="Liczba kolumn"
            value={a.columnsCount}
            onChange={(v) => setAttributes({ columnsCount: v })}
            min={1}
            max={4}
          />
          {colsArray.map((n) => (
            <div key={n} style={{ marginTop: 16, padding: 8, border: "1px solid #ddd" }}>
              <p style={{ margin: "0 0 8px 0", fontWeight: "bold" }}>Ustawienia Kolumny {n}</p>
              <SelectControl
                label={`Wybierz Menu (Kolumna ${n})`}
                value={a[`col${n}MenuId`]}
                options={menuOptions}
                onChange={(v) => setAttributes({ [`col${n}MenuId`]: parseInt(v) })}
              />
            </div>
          ))}
          <p style={{ fontSize: 12, color: "#666", marginTop: 12 }}>
            Uwaga: Same linki dla wybranego menu edytujesz w klasycznej zakładce <strong>Wygląd → Menu</strong>.
          </p>
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
          <SelectControl
            label="Wybierz Menu (Zamiast sztywnych linków)"
            value={a.bottomMenuId}
            options={menuOptions}
            onChange={(v) => setAttributes({ bottomMenuId: parseInt(v) })}
            help="Jeśli wybierzesz menu, pokaże się ono zamiast sztywnych linków (max 3 elementy)."
          />
          {!a.bottomMenuId && (
            <>
              <TextControl label="Polityka URL" value={a.policyURL} onChange={(v) => setAttributes({ policyURL: v })} />
              <TextControl label="Regulamin URL" value={a.termsURL} onChange={(v) => setAttributes({ termsURL: v })} />
            </>
          )}
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
          <div className="footer__links" style={{ display: "grid", gridTemplateColumns: "1fr 1fr", gap: 32, flex: 1 }}>
            {colsArray.map((n) => (
              <div key={n} className="footer__col">
                <RichText tagName="p" className="footer__col-title" value={a[`col${n}Title`]} onChange={(v) => setAttributes({ [`col${n}Title`]: v })} placeholder={`Tytuł Kolumny ${n}`} />
                <ul className="footer__col-list">
                  <li style={{ opacity: 0.5, fontStyle: "italic" }}>
                    <span>[Zarządzaj w Wygląd → Menu: {getMenuName(a[`col${n}MenuId`])}]</span>
                  </li>
                </ul>
              </div>
            ))}

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
            {a.bottomLogo || customLogoUrl
              ? <img src={a.bottomLogo || customLogoUrl} alt="" />
              : <span style={{ fontSize: 11, color: "#999" }}>logo</span>}
          </div>
          <div className="footer__legal">
            {a.bottomMenuId ? (
              <span style={{ fontStyle: "italic", opacity: 0.5 }}>
                [Menu: {getMenuName(a.bottomMenuId)}]
              </span>
            ) : (
              <>
                <RichText tagName="span" value={a.policyLabel} onChange={(v) => setAttributes({ policyLabel: v })} placeholder="Polityka prywatności" />
                <span className="footer__legal-sep" aria-hidden="true"></span>
                <RichText tagName="span" value={a.termsLabel} onChange={(v) => setAttributes({ termsLabel: v })} placeholder="Regulamin" />
              </>
            )}
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
