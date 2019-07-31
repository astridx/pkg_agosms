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
defined('_JEXEC') or die('Restricted access');
?>

<div class="gsearch-field-text-range custom-field">	
	<h3>
		<?php echo JText::_("{$field->instance->label}"); ?>
	</h3>
		<?php
		$from =  '';
		if (JFactory::getApplication()->input->post->get("field{$field->id}-from")) {
			$from = JFactory::getApplication()->input->post->get("field{$field->id}-from");
		}		
		$to =  '';
		if (JFactory::getApplication()->input->post->get("field{$field->id}-to")) {
			$to = JFactory::getApplication()->input->post->get("field{$field->id}-to");
		}		
		?>
	<input class="inputbox" name="field<?php echo $field->id; ?>-from" placeholder="<?php echo JText::_("From"); ?>" value="<?php echo $from; ?>" />
	<input class="inputbox" name="field<?php echo $field->id; ?>-to" placeholder="<?php echo JText::_("To"); ?>" value="<?php echo $to; ?>" />
</div>