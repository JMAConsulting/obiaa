/**
 * BLOCK: wp-bootstrap-blocks/row
 */

// WordPress dependencies
import { registerBlockType } from '@wordpress/blocks';
import { InnerBlocks } from '@wordpress/block-editor';

import edit from './edit';
import transforms from './transforms';
import { columns } from '../icons';
import metadata from './block.json';
import './style.scss';

registerBlockType( metadata.name, {
	icon: columns, // Block icon from Dashicons → https://developer.wordpress.org/resource/dashicons/.

	transforms,

	// attributes are defined server side with register_block_type(). This is needed to make default attributes available in the blocks render callback.

	getEditWrapperProps( attributes ) {
		return {
			'data-alignment': attributes.alignment,
			'data-vertical-alignment': attributes.verticalAlignment,
			'data-editor-stack-columns': attributes.editorStackColumns,
			'data-no-gutters': attributes.noGutters,
			'data-horizontal-gutters': attributes.horizontalGutters,
		};
	},

	edit,

	save() {
		return <InnerBlocks.Content />;
	},
} );
