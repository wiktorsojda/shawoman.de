import {
  useBlockProps,
  RichText,
  InspectorControls,
  MediaUpload,
  MediaUploadCheck,
} from "@wordpress/block-editor";
import { PanelBody, Button, TextareaControl, TextControl } from "@wordpress/components";
import { useState } from "@wordpress/element";

export default function Edit({ attributes, setAttributes }) {
  const {
    title,
    card1Icon, card1TopIcon, card1Title, card1Text,
    card2Icon, card2TopIcon, card2Title, card2Text,
    card3Icon, card3TopIcon, card3Title, card3Text,
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
        <PanelBody title="Karta 1" initialOpen={true}>
          <TextControl
            label="Tytuł karty 1"
            value={card1Title}
            onChange={(v) => setAttributes({ card1Title: v })}
          />
          <TextareaControl
            label="Treść karty 1"
            value={card1Text}
            onChange={(v) => setAttributes({ card1Text: v })}
            rows={5}
            help="Możesz używać znaczników HTML takich jak <strong> czy <br>"
          />
          <p style={{ marginTop: 16 }}>Ikona główna</p>
          <MediaUploadCheck>
            <MediaUpload
              onSelect={(media) => setAttributes({ card1Icon: media.url })}
              allowedTypes={["image"]}
              value={card1Icon}
              render={({ open }) => (
                <Button variant="secondary" onClick={open}>
                  {card1Icon ? "Zmień ikonę" : "Wybierz ikonę"}
                </Button>
              )}
            />
          </MediaUploadCheck>
          {card1Icon && (
            <Button variant="link" isDestructive onClick={() => setAttributes({ card1Icon: "" })} style={{ display: 'block', marginTop: 8 }}>
              Usuń ikonę główną
            </Button>
          )}
          <p style={{ marginTop: 16 }}>Ikona w prawym górnym rogu</p>
          <MediaUploadCheck>
            <MediaUpload
              onSelect={(media) => setAttributes({ card1TopIcon: media.url })}
              allowedTypes={["image"]}
              value={card1TopIcon}
              render={({ open }) => (
                <Button variant="secondary" onClick={open}>
                  {card1TopIcon ? "Zmień ikonę" : "Wybierz ikonę"}
                </Button>
              )}
            />
          </MediaUploadCheck>
          {card1TopIcon && (
            <Button variant="link" isDestructive onClick={() => setAttributes({ card1TopIcon: "" })} style={{ display: 'block', marginTop: 8 }}>
              Usuń ikonę z rogu
            </Button>
          )}
        </PanelBody>

        <PanelBody title="Karta 2" initialOpen={false}>
          <TextControl
            label="Tytuł karty 2"
            value={card2Title}
            onChange={(v) => setAttributes({ card2Title: v })}
          />
          <TextareaControl
            label="Treść karty 2"
            value={card2Text}
            onChange={(v) => setAttributes({ card2Text: v })}
            rows={5}
            help="Możesz używać znaczników HTML takich jak <strong> czy <br>"
          />
          <p style={{ marginTop: 16 }}>Ikona główna</p>
          <MediaUploadCheck>
            <MediaUpload
              onSelect={(media) => setAttributes({ card2Icon: media.url })}
              allowedTypes={["image"]}
              value={card2Icon}
              render={({ open }) => (
                <Button variant="secondary" onClick={open}>
                  {card2Icon ? "Zmień ikonę" : "Wybierz ikonę"}
                </Button>
              )}
            />
          </MediaUploadCheck>
          {card2Icon && (
            <Button variant="link" isDestructive onClick={() => setAttributes({ card2Icon: "" })} style={{ display: 'block', marginTop: 8 }}>
              Usuń ikonę główną
            </Button>
          )}
          <p style={{ marginTop: 16 }}>Ikona w prawym górnym rogu</p>
          <MediaUploadCheck>
            <MediaUpload
              onSelect={(media) => setAttributes({ card2TopIcon: media.url })}
              allowedTypes={["image"]}
              value={card2TopIcon}
              render={({ open }) => (
                <Button variant="secondary" onClick={open}>
                  {card2TopIcon ? "Zmień ikonę" : "Wybierz ikonę"}
                </Button>
              )}
            />
          </MediaUploadCheck>
          {card2TopIcon && (
            <Button variant="link" isDestructive onClick={() => setAttributes({ card2TopIcon: "" })} style={{ display: 'block', marginTop: 8 }}>
              Usuń ikonę z rogu
            </Button>
          )}
        </PanelBody>

        <PanelBody title="Karta 3" initialOpen={false}>
          <TextControl
            label="Tytuł karty 3"
            value={card3Title}
            onChange={(v) => setAttributes({ card3Title: v })}
          />
          <TextareaControl
            label="Treść karty 3"
            value={card3Text}
            onChange={(v) => setAttributes({ card3Text: v })}
            rows={5}
            help="Możesz używać znaczników HTML takich jak <strong> czy <br>"
          />
          <p style={{ marginTop: 16 }}>Ikona główna</p>
          <MediaUploadCheck>
            <MediaUpload
              onSelect={(media) => setAttributes({ card3Icon: media.url })}
              allowedTypes={["image"]}
              value={card3Icon}
              render={({ open }) => (
                <Button variant="secondary" onClick={open}>
                  {card3Icon ? "Zmień ikonę" : "Wybierz ikonę"}
                </Button>
              )}
            />
          </MediaUploadCheck>
          {card3Icon && (
            <Button variant="link" isDestructive onClick={() => setAttributes({ card3Icon: "" })} style={{ display: 'block', marginTop: 8 }}>
              Usuń ikonę główną
            </Button>
          )}
          <p style={{ marginTop: 16 }}>Ikona w prawym górnym rogu</p>
          <MediaUploadCheck>
            <MediaUpload
              onSelect={(media) => setAttributes({ card3TopIcon: media.url })}
              allowedTypes={["image"]}
              value={card3TopIcon}
              render={({ open }) => (
                <Button variant="secondary" onClick={open}>
                  {card3TopIcon ? "Zmień ikonę" : "Wybierz ikonę"}
                </Button>
              )}
            />
          </MediaUploadCheck>
          {card3TopIcon && (
            <Button variant="link" isDestructive onClick={() => setAttributes({ card3TopIcon: "" })} style={{ display: 'block', marginTop: 8 }}>
              Usuń ikonę z rogu
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
        />

        <div className="ekokarty__grid">
          {/* Card 1 */}
          <div className="ekokarty__card">
            {card1TopIcon && (
              <img src={card1TopIcon} className="ekokarty__card-top-icon" alt="" />
            )}
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
            {card2TopIcon && (
              <img src={card2TopIcon} className="ekokarty__card-top-icon" alt="" />
            )}
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
            {card3TopIcon && (
              <img src={card3TopIcon} className="ekokarty__card-top-icon" alt="" />
            )}
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
