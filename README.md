PutSomethingIn
==============

Simple dbinbox clone written in PHP.


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

// Optional: enter a password to protect dropbox uploads
$password = "yourpassword";
```

## This app uses

* [Dropbox Core API PHP SDK](https://www.dropbox.com/developers/core/sdks/php)
* [Bootstrap 3.0.3](http://getbootstrap.com/)
* [jQuery 1.10.2](http://jquery.com/)
* [jQuery UI 1.10.3](http://jqueryui.com/)
* [jQuery-File-Upload](https://github.com/blueimp/jQuery-File-Upload/)

## Author

Fabio Niephaus