<?php
/**
 * @package     Joomla.Site
 * @subpackage  pkg_agosms
 *
 * @copyright   Copyright (C) 2005 - 2019 Astrid Günther, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later;
 * @link        astrid-guenther.de
 */

defined('_JEXEC') or die('');

/**
 * Script file for mod_agosms_search
 *
 * @since  1.0.40
 */
class Mod_AgosmsearchInstallerScript
{
	/**
	 * Method to install mod_agosms_search
	 *
	 * @param   JInstallerAdapterFile  $parent  The class calling this method
	 *
	 * @since  1.0.40
	 *
	 * @return  void
	 */
	public function install($parent)
	{
		$parent->getParent()->setRedirectURL('index.php?option=com_modules');
	}

	/**
	 * Method to uninstall mod_agosms_search
	 *
	 * @param   JInstallerAdapterFile  $parent  The class calling this method
	 *
	 * @since  1.0.40
	 *
	 * @return  void
	 */
	public function uninstall($parent)
	{
		echo '<p>' . JText::_('MOD_AGOSMSSEARCH_UNINSTALL') . '</p>';
	}

	/**
	 * Method to update mod_agosms_search
	 *
	 * @param   JInstallerAdapterFile  $parent  The class calling this method
	 *
	 * @since  1.0.40
	 *
	 * @return  void
	 */
	public function update($parent)
	{
		echo '<p>' . JText::sprintf('MOD_AGOSMSSEARCH_UPDATE', $parent->get('manifest')->version) . '</p>';
	}

	/**
	 * Method to preflight mod_agosms_search (before install and update)
	 *
	 * @param   string                 $type    The type of change (install, update, discover_install)
	 * @param   JInstallerAdapterFile  $parent  The class calling this method
	 *
	 * @since  1.0.40
	 *
	 * @return  void
	 */
	public function preflight($type, $parent)
	{
		echo '<p>' . JText::_('MOD_AGOSMSSEARCH_PREFLIGHT') . '</p>';
	}

	/**
	 * Method to postflight mod_agosms_search (after install or update)
	 *
	 * @param   string                 $type    The type of change (install, update, discover_install)
	 * @param   JInstallerAdapterFile  $parent  The class calling this method
	 *
	 * @since  1.0.40
	 *
	 * @return  void
	 */
	public function postflight($type, $parent)
	{
		echo '<p>' . JText::_('MOD_AGOSMSSEARCH_POSTFLIGHT') . '</p>';
	}
}
