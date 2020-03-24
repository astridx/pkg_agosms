<?php
/**
 * @package     Joomla.Site
 * @subpackage  pkg_agosms
 *
 * @copyright   Copyright (C) 2005 - 2019 Astrid Günther, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later;
 * @link        astrid-guenther.de
 */

namespace Joomla\Component\Agosms\Administrator\Field;

defined('JPATH_PLATFORM') or die;

use Joomla\CMS\Form\Field\MediaField;

/**
 * Provides a modal media selector including upload mechanism
 *
 * @since  1.0.40
 */
class AgosmField extends MediaField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.0.40
	 */
	protected $type = 'agosm';

	/**
	 * Layout to render
	 *
	 * @var    string
	 * @since  1.0.40
	 */
	protected $layout = 'agosm';

	/**
	 * Get the layout paths
	 *
	 * @return  array
	 *
	 * @since   1.0.40
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
