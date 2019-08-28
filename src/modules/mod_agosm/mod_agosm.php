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

// Include the agosm functions only once
require_once __DIR__ . '/helper.php';
require_once __DIR__ . '/Helper/EasyFileUploaderHelper.php';

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

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

if ($params->get('showpin', '1') === "1" || $params->get('showcustompin', '1') === "1" || $params->get('showcustomfieldpin', '1') === "1")
{
	$document->addStyleSheet(JURI::root(true) . '/media/mod_agosm/css/font-awesome.min.css');
	$document->addStyleSheet(JURI::root(true) . '/media/mod_agosm/Leaflet.awesome-markers/leaflet.awesome-markers.css');
	$document->addScript(JURI::root(true) . '/media/mod_agosm/Leaflet.awesome-markers/leaflet.awesome-markers.js');
}

if ($params->get('showcustomfieldpin', '0') === "1")
{
	$document->addStyleSheet(JURI::root(true) . '/media/mod_agosm/cluster/MarkerCluster.css');
	$document->addStyleSheet(JURI::root(true) . '/media/mod_agosm/cluster/MarkerCluster.Default.css');
	$document->addScript(JURI::root(true) . '/media/mod_agosm/cluster/leaflet.markercluster-src.js');
}

if ($params->get('baselayer', 'mapnik') == 'google')
{
	$document->addScript('https://maps.googleapis.com/maps/api/js?key=' . $params->get('googlekey', ''));
	$document->addScript(JURI::root(true) . '/media/mod_agosm/js/Leaflet.GoogleMutant.js');
}

if ($params->get('scrollwheelzoom') === "2")
{
	$document->addStyleSheet(JURI::root(true) . '/media/mod_agosm/GoogleGestureHandling/leaflet-gesture-handling.min.css');
	$document->addScript(JURI::root(true) . '/media/mod_agosm/GoogleGestureHandling/leaflet-gesture-handling.min.js');
}

if ($params->get('showcomponentpin', '0') === "1")
{
	$list = ModagosmHelper::getList($params);
}

if ($params->get('showcustomfieldpin', '0') === "1")
{
	$listcf = ModagosmHelper::getListCustomField($params);
}

if ($params->get('showmarkerfromexternaldb', '0') === "1")
{
	$listexternaldb = ModagosmHelper::getListExternaldb($params);
}

$document->addScript(JURI::root(true) . '/media/mod_agosm/js/agosm.js');
$document->addStyleSheet(JURI::root(true) . '/media/mod_agosm/css/agosms.css');
require JModuleHelper::getLayoutPath('mod_agosm', $params->get('layout', 'default'));
