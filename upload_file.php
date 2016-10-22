<?php
define('IsIncluded', true);
require_once 'config.php';

header('Content-type: application/json');

function upload_file($fileName, $sourceFile) {
    global $ACCESS_TOKEN;
    if (empty($fileName) || !file_exists($sourceFile)) {
        return false;
    }
    $headers = array('Authorization: Bearer ' . $ACCESS_TOKEN,
                     'Content-Type: application/octet-stream',
                     'Dropbox-API-Arg: {"path": "/' . $fileName . '", "mode": "add", "autorename": true}');
    $ch = curl_init('https://content.dropboxapi.com/2/files/upload');
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, file_get_contents($sourceFile));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $res = curl_exec($ch);
    curl_close($ch);
    return json_decode($res);
}

if ((empty($ACCESS_CODE) || (isset($_POST['access_code']) && $_POST['access_code']==$ACCESS_CODE)) &&
    isset($_POST['id']) && is_numeric($_POST['id']) &&
    isset($_FILES['files'])){

    $sourceFile = $_FILES['files']['tmp_name'][0];
    $fileName = $_FILES['files']['name'][0];

    $foo = array('id' => $_POST['id'], 'name' => '', 'size' => '', 'error' => '');
    $output = array('files' => array($foo));

    if (!is_uploaded_file($sourceFile)) {
        $output['files'][0]['error'] = 'PHP upload error #' . $_FILES['files']['error'][0] . ' occurred.';
        echo json_encode($output);
        die;
    }
    $res = upload_file($fileName, $sourceFile);
    if ($res === false) {
        $output['files'][0]['error'] = 'Upload failed';
        echo json_encode($output);
        die;
    }
    if($LOGGING){
        $logfile = tmpfile();
        fwrite($logfile, "Upload date: " . date("F j, Y, g:i a") . "\nIP: " . $_SERVER['REMOTE_ADDR']);
        fseek($logfile, 0);
        $logSourceFile = stream_get_meta_data($logfile)['uri'];
        upload_file($fileName . '.log', $logSourceFile);
        fclose($logfile);
    }

    $output['files'][0]['name'] = $res->path_display;
    $output['files'][0]['size'] = $res->size;
    echo json_encode($output);
    die;
}

echo json_encode(array('error' => 'Upload failed.'));
