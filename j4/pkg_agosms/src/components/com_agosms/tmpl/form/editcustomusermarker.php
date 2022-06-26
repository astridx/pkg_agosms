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
$wa->registerAndUseScript('roadsaftydate', 'com_agosms/admin-roadsaftydate.js', [], [], []);

$this->tab_name  = 'com-agosms-form';
$this->ignore_fieldsets = ['details', 'item_associations', 'language'];
$this->useCoreUI = true;

JText::script('PLG_AGOSMSADDRESSMARKER_ADDRESSE_ERROR');
?>

<style>
.modal-agosm {
    background-color: #fefefe;
}
</style>
<!-- Do not open additional data in media manager by default-->
<script>
document.addEventListener("DOMContentLoaded", function() {
    document.addEventListener('onMediaFileSelected', function() {
        const mediamore = document.querySelector('joomla-field-mediamore');
		const details = mediamore.querySelector('details');
		details.removeAttribute('open');
    });
});
</script>

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

            <div style="padding-top:100px;text-align:right"><button class="mb-2 btn btn-primary"
                    style="display: inline-block;" onclick="function do1(){
					var details = document.querySelector('#com-agosms-form');
					details.activateTab(1);
				};do1()" type="button">Next
                </button></div>
            <?php echo HTMLHelper::_('uitab.endTab'); ?>


            <?php echo HTMLHelper::_('uitab.addTab', $this->tab_name, 'details', empty($this->item->id) ? Text::_('COM_AGOSMS_DESC_AGOSM') : Text::_('COM_AGOSMS_DESC_AGOSM')); ?>
            <?php echo $this->form->renderField('description'); ?>
            <div style="padding-top:100px;text-align:right"><button class="mb-2 btn btn-primary"
                    style="display: inline-block;" onclick="function do2(){
						var details = document.querySelector('#com-agosms-form');
						details.activateTab(2);
					};do2()" type="button">Next
                </button></div>
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
            <div style="padding-top:100px;text-align:right">
                <button type="button" class="btn btn-primary" style="float:right"
                    onclick="window.scrollTo(0, 0);Joomla.submitbutton('agosm.save')">
                    <span class="icon-check" aria-hidden="true"></span>
                    <?php echo Text::_('JSUBMIT'); ?>
                </button>
                <button type="button" class="btn btn-danger" style="float:right"
                    onclick="Joomla.submitbutton('agosm.cancel')">
                    <span class="icon-times" aria-hidden="true"></span>
                    <?php echo Text::_('JCANCEL'); ?>
                </button>
            </div>
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
