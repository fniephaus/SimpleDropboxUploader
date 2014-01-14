$(function () {
    'use strict';

    $('#fileupload').fileupload({
        url: 'upload_file.php',
        dataType: 'json',
        done: function (e, data) {
            if(data.result.files.length>0){
                $("#uploaded").show();
                $.each(data.result.files, function (index, file) {
                    $('<li/>').text(file.name).appendTo('#file_list');
                });
            }else{
                $("#wrong_password").show();
            }
        },
        fail: function (e, data) {
            console.log(data);
        },
        progressall: function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#progress .progress-bar').css(
                'width',
                progress + '%'
            );
        }
    }).prop('disabled', !$.support.fileInput)
        .parent().addClass($.support.fileInput ? undefined : 'disabled');
});

    