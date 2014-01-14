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

    <title><?=$page_title?></title>

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
            <div class="page-header">
                <h1><?=$page_title?></h1>
            </div>

<?php if(!empty($access_code)){ ?>
            <div id="login" class="row">
                <div class="col-md-8">
                    <form id="login_form" role="form">
                        <div class="form-group">
                            <label for="access_code">Enter the correct code to upload files</label>
                            <input type="password" class="form-control" id="access_code" placeholder="Access Code">
                        </div>
                        <button class="btn btn-primary" type="submit">Submit</button>
                        <br><br>
                        <div id="wrong_password" class="alert alert-danger collapse">
                          <strong>Wrong access code!</strong> Try again...
                        </div>
                    </form>
                </div>
            </div>
<?php } ?>
            <div id="upload_wrapper"<?php echo (empty($access_code) ? "" : " class=\"collapse\"");  ?>>

                <div class="row">
                    <div class="col-md-12">
                        <span class="btn btn-success fileinput-button">
                            <i class="glyphicon glyphicon-plus"></i>
                            <span>Select files...</span>
                            <!-- The file input field used as target for the file upload widget -->
                            <input id="fileupload" type="file" name="files[]" multiple>
                        </span>
                        <p class="help-block">...or simply drag and drop files onto this page.</p>
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
                <div id="uploaded" class="panel panel-default collapse">
                  <div class="panel-heading">Uploaded files</div>
                  <div class="panel-body">
                    <ul id="file_list"></ul>
                  </div>
                </div>
                <!-- The container for the uploaded files -->

            </div><!-- /.upload_wrapper -->
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
    <!-- Bootstrap -->
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>
    <!-- PutSomethingIn -->
    <script src="js/putsomethingin.fileupload.js"></script>
<?php if(!empty($access_code)){ ?>
    <script src="js/putsomethingin.js"></script>
<?php } ?>

  </body>
</html>