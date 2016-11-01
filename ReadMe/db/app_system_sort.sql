/*
 Navicat MySQL Data Transfer

 Source Server         : nginx
 Source Server Version : 50629
 Source Host           : localhost
 Source Database       : app_system

 Target Server Version : 50629
 File Encoding         : utf-8

 Date: 06/23/2016 10:56:40 AM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;



-- ----------------------------
--  Table structure for `ad_sort_history`
-- ----------------------------
DROP TABLE IF EXISTS `ad_sort_history`;
CREATE TABLE `ad_sort_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `camp_id` int(11) NOT NULL,
  `app_id` int(11) NOT NULL DEFAULT '0',
  `country` char(2) COLLATE utf8_bin DEFAULT NULL,
  `position` tinyint(4) DEFAULT NULL COMMENT '位置 0表示首页 1表示游戏 2表示应用 3表示banner',
  `source` tinyint(4) DEFAULT NULL,
  `current_sort` int(11) DEFAULT NULL,
  `is_ad` tinyint(2) DEFAULT NULL,
  `click` int(11) DEFAULT NULL,
  `down` int(11) DEFAULT NULL,
  `install` int(11) DEFAULT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ad_sort_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
--  Table structure for `ad_sort_one`
-- ----------------------------
DROP TABLE IF EXISTS `ad_sort_one`;
CREATE TABLE `ad_sort_one` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `camp_id` int(11) NOT NULL,
  `app_id` int(11) NOT NULL DEFAULT '0',
  `country` char(2) COLLATE utf8_bin DEFAULT NULL,
  `position` tinyint(4) DEFAULT NULL COMMENT '位置 0表示首页 1表示游戏 2表示应用 3表示banner',
  `source` tinyint(4) DEFAULT NULL,
  `current_sort` int(11) DEFAULT NULL,
  `is_ad` tinyint(2) DEFAULT NULL,
  `next_sort` int(11) DEFAULT NULL,
  `last_sort` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
--  Table structure for `ad_sort_preview`
-- ----------------------------
DROP TABLE IF EXISTS `ad_sort_preview`;
CREATE TABLE `ad_sort_preview` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `camp_id` int(11) NOT NULL,
  `app_id` int(11) NOT NULL DEFAULT '0',
  `country` char(2) COLLATE utf8_bin DEFAULT NULL,
  `position` tinyint(4) DEFAULT NULL COMMENT '位置 0表示首页 1表示游戏 2表示应用 3表示banner',
  `source` tinyint(4) DEFAULT NULL,
  `current_sort` int(11) DEFAULT NULL,
  `is_ad` tinyint(2) DEFAULT NULL,
  `next_sort` int(11) DEFAULT NULL,
  `last_sort` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
--  Table structure for `ad_sort_record`
-- ----------------------------
DROP TABLE IF EXISTS `ad_sort_record`;
CREATE TABLE `ad_sort_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country` char(2) COLLATE utf8_bin DEFAULT NULL,
  `camp_id` int(11) NOT NULL DEFAULT '0',
  `app_id` int(11) NOT NULL DEFAULT '0',
  `position` tinyint(4) DEFAULT NULL COMMENT '位置 0表示首页 1表示游戏 2表示应用 3表示banner',
  `sort_id` int(11) DEFAULT NULL,
  `current_sort` int(11) DEFAULT NULL,
  `next_sort` int(11) DEFAULT NULL,
  `sort_method` tinyint(2) DEFAULT NULL COMMENT '修改排序方式：0即时生效、\n预排整点生效：11、兑换位置、12自动排序、3、\n预排',
  `update_method` tinyint(2) DEFAULT NULL COMMENT '生效方式：0下时段自动生效、1即时生效、2、预定时间生效',
  `is_updated` tinyint(2) NOT NULL DEFAULT '0' COMMENT '是否已经更新：0未更新，1已经更新完成，2更新异常',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '记录生成时间',
  `start_time` timestamp NULL DEFAULT NULL COMMENT '生效时间',
  `end_time` timestamp NULL DEFAULT NULL,
  `is_ad` tinyint(2) DEFAULT '0',
  `source` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='广告排序操作记录表';

-- ----------------------------
--  Table structure for `ad_sort_two`
-- ----------------------------
DROP TABLE IF EXISTS `ad_sort_two`;
CREATE TABLE `ad_sort_two` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `camp_id` int(11) NOT NULL,
  `app_id` int(11) NOT NULL DEFAULT '0',
  `country` char(2) COLLATE utf8_bin DEFAULT NULL,
  `position` tinyint(4) DEFAULT NULL COMMENT '位置 0表示首页 1表示游戏 2表示应用 3表示banner',
  `source` tinyint(4) DEFAULT NULL,
  `current_sort` int(11) DEFAULT NULL,
  `is_ad` tinyint(2) DEFAULT NULL,
  `next_sort` int(11) DEFAULT NULL,
  `last_sort` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


-- ----------------------------
--  Table structure for `task_status`
-- ----------------------------
DROP TABLE IF EXISTS `task_status`;
CREATE TABLE `task_status` (
  `name` varchar(50) COLLATE utf8_bin NOT NULL,
  `value` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='定时任务状态记录表';

SET FOREIGN_KEY_CHECKS = 1;
