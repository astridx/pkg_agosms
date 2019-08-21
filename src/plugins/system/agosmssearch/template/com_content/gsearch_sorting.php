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
	if(array_key_exists('id', $aField))
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
	<option value=""><?php echo JText::_('MOD_AGOSMSSEARCHSORTING_SELECT'); ?></option>
	<option value="title"><?php echo JText::_('MOD_AGOSMSSEARCHSORTING_TITLE'); ?></option>
	<option value="alias"><?php echo JText::_('MOD_AGOSMSSEARCHSORTING_ALIAS'); ?></option>
	<option value="created"><?php echo JText::_('MOD_AGOSMSSEARCHSORTING_DATE'); ?></option>
	<option value="publish_up"><?php echo JText::_('MOD_AGOSMSSEARCHSORTING_DATE_PUBLISHING'); ?></option>
	<option value="category"><?php echo JText::_('MOD_AGOSMSSEARCHSORTING_CATEGORY'); ?></option>
	<option value="hits"><?php echo JText::_('MOD_AGOSMSSEARCHSORTING_POPULAR'); ?></option>
	<option value="featured"><?php echo JText::_('MOD_AGOSMSSEARCHSORTING_FEATURED'); ?></option>
	<option value="rand"><?php echo JText::_('MOD_AGOSMSSEARCHSORTING_RANDOM'); ?></option>
	<option value="distance"><?php echo JText::_('MOD_AGOSMSSEARCHSORTING_DISTANCE'); ?></option>
	<?php if(count($sortingFields)) { ?>
		<?php foreach($sortingFields as $field) { ?>
			<?php if(property_exists($item, "name")) { ?>
				<option value="field<?php echo $field->id; ?>"><?php echo JText::_($field->name); ?></option>
			<?php } ?>
		<?php } ?>
	<?php } ?>
</select>
<div class="ordering direction 
<?php 
$orderto =  false;
if (JFactory::getApplication()->input->post->get('orderto')) {
	$orderto = JFactory::getApplication()->input->post->get('orderto');
}
echo ($orderto ? $orderto : $model->module_params->ordering_default_dir); ?>"></div>

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