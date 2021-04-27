<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_agosms
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AgosmNamespace\Component\Agosms\Site\Model;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Language\Associations;
use Joomla\CMS\Language\Multilanguage;
use Joomla\Registry\Registry;
use Joomla\Utilities\ArrayHelper;

/**
 * Agosm Component Agosm Model
 *
 * @since  __DEPLOY_VERSION__
 */
class FormModel extends \AgosmNamespace\Component\Agosms\Administrator\Model\AgosmModel
{
	/**
	 * Model typeAlias string. Used for version history.
	 *
	 * @var  string
	 * @since  __DEPLOY_VERSION__
	 */
	public $typeAlias = 'com_agosms.agosm';

	/**
	 * Name of the form
	 *
	 * @var string
	 * @since  __DEPLOY_VERSION__
	 */
	protected $formName = 'form';

	/**
	 * Method to get the row form.
	 *
	 * @param   array    $data      Data for the form.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  \JForm|boolean  A \JForm object on success, false on failure
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getForm($data = [], $loadData = true)
	{
		$form = parent::getForm($data, $loadData);

		// Prevent messing with article language and category when editing existing agosm with associations
		if ($id = $this->getState('agosm.id') && Associations::isEnabled()) {
			$associations = Associations::getAssociations('com_agosms', '#__agosms_details', 'com_agosms.item', $id);

			// Make fields read only
			if (!empty($associations)) {
				$form->setFieldAttribute('language', 'readonly', 'true');
				$form->setFieldAttribute('language', 'filter', 'unset');
			}
		}

		return $form;
	}

	/**
	 * Method to get agosm data.
	 *
	 * @param   integer  $itemId  The id of the agosm.
	 *
	 * @return  mixed  Agosm item data object on success, false on failure.
	 *
	 * @throws  Exception
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getItem($itemId = null)
	{
		$itemId = (int) (!empty($itemId)) ? $itemId : $this->getState('agosm.id');

		// Get a row instance.
		$table = $this->getTable();

		// Attempt to load the row.
		try {
			if (!$table->load($itemId)) {
				return false;
			}
		} catch (Exception $e) {
			Factory::getApplication()->enqueueMessage($e->getMessage());

			return false;
		}

		$properties = $table->getProperties();
		$value      = ArrayHelper::toObject($properties, 'JObject');

		// Convert field to Registry.
		$value->params = new Registry($value->params);

		return $value;
	}

	/**
	 * Get the return URL.
	 *
	 * @return  string  The return URL.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getReturnPage()
	{
		return base64_encode($this->getState('return_page'));
	}

	/**
	 * Method to save the form data.
	 *
	 * @param   array  $data  The form data.
	 *
	 * @return  boolean  True on success.
	 *
	 * @throws Exception
	 * @since   __DEPLOY_VERSION__
	 */
	public function save($data)
	{
		// Associations are not edited in frontend ATM so we have to inherit them
		if (Associations::isEnabled() && !empty($data['id'])
			&& $associations = Associations::getAssociations('com_agosms', '#__agosms_details', 'com_agosms.item', $data['id'])) {
			foreach ($associations as $tag => $associated) {
				$associations[$tag] = (int) $associated->id;
			}

			$data['associations'] = $associations;
		}

		return parent::save($data);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return  void
	 *
	 * @throws  Exception
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	protected function populateState()
	{
		$app = Factory::getApplication();

		// Load state from the request.
		$pk = $app->input->getInt('id');
		$this->setState('agosm.id', $pk);

		$this->setState('agosm.catid', $app->input->getInt('catid'));

		$return = $app->input->get('return', null, 'base64');
		$this->setState('return_page', base64_decode($return));

		// Load the parameters.
		$params = $app->getParams();
		$this->setState('params', $params);

		$this->setState('layout', $app->input->getString('layout'));
	}

	/**
	 * Allows preprocessing of the JForm object.
	 *
	 * @param   Form    $form   The form object
	 * @param   array   $data   The data to be merged into the form object
	 * @param   string  $group  The plugin group to be executed
	 *
	 * @return  Form
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	protected function preprocessForm(Form $form, $data, $group = 'agosm')
	{
		if (!Multilanguage::isEnabled()) {
			$form->setFieldAttribute('language', 'type', 'hidden');
			$form->setFieldAttribute('language', 'default', '*');
		}

		return parent::preprocessForm($form, $data, $group);
	}

	/**
	 * Method to get a table object, load it if necessary.
	 *
	 * @param   string  $name     The table name. Optional.
	 * @param   string  $prefix   The class prefix. Optional.
	 * @param   array   $options  Configuration array for model. Optional.
	 *
	 * @return  Table  A Table object
	 *
	 * @since   __DEPLOY_VERSION__
	 * @throws  \Exception
	 */
	public function getTable($name = 'Agosm', $prefix = 'Administrator', $options = [])
	{
		return parent::getTable($name, $prefix, $options);
	}
}
