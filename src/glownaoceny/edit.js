import {
  useBlockProps, RichText, InspectorControls, MediaUpload, MediaUploadCheck,
} from "@wordpress/block-editor";
import { PanelBody, Button, TextareaControl } from "@wordpress/components";
import { useState } from "@wordpress/element";

const AVATAR_NUMS = [1, 2, 3, 4, 5];

export default function Edit({ attributes, setAttributes }) {
  const a = attributes;
  const blockProps = useBlockProps({ className: "glownaoceny" });
  const [importJson, setImportJson] = useState("");

  return (
    <div {...blockProps}>
      <InspectorControls>
        <PanelBody title="Tłumaczenia AI (JSON)" initialOpen={false}>
          <TextareaControl
            label="Skopiuj ten JSON dla AI"
            value={(() => {
              const data = {
                titleBefore: a.titleBefore || '',
                titleAccent: a.titleAccent || '',
                titleAfter: a.titleAfter || '',
                card1TextStrong: a.card1TextStrong || '',
                card1TextLight: a.card1TextLight || '',
                card2Title: a.card2Title || '',
                card2RatingCount: a.card2RatingCount || '',
                card2RatingScore: a.card2RatingScore || '',
                card3TextStrong: a.card3TextStrong || '',
                card3TextLight: a.card3TextLight || ''
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
              if (parsed.titleBefore !== undefined) updates.titleBefore = parsed.titleBefore;
              if (parsed.titleAccent !== undefined) updates.titleAccent = parsed.titleAccent;
              if (parsed.titleAfter !== undefined) updates.titleAfter = parsed.titleAfter;
              if (parsed.card1TextStrong !== undefined) updates.card1TextStrong = parsed.card1TextStrong;
              if (parsed.card1TextLight !== undefined) updates.card1TextLight = parsed.card1TextLight;
              if (parsed.card2Title !== undefined) updates.card2Title = parsed.card2Title;
              if (parsed.card2RatingCount !== undefined) updates.card2RatingCount = parsed.card2RatingCount;
              if (parsed.card2RatingScore !== undefined) updates.card2RatingScore = parsed.card2RatingScore;
              if (parsed.card3TextStrong !== undefined) updates.card3TextStrong = parsed.card3TextStrong;
              if (parsed.card3TextLight !== undefined) updates.card3TextLight = parsed.card3TextLight;
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
        <PanelBody title="Karta 1 — ikona" initialOpen={false}>
          <MediaUploadCheck>
            <MediaUpload onSelect={(media) => setAttributes({ card1Icon: media.url })} allowedTypes={["image"]} value={a.card1Icon}
              render={({ open }) => (<Button variant="secondary" onClick={open}>{a.card1Icon ? "Zmień ikonę" : "Wybierz ikonę"}</Button>)} />
          </MediaUploadCheck>
        </PanelBody>
        <PanelBody title="Karta 2 — avatary" initialOpen={false}>
          {AVATAR_NUMS.map((n) => (
            <MediaUploadCheck key={n}>
              <MediaUpload onSelect={(media) => setAttributes({ [`card2Avatar${n}`]: media.url })} allowedTypes={["image"]} value={a[`card2Avatar${n}`]}
                render={({ open }) => (
                  <Button variant="secondary" onClick={open} style={{ marginBottom: 8 }}>
                    Avatar {n}{a[`card2Avatar${n}`] ? " (zmień)" : ""}
                  </Button>
                )} />
            </MediaUploadCheck>
          ))}
        </PanelBody>
        <PanelBody title="Karta 3 — ikona" initialOpen={false}>
          <MediaUploadCheck>
            <MediaUpload onSelect={(media) => setAttributes({ card3Icon: media.url })} allowedTypes={["image"]} value={a.card3Icon}
              render={({ open }) => (<Button variant="secondary" onClick={open}>{a.card3Icon ? "Zmień ikonę" : "Wybierz ikonę"}</Button>)} />
          </MediaUploadCheck>
        </PanelBody>
      </InspectorControls>

      <h2 className="glownaoceny__title">
        <RichText tagName="span" value={a.titleBefore} onChange={(v) => setAttributes({ titleBefore: v })} placeholder="Shav " />
        <RichText tagName="span" className="glownaoceny__title-accent" value={a.titleAccent} onChange={(v) => setAttributes({ titleAccent: v })} placeholder="woman." />
        <RichText tagName="span" className="glownaoceny__title-after" value={a.titleAfter} onChange={(v) => setAttributes({ titleAfter: v })} placeholder=" Dlaczego warto wybrać?" />
      </h2>

      <div className="glownaoceny__cards">
        {/* Karta 1 */}
        <div className="glownaoceny__card">
          <div className="glownaoceny__card-icon">
            {a.card1Icon
              ? <img src={a.card1Icon} alt="" />
              : <span className="glownaoceny__card-icon-placeholder">ikona</span>}
          </div>
          <p className="glownaoceny__card-text">
            <RichText tagName="span" value={a.card1TextStrong} onChange={(v) => setAttributes({ card1TextStrong: v })} placeholder="Tekst pierwszy" />
            {" "}
            <RichText tagName="span" className="glownaoceny__card-text-light" value={a.card1TextLight} onChange={(v) => setAttributes({ card1TextLight: v })} placeholder="Tekst drugi" />
          </p>
        </div>

        {/* Karta 2 */}
        <div className="glownaoceny__card">
          <RichText tagName="p" className="glownaoceny__card-title" value={a.card2Title} onChange={(v) => setAttributes({ card2Title: v })} placeholder="Tytuł" />
          <div className="glownaoceny__card-avatars">
            {AVATAR_NUMS.map((n) => a[`card2Avatar${n}`] && (
              <img key={n} src={a[`card2Avatar${n}`]} alt="" />
            ))}
          </div>
          <div className="glownaoceny__card-pill">
            <RichText tagName="span" value={a.card2RatingCount} onChange={(v) => setAttributes({ card2RatingCount: v })} placeholder="+300K Opinii" />
            <span className="glownaoceny__card-pill-sep" aria-hidden="true"></span>
            <RichText tagName="span" value={a.card2RatingScore} onChange={(v) => setAttributes({ card2RatingScore: v })} placeholder="4.9" />
            <span className="glownaoceny__card-pill-stars" aria-hidden="true">★★★★★</span>
          </div>
        </div>

        {/* Karta 3 */}
        <div className="glownaoceny__card">
          <div className="glownaoceny__card-icon">
            {a.card3Icon
              ? <img src={a.card3Icon} alt="" />
              : <span className="glownaoceny__card-icon-placeholder">ikona</span>}
          </div>
          <p className="glownaoceny__card-text">
            <RichText tagName="span" value={a.card3TextStrong} onChange={(v) => setAttributes({ card3TextStrong: v })} placeholder="Tekst pierwszy" />
            {" "}
            <RichText tagName="span" className="glownaoceny__card-text-light" value={a.card3TextLight} onChange={(v) => setAttributes({ card3TextLight: v })} placeholder="Tekst drugi" />
          </p>
        </div>
      </div>
    </div>
  );
}
