(function (window, $) {


    function escapeTags(str) {
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;');
    }

    window.onload = function () {

        var btn = document.getElementById('uploadBtn'),
            progressBar = document.getElementById('progressBar'),
            progressOuter = document.getElementById('progressOuter'),
            msgBox = document.getElementById('output');

        var uploader = new ss.SimpleUpload({
            button: btn,
            url: 'file_upload.php',
            name: 'uploadfile',
            hoverClass: 'hover',
            focusClass: 'focus',
            responseType: 'json',
            startXHR: function () {
                progressOuter.style.display = 'block'; // make progress bar visible
                this.setProgressBar(progressBar);
            },
            onSubmit: function () {
                $('#status').addClass('text-warning').text('Running...');
                $(window).trigger('appizy','Conversion');
                msgBox.innerHTML = ''; // empty the message box
                btn.innerHTML = 'Uploading...'; // change button text to "Uploading..."
            },
            onComplete: function (filename, response) {
                btn.innerHTML = 'Choose File';
                progressOuter.style.display = 'none'; // hide progress bar when upload is completed

                if (!response) {
                    msgBox.innerHTML = 'Unable to upload file';
                    return;
                }

                if (response.success === true) {
                    msgBox.innerHTML = '<strong>' + response.filename + '</strong>' + ' successfully uploaded.';

                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', 'launcher.php', true);
                    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                    xhr.send('filename=' + response.filename + '&app_id=' + response.appId);
                    var timer;
                    var currentPos = 0;
                    timer = window.setInterval(function () {
                        var serverOutput = xhr.responseText.substring(currentPos);
                        var $result = $('#output');
                        serverOutput = serverOutput.replace(/\[4m|\[24m|\[36m|\[22;39m|��filler��|\[31m.\[0m/g, '');
                        serverOutput = serverOutput.replace(/\[32m|\[0;32m|\[31m.\[32m|\[0m.\[32m/g, '<span style="color: green">');
                        serverOutput = serverOutput.replace(/\[33m|\[1;33m|\[0;33m|\[0;35m/g, '<span style="color: darkorange">');
                        serverOutput = serverOutput.replace(/\[31m|\[0;31m/g, '<span style="color: red;">');
                        serverOutput = serverOutput.replace(/\[2;37m/g, '<span style="color: grey;">');
                        serverOutput = serverOutput.replace(/\[34m|\[0;36m/g, '<span style="color: dodgerblue;">');
                        serverOutput = serverOutput.replace(/\[0m|\[0;49m|\[39m/g, '</span>');

                        if (serverOutput.length) {
                            $result.append(serverOutput + '<br>');
                            //$(window).scrollTop($('#output').position().top);
                            $('#result').appendTo($result);
                        }
                        currentPos = xhr.responseText.length;

                        if (xhr.readyState == XMLHttpRequest.DONE) {
                            window.clearTimeout(timer);
                            $('#status').removeClass('text-warning');
                            if ($result.text().match(/Error:/)) {
                                $('#status').addClass('text-important').text('Completed with errors!');
                            }
                            else {
                                $('#status').addClass('text-success').text('Completed.');
                            }
                            // updateServerStatus();
                        }
                    }, 200);

                } else {
                    $('#status').addClass('text-danger').text('Completed with errors!');
                    if (response.msg) {
                        msgBox.innerHTML = escapeTags(response.msg);

                    } else {
                        msgBox.innerHTML = 'An error occurred and the upload failed.';
                    }
                }
            },
            onError: function () {
                $('#status').addClass('text-danger').text('Completed with errors!');
                progressOuter.style.display = 'none';
                msgBox.innerHTML = 'Unable to upload file';
            }
        });
    };

})(window, jQuery);
