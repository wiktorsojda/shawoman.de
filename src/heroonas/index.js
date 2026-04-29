import { registerBlockType } from "@wordpress/blocks";
import '../../css/modules/logobackground.scss';
import { useBlockProps } from "@wordpress/block-editor";
import metadata from "./block.json";
import Edit from "./edit";
import gsap from "gsap";
import { ScrollTrigger, ScrollToPlugin } from "gsap/all";
import Lenis from "@studio-freight/lenis";
import "../../inc/custom-gsap"; // Adjusted path

gsap.registerPlugin(ScrollTrigger, ScrollToPlugin);

registerBlockType(metadata.name, {
  edit: Edit,
  save: () => {
    const blockProps = useBlockProps.save();
    return <div {...blockProps}></div>;
  }
});