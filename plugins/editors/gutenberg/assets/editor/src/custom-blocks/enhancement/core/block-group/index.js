import React from 'react';

import { createHigherOrderComponent } from '@wordpress/compose';
import { Fragment } from '@wordpress/element';
import { InspectorControls } from '@wordpress/editor';
import { PanelBody, TextControl } from '@wordpress/components';
import { addFilter } from '@wordpress/hooks';

// Enable spacing control on the following blocks
const enablePaddingControlOnBlocks = [
    'core/group',
    'core/paragraph'
];


/**
 * Add spacing control attribute to block.
 *
 * @param {object} settings Current block settings.
 * @param {string} name Name of block.
 *
 * @returns {object} Modified block settings.
 */
const addPaddingControlAttribute = ( settings, name ) => {
	// Do nothing if it's another block than our defined ones.
	if ( ! enablePaddingControlOnBlocks.includes( name ) ) {
		return settings;
	}

	return {
        ...settings,
        attributes: {
            ...settings.attributes,
            padding: {
                type: 'string',
                default: '0px 0px 0px 0px'
            }
        }
    };
};

addFilter('blocks.registerBlockType', 'enhance-core-blocks/attribute/padding', addPaddingControlAttribute);

/**
 * Create HOC to add spacing control to inspector controls of block.
 */
const withPaddingControl = createHigherOrderComponent((BlockEdit) => {
	return ( props ) => {
		// Do nothing if it's another block than our defined ones.
		if ( ! enablePaddingControlOnBlocks.includes( props.name ) ) {
			return (
				<BlockEdit { ...props } />
			);
		}

        const { padding } = props.attributes;
        
		return (
			<Fragment>
				<BlockEdit { ...props } />
				<InspectorControls>
					<PanelBody
						title={'Padding Settings'}
						initialOpen={false}
					>
						<TextControl
							label={'Padding'}
							value={padding}
							onChange={(padding) => {
								props.setAttributes({
									padding
								});
							} }
						/>
					</PanelBody>
				</InspectorControls>
			</Fragment>
		);
	};
}, 'withPaddingControl' );

addFilter( 'editor.BlockEdit', 'enhance-core-blocks/with-padding-control', withPaddingControl );

/**
 * Add margin style attribute to save element of block.
 *
 * @param {object} saveElementProps Props of save element.
 * @param {Object} blockType Block type information.
 * @param {Object} attributes Attributes of block.
 *
 * @returns {object} Modified props of save element.
 */
const addPaddingExtraProps = ( saveElementProps, blockType, attributes ) => {
	// Do nothing if it's another block than our defined ones.
	if ( ! enablePaddingControlOnBlocks.includes( blockType.name ) ) {
		return saveElementProps;
    }
    
    const paddings = attributes.padding.trim();
    const style = {paddings};

    saveElementProps = {
        ...saveElementProps,
        style
    }

	return saveElementProps;
};

addFilter('blocks.getSaveContent.extraProps', 'enhance-core-blocks/get-save-content/extra-props', addPaddingExtraProps);