<?php
/**
 * @package     Joomla.Site
 * @subpackage  pkg_agosms
 *
 * @copyright   Copyright (C) 2005 - 2019 Astrid Günther, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later;
 * @link        astrid-guenther.de
 */
defined('_JEXEC') or die();

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\Filesystem\Folder;
use Joomla\Filesystem\Path;

\Joomla\CMS\Form\FormHelper::loadFieldClass('folderlist');

class JFormFieldFixedFolderlist extends \JFormFieldFolderList
{
	/**
	 * The form field type.
	 */
	protected $type = 'fixedfolderlist';


	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 */
	protected function getOptions()
	{
		$options = array();

		$path = $this->directory;

		if (!is_dir($path))
		{
			$path = JPATH_ROOT . '/' . $path;
		}

		$path = Path::clean($path);

		// Prepend some default options based on field attributes.
		if (!$this->hideNone)
		{
			$options[] = HTMLHelper::_('select.option', '-1', Text::alt('JOPTION_DO_NOT_USE', preg_replace('/[^a-zA-Z0-9_\-]/', '_', $this->fieldname)));
		}

		if (!$this->hideDefault)
		{
			$options[] = HTMLHelper::_('select.option', '', Text::alt('JOPTION_USE_DEFAULT', preg_replace('/[^a-zA-Z0-9_\-]/', '_', $this->fieldname)));
		}

		// Get a list of folders in the search path with the given filter.
		$folders = Folder::folders($path, $this->filter, $this->recursive, true);

		// Build the options list from the list of folders.
		if (is_array($folders))
		{
			foreach ($folders as $folder)
			{
				// Remove the root part and the leading /
				$folder = trim(str_replace($path, '', $folder), '/');

				// Check to see if the file is in the exclude mask.
				if ($this->exclude)
				{
					if (preg_match(chr(1) . $this->exclude . chr(1), $folder))
					{
						continue;
					}
				}

				$options[] = HTMLHelper::_('select.option', $folder, $folder);
			}
		}

		// Merge any additional options in the XML definition.
		$options = array_merge(\JFormFieldList::getOptions(), $options);

		return $options;
	}
}
