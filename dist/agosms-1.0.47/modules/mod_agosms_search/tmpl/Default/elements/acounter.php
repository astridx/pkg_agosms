<?php 

/**
 * @package     Articles Good Search
 *
 * @copyright   Copyright (C) 2017 Joomcar extensions. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

defined('_JEXEC') or die;

?>

		<script type="text/javascript">	
			function acounter<?php echo $module->id; ?>() {
				$ = jQuery.noConflict();
				$("#GSearch<?php echo $module->id; ?> div.acounter .data").hide();
				$("#GSearch<?php echo $module->id; ?> div.acounter .loader").show();
				
				if (!window.location.origin){
				  // For IE
				  window.location.origin = window.location.protocol + "//" + (window.location.port ? ':' + window.location.port : '');      
				}
				var url = window.location.origin + window.location.pathname;
				var data = $("#GSearch<?php echo $module->id; ?> form").find(":input").filter(function () {
								return $.trim(this.value).length > 0
							}).serialize();	
				$.ajax({
					data: data + "&search_mode=count",
					type: "get",
					url: url,
					success: function(response) {
						$("#GSearch<?php echo $module->id; ?> div.acounter .loader").hide();
						$("#GSearch<?php echo $module->id; ?> div.acounter .data").html("<p>"+response+" <?php echo JText::_("MOD_AGS_ACOUNTER_TEXT"); ?></p>").show();
					},
					error: function() {
						$("#GSearch<?php echo $module->id; ?> div.acounter .loader").hide();
					}
				});
			}
			$ = jQuery.noConflict();
			$(document).ready(function() {
				$("#GSearch<?php echo $module->id; ?> form").change(function(event) {
					if(sliderLock) return false;
					setTimeout(function() {
						acounter<?php echo $module->id; ?>();
					}, 200);
				});
			});
		</script>
		
		<div class="acounter">
			<div class="data"></div>
			<div class="loader" style="display: none;"><img src='<?php echo JURI::root(); ?>modules/mod_agosms_search/assets/images/loading.png' style='width: 30px;' /></div>
		</div>