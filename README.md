SimpleDropboxUploader
==============

Simple dbinbox.com clone written in PHP.


![SimpleDropboxUploader screenshot](https://raw.github.com/fniephaus/SimpleDropboxUploader/master/screenshot.png)


## Configuration

**config/dropbox.json**
```json
{
  "key": "{YOUR_APP_KEY}",
  "secret": "{YOUR_APP_SECRET}",
  "access_token": "{YOUR_ACCESS_TOKEN}"
}
```

**config/config.php**
```php
<?

// Page title is used for <title> tag and <h1> headline
$page_title = "Upload to my Dropbox";

// Optional: enter an access code to protect Dropbox uploads
$access_code = "yourcode";
```

## This app uses

* [Dropbox Core API PHP SDK](https://www.dropbox.com/developers/core/sdks/php)
* [Bootstrap 3.0.3](http://getbootstrap.com/)
* [jQuery 1.10.2](http://jquery.com/)
* [jQuery UI 1.10.3](http://jqueryui.com/)
* [jQuery-File-Upload](https://github.com/blueimp/jQuery-File-Upload/)

## Author

Fabio Niephaus