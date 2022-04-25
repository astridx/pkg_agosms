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
$defaultArray = [];
$unique = $module->id . '_' . uniqid();
?>

<div style="
	z-index:1;
	width:auto;
	height:<?php echo $params->get('height', '400'); ?><?php echo $params->get('heightunit', 'px'); ?>;"
	data-module-id="<?php echo $module->id; ?>"
	data-addprivacybox="<?php echo $params->get('addprivacybox', '0') ?>"
	data-unique='<?php echo $unique ?>'
	data-no-world-warp="<?php echo $params->get('noWorldWarp', 0); ?>"
	data-detect-retina="<?php echo $params->get('detectRetina', 0); ?>"
	data-baselayer="<?php echo $params->get('baselayer', 'mapnik'); ?>"
	data-layertree="<?php echo $params->get('addlayertree', '0'); ?>"
	data-lonlat="<?php echo $params->get('lonlat', '50.281168, 7.276211'); ?>"
	data-savestate="<?php echo $params->get('savestate', false); ?>"
	data-zoom="<?php echo $params->get('zoom', '10'); ?>"
	data-disable-clustering-at-zoom="<?php echo $params->get('disableClusteringAtZoom', '0'); ?>"
	data-locate="<?php echo $params->get('showlocate', false); ?>"
	data-fullscreen="<?php echo $params->get('showfullscreen', false); ?>"
	data-mouseposition="<?php echo $params->get('showmouseposition', false); ?>"
	
<?php if ($params->get('baselayer', 'mapbox')) : ?>
	data-mapboxmaptype="<?php echo $params->get('mapboxmaptype', 'mapbox/streets-v11'); ?>"
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
	data-showrouting-simple="<?php echo $params->get('showrouting_simple', '1'); ?>"
<?php if ($params->get('showrouting_simple', '1')) : ?>
	data-route-simple-position="<?php echo $params->get('routing_simple_position', 'topright'); ?>"
	data-route-simple-router="<?php echo $params->get('routing_simple_router', 'osrm'); ?>"
	data-route-simple-routerkey="<?php echo $params->get('routing_simple_routerkey', ''); ?>"
	data-route-simple-target="<?php echo $params->get('routing_simple_target', 'Koblenz, Rheinland-Pfalz, Deutschland'); ?>"
<?php endif; ?>
	data-showrouting="<?php echo $params->get('showrouting', '1'); ?>"
<?php if ($params->get('showrouting', '1')) : ?>
	data-routingstart="<?php echo $params->get('routingstart'); ?>"
	data-routingend="<?php echo $params->get('routingend'); ?>"
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
<?php if ($params->get('showcomponentpinone', '1')) : ?>
	data-specialcomponentpinone="<?php echo htmlspecialchars(json_encode($listone), ENT_QUOTES, 'UTF-8'); ?>"
	data-showcomponentpinone="<?php echo $params->get('showcomponentpinone', '1'); ?>"
<?php endif; ?>
	data-geojson="<?php echo $params->get('showgeojson', false); ?>"
<?php if ($params->get('showgeojson', '1')) : ?>
	data-geojsonfile="<?php echo $params->get('showgeojsonfile', false); ?>"
	data-geojson-text="<?php echo htmlspecialchars($params->get('showgeojson_text', '{}'), ENT_QUOTES, 'UTF-8'); ?>"
	data-geojson-file="<?php echo $params->get('showgeojson_file', ''); ?>"
<?php endif; ?>
<?php if ($params->get('showcustomfieldpin', '1') && isset($listcf)) : ?>
	data-specialcustomfieldpins="<?php echo htmlspecialchars(json_encode($listcf), ENT_QUOTES, 'UTF-8'); ?>"
	data-showcustomfieldpin="<?php echo $params->get('showcustomfieldpin', '1'); ?>"
<?php endif; ?>
	data-scrollwheelzoom='<?php echo $params->get('scrollwheelzoom', '') ?>'
	data-owngooglegesturetext='<?php echo $params->get('owngooglegesturetext', '1') ?>'
	data-scroll='<?php echo JText::_('PLG_AGOSMSADDRESSMARKER_SCROLL'); ?>'
	data-touch='<?php echo JText::_('PLG_AGOSMSADDRESSMARKER_TOUCH'); ?>'
	data-scrollmac='<?php echo JText::_('PLG_AGOSMSADDRESSMARKER_SCROLLMAC'); ?>'
	data-uriroot='<?php echo JUri::root(); ?>'
	
	class="leafletmapMod"
	id="map<?php echo $module->id; ?>">
</div>

<?php if ($params->get('addprivacybox', '0')) : ?>
	<p 
		class="p<?php echo $unique ?>"></p>
	<button 
		class="btn btn-success b<?php echo $unique ?>" 
		type="button">	
			<?php echo JText::_('MOD_AGOSM_PRIVACYBUTTON_SHOW_MAP'); ?>
	</button>
<?php endif; ?>

<?php if ($params->get('agmarkerlist', '0') && $params->get('showcustomfieldpin', '1') && isset($listcf)) : ?>
	<?php echo JText::_('MOD_AGOSM_MARKERLISTCF_HEADING') . '<ul class="agmarkerlistul">'; ?>
	<?php
	foreach ($listcf as $marker) {
		if (property_exists($marker, 'popuptext')) {
			echo '<li class="agmarkerlistli' . $marker->id . '"><a class="agmarkerlista' . $marker->id . '" " href="#map' . $module->id .'">' . $marker->popuptext . '</a>';
		}
	}
	?>
	<?php echo '</ul>' . JText::_('MOD_AGOSMCF_MARKERLIST_BOTTOM'); ?>
<?php endif; ?>

<?php if ($params->get('agmarkerlist', '0') && $params->get('showcomponentpin', '1') && isset($list)) : ?>
	<?php echo JText::_('MOD_AGOSM_MARKERLISTCOMONENT_HEADING') . '<ul class="agmarkerlistul">'; ?>
	<?php
	foreach ($list as $marker) {
		if (property_exists($marker, 'popuptext')) {
			echo '<hr><li class="agmarkerlistli_component' . $marker->id . '">' . $marker->popuptext . '<a class="agmarkerlista_component' . $marker->id . '" " href="#map' . $module->id .'">' . "<br><br>" . JText::_('MOD_AGOSM_MARKERLIST_OPEN') . '</a>';
		}
	}
	?>
	<?php echo '</ul>' . JText::_('MOD_AGOSM_MARKERLISTCOMONENT_BOTTOM'); ?>
<?php endif; ?>

<?php if ($params->get('agmarkerlist', '0') && $params->get('showpin', '1')) : ?>
	<?php echo JText::_('MOD_AGOSM_MARKERLISTSPECIAL_HEADING') . '<ul class="agmarkerlistul">'; ?>
	<?php
		$index = 0;
	foreach ($params->get('specialpins', null) as $marker) {
		$index++;
		if (property_exists($marker, 'popuptext')) {
			echo '<li class="agmarkerlistli_specialpin' . $index . '">' . $marker->popuptext . '<a class="agmarkerlista_specialpin' . $index . '" href="#map' . $module->id .'">' . JText::_('MOD_AGOSM_MARKERLIST_OPEN') . '</a>';
		}
	}
	?>
	<?php echo '</ul>' . JText::_('MOD_AGOSM_MARKERLISTSPECIAL_BOTTOM'); ?>
<?php endif; ?>


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
