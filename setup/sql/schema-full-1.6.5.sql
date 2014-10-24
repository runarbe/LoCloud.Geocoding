SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema default_schema
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Table `ds1`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ds1` (
  `id` DOUBLE NULL DEFAULT NULL,
  `title` VARCHAR(255) NULL DEFAULT NULL,
  `description` VARCHAR(255) NULL DEFAULT NULL,
  `country` VARCHAR(255) NULL DEFAULT NULL,
  `region` VARCHAR(255) NULL DEFAULT NULL,
  `type` VARCHAR(255) NULL DEFAULT NULL,
  `imageurl` VARCHAR(255) NULL DEFAULT NULL,
  `latitude` DOUBLE NULL DEFAULT NULL,
  `longitude` DOUBLE NULL DEFAULT NULL,
  INDEX `idx_ds1_pk` (`id` ASC),
  INDEX `idx_ds1_adm0` (`country` ASC),
  INDEX `idx_ds1_adm1` (`region` ASC),
  INDEX `idx_ds1_cat` (`type` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `ds1_adm0`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ds1_adm0` (
  `adm0` VARCHAR(255) NULL DEFAULT NULL,
  `minx` DOUBLE NULL DEFAULT NULL,
  `miny` DOUBLE NULL DEFAULT NULL,
  `maxx` DOUBLE NULL DEFAULT NULL,
  `maxy` DOUBLE NULL DEFAULT NULL)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `ds1_adm1`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ds1_adm1` (
  `adm0` VARCHAR(255) NULL DEFAULT NULL,
  `adm1` VARCHAR(255) NULL DEFAULT NULL,
  `minx` DOUBLE NULL DEFAULT NULL,
  `miny` DOUBLE NULL DEFAULT NULL,
  `maxx` DOUBLE NULL DEFAULT NULL,
  `maxy` DOUBLE NULL DEFAULT NULL)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `ds1_cat`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ds1_cat` (
  `category` VARCHAR(255) NULL DEFAULT NULL,
  `minx` DOUBLE NULL DEFAULT NULL,
  `miny` DOUBLE NULL DEFAULT NULL,
  `maxx` DOUBLE NULL DEFAULT NULL,
  `maxy` DOUBLE NULL DEFAULT NULL)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `ds1_match`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ds1_match` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `fk_ds_id` INT(11) NOT NULL,
  `gc_name` VARCHAR(512) NULL DEFAULT NULL,
  `gc_lon` DOUBLE NOT NULL,
  `gc_lat` DOUBLE NOT NULL,
  `gc_fieldchanges` TEXT NULL DEFAULT NULL,
  `gc_geom` TEXT NULL DEFAULT NULL,
  `gc_usr_id` INT(11) NOT NULL,
  `gc_probability` INT(11) NULL DEFAULT NULL,
  `gc_dbsearch_puri` VARCHAR(512) NULL DEFAULT NULL,
  `created` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` TINYINT(1) NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `idx_match_composite` (`fk_ds_id` ASC, `gc_usr_id` ASC),
  INDEX `idx_match_fk_ds_id` (`fk_ds_id` ASC),
  INDEX `idx_match_gc_usr_id` (`gc_usr_id` ASC),
  INDEX `idx_match_gc_probability` (`gc_probability` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `meta_datasources`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `meta_datasources` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier',
  `ds_title` VARCHAR(50) NOT NULL COMMENT 'Name of a column in the source dataset that contains titles of items to be geocoded',
  `ds_col_pk` VARCHAR(50) NOT NULL COMMENT 'Name of a column in the source dataset that contains a unique identifier for each record',
  `ds_col_name` VARCHAR(50) NOT NULL,
  `ds_col_x` VARCHAR(50) NULL DEFAULT NULL,
  `ds_col_y` VARCHAR(50) NULL DEFAULT NULL,
  `ds_srs` VARCHAR(50) NOT NULL,
  `ds_table` VARCHAR(50) NOT NULL,
  `ds_col_cat` VARCHAR(50) NULL DEFAULT NULL,
  `ds_col_adm0` VARCHAR(50) NULL DEFAULT NULL,
  `ds_col_adm1` VARCHAR(50) NULL DEFAULT NULL,
  `ds_coord_prec` INT(11) NULL DEFAULT NULL,
  `ds_col_image` VARCHAR(50) NULL DEFAULT NULL,
  `ds_col_url` VARCHAR(50) NULL DEFAULT NULL,
  `ds_col_puri` VARCHAR(512) NULL DEFAULT NULL COMMENT 'Name of a column that contains a persistent URI',
  `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` TINYINT(1) NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `meta_dbsearch`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `meta_dbsearch` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `sch_title` VARCHAR(45) NULL DEFAULT NULL,
  `sch_table` VARCHAR(45) NULL DEFAULT NULL,
  `sch_display` VARCHAR(200) NULL DEFAULT NULL,
  `sch_lev1` VARCHAR(200) NULL DEFAULT NULL,
  `sch_lev2` VARCHAR(200) NULL,
  `sch_lev3` VARCHAR(200) NULL DEFAULT NULL,
  `sch_epsg` INT(11) NULL DEFAULT NULL,
  `sch_lon` VARCHAR(200) NULL DEFAULT NULL,
  `sch_lat` VARCHAR(200) NULL DEFAULT NULL,
  `sch_like` VARCHAR(200) NULL DEFAULT NULL,
  `sch_eq` VARCHAR(200) NULL DEFAULT NULL,
  `sch_webservice` VARCHAR(200) NULL DEFAULT NULL,
  `sch_apikey` VARCHAR(200) NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `meta_group`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `meta_group` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(250) NOT NULL,
  `description` VARCHAR(45) NULL,
  `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` TINYINT(1) NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `idx_meta_group_title` (`title` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 11
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `meta_match_template`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `meta_match_template` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `fk_ds_id` INT(11) NOT NULL,
  `gc_name` VARCHAR(512) NULL DEFAULT NULL,
  `gc_lon` DOUBLE NOT NULL,
  `gc_lat` DOUBLE NOT NULL,
  `gc_fieldchanges` TEXT NULL DEFAULT NULL,
  `gc_geom` TEXT NULL DEFAULT NULL,
  `gc_usr_id` INT(11) NOT NULL,
  `gc_probability` INT(11) NULL DEFAULT NULL,
  `gc_confidence` INT(11) NULL DEFAULT NULL,
  `gc_mapresolution` FLOAT NULL DEFAULT NULL,
  `gc_dbsearch_puri` VARCHAR(512) NULL DEFAULT NULL,
  `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` TINYINT(1) NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `idx_match_composite` (`fk_ds_id` ASC, `gc_usr_id` ASC),
  INDEX `idx_match_fk_ds_id` (`fk_ds_id` ASC),
  INDEX `idx_match_gc_usr_id` (`gc_usr_id` ASC),
  INDEX `idx_match_gc_probability` (`gc_probability` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `meta_srs`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `meta_srs` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `srs_name` VARCHAR(150) NOT NULL,
  `srs` TEXT NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `meta_uploads`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `meta_uploads` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `fk_meta_usr_id` INT(11) NOT NULL,
  `fname` VARCHAR(512) NOT NULL,
  `tstamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `meta_usr`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `meta_usr` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `usr` VARCHAR(250) NOT NULL,
  `pwd` VARCHAR(20) NOT NULL,
  `level` INT(11) NOT NULL DEFAULT '5',
  `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` TINYINT(1) NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `idx_usr_usr` (`usr` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 9
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `meta_acl`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `meta_acl` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `src_type` VARCHAR(25) NOT NULL COMMENT 'meta_usr' /* comment truncated */ /*meta_group*/,
  `src_id` INT(11) NOT NULL,
  `tgt_type` VARCHAR(25) NOT NULL COMMENT 'meta_datasources' /* comment truncated */ /*meta_uploads
meta_group
meta_usr*/,
  `tgt_id` INT(11) NOT NULL,
  `access` INT(11) NOT NULL DEFAULT 20 COMMENT 'owner = 10' /* comment truncated */ /*editor = 20
viewer = 30*/,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `idxu_src_tgt` (`src_type` ASC, `src_id` ASC, `tgt_type` ASC, `tgt_id` ASC),
  INDEX `idx_src` (`src_type` ASC, `src_id` ASC),
  INDEX `idc_tgt` (`tgt_type` ASC, `tgt_id` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `sch_geonames_eu500k`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sch_geonames_eu500k` (
  `geonameid` INT(11) NULL DEFAULT NULL,
  `name` VARCHAR(200) NULL DEFAULT NULL,
  `asciiname` VARCHAR(200) NULL DEFAULT NULL,
  `alternatenames` MEDIUMTEXT NULL DEFAULT NULL,
  `latitude` DOUBLE NULL DEFAULT NULL,
  `longitude` DOUBLE NULL DEFAULT NULL,
  `feature class` VARCHAR(1) NULL DEFAULT NULL,
  `feature code` VARCHAR(10) NULL DEFAULT NULL,
  `country code` VARCHAR(2) NULL DEFAULT NULL,
  `cc2` VARCHAR(60) NULL DEFAULT NULL,
  `admin1 code` VARCHAR(20) NULL DEFAULT NULL,
  `admin2 code` VARCHAR(80) NULL DEFAULT NULL,
  `admin3 code` VARCHAR(20) NULL DEFAULT NULL,
  `admin4 code` VARCHAR(20) NULL DEFAULT NULL,
  `population` INT(11) NULL DEFAULT NULL,
  `elevation` SMALLINT(6) NULL DEFAULT NULL,
  `dem` DOUBLE NULL DEFAULT NULL,
  `timezone` VARCHAR(40) NULL DEFAULT NULL,
  `modification date` DATETIME NULL DEFAULT NULL)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `sch_geonames_template`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sch_geonames_template` (
  `geonameid` INT(11) NULL DEFAULT NULL,
  `name` VARCHAR(200) NULL DEFAULT NULL,
  `asciiname` VARCHAR(200) NULL DEFAULT NULL,
  `alternatenames` MEDIUMTEXT NULL DEFAULT NULL,
  `latitude` DOUBLE NULL DEFAULT NULL,
  `longitude` DOUBLE NULL DEFAULT NULL,
  `feature_class` VARCHAR(1) NULL DEFAULT NULL,
  `feature_code` VARCHAR(10) NULL DEFAULT NULL,
  `country_code` VARCHAR(2) NULL DEFAULT NULL,
  `cc2` VARCHAR(60) NULL DEFAULT NULL,
  `admin1_code` VARCHAR(20) NULL DEFAULT NULL,
  `admin2_code` VARCHAR(80) NULL DEFAULT NULL,
  `admin3_code` VARCHAR(20) NULL DEFAULT NULL,
  `admin4_code` VARCHAR(20) NULL DEFAULT NULL,
  `population` INT(11) NULL DEFAULT NULL,
  `elevation` SMALLINT(6) NULL DEFAULT NULL,
  `dem` DOUBLE NULL DEFAULT NULL,
  `timezone` VARCHAR(40) NULL DEFAULT NULL,
  `modification_date` DATETIME NULL DEFAULT NULL,
  `geonames_uri` VARCHAR(512) NULL DEFAULT NULL)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `meta_access`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `meta_access` (
  `id` INT NOT NULL,
  `label` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `meta_usr_level`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `meta_usr_level` (
  `id` INT(11) NOT NULL,
  `label` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- View `meta_usr_group_acl`
-- -----------------------------------------------------
CREATE  OR REPLACE VIEW `meta_usr_group_acl` AS
    SELECT distinct
        id, src_id usr_id, tgt_id group_id, access
    FROM
        meta_acl
    WHERE
        src_type = 'meta_usr'
            AND tgt_type = 'meta_group';

-- -----------------------------------------------------
-- View `meta_usr_datasources_acl`
-- -----------------------------------------------------
CREATE  OR REPLACE VIEW `meta_usr_datasources_acl` AS
    SELECT distinct
        id, src_id usr_id, tgt_id datasource_id, access
    FROM
        meta_acl
    WHERE
        src_type = 'meta_usr'
            AND tgt_type = 'meta_datasources';

-- -----------------------------------------------------
-- View `meta_usr_usr_acl`
-- -----------------------------------------------------
CREATE  OR REPLACE VIEW `meta_usr_usr_acl` AS
    SELECT distinct
        id, src_id usr_id, tgt_id usr_id2, access
    FROM
        meta_acl
    WHERE
        src_type = 'meta_usr'
            AND tgt_type = 'meta_usr';

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `ds1`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `ds1` (`id`, `title`, `description`, `country`, `region`, `type`, `imageurl`, `latitude`, `longitude`) VALUES (1, 'Stonehenge', 'The world\'s most famous prehistoric monument. People around the world consider it as a sacred site and they associate the ceremonial place with the super natural world.', 'United Kingdom', 'Wiltshire', 'Monument', 'http://www.nationsonline.org/gallery/Monuments/Stonehenge.jpg', 51.1788, -1.8262);
INSERT INTO `ds1` (`id`, `title`, `description`, `country`, `region`, `type`, `imageurl`, `latitude`, `longitude`) VALUES (2, 'Acropolis', 'The Acropolis of Athens can be seen as a symbol for the Ancient Greek World, the classical period of the Hellenic civilization.', 'Greece', 'Athens', 'Monument', 'http://www.nationsonline.org/gallery/Monuments/Acropolis_of_Athens.jpg', NULL, NULL);
INSERT INTO `ds1` (`id`, `title`, `description`, `country`, `region`, `type`, `imageurl`, `latitude`, `longitude`) VALUES (3, 'Colosseum', 'The Flavian Amphitheater is an iconic symbol for Rome the \'Eternal City\' as well as for the civilization of the Imperial Roman Empire', 'Italy', 'Rome', 'Monument', 'http://www.nationsonline.org/gallery/Monuments/Colosseum_in_Rome.jpg', NULL, NULL);
INSERT INTO `ds1` (`id`, `title`, `description`, `country`, `region`, `type`, `imageurl`, `latitude`, `longitude`) VALUES (4, 'Eiffel Tower', 'Few things symbolize Paris and France like this monument, it is the foremost universal icon of the French way of life of bon vivant and savoir vivre.', 'France', 'Paris', 'City icon', 'http://www.nationsonline.org/gallery/Monuments/Eiffel_Tower.jpg', 48.8582, 2.2945);
INSERT INTO `ds1` (`id`, `title`, `description`, `country`, `region`, `type`, `imageurl`, `latitude`, `longitude`) VALUES (5, 'Big Ben', 'Clock Tower, Palace of Westminster, London,United Kingdom, is a symbol for London as well as an icon for the British way of life.', 'United Kingdom', 'London', 'City icon', 'http://www.nationsonline.org/gallery/Monuments/Big_Ben.jpg', NULL, NULL);
INSERT INTO `ds1` (`id`, `title`, `description`, `country`, `region`, `type`, `imageurl`, `latitude`, `longitude`) VALUES (6, 'Leaning tower of Pisa', 'The Leaning Tower of Pisa is the result of a colossal miscalculation. Everyone makes mistakes, but most people\'s mistakes does not weigh 14,500 tonnes. But some mistakes are excusable, after a while. ', 'Italy', 'Pisa', 'City icon', 'http://www.nationsonline.org/gallery/Monuments/Leaning_Tower_of_Pisa.jpg', NULL, NULL);
INSERT INTO `ds1` (`id`, `title`, `description`, `country`, `region`, `type`, `imageurl`, `latitude`, `longitude`) VALUES (7, 'Cologne Cathedral', 'The Cologne Cathedral is the largest Gothic church in northern Europe and the dominant landmark of the city of Cologne.', 'Germany', 'Cologne', 'City icon', 'http://www.nationsonline.org/gallery/Monuments/Cologne_Cathedral.jpg', NULL, NULL);
INSERT INTO `ds1` (`id`, `title`, `description`, `country`, `region`, `type`, `imageurl`, `latitude`, `longitude`) VALUES (8, 'Brandenburg Gate', 'Main symbol of Berlin and today also a symbol for the reunited Germany.', 'Germany', 'Berlin', 'City icon', 'http://www.nationsonline.org/gallery/Monuments/Brandenburger_Tor.jpg', NULL, NULL);
INSERT INTO `ds1` (`id`, `title`, `description`, `country`, `region`, `type`, `imageurl`, `latitude`, `longitude`) VALUES (9, 'Hagia Sophia', 'A former patriarchal basilica, the largest cathedral in the world for nearly a thousand years. In 1453, Constantinople (today Istanbul) was conquered by the Ottoman Turks and Sultan Mehmed II ordered the building to be converted into a mosque.', 'Turkey', 'Istanbul', 'Place of worship', 'http://www.nationsonline.org/gallery/Monuments/Hagia-Sophia.jpg', NULL, NULL);
INSERT INTO `ds1` (`id`, `title`, `description`, `country`, `region`, `type`, `imageurl`, `latitude`, `longitude`) VALUES (10, 'Basilica of St Peter', 'One of the symbols and focal points for the Catholic faith, part of Vatican City, the papal residence. ', 'Vatican', 'Vatican City', 'Place of worship', 'http://www.nationsonline.org/gallery/Monuments/St-Peter-Holy-Sea.jpg', NULL, NULL);
INSERT INTO `ds1` (`id`, `title`, `description`, `country`, `region`, `type`, `imageurl`, `latitude`, `longitude`) VALUES (11, 'Schönbrunn Palace', 'The palace was one of the residences of the Habsburg empire in', 'Austria', 'Vienna', 'Palace', 'http://www.nationsonline.org/gallery/Monuments/Schloss_Schoenbrunn.jpg', NULL, NULL);
INSERT INTO `ds1` (`id`, `title`, `description`, `country`, `region`, `type`, `imageurl`, `latitude`, `longitude`) VALUES (12, 'Palace of Versailles (Château de Versailles)', 'The royal chateau was built by the Sun King Louis XIV, it was (and still is) a symbol of absolute monarchy expressed in stone and environment.', 'France', 'Paris', 'Palace', 'http://www.nationsonline.org/gallery/Monuments/Versailles_Palace.jpg', NULL, NULL);

COMMIT;


-- -----------------------------------------------------
-- Data for table `ds1_adm0`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `ds1_adm0` (`adm0`, `minx`, `miny`, `maxx`, `maxy`) VALUES ('Austria', NULL, NULL, NULL, NULL);
INSERT INTO `ds1_adm0` (`adm0`, `minx`, `miny`, `maxx`, `maxy`) VALUES ('France', 2.2945, 48.8582, 2.2945, 48.8582);
INSERT INTO `ds1_adm0` (`adm0`, `minx`, `miny`, `maxx`, `maxy`) VALUES ('Germany', NULL, NULL, NULL, NULL);
INSERT INTO `ds1_adm0` (`adm0`, `minx`, `miny`, `maxx`, `maxy`) VALUES ('Greece', NULL, NULL, NULL, NULL);
INSERT INTO `ds1_adm0` (`adm0`, `minx`, `miny`, `maxx`, `maxy`) VALUES ('Italy', NULL, NULL, NULL, NULL);
INSERT INTO `ds1_adm0` (`adm0`, `minx`, `miny`, `maxx`, `maxy`) VALUES ('Turkey', NULL, NULL, NULL, NULL);
INSERT INTO `ds1_adm0` (`adm0`, `minx`, `miny`, `maxx`, `maxy`) VALUES ('United Kingdom', -1.8262, 51.1788, -1.8262, 51.1788);
INSERT INTO `ds1_adm0` (`adm0`, `minx`, `miny`, `maxx`, `maxy`) VALUES ('Vatican', NULL, NULL, NULL, NULL);

COMMIT;


-- -----------------------------------------------------
-- Data for table `ds1_adm1`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `ds1_adm1` (`adm0`, `adm1`, `minx`, `miny`, `maxx`, `maxy`) VALUES ('Austria', 'Vienna', NULL, NULL, NULL, NULL);
INSERT INTO `ds1_adm1` (`adm0`, `adm1`, `minx`, `miny`, `maxx`, `maxy`) VALUES ('France', 'Paris', 2.2945, 48.8582, 2.2945, 48.8582);
INSERT INTO `ds1_adm1` (`adm0`, `adm1`, `minx`, `miny`, `maxx`, `maxy`) VALUES ('Germany', 'Berlin', NULL, NULL, NULL, NULL);
INSERT INTO `ds1_adm1` (`adm0`, `adm1`, `minx`, `miny`, `maxx`, `maxy`) VALUES ('Germany', 'Cologne', NULL, NULL, NULL, NULL);
INSERT INTO `ds1_adm1` (`adm0`, `adm1`, `minx`, `miny`, `maxx`, `maxy`) VALUES ('Greece', 'Athens', NULL, NULL, NULL, NULL);
INSERT INTO `ds1_adm1` (`adm0`, `adm1`, `minx`, `miny`, `maxx`, `maxy`) VALUES ('Italy', 'Pisa', NULL, NULL, NULL, NULL);
INSERT INTO `ds1_adm1` (`adm0`, `adm1`, `minx`, `miny`, `maxx`, `maxy`) VALUES ('Italy', 'Rome', NULL, NULL, NULL, NULL);
INSERT INTO `ds1_adm1` (`adm0`, `adm1`, `minx`, `miny`, `maxx`, `maxy`) VALUES ('Turkey', 'Istanbul', NULL, NULL, NULL, NULL);
INSERT INTO `ds1_adm1` (`adm0`, `adm1`, `minx`, `miny`, `maxx`, `maxy`) VALUES ('United Kingdom', 'London', NULL, NULL, NULL, NULL);
INSERT INTO `ds1_adm1` (`adm0`, `adm1`, `minx`, `miny`, `maxx`, `maxy`) VALUES ('United Kingdom', 'Wiltshire', -1.8262, 51.1788, -1.8262, 51.1788);
INSERT INTO `ds1_adm1` (`adm0`, `adm1`, `minx`, `miny`, `maxx`, `maxy`) VALUES ('Vatican', 'Vatican City', NULL, NULL, NULL, NULL);

COMMIT;


-- -----------------------------------------------------
-- Data for table `ds1_cat`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `ds1_cat` (`category`, `minx`, `miny`, `maxx`, `maxy`) VALUES ('City icon', 2.2945, 48.8582, 2.2945, 48.8582);
INSERT INTO `ds1_cat` (`category`, `minx`, `miny`, `maxx`, `maxy`) VALUES ('Monument', -1.8262, 51.1788, -1.8262, 51.1788);
INSERT INTO `ds1_cat` (`category`, `minx`, `miny`, `maxx`, `maxy`) VALUES ('Palace', NULL, NULL, NULL, NULL);
INSERT INTO `ds1_cat` (`category`, `minx`, `miny`, `maxx`, `maxy`) VALUES ('Place of worship', NULL, NULL, NULL, NULL);

COMMIT;


-- -----------------------------------------------------
-- Data for table `meta_datasources`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `meta_datasources` (`id`, `ds_title`, `ds_col_pk`, `ds_col_name`, `ds_col_x`, `ds_col_y`, `ds_srs`, `ds_table`, `ds_col_cat`, `ds_col_adm0`, `ds_col_adm1`, `ds_coord_prec`, `ds_col_image`, `ds_col_url`, `ds_col_puri`, `created`, `updated`, `deleted`) VALUES (1, 'LoCloud Demo Dataset', 'id', 'title', 'longitude', 'latitude', '4326', 'ds1', 'type', 'country', 'region', 6, 'imageurl', '', NULL, '2014-07-15 00:04:34', '2014-07-15 00:04:34', 0);

COMMIT;


-- -----------------------------------------------------
-- Data for table `meta_dbsearch`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `meta_dbsearch` (`id`, `sch_title`, `sch_table`, `sch_display`, `sch_lev1`, `sch_lev2`, `sch_lev3`, `sch_epsg`, `sch_lon`, `sch_lat`, `sch_like`, `sch_eq`, `sch_webservice`, `sch_apikey`) VALUES (1, 'European cities > 500k', 'sch_geonames_eu500k', 'name', 'admin1 code', 'admin2 code', 'admin3 code', 4326, 'longitude', 'latitude', 'name;asciiname;alternatenames', NULL, 'ws-search-geonames', NULL);
INSERT INTO `meta_dbsearch` (`id`, `sch_title`, `sch_table`, `sch_display`, `sch_lev1`, `sch_lev2`, `sch_lev3`, `sch_epsg`, `sch_lon`, `sch_lat`, `sch_like`, `sch_eq`, `sch_webservice`, `sch_apikey`) VALUES (2, 'LoCloud Geocoding API', 'Geonames', 'PlaceName', NULL, NULL, NULL, 4326, 'PlaceX', 'PlaceY', NULL, NULL, 'ws-search-logeo', NULL);
INSERT INTO `meta_dbsearch` (`id`, `sch_title`, `sch_table`, `sch_display`, `sch_lev1`, `sch_lev2`, `sch_lev3`, `sch_epsg`, `sch_lon`, `sch_lat`, `sch_like`, `sch_eq`, `sch_webservice`, `sch_apikey`) VALUES (3, 'Geonames API (Names)', 'searchJSON', NULL, NULL, NULL, NULL, 4326, NULL, NULL, NULL, NULL, 'ws-search-geonamesapi', 'locloudgc');
INSERT INTO `meta_dbsearch` (`id`, `sch_title`, `sch_table`, `sch_display`, `sch_lev1`, `sch_lev2`, `sch_lev3`, `sch_epsg`, `sch_lon`, `sch_lat`, `sch_like`, `sch_eq`, `sch_webservice`, `sch_apikey`) VALUES (4, 'Geonames API (Wikipedia)', 'wikipediaSearchJSON', NULL, NULL, NULL, NULL, 4326, NULL, NULL, NULL, NULL, 'ws-search-geonamesapi', 'locloudgc');

COMMIT;


-- -----------------------------------------------------
-- Data for table `meta_group`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `meta_group` (`id`, `title`, `description`, `created`, `updated`, `deleted`) VALUES (1, 'Administators', 'Built-in group for administrators', NULL, NULL, NULL);
INSERT INTO `meta_group` (`id`, `title`, `description`, `created`, `updated`, `deleted`) VALUES (2, 'All users', 'Built-in group for all users', NULL, NULL, NULL);
INSERT INTO `meta_group` (`id`, `title`, `description`, `created`, `updated`, `deleted`) VALUES (3, 'Demo group', 'Built-in test group', NULL, NULL, NULL);

COMMIT;


-- -----------------------------------------------------
-- Data for table `meta_usr`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `meta_usr` (`id`, `usr`, `pwd`, `level`, `created`, `updated`, `deleted`) VALUES (1, 'admin', 'admin', 1, NULL, NULL, NULL);
INSERT INTO `meta_usr` (`id`, `usr`, `pwd`, `level`, `created`, `updated`, `deleted`) VALUES (2, 'user', 'user', 6, NULL, NULL, NULL);
INSERT INTO `meta_usr` (`id`, `usr`, `pwd`, `level`, `created`, `updated`, `deleted`) VALUES (3, 'guest', 'guest', 99, NULL, NULL, NULL);

COMMIT;


-- -----------------------------------------------------
-- Data for table `meta_acl`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `meta_acl` (`id`, `src_type`, `src_id`, `tgt_type`, `tgt_id`, `access`) VALUES (NULL, 'meta_usr', 1, 'meta_datasources', 1, 10);
INSERT INTO `meta_acl` (`id`, `src_type`, `src_id`, `tgt_type`, `tgt_id`, `access`) VALUES (NULL, 'meta_usr', 2, 'meta_datasources', 1, 20);
INSERT INTO `meta_acl` (`id`, `src_type`, `src_id`, `tgt_type`, `tgt_id`, `access`) VALUES (NULL, 'meta_usr', 3, 'meta_datasources', 1, 30);
INSERT INTO `meta_acl` (`id`, `src_type`, `src_id`, `tgt_type`, `tgt_id`, `access`) VALUES (NULL, 'meta_usr', 1, 'meta_usr', 1, 10);
INSERT INTO `meta_acl` (`id`, `src_type`, `src_id`, `tgt_type`, `tgt_id`, `access`) VALUES (NULL, 'meta_usr', 1, 'meta_usr', 2, 10);
INSERT INTO `meta_acl` (`id`, `src_type`, `src_id`, `tgt_type`, `tgt_id`, `access`) VALUES (NULL, 'meta_usr', 1, 'meta_usr', 3, 10);

COMMIT;


-- -----------------------------------------------------
-- Data for table `sch_geonames_eu500k`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (2761369, 'Vienna', 'Vienna', 'Bec,Bech,Becs,Beç,Beč,Bienne,Bécs,Dunaj,VIE,Vena,Viden,Viden\',Vieden,Viedeň,Viena,Vienna,Vienne,Vieno,Viin,Vin,Vinarborg,Vindobona,Viyana,Vídeň,Vín,Vínarborg,Wenen,Wieden,Wiedeń,Wien,bin,weiynna,Βιέννη,Беч,Вена,Виена,Відень,เวียนนา,ቪየና,빈', 48.20849, 16.37208, 'P', 'PPLC', 'AT', NULL, '09', '900', '901', NULL, 1691468, 171, 193, 'Europe/Vienna', '2013-02-07 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (3191281, 'Sarajevo', 'Sarajevo', 'Bosna-Sarai,Gorad Saraeva,SJJ,Saarayego,Saireavo,Sairéavó,Saraevo,Saragebo,Saragiebo,Saragievo,Saraievo,Sarajeva,Sarajevas,Sarajevo,Sarajevó,Sarajewo,Sarajèvo,Saraybosna,Sarayevo,Sarayevu,Seraium,Serayevo,Szarajevo,Szarajevó,Vrh Bosna,carayevo,sai la ye fu,salayebo,saraevo,saraevu~o,sarajyww,sarayebho,sarayevho,sarayevo,sarayewo,sarayww,sarayyfw,srayyfw,srayyww,Σαράγεβο,Σαράγιεβο,Σαραγιεβο,Горад Сараева,Сараево,Сараєво,Сарајево,Сараѥво,Սարաևո,סאראיעווא,סראייבו,ساراجیوو,ساراييفو,سارايېۋو,سارایوو,سارایێڤۆ,سرائیوو,سراييفو,सारायेव्हो,সারায়েভো,ਸਾਰਾਯੇਵੋ,சாரயேவோ,ซาราเยโว,ས་ར་ཇི་བོ།,სარაევო,ሳራዬቮ,サラエヴォ,塞拉耶佛,사라예보', 43.84864, 18.35644, 'P', 'PPLC', 'BA', NULL, '01', '3343737', NULL, NULL, 696731, NULL, 509, 'Europe/Sarajevo', '2012-11-15 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (2800866, 'Brussels', 'Brussels', 'An Bhruiseil,An Bhruiséil,BRU,Breissel,Brisel,Brisele,Briuselis,Brjuksel,Brjusel\',Brjussel\',Brueksel,Bruessel,Bruesszel,Bruiseal,Bruksel,Bruksela,Brukseli,Brukselo,Brusehl\',Brusel,Brusela,Bruselas,Bruseles,Bruselj,Bruselo,Brusel·les,Brussel,Brussele,Brussels,Brussel·les,Bruxel,Bruxelas,Bruxellae,Bruxelles,Brwsel,Bryssel,Bryusel,Bryxelles,Bréissel,Brüksel,Brüssel,Brüsszel,Citta di Bruxelles,Città di Bruxelles,Kota Brusel,beulwisel,braselsa,brassels,briuseli,brwksl,brysl,bu lu sai er,buryusseru,Βρυξέλλες,Брисел,Брусэль,Брюксел,Брюсель,Брюссель,Բրյուսել,בריסל,بروكسل,بروکسل,بريۇسسېل,ܒܪܘܟܣܠ,ब्रसेल्स,บรัสเซลส์,ბრიუსელი,ブリュッセル,布鲁塞尔,브뤼셀', 50.85045, 4.34878, 'P', 'PPLC', 'BE', NULL, 'BRU', 'BRU', '21', '21004', 1019022, NULL, 28, 'Europe/Brussels', '2012-12-03 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (727011, 'Sofia', 'Sofia', 'Gorad Safija,SOF,Serdica,Sofi,Sofia,Sofie,Sofii,Sofij,Sofija,Sofija osh,Sofio,Sofiya,Sofiýa,Sofja,Sofya,Sofía,Soifia,Sophia,Sredets,Szofia,Szófia,Sòfia,Sófia,Sófía,Sóifia,Ulpia Serdica,cohviya,saphiya,seaphiya,sofeiy,sofi\'a,sofia,sophiya,sopia,suo fei ya,swfya,swfyh,swpyh,Σόφια,Горад Сафія,Софи,Софий,София,София ош,Софија,Софія,Софїꙗ,Սոֆիա,סאפיע,סופיה,سوفىيە,سۆفیا,صوفيا,صوفیه,صوفیہ,सोफिया,সফিয়া,ਸੋਫ਼ੀਆ,சோஃவியா,സോഫിയ,โซเฟีย,སོ་ཧྥི་ཡ།,სოფია,ሶፊያ,ソフィア,索菲亞,소피아,', 42.69751, 23.32415, 'P', 'PPLC', 'BG', NULL, '42', 'SOF46', NULL, NULL, 1152556, NULL, 562, 'Europe/Sofia', '2011-06-16 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (625144, 'Minsk', 'Minsk', 'Gorad Minsk,MSQ,Mins\'k,Minsc,Minscum,Minsk,Minsk - Minsk,Minsk - Мінск,Minsk osh,Minska,Minskaj,Minskas,Minsko,Minszk,Mińsk,Myensk,Myenyesk,Mînsk,ming si ke,ming si ke shi,minseukeu,minsk,minsuku,mnsk,mynsk,mynsq,mynysky,Μινσκ,Горад Мінск,Минск,Минск ош,Минскай,Мінск,Мінськ,Мѣньскъ,Մինսկ,מינסק,منسک,مىنىسكى,مينسك,مینسک,मिन्‍स्‍क,மின்ஸ்க்,ಮಿನ್ಸ್ಕ್,മിൻസ്ക്,มินสก์,མིན་སིཀ།,მინსკი,ミンスク,明斯克,明斯克市,민스크', 53.9, 27.56667, 'P', 'PPLC', 'BY', NULL, '04', NULL, NULL, NULL, 1742124, NULL, 222, 'Europe/Minsk', '2013-11-08 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (2618425, 'Copenhagen', 'Copenhagen', 'CPH,Cobanhavan,Copenaga,Copenaghen,Copenaguen,Copenhaga,Copenhagen,Copenhague,Copenhaguen,Copenhaguen - Kobenhavn,Copenhaguen - København,Cóbanhávan,Hafnia,Kapehngagen,Kaupmannahoefn,Kaupmannahöfn,Keypmannahavn,Kjobenhavn,Kjopenhamn,Kjøpenhamn,Kobenhamman,Kobenhaven,Kobenhavn,Kodan,Kodaň,Koebenhavn,Koeoepenhamina,Koepenhamn,Kopenage,Kopenchage,Kopengagen,Kopenhaagen,Kopenhag,Kopenhaga,Kopenhage,Kopenhagen,Kopenhagena,Kopenhago,Kopenhāgena,Kopenkhagen,Koppenhaga,Koppenhága,Kòpenhaga,Köbenhavn,Köpenhamn,Kööpenhamina,København,Københámman,ge ben ha gen,khopenheken,kopanahagana,kopenahagena,kopenahegena,kopenhagen,kwbnhaghn,kwpnhgn,qwpnhgn,Κοπεγχάγη,Капэнгаген,Копенгаген,Копенхаген,Կոպենհագեն,קופנהאגן,קופנהגן,كوبنهاغن,كوپېنھاگېن,ܟܘܦܢܗܓܢ,कोपनहागन,কোপেনহাগেন,কোপেনহেগেন,โคเปนเฮเกน,ཀའོ་པེན་ཧ་ཀེན,კოპენჰაგენი,ኮፐንሀገን,ኮፕንሀግ,コペンハーゲン,哥本哈根,코펜하겐', 55.67594, 12.56553, 'P', 'PPLC', 'DK', NULL, '17', '101', NULL, NULL, 1153615, NULL, 14, 'Europe/Copenhagen', '2012-11-26 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (3067696, 'Prague', 'Prague', 'PRG,Praag,Prag,Praga,Pragae,Prago,Prague,Praha,Pràg,Prág,Prága,Prâg,Prāga,bragh,bu la ge,peulaha,prag,praga,prak,prg,puraha,Πράγα,Праг,Прагæ,Прага,פראג,براغ,پراگ,پراگا,ܦܪܓ,ปราก,པུ་ལ་ཁེ,პრაღა,ፕራግ,プラハ,布拉格,프라하', 50.08804, 14.42076, 'P', 'PPLC', 'CZ', NULL, '52', NULL, NULL, NULL, 1165581, NULL, 202, 'Europe/Prague', '2013-11-25 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (2825297, 'Stuttgart', 'Stuttgart', 'Estugarda,Gorad Shtutgart,STR,Shhutgart,Shtutgart,Shtutgarti,Shtuttgart,Stocarda,Stoccarda,Stoutnkarde,Stucarda,Stuggart,Stutgardia,Stutgartas,Stutgarte,Stutgarto,Stutqart,Stuttgart,ashtwtgart,ch tuthth kar th,icututkart,shtwtgart,shtwtghart,shuto~uttogaruto,si tu jia te,stutagarta,stwtgrt,syututeugaleuteu,Ştutqart,Štutgartas,Štutgarte,Στουτγκάρδη,Горад Штутгарт,Штутгарт,Штуттгарт,Щутгарт,שטוטגארט,שטוטגרט,اشتوتگارت,سٹٹگارٹ,شتوتغارت,شتوتگارت,شٹوٹگارٹ,श्टुटगार्ट,স্টুটগার্ট,સ્ટુટગાર્ટ,இசுடுட்கார்ட்,സ്റ്റുട്ട്ഗാർട്ട്,ชตุทท์การ์ท,შტუტგარტი,シュトゥットガルト,斯图加特,슈투트가르트', 48.78232, 9.17702, 'P', 'PPLA', 'DE', NULL, '01', '081', '08111', '08111000', 589793, NULL, 252, 'Europe/Berlin', '2013-02-19 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (2867714, 'München', 'Muenchen', 'Gorad Mjunkhen,Lungsod ng Muenchen,Lungsod ng München,MUC,Minca,Minche,Minga,Minhen,Minhene,Minkhen,Miunchenas,Mjunkhen,Mnichov,Mnichow,Mníchov,Monachium,Monacho,Monaco,Monaco \'e Baviera,Monaco de Baviera,Monaco di Baviera,Monacu,Monacu di Baviera,Monacum,Muenchen,Muenegh,Muenhen,Muenih,Munchen,Munhen,Munic,Munich,Munich ed Baviera,Munih,Munike,Munique,Munix,Munkeno,Munkhen,Munîh,Mynihu,Myunxen,Myunxén,Mònacu,Mùnich ëd Baviera,Múnic,Múnich,München,Münegh,Münhen,Münih,mi wnik,mi\'unikha,miunkheni,miyunik,mu ni hei,mwinhen,mwnykh,mynkn,myunhen,myunik,myunikha,myunsena,mywnkh,mywnykh,Μόναχο,Горад Мюнхен,Минхен,Мюнхен,Мүнхен,Мүнхэн,Мӱнхен,Մյունխեն,מינכן,مونیخ,ميونخ,ميونيخ,میونخ,म्युन्शेन,म्यूनिख,মিউনিখ,மியூனிக்,ಮ್ಯೂನಿಕ್,มิวนิก,မြူးနစ်ချ်မြို့,მიუნხენი,ミュンヘン,慕尼黑,뮌헨', 48.13743, 11.57549, 'P', 'PPLA', 'DE', NULL, '02', '091', '09162', '09162000', 1260391, NULL, 524, 'Europe/Berlin', '2013-02-19 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (2879139, 'Leipzig', 'Leipzig', 'Gorad Lejpcyg,LEJ,Laeipcig,Lajpcig,Lajpcigu,Lajpcik,Leipciga,Leipcigas,Leipsia,Leipzig,Lejpcig,Leypsiq,Leypzig,Lipcse,Lipekika,Lipsca,Lipsia,Lipsk,Lipsko,Läipcig,Léypzig,Lípsia,la\'ipajhisa,lai bi xi,laipcik,laipeuchihi,laiptsigi,laybzygh,laypzyg,lip sik,lipajiga,liph sic,lypzsh,lyypzyg,raiputsu~ihi,Λειψία,Горад Лейпцыг,Лайпциг,Лајпциг,Лейпциг,Լայպցիգ,לייפציג,لايبزيغ,لایپزیگ,لیپزش,लाइपझिश,लिपजिग,লাইপ্‌ৎসিশ,லைப்சிக்,ไลป์ซิก,ไลพ์ซิจ,လိုက်ပဆစ်မြို့,ლაიფციგი,ライプツィヒ,莱比锡,라이프치히', 51.33962, 12.37129, 'P', 'PPLA2', 'DE', NULL, '13', '147', '14713', '14713000', 504971, NULL, 116, 'Europe/Berlin', '2011-07-06 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (2886242, 'Köln', 'Koeln', 'Augusta Ubiorum,CGN,Cologna,Cologne,Colonha,Colonia,Colonia Agrippina,Colonia Agrippinensis,Colonia Claudia Ara Agrippinensium,Colònia,Colônia,Cołogna,Culonia,Cwlen,Gorad Kjol\'n,K\'oln,Kel\'n,Keln,Kelnas,Kelne,Kelni,Keulen,Kjol\'n,Koelle,Koeln,Kol\'n,Kolin nad Rynem,Kolin nad Rynom,Koln,Koloin,Kolon,Kolonia,Kolonjo,Kolín nad Rýnem,Kolín nad Rýnom,Kyoln,Këlni,Kölle,Köln,Lungsod ng Cologne,Oppidum Ubiorum,kalon,ke long,kerun,kholoy,kln,klwn,koelleun,koln,kolon,kwlwn,kwlwnya,kyolna,qln,Ķelne,Κολωνία,Горад Кёльн,Келн,Кельн,Кьолн,Кёльн,Кӧльн,Көлн,Քյոլն,קלן,קעלן,كولونيا,کلن,کلون,کولون,क्योल्न,கோல்ன்,కొలోన్,ಕಲೋನ್,โคโลญ,კელნი,ኮልን,ケルン,科隆,쾰른', 50.93333, 6.95, 'P', 'PPLA2', 'DE', NULL, '07', '053', '05315', '05315000', 963395, NULL, 58, 'Europe/Berlin', '2013-03-06 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (2910831, 'Hannover', 'Hannover', 'Annobero,Gannovehr,Gannover,Gorad Ganover,HAJ,Hannauver,Hannober,Hannova,Hannover,Hannovera,Hannovere,Hannower,Hannóver,Hanobhar,Hanofer,Hanofér,Hanover,Hanoveri,Hanoveris,Hanovra,Hanovre,Hanovro,Hanower,Hanowery,Hanóver,Hanôver,Honovere,Hànobhar,IHanoveri,Khannover,Khanover,han no wexr,han nuo wei,hanobeo,hanofa,hanophara,hanoveri,hanwfr,hnwbr,Αννόβερο,Ганновер,Ганновэр,Горад Гановер,Ханновер,Хановер,Հաննովեր,האנאווער,הנובר,هانوفر,ھانۆفەر,ہینوور,हानोफर,হানোফার,ฮันโนเวอร์,ჰანოვერი,ハノーファー,汉诺威,하노버', 52.37052, 9.73322, 'P', 'PPLA', 'DE', NULL, '06', '00', NULL, NULL, 515140, NULL, 57, 'Europe/Berlin', '2012-06-11 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (2911298, 'Hamburg', 'Hamburg', 'Amborg,Ambourgo,Amburgo,Ciutat d\'Hamburg,Estat d\'Hamburg,Gamburg,HAM,Hamborg,Hambourg,Hamburg,Hamburga,Hamburgas,Hamburgo,Hamburgu,Hamburgum,Hamburk,Hampuri,Hanburgo,Khamburg,ham bur k,hambuleukeu,hamburgi,hambwrg,hambwrgh,han bao,han bao shi,hanburuku,hmbwrg,Αμβούργο,Гамбург,Гамбурґ,Хамбург,Համբուրգ,המבורג,هامبورغ,هامبورگ,ܗܡܒܘܪܓ,ฮัมบูร์ก,ჰამბურგი,ハンブルク,汉堡,汉堡市,함부르크', 53.57532, 10.01534, 'P', 'PPLA', 'DE', NULL, '04', NULL, NULL, NULL, 1739117, NULL, 8, 'Europe/Berlin', '2012-07-04 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (2925533, 'Frankfurt am Main', 'Frankfurt am Main', 'FRA,Francfort,Francfort - Frankfurt am Main,Francfort d\'o Meno,Francfort del Meno,Francfort sul Main,Francfort-sur-le-Main,Francfòrt sul Main,Francoforte,Francoforte sul Meno,Francofurtum ad Moenum,Francuforti supro Menu,Francuforti suprô Menu,Frankford-on-Main,Frankfort,Frankfort an\'n Main,Frankfort an’n Main,Frankfort on the Main,Frankfurt,Frankfurt am Main,Frankfurt del Main,Frankfurt na Majn,Frankfurt na Majni,Frankfurt nad Menem,Frankfurt nad Mohanem,Frankfurt nad Mohanom,Frankfurt-na-Majne,Frankfurt/Main,Frankfurtas prie Maino,Frankfurte pie Mainas,Frankfurto ce Majno,Frankfurto ĉe Majno,Fráncfort,Fráncfort - Frankfurt am Main,Fráncfort d\'o Meno,Fráncfort del Meno,Phran\'kphourte,fa lan ke fu,frankfwrt,peulangkeupuleuteu,prnqpwrt,Φρανκφούρτη,Франкфурт на Майн,Франкфурт на Мајни,Франкфурт-на-Майне,פרנקפורט,فرانكفورت,فرانکفورت,แฟรงค์เฟิร์ต,ფრანკფურტ-ამ-მაინი,フランクフルト・アム・マイン,法兰克福,프랑크푸르트', 50.11552, 8.68417, 'P', 'PPL', 'DE', NULL, '05', '064', '06412', NULL, 650000, NULL, 113, 'Europe/Berlin', '2013-01-25 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (2928810, 'Essen', 'Essen', 'Asnithi,Assindia,ESS,Ehssen,Esen,Esenas,Esene,Esse,Essen,Gorad Ehsehn,ai sen,asn,ecan,esen,esena,eseni,essen,xes sein,Ésén,Έσσεν,Горад Эсэн,Есен,Ессен,Эссен,אסן,إسن,اسن,منشنگلیڈباخ,एसेन,எசன்,เอสเซิน,ესენი,エッセン,埃森,에센', 51.45657, 7.01228, 'P', 'PPL', 'DE', NULL, '07', '051', NULL, NULL, 593085, NULL, 83, 'Europe/Berlin', '2012-11-23 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (2934246, 'Düsseldorf', 'Dusseldorf', 'DUS,Diseldorf,Diseldorfa,Disseldorf,Diuseldorfas,Djuseldorf,Djussel\'dorf,Duesseldoerp,Duesseldorf,Duessldorf,Duseldorfo,Dusseldoerp,Dusseldorf,Dusseldorpium,Dusseldörp,Dyzeldorfi,Düsseldorf,Düsseldörp,Düssldorf,Gorad Dzjusel\'dorf,Ntiselntorph,de~yusserudorufu,diuseldorpi,du sai er duo fu,dus se ld xrf,dwiseldoleupeu,dwsldwrf,dysldwrp,dyuseladorpha,tucaltorhp,Ντίσελντορφ,Горад Дзюсельдорф,Диселдорф,Дюселдорф,Дюссельдорф,דיסלדורף,دوسلدورف,ڈسلڈورف,ड्युसेलडॉर्फ,டுசல்டோர்ஃப்,ดึสเซลดอร์ฟ,ဒပ်ဆဲလ်ဒေါ့ဖ်မြို့,დიუსელდორფი,デュッセルドルフ,杜塞尔多夫,뒤셀도르프', 51.22172, 6.77616, 'P', 'PPLA', 'DE', NULL, '07', '051', NULL, NULL, 573057, NULL, 45, 'Europe/Berlin', '2012-05-14 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (2934691, 'Duisburg', 'Duisburg', 'DUI,Disburga,Duisboerj,Duisborg,Duisbourg,Duisburch,Duisburg,Duisburg and Hamborn,Duisburg-Hamborn,Duisburgas,Duisburgo,Duisbörj,Duizburg,Dujsburg,Dīsburga,Gorad Dujsburg,Ntouismpournk,Thuiscoburgum,de~yuisuburuku,du yi si bao,du\'isaburga,duisburgi,duys bur k,dwiseubuleukeu,dwysbwrg,dwysbwrgh,dysbwrg,Ντούισμπουργκ,Горад Дуйсбург,Дуизбург,Дуйсбург,דיסבורג,دويسبورغ,دویسبورگ,ڈوئسبرگ,डुइसबुर्ग,ดุยส์บูร์ก,დუისბურგი,ዱይስቡርግ፣ ጀርመን,デュイスブルク,杜伊斯堡,뒤스부르크', 51.43247, 6.76516, 'P', 'PPL', 'DE', NULL, '07', '051', NULL, NULL, 504358, NULL, 38, 'Europe/Berlin', '2012-11-23 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (2935517, 'Dortmund', 'Dortmund', 'DTM,Dortmund,Dortmundas,Dortmunde,Dortmundi,Dueoerpm,Düörpm,Gorad Dortmund,Ntortmount,Throtmenni,Tremonia,d xr thmund,doleuteumunteu,dortamunda,dorutomunto,duo te meng de,dwrtmwnd,Ντόρτμουντ,Горад Дортмунд,Дортмунд,דורטמונד,دورتموند,دۆرتمۆند,ڈارٹمنڈ,डॉर्टमुंड,ดอร์ทมุนด์,დორტმუნდი,ドルトムント,多特蒙德,도르트문트', 51.51494, 7.466, 'P', 'PPL', 'DE', NULL, '07', '059', NULL, NULL, 588462, NULL, 96, 'Europe/Berlin', '2012-11-28 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (2944388, 'Bremen', 'Bremen', 'BRE,Brehmehn,Brema,Breme,Bremen,Bremen hiria,Bremenas,Bremene,Bremeni,Bremeno,Bremy,Brèma,Bréma,Brémy,Brémén,Brême,Brēmene,Brėmenas,Byen Bremen,Gorad Brehmen,ber mein,beulemen,bremena,bremeni,brmn,brymn,bu lai mei,buremen,mdynt brymn,Βρέμη,Бремен,Брэмэн,Горад Брэмен,Բրեմեն,ברמן,برمن,بريمن,مدينة بريمن,ब्रेमेन,เบรเมิน,ဘရီမန်မြို့,ბრემენი,ブレーメン,不来梅,브레멘,', 53.07516, 8.80777, 'P', 'PPLA', 'DE', NULL, '03', '00', NULL, NULL, 546501, NULL, 18, 'Europe/Berlin', '2010-11-22 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (2950159, 'Berlin', 'Berlin', 'BER,Beirlin,Beirlín,Berleno,Berlien,Berliin,Berliini,Berlijn,Berlim,Berlin,Berline,Berlini,Berlino,Berlyn,Berlynas,Berlëno,Berlín,Berlîn,Berlīne,Berolino,Berolinum,Birlinu,Bèrlîn,Estat de Berlin,Estat de Berlín,bai lin,barlina,beleullin,berlini,berurin,bexrlin,brlyn,perlin,Βερολίνο,Берлин,Берлін,Бэрлін,Բերլին,בערלין,ברלין,برلين,برلین,بېرلىن,ܒܪܠܝܢ,बर्लिन,বার্লিন,பெர்லின்,เบอร์ลิน,ბერლინი,ベルリン,柏林,베를린', 52.52437, 13.41053, 'P', 'PPLC', 'DE', NULL, '16', '00', '11000', '11000000', 3426354, 74, 43, 'Europe/Berlin', '2012-09-19 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (2509954, 'Valencia', 'Valencia', 'Balenzia,Gorad Valensija,VLC,Valantsa,Valence,Valencia,Valencia - Valencia,Valencia - València,Valencie,Valencija,Valencio,Valensi,Valensia,Valensija,Valensiya,Valensyaa,Valenthia,Valentia,Valentzia,Valenza,València,Valéncia,Valência,Vałénsia,Walencja,Walensiye,Walénsiye,ba len seiy,ba lun xi ya,ballensia,barenshia,blnsyt,valenciya,valensia,valensiya,wa lun xi ya,walnsya,wlnsyh,Βαλένθια,Валенси,Валенсия,Валенсија,Валенсія,Горад Валенсія,Վալենսիա,וואלענציע,ולנסיה,بلنسية,والنسیا,ویلنسیہ,ڤالێنسیا,वालेन्सिया,வாலேன்சியா,บาเลนเซีย,ვალენსია,バレンシア,巴倫西亞,瓦伦西亚,발렌시아', 39.46975, -0.37739, 'P', 'PPLA', 'ES', NULL, '60', 'V', '46250', NULL, 814208, 15, 23, 'Europe/Madrid', '2011-06-16 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (2510911, 'Sevilla', 'Sevilla', 'Gorad Sevil\'ja,Hispalis,Lungsod ng Sevilla,SVQ,Sebilla,Sebille,Sevidzheh,Sevil\'ja,Sevila,Sevilha,Sevilia,Sevilija,Sevilja,Sevilla,Seville,Sevilo,Sevilya,Seviya,Seweliye,Sewilla,Sibilia,Siviglia,Sivilja,Séville,Séwéliye,ashbylyt,ceviya,sai wei li ya,sbya,sbylyh,sebi ya,sebiria,sebirya,sebiya,sevilia,sybyya,Σεβίλλη,Горад Севілья,Севиджэ,Севилья,Севиля,Севилја,Севиља,Севілья,Սևիլյա,סביליה,סעווילא,إشبيلية,اشبیلیہ,سبیا,سيبييا,سێڤیلیا,सेबिया,செவீயா,เซบียา,სევილია,セビリア,セビリャ,塞维利亚,세비야', 37.38241, -5.97613, 'P', 'PPLA', 'ES', NULL, '51', 'SE', '41091', NULL, 703206, NULL, 14, 'Europe/Madrid', '2013-04-08 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (2514256, 'Málaga', 'Malaga', 'AGP,Gorad Malaga,Malaca,Malaga,Malagae,Malago,Malaqa,Màlaga,Málaga,ma la jia,malaga,malaka,malga,mallaga,malqt,maraga,mlqt,Μάλαγα,Горад Малага,Малагæ,Малага,Մալագա,מאלגה,מלאגה,مالاگا,مالقة,مالگا,ملقة,مەلەگا,मलागा,மாலாகா,มาลากา,მალაგა,マラガ,马拉加,말라가', 36.72016, -4.42034, 'P', 'PPLA2', 'ES', NULL, '51', 'MA', '29067', NULL, 568305, NULL, 22, 'Europe/Madrid', '2011-06-16 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (3104324, 'Zaragoza', 'Zaragoza', 'Caesaraugusta,Caesarea Augusta,Caragoca,Gorad Saragosa,Salduba,Salduie,Saragoca,Saragosa,Saragosae,Saragossa,Saragosse,Saragoza,Saragozza,Saragoça,Saragòssa,Saraqosa,ZAZ,ZGZ,Zaragosa,Zaragoza,Zaragozo,Zargoza,sa la ge sa,sa ra ko sa,salagosa,saragosa,saragwsa,sarajwsa,srgwsh,srqstt,Çaragoça,Żaragoża,Σαραγόσα,Горад Сарагоса,Сарагосæ,Сарагоса,Սարագոսա,סרגוסה,زەرەگۆزا,ساراجوسا,ساراگوسا,سرقسطة,सारागोसा,ซาราโกซา,სარაგოსა,サラゴサ,萨拉戈萨,사라고사', 41.65606, -0.87734, 'P', 'PPLA', 'ES', NULL, '52', 'Z', '50297', NULL, 674317, NULL, 214, 'Europe/Madrid', '2012-12-08 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (3117735, 'Madrid', 'Madrid', 'Gorad Madryd,La Villa y Corte,Los Madriles,Lungsod ng Madrid,MAD,Madrid,Madrid osh,Madridas,Madride,Madridi,Madrido,Madril,Madrit,Madrite,Madryt,Madríd,Madrîd,Magerit,Maidrid,Mairil,Makelika,Matritum,Sanchinarro,ma de li,madeulideu,madorido,madorido shi,madrid,madrida,madridi,madryd,matrit,mdryd,mydrd,Μαδρίτη,Горад Мадрыд,Мaдрид,Мадрид,Мадрид ош,Мадрід,Մադրիդ,מאדריד,מדריד,مادرىد,مادرید,مدريد,میدرد,میڈرڈ,مەدرید,ܡܕܪܝܕ,मद्रिद,मद्रिद्,माद्रिद,মাদ্রিদ,ମାଡ୍ରିଦ,மத்ரித்,ಮಡ್ರಿಡ್,മാഡ്രിഡ്,มาดริด,མ་ད་རིད།,မဒရစ်မြို့,მადრიდი,ማድሪድ,マドリード,マドリード市,馬德里,马德里,마드리드', 40.4165, -3.70256, 'P', 'PPLC', 'ES', NULL, '29', 'M', '28079', NULL, 3255944, NULL, 665, 'Europe/Madrid', '2011-06-16 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (3128760, 'Barcelona', 'Barcelona', 'BCN,Barcellona,Barcellonn-a,Barcelona,Barcelone,Barcelono,Barceluna,Barcelůna,Barcełona,Barcillona,Barcino,Barkelone,Barselona,Barselonae,Barselóna,Barsélona,Bartzelona,Barzelona,Barçellonn-a,Barċellona,Gorad Barselona,Lungsod ng Barcelona,ba sai luo na,baleusellona,bar se lon a,barselona,barsilona,barslwn,barslwna,barsylwna,baruserona,brshlwnt,brzlwnh,la Ciudad Condal,parcelona,Βαρκελώνη,Барселонæ,Барселона,Горад Барселона,Բարսելոնա,בארצעלאנע,ברצלונה,بارسلون,بارسلونا,بارسیلونا,بارسەلۆنا,برشلونة,बार्सिलोना,বার্সেলোনা,பார்செலோனா,ബാര്‍സലോണ,บาร์เซโลนา,བྷར་སེ་ལོ་ནཱ།,ბარსელონა,バルセロナ,巴塞罗那,바르셀로나', 41.38879, 2.15899, 'P', 'PPLA', 'ES', NULL, '56', 'B', '08019', NULL, 1621537, 15, 47, 'Europe/Madrid', '2012-05-20 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (2995469, 'Marseille', 'Marseille', 'Gorad Marsel\',MRS,Marseggia,Marsegia,Marseglia,Marseilla,Marseille,Marseilles,Marsej,Marseja,Marsejlo,Marsel,Marsel\',Marsela,Marsele,Marselha,Marselis,Marsella,Marsella - Marseille,Marselye,Marsey,Marseya,Marseļa,Marsiella,Marsigghia,Marsiglia,Marsiho,Marsilha,Marsilia,Marsilija,Marsilya,Marsylia,Marsylijo,Marsèja,Marsêle,Marsêy,Massalia,Massilia,ma sai,maleuseyu,mar se,mar sæy,marcey,marsaiya,marsela,marseli,marsy,marsylya,marsylz,maruseiyu,mrsylya,mrsyy,Μασσαλία,Горад Марсель,Марсель,Марсеј,Марсељ,Марсилия,Մարսել,מארסיי,מרסיי,مارسيليا,مارسی,مارسیلز,مارسێی,مرسيليا,मार्सेल,मार्सैय,மர்சேய்,มาร์เซ,มาร์แซย์,မာဆေးမြို့,მარსელი,ማርሴ,マルセイユ,马赛,마르세유', 43.29695, 5.38107, 'P', 'PPLA', 'FR', NULL, 'B8', '13', '133', '13055', 794811, NULL, 28, 'Europe/Paris', '2013-07-03 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (658225, 'Helsinki', 'Helsinki', 'Elsin\'ki,Elzinki,Gel\'sinki,Gorad Khel\'sinki,HEL,Heilsinci,Heilsincí,Hel\'sinki,Helsingfors,Helsingi,Helsingia,Helsinki,Helsinkis,Helsinkium,Helsinko,Helsinky,Helsinqui,Helsinquia,Helsset,Helsínquia,Helsînkî,Hèlsinki,Khel\'sinki,Khel\'sinki osh,Khelsinki,Khelzinki,Lungsod ng Helsinki,Xelsinki,Xélsinki,Yelsinki,hailasiki,he er xin ji,helasinki,helcinki,helsingki,helsinki,herushinki,hlsnky,hlsnqy,hlsynky,hlsynqy,hylsynky,Èlzinki,Ħelsinki,Ελσίνκι,Гельсінкі,Горад Хельсінкі,Хелзинки,Хелсинки,Хельсинки,Хельсинки ош,Ҳелсинкӣ,Հելսինկի,הלסינקי,העלסינקי,هلسنكي,هلسینکی,هيلسينكى,ھێلسینکی,ہلسنکی,ہیلسنکی,ܗܠܣܢܩܝ,हेलसिंकी,हेल्सिन्कि,হেলসিঙ্কি,ਹੈਲਸਿੰਕੀ,ஹெல்சின்கி,ಹೆಲ್ಸಿಂಕಿ,ഹെൽസിങ്കി,เฮลซิงกิ,ཧེལ་སིན་ཀི།,ဟယ်လ်ဆင်ကီမြို့,ჰელსინკი,ሄልሲንኪ,Ḥélsinki,ヘルシンキ,赫尔辛基,헬싱키', 60.16952, 24.93545, 'P', 'PPLC', 'FI', NULL, '13', '01', '091', NULL, 558457, NULL, 26, 'Europe/Helsinki', '2011-12-31 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (2988507, 'Paris', 'Paris', 'Baariis,Bahliz,Gorad Paryzh,Lungsod ng Paris,Lutece,Lutetia,Lutetia Parisorum,Lutèce,PAR,Pa-ri,Paarys,Palika,Paname,Pantruche,Paraeis,Paras,Pari,Paries,Parigge,Pariggi,Parighji,Parigi,Pariis,Pariisi,Parij,Parijs,Paris,Parisi,Parixe,Pariz,Parize,Parizh,Parizh osh,Parizh\',Parizo,Parizs,Pariž,Parys,Paryz,Paryzius,Paryż,Paryžius,Paräis,París,Paríž,Parîs,Parĩ,Parī,Parīze,Paříž,Páras,Párizs,Ville-Lumiere,Ville-Lumière,ba li,barys,pairisa,pali,pari,paris,parys,paryzh,perisa,pryz,pyaris,pyarisa,pyrs,Παρίσι,Горад Парыж,Париж,Париж ош,Парижь,Париз,Парис,Паріж,Փարիզ,פאריז,פריז,باريس,پارىژ,پاريس,پاریس,پیرس,ܦܐܪܝܣ,पॅरिस,पेरिस,पैरिस,প্যারিস,ਪੈਰਿਸ,પૅરિસ,பாரிஸ்,పారిస్,ಪ್ಯಾರಿಸ್,പാരിസ്,ปารีส,ཕ་རི།,ပါရီမြို့,პარიზი,ፓሪስ,ប៉ារីស,パリ,巴黎,파리', 48.85341, 2.3488, 'P', 'PPLC', 'FR', NULL, 'A8', '75', '751', '75056', 2138551, NULL, 42, 'Europe/Paris', '2013-08-02 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (2655603, 'Birmingham', 'Birmingham', 'BHX,Birmin\'gxam,Birmingam,Birmingamas,Birmingem,Birmingema,Birmingham,Birminghamia,Birminghem,Gorad Birmingem,Mpermincham,bamingamu,barming\'hyam,barmingahama,barmingahema,barmingham,barminghama,beoming-eom,birmingemi,bo ming han,bo ming han shi,brmngm,brmynghham,byrmngam,parminkam,Μπέρμιγχαμ,Бирмингам,Бирмингем,Бірмінгем,Горад Бірмінгем,Բիրմինգհեմ,בירמינגהאם,ברמינגהאם,برمنگم,برمينغهام,بیرمنگام,बर्मिंगहॅम,बर्मिंघम,बर्मिङ्घम्,બર્મિંગહામ,பர்மிங்காம்,బర్మింగ్‌హామ్,ಬರ್ಮಿಂಗ್ಹ್ಯಾಮ್,เบอร์มิงแฮม,ბირმინგემი,በርሚንግሃም,バーミンガム,伯明翰,伯明翰市,버밍엄', 52.48142, -1.89983, 'P', 'PPLA2', 'GB', NULL, 'ENG', 'A7', NULL, NULL, 984333, NULL, 149, 'Europe/London', '2012-09-21 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (2643741, 'City of London', 'City of London', 'Bandaraya London,Cathair Londan,Ceety o Lunnon,Cidade de Londres,Cite de Londres,Citta di Londra,Città di Londra,City,City de Londres,Cité de Londres,Dakbayan sa Londres,Dinas Llundain,Grad London,Idolobha weLondon,Ker Londrez,Kêr Londrez,La City,London,Londonas Sitija,Londono Sitis,Londons\'ke Siti,Londons\'ke siti,Londra Sehri,Londra Şehri,Lontoon City,Lundunaborg,Lundúnaborg,Lungsod ng Londres,Sici,Siti,Siti tou Londinou,Thanh pho Luan GJon,Thành phố Luân Đôn,Urbs Londiniensis,hsyty sl lwndwn,lun dui shi,mdynt lndn,nkhr lxndxn,siti ofa landana,siti opha landana,sitiobeuleondeon,syty lndn,Σίτι του Λονδίνου,Град Лондон,Лондонське Сіті,Лондонське сіті,Сити,Сіці,Լոնդոնյան Սիթի,הסיטי של לונדון,סיטי פון לאנדאן,سیتی لندن,لندن شہر,مدينة لندن,सिटी ऑफ लंडन,सिटी ऑफ़ लंदन,นครลอนดอน,シティ・オブ・ロンドン,倫敦市,시티오브런던', 51.51279, -0.09184, 'P', 'PPLA3', 'GB', NULL, 'ENG', 'GLA', 'H9', NULL, 7556900, NULL, 27, 'Europe/London', '2012-08-19 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (2643743, 'London', 'London', 'City of London,Gorad Londan,ILondon,LON,Lakana,Landen,Ljondan,Llundain,Londain,Londan,Londar,Londe,Londen,Londinium,Londino,Londn,London,London City,Londona,Londonas,Londoni,Londono,Londonu,Londra,Londres,Londrez,Londri,Londye,Londyn,Londýn,Lonn,Lontoo,Loundres,Luan GJon,Lunden,Lundra,Lundun,Lundunir,Lundúnir,Lung-dung,Lunnainn,Lunnin,Lunnon,Luân Đôn,Lùng-dŭng,Lākana,Lůndůn,Lọndọnu,Ranana,Rānana,The City,ilantan,landan,landana,leondeon,lndn,london,londoni,lun dui,lun dun,lwndwn,lxndxn,rondon,Łondra,Λονδίνο,Горад Лондан,Лондан,Лондон,Лондонъ,Лёндан,Լոնդոն,לאנדאן,לונדון,لندن,لوندون,لەندەن,ܠܘܢܕܘܢ,लंडन,लंदन,लण्डन,लन्डन्,লন্ডন,લંડન,ଲଣ୍ଡନ,இலண்டன்,లండన్,ಲಂಡನ್,ലണ്ടൻ,ලන්ඩන්,ลอนดอน,ລອນດອນ,ལོན་ཊོན།,လန်ဒန်မြို့,ლონდონი,ለንደን,ᎫᎴ ᏗᏍᎪᏂᎯᏱ,ロンドン,伦敦,倫敦,런던', 51.50853, -0.12574, 'P', 'PPLC', 'GB', NULL, 'ENG', 'GLA', NULL, NULL, 7556900, NULL, 25, 'Europe/London', '2013-07-28 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (2648579, 'Glasgow', 'Glasgow', 'GLA,Glasc\'ho,Glaschu,Glaschú,Glasgovia,Glasgovo,Glasgow,Glaskobe,Glazgas,Glazgo,Glazgou,Glazgov,Glazgova,Glazgua,Glesga,Glázgua,Glāzgova,Gorad Glazga,Qlazqo,ge la si ge,geullaeseugo,ghlazghw,glasago,glasgo,glasgw,glasgwv,glazgo,glyasgo,gurasugo,jlasjw,kilasko,klas kow,Γλασκώβη,Глазго,Глазгов,Глазгоу,Горад Глазга,Գլազգո,גלאזגא,גלאזגו,جلاسجو,غلازغو,گلاسگو,گلاسگوۋ,ग्लासगो,ग्लास्गो,கிளாஸ்கோ,గ్లాస్గో,ಗ್ಲ್ಯಾಸ್ಗೋ,กลาสโกว์,ဂလပ်စဂိုးမြို့,გლაზგო,グラスゴー,格拉斯哥,글래스고', 55.86515, -4.25763, 'P', 'PPLA2', 'GB', NULL, 'SCT', 'V2', NULL, NULL, 610268, NULL, 40, 'Europe/London', '2012-10-05 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (264371, 'Athens', 'Athens', 'ATH,Afina,Afini,Afiny,An Aithin,Ateena,Atehny,Aten,Atena,Atenai,Atenas,Atenas - Athena,Atenas - Αθήνα,Atene,Atenes,Ateni,Ateno,Atenoj,Ateny,Athen,Athena,Athenae,Athenai,Athene,Athenes,Athens,Atheny,Athina,Athinai,Athinia,Athènes,Athén,Athénes,Athény,Athína,Athínai,Atina,Atény,Atēnas,Atėnai,Aþena,Kota Athena,Lungsod ng Athina,Lungsod ng Athína,atene,atene si,ateni,athensa,athyna,atn,etens,xethens,ya dian,Αθήνα,Αθήναι,Αθηνα,Αθηναι,Атина,Атэны,Афины,Афіни,Аѳины,Աթենք,אתונה,آتن,أثينا,ئافېنا,ܐܬܝܢܐ,अथेन्स,ஏதென்ஸ்,เอเธนส์,ათენი,Ἀθῆναι,アテネ,雅典,아테네,아테네 시', 37.97945, 23.71622, 'P', 'PPLC', 'GR', NULL, 'ESYE31', '445408', '9186', NULL, 729137, 70, 42, 'Europe/Athens', '2013-10-18 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (3186886, 'Zagreb', 'Zagreb', 'Agram,Andautonia,Gorad Zagrab,Lungsod ng Zagreb,Sagrab,Sagreb,Sagwzlwgbouh,Sakreb,Ságrab,ZAG,Zabrag,Zabreg,Zagabbria,Zagabria,Zagavria,Zagrab,Zagrabia,Zagreb,Zagreba,Zagrebas,Zagrebi,Zagrebo,Zagrep,Zagrzeb,Zagrèb,Zagréb,Zagàbria,Zahreb,Zankremp,Zaqreb,Zágráb,Záhreb,Záhřeb,cakirep,jagareba,jageulebeu,jhagreba,sa ge lei bu,sa kerb,zagaraba,zagrb,zagrebi,zagurebu,zghrb,zghryb,Żagreb,Ζάγκρεμπ,Горад Заграб,Загреб,Զագրեբ,זאגרב,זאגרעב,زاگرب,زاگرێب,زغرب,زغریب,झाग्रेब,জাগরেব,ਜ਼ਾਗਰਬ,சாகிரேப்,ซาเกร็บ,ཛག་རེབ།,ზაგრები,ዛግሬብ,ザグレブ,萨格勒布,Ꙁагрєбъ,자그레브', 45.81444, 15.97798, 'P', 'PPLC', 'HR', NULL, '21', '3186885', NULL, NULL, 698966, NULL, 135, 'Europe/Zagreb', '2012-11-23 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (6618983, 'Zagreb - Centar', 'Zagreb - Centar', NULL, 45.81313, 15.97753, 'P', 'PPLX', 'HR', NULL, '21', '3186885', NULL, NULL, 800000, NULL, 134, 'Europe/Zagreb', '2012-12-10 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (3054643, 'Budapest', 'Budapest', 'BUD,Boedapes,Boedapest,Boudapes,Boudapeste,Boudapès,Budaipeist,Budapescht,Budapeshha,Budapesht,Budapesht osh,Budapest,Budapesta,Budapestas,Budapeste,Budapesti,Budapestinum,Budapesto,Budapeszt,Budapeŝto,Budapeşt,Budapeşte,Budapešt,Budapešta,Budapeštas,Budapešť,Budapèst,Budapésht,Budimpeshta,Budimpesta,Budimpešta,Bùdapest,Bùdapeszt,Búdaipeist,Búdapest,Gorad Budapesht,bu da pei si,budapaisata,budapeseuteu,budapesrr,budapest,budapesta,budapesuto,bwdabst,bwdabyst,bwdapst,bwdpst,putapest,Βουδαπέστη,Будапешт,Будапешт ош,Будапеща,Будимпешта,Горад Будапешт,Բուդապեշտ,בודאפעשט,בודפשט,بودابست,بودابيست,بوداپست,بوداپێست,بوډاپسټ,बुडापेस्ट,बुदापेस्त,বুদাপেস্ট,ਬੁਦਾਪੈਸਤ,બુડાપેસ્ટ,ବୁଦାପେଷ୍ଟ,புடாபெஸ்ட்,ಬುಡಾಪೆಸ್ಟ್,ബുഡാപെസ്റ്റ്,บูดาเปสต์,བུ་ད་ཕེ་སིད།,ဗူးဒပက်မြို့,ბუდაპეშტი,ቡዳፔስት,ブダペスト,布达佩斯,부다페스트', 47.49801, 19.03991, 'P', 'PPLC', 'HU', NULL, '05', NULL, NULL, NULL, 1741041, NULL, 111, 'Europe/Budapest', '2013-07-07 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (2964574, 'Dublin', 'Dublin', 'Baile Atha Cliath,Baile Átha Cliath,Ciuda de Dublin,Ciudá de Dublín,DUB,Dablin,Diblin,Difelin,Divlyn,Doublino,Dublin,Dublin city,Dublina,Dublinas,Dublini,Dublino,Dublinu,Dublín,Dublîn,Dueblin,Dulenn,Dulyn,Dyflinn,Düblin,Eblana,Gorad Dublin,dabalina,dablin,dablina,daburin,dbln,dblyn,deobeullin,dou bai lin,dublini,dwblyn,taplin,Δουβλίνο,Горад Дублін,Даблин,Дублин,Дублін,Дъблин,Դուբլին,דבלין,דובלין,دبلن,دوبلين,دوبلین,دۇبلىن,ڈبلن,ډبلن,ܕܒܠܢ,डब्लिन,दब्लिन,ডাবলিন,ਡਬਲਿਨ,டப்லின்,ಡಬ್ಲಿನ್,ഡബ്ലിൻ,ดับลิน,དུབ་ལིན།,ဒပ်ဗလင်မြို့,დუბლინი,ደብሊን,ダブリン,都柏林,더블린', 53.33306, -6.24889, 'P', 'PPLC', 'IE', NULL, 'L', '33', NULL, NULL, 1024027, NULL, 17, 'Europe/Dublin', '2013-05-30 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (2523920, 'Palermo', 'Palermo', 'PMO,Palerm,Palerma,Palermas,Palerme,Palermo,Palermu,Panormus,Pałermo,ba lei mo shi,balyrmw,palermo,palrmw,parerumo,plrmw,Палерма,Палермо,פלרמו,باليرمو,پالرمو,पलेर्मो,パレルモ,巴勒莫市', 38.11582, 13.35976, 'P', 'PPLA', 'IT', NULL, '15', 'PA', '082053', NULL, 672175, 20, 30, 'Europe/Rome', '2012-04-23 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (3165524, 'Turin', 'Turin', 'Augusta Taurinorum,Gorad Turyn,Julia Augusta Taurinorum,Lungsod ng Turino,TRN,Tori,Torin,Torino,Torinu,Torí,Tueri,Turen,Turijn,Turim,Turin,Turina,Turinas,Turino,Turinu,Turyn,Turén,Turìn,Turín,Turīna,Tórínó,Türì,dou ling,tolino,torino,tu rin,turin,turina,twryn,twrynw,Τορίνο,Горад Турын,Торино,Турин,Թուրին,טורין,טורינו,تورينو,تورین,टोरीनो,तोरिनो,তুরিন,துரின்,ตูริน,ཊུ་རིན།,ტურინი,トリノ,都灵,토리노', 45.07049, 7.68682, 'P', 'PPLA', 'IT', NULL, '12', 'TO', '001272', NULL, 865263, 240, 245, 'Europe/Rome', '2012-10-28 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (3169070, 'Rome', 'Rome', 'An Roimh,An Ròimh,An Róimh,Erroma,Hrom,Lungsod ng Roma,Mji wa Roma,ROM,Ramma,Rhufain,Rim,Rim\",Roeme,Rom,Roma,Rome,Romma,Romo,Romë,Rooma,Roum,Rym,Rzym,Råmma,Rím,Róm,Róma,Urbs,loma,luo ma shi,rm,rom,roma,romi,rwm,rwma,rym,Řím,Ρώμη,Рим,Римъ,Ром,Рым,Հռոմ,רומא,رم,روم,روما,رىم,ܪܘܡܐ,रोम,रोमा,โรม,რომი,ሮማ,ローマ,罗马市,로마', 41.89474, 12.4839, 'P', 'PPLC', 'IT', NULL, '07', 'RM', '058091', NULL, 2563241, NULL, 29, 'Europe/Rome', '2013-11-21 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (3172394, 'Napoli', 'Napoli', 'Gorad Neapal\',NAP,Napels,Naples,Naplés,Napoles,Napoli,Napolo,Napols,Napoly,Nappoli,Napul,Napule,Napuli,Napulj,Napólí,Neapel,Neapelj,Neapol,Neapol\',Neapole,Neapolis,Noapels,Nàpoli,Nàpols,Nàpuli,Nàpułi,Nápoles,Nápoli,Nápols,Nápoly,Parthenope,na bu lei si,nabwly,napl,napoli,napolli,napori,napwly,nepalasa,nepalsa,nepeils,nyplz,Νάπολη,Горад Неапаль,Напуљ,Неапол,Неаполь,Նեապոլ,נאפאלי,נאפולי,نابولي,ناپل,ناپولی,نیپلز,नापोलि,नापोली,नेपल्स,নেপলস,நாபொலி,เนเปิลส์,ნეაპოლი,ナポリ,那不勒斯,나폴리', 40.83333, 14.25, 'P', 'PPLA', 'IT', NULL, '04', 'NA', '063049', NULL, 959574, NULL, 28, 'Europe/Rome', '2013-12-03 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (3173435, 'Milano', 'Milano', 'Lungsod ng Milano,MIL,Mailand,Mediolan,Mediolanum,Mila,Milaan,Milan,Milana,Milanas,Milano,Milanu,Milao,Milà,Milán,Miláno,Milánó,Milão,Milāna,Mílanó,Mилан,mi lan,milan,milani,millano,mirano,mylan,mylanw,Милан,Милано,Мілан,מילאנו,مىلان,ميلانو,மிலன்,มิลาน,მილანი,ミラノ,米蘭,밀라노', 45.46427, 9.18951, 'P', 'PPLA', 'IT', NULL, '09', 'MI', '015146', NULL, 1306661, 120, 127, 'Europe/Rome', '2013-11-21 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (3176219, 'Genoa', 'Genoa', 'Cenova,Dzenova,Dženova,GOA,Genes,Genoa,Genova,Genovo,Genua,Genuja,Genès,Genúa,Gènova,Génova,Gênes,Janov,Xenova - Genova,Xénova - Genova,Zena,genua,jeno\'a,jenoba,jenovu~a,jnwa,jnwt,re na ya,Ĝenovo,Ђенова,Генуа,Генуя,גנואה,جنوا,جنوة,जेनोआ,გენუა,ジェノヴァ,热那亚,제노바', 44.40632, 8.93386, 'P', 'PPLA', 'IT', NULL, '08', 'GE', '010025', NULL, 601951, NULL, 39, 'Europe/Rome', '2013-11-28 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (593116, 'Vilnius', 'Vilnius', 'Bilna,Bilnious,Gorad Vil\'njus,IVilnyusi,VNO,Vil\'njus,Vil\'no,Vil\'nyus,Viln\',Vilna,Vilnia,Vilnias,Vilnious,Vilnis,Vilnius,Vilnjus,Vilnjûs,Vilno,Vilnues,Vilnus,Vilnyus,Vilníus,Vilnüs,Viļņa,Vílnius,Wilna,Wilniyus,Wilno,Wilnus,Wilñus,bhilani\'usa,bilnyuseu,fylnyws,vhilniyasa,vilniusi,vilniyas,vu~irinyusu,vylnyws,wei er niu si,wi lni xus,wlnys,wylnh,wylnyws,Βίλνα,Βίλνιους,Βιλνιους,Вилниус,Вилнус,Вилнюс,Вилн҄ь,Вильнюс,Вилњус,Виљнус,Вільнюс,Горад Вільнюс,Վիլնյուս,ווילנע,וילנה,فيلنيوس,ولنیس,ویلنیوس,ڤیلنیوس,व्हिल्नियस,ভিলনিউস,வில்னியஸ்,วิลนีอุส,ལྦེ་ནི་སུ་ནི།,ვილნიუსი,ቪልኒውስ,ᕕᓪᓂᐅᔅ/vilnius,ヴィリニュス,维尔纽斯,빌뉴스', 54.68916, 25.2798, 'P', 'PPLC', 'LT', NULL, '65', '593118', NULL, NULL, 542366, NULL, 98, 'Europe/Vilnius', '2012-11-16 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (456172, 'Riga', 'Riga', 'Gorad Ryga,RIX,Reiga,Riga,Rigae,Rige,Rigg-a,Rigo,Riia,Riigaa,Riika,Rija,Riqa,Ryga,Ríga,Ríge,Rīga,li jia,liga,ri ka,riga,rika,ryga,rygh,rygha,ryja,Ρίγα,Горад Рыга,Ригæ,Рига,Ріґа,Ռիգա,ריגה,ריגע,رىگا,ريجا,ريغا,ریگا,रिगा,रीगा,রিগা,ரீகா,รีกา,རི་ག,რიგა,ሪጋ,リガ,里加,리가', 56.946, 24.10589, 'P', 'PPLC', 'LV', NULL, '25', NULL, NULL, NULL, 742572, NULL, 6, 'Europe/Riga', '2011-09-24 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (618426, 'Chişinău', 'Chisinau', 'Chisinau,Chisinau - Chisinau,Chisinau - Chişinău,Chişinău,Chișinău,KIV,Kischinew,Kiscinev,Kishinef,Kishinev,Kishiniv,Kishinjov,Kishinëv,Kisinaou,Kisinev,Kisineva,Kisinevo,Kisiniovas,Kisinjev,Kisinov,Kisinyov,Kisyneu,Kiszyniow,Kiszyniów,Kiŝinevo,Kişinev,Kišiniovas,Kišinjev,Kišiněv,Kišiņeva,Kišiňov,cisina\'u,cisinau,ji xi ne wu,kishinau,kisineou,kyshynaw,Κισινάου,Κισιναου,Кишинев,Кишинёв,Кишињев,Кішынёў,Կիշինյով,קישינאו,كيشيناو,चिशिनाउ,चिशिनौ,კიშინიოვი,ኪሺንው,キシナウ,基希讷乌,키시너우', 47.00556, 28.8575, 'P', 'PPLC', 'MD', NULL, '57', NULL, NULL, NULL, 635994, NULL, 55, 'Europe/Chisinau', '2013-12-01 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (2747891, 'Rotterdam', 'Rotterdam', 'Gorad Ratehrdam,RTM,Roterdam,Roterdama,Roterdamas,Roterdami,Roterdamo,Roterdan,Roterdao,Roterdão,Roterntam,Roterodamum,Rotterdam,Rottérdam,Róterdam,Róterdan,loteleudam,lu te dan,rattartem,rotaradema,rotterudamu,rtrdam,rwtrdam,rwtrdm,rxt the xr dam,Ρότερνταμ,Горад Ратэрдам,Ротердам,Роттердам,ראטערדאם,רוטרדם,راٹرڈیم,رتردام,روتردام,ܪܘܛܪܕܐܡ,रॉटरडॅम,ராட்டர்டேம்,รอตเทอร์ดาม,ရော်တာဒမ်မြို့,როტერდამი,ロッテルダム,鹿特丹,로테르담', 51.9225, 4.47917, 'P', 'PPL', 'NL', NULL, '11', '0599', NULL, NULL, 598199, NULL, 9, 'Europe/Amsterdam', '2013-06-14 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (2759794, 'Amsterdam', 'Amsterdam', 'AMS,Amesterdam,Amistardam,Amstardam,Amstedam,Amstelodamum,Amsterdam,Amsterdama,Amsterdamas,Amsterdami,Amsterdamo,Amsterdao,Amsterdão,Amsterntam,Amstèdam,Amszterdam,Damsko,Gorad Amstehrdam,I-Amsterdami,a mu si te dan,aimstardaima,amasataradama,amastaradama,amseuteleudam,amstardama,amstartam,amstrdam,amusuterudamu,anstardyam,emstardyama,xamstexrdam,Àmsterdam,Ámsterdam,Άμστερνταμ,Амстердам,Горад Амстэрдам,Ամստերդամ,אמסטערדאם,אמסטרדם,آمستردام,أمستردام,ئامستېردام,ئەمستردام,امستردام,امسټرډام,ایمسٹرڈیم,ܐܡܣܛܪܕܐܡ,अ‍ॅम्स्टरडॅम,आम्स्टर्डम,एम्स्टर्ड्याम,ऐम्स्टर्डैम,আমস্টারডাম,ਅਮਸਤਰਦਮ,ஆம்ஸ்டர்டம்,ಆಂಸ್ಟರ್ಡ್ಯಾಮ್,ആംസ്റ്റർഡാം,ඈම්ස්ටර්ඩෑම්,อัมสเตอร์ดัม,ཨེམ་སི་ཊར་ཌམ།,အမ်စတာဒမ်မြို့,ამსტერდამი,አምስተርዳም,アムステルダム,阿姆斯特丹,암스테르담', 52.37403, 4.88969, 'P', 'PPLC', 'NL', NULL, '07', '0363', NULL, NULL, 741636, NULL, 13, 'Europe/Amsterdam', '2011-06-16 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (3143244, 'Oslo', 'Oslo', 'Asloa,Christiania (historical),Gorad Osla,Kristiania (historical),OSL,Ohoro,Oslas,Oslo,Oslo osh,Oslu,Osló,ao si lu,asalo,aslw,awslw,osalo,oseullo,oslea,oslo,osuro,xxslo,ywslw,Òslo,Ósló,Ōhoro,Όσλο,Горад Осла,Осло,Осло ош,Օսլո,אוסלו,أوسلو,ئوسلو,ئۆسلۆ,اسلو,اوسلو,ܐܘܣܠܘ,ओस्लो,ওসলো,ਓਸਲੋ,ଅସଲୋ,ஒஸ்லோ,ഓസ്ലൊ,ออสโล,ཨོ་སི་ལོ།,အော့စလိုမြို့,ოსლო,ኦስሎ,オスロ,奥斯陆,오슬로', 59.91273, 10.74609, 'P', 'PPLC', 'NO', NULL, '12', '0301', NULL, NULL, 580000, NULL, 26, 'Europe/Oslo', '2011-06-16 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (756135, 'Warsaw', 'Warsaw', 'Barsobia,Varsa,Varsava,Varsavia,Varsavja,Varshava,Varshavae,Varsja,Varsjá,Varso,Varsova,Varsovia,Varsovia - Warszawa,Varsovie,Varsovio,Varssavi,Varsuva,Varsòvia,Varsó,Varsóvia,Varşova,Varšava,Varšuva,Varșovia,Vársá,WAW,Warsaw,Warsawa,Warschau,Warskou,Warszaw,Warszawa,Waršawa,baleusyaba,hua sha,varshava,vorso,warsw,warushawa,wrsh,wrshw,wrsw,wxrsx,Βαρσοβία,Варшавæ,Варшава,Վարշավա,ווארשע,ורשה,װאַרשע,وارسو,ورشو,ۋارشاۋا,ܘܪܣܘ,वॉर्सो,วอร์ซอ,ვარშავა,ዋርሶው,ワルシャワ,华沙,華沙,바르샤바', 52.22977, 21.01178, 'P', 'PPLC', 'PL', NULL, '78', '1465', NULL, NULL, 1702139, NULL, 113, 'Europe/Warsaw', '2013-12-01 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (3088171, 'Poznań', 'Poznan', 'Gorad Poznan\',POZ,Posen,Posnania,Poznan,Poznan\',Poznana,Poznane,Poznanj,Poznany,Poznanė,Poznań,Poznańy,Poznaņa,Poznaň,Poznon,Pòznóń,Pоznan,bo zi nan,bwznan,phx snan,pocunan,pojeunan,pojhnana,pozunan,pwyzn,pwznan,pwznn,Πόζναν,Горад Познань,Познан,Познань,Познањ,פוזנן,פויזן,بوزنان,پوزنان,پۆزنان,पोझ्नान,போசுனான்,พอซนาน,ཕྰོ་ཟོ་ནན།,პოზნანი,ポズナン,波茲南,포즈난', 52.40692, 16.92993, 'P', 'PPLA', 'PL', NULL, '86', '3064', '306401', NULL, 570352, NULL, 69, 'Europe/Warsaw', '2011-07-28 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (3081368, 'Wrocław', 'Wroclaw', 'Brassel,Breslau,Breslavia,Breslavl\',Breslavl’,Gorad Vroclau,Vratislav,Vratislavia,Vroclav,Vroclava,Vroclavas,Vroclavo,Vroklave,Vroslav,Vrotslav,WRO,Wroclaw,Wroclow,Wrocław,Wrocłow,Wroklaw,Wroslaw,Wrosław,Wrócław,beulocheuwapeu,frwtswaf,fu luo ci wa fu,viratscahp,vrotsavapha,vrotslavi,vurotsuwafu,w rxtswaf,wrwslaw,wrwtswaf,wrwzlb,Βρότσλαβ,Вроцлав,Горад Вроцлаў,ברעסלוי,ורוצלב,فروتسواف,وروتسواف,وروسلاو,ڤرۆتسواف,व्रोत्सवाफ,விராத்ஸ்சாஃப்,วรอตสวัฟ,ვროცლავი,ヴロツワフ,弗罗茨瓦夫,브로츠와프', 51.1, 17.03333, 'P', 'PPLA', 'PL', NULL, '72', '0223', NULL, NULL, 634893, NULL, 119, 'Europe/Warsaw', '2012-11-16 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (3093133, 'Łódź', 'Lodz', 'Gorad Lodz\',Litzmannstadt,Lodz,Lodz\',Lodza,Lodze,Lodzia,Lodzo,Lodzė,Lodž,Log,Lotz,Luc,Ludz,lodzi,luo ci,lwdz,lwdz\',uchi,utchi,vutsa,wuch,wwdj,wwj,Łódź,Łůdź,Λοτζ,Горад Лодзь,Лодз,Лодзь,Лођ,Лоѓ,לאדזש,לודז\',لودز,ووج,وودج,वूत्श,วูช,ლოძი,ウッチ,罗兹,우치', 51.75, 19.46667, 'P', 'PPLA', 'PL', NULL, '74', NULL, NULL, NULL, 768755, NULL, 192, 'Europe/Warsaw', '2011-03-30 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (3094802, 'Kraków', 'Krakow', 'Carcovia,Cracau,Cracaû,Cracovia,Cracovie,Cracow,Cracòvia,Cracóvia,Gorad Krakau,KRK,Kraka,Krakau,Krakiv,Krakko,Krakkó,Krakobia,Krakov,Krakova,Krakovi,Krakovia,Krakovija,Krakovja,Krakovo,Krakow,Krakowo,Krakuw,Kraká,Krakòwò,Krakóvia,Kraków,Krakůw,Krakоv,Krokuva,ke la ke fu,keulakupeu,kraku f,krakupha,krakwf,kurakufu,qrqwb,Κρακοβία,Горад Кракаў,Краков,Краковия,Краків,Կրակով,קראקע,קרקוב,كراكوف,کراکوف,क्राकूफ,กรากุฟ,ကရားကော့မြို့,კრაკოვი,クラクフ,克拉科夫,크라쿠프', 50.06143, 19.93658, 'P', 'PPLA', 'PL', NULL, '77', '1261', '126101', NULL, 755050, NULL, 219, 'Europe/Warsaw', '2013-11-26 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (2267057, 'Lisbon', 'Lisbon', 'Felicitas Julia,Felicitas Julia Olissipo,LIS,Liospoin,Liospóin,Lisabon,Lisabona,Lisboa,Lisbon,Lisbona,Lisbonne,Lisbono,Lisbonum,Lissabon,Lisszabon,Lizboa,Lizbon,Lizbona,Olisipo,Olissipo,li si ben,lisaboni,lisbana,lisbxn,liseubon,lshbwnt,lysabwn,lysbwn,risubon,Λισαβώνα,Лисабон,Лиссабон,Лісабон,Լիսբոնա,ליסבון,لشبونة,لىسابون,لیسبون,ܠܫܒܘܢܐ,लिस्बन,ลิสบอน,ლისაბონი,ሊዝቦን,リスボン,里斯本,리스본', 38.71667, -9.13333, 'P', 'PPLC', 'PT', NULL, '14', '1106', '110653', NULL, 517802, NULL, 45, 'Europe/Lisbon', '2013-12-03 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (683506, 'Bucharest', 'Bucharest', 'BUH,Boekarest,Boukouresti,Bucarest,Bucaresta,Bucareste,Bucarèst,Bucharest,Bucuresti,Bucureşti,București,Buekres,Bukarest,Bukarestas,Bukareste,Bukaresto,Bukareszt,Bukareŝto,Bukareštas,Bukharest,Bukureshh,Bukuresht,Bukureshta,Bukureshti,Bukurest,Bukurešt,Bukurešť,Buxarest,Búkarest,Bûkarest,Bükreş,bkharst,bu jia lei si te,bukaresuto,bukulesyuti,bwkharst,bwqrst,Βουκουρέστι,Букурешт,Букурещ,Бухарест,Բուխարեստ,בוקאַרעשט,בוקרשט,بخارست,بوخارست,بۇخارېست,པུ་ཁ་རེ་སིད,ბუქარესტი,ቡካረስት,ブカレスト,布加勒斯特,부쿠레슈티', 44.43225, 26.10626, 'P', 'PPLC', 'RO', NULL, '10', '179132', NULL, NULL, 1877155, NULL, 83, 'Europe/Bucharest', '2013-12-03 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (792680, 'Belgrade', 'Belgrade', 'BEG,Belehrad,Belgrad,Belgrada,Belgradas,Belgrade,Belgrado,Belgradu,Belgradum,Belgrau,Belgrað,Belgrád,Belgráu,Beligradi,Belohrod,Beograd,Beogradi,Beogrado,Bèlgrade,Bělehrad,Běłohród,Nandorfehervar,Nándorfehérvár,Singidunum,be-ogeuladeu,bei er ge lai de,belgradi,beogurado,blghrad,blgrd,pelkiret,Βελιγράδι,Белград,Београд,Бѣлъ Градъ · Срьбїи,Բելգրադ,בלגרד,بلغراد,بېلگراد,பெல்கிறேட்,ბელგრადი,በልግራድ,ベオグラード,贝尔格莱德,베오그라드', 44.80401, 20.46513, 'P', 'PPLC', 'RS', NULL, 'SE', NULL, NULL, NULL, 1273651, NULL, 120, 'Europe/Belgrade', '2011-10-04 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (468902, 'Yaroslavl', 'Yaroslavl', 'IAR,Jaroslavl,Jaroslavl\',Jaroslawl,Yaroslavl,Ярославль', 57.62987, 39.87368, 'P', 'PPLA', 'RU', NULL, '88', NULL, NULL, NULL, 606730, NULL, 103, 'Europe/Moscow', '2013-11-08 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (472045, 'Voronezh', 'Voronezh', 'VOZ,Voronej,Voronez,Voroneza,Voronezas,Voronezh,Voronezhskaja oblast\',Voronezj,Voroneĵ,Voronež,Voronežas,Voroněž,Voroņeža,Woronesch,Woronesh,Woronez,Woroneż,bolonesi,vu~oroneji,Воронеж,Воронежская область,ヴォロネジ,보로네시', 51.67204, 39.1843, 'P', 'PPLA', 'RU', NULL, '86', NULL, NULL, NULL, 848752, NULL, 156, 'Europe/Moscow', '2012-01-17 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (472757, 'Volgograd', 'Volgograd', 'Caricyn,Estalingrado,Gorad Valgagrad,Stalingrad,Stalingrado,Tsaritsyn,VOG,Vl\'gogradu,Volgograd,Volgograda,Volgogradas,Volgogrado,Volgogradum,Volgográd,Volnkonkrant,Volqoqrad,Wolgograd,Wołgograd,Zarizyn,bolgogeuladeu,fu er jia ge lei,fwlghwghrad,fwljwjrad,volgograda,volgogradi,volkokirat,vu~orugogurado,wlgagrad,wwlgw grad,wwlgwgrad,wwlgwgrd,wxl kok rad,Βόλγκογκραντ,Вльгоградъ,Волгоград,Горад Валгаград,Сталинград,Царицын,Վոլգոգրադ,וולגוגרד,فولجوجراد,فولغوغراد,ولگاگراد,وولگو گراد,وولگوگراد,वोल्गोग्राद,வோல்கோகிராட்,วอลโกกราด,စတာလင်ဂရက်မြို့,ვოლგოგრადი,ヴォルゴグラード,伏尔加格勒,볼고그라드', 48.71939, 44.50184, 'P', 'PPLA', 'RU', NULL, '84', NULL, NULL, NULL, 1011417, NULL, 65, 'Europe/Volgograd', '2012-01-17 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (479123, 'Ulyanovsk', 'Ulyanovsk', 'Oulianovsk,Simbirsk,Sinbirsk,ULY,Ul\'janovsk,Ul\'yanovsk,Uljanowsk,Uljanowska am Wolga,Ulyanovsk,Ul’yanovsk,Синбирск,Ульяновск', 54.32824, 48.38657, 'P', 'PPLA', 'RU', NULL, '81', '479105', NULL, NULL, 640680, NULL, 176, 'Europe/Moscow', '2013-11-22 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (482283, 'Tol’yatti', 'Tol\'yatti', 'Stavropol\',Stavropol’,Stawropol,Togliatti,Togliatti-on-the-Volga,Togliattigrad,Tol\'jatti,Tol\'yatti,Tolati,Toliatti,Toljati,Toljatti,Tolyatti,Tol’yatti,tolliyati,toriyatchi,Ставрополь,Тольятти,Тољати,トリヤッチ,톨리야티', 53.5303, 49.3461, 'P', 'PPL', 'RU', NULL, '65', NULL, NULL, NULL, 702879, 42, 92, 'Europe/Samara', '2013-11-22 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (498677, 'Saratov', 'Saratov', 'Gorad Saratau,ISaratov,RTW,Saratof,Saratov,Saratova,Saratovas,Saratovia,Saratovu,Saratow,Saratu,Saratów,Saretow,Sarytau,Saràtov,Sarátov,Szaratov,sa la tuo fu,salatopeu,saratofu,saratwf,srʼtwb,Σαράτοφ,Горад Саратаў,Сарăту,Саратов,Саратовъ,Сарытау,Һарытау,סראטוב,ساراتوف,ساراٹوف,სარატოვი,サラトフ,薩拉托夫,사라토프', 51.54056, 46.00861, 'P', 'PPLA', 'RU', NULL, '67', '498661', NULL, NULL, 863725, NULL, 72, 'Europe/Volgograd', '2012-12-10 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (498817, 'Saint Petersburg', 'Saint Petersburg', 'Agia Petroupole,Betuyrbukh,LED,Leningrad,Leningrado,Lungsod ng Sankt-Peterburg,Peterburg,Peterburgo,Peterburi,Petersburg,Petrograd,Petrogrado,Petrohrad,Petropolis,Petursborg,Pietari,Piter,Pétursborg,SPb,Saint Petersbourg,Saint Petersburg,Saint Pétersbourg,Saint-Petersbourg,Saint-Petersburg,Saint-Pétersbourg,San Petersburgo,San Pietroburgo,San Pietruburgu,Sankt Peitersbuerg,Sankt Peterburg,Sankt Peterburgas,Sankt Petersborg,Sankt Petersburg,Sankt Peterzburg,Sankt Péitersbuerg,Sankt-Peterburg,Sankt-Peterburgo,Sankt-Petersburg,Sankti Petursborg,Sankti Pétursborg,Sanktpeterburga,Sanktpēterburga,Sant Petersburg,Sant Petersburgo,Sant-Petersbourg,Sao Petersburgo,Sint Petersbork,Sint-Petersburg,St Peterburg,St Petersburg,St. Petersburg,St.-Petersburg,Szentpetervar,Szentpétervár,São Petersburgo,sangteupeteleubuleukeu,sankt. peterburg,sankutopeteruburuku,sant btrsbrgh,sent pi te xrs beirk,sheng bi de bao,sn ptrzbwrg,snqt ptrbwrg,Αγία Πετρούπολη,Бетъырбух,Ленинград,Петербург,Петроград,Питер,СПб,Санкт Петербург,Санкт Петерзбург,Санкт-Петербург,Սանկտ Պետերբուրգ,סנקט פטרבורג,سانت بطرسبرغ,سن پترزبورگ,เซนต์ปีเตอร์สเบิร์ก,სანკტ-პეტერბურგი,სანქტ-პეტერბურგი,サンクトペテルブルク,圣彼得堡,상트페테르부르크', 59.93863, 30.31413, 'P', 'PPLA', 'RU', NULL, '66', NULL, NULL, NULL, 5028000, NULL, 11, 'Europe/Moscow', '2013-07-18 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (499099, 'Samara', 'Samara', 'Gorad Samara,KUF,Kuibyschew,Kuibyshev,Kujbyshev,Kuybyshev,Samar,Samar khot,Samara,Samarae,Samarga,Samāra,Szamara,sa ma la,samala,samara,smara,smrh,Σαμάρα,Горад Самара,Куйбышев,Самар,Самар хот,Самарæ,Самара,Самарҕа,Һамар,סמרה,سامارا,سامارہ,سمارا,समारा,სამარა,サマーラ,薩馬拉,사마라', 53.20006, 50.15, 'P', 'PPLA', 'RU', NULL, '65', NULL, NULL, NULL, 1134730, NULL, 117, 'Europe/Samara', '2012-02-28 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (500096, 'Ryazan’', 'Ryazan\'', 'Resan,Riazan,Riazań,Rjasan,Rjazan,Rjazan\',Rjazaň,Ryazan,Ryazan\',Ryazan’,liang zan,lyajan,ryazan,Рязань,リャザン,梁赞,랴잔', 54.6269, 39.6916, 'P', 'PPLA', 'RU', NULL, '62', NULL, NULL, NULL, 520173, NULL, 102, 'Europe/Moscow', '2012-01-17 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (501175, 'Rostov-na-Donu', 'Rostov-na-Donu', 'Don umbalne Rostov,Dondaguy Rostov,Doni Rostov,Donyn Rostov,Gorad Rastou-na-Done,ROV,Rostof aan die Don,Rostoph ston Nton,Rostov,Rostov Don,Rostov Donal,Rostov Doni aeaeres,Rostov Doni ääres,Rostov aan de Don,Rostov del Don,Rostov do Don,Rostov na Don,Rostov na Donu,Rostov nad Donom,Rostov sul Don,Rostov tren song GJong,Rostov trên sông Đông,Rostov ved Don,Rostov-Donyl,Rostov-Tan-cinchi,Rostov-an-Don,Rostov-na-Donu,Rostov-on-Don,Rostov-pe-Don,Rostov-sur-le-Don,Rostova pie Donas,Rostovas prie Dono,Rostow,Rostow Am Don,Rostow am Don,Rostow nad Donem,Rostow nad Donom,Rostów nad Donem,Rosztov-na-Donu,Tyndagy Rostov,dun he pan luo si tuo fu,loseutopeunadonu,rwstwf-na-dwnw,rwstww na danw,Ροστόφ στον Ντον,Горад Растоў-на-Доне,Дон ӱмбалне Ростов,Дондагъы Ростов,Донын Ростов,Растовъ на Донѣ,Ростов,Ростов на Дон,Ростов на Дону,Ростов-Доныл,Ростов-Тан-çинчи,Ростов-на-Дону,Тындагы Ростов,Դոնի Ռոստով,רוסטוב על הדון,روستوف-نا-دونو,روستوو نا دانو,დონის როსტოვი,დონიშ როსტოვი,ロストフ・ナ・ドヌ,顿河畔罗斯托夫,로스토프나도누', 47.23135, 39.72328, 'P', 'PPLA', 'RU', NULL, '61', NULL, NULL, NULL, 1074482, NULL, 74, 'Europe/Moscow', '2013-03-30 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (511565, 'Penza', 'Penza', 'Gorad Penza,PEZ,Pensa,Penz,Penza,Penza osh,Penzae,ben sa,bynza,penja,penza,pnza,pnzh,pynza,Πένζα,Горад Пенза,Пензæ,Пенза,Пенза ош,פנזה,بينزا,پنزا,پینزا,ペンザ,奔萨,펜자', 53.20066, 45.00464, 'P', 'PPLA', 'RU', NULL, '57', NULL, NULL, NULL, 512602, NULL, 150, 'Europe/Moscow', '2012-01-17 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (520555, 'Nizhniy Novgorod', 'Nizhniy Novgorod', 'GOJ,Gor\'kij,Gor\'kiy,Gorkey,Gorki,Gorkii,Gorky,Nijni Novgorod,Nijnii Novgorod,Nishni-Nowgorod,Nishnii Nowgorod,Nishnij Nowgorod,Nizhni Novgorod,Nizhnii Novgorod,Nizhnij Novgorod,Nizhniy Novgorod,Nizhny Novgorod,Nizjnij Novgorod,Niznij Nowgorod,Nižnij Nowgorod,Горький,Нижний Новгород', 56.32867, 44.00205, 'P', 'PPLA', 'RU', NULL, '51', NULL, NULL, NULL, 1284164, NULL, 141, 'Europe/Moscow', '2012-05-17 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (523750, 'Naberezhnyye Chelny', 'Naberezhnyye Chelny', 'Brezhnev,Jar Chally,Naberejnije Celni,Naberejnye Chelny,Nabereschnyje Tschelny,Naberezhnye Chelny,Naberezhnyye Chelny,Naberezjnye Tsjelny,Nabereznije Celni,Nabereznoje Tselno,Nabereznye Celny,Nabereznyje Celny,Nabereznyje Tselny,Nabereĵnije Ĉelni,Naberežnije Čelni,Naberežnye Čelny,Naberežnyje Tšelny,Naberežnyje Čelny,Naberežnõje Tšelnõ,nabelejeuniyechelni,nabelejuniyechelni,Набережные Челны,Яр Чаллы,나베레주니예첼니,나베레즈니예첼니', 55.72545, 52.41122, 'P', 'PPL', 'RU', NULL, '73', '480620', NULL, NULL, 509870, NULL, 103, 'Europe/Moscow', '2013-11-22 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (524901, 'Moscow', 'Moscow', 'Gorad Maskva,MOW,Maeskuy,Maskav,Maskava,Maskva,Mat-xco-va,Matxcova,Matxcơva,Mosca,Moscfa,Moscha,Mosco,Moscou,Moscova,Moscovo,Moscow,Moscoƿ,Moscu,Moscua,Moscòu,Moscó,Moscù,Moscú,Mosk\"va,Moska,Moskau,Mosko,Moskokh,Moskou,Moskov,Moskova,Moskow,Moskowa,Mosku,Moskuas,Moskva,Moskvo,Moskwa,Moszkva,Muskav,Musko,Mát-xcơ-va,Mòskwa,Məskəү,masko,maskw,mo si ke,moseukeuba,mosko,mosukuwa,mskw,mwskva,mwskw,mwsqbh,mx s ko,Μόσχα,Горад Масква,Мæскуы,Маскав,Москва,Москова,Москох,Москъва,Мускав,Муско,Мәскәү,Մոսկվա,מאָסקװע,מאסקווע,מוסקבה,ماسکو,مسکو,موسكو,موسكۋا,ܡܘܣܩܒܐ,मास्को,मॉस्को,মস্কো,மாஸ்கோ,มอสโก,མོ་སི་ཁོ།,მოსკოვი,ሞስኮ,モスクワ,莫斯科,모스크바', 55.75222, 37.61556, 'P', 'PPLC', 'RU', NULL, '48', NULL, NULL, NULL, 10381222, NULL, 144, 'Europe/Moscow', '2013-10-15 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (535121, 'Lipetsk', 'Lipetsk', 'Gorad Lipeck,LPK,Lipec\'k,Lipeck,Lipecka,Lipeckas,Lipetk,Lipetsk,Lipezk,Lipețk,Lipieck,Lípetsk,li pei ci ke,li pet skh,lipecheukeu,lybytsk,lyptsk,ripetsuku,Ļipecka,Горад Ліпецк,Липецк,Липецьк,ليبيتسك,لپٹسک,لیپتسک,ลีเปตสค์,リペツク,利佩茨克,리페츠크', 52.60311, 39.57076, 'P', 'PPLA', 'RU', NULL, '43', NULL, NULL, NULL, 515655, NULL, 158, 'Europe/Moscow', '2013-11-25 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (542420, 'Krasnodar', 'Krasnodar', 'Ekaterinodar,KRR,Krasnodar,Krasnodara,Yekaterinodar,ke la si nuo da er,keulaseunodaleu,kurasunodaru,qrsnwdr,Краснодар,קרסנודר,クラスノダル,克拉斯诺达尔,크라스노다르', 45.04484, 38.97603, 'P', 'PPLA', 'RU', NULL, '38', NULL, NULL, NULL, 649851, NULL, 28, 'Europe/Moscow', '2013-04-03 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (551487, 'Kazan', 'Kazan', 'Casanum,Caza,Cazã,Gorad Kazan\',KZN,Kaasan,Kasa,Kasan,Kasã,Kazan,Kazan\',Kazan\' osh,Kazana,Kazane,Kazani,Kazanė,Kazan’,Kazań,Kazaņa,Kazaň,Kazán,Khuazan,Khusan,Kozan\',Kuazan,Kuzon,Qazan,Qozon,ka shan,kajan,kazan,kazana,qazan,Καζάν,Горад Казань,Казан,Казань,Казань ош,Казањ,Каꙁанъ,Козань,Кузон,Къазан,Озаҥ,Хусан,Хъазан,Қазан,Ҡаҙан,Կազան,קאזאן,قازان,كازان,काज़ान,ყაზანი,カザン,喀山,카잔', 55.78874, 49.12214, 'P', 'PPLA', 'RU', NULL, '73', 'Gorod', NULL, NULL, 1104738, NULL, 61, 'Europe/Moscow', '2012-12-05 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (554840, 'Izhevsk', 'Izhevsk', 'Gorad Izhehusk,IJK,Ijevsk,Ischewsk,Ishewsk,Izevsk,Izevska,Izevskas,Izewsk,Izh,Izhau,Izhevs\'k,Izhevsk,Izhkar,Izjefsk,Izjevsk,Izsevszk,Iĵevsk,Iżewsk,Iževsk,Iževska,Iževskas,Ustinov,ayjyfsk,ayzhwsk,azwsk,ijebseukeu,ijefusuku,yi re fu si ke,İjevsk,Ιζέβσκ,Іжевськ,Горад Іжэўск,Иж,Ижау,Ижевск,Ижкар,איז\'בסק,إيجيفسك,ازوسک,ایژوسک,იჟევსკი,イジェフスク,伊熱夫斯克,이젭스크', 56.84976, 53.20448, 'P', 'PPLA', 'RU', NULL, '80', NULL, NULL, NULL, 631038, NULL, 166, 'Europe/Samara', '2012-02-28 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (580497, 'Astrakhan’', 'Astrakhan\'', 'Astrachan,Astrakhan,Astrakhan\',Astrakhan’,Астрахань', 46.34968, 48.04076, 'P', 'PPLA', 'RU', NULL, '07', '824964', NULL, NULL, 502533, NULL, -12, 'Europe/Volgograd', '2013-11-21 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (8504951, 'Kalininskiy', 'Kalininskiy', 'Kalininskij,Калининский', 59.99675, 30.3899, 'P', 'PPLX', 'RU', NULL, '42', NULL, NULL, NULL, 504641, NULL, 34, 'Europe/Moscow', '2013-03-29 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (2673730, 'Stockholm', 'Stockholm', 'Estocolm,Estocolme,Estocolmo,Estocòlme,Estokolmo,Gorad Stakgol\'m,Holmia,STO,Stjokolna,Stoccholm,Stoccolma,Stockholbma,Stockholm,Stocolm,Stocolma,Stocòlma,Stocólma,Stokcholme,Stokgol\'m,Stokgol\'m osh,Stokgolm,Stokhol\'m,Stokholm,Stokholma,Stokholmas,Stokholmi,Stokholmo,Stokkholm,Stokkholmur,Stokkhólmur,Stokkolma,Stokol\'ma,Stokolm,Stuculma,Stócólm,Sztokholm,Sztokhòlm,Tukholma,astkhlm,seutogholleum,si de ge er mo,stak\'hom,stakahoma,stokahoma,stwkhwlm,stwqhwlm,stxkholm,sutokkuhorumu,Štokholm,Στοκχόλμη,Горад Стакгольм,Стокhольм,Стокгольм,Стокгольм ош,Стокольма,Стокхолм,Стокҳолм,Стёколна,Ստոքհոլմ,סטוקהולם,שטאקהאלם,استکهلم,ستوكهولم,ستۆکھۆڵم,سٹاکہوم,ܣܛܘܩܗܘܠܡ,स्टॉकहोम,স্টকহোম,ஸ்டாக்ஹோம்,സ്റ്റോക്ക്‌ഹോം,สตอกโฮล์ม,སི་ཏོག་ཧོ་ལིམ།,စတော့ဟုမ်းမြို့,სტოკჰოლმი,ስቶኮልም,ᔅᑑᒃᓱᓪᒻ/stuukhulm,ストックホルム,斯德哥尔摩,스톡홀름,', 59.33258, 18.0649, 'P', 'PPLC', 'SE', NULL, '26', '0180', NULL, NULL, 1253309, NULL, 28, 'Europe/Stockholm', '2010-03-28 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (2711537, 'Göteborg', 'Goeteborg', 'G\'oteborg,GOT,Gautaborg,Geteborg,Geteborga,Geteborgas,Gjoteborg,Goeteborg,Goeteborq,Gorad Gjotehborg,Goteborg,Goteburg,Gotemburgo,Gotenburg,Gothembourg,Gothenburg,Gothoburgum,Gotnburg,Gottenborg,Göteborg,Göteborq,Gøteborg,Gēteborga,Nketempornk,ge de bao,ghwtnbrgh,gtbwrg,gwtnbwrg,jwtnbrj,kx then beirk,yeteboli,yohateborya,yotebori,Γκέτεμποργκ,Гетеборг,Горад Гётэборг,Гьотеборг,Гётеборг,גטבורג,געטעבארג,جوتنبرج,غوتنبرغ,گوتنبورگ,گووتھنبرگ,योहतेबोर्य,กอเทนเบิร์ก,გეტებორგი,ዬተቦርይ,ᐃᐅᑕᐳᕆ,ヨーテボリ,哥德堡,예테보리', 57.70716, 11.96679, 'P', 'PPLA', 'SE', NULL, '28', '1480', NULL, NULL, 504084, NULL, 10, 'Europe/Stockholm', '2013-03-11 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (304531, 'Mercin', 'Mercin', 'Icel,Mersin,Mersina,Mersine,Merson,Mersyna,Mersîn,Myrte,Zephyrium,İçel,Μερσίνη,Мерсин', 36.79526, 34.61792, 'P', 'PPLA', 'TR', 'TR', '32', NULL, NULL, NULL, 537842, NULL, 10, 'Europe/Istanbul', '2012-12-20 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (306571, 'Konya', 'Konya', 'Conia,Iconio,Iconium,Ikonio,Ikoniow,KYA,Kon\'ja,Koni,Konia,Konieh,Konija,Konjao,Konya,Kuniyah,Qonia,kon\'ya,qwnyh,qwnyt,Ικόνιο,Кония,Конья,קוניה,قونية,قونیه,コンヤ', 37.87135, 32.48464, 'P', 'PPLA', 'TR', NULL, '71', NULL, NULL, NULL, 875530, NULL, 1030, 'Europe/Istanbul', '2012-09-04 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (308464, 'Kayseri', 'Kayseri', 'ASR,Caesarea,Caesarea in Cappadocia,Casarea,Cearee,Cesarea,Cesarea de Capadocia,Cesarea in Cappadocia,Cesaree,Céarée,Césarée,Eusebeia,Gorad Kajsery,Kaisareia,Kaisaria,Kaisarie,Kaisarije,Kaiseris,Kaizarie,Kajseri,Kayseri,Kaīzarie,Kesaria,Khuajseri,Mazaca,Qaisariye,kai sai li,kaiseli,kaiseri,ki se ri,qysry,qysryh,qysryt,qyysry,Καισάρεια,Горад Кайсеры,Кайсери,Кайсері,Кајсери,Хъайсери,Կեսարիա,קייסרי,قيصرية,قیصری,قیصریه,کیسری شہر,ไกเซรี,კაისერი,カイセリ,開塞利,카이세리', 38.73222, 35.48528, 'P', 'PPLA', 'TR', NULL, '38', NULL, NULL, NULL, 592840, NULL, 1054, 'Europe/Istanbul', '2012-01-18 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (311046, 'İzmir', 'Izmir', 'Azmir,Esmirna,IZM,Ismir,Izmir,Izmira,Izmiras,Izmiro,Smirne,Smyrna,Smyrne,Yazmir,azmyr,izmiri,izumiru,yi zi mi er,İzmir,Σμύρνη,Измир,איזמיר,إزمير,იზმირი,イズミル,伊兹密尔', 38.41273, 27.13838, 'P', 'PPLA', 'TR', NULL, '35', NULL, NULL, NULL, 2500603, NULL, 122, 'Europe/Istanbul', '2012-12-05 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (314830, 'Gaziantep', 'Gaziantep', 'Aintab,Antep,Ayintap,Ayıntap,Dilok,Dîlok,GZT,Gazi-Ayintap,Gaziantep,Gaziantepa,Gaziantepas,Gazijantep,Gorad Gazijantehp,Guaziantep,Nkaziantep,Qazianteb,Qaziantep,gajiantepeu,gaziantepi,gazu~iantepu,jia ji an tai pu,Γκαζιαντέπ,Газиантеп,Газијантеп,Горад Газіянтэп,Гъазиантеп,Ґазіантеп,גזיאנטפ,عنتاب,غازی عینتاب,გაზიანთეფი,ガズィアンテプ,加濟安泰普,가지안테프', 37.05944, 37.3825, 'P', 'PPLA', 'TR', NULL, '83', NULL, NULL, NULL, 1065975, NULL, 842, 'Europe/Istanbul', '2012-01-18 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (315202, 'Eskişehir', 'Eskisehir', 'Dorylaeum,Doryläum,ESK,Ehskishekhir,Eski Shahr,Eski Shehir,Eski shehr,Eski-chehir,Eski-chéhir,Eskischehir,Eskisehir,Eskişehir,Эскишехир', 39.77667, 30.52056, 'P', 'PPLA', 'TR', NULL, '26', NULL, NULL, NULL, 514869, NULL, 794, 'Europe/Istanbul', '2012-04-05 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (316541, 'Diyarbakır', 'Diyarbakir', 'Amed,Amida,DIY,Diarbakir,Diarbekir,Diarbekr,Diari-Bekir,Diari-Békir,Dijarbakir,Dijarbakira,Dijarbakiro,Dijarbakyr,Dijarbakyras,Dijarbekir,Dikranakerd,Diyarbakir,Diyarbakır,Diyarbekir,Diyarbekır,Diyaribakir,Diyaribekir,Diyarıbakır,Diyarıbekir,Gorad Dyjarbakyr,Ntigiarmpakir,di ya ba ke er,diarbakiri,diyaleubakileu,diyarubakuru,dyar bkr,dyarbkr,Ντιγιάρμπακιρ,Горад Дыярбакыр,Диарбекир,Диярбакыр,Дијарбакир,Дијарбекир,Діярбакир,Դիարբեքիր,דיארבקיר,ئامەد,ديار بكر,دیار بکر,دیاربکر,დიარბაქირი,ディヤルバクル,迪亚巴克尔,디야르바키르', 37.91583, 40.21889, 'P', 'PPLA', 'TR', NULL, '21', NULL, NULL, NULL, 644763, NULL, 679, 'Europe/Istanbul', '2012-01-18 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (323777, 'Antalya', 'Antalya', 'AYT,Adalia,Antal\'ja,Antalia,Antalija,Antaliyah,Antalja,Antalya,Antayla,Antália,Atali,Attalea,Attaleia,Attalia,Gorad Antal\'ja,Olbia,Satalia,an ta li ya,antalia,antallia,antalya,antaruya,Αττάλεια,Анталия,Анталија,Анталья,Анҭалиа,Горад Анталья,אנטליה,آنتالیا,أنطاليا,अंताल्या,ანთალია,アンタルヤ,安塔利亚,安塔利亞,안탈리아', 36.90812, 30.69556, 'P', 'PPLA', 'TR', NULL, '07', NULL, NULL, NULL, 758188, NULL, 61, 'Europe/Istanbul', '2012-01-18 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (323786, 'Ankara', 'Ankara', 'ANK,Anakara,Ancara,Ancyra,Ang-ka-la,Angkara,Angora,Anguriyah,Ankar,Ankara,Ankaro,Ankuara,Ankura,Ankyra,Ankyra (Ankyra),Anqara,Enguri,Engüri,Enqere,Gorad Ankara,an ka la,angkala,ankara,anqrh,anqrt,xangkara,Ăng-kā-lá,Άγκυρα (Ankyra),Анкара,Анкъара,Горад Анкара,Әнкара,Անկարա,אנקארא,אנקרה,آنکارا,أنقرة,ئەنقەرە,انقره,انقرہ,انکرہ,ܐܢܩܪܐ,अंकारा,আঙ্কারা,ଆଙ୍କାରା,அங்காரா,అంకారా,ಅಂಕಾರಾ,അങ്കാറ,อังการา,ཨན་ཁ་ར།,ანკარა,አንካራ,アンカラ,安卡拉,앙카라', 39.91987, 32.85427, 'P', 'PPLC', 'TR', NULL, '68', NULL, NULL, NULL, 3517182, 850, 874, 'Europe/Istanbul', '2013-03-08 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (325363, 'Adana', 'Adana', 'ADA,Adana,Adhanah,Antiocheia,Edene,Gorad Adana,a da na,adana,adnt,yadana,Άδανα,Αδανα,Αντιόχεια,Адана,Горад Адана,Ադանա,אדנה,آدانا,أضنة,ئادانا,अदना,আদানা,ადანა,アダナ,阿达纳,아다나', 37.00167, 35.32889, 'P', 'PPLA', 'TR', NULL, '81', NULL, NULL, NULL, 1248988, NULL, 41, 'Europe/Istanbul', '2012-01-18 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (738329, 'Üsküdar', 'UEskuedar', 'Chrysopolis,Scutari,Skutari,Uskjudar,Uskudar,askdar,Üsküdar,Ускюдар,اسکدار', 41.02252, 29.02369, 'P', 'PPL', 'TR', 'TR', '34', NULL, NULL, NULL, 582666, NULL, 56, 'Europe/Istanbul', '2013-11-13 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (738377, 'Umraniye', 'Umraniye', NULL, 41.01643, 29.12476, 'P', 'PPL', 'TR', NULL, '34', NULL, NULL, NULL, 573265, NULL, 136, 'Europe/Istanbul', '2011-01-05 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (745044, 'İstanbul', 'Istanbul', 'Bizanc,Bizánc,Byzance,Byzantion,Byzantium,Byzanz,Constantinoble,Constantinopla,Constantinople,Constantinopoli,Constantinopolis,Costantinopoli,Estambul,IST,Istamboul,Istambul,Istambuł,Istampoul,Istanbul,Istanbúl,Isztambul,Konstantinapoly,Konstantinopel,Konstantinopolo,Konstantinoupole,Konstantinoupoli,Konstantinoupolis,Konstantinápoly,Kustantiniyah,Micklagard,Micklagård,Mikligardur,Mikligarður,Stamboul,Stambul,Stambula,Stambuł,Tsarigrad,Vizantija (Vizantija),Vyzantio,astnbwl,bijantion,byuzantion,byzntywn,isutanburu,stin Poli [stimˈboli],yi si tan bu er,İstanbul,Βυζάντιο,Βυζαντιο,Ισταμπουλ,Ισταμπούλ,Κωνσταντινουπολη,Κωνσταντινούπολη,Κωνσταντινούπολις,στην Πόλι [stimˈboli],Византија (Vizantija),Истанбул,Стамбул,ביזנטיון,اسطنبول,イスタンブール,ビュザンティオン,伊斯坦布尔,비잔티온', 41.01384, 28.94966, 'P', 'PPLA', 'TR', NULL, '34', NULL, NULL, NULL, 11174257, NULL, 39, 'Europe/Istanbul', '2013-06-11 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (747340, 'Esenler', 'Esenler', 'Litros', 41.0435, 28.87619, 'P', 'PPL', 'TR', 'TR', '34', NULL, NULL, NULL, 520235, NULL, 72, 'Europe/Istanbul', '2011-01-04 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (750269, 'Bursa', 'Bursa', 'Boursa,Brossa,Broussa,Brousse,Brusa,Brussa,Bursa,Bursae,Gorad Bursa,Prousa,YEI,bu er sa,buleusa,bur sa,bursa,burusa,bwrsa,bwrsh,Προύσα,Бурсæ,Бурса,Горад Бурса,בורסה,برصہ,بورسا,بورصا,بۇرسا,บูร์ซา,ბურსა,ブルサ,布尔萨,부르사', 40.19266, 29.08403, 'P', 'PPLA', 'TR', NULL, '16', '7732338', NULL, NULL, 1412701, NULL, 152, 'Europe/Istanbul', '2013-05-08 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (751324, 'Bağcılar', 'Bagcilar', 'Badzilaras rajons,Bagcilar,Bagdzhylar,Bağcılar,Bādžilaras rajons,Cifitburgaz,Ciftburgaz,Çiftburgaz,Çıfıtburgaz,Багджылар', 41.03903, 28.85671, 'P', 'PPL', 'TR', NULL, '34', NULL, NULL, NULL, 724270, NULL, 105, 'Europe/Istanbul', '2011-02-17 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (6955677, 'Çankaya', 'Cankaya', NULL, 39.9179, 32.86268, 'P', 'PPL', 'TR', NULL, '68', NULL, NULL, NULL, 792189, 850, 889, 'Europe/Istanbul', '2009-10-26 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (7627067, 'Bahçelievler', 'Bahcelievler', NULL, 41.00231, 28.8598, 'P', 'PPL', 'TR', NULL, '34', NULL, NULL, NULL, 576799, NULL, 73, 'Europe/Istanbul', '2010-12-30 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (687700, 'Zaporizhzhya', 'Zaporizhzhya', 'Aleksandrovsk,OZH,Saporischschja,Saporoshje,Zaborozha,Zaporijia,Zaporizhia,Zaporizhzhya,Zaporizza,Zaporozh\'e,Zaporozh\'ye,Zaporozhye,Zaporozh’e,Zaporozh’ye,Запорожье,Запоріжжя', 47.82289, 35.19031, 'P', 'PPLA', 'UA', NULL, '26', NULL, NULL, NULL, 796217, NULL, 84, 'Europe/Zaporozhye', '2013-11-22 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (698740, 'Odessa', 'Odessa', 'Ades,Gorad Adehsa,ODS,Odesa,Odessa,Odessae,Odessos,Odessus,Odessza,Oděsa,Udessa,ao de sa,awdsa,awdysa,odesa,odessa,Ódessa,Οδησσός,Горад Адэса,Одеса,Одессæ,Одесса,Օդեսա,אדעס,אודסה,أوديسا,اودسا,اودیسا,ओदेसा,အိုဒက်ဆာမြို့,ოდესა,オデッサ,敖德薩,오데사', 46.47747, 30.73262, 'P', 'PPLA', 'UA', NULL, '17', NULL, NULL, NULL, 1001558, NULL, 58, 'Europe/Kiev', '2011-08-17 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (700569, 'Mykolayiv', 'Mykolayiv', 'Mikolaiv,Mykolaiv,Mykolayiv,NLV,Nikolaev,Nikolajew,Nikolayev,Vernoleninsk,Миколаїв,Николаев', 46.96591, 31.9974, 'P', 'PPLA', 'UA', NULL, '16', NULL, NULL, NULL, 510840, NULL, 18, 'Europe/Kiev', '2013-09-04 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (702550, 'L\'viv', 'L\'viv', 'L\'viv,L\'vov,LWO,Lavov,Lemberg,Leopoli,Leopolis,Leópolis,Liov,Lviv,Lvivo,Lvov,Lvova,Lvovas,Lwow,Lwów,l\'vov,lbwb,li wo fu,libiu,lvov,rivu~iu,Ļvova,Ľvov,Лвов,Львов,Львів,לבוב,לעמבערג,リヴィウ,利沃夫,리비우', 49.83826, 24.02324, 'P', 'PPLA', 'UA', NULL, '15', NULL, NULL, NULL, 717803, NULL, 284, 'Europe/Kiev', '2013-04-07 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (703448, 'Kiev', 'Kiev', 'Chijv,Civ,Cív,Gorad Kieu,IEV,Kaenugardur,Keju,Kiebo,Kief,Kieu,Kiev,Kiev osh,Kievi,Kievo,Kiew,Kiiev,Kiiv,Kijev,Kijeva,Kijevas,Kijew,Kijow,Kijuw,Kijv,Kijów,Kijůw,Kiova,Kiovia,Kiyev,Kiyiw,Kiëf,Kjiv,Kueyiv,Kyev,Kyiiv,Kyiv,Kyjev,Kyjiv,Kyjiw,Kyèv,Kænugarður,Kíev,Kîev,Küyiv,ji fu,kheiyf,kiefu,kiv,kiva,kiyebha,kiyepeu,kyf,kyiva,kyyf,qyyb,Κίεβο,Горад Кіеў,Кейӳ,Киев,Киев ош,Київ,Кијев,Кыив,Кыйив,Кꙑѥвъ,Կիև,קייב,קיעוו,كىيېۋ,كييف,کیف,کیێڤ,کی‌یف,कीव,क्यीव,কিয়েভ,கீவ்,കീവ്,เคียฟ,ཀིབ།,ကီးယက်မြို့,კიევი,ኪየቭ,キエフ,基輔,키예프', 50.45466, 30.5238, 'P', 'PPLC', 'UA', NULL, '12', NULL, NULL, NULL, 2797553, NULL, 187, 'Europe/Kiev', '2013-07-08 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (703845, 'Kryvyy Rih', 'Kryvyy Rih', 'KWG,Krivij Rig,Krivoj Rog,Krivoy Rog,Kriwoi Rog,Kryvyi Rih,Kryvyj Rih,Kryvyy Rih,Кривий Ріг,Кривой Рог', 47.90966, 33.38044, 'P', 'PPL', 'UA', NULL, '04', NULL, NULL, NULL, 652380, NULL, 99, 'Europe/Kiev', '2013-11-02 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (706483, 'Kharkiv', 'Kharkiv', 'Carcovia,Carcóvia,Charcovia,Charkiv,Charkiw,Charkov,Charkovas,Charkow,Charków,HRK,Harkiv,Harkiva,Harkivo,Harkov,Harkova,Jarkov,Khar\'kov,Kharkiv,Kharkov,Khar’kov,ha er ke fu,haleukiu,harikofu,hrqwb,kharkwf,Ĥarkivo,Ĥarkov,Харков,Харків,Харьков,חרקוב,خاركوف,ხარკოვი,ハリコフ,哈爾科夫,하르키우', 49.98081, 36.25272, 'P', 'PPLA', 'UA', NULL, '07', NULL, NULL, NULL, 1430885, NULL, 113, 'Europe/Kiev', '2012-01-18 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (709717, 'Donets’k', 'Donets\'k', 'Donec\'k,Doneck,Donets\'k,Donetsk,Donets’k,Hughesovka,Jusowka,Juzovka,Stalin,Staline,Stalino,Uzivka,Yuzovka,Yuzovo,Донецк,Донецьк,Сталино,Сталіне,Сталіно,Юзовка,Юзівка', 48.023, 37.80224, 'P', 'PPLA', 'UA', NULL, '05', NULL, NULL, NULL, 1024700, NULL, 223, 'Europe/Kiev', '2012-08-19 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (709930, 'Dnipropetrovsk', 'Dnipropetrovsk', 'DNK,Dnepropetrovsk,Dnepropetrovska,Dnepropetrowsk,Dnetropetrovsk,Dniepropetrovsk,Dniepropetrovskas,Dniepropetrowsk,Dnipropetrovs\'k,Dnipropetrovsk,Dnipropetrovsko,Dnipropetrowsk,Dnjepropetrovsk,Dnjepropetrowsk,Dnjipropetrovsk,Dnyipropetrovszk,Dněpropetrovsk,Dņepropetrovska,Ekaterinoslav,Ekaṭerinoslav,Gorad Dneprapjatrousk,Iekaterinoslav,Katerynoslav,Ntnipropetrofsk,Yekaterinoslav,d ni por pet rxfskh,deunipeulopeteulousikeu,di nie bo luo bi de luo fu si ke,dnepropetrovska,dnybrwbtrwfsk,dnyprwptrwbsq,dnyprwptrwfsk,dnyprwptrwwsk,donipuropetoroushiku,Ντνιπροπετρόφσκ,Горад Днепрапятроўск,Днепропетровск,Дніпропетровськ,Днїпропетровск,Дњепропетровск,Екатеринослав,Դնեպրոպետրովսկ,דניפרופטרובסק,دنيبروبتروفسك,دنیپروپتروفسک,دنیپروپترووسک,द्नेप्रोपेत्रोव्स्क,ดนีโปรเปตรอฟสค์,დნეპროპეტროვსკი,ドニプロペトロウシク,第聂伯罗彼得罗夫斯克,드니프로페트로우시크', 48.45, 34.98333, 'P', 'PPLA', 'UA', NULL, '04', NULL, NULL, NULL, 1032822, NULL, 140, 'Europe/Kiev', '2012-08-19 00:00:00');
INSERT INTO `sch_geonames_eu500k` (`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature class`, `feature code`, `country code`, `cc2`, `admin1 code`, `admin2 code`, `admin3 code`, `admin4 code`, `population`, `elevation`, `dem`, `timezone`, `modification date`) VALUES (786714, 'Pristina', 'Pristina', 'Gorad Pryshcina,PRN,Prishhina,Prishtina,Prishtinae,Prishtine,Prishtinë,Prisjtina,Pristina,Pristine,Pristino,Prisztina,Prixtina,Priŝtino,Priştina,Priştine,Priština,Priștina,Prìstina,Prístina,bryshtyna,peulisyutina,phrich ti na,piristina,prisatina,pristina,pryshtyna,prystynh,pu li shen di na,purishutina,Πρίστινα,Горад Прышціна,Приштинæ,Приштина,Прищина,Պրիշտինա,פרישטינה,بريشتينا,پریسٹینہ,پریشتینا,प्रिस्टिना,ਪ੍ਰਿਸ਼ਤੀਨਾ,பிரிஸ்டினா,พริชตีนา,པི་རི་སི་ཊི་ན།,პრიშტინა,プリシュティナ,普里什蒂纳,프리슈티나', 42.67272, 21.16688, 'P', 'PPLC', 'XK', NULL, '20', NULL, NULL, NULL, 550000, NULL, 597, 'Europe/Belgrade', '2013-05-19 00:00:00');

COMMIT;


-- -----------------------------------------------------
-- Data for table `meta_access`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `meta_access` (`id`, `label`) VALUES (10, 'Owner');
INSERT INTO `meta_access` (`id`, `label`) VALUES (20, 'Editor');
INSERT INTO `meta_access` (`id`, `label`) VALUES (30, 'Contributor');

COMMIT;


-- -----------------------------------------------------
-- Data for table `meta_usr_level`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `meta_usr_level` (`id`, `label`) VALUES (1, 'Super administrator');
INSERT INTO `meta_usr_level` (`id`, `label`) VALUES (2, 'Administrator');
INSERT INTO `meta_usr_level` (`id`, `label`) VALUES (4, 'Editor');
INSERT INTO `meta_usr_level` (`id`, `label`) VALUES (6, 'User');
INSERT INTO `meta_usr_level` (`id`, `label`) VALUES (99, 'Guest user');

COMMIT;

