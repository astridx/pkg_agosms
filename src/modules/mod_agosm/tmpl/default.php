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
$defaultArray = [];
?>

<div style="
	width:auto;
	height:<?php echo $params->get('height', '400'); ?>px;"
	data-module-id="<?php echo $module->id; ?>"
	data-no-world-warp="<?php echo $params->get('noWorldWarp', 0); ?>"
	data-detect-retina="<?php echo $params->get('detectRetina', 0); ?>"
	data-baselayer="<?php echo $params->get('baselayer', 'mapnik'); ?>"
	data-lonlat="<?php echo $params->get('lonlat', '50.281168, 7.276211'); ?>"
	data-zoom="<?php echo $params->get('zoom', '10'); ?>"
<?php if ($params->get('baselayer', 'mapbox')) : ?>
	data-mapboxmaptype="<?php echo $params->get('mapboxmaptype', 'streets'); ?>"
	data-mapboxkey="<?php echo $params->get('mapboxkey', ''); ?>"
<?php endif; ?>
<?php if ($params->get('baselayer', 'thunderforest')) : ?>
	data-thunderforestkey="<?php echo $params->get('thunderforestkey', ''); ?>"
	data-thunderforestmaptype="<?php echo $params->get('thunderforestmaptype', 'cycle'); ?>"
<?php endif; ?>
<?php if ($params->get('baselayer', 'stamen')) : ?>
	data-stamenmaptype="<?php echo $params->get('stamenmaptype', 'watercolor'); ?>"
<?php endif; ?>
<?php if ($params->get('baselayer', 'google')) : ?>
	data-googlemapstype="<?php echo $params->get('googlemapstype', 'satellite'); ?>"
<?php endif; ?>
<?php if ($params->get('baselayer', 'custom')) : ?>
	data-customBaselayer="<?php echo $params->get('customBaselayer', 'maxZoom: 18,'); ?>"
	data-customBaselayerURL="<?php echo $params->get('customBaselayerURL', 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png'); ?>"
<?php endif; ?>
	data-attr-module="<?php echo $params->get('attrModule', 1); ?>"
<?php if ($params->get('scale') !== null) : ?>
	data-scale="<?php echo count($params->get('scale')); ?>"
<?php endif; ?>	
	data-scale-metric="<?php echo in_array('metric', $params->get('scale', $defaultArray)); ?>"
	data-scale-imperial="<?php echo in_array('imperial', $params->get('scale', $defaultArray)); ?>"
	data-showgeocoder="<?php echo $params->get('showgeocoder', '1'); ?>"
<?php if ($params->get('showgeocoder', '1')) : ?>
	data-geocodercollapsed="<?php echo $params->get('geocodercollapsed', 'false'); ?>"
	data-geocoderposition="<?php echo $params->get('geocoderposition', 'bottomleft'); ?>"
	data-expand="<?php echo $params->get('expand', 'click'); ?>"
<?php endif; ?>
	data-useesri="<?php echo $params->get('useesri', '1'); ?>"
<?php if ($params->get('useesri', '1')) : ?>
	data-esrireversegeocoding="<?php echo $params->get('esrireversegeocoding', false); ?>"
	data-esrigeocoderopengetaddress="<?php echo $params->get('esrigeocoderopengetaddress', false); ?>"
	data-showgeocoderesri="<?php echo $params->get('showgeocoderesri', '1'); ?>"
	data-positionesrigeocoder="<?php echo $params->get('positionesrigeocoder', 'bottomleft'); ?>"
	data-esrigeocoderzoomToResult="<?php echo $params->get('esrigeocoderzoomToResult', true); ?>"
	data-esrigeocoderuseMapBounds="<?php echo $params->get('esrigeocoderuseMapBounds', false); ?>"
	data-esrigeocodercollapseAfterResult="<?php echo $params->get('esrigeocodercollapseAfterResult', true); ?>"
	data-esrigeocoderexpanded="<?php echo $params->get('esrigeocoderexpanded', true); ?>"
	data-esriallowMultipleResults="<?php echo $params->get('esriallowMultipleResults', true); ?>"
<?php endif; ?>
	data-showrouting="<?php echo $params->get('showrouting', '1'); ?>"
<?php if ($params->get('showrouting', '1')) : ?>
	data-routingstart="<?php echo $params->get('routingstart', '50.273543, 7.262993'); ?>"
	data-routingend="<?php echo $params->get('routingend', '50.281168, 7.276211'); ?>"
	data-mapboxkey-routing="<?php echo $params->get('mapboxkey_routing', ''); ?>"
	data-routingprofile="<?php echo $params->get('routingprofile', 'mapbox/driving'); ?>"
	data-routinglanguage="<?php echo $params->get('routinglanguage', 'de'); ?>"
	data-routingmetric="<?php echo $params->get('routingmetric', 'metric'); ?>"
	data-routewhiledragging="<?php echo $params->get('routewhiledragging', 'de'); ?>"
<?php endif; ?>
<?php if ($params->get('showpin', '1')) : ?>
	data-specialpins="<?php echo htmlspecialchars(json_encode($params->get('specialpins', null)), ENT_QUOTES, 'UTF-8'); ?>"
	data-showpin="<?php echo $params->get('showpin', '1'); ?>"
<?php endif; ?>
<?php if ($params->get('showcomponentpin', '1')) : ?>
	data-specialcomponentpins="<?php echo htmlspecialchars(json_encode($list), ENT_QUOTES, 'UTF-8'); ?>"
	data-showcomponentpin="<?php echo $params->get('showcomponentpin', '1'); ?>"
<?php endif; ?>
	class="leafletmapMod"
	id="map<?php echo $module->id; ?>">
</div>

<?php
JText::script('MOD_AGOSM_MODULE_BY');
JText::script('MOD_AGOSM_DEFAULT_TEXT_PLACEHOLDER');
JText::script('MOD_AGOSM_DEFAULT_TEXT_ERRORMESSAGE');
JText::script('MOD_AGOSM_DEFAULT_ESRI_GEOCODER_PLACEHOLDER');
JText::script('MOD_AGOSM_DEFAULT_ESRI_GEOCODER_TITLE');

