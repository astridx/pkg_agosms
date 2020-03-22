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

$active_from = JFactory::getApplication()->input->get("date-from");
$active_to = JFactory::getApplication()->input->get("date-to");
$active_from_text = '';
$active_to_text = '';

if($active_from) {
	$active_from_text = DateTime::createFromFormat("Y-m-d", $active_from)->getTimestamp();
	$active_from_text = trim(strftime($date_format, $active_from_text));
	$active_from_text = mb_convert_case($active_from_text, MB_CASE_TITLE, 'UTF-8');
}
if($active_to) {
	$active_to_text = DateTime::createFromFormat("Y-m-d", $active_to)->getTimestamp();
	$active_to_text = trim(strftime($date_format, $active_to_text));
	$active_to_text = mb_convert_case($active_to_text, MB_CASE_TITLE, 'UTF-8');
}

?>

<div class="gsearch-field-calendar date">	
	<h3>
		<?php echo JText::_('MOD_AGOSMSSEARCHFILTER_TYPE_DATE'); ?>
	</h3>

	<div class="gsearch-field-calendar-wrapper">
		<input type="text" class="datepicker from" value="<?php echo $active_from_text; ?>" />
		<input type="text" class="datepicker to" value="<?php echo $active_to_text; ?>" />
		
		<input type="hidden" class="from" name="date-from" value="<?php echo $active_from; ?>" />
		<input type="hidden" class="to" name="date-to" value="<?php echo $active_to; ?>" />
	</div>
</div>

