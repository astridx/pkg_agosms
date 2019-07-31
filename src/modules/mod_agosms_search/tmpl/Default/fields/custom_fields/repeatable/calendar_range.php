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
$k = 0;
foreach($field_params->fields as $tmp) {
	if($tmp->fieldname == $sub_field_selected) {
		$sub_field = $tmp;
		$sub_field_selected_number = $k;
	}
	$k++;
}

$field->instance->label = $sub_field->fieldname;

$name_from = "repeatable{$field->id}-{$sub_field_selected_number}-from";
$name_to = "repeatable{$field->id}-{$sub_field_selected_number}-to";

$active_from = JRequest::getVar($name_from);
$active_to = JRequest::getVar($name_to);
$active_from_text = '';
$active_to_text = '';

if($active_from) {
	$active_from_text = DateTime::createFromFormat("Y-m-d", $active_from)->getTimestamp();
	$active_from_text = trim(strftime($date_format, $active_from_text));
	$active_from_text = mb_convert_case($active_from_text, MB_CASE_TITLE, 'UTF-8');
}

if($active_to) {
	$active_to_text = DateTime::createFromFormat("Y-m-d", $active_to)->getTimestamp();
	$active_to_text = trim(strftime($date_format, $active_to_text));
	$active_to_text = mb_convert_case($active_to_text, MB_CASE_TITLE, 'UTF-8');
}

?>

<div class="gsearch-field-calendar range custom-field">	
	<h3>
		<?php echo JText::_("{$field->instance->label}"); ?>
	</h3>

	<div class="gsearch-field-calendar-wrapper">
		<input type="text" class="datepicker from" value="<?php echo $active_from_text; ?>" />
		<input type="text" class="datepicker to" value="<?php echo $active_to_text; ?>" />
		<input type="hidden" class="from" name="<?php echo $name_from; ?>" value="<?php echo $active_from; ?>" />
		<input type="hidden" class="to" name="<?php echo $name_to; ?>" value="<?php echo $active_to; ?>" />
	</div>
</div>

