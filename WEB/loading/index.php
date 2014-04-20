<?php include("init.php"); ?>
<!DOCTYPE html>
<html>

<head>
 	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta http-equiv="content-style-type" content="text/css" />
	<link rel="stylesheet" href="style.css" type="text/css" />
	
	<title>[SERVER NAME HERE]</title>
	
	<link href='http://fonts.googleapis.com/css?family=Open+Sans&subset=latin,latin-ext' rel='stylesheet' type='text/css' />

	<style>
	html { background: url(<?=$image?>) no-repeat center center fixed; }
	</style>
</head>

<body>

    <div id="page-wrap">
	
	<h2>[SERVER NAME HERE]</h2>
	<div>&nbsp;</div>
    
	<table border=0>
	  <tr>
	    <td width="84"><img style="border: 6px solid #585858;" width="64" src="<?=$avatarMedium?>" /></td>
		<td style="vertical-align:top;">
		<h2>Hi <span style="color: #920000"><?=$realname?></span></h2>
		<?php if(!empty($BannedInfo) ) { ?>
		<div style="color:red; font-weight:bold;"><?=$BannedInfo["message"]?></div>
		<?php if(!empty($BannedInfo["reason"])) { ?>
		<div><?=$BannedInfo["reason"]?></div>
		<?php } ?>
		<div><?=OB_ExpireDateRemain($BannedInfo["expire"])?></div>
		<?php } ?>
		<div style="margin-top:16px;"><b>We are playing:</b> <?=$mapname?></div>
		</td>
	  </tr>
	</table>
	
	<div>&nbsp;</div>
	
	<div><b>Server #1:</b> {YOUR SERVER #1 IP HERE}</div>
	<div><b>Server #2:</b> {YOUR SERVER #2 IP HERE}</div>
	
	<div style="margin-top:16px;color: #920000">ohsystem.net</div>
	
    </div>
	
</body>

</html>