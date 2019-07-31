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
 * Agosms helper.
 *
 * @since  1.0.40
 */
class AgosmsHelper extends JHelperContent
{
	/**
	 * Configure the Linkbar.
	 *
	 * @param   string  $vName  The name of the active view.
	 *
	 * @return  void
	 *
	 * @since   1.0.40
	 */
	public static function addSubmenu($vName = 'agosms')
	{
		JHtmlSidebar::addEntry(
			JText::_('COM_AGOSMS_SUBMENU_AGOSMS'),
			'index.php?option=com_agosms&view=agosms',
			$vName == 'agosms'
		);

		JHtmlSidebar::addEntry(
			JText::_('COM_AGOSMS_SUBMENU_CATEGORIES'),
			'index.php?option=com_categories&extension=com_agosms',
			$vName == 'categories'
		);

		if (JComponentHelper::isEnabled('com_fields') && JComponentHelper::getParams('com_agosms')->get('custom_fields_enable', '1'))
		{
			JHtmlSidebar::addEntry(
				JText::_('JGLOBAL_FIELDS'),
				'index.php?option=com_fields&context=com_agosms.agosm',
				$vName == 'fields.fields'
			);

			JHtmlSidebar::addEntry(
				JText::_('JGLOBAL_FIELD_GROUPS'),
				'index.php?option=com_fields&view=groups&context=com_agosms.agosm',
				$vName == 'fields.groups'
			);
		}
	}

	/**
	 * Adds Count Items for WebLinks Category Manager.
	 *
	 * @param   stdClass[]  &$items  The agosms category objects.
	 *
	 * @return  stdClass[]  The agosms category objects.
	 *
	 * @since   1.0.40
	 */
	public static function countItems(&$items)
	{
		$db = JFactory::getDbo();

		foreach ($items as $item)
		{
			$item->count_trashed     = 0;
			$item->count_archived    = 0;
			$item->count_unpublished = 0;
			$item->count_published   = 0;

			$query = $db->getQuery(true)
				->select('state, COUNT(*) AS count')
				->from($db->qn('#__agosms'))
				->where($db->qn('catid') . ' = ' . (int) $item->id)
				->group('state');

			$db->setQuery($query);
			$agosms = $db->loadObjectList();

			foreach ($agosms as $agosm)
			{
				if ($agosm->state == 1)
				{
					$item->count_published = $agosm->count;
				}
				elseif ($agosm->state == 0)
				{
					$item->count_unpublished = $agosm->count;
				}
				elseif ($agosm->state == 2)
				{
					$item->count_archived = $agosm->count;
				}
				elseif ($agosm->state == -2)
				{
					$item->count_trashed = $agosm->count;
				}
			}
		}

		return $items;
	}

	/**
	 * Adds Count Items for Tag Manager.
	 *
	 * @param   stdClass[]  &$items     The agosm tag objects
	 * @param   string      $extension  The name of the active view.
	 *
	 * @return  stdClass[]
	 *
	 * @since   1.0.40
	 */
	public static function countTagItems(&$items, $extension)
	{
		$db = JFactory::getDbo();

		foreach ($items as $item)
		{
			$item->count_trashed = 0;
			$item->count_archived = 0;
			$item->count_unpublished = 0;
			$item->count_published = 0;

			$query = $db->getQuery(true);
			$query->select('published as state, count(*) AS count')
				->from($db->qn('#__contentitem_tag_map') . 'AS ct ')
				->where('ct.tag_id = ' . (int) $item->id)
				->where('ct.type_alias =' . $db->q($extension))
				->join('LEFT', $db->qn('#__categories') . ' AS c ON ct.content_item_id=c.id')
				->group('state');

			$db->setQuery($query);
			$agosms = $db->loadObjectList();

			foreach ($agosms as $agosm)
			{
				if ($agosm->state == 1)
				{
					$item->count_published = $agosm->count;
				}

				if ($agosm->state == 0)
				{
					$item->count_unpublished = $agosm->count;
				}

				if ($agosm->state == 2)
				{
					$item->count_archived = $agosm->count;
				}

				if ($agosm->state == -2)
				{
					$item->count_trashed = $agosm->count;
				}
			}
		}

		return $items;
	}
}
