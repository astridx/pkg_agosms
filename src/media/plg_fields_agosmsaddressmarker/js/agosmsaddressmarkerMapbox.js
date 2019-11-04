;
document.addEventListener('click',function(e){if(e.target.classList.contains('agosmsaddressmarkerbutton')){var n=e.target,t=[],i=n.getAttribute('data-fieldsnamearray').split(','),u=n.getAttribute('data-mapboxkey'),c=n.parentNode,a=c.getElementsByTagName('input'),p=a[0],o=a[1];[].forEach.call(i,function(e){var n=document.getElementById(e);
t.push(n.value)});
t=t.join();
var r=function(e){if(e.features&&e.features.length===1){var n=e.features[0].center;
p.value=n[1];
o.value=n[0];
o.onchange();
Joomla.renderMessages({'notice':[(Joomla.JText._('PLG_AGOSMSADDRESSMARKER_ADDRESSE_NOTICE')+t)+' (Mapbox)']})}
else if(e.features&&e.features.length>0){}
else{Joomla.renderMessages({'error':[Joomla.JText._('PLG_AGOSMSADDRESSMARKER_ADDRESSE_ERROR')+t+' (Mapbox)']})}},s={limit:1,access_token:u};
getJSON('https://api.mapbox.com/geocoding/v5/mapbox.places/'+encodeURIComponent(t)+'.json',s,r)}});
function getJSON(t,a,n){var e=new XMLHttpRequest();
e.onreadystatechange=function(){if(e.readyState!==4){return};
if(e.status!==200&&e.status!==304){n('');
return};
n(e.response)};
e.open('GET',t+getParamString(a),!0);
e.responseType='json';
e.setRequestHeader('Accept','application/json');
e.send(null)};
function getParamString(e,n,s){var r=[];
for(var o in e){var i=encodeURIComponent(s?o.toUpperCase():o),t=e[o];
if(!L.Util.isArray(t)){r.push(i+'='+encodeURIComponent(t))}
else{for(var a=0;a<t.length;a++){r.push(i+'='+encodeURIComponent(t[a]))}}};
return(!n||n.indexOf('?')===-1?'?':'&')+r.join('&')};