<?php
/**
 * @package     Joomla.Site
 * @subpackage  pkg_agosms
 *
 * @copyright   Copyright (C) 2005 - 2019 Astrid Günther, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later;
 * @link        astrid-guenther.de
 */

defined('JPATH_PLATFORM') or die;

jimport('joomla.form.fields.media');
/**
 * Provides a modal media selector including upload mechanism
 *
 * @since  1.6
 */
class JFormFieldAgosm extends JFormFieldMedia
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.6
	 */
	protected $type = 'agosm';

	/**
	 * Layout to render
	 *
	 * @var    string
	 * @since  3.5
	 */
	protected $layout = 'agosm';

	/**
	 * Get the layout paths
	 *
	 * @return  array
	 *
	 * @since   3.5
	 */
	protected function getLayoutPaths()
	{
		$template = JFactory::getApplication()->getTemplate();

		return array(
			JPATH_ADMINISTRATOR . '/templates/' . $template . '/html/layouts/plugins/system/stats',
			dirname(__DIR__) . '/layouts',
			JPATH_SITE . '/layouts'
		);
	}
}
