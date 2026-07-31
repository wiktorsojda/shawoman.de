import {
  useBlockProps, RichText, InspectorControls, MediaUpload, MediaUploadCheck,
} from "@wordpress/block-editor";
import { PanelBody, Button, TextareaControl } from "@wordpress/components";

export default function Edit({ attributes, setAttributes }) {
  const a = attributes;
  const blockProps = useBlockProps({ className: "blacktext-container-wysylka container" });
  return (
    <div {...blockProps}>
      <InspectorControls>
        {[1, 2].map((i) => (
          <PanelBody key={i} title={`Opcja ${i} — ikona`} initialOpen={false}>
            <p style={{ fontSize: 12, opacity: 0.7, marginBottom: 8 }}>
              Wybierz obraz/SVG z biblioteki <strong>lub</strong> wklej kod SVG (inline).
              Jeśli wpiszesz inline, ma priorytet nad URL.
            </p>
            <MediaUploadCheck>
              <MediaUpload
                onSelect={(media) => setAttributes({ [`option${i}Icon`]: media.url })}
                allowedTypes={["image"]}
                value={a[`option${i}Icon`]}
                render={({ open }) => (
                  <Button variant="secondary" onClick={open}>
                    {a[`option${i}Icon`] ? "Zmień ikonę (URL)" : "Wybierz ikonę z biblioteki"}
                  </Button>
                )}
              />
            </MediaUploadCheck>
            {a[`option${i}Icon`] && (
              <Button variant="link" isDestructive onClick={() => setAttributes({ [`option${i}Icon`]: "" })} style={{ marginTop: 8, marginBottom: 8 }}>
                Usuń URL
              </Button>
            )}
            <TextareaControl
              label="Inline SVG (kod)"
              help="Wklej cały tag <svg>...</svg>"
              value={a[`option${i}IconSvg`] || ""}
              onChange={(v) => setAttributes({ [`option${i}IconSvg`]: v })}
              rows={6}
            />
          </PanelBody>
        ))}
      </InspectorControls>
      <div className="metody-wysylki-textcontainer container--narrow2-important">
        <RichText tagName="h2" className="metody-wysylki-header" value={a.header} onChange={(v) => setAttributes({ header: v })} placeholder="Nagłówek" />
        <RichText tagName="p" className="metody-wysylki-p" value={a.description} onChange={(v) => setAttributes({ description: v })} placeholder="Opis" />
        <RichText tagName="h2" className="metody-wysylki-h2" value={a.subheader} onChange={(v) => setAttributes({ subheader: v })} placeholder="Podnagłówek" />
        <ul className="metody-wysylki-ul">
          {[1, 2].map((i) => {
            const svgInline = a[`option${i}IconSvg`];
            const iconUrl = a[`option${i}Icon`];
            return (
              <div key={i} className="metody-wysylki-list">
                {svgInline
                  ? <span className="metody-wysylki-list-icon" dangerouslySetInnerHTML={{ __html: svgInline }} />
                  : iconUrl ? <img className="metody-wysylki-list-icon" src={iconUrl} alt="" style={{ maxHeight: 50 }} /> : null}
                <RichText tagName="li" value={a[`option${i}Title`]} onChange={(v) => setAttributes({ [`option${i}Title`]: v })} placeholder={`Opcja ${i}`} />
                <RichText tagName="span" value={a[`option${i}Desc`]} onChange={(v) => setAttributes({ [`option${i}Desc`]: v })} placeholder={`Opis ${i}`} />
              </div>
            );
          })}
        </ul>
      </div>
    </div>
  );
}
