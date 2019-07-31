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

if($max > 1000000) {
	$step = 1000;
}

$active_min = JRequest::getVar("field{$field->id}-from", $min);
$active_max = JRequest::getVar("field{$field->id}-to", $max);

$doc = JFactory::getDocument();
$doc->addScript('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/9.8.0/bootstrap-slider.min.js');
$doc->addStyleSheet('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/9.8.0/css/bootstrap-slider.min.css');

$delimiter = ' '; //thousand separator
$active_min_amount = number_format($active_min, 0, '', $delimiter);
$active_max_amount = number_format($active_max, 0, '', $delimiter);
 
?>

<div class="gsearch-field-slider custom-field">	
	<h3>
		<?php echo JText::_("{$field->instance->label}"); ?>
	</h3>
	<div class="slider-wrapper">
		<div class="amount">
			<input id="amount-<?php echo "{$field->id}-{$module->id}"; ?>" type="text" style="border: none; background: none; text-align: center;"
				value="<?php echo $active_min_amount; ?> - <?php echo $active_max_amount; ?>"
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
					step: <?php echo $step; ?>,
				}).on("slide", function(ev) { //change by slide
					<?php if($max > 1000000) { ?>
						var value_min = ev.value[0];
						if(value_min != <?php echo $min; ?>) {
							value_min -= <?php echo $min; ?>;
						}
						var value_max = ev.value[1];
						if(value_max != <?php echo $max; ?>) {
							value_max -= <?php echo $min; ?>;
						}
						$("input#amount-<?php echo "{$field->id}-{$module->id}"; ?>").val(addCommas_<?php echo $field->id; ?>_<?php echo $module->id; ?>(value_min) + ' - ' + addCommas_<?php echo $field->id; ?>_<?php echo $module->id; ?>(value_max));
						$("input[name=<?php echo "field{$field->id}-from"; ?>]").val(ev.value[0] - <?php echo $min; ?>);
						$("input[name=<?php echo "field{$field->id}-to"; ?>]").val(ev.value[1] - <?php echo $min; ?>);
					<?php } else { ?>
						$("input#amount-<?php echo "{$field->id}-{$module->id}"; ?>").val(addCommas_<?php echo $field->id; ?>_<?php echo $module->id; ?>(ev.value[0]) + ' - ' + addCommas_<?php echo $field->id; ?>_<?php echo $module->id; ?>(ev.value[1]));
						$("input[name=<?php echo "field{$field->id}-from"; ?>]").val(ev.value[0]);
						$("input[name=<?php echo "field{$field->id}-to"; ?>]").val(ev.value[1]);
					<?php } ?>
				}).on("slideStop", function(ev) { //change by click on the line
					<?php if($max > 1000000) { ?>
						var value_min = ev.value[0];
						if(value_min != <?php echo $min; ?>) {
							value_min -= <?php echo $min; ?>;
						}
						var value_max = ev.value[1];
						if(value_max != <?php echo $max; ?>) {
							value_max -= <?php echo $min; ?>;
						}
						$("input#amount-<?php echo "{$field->id}-{$module->id}"; ?>").val(addCommas_<?php echo $field->id; ?>_<?php echo $module->id; ?>(value_min) + ' - ' + addCommas_<?php echo $field->id; ?>_<?php echo $module->id; ?>(value_max));
						$("input[name=<?php echo "field{$field->id}-from"; ?>]").val(ev.value[0] - <?php echo $min; ?>);
						$("input[name=<?php echo "field{$field->id}-to"; ?>]").val(ev.value[1] - <?php echo $min; ?>);
					<?php } else { ?>
						$("input#amount-<?php echo "{$field->id}-{$module->id}"; ?>").val(addCommas_<?php echo $field->id; ?>_<?php echo $module->id; ?>(ev.value[0]) + ' - ' + addCommas_<?php echo $field->id; ?>_<?php echo $module->id; ?>(ev.value[1]));
						$("input[name=<?php echo "field{$field->id}-from"; ?>]").val(ev.value[0]);
						$("input[name=<?php echo "field{$field->id}-to"; ?>]").val(ev.value[1]);
					<?php } ?>
				});
				
				$("input#amount-<?php echo "{$field->id}-{$module->id}"; ?>").on("keyup", function() {
					var value = $(this).val().replace(/\s/g, "").split("-").map(Number);
					$("#slider-<?php echo "{$field->id}-{$module->id}"; ?>").slider('setValue', value);
					$("input[name=<?php echo "field{$field->id}-from"; ?>]").val(value[0]);
					$("input[name=<?php echo "field{$field->id}-to"; ?>]").val(value[1]);
				});
			});
			
			function addCommas_<?php echo $field->id; ?>_<?php echo $module->id; ?>(nStr) {
				var delimiter = '<?php echo $delimiter; ?>';
				nStr += '';
				x = nStr.split(delimiter);
				x1 = x[0];
				x2 = x.length > 1 ? ',' + x[1] : '';
				var rgx = /(\d+)(\d{3})/;
				while (rgx.test(x1)) {
					x1 = x1.replace(rgx, '$1' + delimiter + '$2');
				}
				return x1 + x2;
			}
		</script>
		<input class="inputbox" type="hidden" name="<?php echo "field{$field->id}-from"; ?>" value="<?php echo JRequest::getVar("field{$field->id}-from", ""); ?>" />
		<input class="inputbox" type="hidden" name="<?php echo "field{$field->id}-to"; ?>" value="<?php echo JRequest::getVar("field{$field->id}-to", ""); ?>" />
	</div>
</div>

