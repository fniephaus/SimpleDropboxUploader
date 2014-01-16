<?php
require_once "config/config.php";
require_once "dropbox-sdk/Dropbox/autoload.php";
use \Dropbox as dbx;

header('Content-type: application/json');

if ((empty($access_code) || (isset($_POST['access_code']) && $_POST['access_code']==$access_code)) &&
    isset($_POST['id']) && is_numeric($_POST['id']) &&
    isset($_FILES['files'])){

    try {
        list($accessToken, $host) = dbx\AuthInfo::loadFromJsonFile("./config/dropbox.json");

        $locale = null;
        $client = new dbx\Client($accessToken, "upload-file", $locale, $host);

        if (is_uploaded_file($_FILES['files']['tmp_name'][0])) {
           $sourcePath = $_FILES['files']['tmp_name'][0];
           $dropboxPath = "/". $_FILES['files']['name'][0];
        } else {
            print '{"files":[{"id":'.$_POST['id'].',"name":"","size":"","error":"An upload error occured."}]}';
            die;
        }

        $pathError = dbx\Path::findErrorNonRoot($dropboxPath);
        if ($pathError !== null) {
            print '{"files":[{"id":'.$_POST['id'].',"name":"'.$dropboxPath.'","size":"","error":"Invalid <dropbox-path>: '.$pathError.'"}]}';
            die;
        }

        $size = null;
        if (\stream_is_local($sourcePath)) {
            $size = \filesize($sourcePath);
        }

        $fp = fopen($sourcePath, "rb");
        $metadata = $client->uploadFile($dropboxPath, dbx\WriteMode::add(), $fp, $size);
        fclose($fp);

        if($logging){
            $logfile = tmpfile();
            fwrite($logfile, "Upload date: " . date("F j, Y, g:i a") . "\nIP: " . $_SERVER['REMOTE_ADDR']);
            fseek($logfile, 0);
            $logsize = filesize(stream_get_meta_data($logfile)['uri']);
            $log_metadata = $client->uploadFile($dropboxPath.".log", dbx\WriteMode::add(), $logfile, $logsize);
            fclose($logfile);
        }

        print '{"files":[{"id":'.$_POST['id'].',"name":"'.$metadata['path'].'","size":'.$size.'}]}';
        die;
    } catch (dbx\AuthInfoLoadException $ex) {
        print '{"files":[{"id":'.$_POST['id'].',"name":"'.$metadata['path'].'","size":'.$size.',"error":"An authentication problem occured.}]}';
        die;
    }
}

print '{"error":"Upload failed"}';