<?php
/**
 * @package     Joomla.Site
 * @subpackage  pkg_agosms
 *
 * @copyright   Copyright (C) 2019 Astrid Günther, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later;
 * @link        astrid-guenther.de
 */

namespace AG\Module\Agosm\Site\Helper;

\defined('_JEXEC') or die;

use Joomla\CMS\Access\Access;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Date\Date;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Router\Route;
use Joomla\Component\Agsoms\Administrator\Extension\AgosmsComponent;
use Joomla\Component\Agosms\Site\Helper\RouteHelper;
use Joomla\String\StringHelper;
use Joomla\Component\Fields\Administrator\Helper\FieldsHelper;

/**
 * Helper for mod_agosm_category
 *
 * @since  1.6
 */
abstract class AgosmsCategoryHelper
{
	/**
	 * Get a list of agosms from a specific category
	 *
	 * @param   \Joomla\Registry\Registry  &$params  object holding the models parameters
	 *
	 * @return  mixed
	 *
	 * @since  1.6
	 */
	public static function getList(&$params)
	{
		$app = Factory::getApplication();
		$factory = $app->bootComponent('com_agosms')->getMVCFactory();

		// Get an instance of the generic agosms model
		$agosms = $factory->createModel('Agosms', 'Site', ['ignore_request' => true]);

		$catids = $params->get('catid', array());
		$agosms->setState('filter.category_id', $catids);

		// Access filter
		$access = !ComponentHelper::getParams('com_agsoms')->get('show_noauth');
		$authorised = Access::getAuthorisedViewLevels(Factory::getUser()->get('id'));
		$agosms->setState('filter.access', $access);

		$items = $agosms->getItems();

		return $items;
	}
	/**
	 * Get a list of agosms from a custom field
	 *
	 * @param   \Joomla\Registry\Registry  &$params  object holding the models parameters
	 *
	 * @return  mixed
	 *
	 * @since  1.6
	 */
	public static function getListCustomField(&$params)
	{

		$app = Factory::getApplication();
		$factory = $app->bootComponent('com_content')->getMVCFactory();

		// Get an instance of the generic articles model
		$articles = $factory->createModel('Articles', 'Site', ['ignore_request' => true]);

		// Set application parameters in model
		$input = $app->input;
		$appParams = $app->getParams();
		$articles->setState('params', $appParams);

		$articles->setState('list.start', 0);
		//$articles->setState('filter.published', ContentComponent::CONDITION_PUBLISHED);

		// Set the filters based on the module params
		$articles->setState('list.limit', (int) $params->get('count', 0));
		$articles->setState('load_tags', $params->get('show_tags', 0) || $params->get('article_grouping', 'none') === 'tags');

		// Access filter
		$access = !ComponentHelper::getParams('com_content')->get('show_noauth');
		$authorised = Access::getAuthorisedViewLevels(Factory::getUser()->get('id'));
		$articles->setState('filter.access', $access);

		$items = $articles->getItems();
		$itemsfiltered = [];

		foreach ($items as $key => $item) {
			$fields = FieldsHelper::getFields('com_content.article', $item, true);

			$fieldsData = [];
	
			if (!empty($fields)) {
				foreach ($fields as $key => $field) {
					$itemfiltered = new \stdClass;

					if ($field->title == 'lat, lon') {
						$itemfiltered->cords = $field->value;
						$test = explode(",", $itemfiltered->cords);

						if (is_numeric($test[0]) && is_numeric($test[1])) {
							$itemfiltered->title = $item->title;
							$itemfiltered->id = $item->id;
							$itemfiltered->type = $field->type;
						}
					}

					if ($field->type == 'agosmsmarker') {
						$itemfiltered->cords = $field->value;
						$test = explode(",", $itemfiltered->cords);

						if (is_numeric($test[0]) && is_numeric($test[1])) {
							$itemfiltered->title = $item->title;
							$itemfiltered->id = $item->id;
							$itemfiltered->type = $field->type;
						}
					}

					if ($field->type == 'agosmsaddressmarker') {
						// Get plugin parameters
						$popup = $field->fieldparams->get('popup', '0');
						$specialicon = $field->fieldparams->get('specialicon', '0');

						$itemfiltered->cords = $field->rawvalue;
						$test = explode(",", $itemfiltered->cords);

						if (sizeof($test) > 5 && is_numeric($test[0]) && is_numeric($test[1])) {
							$itemfiltered->title = $item->title;
							$itemfiltered->id = $item->id;
							$itemfiltered->type = $field->type;
							$itemfiltered->lat = $test[0];
							$itemfiltered->lon = $test[1];

							if ($specialicon) {
								$itemfiltered->iconcolor = $test[2];
								$itemfiltered->markercolor = $test[3];
								$itemfiltered->icon = $test[4];
							}

							if ($popup) {
								$itemfiltered->popuptext = $test[5];
							}
						}
					}

					$itemsfiltered[] = $itemfiltered;
				}
				if ($itemsfiltered) {
					return $itemsfiltered;
				}
			}
		}

		return [];
	}

	/**
	 * Get one agosms
	 *
	 * @param   \Joomla\Registry\Registry  &$params  object holding the models parameters
	 *
	 * @return  mixed
	 *
	 * @since  1.6
	 */
	public static function getListone(&$params)
	{
		$app = Factory::getApplication();
		$factory = $app->bootComponent('com_agosms')->getMVCFactory();

		// Get an instance of the generic agosms model
		$agosm = $factory->createModel('Agosm', 'Site', ['ignore_request' => true]);

		$id = $params->get('showcomponentpinoneid', null);

		$items = $agosm->getItem((int)$id);

		return $items;
	}

	/**
	 * Get one agosms
	 *
	 * @param   \Joomla\Registry\Registry  &$params  object holding the models parameters
	 *
	 * @return  mixed
	 *
	 * @since  1.6
	 */
	public static function getListExternaldb(&$params)
	{
		$options = [
			'driver' => 'mysqli',
			'host' => 'localhost',
			'user' => 'root',
			'password' => 'Schweden1!',
			'database' => 'joomla_db'
		];

		$externalDb = JDatabaseDriver::getInstance($options);
		$query = $externalDb->getQuery(true);

		$query->select($externalDb->quoteName(['*']));
		$query->from($externalDb->quoteName('j3_agosms'));

		// Reset the query using our newly populated query object.
		$externalDb->setQuery($query);

		// Load the results as a list of stdClass objects (see later for more options on retrieving data).
		$results = $externalDb->loadObjectList();

		return $results;
	}
}
