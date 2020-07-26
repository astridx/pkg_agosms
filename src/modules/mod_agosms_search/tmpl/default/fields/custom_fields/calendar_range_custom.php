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

$field_id_from = 16; // Need to set fields Id
$field_id_to   = 17;

$curr_locale = JFactory::getLanguage()->getLocale();
setlocale(LC_ALL, $curr_locale);

$active =  array();
if (JFactory::getApplication()->input->get->get('field-date-custom'.$field_id_from)) {
	$active = JFactory::getApplication()->input->get->get('field-date-custom'.$field_id_from);
}

$parts = explode(":", $active);
list($active_from, $active_to) = explode(",", $parts[1]);
if($active_from) {
	$active_from = DateTime::createFromFormat("Y-m-d", $active_from)->getTimestamp(); // custom format
	$active_from = trim(strftime('%b %e %Y', $active_from));
	$active_from = mb_convert_case($active_from, MB_CASE_TITLE, 'UTF-8');
}
if($active_to) {
	$active_to = DateTime::createFromFormat("Y-m-d", $active_to)->getTimestamp(); // custom format
	$active_to = trim(strftime('%b %e %Y', $active_to));
	$active_to = mb_convert_case($active_to, MB_CASE_TITLE, 'UTF-8');
}
// See https://github.com/uxsolutions/bootstrap-datepicker
$doc = JFactory::getDocument();
$document->addStyleSheet(JURI::root(true) . '/media/mod_agosms_search/datepicker/css/bootstrap-datepicker.min.css');
$document->addScript(JURI::root(true) . '/media/mod_agosms_search/datepicker/js/bootstrap-datepicker.min.js');
?>

<div class="gsearch-field-calendar range custom-field calendar-custom<?php echo $field_id_from; ?>">	
	<h3>
		<?php echo JText::_("Date"); ?>
	</h3>

	<div class="gsearch-field-calendar-wrapper custom">
		<input type="text" class="datepicker from" value="<?php echo $active_from; ?>" />
		<input type="text" class="datepicker to" value="<?php echo $active_to; ?>" />
		
		<input type="hidden" name="field-date-custom<?php echo $field_id_from; ?>" value="<?php echo $active; ?>" id="field-date-custom-<?php echo $field_id_from; ?>" />
	</div>

	<?php 
	$lang = JFactory::getLanguage()->getTag();
	$lang = explode("-", $lang)[0];
	if($lang) {
		$document->addScript(JURI::root(true) . '/media/mod_agosms_search/datepicker/locales/bootstrap-datepicker' . $lang . '.min.js');
	 } ?>

	<script>
		jQuery(document).ready(function($) {
			$(".gsearch-field-calendar-wrapper.custom").find(".datepicker").each(function(k, el) {
				$(el).datepicker({
					format: 'MM d yyyy', //custom format
					<?php if($lang) { ?>
					language: "<?php echo $lang; ?>",
					<?php } ?>
				}).on('changeDate', function(e) {
					prepareDates<?php echo $field_id_from; ?>();
				});
				setTimeout(function() {
					$(el).datepicker('show');
					$(el).datepicker('hide'); // fix single number empty space issue;
				}, 200);
			});
		});
		function prepareDates<?php echo $field_id_from; ?>() {
			$ = jQuery.noConflict();
			var el_from = $(".calendar-custom<?php echo $field_id_from; ?>").find(".datepicker.from");
			var el_to = $(".calendar-custom<?php echo $field_id_from; ?>").find(".datepicker.to");
			var date_from = el_from.datepicker('getDate');
			if(date_from) {
				date_from = date_from.getFullYear() + '-' + ('0' + (date_from.getMonth()+1)).slice(-2) + '-' + ('0' + date_from.getDate()).slice(-2);
			}
			else {
				date_from = '';
			}
			var date_to = el_to.datepicker('getDate');
			if(date_to) {
				date_to = date_to.getFullYear() + '-' + ('0' + (date_to.getMonth()+1)).slice(-2) + '-' + ('0' + date_to.getDate()).slice(-2);
			}
			else {
				date_to = '';
			}
			var val = "<?php echo $field_id_from; ?>,<?php echo $field_id_to; ?>:" + date_from + "," + date_to;
			$('input#field-date-custom-<?php echo $field_id_from; ?>').val(val);
		}
	</script>
</div>
