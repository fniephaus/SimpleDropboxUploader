<?php
require_once "config/config.php";
require_once "dropbox-sdk/Dropbox/autoload.php";
use \Dropbox as dbx;

header('Content-type: application/json');

if ((empty($password) || (isset($_POST['password']) && $_POST['password']==$password)) && isset($_FILES['files'])){

    try {
        list($accessToken, $host) = dbx\AuthInfo::loadFromJsonFile("./config/dropbox.json");
    } catch (dbx\AuthInfoLoadException $ex) {
        //print_r($ex);
        print '{"files":[]}';
        die;
    }

    $locale = null;
    $client = new dbx\Client($accessToken, "upload-file", $locale, $host);

    if (is_uploaded_file($_FILES['files']['tmp_name'][0])) {
       $sourcePath = $_FILES['files']['tmp_name'][0];
       $dropboxPath = "/". $_FILES['files']['name'][0];
    } else {
        //print "Upload error";
        print '{"files":[]}';
        die;
    }

    $pathError = dbx\Path::findErrorNonRoot($dropboxPath);
    if ($pathError !== null) {
        //print "Invalid <dropbox-path>: $pathError\n";
        print '{"files":[]}';
        die;
    }

    $size = null;
    if (\stream_is_local($sourcePath)) {
        $size = \filesize($sourcePath);
    }

    $fp = fopen($sourcePath, "rb");
    $metadata = $client->uploadFile($dropboxPath, dbx\WriteMode::add(), $fp, $size);
    fclose($fp);

    print '{"files":[{"name":"'.$dropboxPath.'","size":'.$size.'}]}';
    die;
}

print '{"files":[]}';
die;
