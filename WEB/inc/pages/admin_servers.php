<?php

  if ( OSS_SuperAdmin()  ) {
     $sql = "";
	 
	 $PageTitle = $lang["Servers"]. ' | OpenSteam';
	 //REMOVE SERVER
	 if(isset($_GET["remove"]) AND is_numeric($_GET["remove"]) ) {
	 
	    $sid = (int)$_GET["remove"];
	    $del = $db->prepare("DELETE FROM `".OSSDB_SERVERS."` WHERE `id` = '".$sid."' LIMIT 1; ");
		$result = $del->execute();
		header("location: ".OSS_HOME."?option=admin_servers");
		die();
	 }
	 
     //ADD SERVER
	 if (isset($_POST["server_add"]))  {
	 
	    $server_name = trim( strip_tags($_POST["server_name"]) );
		$server_ip   = trim( strip_tags($_POST["server_ip"]) );
		$server_port = trim( strip_tags($_POST["server_port"]) );
		$server_rcon = trim( strip_tags($_POST["server_rcon"]) );
		
		if( strlen($server_name)<=2 OR strlen($server_ip)<=2 OR empty($server_port) OR !is_numeric($server_port) ) {
		   header("location: ".OSS_HOME."?option=admin_servers&error");
		   die();
		}
		//INSERT/UPDATE
        if(!isset($_GET["edit"])) {
		$sth = $db->prepare("INSERT INTO `".OSSDB_SERVERS."`(server_name, server_ip, server_port, server_rcon) VALUES(:server_name, :server_ip, :server_port, :server_rcon) ON DUPLICATE KEY UPDATE server_name = :server_name2, server_ip = :server_ip2, server_port=:server_port2, server_rcon = :server_rcon2 ");
		
		$sth->bindValue(':server_name',         $server_name,                 PDO::PARAM_STR); 
		$sth->bindValue(':server_ip',           $server_ip,                   PDO::PARAM_STR); 
		$sth->bindValue(':server_port',         $server_port,                 PDO::PARAM_STR); 
		$sth->bindValue(':server_rcon',         $server_rcon,                 PDO::PARAM_STR); 
		$sth->bindValue(':server_name2',        $server_name,                 PDO::PARAM_STR); 
		$sth->bindValue(':server_ip2',          $server_ip,                   PDO::PARAM_STR); 
		$sth->bindValue(':server_port2',        $server_port,                 PDO::PARAM_STR); 
		$sth->bindValue(':server_rcon2',        $server_rcon,                 PDO::PARAM_STR); 
		
		$result = $sth->execute();
		$serverID = $db->lastInsertId(); 
		} else {
		$serverID = (int)$_GET["edit"];
		$sth = $db->prepare("UPDATE `".OSSDB_SERVERS."` SET server_name = :server_name, server_ip = :server_ip, server_port=:server_port, server_rcon = :server_rcon
		WHERE `id` = :serverID ");
		
		$sth->bindValue(':server_name',        $server_name,                 PDO::PARAM_STR); 
		$sth->bindValue(':server_ip',          $server_ip,                   PDO::PARAM_STR); 
		$sth->bindValue(':server_port',        $server_port,                 PDO::PARAM_STR); 
		$sth->bindValue(':server_rcon',        $server_rcon,                 PDO::PARAM_STR); 
		$sth->bindValue(':serverID',           $serverID,                    PDO::PARAM_STR); 
		
		$result = $sth->execute();
		}
		
        if(isset($_GET["add"])) header("location: ".OSS_HOME."?option=admin_servers"); else
	 	header("location: ".OSS_HOME."?option=admin_servers&edit=".$serverID);
	    die();
	 }
	 
	 $EditServer = array();
	 $EditServer["server_name"] = "";
	 $EditServer["server_ip"] = "";
	 $EditServer["server_port"] = "";
	 $EditServer["server_rcon"] = "";
	 $EditServer["button"]      = $lang["AddServer"];
	 //GET ALL SERVERS
	 if( isset($_GET["edit"]) AND is_numeric($_GET["edit"]) ) {
	   $serverID = (int)$_GET["edit"]; 
	   $sql.=" AND `id` = :serverID ";
	   $EditServer["button"]     = $lang["EditServer"];
	 }
	 
	 if( isset($_GET["rcon"]) AND is_numeric($_GET["rcon"]) ) {
	   $serverID = (int)$_GET["rcon"]; 
	   $sql =" AND `id` = :serverID ";
	 }
	 

	 $sth = $db->prepare("SELECT * FROM `".OSSDB_SERVERS."` WHERE `id`>=1 $sql ORDER BY `id` DESC LIMIT 500");
	 
	 if( isset($_GET["edit"]) AND is_numeric($_GET["edit"]) )
	 $sth->bindValue(':serverID',        $serverID,                 PDO::PARAM_INT); 
	 if( isset($_GET["rcon"]) AND is_numeric($_GET["rcon"]) )
	 $sth->bindValue(':serverID',        $serverID,                 PDO::PARAM_INT); 
	 $result = $sth->execute();
	 
	 $ServersData = array();
	 $c = 0;
	 
	 while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
	   $ServersData[$c]["id"]          = $row["id"];
	   $ServersData[$c]["server_name"] = $row["server_name"];
	   $ServersData[$c]["server_ip"]   = $row["server_ip"];
	   $ServersData[$c]["server_port"] = $row["server_port"];
	   $ServersData[$c]["server_rcon"] = $row["server_rcon"];
	   
	   if(empty($ServersData[$c]["server_rcon"]))
	   $ServersData[$c]["remote_control"] = 'disabled="disabled"'; else
	   $ServersData[$c]["remote_control"] = '';
	   
	   if(isset($_GET["edit"])) {
	   $EditServer["server_name"] = $row["server_name"];
	   $EditServer["server_ip"]   = $row["server_ip"];
	   $EditServer["server_port"] = $row["server_port"];
	   $EditServer["server_rcon"] = $row["server_rcon"];
	   }
	   $c++;
	 }
  

  
	 
	 //REMOTE CONTROL
	 if(isset($_GET["com"]) AND isset($ServersData[0]["server_ip"]) ) {
	 
	    $command = trim( $_GET["com"] );
		//$command = str_replace("ulx kick", 'ulx kick "', $command);
		//$command.= '"';
	    require("inc/query/rcon.class.php");
	    $r = new rcon($ServersData[0]["server_ip"],$ServersData[0]["server_port"], $ServersData[0]["server_rcon"]);
        $r->Auth();
        $r->rconCommand($command);
		$command = strip_tags(  $command );
		header("location: ".OSS_HOME."?option=admin_servers&rcon=".$ServersData[0]["id"]."&command=".$command);
		die();
	 }
	 
	 if(isset($_GET["rcon"]) AND isset($_POST["send_command"]) AND isset($ServersData[0]["server_ip"]) ) {
	 
	    require("inc/query/rcon.class.php");
	    $command = trim( $_POST["rcon_command"] );
	    $r = new rcon($ServersData[0]["server_ip"],$ServersData[0]["server_port"], $ServersData[0]["server_rcon"]);
        $r->Auth();
        $r->rconCommand($command);
		
		$command = strip_tags(  $command );
		header("location: ".OSS_HOME."?option=admin_servers&rcon=".$ServersData[0]["id"]."&command=".$command);
		die();
	    
	 }
	 
	 if ( isset($_GET["rcon"]) AND isset($ServersData[0]["server_ip"]) ) {
	    
	   require 'inc/query/SourceQuery/SourceQuery.class.php';
	   define( 'SQ_SERVER_ADDR', $ServersData[0]["server_ip"] );
	   define( 'SQ_SERVER_PORT', $ServersData[0]["server_port"] );
	   define( 'SQ_TIMEOUT',     2 );
	   define( 'SQ_ENGINE',      SourceQuery :: SOURCE );
	   
	   $Query = new SourceQuery( );
	   
	   try {
		$Query->Connect( SQ_SERVER_ADDR, SQ_SERVER_PORT, SQ_TIMEOUT, SQ_ENGINE );
		
		$ServerInfo    = ( $Query->GetInfo( ) );
		$ServerPlayers = ( $Query->GetPlayers( ) );
		
        } catch( Exception $e ) { $ServerError = $e->getMessage( ); }
	
	    $Query->Disconnect( );
		
  }
  
}
?>
