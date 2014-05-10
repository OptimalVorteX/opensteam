<?php

  if (isset($_REQUEST["steam_info"]) ) {
  
     if ($_REQUEST["url"]) $url = $_REQUEST["url"];
     else die("Invalid url");
	 
	 if(!strstr($url, "http://steamcommunity.com/profiles/") AND !strstr($url, "http://steamcommunity.com/id/")) die("Invalid url");
	 
	 include("../../config.php"); 
	 include("../common.php"); 
	 
	 if ( !OSS_SuperAdmin() )  die("Invalid request!");
	 
	 $url = str_replace(array("?", "&"), array("", ""), $url);
	 
	 $data = explode("/", $url);
	 $ComID = $data[4];
	 $result = file_get_contents($url."?xml=1");

     $xml = new SimpleXMLElement($result);
	 $items = $xml->xpath('*/avatarFull');
	 $Avatar       = FilterData($xml->avatarFull);  
	 $avatarMedium = FilterData($xml->avatarMedium);
	 $location     = FilterData($xml->location);
	 $realname     = FilterData(trim($xml->realname));

	 $xml2 = new SimpleXMLElement($result);
	 $items        = $xml2->xpath('*/steamID');
	 $playerName   = FilterData($xml2->steamID);
	 $realname     = $playerName;
	 
	 $xml3 = new SimpleXMLElement($result);
	 $items = $xml3->xpath('*/profile');
	 $steam64   = $xml3->steamID64;

	 $Steam = ConvertToSteam32( $steam64 );
	 echo "$Steam|	|$steam64|	|$realname|	|$Avatar|	|$avatarMedium|	|$location";
	 die();
	 
  }


 if (!isset( $cfg["website"] ) ) {header('HTTP/1.1 404 Not Found'); die; }
 
 
   if ( OSS_SuperAdmin() )  {
     $sql  = "";
	 
	 if ( isset($_POST["add_ban"] ) AND !empty($_POST["steam"]) ) {
	 
		 $steam = trim($_POST["steam"]);
		 $playerName = trim($_POST["playerName"]);
		 $checked = 1;
		 $reason = trim($_POST["reason"]);
		 $expire = trim($_POST["expire"]);
		 $check = explode("-", $expire );
		 
		  if( isset($check[0]) AND strlen($check[0]) == 4 )  $checked++;
		  if( isset($check[1]) AND strlen($check[1]) == 2 )  $checked++;
		  if( isset($check[2]) AND strstr($check[2], " ") AND strstr($check[2], ":") ) $checked++;
		  
		  if($checked<=3) $error = 1;
		 
		 if(!strstr($steam, "STEAM_")) $error = 2;
		 if(empty($playerName)) $error = 3;
		 
		 $sth = $db->prepare("SELECT COUNT(*) FROM `".OSSDB_BANS."` WHERE `steam`=:steam ");
		 $sth->bindValue(':steam',        $steam,                 PDO::PARAM_STR);
	     $result = $sth->execute();
		 $r = $sth->fetch(PDO::FETCH_NUM);
	     $numrows = $r[0];
		 
		 if($numrows>=1)  $error = 4;
	 
		 if ( isset($error) ) {
		    header("location: ".OSS_HOME."?option=admin_ban_player&error=".$error);
		    die();
		 }
		 //Get PlayerIP
		 $sth = $db->prepare( "SELECT * FROM ".OSSDB_PLAYERS." WHERE `steam`=:steam LIMIT 1" );
		 $sth->bindValue(':steam', $steam, PDO::PARAM_STR); 
		 $result = $sth->execute();
	
		 $row = $sth->fetch(PDO::FETCH_ASSOC);
		 $ip = $row["user_ip"];
		 
		 $sth = $db->prepare("INSERT INTO `".OSSDB_BANS."`(`steam`, `name`, `admin`, `reason`, `bantime`, `expire`, `ip`) VALUES(:steam, :playerName, :admin, :reason, :datetime, :expire, :ip )");
		 
		 $sth->bindValue(':steamID',        $steam,              PDO::PARAM_STR); 
		 $sth->bindValue(':playerName',     $playerName,         PDO::PARAM_STR); 
		 $sth->bindValue(':admin',          $_SESSION["name"],   PDO::PARAM_STR); 
		 $sth->bindValue(':reason',         $reason,             PDO::PARAM_STR); 
		 $sth->bindValue(':datetime',       date("Y-m-d H:i:s"), PDO::PARAM_STR); 
		 $sth->bindValue(':expire',         $expire,             PDO::PARAM_STR); 
		 $sth->bindValue(':ip',             $ip,                 PDO::PARAM_STR); 
		 
		 $result = $sth->execute();
		 
		 header("location: ".OSS_HOME."?option=admin_ban_player&success");
		 die();
 	 
	 }
	 
	 
  AddEvent("OPENSTEAM_AFTER_FOOTER", "OS_AjaxCall");
  
  function OS_AjaxCall() {
    ?>
	<script>
	function GetSteamInfo() {
	var $js = jQuery;
	document.getElementById("info").innerHTML = '<img src="<?=OSS_HOME?>img/loader.gif" alt="" width="90" height="14" />';
	var url = document.getElementById("steamProfile").value;
	     $js.ajax({
                    type: "POST",
                    url: "inc/pages/admin_add_admin.php?steam_info",
                    data: "url="+url,
                    success: function(msg){
					var data = msg.split("|	|");
					 if (data[0] == "") { 
					  document.getElementById("info").innerHTML = "No profile found";
					 }
					 var steam = data[0];
					 var comID = data[1];
					 var player = data[2];
					 var avatar = data[3];
					 var avatarMed = data[4];
					 var location  = data[5];
					 
					 document.getElementById("steam").value = steam;
					 document.getElementById("steamID").value = comID;
					 document.getElementById("player").value = player;
					 //document.getElementById("avatarIMG").innerHTML = '<img src="'+avatar+'" alt="" width="80" />';
                     //document.getElementById("avatar").value = avatar;
					 //document.getElementById("avatarMed").value = avatarMed;
					 //document.getElementById("location").value = location;
					 document.getElementById("info").innerHTML = ' ';
                    },
                    error: function(msg){
					document.getElementById("info").innerHTML = msg;
                    }
                });
	}
	
   function SetDateField(datetime, fieldID) {
     document.getElementById(fieldID).value = datetime;
   }
	</script>		
	<?php
  }

  
  } else {
    header("location: ".OSS_HOME."");
	die();
  }
  