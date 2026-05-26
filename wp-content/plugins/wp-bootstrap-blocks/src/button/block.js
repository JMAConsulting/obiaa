/**
 * BLOCK: wp-bootstrap-blocks/button
 */

// WordPress dependencies
import { registerBlockType } from '@wordpress/blocks';

import edit from './edit';
import { button } from '../icons';
import metadata from './block.json';

registerBlockType( metadata.name, {
	icon: button,

	getEditWrapperProps( attributes ) {
		const { alignment, style } = attributes;

		return {
			'data-alignment': alignment,
			'data-style': style,
		};
	},

	edit,

	save() {
		return null;
	},
} );
