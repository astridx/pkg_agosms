<?php
/**
 * @package     Joomla.Site
 * @subpackage  pkg_agosms
 *
 * @copyright   Copyright (C) 2005 - 2019 Astrid Günther, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later;
 * @link        astrid-guenther.de
 */

\defined('_JEXEC') or die;

/**
 * Installation class to perform additional changes during install/uninstall/update
 *
 * @since  __BUMP_VERSION__
 */
class Pkg_AgosmsInstallerScript
{
	/**
	 * Extension script constructor.
	 *
	 * @since   __BUMP_VERSION__
	 */
	public function __construct()
	{
		$this->minimumJoomla = 'J4.0.0-rc2';
		$this->minimumPhp    = JOOMLA_MINIMUM_PHP;
	}
}
