(function(){'use strict';
window.jSelectWeblink=function(n,r,l,d,o,i){var a='',t,e;
if(!Joomla.getOptions('xtd-weblinks')){window.parent.jModalClose();
return!1};
t=Joomla.getOptions('xtd-weblinks').editor;
if(i!==''){a=' hreflang="'+i+'"'};
e='<a'+a+' href="'+o+'">'+r+'</a>';
if(window.Joomla&&window.Joomla.editors&&Joomla.editors.instances&&Joomla.editors.instances.hasOwnProperty(t)){Joomla.editors.instances[t].replaceSelection(e)}
else{window.parent.jInsertEditorText(e,t)};
window.parent.jModalClose()};
document.addEventListener('DOMContentLoaded',function(){var e=document.querySelectorAll('.select-link');
for(var t=0,a=e.length;a>t;t++){e[t].addEventListener('click',function(t){t.preventDefault();
var e=t.target.getAttribute('data-function');
if(e==='jSelectWeblink'){window[e](t.target.getAttribute('data-id'),t.target.getAttribute('data-title'),t.target.getAttribute('data-cat-id'),null,t.target.getAttribute('data-uri'),t.target.getAttribute('data-language',null))}
else{window.parent[e](t.target.getAttribute('data-id'),t.target.getAttribute('data-title'),t.target.getAttribute('data-cat-id'),null,t.target.getAttribute('data-uri'),t.target.getAttribute('data-language',null))}})}})})();