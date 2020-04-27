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

/**
 * Item Model for Editor.
 *
 * @since  1.0.0
 */
class GutenbergModelEditor extends JModelItem
{
	/**
	 * Model context
	 *
	 * @var		string	component.view
	 * @since	1.0.0
	 */
	protected $_context = 'com_gutenberg.editor';

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return  void
	 *
	 * @since   1.0.0
	 */
    protected function populateState()
	{
		$app 		= JFactory::getApplication('site');
		$itemId 	= $app->input->getInt('id');
		$this->setState('editor.id', $itemId);
		$this->setState('filter.language', JLanguageMultilang::isEnabled());
	}

	/**
	 * Method to get a single record.
	 *
	 * @param   integer  $itemId  The id of the primary key.
	 *
	 * @return  mixed  Object on success, false on failure.
	 */
	public function getItem($itemId = null)
	{
		$user = JFactory::getUser();
		$itemId = (!empty($itemId)) ? $itemId : (int) $this->getState('editor.id');

		if ( $this->_item === null )
		{
			$this->_item = array();
		}

		if (!isset($this->_item[$itemId]))
		{
			try {
				$db = $this->getDbo();
				$query = $db->getQuery(true);
				$query->select('a.*');
				$query->from($db->quoteName('#__gutenberg_editors', 'a'));
				$query->where('a.id = ' . (int) $itemId);

				// Filter by published state.
				$query->where('a.published = 1');

				if ($this->getState('filter.language'))
				{
								$query->where('a.language in (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');
				}

				//Authorised
				$groups = implode(',', $user->getAuthorisedViewLevels());
				$query->where('a.access IN (' . $groups . ')');

				$db->setQuery($query);
				$data = $db->loadObject();

				$this->_item[$itemId] = $data;
			}
			catch (Exception $e)
			{
				if ($e->getCode() == 404 )
				{
					JError::raiseError(404, $e->getMessage());
				}
				else
				{
					$this->setError($e);
					$this->_item[$itemId] = false;
				}
			}
		}

		return $this->_item[$itemId];
    }

}

