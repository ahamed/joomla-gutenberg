import React from 'react';

/**
 * WordPress dependencies
 *
 */
import {
    createSlotFill,
    Panel
} from '@wordpress/components';

const { Slot: InspectorSlot, Fill: InspectorFill } = createSlotFill(
	'StandAloneBlockEditorSidebarInspector'
);

function Sidebar() {
	return (
		<div
			className="joomla-gutenberg-editor__sidebar is-fixed"
			role="region"
			aria-label={'Standalone Block Editor advanced settings.'}
			tabIndex="-1"
		>
			<Panel header={'Inspector'}>
				<InspectorSlot bubblesVirtually />
			</Panel>
		</div>
	);
}

Sidebar.InspectorFill = InspectorFill;

export default Sidebar;
