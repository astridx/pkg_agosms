<?php

/**
 * @package     Articles Good Search
 *
 * @copyright   Copyright (C) 2017 Joomcar extensions. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */
 
defined('_JEXEC') or die ;

class JFormFieldHeader extends JFormField {

	function getInput(){
		return JFormFieldHeader::fetchElement($this->name, $this->value, $this->element, $this->options['control']);
	}

    public function fetchElement($name, $value, &$node, $control_name)
    {
		error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);
		
        return '
			<div class="paramHeaderContainer" style="font-size: 16px; color: #369; margin: 20px 0 20px -160px; padding:10px 17px; background: #d5e7fa; border-bottom: 2px solid #96b0cb;">
				<div class="paramHeaderContent">'.JText::_($value).'</div>
				<div style="clear: both;"></div>
			</div>
		';
    }

}

?>
