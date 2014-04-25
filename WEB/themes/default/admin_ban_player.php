<?php if (!isset( $cfg["website"] ) ) {header('HTTP/1.1 404 Not Found'); die; } ?>
<div class="well bs-component">

<?php if(isset($_GET["success"])) { ?>
<div class="alert alert-dismissable alert-success">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
   <?=$lang["BanAdded"]?>
</div>
<?php } ?>

<?php if(isset($_GET["error"])) { ?>
<div class="alert alert-dismissable alert-danger">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <?=$lang["ErrorOccured"]?>
</div>
<?php } ?>


  <h2><?=$lang["BanPlayerIP"]?></h2>
  <form action="" method="post">
    <table class="table table-striped table-hover ">
	  <tr>
	    <td class="alert alert-dismissable alert-success"><?=$lang["SteamProfileURL"]?>:</td>
		<td class="alert alert-dismissable alert-success">
		  <input type="text" name="steamProfile" value="" id="steamProfile" size="60"/>
		  <input onclick="GetSteamInfo()" type="button" value="<?=$lang["GetSteamData"]?>" class="btn btn-primary" />
		  <span id="info"></span>
		</td>
	  </tr>
      <tr>
        <td width="200"><strong><?=$lang["SteamID"] ?>:</strong></td>
		<td><input type="text" name="steam" value="" id="steam" />
		<strong><?=$lang["CommunityID"] ?>:</strong> <input type="text" name="steamID" id="steamID" value="" />
		</td>
      </tr>
      <tr>
        <td width="200"><strong><?=$lang["Name"] ?>:</strong></td>
		<td><input type="text" name="playerName" id="player" value="" /></td>
      </tr>
	  <tr>
	    <td width="200"><strong><?=$lang["BanReason"]?></strong></td>
		<td><textarea name="reason" class="form-control" rows="3"></textarea></td>
	  </tr>
	  <tr>
	    <td></td>
		<td>
		<strong><?=$lang["Expire"]?>: </strong> 
        <input type="text" id="expiredate" name="expire" value="" />
        <a href="javascript:;" class="btn btn-default" onclick="SetDateField('<?=date("Y-m-d H:i:00", time()+3600 )?>', 'expiredate')" >+1h</a>
        <a href="javascript:;" class="btn btn-default" onclick="SetDateField('<?=date("Y-m-d H:i:00", time()+3600*10 )?>', 'expiredate')" >+10h</a>
        <a href="javascript:;" class="btn btn-default" onclick="SetDateField('<?=date("Y-m-d H:i:00", time()+3600*24 )?>', 'expiredate')" >+1 day</a>
        <a href="javascript:;" class="btn btn-default" onclick="SetDateField('<?=date("Y-m-d H:i:00", time()+3600*24*7 )?>', 'expiredate')" >+7 days</a>
        <a href="javascript:;" class="btn btn-default" onclick="SetDateField('<?=date("Y-m-d H:i:00", time()+3600*24*30 )?>', 'expiredate')" >+1 month</a>
        <a href="javascript:;" class="btn btn-default" onclick="SetDateField('<?=date("Y-m-d H:i:00", time()+3600*24*90 )?>', 'expiredate')" >+3 months</a>
        <a href="javascript:;" class="btn btn-danger" onclick="SetDateField('0000-00-00 00:00:00', 'expiredate')" ><?=$lang["perm"]?></a>
        <a href="javascript:;" class="btn btn-info" onclick="SetDateField('', 'expiredate')" ><?=$lang["clear"]?></a>
		</td>
	  </tr>
      <tr>
        <td width="200"></td>
		<td><input type="submit" name="add_ban" value="<?=$lang["BanPlayerIP"]?>" class="btn btn-primary" /></td>
      </tr>
	</table>
  </form>
</div>
  