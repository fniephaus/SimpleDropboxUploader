SimpleDropboxUploader
==============

Simple dbinbox.com clone written in PHP.


![SimpleDropboxUploader screenshot](https://raw.github.com/fniephaus/SimpleDropboxUploader/master/screenshot.png)


## Configuration

**config/config.php**
```php
<?php
// Access token for your Dropbox App
$ACCESS_TOKEN = "{Your Access Token}";

// Page title is used for <title> tag and <h1> headline
$PAGE_TITLE = "Upload to my Dropbox";

// Optional: enter an access code to protect Dropbox uploads
$ACCESS_CODE = "Your code";

// Optional: enable upload logging
$LOGGING = false;
```

Make sure you have `php5-curl` installed.

If you are having problems uploading files, please check the file upload limits
in your webserver and your PHP configurations.


## This app uses

* [Dropbox API v2](https://www.dropbox.com/developers/documentation/http/documentation#files-upload)
* [Bootstrap 3.0.3](http://getbootstrap.com/)
* [jQuery 1.10.2](http://jquery.com/)
* [jQuery UI 1.10.3](http://jqueryui.com/)
* [jQuery-File-Upload](https://github.com/blueimp/jQuery-File-Upload/)

## Author

Fabio Niephaus