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

/*$active =  array();
if (JFactory::getApplication()->input->post->get('tag')) {
	$active = JFactory::getApplication()->input->post->get('tag');
}*/
?>

<div class="gsearch-field-select tags">	
	<h3>
		<?php echo JText::_('MOD_AGOSMSSEARCHFILTER_TYPE_TAG_CLOUD'); ?>
	</h3>
	<div class="tag-cloud<?php echo $module->id; ?>" style="max-width: 250px;">
		<?php foreach($tags as $tag) { ?>
			<a href="#" data-tag-id="<?php echo $tag->id; ?>" class="label label-primary" style="display: inline-block; margin-bottom: 5px;">
				<?php echo $tag->title; ?>
			</a>
		<?php } ?>
	</div>
	<input type="hidden" class="tag-cloud<?php echo $module->id; ?>-value inputbox" name="tag[]" value="" />
	
	<script>
		jQuery(document).ready(function($) {
			$(".tag-cloud<?php echo $module->id; ?> a").on("click", function() {
				$(".tag-cloud<?php echo $module->id; ?>-value").val($(this).data("tag-id"));
				submit_form_<?php echo $module->id; ?>();
				return false;
			});
		});
	</script>
</div>

