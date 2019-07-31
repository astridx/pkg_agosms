<?php

/**
 * @package     Articles Good Search
 *
 * @copyright   Copyright (C) 2017 Joomcar extensions. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

defined('_JEXEC') or die;

class ArticlesViewGoodSearch extends JViewCategory
{
	function display($search_type = "com_content")
	{
		$mainframe = JFactory::getApplication();
		$override = JPATH_SITE . "/templates/{$mainframe->getTemplate()}/html/com_content/gsearch_blog.php";

		if (JFile::exists($override))
		{
			ob_start();
				require $override;
				$return = ob_get_contents();
			ob_end_clean();
		}
		else
		{
			ob_start();
				require JPATH_SITE . "/plugins/system/plg_agosms_search/template/com_content/gsearch_blog.php";
				$return = ob_get_contents();
			ob_end_clean();
		}

		echo $return;
		die;
	}
}


