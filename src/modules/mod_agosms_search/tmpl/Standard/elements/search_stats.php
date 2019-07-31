<?php 

/**
 * @package     Articles Good Search
 *
 * @copyright   Copyright (C) 2019 Joomcar extensions. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

defined('_JEXEC') or die;

?>
	<script>
		function search_stats<?php echo $module->id; ?>() {
			$ = jQuery.noConflict();
			var stats_title = '';
			$("#GSearch<?php echo $module->id; ?>").find('.gsearch-cell').each(function(k, filter) {
				var name = $(filter).find("h3").text().trim();
				switch($(filter).find(".inputbox")[0].tagName) {
					case "INPUT" :
						var value = $(filter).find(".inputbox").val().trim();
					break;
					case "SELECT" :
					case "DIV" :
						var value = '';
						$(filter).find("option:selected").each(function(k, option) {
							if($(option).hasClass("empty")) return true;
							if(value == '') {
								value = $(option).text().trim();
							}
							else {
								value += ' ' + $(option).text().trim();
							}
						});
					break;
				}
				if(value != "") {
					if(stats_title == '') {
						stats_title = name + ' - ' + value;
					}
					else {
						stats_title += ", " + name + ' - ' + value;
					}
				}
			});
			if(stats_title != '') {
				var url = window.location.origin + window.location.pathname;
				var data = jQuery("#GSearch<?php echo $module->id; ?> form").find(":input").filter(function () {
								return jQuery.trim(this.value).length > 0
							}).serialize();
				var search_link = url + '?' + data;
				var data_stats = JSON.stringify([{"title": stats_title, "link": encodeURIComponent(search_link)}]);
				jQuery.ajax({
					url: url,
					data: "data_stats=" + data_stats + "&gsearch=1&search_type=search_stats&search_mode=save",
					type: "GET",
				});
			}
		}
	</script>

