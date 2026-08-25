import { InspectorControls, RichText, MediaUpload, MediaUploadCheck, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, Button, TextareaControl } from '@wordpress/components';
import { useState } from '@wordpress/element';

export default function Edit({ attributes, setAttributes }) {
  const { title, description, steps } = attributes;
  const [jsonInput, setJsonInput] = useState('');
  const blockProps = useBlockProps({ className: 'jakuzywac' });

  const updateStep = (index, key, value) => {
    const newSteps = [...steps];
    newSteps[index] = { ...newSteps[index], [key]: value };
    setAttributes({ steps: newSteps });
  };

  const getJsonForAi = () => {
    const data = {
      title,
      description,
    };
    return JSON.stringify(data, null, 2);
  };

  const applyAiJson = () => {
    try {
      const data = JSON.parse(jsonInput);
      if (data) {
        const updates = {};
        if (data.title !== undefined) updates.title = data.title;
        if (data.description !== undefined) updates.description = data.description;
        setAttributes(updates);
        alert('Tłumaczenia zostały zaimportowane pomyślnie.');
        setJsonInput('');
      }
    } catch (e) {
      alert('Błąd: Nieprawidłowy format JSON.');
    }
  };

  return (
    <section {...blockProps}>
      <InspectorControls>
        <PanelBody title="Tłumaczenia AI (JSON)" initialOpen={false}>
          <TextareaControl
            label="Skopiuj ten JSON dla AI"
            value={getJsonForAi()}
            readOnly
            rows={8}
            help="Skopiuj i wklej do AI z prośbą o przetłumaczenie samych wartości."
          />
          <TextareaControl
            label="Wklej przetłumaczony JSON"
            value={jsonInput}
            onChange={(val) => setJsonInput(val)}
            rows={8}
            help="Wklej JSON wygenerowany przez AI i kliknij Zastosuj."
          />
          <Button isPrimary onClick={applyAiJson}>
            Zastosuj tłumaczenie
          </Button>
        </PanelBody>
      </InspectorControls>

      <div className="jakuzywac__inner">
        <div className="jakuzywac__content">
          <RichText
            tagName="h2"
            className="jakuzywac__title"
            value={title}
            onChange={(val) => setAttributes({ title: val })}
            placeholder="Wpisz nagłówek..."
          />
          <RichText
            tagName="div"
            className="jakuzywac__desc"
            value={description}
            onChange={(val) => setAttributes({ description: val })}
            placeholder="Wpisz tekst opisu (np. lista wypunktowana)..."
          />
        </div>

        <div className="jakuzywac__gallery">
          {(steps || []).map((step, index) => (
            <figure key={index} className="jakuzywac__step">
              <div className="jakuzywac__image-wrapper" style={{ display: 'flex', flexDirection: 'column', gap: '10px' }}>
                <MediaUploadCheck>
                  <MediaUpload
                    onSelect={(media) => updateStep(index, 'image', media.url)}
                    allowedTypes={['image']}
                    value={step.image}
                    render={({ open }) => (
                      <Button
                        onClick={open}
                        className={step.image ? 'image-button' : 'button button-large'}
                        style={step.image ? { padding: 0, border: 'none', background: 'transparent' } : {}}
                      >
                        {step.image ? (
                          <img
                            src={step.image}
                            alt={`Krok Desktop ${index + 1}`}
                            style={{ width: '100%', height: 'auto', display: 'block' }}
                          />
                        ) : (
                          `Wybierz zdjęcie Desktop ${index + 1}`
                        )}
                      </Button>
                    )}
                  />
                </MediaUploadCheck>
                
                <MediaUploadCheck>
                  <MediaUpload
                    onSelect={(media) => updateStep(index, 'imageMobile', media.url)}
                    allowedTypes={['image']}
                    value={step.imageMobile}
                    render={({ open }) => (
                      <Button
                        onClick={open}
                        className={step.imageMobile ? 'image-button' : 'button button-large'}
                        style={step.imageMobile ? { padding: 0, border: 'none', background: 'transparent' } : {}}
                      >
                        {step.imageMobile ? (
                          <img
                            src={step.imageMobile}
                            alt={`Krok Mobile ${index + 1}`}
                            style={{ width: '50%', height: 'auto', display: 'block', margin: '0 auto' }}
                          />
                        ) : (
                          `Wybierz zdjęcie Mobile ${index + 1}`
                        )}
                      </Button>
                    )}
                  />
                </MediaUploadCheck>
              </div>
            </figure>
          ))}
        </div>
      </div>
    </section>
  );
}
