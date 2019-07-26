document.addEventListener('click', function (e) {
	if (e.target.classList.contains('agosmsaddressmarkerbutton')) {
		var button = e.target;
		var addressstring = [];
		var fieldsNameArray =  button.getAttribute('data-fieldsnamearray').split(',');
		var surroundingDiv = button.parentNode;
		var inputs = surroundingDiv.getElementsByTagName('input');
		var lat = inputs[0];
		var lon = inputs[1];
		var hiddenfield = inputs[2];

		[].forEach.call(fieldsNameArray, function(el){
			var field = document.getElementById(el);
			addressstring.push(field.value);
		});

		addressstring = addressstring.join();

		var cords = function (results, suggest) {
			if (!suggest && results.length === 1) {
				lat.value = results[0].lat;
				lon.value = results[0].lon;
				hiddenfield.value = results[0].lat + "," + results[0].lon;
				tempAlert("OK: " + addressstring, 2000, "28a745");
			} else if (results.length > 0) {
				// Limit is fix set to 1 up to now
			} else {
				tempAlert("Error: " + addressstring, 2000, "dc3545");
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
