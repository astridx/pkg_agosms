document.addEventListener('DOMContentLoaded',function(){var e=document.querySelectorAll('.agosmsaddressmarkerleafletmap');[].forEach.call(e,function(a){var E=a.getAttribute('data-addprivacybox'),t=a.getAttribute('data-unique'),l=document.getElementsByClassName(t);
if(localStorage.getItem('privacyState')===null){localStorage.setItem('privacyState','0')};
var o;
for(o=0;o<l.length;o++){if(localStorage.getItem('privacyState')==='0'){l[o].innerHTML=Joomla.JText._('PLG_AGOSMSADDRESSMARKER_PRIVACYBUTTON_SHOW_MAP')}
else{l[o].innerHTML=Joomla.JText._('PLG_AGOSMSADDRESSMARKER_PRIVACYBUTTON_HIDE_MAP')};
l[o].onclick=function(){if(localStorage.getItem('privacyState')==='0'){document.getElementById('map'+t).style.display='block';
localStorage.setItem('privacyState','1')}
else{localStorage.setItem('privacyState','0')};
window.location.reload()}};
if(E==='1'&&(localStorage.getItem('privacyState')==='0')){document.getElementById('map'+t).style.display='none';
return};
var w=a.getAttribute('data-uriroot'),u=a.getAttribute('data-maptype'),p=a.getAttribute('data-lat'),s=a.getAttribute('data-lon'),i=a.getAttribute('data-scrollwheelzoom'),G=a.getAttribute('data-owngooglegesturetext'),D=a.getAttribute('data-googekey'),I=Joomla.JText._('PLG_AGOSMSADDRESSMARKER_TOUCH'),x=Joomla.JText._('PLG_AGOSMSADDRESSMARKER_SCROLL'),O=Joomla.JText._('PLG_AGOSMSADDRESSMARKER_SCROLLMAC'),W=a.getAttribute('data-specialicon'),d=a.getAttribute('data-popup'),m=a.getAttribute('data-showroutingcontrol');
if(m==='1'){var K=a.getAttribute('data-routingprofile'),v=a.getAttribute('data-routinglanguage'),M=a.getAttribute('data-routingmetric'),f=a.getAttribute('data-routewhiledragging'),S=a.getAttribute('data-routing_position'),P=a.getAttribute('data-routing_router'),y=a.getAttribute('data-fitSelectedRoutes'),h=(a.getAttribute('data-reverseWaypoints')==='true'),A=a.getAttribute('data-collapsible'),b=a.getAttribute('data-showAlternatives')};
var J=a.getAttribute('data-iconcolor'),H=a.getAttribute('data-markercolor'),B=a.getAttribute('data-icon'),U=a.getAttribute('data-popuptext'),g=a.getAttribute('data-mapboxkey'),n=L.DomUtil.get('map'+t);
if(u=='mapbox'){var g=a.getAttribute('data-mapboxkey');
if(!n.children.length>0){if(i==='0'){window['map'+t]=new L.Map('map'+t,{scrollWheelZoom:!1})}
else{window['map'+t]=new L.Map('map'+t,{scrollWheelZoom:!0})}};
var k='https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token='+g,C='Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',Z=new L.TileLayer(k,{attribution:C,id:'mapbox.streets'});
window['map'+t].addLayer(Z)}
else if(u=='google'){var R=document.createElement('script');
R.setAttribute('src','https://maps.googleapis.com/maps/api/js?key='+D);
document.head.appendChild(R);
if(!n.children.length>0){if(i==='0'){window['map'+t]=new L.Map('map'+t,{scrollWheelZoom:!1})}
else{window['map'+t]=new L.Map('map'+t,{scrollWheelZoom:!0})}};
var z=L.gridLayer.googleMutant({type:'roadmap'}).addTo(window['map'+t])}
else{[].forEach.call(e,function(e){if(!n.children.length>0){if(i==='0'){window['map'+t]=new L.Map('map'+t,{scrollWheelZoom:!1})}
else{window['map'+t]=new L.Map('map'+t,{scrollWheelZoom:!0})}};
var a='https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',o='Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors',r=new L.TileLayer(a,{attribution:o});
window['map'+t].addLayer(r)})};
if(!n.children.length>0){if(i==='0'){window['map'+t]=new L.Map('map'+t,{scrollWheelZoom:!1})}
else if(i==='2'){if(G==='1'){window['map'+t]=new L.Map('map'+t,{gestureHandling:!0,gestureHandlingText:{touch:I,scroll:x,scrollMac:O}})}
else{window['map'+t]=new L.Map('map'+t,{gestureHandling:!0})}}
else{window['map'+t]=new L.Map('map'+t,{scrollWheelZoom:!0})}};
if(i==='0'){window['map'+t].on('click',function(){if(window['map'+t].scrollWheelZoom.enabled()){window['map'+t].scrollWheelZoom.disable()}
else{window['map'+t].scrollWheelZoom.enable()}})};
if(m==='1'){var c;
if(P==='mapbox'){c=L.Routing.control(L.extend({fitSelectedRoutes:y,position:S,units:M,router:L.Routing.mapbox(g,{profile:K,language:v,}),waypoints:[L.latLng(p,s),],geocoder:L.Control.Geocoder.nominatim(),routeWhileDragging:f,reverseWaypoints:h,collapsible:A,showAlternatives:b,altLineOptions:{styles:[{color:'black',opacity:0.15,weight:9},{color:'white',opacity:0.8,weight:6},{color:'blue',opacity:0.5,weight:2}]}})).addTo(window['map'+t])}
else{c=L.Routing.control(L.extend({fitSelectedRoutes:y,position:S,units:M,router:L.Routing.osrmv1({language:v}),waypoints:[L.latLng(p,s),],geocoder:L.Control.Geocoder.nominatim(),routeWhileDragging:f,reverseWaypoints:h,collapsible:A,showAlternatives:b,altLineOptions:{styles:[{color:'black',opacity:0.15,weight:9},{color:'white',opacity:0.8,weight:6},{color:'blue',opacity:0.5,weight:2}]}})).addTo(window['map'+t])};
L.Routing.errorControl(c).addTo(window['map'+t])};
try{window['map'+t].setView(new L.LatLng(p,s),13);
var r=L.marker([p,s]);
if(W==='1'){var T=new L.AwesomeMarkers.icon({icon:B,markerColor:H,iconColor:J,prefix:'fa',spin:!1,extraClasses:'agosmsaddressmarkericonclass',});
r.setIcon(T)};
r.addTo(window['map'+t]);
if(d==='1'){r.bindPopup(obj.popuptext.replace(/<img src="images/g,'<img src="'+w+'images'))};
if(d==='2'){r.bindPopup(obj.popuptext.replace(/<img src="images/g,'<img src="'+w+'images')).openPopup()}}catch(V){window['map'+t].setView(new L.LatLng(0,0),13);
var r=L.marker([0,0]).addTo(window['map'+t]);
console.log(V)}})},!1);