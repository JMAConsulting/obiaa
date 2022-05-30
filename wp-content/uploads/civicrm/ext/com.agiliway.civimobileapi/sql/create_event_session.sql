CREATE TABLE IF NOT EXISTS `civicrm_civimobile_event_session` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT NULL,
  `start_time` DATETIME NULL,
  `end_time` DATETIME NULL,
  `event_id` INT NOT NULL,
  `venue_id` INT NULL,
  PRIMARY KEY (`id`)) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
