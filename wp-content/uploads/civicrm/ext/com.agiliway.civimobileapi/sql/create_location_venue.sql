
CREATE TABLE IF NOT EXISTS `civicrm_civimobile_location_venue` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` TEXT NOT NULL,
  `description` TEXT NULL,
  `address_description` TEXT NULL,
  `address` TEXT NULL,
  `latitude` TEXT NULL,
  `longitude` TEXT NULL,
  `is_active` TINYINT NOT NULL,
  `location_id` INT NOT NULL,
  `background_color` VARCHAR(30) NOT NULL,
  `border_color` VARCHAR(30) NOT NULL,
  `weight` INT NULL,
  PRIMARY KEY (`id`)) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
