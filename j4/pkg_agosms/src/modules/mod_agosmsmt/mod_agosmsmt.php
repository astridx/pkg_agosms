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

//use AG\Module\Agosmsmt\Site\Helper\AgosmsCategoryHelper;

use Joomla\CMS\Helper\ModuleHelper;
use Mosets\Map;
use Joomla\CMS\Router\Route;

require_once(JPATH_BASE . '/components/com_mtree/mtree.tools.php');
require_once(JPATH_SITE . '/components/com_mtree/Savant2.php');
require(JPATH_BASE . '/components/com_mtree/init.php');
require_once(JPATH_SITE.'/components/com_mtree/listlisting.php');

$savant = new Savant2([
	'template_path' => JPATH_SITE.'/components/com_mtree/templates/'.$mtconf->get('template').'/',
	'template_path_default' => JPATH_SITE.'/components/com_mtree/Savant2/default/',
	'plugin_path' => JPATH_SITE.'/components/com_mtree/Savant2/',
	'filter_path' => JPATH_SITE.'/components/com_mtree/Savant2/'
]);

$count = $params->get('count', 50);
$type= $params->get('type', 'listpopular');
$show_from_cat_id = $params->get('show_from_cat_id', 0);
$map_cluster_height = $params->get('map_cluster_height');

$listListing = new mtListListing($type);
$listListing->setSubcats(getSubCats_Recursive($show_from_cat_id));
$listListing->hasLatlng();
$listListing->setLimit($count);
$links = $listListing->getListings();

if (empty($links)) {
	require ModuleHelper::getLayoutPath('mod_mt_map', '_emptystate');
	return;
}

$map = Map::assign($savant)->withListings($links)->withConfig($mtconf);



// AGOSM

$arrCats = [];

if (!empty($links)) {
	foreach ($links as $link) {
		if (!in_array($link->cat_name, $arrCats, true)) {
			array_push($arrCats, $link->cat_name);
		}
	}
}

$geojson = [
	'type'      => 'FeatureCollection',
	'features'  => []
];

if (!empty($links)) {
	$Itemid = \MTModuleHelper::getItemid();

	foreach ($links as $link) {
		if ($link->lng != 0 && $link->lat != 0) {
			$feature = [
				'id' => $link->link_id,
				'type' => 'Feature',
				'geometry' => [
					'type' => 'Point',
					'coordinates' => [$link->lng, $link->lat]
				],

				'properties' => [
					'link_name' => json_encode($link->link_name),
					'link_desc' => json_encode($link->link_desc),
					'route' => '\'' . Route::_('index.php?option=com_mtree&task=viewlink&link_id='.$link->link_id.'&Itemid='.$Itemid) . '\'',
					'cat_name' => json_encode($link->cat_name),
					'cat_id' => json_encode($link->cat_id)
					]
				];
			array_push($geojson['features'], $feature);
		}
	}
}

























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
	$document->addStyleSheet(JURI::root(true) . '/media/mod_agosmsmt/leaflet/leaflet.css');
	$document->addScript(JURI::root(true) . '/media/mod_agosmsmt/leaflet/leaflet.js');
}

$document->addStyleSheet(JURI::root(true) . '/media/mod_agosmsmt/cluster/MarkerCluster.css');
$document->addStyleSheet(JURI::root(true) . '/media/mod_agosmsmt/cluster/MarkerCluster.Default.css');
$document->addScript(JURI::root(true) . '/media/mod_agosmsmt/cluster/leaflet.markercluster-src.js');

$document->addScript(JURI::root(true) . '/media/mod_agosmsmt/clusterSupport/leaflet.markercluster.layersupport.js');


if ($params->get('showrouting_simple', '1') == 1) {
	$document->addStyleSheet(JURI::root(true) . '/media/mod_agosmsmt/css/LeafletControlRoutingtoaddress.css');
	$document->addScript(JURI::root(true) . '/media/mod_agosmsmt/js/LeafletControlRoutingtoaddress.js');
}

if (true) {
	$document->addStyleSheet(JURI::root(true) . '/media/mod_agosmsmt/css/LeafletControlRadiuszoom.css');
	$document->addScript(JURI::root(true) . '/media/mod_agosmsmt/js/LeafletControlRadiuszoom.js');
}

if ($params->get('scrollwheelzoom') === "2") {
	$document->addStyleSheet(JURI::root(true) . '/media/mod_agosmsmt/GoogleGestureHandling/leaflet-gesture-handling.min.css');
	$document->addScript(JURI::root(true) . '/media/mod_agosmsmt/GoogleGestureHandling/leaflet-gesture-handling.min.js');
}

$document->addScript(JURI::root(true) . '/media/mod_agosmsmt/js/agosmsmt.js');

if (true) {
	$document->addScript(JURI::root(true) . '/media/mod_agosmsmt/layerstree/L.Control.Layers.Tree.js');
	$document->addScript(JURI::root(true) . '/media/mod_agosmsmt/layerstree/L.Control.Layers.Tree.css');
}

if ($params->get('showlocate', '1') == 1) {
	$document->addStyleSheet(JURI::root(true) . '/media/mod_agosmsmt/locate/L.Control.Locate.css');
	$document->addScript(JURI::root(true) . '/media/mod_agosmsmt/locate/L.Control.Locate.min.js');
}

if ($params->get('showfullscreen', '1') == 1) {
	$document->addStyleSheet(JURI::root(true) . '/media/mod_agosmsmt/fullscreen/leaflet.fullscreen.css');
	$document->addScript(JURI::root(true) . '/media/mod_agosmsmt/fullscreen/Leaflet.fullscreen.min.js');
}

if ($params->get('showfullscreenlink', '1') == 1) {
	$document->addStyleSheet(JURI::root(true) . '/media/mod_agosmsmt/fullscreenlink/leaflet.fullscreenlink.css');
	$document->addScript(JURI::root(true) . '/media/mod_agosmsmt/fullscreenlink/Leaflet.fullscreenlink.js');
}

if ($params->get('spacermouseposition', '1') == 1) {
	$document->addStyleSheet(JURI::root(true) . '/media/mod_agosmsmt/mouseposition/L.Control.MousePosition.css');
	$document->addScript(JURI::root(true) . '/media/mod_agosmsmt/mouseposition/L.Control.MousePosition.js');
}

$document->addStyleSheet(JURI::root(true) . '/media/mod_agosmsmt/css/agosmsmt.css');

require ModuleHelper::getLayoutPath('mod_agosmsmt', $params->get('layout', 'default'));




/*
[192] => stdClass Object ( [link_id] => 298 [link_name] => Fischzucht Rameil GbR [alias] => hoflaeden [link_desc] => In fünfter Generation betreibt die Familie Rameil die Aufzucht und Vermarktung von Forellen, Lachsforellen, Saiblingen, Stören und Karpfen. Neben unseren heimischen Fischen bieten wir auch Seefisch an, den wir direkt über Bremerhaven beziehen. Sie finden bei uns frischen, geräucherten und marinierten Fisch. [user_id] => 166 [link_hits] => 1 [link_votes] => 0 [link_rating] => 0.000000 [link_featured] => 0 [link_published] => 1 [link_approved] => 1 [link_template] => [attribs] => use_map= show_print= show_recommend= show_rating= show_review= show_visit= show_contact= show_report= show_ownerlisting= [metakey] => Hofladen [metadesc] => Hofläden bei regional-optimal.de [internal_notes] => [ordering] => 6 [link_created] => 2022-03-05 16:56:49 [publish_up] => 2022-03-05 16:56:49 [publish_down] => 0000-00-00 00:00:00 [link_modified] => 2022-03-05 16:58:51 [link_visited] => 0 [firstname] => [lastname] => [address] => Pipprichsweg 9 [city] => Fritzlar [state] => [country] => Deutschland [postcode] => 34560 [contactperson] => [telephone] => 056221685 [mobile] => [fax] => [email] => fischzucht-rameil@t-online.de [website] => https://fischzucht-rameil.de/ [year] => [date] => [price] => [show_map] => 1 [lat] => 51.11956 [lng] => 9.25529 [zoom] => 13 [tlcat_id] => 106 [tlcat_name] => Anbieter [cat_id] => 98 [cat_name] => Hofläden [title] => [cat_desc] => [cat_parent] => 106 [cat_links] => 270 [cat_cats] => 0 [cat_featured] => 0 [cat_image] => 98_hofladen.jpg [cat_association] => [cat_published] => 1 [cat_created] => 2012-11-14 16:57:34 [cat_approved] => 1 [cat_template] => [cat_usemainindex] => 0 [cat_allow_submission] => 1 [cat_show_listings] => 1 [metadata] => [lft] => 15 [rgt] => 16 [username] => maj [owner] => maj [owner_email] => mjanzer@arcor.de [link] => /kunden/regional/index.php/component/mtree/fischzucht-rameil-gbr?Itemid=319 [image_path] => [trimmed_website] => https://fischzucht-rameil.de/ [cat_link] => /kunden/regional/index.php/component/mtree/?Itemid=319 )
*/
