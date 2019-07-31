<?php
/**
 * @package     Joomla.Site
 * @subpackage  pkg_agosms
 *
 * @copyright   Copyright (C) 2005 - 2019 Astrid Günther, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later;
 * @link        astrid-guenther.de
 */

defined('_JEXEC') or die;

/**
 * Routing class from com_agosms
 *
 * @since  1.0.40
 */
class AgosmsRouter extends JComponentRouterBase
{
	/**
	 * Build the route for the com_agosms component
	 *
	 * @param   array  &$query  An array of URL arguments
	 *
	 * @return  array  The URL arguments to use to assemble the subsequent URL.
	 *
	 * @since   1.0.40
	 */
	public function build(&$query)
	{
		$segments = array();

		// Get a menu item based on Itemid or currently active
		$app = JFactory::getApplication();
		$menu = $app->getMenu();
		$params = JComponentHelper::getParams('com_agosms');
		$advanced = $params->get('sef_advanced_link', 0);

		// We need a menu item.  Either the one specified in the query, or the current active one if none specified
		if (empty($query['Itemid']))
		{
			$menuItem = $menu->getActive();
		}
		else
		{
			$menuItem = $menu->getItem($query['Itemid']);
		}

		$mView = (empty($menuItem->query['view'])) ? null : $menuItem->query['view'];
		$mId = (empty($menuItem->query['id'])) ? null : $menuItem->query['id'];

		if (isset($query['view']))
		{
			$view = $query['view'];

			if (empty($query['Itemid']) || empty($menuItem) || $menuItem->component != 'com_agosms')
			{
				$segments[] = $query['view'];
			}

			// We need to keep the view for forms since they never have their own menu item
			if ($view != 'form')
			{
				unset($query['view']);
			}
		}

		// Are we dealing with an agosm that is attached to a menu item?
		if (isset($query['view']) && ($mView == $query['view']) && (isset($query['id'])) && ($mId == (int) $query['id']))
		{
			unset($query['view']);
			unset($query['catid']);
			unset($query['id']);

			return $segments;
		}

		if (isset($view) && ($view == 'category' || $view == 'agosm'))
		{
			if ($mId != (int) $query['id'] || $mView != $view)
			{
				if ($view == 'agosm' && isset($query['catid']))
				{
					$catid = $query['catid'];
				}
				elseif (isset($query['id']))
				{
					$catid = $query['id'];
				}

				$menuCatid = $mId;
				$categories = JCategories::getInstance('Agosms');
				$category = $categories->get($catid);

				if ($category)
				{
					// TODO Throw error that the category either not exists or is unpublished
					$path = $category->getPath();
					$path = array_reverse($path);

					$array = array();

					foreach ($path as $id)
					{
						if ((int) $id == (int) $menuCatid)
						{
							break;
						}

						if ($advanced)
						{
							list($tmp, $id) = explode(':', $id, 2);
						}

						$array[] = $id;
					}

					$segments = array_merge($segments, array_reverse($array));
				}

				if ($view == 'agosm')
				{
					if ($advanced)
					{
						list($tmp, $id) = explode(':', $query['id'], 2);
					}
					else
					{
						$id = $query['id'];
					}

					$segments[] = $id;
				}
			}

			unset($query['id']);
			unset($query['catid']);
		}

		if (isset($query['layout']))
		{
			if (!empty($query['Itemid']) && isset($menuItem->query['layout']))
			{
				if ($query['layout'] == $menuItem->query['layout'])
				{
					unset($query['layout']);
				}
			}
			else
			{
				if ($query['layout'] == 'default')
				{
					unset($query['layout']);
				}
			}
		}

		$total = count($segments);

		for ($i = 0; $i < $total; $i++)
		{
			$segments[$i] = str_replace(':', '-', $segments[$i]);
		}

		return $segments;
	}

	/**
	 * Parse the segments of a URL.
	 *
	 * @param   array  &$segments  The segments of the URL to parse.
	 *
	 * @return  array  The URL attributes to be used by the application.
	 *
	 * @since   1.0.40
	 */
	public function parse(&$segments)
	{
		$total = count($segments);
		$vars = array();

		for ($i = 0; $i < $total; $i++)
		{
			$segments[$i] = preg_replace('/-/', ':', $segments[$i], 1);
		}

		// Get the active menu item.
		$app = JFactory::getApplication();
		$menu = $app->getMenu();
		$item = $menu->getActive();
		$params = JComponentHelper::getParams('com_agosms');
		$advanced = $params->get('sef_advanced_link', 0);

		// Count route segments
		$count = count($segments);

		// Standard routing for agosms.
		if (!isset($item))
		{
			$vars['view'] = $segments[0];
			$vars['id'] = $segments[$count - 1];

			return $vars;
		}

		// From the categories view, we can only jump to a category.
		$id = (isset($item->query['id']) && $item->query['id'] > 1) ? $item->query['id'] : 'root';

		$category = JCategories::getInstance('Agosms')->get($id);

		$categories = $category->getChildren();
		$found = 0;

		foreach ($segments as $segment)
		{
			foreach ($categories as $category)
			{
				if (($category->slug == $segment) || ($advanced && $category->alias == str_replace(':', '-', $segment)))
				{
					$vars['id'] = $category->id;
					$vars['view'] = 'category';
					$categories = $category->getChildren();
					$found = 1;

					break;
				}
			}

			if ($found == 0)
			{
				if ($advanced)
				{
					$db = JFactory::getDbo();
					$query = $db->getQuery(true)
						->select($db->quoteName('id'))
						->from('#__agosms')
						->where($db->quoteName('catid') . ' = ' . (int) $vars['catid'])
						->where($db->quoteName('alias') . ' = ' . $db->quote(str_replace(':', '-', $segment)));
					$db->setQuery($query);
					$id = $db->loadResult();
				}
				else
				{
					$id = $segment;
				}

				$vars['id'] = $id;
				$vars['view'] = 'agosm';

				break;
			}

			$found = 0;
		}

		return $vars;
	}
}

/**
 * Agosms router functions
 *
 * @param   array  &$query  An array of URL arguments
 *
 * @return  array  The URL arguments to use to assemble the subsequent URL.
 *
 * Note. These functions are proxies for the new router interface
 * for old SEF extensions.
 *
 * @deprecated  4.0  Use Class based routers instead
 */
function AgosmsBuildRoute(&$query)
{
	$router = new AgosmsRouter;

	return $router->build($query);
}

/**
 * Agosms router functions
 *
 * @param   array  $segments  The segments of the URL to parse.
 *
 * @return  array  The URL attributes to be used by the application.
 *
 * Note. These functions are proxies for the new router interface
 * for old SEF extensions.
 *
 * @deprecated  4.0  Use Class based routers instead
 */
function AgosmsParseRoute($segments)
{
	$router = new AgosmsRouter;

	return $router->parse($segments);
}
