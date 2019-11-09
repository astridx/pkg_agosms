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

$value = $field->value;

JText::script('PLG_AGOSMSADDRESSMARKER_SCROLL');
JText::script('PLG_AGOSMSADDRESSMARKER_TOUCH');
JText::script('PLG_AGOSMSADDRESSMARKER_SCROLLMAC');

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
	$document->addStyleSheet(JURI::root(true) . '/media/plg_fields_agosmsaddressmarker/leaflet/leaflet.css');
	$document->addScript(JURI::root(true) . '/media/plg_fields_agosmsaddressmarker/leaflet/leaflet.js');
	$document->addScript(JURI::root(true) . '/media/plg_fields_agosmsaddressmarker/js/site-agosmsaddressmarker.js');
}

if ($fieldParams->get('showroutingcontrol', '0') == 1)
{
	$document->addStyleSheet(JURI::root(true) . '/media/plg_fields_agosmsaddressmarker/Routing/leaflet-routing-machine/leaflet-routing-machine.css');
	$document->addScript(JURI::root(true) . '/media/plg_fields_agosmsaddressmarker/Routing/leaflet-routing-machine/leaflet-routing-machine.js');
	$document->addScript(JURI::root(true) . '/media/plg_fields_agosmsaddressmarker/Routing/leaflet-routing-machine/Control.Geocoder.js');
}

if ($fieldParams->get('specialicon', '0') === "1")
{
	$document->addStyleSheet(JURI::root(true) . '/media/plg_fields_agosmsaddressmarker/css/font-awesome.min.css');
	$document->addStyleSheet(JURI::root(true) . '/media/plg_fields_agosmsaddressmarker/Leaflet.awesome-markers/leaflet.awesome-markers.css');
	$document->addScript(JURI::root(true) . '/media/plg_fields_agosmsaddressmarker/Leaflet.awesome-markers/leaflet.awesome-markers.js');
}

if ($fieldParams->get('scrollwheelzoom') === "2")
{
	$document->addStyleSheet(JURI::root(true) . '/media/plg_fields_agosmsaddressmarker/GoogleGestureHandling/leaflet-gesture-handling.min.css');
	$document->addScript(JURI::root(true) . '/media/plg_fields_agosmsaddressmarker/GoogleGestureHandling/leaflet-gesture-handling.min.js');
}

if ($fieldParams->get('maptype') === "google")
{
	$document->addScript('https://maps.googleapis.com/maps/api/js?key=' . $fieldParams->get('googlekey', ''));
	$document->addScript(JURI::root(true) . '/media/plg_fields_agosmsaddressmarker/GoogleMutant/Leaflet.GoogleMutant.js');
}

$document->addScript(JURI::root(true) . '/media/plg_fields_agosmsaddressmarker/js/site-agosmsaddressmarker.js');

// We need this for list views
$unique = $field->id . '_' . uniqid();

if ($value == '')
{
	return;
}

$values = explode(',', $value);

// ToDo Prüfe ob genau zwei werte und ob koordinate
$lat = $values[0];
$lon = $values[1];
$iconcolor = $values[2];
$markercolor = $values[3];
$icon = $values[4];
$popuptext = $values[5];

?>

<div
	<?php 
	if ( $lat == 0 && $lon == 0) echo 'style="display:none"' 
	?>
	id="map<?php echo $unique ?>"
	class = 'agosmsaddressmarkerleafletmap' 
	style="height: <?php echo $fieldParams->get('mapheight', '400') ?><?php echo $fieldParams->get('mapheightunit', 'px') ?>"
	data-unique='<?php echo $unique ?>'
	data-maptype='<?php echo $fieldParams->get('maptype') ?>'
	data-lat='<?php echo $lat ?>'
	data-lon='<?php echo $lon ?>'
	data-iconcolor='<?php echo $iconcolor ?>'
	data-markercolor='<?php echo $markercolor ?>'
	data-icon='<?php echo $icon ?>'
	data-popuptext='<?php echo $popuptext ?>'
	data-scrollwheelzoom='<?php echo $fieldParams->get('scrollwheelzoom', '1') ?>'
	data-owngooglegesturetext='<?php echo $fieldParams->get('owngooglegesturetext', '1') ?>'
	data-specialicon='<?php echo $fieldParams->get('specialicon', '0') ?>'
	data-popup='<?php echo $fieldParams->get('popup', '0') ?>'
	data-showroutingcontrol='<?php echo $fieldParams->get('showroutingcontrol', '0') ?>'
	data-mapboxkey='<?php echo $fieldParams->get('mapboxkey', '') ?>'
	data-routing_position='<?php echo $fieldParams->get('routing_position', 'topright') ?>'
	data-routing_router='<?php echo $fieldParams->get('routing_router', 'osrm') ?>'
	data-routingprofile="<?php echo $fieldParams->get('routingprofile', 'mapbox/driving'); ?>"
	data-routinglanguage="<?php echo $fieldParams->get('routinglanguage', 'de'); ?>"
	data-routingmetric="<?php echo $fieldParams->get('routingmetric', 'metric'); ?>"
	data-fitSelectedRoutes="<?php echo $fieldParams->get('fitSelectedRoutes', 'true'); ?>"
	data-reverseWaypoints="<?php echo $fieldParams->get('reverseWaypoints', 'false'); ?>"
	data-collapsible="<?php echo $fieldParams->get('collapsible', 'false'); ?>"
	data-showAlternatives="<?php echo $fieldParams->get('showAlternatives', 'false'); ?>"
	data-routewhiledragging="<?php echo $fieldParams->get('routewhiledragging', 'false'); ?>"
	data-uriroot='<?php echo JUri::root(); ?>'	
>
</div>
