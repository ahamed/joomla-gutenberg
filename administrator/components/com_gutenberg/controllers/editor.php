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
 * The Editor controller.
 *
 * @since  1.0.0
 */
class GutenbergControllerEditor extends JControllerForm
{
	/**
	 * Constructor Function
	 *
	 * @param	array	$config	A named array of configuration variables.
	 *
	 * @since	1.0.0
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);
	}

	/**
	 * Method override to check if you can add a new record.
	 *
	 * @param   array  $data  An array of input data.
	 *
	 * @return  boolean
	 *
	 * @since   1.0.0
	 */
	protected function allowAdd($data = array())
	{
		return parent::allowAdd($data);
	}

	/**
	 * Method override to check if you can edit an existing record.
	 *
	 * @param   array   $data  An array of input data.
	 * @param   string  $key   The name of the key for the primary key.
	 *
	 * @return  boolean
	 *
	 * @since   1.0.0
	 */
	protected function allowEdit($data = array(), $key = 'id')
	{
		$id 	= (int) isset($data[$key]) ? $data[$key] : 0;
		$user 	= JFactory::getUser();

		if (!$id)
		{
			return parent::allowEdit($data, $key);
		}

		if ($user->authorise('core.edit', 'com_gutenberg.editor.' . $id))
		{
			return true;
		}

		if ($user->authorise('core.edit.own', 'com_gutenberg.editor.' . $id))
		{
			$record = $this->getModel()->getItem($id);

			if (empty($record))
			{
				return false;
			}

			return $user->id === $record->created_by;
		}

		return false;
	}
}

