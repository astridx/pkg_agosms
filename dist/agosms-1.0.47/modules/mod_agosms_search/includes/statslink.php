<?php

/**
 * @package     Articles Good Search
 *
 * @copyright   Copyright (C) 2017 Joomcar extensions. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */
 
defined('_JEXEC') or die ;

class JFormFieldStatsLink extends JFormField {

	function getInput(){
		return JFormFieldStatsLink::fetchElement($this->name, $this->value, $this->element, $this->options['control']);
	}

    public function fetchElement($name, $value, &$node, $control_name)
    {
		return '
			<a style="position: absolute; margin-top: -20px; margin-left: -180px;" target="_blank" href="'.JURI::base().'?gsearch=1&search_type=search_stats&search_mode=list">'.JText::_('MOD_AGS_SEARCH_STATS_LINK').'</a>
		';
    }

}

?>
