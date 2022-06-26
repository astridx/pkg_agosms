<?php

/**
 * @package     Joomla.Site
 * @subpackage  com_agosms
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AgosmNamespace\Component\Agosms\Site\Model;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\Language\Text;

/**
 * Agosm model for the Joomla Agosms component.
 *
 * @since  __BUMP_VERSION__
 */
class AgosmcustomusermarkerModel extends BaseDatabaseModel
{
	/**
	 * @var string item
	 */
	protected $_item = null;

	/**
	 * Gets a agosm
	 *
	 * @param   integer  $pk  Id for the agosm
	 *
	 * @return  mixed Object or null
	 *
	 * @since   __BUMP_VERSION__
	 */
	public function getItem($pk = null)
	{
		$app = Factory::getApplication();

		if ($app->input->getInt('id') !== null) {
			$pk = $app->input->getInt('id');
		}

		if ($this->_item === null) {
			$this->_item = [];
		}

		if (!isset($this->_item[$pk])) {
			try {
				$db = $this->getDbo();
				$query = $db->getQuery(true);

				$query->select('*')
					->from($db->quoteName('#__agosms_details', 'a'))
					->where('a.id = ' . (int) $pk);

				$db->setQuery($query);
				$data = $db->loadObject();

				if (empty($data)) {
					throw new \Exception(Text::_('COM_AGOSMS_ERROR_AGOSM_NOT_FOUND'), 404);
				}

				$this->_item[$pk] = $data;
			} catch (\Exception $e) {
				$this->setError($e);
				$this->_item[$pk] = false;
			}
		}

		return $this->_item[$pk];
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return  void
	 *
	 * @since   __BUMP_VERSION__
	 */
	protected function populateState()
	{
		$app = Factory::getApplication();

		$this->setState('agosm.id', $app->input->getInt('id'));
		$this->setState('params', $app->getParams());
	}
}
