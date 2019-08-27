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

$showspecialicon = "display:none";
if ($specialicon)
{
	$showspecialicon = "";
}

$showpopup = "display:none";
if ($popup)
{
	$showpopup = "";
}

$document = JFactory::getDocument();
$document->addScript(JURI::root(true) . '/media/plg_fields_agosmsaddressmarker/leaflet/leaflet.js');
JHtml::_('script', 'plg_fields_agosmsaddressmarker/admin-agosmsaddressmarker.js', array('version' => 'auto', 'relative' => true));

if ($specialicon)
{
	$document->addStyleSheet(JURI::root(true) . '/media/plg_fields_agosmsaddressmarker/css/font-awesome.min.css');
}

if ($geocoder === "mapbox")
{
	JHtml::_('script', 'plg_fields_agosmsaddressmarker/agosmsaddressmarkerMapbox.js', array('version' => 'auto', 'relative' => true));
} elseif ($geocoder === "google")
{
	JHtml::_('script', 'plg_fields_agosmsaddressmarker/agosmsaddressmarkerGoogle.js', array('version' => 'auto', 'relative' => true));
} else
{
	JHtml::_('script', 'plg_fields_agosmsaddressmarker/agosmsaddressmarkerNominatim.js', array('version' => 'auto', 'relative' => true));
}

JHtml::_('stylesheet', 'plg_fields_agosmsaddressmarker/agosmsaddressmarker.css', array('version' => 'auto', 'relative' => true));

JText::script('PLG_AGOSMSADDRESSMARKER_ADDRESSE_ERROR');
JText::script('PLG_AGOSMSADDRESSMARKER_ADDRESSE_NOTICE');

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

// Define defaults
$app = JFactory::getApplication();
$context = 'com_content.article';

// Com_categorie
if ($app->input->getCmd('option') === 'com_categories')
{
	$context = $app->input->getCmd('extension') . '.categories';
}

// Com_users
elseif ($app->input->getCmd('option') === 'com_users')
{
	$context = 'com_users.user';
}

// Com_contact
elseif ($app->input->getCmd('option') === 'com_contact')
{
	//JFactory::getApplication()->enqueueMessage(JText::_('PLG_AGOSMSADDRESSMARKER_SUPPORTET'), 'message');
	$context = 'com_contact.contact';
}

// Third Party
elseif ($app->input->getCmd('option') !== 'com_users'
	&& $app->input->getCmd('option') !== 'com_content'
	&& $app->input->getCmd('option') !== 'com_categories'
	&& $app->input->getCmd('option') !== 'com_contact')
{
	$context = $app->input->getCmd('option') . '.' . $app->input->getCmd('view');
}


// Load fields with prepared values
$fields = FieldsHelper::getFields($context);

$addressfieldsvalues = array();
$addressfieldsArray = json_decode($addressfields);

if (!empty($addressfieldsArray))
{
	foreach ($addressfieldsArray as $a) {
		if(property_exists($a, 'value'))
		{
			$addressfieldsvalues[] = $a->value;
		}
	}
}

// Build the string with the field names from the selected fields
$fieldnames = "";
$fieldsNameArray = array();

if (!empty($fields))
{
	foreach ($fields as $field) {
		// Save value to fieldnames, if field is in the options of this custom field
		if (in_array($field->id, $addressfieldsvalues))
		{
			$fieldsNameArray[] = 'jform' . '_com_fields_' . str_replace('-', '_', $field->name);
			$fieldnames .= $field->label . ', ';
		}
	}
}

$fieldsNameImplode = implode(',', $fieldsNameArray);

?>

<hr>
<div class="agosmsaddressmarkersurroundingdiv form-horizontal">

<div class="control-group">
<label class="control-label"><?php echo JText::_('PLG_AGOSMSADDRESSMARKER_LAT'); ?></label>	
<div class="controls">
	<input type="text" class="agosmsaddressmarkerlat" >
</div>
</div>

<div class="control-group">
<label class="control-label"><?php echo JText::_('PLG_AGOSMSADDRESSMARKER_LON'); ?></label>	
<div class="controls">	
<input type="text" class="agosmsaddressmarkerlon" >
</div>
</div>
	
<button 
		data-fieldsnamearray="<?php echo $fieldsNameImplode; ?>"
		data-mapboxkey="<?php echo $mapboxkey; ?>"
		data-googlekey="<?php echo $googlekey; ?>"
		class="btn btn-success agosmsaddressmarkerbutton" 
		type="button">
<?php echo JText::_('PLG_AGOSMSADDRESSMARKER_CALCULATE_CORDS'); ?>
	</button>

<?php echo JText::_('PLG_AGOSMSADDRESSMARKER_HINT'); ?>
<?php echo JText::_('PLG_AGOSMSADDRESSMARKER_USED_FIELDS'); ?>
<?php echo $fieldnames; ?>
	
<hr>
<h4><?php echo JText::_('PLG_AGOSMSADDRESSMARKER_HEADING_OPTIONAL_VALUES'); ?></h4>

<div style="<?php echo $showspecialicon; ?>">
<div class="control-group">
<label class="control-label"><?php echo JText::_('Iconcolor'); ?></label>	
<div class="controls">
<select
	class="agosmsaddressmarkericoncolor">
	<option></option>
	<option value="red">red</option>
	<option value="darkred">darkred</option>
	<option value="orange">orange</option>
	<option value="green">green</option>
	<option value="darkgreen">darkgreen</option>
	<option value="blue">blue</option>
	<option value="purple">purple</option>
	<option value="darkpurple">darkpurple</option>
	<option value="cadetblue">cadetblue</option>
	<option value="#FFFFFF">white</option>
	<option value="#000000">black</option>
</select>
</div>
</div>

<div class="control-group">
<label class="control-label"><?php echo JText::_('Markercolor'); ?></label>	
<div class="controls">
<select 
	class="agosmsaddressmarkercolor">
	<option></option>
	<option value="red">red</option>
	<option value="darkred">darkred</option>
	<option value="orange">orange</option>
	<option value="green">green</option>
	<option value="darkgreen">darkgreen</option>
	<option value="blue">blue</option>
	<option value="purple">purple</option>
	<option value="darkpurple">darkpurple</option>
	<option value="cadetblue">cadetblue</option>
</select>
</div>
</div>

<div class="control-group agosmsaddressmarkericondiv">
<label class="control-label"><?php echo JText::_('Icon'); ?></label>	
<div class="controls">
<select 
class="agosmsaddressmarkericon">
	<option></option>	
	<option value="circle">circle &#xf111;</option>
	<option value="">noicon</option>
	<option value="home">home &#xf015;</option>
	<option value="star">star &#xf005;</option>
	<option value="500px">&#xf26e;</option>
	<option value="address-book">&#xf2b9;</option>
	<option value="address-book">&#xf2b9;</option>
	<option value="address-book-o">&#xf2ba;</option>
	<option value="address-card">&#xf2bb;</option>
	<option value="address-card-o">&#xf2bc;</option>
	<option value="adjust">&#xf042;</option>
	<option value="adn">&#xf170;</option>
	<option value="align-center">&#xf037;</option>
	<option value="align-justify">&#xf039;</option>
	<option value="align-left">&#xf036;</option>
	<option value="align-right">&#xf038;</option>
	<option value="amazon">&#xf270;</option>
	<option value="ambulance">&#xf0f9;</option>
	<option value="american-sign-language-interpreting">&#xf2a3;</option>
	<option value="anchor">&#xf13d;</option>
	<option value="android">&#xf17b;</option>
	<option value="angellist">&#xf209;</option>
	<option value="angle-double-down">&#xf103;</option>
	<option value="angle-double-left">&#xf100;</option>
	<option value="angle-double-right">&#xf101;</option>
	<option value="angle-double-up">&#xf102;</option>
	<option value="angle-down">&#xf107;</option>
	<option value="angle-left">&#xf104;</option>
	<option value="angle-right">&#xf105;</option>
	<option value="angle-up">&#xf106;</option>
	<option value="apple">&#xf179;</option>
	<option value="archive">&#xf187;</option>
	<option value="area-chart">&#xf1fe;</option>
	<option value="arrow-circle-down">&#xf0ab;</option>
	<option value="arrow-circle-left">&#xf0a8;</option>
	<option value="arrow-circle-o-down">&#xf01a;</option>
	<option value="arrow-circle-o-left">&#xf190;</option>
	<option value="arrow-circle-o-right">&#xf18e;</option>
	<option value="arrow-circle-o-up">&#xf01b;</option>
	<option value="arrow-circle-right">&#xf0a9;</option>
	<option value="arrow-circle-up">&#xf0aa;</option>
	<option value="arrow-down">&#xf063;</option>
	<option value="arrow-left">&#xf060;</option>
	<option value="arrow-right">&#xf061;</option>
	<option value="arrow-up">&#xf062;</option>
	<option value="arrows">&#xf047;</option>
	<option value="arrows-alt">&#xf0b2;</option>
	<option value="arrows-h">&#xf07e;</option>
	<option value="arrows-v">&#xf07d;</option>
	<option value="asl-interpreting">&#xf2a3;</option>
	<option value="assistive-listening-systems">&#xf2a2;</option>
	<option value="asterisk">&#xf069;</option>
	<option value="at">&#xf1fa;</option>
	<option value="audio-description">&#xf29e;</option>
	<option value="automobile">&#xf1b9;</option>
	<option value="backward">&#xf04a;</option>
	<option value="balance-scale">&#xf24e;</option>
	<option value="ban">&#xf05e;</option>
	<option value="bandcamp">&#xf2d5;</option>
	<option value="bank">&#xf19c;</option>
	<option value="bar-chart">&#xf080;</option>
	<option value="bar-chart-o">&#xf080;</option>
	<option value="barcode">&#xf02a;</option>
	<option value="bars">&#xf0c9;</option>
	<option value="bath">&#xf2cd;</option>
	<option value="bathtub">&#xf2cd;</option>
	<option value="battery">&#xf240;</option>
	<option value="battery-0">&#xf244;</option>
	<option value="battery-1">&#xf243;</option>
	<option value="battery-2">&#xf242;</option>
	<option value="battery-3">&#xf241;</option>
	<option value="battery-4">&#xf240;</option>
	<option value="battery-empty">&#xf244;</option>
	<option value="battery-full">&#xf240;</option>
	<option value="battery-half">&#xf242;</option>
	<option value="battery-quarter">&#xf243;</option>
	<option value="battery-three-quarters">&#xf241;</option>
	<option value="bed">&#xf236;</option>
	<option value="beer">&#xf0fc;</option>
	<option value="behance">&#xf1b4;</option>
	<option value="behance-square">&#xf1b5;</option>
	<option value="bell">&#xf0f3;</option>
	<option value="bell-o">&#xf0a2;</option>
	<option value="bell-slash">&#xf1f6;</option>
	<option value="bell-slash-o">&#xf1f7;</option>
	<option value="bicycle">&#xf206;</option>
	<option value="binoculars">&#xf1e5;</option>
	<option value="birthday-cake">&#xf1fd;</option>
	<option value="bitbucket">&#xf171;</option>
	<option value="bitbucket-square">&#xf172;</option>
	<option value="bitcoin">&#xf15a;</option>
	<option value="black-tie">&#xf27e;</option>
	<option value="blind">&#xf29d;</option>
	<option value="bluetooth">&#xf293;</option>
	<option value="bluetooth-b">&#xf294;</option>
	<option value="bold">&#xf032;</option>
	<option value="bolt">&#xf0e7;</option>
	<option value="bomb">&#xf1e2;</option>
	<option value="book">&#xf02d;</option>
	<option value="bookmark">&#xf02e;</option>
	<option value="bookmark-o">&#xf097;</option>
	<option value="braille">&#xf2a1;</option>
	<option value="briefcase">&#xf0b1;</option>
	<option value="btc">&#xf15a;</option>
	<option value="bug">&#xf188;</option>
	<option value="building">&#xf1ad;</option>
	<option value="building-o">&#xf0f7;</option>
	<option value="bullhorn">&#xf0a1;</option>
	<option value="bullseye">&#xf140;</option>
	<option value="bus">&#xf207;</option>
	<option value="buysellads">&#xf20d;</option>
	<option value="cab">&#xf1ba;</option>
	<option value="calculator">&#xf1ec;</option>
	<option value="calendar">&#xf073;</option>
	<option value="calendar-check-o">&#xf274;</option>
	<option value="calendar-minus-o">&#xf272;</option>
	<option value="calendar-o">&#xf133;</option>
	<option value="calendar-plus-o">&#xf271;</option>
	<option value="calendar-times-o">&#xf273;</option>
	<option value="camera">&#xf030;</option>
	<option value="camera-retro">&#xf083;</option>
	<option value="car">&#xf1b9;</option>
	<option value="caret-down">&#xf0d7;</option>
	<option value="caret-left">&#xf0d9;</option>
	<option value="caret-right">&#xf0da;</option>
	<option value="caret-square-o-down">&#xf150;</option>
	<option value="caret-square-o-left">&#xf191;</option>
	<option value="caret-square-o-right">&#xf152;</option>
	<option value="caret-square-o-up">&#xf151;</option>
	<option value="caret-up">&#xf0d8;</option>
	<option value="cart-arrow-down">&#xf218;</option>
	<option value="cart-plus">&#xf217;</option>
	<option value="cc">&#xf20a;</option>
	<option value="cc-amex">&#xf1f3;</option>
	<option value="cc-diners-club">&#xf24c;</option>
	<option value="cc-discover">&#xf1f2;</option>
	<option value="cc-jcb">&#xf24b;</option>
	<option value="cc-mastercard">&#xf1f1;</option>
	<option value="cc-paypal">&#xf1f4;</option>
	<option value="cc-stripe">&#xf1f5;</option>
	<option value="cc-visa">&#xf1f0;</option>
	<option value="certificate">&#xf0a3;</option>
	<option value="chain">&#xf0c1;</option>
	<option value="chain-broken">&#xf127;</option>
	<option value="check">&#xf00c;</option>
	<option value="check-circle">&#xf058;</option>
	<option value="check-circle-o">&#xf05d;</option>
	<option value="check-square">&#xf14a;</option>
	<option value="check-square-o">&#xf046;</option>
	<option value="chevron-circle-down">&#xf13a;</option>
	<option value="chevron-circle-left">&#xf137;</option>
	<option value="chevron-circle-right">&#xf138;</option>
	<option value="chevron-circle-up">&#xf139;</option>
	<option value="chevron-down">&#xf078;</option>
	<option value="chevron-left">&#xf053;</option>
	<option value="chevron-right">&#xf054;</option>
	<option value="chevron-up">&#xf077;</option>
	<option value="child">&#xf1ae;</option>
	<option value="chrome">&#xf268;</option>
	<option value="circle">&#xf111;</option>
	<option value="circle-o">&#xf10c;</option>
	<option value="circle-o-notch">&#xf1ce;</option>
	<option value="circle-thin">&#xf1db;</option>
	<option value="clipboard">&#xf0ea;</option>
	<option value="clock-o">&#xf017;</option>
	<option value="clone">&#xf24d;</option>
	<option value="close">&#xf00d;</option>
	<option value="cloud">&#xf0c2;</option>
	<option value="cloud-download">&#xf0ed;</option>
	<option value="cloud-upload">&#xf0ee;</option>
	<option value="cny">&#xf157;</option>
	<option value="code">&#xf121;</option>
	<option value="code-fork">&#xf126;</option>
	<option value="codepen">&#xf1cb;</option>
	<option value="codiepie">&#xf284;</option>
	<option value="coffee">&#xf0f4;</option>
	<option value="cog">&#xf013;</option>
	<option value="cogs">&#xf085;</option>
	<option value="columns">&#xf0db;</option>
	<option value="comment">&#xf075;</option>
	<option value="comment-o">&#xf0e5;</option>
	<option value="commenting">&#xf27a;</option>
	<option value="commenting-o">&#xf27b;</option>
	<option value="comments">&#xf086;</option>
	<option value="comments-o">&#xf0e6;</option>
	<option value="compass">&#xf14e;</option>
	<option value="compress">&#xf066;</option>
	<option value="connectdevelop">&#xf20e;</option>
	<option value="contao">&#xf26d;</option>
	<option value="copy">&#xf0c5;</option>
	<option value="copyright">&#xf1f9;</option>
	<option value="creative-commons">&#xf25e;</option>
	<option value="credit-card">&#xf09d;</option>
	<option value="credit-card-alt">&#xf283;</option>
	<option value="crop">&#xf125;</option>
	<option value="crosshairs">&#xf05b;</option>
	<option value="css3">&#xf13c;</option>
	<option value="cube">&#xf1b2;</option>
	<option value="cubes">&#xf1b3;</option>
	<option value="cut">&#xf0c4;</option>
	<option value="cutlery">&#xf0f5;</option>
	<option value="dashboard">&#xf0e4;</option>
	<option value="dashcube">&#xf210;</option>
	<option value="database">&#xf1c0;</option>
	<option value="deaf">&#xf2a4;</option>
	<option value="deafness">&#xf2a4;</option>
	<option value="dedent">&#xf03b;</option>
	<option value="delicious">&#xf1a5;</option>
	<option value="desktop">&#xf108;</option>
	<option value="deviantart">&#xf1bd;</option>
	<option value="diamond">&#xf219;</option>
	<option value="digg">&#xf1a6;</option>
	<option value="dollar">&#xf155;</option>
	<option value="dot-circle-o">&#xf192;</option>
	<option value="download">&#xf019;</option>
	<option value="dribbble">&#xf17d;</option>
	<option value="drivers-license">&#xf2c2;</option>
	<option value="drivers-license-o">&#xf2c3;</option>
	<option value="dropbox">&#xf16b;</option>
	<option value="drupal">&#xf1a9;</option>
	<option value="edge">&#xf282;</option>
	<option value="edit">&#xf044;</option>
	<option value="eercast">&#xf2da;</option>
	<option value="eject">&#xf052;</option>
	<option value="ellipsis-h">&#xf141;</option>
	<option value="ellipsis-v">&#xf142;</option>
	<option value="empire">&#xf1d1;</option>
	<option value="envelope">&#xf0e0;</option>
	<option value="envelope-o">&#xf003;</option>
	<option value="envelope-open">&#xf2b6;</option>
	<option value="envelope-open-o">&#xf2b7;</option>
	<option value="envelope-square">&#xf199;</option>
	<option value="envira">&#xf299;</option>
	<option value="eraser">&#xf12d;</option>
	<option value="etsy">&#xf2d7;</option>
	<option value="eur">&#xf153;</option>
	<option value="euro">&#xf153;</option>
	<option value="exchange">&#xf0ec;</option>
	<option value="exclamation">&#xf12a;</option>
	<option value="exclamation-circle">&#xf06a;</option>
	<option value="exclamation-triangle">&#xf071;</option>
	<option value="expand">&#xf065;</option>
	<option value="expeditedssl">&#xf23e;</option>
	<option value="external-link">&#xf08e;</option>
	<option value="external-link-square">&#xf14c;</option>
	<option value="eye">&#xf06e;</option>
	<option value="eye-slash">&#xf070;</option>
	<option value="eyedropper">&#xf1fb;</option>
	<option value="fa">&#xf2b4;</option>
	<option value="facebook">&#xf09a;</option>
	<option value="facebook-f">&#xf09a;</option>
	<option value="facebook-official">&#xf230;</option>
	<option value="facebook-square">&#xf082;</option>
	<option value="fast-backward">&#xf049;</option>
	<option value="fast-forward">&#xf050;</option>
	<option value="fax">&#xf1ac;</option>
	<option value="feed">&#xf09e;</option>
	<option value="female">&#xf182;</option>
	<option value="fighter-jet">&#xf0fb;</option>
	<option value="file">&#xf15b;</option>
	<option value="file-archive-o">&#xf1c6;</option>
	<option value="file-audio-o">&#xf1c7;</option>
	<option value="file-code-o">&#xf1c9;</option>
	<option value="file-excel-o">&#xf1c3;</option>
	<option value="file-image-o">&#xf1c5;</option>
	<option value="file-movie-o">&#xf1c8;</option>
	<option value="file-o">&#xf016;</option>
	<option value="file-pdf-o">&#xf1c1;</option>
	<option value="file-photo-o">&#xf1c5;</option>
	<option value="file-picture-o">&#xf1c5;</option>
	<option value="file-powerpoint-o">&#xf1c4;</option>
	<option value="file-sound-o">&#xf1c7;</option>
	<option value="file-text">&#xf15c;</option>
	<option value="file-text-o">&#xf0f6;</option>
	<option value="file-video-o">&#xf1c8;</option>
	<option value="file-word-o">&#xf1c2;</option>
	<option value="file-zip-o">&#xf1c6;</option>
	<option value="files-o">&#xf0c5;</option>
	<option value="film">&#xf008;</option>
	<option value="filter">&#xf0b0;</option>
	<option value="fire">&#xf06d;</option>
	<option value="fire-extinguisher">&#xf134;</option>
	<option value="firefox">&#xf269;</option>
	<option value="first-order">&#xf2b0;</option>
	<option value="flag">&#xf024;</option>
	<option value="flag-checkered">&#xf11e;</option>
	<option value="flag-o">&#xf11d;</option>
	<option value="flash">&#xf0e7;</option>
	<option value="flask">&#xf0c3;</option>
	<option value="flickr">&#xf16e;</option>
	<option value="floppy-o">&#xf0c7;</option>
	<option value="folder">&#xf07b;</option>
	<option value="folder-o">&#xf114;</option>
	<option value="folder-open">&#xf07c;</option>
	<option value="folder-open-o">&#xf115;</option>
	<option value="font">&#xf031;</option>
	<option value="font-awesome">&#xf2b4;</option>
	<option value="fonticons">&#xf280;</option>
	<option value="fort-awesome">&#xf286;</option>
	<option value="forumbee">&#xf211;</option>
	<option value="forward">&#xf04e;</option>
	<option value="foursquare">&#xf180;</option>
	<option value="free-code-camp">&#xf2c5;</option>
	<option value="frown-o">&#xf119;</option>
	<option value="futbol-o">&#xf1e3;</option>
	<option value="gamepad">&#xf11b;</option>
	<option value="gavel">&#xf0e3;</option>
	<option value="gbp">&#xf154;</option>
	<option value="ge">&#xf1d1;</option>
	<option value="gear">&#xf013;</option>
	<option value="gears">&#xf085;</option>
	<option value="genderless">&#xf22d;</option>
	<option value="get-pocket">&#xf265;</option>
	<option value="gg">&#xf260;</option>
	<option value="gg-circle">&#xf261;</option>
	<option value="gift">&#xf06b;</option>
	<option value="git">&#xf1d3;</option>
	<option value="git-square">&#xf1d2;</option>
	<option value="github">&#xf09b;</option>
	<option value="github-alt">&#xf113;</option>
	<option value="github-square">&#xf092;</option>
	<option value="gitlab">&#xf296;</option>
	<option value="gittip">&#xf184;</option>
	<option value="glass">&#xf000;</option>
	<option value="glide">&#xf2a5;</option>
	<option value="glide-g">&#xf2a6;</option>
	<option value="globe">&#xf0ac;</option>
	<option value="google">&#xf1a0;</option>
	<option value="google-plus">&#xf0d5;</option>
	<option value="google-plus-circle">&#xf2b3;</option>
	<option value="google-plus-official">&#xf2b3;</option>
	<option value="google-plus-square">&#xf0d4;</option>
	<option value="google-wallet">&#xf1ee;</option>
	<option value="graduation-cap">&#xf19d;</option>
	<option value="gratipay">&#xf184;</option>
	<option value="grav">&#xf2d6;</option>
	<option value="group">&#xf0c0;</option>
	<option value="h-square">&#xf0fd;</option>
	<option value="hacker-news">&#xf1d4;</option>
	<option value="hand-grab-o">&#xf255;</option>
	<option value="hand-lizard-o">&#xf258;</option>
	<option value="hand-o-down">&#xf0a7;</option>
	<option value="hand-o-left">&#xf0a5;</option>
	<option value="hand-o-right">&#xf0a4;</option>
	<option value="hand-o-up">&#xf0a6;</option>
	<option value="hand-paper-o">&#xf256;</option>
	<option value="hand-peace-o">&#xf25b;</option>
	<option value="hand-pointer-o">&#xf25a;</option>
	<option value="hand-rock-o">&#xf255;</option>
	<option value="hand-scissors-o">&#xf257;</option>
	<option value="hand-spock-o">&#xf259;</option>
	<option value="hand-stop-o">&#xf256;</option>
	<option value="handshake-o">&#xf2b5;</option>
	<option value="hard-of-hearing">&#xf2a4;</option>
	<option value="hashtag">&#xf292;</option>
	<option value="hdd-o">&#xf0a0;</option>
	<option value="header">&#xf1dc;</option>
	<option value="headphones">&#xf025;</option>
	<option value="heart">&#xf004;</option>
	<option value="heart-o">&#xf08a;</option>
	<option value="heartbeat">&#xf21e;</option>
	<option value="history">&#xf1da;</option>
	<option value="home">&#xf015;</option>
	<option value="hospital-o">&#xf0f8;</option>
	<option value="hotel">&#xf236;</option>
	<option value="hourglass">&#xf254;</option>
	<option value="hourglass-1">&#xf251;</option>
	<option value="hourglass-2">&#xf252;</option>
	<option value="hourglass-3">&#xf253;</option>
	<option value="hourglass-end">&#xf253;</option>
	<option value="hourglass-half">&#xf252;</option>
	<option value="hourglass-o">&#xf250;</option>
	<option value="hourglass-start">&#xf251;</option>
	<option value="houzz">&#xf27c;</option>
	<option value="html5">&#xf13b;</option>
	<option value="i-cursor">&#xf246;</option>
	<option value="id-badge">&#xf2c1;</option>
	<option value="id-card">&#xf2c2;</option>
	<option value="id-card-o">&#xf2c3;</option>
	<option value="ils">&#xf20b;</option>
	<option value="image">&#xf03e;</option>
	<option value="imdb">&#xf2d8;</option>
	<option value="inbox">&#xf01c;</option>
	<option value="indent">&#xf03c;</option>
	<option value="industry">&#xf275;</option>
	<option value="info">&#xf129;</option>
	<option value="info-circle">&#xf05a;</option>
	<option value="inr">&#xf156;</option>
	<option value="instagram">&#xf16d;</option>
	<option value="institution">&#xf19c;</option>
	<option value="internet-explorer">&#xf26b;</option>
	<option value="intersex">&#xf224;</option>
	<option value="ioxhost">&#xf208;</option>
	<option value="italic">&#xf033;</option>
	<option value="joomla">&#xf1aa;</option>
	<option value="jpy">&#xf157;</option>
	<option value="jsfiddle">&#xf1cc;</option>
	<option value="key">&#xf084;</option>
	<option value="keyboard-o">&#xf11c;</option>
	<option value="krw">&#xf159;</option>
	<option value="language">&#xf1ab;</option>
	<option value="laptop">&#xf109;</option>
	<option value="lastfm">&#xf202;</option>
	<option value="lastfm-square">&#xf203;</option>
	<option value="leaf">&#xf06c;</option>
	<option value="leanpub">&#xf212;</option>
	<option value="legal">&#xf0e3;</option>
	<option value="lemon-o">&#xf094;</option>
	<option value="level-down">&#xf149;</option>
	<option value="level-up">&#xf148;</option>
	<option value="life-bouy">&#xf1cd;</option>
	<option value="life-buoy">&#xf1cd;</option>
	<option value="life-ring">&#xf1cd;</option>
	<option value="life-saver">&#xf1cd;</option>
	<option value="lightbulb-o">&#xf0eb;</option>
	<option value="line-chart">&#xf201;</option>
	<option value="link">&#xf0c1;</option>
	<option value="linkedin">&#xf0e1;</option>
	<option value="linkedin-square">&#xf08c;</option>
	<option value="linode">&#xf2b8;</option>
	<option value="linux">&#xf17c;</option>
	<option value="list">&#xf03a;</option>
	<option value="list-alt">&#xf022;</option>
	<option value="list-ol">&#xf0cb;</option>
	<option value="list-ul">&#xf0ca;</option>
	<option value="location-arrow">&#xf124;</option>
	<option value="lock">&#xf023;</option>
	<option value="long-arrow-down">&#xf175;</option>
	<option value="long-arrow-left">&#xf177;</option>
	<option value="long-arrow-right">&#xf178;</option>
	<option value="long-arrow-up">&#xf176;</option>
	<option value="low-vision">&#xf2a8;</option>
	<option value="magic">&#xf0d0;</option>
	<option value="mail-forward">&#xf064;</option>
	<option value="magnet">&#xf076;</option>
	<option value="mail-forward">&#xf064;</option>
	<option value="mail-reply">&#xf112;</option>
	<option value="mail-reply-all">&#xf122;</option>
	<option value="male">&#xf183;</option>
	<option value="map">&#xf279;</option>
	<option value="map-marker">&#xf041;</option>
	<option value="map-o">&#xf278;</option>
	<option value="map-pin">&#xf276;</option>
	<option value="map-signs">&#xf277;</option>
	<option value="mars">&#xf222;</option>
	<option value="mars-double">&#xf227;</option>
	<option value="mars-stroke">&#xf229;</option>
	<option value="mars-stroke-h">&#xf22b;</option>
	<option value="mars-stroke-v">&#xf22a;</option>
	<option value="maxcdn">&#xf136;</option>
	<option value="meanpath">&#xf20c;</option>
	<option value="medium">&#xf23a;</option>
	<option value="medkit">&#xf0fa;</option>
	<option value="meetup">&#xf2e0;</option>
	<option value="meh-o">&#xf11a;</option>
	<option value="mercury">&#xf223;</option>
	<option value="microchip">&#xf2db;</option>
	<option value="microphone">&#xf130;</option>
	<option value="microphone-slash">&#xf131;</option>
	<option value="minus">&#xf068;</option>
	<option value="minus-circle">&#xf056;</option>
	<option value="minus-square">&#xf146;</option>
	<option value="minus-square-o">&#xf147;</option>
	<option value="mixcloud">&#xf289;</option>
	<option value="mobile">&#xf10b;</option>
	<option value="mobile-phone">&#xf10b;</option>
	<option value="modx">&#xf285;</option>
	<option value="money">&#xf0d6;</option>
	<option value="moon-o">&#xf186;</option>
	<option value="mortar-board">&#xf19d;</option>
	<option value="motorcycle">&#xf21c;</option>
	<option value="mouse-pointer">&#xf245;</option>
	<option value="music">&#xf001;</option>
	<option value="navicon">&#xf0c9;</option>
	<option value="neuter">&#xf22c;</option>
	<option value="newspaper-o">&#xf1ea;</option>
	<option value="object-group">&#xf247;</option>
	<option value="object-ungroup">&#xf248;</option>
	<option value="odnoklassniki">&#xf263;</option>
	<option value="odnoklassniki-square">&#xf264;</option>
	<option value="opencart">&#xf23d;</option>
	<option value="openid">&#xf19b;</option>
	<option value="opera">&#xf26a;</option>
	<option value="optin-monster">&#xf23c;</option>
	<option value="outdent">&#xf03b;</option>
	<option value="pagelines">&#xf18c;</option>
	<option value="paint-brush">&#xf1fc;</option>
	<option value="paper-plane">&#xf1d8;</option>
	<option value="paper-plane-o">&#xf1d9;</option>
	<option value="paperclip">&#xf0c6;</option>
	<option value="paragraph">&#xf1dd;</option>
	<option value="paste">&#xf0ea;</option>
	<option value="pause">&#xf04c;</option>
	<option value="pause-circle">&#xf28b;</option>
	<option value="pause-circle-o">&#xf28c;</option>
	<option value="paw">&#xf1b0;</option>
	<option value="paypal">&#xf1ed;</option>
	<option value="pencil">&#xf040;</option>
	<option value="pencil-square">&#xf14b;</option>
	<option value="pencil-square-o">&#xf044;</option>
	<option value="percent">&#xf295;</option>
	<option value="phone">&#xf095;</option>
	<option value="phone-square">&#xf098;</option>
	<option value="photo">&#xf03e;</option>
	<option value="picture-o">&#xf03e;</option>
	<option value="pie-chart">&#xf200;</option>
	<option value="pied-piper">&#xf2ae;</option>
	<option value="pied-piper-alt">&#xf1a8;</option>
	<option value="pied-piper-pp">&#xf1a7;</option>
	<option value="pinterest">&#xf0d2;</option>
	<option value="pinterest-p">&#xf231;</option>
	<option value="pinterest-square">&#xf0d3;</option>
	<option value="plane">&#xf072;</option>
	<option value="play">&#xf04b;</option>
	<option value="play-circle">&#xf144;</option>
	<option value="play-circle-o">&#xf01d;</option>
	<option value="plug">&#xf1e6;</option>
	<option value="plus">&#xf067;</option>
	<option value="plus-circle">&#xf055;</option>
	<option value="plus-square">&#xf0fe;</option>
	<option value="plus-square-o">&#xf196;</option>
	<option value="podcast">&#xf2ce;</option>
	<option value="power-off">&#xf011;</option>
	<option value="print">&#xf02f;</option>
	<option value="product-hunt">&#xf288;</option>
	<option value="puzzle-piece">&#xf12e;</option>
	<option value="qq">&#xf1d6;</option>
	<option value="qrcode">&#xf029;</option>
	<option value="question">&#xf128;</option>
	<option value="question-circle">&#xf059;</option>
	<option value="question-circle-o">&#xf29c;</option>
	<option value="quora">&#xf2c4;</option>
	<option value="quote-left">&#xf10d;</option>
	<option value="quote-right">&#xf10e;</option>
	<option value="ra">&#xf1d0;</option>
	<option value="random">&#xf074;</option>
	<option value="ravelry">&#xf2d9;</option>
	<option value="rebel">&#xf1d0;</option>
	<option value="recycle">&#xf1b8;</option>
	<option value="reddit">&#xf1a1;</option>
	<option value="reddit-alien">&#xf281;</option>
	<option value="reddit-square">&#xf1a2;</option>
	<option value="refresh">&#xf021;</option>
	<option value="registered">&#xf25d;</option>
	<option value="remove">&#xf00d;</option>
	<option value="renren">&#xf18b;</option>
	<option value="reorder">&#xf0c9;</option>
	<option value="repeat">&#xf01e;</option>
	<option value="reply">&#xf112;</option>
	<option value="reply-all">&#xf122;</option>
	<option value="resistance">&#xf1d0;</option>
	<option value="retweet">&#xf079;</option>
	<option value="rmb">&#xf157;</option>
	<option value="road">&#xf018;</option>
	<option value="rocket">&#xf135;</option>
	<option value="rotate-left">&#xf0e2;</option>
	<option value="rotate-right">&#xf01e;</option>
	<option value="rouble">&#xf158;</option>
	<option value="rss">&#xf09e;</option>
	<option value="rss-square">&#xf143;</option>
	<option value="rub">&#xf158;</option>
	<option value="ruble">&#xf158;</option>
	<option value="rupee">&#xf156;</option>
	<option value="s15">&#xf2cd;</option>
	<option value="safari">&#xf267;</option>
	<option value="save">&#xf0c7;</option>
	<option value="scissors">&#xf0c4;</option>
	<option value="scribd">&#xf28a;</option>
	<option value="search">&#xf002;</option>
	<option value="search-minus">&#xf010;</option>
	<option value="search-plus">&#xf00e;</option>
	<option value="sellsy">&#xf213;</option>
	<option value="send">&#xf1d8;</option>
	<option value="send-o">&#xf1d9;</option>
	<option value="server">&#xf233;</option>
	<option value="share">&#xf064;</option>
	<option value="share-alt">&#xf1e0;</option>
	<option value="share-alt-square">&#xf1e1;</option>
	<option value="share-square">&#xf14d;</option>
	<option value="share-square-o">&#xf045;</option>
	<option value="shekel">&#xf20b;</option>
	<option value="sheqel">&#xf20b;</option>
	<option value="shield">&#xf132;</option>
	<option value="ship">&#xf21a;</option>
	<option value="shirtsinbulk">&#xf214;</option>
	<option value="shopping-bag">&#xf290;</option>
	<option value="shopping-basket">&#xf291;</option>
	<option value="shopping-cart">&#xf07a;</option>
	<option value="shower">&#xf2cc;</option>
	<option value="sign-in">&#xf090;</option>
	<option value="sign-language">&#xf2a7;</option>
	<option value="sign-out">&#xf08b;</option>
	<option value="signal">&#xf012;</option>
	<option value="signing">&#xf2a7;</option>
	<option value="simplybuilt">&#xf215;</option>
	<option value="sitemap">&#xf0e8;</option>
	<option value="skyatlas">&#xf216;</option>
	<option value="skype">&#xf17e;</option>
	<option value="slack">&#xf198;</option>
	<option value="sliders">&#xf1de;</option>
	<option value="slideshare">&#xf1e7;</option>
	<option value="smile-o">&#xf118;</option>
	<option value="snapchat">&#xf2ab;</option>
	<option value="snapchat-ghost">&#xf2ac;</option>
	<option value="snapchat-square">&#xf2ad;</option>
	<option value="snowflake-o">&#xf2dc;</option>
	<option value="soccer-ball-o">&#xf1e3;</option>
	<option value="sort">&#xf0dc;</option>
	<option value="sort-alpha-asc">&#xf15d;</option>
	<option value="sort-alpha-desc">&#xf15e;</option>
	<option value="sort-amount-asc">&#xf160;</option>
	<option value="sort-amount-desc">&#xf161;</option>
	<option value="sort-asc">&#xf0de;</option>
	<option value="sort-desc">&#xf0dd;</option>
	<option value="sort-down">&#xf0dd;</option>
	<option value="sort-numeric-asc">&#xf162;</option>
	<option value="sort-numeric-desc">&#xf163;</option>
	<option value="sort-up">&#xf0de;</option>
	<option value="soundcloud">&#xf1be;</option>
	<option value="space-shuttle">&#xf197;</option>
	<option value="spinner">&#xf110;</option>
	<option value="spoon">&#xf1b1;</option>
	<option value="spotify">&#xf1bc;</option>
	<option value="square">&#xf0c8;</option>
	<option value="square-o">&#xf096;</option>
	<option value="stack-exchange">&#xf18d;</option>
	<option value="stack-overflow">&#xf16c;</option>
	<option value="star">&#xf005;</option>
	<option value="star-half">&#xf089;</option>
	<option value="star-half-empty">&#xf123;</option>
	<option value="star-half-full">&#xf123;</option>
	<option value="star-half-o">&#xf123;</option>
	<option value="star-o">&#xf006;</option>
	<option value="steam">&#xf1b6;</option>
	<option value="steam-square">&#xf1b7;</option>
	<option value="step-backward">&#xf048;</option>
	<option value="step-forward">&#xf051;</option>
	<option value="stethoscope">&#xf0f1;</option>
	<option value="sticky-note">&#xf249;</option>
	<option value="sticky-note-o">&#xf24a;</option>
	<option value="stop">&#xf04d;</option>
	<option value="stop-circle">&#xf28d;</option>
	<option value="stop-circle-o">&#xf28e;</option>
	<option value="street-view">&#xf21d;</option>
	<option value="strikethrough">&#xf0cc;</option>
	<option value="stumbleupon">&#xf1a4;</option>
	<option value="stumbleupon-circle">&#xf1a3;</option>
	<option value="subscript">&#xf12c;</option>
	<option value="subway">&#xf239;</option>
	<option value="suitcase">&#xf0f2;</option>
	<option value="sun-o">&#xf185;</option>
	<option value="superpowers">&#xf2dd;</option>
	<option value="superscript">&#xf12b;</option>
	<option value="support">&#xf1cd;</option>
	<option value="table">&#xf0ce;</option>
	<option value="tablet">&#xf10a;</option>
	<option value="tachometer">&#xf0e4;</option>
	<option value="tag">&#xf02b;</option>
	<option value="tags">&#xf02c;</option>
	<option value=tasks">&#xf0ae;</option>
	<option value="taxi">&#xf1ba;</option>
	<option value="telegram">&#xf2c6;</option>
	<option value="television">&#xf26c;</option>
	<option value="tencent-weibo">&#xf1d5;</option>
	<option value="terminal">&#xf120;</option>
	<option value="text-height">&#xf034;</option>
	<option value="text-width">&#xf035;</option>
	<option value="th">&#xf00a;</option>
	<option value="th-large">&#xf009;</option>
	<option value="th-list">&#xf00b;</option>
	<option value="themeisle">&#xf2b2;</option>
	<option value="thermometer">&#xf2c7;</option>
	<option value="thermometer-0">&#xf2cb;</option>
	<option value="thermometer-1">&#xf2ca;</option>
	<option value="thermometer-2">&#xf2c9;</option>
	<option value="thermometer-3">&#xf2c8;</option>
	<option value="thermometer-4">&#xf2c7;</option>
	<option value="thermometer-empty">&#xf2cb;</option>
	<option value="thermometer-full">&#xf2c7;</option>
	<option value="thermometer-half">&#xf2c9;</option>
	<option value="thermometer-quarter">&#xf2ca;</option>
	<option value="thermometer-three-quarters">&#xf2c8;</option>
	<option value="thumb-tack">&#xf08d;</option>
	<option value="thumbs-down">&#xf165;</option>
	<option value="thumbs-o-down">&#xf088;</option>
	<option value="thumbs-o-up">&#xf087;</option>
	<option value="thumbs-up">&#xf164;</option>
	<option value="ticket">&#xf145;</option>
	<option value="times">&#xf00d;</option>
	<option value="times-circle">&#xf057;</option>
	<option value="times-circle-o">&#xf05c;</option>
	<option value="times-rectangle">&#xf2d3;</option>
	<option value="times-rectangle-o">&#xf2d4;</option>
	<option value="tint">&#xf043;</option>
	<option value="toggle-down">&#xf150;</option>
	<option value="toggle-left">&#xf191;</option>
	<option value="toggle-off">&#xf204;</option>
	<option value="toggle-on">&#xf205;</option>
	<option value="toggle-right">&#xf152;</option>
	<option value="toggle-up">&#xf151;</option>
	<option value="trademark">&#xf25c;</option>
	<option value="train">&#xf238;</option>
	<option value="transgender">&#xf224;</option>
	<option value="transgender-alt">&#xf225;</option>
	<option value="trash">&#xf1f8;</option>
	<option value="trash-o">&#xf014;</option>
	<option value="tree">&#xf1bb;</option>
	<option value="trello">&#xf181;</option>
	<option value="tripadvisor">&#xf262;</option>
	<option value="trophy">&#xf091;</option>
	<option value="truck">&#xf0d1;</option>
	<option value="try">&#xf195;</option>
	<option value="tty">&#xf1e4;</option>
	<option value="tumblr">&#xf173;</option>
	<option value="tumblr-square">&#xf174;</option>
	<option value="turkish-lira">&#xf195;</option>
	<option value="tv">&#xf26c;</option>
	<option value="twitch">&#xf1e8;</option>
	<option value="twitter">&#xf099;</option>
	<option value="twitter-square">&#xf081;</option>
	<option value="umbrella">&#xf0e9;</option>
	<option value="underline">&#xf0cd;</option>
	<option value="undo">&#xf0e2;</option>
	<option value="universal-access">&#xf29a;</option>
	<option value="university">&#xf19c;</option>
	<option value="unlink">&#xf127;</option>
	<option value="unlock">&#xf09c;</option>
	<option value="unlock-alt">&#xf13e;</option>
	<option value="unsorted">&#xf0dc;</option>
	<option value="upload">&#xf093;</option>
	<option value="usb">&#xf287;</option>
	<option value="usd">&#xf155;</option>
	<option value="user">&#xf007;</option>
	<option value="user-circle">&#xf2bd;</option>
	<option value="user-circle-o">&#xf2be;</option>
	<option value="user-md">&#xf0f0;</option>
	<option value="user-o">&#xf2c0;</option>
	<option value="user-o">&#xf2c0;</option>
	<option value="user-plus">&#xf234;</option>
	<option value="user-secret">&#xf21b;</option>
	<option value="user-times">&#xf235;</option>
	<option value="users">&#xf0c0;</option>
	<option value="vcard">&#xf2bb;</option>
	<option value="vcard-o">&#xf2bc;</option>
	<option value="venus">&#xf221;</option>
	<option value="venus-double">&#xf226;</option>
	<option value="venus-mars">&#xf228;</option>
	<option value="viacoin">&#xf237;</option>
	<option value="viadeo">&#xf2a9;</option>
	<option value="viadeo-square">&#xf2aa;</option>
	<option value="video-camera">&#xf03d;</option>
	<option value="vimeo">&#xf27d;</option>
	<option value="vimeo-square">&#xf194;</option>
	<option value="vine">&#xf1ca;</option>
	<option value="vk">&#xf189;</option>
	<option value="volume-control-phone">&#xf2a0;</option>
	<option value="volume-down">&#xf027;</option>
	<option value="volume-off">&#xf026;</option>
	<option value="volume-up">&#xf028;</option>
	<option value="warning">&#xf071;</option>
	<option value="wechat">&#xf1d7;</option>
	<option value="weibo">&#xf18a;</option>
	<option value="weixin">&#xf1d7;</option>
	<option value="whatsapp">&#xf232;</option>
	<option value="wheelchair">&#xf193;</option>
	<option value="wheelchair-alt">&#xf29b;</option>
	<option value="wifi">&#xf1eb;</option>
	<option value="wikipedia-w">&#xf266;</option>
	<option value="window-close">&#xf2d3;</option>
	<option value="window-close-o">&#xf2d4;</option>
	<option value="window-maximize">&#xf2d0;</option>
	<option value="window-minimize">&#xf2d1;</option>
	<option value="window-restore">&#xf2d2;</option>
	<option value="windows">&#xf17a;</option>
	<option value="won">&#xf159;</option>
	<option value="wordpress">&#xf19a;</option>
	<option value="wpbeginner">&#xf297;</option>
	<option value="wpexplorer">&#xf2de;</option>
	<option value="wpforms">&#xf298;</option>
	<option value="wrench">&#xf0ad;</option>
	<option value="xing">&#xf168;</option>
	<option value="xing-square">&#xf169;</option>
	<option value="y-combinator">&#xf23b;</option>
	<option value="y-combinator-square">&#xf1d4;</option>
	<option value="yahoo">&#xf19e;</option>
	<option value="yc">&#xf23b;</option>
	<option value="yc-square">&#xf1d4;</option>
	<option value="yelp">&#xf1e9;</option>
	<option value="yen">&#xf157;</option>
	<option value="yoast">&#xf2b1;</option>
	<option value="youtube">&#xf167;</option>
	<option value="youtube-play">&#xf16a;</option>
	<option value="youtube-square">&#xf166;</option>
</select>
</div>
</div>
</div>

<div style="<?php echo $showpopup; ?>">
<div class="control-group">
<label class="control-label"><?php echo JText::_('Popuptext'); ?></label>	
<div class="controls">	
<input type="text" id="agosmsmarkerpopuptext">
</div>
</div>
</div>

<p>
<?php echo JText::_('PLG_AGOSMSADDRESSMARKER_OPTIONAL_HINT'); ?>	
</p>

<input 
	class="agosmsaddressmarkerhiddenfield" 
	type="hidden" 
	readonly name="<?php echo $name; ?>" id="<?php echo $id; ?>" 
	value="<?php echo htmlspecialchars($value, ENT_COMPAT, 'UTF-8'); ?>" <?php echo implode(' ', $attributes); ?> 
/>
</div>
<hr>