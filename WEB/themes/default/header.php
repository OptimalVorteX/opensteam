<?php if (!isset( $cfg["website"] ) ) {header('HTTP/1.1 404 Not Found'); die; } ?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8" />
      <title><?=$PageTitle?></title>
      <meta name="viewport" content="width=device-width, initial-scale=1" />
      <link rel="stylesheet" href="<?=OSS_THEME_LINK?>style.css" media="screen" />
      <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
      <!--[if lt IE 9]>
      <script src="<?=OSS_THEME_LINK?>js/html5shiv.js"></script>
      <script src="<?=OSS_THEME_LINK?>js/respond_min.js"></script>
      <![endif]-->
   </head>