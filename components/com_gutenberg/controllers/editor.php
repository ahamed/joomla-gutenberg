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
 * The Page controller.
 *
 * @since  1.0.0
 */
class GutenbergControllerEditor extends JControllerForm
{
	/**
	 * Get model instance
	 *
	 * @param	string	$name		View name
	 * @param	string	$prefix	Model prefix
	 * @param	array	$config	Configuration array
	 *
	 * @return 	JModel	Model Instance
	 * @since	1.0.0
	 */
	public function getModel($name = 'Editor', $prefix = 'GutenbergController', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}
}

