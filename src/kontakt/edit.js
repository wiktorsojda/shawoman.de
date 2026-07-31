import { useBlockProps, RichText, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, TextControl } from "@wordpress/components";

export default function Edit({ attributes, setAttributes }) {
  const a = attributes;
  const blockProps = useBlockProps({ className: "faq-container faq-container-kontakt" });
  return (
    <div {...blockProps}>
      <InspectorControls>
        <PanelBody title="Linki kontaktowe" initialOpen={false}>
          <TextControl label="Telefon (link tel:)" value={a.phoneHref} onChange={(v) => setAttributes({ phoneHref: v })} />
          <TextControl label="Email (link mailto:)" value={a.emailHref} onChange={(v) => setAttributes({ emailHref: v })} />
        </PanelBody>
        <PanelBody title="Sekcja: Sprzedaż hurtowa" initialOpen={false}>
          <TextControl label="URL przycisku" value={a.hurtButtonURL} onChange={(v) => setAttributes({ hurtButtonURL: v })} />
        </PanelBody>
        <PanelBody title="Sekcja: Zwroty" initialOpen={false}>
          <TextControl label="URL przycisku" value={a.zwrotyButtonURL} onChange={(v) => setAttributes({ zwrotyButtonURL: v })} />
        </PanelBody>
        <PanelBody title="Sekcja: Reklamacje" initialOpen={false}>
          <TextControl label="URL przycisku" value={a.reklamacjeButtonURL} onChange={(v) => setAttributes({ reklamacjeButtonURL: v })} />
        </PanelBody>
      </InspectorControls>
      <div className="container--narrow2-important">
        <RichText tagName="div" className="regulamin-title" value={a.title} onChange={(v) => setAttributes({ title: v })} placeholder="Tytuł" />
        <RichText tagName="div" className="regulamin-subtitle" value={a.subtitle} onChange={(v) => setAttributes({ subtitle: v })} placeholder="Podtytuł" />

        <div className="grid-container-kontakt">
          {/* Sekcja 1: Obsługa */}
          <div className="item1-kontakt">
            <RichText tagName="div" className="item-kontakt-title" value={a.obsługaTitle} onChange={(v) => setAttributes({ obsługaTitle: v })} placeholder="Tytuł sekcji" />
            <RichText tagName="span" value={a.obsługaIntro} onChange={(v) => setAttributes({ obsługaIntro: v })} placeholder="Wprowadzenie" />
            <RichText tagName="span" value={a.phone} onChange={(v) => setAttributes({ phone: v })} placeholder="Numer telefonu" />
            <RichText tagName="span" value={a.email} onChange={(v) => setAttributes({ email: v })} placeholder="Email" />
            <RichText tagName="span" value={a.hours} onChange={(v) => setAttributes({ hours: v })} placeholder="Godziny" />
          </div>

          {/* Sekcja 2: FAQ */}
          <div className="item2-kontakt">
            <div className="faq-wrapper-kontakt kontakt-wrapper">
              <RichText tagName="div" className="item-kontakt-title" value={a.faqTitle} onChange={(v) => setAttributes({ faqTitle: v })} placeholder="Tytuł FAQ" />
              <div className="faq-wrapper-questions-kontakt">
                {[1,2,3,4,5,6,7,8,9,10].map((i) => {
                  const q = a[`kontaktQuestion${i}`];
                  const ans = a[`kontaktAnswer${i}`];
                  if (!q && !ans) return null;
                  return (
                    <div key={i} className="faq-kontakt">
                      <button className="faq-accordion-kontakt" type="button">
                        <RichText tagName="span" value={q} onChange={(v) => setAttributes({ [`kontaktQuestion${i}`]: v })} placeholder={`Pytanie ${i}`} />
                      </button>
                      <div className="faq-pannel-kontakt" style={{ maxHeight: "none", opacity: 1, paddingBottom: 12 }}>
                        <RichText tagName="p" value={ans} onChange={(v) => setAttributes({ [`kontaktAnswer${i}`]: v })} placeholder={`Odpowiedź ${i}`} />
                      </div>
                    </div>
                  );
                })}
              </div>
            </div>
          </div>

          {/* Sekcja 3: Hurtowa */}
          <div className="item3-kontakt background-white">
            <RichText tagName="div" className="item-kontakt-title" value={a.hurtTitle} onChange={(v) => setAttributes({ hurtTitle: v })} placeholder="Tytuł" />
            <RichText tagName="span" value={a.hurtIntro} onChange={(v) => setAttributes({ hurtIntro: v })} placeholder="Wprowadzenie" />
            <RichText tagName="button" className="background-white" value={a.hurtButtonLabel} onChange={(v) => setAttributes({ hurtButtonLabel: v })} placeholder="Etykieta" />
          </div>

          {/* Sekcja 4: Zwroty */}
          <div className="item4-kontakt background-white">
            <RichText tagName="div" className="item-kontakt-title" value={a.zwrotyTitle} onChange={(v) => setAttributes({ zwrotyTitle: v })} placeholder="Tytuł" />
            <RichText tagName="span" value={a.zwrotyIntro} onChange={(v) => setAttributes({ zwrotyIntro: v })} placeholder="Wprowadzenie" />
            <RichText tagName="button" className="background-white" value={a.zwrotyButtonLabel} onChange={(v) => setAttributes({ zwrotyButtonLabel: v })} placeholder="Etykieta" />
          </div>

          {/* Sekcja 5: Reklamacje */}
          <div className="item5-kontakt background-white">
            <RichText tagName="div" className="item-kontakt-title" value={a.reklamacjeTitle} onChange={(v) => setAttributes({ reklamacjeTitle: v })} placeholder="Tytuł" />
            <RichText tagName="span" value={a.reklamacjeIntro} onChange={(v) => setAttributes({ reklamacjeIntro: v })} placeholder="Wprowadzenie" />
            <RichText tagName="button" className="background-white" value={a.reklamacjeButtonLabel} onChange={(v) => setAttributes({ reklamacjeButtonLabel: v })} placeholder="Etykieta" />
          </div>
        </div>
      </div>
    </div>
  );
}
