<?php
/**
 * @package     Joomla.Site
 * @subpackage  pkg_agosms
 *
 * @copyright   Copyright (C) 2005 - 2019 Astrid Günther, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later;
 * @link        astrid-guenther.de
 */

defined('_JEXEC') or die;

require_once JPATH_COMPONENT . '/helpers/route.php';

$controller	= JControllerLegacy::getInstance('Agosms');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
