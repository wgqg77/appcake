

CREATE TABLE IF NOT EXISTS `app_system`.`ad_sort_one` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `camp_id` BIGINT(20) NOT NULL DEFAULT 0,
  `app_id` INT(11) NOT NULL DEFAULT 0,
  `country` CHAR(2) NULL,
  `position` TINYINT(4) NULL COMMENT '位置 0表示首页 1表示游戏 2表示应用 3表示banner',
  `source` TINYINT(4) NULL,
  `current_sort` INT(11) NULL,
  `is_ad` TINYINT(2) NULL,
  `next_sort` INT(11) NULL,
  `last_sort` INT(11) NULL,
  PRIMARY KEY (`id`),
  INDEX `index2` (`country` ASC, `position` ASC, `app_id` ASC, `current_sort` ASC, `next_sort` ASC))
ENGINE = InnoDB


CREATE TABLE IF NOT EXISTS `app_system`.`ad_sort_two` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `camp_id` BIGINT(20) NOT NULL DEFAULT 0,
  `app_id` INT(11) NOT NULL DEFAULT 0,
  `country` CHAR(2) NULL,
  `position` TINYINT(4) NULL COMMENT '位置 0表示首页 1表示游戏 2表示应用 3表示banner',
  `source` TINYINT(4) NULL,
  `current_sort` INT(11) NULL,
  `is_ad` TINYINT(2) NULL,
  `next_sort` INT(11) NULL,
  `last_sort` INT(11) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB


CREATE TABLE IF NOT EXISTS `app_system`.`ad_sort_two_preview` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `camp_id` BIGINT(20) NOT NULL DEFAULT 0,
  `app_id` INT(11) NOT NULL DEFAULT 0,
  `country` CHAR(2) NULL,
  `position` TINYINT(4) NULL COMMENT '位置 0表示首页 1表示游戏 2表示应用 3表示banner',
  `source` TINYINT(4) NULL,
  `current_sort` INT(11) NULL,
  `is_ad` TINYINT(2) NULL,
  `next_sort` INT(11) NULL,
  `last_sort` INT(11) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB



CREATE TABLE IF NOT EXISTS `app_system`.`ad_sort_record` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `country` CHAR(2) NULL,
  `camp_id` BIGINT(20) NOT NULL DEFAULT 0,
  `app_id` INT(11) NOT NULL DEFAULT 0,
  `position` TINYINT(4) NULL COMMENT '位置 0表示首页 1表示游戏 2表示应用 3表示banner',
  `sort_id` INT(11) NULL,
  `current_sort` INT(11) NULL,
  `next_sort` INT(11) NULL,
  `sort_method` TINYINT(2) NULL COMMENT '修改排序方式：0即时生效、\n预排整点生效：11、兑换位置、12自动排序、3、\n预排',
  `update_method` TINYINT(2) NULL COMMENT '生效方式：0下时段自动生效、1即时生效、2、预定时间生效',
  `is_updated` TINYINT(2) NOT NULL DEFAULT 0 COMMENT '是否已经更新：0未更新，1已经更新完成，2更新异常',
  `create_time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP() COMMENT '记录生成时间',
  `start_time` TIMESTAMP NULL COMMENT '生效时间',
  `end_time` TIMESTAMP NULL,
  `is_ad` TINYINT(2) NULL DEFAULT 0,
  `source` TINYINT(4) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_bin
COMMENT = '广告排序操作记录表'


CREATE TABLE IF NOT EXISTS `app_system`.`ad_sort_history` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `camp_id` BIGINT(20) NOT NULL DEFAULT 0,
  `app_id` INT(11) NOT NULL DEFAULT 0,
  `country` CHAR(2) NULL,
  `position` TINYINT(4) NULL COMMENT '位置 0表示首页 1表示游戏 2表示应用 3表示banner',
  `source` TINYINT(4) NULL,
  `current_sort` INT(11) NULL,
  `is_ad` TINYINT(2) NULL,
  `click` INT(11) NULL,
  `down` INT(11) NULL,
  `install` INT(11) NULL,
  `create_time` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `ad_sort_id` INT(11) NULL,
  PRIMARY KEY (`id`),
  INDEX `index2` (`ad_sort_id` ASC))
ENGINE = InnoDB




