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

if (file_exists(JPATH_COMPONENT . '/vendor/autoload.php'))
{
	include JPATH_COMPONENT . '/vendor/autoload.php';
}

if (!JFactory::getUser()->authorise('core.manage', 'com_gutenberg'))
{
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

if (file_exists(JPATH_COMPONENT . '/helpers/gutenberg.php'))
{
	JLoader::register('GutenbergHelper', JPATH_COMPONENT . '/helpers/gutenberg.php');
}

// Load basic css file
$doc = JFactory::getDocument();
$doc->addStyleSheet(JUri::root(true) . '/administrator/components/com_gutenberg/assets/css/style.css');

// Execute the task.
$controller = JControllerLegacy::getInstance('Gutenberg');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();

