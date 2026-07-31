import {
  useBlockProps, RichText, InspectorControls, MediaUpload, MediaUploadCheck,
} from "@wordpress/block-editor";
import {
  PanelBody, Button, TextControl, ToggleControl, RangeControl,
} from "@wordpress/components";

const NAV_NUMS = [1, 2, 3, 4];

export default function Edit({ attributes, setAttributes }) {
  const a = attributes;
  const blockProps = useBlockProps({ className: "rosegoldhero" });
  const bg = a.backgroundImage;

  return (
    <div {...blockProps}>
      <InspectorControls>
        <PanelBody title="Tło hero" initialOpen={true}>
          <MediaUploadCheck>
            <MediaUpload onSelect={(m) => setAttributes({ backgroundImage: m.url })} allowedTypes={["image"]} value={bg}
              render={({ open }) => (
                <Button variant="secondary" onClick={open}>{bg ? "Zmień tło" : "Wybierz tło"}</Button>
              )} />
          </MediaUploadCheck>
          {bg && <Button variant="link" isDestructive onClick={() => setAttributes({ backgroundImage: "" })}>Usuń tło (użyj domyślnego)</Button>}
          <RangeControl label="Przyciemnienie tła (%)" value={a.overlayOpacity} min={0} max={90} onChange={(v) => setAttributes({ overlayOpacity: v })} />
          <RangeControl label="Pozycja tła — poziom (lewo → prawo)" value={a.bgPosX} min={0} max={100} onChange={(v) => setAttributes({ bgPosX: v })} />
          <RangeControl label="Pozycja tła — pion (góra → dół)" value={a.bgPosY} min={0} max={100} onChange={(v) => setAttributes({ bgPosY: v })} />
        </PanelBody>

        <PanelBody title="Logo i nawigacja" initialOpen={false}>
          <MediaUploadCheck>
            <MediaUpload onSelect={(m) => setAttributes({ logoImage: m.url, logoAlt: m.alt || a.logoAlt })} allowedTypes={["image"]} value={a.logoImage}
              render={({ open }) => (
                <Button variant="secondary" onClick={open}>{a.logoImage ? "Zmień logo" : "Wybierz logo"}</Button>
              )} />
          </MediaUploadCheck>
          <TextControl label="Link logo" value={a.logoURL} onChange={(v) => setAttributes({ logoURL: v })} />
          {NAV_NUMS.map((n) => (
            <div key={n} style={{ borderTop: "1px solid #eee", paddingTop: 8, marginTop: 8 }}>
              <TextControl label={`Nawigacja ${n} — etykieta`} value={a[`nav${n}Label`]} onChange={(v) => setAttributes({ [`nav${n}Label`]: v })} />
              <TextControl label={`Nawigacja ${n} — URL`} value={a[`nav${n}URL`]} onChange={(v) => setAttributes({ [`nav${n}URL`]: v })} />
            </div>
          ))}
          <ToggleControl label="Pokaż koszyk" checked={a.showCart} onChange={(v) => setAttributes({ showCart: v })} />
          <TextControl label="URL koszyka" value={a.cartURL} onChange={(v) => setAttributes({ cartURL: v })} />
        </PanelBody>

        <PanelBody title="Licznik odliczający" initialOpen={false}>
          <ToggleControl label="Pokaż licznik" checked={a.showCountdown} onChange={(v) => setAttributes({ showCountdown: v })} />
          <TextControl type="datetime-local" label="Data zakończenia (puste = auto +3 dni)" value={a.countdownDate} onChange={(v) => setAttributes({ countdownDate: v })} />
          <TextControl label="Etykieta: dni" value={a.labelDays} onChange={(v) => setAttributes({ labelDays: v })} />
          <TextControl label="Etykieta: godziny" value={a.labelHours} onChange={(v) => setAttributes({ labelHours: v })} />
          <TextControl label="Etykieta: minuty" value={a.labelMinutes} onChange={(v) => setAttributes({ labelMinutes: v })} />
          <TextControl label="Etykieta: sekundy" value={a.labelSeconds} onChange={(v) => setAttributes({ labelSeconds: v })} />
        </PanelBody>
      </InspectorControls>

      <div className="rosegoldhero__bg" style={{ ...(bg ? { backgroundImage: `url(${bg})` } : {}), backgroundPosition: `${a.bgPosX}% ${a.bgPosY}%` }}>
        <span className="rosegoldhero__overlay" style={{ background: `rgba(0,0,0,${(a.overlayOpacity || 0) / 100})` }} aria-hidden="true"></span>

        <div className="rosegoldhero__header header">
          <div className="header__inner">
            <span className="header__logo">
              {a.logoImage ? <img src={a.logoImage} alt={a.logoAlt} /> : <span style={{ color: "#252525", fontWeight: 700 }}>SHAV</span>}
            </span>
            <nav className="header__nav">
              {NAV_NUMS.map((n) => a[`nav${n}Label`] ? <a key={n} href="#">{a[`nav${n}Label`]}</a> : null)}
            </nav>
            {a.showCart && <span className="header__cart" aria-hidden="true">🛒</span>}
          </div>
        </div>

        <div className="rosegoldhero__content">
          <div className="rosegoldhero__inner">
            <RichText tagName="span" className="rosegoldhero__badge" value={a.badge} onChange={(v) => setAttributes({ badge: v })} placeholder="Badge" />
            <RichText tagName="h1" className="rosegoldhero__title" value={a.title} onChange={(v) => setAttributes({ title: v })} placeholder="Tytuł dropu" />
            <p className="rosegoldhero__subtitle">
              <RichText tagName="span" value={a.subtitle1} onChange={(v) => setAttributes({ subtitle1: v })} placeholder="Podtytuł" />
              <RichText tagName="span" value={a.subtitleEmoji} onChange={(v) => setAttributes({ subtitleEmoji: v })} placeholder="🎂" />
              <RichText tagName="span" value={a.subtitle2} onChange={(v) => setAttributes({ subtitle2: v })} placeholder="Pozostało jeszcze:" />
            </p>
            {a.showCountdown && <>
              <span className="rosegoldhero__divider" aria-hidden="true"></span>
              <div className="rosegoldhero__countdown">
                {[["--", a.labelDays], ["--", a.labelHours], ["--", a.labelMinutes], ["--", a.labelSeconds]].map(([num, lab], i) => (
                  <div className="rosegoldhero__tile" key={i}>
                    <span className="rosegoldhero__tile-num">{num}</span>
                    <span className="rosegoldhero__tile-label">{lab}</span>
                  </div>
                ))}
              </div>
            </>}
          </div>
        </div>
      </div>
    </div>
  );
}
