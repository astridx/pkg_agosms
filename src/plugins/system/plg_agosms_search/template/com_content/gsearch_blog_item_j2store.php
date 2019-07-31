<?php

/**
 * @package     Articles Good Search
 *
 * @copyright   Copyright (C) 2017 Joomcar extensions. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

defined('_JEXEC') or die;

$fields = FieldsHelper::getFields('com_content.article', $item, true);
$tmp = new stdClass;
foreach($fields as $field) {
	$name = $field->name;
	$tmp->{$name} = $field;
}
$fields = $tmp;
//you can call some field with $fields->{"name"}->title and $fields->{"name"}->value
//e.g.
//echo $fields->{"test1"}->title . ' - ' .  $fields->{"test1"}->value;

$image_type = $model->module_params->image_type;
$images = json_decode($item->images);			
$ImageIntro = strlen($images->image_intro) > 1 ? 1 : 0;
preg_match('/(<img[^>]+>)/i', $item->introtext, $matches);
$ImageInText = count($matches);

if (JPluginHelper::isEnabled('system', 'imagestab')) {
	$db = JFactory::getDBO();
	$db->setQuery("SELECT COUNT(*) FROM #__content_images_data WHERE article_id = {$item->id}");
	$res = $db->loadResult();
	$ImagesTab = (int)$res;
}

if ($image_type == "intro" || $ImagesTab) {
	$item->introtext = trim(strip_tags($item->introtext, '<h2><h3>'));
}
if($model->module_params->text_limit) {
	preg_match('/(<img[^>]+>)/i', $item->introtext, $images_text);	
	$item->introtext = trim(strip_tags($item->introtext, '<h2><h3>'));
	if(extension_loaded('mbstring')) {
		$item->introtext = mb_strimwidth($item->introtext, 0, $model->module_params->text_limit, '...', 'utf-8');
	}
	else {
		$item->introtext = strlen($item->introtext) > $model->module_params->text_limit ? substr($item->introtext, 0, $model->module_params->text_limit) . '...' : $item->introtext;
	}
	if(count($images_text) && 
		($image_type == "text" || ($image_type == "" && !$ImageIntro))
	) {
		if(strpos($images_text[0], '://') === false) {
			$parts = explode('src="', $images_text[0]);
			$images_text[0] = $parts[0] . 'src="' . JURI::root() . $parts[1];
		}
		$item->introtext = $images_text[0] . $item->introtext;
	}
}
$model->execPlugins($item);

// Get J2Store product
$query = "
			SELECT p.*, v.* FROM #__j2store_products as p
			LEFT JOIN #__j2store_variants as v ON v.product_id = p.j2store_product_id
			WHERE product_source_id = {$item->id}
		";
$product = JFactory::getDBO()->setQuery($query)->loadObject();
require_once(JPATH_ADMINISTRATOR . '/components/com_j2store/helpers/product.php');
$J2ProductHelper = new J2Product;
$productPrice = $J2ProductHelper->displayPrice($product->price, $product);
$productURL = JRoute::_('index.php?option=com_j2store&view=products&task=view&id='.$product->j2store_product_id);

if($product->vendor_id) {
	$vendor_table = F0FTable::getInstance('Vendor', 'J2StoreTable');
	$vendor_table->load($product->vendor_id);
	if($vendor_table->address_id > 0){
		$address_table = F0FTable::getInstance('Address', 'J2StoreTable');
		$address_table->load($vendor_table->address_id);
		$product->vendor_name = $address_table->first_name." ".$address_table->last_name;
	}
}

?>

<div class="item<?php echo $item->featured ? ' featured' : ''; ?> <?php if($columns > 1 && ($items_counter % $columns == 0)) { echo 'unmarged'; } ?> <?php if($columns > 1) { echo 'span' . 12 / $columns; } ?>" itemprop="blogPost" itemscope itemtype="https://schema.org/BlogPosting">
	<h3 itemprop="name" class="item-title">
		<a href="<?php echo $productURL; ?>" itemprop="url">
			<?php echo $item->title; ?>
		</a>
	</h3>
	
	<?php if($productPrice) { ?>
	<div class="product-price">
		<?php echo $productPrice; ?>
	</div>
	<?php } ?>
	
	<?php echo $item->event->afterDisplayTitle; ?>
	<?php echo $item->event->beforeDisplayContent; ?>

	<?php if ($ImageIntro && !$ImagesTab && ($image_type == "intro" || $image_type == "")) { ?>
	<div class="item-image">
		<a href="<?php echo $productURL; ?>">
			<img src="<?php echo JURI::root() . htmlspecialchars($images->image_intro, ENT_COMPAT, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($images->image_intro_alt, ENT_COMPAT, 'UTF-8'); ?>" itemprop="thumbnailUrl"/>
		</a>
	</div>
	<?php } ?>
	
	<?php 
	$image_empty = $model->module_params->image_empty;
	if(((!$ImageIntro && $image_type == "intro") || (!$ImageInText && $image_type == "text") || (!$ImageIntro && !$ImageInText && $image_type == "")) && $image_empty != "" && $image_empty != "-1" && !$ImagesTab) { ?>
	<div class="item-image image-empty">
		<a href="<?php echo $productURL; ?>">
			<img src="<?php echo JURI::root(); ?>images/<?php echo $image_empty; ?>" itemprop="thumbnailUrl"/>
		</a>
	</div>
	<?php } ?>
	
	<?php if($model->module_params->show_introtext) { ?>
	<div class="item-body">
		<div class="introtext">
			<?php echo $item->introtext; ?>
		</div>
		<div style="clear: both;"></div>
	</div>
	<?php } ?>
	
	<?php if($model->module_params->show_readmore) { ?>
	<div class="item-readmore">
		<a class="btn btn-secondary" href="<?php echo $productURL; ?>"><?php echo JText::_('MOD_AGS_ITEM_READMORE'); ?></a>
	</div>
	<?php } ?>
	
	<?php if($model->module_params->show_info) { ?>
	<div class="item-info">
		<ul>
			<li class="createdby hasTooltip" itemprop="author" itemscope="" itemtype="http://schema.org/Person" title="" data-original-title="Written by">
				<i class="icon icon-user"></i>
				<span itemprop="name"><?php echo $model->getAuthorById($item->created_by)->name; ?></span>
			</li>
			<li class="category-name hasTooltip" title="" data-original-title="Category">
				<i class="icon icon-folder"></i>
				<?php foreach($model->getItemCategories($item) as $category) { ?>
				<a href="<?php echo $category->link; ?>">
					<span itemprop="genre">
						<?php echo $category->title; ?>
					</span>
				</a>				
				<?php } ?>
			</li>
			<?php
			if($item->tags != "") {
				$item->tags = new JHelperTags;
				$item->tags->getItemTags('com_content.article', $item->id);
			?>
			<li class="tags hasTooltip" title="" data-original-title="Tags">
				<i class="icon icon-tags"></i>
				<div style="display: inline-block;">
				<?php echo JLayoutHelper::render('joomla.content.tags', $item->tags->itemTags); ?>
				</div>
			</li>
			<?php } ?>
			<li class="created">
				<i class="icon icon-clock"></i>
				<time datetime="<?php echo $item->created; ?>" itemprop="dateCreated">
					<?php echo JText::_('MOD_AGS_ITEM_CREATED'); ?>
					<?php 
						setlocale(LC_ALL, JFactory::getLanguage()->getLocale());
						$date_format = explode("::", $model->module_params_native->get('date_format', '%e %b %Y::d M yyyy'))[0];
						$date = strftime($date_format, strtotime($item->created));
						$date = mb_convert_case($date, MB_CASE_TITLE, 'UTF-8');
						echo $date; 
					?>
				</time>
			</li>
			<li class="hits">
					<i class="icon icon-eye"></i>
					<meta itemprop="interactionCount" content="UserPageVisits:<?php echo $item->hits; ?>">
					<?php echo JText::_('MOD_AGS_ITEM_HITS'); ?> <?php echo $item->hits; ?>
			</li>
		</ul>
	</div>
	<?php } ?>
	
	<?php echo $item->event->afterDisplayContent; ?>
	<div style="clear: both;"></div>
</div>
<?php if(($items_counter + 1) % $columns == 0) { ?>
<div style="clear: both;"></div>
<?php } ?>