document.addEventListener("DOMContentLoaded",()=>{var e=document.getElementById("photo"),t=require("justified-layout"),o=document.body.clientWidth,i=t(photos,{containerWidth:o,containerPadding:{top:10,right:20,left:20,bottom:60},boxSpacing:20,showWidows:is_show,targetRowHeight:280}),s=document.createElement("div");s.className="photos",s.style.position="relative",i.boxes.map(function(e,t){var o=document.createElement("div");o.className="photo progressive",o.style.width=e.width+"px",o.style.height=e.height+"px",o.style.top=e.top+"px",o.style.left=e.left+"px",o.style.position="absolute",s.appendChild(o);var i=document.createElement("a");i.setAttribute("href","/photo/"+photos[t].hash),o.appendChild(i);var a=document.createElement("img");a.setAttribute("src",photos[t].small),a.setAttribute("data-progressive",photos[t].large),a.className="progressive__img progressive--not-loaded",i.appendChild(a)}),e.appendChild(s),s.style.height=i.containerHeight+"px",progressively.init()});