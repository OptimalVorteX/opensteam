<?php
if (!isset( $cfg["website"] ) ) {header('HTTP/1.1 404 Not Found'); die; } 
  if ( OSS_SuperAdmin() ) {
  
    if ( !is_writable("config.php") ) {
  
    $Warning = '<strong>config.php</strong> is not writable.';
    $ButtonType = 'button';
	$Alert = 'onclick="alert(\'config.php is not writable\')"';
  } else {
  
    $ButtonType = 'submit';
    $Alert = '';
  }
  
  //Save configuration
  if(isset($_POST["save_config"])) {
     
	  OSS_SaveConfig('$cfg["home_title"]', $cfg["home_title"], trim($_POST["home_title"]) );
	  if(file_exists("lang/".$_POST["lang"])) {
	  $_POST["lang"] = str_replace(".php", "", $_POST["lang"]);
	  OSS_SaveConfig('$cfg["default_language"]', $cfg["default_language"], trim($_POST["lang"]) );
	  }
	  
	  OSS_SaveConfig('$cfg["date_format"]', $cfg["date_format"], trim($_POST["date_format"]) );
	  if($_POST["players_per_page"]<500 AND $_POST["players_per_page"]>0)
	  OSS_SaveConfig('$cfg["players_per_page"]', $cfg["players_per_page"], trim($_POST["players_per_page"]) );
      OSS_SaveConfig('$cfg["cache_time"]', $cfg["cache_time"], trim($_POST["cache_time"]) );
	  OSS_SaveConfig('$cfg["debug"]', $cfg["debug"], trim($_POST["debug"]) );
	  OSS_SaveConfig('$cfg["loading_ban_message"]', $cfg["loading_ban_message"], trim($_POST["loading_ban_message"]) );
	  OSS_SaveConfig('$cfg["remove_players"]', $cfg["remove_players"], trim($_POST["remove_players"]) );
	  header("location: ".OSS_HOME."?option=configuration");
	  die();
  }
  

   if ($handle = opendir("./lang")) {
   $c = 0;
   $LangOptions = array();
   
   while (false !== ($file = readdir($handle))) 
	{
	  if ($file !="." AND  $file !="index.html" AND $file !=".." AND strstr($file,".png")==false AND strstr($file,".css")==false AND strstr($file,".js")==false AND strstr($file,".php")==true ) {
	  
	  if (trim( str_replace(".php", "", $file) ) == trim($cfg["default_language"] ))  
	  $LangOptions[$c]["selected"] = 'selected="selected"'; else $LangOptions[$c]["selected"] = "";
	  
	  $LangOptions[$c]["lang"] = str_replace(".php", "", $file);
	  $LangOptions[$c]["file"] = $file;
	  }
	}
  }
  
  
  } else {
  
    header("location: ".OSS_HOME."");
    die();
  }
  
?>
  
  