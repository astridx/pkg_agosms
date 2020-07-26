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
$k = 0;
foreach($field_params->fields as $tmp) {
	if($tmp->fieldname == $sub_field_selected) {
		$sub_field = $tmp;
		$sub_field_selected_number = $k;
	}
	$k++;
}

$field->instance->label = $sub_field->fieldname;
$name = "repeatable{$field->id}-{$sub_field_selected_number}";

$active =  false;
if (JFactory::getApplication()->input->get->get($name)) {
	$active = JFactory::getApplication()->input->get->get($name);
}
$active_text_init = '';

if($active) {
	$active_text_init = DateTime::createFromFormat("Y-m-d", $active)->getTimestamp();
	$active_text_format = trim(strftime($date_format, $active_text_init));
	$active_text = mb_convert_case($active_text_format, MB_CASE_TITLE, 'UTF-8');
}
?>

<div class="gsearch-field-calendar single custom-field">	
	<h3>
		<?php echo JText::_("{$field->instance->label}"); ?>
	</h3>
	
	<div class="gsearch-field-calendar-wrapper">
		<input type="text" class="datepicker single" value="<?php echo $active_text; ?>" />
		<input type="hidden" name="<?php echo $name; ?>" value="<?php echo $active; ?>" />
	</div>
</div>

