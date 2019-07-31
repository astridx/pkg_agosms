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

?>

<div class="gsearch-field-text-range custom-field">	
	<h3>
		<?php echo JText::_("{$field->instance->label}"); ?>
	</h3>
	<input class="inputbox" name="<?php echo $name_from; ?>" placeholder="<?php echo JText::_("From"); ?>" value="<?php echo JRequest::getVar($name_from); ?>" />
	<input class="inputbox" name="<?php echo $name_to; ?>" placeholder="<?php echo JText::_("To"); ?>" value="<?php echo JRequest::getVar($name_to); ?>" />
</div>

