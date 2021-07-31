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
 * Agosm Component HTML Helper.
 *
 * @since  1.0.40
 */
class JHtmlIcon
{
	/**
	 * Create a link to create a new agosm
	 *
	 * @param   mixed  $agosm   Unused
	 * @param   mixed  $params  Unused
	 *
	 * @return  string
	 */
	public static function create($agosm, $params)
	{
		JHtml::_('bootstrap.tooltip');

		$uri = JUri::getInstance();
		$url = JRoute::_(AgosmsHelperRoute::getFormRoute(0, base64_encode($uri)));
		$text = JHtml::_('image', 'system/new.png', JText::_('JNEW'), null, true);
		$button = JHtml::_('link', $url, $text);

		return '<span class="hasTooltip" title="' . JHtml::tooltipText('COM_AGOSMS_FORM_CREATE_AGOSM') . '">' . $button . '</span>';
	}

	/**
	 * Create a link to edit an existing agosm
	 *
	 * @param   object                     $agosm    Agosm data
	 * @param   \Joomla\Registry\Registry  $params   Item params
	 * @param   array                      $attribs  Unused
	 *
	 * @return  string
	 */
	public static function edit($agosm, $params, $attribs = [])
	{
		$uri = JUri::getInstance();

		if ($params && $params->get('popup')) {
			return;
		}

		if ($agosm->state < 0) {
			return;
		}

		JHtml::_('bootstrap.tooltip');

		$url	= AgosmsHelperRoute::getFormRoute($agosm->id, base64_encode($uri));
		$icon	= $agosm->state ? 'edit.png' : 'edit_unpublished.png';
		$text	= JHtml::_('image', 'system/' . $icon, JText::_('JGLOBAL_EDIT'), null, true);

		if ($agosm->state == 0) {
			$overlib = JText::_('JUNPUBLISHED');
		} else {
			$overlib = JText::_('JPUBLISHED');
		}

		$date = JHtml::_('date', $agosm->created);
		$author = $agosm->created_by_alias ? $agosm->created_by_alias : $agosm->author;

		$overlib .= '&lt;br /&gt;';
		$overlib .= $date;
		$overlib .= '&lt;br /&gt;';
		$overlib .= htmlspecialchars($author, ENT_COMPAT, 'UTF-8');

		$button = JHtml::_('link', JRoute::_($url), $text);

		return '<span class="hasTooltip" title="' . JHtml::tooltipText('COM_AGOSMS_EDIT') . ' :: ' . $overlib . '">' . $button . '</span>';
	}
}
