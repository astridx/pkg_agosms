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

use Joomla\Registry\Registry;

$lang = JFactory::getLanguage();
$extension = 'mod_agosms_searchagosms';
$base_dir = JPATH_SITE . '/modules/mod_agosms_searchagosms';
$language_tag = 'en-GB';
$reload = true;
$lang->load($extension, $base_dir, $language_tag, $reload);

require_once JPATH_SITE . "/plugins/system/agosmssearchagosms/models/com_agosms/model.php";
$model = new ArticlesModelAgSearchagosms;
$module_params = new Registry($model->module_params);

// Include skripts/styles to the header
$document = JFactory::getDocument();

$leafletIsLoaded = false;

foreach ($document->_scripts as $key => $script) {
	$leafletPath = "leaflet/leaflet.js";

	if (strpos($key, $leafletPath)) {
		$leafletIsLoaded = true;
	}
}

if ($module_params->get('show_map', "1") === "1") {
	if (!$leafletIsLoaded) {
		$document->addStyleSheet(JURI::root(true) . '/media/mod_agosms_searchagosms/leaflet/leaflet.css');
		$document->addScript(JURI::root(true) . '/media/mod_agosms_searchagosms/leaflet/leaflet.js');
	}

	$document->addStyleSheet(JURI::root(true) . '/media/mod_agosms_searchagosms/cluster/MarkerCluster.css');
	$document->addStyleSheet(JURI::root(true) . '/media/mod_agosms_searchagosms/cluster/MarkerCluster.Default.css');
	$document->addScript(JURI::root(true) . '/media/mod_agosms_searchagosms/cluster/leaflet.markercluster-src.js');
	$document->addScript(JURI::root(true) . '/media/mod_agosms_searchagosms/js/agosm_searchagosms.js');
	$document->addStyleSheet(JURI::root(true) . '/media/mod_agosms_searchagosms/css/agosms_searchagosms.css');
	
	if ($module_params->get('showlocate', "1") == "1") {
		$document->addStyleSheet(JURI::root(true) . '/media/mod_agosms_searchagosms/locate/L.Control.Locate.css');
		$document->addStyleSheet(JURI::root(true) . '/media/mod_agosms_searchagosms/locate/font-awesome.min.css');
		$document->addScript(JURI::root(true) . '/media/mod_agosms_searchagosms/locate/L.Control.Locate.min.js');
	}
}

$model->limit = JFactory::getApplication()->input->get("limit", $module_params->get('items_limit', 10));

$items = $model->getItems();
?>

<div id="gsearch-results" class="blog blog-gsearch gsearch-results-<?php echo $model->module_id; ?>" 
	 itemscope itemtype="https://schema.org/Blog">
	<div class="page-header">
		<div class="new-search-buttons">
			<a href="javascript:history.back()">
				<input style="float:right;padding:1% 10%;" type="submit" value="<?php echo JText::_('MOD_AGOSMSSEARCHBUTTON_NEW_SEARCH_TEXT'); ?>" class="btn btn-primary button submit <?php echo $moduleclass_sfx; ?>" />	
			</a>
		</div>
		<h3>
			<?php
				echo (count($items) ? JText::_("MOD_AGOSMSSEARCHRESULT_PHRASE_DEFAULT") . " ({$model->total_items})" : JText::_("MOD_AGOSMSSEARCHPHRASE_NO_RESULT_DEFAULT"));
			?>
		</h3>
	</div>

<?php if ($module_params->get('show_map', "1") === "1") { ?>
	<?php $defaultArray = []; ?>
<div style="
	width:auto;
	height:<?php echo $module_params->get('height', '400'); ?><?php echo $module_params->get('heightunit', 'px'); ?>;"
	data-module-id="<?php echo $model->module_id; ?>"
	data-uriroot="<?php echo JUri::root(); ?>"
	data-no-world-warp="<?php echo $module_params->get('noWorldWarp', 0); ?>"
	data-detect-retina="<?php echo $module_params->get('detectRetina', 0); ?>"
	data-baselayer="<?php echo $module_params->get('baselayer', 'mapnik'); ?>"
	data-lonlat="<?php echo $module_params->get('lonlat', '50.281168, 7.276211'); ?>"
	data-zoom="<?php echo $module_params->get('zoom', '10'); ?>"
	<?php if ($module_params->get('baselayer', 'mapbox')) : ?>
	data-mapboxmaptype="<?php echo $module_params->get('mapboxmaptype', 'streets'); ?>"
	data-mapboxkey="<?php echo $module_params->get('mapboxkey', ''); ?>"
	<?php endif; ?>
	<?php if ($module_params->get('baselayer', 'thunderforest')) : ?>
	data-thunderforestkey="<?php echo $module_params->get('thunderforestkey', ''); ?>"
	data-thunderforestmaptype="<?php echo $module_params->get('thunderforestmaptype', 'cycle'); ?>"
	<?php endif; ?>
	<?php if ($module_params->get('baselayer', 'stamen')) : ?>
	data-stamenmaptype="<?php echo $module_params->get('stamenmaptype', 'watercolor'); ?>"
	<?php endif; ?>
	<?php if ($module_params->get('baselayer', 'google')) : ?>
	data-googlemapstype="<?php echo $module_params->get('googlemapstype', 'satellite'); ?>"
	<?php endif; ?>
	<?php if ($module_params->get('baselayer', 'custom')) : ?>
	data-customBaselayer="<?php echo $module_params->get('customBaselayer', 'maxZoom: 18,'); ?>"
	data-customBaselayerURL="<?php echo $module_params->get('customBaselayerURL', 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png'); ?>"
	<?php endif; ?>
	data-attr-module="<?php echo $module_params->get('attrModule', 1); ?>"
	<?php if ($module_params->get('scale') !== null) : ?>
	data-scale="<?php echo count($module_params->get('scale')); ?>"
	<?php endif; ?>	
	data-markers="<?php
	echo htmlspecialchars(json_encode($items), ENT_QUOTES, 'UTF-8');
	?>"
	data-scale-metric="<?php echo in_array('metric', $module_params->get('scale', $defaultArray)); ?>"
	data-scale-imperial="<?php echo in_array('imperial', $module_params->get('scale', $defaultArray)); ?>"
	data-showgeocoder="<?php echo $module_params->get('showgeocoder', '1'); ?>"
	data-locate="<?php echo $module_params->get('showlocate', false); ?>"
	class="leafletmapModSearch"
	id="searchmap<?php echo $model->module_id; ?>">
</div>
<?php } ?>



<?php if ($module_params->get('show_resultlist', "1") === "1") { ?>
	<?php if (count($items)) { ?>
<div class="gsearch-toolbox" >
		<?php
		//if ($model->module_params->ordering_show)
		if (false) {
			?>
		<div class="gsearch-sorting">
			<?php require dirname(__FILE__) . '/gsearch_sorting.php'; ?>
		</div>
			<?php
		}
		?>
</div>
	<?php } ?>
	
	
	<div class="itemlist">
	<?php
	foreach ($items as $items_counter => $item) {
		if (!property_exists($item, "parent_alias")) {
			$item->parent_alias = false;
		}
		if (!property_exists($item, "alias")) {
			$item->alias = false;
		}
		$item->slug = $item->alias ? ($item->id . ':' . $item->alias) : $item->id;
		

		if ($item->parent_alias == 'root' || !property_exists($item, "parent_id")) {
			$item->parent_slug = null;
		} else {
			$item->parent_slug = ($item->parent_alias) ? ($item->parent_id . ':' . $item->parent_alias) : $item->parent_id;
		}

		if (property_exists($item, "catslug")) {
			$item->catslug = $item->category_alias ? ($item->catid . ':' . $item->category_alias) : $item->catid;
		} else {
			$item->catslug = null;
		}
		?>
		<?php
		if ($model->module_params->results_template == "") {
			$model->module_params->results_template = "standard";
		}

		if ($model->module_params->results_template == "standard") {
			require dirname(__FILE__) . '/gsearchagosms_blog_item.php';
		} else {
			require dirname(__FILE__) . "/gsearchagosms_blog_item_{$model->module_params->results_template}.php";
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

		if (strpos($PagesLinks, "?") === false) {
			$PagesLinks = preg_replace('/&amp;page-start=/', '?page-start=', $PagesLinks);
		}

		if (strpos($PagesLinks, "page-start") === false) {
			$PagesLinks = preg_replace_callback(
				'/(title="([^"]*)"[^>]*gsearch=1)/smix',
				function ($matches) use ($model) {
					if ((int) $matches[2] != 0) {
						// Is page number
						return $matches[1] . '&page-start=' . ($matches[2] - 1) * $model->limit;
					} else if ($matches[2] == "Prev") {
						return $matches[1] . '&page-start=' . ($model->input->get("page-start") - $model->limit);
					} else if ($matches[2] == "Next") {
						return $matches[1] . '&page-start=' . ($model->input->get("page-start") + $model->limit);
					} else if ($matches[2] == "End") {
						return $matches[1] . '&page-start=' . ($model->total_items - 1);
					} else {
						return $matches[0];
					}
				},
				$PagesLinks
			);
		}

			echo $PagesLinks;
		?>
		<?php
			echo $pagination->getPagesCounter();
		?>
	</div>
<?php } ?>	
</div>
