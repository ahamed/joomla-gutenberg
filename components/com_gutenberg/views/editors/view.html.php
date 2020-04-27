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
	 * The pagination object
	 *
	 * @var  	JPagination
	 * @since	1.0.0
	 */
    protected $pagination;

    /**
	 * Display the view
	 *
	 * @param   string    The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise an Error object.
	 * @since	1.0.0
	 */
    public function display($tpl = null) {
        $model 			= $this->getModel();
		$this->items		= $this->get('Items');

		if (!empty($this->items))
		{
			foreach ($this->items as $item)
			{
				$item->url = JRoute::_('index.php?option=com_gutenberg&view=editor&id=' . $item->id);
			}
		}

        return parent::display($tpl = null);
    }
}

