CREATE TABLE IF NOT EXISTS `civicrm_civimobile_event_session_speaker` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `event_session_id` INT NOT NULL,
  `speaker_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_civicrm_civimobile_event_session_speaker_session_id`
    FOREIGN KEY (`event_session_id`)
    REFERENCES `civicrm_civimobile_event_session` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
