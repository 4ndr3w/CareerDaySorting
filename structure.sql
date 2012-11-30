# Dump of table careers
# ------------------------------------------------------------

DROP TABLE IF EXISTS `careers`;

CREATE TABLE `careers` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `location` text NOT NULL,
  `maxStudents` int(11) NOT NULL,
  `group` int(11) NOT NULL,
  `hidden` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

# Add static events
INSERT INTO `careers` (`id`, `name`, `location`, `maxStudents`, `group`, `hidden`) VALUES (999, 'Assembly', 'Auditorium', 999, -1, 1);
INSERT INTO `careers` (`id`, `name`, `location`, `maxStudents`, `group`, `hidden`) VALUES (998, 'College Visit/Career Shadowing', '-', 999, -1, 1);

# Dump of table homerooms
# ------------------------------------------------------------

DROP TABLE IF EXISTS `homerooms`;

CREATE TABLE `homerooms` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table placements
# ------------------------------------------------------------

DROP TABLE IF EXISTS `placements`;

CREATE TABLE `placements` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `p1` int(11) NOT NULL,
  `p2` int(11) NOT NULL,
  `p3` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table selections
# ------------------------------------------------------------

DROP TABLE IF EXISTS `selections`;

CREATE TABLE `selections` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `s1` int(11) NOT NULL,
  `s2` int(11) NOT NULL,
  `s3` int(11) NOT NULL,
  `s4` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table statistics
# ------------------------------------------------------------

DROP TABLE IF EXISTS `statistics`;

CREATE TABLE `statistics` (
  `name` text NOT NULL,
  `value` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table students
# ------------------------------------------------------------

DROP TABLE IF EXISTS `students`;

CREATE TABLE `students` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `first` text NOT NULL,
  `last` text NOT NULL,
  `grade` int(11) NOT NULL,
  `homeroom` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
