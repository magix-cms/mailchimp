CREATE TABLE IF NOT EXISTS `mc_plugins_mailchimp` (
  `idapi` SMALLINT(5) unsigned NOT NULL AUTO_INCREMENT,
  `account_api` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`idapi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mc_plugins_mailchimp_list` (
  `idlist` SMALLINT(5) unsigned NOT NULL AUTO_INCREMENT,
  `idapi` SMALLINT(5) unsigned NOT NULL,
  `list_id` VARCHAR(20) NOT NULL,
  `idlang` SMALLINT(5) unsigned NOT NULL default 1,
  PRIMARY KEY (`idlist`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;