/**
 * BLOCK: wp-bootstrap-blocks/container
 */

// WordPress dependencies
import { registerBlockType } from '@wordpress/blocks';
import { InnerBlocks } from '@wordpress/block-editor';

import edit from './edit';
import { stack } from '../icons';
import metadata from './block.json';

registerBlockType( metadata.name, {
	icon: stack,
	edit,
	save() {
		return <InnerBlocks.Content />;
	},
} );
