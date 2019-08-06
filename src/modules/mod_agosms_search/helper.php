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
defined('_JEXEC') or die;

class modAgosmsSearchHelper
{
	var $params;

	function __construct($params = null)
	{
		$this->params = $params;
	}

	function getModuleParams($id, $native = false)
	{
		if (!$id)
		{
			return;
		}

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select($db->quoteName('*'))
			->from('#__modules')
			->where('id = ' . $id);

		$db->setQuery($query);

		$result = $db->loadObject();

		if ($native)
		{
			$moduleParams = new JRegistry($result->params);
		}
		else
		{
			$moduleParams = json_decode($result->params);
		}

		return $moduleParams;
	}

	function getCategories($parent = 0, $params = null)
	{
		$db = JFactory::getDbo();
		$categories = Array();

		if ($parent)
		{
			$query = "SELECT id, title, level FROM #__categories WHERE extension = 'com_content' AND parent_id = {$parent} AND published = 1 ORDER BY title ASC";
		}
		else
		{
			if ($params)
			{
				if ($params->get("restrict"))
				{
					$categories_restriction = $this->getCategoriesRestriction($params);
				}
			}

			if ($params && count($categories_restriction))
			{
				$query = "SELECT id, title, level FROM #__categories WHERE extension = 'com_content' AND id IN (" . implode(",", $categories_restriction) . ") ORDER BY title ASC";
			}
			else
			{
				$query = "SELECT id, title, level FROM #__categories WHERE extension = 'com_content' AND level = 1 AND published = 1 ORDER BY title ASC";
			}
		}

		try
		{
			$db->setQuery($query);
			$results = $db->loadObjectList();
		}
		catch (Exception $e)
		{
			echo $e->getMessage();
			echo "<br /><br />" . $query;
		}

		foreach ($results as $category)
		{
			$categories[] = $category;

			if (JFactory::getApplication()->isSite() && $params)
			{
				if ($params->get("restrict"))
				{
					if ($params->get("restsub"))
					{
						$subs = (array) $this->getCategories($category->id, $params);

						if (count($subs))
						{
							$categories = array_merge($categories, $subs);
						}
					}
				}
				else
				{
					$subs = (array) $this->getCategories($category->id, $params);

					if (count($subs))
					{
						$categories = array_merge($categories, $subs);
					}
				}
			}
			else
			{
				$subs = (array) $this->getCategories($category->id, $params);

				if (count($subs))
				{
					$categories = array_merge($categories, $subs);
				}
			}
		}

		return $categories;
	}

	function getCategoriesRestriction($params, $module_id = null)
	{
		$categories = Array();

		switch ($params->get("restmode"))
		{
			case 0 : // Selected
				if ($params->get("restcat") == "")
				{
					return array();
				}

				$categories = explode("\r\n", $params->get("restcat"));
					break;

			case 1 : // Auto
				$view = '';

				if (JFactory::getApplication()->input->get->get('view'))
				{
					$active = JFactory::getApplication()->input->get->get('view');
				}

				$requestid = 0;

				if (JFactory::getApplication()->input->get->get('id'))
				{
					$active = JFactory::getApplication()->input->get->get('id');
				}

				if (in_array($view, Array("featured", "category")))
				{
					$categories[] = $requestid;
				}

				if (!count($categories))
				{
					$categories[] = 1;
				}
					break;
		}

		return $categories;
	}

	function getSubCategories($parent)
	{
		$db = JFactory::getDbo();
		$query = "SELECT id FROM #__categories WHERE extension = 'com_content' AND parent_id = {$parent} AND published = 1";
		$db->setQuery($query);
		$results = $db->loadColumn();

		$categories = array();

		foreach ($results as $catid)
		{
			$categories[] = $catid;
			$subs = (array) $this->getSubCategories($catid);
			$categories = array_merge($categories, $subs);
		}

		return $categories;
	}

	function getTags($params)
	{
		$items = Array();
		$db = JFactory::getDbo();
		$query = "SELECT id FROM #__content WHERE state = 1";

		if ($params->get("restrict"))
		{
			$categories = $this->getCategories(0, $params);

			if (count($categories))
			{
				$ids = Array();

				foreach ($categories as $c)
				{
					$ids[] = $c->id;
				}

				$query .= " AND catid IN (" . implode(",", $ids) . ")";
			}
		}

		try
		{
			$db->setQuery($query);
			$items = $db->loadColumn();
		}
		catch (Exception $e)
		{
			echo $e->getMessage();
			echo "<br /><br />" . $query;
		}

		$query = "SELECT DISTINCT(t.id), t.title, t.parent_id FROM #__tags as t";
		$query .= " LEFT JOIN #__contentitem_tag_map AS tm ON t.id = tm.tag_id";
		$query .= " WHERE published = 1";

		if (count($items))
		{
			$query .= " AND content_item_id IN (" . implode(",", $items) . ")";
			$query .= " ORDER BY t.title ASC";
		}
		else
		{
			return array(); // No tags if no items
		}

		$db->setQuery($query);

		return $db->loadObjectList();
	}

	function getAuthors($params)
	{
		$items = Array();
		$db = JFactory::getDbo();
		$query = "SELECT created_by FROM #__content WHERE state = 1";

		if ($params->get("restrict"))
		{
			$categories = $this->getCategoriesRestriction($params);

			if (count($categories))
			{
				$query .= " AND catid IN (" . implode(",", $categories) . ")";
			}
		}

		$db->setQuery($query);
		$items = $db->loadColumn();

		$authors = Array();

		if (count($items))
		{
			foreach ($items as $created_by)
			{
				$authors[$created_by] = $created_by;
			}

			$query = "SELECT id, name FROM #__users WHERE id IN (" . implode(',', $authors) . ")";
		}
		else
		{
			return $authors; // No authors if no items
		}

		$db->setQuery($query);

		return $db->loadObjectList();
	}

	function getCustomField($id)
	{
		$db = JFactory::getDbo();
		$query = "SELECT * FROM #__fields WHERE id = {$id}";
		$db->setQuery($query);

		return $db->loadObject();
	}

	function getFieldValuesFromText($field_id, $type = "int", $module_id)
	{
		$db = JFactory::getDbo();
		$query = "SELECT i.id, i.catid, GROUP_CONCAT(DISTINCT field{$field_id}.value SEPARATOR '|') as value";
		$query .= " FROM #__content as i";
		$query .= " LEFT JOIN #__fields_values AS field{$field_id} ON field{$field_id}.item_id = i.id AND field{$field_id}.field_id = {$field_id}";
		$query .= " WHERE state = 1";

		// Category restriction
		$module_params = $this->getModuleParams($module_id, true);

		if ($module_params->get('restrict'))
		{
			$category_restriction = $this->getCategoriesRestriction($module_params);

			if ($module_params->get('restsub'))
			{
				$tmp = array();

				foreach ($category_restriction as $catid)
				{
					$cats = $this->getSubCategories($catid);
					$cats[] = $catid;
					$tmp = array_merge($tmp, $cats);
				}

				$category_restriction = $tmp;
			}

			if (count($category_restriction))
			{
				$query .= " AND i.catid IN (" . implode(",", $category_restriction) . ")";
			}
		}

		$query .= " GROUP BY i.id";
		$db->setQuery($query);
		$result = $db->loadObjectList();

		$return = Array();

		if (count($result))
		{
			foreach ($result as $item)
			{
				if ($item->value)
				{
					$value = explode("|", $item->value);

					foreach ($value as $val)
					{
						$return[] = $type == "text" ? $val : (int) $val;
					}
				}
			}

			sort($return);
			$return = array_unique($return);
			$return = array_values($return);
		}

		return $return;
	}

	function getMultiFieldValuesFromText($field_id, $sub_field, $type = "int", $module_id)
	{
		$db = JFactory::getDbo();
		$query = "SELECT i.id, i.catid, GROUP_CONCAT(DISTINCT field{$field_id}.value SEPARATOR '|') as value";
		$query .= " FROM #__content as i";
		$query .= " LEFT JOIN #__fields_values AS field{$field_id} ON field{$field_id}.item_id = i.id AND field{$field_id}.field_id = {$field_id}";
		$query .= " WHERE state = 1";

		// Category restriction
		$module_params = $this->getModuleParams($module_id, true);

		if ($module_params->get('restrict'))
		{
			$category_restriction = $this->getCategoriesRestriction($module_params);

			if ($module_params->get('restsub'))
			{
				$tmp = array();

				foreach ($category_restriction as $catid)
				{
					$cats = $this->getSubCategories($catid);
					$cats[] = $catid;
					$tmp = array_merge($tmp, $cats);
				}

				$category_restriction = $tmp;
			}

			if (count($category_restriction))
			{
				$query .= " AND i.catid IN (" . implode(",", $category_restriction) . ")";
			}
		}

		$query .= " GROUP BY i.id";
		$db->setQuery($query);
		$result = $db->loadObjectList();

		$return = Array();

		if (count($result))
		{
			foreach ($result as $item)
			{
				if ($item->value)
				{
					$value = explode("|", $item->value);

					foreach ($value as $val)
					{
						$val = json_decode($val);

						if ($val)
						{
							foreach ($val as $repeatable)
							{
								if ($repeatable->{$sub_field})
								{
									$return[] = $type == "text" ? $repeatable->{$sub_field} : (int) $repeatable->{$sub_field};
								}
							}
						}
					}
				}
			}

			sort($return);
			$return = array_unique($return);
			$return = array_values($return);
		}

		return $return;
	}

	function getItemExtraFields($iId)
	{
		$query = "SELECT field_id as id, GROUP_CONCAT(value SEPARATOR ';;') AS value FROM #__fields_values WHERE item_id = {$iId} GROUP BY field_id";

		return JFactory::getDBO()->setQuery($query)->loadObjectList();
	}

	function getItemTags($iId)
	{
		$query = "SELECT m.tag_id as id, t.title as name FROM #__contentitem_tag_map as m 
					LEFT JOIN #__tags as t ON t.id = m.tag_id 
					WHERE m.type_alias = 'com_content.article' AND content_item_id = {$iId}
					";

		return JFactory::getDBO()->setQuery($query)->loadObjectList();
	}

	function getItemsTitles($params)
	{
		$db = JFactory::getDbo();
		$query = "SELECT i.title";
		$query .= " FROM #__content as i";
		$query .= " WHERE state = 1";

		// Category restriction
		if ($params->get('restrict'))
		{
			$category_restriction = $this->getCategoriesRestriction($params);

			if ($params->get('restsub'))
			{
				$tmp = array();

				foreach ($category_restriction as $catid)
				{
					$cats = $this->getSubCategories($catid);
					$cats[] = $catid;
					$tmp = array_merge($tmp, $cats);
				}

				$category_restriction = $tmp;
			}

			if (count($category_restriction))
			{
				$query .= " AND i.catid IN (" . implode(",", $category_restriction) . ")";
			}
		}

		$db->setQuery($query);

		return $db->loadColumn();
	}
}
