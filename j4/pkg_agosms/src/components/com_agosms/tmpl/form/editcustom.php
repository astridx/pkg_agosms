<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_agosms
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Associations;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;

HTMLHelper::_('behavior.keepalive');
HTMLHelper::_('behavior.formvalidator');
HTMLHelper::_('script', 'com_agosms/admin-agosms-letter.js', ['version' => 'auto', 'relative' => true]);

$this->tab_name  = 'com-agosms-form';
$this->ignore_fieldsets = ['details', 'item_associations', 'language'];
$this->useCoreUI = true;
?>
<form action="<?php echo Route::_('index.php?option=com_agosms&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" class="form-validate form-vertical">
	<fieldset>
		<?php echo HTMLHelper::_('uitab.startTabSet', $this->tab_name, ['active' => 'details']); ?>
		<?php echo HTMLHelper::_('uitab.addTab', $this->tab_name, 'details', empty($this->item->id) ? Text::_('COM_AGOSMS_NEW_AGOSM') : Text::_('COM_AGOSMS_EDIT_AGOSM')); ?>

		<?php echo Text::_('COM_AGOSMS_HINT_NAME'); ?>
		<?php echo $this->form->renderField('name'); ?>

		<?php echo Text::_('COM_AGOSMS_HINT_DESCRIPTION'); ?>
		<?php echo $this->form->renderField('description'); ?>

		<?php echo Text::_('COM_AGOSMS_HINT_COORDINATES'); ?>
		<?php echo $this->form->renderField('coordinates'); ?>

		<?php if (is_null($this->item->id)) : ?>
			<?php echo $this->form->renderField('alias'); ?>
		<?php endif; ?>

		<?php echo Text::_('COM_AGOSMS_HINT_CUSTOM'); ?>

		<?php echo $this->form->renderField('cusotm1'); ?>
		<?php echo $this->form->renderField('cusotm2'); ?>
		<?php echo $this->form->renderField('cusotm3'); ?>
		<?php echo $this->form->renderField('cusotm4'); ?>
		<?php echo $this->form->renderField('cusotm5'); ?>
		<?php echo $this->form->renderField('cusotm6'); ?>
		<?php echo $this->form->renderField('cusotm7'); ?>
		<?php echo $this->form->renderField('cusotm8'); ?>
		<?php echo $this->form->renderField('cusotm9'); ?>



		<?php echo HTMLHelper::_('uitab.endTab'); ?>
				
		<div style="display:none">
		<?php echo $this->form->renderField('popuptext'); ?>
		<?php echo LayoutHelper::render('joomla.edit.params', $this); ?>
		</div  z>
		<?php echo HTMLHelper::_('uitab.endTabSet'); ?>

		<input type="hidden" name="task" value=""/>
		<input type="hidden" name="return" value="<?php echo $this->return_page; ?>"/>
		<?php echo HTMLHelper::_('form.token'); ?>
	</fieldset>
	<div class="mb-2">
		<button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('agosm.save')">
			<span class="fas fa-check" aria-hidden="true"></span>
			<?php echo Text::_('JSAVE'); ?>
		</button>
		<button type="button" class="btn btn-danger" onclick="Joomla.submitbutton('agosm.cancel')">
			<span class="fas fa-times-cancel" aria-hidden="true"></span>
			<?php echo Text::_('JCANCEL'); ?>
		</button>
	</div>
</form>
