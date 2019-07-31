<?php
/**
 * @package     Articles Good Search
 *
 * @copyright   Copyright (C) 2017 Joomcar extensions. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

$field_id_from = 6; //field from
$field_id_to = 28; //field to
$values_from = $helper->getFieldValuesFromText($field_id_from, "text", $module->id);
$values_to = $helper->getFieldValuesFromText($field_id_to, "text", $module->id);
?>

<div class="gsearch-field-custom-select custom-field">	
	<h3>
		<?php echo JText::_("Custom Select"); ?>
	</h3>
	<div class="fields-wrapper">		
		<select class="inputbox" name="field<?php echo $field_id_from; ?>-from">
			<option class="empty" value=""><?php echo JText::_("Custom Select"); ?></option>
			<?php foreach($values_from as $val) { ?>
				<option 
					value="<?php echo $val; ?>"
					<?php if($val == JRequest::getVar("field{$field_id_from}-from")) { ?> 
					selected="selected"
					<?php } ?>
				>
					<?php 
						echo $val; 
					?>
				</option>
			<?php } ?>
		</select>
		<span>-</span>
		<select class="inputbox" name="field<?php echo $field_id_to; ?>-to">
			<option class="empty" value=""><?php echo JText::_("Custom Select"); ?></option>
			<?php foreach($values_to as $val) { ?>
				<option 
					value="<?php echo $val; ?>"
					<?php if($val == JRequest::getVar("field{$field_id_to}-to")) { ?> 
					selected="selected"
					<?php } ?>
				>
					<?php 
						echo $val; 
					?>
				</option>
			<?php } ?>
		</select>		
	</div>
</div>