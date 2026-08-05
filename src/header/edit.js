import {
  useBlockProps, RichText, InspectorControls, MediaUpload, MediaUploadCheck,
} from "@wordpress/block-editor";
import { PanelBody, Button, TextControl, ToggleControl, SelectControl } from "@wordpress/components";
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

        <PanelBody title="Koszyk i Hamburger" initialOpen={false}>
          <ToggleControl label="Pokaż ikonę koszyka" checked={a.showCart} onChange={(v) => setAttributes({ showCart: v })} />
          <TextControl label="URL koszyka" value={a.cartURL} onChange={(v) => setAttributes({ cartURL: v })} />
          <TextControl label="Aria-label dla Hamburgera" value={a.hamburgerLabel} onChange={(v) => setAttributes({ hamburgerLabel: v })} />
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

        {a.showCart && (
          <a className="header__cart" href={a.cartURL} aria-label="Koszyk">
            <span style={{ display: "inline-block", width: 24, height: 24, border: "1px solid #999", borderRadius: 4, padding: 4, color: "#999", fontSize: 11, textAlign: "center" }}>🛒</span>
          </a>
        )}
      </div>
    </header>
  );
}
