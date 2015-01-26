/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50611
Source Host           : localhost:3306
Source Database       : mysite

Target Server Type    : MYSQL
Target Server Version : 50611
File Encoding         : 65001

Date: 2015-01-09 11:31:26
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for ta_ad
-- ----------------------------
DROP TABLE IF EXISTS `ta_ad`;
CREATE TABLE `ta_ad` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ad_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '广告类型：1内容广告，2多图广告',
  `ad_title` varchar(255) NOT NULL COMMENT '标题',
  `ad_content` text NOT NULL COMMENT '广告内容',
  `start_time` int(11) NOT NULL DEFAULT '0' COMMENT '开始时间',
  `end_time` int(11) NOT NULL DEFAULT '0' COMMENT '结束时间',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ta_ad
-- ----------------------------
INSERT INTO `ta_ad` VALUES ('1', '2', '首页焦点图', '', '0', '0', '1420772445');
