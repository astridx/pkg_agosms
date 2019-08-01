<?php
/**
 * @package     Joomla.Site
 * @subpackage  pkg_agosms
 *
 * @copyright   Copyright (C) 2005 - 2019 Astrid Günther, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later;
 * @link        astrid-guenther.de
 */


defined('_JEXEC') or die;

$customSorting = explode("\r\n", $model->module_params->ordering_fields);
$sortingFields = array();
foreach($customSorting as $field) {
	$tmp = new stdClass;
	$aField = explode(":", $field);
	$tmp->id = $aField[1];
	$flt = explode('{', $field, 2);
	if(!empty($flt[1]) && $flt[1] != '') {
		$extra_params = json_decode('{' . $flt[1]);
		$tmp->name = $extra_params->name;
	}
	$sortingFields[] = $tmp;
}

?>

<select class="inputbox select ordering">
	<option value=""><?php echo JText::_('MOD_AGOSMSSEARCHSORTING_SELECT'); ?></div>
	<option value="title"><?php echo JText::_('MOD_AGOSMSSEARCHSORTING_TITLE'); ?></div>
	<option value="alias"><?php echo JText::_('MOD_AGOSMSSEARCHSORTING_ALIAS'); ?></div>
	<option value="created"><?php echo JText::_('MOD_AGOSMSSEARCHSORTING_DATE'); ?></div>
	<option value="publish_up"><?php echo JText::_('MOD_AGOSMSSEARCHSORTING_DATE_PUBLISHING'); ?></div>
	<option value="category"><?php echo JText::_('MOD_AGOSMSSEARCHSORTING_CATEGORY'); ?></div>
	<option value="hits"><?php echo JText::_('MOD_AGOSMSSEARCHSORTING_POPULAR'); ?></div>
	<option value="featured"><?php echo JText::_('MOD_AGOSMSSEARCHSORTING_FEATURED'); ?></div>
	<option value="rand"><?php echo JText::_('MOD_AGOSMSSEARCHSORTING_RANDOM'); ?></div>
	<?php if(count($sortingFields)) { ?>
		<?php foreach($sortingFields as $field) { ?>
			<?php if(property_exists($item, "name")) { ?>
				<option value="field<?php echo $field->id; ?>"><?php echo JText::_($field->name); ?></option>
			<?php } ?>
		<?php } ?>
	<?php } ?>
</select>
<div class="ordering direction <?php echo (JRequest::getVar("orderto") ? JRequest::getVar("orderto") : $model->module_params->ordering_default_dir); ?>"></div>

<script>
	jQuery(document).ready(function($) {
		var query = window.location.search;
		if(query.indexOf("orderby") != -1) {
			var orderby_active = window.location.search.split("orderby=")[1];
				orderby_active = orderby_active.split("&")[0];
			$("select.ordering").val(orderby_active).trigger("liszt:updated");	
		}
 		
		$("select.ordering").on("change", function() {
			var query = window.location.search;
			if(query.indexOf("orderby") != -1) {
				var current = query.split("orderby=")[1];
				current = current.split("&")[0];
				if($(this).val() == "") {
					query = query.replace("&orderby=" + current, "");
				}
				else {
					query = query.replace("orderby=" + current, "orderby=" + $(this).val());
				}
			}
			else {
				query += "&orderby=" + $(this).val();
			}
			window.location.search = query;
		});
		
		$("div.direction").on("click", function() {
			if(query.indexOf("orderto") != -1) {
				var current = query.split("orderto=")[1];
				current = current.split("&")[0];
				if(current == "asc") {
					query = query.replace("orderto=" + current, "orderto=desc");
				}
				else {
					query = query.replace("orderto=" + current, "orderto=asc");
				}
			}
			else {
				query += "&orderto=asc";
			}
			window.location.search = query;
		});
	});
</script>