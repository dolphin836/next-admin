document.addEventListener('DOMContentLoaded', () => {
    var appElement  = document.getElementById("app");
    var photoLayout = require('justified-layout');
    var max         = document.body.clientWidth; // 页面可视区域宽度

    var records = photoLayout(photos, {
          containerWidth: max,
        containerPadding: {
               top: 80,
             right: 80,
              left: 80,
            bottom: 80
        },
        boxSpacing: {
            horizontal: 20,
              vertical: 40
        },
        showWidows: false
    });

    var photosElement               = document.createElement("div");
    photosElement.className         = "photos";
    photosElement.style.position    = "relative";

    records.boxes.map(function (record, i) {
        var photoElement            = document.createElement("div");
        photoElement.className      = "photo progressive";
        photoElement.style.width    = record.width  + "px";
        photoElement.style.height   = record.height + "px";
        photoElement.style.top      = record.top    + "px";
        photoElement.style.left     = record.left   + "px";
        photoElement.style.position = "absolute";

        photosElement.appendChild(photoElement);

        var picElement = document.createElement("img");
        picElement.setAttribute("src", photos[i]['small']);
        picElement.setAttribute("data-progressive", photos[i]['large']);
        picElement.className    = "progressive__img progressive--not-loaded";
        photoElement.appendChild(picElement);
    });
    
    appElement.appendChild(photosElement);
    photosElement.style.height = records.containerHeight + "px";

    progressively.init();
}); 