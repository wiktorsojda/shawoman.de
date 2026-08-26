import { useBlockProps, RichText, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, TextControl, TextareaControl, Button, SelectControl } from "@wordpress/components";
import { useState, useEffect } from "@wordpress/element";

export default function Edit({ attributes, setAttributes }) {
  const a = attributes;
  const [importJson, setImportJson] = useState("");
  const blockProps = useBlockProps({ className: "kontakt" });

  useEffect(() => {
    // Migracja FAQ (Legacy)
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
      }
    }

    // Migracja Info Kafelków (Legacy)
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
      }
    }
  }, []);

  const contactMethods = a.contactMethods || [];
  const faqItems = a.faqItems || [];
  const infoTiles = a.infoTiles || [];

  const updateContactMethod = (index, field, value) => {
    const newItems = [...contactMethods];
    newItems[index] = { ...newItems[index], [field]: value };
    setAttributes({ contactMethods: newItems });
  };
  const addContactMethod = () => {
    setAttributes({ contactMethods: [...contactMethods, { type: 'phone', label: '', link: '' }] });
  };
  const removeContactMethod = (index) => {
    const newItems = [...contactMethods];
    newItems.splice(index, 1);
    setAttributes({ contactMethods: newItems });
  };

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

  const iconOptions = [
    { label: 'Telefon', value: 'phone' },
    { label: 'E-mail', value: 'email' },
    { label: 'WhatsApp', value: 'whatsapp' },
    { label: 'Inne (Brak)', value: 'other' }
  ];

  const getIconSvg = (type) => {
    if (type === 'phone') {
      return (
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M22.17 1.81994L21.12 0.909942C19.91 -0.300059 17.95 -0.300059 16.74 0.909942C16.71 0.939942 14.86 3.34994 14.86 3.34994C13.72 4.54994 13.72 6.43994 14.86 7.62994L16.02 9.08994C14.56 12.3999 12.29 14.6799 9.09 16.0399L7.63 14.8699C6.44 13.7199 4.54 13.7199 3.35 14.8699C3.35 14.8699 0.940004 16.7199 0.910004 16.7499C-0.299996 17.9599 -0.299996 19.9199 0.860004 21.0799L1.86 22.2299C3.01 23.3799 4.56 24.0099 6.24 24.0099C13.88 24.0099 24 13.8799 24 6.24994C24 4.57994 23.37 3.01994 22.17 1.82994V1.81994ZM6.24 21.9999C5.1 21.9999 4.05 21.5799 3.33 20.8499L2.33 19.6999C1.92 19.2899 1.9 18.6199 2.29 18.1899C2.29 18.1899 4.68 16.3499 4.71 16.3199C5.12 15.9099 5.84 15.9099 6.26 16.3199C6.29 16.3499 8.3 17.9599 8.3 17.9599C8.58 18.1799 8.95 18.2399 9.28 18.1099C13.42 16.5299 16.39 13.5699 18.1 9.29994C18.23 8.96994 18.18 8.58994 17.95 8.29994C17.95 8.29994 16.34 6.27994 16.32 6.25994C15.89 5.82994 15.89 5.13994 16.32 4.70994C16.35 4.67994 18.19 2.28994 18.19 2.28994C18.62 1.89994 19.29 1.90994 19.75 2.36994L20.8 3.27994C21.57 4.04994 22 5.09994 22 6.23994C22 13.1999 12.23 21.9999 6.24 21.9999Z" fill="#3F3F3F"/>
        </svg>
      );
    }
    if (type === 'email') {
      return (
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M20 4H4C2.9 4 2.01 4.9 2.01 6L2 18C2 19.1 2.9 20 4 20H20C21.1 20 22 19.1 22 18V6C22 4.9 21.1 4 20 4ZM20 8L12 13L4 8V6L12 11L20 6V8Z" fill="#3F3F3F"/>
        </svg>
      );
    }
    if (type === 'whatsapp') {
      return (
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91C2.13 13.66 2.59 15.36 3.45 16.86L2 22L7.3 20.62C8.75 21.41 10.38 21.83 12.04 21.83C17.5 21.83 21.95 17.38 21.95 11.92C21.95 9.27 20.92 6.78 19.05 4.91C17.18 3.03 14.69 2 12.04 2ZM12.05 20.16C10.6 20.16 9.18 19.78 7.95 19.05L7.66 18.88L4.47 19.71L5.32 16.59L5.13 16.28C4.33 15.01 3.91 13.5 3.91 11.91C3.91 7.42 7.56 3.77 12.05 3.77C14.23 3.77 16.27 4.62 17.81 6.16C19.35 7.7 20.2 9.74 20.2 11.92C20.2 16.41 16.54 20.16 12.05 20.16ZM16.52 14.07C16.27 13.95 15.07 13.35 14.85 13.27C14.63 13.19 14.45 13.15 14.28 13.39C14.11 13.64 13.62 14.23 13.47 14.41C13.32 14.59 13.17 14.61 12.92 14.49C12.67 14.37 11.89 14.11 10.96 13.28C10.23 12.63 9.73 11.82 9.58 11.57C9.43 11.32 9.56 11.19 9.68 11.07C9.8 10.95 9.94 10.78 10.07 10.63C10.2 10.48 10.25 10.37 10.35 10.21C10.45 10.05 10.4 9.9 10.35 9.78C10.3 9.66 9.8 8.44 9.59 7.94C9.39 7.46 9.18 7.52 9.03 7.52C8.88 7.52 8.71 7.52 8.53 7.52C8.36 7.52 8.08 7.58 7.85 7.82C7.63 8.07 6.98 8.68 6.98 9.92C6.98 11.16 7.88 12.35 8.01 12.52C8.13 12.69 9.77 15.34 12.37 16.35C14.55 17.2 15.01 17.03 15.52 16.96C16.03 16.89 17.15 16.27 17.37 15.6C17.59 14.93 17.59 14.35 17.52 14.23C17.44 14.09 17.27 14.07 17.02 13.95L16.52 14.07Z" fill="#3F3F3F"/>
        </svg>
      );
    }
    return null;
  };

  return (
    <div {...blockProps}>
      <InspectorControls>
        <PanelBody title="Metody kontaktu" initialOpen={true}>
          {contactMethods.map((item, i) => (
            <div key={i} style={{ border: '1px solid #ddd', padding: '12px', marginBottom: '12px', borderRadius: '4px', backgroundColor: '#f9f9f9' }}>
              <SelectControl
                label="Typ"
                value={item.type}
                options={iconOptions}
                onChange={(v) => updateContactMethod(i, "type", v)}
              />
              <TextControl label="Etykieta (tekst)" value={item.label} onChange={(v) => updateContactMethod(i, "label", v)} />
              <TextControl label="Link (href)" value={item.link} onChange={(v) => updateContactMethod(i, "link", v)} help="np. tel:+48690801270 lub mailto:kontakt@shavwoman.pl" />
              <Button isDestructive variant="link" onClick={() => removeContactMethod(i)} style={{ padding: 0 }}>Usuń</Button>
            </div>
          ))}
          <Button variant="secondary" onClick={addContactMethod} style={{ width: "100%", justifyContent: "center" }}>+ Dodaj metodę kontaktu</Button>
        </PanelBody>

        <PanelBody title="Legacy FAQ (Stare dane z Fallbacku)" initialOpen={false}>
          {faqItems.map((item, i) => (
            <div key={i} style={{ border: '1px solid #ddd', padding: '12px', marginBottom: '12px', borderRadius: '4px', backgroundColor: '#f9f9f9' }}>
              <TextControl label={`Pytanie ${i + 1}`} value={item.question} onChange={(v) => updateFaqItem(i, "question", v)} />
              <TextareaControl label={`Odpowiedź ${i + 1}`} value={item.answer} onChange={(v) => updateFaqItem(i, "answer", v)} rows={3} />
              <Button isDestructive variant="link" onClick={() => removeFaqItem(i)} style={{ padding: 0 }}>Usuń to pytanie</Button>
            </div>
          ))}
          <Button variant="secondary" onClick={addFaqItem} style={{ width: "100%", justifyContent: "center" }}>+ Dodaj nowe pytanie FAQ</Button>
        </PanelBody>

        <PanelBody title="Kafelki (Dolne boxy informacyjne)" initialOpen={false}>
          {infoTiles.map((item, i) => (
            <div key={i} style={{ border: '1px solid #ddd', padding: '12px', marginBottom: '12px', borderRadius: '4px', backgroundColor: '#f9f9f9' }}>
              <TextControl label={`Tytuł ${i + 1}`} value={item.title} onChange={(v) => updateInfoTile(i, "title", v)} />
              {/* Ukryto pole intro dla nowych kafelków, ale dane mogą być zachowane */}
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
                contactMethods: contactMethods,
                faqItems: faqItems,
                infoTiles: infoTiles
              };
              return JSON.stringify(data, null, 2);
            })()}
            readOnly
            rows={10}
            help="Skopiuj i wklej do AI z prośbą o przetłumaczenie samych wartości (etykiet)."
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
              const keys = ["title", "subtitle"];
              keys.forEach(k => { if (parsed[k] !== undefined) updates[k] = parsed[k]; });
              if (parsed.contactMethods && Array.isArray(parsed.contactMethods)) updates.contactMethods = parsed.contactMethods;
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
      </InspectorControls>

      <div className="kontakt__inner">
        <RichText 
          tagName="h1" 
          className="kontakt__title" 
          value={a.title} 
          onChange={(v) => setAttributes({ title: v })} 
          placeholder="Jesteśmy tu żeby Ci pomóc" 
        />
        <RichText 
          tagName="p" 
          className="kontakt__subtitle" 
          value={a.subtitle} 
          onChange={(v) => setAttributes({ subtitle: v })} 
          placeholder="Masz pytanie? Skontaktuj się z nami. Jesteśmy dostępni poniedziałek - piątek 8:00-16:00" 
        />
        
        <div className="kontakt__methods">
          {contactMethods.map((item, i) => (
            <div key={i} className="kontakt__method">
              <span className="kontakt__method-icon">
                {getIconSvg(item.type)}
              </span>
              <RichText 
                tagName="span" 
                className="kontakt__method-text" 
                value={item.label} 
                onChange={(v) => updateContactMethod(i, "label", v)} 
                placeholder="Wpisz kontakt (np. numer telefonu)..." 
              />
            </div>
          ))}
        </div>

        <div className="kontakt__tiles">
          {infoTiles.map((item, i) => (
            <div key={i} className="kontakt__tile">
              <RichText 
                tagName="p" 
                className="kontakt__tile-title" 
                value={item.title} 
                onChange={(v) => updateInfoTile(i, "title", v)} 
                placeholder="Wpisz nazwę (np. Shav Woman)" 
              />
              <RichText 
                tagName="span" 
                className="kontakt__tile-link" 
                value={item.buttonLabel} 
                onChange={(v) => updateInfoTile(i, "buttonLabel", v)} 
                placeholder="Wpisz nazwę linku..." 
              />
            </div>
          ))}
        </div>
      </div>
    </div>
  );
}
