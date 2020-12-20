<?php
/**
 * @package     Joomla.Site
 * @subpackage  pkg_agosms
 *
 * @copyright   Copyright (C) 2005 - 2019 Astrid Günther, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later;
 * @link        astrid-guenther.de
 */
defined('JPATH_BASE') or die;

extract($displayData);

$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root(true) . '/media/mod_agosm/leaflet/leaflet.css');
// TGE $document->addScript(JURI::root(true) . '/media/mod_agosm/leaflet/leaflet.js', true);
// TGE $document->addScript(JURI::root(true) . '/media/mod_agosm/js/agosmsaddressfinder.js', true);
// TGE:
$document->addScript(JURI::root(true) . '/media/mod_agosm/leaflet/leaflet.js');
$document->addScript(JURI::root(true) . '/media/mod_agosm/js/agosmsaddressfinder.js');

JHtml::_('stylesheet', 'mod_agosm/agomsaddressfinder.css', array('version' => 'auto', 'relative' => true));

JText::script('MOD_AGOSM_ADDRESSFINDER_ADDRESSE_ERROR');
JText::script('MOD_AGOSM_ADDRESSFINDER_ADDRESSE_NOTICE');

$attributes = array(
	!empty($class) ? 'class="' . $class . '"' : '',
	!empty($size) ? 'size="' . $size . '"' : '',
	$disabled ? 'disabled' : '',
	$readonly ? 'readonly' : '',
	strlen($hint) ? 'placeholder="' . htmlspecialchars($hint, ENT_COMPAT, 'UTF-8') . '"' : '',
	$onchange ? ' onchange="' . $onchange . '"' : '',
	!empty($maxLength) ? $maxLength : '',
	$required ? 'required aria-required="true"' : '',
	$autocomplete,
	$autofocus ? ' autofocus' : '',
	$spellcheck ? '' : 'spellcheck="false"',
	!empty($inputmode) ? $inputmode : '',
	!empty($pattern) ? 'pattern="' . $pattern . '"' : '',
);

// Define defaults
$app = JFactory::getApplication();
$context = 'com_content.article';

// Com_categorie
if ($app->input->getCmd('option') === 'com_categories')
{
	$context = $app->input->getCmd('extension') . '.categories';
}

// Com_users
elseif ($app->input->getCmd('option') === 'com_users')
{
	$context = 'com_users.user';
}

// Com_contact
elseif ($app->input->getCmd('option') === 'com_contact')
{
	//JFactory::getApplication()->enqueueMessage(JText::_('MOD_AGOSM_ADDRESSFINDER_SUPPORTET'), 'message');
	$context = 'com_contact.contact';
}

// Third Party
elseif ($app->input->getCmd('option') !== 'com_users'
	&& $app->input->getCmd('option') !== 'com_content'
	&& $app->input->getCmd('option') !== 'com_categories'
	&& $app->input->getCmd('option') !== 'com_contact')
{
	$context = $app->input->getCmd('option') . '.' . $app->input->getCmd('view');
}

?>

<div class="form-horizontal">
<!-- TGE -->
<table>
  <tr><td><label class="control-label"><?php echo JText::_('MOD_AGOSM_ADDRESSFINDER_LAT'); ?></label></td><td><input type="text" class="agomsaddressfinderlat" /></td><td></td></tr>
  <tr><td><label class="control-label"><?php echo JText::_('MOD_AGOSM_ADDRESSFINDER_LON'); ?></label></td><td><input type="text" class="agomsaddressfinderlon" /></td><td></td></tr>
  <tr><td><label class="control-label"><?php echo JText::_('MOD_AGOSM_ADDRESSFINDER_ADRESSE'); ?></label> </td><td> <input type="text" class="agomsaddressfinderaddressfield" /></td>
  <td>
    <div class="agomsaddressfindersurroundingdiv">
      <button class="btn btn-success agomsaddressfinderbutton_address" type="button">
       <?php echo JText::_('MOD_AGOSM_ADDRESSFINDER_CALCULATE_CORDS_FROM_ADDRESS'); ?>
      </button>
      <input class="agomsaddressfinderhiddenfield" type="hidden" readonly name="<?php echo $name; ?>" id="<?php echo $id; ?>" 
             value="<?php echo htmlspecialchars($value, ENT_COMPAT, 'UTF-8'); ?>" <?php echo implode(' ', $attributes); ?> />
    </div>
  </td></tr>
</table>
<?php echo JText::_('MOD_AGOSM_ADDRESSFINDER_OPTIONAL_HINT'); ?>

<hr>
<div class="tge_addressfinder_map"></div>

</div>

<script>
 // Effectively we need the map ID. If there is no map yet, create a temporary one, which will get 
 var mapID = "tge_" + new Date().getTime();

 // Possibly there is already a map - this is the case of a frontend edit. Then use that map.
 var mapContainer = jQuery(".leafletmapMod");
 if(mapContainer.length)
 {
   mapID = "my" + mapContainer.attr("id");
   console.log("TGE: Map already exists, ID: " + mapID);
   jQuery(".tge_addressfinder_map").remove();
 }
 else
 {
   mapContainer = jQuery(".tge_addressfinder_map");
   mapContainer.attr("id", mapID);
   mapContainer.css("height", "300px").css("width", "auto");
   console.log("TGE: Generated map ID: " + mapID);
 }
 var button = jQuery(".agomsaddressfinderbutton_address");
 button.attr('data-mapid', mapID);
</script>

