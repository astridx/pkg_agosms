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

class JFormFieldStatsLink extends JFormField
{

	function getInput()
	{
		return self::fetchElement($this->name, $this->value, $this->element, $this->options['control']);
	}

	public function fetchElement($name, $value, &$node, $control_name)
	{
		return '
			<a style="position: absolute; margin-top: -20px; margin-left: -180px;" target="_blank" href="' . JURI::base() . '?gsearch=1&search_type=search_stats&search_mode=list">' . JText::_('MOD_AGOSMSSEARCHSEARCH_STATS_LINK') . '</a>
		';
	}

}


