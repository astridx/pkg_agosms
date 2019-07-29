<?php 

/**
 * @package     Articles Good Search
 *
 * @copyright   Copyright (C) 2017 Joomcar extensions. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

defined('_JEXEC') or die;

//get connected fields from parameters
$connected_fields = explode("\n", $params->get("field_connection"));

foreach($connected_fields as $k=>$connected) {
	if($connected != "") {
		$connected_fields[$k] = explode("->", $connected);
	}
	else {
		unset($connected_fields[$k]);
	}
}

//get connection instances for script
$connected = Array();
$result = '';
foreach($connected_fields as $connection) {
	$fields = Array();
	foreach($connection as $field) {
		$fields[] = '"'.trim(preg_replace('/\s\s+/iu', '', $field)).'"';
	}
	$connected[] = "[".implode(", ", $fields)."]";
}
$result = implode(", ", $connected);
?>

<script type="text/javascript">
var all_fields_labels<?php echo $module->id;?> = [];
var connected<?php echo $module->id;?> = [<?php echo $result; ?>];
jQuery(document).ready(function() {
	//populate all filter fields labels
	jQuery("#GSearch<?php echo $module->id;?> div.gsearch-cell").each(function() {
		var label = jQuery(this).find(":first-child:eq(0)").find(":first-child:eq(0)").text().trim();
		all_fields_labels<?php echo $module->id;?>.push(label);
	});
	
	//add classes for parents and childs
	jQuery(connected<?php echo $module->id;?>).each(function(connectionIndex, connection) {
		jQuery(connection).each(function(index, field) {
			var role = index == 0 ? 'parent' : 'child';
			var index_in_filter = all_fields_labels<?php echo $module->id;?>.indexOf(field);
			var selectBox = jQuery("#GSearch<?php echo $module->id;?>").find("div.gsearch-cell").eq(index_in_filter).find("select");
			var prevSelectBox = jQuery("#GSearch<?php echo $module->id;?>").find("div.gsearch-cell").eq(index_in_filter - 1).find("select");
			selectBox.addClass("connected");
			selectBox.addClass(role);
			if(role == "child" && 
				(
					(prevSelectBox.find("option:selected").val() == "" || prevSelectBox.find("option:selected").length == 0) // no any value selected in previous drop-down
					&& prevSelectBox.find("option[hidden!=hidden]").not(".empty").length // skip if no any values exists
				)
			) {
				<?php if(!$connected_show_all) { ?>
				selectBox.attr("disabled", "disabled").addClass("disabled");
				selectBox.parents(".gsearch-cell").addClass("disabled");
				selectBox.trigger("liszt:updated");
				<?php } ?>
			}
			
			if(index == (connection.length - 1)) {
				selectBox.addClass("lastchild");
				return;
			}
			
			//trigger default values
			var nextSelectIndex  = index_in_filter + 1;
			var nextSelect = jQuery("#GSearch<?php echo $module->id;?>").find("div.gsearch-cell").eq(nextSelectIndex).find("select");
			nextSelect.find("option").each(function(k) {
				if(jQuery(this).hasClass("empty")) return; //first empty value of the select boxes
				var checker = 0;
				var current_option = jQuery(this);
				selectBox.find("option:selected").each(function() {
					if(jQuery(this).hasClass("empty") //first empty value of the select boxes
						&& jQuery(this).find("option[hidden!=hidden]").not(".empty").length // show all next box values if this not contains any value
					) { 
						return;
					}
					var selectedVal = jQuery(this).val().trim().toLowerCase();
					if(current_option.val().trim().toLowerCase().indexOf(selectedVal) > -1) {
						checker = 1;
					}	
				});
				if(checker == 0) {
					jQuery(this).hide().attr("hidden", "hidden").addClass("wrap");
				}
			});
			var check_empty = 1;
			jQuery(nextSelect).find("option").each(function() {
				if(!jQuery(this).attr("hidden") && jQuery(this).val() != "") {
					check_empty = 0;
				}							
			});
			if(check_empty) {
				jQuery(nextSelect).parents(".gsearch-cell").addClass("empty");
			}
			jQuery("#GSearch<?php echo $module->id;?> option.wrap").wrap("<span style='display: none;'></span>").removeClass("wrap");
			nextSelect.trigger("liszt:updated");
		});
	});
	
	//onchange event
	jQuery("#GSearch<?php echo $module->id;?> select.connected").on("change", function() {
		if(jQuery(this).hasClass("lastchild")) return;
		jQuery(".filter_loading<?php echo $module->id; ?>").show();
		var selectedVals = jQuery(this).find("option:selected");
		var elemIndex = jQuery('#GSearch<?php echo $module->id; ?> select.connected').index(this);
		var nextAll  = jQuery(this).parents('#GSearch<?php echo $module->id; ?>').find('select.connected:gt('+elemIndex+')');
		nextAll.each(function(index, nextSelect) {
			<?php if(!$connected_show_all) { ?>
			jQuery(nextSelect).attr("disabled", "disabled").addClass("disabled");
			jQuery(nextSelect).parents(".gsearch-cell").addClass("disabled").removeClass("showed");
			<?php } ?>
			if(selectedVals.attr("class") == "empty" && jQuery(this).attr("multiple") != "multiple") {
				jQuery(nextSelect).find("option.empty").prop("selected", "selected");
				<?php if($connected_show_all) { ?>
				jQuery(this).find("option").each(function(k) {
					//show
					if(jQuery(this).parent('span').length) {
						jQuery(this).addClass("unwrap");
					}
					jQuery(this).show().removeAttr("hidden");	
				});
				jQuery("#GSearch<?php echo $module->id;?> option.unwrap").unwrap().removeClass("unwrap");
				<?php } ?>
				return;
			}
			
			//clear the values for all next selects
			jQuery(this).find("option").eq(0).prop("selected", "selected");
			jQuery(this).find("option").each(function() {
				if(jQuery(this).parent('span').length) {
					jQuery(this).unwrap();
				}
			});
			////
			
			if(index == 0) {
				jQuery(this).find("option").each(function(k) {
					if(jQuery(this).hasClass("empty")) return; //first empty value of the select boxes
					var current_option = jQuery(this);
					var checker = 0
					jQuery(selectedVals).each(function() {
						if(jQuery(this).hasClass("empty")) return; //first empty value of the select boxes
						if(current_option.val().trim().toLowerCase().indexOf(jQuery(this).val().trim().toLowerCase()) > -1) { 
							checker = 1;
						}
					});
					
					if(selectedVals.length == 0) checker = 1;
					if(checker == 0) {
						//hide
						jQuery(this).hide().attr("hidden", "hidden").addClass("wrap");
					}
					else {
						//show
						if(jQuery(this).parent('span').length) {
							jQuery(this).addClass("unwrap");
						}
						jQuery(this).show().removeAttr("hidden");
					}
				});
				var check_empty = 1;
				jQuery(nextSelect).find("option").each(function() {
					if(!jQuery(this).attr("hidden") && jQuery(this).val() != "") {
						check_empty = 0;
					}							
				});
				if(check_empty) {
					jQuery(nextSelect).removeAttr("disabled").removeClass("disabled");
					jQuery(nextSelect).parents(".gsearch-cell").removeClass("disabled").addClass("showed empty");
				}
				else {
					jQuery(nextSelect).removeAttr("disabled").removeClass("disabled");
					jQuery(nextSelect).parents(".gsearch-cell").removeClass("disabled empty").addClass("showed");
				}
			}
			//do not touch another chains
			if(jQuery(this).hasClass("lastchild")) return false;
		});
		jQuery("#GSearch<?php echo $module->id;?> option.wrap").wrap("<span style='display: none;'></span>").removeClass("wrap");
		jQuery("#GSearch<?php echo $module->id;?> option.unwrap").unwrap().removeClass("unwrap");
		setTimeout(function() {
			jQuery(".filter_loading<?php echo $module->id; ?>").hide();
		}, 700);
	});
});
</script>