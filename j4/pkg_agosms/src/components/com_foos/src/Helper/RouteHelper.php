<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_agosms
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AgosmNamespace\Component\Agosms\Site\Helper;

\defined('_JEXEC') or die;

use Joomla\CMS\Categories\CategoryNode;
use Joomla\CMS\Language\Multilanguage;

/**
 * Agosms Component Route Helper
 *
 * @static
 * @package     Joomla.Site
 * @subpackage  com_agosms
 * @since       __DEPLOY_VERSION__
 */
abstract class RouteHelper
{
	/**
	 * Get the URL route for a agosms from a agosm ID, agosms category ID and language
	 *
	 * @param   integer  $id        The id of the agosms
	 * @param   integer  $catid     The id of the agosms's category
	 * @param   mixed    $language  The id of the language being used.
	 *
	 * @return  string  The link to the agosms
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function getAgosmsRoute($id, $catid, $language = 0)
	{
		// Create the link
		$link = 'index.php?option=com_agosms&view=agosms&id=' . $id;

		if ($catid > 1) {
			$link .= '&catid=' . $catid;
		}

		if ($language && $language !== '*' && Multilanguage::isEnabled()) {
			$link .= '&lang=' . $language;
		}

		return $link;
	}

	/**
	 * Get the URL route for a agosm from a agosm ID, agosms category ID and language
	 *
	 * @param   integer  $id        The id of the agosms
	 * @param   integer  $catid     The id of the agosms's category
	 * @param   mixed    $language  The id of the language being used.
	 *
	 * @return  string  The link to the agosms
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function getAgosmRoute($id, $catid, $language = 0)
	{
		// Create the link
		$link = 'index.php?option=com_agosms&view=agosm&id=' . $id;

		if ($catid > 1) {
			$link .= '&catid=' . $catid;
		}

		if ($language && $language !== '*' && Multilanguage::isEnabled()) {
			$link .= '&lang=' . $language;
		}

		return $link;
	}

	/**
	 * Get the URL route for a agosms category from a agosms category ID and language
	 *
	 * @param   mixed  $catid     The id of the agosms's category either an integer id or an instance of CategoryNode
	 * @param   mixed  $language  The id of the language being used.
	 *
	 * @return  string  The link to the agosms
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function getCategoryRoute($catid, $language = 0)
	{
		if ($catid instanceof CategoryNode) {
			$id = $catid->id;
		} else {
			$id = (int) $catid;
		}

		if ($id < 1) {
			$link = '';
		} else {
			// Create the link
			$link = 'index.php?option=com_agosms&view=category&id=' . $id;

			if ($language && $language !== '*' && Multilanguage::isEnabled()) {
				$link .= '&lang=' . $language;
			}
		}

		return $link;
	}
}
