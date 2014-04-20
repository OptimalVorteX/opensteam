<?php  
if (strstr($_SERVER['REQUEST_URI'], basename(__FILE__) ) ) {header('HTTP/1.1 404 Not Found'); die; }
	
if (!isset($_SESSION)) { session_start(); }

$cfg = array();


$cfg["server"]    = 'localhost';
$cfg["username"]  = 'root';
$cfg["password"]  = 'password';
$cfg["database"]  = 'prop_hunt';
$cfg["db_prefix"] = 'ph_';

$cfg["website"]          = 'http://ohsystem.net/opensteam/';
$cfg["home_title"]       = 'OpenSteam and User Management System';
$cfg["home_description"] = 'ULX OpenSteam';
$cfg["home_keywords"]    = 'garrys mode, prop hunt, open bans, ulx';

$cfg["default_language"] = 'english';
$cfg["date_format"] = 'd.m.Y, H:i';
$cfg["default_style"] = 'default';

$cfg["logo_text"] = 'OpenSteam';

$cfg["players_per_page"] = '30';
//Show message info for banned users on loading screen
$cfg["loading_ban_message"] = '1';

//Remove inactive players. Default:(inactive time: 90 days)
$cfg["remove_players"] = '90';

//Cache steamIDs (used for loading url). Minutes
$cfg["cache_time"] = '5';

// 3600*24*3 = 3 days (update parsed steam files from steam API)
$cfg["cache_steam_files"] = 3600*24*3;

//Enable error reportings
$cfg["debug"] = '1';

$cfg["time_zone"] = 'Europe/Belgrade';

$cfg["INSTALLED"] = '1';

if($cfg["debug"] == 1 ) error_reporting(E_ALL);

?>