<?php
if (!isset( $cfg["website"] ) ) {header('HTTP/1.1 404 Not Found'); die; }

  $PageTitle = $cfg["home_title"];
  $search = "";
  

  if( isset($_GET["option"])) $_GET["option"] = str_replace( array("./", ".."), array("", ""), $_GET["option"] );

  if ( isset($_GET["option"]) AND file_exists("inc/pages/".$_GET["option"] .".php" ) ) {
  
    include("inc/pages/".$_GET["option"] .".php" );
  
  } else {
  
    include("inc/pages/home.php" );
  }
  
  
  include( OSS_THEME_PATH."header.php" );
  include( OSS_THEME_PATH."menu.php" );
  
  if ( isset($_GET["option"]) AND file_exists(OSS_THEME_PATH.$_GET["option"] .".php" ) ) {
     
	 include( OSS_THEME_PATH.$_GET["option"] .".php" );
	 
  } else {
     include( OSS_THEME_PATH."home.php" );
  }
  
  include( OSS_THEME_PATH."footer.php" );
?>