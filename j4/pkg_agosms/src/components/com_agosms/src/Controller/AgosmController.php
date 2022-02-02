<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_agosms
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AgosmNamespace\Component\Agosms\Site\Controller;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\Utilities\ArrayHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Language\Multilanguage;

/**
 * Controller for single agosm view
 *
 * @since  __DEPLOY_VERSION__
 */
class AgosmController extends FormController
{
	/**
	 * The URL view item variable.
	 *
	 * @var    string
	 * @since  __DEPLOY_VERSION__
	 */
	protected $view_item = 'form';

	/**
	 * The URL view list variable.
	 *
	 * @var    string
	 * @since  4.0.0
	 */
	protected $view_list = 'featured';

	/**
	 * Method to get a model object, loading it if required.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  \Joomla\CMS\MVC\Model\BaseDatabaseModel  The model.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getModel($name = 'form', $prefix = '', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, array('ignore_request' => false));
	}

	/**
	 * Method override to check if you can add a new record.
	 *
	 * @param   array  $data  An array of input data.
	 *
	 * @return  boolean
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	protected function allowAdd($data = [])
	{
		if ($categoryId = ArrayHelper::getValue($data, 'catid', $this->input->getInt('catid'), 'int')) {
			$user = Factory::getUser();

			// If the category has been passed in the data or URL check it.
			return $user->authorise('core.create', 'com_agosms.category.' . $categoryId);
		}

		// In the absence of better information, revert to the component permissions.
		return parent::allowAdd();
	}

	/**
	 * Method override to check if you can edit an existing record.
	 *
	 * @param   array   $data  An array of input data.
	 * @param   string  $key   The name of the key for the primary key; default is id.
	 *
	 * @return  boolean
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	protected function allowEdit($data = [], $key = 'id')
	{
		$recordId = (int) isset($data[$key]) ? $data[$key] : 0;

		if (!$recordId) {
			return false;
		}

		// Need to do a lookup from the model.
		$record     = $this->getModel()->getItem($recordId);
		$categoryId = (int) $record->catid;

		if ($categoryId) {
			$user = Factory::getUser();

			// The category has been set. Check the category permissions.
			if ($user->authorise('core.edit', $this->option . '.category.' . $categoryId)) {
				return true;
			}

			// Fallback on edit.own.
			if ($user->authorise('core.edit.own', $this->option . '.category.' . $categoryId)) {
				return ($record->created_by == $user->id);
			}

			return false;
		}

		// Since there is no asset tracking, revert to the component permissions.
		return parent::allowEdit($data, $key);
	}

	/**
	 * Method to save a record.
	 *
	 * @param   string  $key     The name of the primary key of the URL variable.
	 * @param   string  $urlVar  The name of the URL variable if different from the primary key (sometimes required to avoid router collisions).
	 *
	 * @return  boolean  True if successful, false otherwise.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function save($key = null, $urlVar = null)
	{
		$result = parent::save($key, $urlVar);

		$app = Factory::getApplication();
		$id = $app->input->getInt('id');

		// Load the parameters.
		$params   = $app->getParams();
		$menuitem = (int) $params->get('redirect_menuitem');

		// Check for redirection after submission when creating a new article only
		if ($menuitem > 0 && $id == 0)
		{
			$lang = '';

			if (Multilanguage::isEnabled())
			{
				$item = $app->getMenu()->getItem($menuitem);
				$lang = !is_null($item) && $item->language != '*' ? '&lang=' . $item->language : '';
			}

			// If ok, redirect to the return page.
			if ($result)
			{
				$this->setRedirect(Route::_('index.php?Itemid=' . $menuitem . $lang, false));
			}
		}
		else
		{
			// If ok, redirect to the return page.
			if ($result)
			{
				$this->setRedirect(Route::_($this->getReturnPage(), false));
			}
		}

		if ($result) {
			$this->setMessage(Text::_('COM_AGOSMS_SAVE_SUCCESS'));
		} else {
			$this->setMessage(Text::_('COM_AGOSMS_SAVE_NO_SUCCESS'));
		}

		return $result;
	}

	/**
	 * Method to cancel an edit.
	 *
	 * @param   string  $key  The name of the primary key of the URL variable.
	 *
	 * @return  boolean  True if access level checks pass, false otherwise.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function cancel($key = null)
	{
		$result = parent::cancel($key);

		// Load the parameters.
		$app = Factory::getApplication();
		$params = $app->getParams();

		$customCancelRedir = (bool) $params->get('custom_cancel_redirect');

		if ($customCancelRedir)
		{
			$cancelMenuitemId = (int) $params->get('cancel_redirect_menuitem');

			if ($cancelMenuitemId > 0)
			{
				$item = $app->getMenu()->getItem($cancelMenuitemId);
				$lang = '';

				if (Multilanguage::isEnabled())
				{
					$lang = !is_null($item) && $item->language != '*' ? '&lang=' . $item->language : '';
				}

				// Redirect to the user specified return page.
				$redirlink = $item->link . $lang . '&Itemid=' . $cancelMenuitemId;
			}
			else
			{
				// Redirect to the same article submission form (clean form).
				$redirlink = $app->getMenu()->getActive()->link . '&Itemid=' . $app->getMenu()->getActive()->id;
			}
		}
		else
		{
			$menuitemId = (int) $params->get('redirect_menuitem');

			if ($menuitemId > 0)
			{
				$lang = '';
				$item = $app->getMenu()->getItem($menuitemId);

				if (Multilanguage::isEnabled())
				{
					$lang = !is_null($item) && $item->language != '*' ? '&lang=' . $item->language : '';
				}

				// Redirect to the general (redirect_menuitem) user specified return page.
				$redirlink = $item->link . $lang . '&Itemid=' . $menuitemId;
			}
			else
			{
				// Redirect to the return page.
				$redirlink = $this->getReturnPage();
			}
		}

		$this->setRedirect(Route::_($redirlink, false));

		return $result;
	}

	/**
	 * Gets the URL arguments to append to an item redirect.
	 *
	 * @param   integer  $recordId  The primary key id for the item.
	 * @param   string   $urlVar    The name of the URL variable for the id.
	 *
	 * @return  string    The arguments to append to the redirect URL.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	protected function getRedirectToItemAppend($recordId = 0, $urlVar = 'id')
	{
		// Need to override the parent method completely.
		$tmpl = $this->input->get('tmpl');

		$append = '';

		// Setup redirect info.
		if ($tmpl)
		{
			$append .= '&tmpl=' . $tmpl;
		}

		$append .= '&layout=editcustom';

		$append .= '&' . $urlVar . '=' . (int) $recordId;

		$itemId = $this->input->getInt('Itemid');
		$return = $this->getReturnPage();
		$catId  = $this->input->getInt('catid');

		if ($itemId)
		{
			$append .= '&Itemid=' . $itemId;
		}

		if ($catId)
		{
			$append .= '&catid=' . $catId;
		}

		if ($return)
		{
			$append .= '&return=' . base64_encode($return);
		}

		return $append;
	}

	/**
	 * Get the return URL.
	 *
	 * If a "return" variable has been passed in the request
	 *
	 * @return  string    The return URL.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	protected function getReturnPage()
	{
		$return = $this->input->get('return', null, 'base64');

		if (empty($return) || !Uri::isInternal(base64_decode($return)))
		{
			return Uri::base();
		}

		return base64_decode($return);
	}
}
