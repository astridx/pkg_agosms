<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_agosms
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
\defined('_JEXEC') or die;

use Joomla\CMS\Application\ApplicationHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Installer\InstallerAdapter;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Log\Log;
use Joomla\CMS\Table\Table;
use Joomla\CMS\Installer\InstallerScript;

/**
 * Script file of Agosm Component
 *
 * @since  __BUMP_VERSION__
 */
class Com_AgosmsInstallerScript extends InstallerScript
{
	/**
	 * Minimum Joomla version to check
	 *
	 * @var    string
	 * @since  __BUMP_VERSION__
	 */
	private $minimumJoomlaVersion = 'J4.0.0';

	/**
	 * Minimum PHP version to check
	 *
	 * @var    string
	 * @since  __BUMP_VERSION__
	 */
	private $minimumPHPVersion = JOOMLA_MINIMUM_PHP;

	/**
	 * Method to install the extension
	 *
	 * @param   InstallerAdapter  $parent  The class calling this method
	 *
	 * @return  boolean  True on success
	 *
	 * @since  __BUMP_VERSION__
	 */
	public function install($parent): bool
	{
		echo Text::_('COM_AGOSMS_INSTALLERSCRIPT_INSTALL');

		$db = Factory::getDbo();
		$alias   = ApplicationHelper::stringURLSafe('AgosmUncategorised');

		// Initialize a new category.
		$category = Table::getInstance('Category');

		$data = [
			'extension' => 'com_agosms',
			'title' => 'AgosmUncategorised',
			'alias' => $alias . '(en-GB)',
			'description' => '',
			'published' => 1,
			'access' => 1,
			'params' => '{"target":"","image":""}',
			'metadesc' => '',
			'metakey' => '',
			'metadata' => '{"page_title":"","author":"","robots":""}',
			'created_time' => Factory::getDate()->toSql(),
			'created_user_id' => (int) $this->getAdminId(),
			'language' => 'en-GB',
			'rules' => [],
			'parent_id' => 1,
		];

		$category->setLocation(1, 'last-child');

		// Bind the data to the table
		if (!$category->bind($data)) {
			return false;
		}

		// Check to make sure our data is valid.
		if (!$category->check()) {
			return false;
		}

		// Store the category.
		/*if (!$category->store(true)) {
			return false;
		}*/

		$this->addDashboardMenu('agosms', 'agosms');

		return true;
	}

	/**
	 * Method to uninstall the extension
	 *
	 * @param   InstallerAdapter  $parent  The class calling this method
	 *
	 * @return  boolean  True on success
	 *
	 * @since  __BUMP_VERSION__
	 */
	public function uninstall($parent): bool
	{
		echo Text::_('COM_AGOSMS_INSTALLERSCRIPT_UNINSTALL');

		return true;
	}

	/**
	 * Method to update the extension
	 *
	 * @param   InstallerAdapter  $parent  The class calling this method
	 *
	 * @return  boolean  True on success
	 *
	 * @since  __BUMP_VERSION__
	 *
	 */
	public function update($parent): bool
	{
		echo Text::_('COM_AGOSMS_INSTALLERSCRIPT_UPDATE');

		$this->addDashboardMenu('agosm', 'agosm');

		return true;
	}

	/**
	 * Function called before extension installation/update/removal procedure commences
	 *
	 * @param   string            $type    The type of change (install, update or discover_install, not uninstall)
	 * @param   InstallerAdapter  $parent  The class calling this method
	 *
	 * @return  boolean  True on success
	 *
	 * @since  __BUMP_VERSION__
	 *
	 * @throws Exception
	 */
	public function preflight($type, $parent): bool
	{
		if ($type !== 'uninstall') {
			// Check for the minimum PHP version before continuing
			if (!empty($this->minimumPHPVersion) && version_compare(PHP_VERSION, $this->minimumPHPVersion, '<')) {
				Log::add(
					Text::sprintf('JLIB_INSTALLER_MINIMUM_PHP', $this->minimumPHPVersion),
					Log::WARNING,
					'jerror'
				);

				return false;
			}

			// Check for the minimum Joomla version before continuing
			if (!empty($this->minimumJoomlaVersion) && version_compare(JVERSION, $this->minimumJoomlaVersion, '<')) {
				Log::add(
					Text::sprintf('JLIB_INSTALLER_MINIMUM_JOOMLA', $this->minimumJoomlaVersion),
					Log::WARNING,
					'jerror'
				);

				return false;
			}
		}

		echo Text::_('COM_AGOSMS_INSTALLERSCRIPT_PREFLIGHT');

		return true;
	}

	/**
	 * Function called after extension installation/update/removal procedure commences
	 *
	 * @param   string            $type    The type of change (install, update or discover_install, not uninstall)
	 * @param   InstallerAdapter  $parent  The class calling this method
	 *
	 * @return  boolean  True on success
	 *
	 * @since  __BUMP_VERSION__
	 *
	 */
	public function postflight($type, $parent)
	{
		echo Text::_('COM_AGOSMS_INSTALLERSCRIPT_POSTFLIGHT');

		$this->saveContentTypes();

		return true;
	}

	/**
	 * Retrieve the admin user id.
	 *
	 * @return  integer|boolean  One Administrator ID.
	 *
	 * @since   __BUMP_VERSION__
	 */
	private function getAdminId()
	{
		$db    = Factory::getDbo();
		$query = $db->getQuery(true);

		// Select the admin user ID
		$query
			->clear()
			->select($db->quoteName('u') . '.' . $db->quoteName('id'))
			->from($db->quoteName('#__users', 'u'))
			->join(
				'LEFT',
				$db->quoteName('#__user_usergroup_map', 'map')
				. ' ON ' . $db->quoteName('map') . '.' . $db->quoteName('user_id')
				. ' = ' . $db->quoteName('u') . '.' . $db->quoteName('id')
			)
			->join(
				'LEFT',
				$db->quoteName('#__usergroups', 'g')
				. ' ON ' . $db->quoteName('map') . '.' . $db->quoteName('group_id')
				. ' = ' . $db->quoteName('g') . '.' . $db->quoteName('id')
			)
			->where(
				$db->quoteName('g') . '.' . $db->quoteName('title')
				. ' = ' . $db->quote('Super Users')
			);

		$db->setQuery($query);
		$id = $db->loadResult();

		if (!$id || $id instanceof \Exception) {
			return false;
		}

		return $id;
	}

	/**
	 * Adding content_type for tags.
	 *
	 * @return  integer|boolean  One Administrator ID.
	 *
	 * @since   __BUMP_VERSION__
	 */
	private function saveContentTypes()
	{
		$table = Table::getInstance('Contenttype', 'JTable');

		$table->load(['type_alias' => 'com_agosms.agosm']);

		$tablestring = '{
			"special": {
			  "dbtable": "#__agosms",
			  "key": "id",
			  "type": "AgosmTable",
			  "prefix": "AgosmNamespace\\\\Component\\\\Agosms\\\\Administrator\\\\Table\\\\",
			  "config": "array()"
			},
			"common": {
			  "dbtable": "#__ucm_content",
			  "key": "ucm_id",
			  "type": "Corecontent",
			  "prefix": "JTable",
			  "config": "array()"
			}
		  }';

		$fieldmapping = '{
			"common": {
			  "core_content_item_id": "id",
			  "core_title": "name",
			  "core_state": "published",
			  "core_alias": "alias",
			  "core_publish_up": "publish_up",
			  "core_publish_down": "publish_down",
			  "core_access": "access",
			  "core_params": "params",
			  "core_featured": "featured",
			  "core_language": "language",
			  "core_ordering": "ordering",
			  "core_catid": "catid",
			  "asset_id": "null"
			},
			"special": {
			}
		  }';

		$contenttype = [];
		$contenttype['type_id'] = ($table->type_id) ? $table->type_id : 0;
		$contenttype['type_title'] = 'Agosms';
		$contenttype['type_alias'] = 'com_agosms.agosm';
		$contenttype['table'] = $tablestring;
		$contenttype['rules'] = '';
		$contenttype['router'] = 'RouteHelper::getAgosmRoute';
		$contenttype['field_mappings'] = $fieldmapping;
		$contenttype['content_history_options'] = '';

		$table->save($contenttype);

		return;
	}
}
