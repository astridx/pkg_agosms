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

$extra_params = json_decode($field->extra_params);
$sub_field_selected = $extra_params->selected;

$field_params = json_decode($field->instance->fieldparams);
$sub_field = new stdClass;
foreach($field_params->fields as $tmp) {
	if($tmp->fieldname == $sub_field_selected) {
		$sub_field = $tmp;
	}
}

$field->instance->label = $sub_field->fieldname;

$name_from = "multifield{$field->id}-{$sub_field_selected}-from";
$name_to = "multifield{$field->id}-{$sub_field_selected}-to";

$values = $helper->getMultiFieldValuesFromText($field->id, $sub_field_selected, "int", $module->id);
$min = $values[0];
$max = $values[count($values) - 1];
$step = 1;

$active_min =  '';
if (JFactory::getApplication()->input->get->get($name_from, $min)) {
	$active_min = JFactory::getApplication()->input->get->get($name_from, $min);
}

$active_max =  '';
if (JFactory::getApplication()->input->get->get($name_to, $max)) {
	$active_max = JFactory::getApplication()->input->get->get($name_to, $max);
}

$name_from_request =  '';
if (JFactory::getApplication()->input->get->get($name_from)) {
	$name_from_request = JFactory::getApplication()->input->get->get($name_from);
}

$name_to_request =  '';
if (JFactory::getApplication()->input->get->get($name_to)) {
	$name_from_request = JFactory::getApplication()->input->get->get($name_to);
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
		<input class="inputbox" type="hidden" name="<?php echo $name_from; ?>" value="<?php echo $name_from_request; ?>" />
		<input class="inputbox" type="hidden" name="<?php echo $name_to; ?>" value="<?php echo $name_to_request; ?>" />
	</div>
</div>

