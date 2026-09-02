import { __ } from '@wordpress/i18n';
import { useBlockProps, RichText, InspectorControls, MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import { PanelBody, Button, TextControl, TextareaControl, Modal } from '@wordpress/components';
import { useState } from '@wordpress/element';
import ResponsiveSpacingControl from '../components/ResponsiveSpacingControl';
import './style.scss';

export default function Edit({ attributes, setAttributes }) {
  const { title, experts } = attributes;
  const [isJsonModalOpen, setIsJsonModalOpen] = useState(false);
  const [jsonInput, setJsonInput] = useState('');

  const blockProps = useBlockProps({
    className: 'eksperci'
  });

  const updateExpert = (index, key, value) => {
    const newExperts = [...experts];
    newExperts[index] = { ...newExperts[index], [key]: value };
    setAttributes({ experts: newExperts });
  };

  const addExpert = () => {
    setAttributes({
      experts: [
        ...experts,
        { image: '', name: 'Nowy Ekspert', role: 'Stanowisko', desc1: 'Opis', desc2: '"Cytat"' }
      ]
    });
  };

  const removeExpert = (index) => {
    const newExperts = experts.filter((_, i) => i !== index);
    setAttributes({ experts: newExperts });
  };

  // Obsługa JSON (standard dla tłumaczeń AI)
  const openJsonModal = () => {
    const textData = {
      title,
      experts: experts.map(e => ({ name: e.name, role: e.role, desc1: e.desc1, desc2: e.desc2 }))
    };
    setJsonInput(JSON.stringify(textData, null, 2));
    setIsJsonModalOpen(true);
  };

  const applyJson = () => {
    try {
      const parsed = JSON.parse(jsonInput);
      if (parsed.title) setAttributes({ title: parsed.title });
      
      if (parsed.experts && Array.isArray(parsed.experts)) {
        const newExperts = experts.map((e, index) => {
          if (parsed.experts[index]) {
            return {
              ...e,
              name: parsed.experts[index].name || e.name,
              role: parsed.experts[index].role || e.role,
              desc1: parsed.experts[index].desc1 || e.desc1,
              desc2: parsed.experts[index].desc2 || e.desc2
            };
          }
          return e;
        });
        setAttributes({ experts: newExperts });
      }
      setIsJsonModalOpen(false);
    } catch (e) {
      alert('Błąd JSON: ' + e.message);
    }
  };

  return (
    <>
      <InspectorControls>
        <ResponsiveSpacingControl attributes={attributes} setAttributes={setAttributes} />

        <PanelBody title="Zarządzaj Ekspertami">
          {experts.map((expert, index) => (
            <div key={index} style={{ border: '1px solid #ccc', padding: '10px', marginBottom: '10px' }}>
              <p><strong>Ekspert {index + 1}</strong></p>
              
              <MediaUploadCheck>
                <MediaUpload
                  onSelect={(media) => updateExpert(index, 'image', media.url)}
                  allowedTypes={['image']}
                  render={({ open }) => (
                    <div style={{ marginBottom: '10px' }}>
                      <Button variant="secondary" onClick={open}>
                        {expert.image ? 'Zmień zdjęcie' : 'Wybierz zdjęcie'}
                      </Button>
                      {expert.image && (
                        <div style={{ marginTop: '10px' }}>
                          <img src={expert.image} style={{ width: '100%', maxHeight: '100px', objectFit: 'contain' }} />
                          <Button variant="link" isDestructive onClick={() => updateExpert(index, 'image', '')}>
                            Usuń zdjęcie
                          </Button>
                        </div>
                      )}
                    </div>
                  )}
                />
              </MediaUploadCheck>
              
              <TextControl
                label="Imię"
                value={expert.name}
                onChange={(val) => updateExpert(index, 'name', val)}
              />
              <TextControl
                label="Stanowisko"
                value={expert.role}
                onChange={(val) => updateExpert(index, 'role', val)}
              />
              <TextareaControl
                label="Opis 1 (Zwykły)"
                value={expert.desc1}
                onChange={(val) => updateExpert(index, 'desc1', val)}
              />
              <TextareaControl
                label="Opis 2 (Cytat)"
                value={expert.desc2}
                onChange={(val) => updateExpert(index, 'desc2', val)}
              />

              <Button isDestructive onClick={() => removeExpert(index)} style={{ marginTop: '10px' }}>
                Usuń tego eksperta
              </Button>
            </div>
          ))}
          <Button variant="primary" onClick={addExpert} style={{ width: '100%', justifyContent: 'center' }}>
            + Dodaj eksperta
          </Button>
        </PanelBody>

        <PanelBody title="Tłumaczenia AI (JSON)">
          <p style={{ fontSize: '12px', color: '#666' }}>
            Wygeneruj JSON, przetłumacz w AI (np. na DE) i wklej z powrotem.
          </p>
          <Button variant="secondary" onClick={openJsonModal} style={{ width: '100%', justifyContent: 'center' }}>
            Otwórz panel JSON
          </Button>
        </PanelBody>
      </InspectorControls>

      {isJsonModalOpen && (
        <Modal title="Tłumaczenia AI (JSON)" onRequestClose={() => setIsJsonModalOpen(false)}>
          <TextareaControl
            value={jsonInput}
            onChange={(val) => setJsonInput(val)}
            rows={15}
          />
          <div style={{ display: 'flex', gap: '10px', justifyContent: 'flex-end' }}>
            <Button variant="secondary" onClick={() => setIsJsonModalOpen(false)}>Anuluj</Button>
            <Button variant="primary" onClick={applyJson}>Zastosuj zmiany</Button>
          </div>
        </Modal>
      )}

      <section {...blockProps}>
        <div className="eksperci__inner">
          <RichText
            tagName="h2"
            className="eksperci__title"
            value={title}
            onChange={(val) => setAttributes({ title: val })}
            placeholder="Wpisz nagłówek..."
          />
          
          <div className="eksperci__grid">
            {experts.map((expert, index) => (
              <div key={index} className="eksperci__card">
                <div className="eksperci__header">
                  {expert.image ? (
                    <img src={expert.image} className="eksperci__avatar" alt={expert.name} />
                  ) : (
                    <div className="eksperci__avatar eksperci__avatar--placeholder" />
                  )}
                  <div className="eksperci__info">
                    <div className="eksperci__name">{expert.name}</div>
                    <div className="eksperci__role">{expert.role}</div>
                  </div>
                </div>
                
                <div className="eksperci__desc1">{expert.desc1}</div>
                <div className="eksperci__desc2">{expert.desc2}</div>
              </div>
            ))}
          </div>
        </div>
      </section>
    </>
  );
}
