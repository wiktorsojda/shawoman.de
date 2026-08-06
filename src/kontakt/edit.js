import { useBlockProps, RichText, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, TextControl, TextareaControl, Button } from "@wordpress/components";
import { useState, useEffect } from "@wordpress/element";

export default function Edit({ attributes, setAttributes }) {
  const a = attributes;
  const [importJson, setImportJson] = useState("");
  const blockProps = useBlockProps({ className: "faq-container faq-container-kontakt" });

  useEffect(() => {
    // Migracja FAQ
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

    // Migracja Info Kafelków
    if (!a.infoTiles || a.infoTiles.length === 0) {
      const migratedInfo = [];
      if (a.hurtTitle || a.hurtIntro || a.hurtButtonLabel) {
        migratedInfo.push({
          title: a.hurtTitle || "Sprzedaż hurtowa",
          intro: a.hurtIntro || "Jesteś zainteresowany współpracą z nami?",
          buttonLabel: a.hurtButtonLabel || "Dowiedz się więcej",
          buttonURL: a.hurtButtonURL || "/sprzedaz-hurtowa"
        });
      }
      if (a.zwrotyTitle || a.zwrotyIntro || a.zwrotyButtonLabel) {
        migratedInfo.push({
          title: a.zwrotyTitle || "Zwroty",
          intro: a.zwrotyIntro || "W ciągu 14 dni możesz zwrócić produkt kupiony przez internet.",
          buttonLabel: a.zwrotyButtonLabel || "Dowiedz się więcej",
          buttonURL: a.zwrotyButtonURL || "/zwrot"
        });
      }
      if (a.reklamacjeTitle || a.reklamacjeIntro || a.reklamacjeButtonLabel) {
        migratedInfo.push({
          title: a.reklamacjeTitle || "Reklamacje",
          intro: a.reklamacjeIntro || "Chcesz dowiedzieć się jak złożyć reklamację?",
          buttonLabel: a.reklamacjeButtonLabel || "Dowiedz się więcej",
          buttonURL: a.reklamacjeButtonURL || "/zwrot"
        });
      }
      if (migratedInfo.length > 0) {
        setAttributes({ infoTiles: migratedInfo });
      } else {
        setAttributes({ infoTiles: [
          { title: "Sprzedaż hurtowa", intro: "Jesteś zainteresowany współpracą z nami?", buttonLabel: "Dowiedz się więcej", buttonURL: "/sprzedaz-hurtowa" },
          { title: "Zwroty", intro: "W ciągu 14 dni możesz zwrócić produkt kupiony przez internet.", buttonLabel: "Dowiedz się więcej", buttonURL: "/zwrot" },
          { title: "Reklamacje", intro: "Chcesz dowiedzieć się jak złożyć reklamację?", buttonLabel: "Dowiedz się więcej", buttonURL: "/zwrot" }
        ]});
      }
    }
  }, []);

  const faqItems = a.faqItems || [];
  const infoTiles = a.infoTiles || [];

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

  const updateInfoTile = (index, field, value) => {
    const newItems = [...infoTiles];
    newItems[index] = { ...newItems[index], [field]: value };
    setAttributes({ infoTiles: newItems });
  };
  const addInfoTile = () => {
    setAttributes({ infoTiles: [...infoTiles, { title: "", intro: "", buttonLabel: "", buttonURL: "" }] });
  };
  const removeInfoTile = (index) => {
    const newItems = [...infoTiles];
    newItems.splice(index, 1);
    setAttributes({ infoTiles: newItems });
  };

  return (
    <div {...blockProps}>
      <InspectorControls>
        <PanelBody title="Zarządzaj FAQ" initialOpen={true}>
          {faqItems.map((item, i) => (
            <div key={i} style={{ border: '1px solid #ddd', padding: '12px', marginBottom: '12px', borderRadius: '4px', backgroundColor: '#f9f9f9' }}>
              <TextControl label={`Pytanie ${i + 1}`} value={item.question} onChange={(v) => updateFaqItem(i, "question", v)} />
              <TextareaControl label={`Odpowiedź ${i + 1}`} value={item.answer} onChange={(v) => updateFaqItem(i, "answer", v)} rows={3} />
              <Button isDestructive variant="link" onClick={() => removeFaqItem(i)} style={{ padding: 0 }}>Usuń to pytanie</Button>
            </div>
          ))}
          <Button variant="secondary" onClick={addFaqItem} style={{ width: "100%", justifyContent: "center" }}>+ Dodaj nowe pytanie FAQ</Button>
        </PanelBody>

        <PanelBody title="Zarządzaj Głównymi Kafelkami (Prawa strona)" initialOpen={false}>
          {infoTiles.map((item, i) => (
            <div key={i} style={{ border: '1px solid #ddd', padding: '12px', marginBottom: '12px', borderRadius: '4px', backgroundColor: '#f9f9f9' }}>
              <TextControl label={`Tytuł ${i + 1}`} value={item.title} onChange={(v) => updateInfoTile(i, "title", v)} />
              <TextareaControl label={`Wprowadzenie ${i + 1}`} value={item.intro} onChange={(v) => updateInfoTile(i, "intro", v)} rows={3} />
              <TextControl label={`Etykieta przycisku ${i + 1}`} value={item.buttonLabel} onChange={(v) => updateInfoTile(i, "buttonLabel", v)} />
              <TextControl label={`URL przycisku ${i + 1}`} value={item.buttonURL} onChange={(v) => updateInfoTile(i, "buttonURL", v)} />
              <Button isDestructive variant="link" onClick={() => removeInfoTile(i)} style={{ padding: 0 }}>Usuń ten kafelek</Button>
            </div>
          ))}
          <Button variant="secondary" onClick={addInfoTile} style={{ width: "100%", justifyContent: "center" }}>+ Dodaj nowy kafelek</Button>
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
                faqItems: faqItems,
                infoTiles: infoTiles
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
              const keys = ["title", "subtitle", "obsługaTitle", "obsługaIntro", "phone", "email", "hours", "faqTitle"];
              keys.forEach(k => { if (parsed[k] !== undefined) updates[k] = parsed[k]; });
              if (parsed.faqItems && Array.isArray(parsed.faqItems)) updates.faqItems = parsed.faqItems;
              if (parsed.infoTiles && Array.isArray(parsed.infoTiles)) updates.infoTiles = parsed.infoTiles;
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

        <PanelBody title="Linki kontaktowe (Obsługa klienta)" initialOpen={false}>
          <TextControl label="Telefon (link tel:)" value={a.phoneHref} onChange={(v) => setAttributes({ phoneHref: v })} />
          <TextControl label="Email (link mailto:)" value={a.emailHref} onChange={(v) => setAttributes({ emailHref: v })} />
        </PanelBody>
      </InspectorControls>

      <div className="container--narrow2-important">
        <RichText tagName="div" className="regulamin-title" value={a.title} onChange={(v) => setAttributes({ title: v })} placeholder="Tytuł" />
        <RichText tagName="div" className="regulamin-subtitle" value={a.subtitle} onChange={(v) => setAttributes({ subtitle: v })} placeholder="Podtytuł" />

        <div className="grid-container-kontakt">
          <div className="item1-kontakt">
            <RichText tagName="div" className="item-kontakt-title" value={a.obsługaTitle} onChange={(v) => setAttributes({ obsługaTitle: v })} placeholder="Tytuł sekcji" />
            <RichText tagName="span" value={a.obsługaIntro} onChange={(v) => setAttributes({ obsługaIntro: v })} placeholder="Wprowadzenie" />
            <RichText tagName="span" value={a.phone} onChange={(v) => setAttributes({ phone: v })} placeholder="Numer telefonu" />
            <RichText tagName="span" value={a.email} onChange={(v) => setAttributes({ email: v })} placeholder="Email" />
            <RichText tagName="span" value={a.hours} onChange={(v) => setAttributes({ hours: v })} placeholder="Godziny" />
          </div>

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

          {infoTiles.map((item, i) => (
            <div key={i} className="item3-kontakt background-white" style={{ gridRow: `${i + 1} / ${i + 2}`, gridColumn: "2 / 3" }}>
              <RichText tagName="div" className="item-kontakt-title" value={item.title} onChange={(v) => updateInfoTile(i, "title", v)} placeholder="Tytuł" />
              <RichText tagName="span" value={item.intro} onChange={(v) => updateInfoTile(i, "intro", v)} placeholder="Wprowadzenie" />
              <RichText tagName="button" className="background-white" value={item.buttonLabel} onChange={(v) => updateInfoTile(i, "buttonLabel", v)} placeholder="Etykieta" />
            </div>
          ))}
        </div>
      </div>
    </div>
  );
}
