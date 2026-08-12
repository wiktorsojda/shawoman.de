import { useBlockProps, InspectorControls, MediaUpload, MediaUploadCheck } from "@wordpress/block-editor"
import { PanelBody, Button, TextControl, TextareaControl } from "@wordpress/components"
import { useState } from "@wordpress/element"

export default function Edit(props) {
  const { attributes, setAttributes } = props
  const { slides } = attributes

  const blockProps = useBlockProps({
    className: "produktowaslider-editor-preview"
  })

  // --- Tłumaczenia AI (JSON) ---
  const [jsonInput, setJsonInput] = useState("")
  const [jsonError, setJsonError] = useState("")

  const generateJson = () => {
    // We want to translate only texts (like alt attributes)
    const data = { slides }
    setJsonInput(JSON.stringify(data, null, 2))
    setJsonError("")
  }

  const applyJson = () => {
    try {
      const parsed = JSON.parse(jsonInput)
      if (parsed.slides && Array.isArray(parsed.slides)) {
        setAttributes({ slides: parsed.slides })
        setJsonError("Atrybuty zaktualizowane pomyślnie!")
      } else {
        setJsonError("Brak tablicy 'slides' w JSON.")
      }
      setTimeout(() => setJsonError(""), 3000)
    } catch (e) {
      setJsonError("Błąd parsowania JSON. Sprawdź format.")
    }
  }

  // --- Zarządzanie slajdami ---
  const addSlide = () => {
    const newSlides = [...slides, { desktopImage: "", mobileImage: "", altText: "" }]
    setAttributes({ slides: newSlides })
  }

  const removeSlide = (index) => {
    const newSlides = [...slides]
    newSlides.splice(index, 1)
    setAttributes({ slides: newSlides })
  }

  const updateSlide = (index, key, value) => {
    const newSlides = [...slides]
    newSlides[index][key] = value
    setAttributes({ slides: newSlides })
  }

  return (
    <div {...blockProps}>
      <InspectorControls>
        <PanelBody title="Slajdy (Desktop & Mobile)" initialOpen={true}>
          {slides.map((slide, index) => (
            <div key={index} style={{ marginBottom: "20px", padding: "10px", border: "1px solid #ddd", borderRadius: "4px" }}>
              <p style={{ fontWeight: "bold", margin: "0 0 10px 0" }}>Slajd #{index + 1}</p>
              
              <MediaUploadCheck>
                <MediaUpload
                  onSelect={(media) => updateSlide(index, "desktopImage", media.url)}
                  allowedTypes={["image"]}
                  render={({ open }) => (
                    <Button variant={slide.desktopImage ? "secondary" : "primary"} onClick={open} style={{ width: "100%", marginBottom: "10px", justifyContent: "center" }}>
                      {slide.desktopImage ? "Zmień zdjęcie Desktop" : "Wybierz zdjęcie Desktop"}
                    </Button>
                  )}
                />
              </MediaUploadCheck>
              {slide.desktopImage && (
                <img src={slide.desktopImage} alt="Desktop Preview" style={{ width: "100%", height: "auto", marginBottom: "10px", borderRadius: "4px" }} />
              )}

              <MediaUploadCheck>
                <MediaUpload
                  onSelect={(media) => updateSlide(index, "mobileImage", media.url)}
                  allowedTypes={["image"]}
                  render={({ open }) => (
                    <Button variant={slide.mobileImage ? "secondary" : "primary"} onClick={open} style={{ width: "100%", marginBottom: "10px", justifyContent: "center" }}>
                      {slide.mobileImage ? "Zmień zdjęcie Mobile" : "Wybierz zdjęcie Mobile"}
                    </Button>
                  )}
                />
              </MediaUploadCheck>
              {slide.mobileImage && (
                <img src={slide.mobileImage} alt="Mobile Preview" style={{ width: "100%", height: "auto", marginBottom: "10px", borderRadius: "4px" }} />
              )}

              <TextControl
                label="Tekst alternatywny (Alt) / Tytuł obrazka"
                value={slide.altText}
                onChange={(value) => updateSlide(index, "altText", value)}
              />

              <Button isDestructive onClick={() => removeSlide(index)} style={{ marginTop: "10px" }}>
                Usuń ten slajd
              </Button>
            </div>
          ))}
          <Button variant="primary" onClick={addSlide} style={{ width: "100%", justifyContent: "center" }}>
            + Dodaj kolejny slajd
          </Button>
        </PanelBody>

        <PanelBody title="Tłumaczenia AI (JSON)" initialOpen={false}>
          <p style={{ fontSize: "12px", marginBottom: "10px" }}>
            Wygeneruj strukturę slajdów do przetłumaczenia przez AI (alt texty). Wklej przetłumaczony JSON z powrotem.
          </p>
          <Button variant="secondary" onClick={generateJson} style={{ marginBottom: "10px" }}>
            Wygeneruj JSON
          </Button>
          <TextareaControl
            value={jsonInput}
            onChange={(value) => setJsonInput(value)}
            rows={12}
            help={jsonError}
            style={{ fontFamily: "monospace", fontSize: "11px" }}
          />
          <Button variant="primary" onClick={applyJson} disabled={!jsonInput}>
            Zastosuj tłumaczenie
          </Button>
        </PanelBody>
      </InspectorControls>

      <div style={{ padding: "20px", border: "2px dashed #ccc", textAlign: "center", backgroundColor: "#f9f9f9" }}>
        <h3 style={{ margin: "0 0 10px 0", color: "#666" }}>[Blok: Slider Produktowy]</h3>
        <p style={{ color: "#999", fontSize: "13px", margin: 0 }}>
          Liczba slajdów: {slides.length}.<br/>
          Konfiguruj slajdy w panelu po prawej stronie. Na żywo slider pojawi się po zapisaniu na stronie docelowej.
        </p>
      </div>
    </div>
  )
}
