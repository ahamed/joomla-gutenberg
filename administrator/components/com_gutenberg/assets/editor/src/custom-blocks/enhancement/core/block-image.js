import React from 'react';

import { createHigherOrderComponent } from '@wordpress/compose';
import { Fragment } from '@wordpress/element';
import { InspectorControls } from '@wordpress/editor';
import { PanelBody, SelectControl } from '@wordpress/components';
import { addFilter } from '@wordpress/hooks';

// Enable spacing control on the following blocks
const enableSpacingControlOnBlocks = [
	'core/image',
];

// Available spacing control options
const spacingControlOptions = [
	{
		label: 'None',
		value: '',
	},
	{
		label: 'Small',
		value: 'small',
	},
	{
		label: 'Medium',
		value: 'medium',
	},
	{
		label: 'Large',
		value: 'large',
	},
];

/**
 * Add spacing control attribute to block.
 *
 * @param {object} settings Current block settings.
 * @param {string} name Name of block.
 *
 * @returns {object} Modified block settings.
 */
const addSpacingControlAttribute = ( settings, name ) => {
	// Do nothing if it's another block than our defined ones.
	if ( ! enableSpacingControlOnBlocks.includes( name ) ) {
		return settings;
	}

	console.log(settings);
	return {
        ...settings,
        attributes: {
            ...settings.attributes,
            spacing: {
                type: 'string',
                default: spacingControlOptions[0].value
            }
        }
    };
};

addFilter( 'blocks.registerBlockType', 'enhance-core-image/attribute/spacing', addSpacingControlAttribute );

/**
 * Create HOC to add spacing control to inspector controls of block.
 */
const withSpacingControl = createHigherOrderComponent( ( BlockEdit ) => {
	return ( props ) => {
		// Do nothing if it's another block than our defined ones.
		if ( ! enableSpacingControlOnBlocks.includes( props.name ) ) {
			return (
				<BlockEdit { ...props } />
			);
		}

		const { spacing } = props.attributes;

		// add has-spacing-xy class to block
		if ( spacing ) {
			props.attributes.className = `has-spacing-${ spacing }`;
		}

		return (
			<Fragment>
				<BlockEdit { ...props } />
				<InspectorControls>
					<PanelBody
						title={ ( 'My Spacing Control' ) }
						initialOpen={ true }
					>
						<SelectControl
							label={'Spacing'}
							value={spacing}
							options={spacingControlOptions}
							onChange={(selectedSpacingOption) => {
								props.setAttributes({
									spacing: selectedSpacingOption,
								});
							} }
						/>
					</PanelBody>
				</InspectorControls>
			</Fragment>
		);
	};
}, 'withSpacingControl' );

addFilter( 'editor.BlockEdit', 'enhance-core-image/with-spacing-control', withSpacingControl );

/**
 * Add margin style attribute to save element of block.
 *
 * @param {object} saveElementProps Props of save element.
 * @param {Object} blockType Block type information.
 * @param {Object} attributes Attributes of block.
 *
 * @returns {object} Modified props of save element.
 */
const addSpacingExtraProps = ( saveElementProps, blockType, attributes ) => {
	// Do nothing if it's another block than our defined ones.
	if ( ! enableSpacingControlOnBlocks.includes( blockType.name ) ) {
		return saveElementProps;
	}

	const margins = {
		small: '5px',
		medium: '15px',
		large: '30px',
	};

	if (attributes.spacing in margins) {
		saveElementProps = {
			...saveElementProps,
			style: {'margin-bottom': margins[attributes.spacing]}
		}
	}

	return saveElementProps;
};

addFilter('blocks.getSaveContent.extraProps', 'enhance-core-image/get-save-content/extra-props', addSpacingExtraProps);