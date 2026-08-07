import { useBlockProps, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, TextControl, SelectControl, RangeControl, RadioControl, FormTokenField } from "@wordpress/components";
import { useSelect } from "@wordpress/data";
import { useState } from "@wordpress/element";

export default function Edit({ attributes, setAttributes }) {
    const { mainTitle, subTitle, selectionType, categoryId, productIds, orderBy, limit } = attributes;
    const blockProps = useBlockProps();
    const [draggedIndex, setDraggedIndex] = useState(null);

    const categories = useSelect((select) => {
        return select("core").getEntityRecords("taxonomy", "product_cat", { per_page: -1 });
    }, []);

    const products = useSelect((select) => {
        // Fetch all published products with _embed to get images
        return select("core").getEntityRecords("postType", "product", { per_page: 100, status: "publish", _embed: true });
    }, []);

    const categoryOptions = [
        { label: "Wszystkie kategorie", value: "" },
        ...(categories || []).map((cat) => ({
            label: cat.name,
            value: cat.slug,
        })),
    ];

    const productSuggestions = (products || []).map(p => p.title.rendered);
    
    const handleProductChange = (selectedTitles) => {
        const newIds = selectedTitles.map(title => {
            const product = products.find(p => p.title.rendered === title);
            return product ? product.id : null;
        }).filter(id => id !== null);
        setAttributes({ productIds: newIds });
    };

    const selectedProductTitles = (productIds || []).map(id => {
        const product = (products || []).find(p => p.id === id);
        return product ? product.title.rendered : "";
    }).filter(title => title !== "");

    // Prepare preview products
    let previewProducts = [];
    if (selectionType === 'manual') {
        previewProducts = (productIds || []).map(id => {
            return (products || []).find(p => p.id === id);
        }).filter(Boolean);
    } else {
        // For category view, just show first 'limit' from all products as a dummy preview
        // (proper filtering by category slug would require a separate query)
        previewProducts = (products || []).slice(0, limit);
    }

    const handleDragStart = (e, index) => {
        e.stopPropagation();
        setDraggedIndex(index);
        e.dataTransfer.effectAllowed = "move";
        setTimeout(() => { e.target.style.opacity = '0.5'; }, 0);
    };

    const handleDragEnd = (e) => {
        e.stopPropagation();
        e.target.style.opacity = '1';
        setDraggedIndex(null);
    };

    const handleDragOver = (e, index) => {
        e.preventDefault();
        e.stopPropagation();
        e.dataTransfer.dropEffect = "move";
    };

    const handleDrop = (e, index) => {
        e.preventDefault();
        e.stopPropagation();
        if (draggedIndex === null || draggedIndex === index) return;
        
        const newProductIds = [...productIds];
        const draggedId = newProductIds[draggedIndex];
        
        newProductIds.splice(draggedIndex, 1);
        newProductIds.splice(index, 0, draggedId);
        
        setAttributes({ productIds: newProductIds });
    };

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
                                Możesz zmieniać kolejność łapiąc i przeciągając produkty w podglądzie bloku!
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

            <div style={{ padding: "30px", border: "1px solid #e0e0e0", borderRadius: "10px", backgroundColor: "#fff", boxShadow: "inset 0 0 20px rgba(0,0,0,0.02)" }}>
                <h3 style={{ margin: "0 0 20px 0", fontSize: "24px" }}>
                    <span style={{ color: "#111", fontWeight: "800" }}>{mainTitle} </span>
                    <span style={{ color: "#888", fontWeight: "400" }}>{subTitle}</span>
                </h3>
                
                <div style={{ display: "grid", gridTemplateColumns: "repeat(auto-fill, minmax(200px, 1fr))", gap: "20px" }}>
                    {previewProducts.length === 0 ? (
                        <p style={{ gridColumn: "1 / -1", color: "#999", textAlign: "center", padding: "40px" }}>
                            {products === null ? "Ładowanie produktów..." : "Brak produktów do wyświetlenia. Dodaj produkty w panelu bocznym."}
                        </p>
                    ) : (
                        previewProducts.map((product, index) => {
                            const isManual = selectionType === 'manual' && orderBy === 'menu_order';
                            const imageUrl = product._embedded?.['wp:featuredmedia']?.[0]?.source_url || "";
                            
                            return (
                                <div 
                                    key={product.id + '-' + index}
                                    draggable={isManual}
                                    onDragStart={isManual ? (e) => handleDragStart(e, index) : undefined}
                                    onDragEnd={isManual ? handleDragEnd : undefined}
                                    onDragOver={isManual ? (e) => handleDragOver(e, index) : undefined}
                                    onDrop={isManual ? (e) => handleDrop(e, index) : undefined}
                                    style={{ 
                                        backgroundColor: "#fff", 
                                        borderRadius: "12px", 
                                        overflow: "hidden",
                                        boxShadow: isManual && draggedIndex === index ? "0 10px 20px rgba(0,124,186,0.2)" : "0 4px 10px rgba(0,0,0,0.05)",
                                        cursor: isManual ? "grab" : "default",
                                        border: isManual && draggedIndex === index ? "2px solid #007cba" : "2px solid transparent",
                                        transition: "all 0.2s ease"
                                    }}
                                    onDragEnter={(e) => {
                                        if (isManual && draggedIndex !== null && draggedIndex !== index) {
                                            e.currentTarget.style.transform = "scale(0.95)";
                                        }
                                    }}
                                    onDragLeave={(e) => {
                                        if (isManual) {
                                            e.currentTarget.style.transform = "scale(1)";
                                        }
                                    }}
                                    onMouseDown={(e) => {
                                        // To zapobiega temu, że Gutenberg kradnie zdarzenie myszy
                                        // i próbuje przesuwać CAŁY BLOK zamiast naszego kafelka.
                                        if (isManual) {
                                            e.stopPropagation();
                                        }
                                    }}
                                >
                                    <div style={{ 
                                        height: "200px", 
                                        backgroundColor: "#f5f5f5", 
                                        backgroundImage: imageUrl ? `url(${imageUrl})` : 'none', 
                                        backgroundSize: "cover", 
                                        backgroundPosition: "center",
                                        display: "flex",
                                        alignItems: "center",
                                        justifyContent: "center"
                                    }}>
                                        {!imageUrl && <span style={{color: "#aaa"}}>Brak zdjęcia</span>}
                                    </div>
                                    <div style={{ padding: "15px", fontSize: "14px", fontWeight: "600", textAlign: "center", color: "#333", lineHeight: "1.3" }} dangerouslySetInnerHTML={{ __html: product.title.rendered }} />
                                    
                                    {isManual && (
                                        <div style={{ display: "flex", justifyContent: "space-between", padding: "10px", backgroundColor: "#f9f9f9", borderTop: "1px solid #eee" }}>
                                            <button 
                                                onClick={(e) => {
                                                    e.stopPropagation();
                                                    e.preventDefault();
                                                    if (index > 0) {
                                                        const newIds = [...productIds];
                                                        const temp = newIds[index - 1];
                                                        newIds[index - 1] = newIds[index];
                                                        newIds[index] = temp;
                                                        setAttributes({ productIds: newIds });
                                                    }
                                                }}
                                                disabled={index === 0}
                                                style={{ cursor: index === 0 ? "not-allowed" : "pointer", padding: "5px 10px", border: "1px solid #ccc", borderRadius: "4px", background: "#fff", opacity: index === 0 ? 0.3 : 1 }}
                                                title="Przesuń w lewo"
                                            >
                                                ◀
                                            </button>
                                            <button 
                                                onClick={(e) => {
                                                    e.stopPropagation();
                                                    e.preventDefault();
                                                    if (index < previewProducts.length - 1) {
                                                        const newIds = [...productIds];
                                                        const temp = newIds[index + 1];
                                                        newIds[index + 1] = newIds[index];
                                                        newIds[index] = temp;
                                                        setAttributes({ productIds: newIds });
                                                    }
                                                }}
                                                disabled={index === previewProducts.length - 1}
                                                style={{ cursor: index === previewProducts.length - 1 ? "not-allowed" : "pointer", padding: "5px 10px", border: "1px solid #ccc", borderRadius: "4px", background: "#fff", opacity: index === previewProducts.length - 1 ? 0.3 : 1 }}
                                                title="Przesuń w prawo"
                                            >
                                                ▶
                                            </button>
                                        </div>
                                    )}
                                </div>
                            );
                        })
                    )}
                </div>
                
                {selectionType === 'manual' && orderBy === 'menu_order' && (
                    <div style={{ textAlign: "center", marginTop: "25px", padding: "10px", backgroundColor: "#f0f8ff", borderRadius: "8px", color: "#007cba", fontSize: "14px", fontWeight: "bold" }}>
                        <span style={{marginRight: "8px"}}>👆</span>
                        Chwyć kartę myszką i przeciągnij, aby zmienić kolejność (Drag & Drop)
                    </div>
                )}
            </div>
        </div>
    );
}
