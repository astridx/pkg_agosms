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

$active =  array();
if (JFactory::getApplication()->input->get->get('tag')) {
	$active = JFactory::getApplication()->input->get->get('tag');
}

?>

<div class="gsearch-field-select tags">	
	<h3>
		<?php echo JText::_('MOD_AGOSMSSEARCHFILTER_TYPE_TAG'); ?>
	</h3>
	<select class="inputbox" name="tag[]" multiple="multiple" style="display: none;">
		<option value=""><?php echo JText::_('MOD_AGOSMSSEARCHFILTER_TYPE_TAG'); ?></option>
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

