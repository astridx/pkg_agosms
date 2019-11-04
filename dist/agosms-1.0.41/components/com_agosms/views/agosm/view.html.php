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
 * HTML Agosm View class for the Agosms component
 *
 * @since  1.0.40
 */
class AgosmsViewAgosm extends JViewLegacy
{
	protected $item;

	protected $params;

	protected $state;

	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise an Error object.
	 *
	 * @since   1.0.40
	 */
	public function display($tpl = null)
	{
		$dispatcher = JEventDispatcher::getInstance();

		$this->item   = $this->get('Item');
		$this->state  = $this->get('State');
		$this->params = $this->state->get('params');

		// Create a shortcut for $item.
		$item = $this->item;

		$offset = $this->state->get('list.offset');

		$dispatcher->trigger('onContentPrepare', array ('com_agosms.agosm', &$item, &$item->params, $offset));

		$item->event = new stdClass;

		$results = $dispatcher->trigger('onContentAfterTitle', array('com_agosms.agosm', &$item, &$item->params, $offset));
		$item->event->afterDisplayTitle = trim(implode("\n", $results));

		$results = $dispatcher->trigger('onContentBeforeDisplay', array('com_agosms.agosm', &$item, &$item->params, $offset));
		$item->event->beforeDisplayContent = trim(implode("\n", $results));

		$results = $dispatcher->trigger('onContentAfterDisplay', array('com_agosms.agosm', &$item, &$item->params, $offset));
		$item->event->afterDisplayContent = trim(implode("\n", $results));

		parent::display($tpl);
	}
}
