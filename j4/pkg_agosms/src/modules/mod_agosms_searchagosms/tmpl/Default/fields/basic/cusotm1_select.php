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

$cusotm1s = $helper->getItemsCusotm($params, "1");
$active =  '';
if (JFactory::getApplication()->input->get->get('cusotm1')) {
	$active = JFactory::getApplication()->input->get->get('cusotm1');
}
?>

<div class="gsearch-field-select cusotm1">	
	<h3>
		<?php echo JText::_('MOD_AGOSMSSEARCHAGOSMSFILTER_TYPE_CUSTOM1_SELECT'); ?>
	</h3>
	<select class="inputbox" name="cusotm1" style="display: none;">
		<option value=""><?php echo JText::_('MOD_AGOSMSSEARCHAGOSMSFILTER_TYPE_CUSTOM1_SELECT'); ?></option>
		<?php foreach ($cusotm1s as $cusotm1) { ?>
			<option <?php if ($cusotm1 == $active) {
				?> selected="selected"<?php
					} ?>>
				<?php echo $cusotm1; ?>
			</option>
		<?php } ?>
	</select>
</div>

