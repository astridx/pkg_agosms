<?php
/**
 * @package     Joomla.Site
 * @subpackage  pkg_agosms
 *
 * @copyright   Copyright (C) 2005 - 2019 Astrid Günther, Inc. All rights reserved.
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
 * @since       1.0.40
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
	 * @since   1.0.40
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
	 * @since   1.0.40
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
		$items = $model->getItems();

		if ($items)
		{
			return $items;
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
	 * @since   1.0.40
	 * */
	public static function getListCustomField(&$params)
	{
		// Get an instance of the generic articles model
		$model = JModelLegacy::getInstance('Articles', 'ContentModel', array('ignore_request' => true));

		// Todo check if other compontent than com_content
		if ($model)
		{
			// Set application parameters in model
			$app = JFactory::getApplication();
			$appParams = $app->getParams();
			$model->setState('params', $appParams);

			// Set the filters based on the module params
			$model->setState('list.start', 0);
			$model->setState('filter.state', 1);
			$model->setState('filter.publish_date', true);

			// Access filter
			$access = !JComponentHelper::getParams('com_agosms')->get('show_noauth');
			$model->setState('filter.access', $access);

			$catid = (int) $params->get('catid', 0);
			$model->setState('category.id', $catid);
			$model->setState('category.group', $params->get('groupby', 0));
			$model->setState('category.ordering', $params->get('groupby_ordering', 'c.lft'));
			$model->setState('category.direction', $params->get('groupby_direction', 'ASC'));

			// Create query object
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$items = $model->getItems();
			$itemsfiltered = array();

			if ($items)
			{
				foreach ($items as $key => $item)
				{
					if ($item->state !== "1")
					{
						continue;
					}

					// Get item's fields, also preparing their value property for manual display
					// (calling plugins events and loading layouts to get their HTML display)
					$fields = FieldsHelper::getFields('com_content.article', $item, true);

					$itemfiltered1 = new stdClass;
					$itemfiltered2 = new stdClass;
					$itemfiltered3 = new stdClass;

					foreach ($fields as $key => $field)
					{
						if ($field->title == 'lat, lon')
						{
							$itemfiltered1->cords = $field->value;
							$test = explode(",", $itemfiltered1->cords);

							if (is_numeric($test[0]) && is_numeric($test[1]))
							{
								$itemfiltered1->title = $item->title;
								$itemfiltered1->id = $item->id;
								$itemfiltered1->type = $field->type;
								$itemsfiltered[] = $itemfiltered1;
							}
						}

						if ($field->type == 'agosmsmarker')
						{
							$itemfiltered2->cords = $field->value;
							$test = explode(",", $itemfiltered2->cords);

							if (is_numeric($test[0]) && is_numeric($test[1]))
							{
								$itemfiltered2->title = $item->title;
								$itemfiltered2->id = $item->id;
								$itemfiltered2->type = $field->type;
								$itemsfiltered[] = $itemfiltered2;
							}
						}

						if ($field->type == 'agosmsaddressmarker')
						{
							// Get plugin parameters
							$popup = $field->fieldparams->get('popup', '0');
							$specialicon = $field->fieldparams->get('specialicon', '0');

							$itemfiltered3->cords = $field->rawvalue;
							$test = explode(",", $itemfiltered3->cords);

							if (sizeof($test) > 5 && is_numeric($test[0]) && is_numeric($test[1]))
							{
								$itemfiltered3->title = $item->title;
								$itemfiltered3->id = $item->id;
								$itemfiltered3->type = $field->type;
								$itemfiltered3->lat = $test[0];
								$itemfiltered3->lon = $test[1];

								if ($specialicon)
								{
									$itemfiltered3->iconcolor = $test[2];
									$itemfiltered3->markercolor = $test[3];
									$itemfiltered3->icon = $test[4];
								}

								if ($popup)
								{
									$itemfiltered3->popuptext = $test[5];
								}

								$itemsfiltered[] = $itemfiltered3;
							}
						}
					}
				}
			}

			if ($itemsfiltered)
			{
				return $itemsfiltered;
			}
		}

		return array();
	}

	/**
	 * Show external database
	 *
	 * @param   mixed  &$params  The parameters set in the administrator backend
	 *
	 * @return  mixed   Null if no agosms based on input parameters else an array containing all the agosms.
	 *
	 * @since   1.0.43
	 * */
	public static function getListExternaldb(&$params)
	{
		$options = array(
			'driver' => 'mysqli',
			'host' => 'localhost',
			'user' => 'root',
			'password' => 'Schweden1!',
			'database' => 'joomla_db'
		);

		$externalDb = JDatabaseDriver::getInstance($options);
		$query = $externalDb->getQuery(true);

		$query->select($externalDb->quoteName(array('*')));
		$query->from($externalDb->quoteName('j3_agosms'));

		// Reset the query using our newly populated query object.
		$externalDb->setQuery($query);

		// Load the results as a list of stdClass objects (see later for more options on retrieving data).
		$results = $externalDb->loadObjectList();

		return $results;
	}
}
