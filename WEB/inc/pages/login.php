<?php

 if ( !isset($_SESSION["logged"]) ) {
 
 include("inc/steam_class.php");
 
 
 $Login = SteamSignIn::genUrl( OSS_HOME.'?option=login' );
 if(isset($_GET["openid_ns"])) $Validate = SteamSignIn::validate();
 
 if ( empty($Validate) ) {
 
   //LOGIN HERE
   //header("location: ".$Login ."");
 
 } else {
 if ( strlen($Validate)>=8 AND is_numeric($Validate) ) {
   $_SESSION["logged"] = $Validate;
   $_SESSION["steamID"] = ConvertToSteam32($Validate);
 }
 OSS_Curl();
 header("location: ".OSS_HOME."");
 die();
 }
 
 } else {
 
   header("location: ".OSS_HOME."");
   die();
 }
?>