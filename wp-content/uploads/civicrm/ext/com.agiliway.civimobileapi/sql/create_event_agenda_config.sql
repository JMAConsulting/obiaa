CREATE TABLE IF NOT EXISTS `civicrm_civimobile_event_agenda_config` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `event_id` INT NOT NULL,
  `is_active` TINYINT NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
