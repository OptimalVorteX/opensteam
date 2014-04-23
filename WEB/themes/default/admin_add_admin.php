<?php if (!isset( $cfg["website"] ) ) {header('HTTP/1.1 404 Not Found'); die; } ?>
<div class="well bs-component">

  <h2><?=$lang["AddAdmin"]?></h2>
  
<?php if(isset($_GET["success"])) { ?>
<div class="alert alert-dismissable alert-success">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
   <?=$lang["UserAdded"]?>
</div>
<?php } ?>

<?php if(isset($_GET["error"])) { ?>
<div class="alert alert-dismissable alert-danger">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <?=$lang["ErrorOccured"]?>
</div>
<?php } ?>
  
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
		<td><input type="text" name="steam" value="" id="steam" /></td>
      </tr>
      <tr>
        <td width="200"><strong><?=$lang["CommunityID"] ?>:</strong></td>
		<td><input type="text" name="steamID" id="steamID" value="" /></td>
      </tr>
      <tr>
        <td width="200"><strong><?=$lang["Name"] ?>:</strong></td>
		<td><input type="text" name="playerName" id="player" value="" /></td>
      </tr>
      <tr>
        <td width="200"><strong><?=$lang["Location"]?>:</strong></td>
		<td><input type="text" name="location" id="location" value="" /></td>
      </tr>
      <tr>
        <td width="200"><strong><?=$lang["AvatarImage"]?>:</strong></td>
		<td style="height:90px;">
		<input type="hidden" name="avatar" id="avatar" value="" />
		<input type="hidden" name="avatarMed" id="avatarMed" value="" />
		<span id="avatarIMG"></span>
		</td>
      </tr>
      <tr>
        <td width="200"><strong><?=$lang["Group"]?>:</strong></td>
		<td>
		<select name="group">
		<?php foreach($GroupsData as $group) { ?>
		<option value="<?=$group["group"]?>"><?=$group["group"]?></option>
		<?php } ?>
		</select>
		</td>
      </tr>
      <tr>
        <td width="200"><strong></strong></td>
		<td><input type="submit" name="add_admin" value="<?=$lang["AddAdmin"]?>" class="btn btn-primary" /></td>
      </tr>
	</table>
  </form>
</div>