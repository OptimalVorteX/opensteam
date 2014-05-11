<?php

  //if(isset($_SERVER["HTTP_COOKIE"])) die("This file is not called from the browser");
  
  // USAGE:
  // Add in: server.cfg
  // sv_loadingurl "http://YOURWEBSITE/loading/?mapname=%m&steamid=%s"
  
  if(isset( $_SERVER["REMOTE_ADDR"])) {
  $UserIP = $_SERVER["REMOTE_ADDR"];
   
   if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
     $var = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
     $UserIP = @array_pop( $var );
	 
     if (empty($UserIP)) $UserIP = $_SERVER["REMOTE_ADDR"];
   }
  } else { $UserIP = ""; }
  
  $mapname = "";
  $steamid = "";
  $Avatar  = "";
  $avatarMedium = "";
  $location  = "";
  $realname = "";
  
  $FileCache = 1;
  $DB_INSERT = 1;
  
  include("../config.php");
  require_once('../inc/common.php');
  include("../inc/default-constants.php");
  require_once('../lang/'.OSS_LANGUAGE.'.php');
  require_once('../inc/class.db.PDO.php'); 
  require_once('../inc/db_connect.php');
  
  if(isset($_GET["mapname"])) $mapname = FilterData($_GET["mapname"]);
  if(isset($_GET["steamid"])) $steamid = FilterData($_GET["steamid"]);
  
  
  $imagesDir = 'bcg/';
  $images = glob($imagesDir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);

  $image = $images[array_rand($images)];
  
  if(!file_exists( $image) ) $image = 'bcg/image_01.jpg';
  
  $File = "steams/".$steamid.".php";
  $CacheTime = $cfg["cache_steam_files"];
  
  $Parse = 1;
  
  if (file_exists($File) AND filemtime($File)+$CacheTime >= time() ) {
  include( $File ); 
  $Parse = 0; 
  if (empty(  $realname ) AND empty($playerName) ) $Parse = 1;
  } 
  

  if(!empty($steamid) AND $Parse == 1) {
  
    if (file_exists($File)) unlink( $File );
  
    $result = file_get_contents("http://steamcommunity.com/profiles/".$steamid."/?xml=1");
    $xml = new SimpleXMLElement($result);
	$items = $xml->xpath('*/avatarFull');
	$Avatar       = FilterData( addslashes($xml->avatarFull));
	$avatarMedium = FilterData( addslashes($xml->avatarMedium));
	$location     = FilterData( addslashes($xml->location));
	$realname     = FilterData(trim( addslashes($xml->realname)));

	$xml2 = new SimpleXMLElement($result);
	$items = $xml2->xpath('*/steamID');
	$playerName   = FilterData( addslashes($xml2->steamID));
	$realname = $playerName;

	  $content = '<?php
if (strstr($_SERVER[\'REQUEST_URI\'], basename(__FILE__) ) ) {header(\'HTTP/1.1 404 Not Found\'); die; }
$Avatar = "'.$Avatar.'";
$avatarMedium = "'.$avatarMedium.'";
$location = "'.$location.'";
$realname = "'.$realname.'";
$playerName = "'.$playerName.'";
$cached = "'.time().'";
$UserIP = "'.$UserIP.'";
?>';
    
    //if ( isset($FileCache) ) 
	file_put_contents($File,  $content);
	
  }
  
  //Add to DB

  if( isset($DB_INSERT) AND !empty($realname) ) {
 
  //CHECK USER BAN
  if($cfg["loading_ban_message"] == 1) {
  
    $checkBan = $db->prepare("SELECT * FROM `".OSSDB_BANS."` WHERE steam='".ConvertToSteam32($steamid)."' LIMIT 1");
    $result = $checkBan->execute();
    if ( $checkBan->rowCount()>=1) {
     $BannedInfo = array();
	 $row = $checkBan->fetch(PDO::FETCH_ASSOC);
	 $BannedInfo["message"]  = $lang["LoadingYouAreBanned"];
	 $BannedInfo["reason"]   = $row["reason"];
	 $BannedInfo["bantime"]  = date(OSS_DATE_FORMAT, strtotime($row["bantime"]) );
	 $BannedInfo["expire"]   = $row["expire"];
     }
  }
  
  $sth = $db->prepare("SELECT COUNT(*) FROM `".OSSDB_PLAYERS."` WHERE steamID=:steamid LIMIT 1");
  $sth->bindValue(':steamid',          $steamid,                   PDO::PARAM_STR);
  $result = $sth->execute();
  $r = $sth->fetch(PDO::FETCH_NUM);
  $numrows = $r[0];

  if($numrows<=0) {
   $sth = $db->prepare("INSERT INTO `".OSSDB_PLAYERS."`(steamID, steam, avatar, avatar_medium, location, playerName, last_connection, connections, user_ip) VALUES(:steamid, :steam, :Avatar, :avatarMedium, :location, :realname, :last_connection, '1', :user_ip )");
   
    $sth->bindValue(':steamid',          $steamid,                   PDO::PARAM_STR); 
	$sth->bindValue(':steam',            ConvertToSteam32($steamid), PDO::PARAM_STR); 
	$sth->bindValue(':Avatar',           $Avatar,                    PDO::PARAM_STR); 
	$sth->bindValue(':avatarMedium',     $avatarMedium,              PDO::PARAM_STR); 
	$sth->bindValue(':location',         $location,                  PDO::PARAM_STR); 
	$sth->bindValue(':realname',         $realname,                  PDO::PARAM_STR); 
	$sth->bindValue(':last_connection',  date("Y-m-d H:i:s", time()),PDO::PARAM_STR); 
	$sth->bindValue(':user_ip',          $UserIP,                    PDO::PARAM_STR); 
	
    $result = $sth->execute();
  } else {
  
  
    $upd = $db->prepare("UPDATE `".OSSDB_PLAYERS."` SET 
	avatar= :Avatar, 
	avatar_medium = :avatarMedium, 
	playerName= :realname, 
	last_connection= :last_connection, 
	connections=connections+1, 
	location = :location, 
	steam= :steam,
	user_ip = :user_ip
	WHERE steamID = :steamID AND last_connection<=NOW() - INTERVAL ".$cfg["cache_time"]." MINUTE  ");
	
	$upd->bindValue(':Avatar',          $Avatar, PDO::PARAM_STR); 
	$upd->bindValue(':avatarMedium',    $avatarMedium, PDO::PARAM_STR); 
	$upd->bindValue(':realname',        $realname, PDO::PARAM_STR); 
	$upd->bindValue(':last_connection', date("Y-m-d H:i:s", time()), PDO::PARAM_STR); 
	$upd->bindValue(':location',        $location, PDO::PARAM_STR); 
	$upd->bindValue(':steam',           trim(ConvertToSteam32($steamid)), PDO::PARAM_STR); 
    $upd->bindValue(':user_ip',         $UserIP, PDO::PARAM_STR); 
	$upd->bindValue(':steamID',         $steamid, PDO::PARAM_STR);

    $result = $upd->execute();
    
  }
  
  }
  
  $str = "";
?>