<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_agosms
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AgosmNamespace\Component\Agosms\Site\Controller;

\defined('_JEXEC') or die;

use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;

/**
 * Agosms Component Controller
 *
 * @since  __BUMP_VERSION__
 */
class DisplayController extends BaseController
{
	/**
	 * Constructor.
	 *
	 * @param   array                $config   An optional associative array of configuration settings.
	 * Recognized key values include 'name', 'default_task', 'model_path', and
	 * 'view_path' (this list is not meant to be comprehensive).
	 * @param   MVCFactoryInterface  $factory  The factory.
	 * @param   CMSApplication       $app      The JApplication for the dispatcher
	 * @param   \JInput              $input    Input
	 *
	 * @since   __BUMP_VERSION__
	 */
	public function __construct($config = array(), MVCFactoryInterface $factory = null, $app = null, $input = null)
	{
		// Agosms frontpage Editor contacts proxying.
		$input = Factory::getApplication()->input;

		if ($input->get('view') === 'agosms' && $input->get('layout') === 'modal')
		{
			$config['base_path'] = JPATH_COMPONENT_ADMINISTRATOR;
		}

		parent::__construct($config, $factory, $app, $input);
	}

	/**
	 * Method to display a view.
	 *
	 * @param   boolean  $cachable   If true, the view output will be cached
	 * @param   array    $urlparams  An array of safe URL parameters and their variable types, for valid values see {@link \JFilterInput::clean()}.
	 *
	 * @return  static  This object to support chaining.
	 *
	 * @since   __BUMP_VERSION__
	 */
	public function display($cachable = false, $urlparams = [])
	{
		if (Factory::getApplication()->getUserState('com_agosms.agosm.data') === null)
		{
			$cachable = true;
		}

		$vName = $this->input->get('view', 'categories');
		$this->input->set('view', $vName);

		if ($this->app->getIdentity()->get('id'))
		{
			$cachable = false;
		}

		$safeurlparams = array('catid' => 'INT', 'id' => 'INT', 'cid' => 'ARRAY', 'year' => 'INT', 'month' => 'INT',
			'limit' => 'UINT', 'limitstart' => 'UINT', 'showall' => 'INT', 'return' => 'BASE64', 'filter' => 'STRING',
			'filter_order' => 'CMD', 'filter_order_Dir' => 'CMD', 'filter-search' => 'STRING', 'print' => 'BOOLEAN',
			'lang' => 'CMD');

		parent::display($cachable, $safeurlparams);

		return $this;
	}
}
