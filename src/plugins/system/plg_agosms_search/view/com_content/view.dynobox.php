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

class ArticlesViewGoodSearch extends JViewCategory
{

	function display()
	{
		require_once JPATH_SITE . '/plugins/system/plg_agosms_search/models/com_content/model.php';
		$model = new ArticlesModelGoodSearch;

		// Set limit for model
		$model->limitstart = 0;
		$model->limit = 99999999; // Need all items

		$items = $model->getItems();

		if (count($items))
		{
			$filter_fields = JRequest::getVar("field_type");
			$filter_ids = JRequest::getVar("field_id");
			$filter_vals = Array();

			$items_ids = array();

			foreach ($items as $item)
			{
				$items_ids[] = $item->id;
			}

			foreach ($filter_ids as $k => $field_id)
			{
				$field_values = $this->getFieldAvailableValues($field_id, $items_ids);

				foreach ($field_values as $val)
				{
					$val = strip_tags(trim($val));
					$filter_vals[$k][] = $val;
				}

				$filter_vals[$k] = array_values(array_unique($filter_vals[$k]));
				natsort($filter_vals[$k]);
				ksort($filter_vals[$k]);
			}

			require_once JPATH_SITE . '/modules/mod_agosms_search/helper.php';
			$helper = new modAgosmsSearchHelper;

			foreach ($items as $key => $item)
			{
				// Get categories and tags
				$tags = $helper->getItemTags($item->id);

				foreach ($filter_fields as $k => $field)
				{
					if ($field == 'category')
					{
						$filter_vals['categories'][] = $item->catid;
						$filter_vals['categories'] = array_values(array_unique($filter_vals['categories']));
					}

					if ($field == 'tag')
					{
						foreach ($tags as $tag)
						{
							$filter_vals['tags'][] = $tag->name;
						}

						$filter_vals['tags'] = array_values(array_unique($filter_vals['tags']));
					}
				}
			}

			$fields = Array();

			foreach ($filter_fields as $k => $field)
			{
				$fields[$k] = new JObject;
				$fields[$k]->id = $filter_ids[$k];
				$fields[$k]->type = $field;
				$fields[$k]->name = $field;

				if ($filter_ids[$k] < 10000)
				{
					// Extrafields
					$fields[$k]->name = 'field' . $filter_ids[$k];
				}

				if ($filter_fields[$k] == 'category')
				{
					$fields[$k]->values = $filter_vals['categories'];
				}
				elseif ($filter_fields[$k] == 'tag')
				{
					$fields[$k]->values = $filter_vals['tags'];
				}
				else
				{
					$fields[$k]->values = $filter_vals[$k];
				}
			}

			echo json_encode($fields);
		}
		else
		{
			echo "0";
		}

		exit;
	}

	function getFieldAvailableValues($field_id, $items_ids)
	{
		$query = "SELECT DISTINCT(f.value) AS value FROM #__fields_values as f WHERE f.item_id IN(" . implode(",", $items_ids) . ") AND f.field_id = {$field_id}";
		$values = JFactory::getDBO()->setQuery($query)->loadColumn();

		return $values;
	}
}
