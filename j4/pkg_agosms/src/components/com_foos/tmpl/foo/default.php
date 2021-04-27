<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_agosms
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

$canDo   = ContentHelper::getActions('com_agosms', 'category', $this->item->catid);
$canEdit = $canDo->get('core.edit') || ($canDo->get('core.edit.own') && $this->item->created_by == Factory::getUser()->id);
$tparams = $this->item->params;

if ($tparams->get('show_name')) {
	if ($this->Params->get('show_agosm_name_label')) {
		echo Text::_('COM_AGOSMS_NAME');
	}

	echo $this->item->name;
}
?>

<?php if ($canEdit) : ?>
	<div class="icons">
		<div class="float-end">
			<div>
				<?php echo HTMLHelper::_('agosmicon.edit', $this->item, $tparams); ?>
			</div>
		</div>
	</div>
<?php endif; ?>

<?php
echo $this->item->event->afterDisplayTitle;
echo $this->item->event->beforeDisplayContent;
echo $this->item->event->afterDisplayContent;
