import { useBlockProps, RichText, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, ToggleControl, SelectControl, TextareaControl, Button } from "@wordpress/components";
import { useState } from "@wordpress/element";

export default function Edit({ attributes, setAttributes }) {
  const a = attributes;
  const blockProps = useBlockProps({ className: a.containerClass || "glownafaq" });
  const Heading = a.headingTag || "h3";
  const [importJson, setImportJson] = useState("");

  // W edytorze nadpisujemy max-height/opacity, zeby panel z odpowiedzia byl
  // zawsze widoczny i edytowalny (front zachowuje accordion).
  const panelEditorStyle = {
    maxHeight: "none",
    opacity: 1,
    paddingBottom: 16,
  };

  return (
    <div {...blockProps}>
      <InspectorControls>
        <PanelBody title="Tłumaczenia AI (JSON)" initialOpen={false}>
          <TextareaControl
            label="Skopiuj ten JSON dla AI"
            value={(() => {
              const data = {
                title: a.title || '',
                topTitle: a.topTitle || '',
                question1: a.question1 || '',
                answer1: a.answer1 || '',
                question2: a.question2 || '',
                answer2: a.answer2 || '',
                question3: a.question3 || '',
                answer3: a.answer3 || '',
                question4: a.question4 || '',
                answer4: a.answer4 || '',
                question5: a.question5 || '',
                answer5: a.answer5 || '',
                question6: a.question6 || '',
                answer6: a.answer6 || '',
                question7: a.question7 || '',
                answer7: a.answer7 || '',
                question8: a.question8 || '',
                answer8: a.answer8 || '',
                question9: a.question9 || '',
                answer9: a.answer9 || '',
                question10: a.question10 || '',
                answer10: a.answer10 || ''
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
              if (parsed.title !== undefined) updates.title = parsed.title;
              if (parsed.topTitle !== undefined) updates.topTitle = parsed.topTitle;
              for (let i = 1; i <= 10; i++) {
                if (parsed[`question${i}`] !== undefined) updates[`question${i}`] = parsed[`question${i}`];
                if (parsed[`answer${i}`] !== undefined) updates[`answer${i}`] = parsed[`answer${i}`];
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
        <PanelBody title="Ustawienia FAQ" initialOpen={true}>
          <ToggleControl label="Pokaż główny tytuł sekcji" checked={a.showTopTitle} onChange={(v) => setAttributes({ showTopTitle: v })} />
          <ToggleControl label="Pokaż dodatkowy tytuł (mniejszy)" checked={a.showTitle} onChange={(v) => setAttributes({ showTitle: v })} />
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
      </InspectorControls>
      <div className="glownafaq__inner">
        {a.showTopTitle && (
          <RichText tagName="h2" className="glownafaq__top-title" value={a.topTitle} onChange={(v) => setAttributes({ topTitle: v })} placeholder="Tytuł sekcji" />
        )}
        {a.showTitle && (
          <RichText tagName="div" className="glownafaq__title" value={a.title} onChange={(v) => setAttributes({ title: v })} placeholder="Dodatkowy tytuł" />
        )}
        <div className="glownafaq__list">
          {[1, 2, 3, 4, 5, 6, 7, 8, 9, 10].map((i) => {
            const q = a[`question${i}`];
            const ans = a[`answer${i}`];
            if (!q && !ans) return null;
            return (
              <div key={i} className="glownafaq__item">
                <div className="glownafaq__trigger faq-accordion" style={{ display: "flex", justifyContent: "space-between", alignItems: "center", gap: 16 }}>
                  <RichText tagName={Heading} className="glownafaq__question faq-header" value={q} onChange={(v) => setAttributes({ [`question${i}`]: v })} placeholder={`Pytanie ${i}`} />
                  <span className="glownafaq__chevron" aria-hidden="true">▾</span>
                </div>
                <div className="glownafaq__panel faq-pannel" style={panelEditorStyle}>
                  <RichText tagName="p" value={ans} onChange={(v) => setAttributes({ [`answer${i}`]: v })} placeholder={`Odpowiedź ${i}`} />
                </div>
              </div>
            );
          })}
          <p style={{ fontSize: 12, color: "#999", marginTop: 16 }}>Aby dodać kolejne pytania, wpisz je w slotach 1-10 (puste są pomijane na froncie).</p>
        </div>
      </div>
    </div>
  );
}
