import { useBlockProps, InspectorControls, RichText } from "@wordpress/block-editor"
import { PanelBody, TextareaControl, Button } from "@wordpress/components"
import { useState } from "@wordpress/element"

export default function Edit(props) {
  const { attributes, setAttributes } = props
  const { title } = attributes

  const blockProps = useBlockProps({
    className: "opinieproduktowe-editor-preview"
  })

  // --- Tłumaczenia AI (JSON) ---
  const [jsonInput, setJsonInput] = useState("")
  const [jsonError, setJsonError] = useState("")

  const generateJson = () => {
    const data = { title }
    setJsonInput(JSON.stringify(data, null, 2))
    setJsonError("")
  }

  const applyJson = () => {
    try {
      const parsed = JSON.parse(jsonInput)
      setAttributes({
        title: parsed.title || title
      })
      setJsonError("Atrybuty zaktualizowane pomyślnie!")
      setTimeout(() => setJsonError(""), 3000)
    } catch (e) {
      setJsonError("Błąd parsowania JSON. Sprawdź format.")
    }
  }

  return (
    <div {...blockProps}>
      <InspectorControls>
        <PanelBody title="Tłumaczenia AI (JSON)" initialOpen={false}>
          <p style={{ fontSize: "12px", marginBottom: "10px" }}>
            Wygeneruj strukturę atrybutów do przetłumaczenia przez AI. Wklej przetłumaczony JSON z powrotem.
          </p>
          <Button variant="secondary" onClick={generateJson} style={{ marginBottom: "10px" }}>
            Wygeneruj JSON
          </Button>
          <TextareaControl
            value={jsonInput}
            onChange={(value) => setJsonInput(value)}
            rows={8}
            help={jsonError}
            style={{ fontFamily: "monospace", fontSize: "11px" }}
          />
          <Button variant="primary" onClick={applyJson} disabled={!jsonInput}>
            Zastosuj tłumaczenie
          </Button>
        </PanelBody>
      </InspectorControls>

      <div style={{ padding: "20px", border: "2px dashed #ccc", textAlign: "center", backgroundColor: "#f9f9f9" }}>
        <h3 style={{ margin: "0 0 10px 0", color: "#666" }}>[Blok: Opinie Produktowe]</h3>
        <RichText
          tagName="h4"
          value={title}
          onChange={(value) => setAttributes({ title: value })}
          placeholder="Tytuł nad opiniami..."
          style={{ marginBottom: "15px", color: "#333" }}
        />
        <p style={{ color: "#999", fontSize: "13px", margin: 0 }}>
          Na froncie (strona sklepu) w tym miejscu zostaną automatycznie wyrenderowane oceny klientów oraz natywne opinie WooCommerce przypisane do tego produktu.
        </p>
      </div>
    </div>
  )
}
