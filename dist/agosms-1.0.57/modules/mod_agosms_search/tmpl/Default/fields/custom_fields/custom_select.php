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

$field_id_from = 6;
$field_id_to = 28;
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
					<?php if($val == JFactory::getApplication()->input->get("field{$field_id_from}-from")) { ?> 
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
					<?php if($val == JFactory::getApplication()->input->get("field{$field_id_to}-to")) { ?> 
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

