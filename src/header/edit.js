import { useBlockProps, InnerBlocks } from "@wordpress/block-editor";

export default function Edit() {
  const blockProps = useBlockProps()

  return (
    <div {...blockProps}>
      <div className="our-placeholder-block">University Header Placeholder</div>
      <InnerBlocks
        allowedBlocks={['core/navigation']} // Allow only navigation block or any other block you want to allow
        template={[['core/navigation']]} // Default to navigation block
      />
    </div>
  )
}
