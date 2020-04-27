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
 * Gutenberg Component Admin Controller
 *
 * @since    1.0.0
 */
class GutenbergController extends JControllerLegacy
{
	/**
	 * Method to display a view.
	 *
	 * @param   boolean  $cachable   If true, the view output will be cached.
	 * @param   boolean  $urlparams  An array of safe URL parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return  JController  This object to support chaining.
	 *
	 * @since   1.5
	 */
	public function display($cachable = false, $urlparams = false)
	{
		$view   = $this->input->get('view', 'editors');
		$layout = $this->input->get('layout', 'default');
		$id     = $this->input->getInt('id');
		$this->input->set('view', $view);

		if ($view == 'editor'
			&& $layout == 'edit'
			&& !$this->checkEditId('com_gutenberg.edit.editor', $id))
		{
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
			$this->setMessage($this->getError(), 'error');
			$this->setRedirect(JRoute::_('index.php?option=com_gutenberg&view=editors', false));

			return false;
		}

		parent::display($cachable, $urlparams);

		return $this;
	}
}

