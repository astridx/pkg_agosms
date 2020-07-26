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
if (JFactory::getApplication()->input->get->get('author')) {
	$active = JFactory::getApplication()->input->get->get('author');
}
?>

<div class="gsearch-field-select author">	
	<h3>
		<?php echo JText::_('MOD_AGOSMSSEARCHFILTER_TYPE_AUTHOR'); ?>
	</h3>
	<select class="inputbox" name="author[]" multiple="multiple" style="display: none;">
		<option value=""><?php echo JText::_('MOD_AGOSMSSEARCHFILTER_TYPE_AUTHOR'); ?></option>
		<?php foreach($authors as $author) { ?>
			<option 
				value="<?php echo $author->id; ?>"
				<?php if(in_array($author->id, $active)) { ?> 
				selected="selected"
				<?php } ?>
			>
				<?php 
					echo $author->name; 
				?>
			</option>
		<?php } ?>
	</select>
</div>

