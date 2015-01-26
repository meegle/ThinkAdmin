/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50611
Source Host           : localhost:3306
Source Database       : mysite

Target Server Type    : MYSQL
Target Server Version : 50611
File Encoding         : 65001

Date: 2015-01-09 11:31:35
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for ta_file
-- ----------------------------
DROP TABLE IF EXISTS `ta_file`;
CREATE TABLE `ta_file` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `filegroup` varchar(20) NOT NULL COMMENT '文件所属',
  `dataid` int(11) NOT NULL DEFAULT '0' COMMENT '对应所属的数据ID',
  `filename` varchar(255) NOT NULL COMMENT '文件名称',
  `filesize` double NOT NULL DEFAULT '0' COMMENT '文件大小',
  `fileext` varchar(20) NOT NULL COMMENT '文件类型',
  `filepath` varchar(255) NOT NULL COMMENT '文件路径',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态：0锁定，1正常',
  `displayorder` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `extend` text NOT NULL COMMENT '扩展内容',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ta_file
-- ----------------------------
