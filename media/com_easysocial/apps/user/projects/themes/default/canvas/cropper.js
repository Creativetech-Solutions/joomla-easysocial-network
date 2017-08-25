var cropper = null;
var hasThumb = false;
var ThumbImage = document.querySelector('#thumbnail_img');

jQuery.initCropper = function () {
        hasThumb = true;
        if (!jQuery("#ThumbContainer").hasClass("has-thumb")) {
            jQuery("#ThumbContainer").addClass("has-thumb");
        }
        var buttonReset = document.querySelector('#buttonReset');
        var zoomIn = document.querySelector('#zoomIn');
        var zoomOut = document.querySelector('#zoomOut');
        cropper = new Cropper(ThumbImage, {
            preview: document.querySelector('.preview'),
            aspectRatio: 4 / 3,
            movable: false,
            //                
            //                zoomable: false,
            rotatable: false,
            scalable: false,
            autoCropArea: jQuery('input[name="prid"]').val() !== "" ? true : false

        });
        buttonReset.addEventListener('click', function (e)
        {

            cropper.reset();
        });
        zoomIn.addEventListener('click', function (e)
        {

            cropper.zoom(0.2);
        });
        zoomOut.addEventListener('click', function (e)
        {

            cropper.zoom(-0.2);
        });
    }
  // ...
jQuery(document).ready(function(){

    var submitformButton = document.querySelector('#submit-form');
    var ThumbImage = document.querySelector('#thumbnail_img');
    var imgurl = new URL(ThumbImage.src);
    if (imgurl.pathname !== '/') {
        jQuery.initCropper();
    }

    submitformButton.addEventListener('click', function (e)
    {
        if (hasThumb) {
            addCroppedImage();
        }
        document.getElementById("project-form").submit();
    });
    function addCroppedImage() {

        var cropcanvas = cropper.getCroppedCanvas();
        var ThumbImageData = cropcanvas.toDataURL('ThumbImage/png');
        document.getElementsByName("thumbnailImage")[0].setAttribute("value", ThumbImageData);
    }
});