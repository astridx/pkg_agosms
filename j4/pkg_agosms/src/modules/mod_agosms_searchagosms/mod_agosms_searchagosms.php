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
$helper = new modAgosmsSearchagosmsHelper($params);

$filters = $params->get('filters');

if (!JPluginHelper::isEnabled('system', 'agosmssearchagosms')) {
	echo JText::_('MOD_AGOSMS_PLUGIN_NOT_PUBLISHED');
}

if ($filters == "") {
	echo JText::_('MOD_AGOSMS_PLUGIN_NO_FIELDS');

	return;
}

if ($params->get('savesearchagosms') && JFactory::getSession()->get("SaveSearchValues")) {
	$skip = [
		"option",
		"task",
		"view",
		"Itemid",
		"searchagosms_mode",
		"field_id",
		"field_type"
	];

	foreach (JFactory::getSession()->get("SaveSearchValues") as $key => $value) {
		if (in_array($key, $skip)) {
			continue;
		}

		JFactory::getApplication()->input->set($key, $value);
	}
}

$filters_tmp = explode("\r\n", $filters);
$filters = [];

foreach ($filters_tmp as $k => $filter) {
	$filter = explode(":", $filter);
	$filters[$k] = new stdClass;

	if ($filter[0] == 'field') {
		$instance = $helper->getCustomField($filter[1]);
		$filters[$k]->id = $filter[1];

		if ($filter[2] == "") {
			$filter[2] = $instance->type;
		}

		$filters[$k]->type = $filter[2];
		$filters[$k]->instance = $instance;

		// Added for compatibility with radical multifield
		$flt = explode('{', $filters_tmp[$k], 2);

		if (!empty($flt[1]) && $flt[1] != '') {
			$filters[$k]->extra_params = '{' . $flt[1];
		}
	} else {
		$filters[$k]->id = '1000' . $k;
		$filters[$k]->type = $filter[0];
	}
}


// Map

// Include skripts/styles to the header
$document = JFactory::getDocument();

$leafletIsLoaded = false;

foreach ($document->_scripts as $key => $script) {
	$leafletPath = "leaflet/leaflet.js";

	if (strpos($key, $leafletPath)) {
		$leafletIsLoaded = true;
	}
}

if ($params->get('showlocate', "1") == "1") {
	$document->addStyleSheet(JURI::root(true) . '/media/mod_agosms_searchagosms/locate/L.Control.Locate.css');
	$document->addScript(JURI::root(true) . '/media/mod_agosms_searchagosms/locate/L.Control.Locate.min.js');
}

if ($params->get('show_map', "1") === "1") {
	if (!$leafletIsLoaded) {
		$document->addStyleSheet(JURI::root(true) . '/media/mod_agosms_searchagosms/leaflet/leaflet.css');
		$document->addScript(JURI::root(true) . '/media/mod_agosms_searchagosms/leaflet/leaflet.js');
	}

	$document->addStyleSheet(JURI::root(true) . '/media/mod_agosms_searchagosms/cluster/MarkerCluster.css');
	$document->addStyleSheet(JURI::root(true) . '/media/mod_agosms_searchagosms/cluster/MarkerCluster.Default.css');
	$document->addScript(JURI::root(true) . '/media/mod_agosms_searchagosms/cluster/leaflet.markercluster-src.js');
	$document->addScript(JURI::root(true) . '/media/mod_agosms_searchagosms/js/agosm_searchagosms.js');
	$document->addStyleSheet(JURI::root(true) . '/media/mod_agosms_searchagosms/css/agosms_searchagosms.css');
}

require JModuleHelper::getLayoutPath('mod_agosms_searchagosms', $params->get('module_template', 'Default') . '/template');
