import { useBlockProps, RichText, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, ToggleControl, SelectControl, TextareaControl, TextControl, Button } from "@wordpress/components";
import { useState, useEffect } from "@wordpress/element";

export default function Edit({ attributes, setAttributes }) {
  const { faqItems, ...a } = attributes;
  const blockProps = useBlockProps({ className: "faq-container" });
  const Heading = a.headingTag || "h2";
  const [importJson, setImportJson] = useState("");

  useEffect(() => {
    if (!faqItems || faqItems.length === 0) {
      let migrated = [];
      for (let i = 1; i <= 10; i++) {
        if (a[`question${i}`] || a[`answer${i}`]) {
          migrated.push({
            question: a[`question${i}`] || '',
            answer: a[`answer${i}`] || ''
          });
        }
      }
      if (migrated.length > 0) {
        setAttributes({ faqItems: migrated });
      } else {
        setAttributes({ faqItems: [{ question: "", answer: "" }] });
      }
    }
  }, []);

  const items = faqItems || [];

  const addItem = () => {
    setAttributes({ faqItems: [...items, { question: "", answer: "" }] });
  };

  const removeItem = (index) => {
    const newItems = [...items];
    newItems.splice(index, 1);
    setAttributes({ faqItems: newItems });
  };

  const updateItem = (index, key, val) => {
    const newItems = [...items];
    newItems[index] = { ...newItems[index], [key]: val };
    setAttributes({ faqItems: newItems });
  };

  const panelEditorStyle = {
    padding: "0",
  };

  return (
    <div {...blockProps}>
      <InspectorControls>
        <PanelBody title="Lista pytań (Pasek boczny)" initialOpen={true}>
          {items.map((item, index) => (
            <div key={index} style={{ marginBottom: 24, padding: 12, border: "1px solid #ddd", borderRadius: 4 }}>
              <div style={{ display: "flex", justifyContent: "space-between", alignItems: "center", marginBottom: 12 }}>
                <strong>Pytanie {index + 1}</strong>
                <Button isDestructive isSmall variant="link" onClick={() => removeItem(index)}>Usuń</Button>
              </div>
              <TextControl
                label="Treść pytania"
                value={item.question}
                onChange={(v) => updateItem(index, 'question', v)}
              />
              <TextareaControl
                label="Odpowiedź"
                value={item.answer}
                onChange={(v) => updateItem(index, 'answer', v)}
                rows={4}
              />
            </div>
          ))}
          <Button variant="secondary" onClick={addItem} style={{ width: '100%', justifyContent: 'center' }}>
            + Dodaj kolejne pytanie
          </Button>
        </PanelBody>

        <PanelBody title="Ustawienia FAQ" initialOpen={false}>
          <ToggleControl label="Pokaż główny tytuł sekcji" checked={a.showTopTitle} onChange={(v) => setAttributes({ showTopTitle: v })} />
          <SelectControl
            label="Tag nagłówka pytania"
            value={a.headingTag}
            options={[
              { label: "h2", value: "h2" },
              { label: "h3", value: "h3" },
              { label: "h4", value: "h4" },
              { label: "div", value: "div" },
            ]}
            onChange={(v) => setAttributes({ headingTag: v })}
          />
        </PanelBody>

        <PanelBody title="Tłumaczenia AI (JSON)" initialOpen={false}>
          <TextareaControl
            label="Skopiuj ten JSON dla AI"
            value={(() => {
              const data = {
                topTitle: a.topTitle || '',
                faqItems: items.map(item => ({ question: item.question, answer: item.answer }))
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
              if (parsed.topTitle !== undefined) updates.topTitle = parsed.topTitle;
              if (parsed.faqItems) {
                updates.faqItems = parsed.faqItems;
              }
              setAttributes(updates);
              alert('Zaktualizowano pomyślnie!');
              setImportJson('');
            } catch (e) {
              alert('Błąd! Niepoprawny format JSON.');
            }
          }} style={{ width: '100%', justifyContent: 'center' }}>
            Importuj tłumaczenie
          </Button>
        </PanelBody>
      </InspectorControls>
      
      <div className="faq-wrapper container--narrow2-important">
        {a.showTopTitle && (
          <RichText tagName="div" className="faq-title" value={a.topTitle} onChange={(v) => setAttributes({ topTitle: v })} placeholder="Tytuł sekcji" />
        )}
        
        <div className="faq-wrapper-questions">
          {items.map((item, index) => {
            return (
              <div key={index} className="faq" style={{ position: "relative", marginBottom: "16px", border: "1px dashed #ccc", padding: "16px", borderRadius: "8px" }}>
                <div className="faq-accordion" style={{ display: "flex", justifyContent: "space-between", alignItems: "center", gap: 16 }}>
                  <RichText tagName={Heading} className="faq-header" value={item.question} onChange={(v) => updateItem(index, 'question', v)} placeholder={`Pytanie ${index + 1}...`} style={{ margin: 0 }} />
                  <i className="fas fa-chevron-down"></i>
                </div>
                <div className="faq-pannel" style={panelEditorStyle}>
                  <RichText tagName="p" value={item.answer} onChange={(v) => updateItem(index, 'answer', v)} placeholder={`Odpowiedź ${index + 1}...`} style={{ margin: "10px 0 0 0" }} />
                </div>
              </div>
            );
          })}
        </div>
      </div>
    </div>
  );
}

