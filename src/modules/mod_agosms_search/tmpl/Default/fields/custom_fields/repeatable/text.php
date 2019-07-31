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
$name = "repeatable{$field->id}-{$sub_field_selected_number}";

?>

<div class="gsearch-field-text custom-field">	
	<h3>
		<?php echo JText::_("{$field->instance->label}"); ?>
	</h3>
	<input class="inputbox" name="<?php echo $name; ?>" placeholder="<?php echo JText::_("{$field->instance->label}"); ?>" value="<?php echo JRequest::getVar($name); ?>" />
</div>

