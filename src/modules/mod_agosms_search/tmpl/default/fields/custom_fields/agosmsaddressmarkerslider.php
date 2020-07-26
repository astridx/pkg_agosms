<?php
/**
 * @package     Joomla.Site
 * @subpackage  pkg_agosms
 *
 * @copyright   Copyright (C) 2005 - 2019 Astrid Günther, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later;
 * @link        astrid-guenther.de
 */


// no direct access
defined('_JEXEC') or die;

$field_params = json_decode($field->instance->fieldparams);

// Set defaults
$min = 0;
$max = 2000;
$step = 10;
$lon = 0;
$lat = 0;
$address = '';
$moduleParams = new JRegistry($module->params);

if($field->instance->type == "agosmsaddressmarker") {
	$min = $moduleParams->get('first', 0);
	$max = $moduleParams->get('last', 2000);
	$step = $moduleParams->get('step', 10);
}
else
{
	$values = $helper->getFieldValuesFromText($field->id, "int", $module->id);
	$min = $values[0];
	$max = $values[count($values) - 1];
	$step = 1;
}

// Attention: Here field_{ is correct
$active_min =  $min;
if (JFactory::getApplication()->input->get->get("field_{$field->id}-from")) {
	$active_min = JFactory::getApplication()->input->get->get("field_{$field->id}-from");
}
$active_max =  $max;
if (JFactory::getApplication()->input->get->get("field_{$field->id}-to")) {
	$active_max = JFactory::getApplication()->input->get->get("field_{$field->id}-to");
}

$active_lon = $lon;
if (JFactory::getApplication()->input->get->get("field_{$field->id}-lon")) {
	$active_lon = JFactory::getApplication()->input->get->get("field_{$field->id}-lon");
}
$active_lat = $lat;
if (JFactory::getApplication()->input->get->get("field_{$field->id}-lat")) {
	$active_lat = JFactory::getApplication()->input->get->get("field_{$field->id}-lat");
}
$active_address = $address;
if (JFactory::getApplication()->input->get->get("field_{$field->id}-address")) {
	$active_address = JFactory::getApplication()->input->get->get("field_{$field->id}-address");
}

$doc = JFactory::getDocument();

// See https://github.com/seiyria/bootstrap-slider
$document->addStyleSheet(JURI::root(true) . '/media/mod_agosms_search/slider/bootstrap-slider.min.css');
$document->addScript(JURI::root(true) . '/media/mod_agosms_search/slider/bootstrap-slider.min.js');
$document->addScript(JURI::root(true) . '/media/mod_agosms_search/slider/agosmsaddressmarkerslider/agosmsaddressmarkerslider.js');
?>
<hr>
<div class="gsearch-field-slider custom-field">	
	<h3>
		<?php echo JText::_("{$field->instance->label}"); ?>
	</h3>
	<h4>
		<?php echo JText::_("MOD_AGOSMSSEARCH_DISTANCE"); ?>
	</h4>
	<div class="slider-wrapper">
		<div class="amount">
			<input id="amount-<?php echo "{$field->id}-{$module->id}"; ?>" type="text" style="border: none; background: none; text-align: center;"
				value="<?php echo $active_min; ?> - <?php echo $active_max; ?>"
			/>
		</div>
		<div style="padding: 0 10px;">
			<input 
				id="agslider-<?php echo "{$field->id}-{$module->id}"; ?>" 
				class="agSliderField" 
				data-slider-min="<?php echo $min; ?>"
				data-slider-max="<?php echo $max; ?>"
				data-slider-step="<?php echo $step; ?>"
				data-slider-tooltip="hide"
			/>
		</div>
		<script>
			//jQuery(document).ready(function($) {
				
/*	
 
 				}).on("slideStop", function(ev) {
					$("input#amount-<?php echo "{$field->id}-{$module->id}"; ?>").val(ev.value[0] + ' - ' + ev.value[1]);
					$("input[name=<?php echo "field_{$field->id}-from"; ?>]").val(ev.value[0]);
					$("input[name=<?php echo "field_{$field->id}-to"; ?>]").val(ev.value[1]);
					sliderLock = 0;
					$("#GSearch<?php echo $module->id; ?> form").trigger("change");
				});
				
				$("input#amount-<?php echo "{$field->id}-{$module->id}"; ?>").on("keyup", function() {
					var value = $(this).val().replace(/\s/g, "").split("-").map(Number);
					$("#slider-<?php echo "{$field->id}-{$module->id}"; ?>").bootstrapSlider('setValue', value);
					$("input[name=<?php echo "field_{$field->id}-from"; ?>]").val(value[0]);
					$("input[name=<?php echo "field_{$field->id}-to"; ?>]").val(value[1]);
				});
				$("input#amount-<?php echo "{$field->id}-{$module->id}"; ?>").on("input", function() {
					$("#slider-<?php echo "{$field->id}-{$module->id}"; ?>").bootstrapSlider('refresh');
				});
			});*/
		</script>
		<input style="display:none" class="inputbox" type="text" name="<?php echo "field_{$field->id}-from"; ?>" value="<?php echo $active_min; ?>" />
		<input style="display:none" class="inputbox" type="text" name="<?php echo "field_{$field->id}-to"; ?>" value="<?php echo $active_max; ?>" />
	</div>
</div>

<div class="agosmsaddressmarkersurroundingdiv form-horizontal">
	<h4>
		<?php echo JText::_("MOD_AGOSMSSEARCH_CORDS"); ?>
	</h4>
<div class="control-group">
<label class="control-label"><?php echo JText::_('MOD_AGOSMSSEARCH_LAT'); ?></label>	
<div class="controls">
	<input 
		type="text"
		name="<?php echo "field_{$field->id}-lat"; ?>"
		value="<?php echo $active_lat; ?>"
		class="agosmsaddressmarkerlat inputbox" >
</div>
</div>

<div class="control-group">
<label class="control-label"><?php echo JText::_('MOD_AGOSMSSEARCH_LON'); ?></label>	
<div class="controls">	
	<input 
		type="text"
		name="<?php echo "field_{$field->id}-lon"; ?>"
		value="<?php echo $active_lon; ?>"
		class="agosmsaddressmarkerlon inputbox" >
</div>
</div>

<div class="control-group">
<label class="control-label"><?php echo JText::_('MOD_AGOSMSSEARCH_ADDRESS'); ?></label>	
<div class="controls">	
<input type="text" class="agosmsaddressmarkeraddress inputbox" >
</div>
</div>
	
<button 
		data-fieldsnamearray="<?php //echo $fieldsNameImplode; ?>"
		data-mapboxkey="<?php //echo $mapboxkey; ?>"
		data-googlekey="<?php //echo $googlekey; ?>"
		class="btn btn-success agosmsaddressmarkerbutton" 
		type="button">
<?php echo JText::_('MOD_AGOSMSSEARCH_CALCULATE_CORDS'); ?>
	</button>
</div>
<hr>