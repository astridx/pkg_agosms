<?php
/**
 * @package     Articles Good Search
 *
 * @copyright   Copyright (C) 2017 Joomcar extensions. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

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
$active_min = JRequest::getVar("field{$field->id}-from", $min);
$active_max = JRequest::getVar("field{$field->id}-to", $max);

$doc = JFactory::getDocument();
$doc->addScript('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/9.8.0/bootstrap-slider.min.js');
$doc->addStyleSheet('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/9.8.0/css/bootstrap-slider.min.css');
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
				
				$("#slider-<?php echo "{$field->id}-{$module->id}"; ?>").slider({ 
					min: <?php echo $min; ?>, 
					max: <?php echo $max; ?>, 
					range: true, 
					value: [<?php echo $active_min; ?>, <?php echo $active_max; ?>],
					tooltip: "hide",
				}).on("slide", function(ev) {
					$("input#amount-<?php echo "{$field->id}-{$module->id}"; ?>").val(ev.value[0] + ' - ' + ev.value[1]);
					$("input[name=<?php echo "field{$field->id}-from"; ?>]").val(ev.value[0]);
					$("input[name=<?php echo "field{$field->id}-to"; ?>]").val(ev.value[1]);
				}).on("slideStop", function(ev) {
					$("input#amount-<?php echo "{$field->id}-{$module->id}"; ?>").val(ev.value[0] + ' - ' + ev.value[1]);
					$("input[name=<?php echo "field{$field->id}-from"; ?>]").val(ev.value[0]);
					$("input[name=<?php echo "field{$field->id}-to"; ?>]").val(ev.value[1]);
				});
				
				$("input#amount-<?php echo "{$field->id}-{$module->id}"; ?>").on("keyup", function() {
					var value = $(this).val().replace(/\s/g, "").split("-").map(Number);
					$("#slider-<?php echo "{$field->id}-{$module->id}"; ?>").slider('setValue', value);
					$("input[name=<?php echo "field{$field->id}-from"; ?>]").val(value[0]);
					$("input[name=<?php echo "field{$field->id}-to"; ?>]").val(value[1]);
				});
			});
		</script>
		<input class="inputbox" type="hidden" name="<?php echo "field{$field->id}-from"; ?>" value="<?php echo JRequest::getVar("field{$field->id}-from", ""); ?>" />
		<input class="inputbox" type="hidden" name="<?php echo "field{$field->id}-to"; ?>" value="<?php echo JRequest::getVar("field{$field->id}-to", ""); ?>" />
	</div>
</div>