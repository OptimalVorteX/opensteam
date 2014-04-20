<?php if (!isset( $cfg["website"] ) ) {header('HTTP/1.1 404 Not Found'); die; } ?>
<div class="well bs-component">
<?php if(!empty($PlayerInfo["playerName"])) { ?>
   
<legend><?=OSS_SteamAvatar($PlayerInfo["avatar"], 184, 184)?> <?=$PlayerInfo["playerName"]?>
<?php if( OSS_IsAdmin( $PlayerInfo["rank"] ) ) { ?>
            <div class="label label-primary"><?=$PlayerInfo["rank"] ?></div>
            <?php } ?>
</legend>

<?php if(!empty($PlayerInfo["expire"])) { ?>
         <div class="alert alert-dismissable alert-danger">
            <div><strong><?=$lang["Banned"]?>: </strong> <?=$PlayerInfo["bantime"]?></div>
            <div><?=OB_ExpireDateRemain($PlayerInfo["expire_date"])?></div>
         </div>
<?php } ?>

<table class="table table-striped table-hover ">
     <tr>
	   <td width="140"><strong><?=$lang["Steam"] ?>:</strong></td>
	   <td><?=$PlayerInfo["steam"]?> </td>
	 </tr>
     <tr>
	   <td width="140"><strong><?=$lang["CommunityID"]?>:</strong></td>
	   <td>
	   <img src="<?=OSS_HOME?>img/steam.png" alt="steam" width="32" height="32" />
	   <a target="_blank" href="http://steamcommunity.com/profiles/<?=$PlayerInfo["steamID"]?>/"><?=$PlayerInfo["steamID"]?></a> 
	   </td>
	 </tr>
     <tr>
	   <td width="140"><strong><?=$lang["Location"]?>:</strong></td>
	   <td> <?=OSS_CountryFlag($PlayerInfo["letter"], $PlayerInfo["country"]) ?></td>
	 </tr>
     <tr>
	   <td width="140"><strong><?=$lang["UserGroup"]?>:</strong></td>
	   <td><?=$PlayerInfo["rank"]?></td>
	 </tr>
     <tr>
	   <td width="140"><strong><?=$lang["Connections"] ?>:</strong></td>
	   <td><?=$PlayerInfo["connections"]?></td>
	 </tr>
	 
 
</table>
  

<?php } ?>
</div>