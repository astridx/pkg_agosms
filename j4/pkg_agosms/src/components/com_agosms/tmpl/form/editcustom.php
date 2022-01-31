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

<script>
window.addEventListener('load', (event) => {
    document.getElementById("divagosmsstep2").style.display = "none";
    document.getElementById("divagosmsstep3").style.display = "none";
});

function scrollToItem(item) {
    var diff=(item.offsetTop-window.scrollY)/8
    if (Math.abs(diff)>1) {
        window.scrollTo(0, (window.scrollY+diff))
        clearTimeout(window._TO)
        window._TO=setTimeout(scrollToItem, 30, item)
    } else {
        window.scrollTo(0, item.offsetTop)
    }
}

function step2() {
    document.getElementById("agosmsstep2").style.display = "none";
    document.getElementById("divagosmsstep2").style.display = "block";
	scrollToItem(document.getElementById("heading2"));
	//scrollToItem(document.getElementById("system-message-container));
}

function step3() {
    document.getElementById("agosmsstep3").style.display = "none";
    document.getElementById("divagosmsstep3").style.display = "block";
    scrollToItem(document.getElementById("heading3"))
}

function step4() {
    document.getElementById("agosmsstep3").style.display = "none";
    document.getElementById("divagosmsstep3").style.display = "block";
    scrollToItem(document.getElementById("heading3"))
}
</script>

<h2>Submit an Event</h2>

<form action="<?php echo Route::_('index.php?option=com_agosms&id=' . (int) $this->item->id); ?>" method="post"
    name="adminForm" id="adminForm" class="form-validate form-vertical">
    <div>

        <h2>Title and Description</h2>
        <?php echo Text::_('COM_AGOSMS_HINT_NAME'); ?>
        <?php echo $this->form->renderField('name'); ?>

        <?php echo $this->form->renderField('cusotm1'); ?>
        <?php echo $this->form->renderField('cusotm2'); ?>
        <?php echo $this->form->renderField('cusotm3'); ?>
        <?php echo $this->form->renderField('cusotm4'); ?>
        <?php echo $this->form->renderField('cusotm5'); ?>
        <?php echo $this->form->renderField('cusotm6'); ?>
        <?php echo $this->form->renderField('cusotm7'); ?>
        <?php echo $this->form->renderField('cusotm8'); ?>
        <?php echo $this->form->renderField('cusotm9'); ?>


        <div class="mb-3 mt-4">
            <button onclick="step2()" class="btn btn-primary w-100" id="agosmsstep2"><?php echo Text::_('Step 2'); ?>
                <span class="icon-chevron-right" aria-hidden="true"></span>
            </button>
        </div>
    </div>




    <div id="divagosmsstep2">
        <hr>
        <h2 id="heading2">Step 2 - Coordinate for Map</h2>
        <?php echo Text::_('COM_AGOSMS_HINT_COORDINATES'); ?>
        <?php echo $this->form->renderField('coordinates'); ?>


        <div class="mb-3 mt-4">
            <button onclick="step3()" class="btn btn-primary w-100" id="agosmsstep3"><?php echo Text::_('Step 3'); ?>
                <span class="icon-chevron-right" aria-hidden="true"></span>
            </button>
        </div>

    </div>

    <div id="divagosmsstep3">
        <hr>
        <h2 id="heading3">Step 3 - Futher informations</h2>
        <?php echo Text::_('COM_AGOSMS_HINT_CUSTOM'); ?>

        <?php echo Text::_('COM_AGOSMS_HINT_DESCRIPTION'); ?>
        <?php echo $this->form->renderField('description'); ?>

        <div style="display:none">
            <?php echo $this->form->renderField('popuptext'); ?>
            <?php echo LayoutHelper::render('joomla.edit.params', $this); ?>
        </div>

        <input type="hidden" name="task" value="" />
        <input type="hidden" name="return" value="<?php echo $this->return_page; ?>" />
        <?php echo HTMLHelper::_('form.token'); ?>


        <div class="mb-3 mt-4">
            <button type="button" class="btn btn-primary w-100" onclick="scrollToItem(document.getElementById('system-message-container'));Joomla.submitbutton('agosm.save')">
                <span class="fas fa-check" aria-hidden="true"></span>
                <?php echo Text::_('JSAVE'); ?>
            </button>
            <hr>
            <button type="button" class="btn btn-danger w-100" onclick="Joomla.submitbutton('agosm.cancel')">
                <span class="fas fa-times-cancel" aria-hidden="true"></span>
                <?php echo Text::_('JCANCEL'); ?>
            </button>
        </div>
    </div>
</form>
