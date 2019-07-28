document.addEventListener('click', function (e) {
	if (e.target.classList.contains('agosmsaddressmarkerbutton')) {
		var button = e.target;
		var addressstring = [];
		var fieldsNameArray =  button.getAttribute('data-fieldsnamearray').split(',');
		var googlekey = button.getAttribute('data-googlekey');
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
