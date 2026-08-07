import { useBlockProps, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, TextControl, SelectControl, RangeControl, RadioControl, FormTokenField } from "@wordpress/components";
import { useSelect } from "@wordpress/data";
import { useState, useRef } from "@wordpress/element";

export default function Edit({ attributes, setAttributes }) {
    const { mainTitle, subTitle, selectionType, categoryId, productIds, customCategoryOrder, orderBy, limit } = attributes;
    const blockProps = useBlockProps();
    const draggedIndex = useRef(null);

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
        // For category view
        if (orderBy === 'menu_order' && customCategoryOrder && customCategoryOrder.length > 0) {
            previewProducts = customCategoryOrder.map(id => {
                return (products || []).find(p => p.id === id);
            }).filter(Boolean);
        } else {
            const selectedCategory = (categories || []).find(cat => cat.slug === categoryId);
            const selectedCategoryId = selectedCategory ? selectedCategory.id : null;
            previewProducts = (products || []).filter(p => {
                if (!selectedCategoryId) return true;
                return p.categories && p.categories.includes(selectedCategoryId);
            }).slice(0, limit);
            
            // Fallback for WooCommerce products REST response if 'categories' isn't natively populated but 'product_cat' might be.
            if (previewProducts.length === 0 && selectedCategoryId) {
                previewProducts = (products || []).filter(p => {
                    // Czasami REST dla produktów WC przetrzymuje kategorie w innej strukturze.
                    // Spróbujmy znaleźć cokolwiek:
                    if (p.product_cat && p.product_cat.includes(selectedCategoryId)) return true;
                    return false; // W najgorszym razie podgląd nie pofiltruje dokładnie
                }).slice(0, limit);
            }
            
            // Jeżeli wciąż pusty (brak filtru), weź po prostu produkty.
            if (previewProducts.length === 0 && (products || []).length > 0) {
                 previewProducts = (products || []).slice(0, limit);
            }
        }
    }

    const handleDragStart = (e, index) => {
        e.stopPropagation();
        draggedIndex.current = index;
        e.dataTransfer.effectAllowed = "move";
        setTimeout(() => { 
            if(e.currentTarget) e.currentTarget.style.opacity = '0.5'; 
        }, 0);
    };

    const handleDragEnd = (e) => {
        e.stopPropagation();
        if(e.currentTarget) e.currentTarget.style.opacity = '1';
        draggedIndex.current = null;
    };

    const handleDragOver = (e, index) => {
        e.preventDefault();
        e.stopPropagation();
        e.dataTransfer.dropEffect = "move";
    };

    const handleDrop = (e, index) => {
        e.preventDefault();
        e.stopPropagation();
        if(e.currentTarget) e.currentTarget.style.transform = 'scale(1)';
        if (draggedIndex.current === null || draggedIndex.current === index) return;
        
        let newIds = [];
        if (selectionType === 'manual') {
            newIds = [...productIds];
        } else {
            newIds = customCategoryOrder && customCategoryOrder.length > 0 
                ? [...customCategoryOrder] 
                : previewProducts.map(p => p.id);
        }
        
        const draggedId = newIds[draggedIndex.current];
        newIds.splice(draggedIndex.current, 1);
        newIds.splice(index, 0, draggedId);
        
        if (selectionType === 'manual') {
            setAttributes({ productIds: newIds });
        } else {
            setAttributes({ customCategoryOrder: newIds });
        }
    };

    const handleMoveButton = (e, index, direction) => {
        e.stopPropagation();
        e.preventDefault();
        
        let newIds = [];
        if (selectionType === 'manual') {
            newIds = [...productIds];
        } else {
            newIds = customCategoryOrder && customCategoryOrder.length > 0 
                ? [...customCategoryOrder] 
                : previewProducts.map(p => p.id);
        }
        
        if (direction === 'left' && index > 0) {
            const temp = newIds[index - 1];
            newIds[index - 1] = newIds[index];
            newIds[index] = temp;
        } else if (direction === 'right' && index < newIds.length - 1) {
            const temp = newIds[index + 1];
            newIds[index + 1] = newIds[index];
            newIds[index] = temp;
        }
        
        if (selectionType === 'manual') {
            setAttributes({ productIds: newIds });
        } else {
            setAttributes({ customCategoryOrder: newIds });
        }
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
                            onChange={(val) => setAttributes({ categoryId: val, customCategoryOrder: [] })}
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
                            const isManual = orderBy === 'menu_order';
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
                                        boxShadow: isManual && draggedIndex.current === index ? "0 10px 20px rgba(0,124,186,0.2)" : "0 4px 10px rgba(0,0,0,0.05)",
                                        cursor: isManual ? "grab" : "default",
                                        border: isManual && draggedIndex.current === index ? "2px solid #007cba" : "2px solid transparent",
                                        transition: "all 0.2s ease",
                                        display: "flex",
                                        flexDirection: "column",
                                        height: "100%"
                                    }}
                                    onDragEnter={(e) => {
                                        e.preventDefault();
                                        if (isManual && draggedIndex.current !== null && draggedIndex.current !== index) {
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
                                        backgroundImage: imageUrl ? `url(${imageUrl})` : "none", 
                                        backgroundSize: "cover", 
                                        backgroundPosition: "center",
                                        display: "flex",
                                        alignItems: "center",
                                        justifyContent: "center",
                                        flexShrink: 0
                                    }}>
                                        {!imageUrl && <span style={{color: "#aaa"}}>Brak zdjęcia</span>}
                                    </div>
                                    <div style={{ padding: "15px", fontSize: "14px", fontWeight: "600", textAlign: "center", color: "#333", lineHeight: "1.3", flexGrow: 1 }} dangerouslySetInnerHTML={{ __html: product.title.rendered }} />
                                    
                                    {isManual && (
                                        <div style={{ display: "flex", justifyContent: "space-between", padding: "10px", backgroundColor: "#f9f9f9", borderTop: "1px solid #eee", marginTop: "auto" }}>
                                            <button 
                                                onClick={(e) => handleMoveButton(e, index, 'left')}
                                                disabled={index === 0}
                                                style={{ cursor: index === 0 ? "not-allowed" : "pointer", padding: "5px 10px", border: "1px solid #ccc", borderRadius: "4px", background: "#fff", opacity: index === 0 ? 0.3 : 1 }}
                                                title="Przesuń w lewo"
                                            >
                                                ◀
                                            </button>
                                            <button 
                                                onClick={(e) => handleMoveButton(e, index, 'right')}
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
                
                {orderBy === 'menu_order' && (
                    <div style={{ textAlign: "center", marginTop: "25px", padding: "10px", backgroundColor: "#f0f8ff", borderRadius: "8px", color: "#007cba", fontSize: "14px", fontWeight: "bold" }}>
                        <span style={{marginRight: "8px"}}>👆</span>
                        Chwyć kartę myszką i przeciągnij, aby zmienić kolejność (Drag & Drop)
                    </div>
                )}
            </div>
        </div>
    );
}
