<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_advents
 *
 * @copyright   (C) 2021 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Layout\LayoutHelper;

$displayData = [
	'textPrefix' => 'COM_AGOSMS',
	'formURL' => 'index.php?option=com_agosms',
	'helpURL' => 'https://github.com/astridx/pkg_agosms/blob/master/j4/pkg_agosms/README.md',
	'icon' => 'icon-copy',
];

$user = Factory::getApplication()->getIdentity();

if ($user->authorise('core.create', 'com_agosms') || count($user->getAuthorisedCategories('com_agosms', 'core.create')) > 0) {
	$displayData['createURL'] = 'index.php?option=com_agosms&task=agosm.add';
}

echo LayoutHelper::render('joomla.content.emptystate', $displayData);
