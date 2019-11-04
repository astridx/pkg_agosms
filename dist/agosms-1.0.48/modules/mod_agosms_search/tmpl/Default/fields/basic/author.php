<?php

/**
 * @package     Articles Good Search
 *
 * @copyright   Copyright (C) 2017 Joomcar extensions. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

// no direct access
defined('_JEXEC') or die;
$active = JRequest::getVar("author");

?>

<div class="gsearch-field-select author">	
	<h3>
		<?php echo JText::_('MOD_AGS_FILTER_TYPE_AUTHOR'); ?>
	</h3>
	<select class="inputbox" name="author[]" multiple="multiple" style="display: none;">
		<option value=""><?php echo JText::_('MOD_AGS_FILTER_TYPE_AUTHOR'); ?></option>
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

