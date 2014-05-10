<?php
  
  if (!isset( $cfg["website"] ) ) {header('HTTP/1.1 404 Not Found'); die; }
  
  if ( OSS_SuperAdmin() )  {
  
  $PageTitle = $lang["UserGroups"].' | OpenSteam';
  
  $sql = "";
  
  //Save Commands
  if(isset($_GET["commands"])) {
    
	if(isset($_POST["save_commands"]) AND is_writable("inc/commands.php") ) {
	   $CommandList = $_POST["all_commands"];
	   $ch = explode("\n", $CommandList);
	   $str="";
	   foreach($ch as $com) {
	     $com = trim($com);
	     if(!empty($com) AND strlen($com)>=2 ) $str.=$com."\n";
	   }
	   $str = substr($str, 0, strlen($str)-1 );
	   if(!empty($com)) file_put_contents( "inc/commands.php", $str );
		 header("location: ".OSS_HOME."?option=admin_groups&commands");
	     die();
	}
	
    $AllCommands = file_get_contents("inc/commands.php");
	$TotalCommands = count( explode("\n", $AllCommands) );
	if(!is_writable("inc/commands.php")) $CommandsMessage = "File <strong>commands.php</strong> is not writable";
  }
  
  //REMOVE GROUP
  if(isset($_GET["remove"]) AND !empty($_GET["remove"]) AND $_GET["remove"]!="superadmin") {
  
     $del = trim($_GET["remove"]);
     $sth = $db->prepare("DELETE FROM ".OSSDB_GROUPS." WHERE `group` =:name LIMIT 1 ");
	 $sth->bindValue(':name', $del, PDO::PARAM_STR); 
	 $result = $sth->execute();
	 
	 if($del!='user' AND $del!='superadmin') {
	   $upd = $db->prepare("UPDATE ".OSSDB_PLAYERS." SET `rank` = 'user' WHERE `rank` = :del  ");
	   $upd->bindValue(':del',        $del,    PDO::PARAM_STR); 
	   $result = $upd->execute();
	 }
	 
	 header("location: ".OSS_HOME."?option=admin_groups");
	 die();
  }
  
  if(isset($_GET["remove"]) AND !empty($_GET["remove"]) AND $_GET["remove"]=="superadmin") {
  	 header("location: ".OSS_HOME."?option=admin_groups");
	 die();
  }

  
  //UPDATE GROUP NAME
  if( isset($_GET["edit"]) AND isset($_POST["change_group_name"]) AND !empty($_POST["group"]) ) { 
  
     $NewName = trim(strip_tags($_POST["group"]));
	 $OldName = trim(strip_tags($_POST["old_group"]));
	 //Check if group already exists...
	   $sth = $db->prepare("SELECT * FROM ".OSSDB_GROUPS." WHERE `group`=:NewName");
	   $sth->bindValue(':NewName',        $NewName,    PDO::PARAM_STR); 
	   $result = $sth->execute();

	   if ( $sth->rowCount()>=1 OR empty($NewName) ) {
	      header("location: ".OSS_HOME."?option=admin_groups&edit=".$_GET["edit"]);
		  die();
	   }
	 //Do not change superadmin  
	 if($OldName!="superadmin") {
	 
	 $upd = $db->prepare("UPDATE ".OSSDB_PLAYERS." SET `rank` = :NewName WHERE `rank` = :OldName  ");
	 $upd->bindValue(':NewName',        $NewName,    PDO::PARAM_STR); 
	 $upd->bindValue(':OldName',        $OldName,    PDO::PARAM_STR); 
	 $result = $upd->execute();
	 
	 $upd2 = $db->prepare("UPDATE ".OSSDB_GROUPS." SET `group` = :NewName WHERE `group` = :OldName  ");
	 $upd2->bindValue(':NewName',        $NewName,    PDO::PARAM_STR); 
	 $upd2->bindValue(':OldName',        $OldName,    PDO::PARAM_STR); 
	 $result = $upd2->execute();
	 } else $NewName = "superadmin";
	 
	  header("location: ".OSS_HOME."?option=admin_groups&edit=".$NewName);
	  die();
  }
     
	 
	 if(isset($_GET["edit"]) ) { 
	 
	 $GroupName = strtolower(trim( strip_tags( $_GET["edit"] ) ));
	 $sql.= " AND `group` = :GroupName ";
	 
	 }
	 

  	 $sth = $db->prepare("SELECT * FROM ".OSSDB_GROUPS." WHERE `group`!='' $sql 
	 ORDER BY FIELD(`group`, 'superadmin') DESC, `group` ASC
	 LIMIT 500");
	 if(isset($_GET["edit"]) AND !empty($GroupName) )
	 $sth->bindValue(':GroupName',        $GroupName,    PDO::PARAM_STR); 
	 $result = $sth->execute();
	 
	 $GroupsData = array();
	 $c = 0;
	 while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
	 
	   $GroupsData[$c]["group"]    = $row["group"];
	   $GroupsData[$c]["commands"] = $row["commands"];
	   $GroupsData[$c]["commands_short"] = substr($row["commands"], 0, 70);
	   $GroupsData[$c]["denies"]   = $row["denies"];
	   $GroupsData[$c]["denies_short"] = substr($row["denies"], 0, 70);
	   $GroupsData[$c]["root"]    = 0;
	   $GroupsData[$c]["class"]   = "success";
	   $GroupsData[$c]["sel"]     = "";
		  
	   if($row["group"] == "superadmin") {
	   
	      $GroupsData[$c]["root"]    = 1;
	      $GroupsData[$c]["class"]   = "danger";
		  $GroupsData[$c]["sel"]     = 'disabled="disabled"';
	   }
	   
	   $c++;
	 }
	 
	 //GROUP Commands
	 if(isset($_GET["edit"]) OR isset($_GET["add"]) ) {
	 
	 if(isset($_GET["add"]) ) {
	   $GroupsData[0]["group"]    = "NewGroup";
	   $GroupsData[0]["commands"] = "";
	   $GroupsData[0]["denies"]   = "";
	   $GroupsData[0]["root"]    = 0;
	   $GroupsData[0]["class"]   = "success";
	   $GroupsData[0]["sel"]     = "";
	 }
	 
	 if ( isset($_POST["save_group"]) AND (!empty($GroupName) OR isset($_GET["add"]) ) ) {
	 //Check if group already exists, so we won't rewrite it
	 if(isset($_GET["add"]) ) {
	   $GroupName = strtolower(trim($_POST["group"]));
	   $sth = $db->prepare("SELECT * FROM ".OSSDB_GROUPS." WHERE `group`=:GroupName");
	   $sth->bindValue(':GroupName',        $GroupName,    PDO::PARAM_STR); 
	   $result = $sth->execute();
	   
	   if ( $sth->rowCount()>=1 OR empty($GroupName) ) {
	      header("location: ".OSS_HOME."?option=admin_groups&add=error");
		  die();
	   }
	   
	   if ( (isset($GroupName) AND $GroupName == "superadmin") OR (isset($_GET["edit"]) AND $_GET["edit"] == "superadmin") ) {
	      header("location: ".OSS_HOME."?option=admin_groups");
		  die();
	   }
	   
	 }
	 
		 $AllowedCommands    = "";
		 $DissalowedCommands = "";
		 
		 if(!empty($_POST["commands"]))
		 foreach( $_POST["commands"] as $command=>$v ) 
		    if(!empty($command))$AllowedCommands.=trim($command)."\n";
 
 
        $AllowedCommands = substr($AllowedCommands, 0, strlen($AllowedCommands)-1 );
		
		 if(!empty($_POST["denied"]))		
         foreach( $_POST["denied"] as $cdenied=>$v ) 
		    if(!empty($cdenied))$DissalowedCommands.=trim($cdenied)."\n";
			
		$DissalowedCommands = substr($DissalowedCommands, 0, strlen($DissalowedCommands)-1 );
		
		$ins = $db->prepare("INSERT INTO `".OSSDB_GROUPS."` (`group`, `commands`, `denies`) VALUES(:GroupName, :AllowedCommands, :DissalowedCommands) ON DUPLICATE KEY UPDATE commands = :AllowedCommands2, denies = :DissalowedCommands ");
		
		$ins->bindValue(':GroupName',               $GroupName,                 PDO::PARAM_STR); 
		$ins->bindValue(':AllowedCommands',         $AllowedCommands,           PDO::PARAM_STR); 
		$ins->bindValue(':DissalowedCommands',      $DissalowedCommands,        PDO::PARAM_STR); 
		$ins->bindValue(':AllowedCommands2',        $AllowedCommands,           PDO::PARAM_STR); 
		$ins->bindValue(':DissalowedCommands',      $DissalowedCommands,        PDO::PARAM_STR); 
			
		 //$upd = $db->prepare("UPDATE ".OSSDB_GROUPS." SET 
		 //commands = '".$AllowedCommands."', denies = '".$DissalowedCommands."' WHERE `group` = '".$GroupName."' ");
		 $result = $ins->execute();
		 
		 if(isset($_GET["add"])) header("location: ".OSS_HOME."?option=admin_groups");
		 else
		 header("location: ".OSS_HOME."?option=admin_groups&edit=".$GroupName);
		 die();
	 }
	 
	  if (file_exists("inc/commands.php")) $cmds = file_get_contents( "inc/commands.php" );
	  else $cmds = "";
	  
	  $AllCommands    = explode("\n", $cmds);
	  $SelCommands    = explode("\n", $GroupsData[0]["commands"]);
	  $DeniedCommands = explode("\n", $GroupsData[0]["denies"]);
	  sort($AllCommands);
	  
	  $GroupCommand = array();
	  $GroupDeniedCommand = array();
	  $c = 0;
	  foreach( $AllCommands as $com ) {
	  
	   $GroupCommand[$c]["command"] = trim($com);
	   $GroupCommand[$c]["num"] = $c;
	   $GroupCommand[$c]["allowed"] = 1;
	   $GroupCommand[$c]["check"]   = '';
	   $GroupCommand[$c]["colour"]  = 'com_deselected';
	   foreach($SelCommands as $s ) {
		  $s = trim($s);
		  if( $GroupCommand[$c]["command"] == $s) {
		    $GroupCommand[$c]["allowed"] = 1;
		    $GroupCommand[$c]["check"]   = 'checked';
			$GroupCommand[$c]["colour"]  = 'com_selected';
		    }

	   }

	  
	  $c++;
	 }
	 
	  $c = 0;
	  foreach( $AllCommands as $dcom ) {
	  
	   $GroupDeniedCommand[$c]["command"] = trim($dcom);
	   $GroupDeniedCommand[$c]["num"] = $c;
	   $GroupDeniedCommand[$c]["allowed"] = 1;
	   $GroupDeniedCommand[$c]["check"]   = '';
	   $GroupDeniedCommand[$c]["colour"]  = 'com_deselected';
	   foreach($DeniedCommands as $d ) {
		  $d = trim($d);
		  if(
		    $GroupDeniedCommand[$c]["command"] == $d) {
		    $GroupDeniedCommand[$c]["allowed"] = 1;
		    $GroupDeniedCommand[$c]["check"]   = 'checked';
			$GroupDeniedCommand[$c]["colour"]  = 'com_selected';
		    }

	   }

	  $c++;
	 }
	 
  }
  
}
?>