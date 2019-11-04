		<script type="text/javascript">
			var checker = 0;
			var initbox = '';
			var init_selected_count = 0;
			
			jQuery(document).ready(function($) {
				//on page load event
				$("#GSearch<?php echo $module->id; ?> select").each(function() {
					if($(this).find(":selected").val()) {
						initbox = $(this)[0];
						init_selected_count = 1;
						dynobox<?php echo $module->id; ?>(initbox);
						return false;
					}
				});
				
				//on select box change event
				$("#GSearch<?php echo $module->id; ?> form").change(function(event) {
					if(sliderLock) return false;
					var elemIndex = $('#GSearch<?php echo $module->id; ?> .inputbox').index(event.target);
					
					//added option for reset filter on previous value change
						// var nextAll  = $(event.target).parents('#GSearch<?php echo $module->id; ?>').find('select:gt('+elemIndex+')');
						// nextAll.each(function(index, nextSelect) {
							// if(!$(event.target).find(":selected").hasClass($(nextSelect).find(":selected").text())) {
								// $(nextSelect).find("option.empty").attr("selected", "selected");
							// }
						// });						
					/////////////////

					dynobox<?php echo $module->id; ?>(event.target);
					if(checker == 0
						|| elemIndex < $('#GSearch<?php echo $module->id; ?> select').index(initbox)
					) {
						initbox = event.target;
						$("#GSearch<?php echo $module->id; ?> form").find("select").each(function() {
							if(this.value.length > 0) {
								init_selected_count++;
							}
						});
						checker = 1;
					}
					else {
						var init_selected = $(event.target).find("option:selected");
						if(
							$(event.target).attr("name") == $(initbox).attr("name") 
							&& 
							(init_selected.hasClass("empty") || init_selected.length == 0)
						) {
							checker = 0;
							init_selected_count = 0;
						}
					}
				});
				
				$("#GSearch<?php echo $module->id; ?> .gsearch-cell").each(function() {
					var select = $(this).find("select");
					if(select.length > 0) {
						$(this).prepend("<div class='dynoloader' style='display: none; width: 20px; float: right; margin-top: 10px;'><img src='<?php echo JURI::root(); ?>modules/mod_agosms_search/assets/images/loading.png' /></div>");
					}
				});
			}); 
			
			function dynobox<?php echo $module->id; ?>(target) {
				$ = jQuery.noConflict();
				var form = $("#GSearch<?php echo $module->id; ?> form");
				var url = "";
				var fields = form.find("select");
				
				var parent_block = $(target).parent().parent();
				form.find('div.gsearch-cell').not(parent_block).find(".dynoloader").show();
				
				var field_type = "";
				var field_id = "";
				<?php 
					foreach($filters as $field) {
						echo "field_type += '&field_type[]={$field->type}';\r\n"; 
						echo "field_id += '&field_id[]={$field->id}';\r\n"; 
					}
				?>
				
				var query = $("#GSearch<?php echo $module->id; ?> form").find(":input").filter(function () {
							return $.trim(this.value).length > 0
						}).serialize();
				
				var selected_count_current = 0;
				$("#GSearch<?php echo $module->id; ?> form").find("select").each(function() {
					if(this.value.length > 0) {
						selected_count_current++;
					}
				});
				
				var data = query + "&search_mode=dynobox" + field_type + field_id;
				$.ajax({
					dataType: "json",
					data: data,
					type: "GET",
					url: url,
					success: function(res) {
						if(res.length > 0) {
							$(res).each(function(k, field) {
								//touch only next fields
									// if(k <= $('#GSearch<?php echo $module->id; ?> select').index(target)
										// && selected_count_current != 1
									// ) {
										// return true;
									// }
								
								var valuesAvailable = field.values;
								
								var filter = form.find("select[name="+field.name+"]");
								if(filter.length == 0) {
									filter = form.find("select[name="+field.name+"\\[\\]]");
								}
								
								if(typeof filter.attr("name") === 'undefined') {
									return;
								}
								
								//do not touch initial box 
								if(filter.attr("name") == $(initbox).attr("name")
									&& $(initbox).find(":selected").val()
								) {
									return;
								}
								
								filter.find("option").not(".empty").each(function(k, option) {								
									checkAvailability(target, filter, option, valuesAvailable, selected_count_current, init_selected_count);									
								});
								filter.selectpicker('refresh');
							});
						}						
						else {
							//no any values found
							form.find("select").each(function(ev, el) {
								$(el).find("option").not(".empty").each(function(k, option) {								
									checkAvailability(target, el, option, [], selected_count_current, init_selected_count);									
								});
								$(el).selectpicker('refresh');
							});
						}
						form.find(".dynoloader").hide();
					}
				});
			}
			
			function checkAvailability(target, filter, option, valuesAvailable, selected_count_current, init_selected_count) {
				var valueToCheckText = $(option).text().trim(); //check by option text
				var valueToCheckVal = $(option).val().trim(); //check by option value
				if($(filter).attr('name') == 'category[]') {
					var valueToCheck = $(option).val(); //check by id
				}
				if(!$(initbox).attr("name")) return; //fix for slider
				if(
					$.inArray(valueToCheckText, valuesAvailable) > -1 
					||
					$.inArray(valueToCheckVal, valuesAvailable) > -1 
					|| 
					(
						((target.value.length == 0 && selected_count_current == init_selected_count)
						||
						$(target).attr("name") == $(initbox).attr("name")
						)
						&& 
						$(initbox).attr("name").indexOf($(filter).attr('name')) > -1
					)
				) {
					$(option).show();
					//added for link the value
					if($(target).find(":selected").val()) {
						$(option).addClass($(target).find(":selected").text());
					}
				}
				else {
					if($(target).attr("name") != $(filter).attr("name")) {
						$(option).hide();
					}
				}				
			}	
		</script>