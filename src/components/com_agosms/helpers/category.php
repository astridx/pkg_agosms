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
 * Agosms Component Category Tree.
 *
 * @since  1.0.40
 */
class AgosmsCategories extends JCategories
{
	/**
	 * Constructor
	 *
	 * @param   array  $options  Array of options
	 *
	 * @since   1.0.40
	 */
	public function __construct($options = [])
	{
		$options['table'] = '#__agosms';
		$options['extension'] = 'com_agosms';

		parent::__construct($options);
	}
}
