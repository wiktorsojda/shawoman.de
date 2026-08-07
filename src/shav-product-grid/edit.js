import { useBlockProps, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, TextControl, SelectControl, RangeControl, RadioControl, FormTokenField } from "@wordpress/components";
import { useSelect } from "@wordpress/data";

export default function Edit({ attributes, setAttributes }) {
    const { mainTitle, subTitle, selectionType, categoryId, productIds, orderBy, limit } = attributes;
    const blockProps = useBlockProps();

    const categories = useSelect((select) => {
        return select("core").getEntityRecords("taxonomy", "product_cat", { per_page: -1 });
    }, []);

    const products = useSelect((select) => {
        // Fetch all published products for the FormTokenField suggestions
        return select("core").getEntityRecords("postType", "product", { per_page: -1, status: "publish" });
    }, []);

    const categoryOptions = [
        { label: "Wszystkie kategorie", value: "" },
        ...(categories || []).map((cat) => ({
            label: cat.name,
            value: cat.slug,
        })),
    ];

    // Build suggestions for FormTokenField
    const productSuggestions = (products || []).map(p => p.title.rendered);
    
    // Map selected titles back to IDs
    const handleProductChange = (selectedTitles) => {
        const newIds = selectedTitles.map(title => {
            const product = products.find(p => p.title.rendered === title);
            return product ? product.id : null;
        }).filter(id => id !== null);
        setAttributes({ productIds: newIds });
    };

    // Get current selected titles from IDs
    const selectedProductTitles = (productIds || []).map(id => {
        const product = (products || []).find(p => p.id === id);
        return product ? product.title.rendered : "";
    }).filter(title => title !== "");

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
                    
                    <RadioControl
                        label="Wybierz źródło produktów"
                        selected={selectionType}
                        options={[
                            { label: 'Kategoria', value: 'category' },
                            { label: 'Pojedyncze produkty (Ręcznie)', value: 'manual' },
                        ]}
                        onChange={(val) => setAttributes({ selectionType: val })}
                    />

                    {selectionType === 'category' && (
                        <SelectControl
                            label="Kategoria Produktów"
                            value={categoryId}
                            options={categoryOptions}
                            onChange={(val) => setAttributes({ categoryId: val })}
                        />
                    )}

                    {selectionType === 'manual' && (
                        <div style={{ marginBottom: "20px" }}>
                            <FormTokenField
                                label="Szukaj i dodaj produkty"
                                value={selectedProductTitles}
                                suggestions={productSuggestions}
                                onChange={handleProductChange}
                                __experimentalExpandOnFocus={true}
                            />
                            <p style={{ fontSize: "12px", color: "#666" }}>
                                Dodaj produkty w kolejności, w jakiej mają się wyświetlać (wymaga wybrania sortowania "Ręcznie").
                            </p>
                        </div>
                    )}

                    <SelectControl
                        label="Sortuj według"
                        value={orderBy}
                        options={[
                            { label: 'Ręcznie / Kolejność w Menu', value: 'menu_order' },
                            { label: 'Po dacie (Najnowsze)', value: 'date' },
                            { label: 'Po nazwie (A-Z)', value: 'title' },
                            { label: 'Po popularności (Bestsellery)', value: 'popularity' },
                        ]}
                        onChange={(val) => setAttributes({ orderBy: val })}
                    />

                    <RangeControl
                        label="Limit produktów do pokazania"
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
                        <div key={i} style={{ height: "150px", backgroundColor: "#eaeaea", borderRadius: "8px", display: "flex", alignItems: "center", justifyContent: "center", color: "#999", textAlign: "center", padding: "10px" }}>
                            {selectionType === 'manual' && selectedProductTitles[i] ? selectedProductTitles[i] : "Podgląd karty produktu..."}
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
