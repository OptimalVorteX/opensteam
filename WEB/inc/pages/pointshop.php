<?php
if (!isset( $cfg["website"] ) ) {header('HTTP/1.1 404 Not Found'); die; } 

    if($cfg["pointshop"] != 1) { header("location: ".OSS_HOME.""); die();  }

     $PageTitle = $lang["PointShop"].' | OpenSteam';
	 
	 $sql = " AND ps.`items`!='[]' "; //show only players with items (leave empty for all players)
     $sql = '';
	 
	 $sth = $db->prepare("SELECT COUNT(*) FROM `".OSSDB_POINTSHOP."` as ps
	 WHERE ps.`items`!='' ".$sql." ORDER BY ps.`points` DESC LIMIT 1");
	 $result = $sth->execute();
	 
	 $r = $sth->fetch(PDO::FETCH_NUM);
	 $numrows = $r[0];
	 $result_per_page = $cfg["players_per_page"];
	 $draw_pagination = 0;
	 include('inc/pagination.php');
	 $draw_pagination = 1;
	 $SHOW_TOTALS = 1;
	 
	 $sth = $db->prepare("SELECT ps.uniqueid, ps.points, ps.items, p.playerName as player, p.steam, p.steamID, p.id
	 FROM `".OSSDB_POINTSHOP."` as ps 
	 LEFT JOIN `".OSSDB_PLAYERS."` as p ON ps.uniqueid = p.uniqueID
	 WHERE ps.`items`!='' ".$sql." ORDER BY ps.`points` DESC LIMIT $offset, $rowsperpage");
	 $result = $sth->execute(); 
	 
     $c=0;
	 
	 $PSData = array();
	 
	 while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
	 
	 $PSData[$c]["player"]         = htmlspecialchars_decode(PlayerNameSpecChar($row["player"]));
	 $PSData[$c]["steam"]          = ($row["steam"]);
	 $PSData[$c]["steamID"]        = ($row["steamID"]);
	 $PSData[$c]["id"]             = ($row["id"]);
	 if(empty($row["player"]))     $PSData[$c]["player"] = $row["uniqueid"];
	 $PSData[$c]["uniqueid"]       = ($row["uniqueid"]);
	 $PSData[$c]["points"]         = ($row["points"]);
	 $PSData[$c]["items"]          = json_decode($row["items"], true);
	 $PSData[$c]["num"]            = ($c+1);
	  
	 $c++;
	 }
?>