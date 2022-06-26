<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_agosms
 *
 * @copyright   (C) 2020 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AgosmNamespace\Component\Agosms\Site\Model;

\defined('_JEXEC') or die;

use Exception;
use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Helper\TagsHelper;
use Joomla\CMS\Language\Associations;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\Table\Table;
use Joomla\Registry\Registry;
use Joomla\Utilities\ArrayHelper;

/**
 * Agosms Component Agosms Model
 *
 * @since  4.0.0
 */
class FormcustomusermarkerModel extends \AgosmNamespace\Component\Agosms\Administrator\Model\AgosmModel
{
	/**
	 * Model typeAlias string. Used for version history.
	 *
	 * @var    string
	 *
	 * @since  4.0.0
	 */
	public $typeAlias = 'com_agosms.agosmcustomusermarker';

	/**
	 * Name of the form
	 *
	 * @var    string
	 *
	 * @since  4.0.0
	 */
	protected $formName = 'formcustomusermarker';

	/**
	 * Method to get the row form.
	 *
	 * @param   array    $data      Data for the form.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  Form|boolean  A Form object on success, false on failure
	 *
	 * @since   4.0.0
	 */
	public function getForm($data = [], $loadData = true)
	{

		$app = Factory::getApplication();
		
		$layout = $app->input->get('layout');
		$layout = "editcustom";

		// Get the form.
		$form = $this->loadForm($this->typeAlias, $layout, ['control' => 'jform', 'load_data' => $loadData]);

		if (empty($form)) {
			return false;
		}

		// Prevent messing with article language and category when editing existing agosms with associations
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
	 * Method to get agosms data.
	 *
	 * @param   integer  $itemId  The id of the agosms.
	 *
	 * @return  mixed  Agosms item data object on success, false on failure.
	 *
	 * @throws  Exception
	 *
	 * @since   4.0.0
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
		$value = ArrayHelper::toObject($properties, \Joomla\CMS\Object\CMSObject::class);

		// Convert field to Registry.
		$value->params = new Registry($value->params);

		if ($itemId) {
			$value->tags = new TagsHelper;
			$value->tags->getTagIds($value->id, 'com_agosm.agosm');
			$value->metadata['tags'] = $value->tags;
		}

		return $value;
	}

	/**
	 * Get the return URL.
	 *
	 * @return  string  The return URL.
	 *
	 * @since   4.0.0
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
	 * @since   4.0.0
	 *
	 * @throws  Exception
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
	 * @since   4.0.0
	 *
	 * @throws  Exception
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
	 * @return  void
	 *
	 * @since   4.0.0
	 */
	protected function preprocessForm(Form $form, $data, $group = 'agosms')
	{
		$params = $this->getState()->get('params');

		if ($params && $params->get('enable_category') == 1 && $params->get('catid')) {
			$form->setFieldAttribute('catid', 'default', $params->get('catid'));
			$form->setFieldAttribute('catid', 'readonly', 'true');

			if (Multilanguage::isEnabled()) {
				$categoryId = (int) $params->get('catid');

				$db    = $this->getDbo();
				$query = $db->getQuery(true)
					->select($db->quoteName('language'))
					->from($db->quoteName('#__categories'))
					->where($db->quoteName('id') . ' = :categoryId')
					->bind(':categoryId', $categoryId, ParameterType::INTEGER);
				$db->setQuery($query);

				$result = $db->loadResult();

				if ($result != '*') {
					$form->setFieldAttribute('language', 'readonly', 'true');
					$form->setFieldAttribute('language', 'default', $result);
				}
			}
		}

		if (!Multilanguage::isEnabled()) {
			$form->setFieldAttribute('language', 'type', 'hidden');
			$form->setFieldAttribute('language', 'default', '*');
		}

		parent::preprocessForm($form, $data, $group);
	}

	/**
	 * Method to get a table object, load it if necessary.
	 *
	 * @param   string  $name     The table name. Optional.
	 * @param   string  $prefix   The class prefix. Optional.
	 * @param   array   $options  Configuration array for model. Optional.
	 *
	 * @return  bool|Table  A Table object
	 *
	 * @since   4.0.0

	 * @throws  Exception
	 */
	public function getTable($name = 'Agosm', $prefix = 'Administrator', $options = [])
	{
		return parent::getTable($name, $prefix, $options);
	}
}
