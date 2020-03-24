<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_agosms
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\Component\Agosms\Administrator\Helper\AgosmHelper;
use Joomla\Component\Agosms\Site\Helper\Route as AgosmHelperRoute;
use Joomla\Component\Agosms\Site\Helper\RouteHelper;

HTMLHelper::_('behavior.core');
$canDo   = AgosmHelper::getActions('com_agosms', 'category', $this->category->id);
$canEdit = $canDo->get('core.edit');
$userId  = Factory::getUser()->id;
?>
<div class="com-agosm-category__items">
	<form action="<?php echo htmlspecialchars(Uri::getInstance()->toString()); ?>" method="post" name="adminForm" id="adminForm">
		<?php if ($this->params->get('filter_field') || $this->params->get('show_pagination_limit')) : ?>
		<fieldset class="com-agosm-category__filters filters btn-toolbar">
			<?php if ($this->params->get('filter_field')) : ?>
				<div class="com-agosm-category__filter btn-group">
					<label class="filter-search-lbl sr-only" for="filter-search">
						<span class="badge badge-warning">
							<?php echo Text::_('JUNPUBLISHED'); ?>
						</span>
						<?php echo Text::_('COM_AGOSM_FILTER_LABEL') . '&#160;'; ?>
					</label>
					<input
						type="text"
						name="filter-search"
						id="filter-search"
						value="<?php echo $this->escape($this->state->get('list.filter')); ?>"
						class="inputbox" onchange="document.adminForm.submit();"
						title="<?php echo Text::_('COM_AGOSM_FILTER_SEARCH_DESC'); ?>"
						placeholder="<?php echo Text::_('COM_AGOSM_FILTER_SEARCH_DESC'); ?>"
					>
				</div>
			<?php endif; ?>

			<?php if ($this->params->get('show_pagination_limit')) : ?>
				<div class="com-agosm-category__pagination btn-group float-right">
					<label for="limit" class="sr-only">
						<?php echo Text::_('JGLOBAL_DISPLAY_NUM'); ?>
					</label>
					<?php echo $this->pagination->getLimitBox(); ?>
				</div>
			<?php endif; ?>
		</fieldset>
		<?php endif; ?>
		<?php if (empty($this->items)) : ?>
			<p>
				<?php echo Text::_('COM_AGOSM_NO_AGOSMS'); ?>
			</p>
		<?php else : ?>

			<ul class="com-agosm-category__list category row-striped">
				<?php foreach ($this->items as $i => $item) : ?>

					<?php if (in_array($item->access, $this->user->getAuthorisedViewLevels())) : ?>
						<?php if ($this->items[$i]->published == 0) : ?>
							<li class="row system-unpublished cat-list-row<?php echo $i % 2; ?>">
						<?php else : ?>
							<li class="row cat-list-row<?php echo $i % 2; ?>" >
						<?php endif; ?>

						<?php if ($this->params->get('show_image_heading')) : ?>
							<?php $agosm_width = 7; ?>
							<div class="col-md-2">
								<?php if ($this->items[$i]->image) : ?>
									<a href="<?php echo Route::_(RouteHelper::getAgosmRoute($item->slug, $item->catid, $item->language)); ?>">
										<?php echo HTMLHelper::_('image', $this->items[$i]->image, Text::_('COM_AGOSM_IMAGE_DETAILS'), array('class' => 'agosm-thumbnail img-thumbnail')); ?></a>
								<?php endif; ?>
							</div>
						<?php else : ?>
							<?php $agosm_width = 9; ?>
						<?php endif; ?>

						<div class="list-title col-md-<?php echo $agosm_width; ?>">
							<a href="<?php echo Route::_(RouteHelper::getAgosmRoute($item->slug, $item->catid, $item->language)); ?>">
								<?php echo $item->name; ?></a>
							<?php if ($this->items[$i]->published == 0) : ?>
								<span class="badge badge-warning"><?php echo Text::_('JUNPUBLISHED'); ?></span>
							<?php endif; ?>
							<?php echo $item->event->afterDisplayTitle; ?>

							<?php echo $item->event->beforeDisplayContent; ?>

							<?php if ($this->params->get('show_position_headings')) : ?>
									<?php echo $item->con_position; ?><br>
							<?php endif; ?>
							<?php if ($this->params->get('show_email_headings')) : ?>
									<?php echo $item->email_to; ?><br>
							<?php endif; ?>
							<?php $location = array(); ?>
							<?php if ($this->params->get('show_suburb_headings') && !empty($item->suburb)) : ?>
								<?php $location[] = $item->suburb; ?>
							<?php endif; ?>
							<?php if ($this->params->get('show_state_headings') && !empty($item->state)) : ?>
								<?php $location[] = $item->state; ?>
							<?php endif; ?>
							<?php if ($this->params->get('show_country_headings') && !empty($item->country)) : ?>
								<?php $location[] = $item->country; ?>
							<?php endif; ?>
							<?php echo implode(', ', $location); ?>
						</div>

						<div class="col-md-3">
						</div>

						<?php echo $item->event->afterDisplayContent; ?>
					</li>
					<?php endif; ?>
				<?php endforeach; ?>
			</ul>
			<?php endif; ?>

			<?php if ($canDo->get('core.create')) : ?>
				<?php echo HTMLHelper::_('agosmicon.create', $this->category, $this->category->params); ?>
			<?php endif; ?>

			<?php if ($this->params->get('show_pagination', 2)) : ?>
			<div class="com-agosm-category__counter w-100">
				<?php if ($this->params->def('show_pagination_results', 1)) : ?>
					<p class="counter float-right pt-3 pr-2">
						<?php echo $this->pagination->getPagesCounter(); ?>
					</p>
				<?php endif; ?>

				<?php echo $this->pagination->getPagesLinks(); ?>
			</div>
			<?php endif; ?>
			<div>
				<input type="hidden" name="filter_order" value="<?php echo $this->escape($this->state->get('list.ordering')); ?>">
				<input type="hidden" name="filter_order_Dir" value="<?php echo $this->escape($this->state->get('list.direction')); ?>">
			</div>
	</form>
</div>
