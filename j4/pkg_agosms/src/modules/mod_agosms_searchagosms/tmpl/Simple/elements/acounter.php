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
?>

<script type="text/javascript">
function acounter<?php echo $module->id; ?>() {
	$ = jQuery.noConflict();
	$("#GSearch<?php echo $module->id; ?> div.acounter .data").hide();
	$("#GSearch<?php echo $module->id; ?> div.acounter .loader").show();

	var url = window.location.origin + window.location.pathname;
	var data = $("#GSearch<?php echo $module->id; ?> form").find(":input").filter(function() {
		return $.trim(this.value).length > 0
	}).serialize();
	$.ajax({
		data: data + "&search_mode=count",
		type: "get",
		url: url,
		success: function(response) {
			$("#GSearch<?php echo $module->id; ?> div.acounter .loader").hide();
			$("#GSearch<?php echo $module->id; ?> div.acounter .data").html("<p>" + response +
				" <?php echo JText::_("MOD_AGOSMSSEARCHAGOSMSACOUNTER_TEXT"); ?></p>").show();
		},
		error: function() {
			$("#GSearch<?php echo $module->id; ?> div.acounter .loader").hide();
		}
	});
}
</script>

<div class="acounter">
	<div class="data"></div>
	<div class="loader" style="display: none;">loading ...</div>
</div>