<?php
   if (strstr($_SERVER['REQUEST_URI'], basename(__FILE__) ) ) { header('HTTP/1.1 404 Not Found'); die; }
   
   if (isset( $cfg["debug"] ) AND $cfg["debug"] == 1)
   {
   ini_set ("display_errors", "1");
   error_reporting(E_ALL);
   } 
     else 
     {
	 ini_set ("display_errors", "0");
      error_reporting(NULL);
	 }

  if(isset($DBDriver) AND $DBDriver == "mysql" ) {
  $db = new database(OSSDB_SERVER, OSSDB_USERNAME, OSSDB_PASSWORD, OSSDB_DATABASE);
  $db->connect(database);
  $sth = $db->query("SET NAMES 'utf8'");
  }
  
  else 
     {
      $db = new db("mysql:host=".OSSDB_SERVER.";dbname=".OSSDB_DATABASE."", OSSDB_USERNAME, OSSDB_PASSWORD);
	  
	  if ( !$db ) die("Error!: Unable to connect to the database");
	  
      $sth = $db->prepare("SET NAMES 'utf8'");
      $result = $sth->execute();
	  
	  
      }
  
  ?>