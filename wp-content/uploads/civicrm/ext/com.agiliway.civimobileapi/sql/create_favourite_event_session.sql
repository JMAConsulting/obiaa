CREATE TABLE IF NOT EXISTS `civicrm_civimobile_favourite_event_session` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `contact_id` INT(10) UNSIGNED NOT NULL,
  `event_session_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_fes_contact_id`
    FOREIGN KEY (`contact_id`)
    REFERENCES `civicrm_contact` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_fes_event_session_id`
    FOREIGN KEY (`event_session_id`)
    REFERENCES `civicrm_civimobile_event_session` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
