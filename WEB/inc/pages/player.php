<?php
   if(!isset($_GET["id"])) { header("location: ".OSS_HOME.""); die(); }
   
   
	$pid = trim($_GET["id"]);
	
	$field = 'id';

	if(!is_numeric($pid) AND strstr( $pid, "STEAM_") ) {
	
		  $sth = $db->prepare( "SELECT * FROM ".OSSDB_PLAYERS." WHERE steam=:steam LIMIT 1" );
		  $sth->bindValue(':steam', $pid, PDO::PARAM_STR); 
		  $result = $sth->execute();
	
		  $row = $sth->fetch(PDO::FETCH_ASSOC);
	      $pid = $row["id"];
		  $field = 'id'; 
	}


	$sth = $db->prepare( "SELECT * FROM ".OSSDB_PLAYERS." WHERE ".$field."=:id LIMIT 1" );
	$sth->bindValue(':id', $pid, PDO::PARAM_INT); 
	$result = $sth->execute();
	
	$row = $sth->fetch(PDO::FETCH_ASSOC);
	
	 //Include GeoIP
	 if ( file_exists("inc/geoip/geoip.inc") AND !class_exists("GeoIP")  ) {
	  include_once("inc/geoip/geoip.inc");
	  $GeoIPDatabase = geoip_open("inc/geoip/GeoIP.dat", GEOIP_STANDARD);
	  $GeoIP = 1;
	}
	
	if( strstr($row["user_ip"], ":") ) { 
	  $uip = explode(":", $row["user_ip"]);
	  $row["user_ip"] = $uip[0];
	}
	 
	$Letter   = geoip_country_code_by_addr($GeoIPDatabase, $row["user_ip"]);
	$Country  = geoip_country_name_by_addr($GeoIPDatabase, $row["user_ip"]);
	$PlayerInfo = array();
	
	if(!empty($Letter)) {
	  $PlayerInfo ["letter"]  = $Letter;
	  $PlayerInfo ["country"] = $Country;
	} else {
	 $PlayerInfo ["letter"]  = "blank";
	 $PlayerInfo ["country"] = "";
	}

	$PlayerInfo["id"]      = $row["id"];
	$PlayerInfo["steamID"] = $row["steamID"];
	$PlayerInfo["steam"] = $row["steam"];
	$PlayerInfo["location"] = $row["location"];
	$PlayerInfo["playerName"] = $row["playerName"];
	$PageTitle = $row["playerName"].' | OpenSteam';
	$PlayerInfo["avatar"] = $row["avatar"];
	$PlayerInfo["avatar_medium"] = $row["avatar_medium"];
	$PlayerInfo["last_connection"] = date( OSS_DATE_FORMAT, strtotime($row["last_connection"]) );
	$PlayerInfo["connections"] = $row["connections"];
	$PlayerInfo["rank"] = $row["rank"];
	$PlayerInfo["user_ip"] = $row["user_ip"];
	
	//Check ban
	$sth = $db->prepare( "SELECT * FROM ".OSSDB_BANS." WHERE steam=:steam AND (expire>=NOW() OR expire = '0000-00-00 00:00:00' ) LIMIT 1" );
	$sth->bindValue(':steam', $PlayerInfo["steam"], PDO::PARAM_STR); 
	$result = $sth->execute();
	
	$row = $sth->fetch(PDO::FETCH_ASSOC);
	
	if(!empty($row["name"])) {
	
	  $PlayerInfo["banid"]   = $row["id"];
	  $PlayerInfo["admin"]   = $row["admin"];
	  $PlayerInfo["bantime"] = date( OSS_DATE_FORMAT, strtotime($row["bantime"]));
	  $PlayerInfo["expire"]  = date( OSS_DATE_FORMAT, strtotime($row["expire"] ));
	  $PlayerInfo["expire_date"]  = $row["expire"];
	}

?>