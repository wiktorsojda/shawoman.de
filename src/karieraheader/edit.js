import {
  useBlockProps, RichText, InspectorControls, MediaUpload, MediaUploadCheck,
} from "@wordpress/block-editor";
import { PanelBody, Button } from "@wordpress/components";

export default function Edit({ attributes, setAttributes }) {
  const { videoURL, backgroundImage, title, subtitle } = attributes;
  const wrapperStyle = backgroundImage
    ? { backgroundImage: `url(${backgroundImage})`, backgroundSize: "cover", backgroundPosition: "center" }
    : {};
  const blockProps = useBlockProps({ className: "video-background-container-wysylka", style: wrapperStyle });
  return (
    <div {...blockProps}>
      <InspectorControls>
        <PanelBody title="Tło wideo" initialOpen={true}>
          <MediaUploadCheck>
            <MediaUpload onSelect={(media) => setAttributes({ videoURL: media.url })} allowedTypes={["video"]} value={videoURL}
              render={({ open }) => (<Button variant="secondary" onClick={open}>{videoURL ? "Zmień wideo" : "Wybierz wideo"}</Button>)} />
          </MediaUploadCheck>
          {videoURL && (<Button variant="link" isDestructive onClick={() => setAttributes({ videoURL: "" })} style={{ marginTop: 8 }}>Usuń wideo</Button>)}
        </PanelBody>
        <PanelBody title="Tło — obraz (alternatywnie)" initialOpen={false}>
          <MediaUploadCheck>
            <MediaUpload onSelect={(media) => setAttributes({ backgroundImage: media.url })} allowedTypes={["image"]} value={backgroundImage}
              render={({ open }) => (<Button variant="secondary" onClick={open}>{backgroundImage ? "Zmień obraz" : "Wybierz obraz"}</Button>)} />
          </MediaUploadCheck>
          {backgroundImage && (<Button variant="link" isDestructive onClick={() => setAttributes({ backgroundImage: "" })} style={{ marginTop: 8 }}>Usuń obraz tła</Button>)}
        </PanelBody>
      </InspectorControls>
      {videoURL && (<video className="video-background-wysylka" src={videoURL} autoPlay loop muted playsInline />)}
      <section className="about-us-second-wysylka container--narrow2-important">
        <h1 className="about-us-second-title-wysylka">
          <RichText tagName="span" className="about-us-span-wysylka-first container--narrow2-important" value={title} onChange={(v) => setAttributes({ title: v })} placeholder="Tytuł" />
          <span className="about-us-span-wysylka-container">
            <RichText tagName="span" className="about-us-span-wysylka-second" value={subtitle} onChange={(v) => setAttributes({ subtitle: v })} placeholder="Podtytuł" />
            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="15" viewBox="0 0 25 15" fill="none"><path fillRule="evenodd" clipRule="evenodd" d="M0.512563 0.858103C1.19598 0.158413 2.30402 0.158413 2.98744 0.858103L12.25 10.3412L21.5126 0.858103C22.196 0.158413 23.304 0.158413 23.9874 0.858103C24.6709 1.55779 24.6709 2.69221 23.9874 3.3919L13.4874 14.1419C12.804 14.8416 11.696 14.8416 11.0126 14.1419L0.512563 3.3919C-0.170854 2.69221 -0.170854 1.55779 0.512563 0.858103Z" fill="#065C70"/></svg>
          </span>
        </h1>
      </section>
    </div>
  );
}
