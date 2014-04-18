<?php if (!isset( $cfg["website"] ) ) {header('HTTP/1.1 404 Not Found'); die; } ?>
<table class="table table-striped table-hover ">
  <thead>
    <tr>
      <th><?=$lang["Player"]?></th>
	  <th><?=$lang["SteamID"]?></th>
      <th><?=$lang["Banned_By"]?></th>
	  <th><?=$lang["Expire"]?></th>
    </tr>
  </thead>
  <tbody>
  <?php
  if(!empty($BansData))
  foreach ( $BansData as $Player ) {
  ?>
    <tr>
      <td width="180"><a class="text-danger" target="_blank" href="http://steamcommunity.com/profiles/<?=ConvertToSteam64($Player["steam"])?>/"><?=$Player["name"]?></a>
	  <?=OSS_EditUser($Player["steam"]) ?>
	  </td>
      <td width="200"><?=($Player["steam"])?></td>
      <td width="180"><?=$Player["admin"]?></td>
	  <td><?=OB_ExpireDateRemain($Player["expire_date"])?></td>
    </tr>
   <?php } ?>
  </tbody>
</table> 

<?php
 include("inc/pagination.php");
?>