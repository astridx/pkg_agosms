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
				if(selectBox.parent().hasClass("bootstrap-select")) {
					selectBox.selectpicker('refresh');
				}
				<?php } ?>
			}
			
			if(index == (connection.length - 1)) {
				selectBox.addClass("lastchild");
				return;
			}
			
			//trigger default values
			var nextSelectIndex  = index_in_filter + 1;
			var nextSelect = jQuery("#GSearch<?php echo $module->id;?>").find("div.gsearch-cell").eq(nextSelectIndex).find("select");
			// implode selected vals in a single string
			var selectedValsText = [];
			selectBox.find("option:selected").each(function() {
				selectedValsText.push(jQuery(this).val().trim().toLowerCase());
			});
			selectedValsText = selectedValsText.join("|");
			nextSelect.find("option").each(function(k) {
				if(jQuery(this).hasClass("empty") //first empty value of the select boxes
					&& jQuery(this).find("option[hidden!=hidden]").not(".empty").length // show all next box values if this not contains any value
				) { 
					return;
				}
				var current_option = jQuery(this);
				if(current_option.val().trim().toLowerCase().indexOf(selectedValsText) <= -1) {
					jQuery(this).hide().attr("hidden", "hidden").addClass("wrap");
				}
			});
			
			// check if next select is empty
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
		});
		setTimeout(function() {
			jQuery("#GSearch<?php echo $module->id;?> select").each(function(k, select) {
				if(jQuery(select).parent().hasClass("bootstrap-select")) {
					jQuery(select).selectpicker('refresh');
				}
			});
		}, 500);
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
			
			// implode selected vals in a single string
			var selectedValsText = [];
			jQuery(selectedVals).each(function() {
				selectedValsText.push(jQuery(this).val().trim().toLowerCase());
			});
			selectedValsText = selectedValsText.join("|");

			// check if next select is empty
			var check_empty = 1;			
	
			if(index == 0) { // use only next select
				jQuery(nextSelect).find("option").each(function(k) {
					var current_option = jQuery(this);
					if(current_option.hasClass("empty")) return; //first empty value of the select boxes
					
					// check if value contains a match
					if(current_option.val().trim().toLowerCase().indexOf(selectedValsText) > -1
						|| selectedVals.length == 0
					) { 
						//show
						if(current_option.parent('span').length) {
							current_option.addClass("unwrap");
						}
						current_option.show().removeAttr("hidden");
					}
					else {
						//hide
						current_option.hide().attr("hidden", "hidden").addClass("wrap");
					}
					
					// check if next select is empty
					if(!current_option.attr("hidden") && jQuery(this).val() != "") {
						check_empty = 0;
					}
				});
				
				if(check_empty) {
					jQuery(nextSelect).removeAttr("disabled").removeClass("disabled");
					jQuery(nextSelect).parents(".gsearch-cell").removeClass("disabled").addClass("showed empty"); // add extra 'empty' class for empty selects
				}
				else {
					jQuery(nextSelect).removeAttr("disabled").removeClass("disabled");
					jQuery(nextSelect).parents(".gsearch-cell").removeClass("disabled empty").addClass("showed");
				}
			}
			
			jQuery(nextSelect).find("option.wrap").wrap("<span style='display: none;'></span>").removeClass("wrap");
			jQuery(nextSelect).find("option.unwrap").unwrap().removeClass("unwrap");
			
			//do not touch another chains
			if(jQuery(nextSelect).hasClass("lastchild")) return false;
		});
		setTimeout(function() {
			jQuery(".filter_loading<?php echo $module->id; ?>").hide();
			jQuery("#GSearch<?php echo $module->id;?> select").each(function(k, select) {
				if(jQuery(select).parent().hasClass("bootstrap-select")) {
					jQuery(select).selectpicker('refresh');
				}
			});
		}, 500);
	});
});
</script>