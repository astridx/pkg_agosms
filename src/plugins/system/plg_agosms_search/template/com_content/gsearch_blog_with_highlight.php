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

$document = JFactory::getDocument();
$lang = JFactory::getLanguage();
$lang->load("mod_agosms_search");

require_once JPATH_SITE . "/plugins/system/plg_agosms_search/models/com_content/model.php";
$model = new ArticlesModelGoodSearch;

$model->limit = JRequest::getInt("limit", $model->module_params->items_limit); // Set items per page;
$columns = $model->module_params->template_columns;

$items = $model->getItems();

JHtml::_('bootstrap.framework');
JHtml::_('formbehavior.chosen', '.gsearch-results-' . $model->module_id . ' select', false, Array());
$document->addStyleSheet(JURI::root(true) . '/media/jui/css/icomoon.css');
?>

<style>
	.blog-gsearch img { max-width: 100%; }
	.blog-gsearch .pagination { text-align: center; float: none; width: 100%; }
	.blog-gsearch .item { margin-top: 30px; }	
	.blog-gsearch .item .item-info { font-size: 12px; margin: 20px 0 20px 0; padding-bottom: 10px; border-bottom: 1px solid #eee; }
	.blog-gsearch .item .item-info ul { list-style: none; margin: 0; padding: 0; }
	.blog-gsearch .item .item-info li { display: inline-block; position: relative; margin-right: 15px; }
	.blog-gsearch .item.unmarged { margin-left: 0px !important; }
	<?php if ($model->module_params->image_width)
	{
		?>
		div.gsearch-results-<?php echo $model->module_id; ?> img { 
			max-width: <?php echo str_replace("px", "", $model->module_params->image_width); ?>px !important; 
			height: auto !important; 
		}
	<?php } ?>
	<?php echo $model->module_params->styles; ?>
	
	/* bootstrap grid */
	.row-fluid [class*="span"] {
	  display: block;
	  float: left;
	  width: 100%;
	  min-height: 30px;
	  margin-left: 2.127659574468085%;
	  *margin-left: 2.074468085106383%;
	  -webkit-box-sizing: border-box;
		 -moz-box-sizing: border-box;
			  box-sizing: border-box;
	}

	.row-fluid [class*="span"]:first-child {
	  margin-left: 0;
	}

	.row-fluid .controls-row [class*="span"] + [class*="span"] {
	  margin-left: 2.127659574468085%;
	}

	.row-fluid .span12 {
	  width: 100%;
	  *width: 99.94680851063829%;
	}

	.row-fluid .span11 {
	  width: 91.48936170212765%;
	  *width: 91.43617021276594%;
	}

	.row-fluid .span10 {
	  width: 82.97872340425532%;
	  *width: 82.92553191489361%;
	}

	.row-fluid .span9 {
	  width: 74.46808510638297%;
	  *width: 74.41489361702126%;
	}

	.row-fluid .span8 {
	  width: 65.95744680851064%;
	  *width: 65.90425531914893%;
	}

	.row-fluid .span7 {
	  width: 57.44680851063829%;
	  *width: 57.39361702127659%;
	}

	.row-fluid .span6 {
	  width: 48.93617021276595%;
	  *width: 48.88297872340425%;
	}

	.row-fluid .span5 {
	  width: 40.42553191489362%;
	  *width: 40.37234042553192%;
	}

	.row-fluid .span4 {
	  width: 31.914893617021278%;
	  *width: 31.861702127659576%;
	}

	.row-fluid .span3 {
	  width: 23.404255319148934%;
	  *width: 23.351063829787233%;
	}

	.row-fluid .span2 {
	  width: 14.893617021276595%;
	  *width: 14.840425531914894%;
	}

	.row-fluid .span1 {
	  width: 6.382978723404255%;
	  *width: 6.329787234042553%;
	}	
	
	@media (max-width: 767px) {
		.row-fluid [class*="span"] {
		float: none;
		display: block;
		width: 100%;
		margin-left: 0;
		-webkit-box-sizing: border-box;
		-moz-box-sizing: border-box;
		box-sizing: border-box;
	}
</style>
<script>
		jQuery(document).ready(function() {
			jQuery.fn.highlight = function (str, className) {
				var regex = new RegExp(str, "gi");
				return this.each(function () {
					jQuery(this).contents().filter(function() {
						return this.nodeType == 3 && regex.test(this.nodeValue);
					}).replaceWith(function() {
						return (this.nodeValue || "").replace(regex, function(match) {
							return "<span style='background-color: #ffff00; font-weight: bold; padding: 2px 5px;' class=\"" + className + "\">" + match + "</span>";
						});
					});
				});
			};
			<?php if (JRequest::getVar("keyword", "") != "")
			:
				?>
			jQuery(".blog-gsearch *").highlight("<?php echo JRequest::getVar("keyword", ""); ?>", "highlight");
			<?php endif; ?>
		});
	
</script>

<div class="blog blog-gsearch gsearch-results-<?php echo $model->module_id; ?><?php if ($columns > 1)
{
	echo ' columned';
											  } ?>" itemscope itemtype="https://schema.org/Blog">
	<div class="page-header" style="display: inline-block;">
		<h3><?php echo (count($items) ? JText::_("MOD_AGOSMSSEARCHRESULT_PHRASE_DEFAULT") . " ({$model->total_items})" : JText::_(MOD_AGOSMSSEARCHPHRASE_NO_RESULT_DEFAULT)); ?></h3>
	</div>
	
	<?php if (count($items))
	{
		?>
	<div class="gsearch-toolbox" style="float: right; margin-top: 12px;">
		<div class="gsearch-sorting">
		<?php require dirname(__FILE__) . '/gsearch_sorting.php'; ?>
		</div>
	</div>
	<?php } ?>
	
	<div style="clear: both;"></div>
	
	<div class="itemlist<?php if ($columns > 1)
	{
		echo ' row-fluid';
						} ?>">
	<?php
	foreach ($items as $items_counter => $item)
	{
			$item->slug = $item->alias ? ($item->id . ':' . $item->alias) : $item->id;
			$item->parent_slug = ($item->parent_alias) ? ($item->parent_id . ':' . $item->parent_alias) : $item->parent_id;

		if ($item->parent_alias == 'root')
		{
			$item->parent_slug = null;
		}

			$item->catslug = $item->category_alias ? ($item->catid . ':' . $item->category_alias) : $item->catid;

			// Exec content plugins
			$model->execPlugins($item);
		?>
		<?php
		if ($model->module_params->results_template == "")
		{
			$model->module_params->results_template = "standard";
		}

		if ($model->module_params->results_template == "standard")
		{
			require dirname(__FILE__) . '/gsearch_blog_item.php';
		}
		else
		{
			require dirname(__FILE__) . "/gsearch_blog_item_{$model->module_params->results_template}.php";
		}
		?>
	<?php } ?>
	</div>
	
	<div style="clear: both;"></div>
	<div class="pagination">
		<?php
			$pagination = $model->getPagination();
			$PagesLinks = $pagination->getPagesLinks();
			$PagesLinks = preg_replace('/&amp;limitstart=0/', '', $PagesLinks);
			$PagesLinks = preg_replace('/&amp;page-start=.[0-9]*/', '', $PagesLinks);
			$PagesLinks = preg_replace('/&amp;start=/', '&amp;page-start=', $PagesLinks);
			$PagesLinks = preg_replace('/&amp;limitstart=/', '&amp;page-start=', $PagesLinks);
			$PagesLinks = preg_replace('/\?limitstart=0/', '', $PagesLinks);
			$PagesLinks = preg_replace('/\?page-start=.[0-9]*/', '', $PagesLinks);
			$PagesLinks = preg_replace('/\?start=/', '?page-start=', $PagesLinks);

		if (strpos($PagesLinks, "?") === false)
		{
			$PagesLinks = preg_replace('/&amp;page-start=/', '?page-start=', $PagesLinks);
		}

			echo $PagesLinks;
		?>
		<div class="clearfix"></div>
		<?php echo $pagination->getPagesCounter(); ?>
	</div>
</div>
