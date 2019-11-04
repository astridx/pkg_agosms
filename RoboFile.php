<?php
/**
 * @package     Jorobo
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Joomla\Jorobo\Tasks\loadTasks;

if (!defined('JPATH_BASE'))
{
	define('JPATH_BASE', __DIR__);
}

// PSR-4 Autoload by composer
require_once JPATH_BASE . '/vendor/autoload.php';

/**
 * Sample RoboFile - adjust to your needs, extend your own
 */
class RoboFile extends \Robo\Tasks
{
	use loadTasks;

	/**
	 * Initialize Robo
	 */
	public function __construct()
	{
		$this->stopOnFail(true);
	}

	/**
	 * Map into Joomla installation.
	 *
	 * @param   String  $target  The target joomla instance
	 *
	 * @return  void
	 */
	public function map($target)
	{
		(new \Joomla\Jorobo\Tasks\Map($target))->run();
	}

	/**
	 * Map into Joomla installation.
	 *
	 * @param   String  $target  The target joomla instance
	 *
	 * @return  void
	 */
	public function umap($target)
	{
		(new \Joomla\Jorobo\Tasks\Umap($target))->run();
	}

        /**
	 * Build the joomla extension package
	 *
	 * @param   array  $params  Additional params
	 *
	 * @return  void
	 */
	public function build($params = ['dev' => false])
	{
		(new \Joomla\Jorobo\Tasks\Build($params))->run();
	}

	/**
	 * Generate an extension skeleton - not implemented yet
	 *
	 * @param   array  $extensions  Extensions to build (com_xy, mod_xy)
	 *
	 * @return  void
	 */
	public function generate($extensions)
	{
		(new \Joomla\Jorobo\Tasks\Generate($extensions))->run();
	}

	/**
	 * Update copyright headers for this project. (Set the text up in the jorobo.ini)
	 *
	 * @return  void
	 */
	public function headers()
	{
		(new \Joomla\Jorobo\Tasks\CopyrightHeader)->run();
	}

	/**
	 * Bump Version placeholder __DEPLOY_VERSION__ in this project. (Set the version up in the jorobo.ini)
	 *
	 * @return  void
	 *
	 * @since   1.0.0
	 */
	public function bump()
	{
		(new \Joomla\Jorobo\Tasks\BumpVersion())->run();
	}

	/**
	 * Copy files in media folder and minify all css and js files this folder. (Set the file type up in the jorobo.ini)
	 *
	 * @return  void
	 *
	 * @since   1.0.0
	 */
	public function minify()
	{
		(new \Joomla\Jorobo\Tasks\Minify())->run();
	}
}
