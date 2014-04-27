<?php
/* 
Ivan Antonijević (ivan.anta [at] gmail.com), 2012
*/
if (strstr($_SERVER['REQUEST_URI'],basename(__FILE__))){header('HTTP/1.1 404 Not Found');die;}

$prefix = ""; 
$strana = "&amp;page=";
$end = "";

if (isset($_GET["option"])) {

   $prefix.="?option=".trim($_GET["option"]);

}

if ( !isset($_GET["option"]) ) {
   //$prefix = "?option"; 
   $strana = "?page=";
}

if (isset($_GET["u"]) AND is_numeric($_GET["u"]) ) $prefix.="&amp;u=".(int)($_GET["u"]);
if (isset($_GET["search"]) ) { $prefix.="?search=".strip_tags($_GET["search"]); $strana = "&amp;page="; }
if (isset($_GET["sort"]) )   { $prefix.="?sort=".trim($_GET["sort"]); $strana = "&amp;page="; }

              $rowsperpage = $result_per_page;

              $totalpages = ceil($numrows / $rowsperpage);
              if (isset($_GET['page']) && is_numeric($_GET['page'])) {
                  $currentpage = (int)$_GET['page'];
              } else {
                  $currentpage = 1;
              }
              if ($currentpage > $totalpages) {
                  $currentpage = $totalpages;
              }
              if ($currentpage < 1) {
                  $currentpage = 1;
              }
              if ($totalpages <= 1) {
                  $totalpages = 1;
              }

              $offset = ($currentpage - 1) * $rowsperpage;
              if (isset($_GET['page']) AND is_numeric($_GET['page'])){
                          $current_page = safeEscape($_GET['page']);
                          }

                          if (!isset($current_page)) {
                              $current_page = 1;
                          }
              if (!isset($MaxPaginationLinks) ) $range = 2;
			  else  $range = $MaxPaginationLinks;
			  
              if ($range >= $totalpages) {
                  $range = $totalpages;
              }
			  
			  if ($current_page > $totalpages) {$current_page = $totalpages;}
			  
if ($draw_pagination == 1 AND $totalpages>=2) { ?>
	  <ul class="pagination"> 
	   <?php
              if ($currentpage > 1) {
                  ?><li><a class="button orange" href="<?=OSS_HOME?><?=$prefix?>"><span>&laquo;</span></a></li><?php
                  $prevpage = $currentpage - 1;
                  ?><li><a class="button orange" href="<?=OSS_HOME?><?=$prefix?><?=$strana?><?=$prevpage?><?=$end?>"><span><?=$lang["Previous"]?></span></a></li><?php
              }
              for ($x = ($currentpage - $range); $x < (($currentpage + $range) + 1); $x++) {
                  if (($x > 0) && ($x <= $totalpages)) {
                      if ($x == $currentpage) {
                         ?>
					  <li class="active"><a class="button orange" href="javascript:;"><span><?=$x?></span></a></li><?php
                      } else {
                          ?>
					  <li><a class="button orange" href="<?=OSS_HOME?><?=$prefix?><?=$strana?><?=$x?><?=$end?>"><span><?=$x?></span></a></li><?php
                      }
                  }
              }
              if ($currentpage != $totalpages) {
                  $nextpage = $currentpage + 1;
                 ?>
				 <li><a class="button orange" href="<?=OSS_HOME?><?=$prefix?><?=$strana?><?=$nextpage?><?=$end?>"><span><?=$lang["Next"]?></span></a></li>
				 
				 <li><a class="button orange" href="<?=OSS_HOME?><?=$prefix?><?=$strana?><?=$totalpages?><?=$end?>"><span><?=$totalpages?></span></a></li><?php
              }
             ?>   
			<?php if (isset($SHOW_TOTALS) ) { ?>
			 &nbsp;
			 <li><?=$lang["Page"]?> <b><?=$current_page?></b></li> 
			 <li><?=$lang["of"]?> <?=number_format($totalpages)?></li>
			 <li>(<?=number_format($numrows)?> <?=$lang["total"]?>)</li>
			 
			 <?php } ?>
	  </ul>
			 <?php
}

?>
