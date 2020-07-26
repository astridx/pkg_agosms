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

$ajax_container = $params->get("ajax_container");
if($ajax_container == "div.ajax_container") {
	$ajax_container = "#ajax_container{$module->id}";
	echo '<div id="ajax_container'.$module->id.'" class="ajax_container" style="margin: 40px 0;"></div>';
}
 
?>

	<script type="text/javascript">	
		function ajax_results<?php echo $module->id; ?>() {	
			jQuery(".filter_loading<?php echo $module->id; ?>").show();
		
			if (!window.location.origin){
			  // For IE
			  window.location.origin = window.location.protocol + "//" + (window.location.port ? ':' + window.location.port : '');      
			}
			var url = window.location.origin + window.location.pathname;
			var data = jQuery("#GSearch<?php echo $module->id; ?> form").find(":input").filter(function () {
							return jQuery.trim(this.value).length > 0
						}).serialize();
			
			jQuery.ajax({
				data: data + "&search_mode=raw",
				type: "get",
				url: url,
				success: function(response) {	
					jQuery("<?php echo $ajax_container; ?>").html(response);				
					history.pushState({}, '', "" + "?" + data);				
					jQuery("html, body").animate({
						scrollTop: jQuery("<?php echo $ajax_container; ?>").offset().top - 70
					}, 500);
					jQuery(".filter_loading<?php echo $module->id; ?>").hide();
				},
				error: function() {
					jQuery(".filter_loading<?php echo $module->id; ?>").hide();
				},
			});
		}

		jQuery(document).ready(function() {
			jQuery("#GSearch<?php echo $module->id; ?> input[type=submit]").on("click", function() {
				<?php if($params->get("search_history")) { ?>	
				search_history<?php echo $module->id; ?>();
				<?php } ?>				
				<?php if($params->get("search_stats")) { ?>	
				search_stats<?php echo $module->id; ?>();
				<?php } ?>
				ajax_results<?php echo $module->id; ?>();
				return false;
			});
		
			jQuery('body').on("click", "<?php echo $ajax_container; ?> .pagination a", function() {
				jQuery(".filter_loading<?php echo $module->id; ?>").show();
				
				var url = jQuery(this).attr('href');
				var url_push = jQuery(this).attr('href').replace("&search_mode=raw", "").replace("search_mode=raw&", "");
				jQuery.ajax({
					type: "GET",
					url: url + "&search_mode=raw",
					success: function(response) {
						jQuery("<?php echo $ajax_container; ?>").html(response);	
						history.pushState({}, '', url_push);
						jQuery("html, body").animate({
							scrollTop: jQuery("<?php echo $ajax_container; ?>").offset().top - 70
						}, 500);
						jQuery(".filter_loading<?php echo $module->id; ?>").hide();						
					}
				});
				return false;
			});
			
		});
	</script>