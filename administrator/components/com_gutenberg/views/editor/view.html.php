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
 * View to edit the Editor.
 *
 * @since  1.0.0
 */
class GutenbergViewEditor extends JViewLegacy
{
	/**
	 * The active item
	 *
	 * @var  	object
	 * @since	1.0.0
	 */
	protected $item;

	/**
	 * The JForm object
	 *
	 * @var  	JForm
	 * @since	1.0.0
	 */
	protected $form;

	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise an Error object.
	 *
	 * @since   1.0.0
	 */
	public function display($tpl = null)
	{
		$this->item = $this->get('Item');
		$this->form = $this->get('Form');

		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode('<br>', $errors));

			return false;
		}

		$this->addToolbar();

		return parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   1.0.0
	 */
	protected function addToolbar()
	{
		$input = JFactory::getApplication()->input;
		$input->set('hidemainmenu', true);

		$user 		= JFactory::getUser();
		$userId 	= $user->get('id');
		$isNew 	= $this->item->id == 0;
		$canDo 	= GutenbergHelper::getActions('com_gutenberg', 'component');

		JToolbarHelper::title(JText::_('COM_GUTENBERG_EDITOR_TITLE_' . ($isNew ? 'ADD' : 'EDIT')), '');

		if ($canDo->get('core.edit'))
		{
			JToolbarHelper::apply('editor.apply', 'JTOOLBAR_APPLY');
			JToolbarHelper::save('editor.save', 'JTOOLBAR_SAVE');
			JToolbarHelper::save2new('editor.save2new');
			JToolbarHelper::save2copy('editor.save2copy');
		}

		JToolbarHelper::cancel('editor.cancel', 'JTOOLBAR_CLOSE');
	}
}

