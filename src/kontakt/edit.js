import { useBlockProps, RichText, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, TextControl, TextareaControl, Button } from "@wordpress/components";
import { useState, useEffect } from "@wordpress/element";

export default function Edit({ attributes, setAttributes }) {
  const a = attributes;
  const [importJson, setImportJson] = useState("");
  const blockProps = useBlockProps({ className: "faq-container faq-container-kontakt" });

  useEffect(() => {
    if (!a.faqItems || a.faqItems.length === 0) {
      let migrated = [];
      for (let i = 1; i <= 10; i++) {
        if (a[`kontaktQuestion${i}`] || a[`kontaktAnswer${i}`]) {
          migrated.push({
            question: a[`kontaktQuestion${i}`] || "",
            answer: a[`kontaktAnswer${i}`] || ""
          });
        }
      }
      if (migrated.length > 0) {
        setAttributes({ faqItems: migrated });
      } else {
        setAttributes({ faqItems: [
          {
            question: "Ile trwa realizacja zamówienia?",
            answer: "Realizacja i dostawa wszystkich zamówień ze strony Shav odbywa się w ciągu 1-2 dni roboczych."
          }
        ]});
      }
    }
  }, []);

  const faqItems = a.faqItems || [];

  const updateFaqItem = (index, field, value) => {
    const newItems = [...faqItems];
    newItems[index] = { ...newItems[index], [field]: value };
    setAttributes({ faqItems: newItems });
  };

  const addFaqItem = () => {
    setAttributes({ faqItems: [...faqItems, { question: "", answer: "" }] });
  };

  const removeFaqItem = (index) => {
    const newItems = [...faqItems];
    newItems.splice(index, 1);
    setAttributes({ faqItems: newItems });
  };

  return (
    <div {...blockProps}>
      <InspectorControls>
        <PanelBody title="Zarządzaj FAQ" initialOpen={true}>
          {faqItems.map((item, i) => (
            <div key={i} style={{ border: '1px solid #ddd', padding: '12px', marginBottom: '12px', borderRadius: '4px', backgroundColor: '#f9f9f9' }}>
              <TextControl
                label={`Pytanie ${i + 1}`}
                value={item.question}
                onChange={(v) => updateFaqItem(i, "question", v)}
              />
              <TextareaControl
                label={`Odpowiedź ${i + 1}`}
                value={item.answer}
                onChange={(v) => updateFaqItem(i, "answer", v)}
                rows={3}
              />
              <Button isDestructive variant="link" onClick={() => removeFaqItem(i)} style={{ padding: 0 }}>
                Usuń to pytanie
              </Button>
            </div>
          ))}
          <Button variant="secondary" onClick={addFaqItem} style={{ width: "100%", justifyContent: "center" }}>
            + Dodaj nowe pytanie FAQ
          </Button>
        </PanelBody>

        <PanelBody title="Tłumaczenia AI (JSON)" initialOpen={false}>
          <TextareaControl
            label="Skopiuj ten JSON dla AI"
            value={(() => {
              const data = {
                title: a.title || "",
                subtitle: a.subtitle || "",
                obsługaTitle: a.obsługaTitle || "",
                obsługaIntro: a.obsługaIntro || "",
                phone: a.phone || "",
                email: a.email || "",
                hours: a.hours || "",
                faqTitle: a.faqTitle || "",
                hurtTitle: a.hurtTitle || "",
                hurtIntro: a.hurtIntro || "",
                hurtButtonLabel: a.hurtButtonLabel || "",
                zwrotyTitle: a.zwrotyTitle || "",
                zwrotyIntro: a.zwrotyIntro || "",
                zwrotyButtonLabel: a.zwrotyButtonLabel || "",
                reklamacjeTitle: a.reklamacjeTitle || "",
                reklamacjeIntro: a.reklamacjeIntro || "",
                reklamacjeButtonLabel: a.reklamacjeButtonLabel || "",
                faqItems: faqItems
              };
              return JSON.stringify(data, null, 2);
            })()}
            readOnly
            rows={10}
            help="Skopiuj i wklej do AI z prośbą o przetłumaczenie samych wartości."
          />
          <TextareaControl
            label="Wklej przetłumaczony JSON"
            value={importJson}
            onChange={setImportJson}
            rows={10}
          />
          <Button variant="primary" onClick={() => {
            try {
              const parsed = JSON.parse(importJson);
              const updates = {};
              const keys = ["title", "subtitle", "obsługaTitle", "obsługaIntro", "phone", "email", "hours", "faqTitle", "hurtTitle", "hurtIntro", "hurtButtonLabel", "zwrotyTitle", "zwrotyIntro", "zwrotyButtonLabel", "reklamacjeTitle", "reklamacjeIntro", "reklamacjeButtonLabel"];
              keys.forEach(k => {
                if (parsed[k] !== undefined) updates[k] = parsed[k];
              });
              if (parsed.faqItems && Array.isArray(parsed.faqItems)) {
                updates.faqItems = parsed.faqItems;
              }
              setAttributes(updates);
              alert("Zaktualizowano pomyślnie!");
              setImportJson("");
            } catch (e) {
              alert("Błąd! Niepoprawny format JSON.");
            }
          }} style={{ width: "100%", justifyContent: "center" }}>
            Importuj tłumaczenie
          </Button>
        </PanelBody>
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
                {faqItems.map((item, i) => (
                    <div key={i} className="faq-kontakt">
                      <button className="faq-accordion-kontakt" type="button">
                        <RichText tagName="span" value={item.question} onChange={(v) => updateFaqItem(i, "question", v)} placeholder={`Pytanie ${i + 1}`} />
                      </button>
                      <div className="faq-pannel-kontakt" style={{ maxHeight: "none", opacity: 1, paddingBottom: 12 }}>
                        <RichText tagName="p" value={item.answer} onChange={(v) => updateFaqItem(i, "answer", v)} placeholder={`Odpowiedź ${i + 1}`} />
                      </div>
                    </div>
                ))}
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
