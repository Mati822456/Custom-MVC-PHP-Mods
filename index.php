<?php
    namespace App;

    session_start();

    // Debug
    ini_set( 'error_reporting', E_ALL );
    ini_set( 'display_errors', true );

    require './vendor/autoload.php';

    require_once './config/config.php';

    require 'App\web.php';
    
?>