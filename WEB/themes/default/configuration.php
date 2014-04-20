<?php if (!isset( $cfg["website"] ) ) {header('HTTP/1.1 404 Not Found'); die; } ?>
<div class="well bs-component">
   <h2><?=$lang["Configuration"]?></h2>
 
<?php if(isset($Warning)) { ?> 
   <div class="alert alert-dismissable alert-danger">
   <button type="button" class="close" data-dismiss="alert">×</button>
   <?=$Warning?>
   </div>
<?php } ?>

<form action="" method="post">
   <table class="table table-striped table-hover ">
      <thead>
         <tr>
            <td width="180"><strong><?=$lang["HomeTitle"]?>:</strong></td>
            <td><input type="text" name="home_title" value="<?=$cfg["home_title"]?>" size="75" /></td>
         </tr>
         <tr>
            <td width="180"><strong><?=$lang["Language"]?>:</strong></td>
            <td>
               <select name="lang">
                  <?php foreach($LangOptions as $language) { ?>
                  <option <?=$language["selected"]?> value="<?=$language["file"]?>"><?=$language["lang"]?></option>
                  <?php } ?>
               </select>
            </td>
         </tr>
         <tr>
            <td width="180"><strong><?=$lang["DateFormat"]?>:</strong></td>
            <td><input type="text" name="date_format" value="<?=$cfg["date_format"]?>" size="25" /></td>
         </tr>
         <tr>
            <td width="180"><strong><?=$lang["PlayersPerPage"]?>:</strong></td>
            <td><input type="text" name="players_per_page" value="<?=$cfg["players_per_page"]?>" size="5" /></td>
         </tr>
         <tr>
            <td width="180"><strong><?=$lang["BanInfoMessage"]?>:</strong></td>
            <td>
               <select name="loading_ban_message">
                  <?php if($cfg["loading_ban_message"]=='1') $s='selected="selected"'; else $s="";?>
                  <option <?=$s?> value="1"><?=$lang["Show"]?></option>
                  <?php if($cfg["loading_ban_message"]=='0') $s='selected="selected"'; else $s="";?>
                  <option <?=$s?> value="0"><?=$lang["Hide"]?></option>
               </select>
			   <div><?=$lang["ShowHideInfo"]?></div>
            </td>
         </tr>
		 <tr>
            <td width="180"><strong><?=$lang["CacheSteamFiles"]?>:</strong></td>
            <td><input type="text" name="cache_time" value="<?=$cfg["cache_time"]?>" size="2" /> <?=$lang["min"]?>.</td>
         </tr>
         <tr>
            <td width="180"><strong><?=$lang["ErrorReporting"]?>:</strong></td>
            <td>
               <select name="debug">
                  <?php if($debug=='1') $s='selected="selected"'; else $s="";?>
                  <option <?=$s?> value="1"><?=$lang["Yes"]?></option>
                  <?php if($debug=='0') $s='selected="selected"'; else $s="";?>
                  <option <?=$s?> value="0"><?=$lang["No"]?></option>
               </select>
            </td>
         </tr>
         <tr>
            <td width="180"><strong><?=$lang["LoadingURL"]?>:</strong></td>
            <td>
               <input type="text" value='sv_loadingurl "<?=OSS_HOME?>loading/?mapname=%m&steamid=%s"' size="87" />
               <div class="alert alert-dismissable alert-success">
                  <button type="button" class="close" data-dismiss="alert">×</button>
                  <?=$lang["LoadingURLInfo1"]?>
                  <strong>
                     <div>GAME_ROOT/garrysmod/cfg
                  </strong>
                  <?=$lang["LoadingURLInfo2"]?></div>
                  <div>sv_loadingurl "<?=OSS_HOME?>loading/?mapname=%m&steamid=%s"</div>
               </div>
            </td>
         </tr>
		 
         <tr>
            <td width="180"></td>
            <td><input type="<?=$ButtonType?>" <?=$Alert?> name="save_config" value="<?=$lang["SaveConfiguration"]?>" class="btn btn-primary" /></td>
         </tr>
      </thead>
      <tbody>
   </table>
</form>
</div>