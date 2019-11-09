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
defined('_JEXEC') or die('');

class PlgSystemAgosmssearch extends JPlugin
{

	function onAfterDispatch()
	{
		$init_parameter = JFactory::getApplication()->input->get('gsearch');

		if ($init_parameter)
		{
			$doc = JFactory::getDocument();

			$search_type = JFactory::getApplication()->input->get("search_type", "com_content");
			$format = JFactory::getApplication()->input->get("search_mode", "html");

			switch ($search_type)
			{
				case "com_content" :
					require_once dirname(__FILE__) . "/view/com_content/view.{$format}.php";
					$view = new ArticlesViewAgSearch;
					$template = $view->display($search_type);
					break;
				case "search_stats" :
					switch ($format)
					{
						case "save" :
							require_once JPATH_SITE . "/plugins/system/agosmssearch/models/com_content/model.php";
							$model = new ArticlesModelAgSearch;
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
							$id = JFactory::getApplication()->input->get("id");
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
							$id = JFactory::getApplication()->input->get("id");
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

			if (JFactory::getApplication()->input->get->get('raw', false))
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
		$init_parameter = JFactory::getApplication()->input->get("gsearch");

		if ($init_parameter)
		{
			if ($app->isAdmin())
			{
				return;
			}

			JFactory::getApplication()->input->set("option", "com_content");
			JFactory::getApplication()->input->set("view", "featured");
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
