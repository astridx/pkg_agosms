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

$moduleclass_sfx = $params->get('moduleclass_sfx', '');

?>
		<script type="text/javascript">
			function clearSearch_<?php echo $module->id; ?>() {
				$ = jQuery.noConflict();
				$("#GSearch<?php echo $module->id; ?>").find(".inputbox").each(function(k, field) {
					switch($(this)[0].tagName) {
						case "INPUT" :
							if($(this).attr("type") == "text" || $(this).hasClass("inputbox")) {
								$(this).val("");
								$(this).attr("data-value", "");
								$(this).attr("data-alt-value", "");
							}
							$(this).removeAttr('checked');
						break;
						case "SELECT" :
							$(this).find("option").each(function() {
								$(this).removeAttr("selected");
							});
							$(this).selectpicker('refresh');
						break;
					}
				});
				
				//sliders reset
				if($("#GSearch<?php echo $module->id; ?>").find(".SliderField").length) {
					$("#GSearch<?php echo $module->id; ?>").find(".SliderField").each(function() {
						var slider_obj = $(this);
						var min = slider_obj.parent().find(".slider-handle").attr("aria-valuemin");
						var max = slider_obj.parent().find(".slider-handle").attr("aria-valuemax");
						slider_obj.parents('.slider-wrapper').find(".amount input").val(min + ' - ' + max);
						slider_obj.parents('.slider-wrapper').find(".amount input").trigger("input");
					});
				}
				
				
				//acounter clean
				$("#GSearch<?php echo $module->id; ?> div.acounter .data").hide();
				
				//submit_form_<?php //echo $module->id; ?>();
			}
		</script>	

		<input type="button" value="<?php echo JText::_('MOD_AGOSMSSEARCHBUTTON_CLEAR'); ?>" class="btn btn-warning button reset <?php echo $moduleclass_sfx; ?>" onClick="clearSearch_<?php echo $module->id; ?>()" />