document.addEventListener('DOMContentLoaded',function(){var t=document.querySelectorAll('.leafletmapModSearch');[].forEach.call(t,function(t){var n=t.getAttribute('data-scrollwheelzoom'),d=t.getAttribute('data-no-world-warp'),e=t.getAttribute('data-module-id'),l=t.getAttribute('data-detect-retina'),o=t.getAttribute('data-baselayer'),s=t.getAttribute('data-lonlat').split(',',3),i=t.getAttribute('data-zoom'),Z=t.getAttribute('data-mapboxkey'),f=t.getAttribute('data-thunderforestkey'),y=t.getAttribute('data-stamenmaptype'),x=t.getAttribute('data-thunderforestmaptype'),A=t.getAttribute('data-googlemapstype'),M=t.getAttribute('data-mapboxmaptype'),v=t.getAttribute('data-attr-module'),pt=t.getAttribute('data-customBaselayer'),st=t.getAttribute('data-customBaselayerURL'),C=t.getAttribute('data-scale'),S=t.getAttribute('data-scale-metric'),k=t.getAttribute('data-scale-imperial'),et=t.getAttribute('data-showgeocoder'),R=t.getAttribute('data-useesri'),tt=t.getAttribute('data-esrireversegeocoding'),q=(t.getAttribute('data-geocodercollapsed')==='true'),P=t.getAttribute('data-geocoderposition'),U=t.getAttribute('data-expand'),E=(t.getAttribute('data-esrigeocoderopengetaddress')==='true'),N=t.getAttribute('data-showgeocoderesri'),D=t.getAttribute('data-positionesrigeocoder'),G=(t.getAttribute('data-esrigeocoderzoomToResult')==='true'),I=(t.getAttribute('data-esrigeocoderuseMapBounds')==='true'),Y=(t.getAttribute('data-esrigeocodercollapseAfterResult')==='true'),H=(t.getAttribute('data-esrigeocoderexpanded')==='true'),W=(t.getAttribute('data-esriallowMultipleResults')==='true'),O=t.getAttribute('data-showrouting-simple');
if(O==='1'){var V=t.getAttribute('data-route-simple-position'),at=t.getAttribute('data-route-simple-target'),ot=t.getAttribute('data-route-simple-router'),wt=t.getAttribute('data-route-simple-routerkey')};
var J=t.getAttribute('data-showrouting');
if(J==='1'){var dt=t.getAttribute('data-routingstart').split(',',3),lt=t.getAttribute('data-routingend').split(',',3),mt=t.getAttribute('data-mapboxkey-routing'),ut=t.getAttribute('data-routingprofile'),gt=t.getAttribute('data-routinglanguage'),ct=t.getAttribute('data-routingmetric'),ht=t.getAttribute('data-routewhiledragging')};
var z=t.getAttribute('data-showpin');
if(z==='1'){var it=JSON.parse(t.getAttribute('data-specialpins'))};
var T=t.getAttribute('data-showcomponentpin');
if(T==='1'){var rt=JSON.parse(t.getAttribute('data-specialcomponentpins'))};
var nt=t.getAttribute('data-showcustomfieldpin'),u=JSON.parse(t.getAttribute('data-specialcustomfieldpins')),g=t.getAttribute('data-touch'),b=t.getAttribute('data-scroll'),c=t.getAttribute('data-scrollmac'),h=t.getAttribute('data-owngooglegesturetext');
if(d==='1'&&n==='0'){window['mysearchmap'+e]=new L.Map('searchmap'+e,{scrollWheelZoom:!1,worldCopyJump:!1,maxBounds:[[82,-180],[-82,180]]}).setView(s,i)}
else if(d==='1'&&n==='1'){window['mysearchmap'+e]=new L.Map('searchmap'+e,{worldCopyJump:!1,maxBounds:[[82,-180],[-82,180]]}).setView(s,i)}
else if(d==='1'&&n==='2'){if(h==='1'){window['mysearchmap'+e]=new L.Map('searchmap'+e,{worldCopyJump:!1,maxBounds:[[82,-180],[-82,180]],gestureHandling:!0,gestureHandlingText:{touch:g,scroll:b,scrollMac:c}}).setView(s,i)}
else{window['mysearchmap'+e]=new L.Map('searchmap'+e,{worldCopyJump:!1,maxBounds:[[82,-180],[-82,180]],gestureHandling:!0}).setView(s,i)}}
else if(d==='0'&&n==='0'){window['mysearchmap'+e]=new L.Map('searchmap'+e,{scrollWheelZoom:!1,worldCopyJump:!0}).setView(s,i)}
else if(d==='0'&&n==='2'){if(h==='1'){window['mysearchmap'+e]=new L.Map('searchmap'+e,{worldCopyJump:!0,gestureHandling:!0,gestureHandlingText:{touch:g,scroll:b,scrollMac:c}}).setView(s,i)}
else{window['mysearchmap'+e]=new L.Map('searchmap'+e,{worldCopyJump:!0,gestureHandling:!0}).setView(s,i)}}
else{window['mysearchmap'+e]=new L.Map('searchmap'+e,{worldCopyJump:!0}).setView(s,i)};
if(n==='0'){window['mysearchmap'+e].on('click',function(){if(window['mysearchmap'+e].scrollWheelZoom.enabled()){window['mysearchmap'+e].scrollWheelZoom.disable()}
else{window['mysearchmap'+e].scrollWheelZoom.enable()}})};
var B='noWrap: false, ';
if(d==='1'){B='noWrap: true, '};
var l='detectRetina: false, ';
if(l==='1'){l='detectRetina: true, '};
var r='';
if(v==='1'){r=' '+Joomla.JText._('MOD_AGOSM_MODULE_BY')+' <a href="https://www.astrid-guenther.de">Astrid Günther</a>'};
var a=L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{maxZoom:18,attribution:'&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'+r});
if(o==='mapbox'){a=L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token='+Z,{maxZoom:18,attribution:'Map data &copy; <a href="https://openstreetmap.org">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://mapbox.com">Mapbox</a>'+r,id:M})};
if(o==='mapnikde'){a=L.tileLayer('https://{s}.tile.openstreetmap.de/tiles/osmde/{z}/{x}/{y}.png',{maxZoom:18,attribution:'&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'+r})};
if(o==='stamen'){a=L.tileLayer('https://stamen-tiles-{s}.a.ssl.fastly.net/'+y+'/{z}/{x}/{y}.png',{subdomains:'abcd',minZoom:1,maxZoom:16,attribution:'Map data &copy; <a href="https://openstreetmap.org">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/3.0/">CC-BY 3.0</a>, Imagery &copy; <a href="http://stamen.com">Stamen Design</a>'+r,id:''})};
if(o==='opentopomap'){a=L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png',{maxZoom:16,attribution:'<a href="https://creativecommons.org/licenses/by-sa/3.0/">CC-BY 3.0</a>, Imagery &copy; <a href="http://viewfinderpanoramas.org">SRTM</a>'+r,id:''})};
if(o==='openmapsurfer'){a=L.tileLayer('http://korona.geog.uni-heidelberg.de/tiles/roads/x={x}&y={y}&z={z}',{maxZoom:20,attribution:'<a href="https://creativecommons.org/licenses/by-sa/3.0/">CC-BY 3.0</a>, Imagery &copy; <a href="http://giscience.uni-hd.de">GIScience Research Group</a>'+r,id:''})};
if(o==='humanitarian'){a=L.tileLayer('https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png',{maxZoom:20,attribution:'<a href="https://creativecommons.org/licenses/by-sa/3.0/">CC-BY 3.0</a>, Imagery &copy; <a href="https://hotosm.org">Humanitarian OpenStreetMap Team</a>'+r,id:''})};
if(o==='custom'){};
if(o==='google'){a=L.gridLayer.googleMutant({type:A,attribution:r})};
if(o==='thunderforest'){a=L.tileLayer('https://{s}.tile.thunderforest.com/'+x+'/{z}/{x}/{y}.png?apikey={apikey}',{maxZoom:22,apikey:f,attribution:'&copy; <a href="http://www.thunderforest.com/">Thunderforest</a>, &copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'})};
a.addTo(window['mysearchmap'+e]);
if((C)!=='0'){let aggpxScale=L.control.scale();
if(S!=='1'){aggpxScale.options.metric=!1};
if(k!=='1'){aggpxScale.options.imperial=!1};
aggpxScale.addTo(window['mysearchmap'+e])};
var m=L.markerClusterGroup();
for(var w in u){if(!u.hasOwnProperty(w))continue;
var p=u[w];
let tempMarkercf=null;
if(p.lat==null){p.lat=0};
if(p.lon==null){p.lon=0};
tempMarkercf=L.marker([p.lat,p.lon]);
let url='index.php?options=com_content&view=article&id='+p.id;
let title=p.title;
let popuptext='<a href=\' '+url+' \'> '+title+' </a>';
tempMarkercf.bindPopup(popuptext);
tempMarkercf.addTo(m)};
window['mysearchmap'+e].fitBounds(m.getBounds());
m.addTo(window['mysearchmap'+e])})},!1);