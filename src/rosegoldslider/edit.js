import {
  useBlockProps, InspectorControls, MediaUpload, MediaUploadCheck,
} from "@wordpress/block-editor";
import {
  PanelBody, Button, TextControl, ToggleControl, RangeControl,
} from "@wordpress/components";

const SLIDES = [1, 2, 3, 4, 5];

export default function Edit({ attributes, setAttributes }) {
  const a = attributes;
  const blockProps = useBlockProps({ className: "rosegoldslider" });
  const filled = SLIDES.filter((n) => a[`slide${n}Image`]);
  const preview = filled.length ? filled : [1, 2, 3];

  return (
    <div {...blockProps}>
      <InspectorControls>
        <PanelBody title="Ustawienia karuzeli" initialOpen={true}>
          <ToggleControl label="Auto-przewijanie" checked={a.autoScroll} onChange={(v) => setAttributes({ autoScroll: v })} />
          <RangeControl label="Czas przewijania (ms)" min={2000} max={12000} step={500} value={a.autoScrollMs} onChange={(v) => setAttributes({ autoScrollMs: v })} />
        </PanelBody>
        {SLIDES.map((n) => (
          <PanelBody key={n} title={`Slajd ${n}`} initialOpen={false}>
            <MediaUploadCheck>
              <MediaUpload onSelect={(m) => setAttributes({ [`slide${n}Image`]: m.url, [`slide${n}Alt`]: m.alt || a[`slide${n}Alt`] })} allowedTypes={["image"]} value={a[`slide${n}Image`]}
                render={({ open }) => (
                  <Button variant="secondary" onClick={open}>{a[`slide${n}Image`] ? "Zmień obraz" : "Wybierz obraz"}</Button>
                )} />
            </MediaUploadCheck>
            {a[`slide${n}Image`] && <Button variant="link" isDestructive onClick={() => setAttributes({ [`slide${n}Image`]: "" })}>Usuń slajd</Button>}
            <div style={{ borderTop: "1px solid #eee", paddingTop: 8, marginTop: 8 }}>
              <p style={{ margin: "0 0 4px", fontSize: 11, fontWeight: 600 }}>Obraz mobilny (opcjonalnie)</p>
              <MediaUploadCheck>
                <MediaUpload onSelect={(m) => setAttributes({ [`slide${n}ImageMobile`]: m.url })} allowedTypes={["image"]} value={a[`slide${n}ImageMobile`]}
                  render={({ open }) => (
                    <Button variant="secondary" onClick={open}>{a[`slide${n}ImageMobile`] ? "Zmień mobilny" : "Wybierz mobilny"}</Button>
                  )} />
              </MediaUploadCheck>
              {a[`slide${n}ImageMobile`] && <Button variant="link" isDestructive onClick={() => setAttributes({ [`slide${n}ImageMobile`]: "" })}>Usuń mobilny</Button>}
            </div>
            <TextControl label="Link (opcjonalnie)" value={a[`slide${n}Url`]} onChange={(v) => setAttributes({ [`slide${n}Url`]: v })} />
            <TextControl label="Alt" value={a[`slide${n}Alt`]} onChange={(v) => setAttributes({ [`slide${n}Alt`]: v })} />
          </PanelBody>
        ))}
      </InspectorControls>

      <div className="rosegoldslider__viewport">
        <div className="rosegoldslider__track">
          {preview.map((n, i) => (
            <div className={`rosegoldslider__slide${i === 0 ? " is-active" : ""}`} key={n}>
              {a[`slide${n}Image`]
                ? <img src={a[`slide${n}Image`]} alt={a[`slide${n}Alt`] || ""} />
                : <div className="rosegoldslider__placeholder">Slajd {n} — wybierz obraz w panelu</div>}
            </div>
          ))}
        </div>
      </div>
      <div className="rosegoldslider__dots">
        {preview.map((n, i) => <span key={n} className={`rosegoldslider__dot${i === 0 ? " is-active" : ""}`}></span>)}
      </div>
    </div>
  );
}
