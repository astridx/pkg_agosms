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

/**
 * Agosms Component Route Helper.
 *
 * @since  1.0.40
 */
abstract class AgosmsHelperRoute
{
	protected static $lookup;

	protected static $lang_lookup = [];

	/**
	 * Get the route of the agosm
	 *
	 * @param   integer  $id        Agosm ID
	 * @param   integer  $catid     Category ID
	 * @param   string   $language  Language
	 *
	 * @return  string
	 */
	public static function getAgosmRoute($id, $catid, $language = 0)
	{
		$needles = ['agosm'  => [(int) $id]];

		// Create the link
		$link = 'index.php?option=com_agosms&view=agosm&id=' . $id;

		if ($catid > 1) {
			$categories = JCategories::getInstance('Agosms');
			$category = $categories->get($catid);

			if ($category) {
				$needles['category'] = array_reverse($category->getPath());
				$needles['categories'] = $needles['category'];
				$link .= '&catid=' . $catid;
			}
		}

		if ($language && $language != "*" && JLanguageMultilang::isEnabled()) {
			self::buildLanguageLookup();

			if (isset(self::$lang_lookup[$language])) {
				$link .= '&lang=' . self::$lang_lookup[$language];
				$needles['language'] = $language;
			}
		}

		if ($item = self::_findItem($needles)) {
			$link .= '&Itemid=' . $item;
		}

		return $link;
	}

	/**
	 * Ge the form route
	 *
	 * @param   integer  $id      The id of the agosm.
	 * @param   string   $return  The return page variable.
	 *
	 * @return  string
	 */
	public static function getFormRoute($id, $return = null)
	{
		// Create the link.
		if ($id) {
			$link = 'index.php?option=com_agosms&task=agosm.edit&w_id=' . $id;
		} else {
			$link = 'index.php?option=com_agosms&task=agosm.add&w_id=0';
		}

		if ($return) {
			$link .= '&return=' . $return;
		}

		return $link;
	}

	/**
	 * Get the Category Route
	 *
	 * @param   JCategoryNode|string|integer  $catid     JCategoryNode object or category ID
	 * @param   integer                       $language  Language code
	 *
	 * @return  string
	 */
	public static function getCategoryRoute($catid, $language = 0)
	{
		if ($catid instanceof JCategoryNode) {
			$id = $catid->id;
			$category = $catid;
		} else {
			$id = (int) $catid;
			$category = JCategories::getInstance('Agosms')->get($id);
		}

		if ($id < 1 || !($category instanceof JCategoryNode)) {
			$link = '';
		} else {
			$needles = [];

			// Create the link
			$link = 'index.php?option=com_agosms&view=category&id=' . $id;

			$catids = array_reverse($category->getPath());
			$needles['category'] = $catids;
			$needles['categories'] = $catids;

			if ($language && $language != "*" && JLanguageMultilang::isEnabled()) {
				self::buildLanguageLookup();

				if (isset(self::$lang_lookup[$language])) {
					$link .= '&lang=' . self::$lang_lookup[$language];
					$needles['language'] = $language;
				}
			}

			if ($item = self::_findItem($needles)) {
				$link .= '&Itemid=' . $item;
			}
		}

		return $link;
	}

	/**
	 * Do a language lookup
	 *
	 * @return  void
	 */
	protected static function buildLanguageLookup()
	{
		if (count(self::$lang_lookup) == 0) {
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select('a.sef AS sef')
				->select('a.lang_code AS lang_code')
				->from('#__languages AS a');

			$db->setQuery($query);
			$langs = $db->loadObjectList();

			foreach ($langs as $lang) {
				self::$lang_lookup[$lang->lang_code] = $lang->sef;
			}
		}
	}

	/**
	 * Find items per given $needles
	 *
	 * @param   array  $needles  A given array of needles to find
	 *
	 * @return  void
	 */
	protected static function _findItem($needles = null)
	{
		$app      = JFactory::getApplication();
		$menus    = $app->getMenu('site');
		$language = isset($needles['language']) ? $needles['language'] : '*';

		// Prepare the reverse lookup array.
		if (!isset(self::$lookup[$language])) {
			self::$lookup[$language] = [];

			$component = JComponentHelper::getComponent('com_agosms');

			$attributes = ['component_id'];
			$values = [$component->id];

			if ($language != '*') {
				$attributes[] = 'language';
				$values[] = [$needles['language'], '*'];
			}

			$items = $menus->getItems($attributes, $values);

			if ($items) {
				foreach ($items as $item) {
					if (isset($item->query) && isset($item->query['view'])) {
						$view = $item->query['view'];

						if (!isset(self::$lookup[$language][$view])) {
							self::$lookup[$language][$view] = [];
						}

						if (isset($item->query['id'])) {
							/**
							 * Here it will become a bit tricky
							 * language != * can override existing entries
							 * language == * cannot override existing entries
							 */
							if (!isset(self::$lookup[$language][$view][$item->query['id']]) || $item->language != '*') {
								self::$lookup[$language][$view][$item->query['id']] = $item->id;
							}
						}
					}
				}
			}
		}

		if ($needles) {
			foreach ($needles as $view => $ids) {
				if (isset(self::$lookup[$language][$view])) {
					foreach ($ids as $id) {
						if (isset(self::$lookup[$language][$view][(int) $id])) {
							return self::$lookup[$language][$view][(int) $id];
						}
					}
				}
			}
		}

		// Check if the active menuitem matches the requested language
		$active = $menus->getActive();

		if ($active && ($language == '*' || in_array($active->language, ['*', $language]) || !JLanguageMultilang::isEnabled())) {
			return $active->id;
		}

		// If not found, return language specific home link
		$default = $menus->getDefault($language);

		return !empty($default->id) ? $default->id : null;
	}
}
