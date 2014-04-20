<?php if (!isset( $cfg["website"] ) ) {header('HTTP/1.1 404 Not Found'); die; } ?>

<?php if(!isset($_GET["edit"]) AND !isset($_GET["add"])) { ?>
<div class="well bs-component">

  <div><a href="<?=OSS_HOME?>?option=admin_groups&amp;add" class="btn btn-primary">Add Group</a></div>

  <table class="table table-striped table-hover ">
  <thead>
    <tr>
      <th><?=$lang["Group"]?></th>
	  <th>Commands</th>
	  <th>Denies</th>
    </tr>
  </thead>
  <tbody>
  <?php
  if(!empty($GroupsData))
  foreach ( $GroupsData as $Group ) {
  ?>
    <tr>
      <td width="260">
		<a href="<?=OSS_HOME?>?option=admin_groups&amp;edit=<?=$Group["group"]?>" class="btn btn-xs btn-<?=$Group["class"]?>"><?=$Group["group"]?></a>
		
		<?php if(!$Group["root"]) {?>
		<a href="javascript:;" onclick="if(confirm('Delete Group?')) { location.href='<?=OSS_HOME?>?option=groups&amp;remove=<?=$Group["group"]?>' }" class="floatR btn btn-xs btn-danger">Remove</a>
		<?php } ?>
		
	  </td>
	  <td width="280"><?=($Group["commands_short"])?>...</td>
	  <td><?=$Group["denies_short"]?>...</a></td>
    </tr>
   <?php } ?>
  </tbody>
</table> 

</div>
<?php } ?>

<?php if( (isset($_GET["edit"]) OR isset($_GET["add"]) ) AND !empty($GroupsData)) { ?>
<div class="well bs-component">
   <h2> 
   <span class="btn btn-success btn-lg"><?=$GroupsData[0]["group"]?></span> 
   <a href="<?=OSS_HOME?>?option=admin_groups" class="btn btn-xs btn-info">&raquo; Back</a>
   <a href="javascript:;" onclick="if(confirm('Delete Group?')) { location.href='<?=OSS_HOME?>?option=admin_groups&amp;remove=<?=$GroupsData[0]["group"]?>' }" class="btn btn-xs btn-danger">delete</a>
   </h2>
  
<?php if(!empty($_GET["add"]) AND $_GET["add"] == "error") { ?>
  <div class="alert alert-dismissable alert-danger">
     <button type="button" class="close" data-dismiss="alert">&times;</button>
     <strong>Error!</strong> The group name already exists or empty
   </div>
<?php } ?>
  
<form action="" method="post">  
   <div><strong>Group Name:</strong></div>
   <div>
   
   <input <?=$GroupsData[0]["sel"]?> type="text" value="<?=$GroupsData[0]["group"]?>" name="group" size="50" />
   <?php if(!isset($_GET["add"])) { ?>
   <input type="hidden" value="<?=$GroupsData[0]["group"]?>" name="old_group" size="50" />
   <input <?=$GroupsData[0]["sel"]?> type="submit" value="Change Group Name" name="change_group_name" class="btn btn-primary" />
   <?php } ?>
   </div>
   
   <table>
   <tbody>
     <tr>
       <td>
	    <div style="margin-top:32px;"><strong>Allowed commands:</strong></div>
   <?php
     foreach ($GroupCommand as $Command) {
	 ?>
	 <div>
	   <label for="a-<?=$Command["num"]?>" class="command_list">
	     <input <?=$GroupsData[0]["sel"]?> name="commands[<?=$Command["command"]?>]" id="a-<?=$Command["num"]?>" type="checkbox" <?=$Command["check"]?>/> <?=$Command["command"]?>
	   </label>
	 </div>
	 <?php
	 }
   ?>
       </td>
       <td>
	    <div style="margin-top:32px;"><strong>Disallowed commands:</strong></div>
   <?php
     foreach ($GroupDeniedCommand as $Command) {
	 ?>
	 <div>
	   <label for="d-<?=$Command["num"]?>" class="command_list">
	     <input <?=$GroupsData[0]["sel"]?> name="denied[<?=$Command["command"]?>]" id="d-<?=$Command["num"]?>" type="checkbox" <?=$Command["check"]?>/> <?=$Command["command"]?>
	   </label>
	 </div>
	 <?php
	 }
   ?>
       </td>
     </tr>
   </tbody>
   </table>
   
   <input <?=$GroupsData[0]["sel"]?> type="submit" value="Save Group" name="save_group" class="btn btn-primary" />
   
</form>
</div>
<?php } ?>
