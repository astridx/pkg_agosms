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
 * Agosms Component Controller
 *
 * @since  1.5
 */
class AgosmsController extends JControllerLegacy
{
	/**
	 * Method to display a view.
	 *
	 * @param   boolean  $cacheable  If true, the view output will be cached
	 * @param   array    $urlparams  An array of safe url parameters and their variable types,
	 *                               for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return  AgosmsController  This object to support chaining.
	 *
	 * @since   1.5
	 */
	public function display($cacheable = false, $urlparams = false)
	{
		// Huh? Why not just put that in the constructor?
		$cacheable = true;

		/**
		 * Set the default view name and format from the Request.
		 * Note we are using w_id to avoid collisions with the router and the return page.
		 * Frontend is a bit messier than the backend.
		 */
		$id    = $this->input->getInt('w_id');
		$vName = $this->input->get('view', 'categories');
		$this->input->set('view', $vName);

		if (JFactory::getUser()->id ||($this->input->getMethod() == 'POST' && $vName == 'categories'))
		{
			$cacheable = false;
		}

		$safeurlparams = array(
			'id'               => 'INT',
			'limit'            => 'UINT',
			'limitstart'       => 'UINT',
			'filter_order'     => 'CMD',
			'filter_order_Dir' => 'CMD',
			'lang'             => 'CMD'
		);

		// Check for edit form.
		if ($vName == 'form' && !$this->checkEditId('com_agosms.edit.agosm', $id))
		{
			// Somehow the person just went to the form - we don't allow that.
			return JError::raiseError(403, JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
		}

		return parent::display($cacheable, $safeurlparams);
	}
}
