DROP TABLE IF EXISTS `ph_bans`;
CREATE TABLE IF NOT EXISTS `ph_bans` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `steam` varchar(30) NOT NULL,
  `name` text NOT NULL,
  `admin` varchar(50) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `bantime` datetime NOT NULL,
  `expire` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `steam` (`steam`),
  KEY `expires` (`expire`)
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
('admin', 'ulx armor\nulx ban\nulx banuser\nulx blind\nulx bring\nulx chattime\nulx checkban\nulx cloak\nulx csay\nulx freeze\nulx gag\nulx gimp\nulx god\nulx goto\nulx hp\nulx ignite\nulx jail\nulx jailtp\nulx kick\nulx kickafternamechanges\nulx kickafternamechangescooldown\nulx kickafternamechangeswarning\nulx map\nulx mute\nulx noclip\nulx physgunplayer\nulx playsound\nulx ragdoll\nulx reservedslots\nulx rslots\nulx rslotsmode\nulx rslotsvisible\nulx seeanonymousechoes\nulx send\nulx showgroups\nulx showmotd\nulx sid\nulx slap\nulx slay\nulx spawnecho\nulx spectate\nulx sslay\nulx strip\nulx teleport\nulx tsay\nulx unban\nulx unblind\nulx uncloak\nulx unfreeze\nulx ungag\nulx ungimp\nulx ungod\nulx unignite\nulx unigniteall\nulx unjail\nulx unmute\nulx unragdoll\nulx veto\nulx vote\nulx voteban\nulx votebanminvotes\nulx votebansuccessratio\nulx votekick\nulx votekickminvotes\nulx votekicksuccessratio\nulx votemap2\nulx votemap2minvotes\nulx votemap2successratio\nulx votemapenabled\nulx votemapmapmode\nulx votemapmintime\nulx votemapminvotes\nulx votemapsuccessratio\nulx votemapvetotime\nulx votemapwaittime\nulx welcomemessage\nulx whip', ''),
('superadmin', 'ulx addgroup\nulx adduser\nulx adduserid\nulx addusermysql\nulx armor\nulx asay\nulx ban\nulx banid\nulx banuser\nulx blind\nulx bring\nulx cexec\nulx chattime\nulx checkban\nulx cloak\nulx csay\nulx ent\nulx exec\nulx freeze\nulx gag\nulx gimp\nulx god\nulx goto\nulx groupallow\nulx groupdeny\nulx help\nulx hiddenecho\nulx hp\nulx ignite\nulx jail\nulx jailtp\nulx kick\nulx kickafternamechanges\nulx kickafternamechangescooldown\nulx kickafternamechangeswarning\nulx logchat\nulx logdir\nulx logecho\nulx logechocolorconsole\nulx logechocolordefault\nulx logechocoloreveryone\nulx logechocolormisc\nulx logechocolorplayer\nulx logechocolorplayerasgroup\nulx logechocolors\nulx logechocolorself\nulx logevents\nulx logfile\nulx logjoinleaveecho\nulx logspawns\nulx logspawnsecho\nulx luarun\nulx map\nulx maul\nulx motd\nulx mute\nulx noclip\nulx physgunplayer\nulx playsound\nulx psay\nulx ragdoll\nulx rcon\nulx removeban\nulx removegroup\nulx removeuser\nulx removeuserid\nulx renamegroup\nulx reservedslots\nulx rslots\nulx rslotsmode\nulx rslotsvisible\nulx seeanonymousechoes\nulx seeasay\nulx send\nulx setgroupcantarget\nulx showgroups\nulx showmotd\nulx sid\nulx slap\nulx slay\nulx spawnecho\nulx spectate\nulx sslay\nulx strip\nulx teleport\nulx thetime\nulx tsay\nulx unban\nulx unblind\nulx uncloak\nulx unfreeze\nulx ungag\nulx ungimp\nulx ungod\nulx unignite\nulx unigniteall\nulx unjail\nulx unmute\nulx unragdoll\nulx updateuser\nulx userallow\nulx userallowid\nulx userdeny\nulx userdenyid\nulx usermanagementhelp\nulx veto\nulx vote\nulx voteban\nulx votebanminvotes\nulx votebansuccessratio\nulx voteecho\nulx votekick\nulx votekickminvotes\nulx votekicksuccessratio\nulx votemap\nulx votemap2\nulx votemap2minvotes\nulx votemap2successratio\nulx votemapenabled\nulx votemapmapmode\nulx votemapmintime\nulx votemapminvotes\nulx votemapsuccessratio\nulx votemapvetotime\nulx votemapwaittime\nulx welcomemessage\nulx whip\nulx who\nxgui_gmsettings\nxgui_managebans\nxgui_managegroups\nxgui_svsettings', ''),
('user', 'ulx asay\r\nulx help\r\nulx motd\r\nulx psay\r\nulx thetime\r\nulx usermanagementhelp\r\nulx votemap\r\nulx who', '');