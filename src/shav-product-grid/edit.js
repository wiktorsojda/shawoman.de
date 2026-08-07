import { useBlockProps, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, TextControl, SelectControl, RangeControl } from "@wordpress/components";
import { useSelect } from "@wordpress/data";

export default function Edit({ attributes, setAttributes }) {
    const { mainTitle, subTitle, categoryId, limit } = attributes;
    const blockProps = useBlockProps();

    const categories = useSelect((select) => {
        return select("core").getEntityRecords("taxonomy", "product_cat", { per_page: -1 });
    }, []);

    const categoryOptions = [
        { label: "Wszystkie kategorie", value: "" },
        ...(categories || []).map((cat) => ({
            label: cat.name,
            value: cat.slug,
        })),
    ];

    return (
        <div {...blockProps}>
            <InspectorControls>
                <PanelBody title="Ustawienia Siatki Produktów" initialOpen={true}>
                    <TextControl
                        label="Tytuł główny"
                        value={mainTitle}
                        onChange={(val) => setAttributes({ mainTitle: val })}
                    />
                    <TextControl
                        label="Podtytuł (Szary, marka)"
                        value={subTitle}
                        onChange={(val) => setAttributes({ subTitle: val })}
                    />
                    <SelectControl
                        label="Kategoria Produktów"
                        value={categoryId}
                        options={categoryOptions}
                        onChange={(val) => setAttributes({ categoryId: val })}
                    />
                    <RangeControl
                        label="Liczba produktów"
                        value={limit}
                        onChange={(val) => setAttributes({ limit: val })}
                        min={1}
                        max={24}
                    />
                </PanelBody>
            </InspectorControls>

            <div style={{ padding: "20px", border: "2px dashed #ccc", backgroundColor: "#fafafa" }}>
                <h3 style={{ margin: "0 0 10px 0" }}>
                    <span style={{ color: "#000", fontWeight: "bold" }}>{mainTitle} </span>
                    <span style={{ color: "#666", fontWeight: "normal" }}>{subTitle}</span>
                </h3>
                <div style={{ display: "grid", gridTemplateColumns: "1fr 1fr 1fr", gap: "10px", marginTop: "15px" }}>
                    {[...Array(Math.min(limit, 3))].map((_, i) => (
                        <div key={i} style={{ height: "150px", backgroundColor: "#eaeaea", borderRadius: "8px", display: "flex", alignItems: "center", justifyContent: "center", color: "#999" }}>
                            Podgląd karty produktu...
                        </div>
                    ))}
                </div>
                <p style={{ textAlign: "center", marginTop: "15px", color: "#666", fontSize: "12px" }}>
                    <em>Uwaga: Ten blok ładuje dynamicznie oryginalne karty produktów z motywu na stronie (frontend).</em>
                </p>
            </div>
        </div>
    );
}
