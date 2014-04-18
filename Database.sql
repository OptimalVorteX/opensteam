DROP TABLE IF EXISTS `ph_bans`;
CREATE TABLE IF NOT EXISTS `ph_bans` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `steam` varchar(30) NOT NULL,
  `name` text NOT NULL,
  `admin` varchar(50) NOT NULL,
  `bantime` datetime NOT NULL,
  `expire` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `steam` (`steam`),
  KEY `expires` (`expire`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

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
  `last_connection` varchar(20) NOT NULL,
  `connections` int(10) unsigned NOT NULL,
  `rank` varchar(100) NOT NULL DEFAULT 'user',
  `user_ip` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `steam` (`steam`),
  KEY `steamID` (`steamID`),
  KEY `last_seen` (`last_connection`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;