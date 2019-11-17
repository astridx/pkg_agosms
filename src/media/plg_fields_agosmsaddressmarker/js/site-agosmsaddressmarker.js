;
document.addEventListener('DOMContentLoaded',function(){var e=document.querySelectorAll('.agosmsaddressmarkerleafletmap');[].forEach.call(e,function(a){var E=a.getAttribute('data-addprivacybox'),t=a.getAttribute('data-unique'),l=document.getElementsByClassName(t),i;
if(localStorage.getItem('privacyState')===null){localStorage.setItem('privacyState','0')};
for(i=0;i<l.length;i++){if(localStorage.getItem('privacyState')==='0'){l[i].innerHTML=Joomla.JText._('PLG_AGOSMSADDRESSMARKER_PRIVACYBUTTON_SHOW_MAP')}
else{l[i].innerHTML=Joomla.JText._('PLG_AGOSMSADDRESSMARKER_PRIVACYBUTTON_HIDE_MAP')};
l[i].onclick=function(){if(localStorage.getItem('privacyState')==='0'){document.getElementById('map'+t).style.display='block';
localStorage.setItem('privacyState','1')}
else{localStorage.setItem('privacyState','0')};
window.location.reload()}};
if(E==='1'&&(localStorage.getItem('privacyState')==='0')){document.getElementById('map'+t).style.display='none';
return};
var w=a.getAttribute('data-uriroot'),u=a.getAttribute('data-maptype'),p=a.getAttribute('data-lat'),n=a.getAttribute('data-lon'),o=a.getAttribute('data-scrollwheelzoom'),P=a.getAttribute('data-owngooglegesturetext'),k=Joomla.JText._('PLG_AGOSMSADDRESSMARKER_TOUCH'),I=Joomla.JText._('PLG_AGOSMSADDRESSMARKER_SCROLL'),x=Joomla.JText._('PLG_AGOSMSADDRESSMARKER_SCROLLMAC'),D=a.getAttribute('data-specialicon'),m=a.getAttribute('data-popup'),d=a.getAttribute('data-showroutingcontrol');
if(d==='1'){var H=a.getAttribute('data-routingprofile'),M=a.getAttribute('data-routinglanguage'),y=a.getAttribute('data-routingmetric'),f=a.getAttribute('data-routewhiledragging'),v=a.getAttribute('data-routing_position'),R=a.getAttribute('data-routing_router'),S=a.getAttribute('data-fitSelectedRoutes'),h=(a.getAttribute('data-reverseWaypoints')==='true'),A=a.getAttribute('data-collapsible'),b=a.getAttribute('data-showAlternatives')};
var G=a.getAttribute('data-iconcolor'),Z=a.getAttribute('data-markercolor'),J=a.getAttribute('data-icon'),K=a.getAttribute('data-popuptext'),c=a.getAttribute('data-mapboxkey'),s=L.DomUtil.get('map'+t);
if(u=='mapbox'){var c=a.getAttribute('data-mapboxkey');
if(!s.children.length>0){if(o==='0'){window['map'+t]=new L.Map('map'+t,{scrollWheelZoom:!1})}
else{window['map'+t]=new L.Map('map'+t,{scrollWheelZoom:!0})}};
var W='https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token='+c,C='Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',O=new L.TileLayer(W,{attribution:C,id:'mapbox.streets'});
window['map'+t].addLayer(O)}
else if(u=='google'){if(!s.children.length>0){if(o==='0'){window['map'+t]=new L.Map('map'+t,{scrollWheelZoom:!1})}
else{window['map'+t]=new L.Map('map'+t,{scrollWheelZoom:!0})}};
var V=L.gridLayer.googleMutant({type:'roadmap'}).addTo(window['map'+t])}
else{[].forEach.call(e,function(e){if(!s.children.length>0){if(o==='0'){window['map'+t]=new L.Map('map'+t,{scrollWheelZoom:!1})}
else{window['map'+t]=new L.Map('map'+t,{scrollWheelZoom:!0})}};
var a='https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',i='Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors',r=new L.TileLayer(a,{attribution:i});
window['map'+t].addLayer(r)})};
if(!s.children.length>0){if(o==='0'){window['map'+t]=new L.Map('map'+t,{scrollWheelZoom:!1})}
else if(o==='2'){if(P==='1'){window['map'+t]=new L.Map('map'+t,{gestureHandling:!0,gestureHandlingText:{touch:k,scroll:I,scrollMac:x}})}
else{window['map'+t]=new L.Map('map'+t,{gestureHandling:!0})}}
else{window['map'+t]=new L.Map('map'+t,{scrollWheelZoom:!0})}};
if(o==='0'){window['map'+t].on('click',function(){if(window['map'+t].scrollWheelZoom.enabled()){window['map'+t].scrollWheelZoom.disable()}
else{window['map'+t].scrollWheelZoom.enable()}})};
if(d==='1'){var g;
if(R==='mapbox'){g=L.Routing.control(L.extend({fitSelectedRoutes:S,position:v,units:y,router:L.Routing.mapbox(c,{profile:H,language:M,}),waypoints:[L.latLng(p,n),],geocoder:L.Control.Geocoder.nominatim(),routeWhileDragging:f,reverseWaypoints:h,collapsible:A,showAlternatives:b,altLineOptions:{styles:[{color:'black',opacity:0.15,weight:9},{color:'white',opacity:0.8,weight:6},{color:'blue',opacity:0.5,weight:2}]}})).addTo(window['map'+t])}
else{g=L.Routing.control(L.extend({fitSelectedRoutes:S,position:v,units:y,router:L.Routing.osrmv1({language:M}),waypoints:[L.latLng(p,n),],geocoder:L.Control.Geocoder.nominatim(),routeWhileDragging:f,reverseWaypoints:h,collapsible:A,showAlternatives:b,altLineOptions:{styles:[{color:'black',opacity:0.15,weight:9},{color:'white',opacity:0.8,weight:6},{color:'blue',opacity:0.5,weight:2}]}})).addTo(window['map'+t])};
L.Routing.errorControl(g).addTo(window['map'+t])};
try{window['map'+t].setView(new L.LatLng(p,n),13);
var r=L.marker([p,n]);
if(D==='1'){var T=new L.AwesomeMarkers.icon({icon:J,markerColor:Z,iconColor:G,prefix:'fa',spin:!1,extraClasses:'agosmsaddressmarkericonclass',});
r.setIcon(T)};
r.addTo(window['map'+t]);
if(m==='1'){r.bindPopup(obj.popuptext.replace(/<img src="images/g,'<img src="'+w+'images'))};
if(m==='2'){r.bindPopup(obj.popuptext.replace(/<img src="images/g,'<img src="'+w+'images')).openPopup()}}catch(B){window['map'+t].setView(new L.LatLng(0,0),13);
var r=L.marker([0,0]).addTo(window['map'+t]);
console.log(B)}})},!1);