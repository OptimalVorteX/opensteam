<?php

  if(isset($_GET["img"]) AND strstr($_GET["img"], "http") ) {
    //http://cache.www.gametracker.com/server_info/212.224.126.68:27035/b_560_95_1.png
	//http://cache.www.gametracker.com/server_info/212.224.126.68:27036/b_560_95_1.png
    $link = trim( ($_GET["img"]) );
   // die($link);
	$parts = explode(":", $link);
	$count = count($parts);
	$CacheImage = $parts[$count - 1];
	$CacheImage = str_replace("/", "_", $CacheImage);

	 if ( file_exists("cache/".$CacheImage) AND filemtime("cache/".$CacheImage)+180 < time() ) 
	 unlink("cache/".$CacheImage);
	 
	if ( file_exists("cache/".$CacheImage) AND filesize("cache/".$CacheImage)<=10 ) 
	unlink("cache/".$CacheImage);
	
	if(!file_exists("cache/".$CacheImage)) 
	file_put_contents("cache/".$CacheImage, file_get_contents($link) );

  
  if ( file_exists("cache/".$CacheImage) ) {
  
     header('Content-Length: '.filesize( "cache/".$CacheImage ));
	 header('Content-Type: image/png');
     echo file_get_contents( "cache/".$CacheImage );
  } 
  
  }
?>