/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50611
Source Host           : localhost:3306
Source Database       : mysite

Target Server Type    : MYSQL
Target Server Version : 50611
File Encoding         : 65001

Date: 2015-01-09 11:31:39
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for ta_group
-- ----------------------------
DROP TABLE IF EXISTS `ta_group`;
CREATE TABLE `ta_group` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL COMMENT '名称',
  `status` tinyint(1) DEFAULT NULL COMMENT '状态',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `displayorder` int(3) NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ta_group
-- ----------------------------
INSERT INTO `ta_group` VALUES ('1', '超级管理员', '1', '拥有网站最高管理员权限！', '1417051295', '0', '0');
