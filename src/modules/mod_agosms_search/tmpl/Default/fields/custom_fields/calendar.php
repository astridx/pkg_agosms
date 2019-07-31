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

$active = JRequest::getVar("field{$field->id}");
$active_text = '';

if($active) {
	$active_text = DateTime::createFromFormat("Y-m-d", $active)->getTimestamp();
	$active_text = trim(strftime($date_format, $active_text));
	$active_text = mb_convert_case($active_text, MB_CASE_TITLE, 'UTF-8');
}

?>

<div class="gsearch-field-calendar single custom-field">	
	<h3>
		<?php echo JText::_("{$field->instance->label}"); ?>
	</h3>
	
	<div class="gsearch-field-calendar-wrapper">
		<input type="text" class="datepicker single" value="<?php echo $active_text; ?>" />
		<input type="hidden" name="field<?php echo $field->id; ?>" value="<?php echo $active; ?>" />
	</div>
</div>

