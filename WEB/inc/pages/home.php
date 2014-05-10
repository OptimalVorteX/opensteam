<?php
    $sql = " AND p.playerName != ''";

	if ( isset($_GET["search"]) AND strlen($_GET["search"])>=2 ) {
	
	  $search = strip_tags( trim($_GET["search"]) );
	  $sql.=" AND (p.playerName LIKE :str1 OR p.`steam` LIKE :str2 )";
	  if(is_numeric($search)) $sql.= " OR p.`steamID` = :str3 ";
	}

    $sth = $db->prepare("SELECT COUNT(*) FROM ".OSSDB_PLAYERS." as p WHERE p.id>=1 ".$sql." LIMIT 1");
	
	if ( isset($_GET["search"]) AND strlen($_GET["search"])>=2 ) {
	$sth->bindValue(':str1',           "%".$search."%",                    PDO::PARAM_STR); 
	$sth->bindValue(':str2',           "%".$search."%",                    PDO::PARAM_STR); 
	if(is_numeric($search))
	$sth->bindValue(':str3',           $search,                    PDO::PARAM_STR); 
	}
	
    $result = $sth->execute();

	 $r = $sth->fetch(PDO::FETCH_NUM);
	 $numrows = $r[0];
	 $result_per_page = $cfg["players_per_page"];
	 $draw_pagination = 0;
	 include('inc/pagination.php');
	 $draw_pagination = 1;
	 $SHOW_TOTALS = 1;
	 
	 $orderby  = " id DESC";
	 
	 if(isset($_GET["sort"])) {
	 
	   if($_GET["sort"] == "connections") $orderby  = " connections DESC";
	   if($_GET["sort"] == "id")          $orderby  = " id ASC";
	   if($_GET["sort"] == "player")      $orderby  = " LOWER(playerName) ASC";
	   if($_GET["sort"] == "last_seen")   $orderby  = " (last_connection) DESC";
	 }
	 
	 $sth = $db->prepare("SELECT p.id, p.steamID, p.avatar, p.avatar_medium, p.location, p.playerName, p.connections, p.last_connection, p.rank, p.user_ip, b.name, b.id as ban_id, b.admin, b.expire
	 FROM ".OSSDB_PLAYERS." as p 
	 LEFT JOIN ".OSSDB_BANS." as b ON b.steam = p.steam
	 WHERE p.id>=1 ".$sql ."
	 ORDER BY ".$orderby ."
	 LIMIT ".$offset.", ".$rowsperpage."");
	 
	if ( isset($_GET["search"]) AND strlen($_GET["search"])>=2 ) {
	$sth->bindValue(':str1',           "%".$search."%",                    PDO::PARAM_STR); 
	$sth->bindValue(':str2',           "%".$search."%",                    PDO::PARAM_STR); 
	if(is_numeric($search))
	$sth->bindValue(':str3',           $search,                    PDO::PARAM_STR); 
	}
	 
	 $result = $sth->execute();
	 
	 $c=0;
	 
	 $PlayersData = array();
	 
	 //Include GeoIP
	 if ( file_exists("inc/geoip/geoip.inc") AND !class_exists("GeoIP")  ) {
	  include_once("inc/geoip/geoip.inc");
	  $GeoIPDatabase = geoip_open("inc/geoip/GeoIP.dat", GEOIP_STANDARD);
	  $GeoIP = 1;
	}
	 
	 while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
	 
	if( strstr($row["user_ip"], ":") ) { 
	  $uip = explode(":", $row["user_ip"]);
	  $row["user_ip"] = $uip[0];
	}
	 
	$Letter   = geoip_country_code_by_addr($GeoIPDatabase, $row["user_ip"]);
	$Country  = geoip_country_name_by_addr($GeoIPDatabase, $row["user_ip"]);
	
	if(!empty($Letter)) {
	  $PlayersData[$c]["letter"]  = $Letter;
	  $PlayersData[$c]["country"] = $Country;
	} else {
	 $PlayersData[$c]["letter"]  = "blank";
	 $PlayersData[$c]["country"] = "";
	}
	 
	 $PlayersData[$c]["id"]  = ($row["id"]);
	 $PlayersData[$c]["steamID"]  = ($row["steamID"]);
	 $PlayersData[$c]["avatar"]  = ($row["avatar"]);
	 if(empty($row["avatar"])) $PlayersData[$c]["avatar"] = OSS_THEME_LINK."images/no-avatar.gif";
	 $PlayersData[$c]["avatar_medium"]  = ($row["avatar_medium"]);
	 if(empty($row["avatar_medium"])) $PlayersData[$c]["avatar_medium"] =  OSS_THEME_LINK."images/no-avatar.gif";
	 $PlayersData[$c]["location"]  = ($row["location"]);
	 $PlayersData[$c]["playerName"]  = ($row["playerName"]);
	 $PlayersData[$c]["connections"]  = ($row["connections"]);
	 $PlayersData[$c]["last_connection"]  = date(OSS_DATE_FORMAT, strtotime($row["last_connection"]) );
	 $PlayersData[$c]["rank"]  = ($row["rank"]);
	 
	 $PlayersData[$c]["expire_date"] = "";
	 
	 if($row["rank"] == "superadmin") $PlayersData[$c]["class"] = 'class="success"';
	 else $PlayersData[$c]["class"] = "";
	 
	 if(!empty($row["name"])) {
	 $PlayersData[$c]["class"] = 'class="danger"';
	 $PlayersData[$c]["admin"] = $row["admin"];
	 $PlayersData[$c]["expire_date"] = $row["expire"];
	 $PlayersData[$c]["expire"] = date(OSS_DATE_FORMAT, strtotime($row["expire"]) );
	 }
	 
	 $PlayersData[$c]["num"]  = ($c+1);
	  
	 $c++;
	 }
	 
	 if ( isset($GeoIP) AND $GeoIP == 1) geoip_close($GeoIPDatabase);
?>