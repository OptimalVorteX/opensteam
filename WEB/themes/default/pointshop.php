<?php if (!isset( $cfg["website"] ) ) {header('HTTP/1.1 404 Not Found'); die; } ?>

<table class="table table-striped table-hover ">
  <thead>
    <tr>
      <th><?=$lang["Player"]?></th>
	  <th><?=$lang["Items"]?></th>
	  <th><?=$lang["Points"]?></th>
      <th></th>

    </tr>
  </thead>
  <tbody>
  <?php
  if(!empty($PSData))  foreach ( $PSData as $ps ) {
  ?> 
  <tr>
    <td width="180">
	<?php if(!empty($ps["id"])) { ?>
	<a href="<?=OSS_HOME?>?option=player&amp;id=<?=$ps["id"]?>"><?=$ps["player"]?></a>
	<?php } else { ?>
	<?=$ps["player"]?>
	<?php } ?>
	</td>
	<td width="500">
	<span class="ps_equipped">
	<?php 
	foreach ($ps["items"] as $item=>$val ) {
	    if( $val["Equipped"] == true ) {
		
		if(file_exists("img/pointshop/".$item.".png")) {
		?><img src="<?=OSS_HOME?>img/pointshop/<?=$item?>.png" width="48" height="48" /><?php
		} else {
		?>
		<span><?=$item?></span>
		<?php
		}
		}
	}
	?></span><?php
	foreach ($ps["items"] as $item=>$val ) {
	    if( $val["Equipped"] == false ) {
		
		if(file_exists("img/pointshop/".$item.".png")) {
		?><img src="<?=OSS_HOME?>img/pointshop/<?=$item?>.png" width="48" height="48" /><?php
		} else {
		?>
		  <span><?=$item?></span>
		<?php
		}
		}
	}
	?></td>
	<td><?=number_format($ps["points"])?></td>
	<td></td>
  </tr>
  <?php } ?>
  </tbody>
</table> 

<?php include("inc/pagination.php"); ?>