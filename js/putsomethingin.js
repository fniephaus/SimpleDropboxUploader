$(function () {
    'use strict';

    $('#login_form').submit(function( event ){
        $.ajax({
            type: "POST",
            url: "check_code.php",
            data: {"access_code": $("#access_code").val()}
        }).done(function( data ) {
           if(data.access_code == "valid"){
                $('#login').hide();
                $('#upload_wrapper').fadeIn();
            }else{
                $('#wrong_password').fadeIn();
            }
        });
        event.preventDefault();
    });

    $('#fileupload').fileupload({
        url: 'upload_file.php',
        dataType: 'json',
        done: function (e, data) {
            $("#upload_to_dropbox").hide();
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
            $("#upload_to_dropbox").hide();
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

    
    $('#fileupload').bind('fileuploadsubmit', function (e, data) {
        // The example input, doesn't have to be part of the upload form:
        var input = $('#access_code');
        data.formData = {password: input.val()};
        if (!data.formData.password) {
          input.focus();
          return false;
        }
    });

    $('#fileupload').click(function(){
        $("#wrong_password").hide();
        if($('#access_code').val() == ""){
            $('#access_code').focus();
            return false;
        }
    });
});

    