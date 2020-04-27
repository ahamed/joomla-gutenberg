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

// Helper and model
$com_helper = JPATH_BASE . '/components/com_gutenberg/helpers/helper.php';

if (file_exists($com_helper))
{
    require_once $com_helper;
}
else
{
	echo '<p class="alert alert-warning">' . JText::_('COM_GUTENBERG_COMPONENT_NOT_INSTALLED_OR_MISSING_FILE') . '</p>';

	return;
}


JHtml::_('jquery.framework');
$doc = JFactory::getDocument();

// Include CSS files
$doc->addStylesheet(JURI::root(true) . '/components/com_gutenberg/assets/css/style.css');


$controller	= JControllerLegacy::getInstance('Gutenberg');
$input			= JFactory::getApplication()->input;
$view			= $input->getCmd('view', 'default');
$input->set('view', $view);
$controller->execute($input->getCmd('task'));
$controller->redirect();

