document.addEventListener('click', function (e) {
	if (e.target.classList.contains('agosmsaddressmarkerbutton')) {
		var button = e.target;
		var addressstring = [];
		var fieldsNameArray =  button.getAttribute('data-fieldsnamearray').split(',');
		var mapboxkey = button.getAttribute('data-mapboxkey');
		var surroundingDiv = button.parentNode;
		var inputs = surroundingDiv.getElementsByTagName('input');
		var lat = inputs[0];
		var lon = inputs[1];

		[].forEach.call(fieldsNameArray, function(el){
			var field = document.getElementById(el);
			addressstring.push(field.value);
		});

		addressstring = addressstring.join();

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
