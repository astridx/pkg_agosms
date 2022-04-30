<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_agosms
 *
 * @copyright   (C) 2020 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->document->getWebAssetManager();
$wa->useScript('keepalive')
	->useScript('form.validate')
	->useScript('inlinehelp');

$this->tab_name  = 'com-agosms-form';
$this->ignore_fieldsets = ['details', 'item_associations', 'language'];
$this->useCoreUI = true;
?>

<style>
.modal-agosm {
	background-color: #fefefe;
}
</style>

<div class="edit item-page<?php echo $this->pageclass_sfx; ?>">
	<?php if ($this->params->get('show_page_heading')) : ?>
	<div class="page-header">
		<h1>
			<?php echo $this->escape($this->params->get('page_heading')); ?>
		</h1>
	</div>
	<?php endif; ?>



	<form action="<?php echo Route::_('index.php?option=com_agosms&id=' . (int) $this->item->id); ?>" method="post"
		name="adminForm" id="adminForm" class="form-validate form-vertical">
		<div class="mb-2 d-flex">
			<button type="button" class="btn btn-sm btn-outline-info button-inlinehelp ms-auto">
				<span class="fa fa-question-circle" aria-hidden="true"></span>
				<?php echo Text::_('JINLINEHELP') ?>
			</button>
		</div>

		<fieldset>

			<?php echo $this->form->renderField('name'); ?>

			<?php echo HTMLHelper::_('uitab.startTabSet', $this->tab_name, ['active' => 'cords', 'breakpoint' => 768]); ?>


			<?php echo HTMLHelper::_('uitab.addTab', $this->tab_name, 'cords', Text::_('COM_AGOSMS_FIELDSET_COORDS')); ?>
			<?php echo $this->form->renderField('coordinates'); ?>
			<?php echo HTMLHelper::_('uitab.endTab'); ?>


			<?php echo HTMLHelper::_('uitab.addTab', $this->tab_name, 'details', empty($this->item->id) ? Text::_('COM_AGOSMS_DESC_AGOSM') : Text::_('COM_AGOSMS_EDIT_AGOSM')); ?>
			<?php echo $this->form->renderField('description'); ?>
			<?php echo HTMLHelper::_('uitab.endTab'); ?>

			<?php echo HTMLHelper::_('uitab.addTab', $this->tab_name, 'custom', Text::_('COM_AGOSMS_FIELDSET_CUSTOM')); ?>

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


			<?php if (is_null($this->item->id)) : ?>
				<?php echo $this->form->renderField('alias'); ?>
			<?php endif; ?>

			<?php echo $this->form->renderFieldset('details'); ?>
			<?php echo HTMLHelper::_('uitab.endTab'); ?>

			<div style="display:none">
				<?php echo HTMLHelper::_('uitab.addTab', $this->tab_name, 'misc', Text::_('COM_AGOSMS_FIELDSET_MISCELLANEOUS')); ?>
				<?php echo $this->form->getInput('misc'); ?>
				<?php echo HTMLHelper::_('uitab.endTab'); ?>
			</div>

			<?php echo HTMLHelper::_('uitab.addTab', $this->tab_name, 'end', '<span onclick="showCurrent()">' . Text::_('COM_AGOSMS_FIELDSET_END') . '</span>'); ?>
			<script>
				function showCurrent(){
					document.getElementById("agosmsdescription").innerHTML = document.getElementById("jform_description").value;
					document.getElementById("agosmscusotm1").innerHTML = document.getElementById("jform_agosmscusotm1").value;
					document.getElementById("agosmscusotm2").innerHTML = document.getElementById("jform_agosmscusotm2").value;
					document.getElementById("agosmscusotm3").innerHTML = document.getElementById("jform_agosmscusotm3").value;
					document.getElementById("agosmscusotm4").innerHTML = document.getElementById("jform_agosmscusotm4").value;
					document.getElementById("agosmscusotm5").innerHTML = document.getElementById("jform_agosmscusotm5").value;
					document.getElementById("agosmscusotm6").innerHTML = document.getElementById("jform_agosmscusotm6").value;
					document.getElementById("agosmscusotm7").innerHTML = document.getElementById("jform_agosmscusotm7").value;
					document.getElementById("agosmscusotm8").innerHTML = document.getElementById("jform_agosmscusotm8").value;
					document.getElementById("agosmscusotm9").innerHTML = '<img src="' + document.getElementById("jform_agosmscusotm9").value + '"\>';
				};				
				
			</script>
			<div class="mb-2">
				<table class="table table table-striped table-sm table-bordered">
					<tbody>
						<tr>
							<td><?php echo Text::_('COM_AGOSMS_FIELD_NAME_LABEL'); ?></td>
							<td><?php echo $this->item->name; ?></td>
						</tr>
						<tr>
							<td><?php echo Text::_('COM_AGOSMS_FIELD_DESCRIPTION_LABEL'); ?></td>
							<td><span id="agosmsdescription"></span></td>
						</tr>
						<tr>
							<td><?php echo Text::_('JFIELD_LANGUAGE_LABEL'); ?></td>
							<td><span id="agosmscusotm1"></span></td>
						</tr>
						<tr>
							<td><?php echo Text::_('COM_AGOSMS_FIELD_CUSTOM_TYPEPFEVENT_LABEL'); ?></td>
							<td><span id="agosmscusotm2"></span></td>
						</tr>
						<tr>
							<td><?php echo Text::_('COM_AGOSMS_FIELD_CUSTOM_VALUE9_DATE_LABEL'); ?></td>
							<td><span id="agosmscusotm3"></span></td>
						</tr>
						<tr>
							<td><?php echo Text::_('COM_AGOSMS_FIELD_CUSTOM_VALUE9_ORGANISATION_LABEL'); ?></td>
							<td><span id="agosmscusotm4"></span></td>
						</tr>
						<tr>
							<td><?php echo Text::_('COM_CONTACT_FIELD_INFORMATION_WEBPAGE_LABEL'); ?></td>
							<td><span id="agosmscusotm5"></span></td>
						</tr>
						<tr>
							<td><?php echo Text::_('JGLOBAL_EMAIL'); ?></td>
							<td><span id="agosmscusotm6"></span></td>
						</tr>
						<tr>
							<td><?php echo Text::_('COM_CONTACT_FIELD_INFORMATION_TELEPHONE_LABEL'); ?></td>
							<td><span id="agosmscusotm7"></span></td>
						</tr>
						<tr>
							<td><?php echo Text::_('COM_CONTACT_FIELD_INFORMATION_ADDRESS_LABEL'); ?></td>
							<td><span id="agosmscusotm8"></span></td>
						</tr>
						<tr>
							<td><?php echo Text::_('COM_AGOSMS_FIELD_CUSTOM_VALUE9_LOGO_LABEL'); ?></td>
							<td><span id="agosmscusotm9"></span></td>
						</tr>
					</tbody>
				</table>






				<button type="button" class="btn btn-primary"
					onclick="window.scrollTo(0, 0);Joomla.submitbutton('agosm.save')">
					<span class="icon-check" aria-hidden="true"></span>
					<?php echo Text::_('JSUBMIT'); ?>
				</button>
				<button type="button" class="btn btn-danger" onclick="Joomla.submitbutton('agosm.cancel')">
					<span class="icon-times" aria-hidden="true"></span>
					<?php echo Text::_('JCANCEL'); ?>
				</button>
			</div>

			<?php echo HTMLHelper::_('uitab.endTab'); ?>

			<div style="display:none">
				<?php if (Multilanguage::isEnabled()) : ?>
					<?php echo HTMLHelper::_('uitab.addTab', $this->tab_name, 'language', Text::_('JFIELD_LANGUAGE_LABEL')); ?>
					<?php echo $this->form->renderField('language'); ?>
					<?php echo HTMLHelper::_('uitab.endTab'); ?>
				<?php else : ?>
					<?php echo $this->form->renderField('language'); ?>
				<?php endif; ?>
			</div>
			<div style="display:none">
				<?php echo $this->form->renderField('catid'); ?>
				<?php echo $this->form->renderField('published'); ?>
				<?php echo $this->form->renderField('featured'); ?>
				<?php echo $this->form->renderField('popuptext'); ?>
				<?php echo $this->form->renderField('showpopup'); ?>
				<?php echo LayoutHelper::render('joomla.edit.params', $this); ?>
			</div>

			<div style="display:none">
				<?php echo LayoutHelper::render('joomla.edit.params', $this); ?>
			</div>
			<?php echo HTMLHelper::_('uitab.endTabSet'); ?>

			<input type="hidden" name="task" value="" />
			<input type="hidden" name="return" value="<?php echo $this->return_page; ?>" />
			<?php echo HTMLHelper::_('form.token'); ?>
		</fieldset>
	</form>
</div>