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

class ArticlesModelAgSearch extends JModelList
{
	var $input;
	var $module_id;
	var $module_helper;
	var $module_params;
	var $module_params_native;
	var $limit;
	var $limitstart;
	var $total_items;
	var $search_query;

	function __construct()
	{
		$this->input = JFactory::getApplication()->input;
		require_once JPATH_SITE . "/modules/mod_agosms_search/helper.php";
		$this->module_id = $this->input->get("moduleId", "", "int");
		$this->module_helper = new modAgosmsSearchHelper;
		$this->module_params = $this->module_helper->getModuleParams($this->module_id);
		$this->module_params_native = $this->module_helper->getModuleParams($this->module_id, true);

		if (isset($this->module_params->savesearch) && $this->module_params->savesearch && !JFactory::getApplication()->input->get("initial"))
		{
			$this->saveSearchSession();
		}

		if (isset($this->module_params->savesearch) && $this->module_params->savesearch && JFactory::getSession()->get("SaveSearchValues") && $_GET['applySaved']
		)
		{
			$skip = array("option", "task", "view", "Itemid", "search_mode", "field_id", "field_type", "initial");

			foreach (JFactory::getSession()->get("SaveSearchValues") as $key => $value) {
				if (in_array($key, $skip))
				{
					continue;
				}

				JFactory::getApplication()->input->set($key, $value);
			}
		}

		$this->search_query = $this->getSearchQuery();
		$this->total_items = $this->getItems(true);
	}

	function getItems($total = false)
	{
		if (isset($this->module_params->georestrict) && $this->module_params->georestrict)
		{
			$filterresults = $this->getItemsBeforGeo(false, false);
			$tempfilterresults = array();
			
			foreach ($filterresults as $filterresult) {
				$jcFields = FieldsHelper::getFields('com_content.article', $filterresult, true);

				foreach ($jcFields as $jcField) {
					if ($jcField->type === "agosmsaddressmarker")
					{
						$getlat = "field_" . $jcField->id . "-lat";
						$getlon = "field_" . $jcField->id . "-lon";
						$globallat = JFactory::getApplication()->input->get->get($getlat);
						$globallon = JFactory::getApplication()->input->get->get($getlon);

						$fieldlat = 0;
						$fieldlon = 0;
						if (isset($jcField->rawvalue))
						{
							$fieldcords = explode(",", $jcField->rawvalue);
							$fieldlat = $fieldcords[0];
							$fieldlon = $fieldcords[1];
						}

						// Check if is null
						if ($fieldlat == "0" || $fieldlon == "0" || $globallat === "0" || $globallon === "0")
						{
							$filterresult->lat = $fieldlat;
							$filterresult->lon = $fieldlon;
							//$tempfilterresults[] = $filterresult;
						}
						else
						{
							$from = "field_" . $jcField->id . "-from";
							$to = "field_" . $jcField->id . "-to";
							$globalfrom = JFactory::getApplication()->input->get->get($from);
							$globalto = JFactory::getApplication()->input->get->get($to);
							$geounit = $this->module_params->geounit;
							$distance = $this->distance($fieldlat, $fieldlon, $globallat, $globallon, $geounit);

							if ($distance >= $globalfrom && $distance <= $globalto)
							{
								$filterresult->lat = $fieldlat;
								$filterresult->lon = $fieldlon;
								$filterresult->distance = $distance;
								$tempfilterresults[] = $filterresult;
							}
						}
					}
				}
			}
			
			if ($total)
			{
				return (string)sizeof($tempfilterresults);
			}
			else
			{
				$this->limitstart = $this->input->get("page-start", 0, "int");
				
				if (!empty($tempfilterresults) && JFactory::getApplication()->input->get("orderby") == "distance")
				{
					usort($tempfilterresults, function ($a, $b) {return $a->distance > $b->distance;});
				}
				
				$tempfilterresultslimit = array_slice($tempfilterresults, $this->limitstart, $this->limit);
				
				return $tempfilterresultslimit;
			}
		}
		
		return $this->getItemsBeforGeo($total);
	}

	function getItemsBeforGeo($total = false, $limitforpagination = true)
	{
		// See /var/www/html/joomla-cms/administrator/components/com_actionlogs/models/actionlogs.php
		// And /var/www/html/joomla-cms/administrator/components/com_languages/helpers/multilangstatus.php
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		if ($total)
		{
			$query->select('count(distinct(i.id))');

		} 
		else
		{
			$featuredFirst = false;
			switch ($this->module_params->include_featured) {
				case "First" :
					$featuredFirst = true;
					break;
				case "Only" :
					$query .= " AND i.featured = 1";
					break;
				case "No" :
					$query .= " AND i.featured = 0";
					break;
			}

			$default_ordering = $featuredFirst ? 'featured' : $this->module_params->ordering_default;
			$orderby = JFactory::getApplication()->input->get("orderby", $default_ordering);
			$orderto = JFactory::getApplication()->input->get("orderto", $this->module_params->ordering_default_dir);

			$query = "SELECT i.*, GROUP_CONCAT(tm.tag_id) as tags, cat.title as category";
			
			// Select field ordering value
			if ($featuredFirst)
			{
				preg_match('/^field([0-9]+)$/', $this->module_params->ordering_default, $matches);
			} else
			{
				preg_match('/^field([0-9]+)$/', $orderby, $matches);
			}
			if (count($matches))
			{
				$query .= ", fv2.value as {$matches[0]}";
			}
		}

		$query .= " FROM #__content as i";
		$query .= " LEFT JOIN #__categories AS cat ON cat.id = i.catid";

		if (JFactory::getApplication()->input->get("keyword"))
		{
			//left join all fields values for keyword search
			//commented for prevent slow loading with big databases
			//$query .= " LEFT JOIN #__fields_values AS fv ON fv.item_id = i.id";
		}

		$query .= " LEFT JOIN #__contentitem_tag_map AS tm 
						ON tm.content_item_id = i.id 
							AND type_alias = 'com_content.article'
				";

		if (!$total)
		{
			//left join field ordering value
			if ($featuredFirst)
			{
				preg_match('/^field([0-9]+)$/', $this->module_params->ordering_default, $matches);
			} else
			{
				preg_match('/^field([0-9]+)$/', $orderby, $matches);
			}
			if (count($matches))
			{
				$query .= " LEFT JOIN #__fields_values AS fv2 ON fv2.item_id = i.id AND fv2.field_id = {$matches[1]}";
			}
		}

		$query .= " WHERE i.state = 1";

		//publish up/down
		$timezone = new DateTimeZone(JFactory::getConfig()->get('offset'));
		$time = new DateTime(date("Y-m-d H:i:s"), $timezone);
		$time = $time->format('Y-m-d H:i:s');
		$query .= " AND i.publish_up <= '{$time}' AND (i.publish_down > '{$time}' OR i.publish_down = '0000-00-00 00:00:00')";

		//category restriction
		if (isset($this->module_params->restrict) && $this->module_params->restrict)
		{
			$module_params_native = $this->module_helper->getModuleParams($this->module_id, true);
			$category_restriction = $this->module_helper->getCategories(0, $module_params_native);
			if (count($category_restriction))
			{
				$ids = Array();
				foreach ($category_restriction as $c) {
					$ids[] = $c->id;
				}
				$query .= " AND i.catid IN (" . implode(",", $ids) . ")";
			}
		}

		//language filter
		$language = JFactory::getLanguage();
		$defaultLang = $language->getDefault();
		$currentLang = $language->getTag();
		$query .= " AND i.language IN ('*', '{$currentLang}')";

		//general search query build
		$query .= $this->search_query;

		if (!$total)
		{
			$query .= " GROUP BY i.id";
			$query .= " ORDER BY ";
			switch ($orderby) {
				case "title" :
				case "distance" :
					if (JFactory::getApplication()->input->get("orderto") == "")
					{
						$orderto = "ASC";
						JFactory::getApplication()->input->set("orderto", "asc");
					}
					$query .= "i.title {$orderto}";
					break;
				case "alias" :
					$query .= "i.alias {$orderto}";
					break;
				case "created" :
					$query .= "i.created {$orderto}";
					break;
				case "publish_up" :
					$query .= "i.publish_up {$orderto}";
					break;
				case "category" :
					$query .= "category {$orderto}";
					break;
				case "hits" :
					$query .= "i.hits {$orderto}";
					break;
				case "featured" :
					$query .= "i.featured {$orderto}";
					//order by field value
					preg_match('/^field([0-9]+)$/', $this->module_params->ordering_default, $matches);
					if (count($matches))
					{
						$query .= ", {$this->module_params->ordering_default} {$orderto}";
					} else
					{
						$query .= ", i.{$this->module_params->ordering_default} {$orderto}";
					}
					break;
				case "rand" :
					$currentSession = JFactory::getSession();
					$sessionNum = substr(preg_replace('/[^0-9]/i', '', $currentSession->getId()), 2, 3);
					$query .= "RAND({$sessionNum})";
					break;
				case "id" :
				default :
					//order by field value
					preg_match('/^field([0-9]+)$/', $orderby, $matches);
					if (count($matches))
					{
						$query .= "{$orderby} {$orderto}";
					} else
					{
						$query .= "i.id {$orderto}";
					}
			}
		}

		if ($total)
		{
			$db->setQuery($query);
			$return = $db->loadResult();
		} else
		{
			$this->limitstart = $this->input->get("page-start", 0, "int");
			
			if ($limitforpagination)
			{
				$db->setQuery($query, $this->limitstart, $this->limit);
			}
			else
			{
				$db->setQuery($query);				
			}
			
			$return = $db->loadObjectList();
		}

		return $return;
	}

	function getSearchQuery()
	{
		$timezone = new DateTimeZone(JFactory::getConfig()->get('offset'));
		$query = "";

		// Keyword
		if (JFactory::getApplication()->input->get("keyword"))
		{
			$keyword = strtoupper(JFactory::getApplication()->input->get("keyword"));
			$keyword = addslashes($keyword);
			$keyword = str_replace("/", "\\\\\\\/", $keyword);
			$keyword = str_replace("(", "\\\\(", $keyword);
			$keyword = str_replace(")", "\\\\)", $keyword);
			$keyword = str_replace("*", "\\\\*", $keyword);

			if ($_GET['match'] == 'any')
			{
				$query .= " AND (";

				foreach (explode(" ", $keyword) as $k => $word) {
					$query .= $k > 0 ? " OR " : "";
					$query .= "UPPER(i.title) LIKE '%{$word}%'";
				}

				$query .= ")";
			} else
			{
				$query .= " AND (UPPER(i.title) LIKE '%{$keyword}%'";
				$query .= "  OR UPPER(i.introtext) LIKE '%{$keyword}%'";

				// Commented for prevent slow loading with big databases
				// $query .= "OR GROUP_CONCAT(fv.value SEPARATOR ', ') LIKE '%{$keyword}%'";
				$query .= ")";
			}
		}

		// Category
		if (JFactory::getApplication()->input->getInt("category"))
		{
			$categories = JFactory::getApplication()->input->getInt("category");

			if ($categories[0] != "")
			{
				if ($this->module_params->restsub)
				{
					foreach ($categories as $category) {
						$subs = (array) $this->module_helper->getSubCategories($category);
						$categories = array_merge($categories, $subs);
					}
				}

				$query .= " AND i.catid IN (" . implode(",", $categories) . ")";
			}
		}

		// Tag
		if (JFactory::getApplication()->input->getInt("tag"))
		{
			$query .= " AND tm.tag_id IN (" . implode(",", JFactory::getApplication()->input->getInt("tag")) . ")";
		}

		// Author
		if (JFactory::getApplication()->input->getInt("author"))
		{
			$query .= " AND i.created_by IN (" . implode(",", JFactory::getApplication()->input->getInt("author")) . ")";
		}

		// Date
		if (JFactory::getApplication()->input->get("date-from"))
		{
			$query .= " AND i.created >= '" . JFactory::getApplication()->input->get("date-from") . " 00:00:00'";
		}

		if (JFactory::getApplication()->input->get("date-to"))
		{
			$query .= " AND i.created <= '" . JFactory::getApplication()->input->get("date-to") . " 23:59:59'";
		}

		// Fields search
		require_once JPATH_SITE . '/modules/mod_agosms_search/helper.php';
		$module_helper = new modAgosmsSearchHelper;

		foreach ($_GET as $param => $value) {
			preg_match('/^field([0-9]+)$/', $param, $matches);
			$field_id = 0;

			if (!empty($matches))
			{
				$field_id = $matches[1];
			}

			$query_params = JFactory::getApplication()->input->get("field{$field_id}");
			$sub_query = "SELECT DISTINCT item_id FROM #__fields_values WHERE 1";

			// Text / date
			if (!is_array($query_params) && $query_params != "")
			{
				$query_params = addslashes($query_params);
				$sub_query .= " AND field_id = {$field_id}";
				$field_params = $module_helper->getCustomField($field_id);

				if ($field_params->type == "calendar")
				{
					$date = \JFactory::getDate($query_params)->setTimezone($timezone);
					$sub = $date->getOffsetFromGmt();

					// Get date with timezone offset
					$query_params = date("Y-m-d", strtotime($date->format('Y-m-d H:i:s')) - $sub);
					$sub_query .= " AND value LIKE '%{$query_params}%'";
				} else
				{
					$sub_query .= " AND value LIKE '%{$query_params}%'";
				}
			}

			// List values
			if (is_array($query_params) && $query_params[0] != "")
			{
				$sub_query .= " AND field_id = {$field_id}";
				$sub_query .= " AND (";

				foreach ($query_params as $k => $query_param) {
					$query_param = addslashes($query_param);
					$sub_query .= "value = '{$query_param}'";

					if (($k + 1) != count($query_params))
					{
						if (array_key_exists('match', $_GET))
						{
							if ($_GET['match'] == "all")
							{
								$sub_query .= " AND ";
							} else
							{
								$sub_query .= " OR ";
							}
						}
						else
						{
							$sub_query .= " OR ";
						}
					}
				}

				$sub_query .= ")";
			}

			// Text range / date range
			preg_match('/^field([0-9]+)-from$/', $param, $matches);

			$field_id = 0;

			if (!empty($matches))
			{
				$field_id = $matches[1];
			}

			if (JFactory::getApplication()->input->get("field{$field_id}-from") != "")
			{
				$sub_query .= " AND field_id = {$field_id}";
				$field_params = $module_helper->getCustomField($field_id);
				$query_params = JFactory::getApplication()->input->get("field{$field_id}-from");
				$query_params = addslashes($query_params);

				if ($field_params->type == "calendar")
				{
					$date_search = new DateTime($query_params, $timezone);
					$query_params = $date_search->format('Y-m-d');
					$sub_query .= " AND value >= '{$query_params} 00:00:00'";
				} else
				{
					if (is_numeric($query_params))
					{
						$query_params = trim(preg_replace('/\s+/i', '', $query_params));
					} else
					{
						$query_params = "'" . $query_params . "'";
					}

					$sub_query .= " AND value >= {$query_params}";
				}
			}

			preg_match('/^field([0-9]+)-to$/', $param, $matches);
			$field_id = 0;

			if (!empty($matches))
			{
				$field_id = $matches[1];
			}

			if (JFactory::getApplication()->input->get("field{$field_id}-to") != "")
			{
				$sub_query .= " AND field_id = {$field_id}";
				$field_params = $module_helper->getCustomField($field_id);
				$query_params = JFactory::getApplication()->input->get("field{$field_id}-to");
				$query_params = addslashes($query_params);

				if ($field_params->type == "calendar")
				{
					$date_search = new DateTime($query_params, $timezone);
					$query_params = $date_search->format('Y-m-d');
					$sub_query .= " AND value <= '{$query_params} 23:59:59'";
				} else
				{
					if (is_numeric($query_params))
					{
						$query_params = trim(preg_replace('/\s+/i', '', $query_params));
					} else
					{
						$query_params = "'" . $query_params . "'";
					}

					$sub_query .= " AND value <= {$query_params}";
				}
			}

			// Execute query and get item ids
			if ($query_params != "" && $query_params[0] != "")
			{
				$ids = JFactory::getDBO()->setQuery($sub_query)->loadColumn();

				if (count($ids))
				{
					$query .= " AND i.id IN(" . implode(",", $ids) . ")";
				} else
				{
					$query .= " AND i.id = 0";
				}
			}
		}

		// Added for compatibility with radical multifield
		foreach ($_GET as $param => $value) {
			preg_match('/^multifield([0-9]+)-([^-]*)(.*)/i', $param, $matches);

			$field_id = 0;
			$sub_field = null;
			$isRange = false;

			if (!empty($matches))
			{
				$field_id = $matches[1];
				$sub_field = $matches[2];
				$isRange = $matches[3] != '' ? true : false;
			}

			if (!$field_id || !$sub_field)
			{
				continue;
			}

			$field_params = $module_helper->getCustomField($field_id);

			$uri_params = JFactory::getApplication()->input->get($param);
			$sub_query = "SELECT DISTINCT item_id FROM #__fields_values WHERE 1";

			// Text / date
			if (!is_array($uri_params) && $uri_params != "" && !$isRange)
			{
				$sub_query .= " AND field_id = {$field_id}";

				if ($field_params->type == "calendar")
				{
					$date_search = new DateTime($uri_params, $timezone);
					$uri_params = $date_search->format('Y-m-d');
					$sub_query .= " AND value LIKE '%{$uri_params}%'";
				} else
				{
					$sub_query .= " AND value REGEXP '\"{$sub_field}\":\"{$uri_params}\"'";
				}
			}

			// Text range / date range
			if ($matches[3] == '-from')
			{
				$range_query = "SELECT * FROM #__fields_values WHERE field_id = {$field_id}";
				$values = JFactory::getDBO()->setQuery($range_query)->loadObjectList();
				$ids_to_include = array();

				foreach ($values as $value) {
					$item_id = $value->item_id;
					$value = json_decode($value->value);

					foreach ($value as $val) {
						if ($val->{$sub_field} >= $uri_params)
						{
							// Check for more or equal
							$ids_to_include[] = $item_id;
						}
					}
				}

				$ids_to_include = array_values(array_unique($ids_to_include));

				if (count($ids_to_include))
				{
					$sub_query .= " AND item_id IN(" . implode(",", $ids_to_include) . ")";
				} else
				{
					$sub_query .= " AND item_id = 0";
				}
			}

			if ($matches[3] == '-to')
			{
				$range_query = "SELECT * FROM #__fields_values WHERE field_id = {$field_id}";
				$values = JFactory::getDBO()->setQuery($range_query)->loadObjectList();
				$ids_to_include = array();

				foreach ($values as $value) {
					$item_id = $value->item_id;
					$value = json_decode($value->value);

					foreach ($value as $val) {
						if ($val->{$sub_field} <= $uri_params && $val->{$sub_field} != '')
						{
							// Check for less or equal
							$ids_to_include[] = $item_id;
						}
					}
				}

				$ids_to_include = array_values(array_unique($ids_to_include));

				if (count($ids_to_include))
				{
					$sub_query .= " AND item_id IN(" . implode(",", $ids_to_include) . ")";
				} else
				{
					$sub_query .= " AND item_id = 0";
				}
			}

			// Execute query and get item ids
			if ($uri_params != "" && $uri_params[0] != "")
			{
				$ids = JFactory::getDBO()->setQuery($sub_query)->loadColumn();

				if (count($ids))
				{
					$query .= " AND i.id IN(" . implode(",", $ids) . ")";
				} else
				{
					$query .= " AND i.id = 0";
				}
			}
		}

		// Added for compatibility with repeatable field
		foreach ($_GET as $param => $value) {
			preg_match('/^repeatable([0-9]+)-([^-]*)(.*)/i', $param, $matches);

			$field_id = 0;
			$sub_field = null;
			$isRange = false;

			if (!empty($matches))
			{
				$field_id = $matches[1];
				$sub_field = $matches[2];
				$isRange = $matches[3] != '' ? true : false;
			}

			if (!$field_id || $sub_field_number === null)
			{
				continue;
			}

			$field_params = $module_helper->getCustomField($field_id);

			$uri_params = JFactory::getApplication()->input->get($param);
			$sub_query = "SELECT DISTINCT item_id FROM #__fields_values WHERE 1";

			$sub_field_values = json_decode($field_params->fieldparams);
			$sub_field_name = $sub_field_values->fields->{"fields" . $sub_field_number}->fieldname;

			// Text / date
			if (!is_array($uri_params) && $uri_params != "" && !$isRange)
			{
				$sub_query .= " AND field_id = {$field_id}";

				if ($field_params->type == "calendar")
				{
					$date_search = new DateTime($uri_params, $timezone);
					$uri_params = $date_search->format('Y-m-d');
					$sub_query .= " AND value LIKE '%{$uri_params}%'";
				} else
				{
					$sub_query .= " AND value REGEXP '\"{$sub_field_name}\":\"{$uri_params}\"'";
				}
			}

			// Text range / date range
			if ($matches[3] == '-from')
			{
				$range_query = "SELECT * FROM #__fields_values WHERE field_id = {$field_id}";
				$values = JFactory::getDBO()->setQuery($range_query)->loadObjectList();
				$ids_to_include = array();

				foreach ($values as $value) {
					$item_id = $value->item_id;
					$value = json_decode($value->value);

					foreach ($value as $val) {
						if ($val->{$sub_field_name} >= $uri_params)
						{
							// Check for more or equal
							$ids_to_include[] = $item_id;
						}
					}
				}

				$ids_to_include = array_values(array_unique($ids_to_include));

				if (count($ids_to_include))
				{
					$sub_query .= " AND item_id IN(" . implode(",", $ids_to_include) . ")";
				} else
				{
					$sub_query .= " AND item_id = 0";
				}
			}

			if ($matches[3] == '-to')
			{
				$range_query = "SELECT * FROM #__fields_values WHERE field_id = {$field_id}";
				$values = JFactory::getDBO()->setQuery($range_query)->loadObjectList();
				$ids_to_include = array();

				foreach ($values as $value) {
					$item_id = $value->item_id;
					$value = json_decode($value->value);

					foreach ($value as $val) {
						if ($val->{$sub_field_name} <= $uri_params && $val->{$sub_field_name} != '')
						{
							// Check for less or equal
							$ids_to_include[] = $item_id;
						}
					}
				}

				$ids_to_include = array_values(array_unique($ids_to_include));

				if (count($ids_to_include))
				{
					$sub_query .= " AND item_id IN(" . implode(",", $ids_to_include) . ")";
				} else
				{
					$sub_query .= " AND item_id = 0";
				}
			}

			// Execute query and get item ids
			if ($uri_params != "" && $uri_params[0] != "")
			{
				$ids = JFactory::getDBO()->setQuery($sub_query)->loadColumn();

				if (count($ids))
				{
					$query .= " AND i.id IN(" . implode(",", $ids) . ")";
				} else
				{
					$query .= " AND i.id = 0";
				}
			}
		}

		return $query;
	}

	function getPagination()
	{
		jimport('joomla.html.pagination');
		$pagination = new JPagination($this->total_items, $this->limitstart, $this->limit);

		foreach ($_GET as $param => $value) {
			if (in_array($param, Array("id", "start", "option", "view", "task")))
			{
				continue;
			}

			if (is_array($value))
			{
				foreach ($value as $k => $val) {
					$pagination->setAdditionalUrlParam($param . "[{$k}]", $val);
				}
			} else
			{
				$pagination->setAdditionalUrlParam($param, $value);
			}
		}

		return $pagination;
	}

	function execPlugins(&$item)
	{
		$app = JFactory::getApplication('site');
		$params = $app->getParams();
		$dispatcher = JEventDispatcher::getInstance();
		$item->event = new stdClass;

		// Old plugins: Ensure that text property is available
		$item->text = $item->introtext;

		JPluginHelper::importPlugin('content');
		$dispatcher->trigger('onContentPrepare', array('com_content.category', &$item, &$item->params, 0));

		// Old plugins: Use processed text as introtext
		$item->introtext = $item->text;

		$item->params = new JRegistry($item->attribs);

		$results = $dispatcher->trigger('onContentBeforeDisplay', array('com_content.category', &$item, &$item->params, 0));
		$item->event->beforeDisplayContent = trim(implode("\n", $results));

		$results = $dispatcher->trigger('onContentAfterTitle', array('com_content.category', &$item, &$item->params, 0));
		$item->event->afterDisplayTitle = trim(implode("\n", $results));

		$results = $dispatcher->trigger('onContentAfterDisplay', array('com_content.category', &$item, &$item->params, 0));
		$item->event->afterDisplayContent = trim(implode("\n", $results));
	}

	function getAuthorById($id)
	{
		$db = JFactory::getDBO();
		$query = "SELECT * FROM #__users WHERE id = {$id}";
		$db->setQuery($query);

		return $db->loadObject();
	}

	function getCategoryById($id)
	{
		$db = JFactory::getDBO();
		$query = "SELECT * FROM #__categories WHERE id = {$id}";
		$db->setQuery($query);

		return $db->loadObject();
	}

	function getItemCategories($aItem)
	{
		$aCategories = array();
		$catids = array();

		if (!count($catids))
		{
			$catids = array($aItem->catid);
		}

		$catids = array_unique($catids);
		require_once JPATH_SITE . '/components/com_content/helpers/route.php';

		foreach ($catids as $id) {
			$category = $this->getCategoryById($id);
			$category->link = JRoute::_(ContentHelperRoute::getCategoryRoute($category->id));
			$aCategories[] = $category;
		}

		return $aCategories;
	}

	function saveSearchSession()
	{
		if (!$_GET['gsearch'])
		{
			return;
		}

		JFactory::getSession()->set("SaveSearchValues", $_GET);
	}

	function saveSearchStats()
	{
		$this->searchStatsTableCreate();
		$data = json_decode($_GET['data_stats']);
		$keyword = $data[0]->title;
		$search_link = $data[0]->link;

		// Save keyword
		$query = "SELECT search_count FROM #__content_search_stats WHERE url = '{$search_link}'";
		$count = intval(JFactory::getDBO()->setQuery($query)->loadResult());
		$config = JFactory::getConfig();
		$tzoffset = $config->get('offset');
		$date = JFactory::getDate('', $tzoffset)->format("Y-m-d H:i:s");

		if ($count)
		{
			$query = "UPDATE #__content_search_stats SET search_count = (search_count + 1), last_search_date = '{$date}' WHERE url = '{$search_link}'";
			JFactory::getDBO()->setQuery($query)->query();
			$query = "SELECT id FROM #__content_search_stats WHERE url = '{$search_link}'";
			$keyword_id = JFactory::getDBO()->setQuery($query)->loadResult();
		} else
		{
			$query = "INSERT INTO #__content_search_stats VALUES ('', '{$keyword}', '{$search_link}', '{$date}', 1)";
			JFactory::getDBO()->setQuery($query)->query();
			$keyword_id = JFactory::getDBO()->insertid();
		}

		// Save user
		$user = JFactory::getUser();
		$query = "SELECT search_count FROM #__content_search_stats_users WHERE keyword_id = {$keyword_id} AND user_id = {$user->id}";
		$count = intval(JFactory::getDBO()->setQuery($query)->loadResult());
		$ip_address = $_SERVER['REMOTE_ADDR'];

		if ($count)
		{
			$query = "UPDATE #__content_search_stats_users SET search_count = (search_count + 1), last_search_date = '{$date}', ip_address = '{$ip_address}' WHERE keyword_id = {$keyword_id} AND user_id = {$user->id}";
			JFactory::getDBO()->setQuery($query)->query();
		} else
		{
			$query = "INSERT INTO #__content_search_stats_users VALUES ('', {$user->id}, {$keyword_id}, '{$date}', 1, '{$ip_address}')";
			JFactory::getDBO()->setQuery($query)->query();
		}
	}

	function getStatsList($total = false)
	{
		$this->searchStatsTableCreate();
		$db = JFactory::getDBO();
		$limitstart = $this->limit;

		if ($total)
		{
			$query = "SELECT COUNT(DISTINCT id) FROM #__content_search_stats";
		} else
		{
			$query = "SELECT * FROM #__content_search_stats";
			$order = addslashes(JFactory::getApplication()->input->get("orderby", "last_search_date"));
			$query .= " ORDER BY {$order} DESC";
		}

		if ($total)
		{
			$db->setQuery($query);

			return $db->loadResult();
		} else
		{
			$db->setQuery($query, JFactory::getApplication()->input->getInt("limitstart", 0), 10);

			return $db->loadObjectList();
		}
	}

	function getStatsListPagination()
	{
		$total_items = $this->getStatsList(true);
		jimport('joomla.html.pagination');
		$pagination = new JPagination($total_items, JFactory::getApplication()->input->getInt("limitstart", 0), 10);

		foreach ($_GET as $param => $value) {
			if (in_array($param, Array("id", "start", "option", "view", "task", "limit", "featured")))
			{
				continue;
			}

			if (is_array($value))
			{
				foreach ($value as $k => $val) {
					$pagination->setAdditionalUrlParam($param . "[{$k}]", $val);
				}
			} else
			{
				$pagination->setAdditionalUrlParam($param, $value);
			}
		}

		return $pagination;
	}

	function getStatsKeywordList($total = false)
	{
		$this->searchStatsTableCreate();
		$db = JFactory::getDBO();
		$limitstart = $this->limit;
		$keyword_id = JFactory::getApplication()->input->getInt("id");

		if ($total)
		{
			$query = "SELECT COUNT(DISTINCT id) FROM #__content_search_stats_users WHERE keyword_id = {$keyword_id}";
		} else
		{
			$query = "SELECT * FROM #__content_search_stats_users WHERE keyword_id = {$keyword_id}";
			$order = addslashes(JFactory::getApplication()->input->get("orderby", "last_search_date"));
			$query .= " ORDER BY {$order} DESC";
		}

		if ($total)
		{
			$db->setQuery($query);

			return $db->loadResult();
		} else
		{
			$db->setQuery($query, JFactory::getApplication()->input->getInt("limitstart", 0), 10);

			return $db->loadObjectList();
		}
	}

	function getStatsKeywordListPagination()
	{
		$total_items = $this->getStatsKeywordList(true);
		jimport('joomla.html.pagination');
		$pagination = new JPagination($total_items, JFactory::getApplication()->input->getInt("limitstart", 0), 10);

		foreach ($_GET as $param => $value) {
			if (in_array($param, Array("id", "start", "option", "view", "task", "limit", "featured")))
			{
				continue;
			}

			if (is_array($value))
			{
				foreach ($value as $k => $val) {
					$pagination->setAdditionalUrlParam($param . "[{$k}]", $val);
				}
			} else
			{
				$pagination->setAdditionalUrlParam($param, $value);
			}
		}

		return $pagination;
	}

	function searchStatsTableCreate()
	{
		$query = "CREATE TABLE IF NOT EXISTS `#__content_search_stats` (";
		$query .= "`id` int(21) NOT NULL AUTO_INCREMENT PRIMARY KEY,";
		$query .= "`keyword` varchar(255) NOT NULL,";
		$query .= "`url` tinytext NOT NULL,";
		$query .= "`last_search_date` varchar(255) NOT NULL,";
		$query .= "`search_count` int(11) NOT NULL";
		$query .= ") ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
		JFactory::getDBO()->setQuery($query)->query();

		$query = "CREATE TABLE IF NOT EXISTS `#__content_search_stats_users` (";
		$query .= "`id` int(21) NOT NULL AUTO_INCREMENT PRIMARY KEY,";
		$query .= "`user_id` int(21) NOT NULL,";
		$query .= "`keyword_id` int(21) NOT NULL,";
		$query .= "`last_search_date` varchar(255) NOT NULL,";
		$query .= "`search_count` int(21) NOT NULL,";
		$query .= "`ip_address` varchar(255) NOT NULL";
		$query .= ") ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
		JFactory::getDBO()->setQuery($query)->query();
	}

	// N = Seemeilen, K = Kilometer, M = Meter, Meilen default

	function distance($lat1, $lon1, $lat2, $lon2, $unit = 'K')
	{
		$theta = $lon1 - $lon2;
		$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
		$dist = acos($dist);
		$dist = rad2deg($dist);
		$miles = $dist * 60 * 1.1515;
		$unit = strtoupper($unit);

		if ($unit == 'K')
		{
			return ( $miles * 1.609344 );
		} elseif ($unit == 'M')
		{
			return ( $miles * 1.609344 * 1000 );
		} elseif ($unit == 'N')
		{
			return ( $miles * 0.8684 );
		} else
		{
			return $miles;
		}
	}
}
