<?php
/**
 * @package     Joomla.Site
 * @subpackage  pkg_agosms
 *
 * @copyright   Copyright (C) 2005 - 2019 Astrid Günther, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later;
 * @link        astrid-guenther.de
 */

// No direct access
defined('_JEXEC') or die;

require_once __DIR__ . '/helper.php';
$helper = new modAgosmsSearchHelper($params);

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

$filters = $params->get('filters');

if (!JPluginHelper::isEnabled('system', 'agosmssearch'))
{
	echo JText::_('MOD_AGOSMS_PLUGIN_NOT_PUBLISHED');
}

if ($filters == "")
{
	echo JText::_('MOD_AGOSMS_PLUGIN_NO_FIELDS');

	return;
}

if ($params->get('savesearch') && JFactory::getSession()->get("SaveSearchValues"))
{
	$skip = array(
		"option",
		"task",
		"view",
		"Itemid",
		"search_mode",
		"field_id",
		"field_type"
	);

	foreach (JFactory::getSession()->get("SaveSearchValues") as $key => $value)
	{
		if (in_array($key, $skip))
		{
			continue;
		}

		JFactory::getApplication()->input->set($key, $value);
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


// Map

// Include skripts/styles to the header
$document = JFactory::getDocument();

$leafletIsLoaded = false;

foreach ($document->_scripts as $key => $script)
{
	$leafletPath = "leaflet/leaflet.js";

	if (strpos($key, $leafletPath))
	{
		$leafletIsLoaded = true;
	}
}

if ($params->get('show_map', "1") === "1")
{
	if (!$leafletIsLoaded)
	{
		$document->addStyleSheet(JURI::root(true) . '/media/mod_agosms_search/leaflet/leaflet.css');
		$document->addScript(JURI::root(true) . '/media/mod_agosms_search/leaflet/leaflet.js');
	}

	$document->addStyleSheet(JURI::root(true) . '/media/mod_agosms_search/cluster/MarkerCluster.css');
	$document->addStyleSheet(JURI::root(true) . '/media/mod_agosms_search/cluster/MarkerCluster.Default.css');
	$document->addScript(JURI::root(true) . '/media/mod_agosms_search/cluster/leaflet.markercluster-src.js');
	$document->addScript(JURI::root(true) . '/media/mod_agosms_search/js/agosm_search.js');
	$document->addStyleSheet(JURI::root(true) . '/media/mod_agosms_search/css/agosms_search.css');
}

require JModuleHelper::getLayoutPath('mod_agosms_search', $params->get('module_template', 'Default') . '/template');
