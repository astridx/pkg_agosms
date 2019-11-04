<?php
/**
 * @package     Joomla.Site
 * @subpackage  pkg_agosm
 *
 * @copyright   Copyright (C) 2005 - 2018 Astrid Günther, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later;
 * @link        astrid-guenther.de
 */

defined('_JEXEC') or die;

/**
 * HTML View class for the Agosm component
 *
 * @since  1.0
 */
class AgosmsViewButton extends JViewLegacy
{
	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise an Error object.
	 *
	 * @since   1.0
	 */
	public function display($tpl = null)
	{
		$config = JComponentHelper::getParams('com_agosm');

		$this->session     = JFactory::getSession();
		$this->config      = $config;

		parent::display($tpl);
	}
}
