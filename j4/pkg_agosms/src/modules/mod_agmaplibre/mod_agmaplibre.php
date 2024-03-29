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

use Joomla\CMS\Helper\ModuleHelper;
use AG\Module\Agosm\Site\Helper\AgosmsCategoryHelper;

// Include skripts/styles to the header
$document = JFactory::getDocument();

$leafletIsLoaded = false;

foreach ($document->_scripts as $key => $script) {
	$leafletPath = "leaflet/leaflet.js";

	if (strpos($key, $leafletPath)) {
		$leafletIsLoaded = true;
	}
}

if (!$leafletIsLoaded) {
	$document->addStyleSheet(JURI::root(true) . '/media/mod_agosm/leaflet/leaflet.css');
	$document->addScript(JURI::root(true) . '/media/mod_agosm/leaflet/leaflet.js');
}

if ($params->get('showgeocoder', '1') == 1 || $params->get('showrouting', '1') == 1) {
	$document->addStyleSheet(JURI::root(true) . '/media/mod_agosm/css/Control.Geocoder.css');
	$document->addScript(JURI::root(true) . '/media/mod_agosm/js/Control.Geocoder.js');
}

if ($params->get('useesri', '1') == 1) {
	$document->addScript(JURI::root(true) . '/media/mod_agosm/js/esri-leaflet.js');
	$document->addStyleSheet(JURI::root(true) . '/media/mod_agosm/css/esri-leaflet-geocoder.css');
	$document->addScript(JURI::root(true) . '/media/mod_agosm/js/esri-leaflet-geocoder.js');
}

if ($params->get('showrouting_simple', '1') == 1) {
	$document->addStyleSheet(JURI::root(true) . '/media/mod_agosm/css/LeafletControlRoutingtoaddress.css');
	$document->addScript(JURI::root(true) . '/media/mod_agosm/js/LeafletControlRoutingtoaddress.js');
}

if ($params->get('showrouting', '1') == 1) {
	$document->addStyleSheet(JURI::root(true) . '/media/mod_agosm/css/leaflet-routing-machine.css');
	$document->addScript(JURI::root(true) . '/media/mod_agosm/js/leaflet-routing-machine.js');
}

if ($params->get('showpin', '1') === "1" || $params->get('showcustompin', '1') === "1" || $params->get('showcustomfieldpin', '1') === "1") {
	$document->addStyleSheet(JURI::root(true) . '/media/mod_agosm/css/font-awesome.min.css');
	$document->addStyleSheet(JURI::root(true) . '/media/mod_agosm/Leaflet.awesome-markers/leaflet.awesome-markers.css');
	$document->addScript(JURI::root(true) . '/media/mod_agosm/Leaflet.awesome-markers/leaflet.awesome-markers.js');
}

if (true || $params->get('showcustomfieldpin', '0') === "1") {
	$document->addStyleSheet(JURI::root(true) . '/media/mod_agosm/cluster/MarkerCluster.css');
	$document->addStyleSheet(JURI::root(true) . '/media/mod_agosm/cluster/MarkerCluster.Default.css');
	$document->addScript(JURI::root(true) . '/media/mod_agosm/cluster/leaflet.markercluster-src.js');
}

if ($params->get('baselayer', 'mapnik') == 'google') {
	$document->addScript('https://maps.googleapis.com/maps/api/js?key=' . $params->get('googlekey', ''));
	$document->addScript(JURI::root(true) . '/media/mod_agosm/js/Leaflet.GoogleMutant.js');
}

if ($params->get('scrollwheelzoom') === "2") {
	$document->addStyleSheet(JURI::root(true) . '/media/mod_agosm/GoogleGestureHandling/leaflet-gesture-handling.min.css');
	$document->addScript(JURI::root(true) . '/media/mod_agosm/GoogleGestureHandling/leaflet-gesture-handling.min.js');
}

if ($params->get('showcomponentpin', '0') === "1") {
	$list = AgosmsCategoryHelper::getList($params);
}

if ($params->get('showcomponentpinone', '0') === "1") {
	$listone = AgosmsCategoryHelper::getListone($params);
}

if ($params->get('showcustomfieldpin', '0') === "1") {
	if (!empty(AgosmsCategoryHelper::getListCustomField($params))) {
		$listcf = AgosmsCategoryHelper::getListCustomField($params);
	}
}

if ($params->get('showmarkerfromexternaldb', '0') === "1") {
	$listexternaldb = AgosmsCategoryHelper::getListExternaldb($params);
}

$document->addScript(JURI::root(true) . '/media/mod_agosm/js/agosm.js');

if ($params->get('showrouting_simple', '1') == 1 && $params->get('showrouting_places', '1') == 1) {
	$document->addScript(JURI::root(true) . '/media/mod_agosm/js/places.js');
}

if ($params->get('showlocate', '1') == 1) {
	$document->addStyleSheet(JURI::root(true) . '/media/mod_agosm/locate/L.Control.Locate.css');
	$document->addScript(JURI::root(true) . '/media/mod_agosm/locate/L.Control.Locate.min.js');
}

if ($params->get('showfullscreen', '1') == 1) {
	$document->addStyleSheet(JURI::root(true) . '/media/mod_agosm/fullscreen/leaflet.fullscreen.css');
	$document->addScript(JURI::root(true) . '/media/mod_agosm/fullscreen/Leaflet.fullscreen.min.js');
}

if ($params->get('spacermouseposition', '1') == 1) {
	$document->addStyleSheet(JURI::root(true) . '/media/mod_agosm/mouseposition/L.Control.MousePosition.css');
	$document->addScript(JURI::root(true) . '/media/mod_agosm/mouseposition/L.Control.MousePosition.js');
}

$document->addStyleSheet(JURI::root(true) . '/media/mod_agosm/css/agosms.css');

require ModuleHelper::getLayoutPath('mod_agosm', $params->get('layout', 'default'));
