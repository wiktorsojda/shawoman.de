import { useBlockProps, InspectorControls, MediaUpload, MediaUploadCheck, RichText } from "@wordpress/block-editor";
import { PanelBody, Button, TextControl } from "@wordpress/components";

export default function Edit({ attributes, setAttributes }) {
    const { imageDesktop, imageMobile, text1, text2, linkURL } = attributes;
    const blockProps = useBlockProps({ className: "shop-banner-image-editor" });

    return (
        <div {...blockProps}>
            <InspectorControls>
                <PanelBody title="Ustawienia Banera" initialOpen={true}>
                    <p>Obrazek Desktop</p>
                    <MediaUploadCheck>
                        <MediaUpload
                            onSelect={(media) => setAttributes({ imageDesktop: media.url })}
                            allowedTypes={["image"]}
                            render={({ open }) => (
                                <Button variant="secondary" onClick={open} style={{ marginBottom: "10px", width: "100%", justifyContent: "center" }}>
                                    {imageDesktop ? "Zmień obrazek Desktop" : "Wybierz obrazek Desktop"}
                                </Button>
                            )}
                        />
                    </MediaUploadCheck>
                    {imageDesktop && <img src={imageDesktop} alt="Desktop Preview" style={{ width: "100%", marginBottom: "20px" }} />}

                    <p>Obrazek Mobile</p>
                    <MediaUploadCheck>
                        <MediaUpload
                            onSelect={(media) => setAttributes({ imageMobile: media.url })}
                            allowedTypes={["image"]}
                            render={({ open }) => (
                                <Button variant="secondary" onClick={open} style={{ marginBottom: "10px", width: "100%", justifyContent: "center" }}>
                                    {imageMobile ? "Zmień obrazek Mobile" : "Wybierz obrazek Mobile"}
                                </Button>
                            )}
                        />
                    </MediaUploadCheck>
                    {imageMobile && <img src={imageMobile} alt="Mobile Preview" style={{ width: "100%", marginBottom: "20px" }} />}

                    <TextControl
                        label="Link URL (Opcjonalnie)"
                        value={linkURL}
                        onChange={(val) => setAttributes({ linkURL: val })}
                        help="Jeśli dodasz link, cały baner będzie klikalny"
                        style={{ marginTop: "20px" }}
                    />
                </PanelBody>
            </InspectorControls>

            <div className="shop-banner-image" style={{ position: "relative", border: "1px dashed #ccc", padding: "10px" }}>
                {imageDesktop && (
                    <img className="shop-banner-image-desktop" src={imageDesktop} alt="" style={{ width: "100%", height: "auto", display: "block" }} />
                )}
                {!imageDesktop && <p style={{ textAlign: "center", padding: "20px" }}>Brak obrazka desktop (wymagany)</p>}
                
                <div className="shop-banner-textcontainer" style={{ position: "absolute", top: 0, left: 0, right: 0, bottom: 0, pointerEvents: "none" }}>
                    <RichText
                        tagName="div"
                        className="banner-text banner-text-1"
                        value={text1}
                        onChange={(val) => setAttributes({ text1: val })}
                        placeholder="Wpisz główny tekst (opcjonalnie)"
                        style={{ pointerEvents: "auto" }}
                    />
                    <RichText
                        tagName="div"
                        className="banner-text banner-text-2"
                        value={text2}
                        onChange={(val) => setAttributes({ text2: val })}
                        placeholder="Wpisz dodatkowy tekst (opcjonalnie)"
                        style={{ pointerEvents: "auto" }}
                    />
                </div>
            </div>
        </div>
    );
}
