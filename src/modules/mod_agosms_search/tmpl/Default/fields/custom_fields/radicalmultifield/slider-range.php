<?php

/**
 * @package     Articles Good Search
 *
 * @copyright   Copyright (C) 2017 Joomcar extensions. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

$extra_params = json_decode($field->extra_params);
$sub_field_selected = $extra_params->selected;

$field_params = json_decode($field->instance->fieldparams);
$sub_field = new stdClass;
foreach($field_params->listtype as $tmp) {
	if($tmp->name == $sub_field_selected) {
		$sub_field = $tmp;
	}
}

$field->instance->label = $sub_field->title;

$name_from = "multifield{$field->id}-{$sub_field_selected}-from";
$name_to = "multifield{$field->id}-{$sub_field_selected}-to";

$values = $helper->getMultiFieldValuesFromText($field->id, $sub_field_selected, "int", $module->id);
$min = $values[0];
$max = $values[count($values) - 1];
$step = 1;

$active_min = JRequest::getVar($name_from, $min);
$active_max = JRequest::getVar($name_to, $max);

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
			<input id="amount-<?php echo "{$field->id}-{$sub_field_selected}-{$module->id}"; ?>" type="text" style="border: none; background: none; text-align: center;"
				value="<?php echo $active_min; ?> - <?php echo $active_max; ?>"
			/>
		</div>
		<div style="padding: 0 10px;">
			<input id="slider-<?php echo "{$field->id}-{$sub_field_selected}-{$module->id}"; ?>" class="SliderField" />
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
				
				$("#slider-<?php echo "{$field->id}-{$sub_field_selected}-{$module->id}"; ?>").slider({ 
					min: <?php echo $min; ?>, 
					max: <?php echo $max; ?>, 
					range: true, 
					value: [<?php echo $active_min; ?>, <?php echo $active_max; ?>],
					tooltip: "hide",
					step: <?php echo $step; ?>,
				}).on("slide", function(ev) {
					$("input#amount-<?php echo "{$field->id}-{$sub_field_selected}-{$module->id}"; ?>").val(ev.value[0] + ' - ' + ev.value[1]);
					$("input[name=<?php echo $name_from; ?>]").val(ev.value[0]);
					$("input[name=<?php echo $name_to; ?>]").val(ev.value[1]);
				}).on("slideStop", function(ev) {
					$("input#amount-<?php echo "{$field->id}-{$sub_field_selected}-{$module->id}"; ?>").val(ev.value[0] + ' - ' + ev.value[1]);
					$("input[name=<?php echo $name_from; ?>]").val(ev.value[0]);
					$("input[name=<?php echo $name_to; ?>]").val(ev.value[1]);
				});
				
				$("input#amount-<?php echo "{$field->id}-{$sub_field_selected}-{$module->id}"; ?>").on("keyup", function() {
					var value = $(this).val().replace(/\s/g, "").split("-").map(Number);
					$("#slider-<?php echo "{$field->id}-{$sub_field_selected}-{$module->id}"; ?>").slider('setValue', value);
					$("input[name=<?php echo $name_from; ?>]").val(value[0]);
					$("input[name=<?php echo $name_to; ?>]").val(value[1]);
				});
			});
		</script>
		<input class="inputbox" type="hidden" name="<?php echo $name_from; ?>" value="<?php echo JRequest::getVar($name_from, ""); ?>" />
		<input class="inputbox" type="hidden" name="<?php echo $name_to; ?>" value="<?php echo JRequest::getVar($name_to, ""); ?>" />
	</div>
</div>

