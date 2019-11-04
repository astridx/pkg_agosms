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

class JFormFieldFilters extends JFormField
{

	function getInput()
	{
		return self::fetchElement($this->name, $this->value, $this->element, $this->options['control']);
	}

	function fetchElement($name, $value, &$node, $control_name)
	{
			$db = JFactory::getDBO();

			// Basic filters
			$mitems[] = JHTML::_('select.option', '', '');

			$mitems[] = JHTML::_('select.option', 'keyword', JText::_('MOD_AGOSMSSEARCHFILTER_TYPE_KEYWORD'));
			$mitems[] = JHTML::_('select.option', 'title_select', JText::_('MOD_AGOSMSSEARCHFILTER_TYPE_TITLE_SELECT'));
			$mitems[] = JHTML::_('select.option', 'tag', JText::_('MOD_AGOSMSSEARCHFILTER_TYPE_TAG'));
			$mitems[] = JHTML::_('select.option', 'category', JText::_('MOD_AGOSMSSEARCHFILTER_TYPE_CATEGORY'));
			$mitems[] = JHTML::_('select.option', 'author', JText::_('MOD_AGOSMSSEARCHFILTER_TYPE_AUTHOR'));
			$mitems[] = JHTML::_('select.option', 'date', JText::_('MOD_AGOSMSSEARCHFILTER_TYPE_DATE'));

			$mitems[] = JHTML::_('select.option', '', JText::_('-- Custom Fields --'));
			$query = "SELECT f.*, g.title as group_name FROM #__fields as f 
						LEFT JOIN #__fields_groups AS g ON f.group_id = g.id
						WHERE f.context = 'com_content.article'
						ORDER BY g.id, f.label
					";

		try
		{
			$db->setQuery($query);
			$fields = $db->loadObjectList();
		}
		catch (Exception $e)
		{
			$mitems[] = JHTML::_("select.option", "", "Custom Fields available from Joomla 3.8+");
		}

		if (count($fields))
		{
			$group = @$fields[0]->group_name;
			array_splice($fields, 0, 0, $group);

			for ($i = 1; $i < count($fields); $i++)
			{
				$new_group = $fields[$i]->group_name;

				if ($new_group != $group)
				{
					array_splice($fields, $i, 0, $new_group);
					$group = $new_group;
				}
			}

			foreach ($fields as $field)
			{
				if (is_object($field))
				{
					$field->group_name ? $offset = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" : $offset = "&nbsp;&nbsp;&nbsp;";

					if ($field->type == 'radicalmultifield')
					{
						$field_params = json_decode($field->fieldparams);
						$sub_fields = array();

						foreach ($field_params->listtype as $k => $sub_field)
						{
							$sub_fields[$k] = new stdClass;
							$sub_fields[$k]->name = $sub_field->name;
							$sub_fields[$k]->title = $sub_field->title;
						}

						$tmp = array_values($sub_fields);
						$sub_fields = array();
						$sub_fields['radicalmultifield_fields'] = $tmp;
						$sub_fields = json_encode($sub_fields);

						$mitems[] = JHTML::_("select.option", "field:{$field->id}:{$field->type}:{$sub_fields}", $offset . JText::_("{$field->label} [id: {$field->id}]"));
					}
					elseif ($field->type == 'repeatable')
					{
						$field_params = json_decode($field->fieldparams);
						$sub_fields = array();

						foreach ($field_params->fields as $k => $sub_field)
						{
							$sub_fields[$k] = new stdClass;
							$sub_fields[$k]->name = $sub_field->fieldname;
							$sub_fields[$k]->title = $sub_field->fieldname;
						}

						$tmp = array_values($sub_fields);
						$sub_fields = array();
						$sub_fields['repeatable_fields'] = $tmp;
						$sub_fields = json_encode($sub_fields);

						$mitems[] = JHTML::_("select.option", "field:{$field->id}:{$field->type}:{$sub_fields}", $offset . JText::_("{$field->label} [id: {$field->id}]"));
					}
					else
					{
						$mitems[] = JHTML::_("select.option", "field:{$field->id}:{$field->type}", $offset . JText::_("{$field->label} [id: {$field->id}]"));
					}
				}
				else
				{
					$mitems[] = JHTML::_("select.option", "", "&nbsp;&nbsp;&nbsp;-- {$field} --");
				}
			}
		}
		else
		{
			$mitems[] = JHTML::_('select.option', '', 'None');
		}

			$output = JHTML::_('select.genericlist',  $mitems, '', 'class="ValueSelect inputbox"', 'value', 'text', '0');
			$output .= "<div class='clear'></div><ul class='sortableFields'></ul>";
			$output .= "<div class='clear'></div>";
			$output .= "<textarea style='display: none;' name='" . $name . "' class='ValueSelectVal'>" . $value . "</textarea>";

			$output .= "
				<script>
					var MOD_AGOSMSSEARCHFILTER_SELECT_FIELD_TYPE = '" . JText::_("MOD_AGOSMSSEARCHFILTER_SELECT_FIELD_TYPE") . "';
					var MOD_AGOSMSSEARCHFILTER_TYPE_TEXT_FIELD = \"" . JText::_("MOD_AGOSMSSEARCHFIELD") . "\";
				</script>
			";

			return $output;
	}
}
