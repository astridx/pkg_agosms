<?php
defined('_JEXEC') or die('Restricted access');
 
class mod_easyfileuploaderInstallerScript
{
	/**
	 * Called before any type of action. Method to run before an install/update/uninstall method.
	 *
	 * @param   string  $route  Which action is happening (install|uninstall|discover_install)
	 * @param   JAdapterInstance  $parent  The object responsible for running this script
	 *
	 * @return  boolean  True on success
	 */
	function preflight($route, $adapter) 
	{
	}
 
	/**
	 * Called on installation
	 *
	 * @param   JAdapterInstance  $adapter  The object responsible for running this script
	 *
	 * @return  boolean  True on success
	 */
	function install($adapter) 
	{
	}
 
	/**
	 * Called on update
	 *
	 * @param   JAdapterInstance  $adapter  The object responsible for running this script
	 *
	 * @return  boolean  True on success
	 */
	function update($adapter) 
	{
	}
 
	/**
	 * Called on uninstallation
	 *
	 * @param   JAdapterInstance  $adapter  The object responsible for running this script
	 */
	function uninstall($adapter) 
	{
	}
 
	/**
	 * Called after any type of action. Method to run after an install/update/uninstall method.
	 *
	 * @param   string  $route  Which action is happening (install|uninstall|discover_install)
	 * @param   JAdapterInstance  $adapter  The object responsible for running this script
	 *
	 * @return  boolean  True on success
	 */
	function postflight($route, $adapter) 
	{
	}
}
