<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_agosms
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AgosmNamespace\Component\Agosms\Site\View\Form;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use AgosmNamespace\Component\Agosms\Administrator\Helper\AgosmHelper;

/**
 * HTML Agosm View class for the Agosm component
 *
 * @since  __DEPLOY_VERSION__
 */
class HtmlView extends BaseHtmlView
{
	/**
	 * @var \Joomla\CMS\Form\Form
	 * @since  __DEPLOY_VERSION__
	 */
	protected $form;

	/**
	 * @var object
	 * @since  __DEPLOY_VERSION__
	 */
	protected $item;

	/**
	 * @var string
	 * @since  __DEPLOY_VERSION__
	 */
	protected $return_page;

	/**
	 * @var string
	 * @since  __DEPLOY_VERSION__
	 */
	protected $pageclass_sfx;

	/**
	 * @var \Joomla\Registry\Registry
	 * @since  __DEPLOY_VERSION__
	 */
	protected $state;

	/**
	 * @var \Joomla\Registry\Registry
	 * @since  __DEPLOY_VERSION__
	 */
	protected $params;

	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise an Error object.
	 *
	 * @throws Exception
	 * @since  __DEPLOY_VERSION__
	 */
	public function display($tpl = null)
	{
		$user = Factory::getUser();
		$app  = Factory::getApplication();

		// Get model data.
		$this->state = $this->get('State');
		$this->item = $this->get('Item');
		$this->form = $this->get('Form');
		$this->return_page = $this->get('ReturnPage');

		if (empty($this->item->id)) {
			$authorised = $user->authorise('core.create', 'com_agosms') || count($user->getAuthorisedCategories('com_agosms', 'core.create'));
		} else {
			// Since we don't track these assets at the item level, use the category id.
			$canDo = AgosmHelper::getActions('com_agosms', 'category', $this->item->catid);
			$authorised = $canDo->get('core.edit') || ($canDo->get('core.edit.own') && $this->item->created_by == $user->id);
		}

		if ($authorised !== true) {
			$app->enqueueMessage(Text::_('JERROR_ALERTNOAUTHOR'), 'error');
			$app->setHeader('status', 403, true);

			return false;
		}

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			$app->enqueueMessage(implode("\n", $errors), 'error');

			return false;
		}

		// Create a shortcut to the parameters.
		$this->params = $this->state->params;

		// Escape strings for HTML output
		$this->pageclass_sfx = htmlspecialchars($this->params->get('pageclass_sfx'));

		// Override global params with agosm specific params
		$this->params->merge($this->item->params);

		// Propose current language as default when creating new agosm
		if (empty($this->item->id) && Multilanguage::isEnabled()) {
			$lang = Factory::getLanguage()->getTag();
			$this->form->setFieldAttribute('language', 'default', $lang);
		}

		$this->_prepareDocument();

		parent::display($tpl);
	}

	/**
	 * Prepares the document
	 *
	 * @return  void
	 *
	 * @throws Exception
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected function _prepareDocument()
	{
		$app   = Factory::getApplication();
		$menus = $app->getMenu();
		$title = null;

		// Because the application sets a default page title,
		// we need to get it from the menu item itself
		$menu = $menus->getActive();

		if ($menu) {
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		} else {
			$this->params->def('page_heading', Text::_('COM_AGOSMS_FORM_EDIT_AGOSM'));
		}

		$title = $this->params->def('page_title', Text::_('COM_AGOSMS_FORM_EDIT_AGOSM'));

		if ($app->get('sitename_pagetitles', 0) == 1) {
			$title = Text::sprintf('JPAGETITLE', $app->get('sitename'), $title);
		} else if ($app->get('sitename_pagetitles', 0) == 2) {
			$title = Text::sprintf('JPAGETITLE', $title, $app->get('sitename'));
		}

		$this->document->setTitle($title);

		$pathway = $app->getPathWay();
		$pathway->addItem($title, '');
	}
}
