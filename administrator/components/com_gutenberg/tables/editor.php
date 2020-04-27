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
 * Editor Table class.
 *
 * @since  1.0.0
 */
class GutenbergTableEditor extends JTable
{
	/**
	 * Constructor
	 *
	 * @param   JDatabaseDriver  $db  Database connector object
	 *
	 * @since   1.0.0
	 */
	public function __construct(&$db)
	{
		parent::__construct('#__gutenberg_editors', 'id', $db);
	}

	/**
	 * Bind data to store
	 *
	 * @param   array|object  $src     An associative array or object to bind to the Table instance.
	 * @param   array|string  $ignore  An optional array or space separated list of properties to ignore while binding.
	 *
	 * @return	boolean					True on success
	 * @since	1.0.0
	 */
	public function bind($src, $ignore = array())
	{
		/**
		 * Check if the description is a base64_encoded string
		 * if it is, then decode it as string.
		 *
		 */
		if (preg_match("#^[a-zA-Z0-9/+]*={0,2}$#", $src['description']))
		{
			$src['description'] = base64_decode($src['description']);
		}

		return parent::bind($src, $ignore);
	}

	/**
	 * Method to store a row in the database from the Table instance properties.
	 *
	 * If a primary key value is set the row with that primary key value will be updated with the instance property values.
	 * If no primary key value is set a new row will be inserted into the database with the properties from the Table instance.
	 *
	 * @param   boolean  $updateNulls  True to update fields even if they are null.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   1.0.0
	 */
	public function store($updateNulls = false)
	{
		$user = JFactory::getUser();
		$app  = JFactory::getApplication();
		$date = new JDate('now', $app->getCfg('offset'));

		if ($this->id)
		{
			$this->modified 	= (string) $date;
			$this->modified_by = $user->get('id');
		}

		if (empty($this->created))
		{
			$this->created = (string) $date;
		}

		if (empty($this->created_by))
		{
			$this->created_by = $user->get('id');
		}

		return parent::store($updateNulls);
	}


	/**
	 * Method to perform sanity checks on the Table instance properties to ensure they are safe to store in the database.
	 *
	 * Child classes should override this method to make sure the data they are storing in the database is safe and as expected before storage.
	 *
	 * @return  boolean  True if the instance is sane and able to be stored in the database.
	 *
	 * @since   1.0.0
	 */
	public function check()
	{
		if (trim($this->title) == '')
		{
			throw new UnexpectedValueException(sprintf('The title is empty'));
		}

		return true;
	}

	/**
	 * Method to set the publishing state for a row or list of rows in the database table.
	 *
	 * The method respects checked out rows by other users and will attempt to checkin rows that it can after adjustments are made.
	 *
	 * @param   mixed    $pks			An optional array of primary key values to update. If not set the instance property value is used.
	 * @param   integer  $published	The publishing state. eg. [0 = unpublished, 1 = published]
	 * @param   integer  $userId		The user ID of the user performing the operation.
	 *
	 * @return  boolean  True on success; false if $pks is empty.
	 *
	 * @since   1.0.0
	 */
	public function publish($pks = null, $published = 1, $userId = 0)
	{
		$k = $this->_tbl_key;

		JArrayHelper::toInteger($pks);

		$publilshed = (int) $published;

		if (empty($pks))
		{
			if ($this->$k)
			{
				$pks = array($this->$k);
			}
			else
			{
				$this->setError(JText::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'));

				return false;
			}
		}

		$where = $k . '=' . implode(' OR ' . $k . ' = ', $pks);

		$query = $this->_db->getQuery(true)
			->update($this->_db->quoteName($this->_tbl))
			->set($this->_db->quoteName('published') . ' = ' . $published)
			->where($where);

		$this->_db->setQuery($query);

		try
		{
			$this->_db->execute();
		}
		catch (RuntimeException $e)
		{
			$this->setError($e->getMessage());

			return false;
		}

		if (in_array($this->$k, $pks))
		{
			$this->published = $published;
		}

		$this->setError('');

		return true;
	}
}

