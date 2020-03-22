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

$titles = $helper->getItemsTitles($params);
$active =  '';
if (JFactory::getApplication()->input->get->get('keyword')) {
	$active = JFactory::getApplication()->input->get->get('keyword');
}
?>

<div class="gsearch-field-select title">	
	<h3>
		<?php echo JText::_('MOD_AGOSMSSEARCHFILTER_TYPE_TITLE_SELECT'); ?>
	</h3>
	<select class="inputbox" name="keyword" style="display: none;">
		<option value=""><?php echo JText::_('MOD_AGOSMSSEARCHFILTER_TYPE_TITLE_SELECT'); ?></option>
		<?php foreach($titles as $title) { ?>
			<option <?php if($title == $active) { ?> selected="selected"<?php } ?>>
				<?php echo $title; ?>
			</option>
		<?php } ?>
	</select>
</div>

