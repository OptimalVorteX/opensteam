<?php
if (!isset( $cfg["website"] ) ) {header('HTTP/1.1 404 Not Found'); die; }

$errors = "";

include("inc/Hook.php");
include("inc/default-constants.php");

date_default_timezone_set( $cfg["time_zone"] );
	
  function safeEscape($text)
  {
   if (is_numeric($text)) $text=floor($text);
  $text = htmlspecialchars(strip_tags($text));
  $text = htmlentities($text);
  $text = str_replace ('"','',$text);
  $text = str_replace(array("%20", "\"", "'", "\\", "=", ";", ":"), array("","","","","","",""), $text);
  return $text;
  }
  
  
  function EscapeStr($text)
  {
  $text = htmlentities($text);
  //$text = str_replace(array("%20", "\"", "'", "\\", "=", ";", ":"), "", $text);
  return $text;
  }
  
function FilterData($data) {

    if ( is_array($data) ) foreach( $data as $d ) {
	$d = trim(htmlentities(strip_tags($d)));
	$data[] = $d;
	}
	else $data = trim(htmlentities(strip_tags($data)));
 
    if (get_magic_quotes_gpc())
        if ( !is_array($data) ) $data = stripslashes($data);
 
    if ( !is_array($data) ) $data = htmlentities(trim($data));
    $data = str_replace("../", "", $data);
    return $data;
}

    function getMonthName($month,
	$ljan = "JAN",
	$lfeb = "FEB",
	$lmar = "MAR",
	$lapr = "APR",
	$lmay = "MAY",
	$ljun = "JUN",
	$ljul = "JUL",
	$laug = "AUG",
	$lsep = "SEP",
	$loct = "OCT",
	$lnov = "NOV",
	$ldec = "DEC"
	           ) 
	{
	if ($month == 1) $rmonth = $ljan;
	if ($month == 2) $rmonth = $lfeb;
	if ($month == 3) $rmonth = $lmar;
	if ($month == 4) $rmonth = $lapr;
	if ($month == 5) $rmonth = $lmay;
	if ($month == 6) $rmonth = $ljun;
	if ($month == 7) $rmonth = $ljul;
	if ($month == 8) $rmonth = $laug;
	if ($month == 9) $rmonth = $lsep;
	if ($month == 10) $rmonth = $loct;
	if ($month == 11) $rmonth = $lnov;
	if ($month == 12) $rmonth = $ldec;
	
	return $rmonth;
	
	}
	
	function getDays($m){
	return 31;
	if ($m == 1) return 31;
	if ($m == 2) return 28;
	if ($m == 3) return 31;
	if ($m == 4) return 30;
	if ($m == 5) return 31;
	if ($m == 6) return 30;
	if ($m == 7) return 31;
	if ($m == 8) return 30;
	if ($m == 9) return 31;
	if ($m == 10) return 30;
	if ($m == 11) return 31;
	if ($m == 12) return 30;
	}

  function limit_words($string, $word_limit, $dots = 1)
{
    $string = (strip_tags($string));
	$string = str_replace("\r\n","",$string);
	$string = str_replace("&nbsp;","",$string);
	$string = str_replace("\n","",$string);
	//$string = preg_replace('/\<script>(.*?)</script>/si', '', $string);
	//while( strstr($string, "\t") ) $string = str_replace("\t","",$string); 
    $words = explode(" ",$string);
	if ($dots==1) $add = ""; else $add = "";
    return implode(" ",array_splice($words,0,$word_limit)).$add;
}
  
  function generate_password($pass, $salt = "0#'open73^743_list_923^$&_") {
  $password = substr(md5($salt),0,3).sha1($pass.$salt).substr(md5($pass),0,3).substr(sha1($salt),0,3);
  return $password;
  }
  
  function generate_hash($length=22, $alphnum = 0 ) {
 $alphanum = '0123456789qwertyuiopasdfghjklzxcvbnmqwertyuiopasdfghjklzxcvbnmqwertyuiopasdfghjklzxcvbnm0123456789';
 if ($alphnum==1) $alphanum.="!@#$^()_|";
 $rand = strtolower(substr(str_shuffle($alphanum), 0, $length));
 return $rand;
 }

 function write_value_of($var,$oldval,$newval, $file)
{
 $contents = file_get_contents($file);
 $regex = '~\\'.$var.'\s+=\s+\''.$oldval.'\';~is';
 $contents = preg_replace($regex, "$var = '$newval';", $contents);
 file_put_contents($file, $contents);
}

	function get_value_of($name, $file = "../config.php")
    {
    $lines = file($file);
	 $val = array();
     foreach (array_values($lines) AS $line)
     {
	   if (strstr($line,"="))
	   {
       list($key, $val) = explode('=', trim($line) );
       if (trim($key) == $name)
          {$val = str_replace(";","",$val); $val = str_replace("'","",$val); 
		  $val = str_replace('"',"",$val);  return $val;}
       }
     }
     return false;
  }
  
function OSS_SaveConfig( $var, $old, $new, $file = "config.php" ) {

    $contents = file_get_contents($file);
	$NewContents = str_replace( $var.' = \''.$old.'\'', $var.' = \''.$new.'\'', $contents );
    file_put_contents($file, $NewContents);
}

function removeDoubleSpaces($text) {
   while ( strstr($text, "\t") ) $text = str_replace("\t", " ", $text);
   while ( strstr($text, "  ") ) $text = str_replace("  ", " ", $text);
   $text = trim($text);
   return $text;
}

function OB_ExpireDateRemain( $bandate = "", $d="d", $h="h", $min="min", $remain="remain", $expired = "expired" ) {
  global $lang;
  if ( $bandate!='' AND $bandate!='0000-00-00 00:00:00' ) {
  
    if ( strtotime($bandate)<=time() ) { echo "<b>".$lang["Expired"]."</b>"; return false; }
  
    //Calculate remaining datetime - expired bans
	$seconds = strtotime($bandate) - time();
	$days = floor($seconds / 86400);
	$seconds %= 86400;

	$hours = floor($seconds / 3600);
	$seconds %= 3600;

	$minutes = floor($seconds / 60);
	$seconds %= 60;
    
	if ( $days>=1 ) echo "<b>".$days." $d</b>, ";

	if ($hours>=1)  echo "<b>".$hours." $h</b>, ";
	echo "<b>".$minutes." $min</b> ".$lang["Remain"];
  }
  
  if ($bandate =='0000-00-00 00:00:00' ) echo '<span class="label label-danger">'.$lang["Permanent"].'</span>';
}

//76561197989773862
function ConvertToSteam64( $SteamID = "" ) {

	$parts = explode(':', str_replace('STEAM_', '', $SteamID));
	
	if ( isset($parts[0]) AND isset($parts[1]) AND isset($parts[2]) ) {
	
	$auth          = $parts[1];
	$accountNumber = $parts[2] * 2;
	$return = ($accountNumber) + ( $auth + 76561197960265728 );
	
	return $return;
	
	}
}

//STEAM_0:0:14754067
function ConvertToSteam32($Steam64ID)
{
    $offset = $Steam64ID - 76561197960265728;
    $id = ($offset/2);
    if($offset%2 != 0)
    {
  $Steam32ID = 'STEAM_0:1:' . bcsub($id, '0.5');
    }
    else
    {
  $Steam32ID = "STEAM_0:0:" . $id;
    }
    
	
	return $Steam32ID;
}

function OSS_Curl( $url = "http://ohsystem.net/stats/version_os.php?check" ) {
 
   if (function_exists('curl_init') AND isset($_SESSION["logged"]) ) {
   
	 if(isset($_SERVER['HTTP_REFERER'])) $ref = $_SERVER['HTTP_REFERER'];
	 else $ref = OSS_HOME;
	 $ch = curl_init( $url );
	 curl_setopt($ch, CURLOPT_HEADER, 0);
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	 curl_setopt($ch, CURLOPT_REFERER, $ref);
	 curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.5) Gecko/20041107 Firefox/1.0');
     $return  = curl_exec ($ch);
	 
     curl_close ($ch); 
	 return $return;

	}
 
}
 
function OSS_SuperAdmin() {

   if(isset($_SESSION["rank"]) AND $_SESSION["rank"] == "superadmin" AND isset($_SESSION["admin"]) )
   return true;

}

function OSS_IsAdmin( $rank = "" ) {

   if($rank == "superadmin" OR $rank == "admin" )
   return true;

}

function OSS_EditUser( $userID ) {
    
   if(OSS_SuperAdmin() AND is_numeric($userID) ) {
   global $lang;
   ?>
   <a class="btn btn-warning btn-xs floatR" href="<?=OSS_HOME?>?option=admin_edit_user&amp;id=<?=(int)$userID?>"><?=$lang["Edit"]?></a>
   <?php
   } else if(OSS_SuperAdmin() AND !is_numeric($userID) ) {
   global $lang;
   ?>
   <a class="btn btn-warning btn-xs floatR" href="<?=OSS_HOME?>?option=admin_edit_user&amp;id=<?=$userID?>"><?=$lang["Edit"]?></a>
   <?php
   }
}

function OSS_EditServer( $serverID ) {
    
   if(OSS_SuperAdmin() AND is_numeric($serverID) ) {
   global $lang;
   ?>
   <a class="btn btn-danger btn-xs floatR" href="<?=OSS_HOME?>?option=admin_servers&amp;rcon=<?=(int)$serverID?>">rcon</a> 
    <a class="btn btn-warning btn-xs floatR" href="<?=OSS_HOME?>?option=admin_servers&amp;edit=<?=(int)$serverID?>"><?=$lang["Edit"]?></a>
   <?php
   } 
}

function OSS_DeleteBan( $userID ) {
    
   if(OSS_SuperAdmin() AND is_numeric($userID) ) {
   global $lang;
   if(isset($_GET["page"]) AND is_numeric($_GET["page"]) ) $page = "&amp;page=".(int)$_GET["page"];
   else $page = "";
   ?>
   <a class="btn btn-danger btn-xs floatR" href="javascript:;" onclick="if(confirm('Remove Ban?')) { location.href='<?=OSS_HOME?>?option=bans&rban=<?=(int)$userID?><?=$page?>'} ">&times;</a>
   <?php
   } 
}


function OSS_SteamAvatar( $link = "", $w="64", $h="64" ) {
   if(!empty($link) ) {
   
    ?><img src="<?=$link?>" alt="avatar" width="<?=$w?>" height="<?=$h?>" class="SteamAvatar" /><?php
   
   }
}

function OSS_IsBanned( $expire = "" ) {

  if(!empty($expire) ) return true;

}

function OSS_CountryFlag( $letter = "", $country = "", $w = '24' ) {
  if(!empty( $letter ) AND file_exists("img/flags/".$letter.".gif")) {
  ?>
  <img src="<?=OSS_HOME?>img/flags/<?=$letter?>.gif" alt="" class="flag flag_<?=$letter?>" width="<?=$w?>" /> 
  <?=$country?>
  
  <?php
  }
}

foreach($_POST as $key => $value) {
    $_POST[$key] = FilterData($value);
}

foreach($_GET as $key => $value) {
    $_GET[$key] = FilterData($value);
}

if ($_SESSION) foreach($_SESSION as $key => $value) {
    $_SESSION[$key] = FilterData($value);
}
?>