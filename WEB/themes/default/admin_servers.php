<?php if (!isset( $cfg["website"] ) ) {header('HTTP/1.1 404 Not Found'); die; } ?>
<div class="well bs-component">
<?php if(isset($_GET["error"])) { ?>
         <div class="alert alert-dismissable alert-danger">
		   <button type="button" class="close" data-dismiss="alert">&times;</button>
            <?=$lang["FieldNotCompleted"]?>
         </div>
<?php } ?>
<?php if(isset($_GET["add"]) OR isset($_GET["edit"]) ) { ?>
   <form action="" method="post">
     <table class="table table-striped table-hover ">
	  <tr>
		  <td width="150"><b><?=$lang["ServerName"]?></b></td>
		  <td><input type="text" value="<?=$EditServer["server_name"] ?>" name="server_name" size="60" required /></td>
	  </tr>
	  <tr>
		  <td  width="150"><b><?=$lang["IPAddress"]?></b></td>
		  <td>
		  <input type="text" value="<?=$EditServer["server_ip"] ?>" name="server_ip" size="16" required /> : 
		  <input type="text" value="<?=$EditServer["server_port"] ?>" name="server_port" size="6" required />
		  </td>
	  </tr>
	  <tr>
		  <td  width="150"><b><?=$lang["RCONpassword"]?>:</b></td>
		  <td><input type="text" value="<?=$EditServer["server_rcon"] ?>" name="server_rcon" size="30" /></td>
	  </tr>
	  <tr>
		  <td  width="150"></td>
		  <td>
		    <input type="submit" value="<?=$EditServer["button"]?>" name="server_add" class="btn btn-primary" />
		    <a href="<?=OSS_HOME?>?option=admin_servers" class="btn btn-danger"><?=$lang["Cancel"]?></a>
		  </td>
	  </tr>
	 </table>
   </form>
<?php } else { ?>
   
<h2><?=$lang["Servers"]?> <a href="<?=OSS_HOME?>?option=admin_servers&amp;add" class="btn btn-primary"><?=$lang["AddServer"]?></a></h2>
<table class="table table-striped table-hover ">
  <thead>
    <tr>
      <th><?=$lang["Server"]?></th>
	  <th><?=$lang["IP_Port"]?></th>
	  <th><?=$lang["Option"]?></th>
    </tr>
  </thead>
  <tbody>
  <?php
  if(!empty($ServersData)) foreach($ServersData as $server) {
  ?>
   <tr>
      <td width="320"><a class="btn btn-info btn-xs" href="<?=OSS_HOME?>?option=admin_servers&amp;rcon=<?=$server["id"]?>"><span><?=$server["server_name"]?></span></a></td>
	  <td width="220"><a href="javascript:;" onclick="ToClipboard('<?=$server["server_ip"]?>:<?=$server["server_port"]?>')"><?=$server["server_ip"]?>:<?=$server["server_port"]?></a></td>
	  <td> 
	    <a href="<?=OSS_HOME?>?option=admin_servers&amp;edit=<?=$server["id"]?>" class="btn btn-info btn-xs"><?=$lang["Edit"]?></a>
		<a onclick="if(confirm('Delete Server?')) { location.href='<?=OSS_HOME?>?option=admin_servers&amp;remove=<?=$server["id"]?>' } " href="javascript:;" class="btn btn-danger btn-xs"><?=$lang["Remove"]?></a>
	  </td>
   </tr>
  <?php } ?>
  </tbody>
</table> 
<?php } ?>

</div>

<?php if(isset($_GET["rcon"]) AND is_numeric($_GET["rcon"]) ) { ?>
<div class="well bs-component">
   <h4><?=$lang["ServerRemoteControl"]?></h4>
   
   <form action="" method="post">
	     <input type="text" value="" name="rcon_command" size="65" <?=$ServersData[0]["remote_control"]?> />
         <input type="submit" value="<?=$lang["SendCommand"]?>" name="send_command"  <?=$ServersData[0]["remote_control"]?> />
		 <a href="<?=OSS_HOME?>?option=admin_servers" class="btn btn-danger btn-xs">&laquo; <?=$lang["Back"] ?></a>
   </form>
   
   <?php if(isset($_GET["command"])) { ?>
   <div class="alert alert-dismissable alert-warning">
     <button type="button" class="close" data-dismiss="alert">&times;</button>
     <h4><?=$lang["CommandExecuted"] ?></h4>
     <?=$_GET["command"]?>
   </div>
   <?php } ?>
</div>


<div class="well bs-component">
   <h4><?=$lang["ServerInfo"]?></h4>
   
   <?php if(isset($ServerError)) { ?>
   <div class="alert alert-dismissable alert-danger">
     <button type="button" class="close" data-dismiss="alert">&times;</button>
     <h4><?=$lang["CouldNotConnect"]?></h4>
   </div>
   <?php } ?>
   <table class="table table-striped table-hover ">
   <tr>
     <th>#</th>
	 <th><?=$lang["Player"]?></th>
	 <th><?=$lang["Frags"]?></th>
	 <th><?=$lang["Time"]?></th>
	 <th><?=$lang["Option"]?></th>
   </tr>
   <?php
   $c = 0;
   if (!empty($ServerPlayers)) foreach ($ServerPlayers as $player) { 
   $c++; ?>
    <tr> 
		<td width="24"><?=$c?></td>
		<td width="220"><a href="<?=OSS_HOME?>?search=<?=$player["Name"]?>" target="_blank"><?=$player["Name"]?></a></td>
		<td width="45" style="text-align:center;"><?=$player["Frags"]?></td>
		<td width="100"><?=$player["TimeF"]?></td>
		<td>
		<?php if(!empty($player["Name"])) { ?>
		<a onclick="if(confirm('Kick [<?=$player["Name"]?>]')) { location.href='<?=OSS_HOME?>?option=admin_servers&amp;rcon=<?=$ServersData[0]["id"]?>&amp;com=ulx kick <?=$player["Name"]?>' }" href="javascript:;" class="btn btn-danger btn-xs"><?=$lang["kick"] ?></a>
		<?php } ?>
		</td>
	</tr>
   <?php } ?>
   </table>
</div>

<?php } ?>