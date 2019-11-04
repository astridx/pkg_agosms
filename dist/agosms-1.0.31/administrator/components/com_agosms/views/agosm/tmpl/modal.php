<?php
/**
 * @package     Joomla.Site
 * @subpackage  pkg_agosm
 *
 * @copyright   Copyright (C) 2005 - 2018 Astrid Günther, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later;
 * @link        astrid-guenther.de
 */
defined('_JEXEC') or die;
JHtml::_('bootstrap.tooltip', '.hasTooltip', array('placement' => 'bottom'));

// @deprecated 4.0 the function parameter, the inline js and the buttons are not needed since 3.7.0.
$function  = JFactory::getApplication()->input->getCmd('function', 'jEditAgosm_' . (int) $this->item->id);

// Function to update input title when changed
JFactory::getDocument()->addScriptDeclaration('
	function jEditAgosmModal() {
		if (window.parent && document.formvalidator.isValid(document.getElementById("agosm-form"))) {
			return window.parent.' . $this->escape($function) . '(document.getElementById("jform_title").value);
		}
	}
');
?>
<button id="applyBtn" type="button" class="hidden" onclick="Joomla.submitbutton('agosm.apply'); jEditAgosmModal();"></button>
<button id="saveBtn" type="button" class="hidden" onclick="Joomla.submitbutton('agosm.save'); jEditAgosmModal();"></button>
<button id="closeBtn" type="button" class="hidden" onclick="Joomla.submitbutton('agosm.cancel');"></button>

<div class="container-popup">
	<?php $this->setLayout('edit'); ?>
	<?php echo $this->loadTemplate(); ?>
</div>
