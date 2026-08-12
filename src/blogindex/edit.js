import { useBlockProps, InspectorControls, MediaUpload, MediaUploadCheck, RichText } from "@wordpress/block-editor"
import { useSelect } from "@wordpress/data"
import { PanelBody, TextareaControl, Button, TextControl, ToggleControl } from "@wordpress/components"
import { useState } from "@wordpress/element"

export default function Edit(props) {
  const blockProps = useBlockProps()
  const { attributes } = props
  const title = attributes?.title ?? 'BlendyBlog'
  const subtitle = attributes?.subtitle ?? 'Twój codzienny miks przepisów i lifestylowych inspiracji.'
  const buttonText = attributes?.buttonText ?? 'Przejdź do wpisu'
  const heroButtonText = attributes?.heroButtonText ?? 'Czytaj wpisy'
  const gridTitle = attributes?.gridTitle ?? 'Wszystkie wpisy'
  const noPostsText = attributes?.noPostsText ?? 'Brak wpisów do wyświetlenia.'
  const sidebarCategoriesTitle = attributes?.sidebarCategoriesTitle ?? 'KATEGORIE'
  const featuredLabel = attributes?.featuredLabel ?? 'Najnowszy wpis'
  const videoUrl = attributes?.videoUrl ?? 'https://blendygo.pl/wp-content/uploads/2023/08/lifestylowy-2.mp4'
  const categoryPrefixText = attributes?.categoryPrefixText ?? 'Przeglądasz wpisy z kategorii: '
  const backToBlogUrl = attributes?.backToBlogUrl ?? '/blog/'
  const heroBgImage = attributes?.heroBgImage ?? ''
  const aboutImage = attributes?.aboutImage ?? ''
  const aboutTitle = attributes?.aboutTitle ?? 'O NAS'
  const aboutText = attributes?.aboutText ?? 'Cześć, tu ekipa BlendyGo! 🥤\n\nWierzymy, że zdrowe nawyki mogą być proste i przyjemne. Na tym blogu miksujemy dla Ciebie codzienne porcje pysznych przepisów, porad i czystej motywacji. Złap z nami swój rytm!\n\nWpadnij też na nasze social media po codzienną dawkę miksu inspiracji! 👇'
  const findUsTitle = attributes?.findUsTitle ?? 'Znajdziesz nas na:'
  const igLink = attributes?.igLink ?? 'https://instagram.com/blendygo'
  const tiktokLink = attributes?.tiktokLink ?? 'https://tiktok.com/@blendygo'
  const fbLink = attributes?.fbLink ?? 'https://facebook.com/blendygo'
  const showIg = attributes?.showIg ?? true
  const showTiktok = attributes?.showTiktok ?? true
  const showFb = attributes?.showFb ?? true
  const showViewsCounter = attributes?.showViewsCounter ?? false
  const showDates = attributes?.showDates ?? true

  const [importJson, setImportJson] = useState("")

  const getExportJson = () => {
    const data = {
      title: title || '',
      subtitle: subtitle || '',
      buttonText: buttonText || '',
      heroButtonText: heroButtonText || '',
      gridTitle: gridTitle || '',
      noPostsText: noPostsText || '',
      sidebarCategoriesTitle: sidebarCategoriesTitle || '',
      featuredLabel: featuredLabel || '',
      categoryPrefixText: categoryPrefixText || '',
      backToBlogUrl: backToBlogUrl || '',
      aboutTitle: aboutTitle || '',
      aboutText: aboutText || '',
      findUsTitle: findUsTitle || ''
    }
    return JSON.stringify(data, null, 2)
  }

  const handleImport = () => {
    try {
      const parsed = JSON.parse(importJson)
      const updates = {}
      if (parsed.title !== undefined) updates.title = parsed.title
      if (parsed.subtitle !== undefined) updates.subtitle = parsed.subtitle
      if (parsed.buttonText !== undefined) updates.buttonText = parsed.buttonText
      if (parsed.heroButtonText !== undefined) updates.heroButtonText = parsed.heroButtonText
      if (parsed.gridTitle !== undefined) updates.gridTitle = parsed.gridTitle
      if (parsed.noPostsText !== undefined) updates.noPostsText = parsed.noPostsText
      if (parsed.sidebarCategoriesTitle !== undefined) updates.sidebarCategoriesTitle = parsed.sidebarCategoriesTitle
      if (parsed.featuredLabel !== undefined) updates.featuredLabel = parsed.featuredLabel
      if (parsed.categoryPrefixText !== undefined) updates.categoryPrefixText = parsed.categoryPrefixText
      if (parsed.backToBlogUrl !== undefined) updates.backToBlogUrl = parsed.backToBlogUrl
      if (parsed.aboutTitle !== undefined) updates.aboutTitle = parsed.aboutTitle
      if (parsed.aboutText !== undefined) updates.aboutText = parsed.aboutText
      if (parsed.findUsTitle !== undefined) updates.findUsTitle = parsed.findUsTitle
      
      props.setAttributes(updates)
      alert('Zaktualizowano pomyślnie!')
      setImportJson('')
    } catch (e) {
      alert('Błąd! Niepoprawny format JSON.')
    }
  }

  // Fetch latest posts for the slider preview
  const sliderPosts = useSelect((select) => {
    return select("core").getEntityRecords("postType", "post", {
      per_page: 3,
      _embed: true
    })
  }, [])

  return (
    <div {...blockProps}>
      <InspectorControls>
        <PanelBody title="Tłumaczenia AI (JSON)" initialOpen={false}>
          <TextareaControl
            label="Skopiuj ten JSON dla AI"
            value={getExportJson()}
            readOnly
            rows={10}
            help="Skopiuj i wklej do Gemini z prośbą o przetłumaczenie samych wartości."
          />
          <TextareaControl
            label="Wklej przetłumaczony JSON"
            value={importJson}
            onChange={setImportJson}
            rows={10}
          />
          <Button variant="primary" onClick={handleImport} style={{ width: '100%', justifyContent: 'center' }}>
            Importuj tłumaczenie
          </Button>
        </PanelBody>
        <PanelBody title="Teksty" initialOpen={false}>
          <TextControl
            label="Prefix kategorii"
            value={categoryPrefixText}
            onChange={(val) => props.setAttributes({ categoryPrefixText: val })}
          />
          <TextControl
            label="Link URL powrotu do bloga (używany w archiwum)"
            value={backToBlogUrl}
            onChange={(val) => props.setAttributes({ backToBlogUrl: val })}
          />
        </PanelBody>
        <PanelBody title="O nas - Ustawienia" initialOpen={true}>
          <ToggleControl
            label="Pokaż licznik wyświetleń na kafelkach wpisów"
            checked={showViewsCounter}
            onChange={(val) => props.setAttributes({ showViewsCounter: val })}
            help="Jeśli włączone, użytkownicy będą widzieć ikonę oka z liczbą wyświetleń posta."
          />
          <hr style={{margin: '1rem 0'}}/>
          <TextControl
            label="Tytuł sekcji (O NAS)"
            value={aboutTitle}
            onChange={(val) => props.setAttributes({ aboutTitle: val })}
          />
          <TextControl
            label="Tytuł sekcji (Znajdziesz nas na:)"
            value={findUsTitle}
            onChange={(val) => props.setAttributes({ findUsTitle: val })}
          />
          <MediaUploadCheck>
            <MediaUpload
              onSelect={(media) => props.setAttributes({ aboutImage: media.url })}
              allowedTypes={['image']}
              render={({ open }) => (
                <div style={{marginBottom: '1rem'}}>
                  <Button variant="secondary" onClick={open}>
                    {aboutImage ? 'Zmień zdjęcie' : 'Wybierz zdjęcie'}
                  </Button>
                  {aboutImage && (
                    <Button variant="link" isDestructive onClick={() => props.setAttributes({ aboutImage: '' })}>
                      Usuń zdjęcie
                    </Button>
                  )}
                </div>
              )}
            />
          </MediaUploadCheck>
          <TextareaControl
            label="Tekst O nas"
            value={aboutText}
            onChange={(val) => props.setAttributes({ aboutText: val })}
            rows={6}
          />
          <hr style={{margin: '1rem 0'}}/>
          <h3>Tło Banera Hero</h3>
          <MediaUploadCheck>
            <MediaUpload
              onSelect={(media) => props.setAttributes({ heroBgImage: media.url, videoUrl: media.url })}
              allowedTypes={['image']}
              value={heroBgImage}
              render={({ open }) => (
                <div style={{ marginBottom: '1rem' }}>
                  {heroBgImage && <img src={heroBgImage} style={{ width: '100%', marginBottom: '10px' }} />}
                  <Button isPrimary onClick={open}>
                    {heroBgImage ? 'Zmień tło banera' : 'Wybierz tło banera'}
                  </Button>
                  {heroBgImage && (
                    <Button isLink isDestructive onClick={() => props.setAttributes({ heroBgImage: '', videoUrl: '' })} style={{ display: 'block', marginTop: '10px' }}>
                      Usuń zdjęcie
                    </Button>
                  )}
                </div>
              )}
            />
          </MediaUploadCheck>
          <hr style={{margin: '1rem 0'}}/>
          <ToggleControl
            label="Pokaż Instagram"
            checked={showIg}
            onChange={(val) => props.setAttributes({ showIg: val })}
          />
          {showIg && (
            <TextControl
              label="Link Instagram"
              value={igLink}
              onChange={(val) => props.setAttributes({ igLink: val })}
            />
          )}
          <ToggleControl
            label="Pokaż TikTok"
            checked={showTiktok}
            onChange={(val) => props.setAttributes({ showTiktok: val })}
          />
          {showTiktok && (
            <TextControl
              label="Link TikTok"
              value={tiktokLink}
              onChange={(val) => props.setAttributes({ tiktokLink: val })}
            />
          )}
          <ToggleControl
            label="Pokaż Facebook"
            checked={showFb}
            onChange={(val) => props.setAttributes({ showFb: val })}
          />
          {showFb && (
            <TextControl
              label="Link Facebook"
              value={fbLink}
              onChange={(val) => props.setAttributes({ fbLink: val })}
            />
          )}
          <ToggleControl
            label="Pokaż daty wpisów"
            checked={showDates}
            onChange={(val) => props.setAttributes({ showDates: val })}
          />
        </PanelBody>
      </InspectorControls>

      <div className="blog-index-wrapper">
        
        <header className="blog-hero-simple" style={{ position: 'relative', width: '100%', height: '450px', display: 'flex', alignItems: 'center', justifyContent: 'center', overflow: 'hidden' }}>
            {heroBgImage ? (
                <img src={heroBgImage} alt="" style={{ position: 'absolute', top: 0, left: 0, width: '100%', height: '100%', objectFit: 'cover', zIndex: 1 }} />
            ) : (
                <div style={{ position: 'absolute', top: 0, left: 0, width: '100%', height: '100%', backgroundColor: '#333', zIndex: 1 }}></div>
            )}
            <div style={{ position: 'absolute', top: 0, left: 0, width: '100%', height: '100%', backgroundColor: 'rgba(0,0,0,0.5)', zIndex: 2 }}></div>
            <RichText
                tagName="h1"
                className="section-main-title"
                style={{ color: '#FFF', position: 'relative', zIndex: 3, textAlign: 'center', margin: 0, fontSize: '3rem', fontWeight: 'bold', textTransform: 'uppercase' }}
                value={title}
                onChange={(val) => props.setAttributes({ title: val })}
                placeholder="BlendyBlog"
                allowedFormats={['core/bold', 'core/italic']}
            />
        </header>

        <div className="blog-container">
            <div className="blog-layout">
                <main className="blog-main-content">
                    <div className="blog-section-header">
                        <h2 className="blog-section-header__title">{gridTitle} (Podgląd)</h2>
                    </div>
                    <div className="blog-archive-grid">
                        <p style={{padding: '2rem', background: '#fff', border: '1px dashed #ccc'}}>{noPostsText}</p>
                    </div>

                    <div className="blog-widget blog-widget--about blog-widget--about-wide" style={{marginTop: '0', background: '#FFFAF6', boxShadow: '0px 8px 25px rgba(0, 0, 0, 0.06)'}}>
                        {aboutImage && (
                            <div className="blog-widget-about-image-col">
                                <img src={aboutImage} alt="O blogu" className="blog-widget-about-image" style={{width: '100%', borderRadius: '15px', aspectRatio: '1/1', objectFit: 'cover'}} />
                            </div>
                        )}
                        <div className="blog-widget-about-content-col">
                            <div className="blog-widget__header">
                                <h3 className="blog-widget__title">{aboutTitle}</h3>
                            </div>
                            <div className="blog-widget-about-text" style={{whiteSpace: 'pre-wrap', marginBottom: '1.5rem'}}>{aboutText}</div>
                            
                            <div className="blog-widget-socials" style={{display: 'flex', gap: '1rem', marginTop: '1rem'}}>
                                {showIg && <div style={{width: '40px', height: '40px', background: '#E07800', borderRadius: '50%'}}></div>}
                                {showTiktok && <div style={{width: '40px', height: '40px', background: '#E07800', borderRadius: '50%'}}></div>}
                                {showFb && <div style={{width: '40px', height: '40px', background: '#E07800', borderRadius: '50%'}}></div>}
                            </div>
                        </div>
                    </div>
                </main>
                <aside className="blog-sidebar">
                    <div className="blog-widget blog-widget--categories">
                        <input type="checkbox" id="mobile-cat-toggle-edit" className="mobile-cat-toggle-checkbox" hidden />
                        <label htmlFor="mobile-cat-toggle-edit" className="blog-widget__header">
                            <h3 className="blog-widget__title">{sidebarCategoriesTitle}</h3>
                        </label>
                        <div className="blog-widget__content">
                            <ul className="blog-widget__list">
                                <li><a href="#">PRZYKŁADOWA KATEGORIA</a></li>
                            </ul>
                        </div>
                    </div>

                    {(showIg || showTiktok || showFb) && (
                        <div className="blog-widget blog-widget--sidebar-socials" style={{textAlign: 'center', padding: '2rem', background: '#FFFAF6', borderRadius: '20px', boxShadow: '0px 8px 25px rgba(0, 0, 0, 0.06)'}}>
                            <h3 className="blog-widget__title" style={{marginBottom: '1rem', fontSize: '1.1rem', color: '#111'}}>{findUsTitle}</h3>
                            <div className="blog-widget-socials" style={{display: 'flex', gap: '0.8rem', justifyContent: 'center'}}>
                                {showIg && <div style={{width: '40px', height: '40px', background: '#E07800', borderRadius: '50%'}}></div>}
                                {showTiktok && <div style={{width: '40px', height: '40px', background: '#E07800', borderRadius: '50%'}}></div>}
                                {showFb && <div style={{width: '40px', height: '40px', background: '#E07800', borderRadius: '50%'}}></div>}
                            </div>
                        </div>
                    )}
                </aside>
            </div>
        </div>

      </div>
    </div>
  )
}
