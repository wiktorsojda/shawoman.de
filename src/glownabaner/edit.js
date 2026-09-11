import {
  useBlockProps, RichText, InspectorControls, MediaUpload, MediaUploadCheck, PanelColorSettings
} from "@wordpress/block-editor";
import { PanelBody, Button, TextControl, ToggleControl, TextareaControl, SelectControl, RangeControl } from "@wordpress/components";
import { useState } from "@wordpress/element";
import ResponsiveSpacingControl from "../components/ResponsiveSpacingControl";

const AVATAR_NUMS = [1, 2, 3, 4, 5];

export default function Edit({ attributes, setAttributes }) {
  const a = attributes;
  const blockProps = useBlockProps({ 
    className: `glownabaner glownabaner--x-${a.glassPositionX || 'left'} glownabaner--y-${a.glassPositionY || 'middle'} glownabaner--align-${a.textAlign || 'left'}`,
    style: { '--content-width': a.glassWidth ? `${a.glassWidth}%` : '60%' }
  });
  const [importJson, setImportJson] = useState("");

  return (
    <div {...blockProps}>
      <InspectorControls>
        <ResponsiveSpacingControl attributes={attributes} setAttributes={setAttributes} />
        <PanelBody title="Tłumaczenia AI (JSON)" initialOpen={false}>
          <TextareaControl
            label="Skopiuj ten JSON dla AI"
            value={(() => {
              const data = {
                ratingCount: a.ratingCount || '',
                ratingScore: a.ratingScore || '',
                bannerTitle: a.bannerTitle || '',
                bannerTitleAccent: a.bannerTitleAccent || '',
                badgeText: a.badgeText || '',
                bannerSubtitle: a.bannerSubtitle || '',
                bannerCtaLabel: a.bannerCtaLabel || '',
                bannerCtaLabelMobile: a.bannerCtaLabelMobile || ''
              };
              return JSON.stringify(data, null, 2);
            })()}
            readOnly
            rows={10}
            help="Skopiuj i wklej do AI z prośbą o przetłumaczenie samych wartości."
          />
          <TextareaControl
            label="Wklej przetłumaczony JSON"
            value={importJson}
            onChange={setImportJson}
            rows={10}
          />
          <Button variant="primary" onClick={() => {
            try {
              const parsed = JSON.parse(importJson);
              const updates = {};
              if (parsed.ratingCount !== undefined) updates.ratingCount = parsed.ratingCount;
              if (parsed.ratingScore !== undefined) updates.ratingScore = parsed.ratingScore;
              if (parsed.bannerTitle !== undefined) updates.bannerTitle = parsed.bannerTitle;
              if (parsed.bannerTitleAccent !== undefined) updates.bannerTitleAccent = parsed.bannerTitleAccent;
              if (parsed.badgeText !== undefined) updates.badgeText = parsed.badgeText;
              if (parsed.bannerSubtitle !== undefined) updates.bannerSubtitle = parsed.bannerSubtitle;
              if (parsed.bannerCtaLabel !== undefined) updates.bannerCtaLabel = parsed.bannerCtaLabel;
              if (parsed.bannerCtaLabelMobile !== undefined) updates.bannerCtaLabelMobile = parsed.bannerCtaLabelMobile;
              if (parsed.badgeAlign !== undefined) updates.badgeAlign = parsed.badgeAlign;
              setAttributes(updates);
              alert('Zaktualizowano pomyślnie!');
              setImportJson('');
            } catch (e) {
              alert('Błąd! Niepoprawny format JSON.');
            }
          }} style={{ width: '100%', justifyContent: 'center' }}>
            Importuj tłumaczenie
          </Button>
        </PanelBody>
        <PanelBody title="Układ (Pozycja treści)" initialOpen={false}>
          <SelectControl
            label="Pozycja pozioma"
            value={a.glassPositionX}
            options={[
              { label: "Lewa", value: "left" },
              { label: "Środek", value: "center" },
              { label: "Prawa", value: "right" },
            ]}
            onChange={(v) => setAttributes({ glassPositionX: v })}
          />
          <SelectControl
            label="Pozycja pionowa"
            value={a.glassPositionY}
            options={[
              { label: "Góra", value: "top" },
              { label: "Środek", value: "middle" },
              { label: "Dół", value: "bottom" },
            ]}
            onChange={(v) => setAttributes({ glassPositionY: v })}
          />
          <SelectControl
            label="Wyrównanie tekstu"
            value={a.textAlign}
            options={[
              { label: "Do lewej", value: "left" },
              { label: "Do środka", value: "center" },
              { label: "Do prawej", value: "right" },
            ]}
            onChange={(v) => setAttributes({ textAlign: v })}
          />
          <RangeControl label="Maksymalna szerokość treści (%)" min={20} max={100} step={1} value={a.glassWidth} onChange={(v) => setAttributes({ glassWidth: v })} />
        </PanelBody>
        <PanelBody title="Pasek opinii" initialOpen={false}>
          <ToggleControl label="Pokaż pasek opinii" checked={a.showRating} onChange={(v) => setAttributes({ showRating: v })} />
          {AVATAR_NUMS.map((n) => (
            <MediaUploadCheck key={n}>
              <MediaUpload onSelect={(media) => setAttributes({ [`avatar${n}`]: media.url })} allowedTypes={["image"]} value={a[`avatar${n}`]}
                render={({ open }) => (
                  <Button variant="secondary" onClick={open} style={{ marginBottom: 8 }}>
                    Avatar {n}{a[`avatar${n}`] ? " (zmień)" : ""}
                  </Button>
                )} />
            </MediaUploadCheck>
          ))}
        </PanelBody>

        <PanelBody title="Banner — zdjęcie tła" initialOpen={true}>
          <p style={{ marginTop: 0 }}><strong>Desktop</strong> (≥ 768px)</p>
          <MediaUploadCheck>
            <MediaUpload onSelect={(media) => { console.log("[glownabaner] desktop onSelect:", media); setAttributes({ bannerImage: media?.url || "" }); }} allowedTypes={["image"]} value={a.bannerImage}
              render={({ open }) => (
                <Button variant="secondary" onClick={open} style={{ marginBottom: 16 }}>
                  {a.bannerImage ? "Zmień zdjęcie desktop" : "Wybierz zdjęcie desktop"}
                </Button>
              )} />
          </MediaUploadCheck>

          <p style={{ marginBottom: 8 }}><strong>Mobile</strong> (&lt; 768px) — opcjonalne, pusto = używa desktop</p>
          <MediaUploadCheck>
            <MediaUpload onSelect={(media) => { console.log("[glownabaner] mobile onSelect:", media); setAttributes({ bannerImageMobile: media?.url || "" }); }} allowedTypes={["image"]} value={a.bannerImageMobile}
              render={({ open }) => (
                <Button variant="secondary" onClick={open} style={{ marginRight: 8 }}>
                  {a.bannerImageMobile ? "Zmień zdjęcie mobile" : "Wybierz zdjęcie mobile"}
                </Button>
              )} />
          </MediaUploadCheck>
          {a.bannerImageMobile && (
            <Button variant="link" isDestructive onClick={() => setAttributes({ bannerImageMobile: "" })}>
              Usuń zdjęcie mobile
            </Button>
          )}
        </PanelBody>

        <PanelBody title="Banner — Teksty i Linki CTA" initialOpen={true}>
          <TextControl label="Tekst przycisku (Desktop)" value={a.bannerCtaLabel} onChange={(v) => setAttributes({ bannerCtaLabel: v })} />
          <TextControl label="Tekst przycisku (Mobile)" value={a.bannerCtaLabelMobile} onChange={(v) => setAttributes({ bannerCtaLabelMobile: v })} help="Pozostaw puste, by skopiować tekst z Desktopu" />
          <TextControl label="URL przycisku" value={a.bannerCtaURL} onChange={(v) => setAttributes({ bannerCtaURL: v })} />
        </PanelBody>

        <PanelBody title="Pasek (Badge) nad przyciskiem" initialOpen={false}>
          <TextControl label="Tekst pastylki" value={a.badgeText} onChange={(v) => setAttributes({ badgeText: v })} help="Jeśli puste, element nie będzie się wyświetlał." />
          <SelectControl
            label="Wyrównanie pastylki względem tekstu"
            value={a.badgeAlign || 'left'}
            options={[
              { label: "Do lewej", value: "flex-start" },
              { label: "Do środka", value: "center" },
              { label: "Do prawej", value: "flex-end" },
            ]}
            onChange={(v) => setAttributes({ badgeAlign: v })}
          />
          <PanelColorSettings
            title="Kolory pastylki"
            initialOpen={false}
            colorSettings={[
              {
                value: a.badgeBgColor,
                onChange: (c) => setAttributes({ badgeBgColor: c }),
                label: 'Tło pastylki'
              },
              {
                value: a.badgeTextColor,
                onChange: (c) => setAttributes({ badgeTextColor: c }),
                label: 'Kolor tekstu'
              }
            ]}
          />
        </PanelBody>

        <PanelBody title="Typografia (Fonty)" initialOpen={false}>
          <p style={{ marginTop: 0 }}><strong>Główny tytuł</strong></p>
          <TextControl label="Rodzina fontu (np. Be Vietnam Pro, Black Mango)" value={a.titleFontFamily} onChange={(v) => setAttributes({ titleFontFamily: v })} />
          <TextControl label="Rozmiar (Desktop)" value={a.titleFontSize} onChange={(v) => setAttributes({ titleFontSize: v })} help="np. 42px" />
          <TextControl label="Rozmiar (Mobile)" value={a.titleFontSizeMobile} onChange={(v) => setAttributes({ titleFontSizeMobile: v })} help="np. 24px" />

          <p><strong>Akcent (np. Kod rabatowy)</strong></p>
          <TextControl label="Rodzina fontu" value={a.accentFontFamily} onChange={(v) => setAttributes({ accentFontFamily: v })} />
          <TextControl label="Rozmiar (Desktop)" value={a.accentFontSize} onChange={(v) => setAttributes({ accentFontSize: v })} help="np. 98px" />
          <TextControl label="Rozmiar (Mobile)" value={a.accentFontSizeMobile} onChange={(v) => setAttributes({ accentFontSizeMobile: v })} help="np. 48px" />

          <p><strong>Przycisk (CTA)</strong></p>
          <TextControl label="Rodzina fontu" value={a.ctaFontFamily} onChange={(v) => setAttributes({ ctaFontFamily: v })} />
          <TextControl label="Rozmiar (Desktop)" value={a.ctaFontSize} onChange={(v) => setAttributes({ ctaFontSize: v })} help="np. 16px" />
          <TextControl label="Rozmiar (Mobile)" value={a.ctaFontSizeMobile} onChange={(v) => setAttributes({ ctaFontSizeMobile: v })} help="np. 14px" />
        </PanelBody>
      </InspectorControls>

      {/* Rating */}
      {a.showRating && (
        <div className="glownabaner__rating">
          <span className="glownabaner__rating-line"></span>
          <div className="glownabaner__rating-pill">
            <div className="avatars">
              {AVATAR_NUMS.map((n) => a[`avatar${n}`] && (
                <img key={n} className="avatars__item" src={a[`avatar${n}`]} alt="" />
              ))}
            </div>
            <span className="glownabaner__rating-sep"></span>
            <RichText tagName="span" className="glownabaner__rating-count" value={a.ratingCount} onChange={(v) => setAttributes({ ratingCount: v })} placeholder="+xxx Opinii" />
            <span className="glownabaner__rating-sep"></span>
            <RichText tagName="span" className="glownabaner__rating-score" value={a.ratingScore} onChange={(v) => setAttributes({ ratingScore: v })} placeholder="4.9" />
            <span className="glownabaner__rating-stars" aria-hidden="true">★★★★★</span>
          </div>
          <span className="glownabaner__rating-line"></span>
        </div>
      )}

      {/* Hero banner */}
      <div className="glownabaner__hero">
        {a.bannerImage
          ? <img className="glownabaner__hero-image" src={a.bannerImage} alt="" />
          : <div className="glownabaner__hero-image glownabaner__hero-image--placeholder">Wybierz zdjęcie tła w panelu po prawej</div>}
        <div className="glownabaner__hero-content">
          <div className="glownabaner__hero-title-group" style={{ display: 'flex', flexDirection: 'column', alignItems: 'flex-start', gap: '10px' }}>
            <h1 className="glownabaner__hero-title">
              <RichText tagName="span" value={a.bannerTitle} onChange={(v) => setAttributes({ bannerTitle: v })} placeholder="Shav " />
              <RichText tagName="span" className="glownabaner__hero-title-accent" value={a.bannerTitleAccent} onChange={(v) => setAttributes({ bannerTitleAccent: v })} placeholder="Days" />
            </h1>
            {a.badgeText && (
              <div className="glownabaner__hero-badge" style={{ backgroundColor: a.badgeBgColor, color: a.badgeTextColor, alignSelf: a.badgeAlign || 'flex-start' }}>
                <RichText tagName="span" value={a.badgeText} onChange={(v) => setAttributes({ badgeText: v })} placeholder="Badge Text" />
              </div>
            )}
          </div>
          <RichText tagName="p" className="glownabaner__hero-subtitle" value={a.bannerSubtitle} onChange={(v) => setAttributes({ bannerSubtitle: v })} placeholder="Subtitle" />
          <div className="glownabaner__hero-cta">
            <RichText tagName="span" value={a.bannerCtaLabel} onChange={(v) => setAttributes({ bannerCtaLabel: v })} placeholder="Etykieta CTA" />
            <span className="glownabaner__hero-cta-arrow" aria-hidden="true">→</span>
          </div>
        </div>
      </div>
    </div>
  );
}
