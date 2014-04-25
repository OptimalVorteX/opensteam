DROP TABLE IF EXISTS `ph_bans`;
CREATE TABLE IF NOT EXISTS `ph_bans` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `steam` varchar(30) NOT NULL,
  `name` text NOT NULL,
  `ip` varchar(20) NOT NULL,
  `admin` varchar(50) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `bantime` datetime NOT NULL,
  `expire` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `steam` (`steam`),
  KEY `expires` (`expire`),
  KEY `ip` (`ip`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

DROP TABLE IF EXISTS `ph_config`;
CREATE TABLE IF NOT EXISTS `ph_config` (
  `config_name` varchar(255) NOT NULL,
  `config_value` mediumtext NOT NULL,
  UNIQUE KEY `config_name` (`config_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

DROP TABLE IF EXISTS `ph_users`;
CREATE TABLE IF NOT EXISTS `ph_users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `steamID` varchar(30) NOT NULL,
  `steam` varchar(30) NOT NULL,
  `avatar` varchar(255) NOT NULL,
  `avatar_medium` varchar(255) NOT NULL,
  `location` varchar(60) NOT NULL,
  `playerName` varchar(150) NOT NULL,
  `last_connection` datetime NOT NULL,
  `connections` int(10) unsigned NOT NULL,
  `rank` varchar(100) NOT NULL DEFAULT 'user',
  `user_ip` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `steam` (`steam`),
  KEY `steamID` (`steamID`),
  KEY `last_seen` (`last_connection`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

DROP TABLE IF EXISTS `ph_servers`;
CREATE TABLE IF NOT EXISTS `ph_servers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `server_name` varchar(150) NOT NULL,
  `server_ip` varchar(20) NOT NULL,
  `server_port` varchar(16) NOT NULL,
  `server_rcon` varchar(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

DROP TABLE IF EXISTS `ph_groups`;
CREATE TABLE IF NOT EXISTS `ph_groups` (
  `group` varchar(255) NOT NULL,
  `commands` text NOT NULL,
  `denies` text NOT NULL,
  UNIQUE KEY `group` (`group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

INSERT INTO `ph_groups` (`group`, `commands`, `denies`) VALUES
('admin', 'ulx armor\r\nulx ban\r\nulx banindex\r\nulx banuser\r\nulx blind\r\nulx bring\r\nulx chattime\r\nulx checkban\r\nulx cloak\r\nulx csay\r\nulx freeze\r\nulx gag\r\nulx gimp\r\nulx god\r\nulx goto\r\nulx hp\r\nulx ignite\r\nulx jail\r\nulx jailtp\r\nulx kick\r\nulx kickafternamechanges\r\nulx kickafternamechangescooldown\r\nulx kickafternamechangeswarning\r\nulx map\r\nulx mute\r\nulx noclip\r\nulx physgunplayer\r\nulx playsound\r\nulx ragdoll\r\nulx reservedslots\r\nulx rslots\r\nulx rslotsmode\r\nulx rslotsvisible\r\nulx seeanonymousechoes\r\nulx send\r\nulx showgroups\r\nulx showplayers\r\nulx showmotd\r\nulx sid\r\nulx slap\r\nulx slay\r\nulx spawnecho\r\nulx spectate\r\nulx sslay\r\nulx strip\r\nulx teleport\r\nulx tsay\r\nulx unban\r\nulx unblind\r\nulx uncloak\r\nulx unfreeze\r\nulx ungag\r\nulx ungimp\r\nulx ungod\r\nulx unignite\r\nulx unigniteall\r\nulx unjail\r\nulx unmute\r\nulx unragdoll\r\nulx veto\r\nulx vote\r\nulx voteban\r\nulx votebanminvotes\r\nulx votebansuccessratio\r\nulx votekick\r\nulx votekickminvotes\r\nulx votekicksuccessratio\r\nulx votemap2\r\nulx votemap2minvotes\r\nulx votemap2successratio\r\nulx votemapenabled\r\nulx votemapmapmode\r\nulx votemapmintime\r\nulx votemapminvotes\r\nulx votemapsuccessratio\r\nulx votemapvetotime\r\nulx votemapwaittime\r\nulx welcomemessage\r\nulx whip', ''),
('superadmin', 'ulx addgroup\r\nulx adduser\r\nulx adduserid\r\nulx addusermysql\r\nulx armor\r\nulx asay\r\nulx ban\r\nulx banid\r\nulx banindex\r\nulx banuser\r\nulx blind\r\nulx bring\r\nulx cexec\r\nulx chattime\r\nulx checkban\r\nulx cloak\r\nulx csay\r\nulx ent\r\nulx exec\r\nulx freeze\r\nulx gag\r\nulx gimp\r\nulx god\r\nulx goto\r\nulx groupallow\r\nulx groupdeny\r\nulx help\r\nulx hiddenecho\r\nulx hp\r\nulx ignite\r\nulx jail\r\nulx jailtp\r\nulx kick\r\nulx kickafternamechanges\r\nulx kickafternamechangescooldown\r\nulx kickafternamechangeswarning\r\nulx logchat\r\nulx logdir\r\nulx logecho\r\nulx logechocolorconsole\r\nulx logechocolordefault\r\nulx logechocoloreveryone\r\nulx logechocolormisc\r\nulx logechocolorplayer\r\nulx logechocolorplayerasgroup\r\nulx logechocolors\r\nulx logechocolorself\r\nulx logevents\r\nulx logfile\r\nulx logjoinleaveecho\r\nulx logspawns\r\nulx logspawnsecho\r\nulx luarun\r\nulx map\r\nulx maul\r\nulx motd\r\nulx mute\r\nulx noclip\r\nulx physgunplayer\r\nulx playsound\r\nulx psay\r\nulx ragdoll\r\nulx rcon\r\nulx removeban\r\nulx removegroup\r\nulx removeuser\r\nulx removeuserid\r\nulx renamegroup\r\nulx reservedslots\r\nulx rslots\r\nulx rslotsmode\r\nulx rslotsvisible\r\nulx seeanonymousechoes\r\nulx seeasay\r\nulx send\r\nulx setgroupcantarget\r\nulx showgroups\r\nulx showplayers\r\nulx showmotd\r\nulx sid\r\nulx slap\r\nulx slay\r\nulx spawnecho\r\nulx spectate\r\nulx sslay\r\nulx strip\r\nulx teleport\r\nulx thetime\r\nulx tsay\r\nulx unban\r\nulx unblind\r\nulx uncloak\r\nulx unfreeze\r\nulx ungag\r\nulx ungimp\r\nulx ungod\r\nulx unignite\r\nulx unigniteall\r\nulx unjail\r\nulx unmute\r\nulx unragdoll\r\nulx updateuser\r\nulx userallow\r\nulx userallowid\r\nulx userdeny\r\nulx userdenyid\r\nulx usermanagementhelp\r\nulx veto\r\nulx vote\r\nulx voteban\r\nulx votebanminvotes\r\nulx votebansuccessratio\r\nulx voteecho\r\nulx votekick\r\nulx votekickminvotes\r\nulx votekicksuccessratio\r\nulx votemap\r\nulx votemap2\r\nulx votemap2minvotes\r\nulx votemap2successratio\r\nulx votemapenabled\r\nulx votemapmapmode\r\nulx votemapmintime\r\nulx votemapminvotes\r\nulx votemapsuccessratio\r\nulx votemapvetotime\r\nulx votemapwaittime\r\nulx welcomemessage\r\nulx whip\r\nulx who\r\nxgui_gmsettings\r\nxgui_managebans\r\nxgui_managegroups\r\nxgui_svsettings', ''),
('user', 'ulx asay\r\nulx help\r\nulx motd\r\nulx psay\r\nulx thetime\r\nulx usermanagementhelp\r\nulx votemap\r\nulx who', '');
