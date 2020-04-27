<?php
/**
 * @package		Joomla.Administrator
 * @subpackage	com_gutenberg
 * @author      JoomShaper <support@joomshaper.com>
 * @copyright   Copyright (c) 2010 - 2020 JoomShaper
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */

// No Direct Access
defined('_JEXEC') or die('Resticted Aceess');

/**
 * Utility class for the component
 *
 * @since    1.0.0
 */
class GutenbergHelper extends JHelperContent
{
	/**
	 * Add sidebar submenu method
	 *
	 * @param	string		$vName		The view name
	 *
	 * @return	void
	 *
	 * @since	1.0.0
	 */
	public static function addSubmenu($vName)
	{
		JHtmlSidebar::addEntry(
			JText::_('COM_GUTENBERG_TITLE_VIEW_NAME'),
			'index.php?option=com_gutenberg&view=view_plural',
			$vName === 'view_plural'
		);

		// Every view which is suppose to show in the left side will be added here
	}
}

