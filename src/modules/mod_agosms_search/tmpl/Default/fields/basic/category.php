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
if (JFactory::getApplication()->input->get->get('category')) {
	$active = JFactory::getApplication()->input->get->get('category');
}
?>

<div class="gsearch-field-select category">	
	<h3>
		<?php echo JText::_('MOD_AGOSMSSEARCHFILTER_TYPE_CATEGORY'); ?>
	</h3>
	<select class="inputbox" name="category[]" multiple="multiple">
		<option value=""><?php echo JText::_('MOD_AGOSMSSEARCHFILTER_TYPE_CATEGORY'); ?></option>
		<?php foreach($categories as $category) { ?>
			<option 
				value="<?php echo $category->id; ?>"
				<?php if(in_array($category->id, $active)) { ?> 
				selected="selected"
				<?php } ?>
			>
				<?php 
					$indent = "";
					
					for($i = 1; $i < $category->level; $i++) 
					{ 
						$indent .= " - "; 
					}
					
					echo $indent . $category->title; 
				?>
			</option>
		<?php } ?>
	</select>
</div>

