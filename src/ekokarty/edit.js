import {
  useBlockProps,
  RichText,
  InspectorControls,
  MediaUpload,
  MediaUploadCheck,
} from "@wordpress/block-editor";
import { PanelBody, Button, TextareaControl } from "@wordpress/components";
import { useState } from "@wordpress/element";

export default function Edit({ attributes, setAttributes }) {
  const {
    title,
    card1Icon, card1Title, card1Text,
    card2Icon, card2Title, card2Text,
    card3Icon, card3Title, card3Text,
  } = attributes;

  const [importJson, setImportJson] = useState("");

  const blockProps = useBlockProps({ className: "ekokarty" });

  return (
    <section {...blockProps}>
      <InspectorControls>
        <PanelBody title="Tłumaczenia AI (JSON)" initialOpen={false}>
          <TextareaControl
            label="Skopiuj ten JSON dla AI"
            value={(() => {
              const data = {
                title: title || '',
                card1Title: card1Title || '',
                card1Text: card1Text || '',
                card2Title: card2Title || '',
                card2Text: card2Text || '',
                card3Title: card3Title || '',
                card3Text: card3Text || ''
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
              ['title', 'card1Title', 'card1Text', 'card2Title', 'card2Text', 'card3Title', 'card3Text'].forEach(key => {
                if (parsed[key] !== undefined) updates[key] = parsed[key];
              });
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
        <PanelBody title="Ikony" initialOpen={true}>
          <p>Karta 1</p>
          <MediaUploadCheck>
            <MediaUpload
              onSelect={(media) => setAttributes({ card1Icon: media.url })}
              allowedTypes={["image"]}
              value={card1Icon}
              render={({ open }) => (
                <Button variant="secondary" onClick={open}>
                  {card1Icon ? "Zmień ikonę 1" : "Wybierz ikonę 1"}
                </Button>
              )}
            />
          </MediaUploadCheck>
          {card1Icon && (
            <Button variant="link" isDestructive onClick={() => setAttributes({ card1Icon: "" })} style={{ display: 'block', marginTop: 8 }}>
              Usuń ikonę 1
            </Button>
          )}
          
          <p style={{ marginTop: 16 }}>Karta 2</p>
          <MediaUploadCheck>
            <MediaUpload
              onSelect={(media) => setAttributes({ card2Icon: media.url })}
              allowedTypes={["image"]}
              value={card2Icon}
              render={({ open }) => (
                <Button variant="secondary" onClick={open}>
                  {card2Icon ? "Zmień ikonę 2" : "Wybierz ikonę 2"}
                </Button>
              )}
            />
          </MediaUploadCheck>
          {card2Icon && (
            <Button variant="link" isDestructive onClick={() => setAttributes({ card2Icon: "" })} style={{ display: 'block', marginTop: 8 }}>
              Usuń ikonę 2
            </Button>
          )}

          <p style={{ marginTop: 16 }}>Karta 3</p>
          <MediaUploadCheck>
            <MediaUpload
              onSelect={(media) => setAttributes({ card3Icon: media.url })}
              allowedTypes={["image"]}
              value={card3Icon}
              render={({ open }) => (
                <Button variant="secondary" onClick={open}>
                  {card3Icon ? "Zmień ikonę 3" : "Wybierz ikonę 3"}
                </Button>
              )}
            />
          </MediaUploadCheck>
          {card3Icon && (
            <Button variant="link" isDestructive onClick={() => setAttributes({ card3Icon: "" })} style={{ display: 'block', marginTop: 8 }}>
              Usuń ikonę 3
            </Button>
          )}
        </PanelBody>
      </InspectorControls>

      <div className="ekokarty__inner">
        <RichText
          tagName="h2"
          className="ekokarty__title"
          value={title}
          onChange={(v) => setAttributes({ title: v })}
          placeholder="Nagłówek sekcji"
          allowedFormats={["core/bold", "core/italic", "core/text-color"]}
        />

        <div className="ekokarty__grid">
          {/* Card 1 */}
          <div className="ekokarty__card">
            <div className="ekokarty__card-icon">
              {card1Icon && <img src={card1Icon} alt="" />}
            </div>
            <RichText
              tagName="h3"
              className="ekokarty__card-title"
              value={card1Title}
              onChange={(v) => setAttributes({ card1Title: v })}
              placeholder="Tytuł karty"
            />
            <RichText
              tagName="div"
              className="ekokarty__card-text"
              value={card1Text}
              onChange={(v) => setAttributes({ card1Text: v })}
              placeholder="Treść karty"
              allowedFormats={["core/bold", "core/italic", "core/text-color"]}
            />
          </div>

          {/* Card 2 */}
          <div className="ekokarty__card">
            <div className="ekokarty__card-icon">
              {card2Icon && <img src={card2Icon} alt="" />}
            </div>
            <RichText
              tagName="h3"
              className="ekokarty__card-title"
              value={card2Title}
              onChange={(v) => setAttributes({ card2Title: v })}
              placeholder="Tytuł karty"
            />
            <RichText
              tagName="div"
              className="ekokarty__card-text"
              value={card2Text}
              onChange={(v) => setAttributes({ card2Text: v })}
              placeholder="Treść karty"
              allowedFormats={["core/bold", "core/italic", "core/text-color"]}
            />
          </div>

          {/* Card 3 */}
          <div className="ekokarty__card ekokarty__card--green">
            <div className="ekokarty__card-icon">
              {card3Icon && <img src={card3Icon} alt="" />}
            </div>
            <RichText
              tagName="h3"
              className="ekokarty__card-title ekokarty__card-title--green"
              value={card3Title}
              onChange={(v) => setAttributes({ card3Title: v })}
              placeholder="Tytuł karty"
            />
            <RichText
              tagName="div"
              className="ekokarty__card-text ekokarty__card-text--green"
              value={card3Text}
              onChange={(v) => setAttributes({ card3Text: v })}
              placeholder="Treść karty"
              allowedFormats={["core/bold", "core/italic", "core/text-color"]}
            />
          </div>
        </div>
      </div>
    </section>
  );
}
