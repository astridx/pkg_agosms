document.addEventListener('DOMContentLoaded', function () {
	var agSliderFields = document.querySelectorAll('.agSliderField');

	[].forEach.call(agSliderFields, function (element) {

		var min = parseInt(element.getAttribute('data-slider-min'));
		var max = parseInt(element.getAttribute('data-slider-max'));
		var surroundingDivAmount = element.parentNode.parentNode;
		var inputAmount = surroundingDivAmount.getElementsByTagName('input');
		var inputforamount = inputAmount[0];
		var inputforfrom = inputAmount[2];
		var inputforto = inputAmount[3];

		var agosmsaddressmarkerSlider = new Slider(element, {
			range: true,
			value: [min, max]
		});
		
		inputforamount.onchange = function() {
			var splitedvalue = inputforamount.value.split("-").map(Number);

			if (splitedvalue[0] < min)
			{
				splitedvalue[0] = min;
			}

			if (splitedvalue[1] > max)
			{
				splitedvalue[1] = max;
			}

			if (splitedvalue[0] > splitedvalue[1])
			{
				splitedvalue[1] = splitedvalue[0];
			}

			inputforamount.value = splitedvalue[0] + ' - ' + splitedvalue[1];
			inputforfrom.value = splitedvalue[0];
			inputforto.value = splitedvalue[1];
			element.value = splitedvalue[0] + ',' + splitedvalue[1];
			console.log(agosmsaddressmarkerSlider);
			
			console.log(agosmsaddressmarkerSlider.getValue());
			
			agosmsaddressmarkerSlider.setValue([ splitedvalue[0], splitedvalue[1] ]);
			
			agosmsaddressmarkerSlider.refresh({useCurrentValue: true});
		};
		
		agosmsaddressmarkerSlider.on('slideStart', function (e) {
			agosmsaddressmarkerSlider.sliderLock = 1;
		});
		agosmsaddressmarkerSlider.on('slide', function (e) {
			inputforamount.value = e[0] + ' - ' + e[1];
		});
		agosmsaddressmarkerSlider.on('slideStop', function (e) {
			inputforamount.value = e[0] + ' - ' + e[1];
			inputforfrom.value = e[0];
			inputforto.value = e[1];
			agosmsaddressmarkerSlider.sliderLock = 0;
		});
	})

});

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
				Joomla.renderMessages({"notice": [(Joomla.JText._('PLG_AGOSMSSEARCH_ADDRESSE_NOTICE') + addressstring + ' (Nominatim)')]});
			} else if (results.length > 0) {
				// Limit is fix set to 1 up to now
			} else {
				Joomla.renderMessages({"error": [Joomla.JText._('MOD_AGOSMSSEARCH_ADDRESSE_ERROR') + addressstring + ' (Nominatim)']});
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
