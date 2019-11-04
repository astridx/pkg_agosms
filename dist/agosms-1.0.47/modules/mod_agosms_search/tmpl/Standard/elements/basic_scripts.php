<?php 
/**
 * @package     Articles Good Search
 *
 * @copyright   Copyright (C) 2017 Joomcar extensions. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

defined('_JEXEC') or die;
 
?>
	var sliderLock = 0; // used for apply form change only after slider stop
	
	jQuery(document).ready(function($) {	
		$("#GSearch<?php echo $module->id; ?> .gsearch-field-calendar").each(function() {
			$(this).find("input:eq(0)").attr("placeholder", "<?php echo JText::_('MOD_AGS_FILTER_TYPE_DATE_FROM'); ?>");
			$(this).find("input:eq(1)").attr("placeholder", "<?php echo JText::_('MOD_AGS_FILTER_TYPE_DATE_TO'); ?>");
		});
		$("#GSearch<?php echo $module->id; ?> .gsearch-field-calendar.single input").attr("placeholder", "<?php echo JText::_('MOD_AGS_FILTER_TYPE_DATE'); ?>");
		$(".gsearch-field-calendar input").addClass("inputbox");
		
		$("#GSearch<?php echo $module->id; ?> .gsearch-field-text input[name=keyword]").on("keyup", function(event) {
			if(event.which == 13) {
				submit_form_<?php echo $module->id; ?>();
			}
		});

		$("#GSearch<?php echo $module->id; ?> form").submit(function() {
			<?php if($params->get("search_history")) { ?>	
			search_history<?php echo $module->id; ?>();
			<?php } ?>
			<?php if($params->get("search_stats")) { ?>	
			search_stats<?php echo $module->id; ?>();
			<?php } ?>
			$(".filter_loading<?php echo $module->id; ?>").show();
			$(this).find(".inputbox, input[type=hidden]").each(function() {
				if($(this).val() == '') {
					$(this).attr("name", "");
				}
			});
		});
		
		$("body").append("<div class='filter_loading<?php echo $module->id; ?>'><img src='<?php echo JURI::root(); ?>modules/mod_agosms_search/assets/images/loading.png' /></div>");
	});
	
	function submit_form_<?php echo $module->id; ?>() {
		if(sliderLock) return false;
		<?php if($params->get("search_type") == "ajax") { ?>	
		ajax_results<?php echo $module->id; ?>();
		return false;
		<?php } ?>
		jQuery("#GSearch<?php echo $module->id; ?> form").submit();
	}
	
	<?php if($params->get("autosubmit")) { ?>
	jQuery(document).ready(function($) {
		if(sliderLock) return false;
		$("#GSearch<?php echo $module->id; ?> form").on("change", function() {
			submit_form_<?php echo $module->id; ?>();
		});
		<?php if($params->get("search_type") == "ajax"
				&& JRequest::getVar("view") != "article") { //call search results on initial page loading
		?>	
		ajax_results<?php echo $module->id; ?>();
		<?php } ?>
	});
	<?php } ?>