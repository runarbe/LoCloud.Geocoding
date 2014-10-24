SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


CREATE TABLE IF NOT EXISTS ds1 (
  id double DEFAULT NULL,
  title varchar(255) DEFAULT NULL,
  description varchar(255) DEFAULT NULL,
  country varchar(255) DEFAULT NULL,
  region varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  imageurl varchar(255) DEFAULT NULL,
  latitude double DEFAULT NULL,
  longitude double DEFAULT NULL,
  KEY idx_ds1_pk (id),
  KEY idx_ds1_adm0 (country),
  KEY idx_ds1_adm1 (region),
  KEY idx_ds1_cat (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS ds1_adm0 (
  adm0 varchar(255) DEFAULT NULL,
  minx double DEFAULT NULL,
  miny double DEFAULT NULL,
  maxx double DEFAULT NULL,
  maxy double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS ds1_adm1 (
  adm0 varchar(255) DEFAULT NULL,
  adm1 varchar(255) DEFAULT NULL,
  minx double DEFAULT NULL,
  miny double DEFAULT NULL,
  maxx double DEFAULT NULL,
  maxy double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS ds1_cat (
  category varchar(255) DEFAULT NULL,
  minx double DEFAULT NULL,
  miny double DEFAULT NULL,
  maxx double DEFAULT NULL,
  maxy double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS ds1_match (
  id int(11) NOT NULL AUTO_INCREMENT,
  fk_ds_id int(11) NOT NULL,
  gc_name varchar(512) DEFAULT NULL,
  gc_name2 varchar(512) DEFAULT NULL,
  gc_lon double NOT NULL,
  gc_lat double NOT NULL,
  gc_fieldchanges text,
  gc_geom text,
  gc_full_geom text,
  gc_usr_id int(11) NOT NULL,
  gc_probability int(11) NOT NULL,
  gc_dbsearch_db int(11) DEFAULT NULL,
  gc_dbsearch_id int(11) DEFAULT NULL,
  gc_dbsearch_puri varchar(512) DEFAULT NULL,
  gc_puri varchar(512) DEFAULT NULL,
  gc_timestamp timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  created timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  updated timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  deleted tinyint(1) DEFAULT '0',
  PRIMARY KEY (id),
  UNIQUE KEY idx_match_composite (fk_ds_id,gc_usr_id),
  KEY idx_match_fk_ds_id (fk_ds_id),
  KEY idx_match_gc_usr_id (gc_usr_id),
  KEY idx_match_gc_probability (gc_probability),
  KEY idx_match_fk_db_id (gc_dbsearch_id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

CREATE TABLE IF NOT EXISTS meta_access (
  id int(11) NOT NULL,
  label varchar(45) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS meta_acl (
  id int(11) NOT NULL AUTO_INCREMENT,
  src_type varchar(25) NOT NULL COMMENT 'meta_usr',
  src_id int(11) NOT NULL,
  tgt_type varchar(25) NOT NULL COMMENT 'meta_datasources',
  tgt_id int(11) NOT NULL,
  access int(11) NOT NULL DEFAULT '20' COMMENT 'owner = 10',
  PRIMARY KEY (id),
  UNIQUE KEY idxu_src_tgt (src_type,src_id,tgt_type,tgt_id),
  KEY idx_src (src_type,src_id),
  KEY idc_tgt (tgt_type,tgt_id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=83 ;

CREATE TABLE IF NOT EXISTS meta_datasources (
  id int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier',
  ds_title varchar(50) NOT NULL COMMENT 'Name of a column in the source dataset that contains titles of items to be geocoded',
  ds_col_pk varchar(50) NOT NULL COMMENT 'Name of a column in the source dataset that contains a unique identifier for each record',
  ds_col_name varchar(50) NOT NULL,
  ds_col_x varchar(50) DEFAULT NULL,
  ds_col_y varchar(50) DEFAULT NULL,
  ds_srs varchar(50) NOT NULL,
  ds_table varchar(50) NOT NULL,
  ds_col_cat varchar(50) DEFAULT NULL,
  ds_col_adm0 varchar(50) DEFAULT NULL,
  ds_col_adm1 varchar(50) DEFAULT NULL,
  ds_coord_prec int(11) DEFAULT NULL,
  ds_col_image varchar(50) DEFAULT NULL,
  ds_col_url varchar(50) DEFAULT NULL,
  ds_col_puri varchar(512) DEFAULT NULL COMMENT 'Name of a column that contains a persistent URI',
  created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  deleted tinyint(1) DEFAULT '0',
  PRIMARY KEY (id),
  UNIQUE KEY id_UNIQUE (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

CREATE TABLE IF NOT EXISTS meta_dbsearch (
  id int(11) NOT NULL AUTO_INCREMENT,
  sch_title varchar(45) DEFAULT NULL,
  sch_table varchar(45) DEFAULT NULL,
  sch_display varchar(200) DEFAULT NULL,
  sch_lev1 varchar(200) DEFAULT NULL,
  sch_lev2 varchar(200) DEFAULT NULL,
  sch_lev3 varchar(200) DEFAULT NULL,
  sch_epsg int(11) DEFAULT NULL,
  sch_lon varchar(200) DEFAULT NULL,
  sch_lat varchar(200) DEFAULT NULL,
  sch_like varchar(200) DEFAULT NULL,
  sch_eq varchar(200) DEFAULT NULL,
  sch_webservice varchar(200) DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

CREATE TABLE IF NOT EXISTS meta_group (
  id int(11) NOT NULL AUTO_INCREMENT,
  title varchar(250) NOT NULL,
  description varchar(45) DEFAULT NULL,
  created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  deleted tinyint(1) DEFAULT '0',
  PRIMARY KEY (id),
  UNIQUE KEY idx_meta_group_title (title)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

CREATE TABLE IF NOT EXISTS meta_match_template (
  id int(11) NOT NULL AUTO_INCREMENT,
  fk_ds_id int(11) NOT NULL,
  gc_name varchar(512) DEFAULT NULL,
  gc_name2 varchar(512) DEFAULT NULL,
  gc_lon double NOT NULL,
  gc_lat double NOT NULL,
  gc_fieldchanges text,
  gc_geom text,
  gc_full_geom text,
  gc_usr_id int(11) NOT NULL,
  gc_probability int(11) NOT NULL,
  gc_dbsearch_db int(11) DEFAULT NULL,
  gc_dbsearch_id int(11) DEFAULT NULL,
  gc_dbsearch_puri varchar(512) DEFAULT NULL,
  gc_puri varchar(512) DEFAULT NULL,
  gc_timestamp timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  deleted tinyint(1) DEFAULT '0',
  PRIMARY KEY (id),
  UNIQUE KEY idx_match_composite (fk_ds_id,gc_usr_id),
  KEY idx_match_fk_ds_id (fk_ds_id),
  KEY idx_match_gc_usr_id (gc_usr_id),
  KEY idx_match_gc_probability (gc_probability),
  KEY idx_match_gc_dbsearch_db (gc_dbsearch_db)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS meta_srs (
  id int(11) NOT NULL AUTO_INCREMENT,
  srs_name varchar(150) NOT NULL,
  srs text NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS meta_uploads (
  id int(11) NOT NULL AUTO_INCREMENT,
  fk_meta_usr_id int(11) NOT NULL,
  fname varchar(512) NOT NULL,
  tstamp timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS meta_usr (
  id int(11) NOT NULL AUTO_INCREMENT,
  usr varchar(250) NOT NULL,
  pwd varchar(20) NOT NULL,
  `level` int(11) NOT NULL DEFAULT '5',
  created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  deleted tinyint(1) DEFAULT '0',
  PRIMARY KEY (id),
  UNIQUE KEY idx_usr_usr (usr)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=29 ;
CREATE TABLE IF NOT EXISTS `meta_usr_datasources_acl` (
`id` int(11)
,`usr_id` int(11)
,`datasource_id` int(11)
,`access` int(11)
);CREATE TABLE IF NOT EXISTS `meta_usr_group_acl` (
`id` int(11)
,`usr_id` int(11)
,`group_id` int(11)
,`access` int(11)
);
CREATE TABLE IF NOT EXISTS meta_usr_level (
  id int(11) NOT NULL,
  label varchar(45) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE IF NOT EXISTS `meta_usr_usr_acl` (
`id` int(11)
,`usr_id` int(11)
,`usr_id2` int(11)
,`access` int(11)
);
CREATE TABLE IF NOT EXISTS sch_geonames_eu500k (
  geonameid int(11) DEFAULT NULL,
  `name` varchar(200) DEFAULT NULL,
  asciiname varchar(200) DEFAULT NULL,
  alternatenames mediumtext,
  latitude double DEFAULT NULL,
  longitude double DEFAULT NULL,
  `feature class` varchar(1) DEFAULT NULL,
  `feature code` varchar(10) DEFAULT NULL,
  `country code` varchar(2) DEFAULT NULL,
  cc2 varchar(60) DEFAULT NULL,
  `admin1 code` varchar(20) DEFAULT NULL,
  `admin2 code` varchar(80) DEFAULT NULL,
  `admin3 code` varchar(20) DEFAULT NULL,
  `admin4 code` varchar(20) DEFAULT NULL,
  population int(11) DEFAULT NULL,
  elevation smallint(6) DEFAULT NULL,
  dem double DEFAULT NULL,
  timezone varchar(40) DEFAULT NULL,
  `modification date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS sch_geonames_template (
  geonameid int(11) DEFAULT NULL,
  `name` varchar(200) DEFAULT NULL,
  asciiname varchar(200) DEFAULT NULL,
  alternatenames mediumtext,
  latitude double DEFAULT NULL,
  longitude double DEFAULT NULL,
  feature_class varchar(1) DEFAULT NULL,
  feature_code varchar(10) DEFAULT NULL,
  country_code varchar(2) DEFAULT NULL,
  cc2 varchar(60) DEFAULT NULL,
  admin1_code varchar(20) DEFAULT NULL,
  admin2_code varchar(80) DEFAULT NULL,
  admin3_code varchar(20) DEFAULT NULL,
  admin4_code varchar(20) DEFAULT NULL,
  population int(11) DEFAULT NULL,
  elevation smallint(6) DEFAULT NULL,
  dem double DEFAULT NULL,
  timezone varchar(40) DEFAULT NULL,
  modification_date datetime DEFAULT NULL,
  geonames_uri varchar(512) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `meta_usr_datasources_acl`;

CREATE ALGORITHM=UNDEFINED DEFINER=postvo5_locloud@localhost SQL SECURITY DEFINER VIEW postvo5_locloudv2.meta_usr_datasources_acl AS select distinct postvo5_locloudv2.meta_acl.id AS id,postvo5_locloudv2.meta_acl.src_id AS usr_id,postvo5_locloudv2.meta_acl.tgt_id AS datasource_id,postvo5_locloudv2.meta_acl.access AS access from postvo5_locloudv2.meta_acl where ((postvo5_locloudv2.meta_acl.src_type = 'meta_usr') and (postvo5_locloudv2.meta_acl.tgt_type = 'meta_datasources'));
DROP TABLE IF EXISTS `meta_usr_group_acl`;

CREATE ALGORITHM=UNDEFINED DEFINER=postvo5_locloud@localhost SQL SECURITY DEFINER VIEW postvo5_locloudv2.meta_usr_group_acl AS select distinct postvo5_locloudv2.meta_acl.id AS id,postvo5_locloudv2.meta_acl.src_id AS usr_id,postvo5_locloudv2.meta_acl.tgt_id AS group_id,postvo5_locloudv2.meta_acl.access AS access from postvo5_locloudv2.meta_acl where ((postvo5_locloudv2.meta_acl.src_type = 'meta_usr') and (postvo5_locloudv2.meta_acl.tgt_type = 'meta_group'));
DROP TABLE IF EXISTS `meta_usr_usr_acl`;

CREATE ALGORITHM=UNDEFINED DEFINER=postvo5_locloud@localhost SQL SECURITY DEFINER VIEW postvo5_locloudv2.meta_usr_usr_acl AS select distinct postvo5_locloudv2.meta_acl.id AS id,postvo5_locloudv2.meta_acl.src_id AS usr_id,postvo5_locloudv2.meta_acl.tgt_id AS usr_id2,postvo5_locloudv2.meta_acl.access AS access from postvo5_locloudv2.meta_acl where ((postvo5_locloudv2.meta_acl.src_type = 'meta_usr') and (postvo5_locloudv2.meta_acl.tgt_type = 'meta_usr'));

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
