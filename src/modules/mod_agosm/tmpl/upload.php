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
use Joomla\CMS\Uri\Uri;

$defaultArray = [];

$labelText = Text::_('MOD_AG_LABEL_TEXT');
$buttonText = Text::_('MOD_AG_BUTTON_TEXT');
$questionText = Text::_('MOD_AG_QUESTION_TEXT');
$yesText = Text::_('JYES');
$noText = Text::_('JNO');

$action = Uri::current();
?>

<div class="<?php echo $moduleclass_sfx;?>">
	<?php 
	if (isset($_FILES[$params->get('ag_variable')])) : 
		?>
		<?php for ($j = 0; $j < count($result); $j++) : ?>
			<?php 
			$show_class = "";
			if ($result[$j]['show'] == false) : 
				$show_class = " agm-hide";
			endif; ?>
			<div class="agm-alert agm-<?php echo $result[$j]['type'].$show_class;?>">
			<span class="close-btn" onclick="this.parentNode.style.display = 'none';">&times;</span>
			<?php echo $result[$j]['text']; ?>
			</div>
		<?php endfor; ?>
	<?php endif; ?>
	<!-- Input form for the File Upload -->
	<form enctype="multipart/form-data" action="<?php echo $action; ?>" method="post">
		<?php if ($params->get('ag_multiple') == "1"): ?>
		<label for=<?php echo '"'.$params->get('ag_variable').'[]"'; ?>><?php echo $labelText; ?></label>
		<?php else: ?>
		<?php echo $labelText; ?><br />
		<?php endif; ?>
		<?php 
		$max = intval($params->get('ag_multiple'));
		for ($i = 0; $i < $max; $i++): ?>
		<input type="file" name=<?php echo '"'.$params->get('ag_variable').'[]"'; ?> id=<?php echo '"'.$params->get('ag_variable').'[]"'; ?> /> 
		<br />
		<?php endfor; ?>
		<?php if ($params->get('ag_default_replace') == false && $params->get('ag_replace') == true): /* 1 means 'Yes' or true. 0 means 'No' or false. */ ?>
		<div><?php echo $questionText; ?></div>
		<input type="radio" name="answer" value="1" /><?php echo $yesText; ?><br />
		<input type="radio" name="answer" value="0" checked /><?php echo $noText; ?><br />
		<br />
		<?php endif; ?>
		<input class="btn" type="submit" name="submit" value=<?php echo '"' . $buttonText . '"'; ?> />
	</form>
</div>

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
<?php if ($params->get('showcustomfieldpin', '1')) : ?>
	data-specialcustomfieldpins="<?php echo htmlspecialchars(json_encode($listcf), ENT_QUOTES, 'UTF-8'); ?>"
	data-showcustomfieldpin="<?php echo $params->get('showcustomfieldpin', '1'); ?>"
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

JText::script('MOD_AGOSM_ROUTING_SIMPLE_TEXT_PLACEHOLDER');
JText::script('MOD_AGOSM_ROUTING_SIMPLE_TEXT_ERRORMESSAGE');
JText::script('MOD_AGOSM_ROUTING_SIMPLE_TEXT_DISTANCE');
JText::script('MOD_AGOSM_ROUTING_SIMPLE_TEXT_DURATION');
JText::script('MOD_AGOSM_ROUTING_SIMPLE_TEXT_REQUESTERROR');
