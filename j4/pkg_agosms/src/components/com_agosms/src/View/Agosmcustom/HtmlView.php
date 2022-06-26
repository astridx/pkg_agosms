<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_agosms
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AgosmNamespace\Component\Agosms\Site\View\Agosmcustom;

\defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Factory;
use Joomla\Registry\Registry;
use Joomla\CMS\Language\Text;

/**
 * HTML Agosms View class for the Agosm component
 *
 * @since  __BUMP_VERSION__
 */
class HtmlView extends BaseHtmlView
{
	/**
	 * The page parameters
	 *
	 * @var    \Joomla\Registry\Registry|null
	 * @since  __BUMP_VERSION__
	 */
	protected $params = null;

	/**
	 * The item model state
	 *
	 * @var    \Joomla\Registry\Registry
	 * @since  __BUMP_VERSION__
	 */
	protected $state;

	/**
	 * The item object details
	 *
	 * @var    \JObject
	 * @since  __BUMP_VERSION__
	 */
	protected $item;

	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise an Error object.
	 */
	public function display($tpl = null)
	{
		$app = Factory::getApplication();
		$user = Factory::getUser();
		$state = $this->get('State');
		$this->item = $item = $this->get('Item');
		$this->form = $this->get('Form');
		$params = $state->get('params');
		$item->params = new Registry(json_decode($item->params));


		$temp = clone $params;

		$active = $app->getMenu()->getActive();

		// If the current view is the active item and a agosm view for this agosm, then the menu item params take priority
		if ($active
			&& $active->component == 'com_agosms'
			&& isset($active->query['view'], $active->query['id'])
			&& $active->query['view'] == 'agosmcustom'
			&& $active->query['id'] == $item->id) {
			$this->menuItemMatchContact = true;

			// Load layout from active query (in case it is an alternative menu item)
			if (isset($active->query['layout'])) {
				$this->setLayout($active->query['layout']);
			}
			// Check for alternative layout of agosm
			else if ($layout = $item->params->get('agosm_layout')) {
				$this->setLayout($layout);
			}

			$item->params->merge($temp);
		} else {
			// Merge so that agosm params take priority
			$temp->merge($item->params);
			$item->params = $temp;

			if ($layout = $item->params->get('agosm_layout')) {
				$this->setLayout($layout);
			}
		}

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			throw new GenericDataException(implode("\n", $errors), 500);
		}


		$offset = $state->get('list.offset');
		$app->triggerEvent('onContentPrepare', ['com_agosms.agosmcustom', &$item, &$item->params, $offset]);

		// Store the events for later
		$item->event = new \stdClass;
		$results = $app->triggerEvent('onContentAfterTitle', ['com_agosms.agosmcustom', &$item, &$item->params, $offset]);
		$item->event->afterDisplayTitle = trim(implode("\n", $results));

		$results = $app->triggerEvent('onContentBeforeDisplay', ['com_agosms.agosmcustom', &$item, &$item->params, $offset]);
		$item->event->beforeDisplayContent = trim(implode("\n", $results));

		$results = $app->triggerEvent('onContentAfterDisplay', ['com_agosms.agosmcustom', &$item, &$item->params, $offset]);
		$item->event->afterDisplayContent = trim(implode("\n", $results));


		return parent::display($tpl);
	}
}
