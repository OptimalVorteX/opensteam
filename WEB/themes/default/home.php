<?php if (!isset( $cfg["website"] ) ) {header('HTTP/1.1 404 Not Found'); die; } ?>

<?php /* 
<!-- // PLACE YOUR BANNER HERE
*/ ?>

<table class="table table-striped table-hover ">
   <thead>
      <tr>
         <th><a href="<?=OSS_HOME?>">#</a></th>
         <th><a href="<?=OSS_HOME?>?sort=player"><?=$lang["Player"]?></a></th>
         <th><?=$lang["Location"]?></th>
         <th><a href="<?=OSS_HOME?>?sort=last_seen"><?=$lang["Last_Seen"]?></a></th>
         <th></th>
      </tr>
   </thead>
   <tbody>
      <?php
         if(!empty($PlayersData))
         foreach ( $PlayersData as $Player ) {
         ?>
      <tr <?=$Player["class"]?>>
         <td width="70"><?=OSS_SteamAvatar( $Player["avatar_medium"] )?></td>
         <td width="250">
		 
		 <a class="text-info" href="<?=OSS_HOME?>?option=player&amp;id=<?=$Player["id"]?>">
		   <?=$Player["playerName"]?>
		</a>
		 
		 <a class="text-info" title="View Steam Community Profile" href="http://steamcommunity.com/profiles/<?=$Player["steamID"]?>/" target="_blank">
		   <img src="<?=OSS_HOME?>img/steam.png" alt="steam" width="24" height="24" class="floatR" />
		 </a>
            <?=OSS_EditUser($Player["id"]) ?>
         </td>
         <td width="200">
            <!--<?=$Player["location"]?> -->
            <?=OSS_CountryFlag($Player["letter"], $Player["country"]) ?>
         </td>
         <td><?=$Player["last_connection"]?></td>
         <td>
            <?php if( OSS_IsAdmin( $Player["rank"] ) ) { ?>
            <div class="label label-primary"><?=$Player["rank"] ?></div>
            <?php } ?>
            <?php if( OSS_IsBanned( $Player["expire_date"] ) ) { ?>
            <div class="label label-danger"><?=$lang["Banned"] ?></div>
            <div><?=OB_ExpireDateRemain($Player["expire_date"])?></div>
            <?php } ?>
         </td>
      </tr>
      <?php } ?>
   </tbody>
</table>
<?php include("inc/pagination.php"); ?>