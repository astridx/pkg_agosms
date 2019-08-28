<?php 
/**
 * @package     Joomla.Site
 * @subpackage  pkg_agosms
 *
 * @copyright   Copyright (C) 2005 - 2019 Astrid Günther, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later;
 * @link        astrid-guenther.de
 */

// no direct access
defined('_JEXEC') or die;

require_once(JPATH_SITE . "/plugins/system/agosmssearch/models/com_content/model.php");

JHtml::_('bootstrap.framework');
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root(true) . '/media/jui/css/icomoon.css');

$date_format = explode("::", $params->get("date_format", "%e %b %Y::d M yyyy"))[0];
$date_format_text = explode("::", $params->get("date_format"))[1];
$lang = JFactory::getLanguage()->getTag();
$lang = explode("-", $lang)[0];
$curr_locale = JFactory::getLanguage()->getLocale();
setlocale(LC_ALL, $curr_locale);
$moduleclass_sfx = $params->get('moduleclass_sfx', '');

?>

<script type="text/javascript">

	var sliderLock = 0; // used for apply form change only after slider stop

	jQuery(document).ready(function($) {
		$("#GSearch<?php echo $module->id; ?> .gsearch-field-calendar").each(function() {
			$(this).find("input:eq(0)").attr("placeholder", "<?php echo JText::_('MOD_AGOSMSSEARCHFILTER_TYPE_DATE_FROM'); ?>");
			$(this).find("input:eq(1)").attr("placeholder", "<?php echo JText::_('MOD_AGOSMSSEARCHFILTER_TYPE_DATE_TO'); ?>");
		});
		$("#GSearch<?php echo $module->id; ?> .gsearch-field-calendar.single input").attr("placeholder", "<?php echo JText::_('MOD_AGOSMSSEARCHFILTER_TYPE_DATE'); ?>");
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
	});
	
	function submit_form_<?php echo $module->id; ?>() {
		if(sliderLock) return false;
		<?php if($params->get("search_type") == "ajax") { ?>	
		ajax_results<?php echo $module->id; ?>();
		return false;
		<?php } ?>
		jQuery("#GSearch<?php echo $module->id; ?> form").submit(); 
	}


</script>

<?php 
// See https://developer.snapappointments.com/bootstrap-select/ and https://github.com/snapappointments/bootstrap-select
$doc = JFactory::getDocument();
$document->addStyleSheet(JURI::root(true) . '/media/mod_agosms_search/select/bootstrap-select.min.css');
$document->addScript(JURI::root(true) . '/media/mod_agosms_search/select/bootstrap-select.min.js');
?>

<script type="text/javascript">
	jQuery(document).ready(function($) {
		$("#GSearch<?php echo $module->id; ?> select").each(function() {
			if($(this).attr("multiple") == "multiple") {
				$(this).find("option:eq(0)").attr("disabled", "disabled");
			}
			$(this).selectpicker({
				//actionsBox: true,
				//selectAllText: "Select All",
				//deselectAllText: "Clear",
				title: $(this).find("option:eq(0)").text(),
				liveSearch: "true",
				iconBase: "icon",
				tickIcon: "icon-checkmark",
			});
		});
		$("#GSearch<?php echo $module->id; ?>").find(".datepicker").each(function(k, el) {
			$(el).datepicker({
				autoclose: true,
				format: '<?php echo $date_format_text; ?>',
				<?php if($lang && $lang != 'en') { ?>
				language: "<?php echo $lang; ?>",
				<?php } ?>
			}).on('changeDate', function(e) {
				prepareDates<?php echo $module->id; ?>(el);
			});
			setTimeout(function() {
				$(el).datepicker('show');
				$(el).datepicker('hide'); // fix single number empty space issue;
			}, 200);
		});
	});
	function prepareDates<?php echo $module->id; ?>(el) {
		$ = jQuery.noConflict();
		el = $(el);
		var date = el.datepicker('getDate');
		var date_val = '';
		if(date) {
			date_val = date.getFullYear() + '-' + ('0' + (date.getMonth()+1)).slice(-2) + '-' + ('0' + date.getDate()).slice(-2);
		}
		if(el.hasClass("single")) {
			var el_val = el.parent().find('input[type=hidden]');
		}
		if(el.hasClass("from")) {
			var el_val = el.parent().find('input[type=hidden].from');
			if(el.datepicker('getDate') > el.parent().find(".datepicker.to").datepicker('getDate')) {
				el.parent().find(".datepicker.to").datepicker('setStartDate', el.datepicker('getDate')); // adjust date-to select
			}
		}
		if(el.hasClass("to")) {
			var el_val = el.parent().find('input[type=hidden].to');
		}
		el_val.val(date_val);
	}
</script>
<?php 
// See https://github.com/uxsolutions/bootstrap-datepicker
$document->addStyleSheet(JURI::root(true) . '/media/mod_agosms_search/datepicker/css/bootstrap-datepicker.min.css');
$document->addScript(JURI::root(true) . '/media/mod_agosms_search/datepicker/js/bootstrap-datepicker.min.js');
?>
<?php if($lang && $lang != 'en') { 
		$document->addScript(JURI::root(true) . '/media/mod_agosms_search/datepicker/locales/bootstrap-datepicker' . $lang . '.min.js');
		} 
?>

<div id="GSearch<?php echo $module->id; ?>" class="GSearchBlock <?php echo $params->get('moduleclass_sfx'); ?>">

	<?php if($params->get('descr') != "") : ?>
	<p><?php echo $params->get('descr'); ?></p>
	<?php endif; ?>
	
	<form action="#gsearch-results" name="GSearch<?php echo $module->id; ?>" method="get">		
	  <div class="gsearch-table">

<?php for($filters_counter = 0; $filters_counter < count($filters); $filters_counter++) { 
		$field = $filters[$filters_counter];
?>
		<div class="gsearch-cell gsearch-cell<?php echo $filters_counter; ?>">
		
		<?php	
			switch($field->type) {				
				case 'keyword' :			
					require(JModuleHelper::getLayoutPath('mod_agosms_search', $params->get('module_template', 'Default') . '/fields/basic/keyword'));
				break;	
				
				case 'title_select' :			
					require(JModuleHelper::getLayoutPath('mod_agosms_search', $params->get('module_template', 'Default') . '/fields/basic/title_select'));
				break;		
				
				case 'tag' :
					$tags = (array)$helper->getTags($params);
					require(JModuleHelper::getLayoutPath('mod_agosms_search', $params->get('module_template', 'Default') . '/fields/basic/tag'));
				break;
				
				case 'category' :
					$categories = (array)$helper->getCategories(null, $params);
					require(JModuleHelper::getLayoutPath('mod_agosms_search', $params->get('module_template', 'Default') . '/fields/basic/category'));
				break;			
				
				case 'author' :	
					$authors = (array)$helper->getAuthors($params);
					require(JModuleHelper::getLayoutPath('mod_agosms_search', $params->get('module_template', 'Default') . '/fields/basic/author'));
				break;
				
				case 'date' :
					require(JModuleHelper::getLayoutPath('mod_agosms_search', $params->get('module_template', 'Default') . '/fields/basic/date'));
				break;
				
				//custom fields
				case 'text' :
				case 'textarea' :
				case 'url' :
				case 'editor' :
					require(JModuleHelper::getLayoutPath('mod_agosms_search', $params->get('module_template', 'Default') . '/fields/custom_fields/text'));
				break;
				
				case 'text_range' :
					require(JModuleHelper::getLayoutPath('mod_agosms_search', $params->get('module_template', 'Default') . '/fields/custom_fields/text_range'));
				break;
				
				case 'select' :
					require(JModuleHelper::getLayoutPath('mod_agosms_search', $params->get('module_template', 'Default') . '/fields/custom_fields/select'));
				break;
				
				case 'list' :
				case 'multi' :
					require(JModuleHelper::getLayoutPath('mod_agosms_search', $params->get('module_template', 'Default') . '/fields/custom_fields/multi'));
				break;
				
				case 'checkboxes' :
					require(JModuleHelper::getLayoutPath('mod_agosms_search', $params->get('module_template', 'Default') . '/fields/custom_fields/checkboxes'));
				break;
				
				case 'radio' :
					require(JModuleHelper::getLayoutPath('mod_agosms_search', $params->get('module_template', 'Default') . '/fields/custom_fields/radio'));
				break;
				
				case 'agosmsaddressmarker' :
					require(JModuleHelper::getLayoutPath('mod_agosms_search', $params->get('module_template', 'Default') . '/fields/custom_fields/agosmsaddressmarkerslider'));
				break;

				case 'integer' :
				case 'slider-range' :
					require(JModuleHelper::getLayoutPath('mod_agosms_search', $params->get('module_template', 'Default') . '/fields/custom_fields/slider'));
				break;
				
				case 'calendar' :
					require(JModuleHelper::getLayoutPath('mod_agosms_search', $params->get('module_template', 'Default') . '/fields/custom_fields/calendar'));
				break;
				
				case 'calendar_range' :
					require(JModuleHelper::getLayoutPath('mod_agosms_search', $params->get('module_template', 'Default') . '/fields/custom_fields/calendar_range'));
				break;
				
				case 'custom_select' :
					require(JModuleHelper::getLayoutPath('mod_agosms_search', $params->get('module_template', 'Default') . '/fields/custom_fields/custom_select'));
				break;
				
				//added for compatibility with radical multifield
				case 'radicalmultifield' : 
						$extra_params = json_decode($field->extra_params);
						$sub_field = $extra_params->selected;
						if(!$sub_field) {
							echo "Select radicalmultifield in the module parameters";
							continue;
						}
						$field_type = $extra_params->type ? $extra_params->type : "text";
						require(JModuleHelper::getLayoutPath('mod_agosms_search', $params->get('module_template', 'Default') . '/fields/custom_fields/radicalmultifield/'.$field_type));
				break;
				
				//added for compatibility with repeatable field
				case 'repeatable' : 
						$extra_params = json_decode($field->extra_params);
						$sub_field = $extra_params->selected;
						if(!$sub_field) {
							echo "Select repeatable field in the module parameters";
							continue;
						}
						$field_type = $extra_params->type ? $extra_params->type : "text";
						require(JModuleHelper::getLayoutPath('mod_agosms_search', $params->get('module_template', 'Default') . '/fields/custom_fields/repeatable/'.$field_type));
				break;
				
			}
		?>
		</div>
<?php
}
?>

			<div class="gsearch-buttons">
				<input type="submit" value="<?php echo JText::_('MOD_AGOSMSSEARCHBUTTON_SEARCH_TEXT'); ?>" class="btn btn-primary button submit <?php echo $moduleclass_sfx; ?>" />	
				<?php
				if($params->get("clear_btn_show", 1)) {
					require(JModuleHelper::getLayoutPath('mod_agosms_search', $params->get('module_template', 'Default') . '/elements/clear_btn')); 
				}
				?>
			</div>
		
		   <div class="clear" style="clear: both;"></div>
		</div><!--//gsearch-table-->
	
		<input type="hidden" name="gsearch" value="1" />
		<input type="hidden" name="moduleId" value="<?php echo $module->id; ?>" />
		<?php if($params->get("Itemid")) { ?>
		<input type="hidden" name="Itemid" value="<?php echo $params->get("Itemid"); ?>" />
		<?php } ?>		
	</form>
	
	<?php if($params->get("acounter")) { ?>
		<?php require(JModuleHelper::getLayoutPath('mod_agosms_search', $params->get('module_template', 'Default') . '/elements/acounter')); ?>
	<?php } ?>
	
	<?php if($params->get("field_connection") != "" && 
			 $params->get("field_connection") != "FieldLabel->FieldLabel2->FieldLabel3") { ?>
		<?php require(JModuleHelper::getLayoutPath('mod_agosms_search', $params->get('module_template', 'Default') . '/elements/connected_single')); ?>
	<?php } ?>

	<?php if($params->get("search_history")) { ?>
		<?php require(JModuleHelper::getLayoutPath('mod_agosms_search', $params->get('module_template', 'Default') . '/elements/search_history')); ?>
	<?php } ?>
	
	<?php if($params->get("search_stats")) { ?>
		<?php require(JModuleHelper::getLayoutPath('mod_agosms_search', $params->get('module_template', 'Default') . '/elements/search_stats')); ?>
	<?php } ?>
	
	<?php if($params->get("search_type") == "ajax") { ?>
		<?php require(JModuleHelper::getLayoutPath('mod_agosms_search', $params->get('module_template', 'Default') . '/elements/ajax_search')); ?>
	<?php } ?>
</div><!--//gsearch-box-->