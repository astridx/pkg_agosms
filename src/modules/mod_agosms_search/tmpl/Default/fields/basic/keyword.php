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
defined('_JEXEC') or die('Restricted access');

?>

<div class="gsearch-field-text keyword">	
	<h3>
		<?php echo JText::_('MOD_AGOSMSSEARCHFILTER_TYPE_KEYWORD'); ?>
	</h3>
	<input  placeholder="<?php echo JText::_('MOD_AGOSMSSEARCHFILTER_TYPE_KEYWORD'); ?>" 
			class="inputbox" 
			name="keyword" 
			type="text" 
			value="<?php echo JRequest::getVar('keyword'); ?>"
	/>
</div>

