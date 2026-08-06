import { registerBlockType } from "@wordpress/blocks";
import { useBlockProps } from "@wordpress/block-editor";
import metadata from "./block.json";
import Edit from "./edit";

registerBlockType(metadata.name, {
  edit: Edit,
  save: () => {
    const blockProps = useBlockProps.save();
    return <div {...blockProps}></div>;
  }
});