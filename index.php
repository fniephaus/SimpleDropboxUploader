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
    <!-- Custom CSS style fot this page -->
    <link rel="stylesheet" href="css/style.css">
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

            
<?php if ((isset($_POST['access_code']) && $_POST['access_code']==$access_code) || empty($access_code)) { ?>
            <div id="upload_wrapper">

                <div class="row">
                    <div class="col-md-12">
                        <span class="btn btn-success fileinput-button">
                            <i class="glyphicon glyphicon-cloud-upload"></i>
                            <span>Select files...</span>
                            <!-- The file input field used as target for the file upload widget -->
                            <input id="fileupload" type="file" name="files[]" multiple>
                            <input type="password" class="hide" id="access_code" name="access_code" value="<?=$_POST['access_code']?>">
                        </span>
                        <div id="dropzone" class="well text-center hidden-xs">...or drop files here</div>
                    </div>
                </div>


                <br>
                <br>
                <!-- The global progress bar -->
                <div id="progress" class="progress progress-striped active collapse">
                    <div class="progress-bar progress-bar-success"><span id="upload_progress"></span></div>

                </div>
                <div class="row">
                    <div id="upload_status" class="col-xs-6 text-left"></div>
                    <div id="upload_speed" class="col-xs-6 text-right"></div>
                </div>

                <br>
                <br>
                <div id="uploads" class="collapse">
                  <div class="panel panel-default">
                      <!-- Default panel contents -->
                      <div class="panel-heading">Upload status <span id="upload_count" class="badge pull-right"></span></div>

                      <!-- Table -->
                      <table id="file_list" class="table table-condensed">
                        <tbody></tbody>
                      </table>
                    </div>
                </div>


                <!-- The container for the uploaded files -->

            </div><!-- /.upload_wrapper -->

<?php }else{ ?>

            <div id="login" class="row">
                <div class="col-md-8">
                    <form action="/" method="post" role="form">
                        <div class="form-group">
                            <label for="access_code">Enter the correct code to upload files</label>
                            <input type="password" class="form-control" id="access_code" name="access_code" placeholder="Access Code">
                        </div>
                        <button class="btn btn-primary" type="submit">Submit</button>
                        <br><br>
                        <div id="wrong_password" class="alert alert-danger<?=(isset($_POST['access_code']) ? "" : " collapse")?>">
                          <strong>Wrong access code!</strong> Try again...
                        </div>
                    </form>
                </div>
            </div>

<?php } ?>

        </div>
      </div>

    </div><!-- /.container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
    <!-- Bootstrap -->
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>

    <!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
    <script src="js/jquery.iframe-transport.js"></script>
    <!-- The basic File Upload plugin -->
    <script src="js/jquery.fileupload.js"></script>
    <!-- The File Upload processing plugin -->
    <script src="js/ready.js"></script>

  </body>
</html>