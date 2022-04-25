document.addEventListener('DOMContentLoaded', function () {
/*	document.formvalidator.setHandler('lat', function(value) {
		if (value == 0) {
			return true;
		}
		document.getElementById("addressmarker-alert-latlon").style.display = "none";
		var test = false;
		var latmax = document.getElementById("latmax").value;
		var latmin = document.getElementById("latmin").value;
		regex=/^(\+|-)?(?:90(?:(?:\.0{1,6})?)|(?:[0-9]|[1-8][0-9])(?:(?:\.[0-9]{1,6})?))$/;
		test = regex.test(Math.round(value));
		if (Math.round(value) > latmax || Math.round(value) < latmin) {
			test = false;
			Joomla.renderMessages({"error": [Joomla.JText._('PLG_AGOSMSADDRESSMARKER_LAT_ERROR')]});
			document.getElementById("addressmarker-alert-latlon").innerHTML = Joomla.JText._('PLG_AGOSMSADDRESSMARKER_LAT_ERROR');
			document.getElementById("addressmarker-alert-latlon").style.display = "block";
		}
		return test;
	});*/
});

document.addEventListener('DOMContentLoaded', function () {
/*	document.formvalidator.setHandler('lon', function(value) {
		if (value == 0) {
			return true;
		}
		document.getElementById("addressmarker-alert-latlon").style.display = "none";
		var test = false;
		var lonmax = document.getElementById("lonmax").value;
		var lonmin = document.getElementById("lonmin").value;
		regex=/^(\+|-)?(?:180(?:(?:\.0{1,6})?)|(?:[0-9]|[1-9][0-9]|1[0-7][0-9])(?:(?:\.[0-9]{1,6})?))$/;
		test = regex.test(Math.round(value));
		if (Math.round(value) > lonmax || Math.round(value) < lonmin) {
			test = false;
			Joomla.renderMessages({"error": [Joomla.JText._('PLG_AGOSMSADDRESSMARKER_LON_ERROR')]});
			document.getElementById("addressmarker-alert-latlon").innerHTML = Joomla.JText._('PLG_AGOSMSADDRESSMARKER_LON_ERROR');
			document.getElementById("addressmarker-alert-latlon").style.display = "block";
		}
		return test;
	});*/
});


document.addEventListener('DOMContentLoaded', function () {

	var agosmsaddressmarkersurroundingdiv = document.querySelectorAll('.agosmsaddressmarkersurroundingdiv');

	// For all fields [start]
	[].forEach.call(agosmsaddressmarkersurroundingdiv, function (element) {
		// Set the fields 
		// Todo Error Handling
		var inputs = element.getElementsByTagName('input');
		var lat = inputs[0];
		var lon = inputs[1];
		
		// com_user has different inputs in registration form
		var popuptext = "";
		if (inputs[5]) {
			popuptext = inputs[5];
		} else {
			popuptext = inputs[2];
		}
		
		var hiddenfield = '';
		if (inputs[6]) {
			hiddenfield = inputs[6]
		} else {
			hiddenfield = inputs[3]
		}

		var selects = element.getElementsByTagName('select');

		var iconcolor = selects[0];
		var markercolor = selects[1];
		var icon = selects[2];

		var iconcolorSpan = iconcolor.parentNode.getElementsByTagName('option')[0];
		var markercolorSpan = markercolor.parentNode.getElementsByTagName('option')[0];
		var iconSpan = icon.parentNode.getElementsByTagName('option')[0];

		console.log(markercolor);
		console.log(markercolorSpan);

		// Write the value to the fields
		if (hiddenfield.value.split(',').length !== 6) {
			hiddenfield.value = '0,0,,,,,';
		}
		;
		var hf = hiddenfield.value.split(',');

		lat.value = hf[0];

		lon.value = hf[1];

		if (hf[2] !== '') {
			iconcolor.value = hf[2];
			while (iconcolorSpan.firstChild) {
				iconcolorSpan.removeChild(iconcolorSpan.firstChild);
			}
			iconcolorSpan.appendChild(document.createTextNode(hf[2]));
		}

		if (hf[3] !== '') {
			markercolor.value = hf[3];
			while (markercolorSpan.firstChild) {
				markercolorSpan.removeChild(markercolorSpan.firstChild);
			}
			markercolorSpan.appendChild(document.createTextNode(hf[3]));
		}

		if (hf[4] !== '') {
			icon.value = hf[4];
			while (iconSpan.firstChild) {
				iconSpan.removeChild(iconSpan.firstChild);
			}
			iconSpan.appendChild(document.createTextNode(hf[4]));
		}

		if (hf[5] !== '') {
			popuptext.value = hf[5];
		}

		// Write the value to the hidden field if lat or lon is changed
		lat.onchange = function () {
			changeValue();
		};

		lon.onchange = function () {
			changeValue();
		};

		iconcolor.onchange = function () {
			changeValue();
		};

		markercolor.onchange = function () {
			changeValue();
		};

		icon.onchange = function () {
			changeValue();
		};

		popuptext.onchange = function () {
			changeValue();
		};

		function changeValue() {
			hiddenfield.value = lat.value
				+ ',' + lon.value
				+ ',' + iconcolor.value
				+ ',' + markercolor.value
				+ ',' + icon.value
				+ ',' + popuptext.value;
		}


		var button = element.getElementsByTagName('button')[0];
		var fieldsNameArray = button.getAttribute('data-fieldsnamearray').split(',');
		var geocoder = button.getAttribute('data-geocoder');
		var googlekey = button.getAttribute('data-googlekey');
		var mapboxkey = button.getAttribute('data-mapboxkey');


		button.onclick = function () {
			var addressstring = [];
			[].forEach.call(fieldsNameArray, function (el) {
				var field = document.getElementById(el);
				addressstring.push(field.value);
			});
			addressstring = addressstring.join();

			if (geocoder === "mapbox")
			{
				var cords = function (results) {
					if (results.features && results.features.length === 1) {
						var lonlat = results.features[0].center;
						lat.value = lonlat[1];
						lon.value = lonlat[0];
						lon.onchange();
						Joomla.renderMessages({"notice": [(Joomla.JText._('PLG_AGOSMSADDRESSMARKER_ADDRESSE_NOTICE') + addressstring) + ' (Mapbox)']});
					} else if (results.features && results.features.length > 0) {
						// Limit is fix set to 1 up to now
					} else {
						Joomla.renderMessages({"error": [Joomla.JText._('PLG_AGOSMSADDRESSMARKER_ADDRESSE_ERROR') + addressstring + ' (Mapbox)']});
					}
				}
				var params = {
					limit: 1,
					access_token: mapboxkey
				};
				getJSON("https://api.mapbox.com/geocoding/v5/mapbox.places/" + encodeURIComponent(addressstring) + '.json', params, cords);
			} else if (geocoder === "google")
			{
				var cords = function (results) {
					if (results.status === "OK") {
						var lonlat = results.results[0].geometry.location;
						lat.value = lonlat.lat;
						lon.value = lonlat.lng;
						lon.onchange();
						Joomla.renderMessages({"notice": [(Joomla.JText._('PLG_AGOSMSADDRESSMARKER_ADDRESSE_NOTICE') + addressstring + ' (Google)')]});
					} else {
						var message = (typeof results.error_message == 'undefined') ? "" : results.error_message;
						Joomla.renderMessages({"error": [Joomla.JText._('PLG_AGOSMSADDRESSMARKER_ADDRESSE_ERROR') + addressstring + ' (Google: ' + results.status + ' ' + message + ')']});
					}
				}
				var params = {
					address: addressstring,
					limit: 1,
					key: googlekey
				};

				getJSON("https://maps.googleapis.com/maps/api/geocode/json", params, cords);
			} else
			{
				var cords = function (results, suggest) {
					if (!suggest && results.length === 1) {
						lat.value = results[0].lat;
						lon.value = results[0].lon;
						lon.onchange();
						Joomla.renderMessages({"notice": [(Joomla.JText._('PLG_AGOSMSADDRESSMARKER_ADDRESSE_NOTICE') + addressstring + ' (Nominatim)')]});
						document.getElementById("addressmarker-alert").style.display = "none";
					} else if (results.length > 0) {
						// Limit is fix set to 1 up to now
					} else {
						document.getElementById("addressmarker-alert").innerHTML = Joomla.JText._('PLG_AGOSMSADDRESSMARKER_ADDRESSE_ERROR') + addressstring;
						document.getElementById("addressmarker-alert").style.display = "block";
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
		}



	}); // For all fields [end]
}, false);

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
