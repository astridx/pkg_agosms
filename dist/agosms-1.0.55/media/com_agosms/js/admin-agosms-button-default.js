/**
 * @copyright  Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
(function () {
	"use strict";
	document.addEventListener('DOMContentLoaded', function () {
		const cordsTextField = document.getElementById("jform_paramsmodal_cords_map");
		const unique = cordsTextField.getAttribute('data-unique');
		window['mymap' + unique] = L.map('mapid' + unique).setView([50.27264, 7.26469], 3);
		L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png').addTo(window['mymap' + unique]);
		window['mymap' + unique].on('click', onMapClick);
		var marker = L.marker([50.28, 7.27]).addTo(window['mymap' + unique]).bindPopup(Joomla.JText._('COM_AGOSMS_BUTTON_DEFAULT_POPUP_PROMPT')).openPopup();
		cordsTextField.value = "50.28, 7.27";
		function onMapClick(e) {
			const cordsTextField = document.getElementById("jform_paramsmodal_cords_map");
			if (cordsTextField) {
				cordsTextField.value = e.latlng.lat + ', ' + e.latlng.lng;
				var lat = (e.latlng.lat);
				var lng = (e.latlng.lng);
				var newLatLng = new L.LatLng(lat, lng);
				marker.setLatLng(newLatLng);
			}
		}

		var parts = window.location.search.substr(1).split("&");
		var $_GET = {};
		for (var i = 0; i < parts.length; i++) {
			var temp = parts[i].split("=");
			$_GET[decodeURIComponent(temp[0])] = decodeURIComponent(temp[1]);
		}

		const buttonSaveSelected = document.getElementById("buttonsaveselected");
		const cordsParentTextField = window.parent.document.getElementById($_GET.fieldid);
		if (buttonSaveSelected && cordsParentTextField && cordsTextField) {
			buttonSaveSelected.addEventListener('click', function () {
				cordsParentTextField.setAttribute("readonly", false);
				cordsParentTextField.value = cordsTextField.value;
				cordsParentTextField.setAttribute("readonly", true);
				window.parent.jModalClose();
			});
		}

	});
})();
