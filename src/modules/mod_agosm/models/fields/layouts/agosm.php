<?php
/**
 * @package     Joomla.Site
 * @subpackage  pkg_agosms
 *
 * @copyright   Copyright (C) 2005 - 2019 Astrid Günther, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later;
 * @link        astrid-guenther.de
 */

defined('JPATH_BASE') or die;

extract($displayData);

// Load the modal behavior script.
JHtml::_('behavior.modal');

// Include jQuery
//JHtml::_('script', 'media/com_agosms/js/admin-agosms-button-default.js');

// Tooltip for INPUT showing whole image path
$options = array(
	'onShow' => 'jMediaRefreshImgpathTip',
);

JHtml::_('behavior.tooltip', '.hasTipImgpath', $options);

if (!empty($class))
{
	$class .= ' hasTipImgpath';
}
else
{
	$class = 'hasTipImgpath';
}

$attr = '';

$attr .= ' title="' . htmlspecialchars('<span id="TipImgpath"></span>', ENT_COMPAT, 'UTF-8') . '"';

// Initialize some field attributes.
$attr .= !empty($class) ? ' class="input-small field-media-input ' . $class . '"' : ' class="input-small"';
$attr .= !empty($size) ? ' size="' . $size . '"' : '';

// Initialize JavaScript field attributes.
$attr .= !empty($onchange) ? ' onchange="' . $onchange . '"' : '';

// The text field.
echo '<div class="input-prepend input-append">';

echo '	<input type="text" name="' . $name . '" id="' . $id . '" value="'
	. htmlspecialchars($value, ENT_COMPAT, 'UTF-8') . '" readonly="readonly"' . $attr . ' data-basepath="'
	. JUri::root() . '"/>';

?>
<a class="modal btn" title="<?php echo JText::_('JLIB_FORM_BUTTON_SELECT'); ?>" href="
<?php echo ($readonly ? ''
		: ($link ?: 'index.php?option=com_agosms&amp;'
		. 'view=button&amp;'
		. 'tmpl=component&amp;'
		. 'asset=' . $asset . '&amp;author=' . $authorField) . '&amp;'
		. 'fieldid=' . $id . '&amp;'
		. 'folder=' . $folder) . '"'
	. ' rel="{handler: \'iframe\', size: {x: 400, y: 500}}"'; ?>>
 <?php echo JText::_('JLIB_FORM_BUTTON_SELECT'); ?></a>

 <a
 class="btn hasTooltip"
 title="<?php echo JText::_('JLIB_FORM_BUTTON_CLEAR'); ?>"
 href="#" onclick="jInsertFieldValue('', '<?php echo $id; ?>'); return false;">
<span class="icon-remove" aria-hidden="true"></span>
</a>


</div>
