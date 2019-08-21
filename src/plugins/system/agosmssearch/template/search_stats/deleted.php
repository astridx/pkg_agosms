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

$mainframe = JFactory::getApplication();

// Check for template override
$override = JPATH_SITE . "/templates/{$mainframe->getTemplate()}/html/com_content/search_stats/deleted.php";
$file_path = __FILE__;

if (JFile::exists($override)
	&& strpos($file_path, "html") === false // Do not trigger in override file
)
{
	ob_start();
		require $override;
		$return = ob_get_contents();
	ob_end_clean();
	echo $return;

	return;
}

echo JText::_("Deleted");


