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
	 $items = $xml2->xpath('*/steamID');
	 $playerName   = FilterData($xml2->steamID);
	 $realname = $playerName;
	 
	 $xml3 = new SimpleXMLElement($result);
	 $items = $xml3->xpath('*/profile');
	 $steam64   = FilterData($xml3->steamID64);

	 $Steam = ConvertToSteam32( $steam64 );
	 echo "$Steam|	|$steam64|	|$realname|	|$Avatar|	|$avatarMedium|	|$location";
	 die();
	 
  }
  
  if (!isset( $cfg["website"] ) ) {header('HTTP/1.1 404 Not Found'); die; }
  
  
  if ( OSS_SuperAdmin() )  {
     $sql  = "";
	 
	 if ( isset($_POST["add_admin"] ) AND is_numeric($_POST["steamID"]) AND !empty($_POST["steam"]) ) {
	 
	     $steamID = trim($_POST["steamID"]);
		 $steam = trim($_POST["steam"]);
		 $playerName = trim($_POST["playerName"]);
		 $location = trim($_POST["location"]);
		 $avatar = trim($_POST["avatar"]);
		 $avatarMed = trim($_POST["avatarMed"]);
		 $group = trim($_POST["group"]);
		 
		 if(!strstr($steam, "STEAM_")) $error = 1;
		 if(empty($playerName)) $error = 1;
		 
		 $sth = $db->prepare("SELECT COUNT(*) FROM `".OSSDB_PLAYERS."` WHERE `steamID`='".$steamID."' ");
	     $result = $sth->execute();
		 $r = $sth->fetch(PDO::FETCH_NUM);
	     $numrows = $r[0];
		 
		 if($numrows>=1)  $error = 2;
	 
		 if ( isset($error) ) {
		    header("location: ".OSS_HOME."?option=admin_add_admin&error=".$error);
		    die();
		 }
		 
		 
		 
		 $sth = $db->prepare("INSERT INTO `".OSSDB_PLAYERS."`(`steamID`, `steam`, `avatar`, `avatar_medium`, `location`, `playerName`, `rank`, `last_connection`) VALUES(:steamID, :steam, :avatar, :avatarMed, :location, :playerName, :group, :date )");
		 
		 $sth->bindValue(':steamID',        $steamID,                 PDO::PARAM_STR); 
		 $sth->bindValue(':steam',          $steam,                   PDO::PARAM_STR); 
		 $sth->bindValue(':avatar',         $avatar,                  PDO::PARAM_STR); 
		 $sth->bindValue(':avatarMed',      $avatarMed,               PDO::PARAM_STR); 
		 $sth->bindValue(':location',       $location,                PDO::PARAM_STR); 
		 $sth->bindValue(':playerName',     $playerName,              PDO::PARAM_STR); 
		 $sth->bindValue(':group',          $group,                   PDO::PARAM_STR); 
		 $sth->bindValue(':date',           date("Y-m-d H:i:s"),      PDO::PARAM_STR); 
		 
		 $result = $sth->execute();
		 
		 header("location: ".OSS_HOME."?option=admin_add_admin&success");
		 die();
 	 
	 }
	 
     $sth = $db->prepare("SELECT * FROM ".OSSDB_GROUPS." WHERE `group`!='' 
	 ORDER BY FIELD(`group`, 'superadmin') ASC, `group` ASC
	 LIMIT 500");
	 $result = $sth->execute();
	 
	 $GroupsData = array();
	 $c = 0;
	 while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
	   $GroupsData[$c]["group"]    = $row["group"];
	   if ($row["group"] == "user") $GroupsData[$c]["sel"] = 'selected="selected"'; else
	   $GroupsData[$c]["sel"] = "";
       $c++;
	}
  
  
  
  } else {
    header("location: ".OSS_HOME."");
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
					 document.getElementById("avatarIMG").innerHTML = '<img src="'+avatar+'" alt="" width="80" />';
                     document.getElementById("avatar").value = avatar;
					 document.getElementById("avatarMed").value = avatarMed;
					 document.getElementById("location").value = location;
					 document.getElementById("info").innerHTML = ' ';
                    },
                    error: function(msg){
					document.getElementById("info").innerHTML = msg;
                    }
                });
	}
	</script>		
	<?php
  }