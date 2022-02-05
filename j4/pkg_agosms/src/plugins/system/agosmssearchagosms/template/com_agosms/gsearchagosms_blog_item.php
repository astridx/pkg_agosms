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

use Joomla\Component\Fields\Administrator\Helper\FieldsHelper;

$lang = JFactory::getLanguage();
$extension = 'mod_agosms_searchagosms';
$base_dir = JPATH_SITE . '/modules/mod_agosms_searchagosms';
$language_tag = 'en-GB';
$reload = true;
$lang->load($extension, $base_dir, $language_tag, $reload);

$fields = FieldsHelper::getFields('com_agosms.agosm', $item, true);
$tmp = new stdClass;
foreach ($fields as $field) {
	$name = $field->name;
	$tmp->{$name} = $field;
}
$fields = $tmp;
//you can call some field with $fields->{"name"}->title and $fields->{"name"}->value
//e.g.
//echo $fields->{"test1"}->title . ' - ' .  $fields->{"test1"}->value;

//$image_type = $model->module_params->image_type;
//$images = json_decode($item->images);
//$ImageIntro = strlen($images->image_intro) > 1 ? 1 : 0;
//$ImageIntro = 0;
//preg_match('/(<img[^>]+>)/i', $item->introtext, $matches);
//$ImageInText = count($matches);
//$ImagesTab = 0;
/*
if ($image_type == "intro" || $ImagesTab) {
	$item->introtext = trim(strip_tags($item->introtext, '<h2><h3>'));
}

if ($model->module_params->text_limit) {
	preg_match('/(<img[^>]+>)/i', $item->introtext, $images_text);
	$item->introtext = trim(strip_tags($item->introtext, '<h2><h3>'));
	
	if (extension_loaded('mbstring')) {
		$item->introtext = mb_strimwidth($item->introtext, 0, $model->module_params->text_limit, '...', 'utf-8');
	} else {
		$item->introtext = strlen($item->introtext) > $model->module_params->text_limit ? substr($item->introtext, 0, $model->module_params->text_limit) . '...' : $item->introtext;
	}
	
	if (count($images_text) &&
		($image_type == "text" || ($image_type == "" && !$ImageIntro))
	) {
		if (strpos($images_text[0], '://') === false) {
			$parts = explode('src="', $images_text[0]);
			$images_text[0] = $parts[0] . 'src="' . JURI::root() . $parts[1];
		}
		$item->introtext = $images_text[0] . $item->introtext;
	}
}*/
$model->execPlugins($item);

$distance = "";

if (isset($item->distance)) {
	$distance = round($item->distance, 2);
}
?>

<div class="item<?php echo $item->featured ? ' featured' : ''; ?>" itemprop="blogPost" itemscope itemtype="https://schema.org/BlogPosting">
	<h3 itemprop="name" class="item-title">
		<?php if (property_exists($item, "slug")) { ?>
			<a href="<?php echo JRoute::_(  'index.php?option=com_agosms&view=agosm&id=' . $item->id  ); ?>" itemprop="url">
				<?php echo $item->name . ' ' . $distance; ?>

			</a>
		<?php } else { ?>
				<?php echo $item->name . ' ' . $distance; ?>
		<?php }  ?>
	</h3>


	<div style="clear: both;"></div>
</div>
