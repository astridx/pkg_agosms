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

$cusotm2s = $helper->getItemsCusotm($params, "2");
$active =  '';
if (JFactory::getApplication()->input->get->get('cusotm2')) {
	$active = JFactory::getApplication()->input->get->get('cusotm2');
}
?>

<div class="gsearch-field-select cusotm2">	
	<h3>
		<?php echo JText::_('MOD_AGOSMSSEARCHAGOSMSFILTER_TYPE_OF_EVENT'); ?>
	</h3>
	<select class="inputbox" name="cusotm2" style="display: none;">
		<option value=""><?php echo JText::_('MOD_AGOSMSSEARCHAGOSMSFILTER_TYPE_OF_EVENT_PLACEHOLDER'); ?></option>
		<?php foreach ($cusotm2s as $cusotm2) { ?>
			<option <?php if ($cusotm2 == $active) {
				?> selected="selected"<?php
					} ?>>
				<?php echo $cusotm2; ?>
			</option>
		<?php } ?>
	</select>
</div>

