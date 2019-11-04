document.addEventListener('DOMContentLoaded', function () {

	var agosmsaddressmarkersurroundingdiv = document.querySelectorAll('.agosmsaddressmarkersurroundingdiv');

	// For all fields [start]
	[].forEach.call(agosmsaddressmarkersurroundingdiv, function (element) {
		// Set the fields 
		// Todo Error Handling
		var inputs = element.getElementsByTagName('input');
		var lat = inputs[0];
		var lon = inputs[1];
		var hiddenfield = inputs[2];
		
		// Write the value to the fields lat and lon 
		// Todo Check if number pherhaps on change
		if (hiddenfield.value.split(',').length !== 2) {
			hiddenfield.value = '0,0';
		};
		var hf = hiddenfield.value.split(',');
		lat.value = hf[0];
		lon.value = hf[1];
		
		// Write the value to the hidden field if lat or lon is changed
		lat.onchange = function() {
		hiddenfield.value = lat.value + ',' + lon.value;
		};

		lon.onchange = function() {
		hiddenfield.value = lat.value + ',' + lon.value;
		};
	
	});
	// For all fields [end]

}, false);