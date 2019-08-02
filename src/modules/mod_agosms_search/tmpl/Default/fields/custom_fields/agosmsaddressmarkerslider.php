<?php
/**
 * @package     Joomla.Site
 * @subpackage  pkg_agosms
 *
 * @copyright   Copyright (C) 2005 - 2019 Astrid Günther, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later;
 * @link        astrid-guenther.de
 */


// no direct access
defined('_JEXEC') or die;

$field_params = json_decode($field->instance->fieldparams);

// Set defaults
$min = 0;
$max = 2000;
$step = 10;

if($field->instance->type == "agosmsaddressmarker") {
	$min = $field_params->first;
	$max = $field_params->last;
	$step = $field_params->step;
}
else {
	$values = $helper->getFieldValuesFromText($field->id, "int", $module->id);
	$min = $values[0];
	$max = $values[count($values) - 1];
	$step = 1;
}

$active_min =  $min;
if (JFactory::getApplication()->input->get->get("field{$field->id}-from")) {
	$active_min = JFactory::getApplication()->input->get->get("field{$field->id}-from");
}

$active_max =  $max;
if (JFactory::getApplication()->input->get->get("field{$field->id}-to")) {
	$active_max = JFactory::getApplication()->input->get->get("field{$field->id}-to");
}

$doc = JFactory::getDocument();
$doc->addScript('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/10.6.1/bootstrap-slider.min.js');
$doc->addStyleSheet('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/10.6.1/css/bootstrap-slider.min.css');
?>

<div class="gsearch-field-slider custom-field">	
	<h3>
		<?php echo JText::_("{$field->instance->label}"); ?>
	</h3>
	<div class="slider-wrapper">
		<div class="amount">
			<input id="amount-<?php echo "{$field->id}-{$module->id}"; ?>" type="text" style="border: none; background: none; text-align: center;"
				value="<?php echo $active_min; ?> - <?php echo $active_max; ?>"
			/>
		</div>
		<div style="padding: 0 10px;">
			<input id="slider-<?php echo "{$field->id}-{$module->id}"; ?>" class="SliderField" />
		</div>
		<script>
			jQuery(document).ready(function($) {
				//mootools conflict fix
				if(typeof MooTools != 'undefined' ) {
					Element.implement({
						slide: function(how, mode){
							return this;
						}
					});
				}
				
				$("#slider-<?php echo "{$field->id}-{$module->id}"; ?>").bootstrapSlider({ 
					min: <?php echo $min; ?>, 
					max: <?php echo $max; ?>, 
					range: true, 
					value: [<?php echo $active_min; ?>, <?php echo $active_max; ?>],
					tooltip: "hide",
					step: <?php echo $step; ?>,
				}).on("slideStart", function(ev) {
					sliderLock = 1;
				}).on("slide", function(ev) {
					$("input#amount-<?php echo "{$field->id}-{$module->id}"; ?>").val(ev.value[0] + ' - ' + ev.value[1]);
				}).on("slideStop", function(ev) {
					$("input#amount-<?php echo "{$field->id}-{$module->id}"; ?>").val(ev.value[0] + ' - ' + ev.value[1]);
					$("input[name=<?php echo "field{$field->id}-from"; ?>]").val(ev.value[0]);
					$("input[name=<?php echo "field{$field->id}-to"; ?>]").val(ev.value[1]);
					sliderLock = 0;
					$("#GSearch<?php echo $module->id; ?> form").trigger("change");
				});
				
				$("input#amount-<?php echo "{$field->id}-{$module->id}"; ?>").on("keyup", function() {
					var value = $(this).val().replace(/\s/g, "").split("-").map(Number);
					$("#slider-<?php echo "{$field->id}-{$module->id}"; ?>").bootstrapSlider('setValue', value);
					$("input[name=<?php echo "field{$field->id}-from"; ?>]").val(value[0]);
					$("input[name=<?php echo "field{$field->id}-to"; ?>]").val(value[1]);
				});
			});
		</script>
		<input class="inputbox" type="hidden" name="<?php echo "field{$field->id}-from"; ?>" value="<?php echo $active_min; ?>" />
		<input class="inputbox" type="hidden" name="<?php echo "field{$field->id}-to"; ?>" value="<?php $active_max; ?>" />
	</div>
</div>

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

<div class="control-group">
<label class="control-label"><?php echo JText::_('PLG_AGOSMSADDRESSMARKER_ADDRESS'); ?></label>	
<div class="controls">	
<input type="text" class="agosmsaddressmarkeraddress" >
</div>
</div>
	
<input class="inputbox" type="hidden" name="<?php echo "field{$field->id}-lat"; ?>" value="<?php echo $active_min; ?>" />
<input class="inputbox" type="hidden" name="<?php echo "field{$field->id}-lon"; ?>" value="<?php $active_max; ?>" />
<input class="inputbox" type="hidden" name="<?php echo "field{$field->id}-address"; ?>" value="<?php echo $active_min; ?>" />
	
<button 
		data-fieldsnamearray="<?php //echo $fieldsNameImplode; ?>"
		data-mapboxkey="<?php //echo $mapboxkey; ?>"
		data-googlekey="<?php //echo $googlekey; ?>"
		class="btn btn-success agosmsaddressmarkerbutton" 
		type="button">
<?php echo JText::_('PLG_AGOSMSADDRESSMARKER_CALCULATE_CORDS'); ?>
	</button>
</div>

<script>
document.addEventListener('click', function (e) {
	if (e.target.classList.contains('agosmsaddressmarkerbutton')) {
		var button = e.target;
		var surroundingDiv = button.parentNode;
		var inputs = surroundingDiv.getElementsByTagName('input');
		var lat = inputs[0];
		var lon = inputs[1];
		var address = inputs[2];

		addressstring = address.value;

		var cords = function (results, suggest) {
			if (!suggest && results.length === 1) {
				lat.value = results[0].lat;
				lon.value = results[0].lon;
				Joomla.renderMessages({"notice": [(Joomla.JText._('PLG_AGOSMSADDRESSMARKER_ADDRESSE_NOTICE') + addressstring + ' (Nominatim)')]});
			} else if (results.length > 0) {
				// Limit is fix set to 1 up to now
			} else {
				Joomla.renderMessages({"error": [Joomla.JText._('PLG_AGOSMSADDRESSMARKER_ADDRESSE_ERROR') + addressstring + ' (Nominatim)']});
			}
		}

		var params = {
			q: addressstring,
			limit: 1,
			format: 'json',
			addressdetails: 1
		};

		getJSON("https://nominatim.openstreetmap.org/", params, cords);
	}
});

function getJSON(url, params, callback) {
	var xmlHttp = new XMLHttpRequest();
	xmlHttp.onreadystatechange = function () {
		if (xmlHttp.readyState !== 4) {
			return;
		}
		if (xmlHttp.status !== 200 && xmlHttp.status !== 304) {
			callback('');
			return;
		}
		callback(xmlHttp.response);
	};
	xmlHttp.open('GET', url + getParamString(params), true);
	xmlHttp.responseType = 'json';
	xmlHttp.setRequestHeader('Accept', 'application/json');
	xmlHttp.send(null);
}

function getParamString(obj, existingUrl, uppercase) {
	var params = [];
	for (var i in obj) {
		var key = encodeURIComponent(uppercase ? i.toUpperCase() : i);
		var value = obj[i];
		if (!L.Util.isArray(value)) {
			params.push(key + '=' + encodeURIComponent(value));
		} else {
			for (var j = 0; j < value.length; j++) {
				params.push(key + '=' + encodeURIComponent(value[j]));
			}
		}
	}
	return (!existingUrl || existingUrl.indexOf('?') === -1 ? '?' : '&') + params.join('&');
}


</script>