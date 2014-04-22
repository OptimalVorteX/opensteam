<?php
if (!isset( $cfg["website"] ) ) {header('HTTP/1.1 404 Not Found'); die; }

define('OSS_VERSION', '1.0.0');
define('OSS_HOME',          $cfg["website"] );
define('OSS_ADMIN',         $cfg["website"]."adm/" );
define('OSS_HOME_TITLE',    $cfg["home_title"] );
define('OSS_TIMEZONE',      $cfg["time_zone"] );

//DATABASE
define('OSSDB_SERVER',      $cfg["server"] ); 
define('OSSDB_USERNAME',    $cfg["username"] ); 
define('OSSDB_PASSWORD',    $cfg["password"] ); 
define('OSSDB_DATABASE',    $cfg["database"] ); 
//TABLE PREFIX
define('OSSDB_PREFIX',      $cfg["db_prefix"] ); 

//TABLES
define('OSSDB_BANS',            OSSDB_PREFIX.'bans'); 
define('OSSDB_CONFIG',          OSSDB_PREFIX.'config'); 
define('OSSDB_PLAYERS',         OSSDB_PREFIX.'users'); 
define('OSSDB_GROUPS',          OSSDB_PREFIX.'groups');
define('OSSDB_SERVERS',         OSSDB_PREFIX.'servers'); 

define('OSS_THEMES_DIR',              $cfg["default_style"] );
define('OSS_THEME_LINK',              OSS_HOME."themes/".OSS_THEMES_DIR."/");
define('OSS_THEME_PATH',              "themes/".OSS_THEMES_DIR."/"); 

define('OSS_PLUGINS_DIR',            'plugins/');
define('OSS_PAGE_PATH',               "inc/pages/"); 
define('OSS_LANGUAGE',                $cfg["default_language"] );
define('OSS_DATE_FORMAT',             $cfg["date_format"] ); 

?>