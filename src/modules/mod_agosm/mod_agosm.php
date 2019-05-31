<?php
/**
 * @package     Joomla.Site
 * @subpackage  pkg_agosm
 *
 * @copyright   Copyright (C) 2005 - 2018 Astrid Günther, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later;
 * @link        astrid-guenther.de
 */

defined('_JEXEC') or die;

// Include the agosm functions only once
require_once __DIR__ . '/helper.php';

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

// Include skripts/styles to the header
$document = JFactory::getDocument();
$document->addScript(JURI::root(true) . '/media/mod_agosm/js/agosm.js');

$leafletIsLoaded = false;
foreach ($document->_scripts as $key => $script)
{
	$leafletPath = "leaflet/leaflet.js";
	if (strpos($key, $leafletPath))
	{
		$leafletIsLoaded = true;
	}
}
if (!$leafletIsLoaded)
{
	$document->addStyleSheet(JURI::root(true) . '/media/mod_agosm/leaflet/leaflet.css');
	$document->addScript(JURI::root(true) . '/media/mod_agosm/leaflet/leaflet.js');
}

if ($params->get('showgeocoder', '1') == 1)
{
	$document->addStyleSheet(JURI::root(true) . '/media/mod_agosm/css/Control.Geocoder.css');
	$document->addScript(JURI::root(true) . '/media/mod_agosm/js/Control.Geocoder.js');
}

if ($params->get('useesri', '1') == 1)
{
	$document->addScript(JURI::root(true) . '/media/mod_agosm/js/esri-leaflet.js');
	$document->addStyleSheet(JURI::root(true) . '/media/mod_agosm/css/esri-leaflet-geocoder.css');
	$document->addScript(JURI::root(true) . '/media/mod_agosm/js/esri-leaflet-geocoder.js');
}

if ($params->get('showrouting_simple', '1') == 1)
{
	$document->addStyleSheet(JURI::root(true) . '/media/mod_agosm/css/LeafletControlRoutingtoaddress.css');
	$document->addScript(JURI::root(true) . '/media/mod_agosm/js/LeafletControlRoutingtoaddress.js');
}

if ($params->get('showrouting_simple', '1') == 1 && $params->get('showrouting_places', '1') == 1)
{
	$document->addScript(JURI::root(true) . '/media/mod_agosm/js/places.js');
}

if ($params->get('showrouting', '1') == 1)
{
	$document->addStyleSheet(JURI::root(true) . '/media/mod_agosm/css/leaflet-routing-machine.css');
	$document->addScript(JURI::root(true) . '/media/mod_agosm/js/leaflet-routing-machine.js');
	$document->addStyleSheet(JURI::root(true) . '/media/mod_agosm/css/Control.Geocoder.css');
	$document->addScript(JURI::root(true) . '/media/mod_agosm/js/Control.Geocoder.js');
}

if ($params->get('showpin', '1') === "1" || $params->get('showcustompin', '1') === "1")
{
	$document->addStyleSheet(JURI::root(true) . '/media/mod_agosm/css/font-awesome.min.css');
	$document->addStyleSheet(JURI::root(true) . '/media/mod_agosm/Leaflet.awesome-markers/leaflet.awesome-markers.css');
	$document->addScript(JURI::root(true) . '/media/mod_agosm/Leaflet.awesome-markers/leaflet.awesome-markers.js');
}

if ($params->get('baselayer', 'mapnik') == 'google')
{
	$document->addScript('https://maps.googleapis.com/maps/api/js?key=' . $params->get('googlekey', ''));
	$document->addScript(JURI::root(true) . '/media/mod_agosm/js/Leaflet.GoogleMutant.js');
}

if ($params->get('showcomponentpin', '0') === "1")
{
	$list = ModagosmHelper::getList($params);
}

if ($params->get('showcustomfieldpin', '0') === "1")
{
	$list = ModagosmHelper::getListCustomField($params);
}

require JModuleHelper::getLayoutPath('mod_agosm', $params->get('layout', 'default'));
