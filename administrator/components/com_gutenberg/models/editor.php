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

use Joomla\String\StringHelper;

/**
 * Item Model for .
 *
 * @since  1.0.0
 */
class GutenbergModelEditor extends JModelAdmin
{
	/**
	 * The prefix to use with controller messages.
	 *
	 * @var    string
	 *
	 * @since  1.0.0
	 */
	protected $text_prefix = 'COM_GUTENBERG';

	/**
	 * The type alias for this content type (for example, 'com_gutenberg.editor').
	 *
	 * @var    string
	 *
	 * @since  1.0.0
	 */
	public $typeAlias = 'com_gutenberg.editor';

	/**
	 * Returns a Table object, always creating it.
	 *
	 * @param   string  $type    The table type to instantiate
	 * @param   string  $prefix  A prefix for the table class name. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  JTable    A database object
	 */
	public function getTable($name = 'Editor', $prefix = 'GutenbergTable', $config = array())
	{
		return JTable::getInstance($name, $prefix, $config);
	}

	/**
	 * Method to get the record form.
	 *
	 * @param   array    $data      Data for the form.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  JForm|boolean  A JForm object on success, false on failure
	 *
	 * @since   1.0.0
	 */
	public function getForm($data = array(), $loadData = true)
	{
		$app 	= JFactory::getApplication();
		$form 	= $this->loadForm('com_gutenberg.editor', 'editor', array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form))
		{
			return false;
		}

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  mixed  The data for the form.
	 *
	 * @since   1.0.0
	 */
	public function loadFormData()
	{
		$data = JFactory::getApplication()
			->getUserState('com_gutenberg.edit.editor.data', array());

		if (empty($data))
		{
			$data = $this->getItem();
		}

		return $data;
	}

	/**
	 * Method to test whether a record can be deleted.
	 *
	 * @param   object  $record  A record object.
	 *
	 * @return  boolean  True if allowed to delete the record. Defaults to the permission set in the component.
	 *
	 * @since   1.0.0
	 */
	protected function canDelete($record)
	{
		if (!empty($record->id))
		{
			if ($record->published !== -2)
			{
				return;
			}

			$user = JFactory::getUser();

			return parent::canDelete($record);
		}
	}

	/**
	 * Method to test whether a record can have its state edited.
	 *
	 * @param   object  $record  A record object.
	 *
	 * @return  boolean  True if allowed to change the state of the record. Defaults to the permission set in the component.
	 *
	 * @since   1.0.0
	 */
	protected function canEditState($record)
	{
		return parent::canEditState($record);
	}

	/**
	 * Method to get a single record.
	 *
	 * @param   integer  $pk  The id of the primary key.
	 *
	 * @return  mixed  Object on success, false on failure.
	 */
	public function getItem($pk = null)
	{
		return parent::getItem($pk);
	}

	/**
	 * Generate new title alias
	 *
	 * @param	string	$alias		Alias string
	 * @param	string	$title		Title string
	 *
	 * @return 	array	Newly generated title alias
	 *
	 * @since	1.0.0
	 */
	private function generateNewTitleLocally($alias, $title)
	{
		// Alter the title & alias
		$table = $this->getTable();

		while ($table->load(array('alias' => $alias)))
		{
			$title = StringHelper::increment($title);
			$alias = StringHelper::increment($alias, 'dash');
		}

		return array($title, $alias);
	}

	/**
	 * Method to save the form data.
	 *
	 * @param   array  $data  The form data.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   1.0.0
	 */
	public function save($data)
	{
		$input	= JFactory::getApplication()->input;
		$task	= $input->get('task');

		if ($task === 'save2copy')
		{
			$originalTable = clone $this->getTable();
			$originalTable->load($input->getInt('id'));

			if ($data['title'] === $originalTable->title)
			{
				list($title, $alias) = $this->generateNewTitleLocally($data['alias'], $data['title']);
				$data['title'] = $title;
				$data['alias'] = $alias;
			}
			else
			{
				if ($data['alias'] === $originalTable->alias)
				{
					$data['alias'] = '';
				}
			}

			$data['published'] = 0;
		}

		if (parent::save($data))
		{
			return true;
		}

		return false;
	}
}

