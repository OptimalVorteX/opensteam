<?php if (!isset( $cfg["website"] ) ) {header('HTTP/1.1 404 Not Found'); die; } ?>
<h2><?=$lang["Login"] ?></h2>
<div class="well bs-component">
   <?php if ( isset($Login) ) { ?>
   <a href="<?=$Login?>"><img src="<?=OSS_THEME_LINK?>images/steam_signin.png" alt="login" /></a>
   <?php } ?>
   <div style="margin-bottom: 120px;"></div>
</div>