<?php

/**
 * @package     Articles Good Search
 *
 * @copyright   Copyright (C) 2017 Joomcar extensions. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

$titles = $helper->getItemsTitles($params);
$active = JRequest::getVar("keyword");
?>

<div class="gsearch-field-select title">	
	<h3>
		<?php echo JText::_('MOD_AGS_FILTER_TYPE_TITLE_SELECT'); ?>
	</h3>
	<select class="inputbox" name="keyword">
		<option value=""><?php echo JText::_('MOD_AGS_FILTER_TYPE_TITLE_SELECT'); ?></option>
		<?php foreach($titles as $title) { ?>
			<option <?php if($title == $active) { ?> selected="selected"<?php } ?>>
				<?php echo $title; ?>
			</option>
		<?php } ?>
	</select>
</div>

