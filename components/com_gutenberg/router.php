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
 * Routing class of com_gutenberg
 *
 * @since	1.0.0
 */
class GutenbergRouter extends JComponentRouterView
{
	/**
	 * This is for checking if the sef url allowes ID on the url or not.
	 *
	 * @var		boolean		$noIDs		No ID on the url
	 * @since	1.0.0
	 */
	protected $noIDs = false;

	/**
	 * Content Component router constructor
	 *
	 * @param   JApplicationCms  $app   The application object
	 * @param   JMenu            $menu  The menu object to work with
	 */
	public function __construct($app = null, $menu = null)
	{
		$params = JComponentHelper::getParams('com_gutenberg');
		$this->noIDs = (bool) $params->get('sef_ids', 1);

		// Register your views here

		parent::__construct($app, $menu);

		$this->attachRule(new JComponentRouterRulesNomenu($this));

		if ($params->get('sef_advanced', 0))
		{
			$this->attachRule(new JComponentRouterRulesMenu($this));
			$this->attachRule(new JComponentRouterRulesStandard($this));
		}
		else
		{
			JLoader::register('GutenbergRouterRulesLegacy', __DIR__ . '/helpers/legacyrouter.php');
			$this->attachRule(new GutenbergRouterRulesLegacy($this));
		}
	}
}

/**
 * Gutenberg router functions
 *
 * These functions are proxys for the new router interface
 * for old SEF extensions.
 *
 * @param   array  $query  An array of URL arguments
 *
 * @return  array  The URL arguments to use to assemble the subsequent URL.
 *
 * @deprecated  4.0  Use Class based routers instead
 */
function gutenbergBuildRoute(&$query)
{
	$app 		= JFactory::getApplication();
	$router 	= new GutenbergRouter($app, $app->getMenu());

	return $router->build($query);
}

/**
 * Parse the segments of a URL.
 *
 * This function is a proxy for the new router interface
 * for old SEF extensions.
 *
 * @param   array  $segments  The segments of the URL to parse.
 *
 * @return  array  The URL attributes to be used by the application.
 *
 * @since   	3.3
 * @deprecated  4.0  Use Class based routers instead
 */
function gutenbergParseRoute($segments)
{
	$app 		= JFactory::getApplication();
	$router 	= new GutenbergRouter($app, $app->getMenu());

	return $router->parse($segments);
}

