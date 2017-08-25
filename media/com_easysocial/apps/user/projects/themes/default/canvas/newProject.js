'use strict';
;
//EasySocial.require().done(function () {
//    
//    
//    EasySocial.ajax('apps/user/projects/controllers/projects/store',
//            {
//                "title": "self"
//            })
//            .done(function (item)
//            {
//                console.log("done");
//            })
//            .fail(function (response)
//            {
//                console.log(response);
//            });
//});


(function (document, window, index)
{
    // feature detection for drag&drop upload
    var isAdvancedUpload = function ()
    {
        var div = document.createElement('div');
        return (('draggable' in div) || ('ondragstart' in div && 'ondrop' in div)) && 'FormData' in window && 'FileReader' in window;
    }();
    // applying the effect for every form
    var form = document.querySelector('#project-form');
        var input = form.querySelector('input[name="project_file"]'),

            step1 = form.querySelector('.step1'),
            step2 = form.querySelector('.step2'),
            stepButton = form.querySelector('#stepButton'),
            label2 = form.querySelector('label#filename'),
            errorMsg = form.querySelector('.box__error span'),
            restart = form.querySelectorAll('.box__restart'),
            droppedFiles = false,
            showFiles = function (project_file)
            {
                step1.style.display = 'none';
                step2.style.display = 'block';
                label2.textContent = project_file.name;
                stepButton.style.display = 'block';
            },
//                triggerFormSubmit = function ()
//                {
//                    var event = document.createEvent('HTMLEvents');
//                    event.initEvent('submit', true, false);
//                    form.dispatchEvent(event);
//                },
            checkfiletype = function (project_file) {
                var project_filename = project_file.name.split('.').pop().toLowerCase();
                if (project_filename == "mp3") {
                    return true;
                }
                return false;
            }

    ;

    // letting the server side to know we are going to make an Ajax request
    var ajaxFlag = document.createElement('input');
    ajaxFlag.setAttribute('type', 'hidden');
    ajaxFlag.setAttribute('name', 'ajax');
    ajaxFlag.setAttribute('value', 1);
    form.appendChild(ajaxFlag);

    // automatically submit the form on file select
    input.addEventListener('change', function (e)
    {
        if (!checkfiletype(e.target.files[0])) {
            alert('Select correct file type');
            return false;
        }
        showFiles(e.target.files[0]);


        //triggerFormSubmit();


    });
    stepButton.addEventListener('click', function (e)
    {

        jQuery(".step1,.step2").toggle();

    });

    // drag&drop files if the feature is available
    if (isAdvancedUpload)
    {
        form.classList.add('has-advanced-upload'); // letting the CSS part to know drag&drop is supported by the browser

        ['drag', 'dragstart', 'dragend', 'dragover', 'dragenter', 'dragleave', 'drop'].forEach(function (event)
        {
            form.addEventListener(event, function (e)
            {
                // preventing the unwanted behaviours
                e.preventDefault();
                e.stopPropagation();
            });
        });
        ['dragover', 'dragenter'].forEach(function (event)
        {
            form.addEventListener(event, function ()
            {
                form.classList.add('is-dragover');
            });
        });
        ['dragleave', 'dragend', 'drop'].forEach(function (event)
        {
            form.addEventListener(event, function ()
            {
                form.classList.remove('is-dragover');
            });
        });
        form.addEventListener('drop', function (e)
        {
            if (!checkfiletype(e.dataTransfer.files[0])) {
                alert('Select correct file type');
                return false;
            }
            droppedFiles = e.dataTransfer.files[0]; // the single file that was dropped
            showFiles(droppedFiles);


            //triggerFormSubmit();

        });
    }


    // if the form was submitted
    form.addEventListener('submit', function (e)
    {
        // preventing the duplicate submissions if the current one is in progress
        if (form.classList.contains('is-uploading'))
            return false;

        form.classList.add('is-uploading');
        form.classList.remove('is-error');

        if (isAdvancedUpload) // ajax file upload for modern browsers
        {
            e.preventDefault();

            // gathering the form data
            var ajaxData = new FormData(form);
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
                form.classList.remove('is-uploading');
                if (ajax.status >= 200 && ajax.status < 400)
                {
                    var data = JSON.parse(ajax.responseText);
                    form.classList.add(data.success == true ? 'is-success' : 'is-error');
                    if (!data.success) {
                        jQuery('.box .box__error').show();
                        errorMsg.textContent = data.error;


                    } else {
                        jQuery('.box .box__error').hide();
                        errorMsg.textContent = '';
                        if (data.return) {
                            window.location.href = data.return;
                            return;
                        }
                    }
                } else
                    alert('Error. Please, contact the webmaster!');
            };

            ajax.onerror = function ()
            {
                form.classList.remove('is-uploading');
                alert('Error. Please, try again!');
            };

            ajax.send(ajaxData);
        } else // fallback Ajax solution upload for older browsers
        {
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
    });


    // restart the form if has a state of error/success
    Array.prototype.forEach.call(restart, function (entry)
    {
        entry.addEventListener('click', function (e)
        {
            e.preventDefault();
            form.classList.remove('is-error', 'is-success');
            input.click();
        });
    });

    // Firefox focus bug fix for file input
    input.addEventListener('focus', function () {
        input.classList.add('has-focus');
    });
    input.addEventListener('blur', function () {
        input.classList.remove('has-focus');
    });

}(document, window, 0));

jQuery(function ($) { // DOM ready
    $("#tags input").on({
        focusout: function () {
            var txt = this.value.replace(/[^a-z0-9\+\-\.\#]/ig, ''); // allowed characters
            if (txt)
                $("<span/>", {text: txt.toLowerCase(), insertBefore: this});
            //this.value = "";
            document.getElementById('project_tags').value = '';

            document.getElementById('projecttags').value = document.getElementById('projecttags').value + txt + ",";
            document.getElementById('projecttags').value = document.getElementById('projecttags').value.replace(',,', ',');
        },
        keyup: function (ev) {
            // if: comma|enter (delimit more keyCodes with | pipe)
            if (/(188|13)/.test(ev.which))
                $(this).focusout();
        }
    });
    $('#tags').on('click', 'span', function () {
        if (confirm("Remove " + $(this).text() + "?")) {
            var g = $(this).text();
            var k = document.getElementById('projecttags').value = document.getElementById('projecttags').value.replace(g + ',', '');
            if (k == ',')
                document.getElementById('projecttags').value = '';
            $(this).remove();
        }
    });

});

