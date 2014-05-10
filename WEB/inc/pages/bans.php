<?php

    if (!isset( $cfg["website"] ) ) {header('HTTP/1.1 404 Not Found'); die; }
    
	$PageTitle = $lang["Bans"].' | OpenSteam';
	
    $sql = "";
	
    //Remove ban
	if(isset($_GET["rban"]) AND is_numeric($_GET["rban"])) {
	
	$banid = $_GET["rban"];
	$sth = $db->prepare( "DELETE FROM ".OSSDB_BANS." WHERE id=:id LIMIT 1" );
	$sth->bindValue(':id', $banid, PDO::PARAM_INT); 
	$result = $sth->execute();
	if(isset($_GET["page"]) AND is_numeric($_GET["page"]) ) $page = "&page=".(int)$_GET["page"];
    else $page = "";
	header("location: ".OSS_HOME."?option=bans".$page."&success" );
	die();
	}
	
	if ( isset($_GET["search"]) AND strlen($_GET["search"])>=2 ) {
	
	  $search = strip_tags( trim($_GET["search"]) );
	  
	  $sql.=" AND b.name LIKE :search ";
	}
	
	$sql.=" AND b.expire>NOW() OR b.expire = '0000-00-00 00:00:00' ";
	
    $sth = $db->prepare("SELECT COUNT(*) FROM ".OSSDB_BANS." as b WHERE b.id>=1 $sql LIMIT 1");
	if ( isset($_GET["search"]) AND strlen($_GET["search"])>=2 ) 
	$sth->bindValue(':search',           "%".$search."%",        PDO::PARAM_STR); 
    $result = $sth->execute();
	
	 $r = $sth->fetch(PDO::FETCH_NUM);
	 $numrows = $r[0];
	 $result_per_page = $cfg["players_per_page"];
	 $draw_pagination = 0;
	 include('inc/pagination.php');
	 $draw_pagination = 1;
	 $SHOW_TOTALS = 1;
	 
	 $orderby  = " id DESC, expire ASC";
	 
	 if(isset($_GET["sort"])) {
	 
	   if($_GET["sort"] == "expire")    $orderby  = " expire DESC";
	   if($_GET["sort"] == "id")        $orderby  = " id ASC";
	   if($_GET["sort"] == "name")      $orderby  = " LOWER(name) ASC";
	   if($_GET["sort"] == "admin")     $orderby  = " LOWER(admin) ASC";
	 }
	 
	 $sth = $db->prepare("SELECT b.id, b.steam, b.name, b.admin, b.bantime, b.expire, b.reason
	 FROM ".OSSDB_BANS." as b 
	 WHERE b.id>=1 $sql 
	 ORDER BY $orderby 
	 LIMIT $offset, $rowsperpage");
	 $result = $sth->execute();
	 
	 $c=0;
	 
	 $BansData = array();
	 
	 while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
	 
	 $BansData[$c]["id"]       = ($row["id"]);
	 $BansData[$c]["steam"]    = ($row["steam"]);
	 $BansData[$c]["name"]     = ($row["name"]);
	 $BansData[$c]["admin"]    = ($row["admin"]);
	 $BansData[$c]["reason"]   = ($row["reason"]);
	 $BansData[$c]["bantime"]  = date(OSS_DATE_FORMAT, strtotime($row["bantime"]) );
	 $BansData[$c]["expire"]   = date(OSS_DATE_FORMAT, strtotime($row["expire"]) );
	 $BansData[$c]["expire_date"]   = ( $row["expire"] ) ;
	 
	 $BansData[$c]["num"]  = ($c+1);
	  
	 $c++;
	 }
	 
?>