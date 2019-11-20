;
document.addEventListener('DOMContentLoaded',function(){var e=document.querySelectorAll('.agosmsaddressmarkersurroundingdiv');[].forEach.call(e,function(t){var n=t.getElementsByTagName('input'),o=n[0],a=n[1],i='';
if(n[5]){i=n[5]}
else{i=n[2]};
var r='';
if(n[6]){r=n[6]}
else{r=n[3]};
var p=t.getElementsByTagName('select'),u=p[0],g=p[1],f=p[2],d=u.parentNode.getElementsByTagName('span')[0],m=g.parentNode.getElementsByTagName('span')[0],c=f.parentNode.getElementsByTagName('span')[0];
if(r.value.split(',').length!==6){r.value='0,0,,,,,'};
var e=r.value.split(',');
o.value=e[0];
a.value=e[1];
if(e[2]!==''){u.value=e[2];
while(d.firstChild){d.removeChild(d.firstChild)};
d.appendChild(document.createTextNode(e[2]))};
if(e[3]!==''){g.value=e[3];
while(m.firstChild){m.removeChild(m.firstChild)};
m.appendChild(document.createTextNode(e[3]))};
if(e[4]!==''){f.value=e[4];
while(c.firstChild){c.removeChild(c.firstChild)};
c.appendChild(document.createTextNode(e[4]))};
if(e[5]!==''){i.value=e[5]};
o.onchange=function(){s()};
a.onchange=function(){s()};
u.onchange=function(){s()};
g.onchange=function(){s()};
f.onchange=function(){s()};
i.onchange=function(){s()};
function s(){r.value=o.value+','+a.value+','+u.value+','+g.value+','+f.value+','+i.value};
var l=t.getElementsByTagName('button')[0],R=l.getAttribute('data-fieldsnamearray').split(','),v=l.getAttribute('data-geocoder'),E=l.getAttribute('data-googlekey'),S=l.getAttribute('data-mapboxkey');
l.onclick=function(){var e=[];[].forEach.call(R,function(t){var a=document.getElementById(t);
e.push(a.value)});
e=e.join();
if(v==='mapbox'){var t=function(t){if(t.features&&t.features.length===1){var n=t.features[0].center;
o.value=n[1];
a.value=n[0];
a.onchange();
Joomla.renderMessages({'notice':[(Joomla.JText._('PLG_AGOSMSADDRESSMARKER_ADDRESSE_NOTICE')+e)+' (Mapbox)']})}
else if(t.features&&t.features.length>0){}
else{Joomla.renderMessages({'error':[Joomla.JText._('PLG_AGOSMSADDRESSMARKER_ADDRESSE_ERROR')+e+' (Mapbox)']})}},n={limit:1,access_token:S};
getJSON('https://api.mapbox.com/geocoding/v5/mapbox.places/'+encodeURIComponent(e)+'.json',n,t)}
else if(v==='google'){var t=function(t){if(t.status==='OK'){var n=t.results[0].geometry.location;
o.value=n.lat;
a.value=n.lng;
a.onchange();
Joomla.renderMessages({'notice':[(Joomla.JText._('PLG_AGOSMSADDRESSMARKER_ADDRESSE_NOTICE')+e+' (Google)')]})}
else{var r=(typeof t.error_message=='undefined')?'':t.error_message;
Joomla.renderMessages({'error':[Joomla.JText._('PLG_AGOSMSADDRESSMARKER_ADDRESSE_ERROR')+e+' (Google: '+t.status+' '+r+')']})}},n={address:e,limit:1,key:E};
getJSON('https://maps.googleapis.com/maps/api/geocode/json',n,t)}
else{var t=function(t,n){if(!n&&t.length===1){o.value=t[0].lat;
a.value=t[0].lon;
a.onchange();
Joomla.renderMessages({'notice':[(Joomla.JText._('PLG_AGOSMSADDRESSMARKER_ADDRESSE_NOTICE')+e+' (Nominatim)')]})}
else if(t.length>0){}
else{Joomla.renderMessages({'error':[Joomla.JText._('PLG_AGOSMSADDRESSMARKER_ADDRESSE_ERROR')+e+' (Nominatim)']})}},n={q:e,limit:1,format:'json',addressdetails:1};
getJSON('https://nominatim.openstreetmap.org/',n,t)}}})},!1);
function getJSON(t,a,n){var e=new XMLHttpRequest();
e.onreadystatechange=function(){if(e.readyState!==4){return};
if(e.status!==200&&e.status!==304){n('');
return};
n(e.response)};
e.open('GET',t+getParamString(a),!0);
e.responseType='json';
e.setRequestHeader('Accept','application/json');
e.send(null)};
function getParamString(e,t,s){var r=[];
for(var o in e){var l=encodeURIComponent(s?o.toUpperCase():o),a=e[o];
if(!L.Util.isArray(a)){r.push(l+'='+encodeURIComponent(a))}
else{for(var n=0;n<a.length;n++){r.push(l+'='+encodeURIComponent(a[n]))}}};
return(!t||t.indexOf('?')===-1?'?':'&')+r.join('&')};