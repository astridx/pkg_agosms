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

class ArticlesViewAgSearchagosms extends JViewCategory
{
	function display($search_type = "com_agosms")
	{
		$mainframe = JFactory::getApplication();
		$override = JPATH_SITE . "/templates/{$mainframe->getTemplate()}/html/com_agosms/gsearchagosms_blog.php";

		if (JFile::exists($override)) {
			ob_start();
				require $override;
				$return = ob_get_contents();
			ob_end_clean();
		} else {
			ob_start();
				require JPATH_SITE . "/plugins/system/agosmssearchagosms/template/com_agosms/gsearchagosms_blog.php";
				$return = ob_get_contents();
			ob_end_clean();
		}

		return $return;
	}
}
