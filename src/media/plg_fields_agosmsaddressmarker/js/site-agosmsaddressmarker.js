;
document.addEventListener('DOMContentLoaded',function(){var e=document.querySelectorAll('.agosmsaddressmarkerleafletmap');[].forEach.call(e,function(o){var c=o.getAttribute('data-uriroot'),t=o.getAttribute('data-unique'),p=o.getAttribute('data-maptype'),l=o.getAttribute('data-lat'),r=o.getAttribute('data-lon'),a=o.getAttribute('data-scrollwheelzoom'),x=o.getAttribute('data-owngooglegesturetext'),S=Joomla.JText._('PLG_AGOSMSADDRESSMARKER_TOUCH'),W=Joomla.JText._('PLG_AGOSMSADDRESSMARKER_SCROLL'),R=Joomla.JText._('PLG_AGOSMSADDRESSMARKER_SCROLLMAC'),v=o.getAttribute('data-specialicon'),m=o.getAttribute('data-popup'),d=o.getAttribute('data-showroutingcontrol');
if(d==='1'){var C=o.getAttribute('data-routingprofile'),M=o.getAttribute('data-routinglanguage'),A=o.getAttribute('data-routingmetric'),y=o.getAttribute('data-routewhiledragging'),f=o.getAttribute('data-routing_position'),k=o.getAttribute('data-routing_router'),h=o.getAttribute('data-fitSelectedRoutes'),b=(o.getAttribute('data-reverseWaypoints')==='true'),u=o.getAttribute('data-collapsible'),w=o.getAttribute('data-showAlternatives')};
var O=o.getAttribute('data-iconcolor'),Z=o.getAttribute('data-markercolor'),T=o.getAttribute('data-icon'),H=o.getAttribute('data-popuptext'),g=o.getAttribute('data-mapboxkey');
console.log(p);
var n=L.DomUtil.get('map'+t);
if(p=='mapbox'){var g=o.getAttribute('data-mapboxkey');
if(!n.children.length>0){if(a==='0'){window['map'+t]=new L.Map('map'+t,{scrollWheelZoom:!1})}
else{window['map'+t]=new L.Map('map'+t,{scrollWheelZoom:!0})}};
var G='https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token='+g,E='Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',D=new L.TileLayer(G,{attribution:E,id:'mapbox.streets'});
window['map'+t].addLayer(D)}
else if(p=='google'){if(!n.children.length>0){if(a==='0'){window['map'+t]=new L.Map('map'+t,{scrollWheelZoom:!1})}
else{window['map'+t]=new L.Map('map'+t,{scrollWheelZoom:!0})}};
var K=L.gridLayer.googleMutant({type:'roadmap'}).addTo(window['map'+t])}
else{[].forEach.call(e,function(e){if(!n.children.length>0){if(a==='0'){window['map'+t]=new L.Map('map'+t,{scrollWheelZoom:!1})}
else{window['map'+t]=new L.Map('map'+t,{scrollWheelZoom:!0})}};
var o='https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',i='Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors',r=new L.TileLayer(o,{attribution:i});
window['map'+t].addLayer(r)})};
if(!n.children.length>0){if(a==='0'){window['map'+t]=new L.Map('map'+t,{scrollWheelZoom:!1})}
else if(a==='2'){if(x==='1'){window['map'+t]=new L.Map('map'+t,{gestureHandling:!0,gestureHandlingText:{touch:S,scroll:W,scrollMac:R}})}
else{window['map'+t]=new L.Map('map'+t,{gestureHandling:!0})}}
else{window['map'+t]=new L.Map('map'+t,{scrollWheelZoom:!0})}};
if(a==='0'){window['map'+t].on('click',function(){if(window['map'+t].scrollWheelZoom.enabled()){window['map'+t].scrollWheelZoom.disable()}
else{window['map'+t].scrollWheelZoom.enable()}})};
if(d==='1'){var s;
if(k==='mapbox'){s=L.Routing.control(L.extend({fitSelectedRoutes:h,position:f,units:A,router:L.Routing.mapbox(g,{profile:C,language:M,}),waypoints:[L.latLng(l,r),],geocoder:L.Control.Geocoder.nominatim(),routeWhileDragging:y,reverseWaypoints:b,collapsible:u,showAlternatives:w,altLineOptions:{styles:[{color:'black',opacity:0.15,weight:9},{color:'white',opacity:0.8,weight:6},{color:'blue',opacity:0.5,weight:2}]}})).addTo(window['map'+t])}
else{s=L.Routing.control(L.extend({fitSelectedRoutes:h,position:f,units:A,router:L.Routing.osrmv1({language:M}),waypoints:[L.latLng(l,r),],geocoder:L.Control.Geocoder.nominatim(),routeWhileDragging:y,reverseWaypoints:b,collapsible:u,showAlternatives:w,altLineOptions:{styles:[{color:'black',opacity:0.15,weight:9},{color:'white',opacity:0.8,weight:6},{color:'blue',opacity:0.5,weight:2}]}})).addTo(window['map'+t])};
L.Routing.errorControl(s).addTo(window['map'+t])};
try{window['map'+t].setView(new L.LatLng(l,r),13);
var i=L.marker([l,r]);
if(v==='1'){var J=new L.AwesomeMarkers.icon({icon:T,markerColor:Z,iconColor:O,prefix:'fa',spin:!1,extraClasses:'agosmsaddressmarkericonclass',});
i.setIcon(J)};
i.addTo(window['map'+t]);
if(m==='1'){i.bindPopup(obj.popuptext.replace(/<img src="images/g,'<img src="'+c+'images'))};
if(m==='2'){i.bindPopup(obj.popuptext.replace(/<img src="images/g,'<img src="'+c+'images')).openPopup()}}catch(P){window['map'+t].setView(new L.LatLng(0,0),13);
var i=L.marker([0,0]).addTo(window['map'+t]);
console.log(P)}})},!1);