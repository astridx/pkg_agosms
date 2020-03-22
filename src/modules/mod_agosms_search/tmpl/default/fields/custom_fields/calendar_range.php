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

$active_from =  false;
if (JFactory::getApplication()->input->get->get("field{$field->id}-from")) {
	$active_from = JFactory::getApplication()->input->get->get("field{$field->id}-from");
}

$active_to =  false;
if (JFactory::getApplication()->input->get->get("field{$field->id}-to")) {
	$active_to = JFactory::getApplication()->input->get->get("field{$field->id}-to");
}

$active_from_text_init = '';
$active_to_text_init = '';

if($active_from) {
	$active_from_text_init = DateTime::createFromFormat("Y-m-d", $active_from)->getTimestamp();
	$active_from_text_format = trim(strftime($date_format_init, $active_from_text));
	$active_from_text = mb_convert_case($active_from_text_format, MB_CASE_TITLE, 'UTF-8');
}

if($active_to) {
	$active_to_text_init = DateTime::createFromFormat("Y-m-d", $active_to)->getTimestamp();
	$active_to_text_format = trim(strftime($date_format_init, $active_to_text));
	$active_to_text = mb_convert_case($active_to_text_format, MB_CASE_TITLE, 'UTF-8');
}
?>

<div class="gsearch-field-calendar range custom-field">	
	<h3>
		<?php echo JText::_("{$field->instance->label}"); ?>
	</h3>

	<div class="gsearch-field-calendar-wrapper">
		<input type="text" class="datepicker from" value="<?php echo $active_from_text; ?>" />
		<input type="text" class="datepicker to" value="<?php echo $active_to_text; ?>" />
		<input type="hidden" class="from" name="field<?php echo $field->id; ?>-from" value="<?php echo $active_from; ?>" />
		<input type="hidden" class="to" name="field<?php echo $field->id; ?>-to" value="<?php echo $active_to; ?>" />
	</div>
</div>

