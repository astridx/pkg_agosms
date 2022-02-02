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

HTMLHelper::_('behavior.keepalive');
HTMLHelper::_('behavior.formvalidator');

$this->tab_name  = 'com-agosms-form';
$this->ignore_fieldsets = ['details', 'item_associations', 'language'];
$this->useCoreUI = true;
?>
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
        <fieldset>

            <?php echo $this->form->renderField('name'); ?>

            <?php echo HTMLHelper::_('uitab.startTabSet', $this->tab_name, ['active' => 'details', 'recall' => true, 'breakpoint' => 768]); ?>


            <?php echo HTMLHelper::_('uitab.addTab', $this->tab_name, 'cords', Text::_('COM_AGOSMS_FIELDSET_COORDS')); ?>
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#cordsModal">
                <?php echo Text::_('COM_AGOSMS_HINTS_AGOSM'); ?>
            </button>

            <!-- Modal -->
            <div class="modal fade" id="cordsModal" tabindex="-1" aria-labelledby="cordsModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-agosm">
                        <div class="modal-header">
                            <h5 class="modal-title" id="cordsModalLabel">
                                <?php echo Text::_('COM_AGOSMS_FIELDSET_COORDS'); ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <?php echo Text::_('COM_AGOSMS_HINT_COORDINATES'); ?>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php echo $this->form->renderField('coordinates'); ?>
            <?php echo HTMLHelper::_('uitab.endTab'); ?>


            <?php echo HTMLHelper::_('uitab.addTab', $this->tab_name, 'details', empty($this->item->id) ? Text::_('COM_AGOSMS_DESC_AGOSM') : Text::_('COM_AGOSMS_EDIT_AGOSM')); ?>
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#descModal">
                <?php echo Text::_('COM_AGOSMS_HINTS_AGOSM'); ?>
            </button>

            <!-- Modal -->
            <div class="modal fade" id="descModal" tabindex="-1" aria-labelledby="descModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-agosm">
                        <div class="modal-header">
                            <h5 class="modal-title" id="descModalLabel"><?php echo Text::_('COM_AGOSMS_DESC_AGOSM'); ?>
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <?php echo Text::_('COM_AGOSMS_HINT_DESCRIPTION'); ?>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php echo $this->form->renderField('description'); ?>
            <?php echo HTMLHelper::_('uitab.endTab'); ?>

            <?php echo HTMLHelper::_('uitab.addTab', $this->tab_name, 'custom', Text::_('COM_AGOSMS_FIELDSET_CUSTOM')); ?>
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#detailModal">
                <?php echo Text::_('COM_AGOSMS_HINTS_AGOSM'); ?>
            </button>

            <!-- Modal -->
            <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-agosm">
                        <div class="modal-header">
                            <h5 class="modal-title" id="detailModalLabel">Further details on the event</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <?php echo Text::_('COM_AGOSMS_HINT_CUSTOM'); ?>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
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

            <div style="display:none">
                <?php if (Multilanguage::isEnabled()) : ?>
                <?php echo HTMLHelper::_('uitab.addTab', $this->tab_name, 'language', Text::_('JFIELD_LANGUAGE_LABEL')); ?>
                <?php echo $this->form->renderField('language'); ?>
                <?php echo HTMLHelper::_('uitab.endTab'); ?>
                <?php else: ?>
                <?php echo $this->form->renderField('language'); ?>
                <?php endif; ?>
            </div>
            <div style="display:none">
                <?php echo $this->form->renderField('catid'); ?>
				<?php echo $this->form->renderField('published'); ?>
                <?php echo $this->form->renderField('featured'); ?>
                <?php echo $this->form->renderField('popuptext'); ?>
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
        <div class="mb-2">
            <button type="button" class="btn btn-primary"
                onclick="window.scrollTo(0, 0);Joomla.submitbutton('agosm.save')">
                <span class="icon-check" aria-hidden="true"></span>
                <?php echo Text::_('JSAVE'); ?>
            </button>
            <button type="button" class="btn btn-danger" onclick="Joomla.submitbutton('agosm.cancel')">
                <span class="icon-times" aria-hidden="true"></span>
                <?php echo Text::_('JCANCEL'); ?>
            </button>
        </div>
    </form>
</div>