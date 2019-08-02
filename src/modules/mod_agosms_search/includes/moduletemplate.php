<?php
/**
 * @package     Joomla.Site
 * @subpackage  pkg_agosms
 *
 * @copyright   Copyright (C) 2005 - 2019 Astrid Günther, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later;
 * @link        astrid-guenther.de
 */


// No direct access
defined('_JEXEC') or die;

jimport('joomla.form.formfield');

class JFormFieldModuletemplate extends JFormField
{
	var $_name = 'moduletemplate';

	var	$type = 'moduletemplate';

	function getInput()
	{
		return self::fetchElement($this->name, $this->value, $this->element, $this->options['control']);
	}

	function fetchElement($name, $value, &$node, $control_name)
	{
		jimport('joomla.filesystem.folder');

		$moduleTemplatesPath = JPATH_SITE . '/modules/mod_agosms_search/tmpl';
		$moduleTemplatesFolders = JFolder::folders($moduleTemplatesPath);

		$db = JFactory::getDBO();
		$query = "SELECT template FROM #__template_styles WHERE client_id = 0 AND home = 1";

		$db->setQuery($query);
		$defaultemplate = $db->loadResult();
		$templatePath = JPATH_SITE . '/templates/' . $defaultemplate . '/html/mod_agosms_search';

		if (JFolder::exists($templatePath))
		{
			$templateFolders = JFolder::folders($templatePath);
			$folders = @array_merge($templateFolders, $moduleTemplatesFolders);
			$folders = @array_unique($folders);
		}
		else
		{
			$folders = $moduleTemplatesFolders;
		}

		$exclude = 'Default';
		$options = array ();

		foreach ($folders as $folder)
		{
			if ($folder == $exclude)
			{
				continue;
			}

			$options[] = JHTML::_('select.option', $folder, $folder);
		}

		array_unshift($options, JHTML::_('select.option', 'Default', '-- ' . JText::_('MOD_AGOSMSSEARCHMODULE_TEMPLATE_DEFAULT') . ' --'));
		$fieldName = $name;

		return JHTML::_('select.genericlist', $options, $fieldName, 'class="inputbox"', 'value', 'text', $value, $control_name . $name);
	}
}
