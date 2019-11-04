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
$override = JPATH_SITE . "/templates/{$mainframe->getTemplate()}/html/com_content/search_stats/keyword.php";
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

$items = $model->getStatsKeywordList();

?>
<style>
	.searchStatsList .item { display: table; width: 100%; color: #555; margin: 0px !important; text-align: left; float: none !important; }
	.searchStatsList .item.odd { background: #eee; padding: 0px; }
	.searchStatsList .item.header { background: #ddd; padding: 0px !important; }
	.searchStatsList .item > div { display: table-cell; box-sizing: initial !important; padding: 20px 10px; overflow: hidden; vertical-align: middle; text-align: center; margin: 0 !important; float: none !important; }
	.searchStatsList .item .user { border-left: 1px solid #ccc; border-right: 1px solid #ccc; }
	.searchStatsList .item .count { border-left: 1px solid #ccc; border-right: 1px solid #ccc; }
	
	.searchStatsList .item .num { min-width: 24px; }
	.searchStatsList .item .user { width: 69%; }
	.searchStatsList .item .date { min-width: 130px; }
	.searchStatsList .item .count { min-width: 60px; }
	
	.searchStatsList .pagination { text-align: center; }
</style>

<div class="searchStatsList">
	<div class="page-header" style="display: inline-block;">
		<?php 
			$keyword_id = JFactory::getApplication()->input->get("id");
			$query = "SELECT keyword FROM #__content_search_stats WHERE id = {$keyword_id}";
			$keyword = JFactory::getDBO()->setQuery($query)->loadResult();
		?>
		<h3><?php echo JText::_('Search Stats for Keyword - ') . $keyword; ?></h3>
	</div>
	
	<div class="list">
		<div class="item header">
			<div class="num"><?php echo JText::_("#"); ?></div>
			<div class="user"><?php echo JText::_("User"); ?></a></div>
			<div class="date"><a data-orderby="last_search_date" href="#"><?php echo JText::_("Last Search Date"); ?></a></div>
			<div class="count"><a data-orderby="search_count" href="#"><?php echo JText::_("Count"); ?></a></div>
		</div>
		<?php foreach($items as $items_counter => $item) { ?>
		<div class="item <?php echo $items_counter % 2 == 0 ? 'odd' : ''; ?>">
			<div class="num"><?php echo JFactory::getApplication()->input->get("limitstart", 0) + $items_counter + 1; ?></div>
			<div class="user">
				<?php 
					$uid = $item->user_id;
					if((int)$uid == 0) {
						$user = "Guest";
					}
					else {
						$user_data = array();
						if(!count($user_data)) {
							$obj = JFactory::getUser($uid);
							$user_data[] = JText::_('Name: ') . $obj->name;
							$user_data[] = JText::_('Email: ') . $obj->email;
							$user_data[] = JText::_('IP: ') . $item->ip_address;
						}
						$user = implode(", ", $user_data);
					}
				?>
				<?php echo $user; ?>
			</div>
			<div class="date"><?php echo $item->last_search_date; ?></div>
			<div class="count"><?php echo $item->search_count; ?></div>
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
		<?php $pagination = $model->getStatsKeywordListPagination(); ?>
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