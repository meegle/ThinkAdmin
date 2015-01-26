/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50611
Source Host           : localhost:3306
Source Database       : mysite

Target Server Type    : MYSQL
Target Server Version : 50611
File Encoding         : 65001

Date: 2015-01-09 11:31:30
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for ta_admin
-- ----------------------------
DROP TABLE IF EXISTS `ta_admin`;
CREATE TABLE `ta_admin` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL COMMENT '用户名',
  `password` varchar(50) DEFAULT NULL COMMENT '密码',
  `usertype` int(1) DEFAULT NULL COMMENT '用户类型 1-总管理员 2-代理商',
  `group_id` smallint(6) DEFAULT NULL COMMENT '分组ID',
  `status` tinyint(4) NOT NULL COMMENT '用户状态：0锁定，1正常',
  `last_login_ip` varchar(16) NOT NULL COMMENT '最后登录IP',
  `last_login_time` int(11) NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ta_admin
-- ----------------------------
INSERT INTO `ta_admin` VALUES ('1', 'admin', 'e10adc3949ba59abbe56e057f20f883e', '1', '1', '1', '127.0.0.1', '1420711701', '1417051295');
