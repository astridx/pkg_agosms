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
		require_once JPATH_SITE . "/plugins/system/agosmssearchagosms/models/com_agosms/model.php";
		$model = new ArticlesModelAgSearchagosms;
		echo $model->total_items;
		die;
	}
}
