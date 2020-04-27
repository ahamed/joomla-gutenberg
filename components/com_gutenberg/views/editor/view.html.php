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
 * View to Detail Page.
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
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise an Error object.
	 *
	 * @since   1.0.0
	 */
	public function display($tpl = null) {
		$model      = $this->getModel();
		$this->item = $this->get('Item');

		return parent::display($tpl = null);
	}

	/**
	 * Parse contents before rendering.
	 *
	 * @param	string	$content	The contents
	 *
	 * @return 	parsed content
	 */
	public function parseBeforeRender($content)
	{
		$replacer = array(
			'startFind' => "#<!--\s+wp:.+\s+\{?.*\}?\s*-->#",
			'startReplace' => '',
			'endFind' => "#<!-- \/wp:.+\s*-->#",
			'endReplace' => ''
		);

		$content = preg_replace($replacer['startFind'], $replacer['startReplace'], $content);
		$content = preg_replace($replacer['endFind'], $replacer['endReplace'], $content);

		return $content;
	}
}

