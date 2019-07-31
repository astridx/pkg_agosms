<?php

/**
 * @package     Articles Good Search
 *
 * @copyright   Copyright (C) 2017 Joomcar extensions. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);

require_once dirname(__FILE__) . '/helper.php';
$helper = new modArticlesGoodSearchHelper($params);

$filters = $params->get('filters');

if (!JPluginHelper::isEnabled('system', 'plg_agosms_search'))
{
	echo "Articles Good Search plugin is not published.<br />";
}

if ($filters == "")
{
	echo "Select search fields in Articles Good Search module parameters! <br />";

	return;
}

if ($params->get('savesearch') && JFactory::getSession()->get("SaveSearchValues"))
{
	$skip = array("option", "task", "view", "Itemid", "search_mode", "dynobox", "field_id", "field_type");

	foreach (JFactory::getSession()->get("SaveSearchValues") as $key => $value)
	{
		if (in_array($key, $skip))
		{
			continue;
		}

		JRequest::setVar($key, $value);
	}
}

$filters_tmp = explode("\r\n", $filters);
$filters = Array();

foreach ($filters_tmp as $k => $filter)
{
	$filter = explode(":", $filter);
	$filters[$k] = new stdClass;

	if ($filter[0] == 'field')
	{
		$instance = $helper->getCustomField($filter[1]);
		$filters[$k]->id = $filter[1];

		if ($filter[2] == "")
		{
			$filter[2] = $instance->type;
		}

		$filters[$k]->type = $filter[2];
		$filters[$k]->instance = $instance;

		// Added for compatibility with radical multifield
		$flt = explode('{', $filters_tmp[$k], 2);

		if (!empty($flt[1]) && $flt[1] != '')
		{
			$filters[$k]->extra_params = '{' . $flt[1];
		}
	}
	else
	{
		$filters[$k]->id = '1000' . $k;
		$filters[$k]->type = $filter[0];
	}
}

require JModuleHelper::getLayoutPath('mod_agosms_search', $params->get('module_template', 'Default') . '/template');


