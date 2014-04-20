<?php if (!isset( $cfg["website"] ) ) {header('HTTP/1.1 404 Not Found'); die; } ?>
<div class="well bs-component">
   <?php if(!empty($EditPlayer["playerName"])) { ?>
   <form class="form-horizontal" action="" method="post">
      <fieldset>
         <legend><?=OSS_SteamAvatar($EditPlayer["avatar_medium"])?> <?=$EditPlayer["playerName"]?></legend>
         <?php if(!empty($EditPlayer["expire"])) { ?>	  
         <div class="alert alert-dismissable alert-danger">
            <div><strong><?=$lang["Banned"]?>: </strong> <?=$EditPlayer["bantime"]?></div>
            <div><?=OB_ExpireDateRemain($EditPlayer["expire_date"])?></div>
            <div><input onclick="if(confirm('Remove ban?')) { location.href='<?=OSS_HOME?>?option=<?=$_GET["option"]?>&amp;id=<?=$EditPlayer["id"]?>&amp;rban=<?=$EditPlayer["banid"]?>' }" type="button" class="btn btn-warning" value="Remove Ban" /></div>
         </div>
         <?php } ?>  
         <div class="form-group">
            <label for="inputsteam" class="col-lg-2 control-label"><?=$lang["Steam"]?></label>
            <div class="col-lg-10">
               <?=$EditPlayer["steam"]?> 
			   <div><strong><?=$lang["CommunityID"]?>:</strong> <a target="_blank" href="http://steamcommunity.com/profiles/<?=$EditPlayer["steamID"]?>/"><?=$EditPlayer["steamID"]?></a>
			   </div>
            </div>
         </div>
		 
         <div class="form-group">
            <label for="inputLocation" class="col-lg-2 control-label"><?=$lang["Location"] ?></label>
            <div class="col-lg-10">
               <?=$EditPlayer["location"]?> <?=$EditPlayer["user_ip"]?>
            </div>
         </div>
         <div class="form-group">
            <label for="inputConnections" class="col-lg-2 control-label"><?=$lang["Connections"] ?></label>
            <div class="col-lg-10">
               <?=$EditPlayer["connections"]?>
            </div>
         </div>
         <div class="form-group">
            <label for="select" class="col-lg-2 control-label"><?=$lang["Rank"] ?></label>
            <div class="col-lg-10">
               <select class="form-control" id="select" name="rank" style="width:250px;">
			   <?php foreach ($GroupsData as $Group) { ?>
			     <option <?=$Group["sel"]?> value="<?=$Group["group"]?>"><?=$Group["group"]?></option>
			   <?php } ?>
               </select>
            </div>
         </div>
         <div class="form-group">
            <label for="inputConnections" class="col-lg-2 control-label"><?=$lang["BanPlayer"]?></label>
            <div class="col-lg-10">
               <strong><?=$lang["Expire"]?>: </strong> 
               <input type="text" id="expiredate" name="expire_date" value="<?=$EditPlayer["expire_date"]?>" />
               <a href="javascript:;" class="btn btn-default" onclick="SetDateField('<?=date("Y-m-d H:i:00", time()+3600 )?>', 'expiredate')" >+1h</a>
               <a href="javascript:;" class="btn btn-default" onclick="SetDateField('<?=date("Y-m-d H:i:00", time()+3600*10 )?>', 'expiredate')" >+10h</a>
               <a href="javascript:;" class="btn btn-default" onclick="SetDateField('<?=date("Y-m-d H:i:00", time()+3600*24 )?>', 'expiredate')" >+1 day</a>
               <a href="javascript:;" class="btn btn-default" onclick="SetDateField('<?=date("Y-m-d H:i:00", time()+3600*24*7 )?>', 'expiredate')" >+7 days</a>
               <a href="javascript:;" class="btn btn-default" onclick="SetDateField('<?=date("Y-m-d H:i:00", time()+3600*24*30 )?>', 'expiredate')" >+1 month</a>
               <a href="javascript:;" class="btn btn-default" onclick="SetDateField('<?=date("Y-m-d H:i:00", time()+3600*24*90 )?>', 'expiredate')" >+3 months</a>
               <a href="javascript:;" class="btn btn-danger" onclick="SetDateField('0000-00-00 00:00:00', 'expiredate')" ><?=$lang["perm"]?></a>
               <a href="javascript:;" class="btn btn-info" onclick="SetDateField('', 'expiredate')" ><?=$lang["clear"]?></a>
            </div>
         </div>
		 
         <div class="form-group">
            <label for="inputBanReason" class="col-lg-2 control-label"><?=$lang["BanReason"]?></label>
            <div class="col-lg-10">
                <textarea name="reason" class="form-control" rows="3"><?=$EditPlayer["reason"]?></textarea>
            </div>
         </div>
		 
         <div class="form-group">
            <div class="col-lg-10 col-lg-offset-2">
               <button type="submit" class="btn btn-primary" name="change_user"><?=$lang["Submit"]?></button>
               <input type="button" value="Cancel" class="btn btn-default" onclick="location.href='<?=OSS_HOME?>'">
            </div>
         </div>
      </fieldset>
   </form>
   <?php } ?>
</div>
<script>
   function SetDateField(datetime, fieldID) {
     document.getElementById(fieldID).value = datetime;
   }
</script>