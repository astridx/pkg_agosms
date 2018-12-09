<?php
/**
 * @package     Joomla.Site
 * @subpackage  pkg_agosm
 *
 * @copyright   Copyright (C) 2005 - 2018 Astrid Günther, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later;
 * @link        astrid-guenther.de
 */

defined('_JEXEC') or die('');

require_once JPATH_SITE . '/components/com_agosms/helpers/route.php';
require_once JPATH_SITE . '/components/com_agosms/helpers/category.php';

JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_agosms/models', 'AgosmsModel');

/**
 * Helper for mod_agosm
 *
 * @package     Joomla.Site
 * @subpackage  mod_agosm
 * @since       1.0.4
 */
class ModagosmHelper
	{

	/**
	 * Show online member names
	 *
	 * @param   mixed  &$params  The parameters set in the administrator backend
	 *
	 * @return  mixed   Null if no agosms based on input parameters else an array containing all the agosms.
	 *
	 * @since   1.5
	 * */
	public static function getCategory(&$params)
	{
		// Get an instance of the generic articles model
		$model = JModelLegacy::getInstance('Category', 'AosmsModel', array('ignore_request' => true));

		// Set application parameters in model
		$app = JFactory::getApplication();
		$appParams = $app->getParams();
		$model->setState('params', $appParams);

		// Set the filters based on the module params
		$model->setState('list.start', 0);
		$model->setState('list.limit', (int) $params->get('count', 5));

		/* if ($params->get('test', 1))
		  {
		  $model->setState('filter.begin_ende', 0);
		  }
		  else
		  {
		  $model->setState('filter.begin_ende', 1);
		  } */

		$model->setState('filter.state', 1);
		$model->setState('filter.publish_date', true);

		// Access filter
		$access = !JComponentHelper::getParams('com_agosms')->get('show_noauth');
		$model->setState('filter.access', $access);

		$ordering = $params->get('ordering', 'ordering');
		$model->setState('list.ordering', $ordering == 'order' ? 'ordering' : $ordering);
		$model->setState('list.direction', $params->get('direction', 'asc'));

		$catid = (int) $params->get('catid', 0);
		$model->setState('category.id', $catid);
		$model->setState('category.group', $params->get('groupby', 0));
		$model->setState('category.ordering', $params->get('groupby_ordering', 'c.lft'));
		$model->setState('category.direction', $params->get('groupby_direction', 'ASC'));

		// Create query object
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$case_when1 = ' CASE WHEN ';
		$case_when1 .= $query->charLength('a.alias', '!=', '0');
		$case_when1 .= ' THEN ';
		$a_id = $query->castAsChar('a.id');
		$case_when1 .= $query->concatenate(array($a_id, 'a.alias'), ':');
		$case_when1 .= ' ELSE ';
		$case_when1 .= $a_id . ' END as slug';

		$case_when2 = ' CASE WHEN ';
		$case_when2 .= $query->charLength('c.alias', '!=', '0');
		$case_when2 .= ' THEN ';
		$c_id = $query->castAsChar('c.id');
		$case_when2 .= $query->concatenate(array($c_id, 'c.alias'), ':');
		$case_when2 .= ' ELSE ';
		$case_when2 .= $c_id . ' END as catslug';

		$model->setState(
				'list.select', 'a.*, c.published AS c_published,' . $case_when1 . ',' . $case_when2 . ',' . 'DATE_FORMAT(a.created, "%Y-%m-%d") AS created'
		);

		$model->setState('filter.c.published', 1);

		// Filter by language
		$model->setState('filter.language', $app->getLanguageFilter());

		$items = $model->getItems();

		if ($items)
		{
			foreach ($items as $item)
			{
				$category = $model->getCategory($item->id);
				break;
			}
			return $category;
		}

		return;
	}

	/**
	 * Show online member names
	 *
	 * @param   mixed  &$params  The parameters set in the administrator backend
	 *
	 * @return  mixed   Null if no agosms based on input parameters else an array containing all the agosms.
	 *
	 * @since   1.5
	 * */
	public static function getList(&$params)
	{
		// Get an instance of the generic articles model
		$model = JModelLegacy::getInstance('Category', 'AgosmsModel', array('ignore_request' => true));

		// Set application parameters in model
		$app = JFactory::getApplication();
		$appParams = $app->getParams();
		$model->setState('params', $appParams);

		// Set the filters based on the module params
		$model->setState('list.start', 0);
		//$model->setState('list.limit', (int) $params->get('count', 5));

		/* if ($params->get('test', 1))
		  {
		  $model->setState('filter.begin_ende', 0);
		  }
		  else
		  {
		  $model->setState('filter.begin_ende', 1);
		  } */

		$model->setState('filter.state', 1);
		$model->setState('filter.publish_date', true);

		// Access filter
		$access = !JComponentHelper::getParams('com_agosms')->get('show_noauth');
		$model->setState('filter.access', $access);

		$ordering = $params->get('ordering', 'ordering');
		$model->setState('list.ordering', $ordering == 'order' ? 'ordering' : $ordering);
		$model->setState('list.direction', $params->get('direction', 'asc'));

		$catid = (int) $params->get('catid', 0);
		$model->setState('category.id', $catid);
		$model->setState('category.group', $params->get('groupby', 0));
		$model->setState('category.ordering', $params->get('groupby_ordering', 'c.lft'));
		$model->setState('category.direction', $params->get('groupby_direction', 'ASC'));

		// Create query object
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
/*
		$case_when1 = ' CASE WHEN ';
		$case_when1 .= $query->charLength('a.alias', '!=', '0');
		$case_when1 .= ' THEN ';
		$a_id = $query->castAsChar('a.id');
		$case_when1 .= $query->concatenate(array($a_id, 'a.alias'), ':');
		$case_when1 .= ' ELSE ';
		$case_when1 .= $a_id . ' END as slug';

		$case_when2 = ' CASE WHEN ';
		$case_when2 .= $query->charLength('c.alias', '!=', '0');
		$case_when2 .= ' THEN ';
		$c_id = $query->castAsChar('c.id');
		$case_when2 .= $query->concatenate(array($c_id, 'c.alias'), ':');
		$case_when2 .= ' ELSE ';
		$case_when2 .= $c_id . ' END as catslug';

		$model->setState(
				'list.select', 'a.*, c.published AS c_published,' . $case_when1 . ',' . $case_when2 . ',' . 'DATE_FORMAT(a.created, "%Y-%m-%d") AS created'
		);

		$model->setState('filter.c.published', 1);

		// Filter by language
		$model->setState('filter.language', $app->getLanguageFilter());
*/
		$items = $model->getItems();

		if ($items)
		{
/*			foreach ($items as $item)
			{
				if ($item->params->get('count_clicks', $params->get('count_clicks')) == 1)
				{
					$item->link = JRoute::_('index.php?option=com_agosms&task=agosm.go&view=agosm&id=' . $item->slug);
					$item->link_notactive = JRoute::_('index.php?option=com_agosms&view=agosm_no&id=' . $item->slug);
					$item->link_popup = JRoute::_('index.php?tmpl=component&option=com_agosms&task=agosm.go&view=agosm&id=' . $item->slug);
					$item->link_notactive_popup = JRoute::_('index.php?tmpl=component&option=com_agosms&view=agosm_no&id=' . $item->slug);
				} else
				{
					$item->link = JRoute::_('index.php?option=com_agosms&view=agosm&id=' . $item->slug);
					$item->link_notactive = JRoute::_('index.php?option=com_agosms&view=agosm_no&id=' . $item->slug);
					$item->link_popup = JRoute::_('index.php?option=com_agosms&tmpl=component&view=agosm&id=' . $item->slug);
					$item->link_notactive_popup = JRoute::_('index.php?option=com_agosms&tmpl=component&view=agosm_no&id=' . $item->slug);
				}
			}*/

			return $items;
		}

		return;
	}
}
