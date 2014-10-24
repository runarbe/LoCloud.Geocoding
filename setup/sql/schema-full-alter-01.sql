SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

ALTER TABLE `default_schema`.`ds1_match` 
DROP COLUMN `gc_timestamp`,
DROP COLUMN `gc_puri`,
DROP COLUMN `gc_dbsearch_id`,
DROP COLUMN `gc_dbsearch_db`,
DROP COLUMN `gc_full_geom`,
DROP COLUMN `gc_name2`,
CHANGE COLUMN `gc_probability` `gc_probability` INT(11) NULL DEFAULT NULL ,
DROP INDEX `idx_match_fk_db_id` ;

ALTER TABLE `default_schema`.`meta_dbsearch` 
ADD COLUMN `sch_apikey` VARCHAR(200) NULL DEFAULT NULL AFTER `sch_webservice`;

ALTER TABLE `default_schema`.`meta_match_template` 
DROP COLUMN `gc_timestamp`,
DROP COLUMN `gc_puri`,
DROP COLUMN `gc_dbsearch_id`,
DROP COLUMN `gc_dbsearch_db`,
DROP COLUMN `gc_full_geom`,
DROP COLUMN `gc_name2`,
CHANGE COLUMN `gc_probability` `gc_probability` INT(11) NULL DEFAULT NULL ,
ADD COLUMN `gc_confidence` INT(11) NULL DEFAULT NULL AFTER `gc_probability`,
ADD COLUMN `gc_mapresolution` FLOAT(11) NULL DEFAULT NULL AFTER `gc_confidence`,
DROP INDEX `idx_match_gc_dbsearch_db` ;

ALTER TABLE `default_schema`.`meta_usr` 
CHANGE COLUMN `usr` `usr` VARCHAR(250) NOT NULL ,
CHANGE COLUMN `pwd` `pwd` VARCHAR(20) NOT NULL ;


-- -----------------------------------------------------
-- Placeholder table for view `default_schema`.`meta_usr_group_acl`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `default_schema`.`meta_usr_group_acl` (`id` INT, `usr_id` INT, `group_id` INT, `access` INT);

-- -----------------------------------------------------
-- Placeholder table for view `default_schema`.`meta_usr_datasources_acl`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `default_schema`.`meta_usr_datasources_acl` (`id` INT, `usr_id` INT, `datasource_id` INT, `access` INT);

-- -----------------------------------------------------
-- Placeholder table for view `default_schema`.`meta_usr_usr_acl`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `default_schema`.`meta_usr_usr_acl` (`id` INT, `usr_id` INT, `usr_id2` INT, `access` INT);


USE `default_schema`;

-- -----------------------------------------------------
-- View `default_schema`.`meta_usr_group_acl`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `default_schema`.`meta_usr_group_acl`;
USE `default_schema`;
CREATE  OR REPLACE VIEW `meta_usr_group_acl` AS
    SELECT distinct
        id, src_id usr_id, tgt_id group_id, access
    FROM
        meta_acl
    WHERE
        src_type = 'meta_usr'
            AND tgt_type = 'meta_group';


USE `default_schema`;

-- -----------------------------------------------------
-- View `default_schema`.`meta_usr_datasources_acl`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `default_schema`.`meta_usr_datasources_acl`;
USE `default_schema`;
CREATE  OR REPLACE VIEW `meta_usr_datasources_acl` AS
    SELECT distinct
        id, src_id usr_id, tgt_id datasource_id, access
    FROM
        meta_acl
    WHERE
        src_type = 'meta_usr'
            AND tgt_type = 'meta_datasources';


USE `default_schema`;

-- -----------------------------------------------------
-- View `default_schema`.`meta_usr_usr_acl`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `default_schema`.`meta_usr_usr_acl`;
USE `default_schema`;
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
