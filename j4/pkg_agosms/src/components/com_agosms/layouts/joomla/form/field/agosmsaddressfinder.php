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

$mapid = "map" . uniqid();
$document = JFactory::getDocument();

$wa = $document->getWebAssetManager();
$wa->useScript('com_agosms.leaflet')
	->useScript('com_agosms.agosmsaddressfinder')
	->useStyle('com_agosms.leaflet');

JHtml::_('stylesheet', 'mod_agosm/agomsaddressfinder.css', ['version' => 'auto', 'relative' => true]);

JText::script('COM_AGOSMS_ADDRESSFINDER_ADDRESSE_ERROR');
JText::script('COM_AGOSMS_ADDRESSFINDER_ADDRESSE_NOTICE');

// Define defaults
$app = JFactory::getApplication();
$context = 'com_content.article';

// Com_categorie
if ($app->input->getCmd('option') === 'com_categories') {
	$context = $app->input->getCmd('extension') . '.categories';
}

// Com_users
else if ($app->input->getCmd('option') === 'com_users') {
	$context = 'com_users.user';
}

// Com_contact
else if ($app->input->getCmd('option') === 'com_contact') {
	//JFactory::getApplication()->enqueueMessage(JText::_('COM_AGOSMS_ADDRESSFINDER_SUPPORTET'), 'message');
	$context = 'com_agosms.agosm';
}

// Third Party
else if ($app->input->getCmd('option') !== 'com_users'
	&& $app->input->getCmd('option') !== 'com_content'
	&& $app->input->getCmd('option') !== 'com_categories'
	&& $app->input->getCmd('option') !== 'com_contact') {
	$context = $app->input->getCmd('option') . '.' . $app->input->getCmd('view');
}

?>


<div class="agomsaddressfindersurroundingdiv form-horizontal">

<div style="display:none" class="control-group">
<label class="control-label"><?php echo JText::_('COM_AGOSMS_ADDRESSFINDER_LAT'); ?></label>	
<div class="controls">
	<input type="text" class="agomsaddressfinderlat" >
</div>
</div>

<div style="display:none" class="control-group">
<label class="control-label"><?php echo JText::_('COM_AGOSMS_ADDRESSFINDER_LON'); ?></label>	
<div class="controls">	
<input type="text" class="agomsaddressfinderlon" >
</div>
</div>
	
<div class="control-group">
<label class="control-label"><?php echo JText::_('COM_AGOSMS_ADDRESSFINDER_ADRESSE'); ?></label>	
<div class="controls">	
<input type="text" class="agomsaddressfinderaddressfield" >
</div>
</div>	
	
<button 
		data-mapid="<?php echo $mapid; ?>"
		class="mb-2 btn btn-primary agomsaddressfinderbutton_address" 
		type="button">
<?php echo JText::_('COM_AGOSMS_ADDRESSFINDER_CALCULATE_CORDS_FROM_ADDRESS'); ?>
</button>

<div 
class="adressfindermap"
style="z-index:1;height:<?php echo $displayData["height"]; ?><?php echo $displayData["heightunit"]; ?>;width:auto" 
data-restriced-cords="<?php echo $displayData["restricedCords"]?>"
id="<?php echo $mapid; ?>">
</div>
	
<input 
	class="agomsaddressfinderhiddenfield" 
	type="hidden" 
	readonly name="<?php echo $name; ?>" id="<?php echo $id; ?>" 
	value="<?php echo htmlspecialchars($value, ENT_COMPAT, 'UTF-8'); ?>"
/>

</div>
