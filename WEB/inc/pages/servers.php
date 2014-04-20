<?php

     $sql = "";
	 
	 $PageTitle = $lang["Servers"]. ' | OpenSteam';
	 
	 $ServersData = array();
	 $ServersData["server_name"] = "";
	 $ServersData["server_ip"] = "";
	 $ServersData["server_port"] = "";
	 $ServersData["server_rcon"] = "";
	 
	 if(isset($_GET["id"]) AND is_numeric($_GET["id"]) ) {
	   $id = (int)$_GET["id"];
	   $sql.=" AND `id` = '".$id."' ";
	 }
	 
	 $sth = $db->prepare("SELECT * FROM `".OSSDB_SERVERS."` WHERE `id`>=1 $sql ORDER BY `id` DESC LIMIT 500");
	 $result = $sth->execute();
	 
	 $ServersData = array();
	 $c = 0;
	 
	 while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
	   $ServersData[$c]["id"]          = $row["id"];
	   $ServersData[$c]["server_name"] = $row["server_name"];
	   $ServersData[$c]["server_ip"]   = $row["server_ip"];
	   $ServersData[$c]["server_port"] = $row["server_port"];
	   $ServersData[$c]["server_rcon"] = $row["server_rcon"];
	   $c++;
	 }
	 
	 //Connect to SERVER
	 if(isset($_GET["id"]) AND is_numeric($_GET["id"]) AND isset($ServersData[0]["server_ip"]) ) {
	 
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
		$ServerVars = array("HostName", "Map", "ModDir", "ModDesc", "Players", "MaxPlayers",  "Version", "GamePort",);
		$c=0;
		
		$ServerHostName = ""; $ServerMap = ""; $ServerModDesc = "";
		$ServerTotalPlayers = ""; $ServerMaxPlayers = ""; $ServerVersion = "";  $ServerGamePort = "";
		
		if(!empty($ServerInfo)) foreach ( $ServerInfo as $info=>$v) {
		  //if (in_array($info, $ServerVars)) {
		     if( $info == "HostName" )   $ServerHostName = $v; 
			 if( $info == "Map" )        $ServerMap = $v;
			 if( $info == "ModDesc" )    $ServerModDesc = $v;
			 if( $info == "Players" )    $ServerTotalPlayers = $v;
			 if( $info == "MaxPlayers" ) $ServerMaxPlayers = $v;
			 if( $info == "Version" )    $ServerVersion = $v;
			 if( $info == "GamePort" )   $ServerGamePort = $v;
		  //} 
		
		}
		
        } catch( Exception $e ) { $ServerError = $e->getMessage( ); }
	
	    $Query->Disconnect( );
	 
	 }
?>