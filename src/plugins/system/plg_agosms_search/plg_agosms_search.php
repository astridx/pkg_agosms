<?php
/**
 * @package     Joomla.Site
 * @subpackage  pkg_agosms
 *
 * @copyright   Copyright (C) 2005 - 2019 Astrid Günther, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later;
 * @link        astrid-guenther.de
 */


// No direct access
defined('_JEXEC') or die('Restricted access');

class plgSystemPlg_agosms_search extends JPlugin
{

	function onAfterDispatch()
	{
		if (isset($_REQUEST['K2ContentBuilder']))
		{
			return;
		}

		$init_parameter = JRequest::getVar('gsearch');

		if ($init_parameter)
		{
			error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED & ~E_STRICT);

			$doc = JFactory::getDocument();

			$search_type = JRequest::getVar("search_type", "com_content");
			$format = JRequest::getVar("search_mode", "html");

			switch ($search_type)
			{
				case "com_content" :
					require_once dirname(__FILE__) . "/view/com_content/view.{$format}.php";
					$view = new ArticlesViewGoodSearch;
					$template = $view->display($search_type);
					break;
				case "search_stats" :
					switch ($format)
					{
						case "save" :
							require_once JPATH_SITE . "/plugins/system/plg_agosms_search/models/com_content/model.php";
							$model = new ArticlesModelGoodSearch;
							$model->saveSearchStats();
							exit;
						break;
						case "list" :
							$this->check_logged_admin();
							ob_start();
								require_once dirname(__FILE__) . "/template/search_stats/list.php";
								$template = ob_get_contents();
							ob_end_clean();
						break;
						case "keyword" :
							$this->check_logged_admin();
							ob_start();
								require_once dirname(__FILE__) . "/template/search_stats/keyword.php";
								$template = ob_get_contents();
							ob_end_clean();
						break;
						case "delete" :
							$this->check_logged_admin();
							$id = JRequest::getInt("id");
							$query = "DELETE FROM #__content_search_stats WHERE id = {$id}";
							@JFactory::getDBO()->setQuery($query)->query();
							$query = "DELETE FROM #__content_search_stats_users WHERE keyword_id = {$id}";
							@JFactory::getDBO()->setQuery($query)->query();
							ob_start();
								require_once dirname(__FILE__) . "/template/search_stats/deleted.php";
								$deleted = ob_get_contents();
							ob_end_clean();
							echo $deleted;
							exit; // Raw output for ajax
						break;
						case "reset" :
							$this->check_logged_admin();
							$id = JRequest::getInt("id");
							$query = "TRUNCATE TABLE #__content_search_stats";
							@JFactory::getDBO()->setQuery($query)->query();
							$query = "TRUNCATE TABLE #__content_search_stats_users";
							@JFactory::getDBO()->setQuery($query)->query();
							echo JText::_("Done.");
							exit; // Raw output for ajax
						break;
					}
					break;
			}

			if ($_GET['raw'])
			{
				echo $template;
				exit;
			}
			else
			{
				$doc->setBuffer($template, "component");
			}
		}
	}

	function onAfterRoute()
	{
		$app = JFactory::getApplication();
		$init_parameter = JRequest::getVar('gsearch');

		if ($init_parameter)
		{
			if ($app->isAdmin())
			{
				return;
			}

			JRequest::setVar("option", "com_content");
			JRequest::setVar("view", "featured");

			// Can be enabled for increase a speed
			// JRequest::setVar("option", "com_contact"); //false code for disable standard component output
		}
	}

	function check_logged_admin()
	{
		$user = JFactory::getUser();

		if (!in_array(7, $user->groups) && !in_array(8, $user->groups))
		{
			echo JText::_("Restricted only for admins <br /> Try to log in first");
			exit;
		}
	}
}
