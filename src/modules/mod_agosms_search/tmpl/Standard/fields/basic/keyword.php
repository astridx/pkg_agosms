<?php
/**
 * @package     Articles Good Search
 *
 * @copyright   Copyright (C) 2017 Joomcar extensions. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
?>

<div class="gsearch-field-text keyword">	
	<h3>
		<?php echo JText::_('MOD_AGS_FILTER_TYPE_KEYWORD'); ?>
	</h3>
	<input  placeholder="<?php echo JText::_('MOD_AGS_FILTER_TYPE_KEYWORD'); ?>" 
			class="inputbox" 
			name="keyword" 
			type="text" 
			value="<?php echo JRequest::getVar('keyword'); ?>"
	/>
</div>

