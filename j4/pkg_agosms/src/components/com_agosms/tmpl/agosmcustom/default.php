<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_agosms
 *
 * @copyright   (C) 2006 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\FileLayout;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Router\Route;
use Joomla\Component\Contact\Site\Helper\RouteHelper;
use Joomla\Registry\Registry;

$tparams = $this->item->params;
$this->params = new Registry(json_decode($this->item->params));
$canDo = ContentHelper::getActions('com_agosms', 'category', $this->item->catid);
$canEdit = $canDo->get('core.edit') || ($canDo->get('core.edit.own') && $this->item->created_by === Factory::getUser()->id);
$htag = $tparams->get('show_page_heading') ? 'h2' : 'h1';
$cords = explode(',', $this->item->coordinates);

$document = JFactory::getDocument();

$leafletIsLoaded = false;

foreach ($document->_scripts as $key => $script) {
	$leafletPath = "leaflet/leaflet.js";

	if (strpos($key, $leafletPath)) {
		$leafletIsLoaded = true;
	}
}

if (!$leafletIsLoaded) {
	$document->addStyleSheet(JURI::root(true) . '/media/mod_agosm/leaflet/leaflet.css');
	$document->addScript(JURI::root(true) . '/media/mod_agosm/leaflet/leaflet.js');
}
?>

<div class="com-agosm agosm">
	<?php if ($tparams->get('show_page_heading')) : ?>
	<h1>
		<?php echo $this->escape($tparams->get('page_heading')); ?>
	</h1>
	<?php endif; ?>

	<?php if ($this->item->name && $tparams->get('show_name')) : ?>
	<div class="page-header">
		<<?php echo $htag; ?>>
		<?php if ($this->item->published == 0) : ?>
		<span class="badge bg-warning text-light"><?php echo Text::_('JUNPUBLISHED'); ?></span>
		<?php endif; ?>
		<span class="agosm-name"><?php echo $this->item->name; ?></span>
		</<?php echo $htag; ?>>
	</div>
	<?php endif; ?>

	<?php if ($canEdit) : ?>
	<div class="icons">
		<div class="float-end">
			<div>
				<?php echo HTMLHelper::_('agosmiconcustom.edit', $this->item, $tparams); ?>
			</div>
		</div>
	</div>
	<?php endif; ?>

	<?php echo $this->item->event->afterDisplayTitle; ?>

	<?php echo $this->item->event->beforeDisplayContent; ?>



	<?php echo $this->item->description; ?>




	<script>
	document.addEventListener('DOMContentLoaded', function() {
		var map = new L.Map(<?php echo 'map' . $this->item->id; ?>, {
			scrollWheelZoom: false,
			worldCopyJump: false,
			maxBounds: [
				[82, -180],
				[-82, 180]
			]
		}).setView([<?php echo $cords[0]; ?>, <?php echo $cords[1]; ?>], 12);

		var OpenStreetMap_DE = L.tileLayer(
			'https://{s}.tile.openstreetmap.de/tiles/osmde/{z}/{x}/{y}.png', {
				maxZoom: 18,
				attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
			}).addTo(map);

		var marker = L.marker([<?php echo $cords[0]; ?>, <?php echo $cords[1]; ?>])
			.addTo(map);

	}, false);
	</script>
	<small><?php echo 'Lat ' . $cords[0]; ?> , <?php echo 'Long ' . $cords[1]; ?></small>
	<div style="height: 180px;z-index:1" class="leafletmapComp" id="map<?php echo $this->item->id; ?>">
	</div>









	<table class="table table table-striped table-sm table-bordered">
		<tbody>
			<tr>
				<td><?php echo Text::_('COM_AGOSMS_FIELD_CUSTOM_VALUE1_LABEL'); ?></td>
				<td><?php echo $this->item->cusotm1; ?></td>
			</tr>
			<tr>
				<td><?php echo Text::_('COM_AGOSMS_FIELD_CUSTOM_VALUE2_LABEL'); ?></td>
				<td><?php echo $this->item->cusotm2; ?></td>
			</tr>
			<tr>
				<td><?php echo Text::_('COM_AGOSMS_FIELD_CUSTOM_VALUE3_LABEL'); ?></td>
				<td><?php echo $this->item->cusotm1; ?></td>
			</tr>
			<tr>
				<td><?php echo Text::_('COM_AGOSMS_FIELD_CUSTOM_VALUE4_LABEL'); ?></td>
				<td><?php echo $this->item->cusotm2; ?></td>
			</tr>
			<tr>
				<td><?php echo Text::_('COM_AGOSMS_FIELD_CUSTOM_VALUE5_LABEL'); ?></td>
				<td><?php echo $this->item->cusotm1; ?></td>
			</tr>
			<tr>
				<td><?php echo Text::_('COM_AGOSMS_FIELD_CUSTOM_VALUE6_LABEL'); ?></td>
				<td><?php echo $this->item->cusotm2; ?></td>
			</tr>
			<tr>
				<td><?php echo Text::_('COM_AGOSMS_FIELD_CUSTOM_VALUE7_LABEL'); ?></td>
				<td><?php echo $this->item->cusotm1; ?></td>
			</tr>
			<tr>
				<td><?php echo Text::_('COM_AGOSMS_FIELD_CUSTOM_VALUE8_LABEL'); ?></td>
				<td><?php echo $this->item->cusotm2; ?></td>
			</tr>
			<tr>
				<td><?php echo Text::_('COM_AGOSMS_FIELD_CUSTOM_VALUE9_LABEL'); ?></td>
				<td><?php echo $this->item->cusotm1; ?></td>
			</tr>
			<tr>
				<td><?php echo Text::_('COM_AGOSMS_FIELD_CUSTOM_VALUE10_LABEL'); ?></td>
				<td><?php echo $this->item->cusotm2; ?></td>
			</tr>
			<tr>
				<td><?php echo Text::_('COM_AGOSMS_FIELD_CUSTOM_VALUE11_LABEL'); ?></td>
				<td><?php echo $this->item->cusotm1; ?></td>
			</tr>
			<tr>
				<td><?php echo Text::_('COM_AGOSMS_FIELD_CUSTOM_VALUE12_LABEL'); ?></td>
				<td><?php echo $this->item->cusotm2; ?></td>
			</tr>
			<tr>
				<td><?php echo Text::_('COM_AGOSMS_FIELD_CUSTOM_VALUE13_LABEL'); ?></td>
				<td><?php echo $this->item->cusotm1; ?></td>
			</tr>
			<tr>
				<td><?php echo Text::_('COM_AGOSMS_FIELD_CUSTOM_VALUE14_LABEL'); ?></td>
				<td><?php echo $this->item->cusotm2; ?></td>
			</tr>
			<tr>
				<td><?php echo Text::_('COM_AGOSMS_FIELD_CUSTOM_VALUE15_LABEL'); ?></td>
				<td><?php echo $this->item->cusotm1; ?></td>
			</tr>
			<tr>
				<td><?php echo Text::_('COM_AGOSMS_FIELD_CUSTOM_VALUE16_LABEL'); ?></td>
				<td><?php echo $this->item->cusotm2; ?></td>
			</tr>
			<tr>
				<td><?php echo Text::_('COM_AGOSMS_FIELD_CUSTOM_VALUE17_LABEL'); ?></td>
				<td><?php echo $this->item->cusotm1; ?></td>
			</tr>
			<tr>
				<td><?php echo Text::_('COM_AGOSMS_FIELD_CUSTOM_VALUE18_LABEL'); ?></td>
				<td><?php echo $this->item->cusotm2; ?></td>
			</tr>
			<tr>
				<td><?php echo Text::_('COM_AGOSMS_FIELD_CUSTOM_VALUE19_LABEL'); ?></td>
				<td><?php echo $this->item->cusotm1; ?></td>
			</tr>
			<tr>
				<td><?php echo Text::_('COM_AGOSMS_FIELD_CUSTOM_VALUE20_LABEL'); ?></td>
				<td><?php echo $this->item->cusotm2; ?></td>
			</tr>
		</tbody>
	</table>


	<?php echo $this->item->event->afterDisplayContent; ?>
</div>
