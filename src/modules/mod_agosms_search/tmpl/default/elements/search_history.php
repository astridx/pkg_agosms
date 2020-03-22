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

$history = json_decode($_COOKIE['search_history'.$module->id]);

if($history) {
	echo "<div id='search-history{$module->id}' class='search_history'>
			<ul>
	";
	foreach($history as $h) {
		echo "<li><a href='{$h->link}'>{$h->title}</a></li>";
	}
	echo "
			</ul>
			<a href='#' id='search-history-clear{$module->id}' class='search-history-clear'>".JText::_('MOD_AGOSMSSEARCHBUTTON_HISTORY_CLEAR')."</a>
		</div>";
}
$doc = JFactory::getDocument();
$document->addScript(JURI::root(true) . '/media/mod_agosms_search/history/js.cookie.min.js');
?>
<script>
	function search_history<?php echo $module->id; ?>() {
		$ = jQuery.noConflict();
		var history_active = Cookies.get('search_history<?php echo $module->id ?>');
		if(history_active) {
			var history_arr = JSON.parse(history_active);
		}
		else {
			var history_arr = [];
		}
		var history_title = '';
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
				if(history_title == '') {
					history_title = name + ' - ' + value;
				}
				else {
					history_title += ", " + name + ' - ' + value;
				}
			}
		});
		if(history_title != '') {
			var url = window.location.origin + window.location.pathname;
			var data = jQuery("#GSearch<?php echo $module->id; ?> form").find(":input").filter(function () {
							return jQuery.trim(this.value).length > 0
						}).serialize();
			var history_link = url + '?' + data;
			var checker = 1;
			if(history_arr.length) {
				jQuery.each(history_arr, function(k, el) {
					if(history_link == el.link) { //check for duplicated link
						checker = 0;
					}
				});
			}
			if(checker) {
				history_arr.unshift({'link': history_link, 'title': history_title});
			}
			history_arr = history_arr.slice(0, 5); //limit for 5 saved elements
			Cookies.set('search_history<?php echo $module->id ?>', JSON.stringify(history_arr));
		}
	}

	(function($) {
		jQuery("#search-history-clear<?php echo$module->id; ?>").on("click", function() {
			Cookies.remove('search_history<?php echo $module->id ?>', { path: '/' });
			$("div#search-history<?php echo $module->id; ?>").remove();
			return false;
		});
	}(jQuery))
</script>
