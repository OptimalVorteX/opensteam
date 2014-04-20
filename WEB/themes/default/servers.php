<?php if (!isset( $cfg["website"] ) ) {header('HTTP/1.1 404 Not Found'); die; } ?>

<?php if(!isset($_GET["id"])) { ?>
<table class="table table-striped table-hover ">
  <thead>
    <tr>
	  <th>Server Name</th>
      <th>IP:PORT</th>
    </tr>
  </thead>
  <tbody>
  <?php
  if(!empty($ServersData))
  foreach ( $ServersData as $server ) {
  ?>
    <tr>
	  <td width="290">
	  <a href="<?=OSS_HOME?>?option=servers&amp;id=<?=($server["id"])?>">
	   <?=$server["server_name"]?>
	  </a>
	   </td>
      <td>
		<a class="text-danger" onclick="ToClipboard('<?=$server["server_ip"]?>:<?=$server["server_port"]?>')" href="javascript:;"><?=$server["server_ip"]?>:<?=$server["server_port"]?></a>
	  <?=OSS_EditServer($server["id"]) ?>
	  </td>
    </tr>
   <?php } ?>
  </tbody>
</table> 
<?php } ?>

<?php if( isset($_GET["id"]) AND isset($ServersData[0]["server_ip"])) { ?>
     <h2><?=$ServersData[0]["server_name"]?></h2>
	 <div style="float:left; width:55%">
     <table class="table table-striped table-hover">
	  <?php 
	  $c=0;
	  if(!empty($ServerPlayers)) foreach( $ServerPlayers as $player) { 
	  $c++;
	  ?>
	   <tr>
		 <td width="24"><?=$c?></td>
		 <td width="220">
		    <a target="_blank" href="<?=OSS_HOME?>?search=<?=$player["Name"]?>"><?=$player["Name"]?></a>
		 </td>
		 <td><?=$player["Frags"]?></td>
		 <td><?=$player["TimeF"]?></td>
	   </tr>
	 <?php } ?>
	 </table>
	 </div>
	 
	 <div style="float:left; width:39%; margin-left:14px;">
	 <table class="table table-striped table-hover">
	   <tr>
	     <td></td>
	     <td>Server</td>
	   </tr>
	   <?php if(!empty($ServerHostName) ) { ?>
	   <tr>
	     <td width="100"><strong>Host:</strong></td>
	     <td><?=$ServerHostName?></td>
	   </tr>
	   <tr>
	     <td width="100"><strong>Map:</strong></td>
	     <td><?=$ServerMap?></td>
	   </tr>
	   <tr>
	     <td width="100"><strong>Players:</strong></td>
	     <td><?=$ServerTotalPlayers?>/<?=$ServerMaxPlayers?></td>
	   </tr>
	   <tr>
	     <td width="100"><strong>MOD:</strong></td>
	     <td><?=$ServerModDesc?></td>
	   </tr>
	   <tr>
	     <td width="100"><strong>Version:</strong></td>
	     <td><?=$ServerVersion?></td>
	   </tr>
	   <tr>
	     <td width="100"><strong>GamePort:</strong></td>
	     <td><?=$ServerGamePort?></td>
	   </tr>
	   <?php } ?>
	   <tr>
	     <td width="100"></td>
	     <td><a href="<?=OSS_HOME?>?option=servers" class="btn btn-primary">&laquo; <?=$lang["Back"]?></a></td>
	   </tr>
	 </table>
	 </div>

<?php } ?>

<div style="margin-top:280px;">&nbsp;</div>