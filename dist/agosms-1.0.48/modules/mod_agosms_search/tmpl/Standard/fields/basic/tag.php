<?php
/**
 * @package     Articles Good Search
 *
 * @copyright   Copyright (C) 2017 Joomcar extensions. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

// no direct access
defined('_JEXEC') or die;

$active = JRequest::getVar("tag");
?>

<div class="gsearch-field-select tags">	
	<h3>
		<?php echo JText::_('MOD_AGS_FILTER_TYPE_TAG'); ?>
	</h3>
	<select class="inputbox" name="tag[]" multiple="multiple">
		<option value=""><?php echo JText::_('MOD_AGS_FILTER_TYPE_TAG'); ?></option>
		<?php foreach($tags as $tag) { ?>
			<option 
				value="<?php echo $tag->id; ?>"
				<?php if(in_array($tag->id, $active)) { ?> 
				selected="selected"
				<?php } ?>
			>
				<?php echo $tag->title; ?>
			</option>
		<?php } ?>
	</select>
</div>