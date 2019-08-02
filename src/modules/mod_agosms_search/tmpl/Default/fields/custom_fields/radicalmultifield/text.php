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
foreach($field_params->listtype as $tmp) {
	if($tmp->name == $sub_field_selected) {
		$sub_field = $tmp;
	}
}

$field->instance->label = $sub_field->title;
$name = "multifield{$field->id}-{$sub_field_selected}";

$name_from_request =  '';
if (JFactory::getApplication()->input->get->get($name)) {
	$name_from_request = JFactory::getApplication()->input->get->get($name);
}
?>

<div class="gsearch-field-text custom-field">	
	<h3>
		<?php echo JText::_("{$field->instance->label}"); ?>
	</h3>
	<input class="inputbox" name="<?php echo $name; ?>" placeholder="<?php echo JText::_("{$field->instance->label}"); ?>" value="<?php echo $name_from_request; ?>" />
</div>

