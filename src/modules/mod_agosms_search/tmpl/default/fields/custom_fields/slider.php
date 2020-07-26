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
if($field->instance->type == "integer") {
	$min = $field_params->first;
	$max = $field_params->last;
	$step = $field_params->step;
}
else {
	$values = $helper->getFieldValuesFromText($field->id, "int", $module->id);
	$min = $values[0];
	$max = $values[count($values) - 1];
	$step = 1;
}

$active_min =  $min;
if (JFactory::getApplication()->input->get->get("field{$field->id}-from")) {
	$active_min = JFactory::getApplication()->input->get->get("field{$field->id}-from");
}

$active_max =  $max;
if (JFactory::getApplication()->input->get->get("field{$field->id}-to")) {
	$active_max = JFactory::getApplication()->input->get->get("field{$field->id}-to");
}

// See https://github.com/seiyria/bootstrap-slider
$doc = JFactory::getDocument();
$document->addStyleSheet(JURI::root(true) . '/media/mod_agosms_search/slider/bootstrap-slider.min.css');
$document->addScript(JURI::root(true) . '/media/mod_agosms_search/slider/bootstrap-slider.min.js');
?>

<div class="gsearch-field-slider custom-field">	
	<h3>
		<?php echo JText::_("{$field->instance->label}"); ?>
	</h3>
	<div class="slider-wrapper">
		<div class="amount">
			<input id="amount-<?php echo "{$field->id}-{$module->id}"; ?>" type="text" style="border: none; background: none; text-align: center;"
				value="<?php echo $active_min; ?> - <?php echo $active_max; ?>"
			/>
		</div>
		<div style="padding: 0 10px;">
			<input id="slider-<?php echo "{$field->id}-{$module->id}"; ?>" class="SliderField" />
		</div>
		<script>
			jQuery(document).ready(function($) {
				//mootools conflict fix
				if(typeof MooTools != 'undefined' ) {
					Element.implement({
						slide: function(how, mode){
							return this;
						}
					});
				}
				
				$("#slider-<?php echo "{$field->id}-{$module->id}"; ?>").bootstrapSlider({ 
					min: <?php echo $min; ?>, 
					max: <?php echo $max; ?>, 
					range: true, 
					value: [<?php echo $active_min; ?>, <?php echo $active_max; ?>],
					tooltip: "hide",
					step: <?php echo $step; ?>,
				}).on("slideStart", function(ev) {
					sliderLock = 1;
				}).on("slide", function(ev) {
					$("input#amount-<?php echo "{$field->id}-{$module->id}"; ?>").val(ev.value[0] + ' - ' + ev.value[1]);
				}).on("slideStop", function(ev) {
					$("input#amount-<?php echo "{$field->id}-{$module->id}"; ?>").val(ev.value[0] + ' - ' + ev.value[1]);
					$("input[name=<?php echo "field{$field->id}-from"; ?>]").val(ev.value[0]);
					$("input[name=<?php echo "field{$field->id}-to"; ?>]").val(ev.value[1]);
					sliderLock = 0;
					$("#GSearch<?php echo $module->id; ?> form").trigger("change");
				});
				
				$("input#amount-<?php echo "{$field->id}-{$module->id}"; ?>").on("keyup", function() {
					var value = $(this).val().replace(/\s/g, "").split("-").map(Number);
					$("#slider-<?php echo "{$field->id}-{$module->id}"; ?>").bootstrapSlider('setValue', value);
					$("input[name=<?php echo "field{$field->id}-from"; ?>]").val(value[0]);
					$("input[name=<?php echo "field{$field->id}-to"; ?>]").val(value[1]);
				});
				$("input#amount-<?php echo "{$field->id}-{$module->id}"; ?>").on("input", function() {
					$("#slider-<?php echo "{$field->id}-{$module->id}"; ?>").bootstrapSlider('refresh');
				});
			});
		</script>
		<input class="inputbox" type="hidden" name="<?php echo "field{$field->id}-from"; ?>" value="<?php echo $active_min; ?>" />
		<input class="inputbox" type="hidden" name="<?php echo "field{$field->id}-to"; ?>" value="<?php echo $active_max; ?>" />
	</div>
</div>

