--
-- Installation package for projects.
--
-- @package		projects
CREATE TABLE IF NOT EXISTS `#__social_projects` ((
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key for this table',
  `title` varchar(255) NOT NULL COMMENT 'Title of the project',
  `description` text NOT NULL COMMENT 'The description of the project',
  `user_id` int(11) NOT NULL COMMENT 'The user id that created this project',
  `uid` int(11) NOT NULL COMMENT 'This project may belong to another node other than the user.',
  `type` varchar(255) NOT NULL COMMENT 'This project may belong to another node other than the user.',
  `created` datetime NOT NULL,
  `state` tinyint(3) NOT NULL,
  `featured` tinyint(3) NOT NULL,
  `gener` varchar(255) NOT NULL,
  `hits` int(11) NOT NULL COMMENT 'Total hits received for this project',
  `duration` varchar(255) NOT NULL COMMENT 'Duration of the project',
  `size` int(11) NOT NULL COMMENT 'The file size of the project',
  `params` text NOT NULL COMMENT 'Store project params',
  `storage` varchar(255) NOT NULL COMMENT 'Storage for project',
  `path` text NOT NULL,
  `original` text NOT NULL,
  `file_title` varchar(255) NOT NULL,
  `source` varchar(255) NOT NULL,
  `thumbnail` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `title` (`title`,`user_id`,`state`,`featured`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;
