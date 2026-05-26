// WordPress dependencies
import { __ } from '@wordpress/i18n';
import {
	SelectControl,
	PanelBody,
	ToggleControl,
	TextControl,
	Popover,
} from '@wordpress/components';
import { applyFilters } from '@wordpress/hooks';
import {
	RichText,
	URLInput,
	InspectorControls,
	BlockControls,
	AlignmentToolbar,
	useBlockProps,
} from '@wordpress/block-editor';
import { useState } from '@wordpress/element';
import { useMergeRefs } from '@wordpress/compose';
import { bgColors, colors } from '../constants';
import './editor.scss';

let styleOptions = [
	{
		label: __( 'Primary', 'wp-bootstrap-blocks' ),
		value: 'primary',
		bgColor: bgColors.primary,
		textColor: colors.white,
	},
	{
		label: __( 'Secondary', 'wp-bootstrap-blocks' ),
		value: 'secondary',
		bgColor: bgColors.secondary,
		textColor: colors.white,
	},
];
styleOptions = applyFilters(
	'wpBootstrapBlocks.button.styleOptions',
	styleOptions
);

const DEFAULT_BG_COLOR = bgColors.primary;
const DEFAULT_TEXT_COLOR = colors.white;
const NEW_TAB_REL_DEFAULT_VALUE = 'noreferrer noopener';

const BootstrapButtonEdit = ( { attributes, isSelected, setAttributes } ) => {
	const { url, linkTarget, rel, text, style, alignment } = attributes;

	// Use internal state instead of a ref to make sure that the component
	// re-renders when the popover's anchor updates.
	const [ popoverAnchor, setPopoverAnchor ] = useState( null );

	const richtTextInputRef = useMergeRefs( [ setPopoverAnchor ] );

	// Open in new tab behavior from core/button (source: https://github.com/WordPress/gutenberg/blob/master/packages/block-library/src/button/edit.js)
	const onToggleOpenInNewTab = ( value ) => {
		const newLinkTarget = value ? '_blank' : undefined;

		let updatedRel = rel;
		if ( newLinkTarget && ! rel ) {
			updatedRel = NEW_TAB_REL_DEFAULT_VALUE;
		} else if ( ! newLinkTarget && rel === NEW_TAB_REL_DEFAULT_VALUE ) {
			updatedRel = undefined;
		}

		setAttributes( {
			linkTarget: newLinkTarget,
			rel: updatedRel,
		} );
	};

	// Fill empty color values with default values and check for usage of deprecated color attribute in styleOptions
	let hasDeprecatedColorAttributes = false;
	const styleOptionsWithDefault = styleOptions.map( ( styleOption ) => {
		if ( styleOption.color ) {
			hasDeprecatedColorAttributes = true;
		}
		return {
			...styleOption,
			textColor: styleOption.textColor || DEFAULT_TEXT_COLOR,
			bgColor:
				styleOption.bgColor || styleOption.color || DEFAULT_BG_COLOR, // Fallback to deprecated color attribute
		};
	} );

	if ( hasDeprecatedColorAttributes ) {
		// eslint-disable-next-line no-console
		console.warn(
			'[wpBootstrapBlocks.button.styleOptions filter] The color attribute in styleOptions is deprecated. Please us bgColor and textColor instead.'
		);
	}

	// Prepare CSS rules for selected button style
	let inlineStyle = {
		backgroundColor:
			styleOptionsWithDefault.length > 0
				? styleOptionsWithDefault[ 0 ].bgColor
				: DEFAULT_BG_COLOR,
		color:
			styleOptionsWithDefault.length > 0
				? styleOptionsWithDefault[ 0 ].textColor
				: DEFAULT_TEXT_COLOR,
	};

	if ( style ) {
		const selectedButtonColor = styleOptionsWithDefault.find(
			( styleOption ) => styleOption.value === style
		);
		if ( selectedButtonColor?.bgColor && selectedButtonColor?.textColor ) {
			inlineStyle = {
				backgroundColor: selectedButtonColor.bgColor,
				color: selectedButtonColor.textColor,
			};
		}
	}

	return (
		<>
			<div { ...useBlockProps() }>
				<RichText
					// eslint-disable-next-line @wordpress/i18n-ellipsis
					placeholder={ __( 'Add text...', 'wp-bootstrap-blocks' ) }
					value={ text }
					onChange={ ( value ) => setAttributes( { text: value } ) }
					withoutInteractiveFormatting
					allowedFormats={ [] }
					className="wp-block-wp-bootstrap-blocks-text-input"
					style={ inlineStyle }
					ref={ richtTextInputRef }
				/>
				<InspectorControls>
					<PanelBody>
						<SelectControl
							label={ __( 'Style', 'wp-bootstrap-blocks' ) }
							value={ style }
							options={ styleOptions.map( ( option ) => ( {
								label: option.label,
								value: option.value,
							} ) ) }
							onChange={ ( selectedStyle ) => {
								setAttributes( { style: selectedStyle } );
							} }
							__next40pxDefaultSize={ true }
						/>
					</PanelBody>
					<PanelBody
						title={ __( 'Link settings', 'wp-bootstrap-blocks' ) }
					>
						<ToggleControl
							label={ __(
								'Open in new tab',
								'wp-bootstrap-blocks'
							) }
							onChange={ onToggleOpenInNewTab }
							checked={ linkTarget === '_blank' }
						/>
						<TextControl
							label={ __( 'Link rel', 'wp-bootstrap-blocks' ) }
							value={ rel || '' }
							onChange={ ( newRel ) => {
								setAttributes( { rel: newRel } );
							} }
							__next40pxDefaultSize={ true }
						/>
					</PanelBody>
				</InspectorControls>
				<BlockControls>
					<AlignmentToolbar
						value={ alignment }
						label={ __(
							'Change button alignment',
							'wp-bootstrap-blocks'
						) }
						onChange={ ( newAlignment ) =>
							setAttributes( { alignment: newAlignment } )
						}
					/>
				</BlockControls>
			</div>
			{ isSelected && (
				<Popover
					placement="bottom"
					anchor={ popoverAnchor }
					shift
					focusOnMount={ false }
				>
					<URLInput
						value={ url }
						onChange={ ( value ) =>
							setAttributes( {
								url: value,
							} )
						}
					/>
				</Popover>
			) }
		</>
	);
};

export default BootstrapButtonEdit;
