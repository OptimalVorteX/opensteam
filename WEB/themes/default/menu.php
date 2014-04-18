<?php if (!isset( $cfg["website"] ) ) {header('HTTP/1.1 404 Not Found'); die; } ?>
<body>
   <div class="navbar navbar-default navbar-fixed-top">
      <div class="container">
         <div class="navbar-header">
            <a href="<?=OSS_HOME?>" class="navbar-brand"><?=$lang["Site_Title"]?></a>
            <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            </button>
         </div>
         <div class="navbar-collapse collapse" id="navbar-main">
            <ul class="nav navbar-nav">
               <li>
                  <a href="<?=OSS_HOME?>?option=players"><?=$lang["Players"]?></a>
               </li>
               <li>
                  <a href="<?=OSS_HOME?>?option=bans"><?=$lang["Bans"]?></a>
               </li>
               <li>
                  <a href="<?=OSS_HOME?>?option=admins"><?=$lang["Admins"]?></a>
               </li>
            </ul>
            <form class="navbar-form navbar-left" action="" method="get">
               <input onkeydown="if (event.keyCode == 13) { location.href='<?=OSS_HOME?>?option=<?php if(isset($_GET["option"]) ) echo $_GET["option"]; ?>&search='+srch.value; return false; }" type="text" id="srch" class="form-control col-lg-8" placeholder="Search" value="<?=$search ?>" />
               <input type="button" onclick="location.href='<?=OSS_HOME?>?option=<?php if(isset($_GET["option"]) ) echo $_GET["option"]; ?>&search='+srch.value" value="Search Player" class="btn btn-primary" />
            </form>
            <ul class="nav navbar-nav navbar-right">
               <?php if(!isset($_SESSION["steamID"])) { ?>
               <li><a href="<?=OSS_HOME?>?option=login"><?=$lang["Login"]?></a></li>
               <li><a href="<?=OSS_HOME?>?option=login"><?=$lang["Register"]?></a></li>
               <?php } else { ?>
               <li class="dropdown">
                  <a  class="dropdown-toggle" data-toggle="dropdown" href="#" id="user_panel"><?=$_SESSION["name"]?> <span class="caret"></span></a>
                  <ul class="dropdown-menu" aria-labelledby="user_panel">
                     <?php if (isset($_SESSION["admin"]) ) { ?>
					 <!--
                     <li><a href="<?=OSS_HOME?>?option=admin"><?=$lang["User_Manage"]?></a></li>
                     <li><a href="<?=OSS_HOME?>?option=admin"><?=$lang["Bans_Manage"]?></a></li>
					 -->
                     <li><a href="<?=OSS_HOME?>?option=configuration"><?=$lang["Configuration"]?></a></li>
                     <?php } ?>
                     <li><a href="<?=OSS_HOME?>?option=logout"><?=$lang["Logout"]?></a></li>
                  </ul>
               </li>
               <?php } ?>
            </ul>
         </div>
      </div>
   </div>
   <div class="container MainPage">