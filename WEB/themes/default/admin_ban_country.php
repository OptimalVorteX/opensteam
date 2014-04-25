<?php if (!isset( $cfg["website"] ) ) {header('HTTP/1.1 404 Not Found'); die; } ?>

<div class="well bs-component">
 
    <h2><?=$lang["BanCountry"]?></h2>
	
   <div class="alert alert-dismissable alert-info">
     <button type="button" class="close" data-dismiss="alert">&times;</button>
     <strong>GeoIP Install!</strong> Go to <a href="http://dev.maxmind.com/geoip/legacy/geolite/" target="_blank" class="alert-link">Geoip MaxMind</a> website and download <strong>GeoIPCountryCSV.zip</strong>
     <div>Unpack zip archive to <strong>/garrysmod/data</strong> folder</div>
   </div>
   
	<form action="" method="post">
	<?php foreach ($AllCountries as $c) { ?>
	<div>
	  <label for="<?=$c["code"]?>" <?=$c["colour"]?>>
	    <input type="checkbox" name="country[<?=$c["code"]?>]" <?=$c["checked"]?> id="<?=$c["code"]?>" /> [<?=$c["code"]?>] <?=$c["country"]?>
	  </label>
	</div>
    <?php } ?>
     <input type="hidden" name="country[]" />
	<input type="submit" value="<?=$lang["SaveCountryBans"]?>" name="country_bans" class="btn btn-primary" />
	</form>
</div>