<?php

/**
 * @package     Articles Good Search
 *
 * @copyright   Copyright (C) 2017 Joomcar extensions. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

?>

<div class="gsearch-field-text custom-field">	
	<h3>
		<?php echo JText::_("{$field->instance->label}"); ?>
	</h3>
	<input class="inputbox" name="field<?php echo $field->id; ?>" placeholder="<?php echo JText::_("{$field->instance->label}"); ?>" value="<?php echo JRequest::getVar("field".$field->id); ?>" />
</div>

