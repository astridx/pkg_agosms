<?php
/**
 * @package     Joomla.Site
 * @subpackage  pkg_agosms
 *
 * @copyright   Copyright (C) 2005 - 2019 Astrid Günther, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later;
 * @link        astrid-guenther.de
 */

defined('JPATH_BASE') or die;

extract($displayData);

/**
 * Layout variables
 * -----------------
 * @var   string   $autocomplete    Autocomplete attribute for the field.
 * @var   boolean  $autofocus       Is autofocus enabled?
 * @var   string   $class           Classes for the input.
 * @var   string   $description     Description of the field.
 * @var   boolean  $disabled        Is this field disabled?
 * @var   string   $group           Group the field belongs to. <fields> section in form XML.
 * @var   boolean  $hidden          Is this field hidden in the form?
 * @var   string   $hint            Placeholder for the field.
 * @var   string   $id              DOM id of the field.
 * @var   string   $label           Label of the field.
 * @var   string   $labelclass      Classes to apply to the label.
 * @var   boolean  $multiple        Does this field support multiple values?
 * @var   string   $name            Name of the input field.
 * @var   string   $onchange        Onchange attribute for the field.
 * @var   string   $onclick         Onclick attribute for the field.
 * @var   string   $pattern         Pattern (Reg Ex) of value of the form field.
 * @var   boolean  $readonly        Is this field read only?
 * @var   boolean  $repeat          Allows extensions to duplicate elements.
 * @var   boolean  $required        Is this field required?
 * @var   integer  $size            Size attribute of the input.
 * @var   boolean  $spellcheck      Spellcheck state for the form field.
 * @var   string   $validate        Validation rules to apply.
 * @var   string   $value           Value attribute of the field.
 * @var   array    $checkedOptions  Options that will be set as checked.
 * @var   boolean  $hasValue        Has this field a value assigned?
 * @var   array    $options         Options available for this field.
 * @var   array    $inputType       Options available for this field.
 * @var   string   $accept          File types that are accepted.
 */

// Including fallback code for HTML5 non supported browsers.
JHtml::_('jquery.framework');
JHtml::_('script', 'system/html5fallback.js', array('version' => 'auto', 'relative' => true, 'conditional' => 'lt IE 9'));

$document = JFactory::getDocument();
$document->addScript(JURI::root(true) . '/media/plg_fields_agosmsaddressmarker/leaflet/leaflet.js');
JHtml::_('script', 'plg_fields_agosmsaddressmarker/admin-agosmsaddressmarker.js', array('version' => 'auto', 'relative' => true));

if ($geocoder === "mapbox")
{
	JHtml::_('script', 'plg_fields_agosmsaddressmarker/agosmsaddressmarkerMapbox.js', array('version' => 'auto', 'relative' => true));
}
elseif ($geocoder === "google")
{
	JHtml::_('script', 'plg_fields_agosmsaddressmarker/agosmsaddressmarkerGoogle.js', array('version' => 'auto', 'relative' => true));
}
else
{
	JHtml::_('script', 'plg_fields_agosmsaddressmarker/agosmsaddressmarkerNominatim.js', array('version' => 'auto', 'relative' => true));
}

JHtml::_('stylesheet', 'plg_fields_agosmsaddressmarker/agosmsaddressmarker.css', array('version' => 'auto', 'relative' => true));

$attributes = array(
	!empty($class) ? 'class="' . $class . '"' : '',
	!empty($size) ? 'size="' . $size . '"' : '',
	$disabled ? 'disabled' : '',
	$readonly ? 'readonly' : '',
	strlen($hint) ? 'placeholder="' . htmlspecialchars($hint, ENT_COMPAT, 'UTF-8') . '"' : '',
	$onchange ? ' onchange="' . $onchange . '"' : '',
	!empty($maxLength) ? $maxLength : '',
	$required ? 'required aria-required="true"' : '',
	$autocomplete,
	$autofocus ? ' autofocus' : '',
	$spellcheck ? '' : 'spellcheck="false"',
	!empty($inputmode) ? $inputmode : '',
	!empty($pattern) ? 'pattern="' . $pattern . '"' : '',
);
?>


<?php
// Load the Fields of this item
$current_component  = JFactory::getApplication()->input->getCmd('option');
$current_view  = JFactory::getApplication()->input->getCmd('view');
$current_context  = $current_component . '.' . $current_view;
$item = new stdClass;
$item->id = (int) JFactory::getApplication()->input->getCmd('id');

$fields = FieldsHelper::getFields($current_context, $item);

$addressfieldsvalues = array();
$addressfieldsArray = json_decode($addressfields);
if (!empty($addressfieldsArray))
{
	foreach ($addressfieldsArray as $a)
	{
		$addressfieldsvalues[] = $a->value;
	}
}

// Build the address string and a string with the field names from the selected fields
$addressstring = "";
$fieldnames = "";
if (!empty($fields))
{
	foreach ($fields as $field)
	{
		// Save value to addressstring, if field is in the options of this custom field
		if (in_array($field->id, $addressfieldsvalues))
		{
			$addressstring .= $field->rawvalue . ',';
			$fieldnames .= $field->label . ' ( ' . $field->name . ', ' . $field->id . ')<br>';
		}
	}
}

// Do I need this? Or is tempAlert enough?
// JFactory::getApplication()->enqueueMessage(JText::_('PLG_AGOSMSADDRESSMARKER_ADDRESSTRING') . ': ' . $addressstring, 'message');
?>


<hr>
<p>
	<?php echo JText::_('PLG_AGOSMSADDRESSMARKER_HINT'); ?>
</p>
<div class="agosmsaddressmarkersurroundingdiv">
<?php echo JText::_('PLG_AGOSMSADDRESSMARKER_LAT'); ?><input type="text" class="agosmsaddressmarkerlat" >
<?php echo JText::_('PLG_AGOSMSADDRESSMARKER_LON'); ?><input type="text" class="agosmsaddressmarkerlon" >
<br>
<button 
	data-addressstring="<?php echo htmlspecialchars($addressstring, ENT_QUOTES, 'UTF-8'); ?>"
	data-mapboxkey="<?php echo $mapboxkey; ?>"
	data-googlekey="<?php echo $googlekey; ?>"
	class="btn btn-success agosmsaddressmarkerbutton" 
	type="button">
	<?php echo JText::_('PLG_AGOSMSADDRESSMARKER_CALCULATE_CORDS'); ?>
</button>
<p>
	<?php echo JText::_('PLG_AGOSMSADDRESSMARKER_USED_FIELDS'); ?>
	<?php echo $fieldnames; ?>
</p>



<input class="agosmsaddressmarkerhiddenfield" 
type="hidden" 
readonly name="<?php
echo $name; ?>" id="<?php
echo $id; ?>" value="<?php
echo htmlspecialchars($value, ENT_COMPAT, 'UTF-8'); ?>" <?php echo implode(' ', $attributes); ?> />
</div>
<hr>