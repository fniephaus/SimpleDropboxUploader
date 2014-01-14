<?php
require_once "config/config.php";
header('Content-type: application/json');

if (!empty($_POST['access_code'])){
    if(isset($_POST['access_code']) && $_POST['access_code']==$access_code){
        print '{"access_code":"valid"}';
        die;
    }else{
        print '{"access_code":"invalid"}';
        die;
    }
}