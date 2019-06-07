<?php
/**
 * @package     Joomla.Site
 * @subpackage  pkg_agosms
 *
 * @copyright   Copyright (C) 2005 - 2019 Astrid Günther, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later;
 * @link        astrid-guenther.de
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

//get the module class designation
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

//set up the custom text
$labelText = $params->get('ag_label');
$buttonText = $params->get('ag_button');
$questionText = $params->get('ag_question');
$yesText = $params->get('ag_yes');
$noText = $params->get('ag_no');
if ($params->get('ag_custom') == 0)
{
	$labelText = Text::_('MOD_AG_LABEL_TEXT');
	$buttonText = Text::_('MOD_AG_BUTTON_TEXT');
	$questionText = Text::_('MOD_AG_QUESTION_TEXT');
	$yesText = Text::_('JYES');
	$noText = Text::_('JNO');
}

$action = Uri::current();

?>
<div class="<?php echo $moduleclass_sfx;?>">
	<?php 
	if (isset($_FILES[$params->get('ag_variable')])) : 
		?>
		<?php for ($j = 0; $j < count($result); $j++) : ?>
			<?php 
			$show_class = "";
			if ($result[$j]['show'] == false) : 
				$show_class = " efum-hide";
			endif; ?>
			<div class="efum-alert efum-<?php echo $result[$j]['type'].$show_class;?>">
			<span class="close-btn" onclick="this.parentNode.style.display = 'none';">&times;</span>
			<?php echo $result[$j]['text']; ?>
			</div>
		<?php endfor; ?>
	<?php endif; ?>
	<!-- Input form for the File Upload -->
	<form enctype="multipart/form-data" action="<?php echo $action; ?>" method="post">
		<?php if ($params->get('ag_multiple') == "1"): ?>
		<label for=<?php echo '"'.$params->get('ag_variable').'[]"'; ?>><?php echo $labelText; ?></label>
		<?php else: ?>
		<?php echo $labelText; ?><br />
		<?php endif; ?>
		<?php 
		$max = intval($params->get('ag_multiple'));
		for ($i = 0; $i < $max; $i++): ?>
		<input type="file" name=<?php echo '"'.$params->get('ag_variable').'[]"'; ?> id=<?php echo '"'.$params->get('ag_variable').'[]"'; ?> /> 
		<br />
		<?php endfor; ?>
		<?php if ($params->get('ag_default_replace') == false && $params->get('ag_replace') == true): /* 1 means 'Yes' or true. 0 means 'No' or false. */ ?>
		<div><?php echo $questionText; ?></div>
		<input type="radio" name="answer" value="1" /><?php echo $yesText; ?><br />
		<input type="radio" name="answer" value="0" checked /><?php echo $noText; ?><br />
		<br />
		<?php endif; ?>
		<input class="btn" type="submit" name="submit" value=<?php echo '"' . $buttonText . '"'; ?> />
	</form>
</div>