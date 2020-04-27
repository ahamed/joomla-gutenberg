<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_gutenberg
 * @author      JoomShaper <support@joomshaper.com>
 * @copyright   Copyright (c) 2010 - 2020 JoomShaper
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */

// No Direct Access
defined('_JEXEC') or die('Resticted Aceess');

jimport( 'joomla.application.component.helper' );

/**
 * Methods supporting a list records.
 *
 * @since  1.0.0
 */
class GutenbergModelEditors extends JModelList
{
	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc|desc).
	 *
	 * @return  void
	 *
	 * @since   1.0.0
	 */
	protected function populateState($ordering = null, $direction = null) {
		$app = JFactory::getApplication('site');
		$this->setState('list.start', $app->input->get('limitstart', 0, 'uint'));
		$this->setState('filter.language', JLanguageMultilang::isEnabled());
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return  JDatabaseQuery
	 *
	 * @since   1.0.0
	 */
	protected function getListQuery() {
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('a.*');
		$query->from($db->quoteName('#__gutenberg_editors', 'a'));

		$query->where($db->quoteName('a.published') . ' = ' . $db->quote('1'));

		if ($this->getState('filter.language'))
		{
			$query->where($db->quoteName('a.language') . ' IN (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');
		}

		return $query;
	}
}

