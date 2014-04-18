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
            <td width="180"><strong>Home Title:</strong></td>
            <td><input type="text" name="home_title" value="<?=$cfg["home_title"]?>" size="75" /></td>
         </tr>
         <tr>
            <td width="180"><strong>Language:</strong></td>
            <td>
               <select name="lang">
                  <?php foreach($LangOptions as $language) { ?>
                  <option <?=$language["selected"]?> value="<?=$language["file"]?>"><?=$language["lang"]?></option>
                  <?php } ?>
               </select>
            </td>
         </tr>
         <tr>
            <td width="180"><strong>Date Format:</strong></td>
            <td><input type="text" name="date_format" value="<?=$cfg["date_format"]?>" size="25" /></td>
         </tr>
         <tr>
            <td width="180"><strong>Players Per Page:</strong></td>
            <td><input type="text" name="players_per_page" value="<?=$cfg["players_per_page"]?>" size="5" /></td>
         </tr>
         <tr>
            <td width="180"><strong>Cache Steam Files:</strong></td>
            <td><input type="text" name="cache_time" value="<?=$cfg["cache_time"]?>" size="2" /> min.</td>
         </tr>
         <tr>
            <td width="180"><strong>Error Reporting:</strong></td>
            <td>
               <select name="debug">
                  <?php if($debug=='1') $s='selected="selected"'; else $s="";?>
                  <option <?=$s?> value="1">Yes</option>
                  <?php if($debug=='0') $s='selected="selected"'; else $s="";?>
                  <option <?=$s?> value="0">No</option>
               </select>
            </td>
         </tr>
         <tr>
            <td width="180"><strong>Loading URL:</strong></td>
            <td>
               <input type="text" value='sv_loadingurl "<?=OSS_HOME?>loading/?mapname=%m&steamid=%s"' size="87" />
               <div class="alert alert-dismissable alert-success">
                  <button type="button" class="close" data-dismiss="alert">×</button>
                  Open file <strong>server.cfg</strong> that is located in the following directory 
                  <strong>
                     <div>GAME_ROOT/garrysmod/cfg
                  </strong>
                  and add the following</div>
                  <div>sv_loadingurl "<?=OSS_HOME?>loading/?mapname=%m&steamid=%s"</div>
               </div>
            </td>
         </tr>
		 
         <tr>
            <td width="180"></td>
            <td><input type="<?=$ButtonType?>" <?=$Alert?> name="save_config" value="Save Configuration" class="btn btn-primary" /></td>
         </tr>
      </thead>
      <tbody>
   </table>
</form>
</div>