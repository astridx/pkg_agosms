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

use Joomla\CMS\HTML\HTMLHelper;

extract($displayData);

$mapid = "map" . uniqid();

// Stylesheet
echo HTMLHelper::_('stylesheet', 'media/mod_agosm/leaflet/leaflet.css', ['version' => '1.0.1', 'relative' => false]);
echo HTMLHelper::_('stylesheet', 'mod_agosm/agomsaddressfinder.css', ['version' => 'auto', 'relative' => false]);

// Script
echo HTMLHelper::_('script', 'media/mod_agosm/leaflet/leaflet.js', ['version' => '1.0.1', 'relative' => false], ['defer' => true]);
echo HTMLHelper::_('script', 'media/mod_agosm/js/agosmsaddressfinder.js', ['version' => 'auto', 'relative' => false], ['defer' => true]);


JText::script('MOD_AGOSM_ADDRESSFINDER_ADDRESSE_ERROR');
JText::script('MOD_AGOSM_ADDRESSFINDER_ADDRESSE_NOTICE');

$attributes = [
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
];

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
	//JFactory::getApplication()->enqueueMessage(JText::_('MOD_AGOSM_ADDRESSFINDER_SUPPORTET'), 'message');
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

<hr>
<div class="agomsaddressfindersurroundingdiv form-horizontal">

<div class="control-group">
<label class="control-label"><?php echo JText::_('MOD_AGOSM_ADDRESSFINDER_LAT'); ?></label>	
<div class="controls">
	<input type="text" class="agomsaddressfinderlat" >
</div>
</div>

<div class="control-group">
<label class="control-label"><?php echo JText::_('MOD_AGOSM_ADDRESSFINDER_LON'); ?></label>	
<div class="controls">	
<input type="text" class="agomsaddressfinderlon" >
</div>
</div>
<p>
<?php echo JText::_('MOD_AGOSM_ADDRESSFINDER_OPTIONAL_HINT'); ?>	
</p>	
<hr>
<div style="height:300px;width:auto" id="<?php echo $mapid; ?>"></div>
<hr>
<div class="control-group">
<label class="control-label"><?php echo JText::_('MOD_AGOSM_ADDRESSFINDER_ADRESSE'); ?></label>	
<div class="controls">	
<input type="text" class="agomsaddressfinderaddressfield" >
</div>
</div>	
	
<button 
		data-mapid="<?php echo $mapid; ?>"
		class="btn btn-success agomsaddressfinderbutton_address" 
		type="button">
<?php echo JText::_('MOD_AGOSM_ADDRESSFINDER_CALCULATE_CORDS_FROM_ADDRESS'); ?>
</button>
	
<input 
	class="agomsaddressfinderhiddenfield" 
	type="hidden" 
	readonly name="<?php echo $name; ?>" id="<?php echo $id; ?>" 
	value="<?php echo htmlspecialchars($value, ENT_COMPAT, 'UTF-8'); ?>" <?php echo implode(' ', $attributes); ?> 
/>

</div>
<hr>