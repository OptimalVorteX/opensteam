<?php if (!isset( $cfg["website"] ) ) {header('HTTP/1.1 404 Not Found'); die; } ?>

<div class="well bs-component">
 
    <h2><?=$lang["BanCountry"]?></h2>
	<form action="" method="post">
	<?php foreach ($AllCountries as $c) { ?>
	<div>
	  <label for="<?=$c["code"]?>">
	    <input type="checkbox" name="country[<?=$c["code"]?>]" <?=$c["checked"]?> id="<?=$c["code"]?>" /> [<?=$c["code"]?>] <?=$c["country"]?>
	  </label>
	</div>
    <?php } ?>

	<input type="submit" value="<?=$lang["SaveBan"]?>" name="country_bans" class="btn btn-primary" />
	</form>
</div>