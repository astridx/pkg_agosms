<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_agosms
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

\defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

HTMLHelper::_('behavior.core');

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));

// Create a shortcut for params.
$params = &$this->item->params;
?>

<div class="com-agosms-featured__items">
	<?php if (empty($this->items)) : ?>
		<p class="com-agosms-featured__message"> <?php echo Text::_('COM_AGOSM_NO_AGOSMS'); ?>	 </p>
	<?php else : ?>

	<form action="<?php echo htmlspecialchars(Uri::getInstance()->toString()); ?>" method="post" name="adminForm" id="adminForm">
		<table class="com-agosms-featured__table table">
			<?php if ($this->params->get('show_headings')) : ?>
			<thead class="thead-default">
				<tr>
					<th class="item-num">
						<?php echo Text::_('JGLOBAL_NUM'); ?>
					</th>

					<th class="item-title">
						<?php echo HTMLHelper::_('grid.sort', 'COM_AGOSM_AGOSM_EMAIL_NAME_LABEL', 'a.name', $listDirn, $listOrder); ?>
					</th>
				</tr>
			</thead>
			<?php endif; ?>

			<tbody>
				<?php foreach ($this->items as $i => $item) : ?>
					<tr class="<?php echo ($i % 2) ? 'odd' : 'even'; ?>" itemscope itemtype="https://schema.org/Person">
						<td class="item-num">
							<?php echo $i; ?>
						</td>

						<td class="item-title">
							<?php if ($this->items[$i]->published == 0) : ?>
								<span class="badge badge-warning"><?php echo Text::_('JUNPUBLISHED'); ?></span>
							<?php endif; ?>
								<span itemprop="name"><?php echo $item->name; ?></span>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>

	</form>
	<?php endif; ?>
</div>
