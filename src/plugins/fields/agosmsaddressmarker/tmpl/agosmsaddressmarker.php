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

$document = JFactory::getDocument();

$document->addStyleSheet(JURI::root(true) . '/media/plg_fields_agosmsaddressmarker/leaflet/leaflet.css');
$document->addScript(JURI::root(true) . '/media/plg_fields_agosmsaddressmarker/leaflet/leaflet.js');
$document->addScript(JURI::root(true) . '/media/plg_fields_agosmsaddressmarker/js/agosmsaddressmarker.js');

// We need this for blog view
$unique = $field->id . '_' . uniqid();

if ($value == '')
{
	return;
}

$values = explode(',', $value);

$lat = $values[0];
$lon = $values[1];

// echo htmlentities($value);
// Prüfe ob zweit werte und ob koordinate
?>

<div
	id="map<?php echo $unique ?>"
	class = 'agosmsaddressmarkerleafletmap' 
	style="height: <?php echo $fieldParams->get('map_height', '400') ?>px;"
	data-unique='<?php echo $unique ?>'
	data-lat='<?php echo $lat ?>'
	data-lon='<?php echo $lon ?>'
>
</div>
