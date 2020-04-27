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

use Joomla\Utilities\ArrayHelper;

/**
 * Editors list controller class.
 *
 * @since  1.0.0
 */
class GutenbergControllerEditors extends JControllerAdmin
{
	public function getModel($name = 'Editor', $prefix = 'GutenbergModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}
}

