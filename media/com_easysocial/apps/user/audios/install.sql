--
-- Installation package for Audios.
--
-- @package		Audios
CREATE TABLE IF NOT EXISTS `#__social_audios` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `title` text NOT NULL,
  `alias` text NOT NULL,
  `content` text NOT NULL,
  `created` datetime NOT NULL,
  `params` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
