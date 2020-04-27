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
 * Methods supporting a list records.
 *
 * @since  1.0.0
 */
class GutenbergModelEditors extends JModelList
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @since   1.0.0
	 * @see     JControllerLegacy
	 */
	public function __construct(array $config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = [
				'id','a.id',
				'title', 'a.title',
				'ordering', 'a.ordering',
				'created_by', 'a.created_by',
				'created', 'a.created',
				'published', 'a.published',
				'id', 'a.id'
			];
		}

		parent::__construct($config);
	}

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
	protected function populateState($ordering = 'a.ordering', $direction = 'asc')
	{
		$app 		= JFactory::getApplication();
		$context 	= $this->context;

		$search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$access = $this->getUserStateFromRequest($this->context . '.filter.access', 'filter_access');
		$this->setState('filter.access', $access);

		$published = $this->getUserStateFromRequest($this->context . '.filter.published', 'filter_published', '');
		$this->setState('filter.published', $published);

		$language = $this->getUserStateFromRequest($this->context . '.filter.language', 'filter_language', '');
		$this->setState('filter.language', $language);

		parent::populateState($ordering, $direction);
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string  $id  A prefix for the store id.
	 *
	 * @return  string  A store id.
	 *
	 * @since   1.0.0
	 */
	protected function getStoreId($id = '')
	{
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.access');
		$id .= ':' . $this->getState('filter.published');
		$id .= ':' . $this->getState('filter.language');

		return parent::getStoreId($id);
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return  JDatabaseQuery
	 *
	 * @since   1.0.0
	 */
	protected function getListQuery()
	{
		$app 	= JFactory::getApplication();
		$state = $this->get('State');
		$db 	= JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('a.*, l.title_native as lang');
		$query->from($db->quoteName('#__gutenberg_editors', 'a'));
		$query->join('LEFT', $db->quoteName('#__languages', 'l') . ' ON (' .
			$db->quoteName('a.language') . ' = ' . $db->quoteName('l.lang_code') . ' )'
		);

		// Join over the users for the checked out user.
		$query->select('uc.name AS editor')
			->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');

		$query->select('ua.name AS author_name')
			->join('LEFT', '#__users AS ua ON ua.id = a.created_by');

		$query->select('ug.title AS access_title')
			->join('LEFT', '#__viewlevels AS ug ON ug.id = a.access');

		if ($status = $this->getState('filter.published'))
		{
			if ($status != '*')
			{
				$query->where($db->quoteName('a.published') . ' = ' . $status);
			}
		}
		else
		{
			$query->where($db->quoteName('a.published') . ' IN (0,1)');
		}

		$orderCol 	= $this->getState('list.ordering', 'a.ordering');
		$orderDirn = $this->getState('list.direction', 'desc');

		$order = $db->escape($orderCol) . ' ' . $db->escape($orderDirn);
		$query->order($order);

		return $query;
	}
}

