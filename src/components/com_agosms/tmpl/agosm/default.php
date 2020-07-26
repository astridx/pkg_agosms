<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_agosms
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
\defined('_JEXEC') or die;
?>

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ContentHelper;
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
		<div class="btn-group float-right">
			<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton-<?php echo $this->item->id; ?>"
				aria-label="<?php echo JText::_('JUSER_TOOLS'); ?>"
				data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<span class="fa fa-cog" aria-hidden="true"></span>
			</button>
			<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton-<?php echo $this->item->id; ?>">
				<li class="edit-icon"> <?php echo JHtml::_('agosmicon.edit', $this->item, $tparams); ?> </li>
			</ul>
		</div>
	</div>
<?php endif; ?>

<?php
echo $this->item->event->afterDisplayTitle; 
echo $this->item->event->beforeDisplayContent;
echo $this->item->event->afterDisplayContent;
