document.addEventListener('click', function(e) {
	if (e.target.classList.contains('agosmsaddressmarkerbutton')) {
		var button = e.target;
		var addressstring = button.getAttribute('data-addressstring');
		//alert(addressstring);
		var surroundingDiv = button.parentNode;
		var inputs = surroundingDiv.getElementsByTagName('input');
		var lat = inputs[0];
		var lon = inputs[1];
		var hiddenfield = inputs[2];	   

		
		
		
		
		
		lat.value = "55";
		lon.value = "8";
		hiddenfield.value = "55,8";
	   
	   
   }
    
}, false);

