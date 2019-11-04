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
 * Content categories view.
 *
 * @since  1.0.40
 */
class AgosmsViewCategories extends JViewCategories
{
	protected $item = null;

	/**
	 * @var    string  Default title to use for page title
	 * @since  1.0.40
	 */
	protected $defaultPageTitle = 'COM_AGOSMS_DEFAULT_PAGE_TITLE';

	/**
	 * @var    string  The name of the extension for the category
	 * @since  1.0.40
	 */
	protected $extension = 'com_agosms';

	/**
	 * @var    string  The name of the view to link individual items to
	 * @since  1.0.40
	 */
	protected $viewName = 'agosm';

	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise a Error object.
	 */
	public function display($tpl = null)
	{
		$state		= $this->get('State');
		$items		= $this->get('Items');
		$parent		= $this->get('Parent');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseWarning(500, implode("\n", $errors));

			return false;
		}

		if ($items === false)
		{
			return JError::raiseError(404, JText::_('JGLOBAL_CATEGORY_NOT_FOUND'));
		}

		if ($parent == false)
		{
			return JError::raiseError(404, JText::_('JGLOBAL_CATEGORY_NOT_FOUND'));
		}

		$params = &$state->params;

		$items = array($parent->id => $items);

		// Escape strings for HTML output
		$this->pageclass_sfx = htmlspecialchars($params->get('pageclass_sfx'));

		$this->maxLevelcat = $params->get('maxLevelcat', -1);
		$this->params = &$params;
		$this->parent = &$parent;
		$this->items  = &$items;

		return parent::display($tpl);
	}
}
