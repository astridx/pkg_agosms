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

// Include skripts/styles to the header
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root(true) . '/media/mod_agosm/leaflet/leaflet.css');
$document->addScript(JURI::root(true) . '/media/mod_agosm/leaflet/leaflet.js');
$document->addScript(JURI::root(true) . '/media/com_agosms/js/admin-agosms-button-default.js');
$uniqid = uniqid();
$input  = JFactory::getApplication()->input;
JText::script('COM_AGOSMS_BUTTON_DEFAULT_POPUP_PROMPT');
?>
<div style="
	 width:100%;
	 height:300px;"
	 id="mapid<?php echo $uniqid; ?>">
</div>
<input
	name="jform_paramsmodal_cords_map"
	id="jform_paramsmodal_cords_map"
    data-unique="<?php echo $uniqid; ?>"
	value=""
	readonly="readonly"
	title=""
	style="margin-top:1%;width:98%;padding:1%;"
	class=""
	type="text"
	>
<br>
<button
	class="btn btn-success button-save-selected"
	id="buttonsaveselected"
	type="button"
	data-dismiss="modal">
	<?php echo JText::_('COM_AGOSM_INSERT'); ?>
</button>
<button
	class="btn button-cancel"
	type="button"
	onclick="window.parent.jModalClose();"
	data-dismiss="modal">
	<?php echo JText::_('JCANCEL'); ?>
</button>

