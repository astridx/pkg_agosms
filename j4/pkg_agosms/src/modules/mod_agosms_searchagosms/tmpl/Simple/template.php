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

require_once(JPATH_SITE . "/plugins/system/agosmssearchagosms/models/com_agosms/model.php");

$document = JFactory::getDocument();

$document->addStyleSheet('https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css');
$document->addScript('https://code.jquery.com/jquery-3.3.1.slim.min.js');
$document->addScript('https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js');
$document->addScript('https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js');
$document->addScript(JURI::root(true) . '/media/mod_agosms_searchagosms/js/bootstrap-select.min.js');
$document->addStyleSheet(JURI::root(true) . '/media/mod_agosms_searchagosms/css/bootstrap-select.min.css');

$wa = $document->getWebAssetManager();

$date_format = explode("::", $params->get("date_format", "%e %b %Y::d M yyyy"))[0];
$date_format_text = explode("::", $params->get("date_format"))[1];
$lang = JFactory::getLanguage()->getTag();
$lang = explode("-", $lang)[0];
$curr_locale = JFactory::getLanguage()->getLocale();
setlocale(LC_ALL, $curr_locale);

?>

<style>
.bootstrap-select .dropdown-toggle .filter-option {
	color: #ffffff;
}

.gsearch-cell {
	display: flex;
}

.gsearch-table {
	display: flex;
	flex-flow: row wrap;
	justify-content: space-around;
}

.gsearch-buttons {
	display: block;
	width: 100%;
	text-align: center;
	padding: 2%;
}

.gsearch-button {
	padding: 1% 2%;
}
</style>





<script type="text/javascript">
var sliderLock = 0; // used for apply form change only after slider stop

jQuery(document).ready(function($) {
	$("#GSearch<?php echo $module->id; ?> .gsearch-field-calendar").each(function() {
		$(this).find("input:eq(0)").attr("placeholder",
			"<?php echo JText::_('MOD_AGOSMSSEARCHAGOSMSFILTER_TYPE_DATE_FROM'); ?>");
		$(this).find("input:eq(1)").attr("placeholder",
			"<?php echo JText::_('MOD_AGOSMSSEARCHAGOSMSFILTER_TYPE_DATE_TO'); ?>");
	});
	$("#GSearch<?php echo $module->id; ?> .gsearch-field-calendar.single input").attr("placeholder",
		"<?php echo JText::_('MOD_AGOSMSSEARCHAGOSMSFILTER_TYPE_DATE'); ?>");
	$(".gsearch-field-calendar input").addClass("inputbox");

	$("#GSearch<?php echo $module->id; ?> .gsearch-field-text input[name=keyword]").on("keyup", function(
		event) {
		if (event.which == 13) {
			submit_form_<?php echo $module->id; ?>();
		}
	});

	$("#GSearch<?php echo $module->id; ?> form").submit(function() {
		<?php if ($params->get("search_history")) { ?>
		search_history<?php echo $module->id; ?>();
		<?php } ?>
		<?php if ($params->get("search_stats")) { ?>
		search_stats<?php echo $module->id; ?>();
		<?php } ?>
		$(".filter_loading<?php echo $module->id; ?>").show();
		$(this).find(".inputbox, input[type=hidden]").each(function() {
			if ($(this).val() == '') {
				$(this).attr("name", "");
			}
		});
	});
});

function submit_form_<?php echo $module->id; ?>() {
	if (sliderLock) return false;
	<?php if ($params->get("search_type") == "ajax") { ?>
	ajax_results<?php echo $module->id; ?>();
	return false;
	<?php } ?>
	jQuery("#GSearch<?php echo $module->id; ?> form").submit();
}
</script>



<script type="text/javascript">
jQuery(document).ready(function($) {
	$("#GSearch<?php echo $module->id; ?> select").each(function() {
		if ($(this).attr("multiple") == "multiple") {
			$(this).find("option:eq(0)").attr("disabled", "disabled");
		}
		$(this).selectpicker({
			actionsBox: true,
			selectAllText: "Select All",
			deselectAllText: "Clear",
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
			<?php if ($lang && $lang != 'en') { ?>
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
	if (date) {
		date_val = date.getFullYear() + '-' + ('0' + (date.getMonth() + 1)).slice(-2) + '-' + ('0' + date.getDate())
			.slice(-2);
	}
	if (el.hasClass("single")) {
		var el_val = el.parent().find('input[type=hidden]');
	}
	if (el.hasClass("from")) {
		var el_val = el.parent().find('input[type=hidden].from');
		if (el.datepicker('getDate') > el.parent().find(".datepicker.to").datepicker('getDate')) {
			el.parent().find(".datepicker.to").datepicker('setStartDate', el.datepicker(
				'getDate')); // adjust date-to select
		}
	}
	if (el.hasClass("to")) {
		var el_val = el.parent().find('input[type=hidden].to');
	}
	el_val.val(date_val);
}
</script>
<?php
// See https://github.com/uxsolutions/bootstrap-datepicker
$document->addStyleSheet(JURI::root(true) . '/media/mod_agosms_searchagosms/datepicker/css/bootstrap-datepicker.min.css');
$document->addScript(JURI::root(true) . '/media/mod_agosms_searchagosms/datepicker/js/bootstrap-datepicker.min.js');
?>
<?php if ($lang && $lang != 'en') {
		$document->addScript(JURI::root(true) . '/media/mod_agosms_searchagosms/datepicker/locales/bootstrap-datepicker' . $lang . '.min.js');
}
?>

<div id="GSearch<?php echo $module->id; ?>" class="GSearchBlock">

	<h2>
		<?php
			echo JText::_("MOD_AGOSMSSEARCHAGOSMSRESULT_SEARCHFORM_DEFAULT");
		?>
	</h2>


	<?php if ($params->get('descr') != "") : ?>
	<p><?php echo $params->get('descr'); ?></p>
	<?php endif; ?>

	<form action="#gsearch-results" name="GSearch<?php echo $module->id; ?>" method="get">
		<div class="gsearch-table">

			<?php for ($filters_counter = 0; $filters_counter < count($filters); $filters_counter++) {
				$field = $filters[$filters_counter];
				?>
			<div class="gsearch-cell gsearch-cell<?php echo $filters_counter; ?>">

				<?php
				switch ($field->type) {
					case 'keyword':
						require(JModuleHelper::getLayoutPath('mod_agosms_searchagosms', $params->get('module_template', 'SaftyRoadEvent') . '/fields/basic/keyword'));
						break;
								
					case 'cusotm1_select':
						require(JModuleHelper::getLayoutPath('mod_agosms_searchagosms', $params->get('module_template', 'SaftyRoadEvent') . '/fields/basic/cusotm1_select'));
						break;

					case 'cusotm2_select':
						require(JModuleHelper::getLayoutPath('mod_agosms_searchagosms', $params->get('module_template', 'SaftyRoadEvent') . '/fields/basic/cusotm2_select'));
						break;
				}
				?>
			</div>
				<?php
			}
			?>

			<div class="gsearch-buttons">
				<input onclick="document.getElementById('GSearch<?php echo $module->id; ?>').style.display = 'none';"
					type="submit" value="<?php echo JText::_('MOD_AGOSMSSEARCHAGOSMSBUTTON_SEARCH_TEXT'); ?>"
					class="btn btn-primary button submit gsearch-button " />
			</div>

		</div>
		<!--//gsearch-table-->

		<input type="hidden" name="gsearch" value="1" />
		<input type="hidden" name="moduleId" value="<?php echo $module->id; ?>" />
		<?php if ($params->get("Itemid")) { ?>
		<input type="hidden" name="Itemid" value="<?php echo $params->get("Itemid"); ?>" />
		<?php } ?>
	</form>

	<?php
	if ($params->get("acounter")) {
		require(JModuleHelper::getLayoutPath('mod_agosms_searchagosms', $params->get('module_template', 'SaftyRoadEvent') . '/elements/acounter'));
	}
	?>

	<?php
	if ($params->get("field_connection") != "" &&
			 $params->get("field_connection") != "FieldLabel->FieldLabel2->FieldLabel3") {
		require(JModuleHelper::getLayoutPath('mod_agosms_searchagosms', $params->get('module_template', 'SaftyRoadEvent') . '/elements/connected_single'));
	}
	?>

	<?php if ($params->get("search_history")) { ?>
		<?php require(JModuleHelper::getLayoutPath('mod_agosms_searchagosms', $params->get('module_template', 'SaftyRoadEvent') . '/elements/search_history')); ?>
	<?php } ?>

	<?php if ($params->get("search_stats")) { ?>
		<?php require(JModuleHelper::getLayoutPath('mod_agosms_searchagosms', $params->get('module_template', 'SaftyRoadEvent') . '/elements/search_stats')); ?>
	<?php } ?>

	<?php if ($params->get("search_type") == "ajax") { ?>
		<?php require(JModuleHelper::getLayoutPath('mod_agosms_searchagosms', $params->get('module_template', 'SaftyRoadEvent') . '/elements/ajax_search')); ?>
	<?php } ?>
</div>
<!--//gsearch-box-->