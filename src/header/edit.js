import {
  useBlockProps, RichText, InspectorControls, MediaUpload, MediaUploadCheck,
} from "@wordpress/block-editor";
import { PanelBody, Button, TextControl, ToggleControl, SelectControl, TextareaControl } from "@wordpress/components";
import { useSelect } from "@wordpress/data";

const NAV_NUMS = [1, 2, 3, 4];

export default function Edit({ attributes, setAttributes }) {
  const a = attributes;
  const blockProps = useBlockProps({ className: "header" });

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

  const getMenuName = (menuId) => {
    if (!menus || !menuId) return "Brak wybranego menu";
    const menu = menus.find((m) => m.id === parseInt(menuId));
    return menu ? menu.name : "Wybierz menu w panelu bocznym";
  };

  const aiJson = JSON.stringify({
    logoAlt: a.logoAlt,
    hamburgerLabel: a.hamburgerLabel,
    whatsappQrText: a.whatsappQrText,
    whatsappButtonText: a.whatsappButtonText
  }, null, 2);

  const handleAiJsonChange = (val) => {
    try {
      const parsed = JSON.parse(val);
      setAttributes(parsed);
    } catch (e) {
      // ignore invalid json during typing
    }
  };

  return (
    <header {...blockProps}>
      <InspectorControls>
        <PanelBody title="Logo" initialOpen={true}>
          <MediaUploadCheck>
            <MediaUpload onSelect={(media) => setAttributes({ logoImage: media.url, logoAlt: media.alt || a.logoAlt })} allowedTypes={["image"]} value={a.logoImage}
              render={({ open }) => (<Button variant="secondary" onClick={open}>{a.logoImage ? "Zmień logo" : "Wybierz logo"}</Button>)} />
          </MediaUploadCheck>
          <TextControl label="Alt logo" value={a.logoAlt} onChange={(v) => setAttributes({ logoAlt: v })} />
          <TextControl label="Link logo (URL)" value={a.logoURL} onChange={(v) => setAttributes({ logoURL: v })} />
        </PanelBody>

        <PanelBody title="Nawigacja (Menu)" initialOpen={true}>
          <SelectControl
            label="Wybierz Menu"
            value={a.menuId}
            options={menuOptions}
            onChange={(v) => setAttributes({ menuId: parseInt(v) })}
          />
          <p style={{ fontSize: 12, color: "#666", marginTop: 12 }}>
            Same linki dla wybranego menu edytujesz w klasycznej zakładce <strong>Wygląd → Menu</strong>.
          </p>
        </PanelBody>

        <PanelBody title="Koszyk, WhatsApp i Hamburger" initialOpen={false}>
          <ToggleControl label="Pokaż WhatsApp" checked={a.showWhatsapp} onChange={(v) => setAttributes({ showWhatsapp: v })} />
          {a.showWhatsapp && (
            <div style={{ marginBottom: 16 }}>
              <TextControl label="URL WhatsApp" value={a.whatsappUrl} onChange={(v) => setAttributes({ whatsappUrl: v })} />
              <TextControl label="Tekst nad QR kodem" value={a.whatsappQrText} onChange={(v) => setAttributes({ whatsappQrText: v })} />
              <TextControl label="Tekst przycisku pod QR" value={a.whatsappButtonText} onChange={(v) => setAttributes({ whatsappButtonText: v })} />
              <MediaUploadCheck>
                <MediaUpload onSelect={(media) => setAttributes({ whatsappQrImage: media.url })} allowedTypes={["image"]}
                  render={({ open }) => (<Button variant="secondary" onClick={open} style={{ marginTop: 8 }}>{a.whatsappQrImage ? "Zmień QR code" : "Wybierz QR code"}</Button>)} />
              </MediaUploadCheck>
              {(a.whatsappQrText || a.whatsappQrImage || a.whatsappButtonText) && (
                <div style={{ marginTop: '10px', padding: '10px', border: '1px solid #ccc', borderRadius: '8px', width: 'fit-content' }}>
                  {a.whatsappQrText && <div style={{ fontSize: '13px', fontWeight: '600', marginBottom: '8px', textAlign: 'center', color: '#1a1a1a' }}>{a.whatsappQrText}</div>}
                  {a.whatsappQrImage && <img src={a.whatsappQrImage} alt="QR" style={{ width: '100px', display: 'block', margin: '0 auto', borderRadius: 4 }} />}
                  {a.whatsappButtonText && <div style={{ marginTop: '8px', padding: '6px 12px', background: '#F4E1D9', color: '#1a1a1a', borderRadius: '8px', fontSize: '12px', fontWeight: '600', textAlign: 'center' }}>{a.whatsappButtonText}</div>}
                </div>
              )}
            </div>
          )}

          <ToggleControl label="Pokaż ikonę koszyka" checked={a.showCart} onChange={(v) => setAttributes({ showCart: v })} />
          <TextControl label="URL koszyka" value={a.cartURL} onChange={(v) => setAttributes({ cartURL: v })} />
          <TextControl label="Aria-label dla Hamburgera" value={a.hamburgerLabel} onChange={(v) => setAttributes({ hamburgerLabel: v })} />
        </PanelBody>

        <PanelBody title="Tłumaczenia AI (JSON)" initialOpen={false}>
          <TextareaControl
            label="JSON z atrybutami (skopiuj i przetłumacz)"
            value={aiJson}
            onChange={handleAiJsonChange}
            rows={6}
          />
        </PanelBody>
      </InspectorControls>

      <div className="header__inner">
        <a className="header__logo" href={a.logoURL}>
          {a.logoImage || customLogoUrl
            ? <img src={a.logoImage || customLogoUrl} alt={a.logoAlt} />
            : <span style={{ padding: 8, background: "#f0f0f0", color: "#999", fontSize: 12 }}>Logo (wybierz w Dostosuj lub tutaj)</span>}
        </a>

        <nav className="header__nav" aria-label="Główna nawigacja">
          <span style={{ opacity: 0.5, fontStyle: "italic", alignSelf: "center", display: "inline-block" }}>
            [Zarządzaj w Wygląd → Menu: {getMenuName(a.menuId)}]
          </span>
        </nav>

        {a.showWhatsapp && (
          <div className="header__whatsapp" style={{ marginLeft: "auto" }}>
            <span style={{ display: "inline-flex", alignItems: "center", justifyContent: "center", width: 32, height: 32, color: "#252525" }}>
              <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M25.3991 6.54413C24.1765 5.30961 22.7205 4.33076 21.1158 3.66462C19.5111 2.99848 17.7899 2.65838 16.0524 2.66413C8.77242 2.66413 2.83909 8.59747 2.83909 15.8775C2.83909 18.2108 3.45242 20.4775 4.59909 22.4775L2.73242 29.3308L9.73242 27.4908C11.6658 28.5441 13.8391 29.1041 16.0524 29.1041C23.3324 29.1041 29.2658 23.1708 29.2658 15.8908C29.2658 12.3575 27.8924 9.03747 25.3991 6.54413ZM16.0524 26.8641C14.0791 26.8641 12.1458 26.3308 10.4524 25.3308L10.0524 25.0908L5.89242 26.1841L6.99909 22.1308L6.73242 21.7175C5.63582 19.9669 5.05365 17.9432 5.05242 15.8775C5.05242 9.82414 9.98575 4.8908 16.0391 4.8908C18.9724 4.8908 21.7324 6.03747 23.7991 8.11747C24.8226 9.13595 25.6336 10.3475 26.1853 11.6819C26.7369 13.0163 27.018 14.4469 27.0124 15.8908C27.0391 21.9441 22.1058 26.8641 16.0524 26.8641ZM22.0791 18.6508C21.7458 18.4908 20.1191 17.6908 19.8258 17.5708C19.5191 17.4641 19.3058 17.4108 19.0791 17.7308C18.8524 18.0641 18.2258 18.8108 18.0391 19.0241C17.8524 19.2508 17.6524 19.2775 17.3191 19.1041C16.9858 18.9441 15.9191 18.5841 14.6658 17.4641C13.6791 16.5841 13.0258 15.5041 12.8258 15.1708C12.6391 14.8375 12.7991 14.6641 12.9724 14.4908C13.1191 14.3441 13.3058 14.1041 13.4658 13.9175C13.6258 13.7308 13.6924 13.5841 13.7991 13.3708C13.9058 13.1441 13.8524 12.9575 13.7724 12.7975C13.6924 12.6375 13.0258 11.0108 12.7591 10.3441C12.4924 9.70413 12.2124 9.78413 12.0124 9.7708H11.3724C11.1458 9.7708 10.7991 9.8508 10.4924 10.1841C10.1991 10.5175 9.34575 11.3175 9.34575 12.9441C9.34575 14.5708 10.5324 16.1441 10.6924 16.3575C10.8524 16.5841 13.0258 19.9175 16.3324 21.3441C17.1191 21.6908 17.7324 21.8908 18.2124 22.0375C18.9991 22.2908 19.7191 22.2508 20.2924 22.1708C20.9324 22.0775 22.2524 21.3708 22.5191 20.5975C22.7991 19.8241 22.7991 19.1708 22.7058 19.0241C22.6124 18.8775 22.4124 18.8108 22.0791 18.6508Z" fill="currentColor" />
              </svg>
            </span>
          </div>
        )}

        {a.showCart && (
          <a className="header__cart" href={a.cartURL} aria-label="Koszyk" style={{ marginLeft: a.showWhatsapp ? 12 : "auto" }}>
            <span style={{ display: "inline-block", width: 24, height: 24, border: "1px solid #999", borderRadius: 4, padding: 4, color: "#999", fontSize: 11, textAlign: "center" }}>🛒</span>
          </a>
        )}
      </div>
    </header>
  );
}
