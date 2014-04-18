<?php
if (!isset( $cfg["website"] ) ) {header('HTTP/1.1 404 Not Found'); die; }

  if( isset($_GET["option"]) AND $_GET["option"] == "logout" ) {
  
    session_destroy();
    header("location: ".OSS_HOME."");
	die();
  }

  //Check logged in users
  if ( !empty($_SESSION["steamID"]) ) {
 
	$steamID = trim( strip_tags($_SESSION["steamID"] ) );
	   
	$sth = $db->prepare( "SELECT * FROM `".OSSDB_PLAYERS."` WHERE `steam` = '".$steamID."' LIMIT 1 " );
	$result = $sth->execute();
	
	$row = $sth->fetch(PDO::FETCH_ASSOC);
	
	$_SESSION["rank"] = $row["rank"];
	$_SESSION["name"] = $row["playerName"];
	
	if(empty( $row["playerName"] )) $_SESSION["name"] = $steamID;
	
	if ( $row["rank"] == "superadmin" ) $_SESSION["admin"] = $steamID;
	
  }

?>