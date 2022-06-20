document.addEventListener('DOMContentLoaded', function(){
	"use strict";
	setTimeout(function() {
		if (document.formvalidator) {
			document.formvalidator.setHandler('roadsaftydate', function (value) {

				var returnedValue = false;
				const roadsaftydate = new Date(value);

				if (roadsaftydate.getMonth() == 8) //8=september
					returnedValue = true;

				return returnedValue;
			});
		}
	}, (1000));
});