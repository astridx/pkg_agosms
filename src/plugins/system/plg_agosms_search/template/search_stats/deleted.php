<?php

/**
 * @package     Articles Good Search
 *
 * @copyright   Copyright (C) 2017 Joomcar extensions. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

defined('_JEXEC') or die;

$mainframe = JFactory::getApplication();
//check for template override
$override = JPATH_SITE . "/templates/{$mainframe->getTemplate()}/html/com_content/search_stats/deleted.php";
$file_path = __FILE__;
if(JFile::exists($override)
	&& strpos($file_path, "html") === false //do not trigger in override file
) {
	ob_start();
		require($override);
		$return = ob_get_contents();
	ob_end_clean();
	echo $return;
	return;
}

echo JText::_("Deleted");

?>