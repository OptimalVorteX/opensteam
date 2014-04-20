<?php
if (strstr($_SERVER['REQUEST_URI'], basename(__FILE__) ) ) {header('HTTP/1.1 404 Not Found'); die; }

  $time = microtime();
  $time = explode(' ', $time);
  $time = $time[1] + $time[0];
  $start = $time;
  
   include('config.php');
   require_once('inc/common.php'); 
   require_once('lang/'.OSS_LANGUAGE.'.php');

   require_once('inc/class.db.PDO.php'); 
   require_once('inc/db_connect.php');
   require_once("inc/setup_user.php");
   require_once("inc/db_functions.php");
   OPENSTEAM_INIT();

   if (file_exists('plugins/index.php')) require_once('plugins/index.php');
   
   OPENSTEAM_START();
   
   if ( file_exists('themes/'.OSS_THEMES_DIR.'/functions.php') )
   include('themes/'.OSS_THEMES_DIR.'/functions.php');

   include("inc/route.php");
?>