<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_agosms
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AgosmNamespace\Component\Agosms\Administrator\Controller;

\defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\Utilities\ArrayHelper;
use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\MVC\Controller\AdminController;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\Input\Input;

/**
 * Agosms list controller class.
 *
 * @since  __BUMP_VERSION__
 */
class AgosmsController extends AdminController
{
	/**
	 * Constructor.
	 *
	 * @param   array                $config   An optional associative array of configuration settings.
	 * Recognized key values include 'name', 'default_task', 'model_path', and
	 * 'view_path' (this list is not meant to be comprehensive).
	 * @param   MVCFactoryInterface  $factory  The factory.
	 * @param   CMSApplication       $app      The JApplication for the dispatcher
	 * @param   Input                $input    Input
	 *
	 * @since   __BUMP_VERSION__
	 */
	public function __construct($config = [], MVCFactoryInterface $factory = null, $app = null, $input = null)
	{
		parent::__construct($config, $factory, $app, $input);

		$this->registerTask('unfeatured', 'featured');
	}

	/**
	 * Method to toggle the featured setting of a list of agosms.
	 *
	 * @return  void
	 *
	 * @since   __BUMP_VERSION__
	 */
	public function featured()
	{
		// Check for request forgeries
		$this->checkToken();

		$ids    = $this->input->get('cid', [], 'array');
		$values = ['featured' => 1, 'unfeatured' => 0];
		$task   = $this->getTask();
		$value  = ArrayHelper::getValue($values, $task, 0, 'int');

		$model  = $this->getModel();

		// Access checks.
		foreach ($ids as $i => $id) {
			$item = $model->getItem($id);

			if (!$this->app->getIdentity()->authorise('core.edit.state', 'com_agosms.category.' . (int) $item->catid)) {
				// Prune items that you can't change.
				unset($ids[$i]);
				$this->app->enqueueMessage(Text::_('JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED'), 'notice');
			}
		}

		if (empty($ids)) {
			$this->app->enqueueMessage(Text::_('COM_AGOSMS_NO_ITEM_SELECTED'), 'warning');
		} else {
			// Publish the items.
			if (!$model->featured($ids, $value)) {
				$this->app->enqueueMessage($model->getError(), 'warning');
			}

			if ($value == 1) {
				$message = Text::plural('COM_AGOSMS_N_ITEMS_FEATURED', count($ids));
			} else {
				$message = Text::plural('COM_AGOSMS_N_ITEMS_UNFEATURED', count($ids));
			}
		}

		$this->setRedirect('index.php?option=com_agosms&view=agosms', $message);
	}

	/**
	 * Proxy for getModel.
	 *
	 * @param   string  $name    The name of the model.
	 * @param   string  $prefix  The prefix for the PHP class name.
	 * @param   array   $config  Array of configuration parameters.
	 *
	 * @return  \Joomla\CMS\MVC\Model\BaseDatabaseModel
	 *
	 * @since   __BUMP_VERSION__
	 */
	public function getModel($name = 'Agosm', $prefix = 'Administrator', $config = ['ignore_request' => true])
	{
		return parent::getModel($name, $prefix, $config);
	}
}
