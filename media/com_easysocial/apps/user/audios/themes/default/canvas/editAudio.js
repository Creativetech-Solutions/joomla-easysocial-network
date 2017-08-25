'use strict';
;

(function (document, window, index)
{

    var ThumbContainer = document.querySelector('#ThumbContainer');
    var dropdiv = document.querySelector('#pic_area');
    if (ThumbContainer.classList.contains('has-thumb')) {
        dropdiv.removeAttribute('for');
    }
    // feature detection for drag&drop upload
    var isAdvancedUpload = function ()
    {
        var div = document.createElement('div');
        return (('draggable' in div) || ('ondragstart' in div && 'ondrop' in div)) && 'FormData' in window && 'FileReader' in window;
    }();

    // applying the effect for every form
    var form = document.querySelector('#audio-form'),
            input = form.querySelector('input[name="thumbnail"]'),
            errorMsg = form.querySelector('.thumb__error span'),
            droppedFiles = false,
// if the form was submitted
            triggerFormSubmit = function ()
            {
                // preventing the duplicate submissions if the current one is in progress
                if (dropdiv.classList.contains('is-uploading'))
                    return false;
                var ajaxData = new FormData(form);
                ajaxData.delete('audio_file');//don't need it
                ajaxData.append('thumbUpload', '1');//add for thumb


                dropdiv.classList.add('is-uploading');
                dropdiv.classList.remove('is-error');
                jQuery('.thumb__uploading').show();
                if (isAdvancedUpload) // ajax file upload for modern browsers
                {
                    // gathering the form data
                    if (droppedFiles)
                    {
                        Array.prototype.forEach.call(droppedFiles, function (file)
                        {
                            ajaxData.append(input.getAttribute('name'), file);
                        });
                    }

                    // ajax request
                    var ajax = new XMLHttpRequest();
                    ajax.open(form.getAttribute('method'), form.getAttribute('action'), true);

                    ajax.onload = function ()
                    {
                        dropdiv.classList.remove('is-uploading');

                        jQuery('.thumb__uploading').hide();
                        if (ajax.status >= 200 && ajax.status < 400)
                        {
                            var data = JSON.parse(ajax.responseText);
                            dropdiv.classList.add(data.success == true ? 'is-success' : 'is-error');
                            if (!data.success) {
                                jQuery('.thumb__error').show();
                                errorMsg.textContent = data.error;


                            } else {
                                jQuery('.thumb__error').hide();
                                jQuery('.thumb__success').show();
                                errorMsg.textContent = '';
                                if (data.return) {
                                    jQuery("#thumbnail_img").attr('src', data.return).show();
                                    dropdiv.removeAttribute('for');
                                    jQuery.initCropper();
                                }
                            }
                        } else
                            alert('Error. Please, contact the webmaster!');
                    };

                    ajax.onerror = function ()
                    {
                        dropdiv.classList.remove('is-uploading');
                        alert('Error. Please, try again!');
                    };

                    ajax.send(ajaxData);

                } else // fallback Ajax solution upload for older browsers
                {
                    //@TODO_ DIDN't FIX YET
                    var iframeName = 'uploadiframe' + new Date().getTime(),
                            iframe = document.createElement('iframe');

                    $iframe = $('<iframe name="' + iframeName + '" style="display: none;"></iframe>');

                    iframe.setAttribute('name', iframeName);
                    iframe.style.display = 'none';

                    document.body.appendChild(iframe);
                    form.setAttribute('target', iframeName);

                    iframe.addEventListener('load', function ()
                    {
                        var data = JSON.parse(iframe.contentDocument.body.innerHTML);
                        form.classList.remove('is-uploading')
                        form.classList.add(data.success == true ? 'is-success' : 'is-error')
                        form.removeAttribute('target');
                        if (!data.success) {
                            jQuery('.box .box__error').show();
                            errorMsg.textContent = data.error;
                        } else {
                            jQuery('.box .box__error').hide();
                            errorMsg.textContent = '';
                        }
                        iframe.parentNode.removeChild(iframe);
                    });
                }

            },
            checkfiletype = function (thumb_file) {
                var thumbname = thumb_file.name.split('.').pop().toLowerCase();
                if (thumbname || thumbname == "jpg" || thumbname == "jpeg") {
                    return true;
                }
                return false;
            };

    input.addEventListener('change', function (e)
    {
        if (!checkfiletype(e.target.files[0])) {
            alert('Select correct file type');
            return false;
        }
        triggerFormSubmit();


    });
    // drag&drop files if the feature is available
    if (isAdvancedUpload)
    {
        dropdiv.classList.add('has-advanced-upload'); // letting the CSS part to know drag&drop is supported by the browser

        ['drag', 'dragstart', 'dragend', 'dragover', 'dragenter', 'dragleave', 'drop'].forEach(function (event)
        {
            dropdiv.addEventListener(event, function (e)
            {
                // preventing the unwanted behaviours
                e.preventDefault();
                e.stopPropagation();
            });
        });
        ['dragover', 'dragenter'].forEach(function (event)
        {
            dropdiv.addEventListener(event, function ()
            {
                dropdiv.classList.add('is-dragover');
            });
        });
        ['dragleave', 'dragend', 'drop'].forEach(function (event)
        {
            dropdiv.addEventListener(event, function ()
            {
                dropdiv.classList.remove('is-dragover');
            });
        });
        dropdiv.addEventListener('drop', function (e)
        {
            if (!checkfiletype(e.dataTransfer.files[0])) {
                alert('Select correct file type');
                return false;
            }
            triggerFormSubmit();

        });
    }



    // Firefox focus bug fix for file input
    input.addEventListener('focus', function () {
        input.classList.add('has-focus');
    });
    input.addEventListener('blur', function () {
        input.classList.remove('has-focus');
    });

}(document, window, 0));