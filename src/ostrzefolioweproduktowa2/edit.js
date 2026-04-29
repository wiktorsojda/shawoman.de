import { useBlockProps } from "@wordpress/block-editor"

export default function Edit() {
  const blockProps = useBlockProps()

  return (
    <div {...blockProps}>
      <div className="our-placeholder-block">Ostrze Foliowe Produktowa2</div>
    </div>
  )
}

