document.addEventListener("DOMContentLoaded",()=>{var e=document.getElementById("app"),t=require("justified-layout"),o=document.body.clientWidth,i=parseInt(.1*o),s=t(photos,{containerWidth:o,containerPadding:{top:80,right:i,left:i,bottom:80},boxSpacing:20,showWidows:!0}),a=document.createElement("div");a.className="photos",a.style.position="relative",s.boxes.map(function(e,t){var o=document.createElement("div");o.className="photo progressive",o.style.width=e.width+"px",o.style.height=e.height+"px",o.style.top=e.top+"px",o.style.left=e.left+"px",o.style.position="absolute",a.appendChild(o);var i=document.createElement("a");i.setAttribute("href","/photo/"+photos[t].hash),i.setAttribute("target","_blank"),o.appendChild(i);var s=document.createElement("img");s.setAttribute("src",photos[t].small),s.setAttribute("data-progressive",photos[t].large),s.className="progressive__img progressive--not-loaded",i.appendChild(s)}),e.appendChild(a),a.style.height=s.containerHeight+"px",progressively.init()});