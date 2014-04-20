<?php if (!isset( $cfg["website"] ) ) {header('HTTP/1.1 404 Not Found'); die; } ?>

<?php /* 
<!-- // PLACE YOUR BANNER HERE

You can use the following code to cache images : 
<?=OSS_HOME?>inc/getImg.php?img=HTTP://LINK_TO_IMAGE.png

Example:
<a href="http://www.gametracker.com/server_info/212.224.126.68:27035/" target="_blank"><img src="<?=OSS_HOME?>inc/getImg.php?img=http://cache.www.gametracker.com/server_info/212.224.126.68:27035/b_560_95_1.png" border="0" width="460" height="80" alt=""></a>
<a href="http://www.gametracker.com/server_info/212.224.126.68:27036/" target="_blank"><img src="<?=OSS_HOME?>inc/getImg.php?img=http://cache.www.gametracker.com/server_info/212.224.126.68:27036/b_560_95_1.png" border="0" width="460" height="80" alt=""></a>

-->
<?php */ ?>

<table class="table table-striped table-hover ">
   <thead>
      <tr>
         <th><a href="<?=OSS_HOME?>">#</a></th>
         <th><a href="<?=OSS_HOME?>?sort=player"><?=$lang["Player"]?></a></th>
         <th>ID</th>
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
         <td width="200">
            <a class="text-info" href="<?=OSS_HOME?>?option=player&amp;id=<?=$Player["id"]?>">
              <?php echo wordwrap($Player["playerName"], 25, "<br>" ) ?>
            </a>
          </td>
          <td style="width: 30px">
            <a class="text-info" title="View Steam Community Profile" href="http://steamcommunity.com/profiles/<?=$Player["steamID"]?>/" target="_blank">
              <img src="<?=OSS_HOME?>img/steam.png" alt="steam" width="24" height="24" class="floatR" />
            </a>
            <div style="margin-top: 30px;">
              <?=OSS_EditUser($Player["id"]) ?>
            </div>
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