;
document.addEventListener('click',function(e){if(e.target.classList.contains('agosmsaddressmarkerbutton')){var a=e.target,t=[],i=a.getAttribute('data-fieldsnamearray').split(','),l=a.getAttribute('data-googlekey'),g=a.parentNode,n=g.getElementsByTagName('input'),u=n[0],o=n[1];[].forEach.call(i,function(e){var a=document.getElementById(e);
t.push(a.value)});
t=t.join();
var r=function(e){if(e.status==='OK'){var a=e.results[0].geometry.location;
u.value=a.lat;
o.value=a.lng;
o.onchange();
Joomla.renderMessages({'notice':[(Joomla.JText._('PLG_AGOSMSADDRESSMARKER_ADDRESSE_NOTICE')+t+' (Google)')]})}
else{var n=(typeof e.error_message=='undefined')?'':e.error_message;
Joomla.renderMessages({'error':[Joomla.JText._('PLG_AGOSMSADDRESSMARKER_ADDRESSE_ERROR')+t+' (Google: '+e.status+' '+n+')']})}},s={address:t,limit:1,key:l};
getJSON('https://maps.googleapis.com/maps/api/geocode/json',s,r)}});
function getJSON(t,n,a){var e=new XMLHttpRequest();
e.onreadystatechange=function(){if(e.readyState!==4){return};
if(e.status!==200&&e.status!==304){a('');
return};
a(e.response)};
e.open('GET',t+getParamString(n),!0);
e.responseType='json';
e.setRequestHeader('Accept','application/json');
e.send(null)};
function getParamString(e,a,s){var r=[];
for(var o in e){var i=encodeURIComponent(s?o.toUpperCase():o),t=e[o];
if(!L.Util.isArray(t)){r.push(i+'='+encodeURIComponent(t))}
else{for(var n=0;n<t.length;n++){r.push(i+'='+encodeURIComponent(t[n]))}}};
return(!a||a.indexOf('?')===-1?'?':'&')+r.join('&')};