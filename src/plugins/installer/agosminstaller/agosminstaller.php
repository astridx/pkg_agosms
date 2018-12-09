  <?php
/**
 * @package     Joomla.Site
 * @subpackage  pkg_agosm
 *
 * @copyright   Copyright (C) 2005 - 2018 Astrid Günther, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later;
 * @link        astrid-guenther.de
 */
defined('_JEXEC') or die;

/**
 * FolderInstaller Plugin.
 *
 * @see    https://github.com/joomla/joomla-cms/pull/2769
 *
 * @since  1.0.0
 */
class PlgInstallerAgosmInstaller extends JPlugin
{
	/**
	 * @var    String  base update url, to decide whether to process the event or not
	 * @since  1.0.0
	 */
	private $baseUrl = 'http://astrid-guenther.de/updates/agosm/';

	/**
	 * @var    String  your extension identifier, to retrieve its params
	 * @since  1.0.0
	 */
	private $extension = 'com_agosm';

	/**
	 * Handle adding credentials to package download request
	 *
	 * @param   string  &$url  url from which package is going to be downloaded
	 *
	 * @return  boolean true  Always true, regardless of success
	 *
	 * @since   1.0.0
	 */
	public function onInstallerBeforePackageDownload(&$url)
	{
		// Are we trying to update our extension?
		if (strpos($url, $this->baseUrl) !== 0)
		{
			return true;
		}

		$component = JComponentHelper::getComponent($this->extension);
		$access = $component->params->get('update_credentials_access', '');

		$file = str_replace($this->baseUrl, '', $url);

		$url = "www.astrid-guenther.de/updates/agosm/" . $access . "/" . $file;

		return true;
	}
}
