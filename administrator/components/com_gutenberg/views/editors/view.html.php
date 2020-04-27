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
 * View to list of Editors.
 *
 * @since  1.0.0
 */
class GutenbergViewEditors extends JViewLegacy
{
	/**
	 * An array of items
	 *
	 * @var  	array
	 * @since	1.0.0
	 */
	protected $items;

	/**
	 * The model state
	 *
	 * @var  	object
	 * @since	1.0.0
	 */
	protected $state;

	/**
	 * The pagination object
	 *
	 * @var  	JPagination
	 * @since	1.0.0
	 */
	protected $pagination;

	/**
	 * The model class
	 *
	 * @var		JModel
	 * @since	1.0.0
	 */
	protected $model;

	/**
	 * Form object for search filters
	 *
	 * @var  	JForm
	 * @since	1.0.0
	 */
	public $filterForm;

	/**
	 * The active search filters
	 *
	 * @var  	array
	 * @since	1.0.0
	 */
	public $activeFilters;

	/**
	 * Display the view
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise an Error object.
	 * @since	1.0.0
	 */
	public function display($tpl = null)
	{
		$this->items			= $this->get('Items');
		$this->state			= $this->get('State');
		$this->pagination		= $this->get('Pagination');
		$this->model			= $this->getModel('editors');
		$this->filterForm		= $this->get('FilterForm');
		$this->activeFilters	= $this->get('ActiveFilters');

		GutenbergHelper::addSubmenu('editors');

		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode('<br>', $errors));

			return false;
		}

		$this->addToolbar();
		$this->sidebar = JHtmlSidebar::render();

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
		$state	= $this->get('State');
		$canDo	= GutenbergHelper::getActions('com_gutenberg', 'component');
		$user	= JFactory::getUser();
		$bar	= JToolbar::getInstance('toolbar');

		if ($canDo->get('core.create'))
		{
			JToolbarHelper::addNew('editor.add');
		}

		if ($canDo->get('core.edit'))
		{
			JToolbarHelper::editList('editor.edit');
		}

		if ($canDo->get('core.edit.state'))
		{
			JToolbarHelper::publish('editors.publish', 'JTOOLBAR_PUBLISH', true);
			JToolbarHelper::unpublish('editors.unpublish', 'JTOOLBAR_UNPUBLISH', true);
			JToolbarHelper::archiveList('editors.archive');
			JToolbarHelper::checkin('editors.checkin');
		}

		if ($state->get('filter.published') === -2 && $canDo->get('core.delete'))
		{
			JToolbarHelper::deleteList('', 'editors.delete', 'JTOOLBAR_EMPTY_TRASH');
		}
		elseif ($canDo->get('core.edit.state'))
		{
			JToolbarHelper::trash('editors.trash');
		}

		if ($canDo->get('core.admin'))
		{
			JToolbarHelper::preferences('com_gutenberg');
		}

		JHtmlSidebar::setAction('index.php?option=com_gutenberg&view=editors');
		JToolbarHelper::title(JText::_('CHANGE_TITLE'),'');
	}
}

