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
use Joomla\CMS\Factory;

class PlgSystemAgosmssearchagosms extends JPlugin
{

	function onAfterDispatch()
	{
		$init_parameter = JFactory::getApplication()->input->get('gsearch');

		if ($init_parameter) {
			$doc = JFactory::getDocument();

			$searchagosms_type = JFactory::getApplication()->input->get("searchagosms_type", "com_agosms");
			$format = JFactory::getApplication()->input->get("searchagosms_mode", "html");

			switch ($searchagosms_type) {
				case "com_agosms":
					require_once dirname(__FILE__) . "/view/com_agosms/view.{$format}.php";
					$view = new ArticlesViewAgSearchagosms;
					$template = $view->display($searchagosms_type);
					break;
				case "searchagosms_stats":
					switch ($format) {
						case "save":
							require_once JPATH_SITE . "/plugins/system/agosmssearchagosms/models/com_agosms/model.php";
							$model = new ArticlesModelAgSearch;
							$model->saveSearchStats();
							exit;
						break;
						case "list":
							$this->check_logged_admin();
							ob_start();
								require_once dirname(__FILE__) . "/template/searchagosms_stats/list.php";
								$template = ob_get_contents();
							ob_end_clean();
							break;
						case "keyword":
							$this->check_logged_admin();
							ob_start();
								require_once dirname(__FILE__) . "/template/searchagosms_stats/keyword.php";
								$template = ob_get_contents();
							ob_end_clean();
							break;
						case "delete":
							$this->check_logged_admin();
							$id = JFactory::getApplication()->input->get("id");
							$query = "DELETE FROM #__content_searchagosms_stats WHERE id = {$id}";
							@JFactory::getDBO()->setQuery($query)->query();
							$query = "DELETE FROM #__content_searchagosms_stats_users WHERE keyword_id = {$id}";
							@JFactory::getDBO()->setQuery($query)->query();
							ob_start();
								require_once dirname(__FILE__) . "/template/searchagosms_stats/deleted.php";
								$deleted = ob_get_contents();
							ob_end_clean();
							echo $deleted;
							exit; // Raw output for ajax
						break;
						case "reset":
							$this->check_logged_admin();
							$id = JFactory::getApplication()->input->get("id");
							$query = "TRUNCATE TABLE #__content_searchagosms_stats";
							@JFactory::getDBO()->setQuery($query)->query();
							$query = "TRUNCATE TABLE #__content_searchagosms_stats_users";
							@JFactory::getDBO()->setQuery($query)->query();
							echo JText::_("Done.");
							exit; // Raw output for ajax
						break;
					}
					break;
			}

			if (JFactory::getApplication()->input->get->get('raw', false)) {
				echo $template;
				exit;
			} else {
				$doc->setBuffer($template, "component");
			}
		}
	}

	function onAfterRoute()
	{
		$app = JFactory::getApplication();
		$init_parameter = JFactory::getApplication()->input->get("gsearch");

		if ($init_parameter) {
			$app = Factory::getApplication();
					
			if ($app->isClient('administrator')) {
				return;
			}

			JFactory::getApplication()->input->set("option", "com_agosms");
			JFactory::getApplication()->input->set("view", "featured");
		}
	}

	function check_logged_admin()
	{
		$user = JFactory::getUser();

		if (!in_array(7, $user->groups) && !in_array(8, $user->groups)) {
			echo JText::_("Restricted only for admins <br /> Try to log in first");
			exit;
		}
	}
}
