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
 * Legacy routing rules class from com_gutenberg
 *
 * @since       3.6
 * @deprecated  4.0
 */
class GutenbergRouterRulesLegacy implements JComponentRouterRulesInterface
{
	/**
	 * Constructor for this legacy router
	 *
	 * @param	JComponentRouterView  $router  The router this rule belongs to
	 *
	 * @since       3.6
	 * @deprecated  4.0
	 */
	public function __construct($router)
	{
		$this->router = $router;
	}

	/**
	 * Preprocess the route for the com_gutenberg component
	 *
	 * @param   array  $query  An array of URL arguments
	 *
	 * @return  void
	 *
	 * @since       3.6
	 * @deprecated  4.0
	 */
	public function preprocess(&$query)
	{
	}

	/**
	 * Build the route for the com_gutenberg component
	 *
	 * @param   array  $query     An array of URL arguments
	 * @param   array  $segments  The URL arguments to use to assemble the subsequent URL.
	 *
	 * @return  void
	 *
	 * @since       3.6
	 * @deprecated  4.0
	 */
	public function build(&$query, &$segments)
	{
		$params 	= JComponentHelper::getParams('com_gutenberg');
		$advanced 	= $params->get('sef_advanced_link', 0);

		if (empty($query['Itemid']))
		{
			$menuItem 		= $this->router->menu->getActive();
			$menuItemGiven = false;
		}
		else
		{
			$menuItem 		= $this->router->menu->getItem($query['Itemid']);
			$menuItemGiven = true;
		}

		// Check again
		if ($menuItemGiven && isset($menuItem) && $menuItem->component !== 'com_gutenberg')
		{
			$menuItemGiven = false;
			unset($query['Itemid']);
		}

		if (isset($query['view']))
		{
			$view = $query['view'];
		}
		else
		{
			// We need to have a view in the query or it is an invalid URL
			return;
		}

		if ($menuItem !== null
			&& isset($menuItem->query['view'], $query['view'], $menuItem->query['id'], $query['id'])
			&& $menuItem->query['view'] == $query['view']
			&& $menuItem->query['id'] == (int) $query['id'])
		{
			unset($query['view']);

			if (isset($query['catid']))
			{
				unset($query['catid']);
			}

			if (isset($query['layout']))
			{
				unset($query['layout']);
			}

			unset($query['id']);

			return;
		}

		foreach ($segments as $i => &$segment)
		{
			$segment = str_replace(':', '-', $segment);
		}
    }

	/**
	 * Parse the segments of a URL.
	 *
	 * @param   array  $segments  The segments of the URL to parse.
	 * @param   array  $vars      The URL attributes to be used by the application.
	 *
	 * @return  void
	 *
	 * @since       3.6
	 * @deprecated  4.0
	 */
	public function parse(&$segments, &$vars)
	{
		$total = count($segments);

		for ($i = 0; $i < $total; $i++)
		{
			$segments[$i] = preg_replace('/-/', ':', $segments[$i], 1);
		}

		// Get the active menu item.
		$item 		= $this->router->menu->getActive();
		$params 	= JComponentHelper::getParams('com_gutenberg');
		$advanced 	= $params->get('sef_advanced_link', 0);
		$db 		= JFactory::getDbo();

		// Count route segments
		$count = count($segments);

		if (!isset($item))
		{
			$vars['view'] 	= $segments[0];
			$vars['id']	= $segments[$count - 1];

			return;
		}
	}
}

