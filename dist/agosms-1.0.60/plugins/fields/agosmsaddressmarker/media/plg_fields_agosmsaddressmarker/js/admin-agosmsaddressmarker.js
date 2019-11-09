document.addEventListener('DOMContentLoaded',function(){var e=document.querySelectorAll('.agosmsaddressmarkersurroundingdiv');[].forEach.call(e,function(t){var s=t.getElementsByTagName('input'),n=s[0],a=s[1],p=s[5],l=s[6],f=t.getElementsByTagName('select'),i=f[0],u=f[1],g=f[2],d=i.parentNode.getElementsByTagName('span')[0],m=u.parentNode.getElementsByTagName('span')[0],c=g.parentNode.getElementsByTagName('span')[0];
if(l.value.split(',').length!==6){l.value='0,0,,,,,'};
var e=l.value.split(',');
n.value=e[0];
a.value=e[1];
if(e[2]!==''){i.value=e[2];
while(d.firstChild){d.removeChild(d.firstChild)};
d.appendChild(document.createTextNode(e[2]))};
if(e[3]!==''){u.value=e[3];
while(m.firstChild){m.removeChild(m.firstChild)};
m.appendChild(document.createTextNode(e[3]))};
if(e[4]!==''){g.value=e[4];
while(c.firstChild){c.removeChild(c.firstChild)};
c.appendChild(document.createTextNode(e[4]))};
if(e[5]!==''){p.value=e[5]};
n.onchange=function(){o()};
a.onchange=function(){o()};
i.onchange=function(){o()};
u.onchange=function(){o()};
g.onchange=function(){o()};
p.onchange=function(){o()};
function o(){l.value=n.value+','+a.value+','+i.value+','+u.value+','+g.value+','+p.value};
var r=t.getElementsByTagName('button')[0],h=r.getAttribute('data-fieldsnamearray').split(','),v=r.getAttribute('data-geocoder'),E=r.getAttribute('data-googlekey'),S=r.getAttribute('data-mapboxkey');
r.onclick=function(){var e=[];[].forEach.call(h,function(t){var a=document.getElementById(t);
e.push(a.value)});
e=e.join();
if(v==='mapbox'){var t=function(t){if(t.features&&t.features.length===1){var o=t.features[0].center;
n.value=o[1];
a.value=o[0];
a.onchange();
Joomla.renderMessages({'notice':[(Joomla.JText._('PLG_AGOSMSADDRESSMARKER_ADDRESSE_NOTICE')+e)+' (Mapbox)']})}
else if(t.features&&t.features.length>0){}
else{Joomla.renderMessages({'error':[Joomla.JText._('PLG_AGOSMSADDRESSMARKER_ADDRESSE_ERROR')+e+' (Mapbox)']})}},o={limit:1,access_token:S};
getJSON('https://api.mapbox.com/geocoding/v5/mapbox.places/'+encodeURIComponent(e)+'.json',o,t)}
else if(v==='google'){var t=function(t){if(t.status==='OK'){var o=t.results[0].geometry.location;
n.value=o.lat;
a.value=o.lng;
a.onchange();
Joomla.renderMessages({'notice':[(Joomla.JText._('PLG_AGOSMSADDRESSMARKER_ADDRESSE_NOTICE')+e+' (Google)')]})}
else{var r=(typeof t.error_message=='undefined')?'':t.error_message;
Joomla.renderMessages({'error':[Joomla.JText._('PLG_AGOSMSADDRESSMARKER_ADDRESSE_ERROR')+e+' (Google: '+t.status+' '+r+')']})}},o={address:e,limit:1,key:E};
getJSON('https://maps.googleapis.com/maps/api/geocode/json',o,t)}
else{var t=function(t,o){if(!o&&t.length===1){n.value=t[0].lat;
a.value=t[0].lon;
a.onchange();
Joomla.renderMessages({'notice':[(Joomla.JText._('PLG_AGOSMSADDRESSMARKER_ADDRESSE_NOTICE')+e+' (Nominatim)')]})}
else if(t.length>0){}
else{Joomla.renderMessages({'error':[Joomla.JText._('PLG_AGOSMSADDRESSMARKER_ADDRESSE_ERROR')+e+' (Nominatim)']})}},o={q:e,limit:1,format:'json',addressdetails:1};
getJSON('https://nominatim.openstreetmap.org/',o,t)}}})},!1);
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