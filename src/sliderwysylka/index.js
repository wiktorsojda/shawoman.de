import { registerBlockType } from "@wordpress/blocks";
import { useBlockProps } from "@wordpress/block-editor";
import metadata from "./block.json";
import Edit from "./edit";
import gsap from "gsap";
import { ScrollTrigger, ScrollToPlugin } from "gsap/all";
import Lenis from "@studio-freight/lenis";
import "../../inc/slider-platnosci"; // Adjusted path

gsap.registerPlugin(ScrollTrigger, ScrollToPlugin);

registerBlockType(metadata.name, {
  edit: Edit,
  save: () => {
    const blockProps = useBlockProps.save();
    return <div {...blockProps}></div>;
  }
});