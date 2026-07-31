import {
  useBlockProps, RichText, InspectorControls, MediaUpload, MediaUploadCheck,
} from "@wordpress/block-editor";
import { PanelBody, Button, TextControl } from "@wordpress/components";

export default function Edit({ attributes, setAttributes }) {
  const a = attributes;
  const blockProps = useBlockProps({ className: "container container-onas-images" });
  return (
    <div {...blockProps}>
      <InspectorControls>
        {[1, 2, 3, 4].map((i) => (
          <PanelBody key={i} title={`Slajd ${i}`} initialOpen={false}>
            <MediaUploadCheck>
              <MediaUpload onSelect={(media) => setAttributes({ [`item${i}Image`]: media.url, [`item${i}Alt`]: media.alt || a[`item${i}Alt`] })} allowedTypes={["image"]} value={a[`item${i}Image`]}
                render={({ open }) => (<Button variant="secondary" onClick={open}>{a[`item${i}Image`] ? "Zmień zdjęcie" : "Wybierz zdjęcie"}</Button>)} />
            </MediaUploadCheck>
            <TextControl label="Alt zdjęcia" value={a[`item${i}Alt`]} onChange={(v) => setAttributes({ [`item${i}Alt`]: v })} />
          </PanelBody>
        ))}
      </InspectorControls>
      <section className="banner-onas">
        <div className="banner-slider-onas">
          {[1, 2, 3, 4].map((i, idx) => (
            <div key={i} className={`slider-item-onas ${idx === 0 ? "active-onas" : ""}`}>
              {a[`item${i}Image`] && <img src={a[`item${i}Image`]} alt={a[`item${i}Alt`]} className="img-cover-onas bg-image-onas" />}
              <div className="banner-content-onas">
                <RichText tagName="h2" className="heading-onas" value={a[`item${i}Title`]} onChange={(v) => setAttributes({ [`item${i}Title`]: v })} placeholder={`Tytuł slajdu ${i}`} />
                <RichText tagName="p" className="banner-text-onas" value={a[`item${i}Text`]} onChange={(v) => setAttributes({ [`item${i}Text`]: v })} placeholder={`Tekst slajdu ${i}`} />
              </div>
            </div>
          ))}
        </div>
      </section>
    </div>
  );
}
