import { useBlockProps } from "@wordpress/block-editor";
import '../../css/modules/logobackground.scss';

export default function Edit() {
  const blockProps = useBlockProps()

  return (
    <div {...blockProps}>
      <div className="our-placeholder-block">Hero O Nas</div>
    </div>
  )
}

