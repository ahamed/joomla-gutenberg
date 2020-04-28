import React from 'react';
/**
 * WordPress dependencies
 */
import {
	Popover,
	SlotFillProvider,
	DropZoneProvider,
} from '@wordpress/components';

/**
 * Internal dependencies
 */
import Notices from './components/notices';
import Sidebar from './components/sidebar';
import BlockEditor from './components/block-editor';

function Editor( { settings } ) {
	return (
		<SlotFillProvider>
			<DropZoneProvider>
                <div className="joomla-gutenberg__notices">
                    <Notices />
                </div>
                <Sidebar />
                <Popover.Slot name="block-toolbar" />
                <BlockEditor settings={settings} />
				<Popover.Slot />
			</DropZoneProvider>
		</SlotFillProvider>
	);
}

export default Editor;
