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

use Joomla\CMS\Language\Text;

$defaultArray = [];
$unique = $module->id . '_' . uniqid();
?>

<div style="
	z-index:1;
	width:auto;
	height:<?php echo $params->get('height', '400'); ?><?php echo $params->get('heightunit', 'px'); ?>;"
	data-disable-clustering-at-zoom="<?php echo $params->get('disableClusteringAtZoom', '0'); ?>"
	data-module-id="<?php echo $module->id; ?>" data-addprivacybox="<?php echo $params->get('addprivacybox', '0') ?>"
	data-unique='<?php echo $unique ?>' data-no-world-warp="<?php echo $params->get('noWorldWarp', 0); ?>"
	data-detect-retina="<?php echo $params->get('detectRetina', 0); ?>"
	data-baselayer="<?php echo $params->get('baselayer', 'mapnik'); ?>"
	data-lonlat="<?php echo $params->get('lonlat', '50.281168, 7.276211'); ?>"
	data-zoom="<?php echo $params->get('zoom', '10'); ?>" data-locate="<?php echo $params->get('showlocate', false); ?>"
	data-show-radiusZoom="<?php echo $params->get('show_radiusZoom', false); ?>"
	data-showfullscreenlink="<?php echo $params->get('showfullscreenlink', false); ?>"
	data-fullscreenlink="<?php echo $params->get('fullscreenlink', '#'); ?>"
	data-move-form="<?php echo $params->get('move_form', false); ?>"
	data-use-own-marker-image="<?php echo $params->get('use_own_marker_image', false); ?>"
	data-fullscreen="<?php echo $params->get('showfullscreen', false); ?>"
	data-mouseposition="<?php echo $params->get('showmouseposition', false); ?>"
	<?php if ($params->get('baselayer', 'mapbox')) : ?>
	data-mapboxmaptype="<?php echo $params->get('mapboxmaptype', 'mapbox/streets-v11'); ?>"
	data-mapboxkey="<?php echo $params->get('mapboxkey', ''); ?>" <?php
	endif; ?> <?php if ($params->get('baselayer', 'thunderforest')) : ?>
	data-thunderforestkey="<?php echo $params->get('thunderforestkey', ''); ?>"
	data-thunderforestmaptype="<?php echo $params->get('thunderforestmaptype', 'cycle'); ?>" <?php
	endif; ?> <?php if ($params->get('baselayer', 'stamen')) : ?>
	data-stamenmaptype="<?php echo $params->get('stamenmaptype', 'watercolor'); ?>" <?php
	endif; ?> <?php if ($params->get('baselayer', 'google')) : ?>
	data-googlemapstype="<?php echo $params->get('googlemapstype', 'satellite'); ?>" <?php
	endif; ?> <?php if ($params->get('baselayer', 'custom')) : ?>
	data-customBaselayer="<?php echo $params->get('customBaselayer', 'maxZoom: 18,'); ?>"
	data-customBaselayerURL="<?php echo $params->get('customBaselayerURL', 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png'); ?>"
	<?php endif; ?> <?php if ($params->get('scale') !== null) : ?>
	data-scale="<?php echo count($params->get('scale')); ?>" <?php
	endif; ?> data-scale-metric="<?php echo in_array('metric', $params->get('scale', $defaultArray)); ?>"
	data-scale-imperial="<?php echo in_array('imperial', $params->get('scale', $defaultArray)); ?>"
	data-showrouting-simple="<?php echo $params->get('showrouting_simple', '1'); ?>"
	data-mtmarkers="<?php echo htmlspecialchars(json_encode($geojson), ENT_QUOTES, 'UTF-8'); ?>"
	data-arrCats="<?php echo htmlspecialchars(json_encode($arrCats), ENT_QUOTES, 'UTF-8'); ?>"
	data-scrollwheelzoom='<?php echo $params->get('scrollwheelzoom', '') ?>'
	data-owngooglegesturetext='<?php echo $params->get('owngooglegesturetext', '1') ?>'
	data-scroll='<?php echo JText::_('PLG_AGOSMSADDRESSMARKER_SCROLL'); ?>'
	data-touch='<?php echo JText::_('PLG_AGOSMSADDRESSMARKER_TOUCH'); ?>'
	data-scrollmac='<?php echo JText::_('PLG_AGOSMSADDRESSMARKER_SCROLLMAC'); ?>'
	data-uriroot='<?php echo JUri::root(); ?>' class="leafletmapMod" id="map<?php echo $module->id; ?>">
</div>

<?php if ($params->get('addprivacybox', '0')) : ?>
<p class="p<?php echo $unique ?>"></p>
<button class="btn btn-success b<?php echo $unique ?>" type="button">
	<?php echo JText::_('MOD_AGOSM_PRIVACYBUTTON_SHOW_MAP'); ?>
</button>
<?php endif; ?>





<style>
.leaflet-layerstree-children {
	display: flex;
	flex-direction: row;
	flex-wrap: wrap;
	font-size: 0.85rem;
	justify-content: space-evenly;
}

.leaflet-control-layers-expanded {
	width: 100%;
}

.leaflet-control-routingtoaddress {}
</style>

<div style="display:flex;flex-wrap: wrap;" class="px-1 py-1 my-5 text-center bg-light bg-gradient">
	<div class="text-center px-1 py-1 my-2" id="agosms-mt-form-layer<?php echo $module->id; ?>"></div>
	<div class="text-center px-1 py-1 my-2" id="agosms-mt-form-radius<?php echo $module->id; ?>"></div>
	<div class="text-center px-1 py-1 my-2">
		<span id="agosms-mt-locate-text<?php echo $module->id; ?>"></span>
		<span id="agosms-mt-locate<?php echo $module->id; ?>"></span>
	</div>
</div>

<?php
JText::script('MOD_AGOSM_MODULE_BY');
JText::script('MOD_AGOSM_DEFAULT_TEXT_PLACEHOLDER');
JText::script('MOD_AGOSM_DEFAULT_TEXT_ERRORMESSAGE');
JText::script('MOD_AGOSM_DEFAULT_ESRI_GEOCODER_PLACEHOLDER');
JText::script('MOD_AGOSM_DEFAULT_ESRI_GEOCODER_TITLE');

JText::script('MOD_AGOSM_ROUTING_SIMPLE_TEXT_PLACEHOLDER');
JText::script('MOD_AGOSM_ROUTING_SIMPLE_TEXT_ERRORMESSAGE');
JText::script('MOD_AGOSM_ROUTING_SIMPLE_TEXT_DISTANCE');
JText::script('MOD_AGOSM_ROUTING_SIMPLE_TEXT_DURATION');
JText::script('MOD_AGOSM_ROUTING_SIMPLE_TEXT_STUNDEN');
JText::script('MOD_AGOSM_ROUTING_SIMPLE_TEXT_KILOMETER');
JText::script('MOD_AGOSM_ROUTING_SIMPLE_TEXT_REQUESTERROR');

JText::script('PLG_AGOSMSADDRESSMARKER_PRIVACYBUTTON_SHOW_MAP');
JText::script('PLG_AGOSMSADDRESSMARKER_PRIVACYBUTTON_HIDE_MAP');
JText::script('PLG_AGOSMSADDRESSMARKER_PRIVACYTEXT_SHOW_MAP');
JText::script('PLG_AGOSMSADDRESSMARKER_PRIVACYTEXT_HIDE_MAP');
?>