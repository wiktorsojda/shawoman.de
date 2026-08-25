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
  const { leftImage, leftTitle, rightImage, rightTitle, features } = attributes;
  const [importJson, setImportJson] = useState("");

  const blockProps = useBlockProps({
    className: "porownanieprodukty",
  });

  const updateFeature = (index, key, value) => {
    const newFeatures = [...features];
    newFeatures[index] = { ...newFeatures[index], [key]: value };
    setAttributes({ features: newFeatures });
  };

  const addFeature = () => {
    setAttributes({
      features: [...features, { left: "", center: "", right: "" }],
    });
  };

  const removeFeature = (index) => {
    const newFeatures = features.filter((_, i) => i !== index);
    setAttributes({ features: newFeatures });
  };

  const moveFeature = (index, direction) => {
    if (
      (direction === -1 && index === 0) ||
      (direction === 1 && index === features.length - 1)
    ) {
      return;
    }
    const newFeatures = [...features];
    const temp = newFeatures[index];
    newFeatures[index] = newFeatures[index + direction];
    newFeatures[index + direction] = temp;
    setAttributes({ features: newFeatures });
  };

  return (
    <section {...blockProps}>
      <InspectorControls>
        <PanelBody title="Tłumaczenia AI (JSON)" initialOpen={false}>
          <TextareaControl
            label="Skopiuj ten JSON dla AI"
            value={(() => {
              const data = {
                leftTitle,
                rightTitle,
                features,
              };
              return JSON.stringify(data, null, 2);
            })()}
            readOnly
            rows={8}
            help="Skopiuj i wklej do AI z prośbą o przetłumaczenie samych wartości."
          />
          <TextareaControl
            label="Wklej przetłumaczony JSON"
            value={importJson}
            onChange={(val) => setImportJson(val)}
            rows={8}
            help="Wklej JSON wygenerowany przez AI i kliknij Zastosuj."
          />
          <Button
            isPrimary
            onClick={() => {
              try {
                const parsed = JSON.parse(importJson);
                if (parsed) {
                  const newAttrs = {};
                  if (parsed.leftTitle !== undefined) newAttrs.leftTitle = parsed.leftTitle;
                  if (parsed.rightTitle !== undefined) newAttrs.rightTitle = parsed.rightTitle;
                  if (parsed.features !== undefined && Array.isArray(parsed.features)) {
                    newAttrs.features = parsed.features;
                  }
                  setAttributes(newAttrs);
                  alert("Tłumaczenia zostały zaimportowane pomyślnie.");
                  setImportJson("");
                }
              } catch (e) {
                alert("Błąd: Nieprawidłowy format JSON.");
              }
            }}
          >
            Zastosuj tłumaczenie
          </Button>
        </PanelBody>
      </InspectorControls>

      <div className="porownanieprodukty__inner">
        <div className="porownanieprodukty__header">
          <div className="porownanieprodukty__product porownanieprodukty__product--left">
            <MediaUploadCheck>
              <MediaUpload
                onSelect={(media) => setAttributes({ leftImage: media.url })}
                allowedTypes={["image"]}
                value={leftImage}
                render={({ open }) => (
                  <Button onClick={open} className={leftImage ? "image-button" : "button button-large"}>
                    {leftImage ? (
                      <img src={leftImage} alt="Left Product" className="porownanieprodukty__image" />
                    ) : (
                      "Wybierz zdjęcie lewe"
                    )}
                  </Button>
                )}
              />
            </MediaUploadCheck>
            <RichText
              tagName="h3"
              className="porownanieprodukty__title"
              value={leftTitle}
              onChange={(val) => setAttributes({ leftTitle: val })}
              placeholder="Tytuł produktu lewego..."
            />
          </div>
          <div className="porownanieprodukty__vs">VS</div>
          <div className="porownanieprodukty__product porownanieprodukty__product--right">
            <MediaUploadCheck>
              <MediaUpload
                onSelect={(media) => setAttributes({ rightImage: media.url })}
                allowedTypes={["image"]}
                value={rightImage}
                render={({ open }) => (
                  <Button onClick={open} className={rightImage ? "image-button" : "button button-large"}>
                    {rightImage ? (
                      <img src={rightImage} alt="Right Product" className="porownanieprodukty__image" />
                    ) : (
                      "Wybierz zdjęcie prawe"
                    )}
                  </Button>
                )}
              />
            </MediaUploadCheck>
            <RichText
              tagName="h3"
              className="porownanieprodukty__title"
              value={rightTitle}
              onChange={(val) => setAttributes({ rightTitle: val })}
              placeholder="Tytuł produktu prawego..."
            />
          </div>
        </div>

        <div className="porownanieprodukty__features" style={{ marginTop: '40px' }}>
          {(features || []).map((feature, index) => (
            <div
              key={index}
              className="porownanieprodukty__row"
              style={{
                display: "grid",
                gridTemplateColumns: "1fr 1fr 1fr auto",
                gap: "10px",
                marginBottom: "10px",
                alignItems: "center",
                background: "#fff",
                padding: "10px",
                borderRadius: "8px",
              }}
            >
              <RichText
                tagName="div"
                className="porownanieprodukty__feature porownanieprodukty__feature--left"
                value={feature.left}
                onChange={(val) => updateFeature(index, "left", val)}
                placeholder="Cecha (Shav Woman)..."
              />
              <RichText
                tagName="div"
                className="porownanieprodukty__label"
                value={feature.center}
                onChange={(val) => updateFeature(index, "center", val)}
                placeholder="Nagłówek cechy..."
                style={{ textAlign: 'center', fontWeight: 'bold' }}
              />
              <RichText
                tagName="div"
                className="porownanieprodukty__feature porownanieprodukty__feature--right"
                value={feature.right}
                onChange={(val) => updateFeature(index, "right", val)}
                placeholder="Cecha (Jednorazówka)..."
              />
              <div className="porownanieprodukty__row-actions" style={{ display: 'flex', flexDirection: 'column' }}>
                <Button isSmall onClick={() => moveFeature(index, -1)} disabled={index === 0}>
                  ↑
                </Button>
                <Button isSmall onClick={() => moveFeature(index, 1)} disabled={index === features.length - 1}>
                  ↓
                </Button>
                <Button isDestructive isSmall onClick={() => removeFeature(index)}>
                  Usuń
                </Button>
              </div>
            </div>
          ))}
          <Button isSecondary onClick={addFeature} style={{ marginTop: '10px' }}>
            Dodaj kolejną cechę
          </Button>
        </div>
      </div>
    </section>
  );
}
