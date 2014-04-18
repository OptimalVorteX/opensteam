<?php if (!isset( $cfg["website"] ) ) {header('HTTP/1.1 404 Not Found'); die; } ?>
<table class="table table-striped table-hover ">
  <thead>
    <tr>
      <th><?=$lang["Player"]?></th>
	  <th><?=$lang["Expire"]?></th>
	  <th><?=$lang["Reason"]?></th>
      <th><?=$lang["Banned_By"]?></th>

    </tr>
  </thead>
  <tbody>
  <?php
  if(!empty($BansData))
  foreach ( $BansData as $Player ) {
  ?>
    <tr>
      <td width="260">
	  
		<a class="text-danger" href="<?=OSS_HOME?>?option=player&amp;id=<?=($Player["steam"])?>">
		   <?=$Player["name"]?>
		</a>
	  
	  <?=OSS_EditUser($Player["steam"]) ?>
	  </td>
	  <td width="190"><?=OB_ExpireDateRemain($Player["expire_date"])?></td>
	  <td width="200"><?=$Player["reason"]?></a></td>
	  <td><?=$Player["admin"]?></td>
    </tr>
   <?php } ?>
  </tbody>
</table> 

<?php
 include("inc/pagination.php");
?>