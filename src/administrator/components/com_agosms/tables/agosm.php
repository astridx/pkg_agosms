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
 * Agosm Table class
 *
 * @since  1.0.40
 */
class AgosmsTableAgosm extends JTable
{
	/**
	 * Ensure the params and metadata in json encoded in the bind method
	 *
	 * @var    array
	 * @since  1.0.40
	 */
	protected $_jsonEncode = ['params', 'metadata', 'images'];

	/**
	 * Constructor
	 *
	 * @param   JDatabaseDriver  &$db  A database connector object
	 *
	 * @since   1.0.40
	 */
	public function __construct(&$db)
	{
		parent::__construct('#__agosms', 'id', $db);

		// Set the published column alias
		$this->setColumnAlias('published', 'state');

		JTableObserverTags::createObserver($this, ['typeAlias' => 'com_agosms.agosm']);
		JTableObserverContenthistory::createObserver($this, ['typeAlias' => 'com_agosms.agosm']);
	}

	/**
	 * Overload the store method for the Agosms table.
	 *
	 * @param   boolean  $updateNulls  Toggle whether null values should be updated.
	 *
	 * @return  boolean  True on success, false on failure.
	 *
	 * @since   1.0.40
	 */
	public function store($updateNulls = false)
	{
		$date = JFactory::getDate();
		$user = JFactory::getUser();

		$this->modified = $date->toSql();

		if ($this->id) {
			// Existing item
			$this->modified_by = $user->id;
		} else {
			// New agosm. A agosm created and created_by field can be set by the user,
			// so we don't touch either of these if they are set.
			if (!(int) $this->created) {
				$this->created = $date->toSql();
			}

			if (empty($this->created_by)) {
				$this->created_by = $user->id;
			}
		}

		// Set publish_up to null date if not set
		if (!$this->publish_up) {
			$this->publish_up = $this->getDbo()->getNullDate();
		}

		// Set publish_down to null date if not set
		if (!$this->publish_down) {
			$this->publish_down = $this->getDbo()->getNullDate();
		}

		// Verify that the alias is unique
		$table = JTable::getInstance('Agosm', 'AgosmsTable');

		if ($table->load(['language' => $this->language, 'alias' => $this->alias, 'catid' => $this->catid])
			&& ($table->id != $this->id || $this->id == 0)) {
			$this->setError(JText::_('COM_AGOSMS_ERROR_UNIQUE_ALIAS'));

			return false;
		}

		// Convert IDN urls to punycode
		$this->url = JStringPunycode::urlToPunycode($this->url);

		return parent::store($updateNulls);
	}

	/**
	 * Overloaded check method to ensure data integrity.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   1.0.40
	 */
	public function check()
	{
		if (JFilterInput::checkAttribute(['href', $this->url])) {
			$this->setError(JText::_('COM_AGOSMS_ERR_TABLES_PROVIDE_URL'));

			return false;
		}

		// Check for valid name
		if (trim($this->title) == '') {
			$this->setError(JText::_('COM_AGOSMS_ERR_TABLES_TITLE'));

			return false;
		}

		// Check for existing name
		$db = $this->getDbo();

		$query = $db->getQuery(true)
			->select($db->quoteName('id'))
			->from($db->quoteName('#__agosms'))
			->where($db->quoteName('title') . ' = ' . $db->quote($this->title))
			->where($db->quoteName('language') . ' = ' . $db->quote($this->language))
			->where($db->quoteName('catid') . ' = ' . (int) $this->catid);
		$db->setQuery($query);

		$xid = (int) $db->loadResult();

		if ($xid && $xid != (int) $this->id) {
			$this->setError(JText::_('COM_AGOSMS_ERR_TABLES_NAME'));

			return false;
		}

		if (empty($this->alias)) {
			$this->alias = $this->title;
		}

		$this->alias = JApplicationHelper::stringURLSafe($this->alias, $this->language);

		if (trim(str_replace('-', '', $this->alias)) == '') {
			$this->alias = JFactory::getDate()->format("Y-m-d-H-i-s");
		}

		// Check the publish down date is not earlier than publish up.
		if ($this->publish_down > $db->getNullDate() && $this->publish_down < $this->publish_up) {
			$this->setError(JText::_('JGLOBAL_START_PUBLISH_AFTER_FINISH'));

			return false;
		}

		/*
		 * Clean up keywords -- eliminate extra spaces between phrases
		 * and cr (\r) and lf (\n) characters from string
		 */
		if (!empty($this->metakey)) {
			// Array of characters to remove
			$bad_characters = ["\n", "\r", "\"", "<", ">"];
			$after_clean = JString::str_ireplace($bad_characters, "", $this->metakey);
			$keys = explode(',', $after_clean);
			$clean_keys = [];

			foreach ($keys as $key) {
				// Ignore blank keywords
				if (trim($key)) {
					$clean_keys[] = trim($key);
				}
			}

			// Put array back together delimited by ", "
			$this->metakey = implode(", ", $clean_keys);
		}

		return true;
	}
}
