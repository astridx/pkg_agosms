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
$fieldid =  '';
if (JFactory::getApplication()->input->get->get("field" . $field->id)) {
	$fieldid = JFactory::getApplication()->input->get->get("field" . $field->id);
}		
$active = $fieldid;
$field_params = json_decode($field->instance->fieldparams);
$values = $field_params->options;
if($field->instance->type == "integer") {
	$values = Array();
	$range = range($field_params->first, $field_params->last, $field_params->step);
	foreach($range as $val) {
		$tmp = new stdClass;
		$tmp->value = $val;
		$tmp->name = $val;
		$values[] = $tmp;
	}
}
if(!$values) {
	//try to get a values from text (autofill)
	$values = Array();
	$text_values = $helper->getFieldValuesFromText($field->id, "text", $module->id);
	foreach($text_values as $val) {
		$tmp = new stdClass;
		$tmp->value = $val;
		$tmp->name = $val;
		$values[] = $tmp;
	}
}

require_once(JPATH_SITE . "/plugins/system/agosmssearch/models/com_content/model.php");
JFactory::getApplication()->input->set("moduleId", $module->id);

//reset selected value
$tmp = JFactory::getApplication()->input->get("field".$field->id);
JFactory::getApplication()->input->set("field".$field->id, "");

$counters = Array();
JFactory::getApplication()->input->set("initial", 1);
foreach($values as $vk=>$val) {
	JFactory::getApplication()->input->set("field".$field->id, $val->value);
	$model = new ArticlesModelAgSearch;
	$total = $model->total_items;	
	$counters[] = $total;
}
//reset selected value
JFactory::getApplication()->input->set("field".$field->id, $tmp);
JFactory::getApplication()->input->set("initial", 0);

?>

<div class="gsearch-field-select custom-field">	
	<h3>
		<?php echo JText::_("{$field->instance->label}"); ?>
	</h3>
	<select class="inputbox" name="field<?php echo $field->id; ?>[]">
		<option class="empty" value=""><?php echo JText::_("{$field->instance->label}"); ?></option>
		<?php 
		$vk = 0;
		foreach($values as $val) { ?>
			<option 
				value="<?php echo $val->value; ?>"
				<?php if(in_array($val->value, $active)) { ?> 
				selected="selected"
				<?php } ?>
			>
				<?php 
					echo $val->name . " (".$counters[$vk].")";  
				?>
			</option>
		<?php 
			$vk++;
		} ?>
	</select>
</div>

