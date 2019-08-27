define(function(){function d(){var t=document.querySelector("body");this.append=function(e){t.appendChild(e)},this.remove=function(e){e&&t.removeChild(e)}}function h(){function e(){i=document.createElement("DIV"),arguments[0]&&i.setAttribute("class",arguments[0]),arguments[1]&&(i.innerHTML=arguments[1]),arguments[2]&&i.addEventListener("click",arguments[2],!1),arguments[3]&&i.setAttribute("title",arguments[3]),n.append(i)}var t=arguments[0],n=arguments[1],o=arguments[2],i=null,r='<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 96 96" enable-background="new 0 0 96 96" xml:space="preserve"><polygon points="96,14 82,0 48,34 14,0 0,14 34,48 0,82 14,96 48,62 82,96 96,82 62,48 "/></svg>',a='<svg xmlns="http://www.w3.org/2000/svg" width="170.541" height="75.74" viewBox="0 0 170.541 75.74"><g transform="translate(28.737 75.74) rotate(180)"><path d="M158.97,35.026h0a3.226,3.226,0,0,0-.987-2.27L120.089-1.791a3.384,3.384,0,0,0-3.464-.486A3,3,0,0,0,114.754.582V22.69H-8.4a3.124,3.124,0,0,0-3.169,3.154V44.8A3.124,3.124,0,0,0-8.4,47.952H114.754V70.06a3.162,3.162,0,0,0,5.335,2.27L157.982,37.4a3.471,3.471,0,0,0,.987-2.373" transform="translate(-130.232 2.549)" /></g></svg>',s='<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 170.541 75.74"><path d="M158.97,35.026h0a3.226,3.226,0,0,0-.987-2.27L120.089-1.791a3.384,3.384,0,0,0-3.464-.486A3,3,0,0,0,114.754.582V22.69H-8.4a3.124,3.124,0,0,0-3.169,3.154V44.8A3.124,3.124,0,0,0-8.4,47.952H114.754V70.06a3.162,3.162,0,0,0,5.335,2.27L157.982,37.4a3.471,3.471,0,0,0,.987-2.373" transform="translate(11.572 2.549)" /></svg>';this.init=function(){if(i)return i;switch(t){case"background":e("ce-largegallery__lightbox--background","",function(){o.close()});break;case"closeButton":e("ce-largegallery__lightbox--close",r,function(){o.close()},"Lightbox schließen (ESC-Taste)");break;case"previousButton":e("ce-largegallery__lightbox--previous",a,function(){o.showPrevious()},"Vorheriges Bild (Pfeil-Taste links)");break;case"nextButton":e("ce-largegallery__lightbox--next",s,function(){o.showNext()},"Nächstes Bild (Pfeil-Taste rechts)");break;case"container":e("ce-largegallery__lightbox--container")}return i},this.remove=function(){n.remove(i),i=null}}function c(){var i=this,r=0,a=[],s=!1,e=new d,t=new h("background",e,this),n=new h("closeButton",e,this),o=new h("container",e,this),l=new h("previousButton",e,this),c=new h("nextButton",e,this);this.addImage=function(e){return a.push(e),a.length-1},this.show=function(){a[r]?(o.init().style.backgroundImage="url("+a[r]+")",u()):console.warn("Could not find image "+r+" in images!")},this.showNext=function(){a[r+1]&&(r++,this.show())},this.showPrevious=function(){a[r-1]&&(r--,this.show())};var u=function(){a[r-1]?l.init():l.remove(),a[r+1]?c.init():c.remove()};this.close=function(){t.remove(),n.remove(),o.remove(),l.remove(),c.remove(),s=!1},this.open=function(e){a[e]&&(r=e,t.init(),n.init(),this.show(),s=!0)},document.addEventListener("keydown",function(e){if(!s)return!1;var t=!1,n=!1,o=!1;o="key"in(e=e||window.event)?(t="Escape"===e.key||"Esc"===e.key,n="ArrowLeft"===e.key,"ArrowRight"===e.key):(t=27===e.keyCode,n=37===e.keyCode,39===e.keyCode),t?i.close():n?a[r-1]&&(r--,i.show()):o&&a[r+1]&&(r++,i.show())},!1)}return function(e){function n(){for(var t=new c,e=i.querySelectorAll("[data-image-url]"),n=0;n<e.length;n++){var o=t.addImage(e[n].getAttribute("data-image-url"));e[n].setAttribute("data-image-num",o),e[n].addEventListener("click",function(e){e.preventDefault(),t.open(parseInt(this.getAttribute("data-image-num")))},!1)}}var i=e.container,o=e.entryPoint,r=e.offset,a=e.key,s=e.xhrButton,l=null;n();s.addEventListener("click",function(e){e.preventDefault(),i.style.cursor="wait",s.parentNode.style.visibility="hidden";var t={type:19845,"tx_celargegallery_pi1[controller]":"Gallery","tx_celargegallery_pi1[action]":"xhr","tx_celargegallery_pi1[offset]":r,"tx_celargegallery_pi1[key]":a};(l=new XMLHttpRequest).overrideMimeType("application/json"),l.addEventListener("load",function(e){200<=l.status&&l.status<300?(function(e){e&&e.content?(i.innerHTML+=e.content,n(),e.total>e.end?(r=e.offset,s.parentNode.style.visibility="visible"):s.parentNode.parentNode.removeChild(s.parentNode)):s.parentNode.parentNode.removeChild(s.parentNode)}(JSON.parse(l.responseText)),i.style.cursor="auto"):console.warn(l.statusText,l.responseText)}),l.open("GET",o+function(t){return(-1<o.indexOf("?")?"&":"?")+Object.keys(t).map(function(e){return e+"="+encodeURIComponent(t[e])}).join("&")}(t)),l.send()},!1)}});