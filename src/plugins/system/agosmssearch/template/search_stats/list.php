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

$mainframe = JFactory::getApplication();
//check for template override
$override = JPATH_SITE . "/templates/{$mainframe->getTemplate()}/html/com_content/search_stats/list.php";
$file_path = __FILE__;
if(JFile::exists($override)
	&& strpos($file_path, "html") === false //do not trigger in override file
) {
	ob_start();
		require($override);
		$return = ob_get_contents();
	ob_end_clean();
	echo $return;
	return;
}

//get model
require_once(JPATH_SITE . "/plugins/system/agosmssearch/models/com_content/model.php");
$model = new ArticlesModelAgSearch;

$items = $model->getStatsList();

?>
<style>
	.searchStatsList .item { display: table; width: 100%; color: #555; margin: 0px !important; text-align: left; float: none !important; }
	.searchStatsList .item.odd { background: #eee; padding: 0px; }
	.searchStatsList .item.header { background: #ddd; padding: 0px !important; }
	.searchStatsList .item > div { display: table-cell; box-sizing: initial !important; padding: 20px 10px; overflow: hidden; vertical-align: middle; text-align: center; margin: 0 !important; float: none !important; }
	.searchStatsList .item .keyword { border-left: 1px solid #ccc; border-right: 1px solid #ccc; }
	.searchStatsList .item .count { border-left: 1px solid #ccc; border-right: 1px solid #ccc; }
	.searchStatsList .item .keyword .value { 
		display: inline-block; margin: 0 5px 5px 0; padding: 5px 8px; font-size: 12px; line-height: 14px; background-color: #999; font-weight: bold;  color: #fff; vertical-align: baseline; text-shadow: 0 -1px 0 rgba(0,0,0,0.25); border-radius: 3px;
	}
	.searchStatsList .item .keyword .value a { color: #fff; }
	.searchStatsList .item .actions { text-align: center; }
	.searchStatsList .item .actions a { display: block; color: #555; }
	
	.searchStatsList .item .num { min-width: 24px; }
	.searchStatsList .item .keyword { width: 60%; }
	.searchStatsList .item .date { min-width: 130px; }
	.searchStatsList .item .count { min-width: 60px; }
	.searchStatsList .item .actions { min-width: 60px; }
	
	.searchStatsList .pagination { text-align: center; }
	.searchStatsList .ajax-result { text-align: center; font-size: 12px; }
</style>

<script>
	window.addEvent('domready', function() {
		SqueezeBox.assign($$('a[rel=popup]'), {
			size: {x: 800, y: 600},
			ajaxOptions: {
				method: 'get'
			}
		});
	});
</script>
<?php JHTML::_('behavior.modal'); ?>

<div class="searchStatsList">
	<div class="page-header" style="display: inline-block;">
		<h2><?php echo JText::_('Search stats'); ?></h2>
	</div>
	
	<div class="btn-toolbar" role="toolbar" aria-label="Toolbar" id="toolbar" style="float: right;">	
		<div class="btn-wrapper" id="toolbar-refresh">
			<button class="btn btn-small button-refresh" data-ajax="gsearch=1&search_type=search_stats&search_mode=reset">
				<span class="icon-refresh" aria-hidden="true"></span>
				Reset stats
			</button>
			<div class="ajax-result" style="display: none;">
				<img style="width: 20px;" src="<?php echo JURI::root(); ?>modules/mod_agosms_search/assets/images/loading.png" />
			</div>
		</div>
	</div>	
	
	<div class="list">
		<div class="item header">
			<div class="num"><?php echo JText::_("#"); ?></div>
			<div class="keyword"><a data-orderby="keyword" href="#"><?php echo JText::_("Keyword"); ?></a></div>
			<div class="date"><a data-orderby="last_search_date" href="#"><?php echo JText::_("Last Search Date"); ?></a></div>
			<div class="count"><a data-orderby="search_count" href="#"><?php echo JText::_("Count"); ?></a></div>
			<div class="actions"><?php echo JText::_("Actions"); ?></a></div>
		</div>
		<?php foreach($items as $items_counter => $item) { ?>
		<div class="item <?php echo $items_counter % 2 == 0 ? 'odd' : ''; ?>">
			<div class="num"><?php echo JFactory::getApplication()->input->get("limitstart", 0) + $items_counter + 1; ?></div>
			<div class="keyword">
				<span class="value">
					<a rel="popup" href="<?php echo JURI::base(); ?>?gsearch=1&search_type=search_stats&search_mode=keyword&id=<?php echo $item->id; ?>&raw=1">
						<?php echo $item->keyword; ?>
					</a>
				</span>
			</div>
			<div class="date"><?php echo $item->last_search_date; ?></div>
			<div class="count"><?php echo $item->search_count; ?></div>
			<div class="actions">
				<a class="search" href="<?php echo urldecode($item->url); ?>" target="_blank"><?php echo JText::_("Search"); ?></a>
				<a class="view" href="<?php echo JURI::base(); ?>?gsearch=1&search_type=search_stats&search_mode=keyword&id=<?php echo $item->id; ?>"><?php echo JText::_("View"); ?></a>
				<a class="delete" data-ajax="gsearch=1&search_type=search_stats&search_mode=delete&id=<?php echo $item->id; ?>" href="#"><?php echo JText::_("Delete"); ?></a>
				<div class="ajax-result" style="display: none;">
					<img style="width: 20px;" src="<?php echo JURI::root(); ?>modules/mod_agosms_search/assets/images/loading.png" />
				</div>
			</div>
		</div>
		<?php } ?>
	</div>
	
	<div style="clear: both;"></div>
	<!-- admin fix -->
	<form name="adminForm" id="adminForm" method="get" action="">
		<input name="limitstart" type="hidden" value="" />
		<?php foreach($_GET as $param=>$value) { 
			if(in_array($param, Array("id", "start", "option", "view", "task", "limit", "limitstart", "featured"))) continue;
		?>
		<input name="<?php echo $param; ?>" value="<?php echo $value; ?>" type="hidden" />
		<?php } ?>
	</form>
	<div class="pagination">
		<?php $pagination = $model->getStatsListPagination(); ?>
		<?php echo $pagination->getPagesLinks(); ?>
		<div class="clearfix"></div>
		<?php echo $pagination->getPagesCounter(); ?>
	</div>
	
	<script>
		jQuery(document).ready(function($) {
			$(".searchStatsList a.delete").on("click", function() {
				var loader = $(this).parent().find(".ajax-result");
				loader.show();
				$.ajax({
					data: $(this).attr("data-ajax"),
					type: "get",
					url: "<?php echo JURI::base(); ?>",
					success: function(res) {
						loader.html(res);
						setTimeout(function() {
							window.location.reload();
						}, 1000);
					}
				});				
				return false;
			});
			
			$(".searchStatsList .button-refresh").on("click", function() {
				var loader = $(this).parent().find(".ajax-result");
				loader.show();
				$.ajax({
					data: $(this).attr("data-ajax"),
					type: "get",
					url: "<?php echo JURI::base(); ?>",
					success: function(res) {
						loader.html(res);
						setTimeout(function() {
							window.location.reload();
						}, 1000);
					}
				});				
				return false;
			});
						
			$(".searchStatsList .header a").on("click", function() {
				var query = window.location.search;
				if(query.indexOf("orderby") != -1) {
					var current = query.split("orderby=")[1];
					current = current.split("&")[0];
					query = query.replace("orderby=" + current, "orderby=" + $(this).data('orderby'));
				}
				else {
					query += "&orderby=" + $(this).data('orderby');
				}
				window.location.search = query;
				return false;
			});
		});
	</script>
</div>