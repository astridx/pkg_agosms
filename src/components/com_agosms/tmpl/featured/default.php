<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_agosms
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

\defined('_JEXEC') or die;

?>
<div class="com-agosms-featured blog-featured">
<?php if ($this->params->get('show_page_headings') != 0 ) : ?>
	<h1>
		<?php echo $this->escape($this->params->get('page_heading')); ?>
	</h1>
<?php endif; ?>

<?php echo $this->loadTemplate('items'); ?>

<?php if ($this->params->def('show_pagination', 2) == 1  || ($this->params->get('show_pagination') == 2 && $this->pagination->pagesTotal > 1)) : ?>
	<div class="com-agosms-featured__pagination w-100">
		<?php if ($this->params->def('show_pagination_results', 1)) : ?>
			<p class="counter float-right pt-3 pr-2">
				<?php echo $this->pagination->getPagesCounter(); ?>
			</p>
		<?php endif; ?>

		<?php echo $this->pagination->getPagesLinks(); ?>
	</div>
<?php endif; ?>
</div>
