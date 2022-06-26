<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_agosms
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AgosmNamespace\Component\Agosms\Administrator\Service\HTML;

\defined('_JEXEC') or die;

use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use AgosmNamespace\Component\Agosms\Site\Helper\RouteHelper;
use Joomla\Registry\Registry;

/**
 * Content Component HTML Helper
 *
 * @since  __DEPLOY_VERSION__
 */
class Iconcustom
{
	/**
	 * The application
	 *
	 * @var    CMSApplication
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	private $application;

	/**
	 * Service constructor
	 *
	 * @param   CMSApplication  $application  The application
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function __construct(CMSApplication $application)
	{
		$this->application = $application;
	}

	/**
	 * Method to generate a link to the create item page for the given category
	 *
	 * @param   object    $category  The category information
	 * @param   Registry  $params    The item parameters
	 * @param   array     $attribs   Optional attributes for the link
	 *
	 * @return  string  The HTML markup for the create item link
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public static function create($category, $params, $attribs = [])
	{
		$uri = Uri::getInstance();

		$url = 'index.php?option=com_agosms&task=agosm.add&return=' . base64_encode($uri) . '&id=0&catid=' . $category->id;

		$text = LayoutHelper::render('joomla.content.icons.create', ['params' => $params, 'legacy' => false]);

		// Add the button classes to the attribs array
		if (isset($attribs['class'])) {
			$attribs['class'] .= ' btn btn-primary';
		} else {
			$attribs['class'] = 'btn btn-primary';
		}

		$button = HTMLHelper::_('link', Route::_($url), $text, $attribs);

		$output = '<span class="hasTooltip" title="' . HTMLHelper::_('tooltipText', 'COM_AGOSMS_CREATE_AGOSM') . '">' . $button . '</span>';

		return $output;
	}

	/**
	 * Display an edit icon for the agosm.
	 *
	 * This icon will not display in a popup window, nor if the agosm is trashed.
	 * Edit access checks must be performed in the calling code.
	 *
	 * @param   object    $agosm  The agosm information
	 * @param   Registry  $params   The item parameters
	 * @param   array     $attribs  Optional attributes for the link
	 * @param   boolean   $legacy   True to use legacy images, false to use icomoon based graphic
	 *
	 * @return  string   The HTML for the agosm edit icon.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function edit($agosm, $params, $attribs = [], $legacy = false)
	{
		$user = Factory::getUser();
		$uri  = Uri::getInstance();

		// Ignore if in a popup window.
		if ($params && $params->get('popup')) {
			return '';
		}

		// Ignore if the state is negative (trashed).
		if ($agosm->published < 0) {
			return '';
		}

		// Set the link class
		$attribs['class'] = 'dropdown-item';

		// Show checked_out icon if the agosm is checked out by a different user
		if (property_exists($agosm, 'checked_out')
			&& property_exists($agosm, 'checked_out_time')
			&& $agosm->checked_out > 0
			&& $agosm->checked_out != $user->get('id')) {
			$checkoutUser = Factory::getUser($agosm->checked_out);
			$date         = HTMLHelper::_('date', $agosm->checked_out_time);
			$tooltip      = Text::_('JLIB_HTML_CHECKED_OUT') . ' :: ' . Text::sprintf('COM_AGOSMS_CHECKED_OUT_BY', $checkoutUser->name)
				. ' <br /> ' . $date;

			$text = LayoutHelper::render('joomla.content.icons.edit_lock', ['tooltip' => $tooltip, 'legacy' => $legacy]);

			$output = HTMLHelper::_('link', '#', $text, $attribs);

			return $output;
		}

		if (!isset($agosm->slug)) {
			$agosm->slug = "";
		}

		$agosmUrl = RouteHelper::getAgosmRoute($agosm->slug, $agosm->catid, $agosm->language);
		$url = $agosmUrl . '&task=agosm.edit&id=' . $agosm->id . '&return=' . base64_encode($uri);

		if ($agosm->published == 0) {
			$overlib = Text::_('JUNPUBLISHED');
		} else {
			$overlib = Text::_('JPUBLISHED');
		}

		if (!isset($agosm->created)) {
			$date = HTMLHelper::_('date', 'now');
		} else {
			$date = HTMLHelper::_('date', $agosm->created);
		}

		if (!isset($created_by_alias) && !isset($agosm->created_by)) {
			$author = '';
		} else {
			$author = $agosm->created_by_alias ?: Factory::getUser($agosm->created_by)->name;
		}

		$overlib .= '&lt;br /&gt;';
		$overlib .= $date;
		$overlib .= '&lt;br /&gt;';
		$overlib .= Text::sprintf('COM_AGOSMS_WRITTEN_BY', htmlspecialchars($author, ENT_COMPAT, 'UTF-8'));

		$icon = $agosm->published ? 'edit' : 'eye-slash';

		if (strtotime($agosm->publish_up) > strtotime(Factory::getDate())
			|| ((strtotime($agosm->publish_down) < strtotime(Factory::getDate())) && $agosm->publish_down != Factory::getDbo()->getNullDate())) {
			$icon = 'eye-slash';
		}

		$text = '<span class="hasTooltip fa fa-' . $icon . '" title="'
			. HTMLHelper::tooltipText(Text::_('COM_AGOSMS_EDIT_AGOSM'), $overlib, 0, 0) . '"></span> ';
		$text .= Text::_('JGLOBAL_EDIT');

		$attribs['title'] = Text::_('COM_AGOSMS_EDIT_AGOSM');
		$output           = HTMLHelper::_('link', Route::_($url), $text, $attribs);

		return $output;
	}
}
