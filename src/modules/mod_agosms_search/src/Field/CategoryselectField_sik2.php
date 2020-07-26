<?php

/**
 * @package     Joomla.Site
 * @subpackage  pkg_agosms
 *
 * @copyright   Copyright (C) 2005 - 2019 Astrid Günther, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later;
 * @link        astrid-guenther.de
 */

namespace AG\Module\Agosmssearch\Site\Field;

defined('_JEXEC') or die;

use Joomla\CMS\Form\Field\CategoryField;

class CategoryselectField extends CategoryField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  __BUMP_VERSION__
	 */
	public $type = 'categoryselect';

}
