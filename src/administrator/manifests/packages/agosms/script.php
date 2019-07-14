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
 * Installation class to perform additional changes during install/uninstall/update
 *
 * @since  1.0.40
 */
class Pkg_AgosmsInstallerScript extends JInstallerScript
{
	/**
	 * Extension script constructor.
	 *
	 * @since   1.0.40
	 */
	public function __construct()
	{
		$this->minimumJoomla = '3.7.0';
		$this->minimumPhp    = JOOMLA_MINIMUM_PHP;
	}
}
