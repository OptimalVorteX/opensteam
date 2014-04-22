<?php
if (!isset( $cfg["website"] ) ) {header('HTTP/1.1 404 Not Found'); die; } 
  if ( OSS_SuperAdmin() ) {
  
  if(isset($_POST["country_bans"])) {
  
    if( isset($_POST["country"]) ) {
	  
	  $str = "";
	  foreach( $_POST["country"] as $e=>$v ) {
		$str.=$e.",";
	  }
	  
	  if(!empty($str)) {
	    $str = substr($str, 0, strlen($str)-1 );
	    $sth = $db->prepare("INSERT INTO `".OSSDB_CONFIG."`(`config_name`, `config_value` ) VALUES('country_bans', '".$str."') 
		ON DUPLICATE KEY UPDATE config_value = '".$str."' ");
		$result = $sth->execute();
	  }
	
	}
	header("location: ".OSS_HOME."?option=admin_ban_country");
	die();
	
  }
  
    $sth = $db->prepare("SELECT `config_value` FROM `".OSSDB_CONFIG."` WHERE config_name = 'country_bans'  ");
	$result = $sth->execute();
	$row = $sth->fetch(PDO::FETCH_ASSOC);
	
	$BannedCountries = explode(",", $row["config_value"]);

    if (file_exists("inc/countries.php")) { 
	  
	  include("inc/countries.php"); 
	
	  $AllCountries = array();
	  $c = 0;
	  
	  foreach($Countries as $code=>$Country) {
	  
	    $AllCountries[$c]["country"] = $Country;
		$AllCountries[$c]["code"]    = trim($code);
		
		$AllCountries[$c]["checked"]    = '';
		
		  foreach($BannedCountries as $bc) {
		     if( trim($bc) == trim($code) ) $AllCountries[$c]["checked"]    = 'checked="checked"';
		  }

		$c++;
	  
	  }
	
	} else {
	  header("location: ".OSS_HOME);
	   die();
	}

}
  
?>