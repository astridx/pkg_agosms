GSEARCH_ANFANG
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

$model->limit = $model->module_params->items_limit;

$items = $model->getItems();

if ($model->module_params->page_heading != "")
{
	$document->setTitle($model->module_params->page_heading);
}
?>

<div id="gsearch-results" class="blog blog-gsearch gsearch-results-<?php echo $model->module_id; ?>" 
	 itemscope itemtype="https://schema.org/Blog">
	<div class="page-header">
		<h3>
			<?php
			if (!$model->module_params->resultf)
{
				$model->module_params->resultf = JText::_("MOD_AGOSMSSEARCHRESULT_PHRASE_DEFAULTфыв");
			}

				echo (count($items) ? JText::_($model->module_params->resultf) . " ({$model->total_items})" : JText::_($model->module_params->noresult));
			?>
		</h3>
	</div>
	
	<?php if (count($items))
	{
	?>
	<div class="gsearch-toolbox" >
		<?php
		if ($model->module_params->ordering_show)
		{
		?>
			<div class="gsearch-sorting">
				<?php require dirname(__FILE__) . '/gsearch_sorting.php'; ?>
			</div>
		<?php 
		} 
		?>
	</div>
	<?php 
	} 
	?>
	
	
	<div class="itemlist">
	<?php
	foreach ($items as $items_counter => $item)
{
		if (!property_exists($item, "parent_alias"))
		{
			$item->parent_alias = false;
		}
		if (!property_exists($item, "alias"))
		{
			$item->alias = false;
		}
		$item->slug = $item->alias ? ($item->id . ':' . $item->alias) : $item->id;
		

		if ($item->parent_alias == 'root' || !property_exists($item, "parent_id"))
		{
			$item->parent_slug = null;
		}
		else
		{
			$item->parent_slug = ($item->parent_alias) ? ($item->parent_id . ':' . $item->parent_alias) : $item->parent_id;
		}

		if (property_exists($item, "catslug"))
		{
			$item->catslug = $item->category_alias ? ($item->catid . ':' . $item->category_alias) : $item->catid;
		}
		else 
		{
			$item->catslug = null;
		}
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

		if (strpos($PagesLinks, "page-start") === false)
{
			// Sh404sef fix
			$PagesLinks = preg_replace_callback(
				'/(title="([^"]*)"[^>]*gsearch=1)/smix',
				function ($matches) use ($model) {
					if ((int) $matches[2] != 0)
			{
						// Is page number
						return $matches[1] . '&page-start=' . ($matches[2] - 1) * $model->limit;
					}
					elseif ($matches[2] == "Prev")
			{
						return $matches[1] . '&page-start=' . ($model->input->get("page-start") - $model->limit);
					}
					elseif ($matches[2] == "Next")
			{
						return $matches[1] . '&page-start=' . ($model->input->get("page-start") + $model->limit);
					}
					elseif ($matches[2] == "End")
			{
						return $matches[1] . '&page-start=' . ($model->total_items - 1);
					}
					else
			{
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
