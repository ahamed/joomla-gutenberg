import React from 'react';
/**
 * WordPress dependencies
 */

export default function Header() {
	return (
		<div
			className="joomla-gutenberg__header"
			role="region"
			aria-label={'Standalone Editor top bar.'}
			tabIndex="-1"
		>
			<h1 className="joomla-gutenberg-header__title">
				{'Standalone Block Editor'}
			</h1>
		</div>
	);
}
