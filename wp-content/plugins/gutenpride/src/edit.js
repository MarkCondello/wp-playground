/* WordPress translations package
*
*/
import { __ } from '@wordpress/i18n';

/**
 * WordPress components that create the necessary UI elements for the block
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-components/
 */
import { Placeholder, TextControl } from '@wordpress/components';

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import { useBlockProps } from '@wordpress/block-editor';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @param {Object}   props               Properties passed to the function.
 * @param {Object}   props.attributes    Available block attributes.
 * @param {Function} props.setAttributes Function that updates individual attributes.
 *
 * @return {WPElement} Element to render.
 */
export default function Edit( { attributes, isSelected, setAttributes } ) {
	const blockProps = useBlockProps();
	return (
		<div { ...blockProps }>
			{
				attributes.message && !isSelected ? 
				(
					<div>Message: {attributes.message}</div>
				)
				: (
					<Placeholder
					label={ __('Gutenpride Block', 'gutenpride') }
					instructions={ __('Add your message', 'gutenpride') }
				>
					<TextControl
						label={ __('Message', 'gutenpride') }
						value={ attributes.message }
						onChange={ ( val ) => setAttributes( { message: val } ) }
						/>
				</Placeholder>
				)
			}

		</div>
	)
}
/*
The isSelected parameter is passed in to the edit function and is set to true if the block is selected in the editor (currently editing) otherwise set to false (editing elsewhere).

Additional features can be added and referenced from the Codex here: https://developer.wordpress.org/block-editor/getting-started/create-block/finishing/
*/