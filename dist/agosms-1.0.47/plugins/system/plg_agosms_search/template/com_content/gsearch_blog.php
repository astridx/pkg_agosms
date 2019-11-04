<?php

/**
 * @package     Articles Good Search
 *
 * @copyright   Copyright (C) 2017 Joomcar extensions. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

defined('_JEXEC') or die;

$document = JFactory::getDocument();
$lang = JFactory::getLanguage();
$lang->load("mod_agosms_search");

require_once(JPATH_SITE . "/plugins/system/plg_agosms_search/models/com_content/model.php");
$model = new ArticlesModelAgSearch;

$model->limit = JRequest::getInt("limit", $model->module_params->items_limit); //set items per page;
$columns = JRequest::getInt('columned', $model->module_params->template_columns);
if(!$columns) $columns = 1;

$items = $model->getItems();

if($model->module_params->page_heading != "") {
	$document->setTitle($model->module_params->page_heading);
}

JHtml::_('bootstrap.framework');
$document->addStyleSheet(JURI::root(true) . '/media/jui/css/icomoon.css');
?>

<style>
	.blog-gsearch img { max-width: 100%; }
	.blog-gsearch div.pagination { text-align: center; float: none; width: 100%; margin: 40px 0 0 0; }
	.blog-gsearch ul.pagination { display: inline-block; margin: 0 auto; float: none; }
	.blog-gsearch .item { margin-top: 30px; margin-right: 0px !important; }	
	.blog-gsearch .item .item-info { font-size: 12px; margin: 20px 0 20px 0; padding-bottom: 10px; border-bottom: 1px solid #eee; }
	.blog-gsearch .item .item-info ul { list-style: none; margin: 0; padding: 0; }
	.blog-gsearch .item .item-info li { display: inline-block; position: relative; margin-right: 15px; }
	.blog-gsearch .item .item-info ul.tags li { margin-right: 3px; padding: 0; }
	.blog-gsearch .item.unmarged { margin-left: 0px !important; }
	<?php if($model->module_params->image_width != 'auto') { ?>
		div.gsearch-results-<?php echo $model->module_id; ?> img { 
			max-width: <?php echo $model->module_params->image_width; ?> !important; 
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
	}
</style>

<div id="gsearch-results" class="blog blog-gsearch gsearch-results-<?php echo $model->module_id; ?><?php if($columns > 1) { echo ' columned'; } ?>" itemscope itemtype="https://schema.org/Blog">
	<div class="page-header" style="display: inline-block;">
		<h3>
			<?php
				if(!$model->module_params->resultf) {
					$model->module_params->resultf = JText::_("MOD_AGS_RESULT_PHRASE_DEFAULTфыв");
				}
				echo (count($items) ? JText::_($model->module_params->resultf) . " ({$model->total_items})" : JText::_($model->module_params->noresult)); 
			?>
		</h3>
	</div>
	
	<?php if(count($items)) { ?>
	<div class="gsearch-toolbox" style="float: right; margin-top: 12px;">
		<?php if($model->module_params->layout_show) { ?>
		<div class="gsearch-layout">
		<?php require(dirname(__FILE__). '/gsearch_layout.php'); ?>
		</div>
		<?php } ?>
		<?php if($model->module_params->ordering_show) { ?>
		<div class="gsearch-sorting">
		<?php require(dirname(__FILE__). '/gsearch_sorting.php'); ?>
		</div>
		<?php } ?>
		<div style="clear: both;"></div>
	</div>
	<?php } ?>
	
	<div style="clear: both;"></div>
	
	<div class="itemlist<?php if($columns > 1) { echo ' row-fluid'; } ?>">
	<?php foreach($items as $items_counter => $item) { 
			$item->slug = $item->alias ? ($item->id . ':' . $item->alias) : $item->id;
			$item->parent_slug = ($item->parent_alias) ? ($item->parent_id . ':' . $item->parent_alias) : $item->parent_id;
			if ($item->parent_alias == 'root') {
				$item->parent_slug = null;
			}
			$item->catslug = $item->category_alias ? ($item->catid . ':' . $item->category_alias) : $item->catid;
	?>
		<?php
			if($model->module_params->results_template == "") {
				$model->module_params->results_template = "standard";
			}
			if($model->module_params->results_template == "standard") {
				require(dirname(__FILE__). '/gsearch_blog_item.php');
			}
			else {
				require(dirname(__FILE__). "/gsearch_blog_item_{$model->module_params->results_template}.php"); 
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
			if(strpos($PagesLinks, "?") === false) {
				$PagesLinks = preg_replace('/&amp;page-start=/', '?page-start=', $PagesLinks);
			}			
			if(strpos($PagesLinks, "page-start") === false) { // sh404sef fix
				$PagesLinks = preg_replace_callback(
					'/(title="([^"]*)"[^>]*gsearch=1)/smix', 
					function($matches) use($model) {
						if((int)$matches[2] != 0) { // is page number
							return $matches[1] . '&page-start=' . ($matches[2] - 1) * $model->limit;
						}
						else if($matches[2] == "Prev") {
							return $matches[1] . '&page-start=' . ($model->input->get("page-start") - $model->limit);
						}
						else if($matches[2] == "Next") {
							return $matches[1] . '&page-start=' . ($model->input->get("page-start") + $model->limit);
						}
						else if($matches[2] == "End") {
							return $matches[1] . '&page-start=' . ($model->total_items - 1);
						}
						else {
							return $matches[0];
						}
					}, 
					$PagesLinks
				);
			}
			echo $PagesLinks; 
		?>
		<div class="clearfix"></div>
		<?php echo $pagination->getPagesCounter(); ?>
	</div>
</div>