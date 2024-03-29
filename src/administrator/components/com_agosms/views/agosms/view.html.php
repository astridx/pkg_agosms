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
 * View class for a list of agosms.
 *
 * @since  1.0.40
 */
class AgosmsViewAgosms extends JViewLegacy
{
	protected $items;

	protected $pagination;

	protected $state;

	/**
	 * Display the view.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise an Error object.
	 */
	public function display($tpl = null)
	{
		$this->state         = $this->get('State');
		$this->items         = $this->get('Items');
		$this->pagination    = $this->get('Pagination');
		$this->filterForm    = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');

		// Modal layout doesn't need the submenu.
		if ($this->getLayout() !== 'modal') {
			AgosmsHelper::addSubmenu('agosms');
		}

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));

			return false;
		}

		// We don't need toolbar in the modal layout.
		if ($this->getLayout() !== 'modal') {
			$this->addToolbar();
			$this->sidebar = JHtmlSidebar::render();
		} else {
			// In article associations modal we need to remove language filter if forcing a language.
			// We also need to change the category filter to show show categories with All or the forced language.
			if ($forcedLanguage = JFactory::getApplication()->input->get('forcedLanguage', '', 'CMD')) {
				// If the language is forced we can't allow to select the language, so transform the language selector filter into an hidden field.
				$languageXml = new SimpleXMLElement('<field name="language" type="hidden" default="' . $forcedLanguage . '" />');
				$this->filterForm->setField($languageXml, 'filter', true);

				// Also, unset the active language filter so the search tools is not open by default with this filter.
				unset($this->activeFilters['language']);

				// One last changes needed is to change the category filter to just show categories with All language or with the forced language.
				$this->filterForm->setFieldAttribute('category_id', 'language', '*,' . $forcedLanguage, 'filter');
			}
		}

		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   1.0.40
	 */
	protected function addToolbar()
	{
		require_once JPATH_COMPONENT . '/helpers/agosms.php';

		$state = $this->get('State');
		$canDo = JHelperContent::getActions('com_agosms', 'category', $state->get('filter.category_id'));
		$user  = JFactory::getUser();

		// Get the toolbar object instance
		$bar = JToolBar::getInstance('toolbar');

		JToolbarHelper::title(JText::_('COM_AGOSMS_MANAGER_AGOSMS'), 'link agosms');

		if (count($user->getAuthorisedCategories('com_agosms', 'core.create')) > 0) {
			JToolbarHelper::addNew('agosm.add');
		}

		if ($canDo->get('core.edit') || $canDo->get('core.edit.own')) {
			JToolbarHelper::editList('agosm.edit');
		}

		if ($canDo->get('core.edit.state')) {
			JToolbarHelper::publish('agosms.publish', 'JTOOLBAR_PUBLISH', true);
			JToolbarHelper::unpublish('agosms.unpublish', 'JTOOLBAR_UNPUBLISH', true);

			JToolbarHelper::archiveList('agosms.archive');
			JToolbarHelper::checkin('agosms.checkin');
		}

		if ($state->get('filter.published') == -2 && $canDo->get('core.delete')) {
			JToolbarHelper::deleteList('JGLOBAL_CONFIRM_DELETE', 'agosms.delete', 'JTOOLBAR_EMPTY_TRASH');
		} else if ($canDo->get('core.edit.state')) {
			JToolbarHelper::trash('agosms.trash');
		}

		// Add a batch button
		if ($user->authorise('core.create', 'com_agosms') && $user->authorise('core.edit', 'com_agosms')
			&& $user->authorise('core.edit.state', 'com_agosms')) {
			JHtml::_('bootstrap.modal', 'collapseModal');
			$title = JText::_('JTOOLBAR_BATCH');

			// Instantiate a new JLayoutFile instance and render the batch button
			$layout = new JLayoutFile('joomla.toolbar.batch');

			$dhtml = $layout->render(['title' => $title]);
			$bar->appendButton('Custom', $dhtml, 'batch');
		}

		if ($user->authorise('core.admin', 'com_agosms') || $user->authorise('core.options', 'com_agosms')) {
			JToolbarHelper::preferences('com_agosms');
		}

		JToolbarHelper::help('JHELP_COMPONENTS_AGOSMS_LINKS');
	}

	/**
	 * Returns an array of fields the table can be sorted by
	 *
	 * @return  array  Array containing the field name to sort by as the key and display text as value
	 *
	 * @since   1.0.40
	 */
	protected function getSortFields()
	{
		return [
			'a.ordering' => JText::_('JGRID_HEADING_ORDERING'),
			'a.state' => JText::_('JSTATUS'),
			'a.title' => JText::_('JGLOBAL_TITLE'),
			'a.access' => JText::_('JGRID_HEADING_ACCESS'),
			'a.hits' => JText::_('JGLOBAL_HITS'),
			'a.language' => JText::_('JGRID_HEADING_LANGUAGE'),
			'a.id' => JText::_('JGRID_HEADING_ID')
		];
	}
}
