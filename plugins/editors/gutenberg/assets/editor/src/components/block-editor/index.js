import React from 'react';
/**
 * WordPress dependencies
 */
import '@wordpress/editor'; // This shouldn't be necessary
import '@wordpress/format-library';
import { useDispatch } from '@wordpress/data';
import { useEffect, useState, useMemo } from '@wordpress/element';
import { serialize, parse } from '@wordpress/blocks';
import { uploadMedia } from '@wordpress/media-utils';

import {
	BlockEditorKeyboardShortcuts,
	BlockEditorProvider,
	BlockList,
	BlockInspector,
	WritingFlow,
	ObserveTyping,
} from '@wordpress/block-editor';

/**
 * Internal dependencies
 */
import Sidebar from '../sidebar';

import {options} from '../../utils';


// import '../../custom-blocks/enhancement/core';


function BlockEditor( { settings: _settings } ) {
	const [ blocks, updateBlocks ] = useState( [] );
	const { createInfoNotice } = useDispatch( 'core/notices' );

	const canUserCreateMedia = true;
    

	const settings = useMemo(() => {
		if (!canUserCreateMedia) {
			return _settings;
		}
		return {
			..._settings,
			mediaUpload({ onError, ...rest }) {
				uploadMedia({
					wpAllowedMimeTypes: _settings.allowedMimeTypes,
					onError: ( { message } ) => onError( message ),
					...rest,
				});
			},
		};
	}, [ canUserCreateMedia, _settings ] );

	useEffect( () => {
		const storedBlocks = parse(options.blocks);

		if ( storedBlocks && storedBlocks.length ) {
			updateBlocks(() => storedBlocks);
			/**
			 * On load editor contents blocks
			 * set the Joomla editor's instance
			 */
			Joomla.editors.instances[options.id] = options.blocks;
		}
	}, [] );

	
	function persistBlocks(newBlocks) {
		updateBlocks(newBlocks);

		const content = serialize(newBlocks);
		const input = document.querySelector(`#${options.id}`);
		input.value = content;
		Joomla.editors.instances[options.id] = content;
	}

	function onInputChange(newBlocks) {
		updateBlocks(newBlocks);
		const content = serialize(newBlocks);
		const input = document.querySelector(`#${options.id}`);
		input.value = content;
		Joomla.editors.instances[options.id] = content;
	}

	return (
		<div className="joomla-gutenberg__editor">
			<BlockEditorProvider
				value={ blocks }
				onInput={ onInputChange }
				onChange={ persistBlocks }
				settings={ settings }
			>
				<Sidebar.InspectorFill>
					<BlockInspector />
				</Sidebar.InspectorFill>
				<div className="editor-styles-wrapper">
					<BlockEditorKeyboardShortcuts />
					<WritingFlow>
						<ObserveTyping>
							<BlockList className="joomla-gutenberg-editor__block-list" />
						</ObserveTyping>
					</WritingFlow>
				</div>
			</BlockEditorProvider>

		</div>
	);
}

export default BlockEditor;

