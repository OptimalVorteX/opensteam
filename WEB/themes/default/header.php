<?php if (!isset( $cfg["website"] ) ) {header('HTTP/1.1 404 Not Found'); die; } ?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1" />
	  <meta name="author" content="Ivan Antonijevic" />
	  <meta name="rating" content="Safe For Kids" />
	  <title><?=$PageTitle?></title>
	  <link rel="shortcut icon" href="<?=OSS_THEME_LINK?>favicon.ico" />
      <link rel="stylesheet" href="<?=OSS_THEME_LINK?>style.css" media="screen" />
      <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
      <!--[if lt IE 9]>
      <script src="<?=OSS_THEME_LINK?>js/html5shiv.js"></script>
      <script src="<?=OSS_THEME_LINK?>js/respond_min.js"></script>
      <![endif]-->
   </head>