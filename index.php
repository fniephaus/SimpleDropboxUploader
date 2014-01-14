<?php
require_once "config/config.php";
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Upload to my Dropbox</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css">
    <!-- CSS to style the file input field as button and adjust the Bootstrap progress bars -->
    <link rel="stylesheet" href="css/jquery.fileupload.css">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="container">

      <div class="row">
        <div class="col-md-offset-3 col-md-6">
            <h1>Upload to my Dropbox</h1>

            <br>
            <br>

            <div class="row">
            <?php if(!empty($password)){?>
                <div class="col-md-6">
                    <div class="form-group">
                        <input type="password" class="form-control" id="password" placeholder="Password">
                    </div>
                </div>
            <?php } ?>
                <div class="col-md-6">
                    <span class="btn btn-success fileinput-button">
                    <i class="glyphicon glyphicon-plus"></i>
                    <span>Select files...</span>
                    <!-- The file input field used as target for the file upload widget -->
                    <input id="fileupload" type="file" name="files[]" multiple>
                </span>
                </div>
            </div>


            <br>
            <br>
            <!-- The global progress bar -->
            <div id="progress" class="progress">
                <div class="progress-bar progress-bar-success"></div>
            </div>

            <br>
            <br>
            <div id="uploaded" class="panel panel-success collapse">
              <div class="panel-heading">Uploaded files</div>
              <div class="panel-body">
                <ul id="file_list"></ul>
              </div>
            </div>
            <!-- The container for the uploaded files -->

            <br>
            <br>
            <div id="wrong_password" class="alert alert-danger alert-dismissable collapse">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              <strong>Wrong password!</strong>
            </div>

        </div>
      </div>

    </div><!-- /.container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
    <!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
    <script src="js/jquery.iframe-transport.js"></script>
    <!-- The basic File Upload plugin -->
    <script src="js/jquery.fileupload.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>
    <script>
    $(function () {
        'use strict';
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

<?php if(!empty($password)){?>
        
        $('#fileupload').bind('fileuploadsubmit', function (e, data) {
            // The example input, doesn't have to be part of the upload form:
            var input = $('#password');
            data.formData = {password: input.val()};
            if (!data.formData.password) {
              input.focus();
              return false;
            }
        });

        $('#fileupload').click(function(){
            $("#wrong_password").hide();
            if($('#password').val() == ""){
                $('#password').focus();
                return false;
            }
        });
    });
<?php }else{ ?>
        $('#fileupload').click(function(){
            $("#wrong_password").hide();
        });
<?php }?>
    </script>
  </body>
</html>