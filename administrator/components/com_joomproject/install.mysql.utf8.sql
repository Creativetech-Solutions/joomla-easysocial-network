

DROP TABLE IF EXISTS `#__joomproject_category`;

CREATE TABLE IF NOT EXISTS `#__joomproject_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cname` varchar(255) NOT NULL,
  `ordering` int(11) NOT NULL,
  `published` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;


