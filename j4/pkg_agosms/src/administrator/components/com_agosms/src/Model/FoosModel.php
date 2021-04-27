<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_agosms
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AgosmNamespace\Component\Agosms\Administrator\Model;

\defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Language\Associations;
use Joomla\CMS\Factory;
use Joomla\Utilities\ArrayHelper;
use Joomla\Database\ParameterType;

/**
 * Methods supporting a list of agosm records.
 *
 * @since  __BUMP_VERSION__
 */
class AgosmsModel extends ListModel
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @see     \JControllerLegacy
	 *
	 * @since   __BUMP_VERSION__
	 */
	public function __construct($config = [])
	{

		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = [
				'id', 'a.id',
				'name', 'a.name',
				'catid', 'a.catid', 'category_id', 'category_title',
				'published', 'a.published',
				'access', 'a.access', 'access_level',
				'ordering', 'a.ordering',
				'featured', 'a.featured',
				'language', 'a.language', 'language_title',
				'publish_up', 'a.publish_up',
				'publish_down', 'a.publish_down',
				'tag',
			];

			$assoc = Associations::isEnabled();

			if ($assoc) {
				$config['filter_fields'][] = 'association';
			}
		}

		parent::__construct($config);
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return  \JDatabaseQuery
	 *
	 * @since   __BUMP_VERSION__
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$db->quoteName(
				explode(
					', ',
					$this->getState(
						'list.select',
						'a.id, a.name, a.catid' .
						', a.access' .
						', a.checked_out' .
						', a.checked_out_time' .
						', a.language' .
						', a.ordering' .
						', a.featured' .
						', a.state' .
						', a.published' .
						', a.publish_up, a.publish_down'
					)
				)
			)
		);

		$query->from($db->quoteName('#__agosms_details', 'a'));

		// Join over the asset groups.
		$query->select($db->quoteName('ag.title', 'access_level'))
			->join(
				'LEFT',
				$db->quoteName('#__viewlevels', 'ag') . ' ON ' . $db->quoteName('ag.id') . ' = ' . $db->quoteName('a.access')
			);

		// Join over the categories.
		$query->select($db->quoteName('c.title', 'category_title'))
			->join(
				'LEFT',
				$db->quoteName('#__categories', 'c') . ' ON ' . $db->quoteName('c.id') . ' = ' . $db->quoteName('a.catid')
			);

		// Join over the language
		$query->select($db->quoteName('l.title', 'language_title'))
			->select($db->quoteName('l.image', 'language_image'))
			->join(
				'LEFT',
				$db->quoteName('#__languages', 'l') . ' ON ' . $db->quoteName('l.lang_code') . ' = ' . $db->quoteName('a.language')
			);

		// Join over the associations.
		if (Associations::isEnabled()) {
			$subQuery = $db->getQuery(true)
				->select('COUNT(' . $db->quoteName('asso1.id') . ') > 1')
				->from($db->quoteName('#__associations', 'asso1'))
				->join('INNER', $db->quoteName('#__associations', 'asso2'), $db->quoteName('asso1.key') . ' = ' . $db->quoteName('asso2.key'))
				->where(
					[
						$db->quoteName('asso1.id') . ' = ' . $db->quoteName('a.id'),
						$db->quoteName('asso1.context') . ' = ' . $db->quote('com_agosms.item'),
					]
				);

			$query->select('(' . $subQuery . ') AS ' . $db->quoteName('association'));
		}

		// Join over the users for the checked out user.
		$query->select($db->quoteName('uc.name', 'editor'))
			->join(
				'LEFT',
				$db->quoteName('#__users', 'uc') . ' ON ' . $db->quoteName('uc.id') . ' = ' . $db->quoteName('a.checked_out')
			);

		// Filter on the language.
		if ($language = $this->getState('filter.language')) {
			$query->where($db->quoteName('a.language') . ' = ' . $db->quote($language));
		}

		// Filter by a single or group of tags.
		$tag = $this->getState('filter.tag');

		// Run simplified query when filtering by one tag.
		if (\is_array($tag) && \count($tag) === 1) {
			$tag = $tag[0];
		}

		if ($tag && \is_array($tag)) {
			$tag = ArrayHelper::toInteger($tag);

			$subQuery = $db->getQuery(true)
				->select('DISTINCT ' . $db->quoteName('content_item_id'))
				->from($db->quoteName('#__contentitem_tag_map'))
				->where(
					[
						$db->quoteName('tag_id') . ' IN (' . implode(',', $query->bindArray($tag)) . ')',
						$db->quoteName('type_alias') . ' = ' . $db->quote('com_agosms.agosm'),
					]
				);

			$query->join(
				'INNER',
				'(' . $subQuery . ') AS ' . $db->quoteName('tagmap'),
				$db->quoteName('tagmap.content_item_id') . ' = ' . $db->quoteName('a.id')
			);
		} else if ($tag = (int) $tag) {
			$query->join(
				'INNER',
				$db->quoteName('#__contentitem_tag_map', 'tagmap'),
				$db->quoteName('tagmap.content_item_id') . ' = ' . $db->quoteName('a.id')
			)
				->where(
					[
						$db->quoteName('tagmap.tag_id') . ' = :tag',
						$db->quoteName('tagmap.type_alias') . ' = ' . $db->quote('com_agosms.agosm'),
					]
				)
				->bind(':tag', $tag, ParameterType::INTEGER);
		}

		// Filter by access level.
		if ($access = $this->getState('filter.access')) {
			$query->where($db->quoteName('a.access') . ' = ' . (int) $access);
		}

		// Filter by published state
		$published = (string) $this->getState('filter.published');

		if (is_numeric($published)) {
			$query->where($db->quoteName('a.published') . ' = ' . (int) $published);
		} else if ($published === '') {
			$query->where('(' . $db->quoteName('a.published') . ' = 0 OR ' . $db->quoteName('a.published') . ' = 1)');
		}

		// Filter by a single or group of categories.
		$categoryId = $this->getState('filter.category_id');

		if (is_numeric($categoryId)) {
			$query->where($db->quoteName('a.catid') . ' = ' . (int) $categoryId);
		} else if (is_array($categoryId)) {
			$query->where($db->quoteName('a.catid') . ' IN (' . implode(',', ArrayHelper::toInteger($categoryId)) . ')');
		}

		// Filter by search in name.
		$search = $this->getState('filter.search');

		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('a.id = ' . (int) substr($search, 3));
			} else {
				$search = $db->quote('%' . str_replace(' ', '%', $db->escape(trim($search), true) . '%'));
				$query->where(
					'(' . $db->quoteName('a.name') . ' LIKE ' . $search . ')'
				);
			}
		}

		// Filter by featured.
		$featured = (string) $this->getState('filter.featured');

		if (in_array($featured, ['0','1'])) {
			$query->where($db->quoteName('a.featured') . ' = ' . (int) $featured);
		}

		// Add the list ordering clause.
		$orderCol = $this->state->get('list.ordering', 'a.name');
		$orderDirn = $this->state->get('list.direction', 'asc');

		if ($orderCol == 'a.ordering' || $orderCol == 'category_title') {
			$orderCol = $db->quoteName('c.title') . ' ' . $orderDirn . ', ' . $db->quoteName('a.ordering');
		}

		$query->order($db->escape($orderCol . ' ' . $orderDirn));

		return $query;
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc|desc).
	 *
	 * @return  void
	 *
	 * @since   __BUMP_VERSION__
	 */
	protected function populateState($ordering = 'a.name', $direction = 'asc')
	{
		$app = Factory::getApplication();
		$forcedLanguage = $app->input->get('forcedLanguage', '', 'cmd');

		// Adjust the context to support modal layouts.
		if ($layout = $app->input->get('layout')) {
			$this->context .= '.' . $layout;
		}

		// Adjust the context to support forced languages.
		if ($forcedLanguage) {
			$this->context .= '.' . $forcedLanguage;
		}

		// List state information.
		parent::populateState($ordering, $direction);

		// Force a language.
		if (!empty($forcedLanguage)) {
			$this->setState('filter.language', $forcedLanguage);
		}
	}
}
