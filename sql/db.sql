CREATE TABLE IF NOT EXISTS `mc_mailchimp` (
  `id_api` SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `api_key` VARCHAR(50) DEFAULT NULL,
  PRIMARY KEY (`id_api`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

INSERT INTO `mc_mailchimp` (`api_key`) VALUES
(NULL);

CREATE TABLE IF NOT EXISTS `mc_mailchimp_list` (
  `id_list` SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_api` SMALLINT(5) UNSIGNED NOT NULL,
  `list_id` VARCHAR(20) NOT NULL,
  PRIMARY KEY (`id_list`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `mc_mailchimp_content` (
    `id_content` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
    `id_list` smallint(5) unsigned NOT NULL,
    `id_lang` SMALLINT(5) UNSIGNED NOT NULL default 1,
    `name_list` VARCHAR(180) DEFAULT NULL,
    `active` SMALLINT(1) UNSIGNED NOT NULL default 0,
    PRIMARY KEY (`id_content`),
    KEY `id_contact` (`id_list`,`id_lang`),
    KEY `id_lang` (`id_lang`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE `mc_mailchimp_content`
    ADD CONSTRAINT `mc_mailchimp_content_ibfk_2` FOREIGN KEY (`id_lang`) REFERENCES `mc_lang` (`id_lang`) ON DELETE CASCADE ON UPDATE CASCADE,
    ADD CONSTRAINT `mc_mailchimp_content_ibfk_1` FOREIGN KEY (`id_list`) REFERENCES `mc_mailchimp_list` (`id_list`) ON DELETE CASCADE ON UPDATE CASCADE;

INSERT INTO `mc_admin_access` (`id_role`, `id_module`, `view`, `append`, `edit`, `del`, `action`)
SELECT 1, m.id_module, 1, 1, 1, 1, 1 FROM mc_module as m WHERE name = 'mailchimp';