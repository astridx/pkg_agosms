<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_agosms
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AgosmNamespace\Component\Agosms\Administrator\Table;

\defined('_JEXEC') or die;

use Joomla\CMS\Application\ApplicationHelper;
use Joomla\CMS\Table\Table;
use Joomla\CMS\Tag\TaggableTableInterface;
use Joomla\CMS\Tag\TaggableTableTrait;
use Joomla\Database\DatabaseDriver;
use Joomla\CMS\Language\Text;
use Joomla\Registry\Registry;
use Joomla\CMS\String\PunycodeHelper;
use Joomla\CMS\Factory;

/**
 * Agosms Table class.
 *
 * @since  __BUMP_VERSION__
 */
class AgosmTable extends Table implements TaggableTableInterface
{
	use TaggableTableTrait;

	/**
	 * Constructor
	 *
	 * @param   DatabaseDriver  $db  Database connector object
	 *
	 * @since   __BUMP_VERSION__
	 */
	public function __construct(DatabaseDriver $db)
	{
		$this->typeAlias = 'com_agosms.agosm';

		parent::__construct('#__agosms_details', 'id', $db);
	}

	/**
	 * Stores a agosm.
	 *
	 * @param   boolean  $updateNulls  True to update fields even if they are null.
	 *
	 * @return  boolean  True on success, false on failure.
	 *
	 * @since   __BUMP_VERSION__
	 */
	public function store($updateNulls = false)
	{
		// Transform the params field
		if (is_array($this->params)) {
			$registry = new Registry($this->params);
			$this->params = (string) $registry;
		}

		$date = Factory::getDate()->toSql();
		$userId = Factory::getUser()->id;

		// Set created date if not set.
		if (!(int) $this->created) {
			$this->created = $date;
		}

		if ($this->id) {
			// Existing item
			$this->modified_by = $userId;
			$this->modified    = $date;
		} else {
			// Field created_by field can be set by the user, so we don't touch it if it's set.
			if (empty($this->created_by)) {
				$this->created_by = $userId;
			}

			if (!(int) $this->modified) {
				$this->modified = $date;
			}

			if (empty($this->modified_by)) {
				$this->modified_by = $userId;
			}
		}

		// Store utf8 email as punycode
		//$this->email_to = PunycodeHelper::emailToPunycode($this->email_to);

		// Convert IDN urls to punycode
		//$this->webpage = PunycodeHelper::urlToPunycode($this->webpage);

		// Verify that the alias is unique
		$table = Table::getInstance('AgosmTable', __NAMESPACE__ . '\\', ['dbo' => $this->getDbo()]);

		if ($table->load(['alias' => $this->alias, 'catid' => $this->catid]) && ($table->id != $this->id || $this->id == 0)) {
			$this->alias = $this->alias . uniqid();
		}

		$success = parent::store($updateNulls);

		return $success;
	}

	/**
	 * Generate a valid alias from title / date.
	 * Remains public to be able to check for duplicated alias before saving
	 *
	 * @return  string
	 */
	public function generateAlias()
	{
		if (empty($this->alias)) {
			$this->alias = $this->name;
		}

		$this->alias = ApplicationHelper::stringURLSafe($this->alias, $this->language);

		if (trim(str_replace('-', '', $this->alias)) == '') {
			$this->alias = Factory::getDate()->format('Y-m-d-H-i-s');
		}

		return $this->alias;
	}

	/**
	 * Overloaded check function
	 *
	 * @return  boolean
	 *
	 * @see     Table::check
	 * @since   __BUMP_VERSION__
	 */
	public function check()
	{
		try {
			parent::check();
		} catch (\Exception $e) {
			$this->setError($e->getMessage());

			return false;
		}

		// Check the publish down date is not earlier than publish up.
		if ($this->publish_down > $this->_db->getNullDate() && $this->publish_down < $this->publish_up) {
			$this->setError(Text::_('JGLOBAL_START_PUBLISH_AFTER_FINISH'));

			return false;
		}

		// Set publish_up, publish_down to null if not set
		if (!$this->publish_up) {
			$this->publish_up = null;
		}

		if (!$this->publish_down) {
			$this->publish_down = null;
		}

		return true;
	}

	/**
	 * Get the type alias
	 *
	 * @return  string  The alias as described above
	 *
	 * @since   __BUMP_VERSION__
	 */
	public function getTypeAlias()
	{
		return $this->typeAlias;
	}
}
